@php
    $navItems = [
        [
            'label' => 'Dashboard',
            'route' => 'candidate.dashboard',
            'icon' => 'layout-dashboard',
            'active' => request()->routeIs('candidate.dashboard'),
        ],
        [
            'label' => 'Campaign Profile',
            'route' => 'candidate.profile',
            'icon' => 'user',
            'active' => request()->routeIs('candidate.profile*'),
        ],
        [
            'label' => 'My Election',
            'route' => 'candidate.election',
            'icon' => 'vote',
            'active' => request()->routeIs('candidate.election'),
        ],
        [
            'label' => 'My Position',
            'route' => 'candidate.position',
            'icon' => 'target',
            'active' => request()->routeIs('candidate.position'),
        ],
        [
            'label' => 'Results',
            'route' => 'candidate.results',
            'icon' => 'bar-chart-3',
            'active' => request()->routeIs('candidate.results'),
        ],
    ];
@endphp

@foreach ($navItems as $item)
    <a href="{{ route($item['route']) }}"
       class="sidebar-link group flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-all duration-300 {{ $item['active'] ? 'bg-orange-500 text-white shadow-lg shadow-orange-500/20' : 'text-slate-600 hover:bg-slate-100 hover:text-orange-600' }}">
        <span class="shrink-0 {{ $item['active'] ? 'text-white' : 'text-slate-400 group-hover:text-orange-500' }} transition-colors duration-300">
            <i data-lucide="{{ $item['icon'] }}" class="w-5 h-5"></i>
        </span>
        <span>{{ $item['label'] }}</span>
        @if ($item['active'])
            <span class="ml-auto w-1.5 h-1.5 rounded-full bg-white animate-pulse"></span>
        @endif
    </a>
@endforeach
