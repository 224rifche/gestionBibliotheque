@props(['type' => 'success', 'message'])

@php
    $styles = [
        'success' => [
            'bg' => 'bg-emerald-50 border-emerald-200',
            'text' => 'text-emerald-800',
            'icon' => 'text-emerald-600',
            'iconSvg' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />'
        ],
        'error' => [
            'bg' => 'bg-red-50 border-red-200',
            'text' => 'text-red-800',
            'icon' => 'text-red-600',
            'iconSvg' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />'
        ],
        'warning' => [
            'bg' => 'bg-amber-50 border-amber-200',
            'text' => 'text-amber-800',
            'icon' => 'text-amber-600',
            'iconSvg' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />'
        ],
        'info' => [
            'bg' => 'bg-blue-50 border-blue-200',
            'text' => 'text-blue-800',
            'icon' => 'text-blue-600',
            'iconSvg' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />'
        ],
    ];
    $style = $styles[$type] ?? $styles['success'];
@endphp

<div x-data="{ show: true }" 
     x-show="show"
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0 transform translate-y-2"
     x-transition:enter-end="opacity-100 transform translate-y-0"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     class="mb-4 {{ $style['bg'] }} border-l-4 {{ $style['bg'] }} rounded-lg p-4 shadow-sm animate-slide-up">
    <div class="flex items-start">
        <div class="flex-shrink-0">
            <svg class="h-5 w-5 {{ $style['icon'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                {!! $style['iconSvg'] !!}
            </svg>
        </div>
        <div class="ml-3 flex-1">
            <p class="text-sm font-semibold {{ $style['text'] }}">
                {{ $message ?? $slot }}
            </p>
        </div>
        <div class="ml-auto pl-3">
            <button @click="show = false" class="inline-flex {{ $style['text'] }} hover:opacity-75 focus:outline-none transition-opacity duration-200">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    </div>
</div>
