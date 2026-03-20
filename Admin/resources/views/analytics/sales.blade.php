@extends('partials.layouts.master')

@section('title', 'Sales Analytics | WinCase CRM')
@section('sub-title', 'Sales Analytics')
@section('sub-title-lang', 'wc-sales-analytics')
@section('pagetitle', 'Analytics')
@section('pagetitle-lang', 'wc-analytics')

@section('css')
<link rel="stylesheet" href="{{ asset('assets/libs/apexcharts/apexcharts.min.css') }}">
@endsection

@section('content')

<!-- Stat Cards -->
<div class="row">
    <div class="col-xl-3 col-md-6">
        <div class="card card-hover overflow-hidden border-primary border-3 border-bottom">
            <div class="card-body p-4 d-flex align-items-start gap-3">
                <div class="flex-fill d-flex flex-column justify-content-between">
                    <div>
                        <h3 class="fw-semibold mb-1">PLN 34,200</h3>
                        <h6 class="mb-0">Revenue MTD</h6>
                    </div>
                    <div class="text-muted fs-13 mt-2">
                        <span class="text-body fw-semibold me-1"><i class="ri-arrow-right-up-line fs-16 me-1"></i>14%</span>vs last month
                    </div>
                </div>
                <div><i class="ri-money-euro-circle-line display-6 fw-medium text-muted opacity-50"></i></div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card card-hover overflow-hidden border-success border-3 border-bottom">
            <div class="card-body p-4 d-flex align-items-start gap-3">
                <div class="flex-fill d-flex flex-column justify-content-between">
                    <div>
                        <h3 class="fw-semibold mb-1">23</h3>
                        <h6 class="mb-0">Invoices Issued</h6>
                    </div>
                    <div class="text-muted fs-13 mt-2">
                        <span class="text-body fw-semibold me-1"><i class="ri-arrow-right-up-line fs-16 me-1"></i>8%</span>vs last month
                    </div>
                </div>
                <div><i class="ri-file-list-3-line display-6 fw-medium text-muted opacity-50"></i></div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card card-hover overflow-hidden border-warning border-3 border-bottom">
            <div class="card-body p-4 d-flex align-items-start gap-3">
                <div class="flex-fill d-flex flex-column justify-content-between">
                    <div>
                        <h3 class="fw-semibold mb-1">PLN 1,487</h3>
                        <h6 class="mb-0">Avg Invoice</h6>
                    </div>
                    <div class="text-muted fs-13 mt-2">
                        <span class="text-body fw-semibold me-1"><i class="ri-arrow-right-up-line fs-16 me-1"></i>5%</span>vs last month
                    </div>
                </div>
                <div><i class="ri-calculator-line display-6 fw-medium text-muted opacity-50"></i></div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card card-hover overflow-hidden border-info border-3 border-bottom">
            <div class="card-body p-4 d-flex align-items-start gap-3">
                <div class="flex-fill d-flex flex-column justify-content-between">
                    <div>
                        <h3 class="fw-semibold mb-1">27.4%</h3>
                        <h6 class="mb-0">Conversion Rate</h6>
                    </div>
                    <div class="text-muted fs-13 mt-2">
                        <span class="text-body fw-semibold me-1"><i class="ri-arrow-right-up-line fs-16 me-1"></i>3.2%</span>vs last month
                    </div>
                </div>
                <div><i class="ri-exchange-funds-line display-6 fw-medium text-muted opacity-50"></i></div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Revenue Trend -->
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h5 class="card-title mb-0">Revenue Trend</h5>
                <div class="d-flex gap-2">
                    <button class="btn btn-sm btn-subtle-primary active" onclick="updateRevenuePeriod('month', this)">Month</button>
                    <button class="btn btn-sm btn-subtle-primary" onclick="updateRevenuePeriod('quarter', this)">Quarter</button>
                    <button class="btn btn-sm btn-subtle-primary" onclick="updateRevenuePeriod('year', this)">Year</button>
                </div>
            </div>
            <div class="card-body">
                <div id="revenueTrendChart" style="height: 350px;"></div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Revenue by Service Type -->
    <div class="col-xl-4">
        <div class="card card-h-100">
            <div class="card-header">
                <h5 class="card-title mb-0">Revenue by Service Type</h5>
            </div>
            <div class="card-body">
                <div id="revenueByServiceChart" style="height: 300px;"></div>
                <div class="mt-3">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted fs-13"><i class="ri-circle-fill text-primary me-1 fs-10"></i>Work Permit</span>
                        <span class="fw-semibold">PLN 13,680</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted fs-13"><i class="ri-circle-fill text-success me-1 fs-10"></i>Residence Permit</span>
                        <span class="fw-semibold">PLN 8,550</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted fs-13"><i class="ri-circle-fill text-warning me-1 fs-10"></i>Company Registration</span>
                        <span class="fw-semibold">PLN 5,130</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted fs-13"><i class="ri-circle-fill text-danger me-1 fs-10"></i>Tax Services</span>
                        <span class="fw-semibold">PLN 4,104</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="text-muted fs-13"><i class="ri-circle-fill text-secondary me-1 fs-10"></i>Other</span>
                        <span class="fw-semibold">PLN 2,736</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Top Clients by Revenue -->
    <div class="col-xl-8">
        <div class="card card-h-100">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h5 class="card-title mb-0">Top Clients by Revenue</h5>
                <a href="{{ url('crm-clients') }}" class="btn btn-sm btn-subtle-primary">View All</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Client</th>
                                <th class="text-center">Cases</th>
                                <th class="text-end">Total Paid (PLN)</th>
                                <th class="text-end">Outstanding (PLN)</th>
                                <th>Last Payment</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="avatar avatar-xs avatar-rounded bg-primary-subtle text-primary">
                                            <span>OP</span>
                                        </div>
                                        <a href="#" class="fw-semibold text-body">Oleksandr Petrov</a>
                                    </div>
                                </td>
                                <td class="text-center">4</td>
                                <td class="text-end fw-semibold">8,450</td>
                                <td class="text-end text-danger">1,200</td>
                                <td class="text-muted fs-12">Feb 20, 2026</td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="avatar avatar-xs avatar-rounded bg-success-subtle text-success">
                                            <span>MI</span>
                                        </div>
                                        <a href="#" class="fw-semibold text-body">Maria Ivanova</a>
                                    </div>
                                </td>
                                <td class="text-center">3</td>
                                <td class="text-end fw-semibold">6,200</td>
                                <td class="text-end text-danger">800</td>
                                <td class="text-muted fs-12">Feb 18, 2026</td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="avatar avatar-xs avatar-rounded bg-warning-subtle text-warning">
                                            <span>DB</span>
                                        </div>
                                        <a href="#" class="fw-semibold text-body">Dmytro Boyko</a>
                                    </div>
                                </td>
                                <td class="text-center">2</td>
                                <td class="text-end fw-semibold">5,800</td>
                                <td class="text-end text-success">0</td>
                                <td class="text-muted fs-12">Feb 15, 2026</td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="avatar avatar-xs avatar-rounded bg-info-subtle text-info">
                                            <span>AK</span>
                                        </div>
                                        <a href="#" class="fw-semibold text-body">Anna Kowalska</a>
                                    </div>
                                </td>
                                <td class="text-center">2</td>
                                <td class="text-end fw-semibold">4,350</td>
                                <td class="text-end text-danger">2,100</td>
                                <td class="text-muted fs-12">Feb 12, 2026</td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="avatar avatar-xs avatar-rounded bg-danger-subtle text-danger">
                                            <span>VK</span>
                                        </div>
                                        <a href="#" class="fw-semibold text-body">Viktor Kovalenko</a>
                                    </div>
                                </td>
                                <td class="text-center">1</td>
                                <td class="text-end fw-semibold">3,400</td>
                                <td class="text-end text-success">0</td>
                                <td class="text-muted fs-12">Feb 10, 2026</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Monthly Comparison -->
    <div class="col-xl-6">
        <div class="card card-h-100">
            <div class="card-header">
                <h5 class="card-title mb-0">Monthly Comparison</h5>
            </div>
            <div class="card-body">
                <div id="monthlyComparisonChart" style="height: 300px;"></div>
            </div>
        </div>
    </div>

    <!-- Payment Methods -->
    <div class="col-xl-6">
        <div class="card card-h-100">
            <div class="card-header">
                <h5 class="card-title mb-0">Payment Methods</h5>
            </div>
            <div class="card-body">
                <div id="paymentMethodsChart" style="height: 300px;"></div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('js')
