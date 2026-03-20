@extends('partials.layouts.master')

@section('title', 'Dashboard | WinCase CRM')
@section('sub-title', 'Overview')
@section('sub-title-lang', 'wc-overview')
@section('pagetitle', 'Dashboard')
@section('pagetitle-lang', 'wc-dashboard')

@section('css')
<link rel="stylesheet" href="{{ asset('assets/libs/air-datepicker/air-datepicker.css') }}">
@endsection

@section('content')
<div class="crm-dashboard">

    <!-- KPI Cards -->
    <div class="row">
        <div class="col">
            <div class="card card-hover card-h-100 overflow-hidden border-primary border-3 border-bottom">
                <div class="card-body p-4 d-flex align-items-start gap-3 h-100">
                    <div class="flex-fill h-100 d-flex flex-column justify-content-between">
                        <div>
                            <h3 class="fw-semibold mb-1">47</h3>
                            <h6 class="mb-0" data-lang="wc-new-leads-kpi">New Leads</h6>
                        </div>
                        <div class="text-muted fs-13 mt-2">
                            <span class="text-success fw-semibold me-1"><i class="ri-arrow-right-up-line fs-16 me-1"></i>12%</span><span data-lang="wc-this-week">This week</span>
                        </div>
                    </div>
                    <div><i class="ri-user-follow-line display-6 fw-medium text-muted opacity-50"></i></div>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card card-hover card-h-100 overflow-hidden border-success border-3 border-bottom">
                <div class="card-body p-4 d-flex align-items-start gap-3 h-100">
                    <div class="flex-fill h-100 d-flex flex-column justify-content-between">
                        <div>
                            <h3 class="fw-semibold mb-1">156</h3>
                            <h6 class="mb-0" data-lang="wc-active-clients-kpi">Active Clients</h6>
                        </div>
                        <div class="text-muted fs-13 mt-2">
                            <span class="text-success fw-semibold me-1"><i class="ri-arrow-right-up-line fs-16 me-1"></i>8%</span><span data-lang="wc-this-month">This month</span>
                        </div>
                    </div>
                    <div><i class="ri-group-line display-6 fw-medium text-muted opacity-50"></i></div>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card card-hover card-h-100 overflow-hidden border-warning border-3 border-bottom">
                <div class="card-body p-4 d-flex align-items-start gap-3 h-100">
                    <div class="flex-fill h-100 d-flex flex-column justify-content-between">
                        <div>
                            <h3 class="fw-semibold mb-1">34</h3>
                            <h6 class="mb-0" data-lang="wc-open-cases-kpi">Open Cases</h6>
                        </div>
                        <div class="text-muted fs-13 mt-2">
                            <span class="text-danger fw-semibold me-1"><i class="ri-arrow-right-down-line fs-16 me-1"></i>3%</span><span data-lang="wc-this-week">This week</span>
                        </div>
                    </div>
                    <div><i class="ri-briefcase-line display-6 fw-medium text-muted opacity-50"></i></div>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card card-hover card-h-100 overflow-hidden border-info border-3 border-bottom">
                <div class="card-body p-4 d-flex align-items-start gap-3 h-100">
                    <div class="flex-fill h-100 d-flex flex-column justify-content-between">
                        <div>
                            <h3 class="fw-semibold mb-1">12</h3>
                            <h6 class="mb-0" data-lang="wc-tasks-due-today">Tasks Due Today</h6>
                        </div>
                        <div class="text-muted fs-13 mt-2">
                            <span class="text-danger fw-semibold me-1"><i class="ri-time-line fs-16 me-1"></i>5</span><span data-lang="wc-overdue">Overdue</span>
                        </div>
                    </div>
                    <div><i class="ri-task-line display-6 fw-medium text-muted opacity-50"></i></div>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card card-hover card-h-100 overflow-hidden border-danger border-3 border-bottom">
                <div class="card-body p-4 d-flex align-items-start gap-3 h-100">
                    <div class="flex-fill h-100 d-flex flex-column justify-content-between">
                        <div>
                            <h3 class="fw-semibold mb-1">98 240 PLN</h3>
                            <h6 class="mb-0" data-lang="wc-revenue-mtd">Revenue (MTD)</h6>
                        </div>
                        <div class="text-muted fs-13 mt-2">
                            <span class="text-success fw-semibold me-1"><i class="ri-arrow-right-up-line fs-16 me-1"></i>18%</span><span data-lang="wc-vs-last-month">vs last month</span>
                        </div>
                    </div>
                    <div><i class="ri-money-euro-circle-line display-6 fw-medium text-muted opacity-50"></i></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Case Pipeline Mini -->
    <div class="card">
        <div class="card-header d-flex align-items-center justify-content-between">
            <h5 class="card-title mb-0"><i class="ri-flow-chart me-2"></i><span data-lang="wc-case-pipeline">Case Pipeline</span></h5>
            <a href="/crm-cases" class="btn btn-sm btn-subtle-primary" data-lang="wc-view-cases">View Cases</a>
        </div>
        <div class="card-body py-3">
            <div class="row text-center g-2">
                <div class="col"><div class="bg-primary-subtle rounded p-2"><h5 class="mb-0 text-primary">8</h5><small class="text-muted fs-11" data-lang="wc-stage-submitted">Submitted</small></div></div>
                <div class="col"><div class="bg-info-subtle rounded p-2"><h5 class="mb-0 text-info">5</h5><small class="text-muted fs-11" data-lang="wc-stage-await-fingerpr">Await Fingerpr.</small></div></div>
                <div class="col"><div class="bg-warning-subtle rounded p-2"><h5 class="mb-0 text-warning">4</h5><small class="text-muted fs-11" data-lang="wc-stage-fingerpr-appt">Fingerpr. Appt.</small></div></div>
                <div class="col"><div class="bg-secondary-subtle rounded p-2"><h5 class="mb-0">6</h5><small class="text-muted fs-11" data-lang="wc-stage-fingerpr-done">Fingerpr. Done</small></div></div>
                <div class="col"><div class="bg-purple-subtle rounded p-2" style="background:rgba(139,92,246,.1)"><h5 class="mb-0" style="color:#8b5cf6">7</h5><small class="text-muted fs-11" data-lang="wc-stage-await-decision">Await Decision</small></div></div>
                <div class="col"><div class="bg-success-subtle rounded p-2"><h5 class="mb-0 text-success">3</h5><small class="text-muted fs-11" data-lang="wc-stage-decision-signed">Decision Signed</small></div></div>
                <div class="col"><div class="rounded p-2" style="background:rgba(16,185,129,.15)"><h5 class="mb-0" style="color:#059669">1</h5><small class="text-muted fs-11" data-lang="wc-stage-card-issued">Card Issued</small></div></div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Lead Funnel -->
        <div class="col-xl-4">
            <div class="card card-h-100">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="card-title mb-0" data-lang="wc-lead-funnel">Lead Funnel</h5>
                    <div class="dropdown">
                        <button class="btn btn-sm btn-subtle-primary" data-bs-toggle="dropdown"><i class="ri-more-2-fill"></i></button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="#" data-lang="wc-this-week">This Week</a></li>
                            <li><a class="dropdown-item" href="#" data-lang="wc-this-month">This Month</a></li>
                            <li><a class="dropdown-item" href="#" data-lang="wc-this-year">This Year</a></li>
                        </ul>
                    </div>
                </div>
                <div class="card-body">
                    <div id="leadFunnelChart"></div>
                    <div class="mt-3">
                        <div class="d-flex justify-content-between mb-2"><span class="text-muted fs-13" data-lang="wc-funnel-new">New</span><span class="fw-semibold">47</span></div>
                        <div class="d-flex justify-content-between mb-2"><span class="text-muted fs-13" data-lang="wc-funnel-contacted">Contacted</span><span class="fw-semibold">32</span></div>
                        <div class="d-flex justify-content-between mb-2"><span class="text-muted fs-13" data-lang="wc-funnel-consultation">Consultation</span><span class="fw-semibold">18</span></div>
                        <div class="d-flex justify-content-between mb-2"><span class="text-muted fs-13" data-lang="wc-funnel-converted">Converted</span><span class="fw-semibold text-success">12</span></div>
                        <div class="d-flex justify-content-between"><span class="text-muted fs-13" data-lang="wc-conversion-rate">Conversion Rate</span><span class="fw-semibold text-primary">25.5%</span></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Leads by Source -->
        <div class="col-xl-4">
            <div class="card card-h-100">
                <div class="card-header"><h5 class="card-title mb-0" data-lang="wc-leads-by-source">Leads by Source</h5></div>
                <div class="card-body"><div id="leadsBySourceChart"></div></div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="col-xl-4">
            <div class="card card-h-100">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="card-title mb-0" data-lang="wc-recent-activity">Recent Activity</h5>
                    <a href="/crm-tasks" class="btn btn-sm btn-subtle-primary" data-lang="wc-view-all">View All</a>
                </div>
                <div class="card-body p-0">
                    <div data-simplebar style="max-height: 400px;">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex align-items-start gap-3">
                                <div class="avatar avatar-xs avatar-rounded bg-primary-subtle text-primary flex-shrink-0 mt-1"><i class="ri-user-add-line"></i></div>
                                <div class="flex-grow-1">
                                    <p class="mb-1 fs-13">New lead <a href="/crm-leads" class="fw-semibold">Olena Kovalenko</a> from Facebook Ads</p>
                                    <span class="text-muted fs-12">5 min ago</span>
                                </div>
                            </li>
                            <li class="list-group-item d-flex align-items-start gap-3">
                                <div class="avatar avatar-xs avatar-rounded bg-success-subtle text-success flex-shrink-0 mt-1"><i class="ri-check-double-line"></i></div>
                                <div class="flex-grow-1">
                                    <p class="mb-1 fs-13">Case <a href="/crm-cases" class="fw-semibold">#WC-2026-0189</a> advanced to <span class="badge bg-success-subtle text-success">Decision Signed</span></p>
                                    <span class="text-muted fs-12">22 min ago</span>
                                </div>
                            </li>
                            <li class="list-group-item d-flex align-items-start gap-3">
                                <div class="avatar avatar-xs avatar-rounded bg-warning-subtle text-warning flex-shrink-0 mt-1"><i class="ri-money-euro-circle-line"></i></div>
                                <div class="flex-grow-1">
                                    <p class="mb-1 fs-13">Payment <strong>4 800 PLN</strong> received from <a href="/crm-clients" class="fw-semibold">Ahmed Al-Rashid</a></p>
                                    <span class="text-muted fs-12">1 hour ago</span>
                                </div>
                            </li>
                            <li class="list-group-item d-flex align-items-start gap-3">
                                <div class="avatar avatar-xs avatar-rounded bg-info-subtle text-info flex-shrink-0 mt-1"><i class="ri-calendar-check-line"></i></div>
                                <div class="flex-grow-1">
                                    <p class="mb-1 fs-13">Consultation scheduled with <strong>Nguyen Van Tuan</strong> for tomorrow 10:00</p>
                                    <span class="text-muted fs-12">2 hours ago</span>
                                </div>
                            </li>
                            <li class="list-group-item d-flex align-items-start gap-3">
                                <div class="avatar avatar-xs avatar-rounded bg-danger-subtle text-danger flex-shrink-0 mt-1"><i class="ri-file-warning-line"></i></div>
                                <div class="flex-grow-1">
                                    <p class="mb-1 fs-13">Work permit expires in <strong class="text-danger">7 days</strong> for <strong>Igor Petrov</strong></p>
                                    <span class="text-muted fs-12">3 hours ago</span>
                                </div>
                            </li>
                            <li class="list-group-item d-flex align-items-start gap-3">
                                <div class="avatar avatar-xs avatar-rounded bg-primary-subtle text-primary flex-shrink-0 mt-1"><i class="ri-user-follow-line"></i></div>
                                <div class="flex-grow-1">
                                    <p class="mb-1 fs-13">Lead <strong>Li Wei</strong> converted to client → Case <strong>#WC-2026-0192</strong></p>
                                    <span class="text-muted fs-12">5 hours ago</span>
                                </div>
                            </li>
                            <li class="list-group-item d-flex align-items-start gap-3">
                                <div class="avatar avatar-xs avatar-rounded bg-success-subtle text-success flex-shrink-0 mt-1"><i class="ri-file-check-line"></i></div>
                                <div class="flex-grow-1">
                                    <p class="mb-1 fs-13">Invoice <strong>FV/2026/03/012</strong> paid by <strong>Maria Santos</strong> — 3 500 PLN</p>
                                    <span class="text-muted fs-12">6 hours ago</span>
                                </div>
                            </li>
                            <li class="list-group-item d-flex align-items-start gap-3">
                                <div class="avatar avatar-xs avatar-rounded bg-warning-subtle text-warning flex-shrink-0 mt-1"><i class="ri-fingerprint-line"></i></div>
                                <div class="flex-grow-1">
                                    <p class="mb-1 fs-13"><strong>Fatima Hassan</strong> fingerprint appointment confirmed — <strong>Mar 5, 09:00</strong></p>
                                    <span class="text-muted fs-12">Yesterday</span>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Revenue Chart -->
        <div class="col-xl-8">
            <div class="card">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="card-title mb-0" data-lang="wc-revenue-overview">Revenue Overview</h5>
                    <div class="d-flex gap-2">
                        <button class="btn btn-sm btn-subtle-primary active revenue-range" data-range="month" data-lang="wc-month">Month</button>
                        <button class="btn btn-sm btn-subtle-primary revenue-range" data-range="quarter" data-lang="wc-quarter">Quarter</button>
                        <button class="btn btn-sm btn-subtle-primary revenue-range" data-range="year" data-lang="wc-year">Year</button>
                    </div>
                </div>
                <div class="card-body">
                    <div id="revenueOverviewChart" style="height: 350px;"></div>
                </div>
            </div>
        </div>

        <!-- Upcoming Tasks -->
        <div class="col-xl-4">
            <div class="card card-h-100">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="card-title mb-0" data-lang="wc-upcoming-tasks">Upcoming Tasks</h5>
                    <a href="/crm-tasks" class="btn btn-sm btn-subtle-primary" data-lang="wc-view-all">View All</a>
                </div>
                <div class="card-body p-0">
                    <div data-simplebar style="max-height: 370px;">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="form-check"><input class="form-check-input" type="checkbox"></div>
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1 fs-13">Prepare documents for Kovalenko case</h6>
                                        <div class="d-flex gap-2">
                                            <span class="badge bg-danger-subtle text-danger">High</span>
                                            <span class="text-muted fs-12">Due: Today</span>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li class="list-group-item">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="form-check"><input class="form-check-input" type="checkbox"></div>
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1 fs-13">Submit Al-Rashid work permit application</h6>
                                        <div class="d-flex gap-2">
                                            <span class="badge bg-danger-subtle text-danger">High</span>
                                            <span class="text-muted fs-12">Due: Today</span>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li class="list-group-item">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="form-check"><input class="form-check-input" type="checkbox"></div>
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1 fs-13">Follow up with lead Raj Patel</h6>
                                        <div class="d-flex gap-2">
                                            <span class="badge bg-warning-subtle text-warning">Medium</span>
                                            <span class="text-muted fs-12">Due: Mar 3</span>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li class="list-group-item">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="form-check"><input class="form-check-input" type="checkbox"></div>
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1 fs-13">Nguyen Van Tuan — consultation 10:00</h6>
                                        <div class="d-flex gap-2">
                                            <span class="badge bg-info-subtle text-info">Meeting</span>
                                            <span class="text-muted fs-12">Due: Mar 3</span>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li class="list-group-item">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="form-check"><input class="form-check-input" type="checkbox"></div>
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1 fs-13">Review monthly invoices report</h6>
                                        <div class="d-flex gap-2">
                                            <span class="badge bg-info-subtle text-info">Low</span>
                                            <span class="text-muted fs-12">Due: Mar 5</span>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li class="list-group-item">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="form-check"><input class="form-check-input" type="checkbox"></div>
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1 fs-13">Petrov — fingerprint appointment follow-up</h6>
                                        <div class="d-flex gap-2">
                                            <span class="badge bg-warning-subtle text-warning">Medium</span>
                                            <span class="text-muted fs-12">Due: Mar 6</span>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Finance Quick Summary -->
    <div class="row">
        <div class="col-xl-3 col-md-6">
            <div class="card card-h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="avatar avatar-sm bg-success-subtle text-success rounded-2"><i class="ri-money-euro-circle-line fs-18"></i></div>
                        <h6 class="card-title mb-0" data-lang="wc-payments-this-month">Payments This Month</h6>
                    </div>
                    <h4 class="fw-semibold text-success">72 400 PLN</h4>
                    <div class="progress mt-2" style="height:4px"><div class="progress-bar bg-success" style="width:74%"></div></div>
                    <small class="text-muted" data-lang="wc-invoiced-pct">74% of 98 240 PLN invoiced</small>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card card-h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="avatar avatar-sm bg-warning-subtle text-warning rounded-2"><i class="ri-file-list-3-line fs-18"></i></div>
                        <h6 class="card-title mb-0" data-lang="wc-pending-invoices">Pending Invoices</h6>
                    </div>
                    <h4 class="fw-semibold text-warning">25 840 PLN</h4>
                    <div class="d-flex justify-content-between mt-2">
                        <small class="text-muted" data-lang="wc-n-invoices">8 invoices</small>
                        <a href="/finance-invoices" class="text-primary fs-13"><span data-lang="wc-view">View</span> <i class="ri-arrow-right-s-line"></i></a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card card-h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="avatar avatar-sm bg-danger-subtle text-danger rounded-2"><i class="ri-error-warning-line fs-18"></i></div>
                        <h6 class="card-title mb-0" data-lang="wc-overdue">Overdue</h6>
                    </div>
                    <h4 class="fw-semibold text-danger">6 200 PLN</h4>
                    <div class="d-flex justify-content-between mt-2">
                        <small class="text-muted" data-lang="wc-invoices-overdue">3 invoices overdue</small>
                        <a href="/finance-invoices" class="text-danger fs-13"><span data-lang="wc-action">Action</span> <i class="ri-arrow-right-s-line"></i></a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card card-h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="avatar avatar-sm bg-info-subtle text-info rounded-2"><i class="ri-bank-card-line fs-18"></i></div>
                        <h6 class="card-title mb-0" data-lang="wc-expenses-mtd">Expenses (MTD)</h6>
                    </div>
                    <h4 class="fw-semibold">18 450 PLN</h4>
                    <div class="progress mt-2" style="height:4px"><div class="progress-bar bg-info" style="width:74%"></div></div>
                    <small class="text-muted" data-lang="wc-budget-pct">74% of 25 000 PLN budget</small>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Recent Leads Table -->
        <div class="col-xl-6">
            <div class="card">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="card-title mb-0" data-lang="wc-recent-leads">Recent Leads</h5>
                    <a href="/crm-leads" class="btn btn-sm btn-subtle-primary" data-lang="wc-view-all">View All</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th data-lang="wc-th-name">Name</th>
                                    <th data-lang="wc-th-source">Source</th>
                                    <th data-lang="wc-th-service">Service</th>
                                    <th data-lang="wc-th-status">Status</th>
                                    <th data-lang="wc-th-date">Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><a href="/crm-leads" class="fw-semibold text-body">Olena Kovalenko</a></td>
                                    <td><span class="badge bg-primary-subtle text-primary">Facebook</span></td>
                                    <td>Karta pobytu</td>
                                    <td><span class="badge bg-warning-subtle text-warning">New</span></td>
                                    <td class="text-muted fs-12">Mar 2</td>
                                </tr>
                                <tr>
                                    <td><a href="/crm-leads" class="fw-semibold text-body">Ahmed Al-Rashid</a></td>
                                    <td><span class="badge bg-success-subtle text-success">Google Ads</span></td>
                                    <td>Pozwolenie na prace</td>
                                    <td><span class="badge bg-primary-subtle text-primary">Contacted</span></td>
                                    <td class="text-muted fs-12">Mar 1</td>
                                </tr>
                                <tr>
                                    <td><a href="/crm-leads" class="fw-semibold text-body">Raj Patel</a></td>
                                    <td><span class="badge bg-danger-subtle text-danger">TikTok</span></td>
                                    <td>Pozwolenie na prace</td>
                                    <td><span class="badge bg-warning-subtle text-warning">New</span></td>
                                    <td class="text-muted fs-12">Mar 1</td>
                                </tr>
                                <tr>
                                    <td><a href="/crm-leads" class="fw-semibold text-body">Nguyen Van Tuan</a></td>
                                    <td><span class="badge bg-info-subtle text-info">Website</span></td>
                                    <td>Karta pobytu</td>
                                    <td><span class="badge bg-success-subtle text-success">Consultation</span></td>
                                    <td class="text-muted fs-12">Feb 28</td>
                                </tr>
                                <tr>
                                    <td><a href="/crm-leads" class="fw-semibold text-body">Li Wei</a></td>
                                    <td><span class="badge bg-warning-subtle text-warning">Referral</span></td>
                                    <td>Rejestracja firmy</td>
                                    <td><span class="badge bg-primary-subtle text-primary">Contacted</span></td>
                                    <td class="text-muted fs-12">Feb 27</td>
                                </tr>
                                <tr>
                                    <td><a href="/crm-leads" class="fw-semibold text-body">Carlos Rodriguez</a></td>
                                    <td><span class="badge bg-info-subtle text-info">LinkedIn</span></td>
                                    <td>EU Blue Card</td>
                                    <td><span class="badge bg-warning-subtle text-warning">New</span></td>
                                    <td class="text-muted fs-12">Feb 26</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Cases Overview -->
        <div class="col-xl-6">
            <div class="card">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="card-title mb-0" data-lang="wc-active-cases-title">Active Cases</h5>
                    <a href="/crm-cases" class="btn btn-sm btn-subtle-primary" data-lang="wc-view-all">View All</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th data-lang="wc-th-case">Case #</th>
                                    <th data-lang="wc-th-client">Client</th>
                                    <th data-lang="wc-th-type">Type</th>
                                    <th data-lang="wc-th-stage">Stage</th>
                                    <th data-lang="wc-th-manager">Manager</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><a href="/crm-cases" class="fw-semibold text-body">#WC-2026-0192</a></td>
                                    <td>Li Wei</td>
                                    <td>Rejestracja firmy</td>
                                    <td><span class="badge bg-primary-subtle text-primary">Submitted</span></td>
                                    <td>Anna Kowalska</td>
                                </tr>
                                <tr>
                                    <td><a href="/crm-cases" class="fw-semibold text-body">#WC-2026-0191</a></td>
                                    <td>Fatima Hassan</td>
                                    <td>Karta pobytu</td>
                                    <td><span class="badge bg-warning-subtle text-warning">Fingerprint Appt.</span></td>
                                    <td>Marek Wisniewski</td>
                                </tr>
                                <tr>
                                    <td><a href="/crm-cases" class="fw-semibold text-body">#WC-2026-0190</a></td>
                                    <td>Maria Santos</td>
                                    <td>Pozwolenie na prace</td>
                                    <td><span class="badge bg-info-subtle text-info">Awaiting Decision</span></td>
                                    <td>Piotr Zielinski</td>
                                </tr>
                                <tr>
                                    <td><a href="/crm-cases" class="fw-semibold text-body">#WC-2026-0189</a></td>
                                    <td>Ahmed Al-Rashid</td>
                                    <td>Pozwolenie na prace</td>
                                    <td><span class="badge bg-success-subtle text-success">Decision Signed</span></td>
                                    <td>Anna Kowalska</td>
                                </tr>
                                <tr>
                                    <td><a href="/crm-cases" class="fw-semibold text-body">#WC-2026-0188</a></td>
                                    <td>Igor Petrov</td>
                                    <td>Karta pobytu</td>
                                    <td><span class="badge bg-secondary-subtle text-secondary">Fingerpr. Submitted</span></td>
                                    <td>Marek Wisniewski</td>
                                </tr>
                                <tr>
                                    <td><a href="/crm-cases" class="fw-semibold text-body">#WC-2026-0187</a></td>
                                    <td>Olena Kovalenko</td>
                                    <td>Obywatelstwo</td>
                                    <td><span class="badge bg-danger-subtle text-danger">Awaiting Fingerpr.</span></td>
                                    <td>Piotr Zielinski</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Marketing Quick Stats -->
    <div class="card">
        <div class="card-header d-flex align-items-center justify-content-between">
            <h5 class="card-title mb-0"><i class="ri-megaphone-line me-2"></i><span data-lang="wc-marketing-stats">Marketing Quick Stats</span></h5>
            <div class="d-flex gap-2">
                <a href="/marketing-advertising" class="btn btn-sm btn-subtle-primary" data-lang="wc-ads">Ads</a>
                <a href="/marketing-seo" class="btn btn-sm btn-subtle-success">SEO</a>
                <a href="/marketing-social-media" class="btn btn-sm btn-subtle-info" data-lang="wc-social">Social</a>
            </div>
        </div>
        <div class="card-body py-3">
            <div class="row text-center g-3">
                <div class="col-xl-2 col-md-4 col-6">
                    <div class="border rounded p-3"><i class="ri-advertisement-line fs-24 text-primary mb-1 d-block"></i><h5 class="mb-0">12</h5><small class="text-muted" data-lang="wc-ad-campaigns">Ad Campaigns</small></div>
                </div>
                <div class="col-xl-2 col-md-4 col-6">
                    <div class="border rounded p-3"><i class="ri-money-euro-circle-line fs-24 text-warning mb-1 d-block"></i><h5 class="mb-0">8 450 PLN</h5><small class="text-muted" data-lang="wc-ad-spend-mtd">Ad Spend (MTD)</small></div>
                </div>
                <div class="col-xl-2 col-md-4 col-6">
                    <div class="border rounded p-3"><i class="ri-search-eye-line fs-24 text-success mb-1 d-block"></i><h5 class="mb-0">22</h5><small class="text-muted" data-lang="wc-keywords-top10">Keywords Top-10</small></div>
                </div>
                <div class="col-xl-2 col-md-4 col-6">
                    <div class="border rounded p-3"><i class="ri-global-line fs-24 text-info mb-1 d-block"></i><h5 class="mb-0">4 820</h5><small class="text-muted" data-lang="wc-organic-visits">Organic Visits</small></div>
                </div>
                <div class="col-xl-2 col-md-4 col-6">
                    <div class="border rounded p-3"><i class="ri-group-line fs-24 text-primary mb-1 d-block"></i><h5 class="mb-0">29.7K</h5><small class="text-muted" data-lang="wc-social-followers">Social Followers</small></div>
                </div>
                <div class="col-xl-2 col-md-4 col-6">
                    <div class="border rounded p-3"><i class="ri-pages-line fs-24 text-danger mb-1 d-block"></i><h5 class="mb-0">3.8%</h5><small class="text-muted" data-lang="wc-lp-conversion">LP Conversion</small></div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

