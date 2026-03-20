@extends('partials.layouts.master-employee')
@section('title', 'My Clients — WinCase Staff')
@section('page-title', 'My Clients')

@section('css')
<style>
    .client-card { transition: box-shadow .2s, transform .15s; cursor: pointer; }
    .client-card:hover { box-shadow: 0 4px 15px rgba(1,94,167,.12); transform: translateY(-2px); }
    .client-avatar { width: 48px; height: 48px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: .85rem; flex-shrink: 0; }
</style>
@endsection

@section('content')
<!-- Filters -->
<div class="row mb-3">
    <div class="col-12">
        <div class="card">
            <div class="card-body py-2">
                <div class="d-flex flex-wrap align-items-center gap-3">
                    <div class="d-flex align-items-center gap-2">
                        <span class="fw-semibold text-muted" style="font-size:.8rem;">Filter:</span>
                        <button class="btn btn-sm btn-success active" data-filter="all">All (<span id="cntAll">0</span>)</button>
                        <button class="btn btn-sm btn-outline-secondary" data-filter="active">Active (<span id="cntActive">0</span>)</button>
                        <button class="btn btn-sm btn-outline-secondary" data-filter="pending">Pending (<span id="cntPending">0</span>)</button>
                        <button class="btn btn-sm btn-outline-secondary" data-filter="completed">Completed (<span id="cntCompleted">0</span>)</button>
                    </div>
                    <div class="ms-auto">
                        <input type="text" class="form-control form-control-sm" placeholder="Search clients..." style="width:200px;" id="clientSearch">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Client Cards -->
<div class="row g-3" id="clientGrid">
    <div class="col-12 text-center py-5" id="loadingState">
        <div class="spinner-border text-primary" role="status"></div>
        <p class="text-muted mt-2">Loading your clients...</p>
    </div>
</div>

<!-- Empty state -->
<div class="d-none" id="emptyState">
    <div class="text-center py-5">
        <i class="ri-group-line" style="font-size:3rem;color:#ccc;"></i>
        <h5 class="text-muted mt-3">No clients assigned yet</h5>
        <p class="text-muted">Clients will appear here when Boss assigns them to you.</p>
    </div>
</div>

<!-- Client Detail Modal -->
<div class="modal fade" id="clientModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="ri-user-line me-2"></i>Client Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="clientModalBody">
                <div class="text-center py-4"><div class="spinner-border text-primary"></div></div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
const API = '/api/v1/staff';
const TOKEN = localStorage.getItem('wc_token');
const H = { 'Accept': 'application/json', 'Authorization': 'Bearer ' + TOKEN };

let allClients = [];
let currentFilter = 'all';

const COLORS = [
    'rgba(1,94,167,.15)', 'rgba(13,110,253,.15)', 'rgba(255,87,34,.15)',
    'rgba(156,39,176,.15)', 'rgba(233,30,99,.15)', 'rgba(0,150,136,.15)',
    'rgba(121,85,72,.15)', 'rgba(255,152,0,.15)', 'rgba(63,81,181,.15)',
    'rgba(244,67,54,.15)', 'rgba(76,175,80,.15)', 'rgba(96,125,139,.15)'
];
const TEXT_COLORS = [
    '#015EA7', '#0d6efd', '#ff5722', '#9c27b0', '#e91e63', '#009688',
    '#795548', '#ff9800', '#3f51b5', '#f44336', '#4caf50', '#607d8b'
];

function getInitials(name) {
    return (name || '??').split(' ').map(w => w[0]).join('').slice(0, 2).toUpperCase();
}

function getColor(id) {
    const idx = (id || 0) % COLORS.length;
    return { bg: COLORS[idx], text: TEXT_COLORS[idx] };
}

function statusBadge(status) {
    const map = {
        'active': '<span class="badge bg-success-subtle text-success">Active</span>',
        'pending': '<span class="badge bg-warning-subtle text-warning">Pending</span>',
        'completed': '<span class="badge bg-success">Completed</span>',
        'archived': '<span class="badge bg-secondary-subtle text-secondary">Archived</span>',
    };
    return map[status] || '<span class="badge bg-secondary-subtle text-secondary">' + (status || 'N/A') + '</span>';
}

