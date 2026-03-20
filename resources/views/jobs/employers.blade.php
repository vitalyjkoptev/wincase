@extends('partials.layouts.master')
@section('title', 'Employers | WinCase CRM')
@section('sub-title', 'Employers')
@section('pagetitle', 'Jobs Portal')

@section('content')
<!-- Stats -->
<div class="row" id="statsRow">
    <div class="col-xl-3 col-md-6">
        <div class="card card-h-100"><div class="card-body">
            <div class="d-flex align-items-center gap-3">
                <div class="avatar avatar-sm bg-primary-subtle text-primary rounded-2"><i class="ri-building-2-line fs-18"></i></div>
                <div><p class="text-muted mb-0 fs-13">Total</p><h4 class="mb-0 fw-semibold" id="sTotal">—</h4></div>
            </div>
        </div></div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card card-h-100"><div class="card-body">
            <div class="d-flex align-items-center gap-3">
                <div class="avatar avatar-sm bg-success-subtle text-success rounded-2"><i class="ri-checkbox-circle-line fs-18"></i></div>
                <div><p class="text-muted mb-0 fs-13">Active</p><h4 class="mb-0 fw-semibold" id="sActive">—</h4></div>
            </div>
        </div></div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card card-h-100"><div class="card-body">
            <div class="d-flex align-items-center gap-3">
                <div class="avatar avatar-sm bg-warning-subtle text-warning rounded-2"><i class="ri-time-line fs-18"></i></div>
                <div><p class="text-muted mb-0 fs-13">Pending</p><h4 class="mb-0 fw-semibold" id="sPending">—</h4></div>
            </div>
        </div></div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card card-h-100"><div class="card-body">
            <div class="d-flex align-items-center gap-3">
                <div class="avatar avatar-sm bg-info-subtle text-info rounded-2"><i class="ri-briefcase-4-line fs-18"></i></div>
                <div><p class="text-muted mb-0 fs-13">Total Vacancies</p><h4 class="mb-0 fw-semibold" id="sVacancies">—</h4></div>
            </div>
        </div></div>
    </div>
</div>

<!-- Filters -->
<div class="card">
    <div class="card-body">
        <div class="row g-3 align-items-end">
            <div class="col-md-5">
                <input type="text" class="form-control" id="fSearch" placeholder="Search by company, contact, email...">
            </div>
            <div class="col-md-3">
                <select class="form-select" id="fStatus">
                    <option value="">All Statuses</option>
                    <option value="active">Active</option>
                    <option value="pending">Pending</option>
                    <option value="blocked">Blocked</option>
                </select>
            </div>
            <div class="col-md-2">
                <select class="form-select" id="fPerPage">
                    <option value="25">25 per page</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
            </div>
            <div class="col-md-2">
                <button class="btn btn-primary w-100" onclick="loadData()"><i class="ri-search-line me-1"></i>Filter</button>
            </div>
        </div>
    </div>
</div>

<!-- Table -->
<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Company</th>
                        <th>Contact</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>City</th>
                        <th>Industry</th>
                        <th>Vacancies</th>
                        <th>Status</th>
                        <th width="120">Actions</th>
                    </tr>
                </thead>
                <tbody id="tBody"><tr><td colspan="9" class="text-center py-3 text-muted">Loading...</td></tr></tbody>
            </table>
        </div>
    </div>
    <div class="card-footer d-flex justify-content-between align-items-center">
        <span class="text-muted fs-13" id="pInfo">—</span>
        <nav><ul class="pagination pagination-sm mb-0" id="pNav"></ul></nav>
    </div>
</div>

<!-- View Modal -->
<div class="modal fade" id="viewModal" tabindex="-1"><div class="modal-dialog modal-lg"><div class="modal-content">
    <div class="modal-header"><h5 class="modal-title">Employer Details</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
    <div class="modal-body" id="viewBody"></div>
</div></div></div>

