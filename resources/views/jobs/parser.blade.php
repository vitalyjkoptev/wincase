@extends('partials.layouts.master')
@section('title', 'Parser Sources | WinCase CRM')
@section('sub-title', 'Parser Sources')
@section('pagetitle', 'Jobs Portal')

@section('content')
<!-- Stats -->
<div class="row">
    <div class="col-xl-3 col-md-6">
        <div class="card card-h-100"><div class="card-body">
            <div class="d-flex align-items-center gap-3">
                <div class="avatar avatar-sm bg-primary-subtle text-primary rounded-2"><i class="ri-global-line fs-18"></i></div>
                <div><p class="text-muted mb-0 fs-13">Total Sources</p><h4 class="mb-0 fw-semibold" id="sTotal">—</h4></div>
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
                <div class="avatar avatar-sm bg-info-subtle text-info rounded-2"><i class="ri-database-line fs-18"></i></div>
                <div><p class="text-muted mb-0 fs-13">Total Parsed</p><h4 class="mb-0 fw-semibold" id="sParsed">—</h4></div>
            </div>
        </div></div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card card-h-100"><div class="card-body">
            <div class="d-flex align-items-center gap-3">
                <div class="avatar avatar-sm bg-warning-subtle text-warning rounded-2"><i class="ri-inbox-line fs-18"></i></div>
                <div><p class="text-muted mb-0 fs-13">New in Queue</p><h4 class="mb-0 fw-semibold" id="sQueue">—</h4></div>
            </div>
        </div></div>
    </div>
</div>

<!-- Actions -->
<div class="card">
    <div class="card-body d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
            <button class="btn btn-success" onclick="runParser()" id="btnRunAll">
                <i class="ri-play-circle-line me-1"></i>Run Parser (All Sources)
            </button>
            <a href="jobs-applications" class="btn btn-outline-warning ms-2">
                <i class="ri-inbox-line me-1"></i>View Queue
            </a>
        </div>
        <button class="btn btn-primary" onclick="showAddModal()">
            <i class="ri-add-line me-1"></i>Add Source
        </button>
    </div>
</div>

<!-- Parser Results -->
<div class="alert alert-info d-none" id="parseResults">
    <strong><i class="ri-robot-line me-1"></i>Parser Results:</strong>
    <div id="parseResultsBody" class="mt-2"></div>
</div>

<!-- Table -->
<div class="card">
    <div class="card-header"><h5 class="card-title mb-0">Parser Sources <span class="badge bg-primary ms-2" id="srcCount">0</span></h5></div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Source</th>
                        <th>Domain</th>
                        <th>Type</th>
                        <th>Category</th>
                        <th>Total Parsed</th>
                        <th>Last Parsed</th>
                        <th>Status</th>
                        <th width="180">Actions</th>
                    </tr>
                </thead>
                <tbody id="tBody"><tr><td colspan="8" class="text-center py-3 text-muted">Loading...</td></tr></tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add/Edit Modal -->
<div class="modal fade" id="srcModal" tabindex="-1"><div class="modal-dialog"><div class="modal-content">
    <div class="modal-header"><h5 class="modal-title" id="srcModalTitle">Add Source</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
    <div class="modal-body">
        <input type="hidden" id="srcId">
        <div class="mb-3">
            <label class="form-label">Name</label>
            <input type="text" class="form-control" id="srcName" placeholder="e.g. Biuro Imigracyjne">
        </div>
        <div class="mb-3">
            <label class="form-label">Domain</label>
            <input type="text" class="form-control" id="srcDomain" placeholder="e.g. biuro-imigracyjne.com">
        </div>
        <div class="mb-3">
            <label class="form-label">Parse URL</label>
            <input type="url" class="form-control" id="srcUrl" placeholder="https://biuro-imigracyjne.com">
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Type</label>
                <select class="form-select" id="srcType">
                    <option value="html_list">HTML List</option>
                    <option value="rss">RSS Feed</option>
                    <option value="sitemap">Sitemap</option>
                    <option value="api">API</option>
                </select>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Category</label>
                <input type="text" class="form-control" id="srcCategory" placeholder="e.g. immigration">
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-primary" onclick="saveSrc()">Save</button>
    </div>
