@extends('partials.layouts.master')
@section('title', 'Job Categories | WinCase CRM')
@section('sub-title', 'Categories')
@section('pagetitle', 'Jobs Portal')

@section('content')
<!-- Info -->
<div class="row">
    <div class="col-xl-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Job Categories</h5>
                <span class="badge bg-primary" id="catCount">—</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Category</th>
                                <th>Vacancies</th>
                                <th>Active</th>
                                <th>% of Total</th>
                            </tr>
                        </thead>
                        <tbody id="catBody"><tr><td colspan="4" class="text-center py-3 text-muted">Loading...</td></tr></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-4">
        <div class="card">
            <div class="card-header"><h5 class="card-title mb-0">Category Overview</h5></div>
            <div class="card-body">
                <p class="text-muted fs-13">Categories are automatically derived from vacancy data. Each vacancy has a <code>category</code> field.</p>
                <hr>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Total Categories:</span>
                    <strong id="totalCats">—</strong>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Total Vacancies:</span>
                    <strong id="totalVacs">—</strong>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Most Popular:</span>
                    <strong id="topCat">—</strong>
                </div>
                <hr>
                <h6>Standard Categories</h6>
                <div class="d-flex flex-wrap gap-1">
                    <span class="badge bg-primary-subtle text-primary">Production</span>
                    <span class="badge bg-primary-subtle text-primary">Construction</span>
                    <span class="badge bg-primary-subtle text-primary">Logistics</span>
                    <span class="badge bg-primary-subtle text-primary">Driver</span>
                    <span class="badge bg-primary-subtle text-primary">Welding</span>
                    <span class="badge bg-primary-subtle text-primary">Agriculture</span>
                    <span class="badge bg-primary-subtle text-primary">Hospitality</span>
                    <span class="badge bg-primary-subtle text-primary">Cleaning</span>
                    <span class="badge bg-primary-subtle text-primary">IT / Office</span>
                    <span class="badge bg-primary-subtle text-primary">Medical</span>
                    <span class="badge bg-primary-subtle text-primary">Retail</span>
                    <span class="badge bg-primary-subtle text-primary">Other</span>
                </div>
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

    // Load all vacancies to compute categories
    fetch('/api/v1/jobs/vacancies?per_page=1000', { headers: H }).then(r => r.json()).then(j => {
        var items = (j.data && j.data.data) || [];
        var totalVacs = items.length;

        // Group by category
        var cats = {};
        items.forEach(function(v) {
            var cat = v.category || 'Uncategorized';
            if (!cats[cat]) cats[cat] = { total: 0, active: 0 };
            cats[cat].total++;
            if (v.status === 'active') cats[cat].active++;
        });

        var sorted = Object.keys(cats).sort(function(a, b) { return cats[b].total - cats[a].total; });
        var tbody = document.getElementById('catBody');

        if (!sorted.length) {
            tbody.innerHTML = '<tr><td colspan="4" class="text-center py-3 text-muted">No categories yet — create vacancies first</td></tr>';
            document.getElementById('catCount').textContent = '0';
            document.getElementById('totalCats').textContent = '0';
            document.getElementById('totalVacs').textContent = '0';
            document.getElementById('topCat').textContent = '—';
            return;
        }

        var colors = ['primary', 'success', 'warning', 'info', 'danger', 'secondary', 'dark'];
        tbody.innerHTML = sorted.map(function(cat, i) {
            var pct = totalVacs > 0 ? Math.round(cats[cat].total / totalVacs * 100) : 0;
            var color = colors[i % colors.length];
            return '<tr>' +
                '<td><span class="badge bg-' + color + '-subtle text-' + color + ' fs-13">' + cat + '</span></td>' +
                '<td class="fw-semibold">' + cats[cat].total + '</td>' +
                '<td><span class="text-success">' + cats[cat].active + '</span></td>' +
                '<td>' +
                    '<div class="d-flex align-items-center gap-2">' +
                        '<div class="progress flex-grow-1" style="height:6px"><div class="progress-bar bg-' + color + '" style="width:' + pct + '%"></div></div>' +
                        '<span class="text-muted fs-12">' + pct + '%</span>' +
                    '</div>' +
                '</td></tr>';
        }).join('');

        document.getElementById('catCount').textContent = sorted.length;
        document.getElementById('totalCats').textContent = sorted.length;
        document.getElementById('totalVacs').textContent = totalVacs;
        document.getElementById('topCat').textContent = sorted[0];
    }).catch(function() {});
})();
</script>
@endsection