<!-- Edit Status Modal -->
<div class="modal fade" id="editModal" tabindex="-1"><div class="modal-dialog"><div class="modal-content">
    <div class="modal-header"><h5 class="modal-title">Edit Employer</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
    <div class="modal-body">
        <input type="hidden" id="editId">
        <div class="mb-3"><label class="form-label">Status</label>
            <select class="form-select" id="editStatus">
                <option value="active">Active</option>
                <option value="pending">Pending</option>
                <option value="blocked">Blocked</option>
            </select>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-primary" onclick="saveEdit()">Save</button>
    </div>
</div></div></div>
@endsection

@section('js')
<script>
(function() {
    var TOKEN = localStorage.getItem('wc_token');
    var H = { 'Accept': 'application/json', 'Authorization': 'Bearer ' + TOKEN };
    var curPage = 1;

    // Stats
    fetch('/api/v1/jobs/dashboard', { headers: H }).then(r => r.json()).then(j => {
        var e = (j.data || {}).employers || {};
        var v = (j.data || {}).vacancies || {};
        document.getElementById('sTotal').textContent = e.total || 0;
        document.getElementById('sActive').textContent = e.active || 0;
        document.getElementById('sPending').textContent = e.pending || 0;
        document.getElementById('sVacancies').textContent = v.total || 0;
    }).catch(function() {});

    window.loadData = function(page) {
        curPage = page || 1;
        var params = new URLSearchParams();
        params.set('page', curPage);
        params.set('per_page', document.getElementById('fPerPage').value);
        var s = document.getElementById('fSearch').value.trim();
        if (s) params.set('search', s);
        var st = document.getElementById('fStatus').value;
        if (st) params.set('status', st);

        fetch('/api/v1/jobs/employers?' + params.toString(), { headers: H }).then(r => r.json()).then(j => {
            var items = (j.data && j.data.data) || [];
            var meta = (j.data && j.data.meta) || {};
            var tbody = document.getElementById('tBody');

            if (!items.length) {
                tbody.innerHTML = '<tr><td colspan="9" class="text-center py-3 text-muted">No employers found</td></tr>';
                document.getElementById('pInfo').textContent = '0 results';
                document.getElementById('pNav').innerHTML = '';
                return;
            }

            var stMap = { active: 'success', pending: 'warning', blocked: 'danger' };
            tbody.innerHTML = items.map(function(e) {
                return '<tr>' +
                    '<td class="fw-semibold">' + (e.company_name || '—') + (e.nip ? '<br><small class="text-muted">NIP: ' + e.nip + '</small>' : '') + '</td>' +
                    '<td>' + (e.contact_name || '—') + (e.position ? '<br><small class="text-muted">' + e.position + '</small>' : '') + '</td>' +
                    '<td><a href="mailto:' + (e.email || '') + '">' + (e.email || '—') + '</a></td>' +
                    '<td>' + (e.phone || '—') + '</td>' +
                    '<td>' + (e.city || '—') + '</td>' +
                    '<td>' + (e.industry || '—') + '</td>' +
                    '<td><span class="badge bg-primary-subtle text-primary">' + (e.vacancies_count || 0) + '</span></td>' +
                    '<td><span class="badge bg-' + (stMap[e.status] || 'secondary') + '-subtle text-' + (stMap[e.status] || 'secondary') + '">' + (e.status || 'unknown') + '</span></td>' +
                    '<td>' +
                        '<button class="btn btn-sm btn-outline-primary me-1" onclick="viewEmployer(' + e.id + ')" title="View"><i class="ri-eye-line"></i></button>' +
                        '<button class="btn btn-sm btn-outline-warning" onclick="editEmployer(' + e.id + ',\'' + (e.status || 'pending') + '\')" title="Edit"><i class="ri-edit-line"></i></button>' +
                    '</td></tr>';
            }).join('');

            document.getElementById('pInfo').textContent = 'Page ' + meta.current_page + ' of ' + meta.last_page + ' (' + meta.total + ' total)';
            var nav = '';
            for (var i = 1; i <= meta.last_page; i++) {
                nav += '<li class="page-item ' + (i === meta.current_page ? 'active' : '') + '"><a class="page-link" href="javascript:void(0)" onclick="loadData(' + i + ')">' + i + '</a></li>';
            }
            document.getElementById('pNav').innerHTML = nav;
        }).catch(function() {});
    };

    window.viewEmployer = function(id) {
        fetch('/api/v1/jobs/employers/' + id, { headers: H }).then(r => r.json()).then(j => {
            var e = j.data || {};
            var vacs = (e.vacancies || []).map(function(v) {
                return '<tr><td>' + v.title + '</td><td>' + (v.city || '—') + '</td><td>' + (v.status || '—') + '</td></tr>';
            }).join('') || '<tr><td colspan="3" class="text-muted text-center">No vacancies</td></tr>';

            document.getElementById('viewBody').innerHTML =
                '<div class="row mb-3">' +
                    '<div class="col-md-6"><small class="text-muted">Company:</small><br><strong>' + (e.company_name || '—') + '</strong></div>' +
                    '<div class="col-md-6"><small class="text-muted">NIP / REGON:</small><br>' + (e.nip || '—') + ' / ' + (e.regon || '—') + '</div>' +
                    '<div class="col-md-6 mt-2"><small class="text-muted">Contact:</small><br>' + (e.contact_name || '—') + (e.position ? ' (' + e.position + ')' : '') + '</div>' +
                    '<div class="col-md-6 mt-2"><small class="text-muted">Email:</small><br>' + (e.email || '—') + '</div>' +
                    '<div class="col-md-6 mt-2"><small class="text-muted">Phone:</small><br>' + (e.phone || '—') + '</div>' +
                    '<div class="col-md-6 mt-2"><small class="text-muted">City:</small><br>' + (e.city || '—') + '</div>' +
                    '<div class="col-md-6 mt-2"><small class="text-muted">Industry:</small><br>' + (e.industry || '—') + '</div>' +
                    '<div class="col-md-6 mt-2"><small class="text-muted">Website:</small><br>' + (e.website ? '<a href="' + e.website + '" target="_blank">' + e.website + '</a>' : '—') + '</div>' +
                    '<div class="col-md-6 mt-2"><small class="text-muted">Status:</small><br>' + (e.status || '—') + '</div>' +
                    '<div class="col-md-6 mt-2"><small class="text-muted">Vacancies:</small><br>' + (e.vacancies_count || 0) + '</div>' +
                '</div>' +
                (e.description ? '<div class="mb-3"><small class="text-muted">Description:</small><div class="border rounded p-2 bg-light">' + e.description + '</div></div>' : '') +
                '<h6 class="mt-3">Vacancies</h6>' +
                '<table class="table table-sm"><thead><tr><th>Title</th><th>City</th><th>Status</th></tr></thead><tbody>' + vacs + '</tbody></table>';

            new bootstrap.Modal(document.getElementById('viewModal')).show();
        }).catch(function() {});
    };

    window.editEmployer = function(id, status) {
        document.getElementById('editId').value = id;
        document.getElementById('editStatus').value = status;
        new bootstrap.Modal(document.getElementById('editModal')).show();
    };

    window.saveEdit = function() {
        var id = document.getElementById('editId').value;
        var fd = new FormData();
        fd.append('_method', 'PUT');
        fd.append('status', document.getElementById('editStatus').value);
        fetch('/api/v1/jobs/employers/' + id, { method: 'POST', headers: { 'Accept': 'application/json', 'Authorization': 'Bearer ' + TOKEN }, body: fd })
            .then(r => r.json()).then(function() {
                bootstrap.Modal.getInstance(document.getElementById('editModal')).hide();
                loadData(curPage);
            }).catch(function() {});
    };

    document.getElementById('fSearch').addEventListener('keydown', function(e) { if (e.key === 'Enter') loadData(); });
    loadData();
})();
</script>
@endsection
