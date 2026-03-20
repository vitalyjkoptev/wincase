@extends('partials.layouts.master')
@section('title', 'Job Listings | WinCase CRM')
@section('sub-title', 'Job Listings')
@section('pagetitle', 'Jobs Portal')

@section('content')
<!-- Stats -->
<div class="row" id="statsRow">
    <div class="col-xl-3 col-md-6">
        <div class="card card-h-100"><div class="card-body">
            <div class="d-flex align-items-center gap-3">
                <div class="avatar avatar-sm bg-primary-subtle text-primary rounded-2"><i class="ri-briefcase-4-line fs-18"></i></div>
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
                <div class="avatar avatar-sm bg-warning-subtle text-warning rounded-2"><i class="ri-draft-line fs-18"></i></div>
                <div><p class="text-muted mb-0 fs-13">Draft</p><h4 class="mb-0 fw-semibold" id="sDraft">—</h4></div>
            </div>
        </div></div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card card-h-100"><div class="card-body">
            <div class="d-flex align-items-center gap-3">
                <div class="avatar avatar-sm bg-secondary-subtle text-secondary rounded-2"><i class="ri-close-circle-line fs-18"></i></div>
                <div><p class="text-muted mb-0 fs-13">Closed</p><h4 class="mb-0 fw-semibold" id="sClosed">—</h4></div>
            </div>
        </div></div>
    </div>
</div>

<!-- Filters -->
<div class="card">
    <div class="card-body">
        <div class="row g-3 align-items-end">
            <div class="col-md-4">
                <input type="text" class="form-control" id="fSearch" placeholder="Search by title, city, category...">
            </div>
            <div class="col-md-2">
                <select class="form-select" id="fStatus">
                    <option value="">All Statuses</option>
                    <option value="active">Active</option>
                    <option value="draft">Draft</option>
                    <option value="closed">Closed</option>
                    <option value="archived">Archived</option>
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
            <div class="col-md-2">
                <a href="jobs-create" class="btn btn-success w-100"><i class="ri-add-line me-1"></i>New Listing</a>
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
                        <th>Title</th>
                        <th>Category</th>
                        <th>City</th>
                        <th>Employer</th>
                        <th>Salary</th>
                        <th>Status</th>
                        <th>Views</th>
                        <th>Created</th>
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
    <div class="modal-header"><h5 class="modal-title">Vacancy Details</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
    <div class="modal-body" id="viewBody"></div>
</div></div></div>

