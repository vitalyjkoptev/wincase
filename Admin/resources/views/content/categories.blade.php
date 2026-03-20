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
                        <th style="width: 120px;">Articles Count</th>
                        <th>Description</th>
                        <th style="width: 100px;">Status</th>
                        <th style="width: 80px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="text-muted">1</td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="avatar avatar-xs bg-primary-subtle text-primary rounded-2">
                                    <i class="ri-flight-takeoff-line fs-14"></i>
                                </div>
                                <span class="fw-semibold">Immigration</span>
                            </div>
                        </td>
                        <td><code class="fs-12">imigracja</code></td>
                        <td>
                            <span class="badge bg-primary-subtle text-primary">18 articles</span>
                        </td>
                        <td class="text-muted text-truncate" style="max-width: 250px;" title="Og&#243;lne informacje o procesie imigracji do Polski, wymagania, dokumenty, procedury">
                            Og&#243;lne informacje o procesie imigracji do Polski, wymagania, dokumenty, procedury
                        </td>
                        <td><span class="badge bg-success-subtle text-success">Active</span></td>
                        <td>
                            <div class="dropdown">
                                <button class="btn btn-sm btn-subtle-secondary" data-bs-toggle="dropdown"><i class="ri-more-2-fill"></i></button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#editCategoryModal"><i class="ri-edit-line me-2"></i>Edit</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item text-danger" href="#"><i class="ri-delete-bin-line me-2"></i>Delete</a></li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-muted">2</td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="avatar avatar-xs bg-warning-subtle text-warning rounded-2">
                                    <i class="ri-briefcase-line fs-14"></i>
                                </div>
                                <span class="fw-semibold">Work Permits</span>
                            </div>
                        </td>
                        <td><code class="fs-12">pozwolenie-na-prace</code></td>
                        <td>
                            <span class="badge bg-primary-subtle text-primary">12 articles</span>
                        </td>
                        <td class="text-muted text-truncate" style="max-width: 250px;" title="Pozwolenia na prac&#281;, o&#347;wiadczenia, zezwolenia na prac&#281; sezonow&#261; i typ A/B/C">
                            Pozwolenia na prac&#281;, o&#347;wiadczenia, zezwolenia na prac&#281; sezonow&#261; i typ A/B/C
                        </td>
                        <td><span class="badge bg-success-subtle text-success">Active</span></td>
                        <td>
                            <div class="dropdown">
                                <button class="btn btn-sm btn-subtle-secondary" data-bs-toggle="dropdown"><i class="ri-more-2-fill"></i></button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#editCategoryModal"><i class="ri-edit-line me-2"></i>Edit</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item text-danger" href="#"><i class="ri-delete-bin-line me-2"></i>Delete</a></li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-muted">3</td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="avatar avatar-xs bg-success-subtle text-success rounded-2">
                                    <i class="ri-home-4-line fs-14"></i>
                                </div>
                                <span class="fw-semibold">Residence</span>
                            </div>
                        </td>
                        <td><code class="fs-12">karta-pobytu</code></td>
                        <td>
                            <span class="badge bg-primary-subtle text-primary">10 articles</span>
                        </td>
                        <td class="text-muted text-truncate" style="max-width: 250px;" title="Karta pobytu, pobyt czasowy, sta&#322;y, rezydenta d&#322;ugoterminowego UE">
                            Karta pobytu, pobyt czasowy, sta&#322;y, rezydenta d&#322;ugoterminowego UE
                        </td>
                        <td><span class="badge bg-success-subtle text-success">Active</span></td>
                        <td>
                            <div class="dropdown">
                                <button class="btn btn-sm btn-subtle-secondary" data-bs-toggle="dropdown"><i class="ri-more-2-fill"></i></button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#editCategoryModal"><i class="ri-edit-line me-2"></i>Edit</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item text-danger" href="#"><i class="ri-delete-bin-line me-2"></i>Delete</a></li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-muted">4</td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="avatar avatar-xs bg-info-subtle text-info rounded-2">
                                    <i class="ri-money-euro-circle-line fs-14"></i>
                                </div>
                                <span class="fw-semibold">Tax & Accounting</span>
                            </div>
                        </td>
                        <td><code class="fs-12">podatki</code></td>
                        <td>
                            <span class="badge bg-primary-subtle text-primary">8 articles</span>
                        </td>
                        <td class="text-muted text-truncate" style="max-width: 250px;" title="Rozliczenia podatkowe, PIT, VAT, ZUS, ksi&#281;gowo&#347;&#263; dla cudzoziemc&#243;w">
                            Rozliczenia podatkowe, PIT, VAT, ZUS, ksi&#281;gowo&#347;&#263; dla cudzoziemc&#243;w
                        </td>
                        <td><span class="badge bg-success-subtle text-success">Active</span></td>
                        <td>
                            <div class="dropdown">
                                <button class="btn btn-sm btn-subtle-secondary" data-bs-toggle="dropdown"><i class="ri-more-2-fill"></i></button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#editCategoryModal"><i class="ri-edit-line me-2"></i>Edit</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item text-danger" href="#"><i class="ri-delete-bin-line me-2"></i>Delete</a></li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-muted">5</td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="avatar avatar-xs bg-danger-subtle text-danger rounded-2">
                                    <i class="ri-building-line fs-14"></i>
                                </div>
                                <span class="fw-semibold">Company Registration</span>
                            </div>
                        </td>
                        <td><code class="fs-12">rejestracja-firmy</code></td>
                        <td>
                            <span class="badge bg-primary-subtle text-primary">7 articles</span>
                        </td>
                        <td class="text-muted text-truncate" style="max-width: 250px;" title="Rejestracja dzia&#322;alno&#347;ci gospodarczej, sp. z o.o., KRS, CEIDG dla obcokrajowc&#243;w">
                            Rejestracja dzia&#322;alno&#347;ci gospodarczej, sp. z o.o., KRS, CEIDG dla obcokrajowc&#243;w
                        </td>
                        <td><span class="badge bg-success-subtle text-success">Active</span></td>
                        <td>
                            <div class="dropdown">
                                <button class="btn btn-sm btn-subtle-secondary" data-bs-toggle="dropdown"><i class="ri-more-2-fill"></i></button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#editCategoryModal"><i class="ri-edit-line me-2"></i>Edit</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item text-danger" href="#"><i class="ri-delete-bin-line me-2"></i>Delete</a></li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-muted">6</td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="avatar avatar-xs bg-dark-subtle text-dark rounded-2">
                                    <i class="ri-scales-3-line fs-14"></i>
                                </div>
                                <span class="fw-semibold">Legal Updates</span>
                            </div>
                        </td>
                        <td><code class="fs-12">prawo</code></td>
                        <td>
                            <span class="badge bg-primary-subtle text-primary">6 articles</span>
                        </td>
                        <td class="text-muted text-truncate" style="max-width: 250px;" title="Zmiany w prawie, nowe ustawy, rozporz&#261;dzenia dotycz&#261;ce cudzoziemc&#243;w w Polsce">
                            Zmiany w prawie, nowe ustawy, rozporz&#261;dzenia dotycz&#261;ce cudzoziemc&#243;w w Polsce
                        </td>
                        <td><span class="badge bg-success-subtle text-success">Active</span></td>
                        <td>
                            <div class="dropdown">
                                <button class="btn btn-sm btn-subtle-secondary" data-bs-toggle="dropdown"><i class="ri-more-2-fill"></i></button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#editCategoryModal"><i class="ri-edit-line me-2"></i>Edit</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item text-danger" href="#"><i class="ri-delete-bin-line me-2"></i>Delete</a></li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-muted">7</td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="avatar avatar-xs bg-secondary-subtle text-secondary rounded-2">
                                    <i class="ri-book-open-line fs-14"></i>
                                </div>
                                <span class="fw-semibold">Guides</span>
                            </div>
                        </td>
                        <td><code class="fs-12">poradniki</code></td>
                        <td>
                            <span class="badge bg-primary-subtle text-primary">6 articles</span>
                        </td>
                        <td class="text-muted text-truncate" style="max-width: 250px;" title="Praktyczne poradniki krok po kroku: jak za&#322;atwi&#263; dokumenty, gdzie z&#322;o&#380;y&#263; wniosek">
                            Praktyczne poradniki krok po kroku: jak za&#322;atwi&#263; dokumenty, gdzie z&#322;o&#380;y&#263; wniosek
                        </td>
                        <td><span class="badge bg-success-subtle text-success">Active</span></td>
                        <td>
                            <div class="dropdown">
                                <button class="btn btn-sm btn-subtle-secondary" data-bs-toggle="dropdown"><i class="ri-more-2-fill"></i></button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#editCategoryModal"><i class="ri-edit-line me-2"></i>Edit</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item text-danger" href="#"><i class="ri-delete-bin-line me-2"></i>Delete</a></li>
                                </ul>
                            </div>
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
    // Auto-generate slug from category name
    var nameInput = document.getElementById('categoryName');
    var slugInput = document.getElementById('categorySlug');

    nameInput?.addEventListener('input', function() {
        // Polish-friendly transliteration
        var map = {
            'ą': 'a', 'ć': 'c', 'ę': 'e', 'ł': 'l', 'ń': 'n',
            'ó': 'o', 'ś': 's', 'ź': 'z', 'ż': 'z',
            'Ą': 'a', 'Ć': 'c', 'Ę': 'e', 'Ł': 'l', 'Ń': 'n',
            'Ó': 'o', 'Ś': 's', 'Ź': 'z', 'Ż': 'z'
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
</script>
@endsection
