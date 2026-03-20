@extends('partials.layouts.master')

@section('title', 'Sales Quota | WinCase CRM')
@section('sub-title', 'Sales Quota')
@section('sub-title-lang', 'wc-sales-quota')
@section('pagetitle', 'Analytics')
@section('pagetitle-lang', 'wc-analytics')

@section('content')
<div class="row">
    <!-- Period Selector -->
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <h5 class="card-title mb-0">Monthly Sales Quota</h5>
                    <div class="d-flex gap-2">
                        <select id="quotaPeriodSelect" class="form-select form-select-sm" style="width: 150px;">
                            <option value="2026-03">March 2026</option>
                            <option value="2026-02">February 2026</option>
                            <option value="2026-01">January 2026</option>
                            <option value="2025-12">December 2025</option>
                        </select>
                        <button id="quotaRefreshBtn" class="btn btn-sm btn-primary"><i class="ri-refresh-line"></i></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Total Sales Stats -->
<div class="row">
    <div class="col-xl-3 col-md-6">
        <div class="card card-h-100">
            <div class="card-body">
                <div class="d-flex align-items-center gap-3">
                    <div class="avatar avatar-sm bg-primary-subtle text-primary rounded-2">
                        <i class="ri-money-dollar-circle-line fs-18"></i>
                    </div>
                    <div>
                        <p class="text-muted mb-0 fs-13">Total Sales (Month)</p>
                        <h4 id="kpiTotalSales" class="mb-0 fw-semibold">—</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card card-h-100">
            <div class="card-body">
                <div class="d-flex align-items-center gap-3">
                    <div class="avatar avatar-sm bg-success-subtle text-success rounded-2">
                        <i class="ri-user-follow-line fs-18"></i>
                    </div>
                    <div>
                        <p class="text-muted mb-0 fs-13">New Cases (Month)</p>
                        <h4 id="kpiNewCases" class="mb-0 fw-semibold">—</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card card-h-100">
            <div class="card-body">
                <div class="d-flex align-items-center gap-3">
                    <div class="avatar avatar-sm bg-warning-subtle text-warning rounded-2">
                        <i class="ri-bar-chart-line fs-18"></i>
                    </div>
                    <div>
                        <p class="text-muted mb-0 fs-13">Monthly Target</p>
                        <h4 id="kpiMonthlyTarget" class="mb-0 fw-semibold">—</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card card-h-100">
            <div class="card-body">
                <div class="d-flex align-items-center gap-3">
                    <div class="avatar avatar-sm bg-info-subtle text-info rounded-2">
                        <i class="ri-percent-line fs-18"></i>
                    </div>
                    <div>
                        <p class="text-muted mb-0 fs-13">Quota Completion</p>
                        <h4 id="kpiQuotaPct" class="mb-0 fw-semibold">—</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Per-Manager Sales Quota -->
<div class="card">
    <div class="card-header">
        <h5 id="quotaMonthTitle" class="card-title mb-0">Sales by Manager</h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Manager</th>
                        <th>Monthly Quota</th>
                        <th>Sales Amount</th>
                        <th>Cases Sold</th>
                        <th>Quota %</th>
                        <th>Progress</th>
                        <th>Avg. Deal</th>
                        <th>Conversion</th>
                    </tr>
                </thead>
                <tbody id="quotaTableBody">
                    <tr><td colspan="8" class="text-center text-muted py-4">Loading...</td></tr>
                </tbody>
                <tfoot id="quotaTableFoot" class="table-light"></tfoot>
            </table>
        </div>
    </div>
</div>

<!-- Detailed Sales List -->
<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">Sales This Month — Detailed</h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Date</th>
                        <th>Client</th>
                        <th>Case #</th>
                        <th>Case Type</th>
                        <th>Contract Amount</th>
                        <th>Sold By</th>
                        <th>Source</th>
                        <th>Payment Status</th>
                    </tr>
                </thead>
                <tbody id="salesDetailBody">
                    <tr><td colspan="8" class="text-center text-muted py-4">Loading...</td></tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer">
        <div id="salesPagination" class="d-flex align-items-center justify-content-between">
            <div class="text-muted fs-13">—</div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
