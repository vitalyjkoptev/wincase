@extends('partials.layouts.master')

@section('title', 'Team Performance | WinCase CRM')
@section('sub-title', 'Team Performance')
@section('sub-title-lang', 'wc-team-performance')
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
                        <h3 class="fw-semibold mb-1" id="kpiActiveUsers">--</h3>
                        <h6 class="mb-0">Active Users</h6>
                    </div>
                    <div class="text-muted fs-13 mt-2">
                        <span class="text-body fw-semibold me-1"><i class="ri-team-line fs-16 me-1"></i><span id="kpiOnlineNow">--</span></span>Online now
                    </div>
                </div>
                <div><i class="ri-group-line display-6 fw-medium text-muted opacity-50"></i></div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card card-hover overflow-hidden border-success border-3 border-bottom">
            <div class="card-body p-4 d-flex align-items-start gap-3">
                <div class="flex-fill d-flex flex-column justify-content-between">
                    <div>
                        <h3 class="fw-semibold mb-1" id="kpiTasksCompleted">--</h3>
                        <h6 class="mb-0">Tasks Completed</h6>
                    </div>
                    <div class="text-muted fs-13 mt-2">
                        <span class="text-body fw-semibold me-1"><i class="ri-arrow-right-up-line fs-16 me-1"></i>12%</span>This week
                    </div>
                </div>
                <div><i class="ri-task-line display-6 fw-medium text-muted opacity-50"></i></div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card card-hover overflow-hidden border-warning border-3 border-bottom">
            <div class="card-body p-4 d-flex align-items-start gap-3">
                <div class="flex-fill d-flex flex-column justify-content-between">
                    <div>
                        <h3 class="fw-semibold mb-1" id="kpiAvgResponse">--</h3>
                        <h6 class="mb-0">Avg Response Time</h6>
                    </div>
                    <div class="text-muted fs-13 mt-2">
                        <span class="text-body fw-semibold me-1"><i class="ri-arrow-right-down-line fs-16 me-1 text-success"></i>18%</span>Improved
                    </div>
                </div>
                <div><i class="ri-time-line display-6 fw-medium text-muted opacity-50"></i></div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card card-hover overflow-hidden border-info border-3 border-bottom">
            <div class="card-body p-4 d-flex align-items-start gap-3">
                <div class="flex-fill d-flex flex-column justify-content-between">
                    <div>
                        <h3 class="fw-semibold mb-1" id="kpiRating">--</h3>
                        <h6 class="mb-0">Client Satisfaction</h6>
                    </div>
                    <div class="text-muted fs-13 mt-2">
                        <span class="text-body fw-semibold me-1"><i class="ri-star-fill fs-16 me-1 text-warning"></i></span>Based on <span id="kpiReviewCount">--</span> reviews
                    </div>
                </div>
                <div><i class="ri-emotion-happy-line display-6 fw-medium text-muted opacity-50"></i></div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Team Performance Chart -->
    <div class="col-xl-8">
        <div class="card card-h-100">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h5 class="card-title mb-0">Team Performance</h5>
                <div class="dropdown">
                    <button class="btn btn-sm btn-subtle-primary" data-bs-toggle="dropdown"><i class="ri-more-2-fill"></i></button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="#">This Week</a></li>
                        <li><a class="dropdown-item" href="#">This Month</a></li>
                        <li><a class="dropdown-item" href="#">This Quarter</a></li>
                    </ul>
                </div>
            </div>
            <div class="card-body">
                <div id="teamPerformanceChart" style="height: 350px;"></div>
            </div>
        </div>
    </div>

    <!-- Leaderboard -->
    <div class="col-xl-4">
        <div class="card card-h-100">
            <div class="card-header">
                <h5 class="card-title mb-0">Leaderboard</h5>
            </div>
            <div class="card-body">
                <div class="d-flex flex-column gap-3" id="leaderboardContainer">
                    <div class="text-center text-muted py-4">Loading...</div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Individual KPIs Table -->
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h5 class="card-title mb-0">Individual KPIs</h5>
                <div class="dropdown">
                    <button class="btn btn-sm btn-subtle-primary" data-bs-toggle="dropdown"><i class="ri-download-2-line me-1"></i>Export</button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="#">Export CSV</a></li>
                        <li><a class="dropdown-item" href="#">Export PDF</a></li>
                    </ul>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>User</th>
                                <th>Role</th>
                                <th class="text-center">Cases Active</th>
                                <th class="text-center">Cases Closed (month)</th>
                                <th class="text-center">Leads Converted</th>
                                <th class="text-center">Tasks Done</th>
                                <th class="text-center">Tasks Overdue</th>
                                <th class="text-center">Points</th>
                            </tr>
                        </thead>
                        <tbody id="kpisTableBody">
                            <tr>
                                <td colspan="8" class="text-center text-muted py-4">Loading...</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('js')
