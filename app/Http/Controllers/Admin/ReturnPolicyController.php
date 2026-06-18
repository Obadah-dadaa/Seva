<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ReturnPolicy;
use Illuminate\Http\Request;

class ReturnPolicyController extends Controller
{
    /**
     * Show the form for editing the return policy page.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {
        $policy = ReturnPolicy::current();

        return view('admin.return-policy.edit', compact('policy'));
    }

    /**
     * Update the return policy page content.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $data = $request->validate([
            'badge' => ['nullable', 'string', 'max:255'],
            'title' => ['required', 'string', 'max:255'],
            'subtitle' => ['nullable', 'string', 'max:255'],
            'note' => ['nullable', 'string'],
            'sections' => ['nullable', 'array'],
            'sections.*.icon' => ['nullable', 'string', 'max:16'],
            'sections.*.title' => ['nullable', 'string', 'max:255'],
            'sections.*.type' => ['nullable', 'in:paragraph,list'],
            'sections.*.body' => ['nullable', 'string'],
        ]);

        $sections = collect($data['sections'] ?? [])
            ->map(function ($section) {
                return [
                    'icon' => trim($section['icon'] ?? ''),
                    'title' => trim($section['title'] ?? ''),
                    'type' => ($section['type'] ?? 'paragraph') === 'list' ? 'list' : 'paragraph',
                    'body' => $section['body'] ?? '',
                ];
            })
            // Drop fully empty rows
            ->filter(fn ($s) => $s['title'] !== '' || trim($s['body']) !== '')
            ->values()
            ->all();

        $policy = ReturnPolicy::current();
        $policy->update([
            'badge' => $data['badge'] ?? null,
            'title' => $data['title'],
            'subtitle' => $data['subtitle'] ?? null,
            'note' => $data['note'] ?? null,
            'sections' => $sections,
        ]);

        return redirect()
            ->route('admin.return-policy.edit')
            ->with('success', 'تم حفظ صفحة سياسة الترجيع.');
    }
}
