@extends('partials.layouts.master')
@section('title', 'Candidates | WinCase CRM')
@section('sub-title', 'Candidates')
@section('pagetitle', 'Jobs Portal')

@section('content')
<!-- Stats -->
<div class="row">
    <div class="col-xl-3 col-md-6">
        <div class="card card-h-100"><div class="card-body">
            <div class="d-flex align-items-center gap-3">
                <div class="avatar avatar-sm bg-info-subtle text-info rounded-2"><i class="ri-user-search-line fs-18"></i></div>
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
                <div class="avatar avatar-sm bg-primary-subtle text-primary rounded-2"><i class="ri-file-user-line fs-18"></i></div>
                <div><p class="text-muted mb-0 fs-13">With CV</p><h4 class="mb-0 fw-semibold" id="sWithCV">—</h4></div>
            </div>
        </div></div>
    </div>
</div>

<!-- Filters -->
<div class="card">
    <div class="card-body">
        <div class="row g-3 align-items-end">
            <div class="col-md-4">
                <input type="text" class="form-control" id="fSearch" placeholder="Search by name, email, phone...">
            </div>
            <div class="col-md-2">
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
            <div class="col-md-2">
                <button class="btn btn-outline-secondary w-100" onclick="exportCSV()"><i class="ri-download-line me-1"></i>Export</button>
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
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Nationality</th>
                        <th>Category</th>
                        <th>Experience</th>
                        <th>Polish</th>
                        <th>City</th>
                        <th>Work Permit</th>
                        <th>Status</th>
                        <th width="100">Actions</th>
                    </tr>
                </thead>
                <tbody id="tBody"><tr><td colspan="11" class="text-center py-3 text-muted">Loading...</td></tr></tbody>
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
    <div class="modal-header"><h5 class="modal-title">Candidate Profile</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
    <div class="modal-body" id="viewBody"></div>
</div></div></div>

