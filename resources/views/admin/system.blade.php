@extends('partials.layouts.master')
@section('title', 'System | WinCase CRM')
@section('sub-title', 'System')
@section('sub-title-lang', 'wc-system')
@section('pagetitle', 'Admin')
@section('pagetitle-lang', 'wc-admin')
@section('content')
<div class="row">
    <div class="col-xl-4">
        <div class="card"><div class="card-header"><h5 class="card-title mb-0">System Health</h5></div>
        <div class="card-body" id="healthStatus">
            <div class="text-center py-3"><span class="spinner-border spinner-border-sm"></span> Checking...</div>
        </div></div>
    </div>
    <div class="col-xl-4">
        <div class="card card-h-100"><div class="card-header"><h5 class="card-title mb-0">Server Info</h5></div>
        <div class="card-body">
            <div class="d-flex justify-content-between mb-2"><span class="text-muted">PHP</span><span id="infoPHP">—</span></div>
            <div class="d-flex justify-content-between mb-2"><span class="text-muted">Laravel</span><span id="infoLaravel">—</span></div>
            <div class="d-flex justify-content-between mb-2"><span class="text-muted">MySQL</span><span id="infoMySQL">—</span></div>
            <div class="d-flex justify-content-between mb-2"><span class="text-muted">Disk Usage</span><span id="infoDisk">—</span></div>
            <div class="d-flex justify-content-between mb-2"><span class="text-muted">Memory</span><span id="infoMemory">—</span></div>
            <div class="d-flex justify-content-between"><span class="text-muted">Uptime</span><span id="infoUptime">—</span></div>
        </div></div>
    </div>
    <div class="col-xl-4">
        <div class="card card-h-100"><div class="card-header"><h5 class="card-title mb-0">Quick Actions</h5></div>
        <div class="card-body d-flex flex-column gap-2">
            <button class="btn btn-subtle-primary text-start" onclick="sysAction('cache/clear')"><i class="ri-refresh-line me-2"></i>Clear Cache</button>
            <button class="btn btn-subtle-primary text-start" onclick="sysAction('cache/optimize')"><i class="ri-speed-up-line me-2"></i>Optimize Cache</button>
            <button class="btn btn-subtle-warning text-start" onclick="sysAction('maintenance/enable')"><i class="ri-tools-line me-2"></i>Enable Maintenance Mode</button>
            <button class="btn btn-subtle-success text-start" onclick="sysAction('maintenance/disable')"><i class="ri-play-line me-2"></i>Disable Maintenance Mode</button>
        </div></div>
    </div>
</div>

<div class="row mt-3">
    <div class="col-xl-6">
        <div class="card">
            <div class="card-header"><h5 class="card-title mb-0">Database Statistics</h5></div>
            <div class="card-body" id="dbStats">
                <div class="text-center py-3"><span class="spinner-border spinner-border-sm"></span> Loading...</div>
            </div>
        </div>
    </div>
    <div class="col-xl-6">
        <div class="card">
            <div class="card-header"><h5 class="card-title mb-0">API Statistics</h5></div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-2"><span class="text-muted">Total Endpoints</span><span class="fw-medium">251+</span></div>
                <div class="d-flex justify-content-between mb-2"><span class="text-muted">Auth Type</span><span class="fw-medium">Sanctum Bearer Token</span></div>
                <div class="d-flex justify-content-between mb-2"><span class="text-muted">Rate Limit</span><span class="fw-medium">60/min per user</span></div>
                <div class="d-flex justify-content-between mb-2"><span class="text-muted">API Version</span><span class="fw-medium">v1</span></div>
                <div class="d-flex justify-content-between"><span class="text-muted">Roles</span><span class="fw-medium">boss, staff, user</span></div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
const API_BASE = '/api/v1';
function getToken() { return localStorage.getItem('wc_token') || ''; }
function apiHeaders() { return { 'Authorization': 'Bearer ' + getToken(), 'Content-Type': 'application/json', 'Accept': 'application/json' }; }

document.addEventListener('DOMContentLoaded', loadSystemInfo);

async function loadSystemInfo() {
    try {
        const res = await fetch(API_BASE + '/system/health', { headers: apiHeaders() });
        if (res.ok) {
            const json = await res.json();
            const h = json.data || json;
            const services = h.services || { database: h.database || 'ok', cache: h.cache || 'ok', queue: h.queue || 'unknown', storage: h.storage || 'ok', mail: h.mail || 'unknown', scheduler: h.scheduler || 'unknown' };
            let html = '';
            for (const [name, status] of Object.entries(services)) {
                const isOk = status === 'ok' || status === true || status === 'running';
                html += `<div class="d-flex justify-content-between mb-3"><span>${name.charAt(0).toUpperCase() + name.slice(1)}</span><span class="badge ${isOk ? 'bg-success' : 'bg-danger'}">${isOk ? 'OK' : status}</span></div>`;
            }
            document.getElementById('healthStatus').innerHTML = html;
            if (h.server) {
                document.getElementById('infoPHP').textContent = h.server.php || '—';
                document.getElementById('infoLaravel').textContent = h.server.laravel || '—';
                document.getElementById('infoMySQL').textContent = h.server.mysql || '—';
                document.getElementById('infoDisk').textContent = h.server.disk || '—';
                document.getElementById('infoMemory').textContent = h.server.memory || '—';
                document.getElementById('infoUptime').textContent = h.server.uptime || '—';
            }
            if (h.database_stats) {
                let dbHtml = '';
                for (const [table, count] of Object.entries(h.database_stats)) {
                    dbHtml += `<div class="d-flex justify-content-between mb-2"><span class="text-muted">${table}</span><span class="fw-medium">${count} records</span></div>`;
                }
                document.getElementById('dbStats').innerHTML = dbHtml || '<p class="text-muted">No data</p>';
            } else {
                document.getElementById('dbStats').innerHTML = '<p class="text-muted">Stats not available from API</p>';
            }
        } else {
            document.getElementById('healthStatus').innerHTML = '<div class="alert alert-warning mb-0">Unable to fetch. Check API token.</div>';
        }
    } catch (e) {
        document.getElementById('healthStatus').innerHTML = '<div class="alert alert-danger mb-0">Connection error</div>';
    }
}

async function sysAction(action) {
    if (!confirm('Execute: ' + action + '?')) return;
    try {
        const res = await fetch(API_BASE + '/system/' + action, { method: 'POST', headers: apiHeaders() });
        const json = await res.json();
        alert(res.ok ? 'Success: ' + (json.message || action) : 'Error: ' + (json.message || 'Failed'));
    } catch (e) { alert('Error: ' + e.message); }
}
</script>
@endsection
