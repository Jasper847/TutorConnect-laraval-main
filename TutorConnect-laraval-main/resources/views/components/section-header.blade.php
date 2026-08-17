@props([
    'title' => '',
    'subtitle' => null,
    'actionText' => null,
    'actionUrl' => null,
    'actionIcon' => null,
])

<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
    <div>
        <h2 class="text-xl sm:text-2xl font-bold font-heading text-slate-900 tracking-tight">{{ $title }}</h2>
        @if($subtitle)
            <p class="text-xs sm:text-sm text-slate-500 mt-1">{{ $subtitle }}</p>
        @endif
    </div>

    @if($actionText && $actionUrl)
        <div>
            <a href="{{ $actionUrl }}" class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold px-4 py-2.5 rounded-xl shadow-sm hover:shadow transition-all">
                @if($actionIcon)
                    <i class="{{ $actionIcon }} text-xs"></i>
                @endif
                <span>{{ $actionText }}</span>
            </a>
        </div>
    @endif
</div>