<!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1"><div class="modal-dialog"><div class="modal-content">
    <div class="modal-header"><h5 class="modal-title">Edit Candidate</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
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
    var allData = [];

    // Stats
    fetch('/api/v1/jobs/dashboard', { headers: H }).then(r => r.json()).then(j => {
        var s = (j.data || {}).seekers || {};
        document.getElementById('sTotal').textContent = s.total || 0;
        document.getElementById('sActive').textContent = s.active || 0;
        document.getElementById('sPending').textContent = s.pending || 0;
        document.getElementById('sWithCV').textContent = '—';
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

        fetch('/api/v1/jobs/seekers?' + params.toString(), { headers: H }).then(r => r.json()).then(j => {
            var items = (j.data && j.data.data) || [];
            allData = items;
            var meta = (j.data && j.data.meta) || {};
            var tbody = document.getElementById('tBody');

            if (!items.length) {
                tbody.innerHTML = '<tr><td colspan="11" class="text-center py-3 text-muted">No candidates found</td></tr>';
                document.getElementById('pInfo').textContent = '0 results';
                document.getElementById('pNav').innerHTML = '';
                return;
            }

            var stMap = { active: 'success', pending: 'warning', blocked: 'danger' };
            tbody.innerHTML = items.map(function(c) {
                var name = ((c.first_name || '') + ' ' + (c.last_name || '')).trim() || '—';
                var wp = c.work_permit ? '<span class="badge bg-success-subtle text-success">Yes</span>' : '<span class="badge bg-secondary-subtle text-secondary">No</span>';
                return '<tr>' +
                    '<td class="fw-semibold">' + name + '</td>' +
                    '<td><a href="mailto:' + (c.email || '') + '">' + (c.email || '—') + '</a></td>' +
                    '<td>' + (c.phone || '—') + '</td>' +
                    '<td>' + (c.nationality || '—') + '</td>' +
                    '<td>' + (c.job_category || '—') + '</td>' +
                    '<td>' + (c.experience || '—') + '</td>' +
                    '<td>' + (c.polish_level || '—') + '</td>' +
                    '<td>' + (c.preferred_city || '—') + '</td>' +
                    '<td>' + wp + '</td>' +
                    '<td><span class="badge bg-' + (stMap[c.status] || 'secondary') + '-subtle text-' + (stMap[c.status] || 'secondary') + '">' + (c.status || 'unknown') + '</span></td>' +
                    '<td>' +
                        '<button class="btn btn-sm btn-outline-primary me-1" onclick="viewCandidate(' + c.id + ')" title="View"><i class="ri-eye-line"></i></button>' +
                        '<button class="btn btn-sm btn-outline-warning" onclick="editCandidate(' + c.id + ',\'' + (c.status || 'pending') + '\')" title="Edit"><i class="ri-edit-line"></i></button>' +
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

    window.viewCandidate = function(id) {
        fetch('/api/v1/jobs/seekers/' + id, { headers: H }).then(r => r.json()).then(j => {
            var c = j.data || {};
            var name = ((c.first_name || '') + ' ' + (c.last_name || '')).trim();
            var skills = c.skills || '—';

            document.getElementById('viewBody').innerHTML =
                '<div class="row mb-3">' +
                    '<div class="col-md-6"><small class="text-muted">Name:</small><br><strong>' + name + '</strong></div>' +
                    '<div class="col-md-6"><small class="text-muted">Email:</small><br>' + (c.email || '—') + '</div>' +
                    '<div class="col-md-6 mt-2"><small class="text-muted">Phone:</small><br>' + (c.phone || '—') + '</div>' +
                    '<div class="col-md-6 mt-2"><small class="text-muted">Nationality:</small><br>' + (c.nationality || '—') + '</div>' +
                    '<div class="col-md-6 mt-2"><small class="text-muted">Date of Birth:</small><br>' + (c.date_of_birth || '—') + '</div>' +
                    '<div class="col-md-6 mt-2"><small class="text-muted">Job Category:</small><br>' + (c.job_category || '—') + '</div>' +
                    '<div class="col-md-6 mt-2"><small class="text-muted">Experience:</small><br>' + (c.experience || '—') + '</div>' +
                    '<div class="col-md-6 mt-2"><small class="text-muted">Polish Level:</small><br>' + (c.polish_level || '—') + '</div>' +
                    '<div class="col-md-6 mt-2"><small class="text-muted">Preferred City:</small><br>' + (c.preferred_city || '—') + '</div>' +
                    '<div class="col-md-6 mt-2"><small class="text-muted">Work Permit:</small><br>' + (c.work_permit ? 'Yes' : 'No') + '</div>' +
                    '<div class="col-md-6 mt-2"><small class="text-muted">Status:</small><br>' + (c.status || '—') + '</div>' +
                    '<div class="col-md-6 mt-2"><small class="text-muted">CV:</small><br>' + (c.cv_file ? '<a href="' + c.cv_file + '" target="_blank" class="btn btn-sm btn-outline-primary"><i class="ri-file-download-line me-1"></i>Download CV</a>' : 'Not uploaded') + '</div>' +
                '</div>' +
                '<div class="mb-3"><small class="text-muted">Skills:</small><div class="border rounded p-2 bg-light">' + skills + '</div></div>' +
                (c.bio ? '<div class="mb-3"><small class="text-muted">Bio:</small><div class="border rounded p-2 bg-light">' + c.bio + '</div></div>' : '');

            new bootstrap.Modal(document.getElementById('viewModal')).show();
        }).catch(function() {});
    };

    window.editCandidate = function(id, status) {
        document.getElementById('editId').value = id;
        document.getElementById('editStatus').value = status;
        new bootstrap.Modal(document.getElementById('editModal')).show();
    };

    window.saveEdit = function() {
        var id = document.getElementById('editId').value;
        var fd = new FormData();
        fd.append('_method', 'PUT');
        fd.append('status', document.getElementById('editStatus').value);
        fetch('/api/v1/jobs/seekers/' + id, { method: 'POST', headers: { 'Accept': 'application/json', 'Authorization': 'Bearer ' + TOKEN }, body: fd })
            .then(r => r.json()).then(function() {
                bootstrap.Modal.getInstance(document.getElementById('editModal')).hide();
                loadData(curPage);
            }).catch(function() {});
    };

    window.exportCSV = function() {
        if (!allData.length) { alert('No data to export'); return; }
        var csv = 'Name,Email,Phone,Nationality,Category,Experience,Polish,City,Work Permit,Status\n';
        allData.forEach(function(c) {
            csv += '"' + ((c.first_name || '') + ' ' + (c.last_name || '')).trim() + '",' +
                   '"' + (c.email || '') + '",' +
                   '"' + (c.phone || '') + '",' +
                   '"' + (c.nationality || '') + '",' +
                   '"' + (c.job_category || '') + '",' +
                   '"' + (c.experience || '') + '",' +
                   '"' + (c.polish_level || '') + '",' +
                   '"' + (c.preferred_city || '') + '",' +
                   '"' + (c.work_permit ? 'Yes' : 'No') + '",' +
                   '"' + (c.status || '') + '"\n';
        });
        var blob = new Blob([csv], { type: 'text/csv' });
        var a = document.createElement('a');
        a.href = URL.createObjectURL(blob);
        a.download = 'candidates_export.csv';
        a.click();
    };

    document.getElementById('fSearch').addEventListener('keydown', function(e) { if (e.key === 'Enter') loadData(); });
    loadData();
})();
</script>
@endsection
