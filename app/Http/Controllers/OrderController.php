<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\ItemVariant;
use App\Models\Order;
use App\Services\WebPushService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\Rule;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        if (!auth()->check()) {
            return response()->json(['message' => 'يجب تسجيل الدخول أولاً.'], 401);
        }

        $data = $request->validate([
            'customer.name' => ['required', 'string', 'max:255'],
            'customer.phone' => ['required', 'string', 'max:40'],
            'customer.city' => ['required', 'string', 'max:255'],
            'customer.address' => ['required', 'string', 'max:500'],
            'customer.notes' => ['nullable', 'string', 'max:1000'],
            'payment_method' => ['required', Rule::in(array_keys(Order::PAYMENT_METHODS))],
            'payment_reference' => ['nullable', 'string', 'max:255', Rule::requiredIf(\in_array($request->input('payment_method'), ['vodafone_cash', 'instapay'], true))],
            'items' => ['required', 'array', 'min:1'],
            'items.*.id' => ['required', 'integer', 'exists:items,id'],
            'items.*.qty' => ['required', 'integer', 'min:1'],
            'items.*.size' => ['nullable', 'string', 'max:255'],
            'items.*.color' => ['nullable', 'string', 'max:255'],
        ]);

        $order = DB::transaction(function () use ($data) {
            $ids = collect($data['items'])->pluck('id')->unique()->values();
            $products = Item::with('category')
                ->whereIn('id', $ids)
                ->where('active', true)
                ->lockForUpdate()
                ->get()
                ->keyBy('id');

            $total = 0;
            $lines = [];

            foreach ($data['items'] as $cartItem) {
                $product = $products->get($cartItem['id']);

                if (!$product) {
                    abort(422, 'أحد المنتجات لم يعد متاحاً.');
                }

                $variant = ItemVariant::where('item_id', $product->id)
                    ->where('color', $cartItem['color'] ?? null)
                    ->where('size', $cartItem['size'] ?? null)
                    ->lockForUpdate()
                    ->first();

                if (!$variant || $cartItem['qty'] > $variant->stock) {
                    abort(422, 'الكمية المطلوبة غير متاحة لهذه التركيبة من المنتج: ' . $product->name);
                }

                $subtotal = (float) $product->price * (int) $cartItem['qty'];
                $total += $subtotal;

                $lines[] = [
                    'item_id' => $product->id,
                    'product_name' => $product->name,
                    'product_type' => $product->type,
                    'category_name' => optional($product->category)->name,
                    'image' => $product->image,
                    'size' => $cartItem['size'] ?? null,
                    'color' => $cartItem['color'] ?? null,
                    'unit_price' => $product->price,
                    'quantity' => $cartItem['qty'],
                    'subtotal' => $subtotal,
                ];

                $variant->decrement('stock', $cartItem['qty']);
                $product->decrement('stock', $cartItem['qty']);
            }

            $order = Order::create([
                'customer_id'  => auth()->id(),
                'order_number' => 'SEVA-' . now()->format('YmdHis') . '-' . random_int(100, 999),
                'customer_name' => $data['customer']['name'],
                'customer_phone' => $data['customer']['phone'],
                'customer_city' => $data['customer']['city'],
                'customer_address' => $data['customer']['address'],
                'customer_notes' => $data['customer']['notes'] ?? null,
                'payment_method' => $data['payment_method'],
                'payment_reference' => $data['payment_reference'] ?? null,
                'status' => $data['payment_method'] === 'cash_on_delivery' ? 'new' : 'pending_payment',
                'total' => $total,
            ]);

            $order->items()->createMany($lines);
            $order->statusLogs()->create(['status' => $order->status, 'created_at' => now()]);

            return $order;
        });

        $order->load('items');
        $this->notifyAdmin($order);

        // Web Push to all admin browsers (even closed)
        try {
            (new WebPushService())->notifyAllAdmins();
        } catch (\Throwable $e) {
            // non-fatal
        }

        return response()->json([
            'message'   => 'تم إرسال طلبك بنجاح.',
            'order_number' => $order->order_number,
            'track_url' => route('orders.track', $order->order_number),
        ]);
    }

    public function track(string $orderNumber)
    {
        $order = Order::with(['items', 'statusLogs'])
            ->where('order_number', $orderNumber)
            ->firstOrFail();

        $whatsappNumber = config('services.seva_whatsapp');

        return view('track', compact('order', 'whatsappNumber'));
    }

    private function notifyAdmin(Order $order): void
    {
        $phone  = config('services.callmebot.phone');
        $apikey = config('services.callmebot.apikey');

        if (!$phone || !$apikey) {
            return;
        }

        $items = $order->items->map(function ($i) {
            $variant = trim(implode(' / ', array_filter([$i->color, $i->size])));
            return "• {$i->product_name}" . ($variant ? " ({$variant})" : '') . " × {$i->quantity} = {$i->subtotal} ج.م";
        })->join("\n");

        $paymentLabel = Order::PAYMENT_METHODS[$order->payment_method] ?? $order->payment_method;
        $ref = $order->payment_reference ? "\nمرجع الدفع: {$order->payment_reference}" : '';

        $message = implode("\n", [
            '🛍️ طلب جديد على SEVA',
            '',
            "رقم الطلب: {$order->order_number}",
            "العميل: {$order->customer_name}",
            "الهاتف: {$order->customer_phone}",
            "المدينة: {$order->customer_city}",
            '',
            'المنتجات:',
            $items,
            '',
            "الإجمالي: {$order->total} ج.م",
            "الدفع: {$paymentLabel}{$ref}",
        ]);

        try {
            Http::timeout(5)->get('https://api.callmebot.com/whatsapp.php', [
                'phone'  => $phone,
                'text'   => $message,
                'apikey' => $apikey,
            ]);
        } catch (\Throwable $e) {
            // الإشعار اختياري - فشله لا يؤثر على الطلب
        }
    }
}
