@props([
    'type' => 'success', // success, error, info, warning
    'message' => '',
])

@php
    $typeMap = [
        'success' => [
            'bg' => 'bg-emerald-50',
            'border' => 'border-emerald-200',
            'text' => 'text-emerald-800',
            'icon' => 'fa-solid fa-circle-check text-emerald-600',
        ],
        'error' => [
            'bg' => 'bg-rose-50',
            'border' => 'border-rose-200',
            'text' => 'text-rose-800',
            'icon' => 'fa-solid fa-circle-exclamation text-rose-600',
        ],
        'info' => [
            'bg' => 'bg-blue-50',
            'border' => 'border-blue-200',
            'text' => 'text-blue-800',
            'icon' => 'fa-solid fa-circle-info text-blue-600',
        ],
        'warning' => [
            'bg' => 'bg-amber-50',
            'border' => 'border-amber-200',
            'text' => 'text-amber-800',
            'icon' => 'fa-solid fa-triangle-exclamation text-amber-600',
        ],
    ];

    $style = $typeMap[$type] ?? $typeMap['info'];
@endphp

<div class="mb-4 {{ $style['bg'] }} border {{ $style['border'] }} {{ $style['text'] }} px-5 py-4 rounded-xl flex items-center gap-3 shadow-sm" role="alert">
    <i class="{{ $style['icon'] }} text-lg"></i>
    <span class="text-xs sm:text-sm font-medium flex-1">{{ $message ?: $slot }}</span>
</div>
