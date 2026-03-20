@extends('partials.layouts.master')
@section('title', 'Parsed Jobs Queue | WinCase CRM')
@section('sub-title', 'Parsed Jobs')
@section('pagetitle', 'Jobs Portal')

@section('content')
<!-- Stats -->
<div class="row">
    <div class="col-xl-3 col-md-6">
        <div class="card card-h-100"><div class="card-body">
            <div class="d-flex align-items-center gap-3">
                <div class="avatar avatar-sm bg-warning-subtle text-warning rounded-2"><i class="ri-robot-line fs-18"></i></div>
                <div><p class="text-muted mb-0 fs-13">Total Parsed</p><h4 class="mb-0 fw-semibold" id="sTotal">—</h4></div>
            </div>
        </div></div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card card-h-100"><div class="card-body">
            <div class="d-flex align-items-center gap-3">
                <div class="avatar avatar-sm bg-primary-subtle text-primary rounded-2"><i class="ri-inbox-line fs-18"></i></div>
                <div><p class="text-muted mb-0 fs-13">New (To Review)</p><h4 class="mb-0 fw-semibold" id="sNew">—</h4></div>
            </div>
        </div></div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card card-h-100"><div class="card-body">
            <div class="d-flex align-items-center gap-3">
                <div class="avatar avatar-sm bg-success-subtle text-success rounded-2"><i class="ri-check-double-line fs-18"></i></div>
                <div><p class="text-muted mb-0 fs-13">Published</p><h4 class="mb-0 fw-semibold" id="sPublished">—</h4></div>
            </div>
        </div></div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card card-h-100"><div class="card-body">
            <div class="d-flex align-items-center gap-3">
                <div class="avatar avatar-sm bg-danger-subtle text-danger rounded-2"><i class="ri-close-circle-line fs-18"></i></div>
                <div><p class="text-muted mb-0 fs-13">Rejected</p><h4 class="mb-0 fw-semibold" id="sRejected">—</h4></div>
            </div>
        </div></div>
    </div>
</div>

<!-- Info -->
<div class="alert alert-info d-flex align-items-center gap-2 mb-3">
    <i class="ri-robot-line fs-20"></i>
    <div>
        <strong>Parsed Jobs Queue</strong> — jobs automatically parsed from external sources via n8n automation.
        Review each job and <strong>Approve</strong> to publish as a vacancy or <strong>Reject</strong> to discard.
    </div>
</div>

<!-- Filters -->
<div class="card">
    <div class="card-body">
        <div class="row g-3 align-items-end">
            <div class="col-md-4">
                <select class="form-select" id="fStatus">
                    <option value="">All Statuses</option>
                    <option value="new" selected>New (To Review)</option>
                    <option value="approved">Approved</option>
                    <option value="published">Published</option>
                    <option value="rejected">Rejected</option>
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
                        <th>Title</th>
                        <th>Company</th>
                        <th>City</th>
                        <th>Salary</th>
                        <th>Source</th>
                        <th>Status</th>
                        <th>Parsed At</th>
                        <th width="160">Actions</th>
                    </tr>
                </thead>
                <tbody id="tBody"><tr><td colspan="8" class="text-center py-3 text-muted">Loading...</td></tr></tbody>
            </table>
        </div>
    </div>
    <div class="card-footer d-flex justify-content-between align-items-center">
        <span class="text-muted fs-13" id="pInfo">—</span>
        <nav><ul class="pagination pagination-sm mb-0" id="pNav"></ul></nav>
    </div>
</div>

