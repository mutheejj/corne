@props(['status'])

@php
    $colors = [
        'draft' => 'slate',
        'scheduled' => 'blue',
        'active' => 'green',
        'paused' => 'yellow',
        'completed' => 'navy',
        'cancelled' => 'red',
        'pending' => 'yellow',
        'approved' => 'green',
        'rejected' => 'red',
        'disqualified' => 'red',
    ];
    $color = $colors[$status] ?? 'slate';
@endphp

<span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-{{ $color }}-100 text-{{ $color }}-700">
    <span class="w-1.5 h-1.5 rounded-full bg-{{ $color }}-500"></span>
    {{ ucfirst($status) }}
</span>
