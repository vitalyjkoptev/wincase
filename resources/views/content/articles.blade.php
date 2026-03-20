@extends('partials.layouts.master')
@section('title', 'Articles | WinCase CRM')
@section('sub-title', 'Articles')
@section('sub-title-lang', 'wc-articles')
@section('pagetitle', 'Content')
@section('pagetitle-lang', 'wc-content')
@section('buttonTitle', 'New Article')
@section('buttonTitle-lang', 'wc-new-article')
@section('link', 'content-create-article')

@section('content')
<!-- Stat Cards -->
<div class="row">
    <div class="col-xl-3 col-md-6">
        <div class="card card-h-100">
            <div class="card-body">
                <div class="d-flex align-items-center gap-3">
                    <div class="avatar avatar-sm bg-primary-subtle text-primary rounded-2">
                        <i class="ri-newspaper-line fs-18"></i>
                    </div>
                    <div>
                        <p class="text-muted mb-0 fs-13">Total Articles</p>
                        <h4 class="mb-0 fw-semibold" id="statTotal">--</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card card-h-100">
            <div class="card-body">
                <div class="d-flex align-items-center gap-3">
                    <div class="avatar avatar-sm bg-success-subtle text-success rounded-2">
                        <i class="ri-check-double-line fs-18"></i>
                    </div>
                    <div>
                        <p class="text-muted mb-0 fs-13">Published</p>
                        <h4 class="mb-0 fw-semibold" id="statPublished">--</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card card-h-100">
            <div class="card-body">
                <div class="d-flex align-items-center gap-3">
                    <div class="avatar avatar-sm bg-secondary-subtle text-secondary rounded-2">
                        <i class="ri-edit-2-line fs-18"></i>
                    </div>
                    <div>
                        <p class="text-muted mb-0 fs-13">Draft</p>
                        <h4 class="mb-0 fw-semibold" id="statDraft">--</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card card-h-100">
            <div class="card-body">
                <div class="d-flex align-items-center gap-3">
                    <div class="avatar avatar-sm bg-warning-subtle text-warning rounded-2">
                        <i class="ri-eye-line fs-18"></i>
                    </div>
                    <div>
                        <p class="text-muted mb-0 fs-13">In Review</p>
                        <h4 class="mb-0 fw-semibold" id="statReview">--</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="card">
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-3">
                <input type="text" class="form-control" id="filterSearch" placeholder="Search articles...">
            </div>
            <div class="col-md-2">
                <select class="form-select" id="filterCategory">
                    <option value="">All Categories</option>
                    <option value="immigration">Immigration</option>
                    <option value="work_permits">Work Permits</option>
                    <option value="residence">Residence</option>
                    <option value="tax">Tax & Accounting</option>
                    <option value="company_reg">Company Registration</option>
                    <option value="legal_updates">Legal Updates</option>
                    <option value="guides">Guides</option>
                    <option value="business">Business</option>
                    <option value="eu_policy">EU Policy</option>
                    <option value="tech_news">Tech News</option>
                </select>
            </div>
            <div class="col-md-2">
                <select class="form-select" id="filterStatus">
                    <option value="">All Statuses</option>
                    <option value="published">Published</option>
                    <option value="draft">Draft</option>
                    <option value="review">Review</option>
                    <option value="scheduled">Scheduled</option>
                </select>
            </div>
            <div class="col-md-2">
                <select class="form-select" id="filterLanguage">
                    <option value="">All Languages</option>
                    <option value="pl">Polish</option>
                    <option value="en">English</option>
                    <option value="uk">Ukrainian</option>
                    <option value="ru">Russian</option>
                </select>
            </div>
            <div class="col-md-2">
                <select class="form-select" id="filterPerPage">
                    <option value="25">25 per page</option>
                    <option value="50">50 per page</option>
                    <option value="100">100 per page</option>
                </select>
            </div>
            <div class="col-md-1">
                <button class="btn btn-primary w-100" id="btnFilter"><i class="ri-filter-3-line"></i></button>
            </div>
        </div>
    </div>
</div>