@section('js')
<script src="{{ asset('assets/libs/apexcharts/apexcharts.min.js') }}"></script>
<script>
const API_BASE = '/api/v1';
const TOKEN = localStorage.getItem('wc_token');
const headers = { 'Accept': 'application/json', 'Authorization': 'Bearer ' + TOKEN };

async function api(url) {
    try {
        const r = await fetch(API_BASE + url, { headers });
        if (!r.ok) return null;
        const j = await r.json();
        return j.data || j;
    } catch(e) { return null; }
}

function fmt(n) { return n ? Number(n).toLocaleString('pl-PL') : '0'; }
function fmtPLN(n) { return fmt(n) + ' PLN'; }
function ago(d) {
    if (!d) return '';
    const diff = Math.floor((Date.now() - new Date(d)) / 60000);
    if (diff < 1) return 'Just now';
    if (diff < 60) return diff + ' min ago';
    if (diff < 1440) return Math.floor(diff/60) + 'h ago';
    return Math.floor(diff/1440) + 'd ago';
}
function badge(status) {
    const m = {new:'warning',contacted:'primary',consultation:'info',converted:'success',paid:'success',rejected:'danger',submitted:'primary',active:'success',in_progress:'info',pending:'warning',completed:'success',closed:'secondary'};
    return m[status] || 'secondary';
}

