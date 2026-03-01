<x-filament-panels::page>

    {{-- ── Filter Section ─────────────────────────────────────────────────── --}}
    <x-filament::section icon="heroicon-o-funnel" icon-color="primary">
        <x-slot name="heading">Report Filters</x-slot>
        <x-slot name="description">Choose a date range and report type, then apply to refresh results.</x-slot>
        <x-slot name="headerEnd">
            <x-filament::badge
                :color="match($filters['report']) {
                    'sales'     => 'success',
                    'orders'    => 'info',
                    'products'  => 'warning',
                    'customers' => 'primary',
                    default     => 'gray',
                }"
                class="hidden sm:inline-flex"
            >
                {{ match($filters['report']) {
                    'sales'     => 'Sales Revenue',
                    'orders'    => 'Orders by Status',
                    'products'  => 'Top Products',
                    'customers' => 'New Customers',
                    default     => 'Report',
                } }}
            </x-filament::badge>
        </x-slot>

        <form wire:submit="applyFilters">
            {{ $this->filtersForm }}

            <div class="mt-6 flex flex-wrap items-center gap-3 pt-5 border-t border-gray-100 dark:border-white/10">
                <x-filament::button type="submit" icon="heroicon-m-funnel" size="sm">
                    Apply Filters
                </x-filament::button>

                <x-filament::button
                    tag="a"
                    href="{{ $this->getCsvUrl() }}"
                    target="_blank"
                    color="gray"
                    icon="heroicon-m-arrow-down-tray"
                    size="sm"
                >
                    Export CSV
                </x-filament::button>
            </div>
        </form>
    </x-filament::section>

    {{-- ── Sales Revenue ───────────────────────────────────────────────────── --}}
    @if($filters['report'] === 'sales')
        @php $data = $this->getSalesData(); $rows = $data['rows']; $totals = $data['totals']; @endphp

        {{-- KPI cards --}}
        <div class="mt-6 grid grid-cols-2 lg:grid-cols-4 gap-4">

            <x-filament::section compact icon="heroicon-o-banknotes" icon-color="success">
                <p class="text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400 mb-0.5">Total Revenue</p>
                <p class="text-2xl font-bold text-success-600 dark:text-success-400">৳{{ number_format($totals['revenue'], 0) }}</p>
            </x-filament::section>

            <x-filament::section compact icon="heroicon-o-shopping-bag" icon-color="primary">
                <p class="text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400 mb-0.5">Paid Orders</p>
                <p class="text-2xl font-bold text-primary-600 dark:text-primary-400">{{ number_format($totals['orders']) }}</p>
            </x-filament::section>

            <x-filament::section compact icon="heroicon-o-tag" icon-color="warning">
                <p class="text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400 mb-0.5">Discounts Given</p>
                <p class="text-2xl font-bold text-warning-600 dark:text-warning-400">৳{{ number_format($totals['discounts'], 0) }}</p>
            </x-filament::section>

            <x-filament::section compact icon="heroicon-o-calculator" icon-color="gray">
                <p class="text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400 mb-0.5">Avg. Order Value</p>
                <p class="text-2xl font-bold text-gray-800 dark:text-gray-100">
                    ৳{{ $totals['orders'] > 0 ? number_format($totals['revenue'] / $totals['orders'], 0) : 0 }}
                </p>
            </x-filament::section>

        </div>

        {{-- Daily Breakdown table --}}
        <x-filament::section class="mt-6" icon="heroicon-o-table-cells" icon-color="gray">
            <x-slot name="heading">Daily Breakdown</x-slot>
            <x-slot name="description">Revenue, orders and discounts grouped by day.</x-slot>

            @if($rows->isEmpty())
                <div class="flex flex-col items-center py-10 gap-2 text-gray-400 dark:text-gray-500">
                    <x-heroicon-o-inbox class="h-10 w-10 opacity-40" />
                    <p class="text-sm">No paid orders in this date range.</p>
                </div>
            @else
                <div class="-mx-6 -mb-6 overflow-x-auto">
                    <x-filament-tables::table>
                        <x-slot name="header">
                            <x-filament-tables::header-cell name="date">Date</x-filament-tables::header-cell>
                            <x-filament-tables::header-cell name="orders" alignment="end">Orders</x-filament-tables::header-cell>
                            <x-filament-tables::header-cell name="revenue" alignment="end">Revenue</x-filament-tables::header-cell>
                            <x-filament-tables::header-cell name="discounts" alignment="end">Discounts</x-filament-tables::header-cell>
                            <x-filament-tables::header-cell name="shipping" alignment="end">Shipping</x-filament-tables::header-cell>
                        </x-slot>

                        @foreach($rows as $i => $row)
                            <x-filament-tables::row :striped="$i % 2 !== 0">
                                <x-filament-tables::cell class="px-4 py-2.5 text-sm text-gray-700 dark:text-gray-300 sm:ps-6">
                                    {{ \Carbon\Carbon::parse($row->date)->format('d M Y') }}
                                </x-filament-tables::cell>
                                <x-filament-tables::cell class="px-4 py-2.5 text-sm text-right text-gray-600 dark:text-gray-400 sm:pe-6">
                                    {{ $row->orders }}
                                </x-filament-tables::cell>
                                <x-filament-tables::cell class="px-4 py-2.5 text-sm text-right font-semibold text-success-700 dark:text-success-400">
                                    ৳{{ number_format($row->revenue, 0) }}
                                </x-filament-tables::cell>
                                <x-filament-tables::cell class="px-4 py-2.5 text-sm text-right text-warning-600 dark:text-warning-400">
                                    ৳{{ number_format($row->discounts, 0) }}
                                </x-filament-tables::cell>
                                <x-filament-tables::cell class="px-4 py-2.5 text-sm text-right text-gray-500 dark:text-gray-400">
                                    ৳{{ number_format($row->shipping, 0) }}
                                </x-filament-tables::cell>
                            </x-filament-tables::row>
                        @endforeach

                        <x-slot name="footer">
                            <x-filament-tables::cell class="px-4 py-3 text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-200 sm:ps-6">
                                Total
                            </x-filament-tables::cell>
                            <x-filament-tables::cell class="px-4 py-3 text-sm text-right font-bold text-gray-700 dark:text-gray-200">
                                {{ $totals['orders'] }}
                            </x-filament-tables::cell>
                            <x-filament-tables::cell class="px-4 py-3 text-sm text-right font-bold text-success-700 dark:text-success-400">
                                ৳{{ number_format($totals['revenue'], 0) }}
                            </x-filament-tables::cell>
                            <x-filament-tables::cell class="px-4 py-3 text-sm text-right font-bold text-warning-600 dark:text-warning-400">
                                ৳{{ number_format($totals['discounts'], 0) }}
                            </x-filament-tables::cell>
                            <x-filament-tables::cell class="px-4 py-3 text-sm text-right font-bold text-gray-500 dark:text-gray-400">
                                ৳{{ number_format($totals['shipping'], 0) }}
                            </x-filament-tables::cell>
                        </x-slot>
                    </x-filament-tables::table>
                </div>
            @endif
        </x-filament::section>
    @endif

    {{-- ── Orders by Status ────────────────────────────────────────────────── --}}
    @if($filters['report'] === 'orders')
        @php $rows = $this->getOrdersData(); $total = collect($rows)->sum('count'); @endphp

        <x-filament::section class="mt-6" icon="heroicon-o-clipboard-document-list" icon-color="info">
            <x-slot name="heading">Orders by Status</x-slot>
            <x-slot name="description">
                Distribution of all {{ number_format($total) }} orders across statuses in the selected period.
            </x-slot>

            @if(empty($rows))
                <div class="flex flex-col items-center py-10 gap-2 text-gray-400 dark:text-gray-500">
                    <x-heroicon-o-inbox class="h-10 w-10 opacity-40" />
                    <p class="text-sm">No orders in this date range.</p>
                </div>
            @else
                @php
                    $statusColors = [
                        'pending'    => 'warning',
                        'processing' => 'info',
                        'shipped'    => 'primary',
                        'delivered'  => 'success',
                        'cancelled'  => 'danger',
                        'returned'   => 'gray',
                    ];
                    $maxCount = collect($rows)->max('count') ?: 1;
                @endphp
                <div class="-mx-6 -mb-6 overflow-x-auto">
                    <x-filament-tables::table>
                        <x-slot name="header">
                            <x-filament-tables::header-cell name="status">Status</x-filament-tables::header-cell>
                            <x-filament-tables::header-cell name="bar">Distribution</x-filament-tables::header-cell>
                            <x-filament-tables::header-cell name="count" alignment="end">Count</x-filament-tables::header-cell>
                            <x-filament-tables::header-cell name="pct" alignment="end">Share</x-filament-tables::header-cell>
                            <x-filament-tables::header-cell name="total" alignment="end">Total Amount</x-filament-tables::header-cell>
                        </x-slot>

                        @foreach($rows as $i => $row)
                            @php $pct = $total > 0 ? $row['count'] / $total * 100 : 0; @endphp
                            <x-filament-tables::row :striped="$i % 2 !== 0">
                                <x-filament-tables::cell class="px-4 py-2.5 sm:ps-6">
                                    <x-filament::badge :color="$statusColors[$row['status']] ?? 'gray'">
                                        {{ ucfirst($row['status']) }}
                                    </x-filament::badge>
                                </x-filament-tables::cell>
                                <x-filament-tables::cell class="px-4 py-2.5 w-40">
                                    <div class="h-2 w-full rounded-full bg-gray-100 dark:bg-white/10 overflow-hidden">
                                        <div
                                            class="h-2 rounded-full bg-primary-500 dark:bg-primary-400 transition-all"
                                            style="width: {{ number_format($pct, 1) }}%"
                                        ></div>
                                    </div>
                                </x-filament-tables::cell>
                                <x-filament-tables::cell class="px-4 py-2.5 text-sm text-right font-semibold text-gray-800 dark:text-gray-200">
                                    {{ number_format($row['count']) }}
                                </x-filament-tables::cell>
                                <x-filament-tables::cell class="px-4 py-2.5 text-sm text-right text-gray-500 dark:text-gray-400">
                                    {{ number_format($pct, 1) }}%
                                </x-filament-tables::cell>
                                <x-filament-tables::cell class="px-4 py-2.5 text-sm text-right font-semibold text-success-700 dark:text-success-400 sm:pe-6">
                                    ৳{{ number_format($row['total'], 0) }}
                                </x-filament-tables::cell>
                            </x-filament-tables::row>
                        @endforeach
                    </x-filament-tables::table>
                </div>
            @endif
        </x-filament::section>
    @endif

    {{-- ── Top Products ─────────────────────────────────────────────────────── --}}
    @if($filters['report'] === 'products')
        @php $rows = $this->getProductsData(); @endphp

        <x-filament::section class="mt-6" icon="heroicon-o-trophy" icon-color="warning">
            <x-slot name="heading">Top Products by Revenue</x-slot>
            <x-slot name="description">Best-selling products from paid orders in the selected date range.</x-slot>

            @if(empty($rows))
                <div class="flex flex-col items-center py-10 gap-2 text-gray-400 dark:text-gray-500">
                    <x-heroicon-o-inbox class="h-10 w-10 opacity-40" />
                    <p class="text-sm">No data in this date range.</p>
                </div>
            @else
                @php $maxRevenue = collect($rows)->max('total_revenue') ?: 1; @endphp
                <div class="-mx-6 -mb-6 overflow-x-auto">
                    <x-filament-tables::table>
                        <x-slot name="header">
                            <x-filament-tables::header-cell name="rank" class="w-10">#</x-filament-tables::header-cell>
                            <x-filament-tables::header-cell name="product">Product</x-filament-tables::header-cell>
                            <x-filament-tables::header-cell name="bar">Revenue Share</x-filament-tables::header-cell>
                            <x-filament-tables::header-cell name="qty" alignment="end">Units Sold</x-filament-tables::header-cell>
                            <x-filament-tables::header-cell name="revenue" alignment="end">Revenue</x-filament-tables::header-cell>
                        </x-slot>

                        @foreach($rows as $i => $row)
                            <x-filament-tables::row :striped="$i % 2 !== 0">
                                <x-filament-tables::cell class="px-4 py-2.5 sm:ps-6">
                                    @if($i === 0)
                                        <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-warning-100 dark:bg-warning-900/30 text-warning-700 dark:text-warning-400 text-xs font-bold">1</span>
                                    @elseif($i === 1)
                                        <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-300 text-xs font-bold">2</span>
                                    @elseif($i === 2)
                                        <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-orange-100 dark:bg-orange-900/30 text-orange-600 dark:text-orange-400 text-xs font-bold">3</span>
                                    @else
                                        <span class="text-xs text-gray-400 dark:text-gray-500 pl-1.5">{{ $i + 1 }}</span>
                                    @endif
                                </x-filament-tables::cell>
                                <x-filament-tables::cell class="px-4 py-2.5 text-sm font-medium text-gray-800 dark:text-gray-200 max-w-xs truncate">
                                    {{ $row['product_name'] }}
                                </x-filament-tables::cell>
                                <x-filament-tables::cell class="px-4 py-2.5 w-36">
                                    <div class="h-2 w-full rounded-full bg-gray-100 dark:bg-white/10 overflow-hidden">
                                        <div
                                            class="h-2 rounded-full bg-success-500 dark:bg-success-400 transition-all"
                                            style="width: {{ number_format($row['total_revenue'] / $maxRevenue * 100, 1) }}%"
                                        ></div>
                                    </div>
                                </x-filament-tables::cell>
                                <x-filament-tables::cell class="px-4 py-2.5 text-sm text-right text-gray-600 dark:text-gray-400">
                                    {{ number_format($row['total_qty']) }}
                                </x-filament-tables::cell>
                                <x-filament-tables::cell class="px-4 py-2.5 text-sm text-right font-bold text-success-700 dark:text-success-400 sm:pe-6">
                                    ৳{{ number_format($row['total_revenue'], 0) }}
                                </x-filament-tables::cell>
                            </x-filament-tables::row>
                        @endforeach
                    </x-filament-tables::table>
                </div>
            @endif
        </x-filament::section>
    @endif

    {{-- ── New Customers ────────────────────────────────────────────────────── --}}
    @if($filters['report'] === 'customers')
        @php $rows = $this->getCustomersData(); $totalNew = collect($rows)->sum('count'); @endphp

        <x-filament::section class="mt-6" icon="heroicon-o-users" icon-color="primary">
            <x-slot name="heading">New Customer Registrations</x-slot>
            <x-slot name="description">
                {{ number_format($totalNew) }} new {{ Str::plural('customer', $totalNew) }} registered in the selected period.
            </x-slot>

            @if(empty($rows))
                <div class="flex flex-col items-center py-10 gap-2 text-gray-400 dark:text-gray-500">
                    <x-heroicon-o-inbox class="h-10 w-10 opacity-40" />
                    <p class="text-sm">No new customers in this date range.</p>
                </div>
            @else
                @php $maxCount = collect($rows)->max('count') ?: 1; @endphp
                <div class="-mx-6 -mb-6 overflow-x-auto">
                    <x-filament-tables::table>
                        <x-slot name="header">
                            <x-filament-tables::header-cell name="date">Date</x-filament-tables::header-cell>
                            <x-filament-tables::header-cell name="bar">Trend</x-filament-tables::header-cell>
                            <x-filament-tables::header-cell name="count" alignment="end">New Registrations</x-filament-tables::header-cell>
                        </x-slot>

                        @foreach($rows as $i => $row)
                            <x-filament-tables::row :striped="$i % 2 !== 0">
                                <x-filament-tables::cell class="px-4 py-2.5 text-sm text-gray-700 dark:text-gray-300 sm:ps-6">
                                    {{ \Carbon\Carbon::parse($row['date'])->format('d M Y') }}
                                </x-filament-tables::cell>
                                <x-filament-tables::cell class="px-4 py-2.5 w-40">
                                    <div class="h-2 w-full rounded-full bg-gray-100 dark:bg-white/10 overflow-hidden">
                                        <div
                                            class="h-2 rounded-full bg-primary-500 dark:bg-primary-400 transition-all"
                                            style="width: {{ number_format($row['count'] / $maxCount * 100, 1) }}%"
                                        ></div>
                                    </div>
                                </x-filament-tables::cell>
                                <x-filament-tables::cell class="px-4 py-2.5 text-sm text-right font-semibold text-primary-700 dark:text-primary-400 sm:pe-6">
                                    {{ $row['count'] }}
                                </x-filament-tables::cell>
                            </x-filament-tables::row>
                        @endforeach

                        <x-slot name="footer">
                            <x-filament-tables::cell class="px-4 py-3 text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-200 sm:ps-6">
                                Total
                            </x-filament-tables::cell>
                            <x-filament-tables::cell class="px-4 py-3"></x-filament-tables::cell>
                            <x-filament-tables::cell class="px-4 py-3 text-sm text-right font-bold text-primary-700 dark:text-primary-400 sm:pe-6">
                                {{ number_format($totalNew) }}
                            </x-filament-tables::cell>
                        </x-slot>
                    </x-filament-tables::table>
                </div>
            @endif
        </x-filament::section>
    @endif

</x-filament-panels::page>
