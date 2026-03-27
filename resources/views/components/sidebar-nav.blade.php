{{-- Sidebar Navigation Component --}}
@props(['items', 'currentRoute' => ''])

@foreach($items as $item)
    <a href="{{ $item['url'] }}" class="sidebar-link {{ request()->routeIs($item['route'] ?? '') ? 'active' : '' }}">
        {!! $item['icon'] !!}
        <span>{{ $item['label'] }}</span>
    </a>
@endforeach
