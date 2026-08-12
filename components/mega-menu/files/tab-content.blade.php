@props([])

<div {{ $attributes->merge(['class' => 'flex flex-col gap-3']) }}>
    {{ $slot }}
</div>