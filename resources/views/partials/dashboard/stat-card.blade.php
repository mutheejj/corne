@props(['label', 'value', 'icon', 'color' => 'orange', 'trend' => null])

<div class="bg-white rounded-xl p-6 shadow-sm border border-slate-100">
    <div class="flex items-center justify-between mb-4">
        <span class="text-slate-500 text-sm font-medium">{{ $label }}</span>
        <div class="w-10 h-10 rounded-lg flex items-center justify-center bg-{{ $color }}-50">
            {!! $icon !!}
        </div>
    </div>
    <p class="text-3xl font-extrabold text-navy-950">{{ $value }}</p>
    @if ($trend)
        <p class="text-{{ $color }}-600 text-sm mt-2">{{ $trend }}</p>
    @endif
</div>