<!-- Edit Status Modal -->
<div class="modal fade" id="statusModal" tabindex="-1"><div class="modal-dialog"><div class="modal-content">
    <div class="modal-header"><h5 class="modal-title">Change Status</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
    <div class="modal-body">
        <input type="hidden" id="statusId">
        <label class="form-label">New Status</label>
        <select class="form-select" id="statusVal">
            <option value="active">Active</option>
            <option value="draft">Draft</option>
            <option value="closed">Closed</option>
            <option value="archived">Archived</option>
        </select>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-primary" onclick="saveStatus()">Save</button>
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
        var v = (j.data || {}).vacancies || {};
        document.getElementById('sTotal').textContent = v.total || 0;
        document.getElementById('sActive').textContent = v.active || 0;
        document.getElementById('sDraft').textContent = v.draft || 0;
        document.getElementById('sClosed').textContent = v.closed || 0;
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

        fetch('/api/v1/jobs/vacancies?' + params.toString(), { headers: H }).then(r => r.json()).then(j => {
            var items = (j.data && j.data.data) || [];
            var meta = (j.data && j.data.meta) || {};
            var tbody = document.getElementById('tBody');

            if (!items.length) {
                tbody.innerHTML = '<tr><td colspan="9" class="text-center py-3 text-muted">No vacancies found</td></tr>';
                document.getElementById('pInfo').textContent = '0 results';
                document.getElementById('pNav').innerHTML = '';
                return;
            }

            var stMap = { active: 'success', draft: 'warning', closed: 'secondary', archived: 'dark' };
            tbody.innerHTML = items.map(function(v) {
                var salary = '—';
                if (v.salary_from || v.salary_to) {
                    salary = (v.salary_from || '?') + ' – ' + (v.salary_to || '?') + ' ' + (v.currency || 'PLN');
                }
                return '<tr>' +
                    '<td class="fw-semibold">' + v.title + '</td>' +
                    '<td>' + (v.category || '—') + '</td>' +
                    '<td>' + (v.city || '—') + '</td>' +
                    '<td>' + (v.employer ? v.employer.company_name : '—') + '</td>' +
                    '<td class="text-nowrap">' + salary + '</td>' +
                    '<td><span class="badge bg-' + (stMap[v.status] || 'secondary') + '-subtle text-' + (stMap[v.status] || 'secondary') + '">' + v.status + '</span></td>' +
                    '<td>' + (v.views || 0) + '</td>' +
                    '<td class="text-muted fs-12">' + new Date(v.created_at).toLocaleDateString('en-GB', {day:'2-digit',month:'short',year:'numeric'}) + '</td>' +
                    '<td>' +
                        '<button class="btn btn-sm btn-outline-primary me-1" onclick="viewVacancy(' + v.id + ')" title="View"><i class="ri-eye-line"></i></button>' +
                        '<button class="btn btn-sm btn-outline-warning me-1" onclick="editStatus(' + v.id + ',\'' + v.status + '\')" title="Status"><i class="ri-edit-line"></i></button>' +
                        '<button class="btn btn-sm btn-outline-danger" onclick="archiveVacancy(' + v.id + ')" title="Archive"><i class="ri-delete-bin-line"></i></button>' +
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

    window.viewVacancy = function(id) {
        fetch('/api/v1/jobs/vacancies/' + id, { headers: H }).then(r => r.json()).then(j => {
            var v = j.data || {};
            var perks = [];
            if (v.work_permit_provided) perks.push('<span class="badge bg-success-subtle text-success me-1">Work Permit</span>');
            if (v.accommodation_provided) perks.push('<span class="badge bg-info-subtle text-info me-1">Accommodation</span>');
            if (v.transport_provided) perks.push('<span class="badge bg-primary-subtle text-primary me-1">Transport</span>');

            document.getElementById('viewBody').innerHTML =
                '<h5>' + v.title + '</h5>' +
                '<div class="row mb-3">' +
                    '<div class="col-md-6"><small class="text-muted">Category:</small> ' + (v.category || '—') + '</div>' +
                    '<div class="col-md-6"><small class="text-muted">City:</small> ' + (v.city || '—') + '</div>' +
                    '<div class="col-md-6"><small class="text-muted">Employer:</small> ' + (v.employer ? v.employer.company_name : '—') + '</div>' +
                    '<div class="col-md-6"><small class="text-muted">Employment:</small> ' + (v.employment_type || '—') + '</div>' +
                    '<div class="col-md-6"><small class="text-muted">Salary:</small> ' + ((v.salary_from || '?') + ' – ' + (v.salary_to || '?') + ' ' + (v.currency || 'PLN')) + '</div>' +
                    '<div class="col-md-6"><small class="text-muted">Status:</small> ' + (v.status || '—') + '</div>' +
                    '<div class="col-md-6"><small class="text-muted">Views:</small> ' + (v.views || 0) + '</div>' +
                    '<div class="col-md-6"><small class="text-muted">Source:</small> ' + (v.source || '—') + '</div>' +
                '</div>' +
                (perks.length ? '<div class="mb-3"><small class="text-muted">Perks:</small><br>' + perks.join('') + '</div>' : '') +
                '<div class="mb-3"><small class="text-muted d-block mb-1">Description:</small><div class="border rounded p-2 bg-light" style="max-height:200px;overflow:auto">' + (v.description || '—') + '</div></div>' +
                (v.requirements ? '<div class="mb-3"><small class="text-muted d-block mb-1">Requirements:</small><div class="border rounded p-2 bg-light" style="max-height:200px;overflow:auto">' + v.requirements + '</div></div>' : '') +
                (v.source_url ? '<a href="' + v.source_url + '" target="_blank" class="btn btn-sm btn-outline-secondary"><i class="ri-external-link-line me-1"></i>Source</a>' : '');

            new bootstrap.Modal(document.getElementById('viewModal')).show();
        }).catch(function() {});
    };

    window.editStatus = function(id, current) {
        document.getElementById('statusId').value = id;
        document.getElementById('statusVal').value = current;
        new bootstrap.Modal(document.getElementById('statusModal')).show();
    };

    window.saveStatus = function() {
        var id = document.getElementById('statusId').value;
        var st = document.getElementById('statusVal').value;
        var fd = new FormData();
        fd.append('_method', 'PUT');
        fd.append('status', st);
        fetch('/api/v1/jobs/vacancies/' + id, { method: 'POST', headers: { 'Accept': 'application/json', 'Authorization': 'Bearer ' + TOKEN }, body: fd })
            .then(r => r.json()).then(function() {
                bootstrap.Modal.getInstance(document.getElementById('statusModal')).hide();
                loadData(curPage);
            }).catch(function() {});
    };

    window.archiveVacancy = function(id) {
        if (!confirm('Archive this vacancy?')) return;
        fetch('/api/v1/jobs/vacancies/' + id, { method: 'DELETE', headers: H }).then(r => r.json()).then(function() {
            loadData(curPage);
        }).catch(function() {});
    };

    // Enter key search
    document.getElementById('fSearch').addEventListener('keydown', function(e) { if (e.key === 'Enter') loadData(); });

    loadData();
})();
</script>
@endsection