<script src="{{ asset('assets/libs/apexcharts/apexcharts.min.js') }}"></script>
<script>
// Revenue Trend — Area Chart
var revenueTrendData = {
    month: { categories: ['Sep', 'Oct', 'Nov', 'Dec', 'Jan', 'Feb'], data: [22400, 28600, 25200, 32800, 29500, 34200] },
    quarter: { categories: ['Q1 2025', 'Q2 2025', 'Q3 2025', 'Q4 2025'], data: [68200, 75400, 82100, 96500] },
    year: { categories: ['2022', '2023', '2024', '2025', '2026'], data: [185000, 242000, 310000, 356000, 34200] }
};
var revenueTrendOptions = {
    chart: { type: 'area', height: 350, toolbar: { show: false } },
    series: [{ name: 'Revenue (PLN)', data: revenueTrendData.month.data }],
    xaxis: { categories: revenueTrendData.month.categories },
    colors: ['#3b82f6'],
    stroke: { curve: 'smooth', width: 2 },
    fill: { type: 'gradient', gradient: { opacityFrom: 0.4, opacityTo: 0.05 } },
    dataLabels: { enabled: false },
    grid: { borderColor: '#f1f1f1' },
    tooltip: { y: { formatter: function(val) { return 'PLN ' + val.toLocaleString() } } }
};
var revenueTrendChart = new ApexCharts(document.querySelector("#revenueTrendChart"), revenueTrendOptions);
revenueTrendChart.render();

