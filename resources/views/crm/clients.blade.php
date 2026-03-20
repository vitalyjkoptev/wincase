@extends('partials.layouts.master')

@section('title', 'Clients | WinCase CRM')
@section('sub-title', 'Clients')
@section('sub-title-lang', 'wc-clients')
@section('pagetitle', 'CRM')
@section('pagetitle-lang', 'wc-title-crm')
@section('buttonTitle', 'Add Client')
@section('buttonTitle-lang', 'wc-add-client')
@section('modalTarget', 'addClientModal')

@section('content')
<!-- Stats Cards -->
<div class="row" id="statsRow">
    <div class="col-xl-3 col-md-6">
        <div class="card card-h-100">
            <div class="card-body">
                <div class="d-flex align-items-center gap-3">
                    <div class="avatar avatar-sm bg-primary-subtle text-primary rounded-2"><i class="ri-group-line fs-18"></i></div>
                    <div>
                        <p class="text-muted mb-0 fs-13">Total Clients</p>
                        <h4 class="mb-0 fw-semibold" id="statTotal">—</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card card-h-100">
            <div class="card-body">
                <div class="d-flex align-items-center gap-3">
                    <div class="avatar avatar-sm bg-success-subtle text-success rounded-2"><i class="ri-user-star-line fs-18"></i></div>
                    <div>
                        <p class="text-muted mb-0 fs-13">Active</p>
                        <h4 class="mb-0 fw-semibold" id="statActive">—</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card card-h-100">
            <div class="card-body">
                <div class="d-flex align-items-center gap-3">
                    <div class="avatar avatar-sm bg-warning-subtle text-warning rounded-2"><i class="ri-time-line fs-18"></i></div>
                    <div>
                        <p class="text-muted mb-0 fs-13">Pending</p>
                        <h4 class="mb-0 fw-semibold" id="statPending">—</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card card-h-100">
            <div class="card-body">
                <div class="d-flex align-items-center gap-3">
                    <div class="avatar avatar-sm bg-secondary-subtle text-secondary rounded-2"><i class="ri-archive-line fs-18"></i></div>
                    <div>
                        <p class="text-muted mb-0 fs-13">Archived</p>
                        <h4 class="mb-0 fw-semibold" id="statArchived">—</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="card">
    <div class="card-body py-2">
        <div class="row g-2 align-items-end">
            <div class="col-md-3">
                <input type="text" class="form-control form-control-sm" placeholder="Search name, phone, email..." id="fSearch">
            </div>
            <div class="col-md-2">
                <select class="form-select form-select-sm" id="fStatus">
                    <option value="">All Statuses</option>
                    <option value="active">Active</option>
                    <option value="pending">Pending</option>
                    <option value="archived">Archived</option>
                </select>
            </div>
            <div class="col-md-2">
                <select class="form-select form-select-sm" id="fNationality">
                    <option value="">All Nationalities</option>
                    <option>Ukrainian</option>
                    <option>Belarusian</option>
                    <option>Georgian</option>
                    <option>Indian</option>
                    <option>Uzbek</option>
                    <option>Moldovan</option>
                    <option>Russian</option>
                </select>
            </div>
            <div class="col-md-2">
                <select class="form-select form-select-sm" id="fStaff">
                    <option value="">All Staff</option>
                </select>
            </div>
            <div class="col-md-2">
                <select class="form-select form-select-sm" id="fPerPage">
                    <option value="25">25 per page</option>
                    <option value="50">50 per page</option>
                    <option value="100">100 per page</option>
                </select>
            </div>
            <div class="col-md-1">
                <button class="btn btn-primary btn-sm w-100" id="btnFilter"><i class="ri-filter-3-line"></i></button>
            </div>
        </div>
    </div>
</div>

<!-- Clients Table -->
<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" id="clientsTable">
                <thead class="table-light">
                    <tr>
                        <th style="width:40px;"><input class="form-check-input" type="checkbox" id="selectAll"></th>
                        <th>Client</th>
                        <th>Phone</th>
                        <th>Nationality</th>
                        <th>Cases</th>
                        <th>Status</th>
                        <th>Assigned Staff</th>
                        <th>Created</th>
                        <th style="width:80px;">Actions</th>
                    </tr>
                </thead>
                <tbody id="clientsBody">
                    <tr><td colspan="9" class="text-center py-4"><div class="spinner-border text-primary spinner-border-sm"></div> Loading clients...</td></tr>
                </tbody>
            </table>
        </div>
        <!-- Pagination -->
        <div class="d-flex justify-content-between align-items-center p-3" id="paginationRow" style="display:none!important;">
            <small class="text-muted" id="paginationInfo"></small>
            <nav><ul class="pagination pagination-sm mb-0" id="paginationNav"></ul></nav>
        </div>
    </div>
