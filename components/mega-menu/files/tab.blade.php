@props([
    'icon' => null,
    'title',
    'variant' => 'default', // 'default' | 'accent' | 'minimal'
])

@php
    // Generated once per render so the trigger and its panel can reference
    // the same value without any shared Alpine index/counter between them.
    $tabId = (string) \Illuminate\Support\Str::uuid();

    $iconWrapClasses = match ($variant) {
        'accent' => 'bg-grad-accent-left text-white',
        'minimal' => 'bg-transparent text-neutral-500 dark:text-neutral-400',
        default => 'bg-neutral-100 dark:bg-neutral-800 text-neutral-900 dark:text-white',
    };
@endphp

{{-- Trigger — auto-placed into column 1 of the parent grid, one per row --}}
<button
    type="button"
    role="tab"
    id="mega-tab-{{ $tabId }}"
    aria-controls="mega-tab-panel-{{ $tabId }}"
    :aria-selected="activeTab === '{{ $tabId }}'"
    x-init="if (activeTab === null) activeTab = '{{ $tabId }}'"
    @mouseenter="activeTab = '{{ $tabId }}'"
    @click="activeTab = '{{ $tabId }}'"
    class="col-start-1 mb-1 flex items-center gap-3 p-3 rounded-xl text-left transition-colors duration-150 hover:bg-neutral-100 dark:hover:bg-neutral-800"
    :class="{ 'bg-neutral-100 dark:bg-neutral-800': activeTab === '{{ $tabId }}' }"
>
    @if ($icon)
        <span class="w-8 h-8 rounded-lg flex items-center justify-center shrink-0 {{ $iconWrapClasses }}">
            <x-dynamic-component :component="$icon" class="w-4 h-4" />
        </span>
    @endif

    <span class="font-lemonmilk text-xs text-neutral-900 dark:text-white">
        {{ $title }}
    </span>
</button>

{{-- Panel — pinned to column 2, spans well past however many triggers exist --}}
<div
    x-show="activeTab === '{{ $tabId }}'"
    x-transition:enter="transition ease-out duration-200"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-cloak
    role="tabpanel"
    id="mega-tab-panel-{{ $tabId }}"
    aria-labelledby="mega-tab-{{ $tabId }}"
    class="col-start-2 col-span-2 row-start-1 row-span-[99]"
>
    {{ $slot }}
</div>