<script src="{{ asset('assets/libs/apexcharts/apexcharts.min.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    var token = localStorage.getItem('wc_token');
    var avatarColors = [
        'bg-primary-subtle text-primary',
        'bg-success-subtle text-success',
        'bg-info-subtle text-info',
        'bg-warning-subtle text-warning',
        'bg-danger-subtle text-danger'
    ];

    fetch('/api/v1/analytics/performance', {
        headers: {
            'Accept': 'application/json',
            'Authorization': 'Bearer ' + token
        }
    })
    .then(function (res) { return res.json(); })
    .then(function (json) {
        if (!json.success || !json.data) return;

        var kpi = json.data.kpi;
        var managers = json.data.managers || [];

        // --- KPI Cards ---
        document.getElementById('kpiActiveUsers').textContent = kpi.active_users;
        document.getElementById('kpiOnlineNow').textContent = kpi.active_users;
        document.getElementById('kpiTasksCompleted').textContent = kpi.tasks_completed;
        document.getElementById('kpiAvgResponse').textContent = kpi.avg_response_hours + 'h';
        document.getElementById('kpiRating').textContent = kpi.avg_rating + '/5';
        document.getElementById('kpiReviewCount').textContent = kpi.review_count;

        // --- Leaderboard (top 4 by points) ---
        var sorted = managers.slice().sort(function (a, b) { return b.points - a.points; });
        var top4 = sorted.slice(0, 4);
        var container = document.getElementById('leaderboardContainer');
        container.innerHTML = '';

        var badgeConfigs = [
            { label: '1st', cls: 'bg-warning text-dark' },
            { label: '2nd', cls: 'bg-secondary' },
            { label: '3rd', cls: 'bg-danger-subtle text-danger' }
        ];

        top4.forEach(function (m, i) {
            var colorClass = avatarColors[i % avatarColors.length];
            var rowBg = i === 0 ? 'bg-warning-subtle' : (i < 3 ? 'bg-light' : '');
            var pointsColor = i === 0 ? 'text-warning' : (i === 1 ? 'text-secondary' : '');
            var badgeHtml = '';
            if (i < 3) {
                badgeHtml = '<span class="position-absolute top-0 start-100 translate-middle badge rounded-pill ' + badgeConfigs[i].cls + '" style="font-size: 10px;">' + badgeConfigs[i].label + '</span>';
            }

            var roleLabel = m.role === 'boss' ? 'Boss' : (m.role === 'staff' ? 'Staff' : 'Client');
            var pointsFormatted = m.points.toLocaleString();

            container.innerHTML += '<div class="d-flex align-items-center gap-3 p-3 rounded ' + rowBg + '">' +
                '<div class="position-relative">' +
                    '<div class="avatar avatar-sm avatar-rounded ' + colorClass + '">' +
                        '<span>' + m.initials + '</span>' +
                    '</div>' +
                    badgeHtml +
                '</div>' +
                '<div class="flex-grow-1">' +
                    '<h6 class="mb-0 fw-semibold">' + m.name + '</h6>' +
                    '<span class="text-muted fs-12">' + roleLabel + '</span>' +
                '</div>' +
                '<div class="text-end">' +
                    '<h5 class="mb-0 fw-bold ' + pointsColor + '">' + pointsFormatted + '</h5>' +
                    '<span class="text-muted fs-12">points</span>' +
                '</div>' +
            '</div>';
        });

        // --- Individual KPIs Table ---
        var tbody = document.getElementById('kpisTableBody');
        tbody.innerHTML = '';

        managers.forEach(function (m, i) {
            var colorClass = avatarColors[i % avatarColors.length];
            var roleLabel = m.role === 'boss' ? 'Boss' : (m.role === 'staff' ? 'Staff' : 'Client');
            var roleBadgeCls = m.role === 'boss' ? 'bg-primary-subtle text-primary' : (m.role === 'staff' ? 'bg-success-subtle text-success' : 'bg-info-subtle text-info');

            var overdueBadgeCls = 'bg-success-subtle text-success';
            if (m.tasks_overdue > 2) {
                overdueBadgeCls = 'bg-danger-subtle text-danger';
            } else if (m.tasks_overdue > 0) {
                overdueBadgeCls = 'bg-warning-subtle text-warning';
            }

            tbody.innerHTML += '<tr>' +
                '<td>' +
                    '<div class="d-flex align-items-center gap-2">' +
                        '<div class="avatar avatar-xs avatar-rounded ' + colorClass + '"><span>' + m.initials + '</span></div>' +
                        '<span class="fw-semibold">' + m.name + '</span>' +
                    '</div>' +
                '</td>' +
                '<td><span class="badge ' + roleBadgeCls + '">' + roleLabel + '</span></td>' +
                '<td class="text-center">' + m.cases_active + '</td>' +
                '<td class="text-center fw-semibold">' + m.cases_closed + '</td>' +
                '<td class="text-center">' + m.leads_converted + '</td>' +
                '<td class="text-center fw-semibold">' + m.tasks_done + '</td>' +
                '<td class="text-center"><span class="badge ' + overdueBadgeCls + '">' + m.tasks_overdue + '</span></td>' +
                '<td class="text-center fw-semibold">' + m.points.toLocaleString() + '</td>' +
            '</tr>';
        });

        // --- Team Performance Chart ---
        var names = managers.map(function (m) { return m.name; });
        var casesClosed = managers.map(function (m) { return m.cases_closed; });
        var tasksDone = managers.map(function (m) { return m.tasks_done; });
        var leadsConverted = managers.map(function (m) { return m.leads_converted; });

        var teamOptions = {
            chart: { type: 'bar', height: 350, toolbar: { show: false } },
            series: [
                { name: 'Cases Closed', data: casesClosed },
                { name: 'Tasks Done', data: tasksDone },
                { name: 'Leads Converted', data: leadsConverted }
            ],
            xaxis: { categories: names },
            colors: ['#3b82f6', '#10b981', '#f59e0b'],
            plotOptions: { bar: { borderRadius: 4, columnWidth: '55%' } },
            dataLabels: { enabled: false },
            grid: { borderColor: '#f1f1f1' },
            legend: { position: 'top' },
            tooltip: { shared: true, intersect: false }
        };
        new ApexCharts(document.querySelector('#teamPerformanceChart'), teamOptions).render();
    })
    .catch(function (err) {
        console.error('Performance API error:', err);
    });
});
</script>
@endsection
