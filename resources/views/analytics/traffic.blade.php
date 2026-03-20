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
                        <h3 class="fw-semibold mb-1" id="kpiVisitors">--</h3>
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
                        <h3 class="fw-semibold mb-1" id="kpiLandingViews">--</h3>
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
                        <h3 class="fw-semibold mb-1" id="kpiBounceRate">--</h3>
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
                        <h3 class="fw-semibold mb-1" id="kpiAvgSession">--</h3>
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
                <div class="mt-3" id="sourcesDetailList">
                    <div class="text-muted text-center py-2">Loading...</div>
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
                        <tbody id="landingPagesBody">
                            <tr><td colspan="5" class="text-center text-muted py-3">Loading...</td></tr>
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
                        <tbody id="geoBody">
                            <tr><td colspan="4" class="text-center text-muted py-3">Loading...</td></tr>
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
document.addEventListener('DOMContentLoaded', function() {

    var trafficOverviewChart = null;
    var trafficSourcesChart = null;
    var deviceBreakdownChart = null;

    var FLAG_MAP = {
        'PL': '\u{1F1F5}\u{1F1F1}', 'UA': '\u{1F1FA}\u{1F1E6}', 'BY': '\u{1F1E7}\u{1F1FE}',
        'DE': '\u{1F1E9}\u{1F1EA}', 'RU': '\u{1F1F7}\u{1F1FA}', 'GB': '\u{1F1EC}\u{1F1E7}',
        'US': '\u{1F1FA}\u{1F1F8}'
    };
    var COUNTRY_MAP = {
        'PL': 'Poland', 'UA': 'Ukraine', 'BY': 'Belarus',
        'DE': 'Germany', 'RU': 'Russia', 'GB': 'United Kingdom', 'US': 'United States'
    };
    var BAR_COLORS = ['bg-primary', 'bg-success', 'bg-warning', 'bg-info', 'bg-secondary'];
    var SOURCE_LABELS = { organic: 'Organic Search', paid: 'Paid Ads', social: 'Social Media', direct: 'Direct' };
    var SOURCE_COLORS_CSS = { organic: 'text-primary', paid: 'text-success', social: 'text-warning', direct: 'text-info' };

    function fmt(n) {
        return Number(n).toLocaleString('en-US');
    }

    function formatDate(dateStr) {
        var d = new Date(dateStr);
        return d.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
    }

    function renderKPI(kpi) {
        document.getElementById('kpiVisitors').textContent = fmt(kpi.visitors);
        document.getElementById('kpiLandingViews').textContent = fmt(kpi.landing_views);
        document.getElementById('kpiBounceRate').textContent = kpi.bounce_rate + '%';
        document.getElementById('kpiAvgSession').textContent = kpi.avg_session;
    }

    function renderTrafficOverview(dailyTraffic) {
        var categories = dailyTraffic.map(function(d) { return formatDate(d.date); });
        var users = dailyTraffic.map(function(d) { return d.users; });
        var sessions = dailyTraffic.map(function(d) { return d.sessions; });

        var options = {
            chart: { type: 'line', height: 350, toolbar: { show: false } },
            series: [
                { name: 'Users', data: users },
                { name: 'Sessions', data: sessions }
            ],
            xaxis: { categories: categories },
            colors: ['#3b82f6', '#10b981'],
            stroke: { curve: 'smooth', width: 2 },
            dataLabels: { enabled: false },
            grid: { borderColor: '#f1f1f1' },
            legend: { position: 'top' },
            tooltip: { shared: true, intersect: false }
        };

        if (trafficOverviewChart) trafficOverviewChart.destroy();
        trafficOverviewChart = new ApexCharts(document.querySelector('#trafficOverviewChart'), options);
        trafficOverviewChart.render();
    }

    function renderTrafficSources(sources, totalVisitors) {
        var keys = ['organic', 'paid', 'social', 'direct'];
        var series = keys.map(function(k) { return sources[k] || 0; });
        var labels = keys.map(function(k) { return SOURCE_LABELS[k]; });
        var total = fmt(totalVisitors);

        var options = {
            chart: { type: 'donut', height: 280 },
            series: series,
            labels: labels,
            colors: ['#3b82f6', '#10b981', '#f59e0b', '#8b5cf6'],
            legend: { show: false },
            dataLabels: { enabled: true, formatter: function(val) { return val.toFixed(0) + '%'; } },
            plotOptions: {
                pie: {
                    donut: {
                        size: '65%',
                        labels: {
                            show: true,
                            total: { show: true, label: 'Total', formatter: function() { return total; } }
                        }
                    }
                }
            }
        };

        if (trafficSourcesChart) trafficSourcesChart.destroy();
        trafficSourcesChart = new ApexCharts(document.querySelector('#trafficSourcesChart'), options);
        trafficSourcesChart.render();

        // Sources detail list
        var listEl = document.getElementById('sourcesDetailList');
        var html = '';
        keys.forEach(function(k, i) {
            var isLast = (i === keys.length - 1);
            html += '<div class="d-flex justify-content-between' + (isLast ? '' : ' mb-2') + '">';
            html += '<span class="text-muted fs-13"><i class="ri-circle-fill ' + SOURCE_COLORS_CSS[k] + ' me-1 fs-10"></i>' + SOURCE_LABELS[k] + '</span>';
            html += '<span class="fw-semibold">' + (sources[k] || 0) + '%</span>';
            html += '</div>';
        });
        listEl.innerHTML = html;
    }

    function renderLandingPages(pages) {
        var tbody = document.getElementById('landingPagesBody');
        if (!pages || pages.length === 0) {
            tbody.innerHTML = '<tr><td colspan="5" class="text-center text-muted py-3">No data available</td></tr>';
            return;
        }
        var html = '';
        pages.forEach(function(p) {
            var badgeClass = p.conv_rate >= 4 ? 'bg-success-subtle text-success' : 'bg-warning-subtle text-warning';
            html += '<tr>';
            html += '<td><div class="d-flex align-items-center gap-2">';
            html += '<i class="ri-pages-line text-primary"></i>';
            html += '<a href="#" class="fw-semibold text-body">' + p.url + '</a>';
            html += '</div></td>';
            html += '<td class="text-center">' + fmt(p.visitors) + '</td>';
            html += '<td class="text-center fw-semibold">' + fmt(p.conversions) + '</td>';
            html += '<td class="text-center"><span class="badge ' + badgeClass + '">' + p.conv_rate + '%</span></td>';
            html += '<td class="text-center">--</td>';
            html += '</tr>';
        });
        tbody.innerHTML = html;
    }

    function renderDeviceBreakdown(devices) {
        var options = {
            chart: { type: 'bar', height: 300, toolbar: { show: false } },
            series: [{ name: 'Visitors', data: [devices.desktop || 0, devices.mobile || 0, devices.tablet || 0] }],
            xaxis: { categories: ['Desktop', 'Mobile', 'Tablet'] },
            colors: ['#3b82f6'],
            plotOptions: { bar: { horizontal: true, borderRadius: 6, barHeight: '45%', distributed: true } },
            dataLabels: { enabled: true, formatter: function(val) { return val + '%'; }, style: { fontSize: '14px' } },
            grid: { borderColor: '#f1f1f1' },
            legend: { show: false },
            tooltip: { y: { formatter: function(val) { return val + '% of visitors'; } } }
        };

        if (deviceBreakdownChart) deviceBreakdownChart.destroy();
        deviceBreakdownChart = new ApexCharts(document.querySelector('#deviceBreakdownChart'), options);
        deviceBreakdownChart.render();
    }

    function renderGeo(geoData, totalVisitors) {
        var tbody = document.getElementById('geoBody');
        if (!geoData || geoData.length === 0) {
            tbody.innerHTML = '<tr><td colspan="4" class="text-center text-muted py-3">No data available</td></tr>';
            return;
        }
        var total = totalVisitors || geoData.reduce(function(s, g) { return s + g.cnt; }, 0);
        var html = '';
        geoData.forEach(function(g, i) {
            var pct = total > 0 ? ((g.cnt / total) * 100).toFixed(1) : '0.0';
            var flag = FLAG_MAP[g.country] || '\u{1F310}';
            var name = COUNTRY_MAP[g.country] || g.country;
            var barColor = BAR_COLORS[i % BAR_COLORS.length];
            html += '<tr>';
            html += '<td><div class="d-flex align-items-center gap-2">';
            html += '<span class="fs-18">' + flag + '</span>';
            html += '<span class="fw-semibold">' + name + '</span>';
            html += '</div></td>';
            html += '<td class="text-center">' + fmt(g.cnt) + '</td>';
            html += '<td><div class="progress progress-sm" style="width:120px;">';
            html += '<div class="progress-bar ' + barColor + '" style="width:' + pct + '%"></div>';
            html += '</div></td>';
            html += '<td class="text-end fw-semibold">' + pct + '%</td>';
            html += '</tr>';
        });
        tbody.innerHTML = html;
    }

    // Fetch data from API
    var token = localStorage.getItem('wc_token');
    fetch('/api/v1/analytics/traffic', {
        method: 'GET',
        headers: {
            'Accept': 'application/json',
            'Authorization': 'Bearer ' + (token || '')
        }
    })
    .then(function(res) { return res.json(); })
    .then(function(json) {
        if (!json.success || !json.data) {
            console.error('Traffic analytics: API returned error', json);
            return;
        }
        var d = json.data;

        renderKPI(d.kpi || {});
        renderTrafficOverview(d.daily_traffic || []);
        renderTrafficSources(d.sources || {}, (d.kpi || {}).visitors || 0);
        renderLandingPages(d.landing_pages || []);
        renderDeviceBreakdown(d.devices || {});
        renderGeo(d.geo || [], (d.kpi || {}).visitors || 0);
    })
    .catch(function(err) {
        console.error('Traffic analytics: fetch failed', err);
    });

});
</script>
@endsection
