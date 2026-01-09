@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'input-modern disabled:opacity-50 disabled:cursor-not-allowed']) }}>
