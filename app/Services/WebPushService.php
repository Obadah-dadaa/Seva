<?php

namespace App\Services;

use App\Models\PushSubscription;
use Illuminate\Support\Facades\Log;

class WebPushService
{
    private string $publicKey;
    private string $privateKeyPem;
    private string $subject;

    public function __construct()
    {
        $this->publicKey    = env('VAPID_PUBLIC_KEY', '');
        $this->privateKeyPem = base64_decode(env('VAPID_PRIVATE_KEY', ''));
        $this->subject      = env('VAPID_SUBJECT', 'mailto:admin@example.com');
    }

    public function isConfigured(): bool
    {
        return !empty($this->publicKey) && !empty($this->privateKeyPem);
    }

    public function notifyAllAdmins(): void
    {
        if (!$this->isConfigured()) return;

        $subscriptions = PushSubscription::whereHas('user', fn($q) => $q->where('is_admin', true))->get();
        foreach ($subscriptions as $sub) {
            $this->sendPing($sub);
        }
    }

    public function notifyUser(int $userId, string $title, string $body, string $url = '/'): void
    {
        if (!$this->isConfigured()) return;

        $subscriptions = PushSubscription::where('user_id', $userId)->get();
        foreach ($subscriptions as $sub) {
            $this->sendPing($sub);
        }
    }

    private function sendPing(PushSubscription $sub): void
    {
        try {
            $jwt = $this->createVapidJWT($sub->endpoint);

            $ch = curl_init($sub->endpoint);
            curl_setopt_array($ch, [
                CURLOPT_POST           => true,
                CURLOPT_POSTFIELDS     => '',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_TIMEOUT        => 10,
                CURLOPT_HTTPHEADER     => [
                    'Authorization: vapid t=' . $jwt . ',k=' . $this->publicKey,
                    'Content-Length: 0',
                    'Content-Type: application/octet-stream',
                    'TTL: 86400',
                ],
            ]);

            $httpCode = 0;
            curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            // 404/410 means subscription is gone — clean it up
            if ($httpCode === 404 || $httpCode === 410) {
                $sub->delete();
            }
        } catch (\Throwable $e) {
            Log::warning('WebPush failed: ' . $e->getMessage());
        }
    }

    private function createVapidJWT(string $endpoint): string
    {
        $url      = parse_url($endpoint);
        $audience = $url['scheme'] . '://' . $url['host'];

        $header  = $this->b64u(json_encode(['typ' => 'JWT', 'alg' => 'ES256']));
        $payload = $this->b64u(json_encode([
            'aud' => $audience,
            'exp' => time() + 43200,
            'sub' => $this->subject,
        ]));

        $signingInput = $header . '.' . $payload;

        $key = openssl_pkey_get_private($this->privateKeyPem);
        if (!$key) {
            throw new \RuntimeException('Invalid VAPID private key PEM');
        }

        openssl_sign($signingInput, $derSig, $key, OPENSSL_ALGO_SHA256);

        return $signingInput . '.' . $this->derToJoseSignature($derSig);
    }

    // Convert OpenSSL DER-encoded ECDSA signature to JOSE (r||s, 64 bytes, base64url)
    private function derToJoseSignature(string $der): string
    {
        $offset = 2; // skip SEQUENCE (0x30) + total length byte

        // Read r
        $offset++;   // skip INTEGER tag (0x02)
        $rLen = ord($der[$offset++]);
        $r    = substr($der, $offset, $rLen);
        $offset += $rLen;

        // Read s
        $offset++;   // skip INTEGER tag (0x02)
        $sLen = ord($der[$offset++]);
        $s    = substr($der, $offset, $sLen);

        // Strip leading 0x00 padding, then left-pad to 32 bytes
        $r = str_pad(ltrim($r, "\x00"), 32, "\x00", STR_PAD_LEFT);
        $s = str_pad(ltrim($s, "\x00"), 32, "\x00", STR_PAD_LEFT);

        return $this->b64u($r . $s);
    }

    private function b64u(string $data): string
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }
}
