@props(['value'])

<label {{ $attributes->merge(['class' => 'block font-semibold text-sm text-black dark:text-gray-100 mb-1.5']) }}>
    {{ $value ?? $slot }}
</label>