function renderClients(clients) {
    var grid = document.getElementById('clientGrid');
    var empty = document.getElementById('emptyState');
    var loading = document.getElementById('loadingState');
    if (loading) loading.remove();

    if (!clients.length) {
        grid.innerHTML = '';
        empty.classList.remove('d-none');
        return;
    }
    empty.classList.add('d-none');

    grid.innerHTML = clients.map(function(c) {
        var name = c.name || ((c.first_name || '') + ' ' + (c.last_name || '')).trim() || 'Unknown';
        var initials = getInitials(name);
        var color = getColor(c.id);
        var st = c.status || 'pending';
        var cs = c.cases && c.cases[0] ? c.cases[0] : null;

        var caseHtml = '';
        if (cs) {
            caseHtml = '<div class="mt-2">';
            caseHtml += '<small class="text-muted d-block"><i class="ri-folder-line me-1"></i>' + (cs.case_number || 'Case #' + cs.id) + '</small>';
            if (cs.service_type || cs.type) caseHtml += '<small class="text-muted d-block"><i class="ri-file-text-line me-1"></i>' + (cs.service_type || cs.type) + '</small>';
            if (cs.deadline) caseHtml += '<small class="text-muted d-block"><i class="ri-calendar-line me-1"></i>Deadline: ' + new Date(cs.deadline).toLocaleDateString('en-US', {month:'short', day:'numeric', year:'numeric'}) + '</small>';
            if (cs.progress_percentage !== undefined && cs.progress_percentage !== null) {
                var pct = cs.progress_percentage;
                var barColor = pct >= 80 ? 'success' : pct >= 40 ? 'warning' : 'primary';
                caseHtml += '<div class="mt-2"><div class="progress" style="height:4px;"><div class="progress-bar bg-' + barColor + '" style="width:' + pct + '%"></div></div>';
                caseHtml += '<small class="text-muted" style="font-size:.65rem;">' + pct + '% — ' + (cs.status || '') + '</small></div>';
            }
            caseHtml += '</div>';
        } else {
            caseHtml = '<div class="mt-2"><small class="text-muted"><i class="ri-information-line me-1"></i>No cases yet</small></div>';
        }

        return '<div class="col-md-6 col-xl-4 client-item" data-status="' + st + '" data-name="' + name.toLowerCase() + '">' +
            '<div class="card client-card" onclick="showClient(' + c.id + ')">' +
            '<div class="card-body"><div class="d-flex align-items-start gap-3">' +
            '<div class="client-avatar" style="background:' + color.bg + ';color:' + color.text + ';">' + initials + '</div>' +
            '<div class="flex-grow-1">' +
            '<div class="d-flex justify-content-between align-items-start"><div>' +
            '<h6 class="mb-0">' + name + '</h6>' +
            '<small class="text-muted">' + (c.nationality || '') + (c.phone ? ' &bull; ' + c.phone : '') + '</small>' +
            '</div>' + statusBadge(st) + '</div>' +
            caseHtml +
            '</div></div></div></div></div>';
    }).join('');
}

function updateCounts() {
    document.getElementById('cntAll').textContent = allClients.length;
    document.getElementById('cntActive').textContent = allClients.filter(function(c) { return c.status === 'active'; }).length;
    document.getElementById('cntPending').textContent = allClients.filter(function(c) { return c.status === 'pending'; }).length;
    document.getElementById('cntCompleted').textContent = allClients.filter(function(c) { return c.status === 'completed'; }).length;
}

function applyFilter(filter) {
    currentFilter = filter;
    document.querySelectorAll('[data-filter]').forEach(function(b) {
        b.classList.remove('btn-success', 'active');
        b.classList.add('btn-outline-secondary');
    });
    var active = document.querySelector('[data-filter="' + filter + '"]');
    if (active) { active.classList.remove('btn-outline-secondary'); active.classList.add('btn-success', 'active'); }

    document.querySelectorAll('.client-item').forEach(function(el) {
        el.style.display = (filter === 'all' || el.dataset.status === filter) ? '' : 'none';
    });
}

async function loadClients() {
    try {
        var r = await fetch(API + '/clients?per_page=100', { headers: H });
        if (!r.ok) { console.error('Failed to load clients', r.status); return; }
        var j = await r.json();
        var data = j.data || j;
        allClients = data.data || data || [];
        if (!Array.isArray(allClients)) allClients = [];
        renderClients(allClients);
        updateCounts();
    } catch(e) {
        console.error('Error loading clients', e);
        var loading = document.getElementById('loadingState');
        if (loading) loading.remove();
        document.getElementById('emptyState').classList.remove('d-none');
    }
}

