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
                        <h3 class="fw-semibold mb-1">6</h3>
                        <h6 class="mb-0">Active Users</h6>
                    </div>
                    <div class="text-muted fs-13 mt-2">
                        <span class="text-body fw-semibold me-1"><i class="ri-team-line fs-16 me-1"></i>4</span>Online now
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
                        <h3 class="fw-semibold mb-1">48</h3>
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
                        <h3 class="fw-semibold mb-1">2.4h</h3>
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
                        <h3 class="fw-semibold mb-1">4.7/5</h3>
                        <h6 class="mb-0">Client Satisfaction</h6>
                    </div>
                    <div class="text-muted fs-13 mt-2">
                        <span class="text-body fw-semibold me-1"><i class="ri-star-fill fs-16 me-1 text-warning"></i></span>Based on 84 reviews
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
                <div class="d-flex flex-column gap-3">
                    <!-- 1st Place -->
                    <div class="d-flex align-items-center gap-3 p-3 rounded bg-warning-subtle">
                        <div class="position-relative">
                            <div class="avatar avatar-sm avatar-rounded bg-primary text-white">
                                <span>JN</span>
                            </div>
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-warning text-dark" style="font-size: 10px;">1st</span>
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="mb-0 fw-semibold">Jan Nowak</h6>
                            <span class="text-muted fs-12">Senior Manager</span>
                        </div>
                        <div class="text-end">
                            <h5 class="mb-0 fw-bold text-warning">1,240</h5>
                            <span class="text-muted fs-12">points</span>
                        </div>
                    </div>

                    <!-- 2nd Place -->
                    <div class="d-flex align-items-center gap-3 p-3 rounded bg-light">
                        <div class="position-relative">
                            <div class="avatar avatar-sm avatar-rounded bg-success text-white">
                                <span>AW</span>
                            </div>
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-secondary" style="font-size: 10px;">2nd</span>
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="mb-0 fw-semibold">Anna Wisniewska</h6>
                            <span class="text-muted fs-12">Case Manager</span>
                        </div>
                        <div class="text-end">
                            <h5 class="mb-0 fw-bold text-secondary">1,085</h5>
                            <span class="text-muted fs-12">points</span>
                        </div>
                    </div>

                    <!-- 3rd Place -->
                    <div class="d-flex align-items-center gap-3 p-3 rounded bg-light">
                        <div class="position-relative">
                            <div class="avatar avatar-sm avatar-rounded bg-info text-white">
                                <span>PK</span>
                            </div>
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger-subtle text-danger" style="font-size: 10px;">3rd</span>
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="mb-0 fw-semibold">Piotr Kowalczyk</h6>
                            <span class="text-muted fs-12">Accountant</span>
                        </div>
                        <div class="text-end">
                            <h5 class="mb-0 fw-bold">920</h5>
                            <span class="text-muted fs-12">points</span>
                        </div>
                    </div>

                    <!-- 4th Place -->
                    <div class="d-flex align-items-center gap-3 p-3 rounded">
                        <div class="position-relative">
                            <div class="avatar avatar-sm avatar-rounded bg-warning text-white">
                                <span>KZ</span>
                            </div>
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="mb-0 fw-semibold">Katarzyna Zielinska</h6>
                            <span class="text-muted fs-12">Junior Manager</span>
                        </div>
                        <div class="text-end">
                            <h5 class="mb-0 fw-bold">780</h5>
                            <span class="text-muted fs-12">points</span>
                        </div>
                    </div>
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
                                <th class="text-center">Avg Case Duration (days)</th>
                                <th class="text-center">Tasks Overdue</th>
                                <th class="text-center">Response Time</th>
                                <th class="text-center">Rating</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="avatar avatar-xs avatar-rounded bg-primary-subtle text-primary"><span>JN</span></div>
                                        <span class="fw-semibold">Jan Nowak</span>
                                    </div>
                                </td>
                                <td><span class="badge bg-primary-subtle text-primary">Manager</span></td>
                                <td class="text-center">12</td>
                                <td class="text-center fw-semibold">8</td>
                                <td class="text-center">14</td>
                                <td class="text-center"><span class="badge bg-danger-subtle text-danger">2</span></td>
                                <td class="text-center">1.8h</td>
                                <td class="text-center">
                                    <span class="text-warning"><i class="ri-star-fill"></i></span> 4.8
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="avatar avatar-xs avatar-rounded bg-success-subtle text-success"><span>AW</span></div>
                                        <span class="fw-semibold">Anna Wisniewska</span>
                                    </div>
                                </td>
                                <td><span class="badge bg-primary-subtle text-primary">Manager</span></td>
                                <td class="text-center">10</td>
                                <td class="text-center fw-semibold">7</td>
                                <td class="text-center">18</td>
                                <td class="text-center"><span class="badge bg-warning-subtle text-warning">1</span></td>
                                <td class="text-center">2.1h</td>
                                <td class="text-center">
                                    <span class="text-warning"><i class="ri-star-fill"></i></span> 4.9
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="avatar avatar-xs avatar-rounded bg-info-subtle text-info"><span>PK</span></div>
                                        <span class="fw-semibold">Piotr Kowalczyk</span>
                                    </div>
                                </td>
                                <td><span class="badge bg-success-subtle text-success">Accountant</span></td>
                                <td class="text-center">5</td>
                                <td class="text-center fw-semibold">6</td>
                                <td class="text-center">10</td>
                                <td class="text-center"><span class="badge bg-success-subtle text-success">0</span></td>
                                <td class="text-center">3.2h</td>
                                <td class="text-center">
                                    <span class="text-warning"><i class="ri-star-fill"></i></span> 4.5
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="avatar avatar-xs avatar-rounded bg-warning-subtle text-warning"><span>KZ</span></div>
                                        <span class="fw-semibold">Katarzyna Zielinska</span>
                                    </div>
                                </td>
                                <td><span class="badge bg-info-subtle text-info">Junior Manager</span></td>
                                <td class="text-center">7</td>
                                <td class="text-center fw-semibold">4</td>
                                <td class="text-center">22</td>
                                <td class="text-center"><span class="badge bg-danger-subtle text-danger">3</span></td>
                                <td class="text-center">3.5h</td>
                                <td class="text-center">
                                    <span class="text-warning"><i class="ri-star-fill"></i></span> 4.3
                                </td>
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
// Team Performance — Grouped Bar Chart
var teamOptions = {
    chart: { type: 'bar', height: 350, toolbar: { show: false } },
    series: [
        { name: 'Cases Closed', data: [8, 7, 6, 4] },
        { name: 'Tasks Done', data: [15, 14, 10, 9] },
        { name: 'Leads Converted', data: [5, 4, 2, 3] }
    ],
    xaxis: { categories: ['Jan Nowak', 'Anna Wisniewska', 'Piotr Kowalczyk', 'Katarzyna Zielinska'] },
    colors: ['#3b82f6', '#10b981', '#f59e0b'],
    plotOptions: { bar: { borderRadius: 4, columnWidth: '55%' } },
    dataLabels: { enabled: false },
    grid: { borderColor: '#f1f1f1' },
    legend: { position: 'top' },
    tooltip: { shared: true, intersect: false }
};
new ApexCharts(document.querySelector("#teamPerformanceChart"), teamOptions).render();
</script>
@endsection
