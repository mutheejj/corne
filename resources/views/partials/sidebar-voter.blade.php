@php
    $navItems = [
        [
            'label' => 'Dashboard',
            'route' => 'voter.dashboard',
            'icon' => 'layout-dashboard',
            'active' => request()->routeIs('voter.dashboard'),
        ],
        [
            'label' => 'Elections',
            'route' => 'voter.elections.index',
            'icon' => 'vote',
            'active' => request()->routeIs('voter.elections*'),
        ],
        [
            'label' => 'Vote History',
            'route' => 'voter.vote-history',
            'icon' => 'history',
            'active' => request()->routeIs('voter.vote-history'),
        ],
        [
            'label' => 'Verify Vote',
            'route' => 'voter.verify-vote',
            'icon' => 'shield-check',
            'active' => request()->routeIs('voter.verify-vote*'),
        ],
        [
            'label' => 'Profile',
            'route' => 'voter.profile',
            'icon' => 'user',
            'active' => request()->routeIs('voter.profile'),
        ],
    ];
@endphp

@foreach ($navItems as $item)
    <a href="{{ route($item['route']) }}"
       class="sidebar-link group flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-all duration-300 {{ $item['active'] ? 'bg-orange-500 text-white shadow-lg shadow-orange-500/20' : 'text-navy-300 hover:bg-navy-800 hover:text-white' }}">
        <span class="shrink-0 {{ $item['active'] ? 'text-white' : 'text-navy-400 group-hover:text-orange-400' }} transition-colors duration-300">
            <i data-lucide="{{ $item['icon'] }}" class="w-5 h-5"></i>
        </span>
        <span>{{ $item['label'] }}</span>
        @if ($item['active'])
            <span class="ml-auto w-1.5 h-1.5 rounded-full bg-white animate-pulse"></span>
        @endif
    </a>
@endforeach
