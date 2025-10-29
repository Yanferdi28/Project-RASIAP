@props([
    'active' => false,
    'activeChildItems' => false,
    'activeIcon' => null,
    'badge' => null,
    'badgeColor' => null,
    'badgeTooltip' => null,
    'childItems' => [],
    'first' => false,
    'grouped' => false,
    'icon' => null,
    'last' => false,
    'shouldOpenUrlInNewTab' => false,
    'sidebarCollapsible' => true,
    'subGrouped' => false,
    'url',
])

@php
    use Illuminate\Support\Str;

    $sidebarCollapsible = $sidebarCollapsible && filament()->isSidebarCollapsibleOnDesktop();

    /**
     * Fallback ikon untuk item di dalam group (subGrouped) saat $icon null.
     * Kita tebak dari URL atau label agar ikon tetap muncul.
     * Tambahkan mapping lain kalau perlu.
     */
    if ($subGrouped && blank($icon)) {
        $labelStr = trim($slot);
        $urlStr = is_string($url) ? $url : '';

        $icon = match (true) {
            Str::contains($urlStr, '/naskah-masuks') || Str::contains(Str::lower($labelStr), 'naskah masuk')
                => 'heroicon-o-document-arrow-down',

            Str::contains($urlStr, '/arsip-aktifs') || Str::contains(Str::lower($labelStr), 'aktif')
                => 'heroicon-o-archive-box',

            Str::contains($urlStr, '/arsip-inaktifs') || Str::contains(Str::lower($labelStr), 'inaktif')
                => 'heroicon-o-archive-box-arrow-down',

            Str::contains($urlStr, '/arsip-units') || Str::contains(Str::lower($labelStr), 'arsip unit')
                => 'heroicon-o-briefcase',

            Str::contains($urlStr, '/kategoris') || Str::contains(Str::lower($labelStr), 'kategori')
                => 'heroicon-o-rectangle-stack',

            Str::contains($urlStr, '/kode-klasifikasis') || Str::contains(Str::lower($labelStr), 'kode klasifikasi')
                => 'heroicon-o-document-text',

            Str::contains($urlStr, '/unit-pengolahs') || Str::contains(Str::lower($labelStr), 'unit pengolah')
                => 'heroicon-o-building-office',

            Str::contains($urlStr, '/users') || Str::contains(Str::lower($labelStr), 'pengguna')
                => 'heroicon-o-user-group',

            default => null,
        };
    }
@endphp

<li
    {{
        $attributes->class([
            'fi-sidebar-item',
            'fi-active' => $active,
            'fi-sidebar-item-has-active-child-items' => $activeChildItems,
            'fi-sidebar-item-has-url' => filled($url),
        ])
    }}
>
    <a
        {{ \Filament\Support\generate_href_html($url, $shouldOpenUrlInNewTab) }}
        x-on:click="window.matchMedia(`(max-width: 1024px)`).matches && $store.sidebar.close()"
        @if ($sidebarCollapsible)
            x-data="{ tooltip: false }"
            x-effect="
                tooltip = $store.sidebar.isOpen
                    ? false
                    : {
                          content: @js($slot->toHtml()),
                          placement: document.dir === 'rtl' ? 'left' : 'right',
                          theme: $store.theme,
                      }
            "
            x-tooltip.html="tooltip"
        @endif
        class="fi-sidebar-item-btn"
    >
        {{-- Render ikon --}}
        @if (filled($icon))
            {{
                \Filament\Support\generate_icon_html(
                    ($active && $activeIcon) ? $activeIcon : $icon,
                    attributes: (new \Illuminate\View\ComponentAttributeBag())->class(['fi-sidebar-item-icon']),
                    size: \Filament\Support\Enums\IconSize::Large
                )
            }}
        @endif

        {{-- Hanya tampilkan bullet/garis jika TIDAK ada ikon --}}
        @if (blank($icon) && ($grouped || $subGrouped))
            <div class="fi-sidebar-item-grouped-border">
                @if (! $first)
                    <div class="fi-sidebar-item-grouped-border-part-not-first"></div>
                @endif

                @if (! $last)
                    <div class="fi-sidebar-item-grouped-border-part-not-last"></div>
                @endif

                <div class="fi-sidebar-item-grouped-border-part"></div>
            </div>
        @endif

        <span
            @if ($sidebarCollapsible)
                x-show="$store.sidebar.isOpen"
                x-transition:enter="fi-transition-enter"
                x-transition:enter-start="fi-transition-enter-start"
                x-transition:enter-end="fi-transition-enter-end"
            @endif
            class="fi-sidebar-item-label"
        >
            {{ $slot }}
        </span>

        @if (filled($badge))
            <span
                @if ($sidebarCollapsible)
                    x-show="$store.sidebar.isOpen"
                    x-transition:enter="fi-transition-enter"
                    x-transition:enter-start="fi-transition-enter-start"
                    x-transition:enter-end="fi-transition-enter-end"
                @endif
                class="fi-sidebar-item-badge-ctn"
            >
                <x-filament::badge
                    :color="$badgeColor"
                    :tooltip="$badgeTooltip"
                >
                    {{ $badge }}
                </x-filament::badge>
            </span>
        @endif
    </a>

    @if (($active || $activeChildItems) && $childItems)
        <ul class="fi-sidebar-sub-group-items">
            @foreach ($childItems as $childItem)
                @php
                    $isChildActive = $childItem->isActive();
                    $isChildItemChildItemsActive = $childItem->isChildItemsActive();
                    $childItemActiveIcon = $childItem->getActiveIcon();
                    $childItemBadge = $childItem->getBadge();
                    $childItemBadgeColor = $childItem->getBadgeColor();
                    $childItemBadgeTooltip = $childItem->getBadgeTooltip();
                    $childItemIcon = $childItem->getIcon();
                    $shouldChildItemOpenUrlInNewTab = $childItem->shouldOpenUrlInNewTab();
                    $childItemUrl = $childItem->getUrl();
                @endphp

                <x-filament-panels::sidebar.item
                    :active="$isChildActive"
                    :active-child-items="$isChildItemChildItemsActive"
                    :active-icon="$childItemActiveIcon"
                    :badge="$childItemBadge"
                    :badge-color="$childItemBadgeColor"
                    :badge-tooltip="$childItemBadgeTooltip"
                    :first="$loop->first"
                    grouped
                    :icon="$childItemIcon"
                    :last="$loop->last"
                    :should-open-url-in-new-tab="$shouldChildItemOpenUrlInNewTab"
                    sub-grouped
                    :url="$childItemUrl"
                >
                    {{ $childItem->getLabel() }}
                </x-filament-panels::sidebar.item>
            @endforeach
        </ul>
    @endif
</li>
