@php
    $navItems = [
        [
            'label' => 'Dashboard',
            'route' => 'admin.dashboard',
            'icon' => 'layout-dashboard',
            'active' => request()->routeIs('admin.dashboard'),
        ],
        [
            'label' => 'Elections',
            'route' => 'admin.elections.index',
            'icon' => 'vote',
            'active' => request()->routeIs('admin.elections*'),
        ],
        [
            'label' => 'Candidates',
            'route' => 'admin.candidates.index',
            'icon' => 'users',
            'active' => request()->routeIs('admin.candidates*'),
        ],
        [
            'label' => 'Voters',
            'route' => 'admin.voters.index',
            'icon' => 'user-check',
            'active' => request()->routeIs('admin.voters*'),
        ],
        [
            'label' => 'Audit Logs',
            'route' => 'admin.audit-logs.index',
            'icon' => 'scroll-text',
            'active' => request()->routeIs('admin.audit-logs*'),
        ],
        [
            'label' => 'Security',
            'route' => 'admin.security-report',
            'icon' => 'shield-alert',
            'active' => request()->routeIs('admin.security-report'),
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