let funnelChart, sourceChart, revenueChart;

(async function() {
    // KPI
    const kpi = await api('/dashboard/kpi');
    if (kpi) {
        const cards = document.querySelectorAll('.crm-dashboard .card-hover h3');
        if (cards[0]) cards[0].textContent = fmt(kpi.new_leads ?? kpi.leads_new ?? 0);
        if (cards[1]) cards[1].textContent = fmt(kpi.active_clients ?? kpi.clients_active ?? 0);
        if (cards[2]) cards[2].textContent = fmt(kpi.open_cases ?? kpi.cases_active ?? 0);
        if (cards[3]) cards[3].textContent = fmt(kpi.tasks_due_today ?? kpi.tasks_today ?? 0);
        if (cards[4]) cards[4].textContent = fmtPLN(kpi.revenue_mtd ?? kpi.revenue ?? 0);
    }

    // Lead Funnel
    const funnel = await api('/leads/funnel');
    const funnelData = funnel ? [funnel.new||0, funnel.contacted||0, funnel.consultation||0, funnel.converted||0] : [0,0,0,0];
    const total = funnelData.reduce((a,b)=>a+b,0);
    const convRate = total > 0 ? ((funnelData[3]/total)*100).toFixed(1) : '0';
    document.querySelectorAll('.crm-dashboard [data-lang="wc-lead-funnel"]').forEach(el => {
        const wrap = el.closest('.card-body');
        if (wrap) {
            const rows = wrap.querySelectorAll('.d-flex.justify-content-between .fw-semibold');
            if (rows[0]) rows[0].textContent = fmt(funnelData[0]);
            if (rows[1]) rows[1].textContent = fmt(funnelData[1]);
            if (rows[2]) rows[2].textContent = fmt(funnelData[2]);
            if (rows[3]) rows[3].textContent = fmt(funnelData[3]);
            if (rows[4]) rows[4].textContent = convRate + '%';
        }
    });

    funnelChart = new ApexCharts(document.querySelector("#leadFunnelChart"), {
        chart: { type: 'bar', height: 200, toolbar: { show: false } },
        plotOptions: { bar: { horizontal: true, borderRadius: 4, barHeight: '70%' } },
        dataLabels: { enabled: false },
        series: [{ name: 'Leads', data: funnelData }],
        xaxis: { categories: ['New', 'Contacted', 'Consultation', 'Converted'] },
        colors: ['#3b82f6'], grid: { borderColor: '#f1f1f1' }
    });
    funnelChart.render();

    // Leads by Source
    const stats = await api('/leads/statistics');
    let srcLabels = ['Facebook', 'Google', 'Website', 'TikTok', 'Referral', 'Other'];
    let srcData = [0,0,0,0,0,0];
    if (stats && stats.by_source) {
        srcLabels = []; srcData = [];
        (Array.isArray(stats.by_source) ? stats.by_source : Object.entries(stats.by_source).map(([k,v])=>({source:k,count:v}))).slice(0,6).forEach(s => {
            srcLabels.push(s.source || s.name || 'Other');
            srcData.push(s.count || 0);
        });
    }
    const srcTotal = srcData.reduce((a,b)=>a+b,0);
    sourceChart = new ApexCharts(document.querySelector("#leadsBySourceChart"), {
        chart: { type: 'donut', height: 300 },
        series: srcData, labels: srcLabels,
        colors: ['#3b82f6','#10b981','#f59e0b','#ef4444','#8b5cf6','#6b7280'],
        legend: { position: 'bottom' }, dataLabels: { enabled: false },
        plotOptions: { pie: { donut: { size: '65%', labels: { show: true, total: { show: true, label: 'Total Leads', formatter: () => fmt(srcTotal) } } } } }
    });
    sourceChart.render();

    // Revenue Chart
    const fin = await api('/dashboard/finance');
    let revData = [0,0,0,0,0,0], expData = [0,0,0,0,0,0], revCats = [];
    if (fin && fin.monthly_trend) {
        revCats = fin.monthly_trend.map(m => m.month || m.label);
        revData = fin.monthly_trend.map(m => m.revenue || 0);
        expData = fin.monthly_trend.map(m => m.expenses || 0);
    } else {
        const months = ['Oct','Nov','Dec','Jan','Feb','Mar'];
        revCats = months;
    }
    revenueChart = new ApexCharts(document.querySelector("#revenueOverviewChart"), {
        chart: { type: 'area', height: 350, toolbar: { show: false } },
        series: [{ name: 'Revenue', data: revData }, { name: 'Expenses', data: expData }],
        xaxis: { categories: revCats },
        colors: ['#10b981', '#ef4444'],
        stroke: { curve: 'smooth', width: 2 },
        fill: { type: 'gradient', gradient: { opacityFrom: 0.3, opacityTo: 0.05 } },
        dataLabels: { enabled: false }, grid: { borderColor: '#f1f1f1' },
        yaxis: { labels: { formatter: v => (v/1000).toFixed(0) + 'K' } },
        tooltip: { y: { formatter: v => v.toLocaleString('pl-PL') + ' PLN' } }
    });
    revenueChart.render();

    // Finance Summary
    if (fin) {
        const fCards = document.querySelectorAll('.crm-dashboard .col-xl-3.col-md-6 h4');
        if (fCards[0]) fCards[0].textContent = fmtPLN(fin.payments_this_month ?? fin.paid ?? 0);
        if (fCards[1]) fCards[1].textContent = fmtPLN(fin.pending_invoices ?? fin.outstanding ?? 0);
        if (fCards[2]) fCards[2].textContent = fmtPLN(fin.overdue ?? 0);
        if (fCards[3]) fCards[3].textContent = fmtPLN(fin.expenses_mtd ?? fin.expenses ?? 0);
    }

    // Case Pipeline
    const cases = await api('/cases?per_page=200&status=active');
    if (cases) {
        const arr = cases.data || cases;
        const pipeline = {};
        (Array.isArray(arr) ? arr : []).forEach(c => { pipeline[c.status] = (pipeline[c.status]||0)+1; });
        const pipeEls = document.querySelectorAll('.crm-dashboard [data-lang="wc-case-pipeline"]');
        if (pipeEls.length) {
            const row = pipeEls[0].closest('.card').querySelector('.row');
            if (row) {
                const cols = row.querySelectorAll('.col h5');
                const stages = ['submitted','await_fingerprint','fingerprint_appointment','fingerprint_done','await_decision','decision_signed','card_issued'];
                stages.forEach((s,i) => { if (cols[i]) cols[i].textContent = fmt(pipeline[s]||0); });
            }
        }
    }

    // Recent Leads Table
    const leads = await api('/leads?per_page=6&sort=-created_at');
    if (leads) {
        const arr = leads.data || leads;
        const tbody = document.querySelector('.crm-dashboard [data-lang="wc-recent-leads"]')?.closest('.card')?.querySelector('tbody');
        if (tbody && Array.isArray(arr)) {
            tbody.innerHTML = arr.map(l => `<tr>
                <td><a href="/crm-leads" class="fw-semibold text-body">${l.name||l.first_name||''}</a></td>
                <td><span class="badge bg-primary-subtle text-primary">${l.source||''}</span></td>
                <td>${l.service_type||''}</td>
                <td><span class="badge bg-${badge(l.status)}-subtle text-${badge(l.status)}">${l.status||''}</span></td>
                <td class="text-muted fs-12">${l.created_at ? new Date(l.created_at).toLocaleDateString('en-US',{month:'short',day:'numeric'}) : ''}</td>
            </tr>`).join('');
        }
    }

    // Active Cases Table
    const casesRecent = await api('/cases?per_page=6&sort=-updated_at');
    if (casesRecent) {
        const arr = casesRecent.data || casesRecent;
        const tbody = document.querySelector('.crm-dashboard [data-lang="wc-active-cases-title"]')?.closest('.card')?.querySelector('tbody');
        if (tbody && Array.isArray(arr)) {
            tbody.innerHTML = arr.map(c => `<tr>
                <td><a href="/crm-cases" class="fw-semibold text-body">${c.case_number||'#'+c.id}</a></td>
                <td>${c.client?.name||c.client_name||''}</td>
                <td>${c.service_type||''}</td>
                <td><span class="badge bg-${badge(c.status)}-subtle text-${badge(c.status)}">${(c.status||'').replace(/_/g,' ')}</span></td>
                <td>${c.assigned_user?.name||c.manager_name||''}</td>
            </tr>`).join('');
        }
    }

    // Upcoming Tasks
    const tasks = await api('/tasks/my?per_page=6&status=pending,in_progress');
    if (tasks) {
        const arr = tasks.data || tasks;
        const list = document.querySelector('.crm-dashboard [data-lang="wc-upcoming-tasks"]')?.closest('.card')?.querySelector('ul');
        if (list && Array.isArray(arr)) {
            list.innerHTML = arr.map(t => `<li class="list-group-item">
                <div class="d-flex align-items-center gap-3">
                    <div class="form-check"><input class="form-check-input task-check" type="checkbox" data-id="${t.id}"></div>
                    <div class="flex-grow-1">
                        <h6 class="mb-1 fs-13">${t.title||''}</h6>
                        <div class="d-flex gap-2">
                            <span class="badge bg-${badge(t.priority||'medium')}-subtle text-${badge(t.priority||'medium')}">${t.priority||'Medium'}</span>
                            <span class="text-muted fs-12">Due: ${t.due_date ? new Date(t.due_date).toLocaleDateString('en-US',{month:'short',day:'numeric'}) : 'N/A'}</span>
                        </div>
                    </div>
                </div>
            </li>`).join('');
            list.querySelectorAll('.task-check').forEach(cb => {
                cb.addEventListener('change', function() {
                    const li = this.closest('li');
                    if (this.checked) { li.style.opacity='0.5'; li.querySelector('h6').style.textDecoration='line-through'; api('/tasks/'+this.dataset.id+'/status'); }
                    else { li.style.opacity='1'; li.querySelector('h6').style.textDecoration='none'; }
                });
            });
        }
    }
})();

// Revenue range buttons
document.querySelectorAll('.revenue-range').forEach(btn => {
    btn.addEventListener('click', function(){
        document.querySelectorAll('.revenue-range').forEach(b => b.classList.remove('active'));
        this.classList.add('active');
    });
});
</script>
@endsection