</div>

<!-- ============================================ -->
<!-- VIEW CLIENT MODAL -->
<!-- ============================================ -->
<div class="modal fade" id="viewModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="ri-user-line me-2"></i>Client Profile</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="viewBody">
                <div class="text-center py-4"><div class="spinner-border text-primary"></div></div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-light" data-bs-dismiss="modal">Close</button>
                <button class="btn btn-primary" id="viewEditBtn"><i class="ri-edit-line me-1"></i>Edit</button>
            </div>
        </div>
    </div>
</div>

<!-- ============================================ -->
<!-- EDIT CLIENT MODAL -->
<!-- ============================================ -->
<div class="modal fade" id="editModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="ri-edit-line me-2"></i>Edit Client</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="editId">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="editName">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Phone <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="editPhone">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" id="editEmail">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Nationality</label>
                        <select class="form-select" id="editNationality">
                            <option value="">—</option>
                            <option value="UA">Ukrainian</option>
                            <option value="BY">Belarusian</option>
                            <option value="GE">Georgian</option>
                            <option value="IN">Indian</option>
                            <option value="UZ">Uzbek</option>
                            <option value="MD">Moldovan</option>
                            <option value="RU">Russian</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Language</label>
                        <select class="form-select" id="editLanguage">
                            <option value="">—</option>
                            <option value="uk">Ukrainian</option>
                            <option value="ru">Russian</option>
                            <option value="pl">Polish</option>
                            <option value="en">English</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Passport</label>
                        <input type="text" class="form-control" id="editPassport">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">PESEL</label>
                        <input type="text" class="form-control" id="editPesel" maxlength="11">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Date of Birth</label>
                        <input type="date" class="form-control" id="editDob">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">City</label>
                        <input type="text" class="form-control" id="editCity">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Address</label>
                        <input type="text" class="form-control" id="editAddress">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Assigned Staff</label>
                        <select class="form-select" id="editStaff">
                            <option value="">— Not assigned —</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Status</label>
                        <select class="form-select" id="editStatus">
                            <option value="active">Active</option>
                            <option value="pending">Pending</option>
                            <option value="archived">Archived</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Notes</label>
                        <textarea class="form-control" rows="2" id="editNotes"></textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <button class="btn btn-primary" id="editSaveBtn"><i class="ri-save-line me-1"></i>Save</button>
            </div>
        </div>
    </div>
</div>

<!-- ============================================ -->
<!-- ADD CLIENT MODAL -->
<!-- ============================================ -->
<div class="modal fade" id="addClientModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="ri-user-add-line me-2"></i>Add New Client</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="addName" placeholder="Full name">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Phone <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="addPhone" placeholder="+48 XXX XXX XXX">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" id="addEmail" placeholder="email@example.com">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Nationality</label>
                        <select class="form-select" id="addNationality">
                            <option value="">—</option>
                            <option value="UA">Ukrainian</option>
                            <option value="BY">Belarusian</option>
                            <option value="GE">Georgian</option>
                            <option value="IN">Indian</option>
                            <option value="UZ">Uzbek</option>
                            <option value="MD">Moldovan</option>
                            <option value="RU">Russian</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Language</label>
                        <select class="form-select" id="addLanguage">
                            <option value="">—</option>
                            <option value="uk">Ukrainian</option>
                            <option value="ru">Russian</option>
                            <option value="pl">Polish</option>
                            <option value="en">English</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Assigned Staff</label>
                        <select class="form-select" id="addStaff">
                            <option value="">— Not assigned —</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">City</label>
                        <input type="text" class="form-control" id="addCity" placeholder="e.g. Warszawa">
                    </div>
                    <div class="col-12">
                        <label class="form-label">Notes</label>
                        <textarea class="form-control" rows="2" id="addNotes" placeholder="Notes..."></textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <button class="btn btn-primary" id="addSaveBtn"><i class="ri-save-line me-1"></i>Save Client</button>
            </div>
        </div>
    </div>
