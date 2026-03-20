@extends('partials.layouts.master')

@section('title', 'Traffic Analytics | WinCase CRM')
@section('sub-title', 'Traffic Analytics')
@section('sub-title-lang', 'wc-traffic-analytics')
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
                        <h3 class="fw-semibold mb-1">12,450</h3>
                        <h6 class="mb-0">Website Visitors</h6>
                    </div>
                    <div class="text-muted fs-13 mt-2">
                        <span class="text-body fw-semibold me-1"><i class="ri-arrow-right-up-line fs-16 me-1"></i>22%</span>vs last month
                    </div>
                </div>
                <div><i class="ri-global-line display-6 fw-medium text-muted opacity-50"></i></div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card card-hover overflow-hidden border-success border-3 border-bottom">
            <div class="card-body p-4 d-flex align-items-start gap-3">
                <div class="flex-fill d-flex flex-column justify-content-between">
                    <div>
                        <h3 class="fw-semibold mb-1">8,230</h3>
                        <h6 class="mb-0">Landing Page Views</h6>
                    </div>
                    <div class="text-muted fs-13 mt-2">
                        <span class="text-body fw-semibold me-1"><i class="ri-arrow-right-up-line fs-16 me-1"></i>15%</span>vs last month
                    </div>
                </div>
                <div><i class="ri-pages-line display-6 fw-medium text-muted opacity-50"></i></div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card card-hover overflow-hidden border-danger border-3 border-bottom">
            <div class="card-body p-4 d-flex align-items-start gap-3">
                <div class="flex-fill d-flex flex-column justify-content-between">
                    <div>
                        <h3 class="fw-semibold mb-1">34.2%</h3>
                        <h6 class="mb-0">Bounce Rate</h6>
                    </div>
                    <div class="text-muted fs-13 mt-2">
                        <span class="text-body fw-semibold me-1"><i class="ri-arrow-right-down-line fs-16 me-1 text-success"></i>4.1%</span>Improved
                    </div>
                </div>
                <div><i class="ri-logout-box-r-line display-6 fw-medium text-muted opacity-50"></i></div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card card-hover overflow-hidden border-info border-3 border-bottom">
            <div class="card-body p-4 d-flex align-items-start gap-3">
                <div class="flex-fill d-flex flex-column justify-content-between">
                    <div>
                        <h3 class="fw-semibold mb-1">2:45</h3>
                        <h6 class="mb-0">Avg Session Duration</h6>
                    </div>
                    <div class="text-muted fs-13 mt-2">
                        <span class="text-body fw-semibold me-1"><i class="ri-arrow-right-up-line fs-16 me-1"></i>8%</span>vs last month
                    </div>
                </div>
                <div><i class="ri-timer-line display-6 fw-medium text-muted opacity-50"></i></div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Traffic Overview -->
    <div class="col-xl-8">
        <div class="card card-h-100">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h5 class="card-title mb-0">Traffic Overview</h5>
                <div class="dropdown">
                    <button class="btn btn-sm btn-subtle-primary" data-bs-toggle="dropdown"><i class="ri-more-2-fill"></i></button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="#">Last 7 Days</a></li>
                        <li><a class="dropdown-item" href="#">Last 30 Days</a></li>
                        <li><a class="dropdown-item" href="#">Last 90 Days</a></li>
                    </ul>
                </div>
            </div>
            <div class="card-body">
                <div id="trafficOverviewChart" style="height: 350px;"></div>
            </div>
        </div>
    </div>

    <!-- Traffic Sources -->
    <div class="col-xl-4">
        <div class="card card-h-100">
            <div class="card-header">
                <h5 class="card-title mb-0">Traffic Sources</h5>
            </div>
            <div class="card-body">
                <div id="trafficSourcesChart" style="height: 280px;"></div>
                <div class="mt-3">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted fs-13"><i class="ri-circle-fill text-primary me-1 fs-10"></i>Organic Search</span>
                        <span class="fw-semibold">42%</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted fs-13"><i class="ri-circle-fill text-success me-1 fs-10"></i>Paid Ads</span>
                        <span class="fw-semibold">31%</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted fs-13"><i class="ri-circle-fill text-warning me-1 fs-10"></i>Social Media</span>
                        <span class="fw-semibold">18%</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="text-muted fs-13"><i class="ri-circle-fill text-info me-1 fs-10"></i>Direct</span>
                        <span class="fw-semibold">9%</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Top Landing Pages -->
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h5 class="card-title mb-0">Top Landing Pages</h5>
                <a href="#" class="btn btn-sm btn-subtle-primary"><i class="ri-external-link-line me-1"></i>View in Google Analytics</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>URL</th>
                                <th class="text-center">Visitors</th>
                                <th class="text-center">Conversions</th>
                                <th class="text-center">Conv. Rate (%)</th>
                                <th class="text-center">Bounce Rate</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <i class="ri-pages-line text-primary"></i>
                                        <a href="#" class="fw-semibold text-body">/pozwolenie-na-prace</a>
                                    </div>
                                </td>
                                <td class="text-center">3,240</td>
                                <td class="text-center fw-semibold">156</td>
                                <td class="text-center"><span class="badge bg-success-subtle text-success">4.8%</span></td>
                                <td class="text-center">28.3%</td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <i class="ri-pages-line text-primary"></i>
                                        <a href="#" class="fw-semibold text-body">/karta-pobytu</a>
                                    </div>
                                </td>
                                <td class="text-center">2,180</td>
                                <td class="text-center fw-semibold">98</td>
                                <td class="text-center"><span class="badge bg-success-subtle text-success">4.5%</span></td>
                                <td class="text-center">31.5%</td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <i class="ri-pages-line text-primary"></i>
                                        <a href="#" class="fw-semibold text-body">/rejestracja-firmy</a>
                                    </div>
                                </td>
                                <td class="text-center">1,450</td>
                                <td class="text-center fw-semibold">52</td>
                                <td class="text-center"><span class="badge bg-warning-subtle text-warning">3.6%</span></td>
                                <td class="text-center">36.2%</td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <i class="ri-pages-line text-primary"></i>
                                        <a href="#" class="fw-semibold text-body">/rozliczenia-podatkowe</a>
                                    </div>
                                </td>
                                <td class="text-center">820</td>
                                <td class="text-center fw-semibold">28</td>
                                <td class="text-center"><span class="badge bg-warning-subtle text-warning">3.4%</span></td>
                                <td class="text-center">38.7%</td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <i class="ri-pages-line text-primary"></i>
                                        <a href="#" class="fw-semibold text-body">/kontakt</a>
                                    </div>
                                </td>
                                <td class="text-center">540</td>
                                <td class="text-center fw-semibold">42</td>
                                <td class="text-center"><span class="badge bg-success-subtle text-success">7.8%</span></td>
                                <td class="text-center">22.1%</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Geo Distribution -->
    <div class="col-xl-6">
        <div class="card card-h-100">
            <div class="card-header">
                <h5 class="card-title mb-0">Geo Distribution</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Country</th>
                                <th class="text-center">Visitors</th>
                                <th>Share</th>
                                <th class="text-end">%</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="fs-18">&#127477;&#127473;</span>
                                        <span class="fw-semibold">Poland</span>
                                    </div>
                                </td>
                                <td class="text-center">5,480</td>
                                <td>
                                    <div class="progress progress-sm" style="width: 120px;">
                                        <div class="progress-bar bg-primary" style="width: 44%"></div>
                                    </div>
                                </td>
                                <td class="text-end fw-semibold">44.0%</td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="fs-18">&#127482;&#127462;</span>
                                        <span class="fw-semibold">Ukraine</span>
                                    </div>
                                </td>
                                <td class="text-center">3,860</td>
                                <td>
                                    <div class="progress progress-sm" style="width: 120px;">
                                        <div class="progress-bar bg-success" style="width: 31%"></div>
                                    </div>
                                </td>
                                <td class="text-end fw-semibold">31.0%</td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="fs-18">&#127463;&#127486;</span>
                                        <span class="fw-semibold">Belarus</span>
                                    </div>
                                </td>
                                <td class="text-center">1,620</td>
                                <td>
                                    <div class="progress progress-sm" style="width: 120px;">
                                        <div class="progress-bar bg-warning" style="width: 13%"></div>
                                    </div>
                                </td>
                                <td class="text-end fw-semibold">13.0%</td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="fs-18">&#127465;&#127466;</span>
                                        <span class="fw-semibold">Germany</span>
                                    </div>
                                </td>
                                <td class="text-center">870</td>
                                <td>
                                    <div class="progress progress-sm" style="width: 120px;">
                                        <div class="progress-bar bg-info" style="width: 7%"></div>
                                    </div>
                                </td>
                                <td class="text-end fw-semibold">7.0%</td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="fs-18">&#127760;</span>
                                        <span class="fw-semibold">Other</span>
                                    </div>
                                </td>
                                <td class="text-center">620</td>
                                <td>
                                    <div class="progress progress-sm" style="width: 120px;">
                                        <div class="progress-bar bg-secondary" style="width: 5%"></div>
                                    </div>
                                </td>
                                <td class="text-end fw-semibold">5.0%</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Device Breakdown -->
    <div class="col-xl-6">
        <div class="card card-h-100">
            <div class="card-header">
                <h5 class="card-title mb-0">Device Breakdown</h5>
            </div>
            <div class="card-body">
                <div id="deviceBreakdownChart" style="height: 300px;"></div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('js')
