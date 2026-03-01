<x-filament-panels::page>

    {{-- Filter Section --}}
    <x-filament::section>
        <x-slot name="heading">Filters</x-slot>
        <x-slot name="description">Select a date range and report type, then click Apply.</x-slot>

        <form wire:submit="applyFilters">
            {{ $this->filtersForm }}

            <div class="mt-4 flex items-center gap-3">
                <x-filament::button type="submit" icon="heroicon-m-funnel">
                    Apply Filters
                </x-filament::button>

                <x-filament::button
                    tag="a"
                    href="{{ $this->getCsvUrl() }}"
                    target="_blank"
                    color="gray"
                    icon="heroicon-m-arrow-down-tray"
                >
                    Export CSV
                </x-filament::button>
            </div>
        </form>
    </x-filament::section>

    {{-- Report: Sales Revenue --}}
    @if($filters['report'] === 'sales')
        @php $data = $this->getSalesData(); $rows = $data['rows']; $totals = $data['totals']; @endphp

        {{-- KPI cards --}}
        <div class="mt-6 grid grid-cols-2 lg:grid-cols-4 gap-4">
            <x-filament::section>
                <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider font-semibold mb-1">Total Revenue</p>
                <p class="text-2xl font-bold text-success-600 dark:text-success-400">৳{{ number_format($totals['revenue'], 0) }}</p>
            </x-filament::section>
            <x-filament::section>
                <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider font-semibold mb-1">Paid Orders</p>
                <p class="text-2xl font-bold text-primary-600 dark:text-primary-400">{{ number_format($totals['orders']) }}</p>
            </x-filament::section>
            <x-filament::section>
                <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider font-semibold mb-1">Discounts Given</p>
                <p class="text-2xl font-bold text-warning-600 dark:text-warning-400">৳{{ number_format($totals['discounts'], 0) }}</p>
            </x-filament::section>
            <x-filament::section>
                <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider font-semibold mb-1">Avg. Order Value</p>
                <p class="text-2xl font-bold text-gray-800 dark:text-gray-100">৳{{ $totals['orders'] > 0 ? number_format($totals['revenue'] / $totals['orders'], 0) : 0 }}</p>
            </x-filament::section>
        </div>

        {{-- Daily Breakdown --}}
        <x-filament::section class="mt-6">
            <x-slot name="heading">Daily Breakdown</x-slot>

            @if($rows->isEmpty())
                <p class="py-6 text-center text-sm text-gray-400 dark:text-gray-500">No paid orders in this date range.</p>
            @else
                <div class="overflow-x-auto">
                    <x-filament-tables::table>
                        <x-slot name="header">
                            <x-filament-tables::header-cell name="date">Date</x-filament-tables::header-cell>
                            <x-filament-tables::header-cell name="orders" alignment="end">Orders</x-filament-tables::header-cell>
                            <x-filament-tables::header-cell name="revenue" alignment="end">Revenue</x-filament-tables::header-cell>
                            <x-filament-tables::header-cell name="discounts" alignment="end">Discounts</x-filament-tables::header-cell>
                            <x-filament-tables::header-cell name="shipping" alignment="end">Shipping</x-filament-tables::header-cell>
                        </x-slot>

                        @foreach($rows as $row)
                            <x-filament-tables::row>
                                <x-filament-tables::cell class="px-3 py-2 text-sm text-gray-700 dark:text-gray-300">
                                    {{ \Carbon\Carbon::parse($row->date)->format('d M Y') }}
                                </x-filament-tables::cell>
                                <x-filament-tables::cell class="px-3 py-2 text-sm text-right text-gray-600 dark:text-gray-400">
                                    {{ $row->orders }}
                                </x-filament-tables::cell>
                                <x-filament-tables::cell class="px-3 py-2 text-sm text-right font-semibold text-success-700 dark:text-success-400">
                                    ৳{{ number_format($row->revenue, 0) }}
                                </x-filament-tables::cell>
                                <x-filament-tables::cell class="px-3 py-2 text-sm text-right text-warning-600 dark:text-warning-400">
                                    ৳{{ number_format($row->discounts, 0) }}
                                </x-filament-tables::cell>
                                <x-filament-tables::cell class="px-3 py-2 text-sm text-right text-gray-500 dark:text-gray-400">
                                    ৳{{ number_format($row->shipping, 0) }}
                                </x-filament-tables::cell>
                            </x-filament-tables::row>
                        @endforeach

                        <x-slot name="footer">
                            <x-filament-tables::cell class="px-3 py-2 text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-200">Total</x-filament-tables::cell>
                            <x-filament-tables::cell class="px-3 py-2 text-sm text-right font-bold text-gray-700 dark:text-gray-200">{{ $totals['orders'] }}</x-filament-tables::cell>
                            <x-filament-tables::cell class="px-3 py-2 text-sm text-right font-bold text-success-700 dark:text-success-400">৳{{ number_format($totals['revenue'], 0) }}</x-filament-tables::cell>
                            <x-filament-tables::cell class="px-3 py-2 text-sm text-right font-bold text-warning-600 dark:text-warning-400">৳{{ number_format($totals['discounts'], 0) }}</x-filament-tables::cell>
                            <x-filament-tables::cell class="px-3 py-2 text-sm text-right font-bold text-gray-500 dark:text-gray-400">৳{{ number_format($totals['shipping'], 0) }}</x-filament-tables::cell>
                        </x-slot>
                    </x-filament-tables::table>
                </div>
            @endif
        </x-filament::section>
    @endif

    {{-- Report: Orders by Status --}}
    @if($filters['report'] === 'orders')
        @php $rows = $this->getOrdersData(); $total = collect($rows)->sum('count'); @endphp

        <x-filament::section class="mt-6">
            <x-slot name="heading">
                Orders by Status
                <span class="text-sm text-gray-400 dark:text-gray-500 font-normal ml-2">({{ number_format($total) }} total)</span>
            </x-slot>

            @if(empty($rows))
                <p class="py-6 text-center text-sm text-gray-400 dark:text-gray-500">No orders in this date range.</p>
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
                @endphp
                <div class="overflow-x-auto">
                    <x-filament-tables::table>
                        <x-slot name="header">
                            <x-filament-tables::header-cell name="status">Status</x-filament-tables::header-cell>
                            <x-filament-tables::header-cell name="count" alignment="end">Count</x-filament-tables::header-cell>
                            <x-filament-tables::header-cell name="pct" alignment="end">% of Total</x-filament-tables::header-cell>
                            <x-filament-tables::header-cell name="total" alignment="end">Total Amount</x-filament-tables::header-cell>
                        </x-slot>

                        @foreach($rows as $row)
                            <x-filament-tables::row>
                                <x-filament-tables::cell class="px-3 py-2">
                                    <x-filament::badge :color="$statusColors[$row['status']] ?? 'gray'">
                                        {{ ucfirst($row['status']) }}
                                    </x-filament::badge>
                                </x-filament-tables::cell>
                                <x-filament-tables::cell class="px-3 py-2 text-sm text-right font-semibold text-gray-800 dark:text-gray-200">
                                    {{ number_format($row['count']) }}
                                </x-filament-tables::cell>
                                <x-filament-tables::cell class="px-3 py-2 text-sm text-right text-gray-500 dark:text-gray-400">
                                    {{ $total > 0 ? number_format($row['count'] / $total * 100, 1) : 0 }}%
                                </x-filament-tables::cell>
                                <x-filament-tables::cell class="px-3 py-2 text-sm text-right font-semibold text-success-700 dark:text-success-400">
                                    ৳{{ number_format($row['total'], 0) }}
                                </x-filament-tables::cell>
                            </x-filament-tables::row>
                        @endforeach
                    </x-filament-tables::table>
                </div>
            @endif
        </x-filament::section>
    @endif

    {{-- Report: Top Products --}}
    @if($filters['report'] === 'products')
        @php $rows = $this->getProductsData(); @endphp

        <x-filament::section class="mt-6">
            <x-slot name="heading">Top Products by Revenue</x-slot>
            <x-slot name="description">Based on paid orders in the selected date range.</x-slot>

            @if(empty($rows))
                <p class="py-6 text-center text-sm text-gray-400 dark:text-gray-500">No data in this date range.</p>
            @else
                <div class="overflow-x-auto">
                    <x-filament-tables::table>
                        <x-slot name="header">
                            <x-filament-tables::header-cell name="rank">#</x-filament-tables::header-cell>
                            <x-filament-tables::header-cell name="product">Product</x-filament-tables::header-cell>
                            <x-filament-tables::header-cell name="qty" alignment="end">Units Sold</x-filament-tables::header-cell>
                            <x-filament-tables::header-cell name="revenue" alignment="end">Revenue</x-filament-tables::header-cell>
                        </x-slot>

                        @foreach($rows as $i => $row)
                            <x-filament-tables::row>
                                <x-filament-tables::cell class="px-3 py-2 text-xs text-gray-400 dark:text-gray-500">
                                    {{ $i + 1 }}
                                </x-filament-tables::cell>
                                <x-filament-tables::cell class="px-3 py-2 text-sm font-medium text-gray-800 dark:text-gray-200">
                                    {{ $row['product_name'] }}
                                </x-filament-tables::cell>
                                <x-filament-tables::cell class="px-3 py-2 text-sm text-right text-gray-600 dark:text-gray-400">
                                    {{ number_format($row['total_qty']) }}
                                </x-filament-tables::cell>
                                <x-filament-tables::cell class="px-3 py-2 text-sm text-right font-bold text-success-700 dark:text-success-400">
                                    ৳{{ number_format($row['total_revenue'], 0) }}
                                </x-filament-tables::cell>
                            </x-filament-tables::row>
                        @endforeach
                    </x-filament-tables::table>
                </div>
            @endif
        </x-filament::section>
    @endif

    {{-- Report: New Customers --}}
    @if($filters['report'] === 'customers')
        @php $rows = $this->getCustomersData(); $totalNew = collect($rows)->sum('count'); @endphp

        <x-filament::section class="mt-6">
            <x-slot name="heading">
                New Customer Registrations
                <span class="text-sm text-gray-400 dark:text-gray-500 font-normal ml-2">({{ number_format($totalNew) }} total)</span>
            </x-slot>
            <x-slot name="description">Daily count of new user registrations in the selected period.</x-slot>

            @if(empty($rows))
                <p class="py-6 text-center text-sm text-gray-400 dark:text-gray-500">No new customers in this date range.</p>
            @else
                <div class="overflow-x-auto">
                    <x-filament-tables::table>
                        <x-slot name="header">
                            <x-filament-tables::header-cell name="date">Date</x-filament-tables::header-cell>
                            <x-filament-tables::header-cell name="count" alignment="end">New Registrations</x-filament-tables::header-cell>
                        </x-slot>

                        @foreach($rows as $row)
                            <x-filament-tables::row>
                                <x-filament-tables::cell class="px-3 py-2 text-sm text-gray-700 dark:text-gray-300">
                                    {{ \Carbon\Carbon::parse($row['date'])->format('d M Y') }}
                                </x-filament-tables::cell>
                                <x-filament-tables::cell class="px-3 py-2 text-sm text-right font-semibold text-primary-700 dark:text-primary-400">
                                    {{ $row['count'] }}
                                </x-filament-tables::cell>
                            </x-filament-tables::row>
                        @endforeach

                        <x-slot name="footer">
                            <x-filament-tables::cell class="px-3 py-2 text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-200">Total</x-filament-tables::cell>
                            <x-filament-tables::cell class="px-3 py-2 text-sm text-right font-bold text-primary-700 dark:text-primary-400">{{ $totalNew }}</x-filament-tables::cell>
                        </x-slot>
                    </x-filament-tables::table>
                </div>
            @endif
        </x-filament::section>
    @endif

</x-filament-panels::page>