</div></div></div>
@endsection

@section('js')
<script>
(function() {
    var TOKEN = localStorage.getItem('wc_token');
    var H = { 'Accept': 'application/json', 'Authorization': 'Bearer ' + TOKEN };
    var allSources = [];

    function loadSources() {
        fetch('/api/v1/jobs/parser/sources', { headers: H }).then(r => r.json()).then(j => {
            allSources = j.data || [];
            var tbody = document.getElementById('tBody');
            var active = allSources.filter(function(s) { return s.is_active; }).length;
            var totalParsed = allSources.reduce(function(a, s) { return a + (s.total_parsed || 0); }, 0);

            document.getElementById('sTotal').textContent = allSources.length;
            document.getElementById('sActive').textContent = active;
            document.getElementById('sParsed').textContent = totalParsed;
            document.getElementById('srcCount').textContent = allSources.length;

            if (!allSources.length) {
                tbody.innerHTML = '<tr><td colspan="8" class="text-center py-3 text-muted">No sources</td></tr>';
                return;
            }

            tbody.innerHTML = allSources.map(function(s) {
                var lastParsed = s.last_parsed_at ? new Date(s.last_parsed_at).toLocaleDateString('en-GB', {day:'2-digit',month:'short',hour:'2-digit',minute:'2-digit'}) : '<span class="text-muted">Never</span>';
                var statusBadge = s.is_active
                    ? '<span class="badge bg-success-subtle text-success">Active</span>'
                    : '<span class="badge bg-secondary-subtle text-secondary">Inactive</span>';
                var errorIcon = s.last_error ? ' <i class="ri-error-warning-line text-danger" title="' + (s.last_error || '').substring(0, 100).replace(/"/g, '&quot;') + '"></i>' : '';

                return '<tr>' +
                    '<td class="fw-semibold">' + s.name + errorIcon + '</td>' +
                    '<td><a href="https://' + s.domain + '" target="_blank" class="text-primary">' + s.domain + ' <i class="ri-external-link-line fs-11"></i></a></td>' +
                    '<td><span class="badge bg-primary-subtle text-primary">' + (s.parse_type || 'html_list') + '</span></td>' +
                    '<td>' + (s.category || '—') + '</td>' +
                    '<td><strong>' + (s.total_parsed || 0) + '</strong></td>' +
                    '<td class="text-muted fs-12">' + lastParsed + '</td>' +
                    '<td>' + statusBadge + '</td>' +
                    '<td>' +
                        '<button class="btn btn-sm btn-outline-success me-1" onclick="runSingle(' + s.id + ')" title="Parse Now"><i class="ri-play-line"></i></button>' +
                        '<button class="btn btn-sm btn-outline-' + (s.is_active ? 'secondary' : 'primary') + ' me-1" onclick="toggleSrc(' + s.id + ')" title="' + (s.is_active ? 'Deactivate' : 'Activate') + '"><i class="ri-' + (s.is_active ? 'pause' : 'play') + '-circle-line"></i></button>' +
                        '<button class="btn btn-sm btn-outline-warning me-1" onclick="editSrc(' + s.id + ')" title="Edit"><i class="ri-edit-line"></i></button>' +
                        '<button class="btn btn-sm btn-outline-danger" onclick="deleteSrc(' + s.id + ')" title="Delete"><i class="ri-delete-bin-line"></i></button>' +
                    '</td></tr>';
            }).join('');
        }).catch(function() {});
    }

    // Queue count
    fetch('/api/v1/jobs/dashboard', { headers: H }).then(r => r.json()).then(j => {
        var p = (j.data || {}).parsed_jobs || {};
        document.getElementById('sQueue').textContent = p.new || 0;
    }).catch(function() {});

    window.showAddModal = function() {
        document.getElementById('srcId').value = '';
        document.getElementById('srcName').value = '';
        document.getElementById('srcDomain').value = '';
        document.getElementById('srcUrl').value = '';
        document.getElementById('srcType').value = 'html_list';
        document.getElementById('srcCategory').value = '';
        document.getElementById('srcModalTitle').textContent = 'Add Source';
        new bootstrap.Modal(document.getElementById('srcModal')).show();
    };

    window.editSrc = function(id) {
        var s = allSources.find(function(x) { return x.id === id; });
        if (!s) return;
        document.getElementById('srcId').value = s.id;
        document.getElementById('srcName').value = s.name;
        document.getElementById('srcDomain').value = s.domain;
        document.getElementById('srcUrl').value = s.parse_url;
        document.getElementById('srcType').value = s.parse_type || 'html_list';
        document.getElementById('srcCategory').value = s.category || '';
        document.getElementById('srcModalTitle').textContent = 'Edit Source';
        new bootstrap.Modal(document.getElementById('srcModal')).show();
    };

    window.saveSrc = function() {
        var id = document.getElementById('srcId').value;
        var fd = new FormData();
        fd.append('name', document.getElementById('srcName').value);
        fd.append('domain', document.getElementById('srcDomain').value);
        fd.append('parse_url', document.getElementById('srcUrl').value);
        fd.append('parse_type', document.getElementById('srcType').value);
        fd.append('category', document.getElementById('srcCategory').value);

        var url = '/api/v1/jobs/parser/sources';
        if (id) {
            url += '/' + id;
            fd.append('_method', 'PUT');
        }

        fetch(url, { method: 'POST', headers: { 'Accept': 'application/json', 'Authorization': 'Bearer ' + TOKEN }, body: fd })
            .then(r => r.json()).then(function(j) {
                if (j.success) {
                    bootstrap.Modal.getInstance(document.getElementById('srcModal')).hide();
                    loadSources();
                } else {
                    alert(j.message || 'Error');
                }
            }).catch(function() { alert('Error'); });
    };

    window.toggleSrc = function(id) {
        fetch('/api/v1/jobs/parser/sources/' + id + '/toggle', { method: 'POST', headers: H })
            .then(r => r.json()).then(function() { loadSources(); }).catch(function() {});
    };

    window.deleteSrc = function(id) {
        if (!confirm('Delete this source?')) return;
        fetch('/api/v1/jobs/parser/sources/' + id, { method: 'DELETE', headers: H })
            .then(r => r.json()).then(function() { loadSources(); }).catch(function() {});
    };

    window.runParser = function() {
        var btn = document.getElementById('btnRunAll');
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Parsing...';

        fetch('/api/v1/jobs/parser/run', { method: 'POST', headers: H })
            .then(r => r.json()).then(function(j) {
                btn.disabled = false;
                btn.innerHTML = '<i class="ri-play-circle-line me-1"></i>Run Parser (All Sources)';
                showResults(j.data || []);
                loadSources();
            }).catch(function() {
                btn.disabled = false;
                btn.innerHTML = '<i class="ri-play-circle-line me-1"></i>Run Parser (All Sources)';
            });
    };

    window.runSingle = function(id) {
        var fd = new FormData();
        fd.append('source_id', id);
        fetch('/api/v1/jobs/parser/run', { method: 'POST', headers: { 'Accept': 'application/json', 'Authorization': 'Bearer ' + TOKEN }, body: fd })
            .then(r => r.json()).then(function(j) {
                showResults(j.data || []);
                loadSources();
            }).catch(function() {});
    };

    function showResults(results) {
        var div = document.getElementById('parseResults');
        var body = document.getElementById('parseResultsBody');
        if (!results.length) { div.classList.add('d-none'); return; }
        div.classList.remove('d-none');

        body.innerHTML = results.map(function(r) {
            if (r.status === 'ok') {
                return '<div class="text-success"><i class="ri-checkbox-circle-line me-1"></i><strong>' + r.source + '</strong>: ' + r.new_jobs + ' new jobs (found ' + r.total_found + ')</div>';
            } else {
                return '<div class="text-danger"><i class="ri-error-warning-line me-1"></i><strong>' + r.source + '</strong>: ' + (r.message || 'error') + '</div>';
            }
        }).join('');
    }

    loadSources();
})();
</script>
@endsection