document.addEventListener('DOMContentLoaded', function() {

    var API_BASE = '/api/v1';
    var token = localStorage.getItem('wc_token');

    var avatarColors = [
        'bg-primary-subtle text-primary',
        'bg-success-subtle text-success',
        'bg-warning-subtle text-warning',
        'bg-info-subtle text-info',
        'bg-danger-subtle text-danger'
    ];

    var serviceTypeColors = {
        'temp_residence':   'bg-primary-subtle text-primary',
        'perm_residence':   'bg-info-subtle text-info',
        'citizenship':      'bg-success-subtle text-success',
        'work_permit':      'bg-warning-subtle text-warning',
        'appeal':           'bg-danger-subtle text-danger',
        'speedup':          'bg-warning-subtle text-warning',
        'consultation':     'bg-secondary-subtle text-secondary',
        'eu_residence':     'bg-info-subtle text-info',
        'family_reunion':   'bg-primary-subtle text-primary',
        'deportation':      'bg-danger-subtle text-danger',
        'business_setup':   'bg-success-subtle text-success',
        'other':            'bg-secondary-subtle text-secondary'
    };

    var serviceTypeLabels = {
        'temp_residence':   'Temp. Residence',
        'perm_residence':   'Perm. Residence',
        'citizenship':      'Citizenship',
        'work_permit':      'Work Permit',
        'appeal':           'Appeal',
        'speedup':          'Speedup',
        'consultation':     'Consultation',
        'eu_residence':     'EU Residence',
        'family_reunion':   'Family Reunion',
        'deportation':      'Deportation Defense',
        'business_setup':   'Business Setup',
        'other':            'Other'
    };

    var sourceColors = {
        'google':       'bg-primary-subtle text-primary',
        'instagram':    'bg-danger-subtle text-danger',
        'facebook':     'bg-info-subtle text-info',
        'tiktok':       'bg-info-subtle text-info',
        'referral':     'bg-warning-subtle text-warning',
        'meta_ads':     'bg-secondary-subtle text-secondary',
        'google_ads':   'bg-primary-subtle text-primary',
        'website':      'bg-success-subtle text-success',
        'walk_in':      'bg-secondary-subtle text-secondary',
        'telegram':     'bg-info-subtle text-info',
        'transfer':     'bg-warning-subtle text-warning',
        'other':        'bg-secondary-subtle text-secondary'
    };

    function formatPLN(val) {
        return 'PLN ' + Number(val).toLocaleString('pl-PL');
    }

    function formatDate(dateStr) {
        var d = new Date(dateStr + 'T00:00:00');
        var months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
        return months[d.getMonth()] + ' ' + d.getDate();
    }

    function getAvatarColor(index) {
        return avatarColors[index % avatarColors.length];
    }

    function getInitials(name) {
        return name.split(' ').map(function(w){ return w.charAt(0).toUpperCase(); }).join('').substring(0, 2);
    }

    function formatSourceLabel(source) {
        return source.replace(/_/g, ' ').replace(/\b\w/g, function(c){ return c.toUpperCase(); });
    }

    function progressBarClass(pct) {
        if (pct >= 80) return 'bg-success';
        if (pct >= 50) return 'bg-warning';
        return 'bg-danger';
    }

    function conversionBadgeClass(pct) {
        if (pct >= 70) return 'bg-success-subtle text-success';
        if (pct >= 50) return 'bg-warning-subtle text-warning';
        return 'bg-danger-subtle text-danger';
    }

    function paymentBadge(status) {
        var map = {
            'paid':           { cls: 'bg-success-subtle text-success', label: 'Fully Paid' },
            'partially_paid': { cls: 'bg-warning-subtle text-warning', label: 'Partially Paid' },
            'unpaid':         { cls: 'bg-danger-subtle text-danger',   label: 'Unpaid' },
            'pending':        { cls: 'bg-danger-subtle text-danger',   label: 'Pending' }
        };
        var info = map[status] || { cls: 'bg-secondary-subtle text-secondary', label: status };
        return '<span class="badge ' + info.cls + '">' + info.label + '</span>';
    }

    function loadQuotaData() {
        var period = document.getElementById('quotaPeriodSelect').value;
        var url = API_BASE + '/analytics/quota?period=' + encodeURIComponent(period);

        fetch(url, {
            headers: {
                'Accept': 'application/json',
                'Authorization': 'Bearer ' + token
            }
        })
        .then(function(res) { return res.json(); })
        .then(function(json) {
            if (!json.success || !json.data) return;
            var data = json.data;

            // Update period selector text
            var sel = document.getElementById('quotaPeriodSelect');
            var opts = sel.options;
            var found = false;
            for (var i = 0; i < opts.length; i++) {
                if (opts[i].text === data.month) {
                    sel.selectedIndex = i;
                    found = true;
                    break;
                }
            }
            if (!found && data.month) {
                opts[0].text = data.month;
            }

            // KPI cards
            document.getElementById('kpiTotalSales').textContent = formatPLN(data.kpi.total_sales);
            document.getElementById('kpiNewCases').textContent = data.kpi.total_cases;
            document.getElementById('kpiMonthlyTarget').textContent = formatPLN(data.kpi.total_quota);
            document.getElementById('kpiQuotaPct').textContent = data.kpi.quota_pct + '%';

            // Month title
            document.getElementById('quotaMonthTitle').textContent = 'Sales by Manager \u2014 ' + data.month;

            // Manager quota table
            renderManagerTable(data.managers, data.kpi);

            // Sales detail table
            renderSalesTable(data.recent_sales);
        })
        .catch(function(err) {
            console.error('Quota API error:', err);
        });
    }

    function renderManagerTable(managers, kpi) {
        var tbody = document.getElementById('quotaTableBody');
        var tfoot = document.getElementById('quotaTableFoot');

        if (!managers || managers.length === 0) {
            tbody.innerHTML = '<tr><td colspan="8" class="text-center text-muted py-4">No manager data available</td></tr>';
            tfoot.innerHTML = '';
            return;
        }

        var html = '';
        managers.forEach(function(m, idx) {
            var color = getAvatarColor(idx);
            var barClass = progressBarClass(m.quota_pct);
            var convClass = conversionBadgeClass(m.conversion);
            var quotaPctClass = m.quota_pct < 50 ? ' text-danger' : '';

            html += '<tr>' +
                '<td>' +
                    '<div class="d-flex align-items-center gap-2">' +
                        '<div class="avatar avatar-xs avatar-rounded ' + color + '">' + (m.initials || getInitials(m.name)) + '</div>' +
                        '<span class="fw-semibold">' + m.name + '</span>' +
                    '</div>' +
                '</td>' +
                '<td>' + formatPLN(m.quota) + '</td>' +
                '<td class="fw-semibold text-success">' + formatPLN(m.sales) + '</td>' +
                '<td><span class="badge bg-primary">' + m.cases_sold + '</span></td>' +
                '<td class="fw-semibold' + quotaPctClass + '">' + m.quota_pct + '%</td>' +
                '<td>' +
                    '<div class="d-flex align-items-center gap-2">' +
                        '<div class="progress flex-grow-1" style="height: 8px; width: 120px;">' +
                            '<div class="progress-bar ' + barClass + '" style="width: ' + Math.min(m.quota_pct, 100) + '%"></div>' +
                        '</div>' +
                    '</div>' +
                '</td>' +
                '<td class="text-muted">' + formatPLN(m.avg_deal) + '</td>' +
                '<td><span class="badge ' + convClass + '">' + m.conversion + '%</span></td>' +
            '</tr>';
        });
        tbody.innerHTML = html;

        // Footer totals
        var totalQuota = 0, totalSales = 0, totalCases = 0;
        managers.forEach(function(m) {
            totalQuota += Number(m.quota);
            totalSales += Number(m.sales);
            totalCases += Number(m.cases_sold);
        });
        var totalPct = totalQuota > 0 ? ((totalSales / totalQuota) * 100).toFixed(1) : 0;
        var totalAvg = totalCases > 0 ? Math.round(totalSales / totalCases) : 0;

        tfoot.innerHTML = '<tr>' +
            '<td class="fw-semibold">TOTAL</td>' +
            '<td class="fw-semibold">' + formatPLN(totalQuota) + '</td>' +
            '<td class="fw-semibold text-success">' + formatPLN(totalSales) + '</td>' +
            '<td><span class="badge bg-primary">' + totalCases + '</span></td>' +
            '<td class="fw-semibold">' + totalPct + '%</td>' +
            '<td>' +
                '<div class="d-flex align-items-center gap-2">' +
                    '<div class="progress flex-grow-1" style="height: 8px; width: 120px;">' +
                        '<div class="progress-bar bg-primary" style="width: ' + Math.min(totalPct, 100) + '%"></div>' +
                    '</div>' +
                '</div>' +
            '</td>' +
            '<td class="fw-semibold">' + formatPLN(totalAvg) + '</td>' +
            '<td>\u2014</td>' +
        '</tr>';
    }

    function renderSalesTable(sales) {
        var tbody = document.getElementById('salesDetailBody');
        var pagination = document.getElementById('salesPagination');

        if (!sales || sales.length === 0) {
            tbody.innerHTML = '<tr><td colspan="8" class="text-center text-muted py-4">No sales data available</td></tr>';
            pagination.innerHTML = '<div class="text-muted fs-13">No sales to display</div>';
            return;
        }

        var html = '';
        sales.forEach(function(s, idx) {
            var svcColor = serviceTypeColors[s.service_type] || 'bg-secondary-subtle text-secondary';
            var svcLabel = serviceTypeLabels[s.service_type] || s.service_type.replace(/_/g, ' ').replace(/\b\w/g, function(c){ return c.toUpperCase(); });
            var srcColor = sourceColors[s.source] || 'bg-secondary-subtle text-secondary';
            var srcLabel = formatSourceLabel(s.source);
            var mgrInitials = getInitials(s.manager_name);
            var mgrColor = getAvatarColor(idx);

            html += '<tr>' +
                '<td class="text-muted fs-12">' + formatDate(s.date) + '</td>' +
                '<td class="fw-semibold">' + s.client_name + '</td>' +
                '<td><a href="#" class="text-primary">' + s.case_number + '</a></td>' +
                '<td><span class="badge ' + svcColor + '">' + svcLabel + '</span></td>' +
                '<td class="fw-semibold">' + formatPLN(s.amount) + '</td>' +
                '<td>' +
                    '<div class="d-flex align-items-center gap-1">' +
                        '<div class="avatar avatar-xs avatar-rounded ' + mgrColor + '">' + mgrInitials + '</div>' +
                        '<span>' + s.manager_name + '</span>' +
                    '</div>' +
                '</td>' +
                '<td><span class="badge ' + srcColor + '">' + srcLabel + '</span></td>' +
                '<td>' + paymentBadge(s.payment_status) + '</td>' +
            '</tr>';
        });
        tbody.innerHTML = html;

        // Pagination info
        pagination.innerHTML = '<div class="text-muted fs-13">Showing ' + sales.length + ' sale' + (sales.length !== 1 ? 's' : '') + '</div>';
    }

    // Event listeners
    document.getElementById('quotaRefreshBtn').addEventListener('click', function() {
        loadQuotaData();
    });

    document.getElementById('quotaPeriodSelect').addEventListener('change', function() {
        loadQuotaData();
    });

    // Initial load
    loadQuotaData();
});
</script>
@endsection
