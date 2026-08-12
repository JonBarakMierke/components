@props([
    'label',
    'width' => 'w-[100vw]',
    'variant' => 'default', // 'default' | 'tabs'
])

<div
    x-data="{
        openTimeout: null,
        closeTimeout: null,
        show() {
            clearTimeout(this.closeTimeout);
            clearTimeout(this.openTimeout);
            this.openTimeout = setTimeout(() => { activeMenu = '{{ $label }}' }, 100);
        },
        hide() {
            clearTimeout(this.openTimeout);
            clearTimeout(this.closeTimeout);
            this.closeTimeout = setTimeout(() => {
                if (activeMenu === '{{ $label }}') activeMenu = null;
            }, 150);
        }
    }"
    @mouseenter="show()"
    @mouseleave="hide()"
    class="relative"
>

    {{-- TRIGGER --}}
    <button class="nav-link flex items-center gap-1">
        {{ $label }}
        <svg class="w-3 h-3 opacity-70 transition-transform duration-200"
             :class="{ 'rotate-180': activeMenu === '{{ $label }}' }"
             fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M19 9l-7 7-7-7"/>
        </svg>
    </button>

    {{-- DROPDOWN --}}
    <template x-teleport="body">
        <div
            x-show="activeMenu === '{{ $label }}'"
            @mouseenter="show()"
            @mouseleave="hide()"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 translate-y-2"
            x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 translate-y-2"
            x-cloak
            class="fixed inset-x-0 top-[72px] flex justify-center z-50"
        >
            <div class="{{ $width }} p-6 rounded-3xl bg-white/90 dark:bg-neutral-900/95 backdrop-blur-xl border border-white/10 shadow-2xl">
                @if ($variant === 'tabs')
                    {{--
                        x-ui.mega-menu.tab renders a trigger + a panel as flat siblings (there's
                        no way to split one slot into two separately-grouped wrapper elements the
                        way sections/products.blade.php does with two @foreach loops over one PHP
                        array). Grid sidesteps that: triggers auto-place down column 1, one per
                        row, and every panel is explicitly pinned to column 2 starting at row 1 and
                        spanning far more rows than could ever exist (row-span-[99] on the panel
                        itself). So whichever panel is active always sits beside row 1 instead of
                        wherever its own trigger happened to land. Row gap is deliberately omitted
                        here — with a span that large, a row-gap would apply between every one of
                        those mostly-empty spanned rows and add a lot of unwanted height, so panel
                        spacing between triggers comes from margin (see tab.blade.php) instead.
                    --}}
                    <div
                        x-data="{ activeTab: null }"
                        role="tablist"
                        aria-orientation="vertical"
                        class="grid grid-cols-3 items-start gap-x-6 min-h-[260px]"
                    >
                        {{ $slot }}
                    </div>
                @else
                    {{ $slot }}
                @endif
            </div>
        </div>
    </template>

</div>