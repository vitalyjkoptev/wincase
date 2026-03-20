@extends('partials.layouts.master')
@section('title', 'Jobs Dashboard | WinCase CRM')
@section('sub-title', 'Jobs Dashboard')
@section('pagetitle', 'Jobs Portal')

@section('content')
<!-- Stats -->
<div class="row" id="statsRow">
    <div class="col-xl-3 col-md-6">
        <div class="card card-h-100"><div class="card-body">
            <div class="d-flex align-items-center gap-3">
                <div class="avatar avatar-sm bg-primary-subtle text-primary rounded-2"><i class="ri-briefcase-4-line fs-18"></i></div>
                <div><p class="text-muted mb-0 fs-13">Vacancies</p><h4 class="mb-0 fw-semibold" id="sVacTotal">—</h4>
                <small class="text-muted"><span class="text-success" id="sVacActive">0</span> active</small></div>
            </div>
        </div></div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card card-h-100"><div class="card-body">
            <div class="d-flex align-items-center gap-3">
                <div class="avatar avatar-sm bg-success-subtle text-success rounded-2"><i class="ri-building-2-line fs-18"></i></div>
                <div><p class="text-muted mb-0 fs-13">Employers</p><h4 class="mb-0 fw-semibold" id="sEmpTotal">—</h4>
                <small class="text-muted"><span class="text-warning" id="sEmpPending">0</span> pending</small></div>
            </div>
        </div></div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card card-h-100"><div class="card-body">
            <div class="d-flex align-items-center gap-3">
                <div class="avatar avatar-sm bg-info-subtle text-info rounded-2"><i class="ri-user-search-line fs-18"></i></div>
                <div><p class="text-muted mb-0 fs-13">Candidates</p><h4 class="mb-0 fw-semibold" id="sSeekTotal">—</h4>
                <small class="text-muted"><span class="text-success" id="sSeekActive">0</span> active</small></div>
            </div>
        </div></div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card card-h-100"><div class="card-body">
            <div class="d-flex align-items-center gap-3">
                <div class="avatar avatar-sm bg-warning-subtle text-warning rounded-2"><i class="ri-robot-line fs-18"></i></div>
                <div><p class="text-muted mb-0 fs-13">Parsed Jobs</p><h4 class="mb-0 fw-semibold" id="sParsedTotal">—</h4>
                <small class="text-muted"><span class="text-primary" id="sParsedNew">0</span> new to review</small></div>
            </div>
        </div></div>
    </div>
</div>

<!-- Quick Links -->
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header"><h5 class="card-title mb-0">Recent Vacancies</h5></div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light"><tr><th>Title</th><th>City</th><th>Employer</th><th>Status</th><th>Views</th><th>Created</th></tr></thead>
                        <tbody id="recentVacancies"><tr><td colspan="6" class="text-center py-3 text-muted">Loading...</td></tr></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-header"><h5 class="card-title mb-0">Quick Actions</h5></div>
            <div class="card-body">
                <a href="jobs-listings" class="btn btn-primary w-100 mb-2"><i class="ri-briefcase-4-line me-1"></i>All Listings</a>
                <a href="jobs-create" class="btn btn-success w-100 mb-2"><i class="ri-add-line me-1"></i>Create Listing</a>
                <a href="jobs-employers" class="btn btn-outline-primary w-100 mb-2"><i class="ri-building-2-line me-1"></i>Employers</a>
                <a href="jobs-candidates" class="btn btn-outline-info w-100 mb-2"><i class="ri-user-search-line me-1"></i>Candidates</a>
            </div>
        </div>
        <div class="card">
            <div class="card-header"><h5 class="card-title mb-0">WinCaseJobs Site</h5></div>
            <div class="card-body">
                <a href="https://wincasejobs.com" target="_blank" class="btn btn-outline-secondary w-100 mb-2"><i class="ri-external-link-line me-1"></i>Open Website</a>
                <a href="https://admin.wincasejobs.com" target="_blank" class="btn btn-outline-secondary w-100"><i class="ri-settings-4-line me-1"></i>WCJ Admin Panel</a>
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

    fetch('/api/v1/jobs/dashboard', { headers: H }).then(r => r.json()).then(j => {
        var d = j.data || {};
        var v = d.vacancies || {}; var e = d.employers || {}; var s = d.seekers || {}; var p = d.parsed_jobs || {};
        document.getElementById('sVacTotal').textContent = v.total || 0;
        document.getElementById('sVacActive').textContent = v.active || 0;
        document.getElementById('sEmpTotal').textContent = e.total || 0;
        document.getElementById('sEmpPending').textContent = e.pending || 0;
        document.getElementById('sSeekTotal').textContent = s.total || 0;
        document.getElementById('sSeekActive').textContent = s.active || 0;
        document.getElementById('sParsedTotal').textContent = p.total || 0;
        document.getElementById('sParsedNew').textContent = p.new || 0;
    }).catch(function() {});

    fetch('/api/v1/jobs/vacancies?per_page=10', { headers: H }).then(r => r.json()).then(j => {
        var items = (j.data && j.data.data) || [];
        var tbody = document.getElementById('recentVacancies');
        if (!items.length) { tbody.innerHTML = '<tr><td colspan="6" class="text-center py-3 text-muted">No vacancies yet</td></tr>'; return; }
        tbody.innerHTML = items.map(function(v) {
            var st = { active: 'success', draft: 'warning', closed: 'secondary', archived: 'dark' };
            return '<tr><td class="fw-semibold">' + v.title + '</td>' +
                '<td>' + (v.city || '—') + '</td>' +
                '<td>' + (v.employer ? v.employer.company_name : '—') + '</td>' +
                '<td><span class="badge bg-' + (st[v.status] || 'secondary') + '-subtle text-' + (st[v.status] || 'secondary') + '">' + v.status + '</span></td>' +
                '<td>' + (v.views || 0) + '</td>' +
                '<td class="text-muted fs-12">' + new Date(v.created_at).toLocaleDateString('en-GB', {day:'2-digit',month:'short'}) + '</td></tr>';
        }).join('');
    }).catch(function() {});
})();
</script>
@endsection