<!-- Articles Table -->
<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width: 40px;"><input class="form-check-input" type="checkbox" id="checkAll"></th>
                        <th>Title</th>
                        <th>Category</th>
                        <th style="width: 80px;">Language</th>
                        <th>Status</th>
                        <th>Source</th>
                        <th>Published</th>
                        <th style="width: 60px;">Actions</th>
                    </tr>
                </thead>
                <tbody id="articlesBody">
                    <tr>
                        <td colspan="8" class="text-center text-muted py-4">
                            <div class="spinner-border spinner-border-sm me-2" role="status"></div>Loading articles...
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer">
        <div class="d-flex align-items-center justify-content-between">
            <div class="text-muted fs-13" id="paginationInfo">Showing 0 of 0</div>
            <nav>
                <ul class="pagination pagination-sm mb-0" id="pagination"></ul>
            </nav>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const API = '/api/v1';
    const token = localStorage.getItem('wc_token');
    let currentPage = 1;

    const categoryColors = {
        immigration: 'primary',
        work_permits: 'success',
        residence: 'info',
        tax: 'warning',
        company_reg: 'dark',
        legal_updates: 'danger',
        guides: 'secondary',
        business: 'primary',
        eu_policy: 'info',
        tech_news: 'dark'
    };

    const statusColors = {
        published: 'success',
        draft: 'secondary',
        review: 'warning',
        scheduled: 'info'
    };

    const langFlags = {
        pl: '\u{1F1F5}\u{1F1F1}',
        en: '\u{1F1EC}\u{1F1E7}',
        uk: '\u{1F1FA}\u{1F1E6}',
        ru: '\u{1F1F7}\u{1F1FA}'
    };

    const langTitles = {
        pl: 'Polish',
        en: 'English',
        uk: 'Ukrainian',
        ru: 'Russian'
    };

    function headers() {
        return { 'Authorization': 'Bearer ' + token, 'Accept': 'application/json' };
    }

    function formatDate(dateStr) {
        if (!dateStr) return '--';
        var d = new Date(dateStr);
        if (isNaN(d.getTime())) return '--';
        return d.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
    }

    function escapeHtml(str) {
        if (!str) return '';
        var div = document.createElement('div');
        div.textContent = str;
        return div.innerHTML;
    }

    function categoryLabel(cat) {
        if (!cat) return '';
        return cat.replace(/_/g, ' ').replace(/\b\w/g, function(c) { return c.toUpperCase(); });
    }

    function loadArticles(page) {
        page = page || 1;
        currentPage = page;

        var status = document.getElementById('filterStatus').value;
        var category = document.getElementById('filterCategory').value;
        var language = document.getElementById('filterLanguage').value;
        var search = document.getElementById('filterSearch').value.trim();
        var perPage = document.getElementById('filterPerPage').value;

        var params = new URLSearchParams();
        params.append('page', page);
        params.append('per_page', perPage);
        if (status) params.append('status', status);
        if (category) params.append('category', category);
        if (language) params.append('language', language);
        if (search) params.append('search', search);

        var tbody = document.getElementById('articlesBody');
        tbody.innerHTML = '<tr><td colspan="8" class="text-center text-muted py-4"><div class="spinner-border spinner-border-sm me-2" role="status"></div>Loading articles...</td></tr>';

        fetch(API + '/news/articles?' + params.toString(), { headers: headers() })
            .then(function(r) { return r.json(); })
            .then(function(res) {
                if (!res.success || !res.data) {
                    tbody.innerHTML = '<tr><td colspan="8" class="text-center text-muted py-4">Failed to load articles</td></tr>';
                    return;
                }

                var data = res.data;
                var articles = data.articles || [];
                var stats = data.stats || {};
                var meta = data.meta || {};

                // Update stat cards
                document.getElementById('statTotal').textContent = stats.total != null ? stats.total : 0;
                document.getElementById('statPublished').textContent = stats.published != null ? stats.published : 0;
                document.getElementById('statDraft').textContent = stats.draft != null ? stats.draft : 0;
                document.getElementById('statReview').textContent = stats.review != null ? stats.review : 0;

                // Build table rows
                if (articles.length === 0) {
                    tbody.innerHTML = '<tr><td colspan="8" class="text-center text-muted py-4"><i class="ri-article-line fs-24 d-block mb-2"></i>No articles found</td></tr>';
                    renderPagination(meta);
                    return;
                }

                var html = '';
                for (var i = 0; i < articles.length; i++) {
                    var a = articles[i];
                    var catColor = categoryColors[a.category] || 'secondary';
                    var stColor = statusColors[a.status] || 'secondary';
                    var flag = langFlags[a.language] || '';
                    var langTitle = langTitles[a.language] || a.language || '';
                    var statusLabel = a.status ? a.status.charAt(0).toUpperCase() + a.status.slice(1) : '';
                    var pubDate = formatDate(a.published_at || a.created_at);
                    var sourceBadge = a.source_name ? '<br><small class="text-muted fs-11">' + escapeHtml(a.source_name) + '</small>' : '';
                    var siteBadge = a.site_domain ? ' <span class="badge bg-light text-muted fs-10">' + escapeHtml(a.site_domain) + '</span>' : '';

                    html += '<tr data-id="' + a.id + '">'
                        + '<td><input class="form-check-input row-check" type="checkbox" value="' + a.id + '"></td>'
                        + '<td>'
                        +   '<a href="content-create-article?id=' + a.id + '" class="fw-semibold text-body text-truncate d-inline-block" style="max-width: 320px;" title="' + escapeHtml(a.title) + '">'
                        +     escapeHtml(a.title)
                        +   '</a>'
                        +   sourceBadge + siteBadge
                        + '</td>'
                        + '<td><span class="badge bg-' + catColor + '-subtle text-' + catColor + '">' + escapeHtml(categoryLabel(a.category)) + '</span></td>'
                        + '<td class="text-center fs-18" title="' + escapeHtml(langTitle) + '">' + flag + '</td>'
                        + '<td><span class="badge bg-' + stColor + '-subtle text-' + stColor + '">' + escapeHtml(statusLabel) + '</span></td>'
                        + '<td class="text-muted fs-12">' + escapeHtml(a.source_name || '--') + '</td>'
                        + '<td class="text-muted fs-12">' + pubDate + '</td>'
                        + '<td>'
                        +   '<div class="dropdown">'
                        +     '<button class="btn btn-sm btn-subtle-secondary" data-bs-toggle="dropdown"><i class="ri-more-2-fill"></i></button>'
                        +     '<ul class="dropdown-menu">'
                        +       '<li><a class="dropdown-item" href="content-create-article?id=' + a.id + '&view=1"><i class="ri-eye-line me-2"></i>View</a></li>'
                        +       '<li><a class="dropdown-item" href="content-create-article?id=' + a.id + '"><i class="ri-edit-line me-2"></i>Edit</a></li>';

                    if (a.status !== 'published') {
                        html += '<li><a class="dropdown-item text-success action-approve" href="javascript:void(0)" data-id="' + a.id + '"><i class="ri-check-line me-2"></i>Approve</a></li>';
                    }
                    if (a.status === 'review' || a.status === 'draft') {
                        html += '<li><a class="dropdown-item text-warning action-reject" href="javascript:void(0)" data-id="' + a.id + '"><i class="ri-close-line me-2"></i>Reject</a></li>';
                    }

                    html += '<li><hr class="dropdown-divider"></li>'
                        +       '<li><a class="dropdown-item text-danger action-delete" href="javascript:void(0)" data-id="' + a.id + '"><i class="ri-delete-bin-line me-2"></i>Delete</a></li>'
                        +     '</ul>'
                        +   '</div>'
                        + '</td>'
                        + '</tr>';
                }

                tbody.innerHTML = html;
                renderPagination(meta);
                bindActions();
            })
            .catch(function(err) {
                console.error('Articles load error:', err);
                tbody.innerHTML = '<tr><td colspan="8" class="text-center text-danger py-4"><i class="ri-error-warning-line me-1"></i>Error loading articles</td></tr>';
            });
    }

    function renderPagination(meta) {
        var container = document.getElementById('pagination');
        var info = document.getElementById('paginationInfo');

        if (!meta || !meta.last_page) {
            container.innerHTML = '';
            info.textContent = 'Showing 0 of 0';
            return;
        }

        var total = meta.total || 0;
        var perPage = meta.per_page || 25;
        var current = meta.current_page || 1;
        var last = meta.last_page || 1;

        var from = total > 0 ? (current - 1) * perPage + 1 : 0;
        var to = Math.min(current * perPage, total);
        info.textContent = 'Showing ' + from + '-' + to + ' of ' + total;

        if (last <= 1) {
            container.innerHTML = '';
            return;
        }

        var html = '';

        // Previous
        html += '<li class="page-item ' + (current <= 1 ? 'disabled' : '') + '">'
            + '<a class="page-link" href="javascript:void(0)" data-page="' + (current - 1) + '">Previous</a></li>';

        // Page numbers with smart ellipsis
        var pages = [];
        pages.push(1);
        if (current > 3) pages.push('...');
        for (var p = Math.max(2, current - 1); p <= Math.min(last - 1, current + 1); p++) {
            pages.push(p);
        }
        if (current < last - 2) pages.push('...');
        if (last > 1) pages.push(last);

        for (var i = 0; i < pages.length; i++) {
            var pg = pages[i];
            if (pg === '...') {
                html += '<li class="page-item disabled"><a class="page-link" href="javascript:void(0)">...</a></li>';
            } else {
                html += '<li class="page-item ' + (pg === current ? 'active' : '') + '">'
                    + '<a class="page-link" href="javascript:void(0)" data-page="' + pg + '">' + pg + '</a></li>';
            }
        }

        // Next
        html += '<li class="page-item ' + (current >= last ? 'disabled' : '') + '">'
            + '<a class="page-link" href="javascript:void(0)" data-page="' + (current + 1) + '">Next</a></li>';

        container.innerHTML = html;

        // Bind pagination clicks
        container.querySelectorAll('a[data-page]').forEach(function(link) {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                var pg = parseInt(this.getAttribute('data-page'));
                if (pg >= 1 && pg <= last) {
                    loadArticles(pg);
                }
            });
        });
    }

    function articleAction(endpoint, method, id) {
        var fd = new FormData();
        if (method === 'DELETE') {
            fd.append('_method', 'DELETE');
        }
        fetch(API + '/news/articles/' + id + endpoint, {
            method: 'POST',
            headers: { 'Authorization': 'Bearer ' + token, 'Accept': 'application/json' },
            body: fd
        })
        .then(function(r) { return r.json(); })
        .then(function(res) {
            if (res.success) {
                if (typeof CRM !== 'undefined' && CRM.toast) {
                    CRM.toast('success', res.message || 'Action completed');
                }
                loadArticles(currentPage);
            } else {
                if (typeof CRM !== 'undefined' && CRM.toast) {
                    CRM.toast('danger', res.message || 'Action failed');
                }
            }
        })
        .catch(function(err) {
            console.error('Action error:', err);
            if (typeof CRM !== 'undefined' && CRM.toast) {
                CRM.toast('danger', 'Network error');
            }
        });
    }

    function bindActions() {
        // Delete
        document.querySelectorAll('.action-delete').forEach(function(btn) {
            btn.addEventListener('click', function() {
                var id = this.getAttribute('data-id');
                if (confirm('Are you sure you want to delete this article?')) {
                    articleAction('', 'DELETE', id);
                }
            });
        });

        // Approve
        document.querySelectorAll('.action-approve').forEach(function(btn) {
            btn.addEventListener('click', function() {
                var id = this.getAttribute('data-id');
                articleAction('/approve', 'POST', id);
            });
        });

        // Reject
        document.querySelectorAll('.action-reject').forEach(function(btn) {
            btn.addEventListener('click', function() {
                var id = this.getAttribute('data-id');
                articleAction('/reject', 'POST', id);
            });
        });
    }

    // Filter events
    document.getElementById('filterStatus').addEventListener('change', function() { loadArticles(1); });
    document.getElementById('filterCategory').addEventListener('change', function() { loadArticles(1); });
    document.getElementById('filterLanguage').addEventListener('change', function() { loadArticles(1); });
    document.getElementById('filterPerPage').addEventListener('change', function() { loadArticles(1); });
    document.getElementById('btnFilter').addEventListener('click', function() { loadArticles(1); });

    // Search on Enter
    document.getElementById('filterSearch').addEventListener('keydown', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            loadArticles(1);
        }
    });

    // Check All checkbox
    document.getElementById('checkAll').addEventListener('change', function() {
        var checked = this.checked;
        document.querySelectorAll('.row-check').forEach(function(cb) { cb.checked = checked; });
    });

    // Initial load
    loadArticles(1);
});
</script>
@endsection
