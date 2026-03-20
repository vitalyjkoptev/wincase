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
                        <h3 class="fw-semibold mb-1" id="kpiRevenueMtd">--</h3>
                        <h6 class="mb-0">Revenue MTD</h6>
                    </div>
                    <div class="text-muted fs-13 mt-2">
                        <span class="text-body fw-semibold me-1" id="kpiRevenueChange"><i class="ri-arrow-right-up-line fs-16 me-1"></i>--%</span>vs last month
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
                        <h3 class="fw-semibold mb-1" id="kpiInvoicesCount">--</h3>
                        <h6 class="mb-0">Invoices Issued</h6>
                    </div>
                    <div class="text-muted fs-13 mt-2">
                        <span class="text-body fw-semibold me-1" id="kpiInvoicesChange"><i class="ri-arrow-right-up-line fs-16 me-1"></i>--%</span>vs last month
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
                        <h3 class="fw-semibold mb-1" id="kpiAvgInvoice">--</h3>
                        <h6 class="mb-0">Avg Invoice</h6>
                    </div>
                    <div class="text-muted fs-13 mt-2">
                        <span class="text-body fw-semibold me-1" id="kpiAvgChange"><i class="ri-arrow-right-up-line fs-16 me-1"></i>--%</span>vs last month
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
                        <h3 class="fw-semibold mb-1" id="kpiConversionRate">--</h3>
                        <h6 class="mb-0">Conversion Rate</h6>
                    </div>
                    <div class="text-muted fs-13 mt-2">
                        <span class="text-body fw-semibold me-1" id="kpiConversionChange"><i class="ri-arrow-right-up-line fs-16 me-1"></i>--%</span>vs last month
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
                <div class="mt-3" id="serviceDetailList">
                    <div class="text-center text-muted py-3">
                        <div class="spinner-border spinner-border-sm me-2" role="status"></div>Loading...
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
                        <tbody id="topClientsBody">
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">
                                    <div class="spinner-border spinner-border-sm me-2" role="status"></div>Loading...
                                </td>
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
document.addEventListener('DOMContentLoaded', function() {
    var API = '/api/v1';
    var token = localStorage.getItem('wc_token');

    // Chart instances (will be created after data loads)
    var revenueTrendChart = null;
    var serviceChart = null;
    var comparisonChart = null;
    var paymentChart = null;

    // Stored data for revenue period switching
    var revenueTrendData = null;

    // Service type labels map
    var serviceLabels = {
        'work_permit': 'Work Permit',
        'residence_permit': 'Residence Permit',
        'company_registration': 'Company Registration',
        'tax_services': 'Tax Services',
        'consultation': 'Consultation',
        'legalization': 'Legalization',
        'other': 'Other'
    };

    // Payment method labels map
    var paymentLabels = {
        'transfer': 'Bank Transfer',
        'cash': 'Cash',
        'card': 'Card',
        'pos': 'POS Terminal',
        'online': 'Online',
        'other': 'Other'
    };

    // Chart colors
    var chartColors = ['#3b82f6', '#10b981', '#f59e0b', '#ef4444', '#6b7280', '#8b5cf6', '#ec4899'];
    var serviceDetailColors = ['text-primary', 'text-success', 'text-warning', 'text-danger', 'text-secondary', 'text-info', 'text-dark'];

    // Avatar color palette for Top Clients
    var avatarPalette = [
        'bg-primary-subtle text-primary',
        'bg-success-subtle text-success',
        'bg-warning-subtle text-warning',
        'bg-info-subtle text-info',
        'bg-danger-subtle text-danger'
    ];

    // Format PLN amount
    function fmtPLN(val) {
        return val.toLocaleString('pl-PL') + ' PLN';
    }

    // Format date to readable string
    function fmtDate(dateStr) {
        if (!dateStr) return '--';
        var d = new Date(dateStr);
        var months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
        return months[d.getMonth()] + ' ' + d.getDate() + ', ' + d.getFullYear();
    }

    // Format month label from "2025-10" -> "Oct"
    function fmtMonth(monthStr) {
        var months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
        var parts = monthStr.split('-');
        return months[parseInt(parts[1], 10) - 1];
    }

    // Get initials from name
    function getInitials(name) {
        return name.split(' ').map(function(w) { return w[0]; }).join('');
    }

    // Set KPI change indicator (arrow + percentage)
    function setKpiChange(elId, value) {
        var el = document.getElementById(elId);
        if (!el) return;
        var isPositive = value >= 0;
        var icon = isPositive ? 'ri-arrow-right-up-line' : 'ri-arrow-right-down-line';
        var colorClass = isPositive ? '' : 'text-danger';
        el.className = 'text-body fw-semibold me-1' + (colorClass ? ' ' + colorClass : '');
        el.innerHTML = '<i class="' + icon + ' fs-16 me-1"></i>' + Math.abs(value) + '%';
    }

    // --- Populate KPI Cards ---
    function populateKPI(kpi) {
        document.getElementById('kpiRevenueMtd').textContent = fmtPLN(kpi.revenue_mtd);
        document.getElementById('kpiInvoicesCount').textContent = kpi.invoices_count;
        document.getElementById('kpiAvgInvoice').textContent = fmtPLN(kpi.avg_invoice);
        document.getElementById('kpiConversionRate').textContent = kpi.conversion_rate + '%';

        setKpiChange('kpiRevenueChange', kpi.revenue_change);
        setKpiChange('kpiInvoicesChange', kpi.invoices_change);
        // avg_invoice and conversion_rate don't have explicit change values in API, leave as-is
    }

    // --- Revenue Trend Chart ---
    function renderRevenueTrend(trendData) {
        var categories = trendData.map(function(item) { return fmtMonth(item.month); });
        var data = trendData.map(function(item) { return item.revenue; });

        // Store for period switching (month view = API data)
        revenueTrendData = {
            month: { categories: categories, data: data }
        };

        var options = {
            chart: { type: 'area', height: 350, toolbar: { show: false } },
            series: [{ name: 'Revenue (PLN)', data: data }],
            xaxis: { categories: categories },
            colors: ['#3b82f6'],
            stroke: { curve: 'smooth', width: 2 },
            fill: { type: 'gradient', gradient: { opacityFrom: 0.4, opacityTo: 0.05 } },
            dataLabels: { enabled: false },
            grid: { borderColor: '#f1f1f1' },
            tooltip: { y: { formatter: function(val) { return fmtPLN(val); } } }
        };

        revenueTrendChart = new ApexCharts(document.querySelector('#revenueTrendChart'), options);
        revenueTrendChart.render();
    }

    // --- Revenue by Service Type Donut ---
    function renderServiceDonut(byService) {
        var labels = byService.map(function(item) { return serviceLabels[item.service_type] || item.service_type; });
        var series = byService.map(function(item) { return item.total; });
        var totalRevenue = series.reduce(function(a, b) { return a + b; }, 0);

        var options = {
            chart: { type: 'donut', height: 300 },
            series: series,
            labels: labels,
            colors: chartColors.slice(0, labels.length),
            legend: { position: 'bottom' },
            dataLabels: { enabled: true, formatter: function(val) { return val.toFixed(0) + '%'; } },
            plotOptions: {
                pie: {
                    donut: {
                        size: '65%',
                        labels: {
                            show: true,
                            total: {
                                show: true,
                                label: 'Total',
                                formatter: function() { return fmtPLN(totalRevenue); }
                            }
                        }
                    }
                }
            }
        };

        serviceChart = new ApexCharts(document.querySelector('#revenueByServiceChart'), options);
        serviceChart.render();

        // Populate service detail list
        var listEl = document.getElementById('serviceDetailList');
        var html = '';
        byService.forEach(function(item, i) {
            var colorClass = serviceDetailColors[i % serviceDetailColors.length];
            var label = serviceLabels[item.service_type] || item.service_type;
            var isLast = (i === byService.length - 1);
            html += '<div class="d-flex justify-content-between' + (isLast ? '' : ' mb-2') + '">';
            html += '<span class="text-muted fs-13"><i class="ri-circle-fill ' + colorClass + ' me-1 fs-10"></i>' + label + '</span>';
            html += '<span class="fw-semibold">' + fmtPLN(item.total) + '</span>';
            html += '</div>';
        });
        listEl.innerHTML = html;
    }

    // --- Top Clients Table ---
    function renderTopClients(clients) {
        var tbody = document.getElementById('topClientsBody');
        if (!clients || clients.length === 0) {
            tbody.innerHTML = '<tr><td colspan="5" class="text-center text-muted py-4">No data available</td></tr>';
            return;
        }

        var html = '';
        clients.forEach(function(client, i) {
            var initials = getInitials(client.name);
            var avatarClass = avatarPalette[i % avatarPalette.length];
            var outstandingClass = client.outstanding > 0 ? 'text-danger' : 'text-success';

            html += '<tr>';
            html += '<td>';
            html += '<div class="d-flex align-items-center gap-2">';
            html += '<div class="avatar avatar-xs avatar-rounded ' + avatarClass + '">';
            html += '<span>' + initials + '</span>';
            html += '</div>';
            html += '<a href="#" class="fw-semibold text-body">' + client.name + '</a>';
            html += '</div>';
            html += '</td>';
            html += '<td class="text-center">' + client.cases + '</td>';
            html += '<td class="text-end fw-semibold">' + client.total_paid.toLocaleString('pl-PL') + '</td>';
            html += '<td class="text-end ' + outstandingClass + '">' + client.outstanding.toLocaleString('pl-PL') + '</td>';
            html += '<td class="text-muted fs-12">' + fmtDate(client.last_payment) + '</td>';
            html += '</tr>';
        });
        tbody.innerHTML = html;
    }

    // --- Monthly Comparison Bar Chart ---
    function renderComparison(comparison) {
        var currentData = comparison.current;
        var previousData = comparison.previous;

        // Build categories and series from all keys in current
        var keys = Object.keys(currentData);
        var categories = keys.map(function(k) { return serviceLabels[k] || k; });
        var currentSeries = keys.map(function(k) { return currentData[k] || 0; });
        var previousSeries = keys.map(function(k) { return (previousData[k] || 0); });

        var options = {
            chart: { type: 'bar', height: 300, toolbar: { show: false } },
            series: [
                { name: 'Current Month', data: currentSeries },
                { name: 'Previous Month', data: previousSeries }
            ],
            xaxis: { categories: categories },
            colors: ['#3b82f6', '#c7d2fe'],
            plotOptions: { bar: { borderRadius: 4, columnWidth: '60%' } },
            dataLabels: { enabled: false },
            grid: { borderColor: '#f1f1f1' },
            legend: { position: 'top' },
            tooltip: { y: { formatter: function(val) { return fmtPLN(val); } } }
        };

        comparisonChart = new ApexCharts(document.querySelector('#monthlyComparisonChart'), options);
        comparisonChart.render();
    }

    // --- Payment Methods Horizontal Bar ---
    function renderPaymentMethods(methods) {
        if (!methods || methods.length === 0) {
            document.querySelector('#paymentMethodsChart').innerHTML = '<div class="text-center text-muted py-5">No payment data</div>';
            return;
        }

        var totalPayments = methods.reduce(function(sum, m) { return sum + m.count; }, 0);
        var categories = methods.map(function(m) { return paymentLabels[m.payment_method] || m.payment_method; });
        var data = methods.map(function(m) { return totalPayments > 0 ? Math.round((m.count / totalPayments) * 100) : 0; });

        var options = {
            chart: { type: 'bar', height: 300, toolbar: { show: false } },
            series: [{ name: 'Payments', data: data }],
            xaxis: { categories: categories },
            colors: ['#3b82f6'],
            plotOptions: { bar: { horizontal: true, borderRadius: 4, barHeight: '50%' } },
            dataLabels: { enabled: true, formatter: function(val) { return val + '%'; } },
            grid: { borderColor: '#f1f1f1' },
            tooltip: { y: { formatter: function(val) { return val + '%'; } } }
        };

        paymentChart = new ApexCharts(document.querySelector('#paymentMethodsChart'), options);
        paymentChart.render();
    }

    // --- Fetch data from API ---
    fetch(API + '/analytics/sales', {
        method: 'GET',
        headers: {
            'Accept': 'application/json',
            'Authorization': 'Bearer ' + token
        }
    })
    .then(function(response) {
        if (!response.ok) throw new Error('HTTP ' + response.status);
        return response.json();
    })
    .then(function(json) {
        if (!json.success || !json.data) throw new Error('Invalid response');

        var data = json.data;

        populateKPI(data.kpi);
        renderRevenueTrend(data.revenue_trend);
        renderServiceDonut(data.by_service);
        renderTopClients(data.top_clients);
        renderComparison(data.comparison);
        renderPaymentMethods(data.payment_methods);
    })
    .catch(function(err) {
        console.error('Sales analytics load error:', err);
        // Show error in table
        var tbody = document.getElementById('topClientsBody');
        if (tbody) {
            tbody.innerHTML = '<tr><td colspan="5" class="text-center text-danger py-4">Failed to load data</td></tr>';
        }
        var serviceList = document.getElementById('serviceDetailList');
        if (serviceList) {
            serviceList.innerHTML = '<div class="text-center text-danger py-3">Failed to load data</div>';
        }
        // Render empty charts so containers don't remain blank
        renderRevenueTrend([]);
        renderComparison({ current: {}, previous: {} });
        renderPaymentMethods([]);
    });

    // --- Revenue period toggle (exposed globally) ---
    window.updateRevenuePeriod = function(period, btn) {
        document.querySelectorAll('.card-header .btn-subtle-primary').forEach(function(b) { b.classList.remove('active'); });
        btn.classList.add('active');

        if (revenueTrendData && revenueTrendData[period]) {
            var d = revenueTrendData[period];
            revenueTrendChart.updateOptions({ xaxis: { categories: d.categories } });
            revenueTrendChart.updateSeries([{ name: 'Revenue (PLN)', data: d.data }]);
        }
    };
});
</script>
@endsection