<script src="{{ asset('assets/libs/apexcharts/apexcharts.min.js') }}"></script>
<script>
// Traffic Overview — Line Chart (7 days)
var trafficOverviewOptions = {
    chart: { type: 'line', height: 350, toolbar: { show: false } },
    series: [
        { name: 'Organic', data: [620, 780, 850, 720, 900, 1050, 980] },
        { name: 'Paid', data: [420, 560, 510, 480, 620, 580, 640] },
        { name: 'Social', data: [180, 240, 320, 280, 350, 310, 290] },
        { name: 'Direct', data: [90, 110, 130, 100, 140, 120, 150] }
    ],
    xaxis: { categories: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'] },
    colors: ['#3b82f6', '#10b981', '#f59e0b', '#8b5cf6'],
    stroke: { curve: 'smooth', width: 2 },
    dataLabels: { enabled: false },
    grid: { borderColor: '#f1f1f1' },
    legend: { position: 'top' },
    tooltip: { shared: true, intersect: false }
};
new ApexCharts(document.querySelector("#trafficOverviewChart"), trafficOverviewOptions).render();

// Traffic Sources — Donut Chart
var sourcesOptions = {
    chart: { type: 'donut', height: 280 },
    series: [42, 31, 18, 9],
    labels: ['Organic Search', 'Paid Ads', 'Social Media', 'Direct'],
    colors: ['#3b82f6', '#10b981', '#f59e0b', '#8b5cf6'],
    legend: { show: false },
    dataLabels: { enabled: true, formatter: function(val) { return val.toFixed(0) + '%' } },
    plotOptions: { pie: { donut: { size: '65%', labels: { show: true, total: { show: true, label: 'Total', formatter: function() { return '12,450' } } } } } }
};
new ApexCharts(document.querySelector("#trafficSourcesChart"), sourcesOptions).render();

// Device Breakdown — Horizontal Bar
var deviceOptions = {
    chart: { type: 'bar', height: 300, toolbar: { show: false } },
    series: [{ name: 'Visitors', data: [55, 38, 7] }],
    xaxis: { categories: ['Desktop', 'Mobile', 'Tablet'] },
    colors: ['#3b82f6'],
    plotOptions: { bar: { horizontal: true, borderRadius: 6, barHeight: '45%', distributed: true } },
    dataLabels: { enabled: true, formatter: function(val) { return val + '%' }, style: { fontSize: '14px' } },
    grid: { borderColor: '#f1f1f1' },
    legend: { show: false },
    tooltip: { y: { formatter: function(val) { return val + '% of visitors' } } }
};
new ApexCharts(document.querySelector("#deviceBreakdownChart"), deviceOptions).render();
</script>
@endsection
