@props(['href' => null, 'active' => false, 'icon' => null])

@php
$classes = $active ?? false
            ? 'flex items-center gap-4 px-4 py-3 text-sm font-medium rounded-lg bg-white/20 text-white shadow-sm transition-all duration-200 relative'
            : 'flex items-center gap-4 px-4 py-3 text-sm font-medium text-white/80 hover:bg-white/10 hover:text-white rounded-lg transition-all duration-200';
@endphp

<a {{ $attributes->merge(['href' => $href, 'class' => $classes]) }}>
    @if($icon)
        <span class="flex-shrink-0 h-5 w-5 text-white">
            {!! $icon !!}
        </span>
    @endif
    <span class="truncate flex-1">{{ $slot }}</span>
    @if($active)
        <div class="absolute right-0 top-1/2 -translate-y-1/2 h-8 w-1 bg-white rounded-l-full"></div>
    @endif
</a>