<!-- Preview Modal -->
<div class="modal fade" id="previewModal" tabindex="-1"><div class="modal-dialog modal-lg"><div class="modal-content">
    <div class="modal-header"><h5 class="modal-title">Parsed Job Preview</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
    <div class="modal-body" id="previewBody"></div>
    <div class="modal-footer" id="previewFooter"></div>
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
        var p = (j.data || {}).parsed_jobs || {};
        document.getElementById('sTotal').textContent = p.total || 0;
        document.getElementById('sNew').textContent = p.new || 0;
        document.getElementById('sPublished').textContent = p.published || 0;
        document.getElementById('sRejected').textContent = '—';
    }).catch(function() {});

    window.loadData = function(page) {
        curPage = page || 1;
        var params = new URLSearchParams();
        params.set('page', curPage);
        params.set('per_page', document.getElementById('fPerPage').value);
        var st = document.getElementById('fStatus').value;
        if (st) params.set('status', st);

        fetch('/api/v1/jobs/parsed?' + params.toString(), { headers: H }).then(r => r.json()).then(j => {
            var items = (j.data && j.data.data) || [];
            var meta = (j.data && j.data.meta) || {};
            var tbody = document.getElementById('tBody');

            if (!items.length) {
                tbody.innerHTML = '<tr><td colspan="8" class="text-center py-3 text-muted">No parsed jobs found</td></tr>';
                document.getElementById('pInfo').textContent = '0 results';
                document.getElementById('pNav').innerHTML = '';
                return;
            }

            var stMap = { 'new': 'primary', approved: 'info', published: 'success', rejected: 'danger' };
            tbody.innerHTML = items.map(function(p) {
                var actions = '';
                if (p.status === 'new') {
                    actions = '<button class="btn btn-sm btn-success me-1" onclick="approveJob(' + p.id + ')" title="Approve & Publish"><i class="ri-check-line"></i> Approve</button>' +
                              '<button class="btn btn-sm btn-danger me-1" onclick="rejectJob(' + p.id + ')" title="Reject"><i class="ri-close-line"></i></button>';
                } else {
                    actions = '<span class="text-muted fs-12">' + p.status + '</span>';
                }

                return '<tr>' +
                    '<td class="fw-semibold">' + (p.title || '—') + '</td>' +
                    '<td>' + (p.company || '—') + '</td>' +
                    '<td>' + (p.city || '—') + '</td>' +
                    '<td>' + (p.salary || '—') + '</td>' +
                    '<td>' + (p.source || '—') + '</td>' +
                    '<td><span class="badge bg-' + (stMap[p.status] || 'secondary') + '-subtle text-' + (stMap[p.status] || 'secondary') + '">' + p.status + '</span></td>' +
                    '<td class="text-muted fs-12">' + (p.created_at ? new Date(p.created_at).toLocaleDateString('en-GB', {day:'2-digit',month:'short',year:'numeric'}) : '—') + '</td>' +
                    '<td>' +
                        '<button class="btn btn-sm btn-outline-primary me-1" onclick="previewJob(' + p.id + ')" title="Preview"><i class="ri-eye-line"></i></button>' +
                        actions +
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

    window.previewJob = function(id) {
        var items = [];
        // Find in current data
        fetch('/api/v1/jobs/parsed?per_page=100', { headers: H }).then(r => r.json()).then(j => {
            items = (j.data && j.data.data) || [];
            var p = items.find(function(x) { return x.id == id; });
            if (!p) { alert('Not found'); return; }

            var parsedExtra = '';
            if (p.parsed_data && typeof p.parsed_data === 'object') {
                parsedExtra = '<div class="mb-3"><small class="text-muted d-block mb-1">Parsed Data (JSON):</small>' +
                    '<pre class="border rounded p-2 bg-light" style="max-height:200px;overflow:auto;font-size:12px">' + JSON.stringify(p.parsed_data, null, 2) + '</pre></div>';
            }

            document.getElementById('previewBody').innerHTML =
                '<h5>' + (p.title || '—') + '</h5>' +
                '<div class="row mb-3">' +
                    '<div class="col-md-6"><small class="text-muted">Company:</small> ' + (p.company || '—') + '</div>' +
                    '<div class="col-md-6"><small class="text-muted">City:</small> ' + (p.city || '—') + '</div>' +
                    '<div class="col-md-6 mt-2"><small class="text-muted">Salary:</small> ' + (p.salary || '—') + '</div>' +
                    '<div class="col-md-6 mt-2"><small class="text-muted">Source:</small> ' + (p.source || '—') + '</div>' +
                '</div>' +
                (p.description ? '<div class="mb-3"><small class="text-muted d-block mb-1">Description:</small><div class="border rounded p-2 bg-light" style="max-height:200px;overflow:auto">' + p.description + '</div></div>' : '') +
                parsedExtra +
                (p.source_url ? '<a href="' + p.source_url + '" target="_blank" class="btn btn-sm btn-outline-secondary"><i class="ri-external-link-line me-1"></i>Source URL</a>' : '');

            var footer = '';
            if (p.status === 'new') {
                footer = '<button class="btn btn-success" onclick="approveJob(' + p.id + '); bootstrap.Modal.getInstance(document.getElementById(\'previewModal\')).hide();"><i class="ri-check-line me-1"></i>Approve & Publish</button>' +
                         '<button class="btn btn-danger" onclick="rejectJob(' + p.id + '); bootstrap.Modal.getInstance(document.getElementById(\'previewModal\')).hide();"><i class="ri-close-line me-1"></i>Reject</button>';
            }
            document.getElementById('previewFooter').innerHTML = '<button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>' + footer;

            new bootstrap.Modal(document.getElementById('previewModal')).show();
        }).catch(function() {});
    };

    window.approveJob = function(id) {
        if (!confirm('Approve and publish this job as a new vacancy?')) return;
        fetch('/api/v1/jobs/parsed/' + id + '/approve', { method: 'POST', headers: H }).then(r => r.json()).then(function(j) {
            if (j.success) {
                if (typeof CRM !== 'undefined' && CRM.toast) CRM.toast('Published!', 'success');
                loadData(curPage);
            }
        }).catch(function() {});
    };

    window.rejectJob = function(id) {
        if (!confirm('Reject this parsed job?')) return;
        fetch('/api/v1/jobs/parsed/' + id + '/reject', { method: 'POST', headers: H }).then(r => r.json()).then(function() {
            loadData(curPage);
        }).catch(function() {});
    };

    loadData();
})();
</script>
@endsection
