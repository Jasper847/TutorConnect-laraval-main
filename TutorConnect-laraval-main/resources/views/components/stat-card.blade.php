@props([
    'icon' => 'fa-solid fa-chart-simple',
    'value' => '0',
    'label' => 'Stat Label',
    'color' => 'primary', // primary, emerald, amber, purple
    'subtext' => null,
])

@php
    $colorMap = [
        'primary' => ['bg' => 'bg-primary-50', 'text' => 'text-primary-800'],
        'emerald' => ['bg' => 'bg-emerald-50', 'text' => 'text-emerald-600'],
        'amber'   => ['bg' => 'bg-amber-50',   'text' => 'text-amber-600'],
        'purple'  => ['bg' => 'bg-purple-50',  'text' => 'text-purple-600'],
        'blue'    => ['bg' => 'bg-blue-50',    'text' => 'text-blue-600'],
    ];
    $theme = $colorMap[$color] ?? $colorMap['primary'];
@endphp

<div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm flex items-center gap-4 hover:shadow-md transition-shadow">
    <div class="w-12 h-12 rounded-xl {{ $theme['bg'] }} {{ $theme['text'] }} flex items-center justify-center text-xl shrink-0">
        <i class="{{ $icon }}"></i>
    </div>
    <div class="min-w-0 flex-1">
        <p class="text-xs font-semibold uppercase tracking-wider text-slate-400 truncate">{{ $label }}</p>
        <h3 class="text-2xl font-bold font-heading text-slate-900 mt-0.5">{{ $value }}</h3>
        @if($subtext)
            <p class="text-[11px] text-slate-400 mt-0.5 truncate">{{ $subtext }}</p>
        @endif
    </div>
</div>
