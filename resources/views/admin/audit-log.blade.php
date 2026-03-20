@extends('partials.layouts.master')
@section('title', 'Audit Log | WinCase CRM')
@section('sub-title', 'Audit Log')
@section('sub-title-lang', 'wc-audit-log')
@section('pagetitle', 'Admin')
@section('pagetitle-lang', 'wc-admin')
@section('content')

<!-- Stats -->
<div class="row mb-4" id="auditStats">
    <div class="col-sm-6 col-xl-3">
        <div class="card"><div class="card-body">
            <div class="d-flex align-items-center gap-3">
                <div class="avatar avatar-md bg-primary-subtle text-primary rounded"><i class="ri-history-line fs-20"></i></div>
                <div><p class="text-muted mb-1 fs-12">Total Events</p><h5 class="mb-0" id="statTotal">0</h5></div>
            </div>
        </div></div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card"><div class="card-body">
            <div class="d-flex align-items-center gap-3">
                <div class="avatar avatar-md bg-success-subtle text-success rounded"><i class="ri-login-circle-line fs-20"></i></div>
                <div><p class="text-muted mb-1 fs-12">Logins Today</p><h5 class="mb-0" id="statLogins">0</h5></div>
            </div>
        </div></div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card"><div class="card-body">
            <div class="d-flex align-items-center gap-3">
                <div class="avatar avatar-md bg-warning-subtle text-warning rounded"><i class="ri-edit-2-line fs-20"></i></div>
                <div><p class="text-muted mb-1 fs-12">Changes Today</p><h5 class="mb-0" id="statChanges">0</h5></div>
            </div>
        </div></div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card"><div class="card-body">
            <div class="d-flex align-items-center gap-3">
                <div class="avatar avatar-md bg-danger-subtle text-danger rounded"><i class="ri-shield-keyhole-line fs-20"></i></div>
                <div><p class="text-muted mb-1 fs-12">Security Events</p><h5 class="mb-0" id="statSecurity">0</h5></div>
            </div>
        </div></div>
    </div>
</div>

<!-- Filters -->
<div class="card mb-3">
    <div class="card-body py-2">
        <div class="row align-items-center g-2">
            <div class="col-md-3">
                <select class="form-select form-select-sm" id="actionFilter" onchange="loadAudit()">
                    <option value="">All Actions</option>
                    <option value="create">Create</option>
                    <option value="update">Update</option>
                    <option value="delete">Delete</option>
                    <option value="login">Login</option>
                    <option value="logout">Logout</option>
                    <option value="payment">Payment</option>
                </select>
            </div>
            <div class="col-md-3">
                <select class="form-select form-select-sm" id="entityFilter" onchange="loadAudit()">
                    <option value="">All Entities</option>
                    <option value="lead">Lead</option>
                    <option value="client">Client</option>
                    <option value="case">Case</option>
                    <option value="task">Task</option>
                    <option value="user">User</option>
                    <option value="invoice">Invoice</option>
                    <option value="payment">Payment</option>
                </select>
            </div>
            <div class="col-md-4">
                <input type="text" class="form-control form-control-sm" id="searchAudit" placeholder="Search by user or details..." onkeyup="loadAudit()">
            </div>
            <div class="col-md-2 text-end">
                <button class="btn btn-sm btn-subtle-primary" onclick="loadAudit()"><i class="ri-refresh-line"></i></button>
            </div>
        </div>
    </div>
</div>

<!-- Audit Table -->
<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr><th>Time</th><th>User</th><th>Action</th><th>Entity</th><th>Details</th><th>IP</th></tr>
                </thead>
                <tbody id="auditBody">
                    <tr><td colspan="6" class="text-center py-4"><span class="spinner-border spinner-border-sm"></span> Loading...</td></tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
const API_BASE = '/api/v1';
function getToken() { return localStorage.getItem('wc_token') || ''; }
function apiHeaders() { return { 'Authorization': 'Bearer ' + getToken(), 'Content-Type': 'application/json', 'Accept': 'application/json' }; }

const ACTION_COLORS = { create: 'success', update: 'warning', delete: 'danger', login: 'info', logout: 'secondary', payment: 'primary' };

document.addEventListener('DOMContentLoaded', () => { loadAudit(); loadAuditStats(); });

async function loadAudit() {
    const action = document.getElementById('actionFilter').value;
    const entity = document.getElementById('entityFilter').value;
    const search = document.getElementById('searchAudit').value;

    let url = API_BASE + '/audit/logs?';
    if (action) url += 'action=' + action + '&';
    if (entity) url += 'entity=' + entity + '&';
    if (search) url += 'search=' + encodeURIComponent(search) + '&';

    try {
        const res = await fetch(url, { headers: apiHeaders() });
        if (!res.ok) {
            document.getElementById('auditBody').innerHTML = '<tr><td colspan="6" class="text-center text-muted py-4">Unable to load audit logs. Check permissions.</td></tr>';
            return;
        }
        const json = await res.json();
        const logs = json.data || [];

        if (logs.length === 0) {
            document.getElementById('auditBody').innerHTML = '<tr><td colspan="6" class="text-center text-muted py-4">No audit log entries found</td></tr>';
            return;
        }

        let html = '';
        for (const log of logs) {
            const color = ACTION_COLORS[log.action] || 'secondary';
            const time = log.created_at ? new Date(log.created_at).toLocaleString('en-US', { month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit' }) : '—';
            html += `<tr>
                <td class="text-muted fs-12">${time}</td>
                <td>${log.user || log.user_name || 'System'}</td>
                <td><span class="badge bg-${color}-subtle text-${color}">${log.action || '—'}</span></td>
                <td>${log.entity || log.entity_type || '—'}</td>
                <td class="text-muted fs-12">${log.details || log.description || '—'}</td>
                <td class="text-muted fs-12">${log.ip || log.ip_address || '—'}</td>
            </tr>`;
        }
        document.getElementById('auditBody').innerHTML = html;
    } catch (e) {
        document.getElementById('auditBody').innerHTML = '<tr><td colspan="6" class="text-center text-danger py-4">Connection error</td></tr>';
    }
}

async function loadAuditStats() {
    try {
        const res = await fetch(API_BASE + '/audit/stats', { headers: apiHeaders() });
        if (res.ok) {
            const json = await res.json();
            const d = json.data || json;
            document.getElementById('statTotal').textContent = d.total || d.total_events || '0';
            document.getElementById('statLogins').textContent = d.logins_today || '0';
            document.getElementById('statChanges').textContent = d.changes_today || '0';
            document.getElementById('statSecurity').textContent = d.security_events || '0';
        }
    } catch (e) { /* stats not critical */ }
}
</script>
@endsection