</div>

<!-- ============================================ -->
<!-- ASSIGN STAFF MODAL -->
<!-- ============================================ -->
<div class="modal fade" id="assignModal" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="ri-user-settings-line me-2"></i>Assign Staff</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="assignClientId">
                <p class="mb-2">Assign staff to <strong id="assignClientName"></strong>:</p>
                <select class="form-select" id="assignStaffSelect">
                    <option value="">— Not assigned —</option>
                </select>
            </div>
            <div class="modal-footer">
                <button class="btn btn-light btn-sm" data-bs-dismiss="modal">Cancel</button>
                <button class="btn btn-primary btn-sm" id="assignSaveBtn"><i class="ri-check-line me-1"></i>Assign</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
(function() {
    var TOKEN = localStorage.getItem('wc_token');
    var H = { 'Accept': 'application/json', 'Authorization': 'Bearer ' + TOKEN };
    var staffList = [];
    var currentPage = 1;
    var currentClientId = null;

    // ===== HELPERS =====
    function toast(msg, type) {
        var t = document.createElement('div');
        t.className = 'position-fixed top-0 end-0 p-3';
        t.style.zIndex = '9999';
        t.innerHTML = '<div class="alert alert-' + (type || 'success') + ' alert-dismissible fade show shadow">' + msg + '<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>';
        document.body.appendChild(t);
        setTimeout(function() { t.remove(); }, 3000);
    }

    var STATUS_MAP = {
        'active': '<span class="badge bg-success-subtle text-success">Active</span>',
        'pending': '<span class="badge bg-warning-subtle text-warning">Pending</span>',
        'archived': '<span class="badge bg-secondary-subtle text-secondary">Archived</span>',
    };

    var NAT_MAP = {
        'UA': 'Ukrainian', 'BY': 'Belarusian', 'GE': 'Georgian', 'IN': 'Indian',
        'UZ': 'Uzbek', 'MD': 'Moldovan', 'RU': 'Russian',
    };

    function statusBadge(s) { return STATUS_MAP[s] || '<span class="badge bg-secondary">' + (s || '—') + '</span>'; }
    function natLabel(code) { return NAT_MAP[code] || code || '—'; }
    function fmtDate(d) { if (!d) return '—'; return new Date(d).toLocaleDateString('en-GB', { day: '2-digit', month: 'short', year: 'numeric' }); }
    function initials(name) { return (name || '??').split(' ').map(function(w) { return w[0]; }).join('').slice(0, 2).toUpperCase(); }

    // ===== LOAD STAFF LIST =====
    async function loadStaff() {
        try {
            var r = await fetch('/api/v1/users?role=staff&role=boss', { headers: H });
            if (!r.ok) return;
            var j = await r.json();
            staffList = Array.isArray(j.data) ? j.data : [];
            ['fStaff', 'editStaff', 'addStaff', 'assignStaffSelect'].forEach(function(id) {
                var sel = document.getElementById(id);
                if (!sel) return;
                while (sel.options.length > 1) sel.remove(1);
                staffList.forEach(function(s) {
                    sel.add(new Option(s.name + ' (' + s.role + ')', s.id));
                });
            });
        } catch(e) { console.error('Staff load failed', e); }
    }

    // ===== LOAD CLIENTS =====
    async function loadClients(page) {
        page = page || 1;
        currentPage = page;
        var body = document.getElementById('clientsBody');
        body.innerHTML = '<tr><td colspan="9" class="text-center py-4"><div class="spinner-border text-primary spinner-border-sm"></div> Loading...</td></tr>';

        var params = new URLSearchParams();
        params.set('page', page);
        params.set('per_page', document.getElementById('fPerPage').value);
        var search = document.getElementById('fSearch').value.trim();
        if (search) params.set('search', search);
        var status = document.getElementById('fStatus').value;
        if (status) params.set('status', status);
        var nat = document.getElementById('fNationality').value;
        if (nat) params.set('nationality', nat);
        var staff = document.getElementById('fStaff').value;
        if (staff) params.set('assigned_to', staff);

        try {
            var r = await fetch('/api/v1/clients?' + params.toString(), { headers: H });
            if (!r.ok) { body.innerHTML = '<tr><td colspan="9" class="text-center py-4 text-danger">Failed to load clients</td></tr>'; return; }
            var j = await r.json();
            var data = j.data || {};
            var clients = data.data || [];
            var meta = data.meta || {};

            // Stats
            updateStats(meta.total || clients.length);

            if (!clients.length) {
                body.innerHTML = '<tr><td colspan="9" class="text-center py-5 text-muted"><i class="ri-group-line fs-1 d-block mb-2"></i>No clients found</td></tr>';
                document.getElementById('paginationRow').style.display = 'none';
                return;
            }

            body.innerHTML = clients.map(function(c) {
                var name = c.name || ((c.first_name || '') + ' ' + (c.last_name || '')).trim() || 'Unknown';
                var casesCount = c.cases ? c.cases.length : 0;
                var staffName = c.assigned_manager ? c.assigned_manager.name : '—';

                return '<tr data-id="' + c.id + '">' +
                    '<td><input class="form-check-input row-check" type="checkbox" value="' + c.id + '"></td>' +
                    '<td><div class="d-flex align-items-center gap-2">' +
                        '<div class="avatar avatar-xs avatar-rounded bg-primary-subtle text-primary" style="font-size:.65rem;">' + initials(name) + '</div>' +
                        '<div><a href="#" class="fw-semibold text-body d-block view-link" data-id="' + c.id + '">' + name + '</a>' +
                        '<small class="text-muted">' + (c.email || '') + '</small></div></div></td>' +
                    '<td><a href="tel:' + (c.phone || '').replace(/\s/g, '') + '" class="text-body">' + (c.phone || '—') + '</a></td>' +
                    '<td><span class="badge bg-info-subtle text-info fs-10">' + natLabel(c.nationality) + '</span></td>' +
                    '<td>' + (casesCount > 0 ? '<span class="badge bg-primary">' + casesCount + '</span>' : '<span class="text-muted">0</span>') + '</td>' +
                    '<td>' + statusBadge(c.status) + '</td>' +
                    '<td>' + staffName + '</td>' +
                    '<td class="text-muted fs-12">' + fmtDate(c.created_at) + '</td>' +
                    '<td><div class="dropdown"><button class="btn btn-sm btn-subtle-secondary" data-bs-toggle="dropdown"><i class="ri-more-2-fill"></i></button>' +
                    '<ul class="dropdown-menu">' +
                        '<li><a class="dropdown-item view-link" href="#" data-id="' + c.id + '"><i class="ri-eye-line me-2"></i>View</a></li>' +
                        '<li><a class="dropdown-item edit-link" href="#" data-id="' + c.id + '"><i class="ri-edit-line me-2"></i>Edit</a></li>' +
                        '<li><a class="dropdown-item assign-link" href="#" data-id="' + c.id + '" data-name="' + name + '"><i class="ri-user-settings-line me-2"></i>Assign Staff</a></li>' +
                        '<li><hr class="dropdown-divider"></li>' +
                        (c.status !== 'archived'
                            ? '<li><a class="dropdown-item text-warning archive-link" href="#" data-id="' + c.id + '"><i class="ri-archive-line me-2"></i>Archive</a></li>'
                            : '<li><a class="dropdown-item text-success activate-link" href="#" data-id="' + c.id + '"><i class="ri-inbox-unarchive-line me-2"></i>Activate</a></li>') +
                    '</ul></div></td></tr>';
            }).join('');

            // Pagination
            if (meta.last_page > 1) {
                document.getElementById('paginationRow').style.display = '';
                document.getElementById('paginationInfo').textContent = 'Page ' + meta.current_page + ' of ' + meta.last_page + ' (' + meta.total + ' total)';
                var nav = '';
                nav += '<li class="page-item ' + (meta.current_page <= 1 ? 'disabled' : '') + '"><a class="page-link page-btn" href="#" data-page="' + (meta.current_page - 1) + '">&laquo;</a></li>';
                for (var p = 1; p <= meta.last_page; p++) {
                    if (meta.last_page > 7 && Math.abs(p - meta.current_page) > 2 && p !== 1 && p !== meta.last_page) {
                        if (p === 2 || p === meta.last_page - 1) nav += '<li class="page-item disabled"><span class="page-link">...</span></li>';
                        continue;
                    }
                    nav += '<li class="page-item ' + (p === meta.current_page ? 'active' : '') + '"><a class="page-link page-btn" href="#" data-page="' + p + '">' + p + '</a></li>';
                }
                nav += '<li class="page-item ' + (meta.current_page >= meta.last_page ? 'disabled' : '') + '"><a class="page-link page-btn" href="#" data-page="' + (meta.current_page + 1) + '">&raquo;</a></li>';
                document.getElementById('paginationNav').innerHTML = nav;
            } else {
                document.getElementById('paginationRow').style.display = 'none';
                if (meta.total) document.getElementById('paginationInfo').textContent = meta.total + ' clients';
            }
        } catch(e) {
            console.error('Load clients error', e);
            body.innerHTML = '<tr><td colspan="9" class="text-center py-4 text-danger">Connection error</td></tr>';
        }
    }

    function updateStats(total) {
        document.getElementById('statTotal').textContent = total || 0;
        // Load detailed stats asynchronously
        fetch('/api/v1/clients?status=active&per_page=1', { headers: H }).then(function(r) { return r.json(); }).then(function(j) {
            document.getElementById('statActive').textContent = (j.data && j.data.meta) ? j.data.meta.total : '—';
        }).catch(function() {});
        fetch('/api/v1/clients?status=pending&per_page=1', { headers: H }).then(function(r) { return r.json(); }).then(function(j) {
            document.getElementById('statPending').textContent = (j.data && j.data.meta) ? j.data.meta.total : '—';
        }).catch(function() {});
        fetch('/api/v1/clients?status=archived&per_page=1', { headers: H }).then(function(r) { return r.json(); }).then(function(j) {
            document.getElementById('statArchived').textContent = (j.data && j.data.meta) ? j.data.meta.total : '—';
        }).catch(function() {});
    }

    // ===== VIEW CLIENT =====
    async function viewClient(id) {
        currentClientId = id;
        var body = document.getElementById('viewBody');
        body.innerHTML = '<div class="text-center py-4"><div class="spinner-border text-primary"></div></div>';
        new bootstrap.Modal(document.getElementById('viewModal')).show();

        try {
            var r = await fetch('/api/v1/clients/' + id, { headers: H });
            if (!r.ok) { body.innerHTML = '<div class="alert alert-danger">Failed to load client</div>'; return; }
            var j = await r.json();
            var d = j.data || {};
            var c = d.client || d;
            var stats = d.stats || {};
            var timeline = d.timeline || [];
            var name = c.name || ((c.first_name || '') + ' ' + (c.last_name || '')).trim() || 'Unknown';

            var html = '';

            // Header
            html += '<div class="d-flex align-items-center gap-3 mb-3">';
            html += '<div class="avatar avatar-md avatar-rounded bg-primary-subtle text-primary" style="font-size:1rem;">' + initials(name) + '</div>';
            html += '<div><h5 class="mb-0">' + name + '</h5>';
            html += '<div>' + statusBadge(c.status) + ' <span class="badge bg-info-subtle text-info ms-1">' + natLabel(c.nationality) + '</span></div></div></div>';

            // Stats mini
            html += '<div class="row g-2 mb-3">';
            html += '<div class="col-3"><div class="border rounded p-2 text-center"><div class="text-muted fs-11">Cases</div><div class="fw-semibold">' + (stats.total_cases || 0) + '</div></div></div>';
            html += '<div class="col-3"><div class="border rounded p-2 text-center"><div class="text-muted fs-11">Active</div><div class="fw-semibold text-primary">' + (stats.active_cases || 0) + '</div></div></div>';
            html += '<div class="col-3"><div class="border rounded p-2 text-center"><div class="text-muted fs-11">Paid</div><div class="fw-semibold text-success">' + (stats.total_paid || 0) + ' PLN</div></div></div>';
            html += '<div class="col-3"><div class="border rounded p-2 text-center"><div class="text-muted fs-11">Debt</div><div class="fw-semibold text-danger">' + (stats.total_outstanding || 0) + ' PLN</div></div></div>';
            html += '</div>';

            // Tabs
            html += '<ul class="nav nav-tabs" role="tablist">';
            html += '<li class="nav-item"><a class="nav-link active" data-bs-toggle="tab" href="#vInfo">Info</a></li>';
            html += '<li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#vCases">Cases (' + (stats.total_cases || 0) + ')</a></li>';
            html += '<li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#vDocs">Docs (' + (stats.documents_count || 0) + ')</a></li>';
            html += '<li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#vTimeline">Timeline</a></li>';
            html += '</ul>';

            html += '<div class="tab-content pt-3">';

            // Info Tab
            html += '<div class="tab-pane fade show active" id="vInfo"><div class="row g-3">';
            var fields = [
                ['Name', name], ['Phone', c.phone], ['Email', c.email],
                ['Nationality', natLabel(c.nationality)], ['Passport', c.passport_number], ['PESEL', c.pesel],
                ['Date of Birth', fmtDate(c.date_of_birth)], ['City', c.city], ['Address', c.address],
                ['Language', c.preferred_language], ['Status', c.status],
                ['Staff', c.assigned_manager ? c.assigned_manager.name : '—'],
            ];
            fields.forEach(function(f) {
                html += '<div class="col-md-6"><label class="text-muted small">' + f[0] + '</label><div class="fw-semibold">' + (f[1] || '—') + '</div></div>';
            });
            if (c.notes) html += '<div class="col-12"><label class="text-muted small">Notes</label><div>' + c.notes + '</div></div>';
            html += '</div></div>';

            // Cases Tab
            html += '<div class="tab-pane fade" id="vCases">';
            if (c.cases && c.cases.length) {
                c.cases.forEach(function(cs) {
                    var csColor = cs.status === 'completed' ? 'success' : cs.status === 'active' ? 'primary' : 'warning';
                    html += '<div class="card mb-2"><div class="card-body py-2">';
                    html += '<div class="d-flex justify-content-between align-items-center"><div>';
                    html += '<strong>' + (cs.case_number || '#' + cs.id) + '</strong>';
                    html += '<span class="text-muted ms-2">' + (cs.service_type || '') + '</span></div>';
                    html += '<span class="badge bg-' + csColor + '-subtle text-' + csColor + '">' + (cs.status || '') + '</span></div>';
                    if (cs.deadline) html += '<small class="text-muted"><i class="ri-calendar-line me-1"></i>Deadline: ' + fmtDate(cs.deadline) + '</small>';
                    html += '</div></div>';
                });
            } else { html += '<p class="text-muted">No cases yet</p>'; }
            html += '</div>';

            // Docs Tab
            html += '<div class="tab-pane fade" id="vDocs">';
            if (c.documents && c.documents.length) {
                c.documents.forEach(function(doc) {
                    html += '<div class="list-group-item d-flex align-items-center">';
                    html += '<i class="ri-file-text-line text-primary me-2"></i>';
                    html += '<div class="flex-grow-1"><div style="font-size:.85rem;">' + (doc.name || doc.original_filename || 'Document') + '</div>';
                    html += '<small class="text-muted">' + (doc.type || '') + ' ' + fmtDate(doc.created_at) + '</small></div>';
                    html += '<span class="badge bg-' + (doc.status === 'approved' ? 'success' : 'warning') + '">' + (doc.status || '') + '</span></div>';
                });
            } else { html += '<p class="text-muted">No documents yet</p>'; }
            html += '</div>';

            // Timeline Tab
            html += '<div class="tab-pane fade" id="vTimeline">';
            if (timeline.length) {
                timeline.forEach(function(ev) {
                    var icon = ev.type === 'case_opened' ? 'ri-briefcase-line text-primary' : ev.type.includes('invoice') ? 'ri-money-dollar-circle-line text-success' : 'ri-time-line text-muted';
                    html += '<div class="d-flex gap-2 mb-2"><i class="' + icon + ' mt-1"></i><div>';
                    html += '<div style="font-size:.85rem;">' + ev.description + '</div>';
                    html += '<small class="text-muted">' + fmtDate(ev.date) + '</small></div></div>';
                });
            } else { html += '<p class="text-muted">No events yet</p>'; }
            html += '</div>';

            html += '</div>';
            body.innerHTML = html;
        } catch(e) {
            body.innerHTML = '<div class="alert alert-danger">Connection error</div>';
        }
    }

    // ===== EDIT CLIENT =====
    async function openEdit(id) {
        currentClientId = id;
        try {
            var r = await fetch('/api/v1/clients/' + id, { headers: H });
            if (!r.ok) { toast('Failed to load client', 'danger'); return; }
            var j = await r.json();
            var c = (j.data && j.data.client) ? j.data.client : (j.data || {});

            document.getElementById('editId').value = c.id;
            document.getElementById('editName').value = c.name || ((c.first_name || '') + ' ' + (c.last_name || '')).trim();
            document.getElementById('editPhone').value = c.phone || '';
            document.getElementById('editEmail').value = c.email || '';
            document.getElementById('editNationality').value = c.nationality || '';
            document.getElementById('editLanguage').value = c.preferred_language || '';
            document.getElementById('editPassport').value = c.passport_number || '';
            document.getElementById('editPesel').value = c.pesel || '';
            document.getElementById('editDob').value = c.date_of_birth ? c.date_of_birth.split('T')[0] : '';
            document.getElementById('editCity').value = c.city || '';
            document.getElementById('editAddress').value = c.address || '';
            document.getElementById('editStaff').value = c.assigned_to || '';
            document.getElementById('editStatus').value = c.status || 'pending';
            document.getElementById('editNotes').value = c.notes || '';

            // Close view modal if open
            var vm = bootstrap.Modal.getInstance(document.getElementById('viewModal'));
            if (vm) vm.hide();

            setTimeout(function() {
                new bootstrap.Modal(document.getElementById('editModal')).show();
            }, 300);
        } catch(e) { toast('Connection error', 'danger'); }
    }

    document.getElementById('editSaveBtn').addEventListener('click', async function() {
        var id = document.getElementById('editId').value;
        var fd = new FormData();
        fd.append('_method', 'PUT');
        fd.append('name', document.getElementById('editName').value.trim());
        fd.append('phone', document.getElementById('editPhone').value.trim());
        fd.append('email', document.getElementById('editEmail').value.trim());
        fd.append('nationality', document.getElementById('editNationality').value);
        fd.append('preferred_language', document.getElementById('editLanguage').value);
        fd.append('passport_number', document.getElementById('editPassport').value);
        fd.append('pesel', document.getElementById('editPesel').value);
        fd.append('date_of_birth', document.getElementById('editDob').value);
        fd.append('city', document.getElementById('editCity').value);
        fd.append('address', document.getElementById('editAddress').value);
        fd.append('assigned_to', document.getElementById('editStaff').value);
        fd.append('status', document.getElementById('editStatus').value);
        fd.append('notes', document.getElementById('editNotes').value);

        try {
            var r = await fetch('/api/v1/clients/' + id, {
                method: 'POST', headers: { 'Accept': 'application/json', 'Authorization': 'Bearer ' + TOKEN }, body: fd
            });
            if (r.ok) {
                bootstrap.Modal.getInstance(document.getElementById('editModal')).hide();
                toast('<i class="ri-check-line me-1"></i> Client updated');
                loadClients(currentPage);
            } else {
                var err = await r.json();
                toast('<i class="ri-error-warning-line me-1"></i> ' + (err.message || 'Failed to update'), 'danger');
            }
        } catch(e) { toast('Connection error', 'danger'); }
    });

    // ===== VIEW → EDIT BUTTON =====
    document.getElementById('viewEditBtn').addEventListener('click', function() {
        if (currentClientId) openEdit(currentClientId);
    });

    // ===== ADD CLIENT =====
    document.getElementById('addSaveBtn').addEventListener('click', async function() {
        var name = document.getElementById('addName').value.trim();
        var phone = document.getElementById('addPhone').value.trim();
        if (!name || !phone) { toast('Name and Phone are required', 'warning'); return; }

        var fd = new FormData();
        fd.append('name', name);
        fd.append('phone', phone);
        fd.append('email', document.getElementById('addEmail').value.trim());
        fd.append('nationality', document.getElementById('addNationality').value);
        fd.append('preferred_language', document.getElementById('addLanguage').value);
        fd.append('assigned_to', document.getElementById('addStaff').value);
        fd.append('city', document.getElementById('addCity').value);
        fd.append('notes', document.getElementById('addNotes').value);

        try {
            var r = await fetch('/api/v1/clients', {
                method: 'POST', headers: { 'Accept': 'application/json', 'Authorization': 'Bearer ' + TOKEN }, body: fd
            });
            if (r.ok) {
                bootstrap.Modal.getInstance(document.getElementById('addClientModal')).hide();
                toast('<i class="ri-check-line me-1"></i> Client created');
                // Reset form
                ['addName', 'addPhone', 'addEmail', 'addNationality', 'addLanguage', 'addStaff', 'addCity', 'addNotes'].forEach(function(id) {
                    var el = document.getElementById(id);
                    if (el.tagName === 'SELECT') el.selectedIndex = 0; else el.value = '';
                });
                loadClients(1);
            } else {
                var err = await r.json();
                toast('<i class="ri-error-warning-line me-1"></i> ' + (err.message || 'Failed to create'), 'danger');
            }
        } catch(e) { toast('Connection error', 'danger'); }
    });

    // ===== ASSIGN STAFF =====
    document.getElementById('assignSaveBtn').addEventListener('click', async function() {
        var clientId = document.getElementById('assignClientId').value;
        var staffId = document.getElementById('assignStaffSelect').value;
        var fd = new FormData();
        fd.append('_method', 'PUT');
        fd.append('assigned_to', staffId);

        try {
            var r = await fetch('/api/v1/clients/' + clientId, {
                method: 'POST', headers: { 'Accept': 'application/json', 'Authorization': 'Bearer ' + TOKEN }, body: fd
            });
            if (r.ok) {
                bootstrap.Modal.getInstance(document.getElementById('assignModal')).hide();
                toast('<i class="ri-check-line me-1"></i> Staff assigned');
                loadClients(currentPage);
            } else { toast('Failed to assign', 'danger'); }
        } catch(e) { toast('Connection error', 'danger'); }
    });

    // ===== ARCHIVE / ACTIVATE =====
    async function archiveClient(id) {
        if (!confirm('Archive this client?')) return;
        try {
            var r = await fetch('/api/v1/clients/' + id, {
                method: 'DELETE', headers: H
            });
            if (r.ok) { toast('Client archived', 'warning'); loadClients(currentPage); }
            else { toast('Failed to archive', 'danger'); }
        } catch(e) { toast('Connection error', 'danger'); }
    }

    async function activateClient(id) {
        var fd = new FormData();
        fd.append('_method', 'PUT');
        fd.append('status', 'active');
        try {
            var r = await fetch('/api/v1/clients/' + id, {
                method: 'POST', headers: { 'Accept': 'application/json', 'Authorization': 'Bearer ' + TOKEN }, body: fd
            });
            if (r.ok) { toast('Client activated'); loadClients(currentPage); }
            else { toast('Failed to activate', 'danger'); }
        } catch(e) { toast('Connection error', 'danger'); }
    }

    // ===== EVENT DELEGATION =====
    document.getElementById('clientsTable').addEventListener('click', function(e) {
        e.preventDefault();
        var el = e.target.closest('[data-id]');
        if (!el) return;
        var id = el.dataset.id;

        if (el.classList.contains('view-link')) { viewClient(id); }
        else if (el.classList.contains('edit-link')) { openEdit(id); }
        else if (el.classList.contains('assign-link')) {
            document.getElementById('assignClientId').value = id;
            document.getElementById('assignClientName').textContent = el.dataset.name || '';
            document.getElementById('assignStaffSelect').value = '';
            new bootstrap.Modal(document.getElementById('assignModal')).show();
        }
        else if (el.classList.contains('archive-link')) { archiveClient(id); }
        else if (el.classList.contains('activate-link')) { activateClient(id); }
    });

    // Pagination clicks
    document.getElementById('paginationNav').addEventListener('click', function(e) {
        e.preventDefault();
        var btn = e.target.closest('.page-btn');
        if (btn && !btn.closest('.disabled')) loadClients(parseInt(btn.dataset.page));
    });

    // Filter button
    document.getElementById('btnFilter').addEventListener('click', function() { loadClients(1); });

    // Search on Enter
    document.getElementById('fSearch').addEventListener('keydown', function(e) { if (e.key === 'Enter') loadClients(1); });

    // Select all
    document.getElementById('selectAll').addEventListener('change', function() {
        var checks = document.querySelectorAll('#clientsBody .row-check');
        for (var i = 0; i < checks.length; i++) checks[i].checked = this.checked;
    });

    // ===== INIT =====
    loadStaff();
    loadClients(1);
})();
</script>
@endsection
