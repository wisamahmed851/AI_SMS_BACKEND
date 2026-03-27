{{-- Stat Card Component --}}
@props(['title', 'value', 'icon', 'color' => 'indigo', 'subtitle' => ''])

@php
    $gradients = [
        'indigo' => 'from-indigo-500 to-indigo-600',
        'emerald' => 'from-emerald-500 to-emerald-600',
        'amber' => 'from-amber-500 to-amber-600',
        'rose' => 'from-rose-500 to-rose-600',
        'blue' => 'from-blue-500 to-blue-600',
        'purple' => 'from-purple-500 to-purple-600',
        'teal' => 'from-teal-500 to-teal-600',
        'cyan' => 'from-cyan-500 to-cyan-600',
    ];
    $bgLight = [
        'indigo' => 'bg-indigo-50',
        'emerald' => 'bg-emerald-50',
        'amber' => 'bg-amber-50',
        'rose' => 'bg-rose-50',
        'blue' => 'bg-blue-50',
        'purple' => 'bg-purple-50',
        'teal' => 'bg-teal-50',
        'cyan' => 'bg-cyan-50',
    ];
@endphp

<div class="stat-card group">
    <div class="flex items-start justify-between">
        <div class="flex-1">
            <p class="text-sm font-medium text-slate-500 mb-1">{{ $title }}</p>
            <p class="text-2xl font-bold text-slate-800">{{ $value }}</p>
            @if($subtitle)
                <p class="text-xs text-slate-400 mt-1">{{ $subtitle }}</p>
            @endif
        </div>
        <div class="w-12 h-12 bg-gradient-to-br {{ $gradients[$color] ?? $gradients['indigo'] }} rounded-xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-300">
            {!! $icon !!}
        </div>
    </div>
</div>
