@extends('partials.layouts.master')
@section('title', 'Categories | WinCase CRM')
@section('sub-title', 'Categories')
@section('sub-title-lang', 'wc-categories')
@section('pagetitle', 'Content')
@section('pagetitle-lang', 'wc-content')
@section('buttonTitle', 'Add Category')
@section('buttonTitle-lang', 'wc-add-category')
@section('modalTarget', 'addCategoryModal')

@section('content')
<!-- Categories Table -->
<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width: 50px;">#</th>
                        <th>Name</th>
                        <th>Slug</th>
                        <th style="width: 120px;">Articles</th>
                        <th style="width: 100px;">Status</th>
                        <th style="width: 140px;">Actions</th>
                    </tr>
                </thead>
                <tbody id="categoriesBody">
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">
                            <div class="spinner-border spinner-border-sm me-2" role="status"></div>
                            Loading categories...
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add Category Modal -->
<div class="modal fade" id="addCategoryModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="ri-folder-add-line me-2"></i>Add Category</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-12">
                        <label for="categoryName" class="form-label">Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="categoryName" placeholder="Category name...">
                    </div>
                    <div class="col-12">
                        <label for="categorySlug" class="form-label">Slug</label>
                        <div class="input-group">
                            <span class="input-group-text fs-13">/category/</span>
                            <input type="text" class="form-control" id="categorySlug" placeholder="auto-generated" style="font-family: monospace; font-size: 13px;">
                        </div>
                    </div>
                    <div class="col-12">
                        <label for="categoryParent" class="form-label">Parent Category</label>
                        <select class="form-select" id="categoryParent">
                            <option value="" selected>None (Top Level)</option>
                            <option value="immigration">Immigration</option>
                            <option value="work-permits">Work Permits</option>
                            <option value="residence">Residence</option>
                            <option value="tax">Tax & Accounting</option>
                            <option value="company-registration">Company Registration</option>
                            <option value="legal-updates">Legal Updates</option>
                            <option value="guides">Guides</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <label for="categoryDescription" class="form-label">Description</label>
                        <textarea class="form-control" id="categoryDescription" rows="3" placeholder="Short description of the category..."></textarea>
                    </div>
                    <div class="col-12">
                        <div class="d-flex align-items-center justify-content-between">
                            <label for="categoryStatus" class="form-label mb-0">Status</label>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="categoryStatus" checked>
                                <label class="form-check-label" for="categoryStatus">Active</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary"><i class="ri-save-line me-1"></i>Save Category</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const API = '/api/v1';
    const TOKEN = localStorage.getItem('wc_token');
    const tbody = document.getElementById('categoriesBody');

    // Icon mapping by slug keyword
    const ICONS = {
        immigration: { emoji: '\ud83d\udccd', bg: 'primary' },
        work_permits: { emoji: '\ud83d\udcbc', bg: 'warning' },
        residence: { emoji: '\ud83c\udfe0', bg: 'success' },
        tax: { emoji: '\ud83d\udcb6', bg: 'info' },
        company_reg: { emoji: '\ud83c\udfe2', bg: 'danger' },
        legal_updates: { emoji: '\u2696\ufe0f', bg: 'dark' },
        guides: { emoji: '\ud83d\udcd6', bg: 'secondary' },
        business: { emoji: '\ud83d\udcca', bg: 'primary' },
        eu_policy: { emoji: '\ud83c\uddea\ud83c\uddfa', bg: 'info' },
        tech_news: { emoji: '\ud83d\udcbb', bg: 'warning' }
    };

    function getIcon(slug) {
        for (var key in ICONS) {
            if (slug && slug.indexOf(key) !== -1) return ICONS[key];
        }
        return { emoji: '\ud83d\udcc1', bg: 'secondary' };
    }

    function escapeHtml(str) {
        if (!str) return '';
        var div = document.createElement('div');
        div.textContent = str;
        return div.innerHTML;
    }

    function renderRow(cat, idx) {
        var icon = getIcon(cat.slug);
        var count = cat.articles_count || 0;
        var status = cat.status || 'active';
        var isActive = status === 'active' || status === 'Active';
        var statusBadge = isActive
            ? '<span class="badge bg-success-subtle text-success">Active</span>'
            : '<span class="badge bg-secondary-subtle text-secondary">' + escapeHtml(status) + '</span>';

        return '<tr>' +
            '<td class="text-muted">' + (idx + 1) + '</td>' +
            '<td>' +
                '<div class="d-flex align-items-center gap-2">' +
                    '<div class="avatar avatar-xs bg-' + icon.bg + '-subtle text-' + icon.bg + ' rounded-2 d-flex align-items-center justify-content-center" style="width:28px;height:28px;font-size:16px;">' +
                        icon.emoji +
                    '</div>' +
                    '<span class="fw-semibold">' + escapeHtml(cat.name) + '</span>' +
                '</div>' +
            '</td>' +
            '<td><code class="fs-12">' + escapeHtml(cat.slug) + '</code></td>' +
            '<td><span class="badge bg-primary-subtle text-primary">' + count + ' articles</span></td>' +
            '<td>' + statusBadge + '</td>' +
            '<td>' +
                '<div class="d-flex gap-1">' +
                    '<button class="btn btn-sm btn-subtle-primary" data-bs-toggle="modal" data-bs-target="#addCategoryModal" title="Edit">' +
                        '<i class="ri-edit-line"></i>' +
                    '</button>' +
                    '<a href="/admin/content/articles?category=' + encodeURIComponent(cat.slug) + '" class="btn btn-sm btn-subtle-info" title="View Articles">' +
                        '<i class="ri-article-line"></i>' +
                    '</a>' +
                '</div>' +
            '</td>' +
        '</tr>';
    }

    function loadCategories() {
        tbody.innerHTML = '<tr><td colspan="6" class="text-center text-muted py-4">' +
            '<div class="spinner-border spinner-border-sm me-2" role="status"></div>' +
            'Loading categories...</td></tr>';

        fetch(API + '/news/categories', {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'Authorization': 'Bearer ' + TOKEN
            }
        })
        .then(function(res) {
            if (!res.ok) throw new Error('HTTP ' + res.status);
            return res.json();
        })
        .then(function(json) {
            var categories = json.data || json || [];
            if (!Array.isArray(categories)) categories = [];

            if (categories.length === 0) {
                tbody.innerHTML = '<tr><td colspan="6" class="text-center text-muted py-4">' +
                    '<i class="ri-folder-line fs-24 d-block mb-2"></i>No categories found</td></tr>';
                return;
            }

            var html = '';
            for (var i = 0; i < categories.length; i++) {
                html += renderRow(categories[i], i);
            }
            tbody.innerHTML = html;
        })
        .catch(function(err) {
            console.error('Categories load error:', err);
            tbody.innerHTML = '<tr><td colspan="6" class="text-center text-danger py-4">' +
                '<i class="ri-error-warning-line fs-24 d-block mb-2"></i>' +
                'Failed to load categories: ' + escapeHtml(err.message) + '</td></tr>';
        });
    }

    // Load on page init
    loadCategories();

    // --- Polish slug auto-generation for Add Category modal ---
    var nameInput = document.getElementById('categoryName');
    var slugInput = document.getElementById('categorySlug');

    if (nameInput && slugInput) {
        nameInput.addEventListener('input', function() {
            var map = {
                '\u0105': 'a', '\u0107': 'c', '\u0119': 'e', '\u0142': 'l', '\u0144': 'n',
                '\u00f3': 'o', '\u015b': 's', '\u017a': 'z', '\u017c': 'z',
                '\u0104': 'a', '\u0106': 'c', '\u0118': 'e', '\u0141': 'l', '\u0143': 'n',
                '\u00d3': 'o', '\u015a': 's', '\u0179': 'z', '\u017b': 'z'
            };
            var slug = this.value
                .split('')
                .map(function(ch) { return map[ch] || ch; })
                .join('')
                .toLowerCase()
                .replace(/[^\w\s-]/g, '')
                .replace(/[\s_]+/g, '-')
                .replace(/^-+|-+$/g, '');
            slugInput.value = slug;
        });
    }

    // --- Save Category button (informational - categories are config-based) ---
    var saveBtn = document.querySelector('#addCategoryModal .btn-primary');
    if (saveBtn) {
        saveBtn.addEventListener('click', function() {
            var name = (document.getElementById('categoryName').value || '').trim();
            var slug = (document.getElementById('categorySlug').value || '').trim();

            if (!name) {
                if (typeof CRM !== 'undefined' && CRM.toast) {
                    CRM.toast('Please enter a category name', 'warning');
                } else {
                    alert('Please enter a category name');
                }
                return;
            }

            // Categories are managed via NewsArticle::getCategories() config.
            // Show informational message.
            if (typeof CRM !== 'undefined' && CRM.toast) {
                CRM.toast('Category "' + name + '" (' + slug + ') noted. Categories are config-based in NewsArticle::getCategories().', 'info');
            } else {
                alert('Category "' + name + '" (' + slug + ') noted.\nCategories are config-based in NewsArticle::getCategories().');
            }

            var modal = bootstrap.Modal.getInstance(document.getElementById('addCategoryModal'));
            if (modal) modal.hide();

            // Reset form
            document.getElementById('categoryName').value = '';
            document.getElementById('categorySlug').value = '';
            document.getElementById('categoryDescription').value = '';
        });
    }
});
</script>
@endsection
