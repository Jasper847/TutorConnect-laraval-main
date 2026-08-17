@props([
    'status' => 'pending',
])

@php
    $statusMap = [
        'pending'   => ['bg' => 'bg-amber-100',  'text' => 'text-amber-800',  'border' => 'border-amber-200', 'label' => 'Pending'],
        'confirmed' => ['bg' => 'bg-blue-100',   'text' => 'text-blue-800',   'border' => 'border-blue-200',  'label' => 'Confirmed'],
        'completed' => ['bg' => 'bg-emerald-100','text' => 'text-emerald-800','border' => 'border-emerald-200', 'label' => 'Completed'],
        'cancelled' => ['bg' => 'bg-rose-100',   'text' => 'text-rose-800',   'border' => 'border-rose-200',  'label' => 'Cancelled'],
    ];

    $badge = $statusMap[strtolower($status)] ?? $statusMap['pending'];
@endphp

<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-semibold border {{ $badge['bg'] }} {{ $badge['text'] }} {{ $badge['border'] }}">
    {{ $badge['label'] }}
</span>