async function showClient(id) {
    var modal = new bootstrap.Modal(document.getElementById('clientModal'));
    var body = document.getElementById('clientModalBody');
    body.innerHTML = '<div class="text-center py-4"><div class="spinner-border text-primary"></div></div>';
    modal.show();

    try {
        var r = await fetch(API + '/clients/' + id, { headers: H });
        if (!r.ok) { body.innerHTML = '<div class="alert alert-danger">Failed to load client</div>'; return; }
        var j = await r.json();
        var d = j.data || j;
        var c = d.client || d;
        var stats = d.stats || {};
        var name = c.name || ((c.first_name || '') + ' ' + (c.last_name || '')).trim() || 'Unknown';

        var docsHtml = '';
        if (c.documents && c.documents.length) {
            docsHtml = c.documents.map(function(doc) {
                var isPdf = doc.mime_type && doc.mime_type.includes('pdf');
                var stColor = doc.status === 'approved' ? 'success' : doc.status === 'pending_review' ? 'warning' : 'secondary';
                return '<div class="list-group-item d-flex align-items-center">' +
                    '<i class="ri-file-' + (isPdf ? 'pdf' : 'text') + '-line ' + (isPdf ? 'text-danger' : 'text-primary') + ' me-2 fs-5"></i>' +
                    '<div class="flex-grow-1"><div style="font-size:.85rem;">' + (doc.name || doc.original_filename || 'Document') + '</div>' +
                    '<small class="text-muted">' + (doc.type || '') + (doc.created_at ? ' &bull; ' + new Date(doc.created_at).toLocaleDateString() : '') + '</small></div>' +
                    '<span class="badge bg-' + stColor + '">' + (doc.status || 'N/A') + '</span></div>';
            }).join('');
        } else {
            docsHtml = '<p class="text-muted">No documents yet</p>';
        }

        var casesHtml = '';
        if (c.cases && c.cases.length) {
            casesHtml = c.cases.map(function(cs) {
                var csColor = cs.status === 'completed' ? 'success' : cs.status === 'active' ? 'primary' : 'warning';
                return '<div class="card mb-2"><div class="card-body py-2">' +
                    '<div class="d-flex justify-content-between align-items-center"><div>' +
                    '<strong>' + (cs.case_number || '#' + cs.id) + '</strong>' +
                    '<span class="text-muted ms-2">' + (cs.service_type || cs.type || '') + '</span></div>' +
                    '<span class="badge bg-' + csColor + '-subtle text-' + csColor + '">' + (cs.status || '') + '</span></div>' +
                    (cs.deadline ? '<small class="text-muted"><i class="ri-calendar-line me-1"></i>Deadline: ' + new Date(cs.deadline).toLocaleDateString() + '</small>' : '') +
                    '</div></div>';
            }).join('');
        } else {
            casesHtml = '<p class="text-muted">No cases yet</p>';
        }

        body.innerHTML = '<ul class="nav nav-tabs" role="tablist">' +
            '<li class="nav-item"><a class="nav-link active" data-bs-toggle="tab" href="#clInfo">Info</a></li>' +
            '<li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#clDocs">Documents (' + (stats.documents_count || 0) + ')</a></li>' +
            '<li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#clCases">Cases (' + (stats.total_cases || 0) + ')</a></li>' +
            '</ul>' +
            '<div class="tab-content pt-3">' +
            '<div class="tab-pane fade show active" id="clInfo"><div class="row g-3">' +
            '<div class="col-md-6"><label class="text-muted small">Full Name</label><div class="fw-semibold">' + name + '</div></div>' +
            '<div class="col-md-6"><label class="text-muted small">Nationality</label><div class="fw-semibold">' + (c.nationality || '\u2014') + '</div></div>' +
            '<div class="col-md-6"><label class="text-muted small">Phone</label><div class="fw-semibold">' + (c.phone || '\u2014') + '</div></div>' +
            '<div class="col-md-6"><label class="text-muted small">Email</label><div class="fw-semibold">' + (c.email || '\u2014') + '</div></div>' +
            '<div class="col-md-6"><label class="text-muted small">PESEL</label><div class="fw-semibold">' + (c.pesel || '\u2014') + '</div></div>' +
            '<div class="col-md-6"><label class="text-muted small">Passport</label><div class="fw-semibold">' + (c.passport_number || '\u2014') + '</div></div>' +
            '<div class="col-md-6"><label class="text-muted small">Address</label><div class="fw-semibold">' + (c.address || '\u2014') + ', ' + (c.city || '') + ' ' + (c.postal_code || '') + '</div></div>' +
            '<div class="col-md-6"><label class="text-muted small">Status</label><div>' + statusBadge(c.status) + '</div></div>' +
            (c.notes ? '<div class="col-12"><label class="text-muted small">Notes</label><div style="font-size:.85rem;">' + c.notes + '</div></div>' : '') +
            '</div></div>' +
            '<div class="tab-pane fade" id="clDocs">' + docsHtml + '</div>' +
            '<div class="tab-pane fade" id="clCases">' + casesHtml + '</div>' +
            '</div>';
    } catch(e) {
        body.innerHTML = '<div class="alert alert-danger">Connection error</div>';
    }
}

// Search
document.getElementById('clientSearch').addEventListener('input', function() {
    var q = this.value.toLowerCase();
    document.querySelectorAll('.client-item').forEach(function(el) {
        var matchesFilter = currentFilter === 'all' || el.dataset.status === currentFilter;
        var matchesSearch = !q || el.dataset.name.includes(q) || el.textContent.toLowerCase().includes(q);
        el.style.display = (matchesFilter && matchesSearch) ? '' : 'none';
    });
});

// Filter buttons
document.querySelectorAll('[data-filter]').forEach(function(btn) {
    btn.addEventListener('click', function() { applyFilter(this.dataset.filter); });
});

// Load on page ready
loadClients();
</script>
@endsection