function updateRevenuePeriod(period, btn) {
    document.querySelectorAll('.card-header .btn-subtle-primary').forEach(function(b) { b.classList.remove('active'); });
    btn.classList.add('active');
    var d = revenueTrendData[period];
    revenueTrendChart.updateOptions({ xaxis: { categories: d.categories } });
    revenueTrendChart.updateSeries([{ name: 'Revenue (PLN)', data: d.data }]);
}

// Revenue by Service Type — Donut Chart
var serviceOptions = {
    chart: { type: 'donut', height: 300 },
    series: [40, 25, 15, 12, 8],
    labels: ['Work Permit', 'Residence Permit', 'Company Registration', 'Tax Services', 'Other'],
    colors: ['#3b82f6', '#10b981', '#f59e0b', '#ef4444', '#6b7280'],
    legend: { position: 'bottom' },
    dataLabels: { enabled: true, formatter: function(val) { return val.toFixed(0) + '%' } },
    plotOptions: { pie: { donut: { size: '65%', labels: { show: true, total: { show: true, label: 'Total', formatter: function() { return 'PLN 34,200' } } } } } }
};
new ApexCharts(document.querySelector("#revenueByServiceChart"), serviceOptions).render();

// Monthly Comparison — Bar Chart
var comparisonOptions = {
    chart: { type: 'bar', height: 300, toolbar: { show: false } },
    series: [
        { name: 'Current Month', data: [12800, 6400, 5100, 4900, 5000] },
        { name: 'Previous Month', data: [10200, 5800, 4600, 4200, 4700] }
    ],
    xaxis: { categories: ['Work Permit', 'Residence', 'Company Reg.', 'Tax', 'Other'] },
    colors: ['#3b82f6', '#c7d2fe'],
    plotOptions: { bar: { borderRadius: 4, columnWidth: '60%' } },
    dataLabels: { enabled: false },
    grid: { borderColor: '#f1f1f1' },
    legend: { position: 'top' },
    tooltip: { y: { formatter: function(val) { return 'PLN ' + val.toLocaleString() } } }
};
new ApexCharts(document.querySelector("#monthlyComparisonChart"), comparisonOptions).render();

// Payment Methods — Horizontal Bar
var paymentOptions = {
    chart: { type: 'bar', height: 300, toolbar: { show: false } },
    series: [{ name: 'Payments', data: [60, 20, 15, 5] }],
    xaxis: { categories: ['Bank Transfer', 'Cash', 'Card', 'POS Terminal'] },
    colors: ['#3b82f6'],
    plotOptions: { bar: { horizontal: true, borderRadius: 4, barHeight: '50%' } },
    dataLabels: { enabled: true, formatter: function(val) { return val + '%' } },
    grid: { borderColor: '#f1f1f1' },
    tooltip: { y: { formatter: function(val) { return val + '%' } } }
};
new ApexCharts(document.querySelector("#paymentMethodsChart"), paymentOptions).render();
</script>
@endsection
