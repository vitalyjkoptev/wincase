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
                        <h4 class="mb-0 fw-semibold">67</h4>
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
                        <h4 class="mb-0 fw-semibold">45</h4>
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
                        <i class="ri-edit-2-line fs-18"></i>
                    </div>
                    <div>
                        <p class="text-muted mb-0 fs-13">Draft / Review</p>
                        <h4 class="mb-0 fw-semibold">15</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card card-h-100">
            <div class="card-body">
                <div class="d-flex align-items-center gap-3">
                    <div class="avatar avatar-sm bg-info-subtle text-info rounded-2">
                        <i class="ri-robot-line fs-18"></i>
                    </div>
                    <div>
                        <p class="text-muted mb-0 fs-13">AI Rewritten</p>
                        <h4 class="mb-0 fw-semibold">52</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Pipeline Progress -->
<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0"><i class="ri-flow-chart me-2"></i>Pipeline Progress</h5>
    </div>
    <div class="card-body">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <div class="text-center flex-fill">
                <div class="avatar avatar-sm bg-secondary-subtle text-secondary rounded-circle mx-auto mb-2">
                    <i class="ri-rss-line fs-16"></i>
                </div>
                <h6 class="fs-13 mb-0">Parse RSS</h6>
                <span class="text-muted fs-12">27 sources</span>
            </div>
            <i class="ri-arrow-right-line text-muted fs-20"></i>
            <div class="text-center flex-fill">
                <div class="avatar avatar-sm bg-info-subtle text-info rounded-circle mx-auto mb-2">
                    <i class="ri-robot-line fs-16"></i>
                </div>
                <h6 class="fs-13 mb-0">AI Rewrite</h6>
                <span class="text-muted fs-12">GPT-4o</span>
            </div>
            <i class="ri-arrow-right-line text-muted fs-20"></i>
            <div class="text-center flex-fill">
                <div class="avatar avatar-sm bg-primary-subtle text-primary rounded-circle mx-auto mb-2">
                    <i class="ri-translate-2 fs-16"></i>
                </div>
                <h6 class="fs-13 mb-0">Translate</h6>
                <span class="text-muted fs-12">PL/UA/RU/EN</span>
            </div>
            <i class="ri-arrow-right-line text-muted fs-20"></i>
            <div class="text-center flex-fill">
                <div class="avatar avatar-sm bg-warning-subtle text-warning rounded-circle mx-auto mb-2">
                    <i class="ri-eye-line fs-16"></i>
                </div>
                <h6 class="fs-13 mb-0">Review</h6>
                <span class="text-muted fs-12">Manual check</span>
            </div>
            <i class="ri-arrow-right-line text-muted fs-20"></i>
            <div class="text-center flex-fill">
                <div class="avatar avatar-sm bg-success-subtle text-success rounded-circle mx-auto mb-2">
                    <i class="ri-send-plane-line fs-16"></i>
                </div>
                <h6 class="fs-13 mb-0">Publish</h6>
                <span class="text-muted fs-12">Auto / Manual</span>
            </div>
        </div>
        <div class="progress" style="height: 8px;">
            <div class="progress-bar bg-secondary" role="progressbar" style="width: 20%" title="Parse RSS"></div>
            <div class="progress-bar bg-info" role="progressbar" style="width: 20%" title="AI Rewrite"></div>
            <div class="progress-bar bg-primary" role="progressbar" style="width: 20%" title="Translate"></div>
            <div class="progress-bar bg-warning" role="progressbar" style="width: 20%" title="Review"></div>
            <div class="progress-bar bg-success" role="progressbar" style="width: 20%" title="Publish"></div>
        </div>
        <div class="d-flex justify-content-between mt-2">
            <span class="fs-11 text-muted">Parse RSS</span>
            <span class="fs-11 text-muted">AI Rewrite</span>
            <span class="fs-11 text-muted">Translate</span>
            <span class="fs-11 text-muted">Review</span>
            <span class="fs-11 text-muted">Publish</span>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="card">
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-3">
                <input type="text" class="form-control" placeholder="Search articles...">
            </div>
            <div class="col-md-2">
                <select class="form-select">
                    <option selected>All Categories</option>
                    <option>Immigration</option>
                    <option>Work Permits</option>
                    <option>Residence</option>
                    <option>Tax & Accounting</option>
                    <option>Company Registration</option>
                    <option>Legal Updates</option>
                    <option>Guides</option>
                </select>
            </div>
            <div class="col-md-2">
                <select class="form-select">
                    <option selected>All Statuses</option>
                    <option>Published</option>
                    <option>Draft</option>
                    <option>Review</option>
                    <option>Scheduled</option>
                </select>
            </div>
            <div class="col-md-2">
                <select class="form-select">
                    <option selected>All Languages</option>
                    <option value="pl">Polish</option>
                    <option value="ua">Ukrainian</option>
                    <option value="ru">Russian</option>
                    <option value="en">English</option>
                </select>
            </div>
            <div class="col-md-2">
                <select class="form-select">
                    <option selected>All Authors</option>
                    <option>Jan Nowak</option>
                    <option>Anna Wi&#347;niewska</option>
                    <option>AI Writer</option>
                </select>
            </div>
            <div class="col-md-1">
                <button class="btn btn-primary w-100"><i class="ri-filter-3-line"></i></button>
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
                        <th>Author</th>
                        <th style="width: 80px;">Views</th>
                        <th>Published</th>
                        <th style="width: 60px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Row 1 -->
                    <tr>
                        <td><input class="form-check-input" type="checkbox"></td>
                        <td>
                            <a href="#" class="fw-semibold text-body text-truncate d-inline-block" style="max-width: 320px;" title="Jak uzyska&#263; kart&#281; pobytu w 2026? Kompletny przewodnik">
                                Jak uzyska&#263; kart&#281; pobytu w 2026? Kompletny przewodnik
                            </a>
                        </td>
                        <td><span class="badge bg-primary-subtle text-primary">Residence</span></td>
                        <td class="text-center fs-18" title="Polish">&#127477;&#127473;</td>
                        <td><span class="badge bg-success-subtle text-success">Published</span></td>
                        <td>Jan Nowak</td>
                        <td class="text-muted">2,341</td>
                        <td class="text-muted fs-12">Feb 20, 2026</td>
                        <td>
                            <div class="dropdown">
                                <button class="btn btn-sm btn-subtle-secondary" data-bs-toggle="dropdown"><i class="ri-more-2-fill"></i></button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="#"><i class="ri-eye-line me-2"></i>View</a></li>
                                    <li><a class="dropdown-item" href="#"><i class="ri-edit-line me-2"></i>Edit</a></li>
                                    <li><a class="dropdown-item" href="#"><i class="ri-translate-2 me-2"></i>Translate</a></li>
                                    <li><a class="dropdown-item" href="#"><i class="ri-file-copy-line me-2"></i>Duplicate</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item text-danger" href="#"><i class="ri-delete-bin-line me-2"></i>Delete</a></li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                    <!-- Row 2 -->
                    <tr>
                        <td><input class="form-check-input" type="checkbox"></td>
                        <td>
                            <a href="#" class="fw-semibold text-body text-truncate d-inline-block" style="max-width: 320px;" title="Nowe przepisy o pozwoleniu na prac&#281; dla cudzoziemc&#243;w">
                                Nowe przepisy o pozwoleniu na prac&#281; dla cudzoziemc&#243;w
                            </a>
                        </td>
                        <td><span class="badge bg-warning-subtle text-warning">Work Permits</span></td>
                        <td class="text-center fs-18" title="Polish">&#127477;&#127473;</td>
                        <td><span class="badge bg-success-subtle text-success">Published</span></td>
                        <td>AI Writer</td>
                        <td class="text-muted">1,876</td>
                        <td class="text-muted fs-12">Feb 18, 2026</td>
                        <td>
                            <div class="dropdown">
                                <button class="btn btn-sm btn-subtle-secondary" data-bs-toggle="dropdown"><i class="ri-more-2-fill"></i></button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="#"><i class="ri-eye-line me-2"></i>View</a></li>
                                    <li><a class="dropdown-item" href="#"><i class="ri-edit-line me-2"></i>Edit</a></li>
                                    <li><a class="dropdown-item" href="#"><i class="ri-translate-2 me-2"></i>Translate</a></li>
                                    <li><a class="dropdown-item" href="#"><i class="ri-file-copy-line me-2"></i>Duplicate</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item text-danger" href="#"><i class="ri-delete-bin-line me-2"></i>Delete</a></li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                    <!-- Row 3 -->
                    <tr>
                        <td><input class="form-check-input" type="checkbox"></td>
                        <td>
                            <a href="#" class="fw-semibold text-body text-truncate d-inline-block" style="max-width: 320px;" title="&#1071;&#1082; &#1086;&#1090;&#1088;&#1080;&#1084;&#1072;&#1090;&#1080; &#1082;&#1072;&#1088;&#1090;&#1091; &#1087;&#1086;&#1073;&#1080;&#1090;&#1091; &#1074; &#1055;&#1086;&#1083;&#1100;&#1097;&#1110;: &#1087;&#1086;&#1082;&#1088;&#1086;&#1082;&#1086;&#1074;&#1072; &#1110;&#1085;&#1089;&#1090;&#1088;&#1091;&#1082;&#1094;&#1110;&#1103;">
                                &#1071;&#1082; &#1086;&#1090;&#1088;&#1080;&#1084;&#1072;&#1090;&#1080; &#1082;&#1072;&#1088;&#1090;&#1091; &#1087;&#1086;&#1073;&#1080;&#1090;&#1091; &#1074; &#1055;&#1086;&#1083;&#1100;&#1097;&#1110;: &#1087;&#1086;&#1082;&#1088;&#1086;&#1082;&#1086;&#1074;&#1072; &#1110;&#1085;&#1089;&#1090;&#1088;&#1091;&#1082;&#1094;&#1110;&#1103;
                            </a>
                        </td>
                        <td><span class="badge bg-primary-subtle text-primary">Residence</span></td>
                        <td class="text-center fs-18" title="Ukrainian">&#127482;&#127462;</td>
                        <td><span class="badge bg-info-subtle text-info">Review</span></td>
                        <td>Anna Wi&#347;niewska</td>
                        <td class="text-muted">--</td>
                        <td class="text-muted fs-12">--</td>
                        <td>
                            <div class="dropdown">
                                <button class="btn btn-sm btn-subtle-secondary" data-bs-toggle="dropdown"><i class="ri-more-2-fill"></i></button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="#"><i class="ri-eye-line me-2"></i>View</a></li>
                                    <li><a class="dropdown-item" href="#"><i class="ri-edit-line me-2"></i>Edit</a></li>
                                    <li><a class="dropdown-item" href="#"><i class="ri-translate-2 me-2"></i>Translate</a></li>
                                    <li><a class="dropdown-item" href="#"><i class="ri-file-copy-line me-2"></i>Duplicate</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item text-danger" href="#"><i class="ri-delete-bin-line me-2"></i>Delete</a></li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                    <!-- Row 4 -->
                    <tr>
                        <td><input class="form-check-input" type="checkbox"></td>
                        <td>
                            <a href="#" class="fw-semibold text-body text-truncate d-inline-block" style="max-width: 320px;" title="Rejestracja firmy w Polsce przez cudzoziemc&#243;w &mdash; co musisz wiedzie&#263;">
                                Rejestracja firmy w Polsce przez cudzoziemc&#243;w &mdash; co musisz wiedzie&#263;
                            </a>
                        </td>
                        <td><span class="badge bg-danger-subtle text-danger">Company Reg.</span></td>
                        <td class="text-center fs-18" title="Polish">&#127477;&#127473;</td>
                        <td><span class="badge bg-success-subtle text-success">Published</span></td>
                        <td>Jan Nowak</td>
                        <td class="text-muted">1,523</td>
                        <td class="text-muted fs-12">Feb 15, 2026</td>
                        <td>
                            <div class="dropdown">
                                <button class="btn btn-sm btn-subtle-secondary" data-bs-toggle="dropdown"><i class="ri-more-2-fill"></i></button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="#"><i class="ri-eye-line me-2"></i>View</a></li>
                                    <li><a class="dropdown-item" href="#"><i class="ri-edit-line me-2"></i>Edit</a></li>
                                    <li><a class="dropdown-item" href="#"><i class="ri-translate-2 me-2"></i>Translate</a></li>
                                    <li><a class="dropdown-item" href="#"><i class="ri-file-copy-line me-2"></i>Duplicate</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item text-danger" href="#"><i class="ri-delete-bin-line me-2"></i>Delete</a></li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                    <!-- Row 5 -->
                    <tr>
                        <td><input class="form-check-input" type="checkbox"></td>
                        <td>
                            <a href="#" class="fw-semibold text-body text-truncate d-inline-block" style="max-width: 320px;" title="&#1056;&#1072;&#1079;&#1088;&#1077;&#1096;&#1077;&#1085;&#1080;&#1077; &#1085;&#1072; &#1088;&#1072;&#1073;&#1086;&#1090;&#1091; &#1074; &#1055;&#1086;&#1083;&#1100;&#1096;&#1077;: &#1080;&#1079;&#1084;&#1077;&#1085;&#1077;&#1085;&#1080;&#1103; 2026 &#1075;&#1086;&#1076;&#1072;">
                                &#1056;&#1072;&#1079;&#1088;&#1077;&#1096;&#1077;&#1085;&#1080;&#1077; &#1085;&#1072; &#1088;&#1072;&#1073;&#1086;&#1090;&#1091; &#1074; &#1055;&#1086;&#1083;&#1100;&#1096;&#1077;: &#1080;&#1079;&#1084;&#1077;&#1085;&#1077;&#1085;&#1080;&#1103; 2026 &#1075;&#1086;&#1076;&#1072;
                            </a>
                        </td>
                        <td><span class="badge bg-warning-subtle text-warning">Work Permits</span></td>
                        <td class="text-center fs-18" title="Russian">&#127479;&#127482;</td>
                        <td><span class="badge bg-secondary-subtle text-secondary">Draft</span></td>
                        <td>AI Writer</td>
                        <td class="text-muted">--</td>
                        <td class="text-muted fs-12">--</td>
                        <td>
                            <div class="dropdown">
                                <button class="btn btn-sm btn-subtle-secondary" data-bs-toggle="dropdown"><i class="ri-more-2-fill"></i></button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="#"><i class="ri-eye-line me-2"></i>View</a></li>
                                    <li><a class="dropdown-item" href="#"><i class="ri-edit-line me-2"></i>Edit</a></li>
                                    <li><a class="dropdown-item" href="#"><i class="ri-translate-2 me-2"></i>Translate</a></li>
                                    <li><a class="dropdown-item" href="#"><i class="ri-file-copy-line me-2"></i>Duplicate</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item text-danger" href="#"><i class="ri-delete-bin-line me-2"></i>Delete</a></li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                    <!-- Row 6 -->
                    <tr>
                        <td><input class="form-check-input" type="checkbox"></td>
                        <td>
                            <a href="#" class="fw-semibold text-body text-truncate d-inline-block" style="max-width: 320px;" title="Zmiany w ustawie o cudzoziemcach &mdash; podsumowanie nowo&#347;ci prawnych">
                                Zmiany w ustawie o cudzoziemcach &mdash; podsumowanie nowo&#347;ci prawnych
                            </a>
                        </td>
                        <td><span class="badge bg-dark-subtle text-dark">Legal Updates</span></td>
                        <td class="text-center fs-18" title="English">&#127468;&#127463;</td>
                        <td><span class="badge bg-purple-subtle text-purple">Scheduled</span></td>
                        <td>Anna Wi&#347;niewska</td>
                        <td class="text-muted">--</td>
                        <td class="text-muted fs-12">Mar 01, 2026</td>
                        <td>
                            <div class="dropdown">
                                <button class="btn btn-sm btn-subtle-secondary" data-bs-toggle="dropdown"><i class="ri-more-2-fill"></i></button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="#"><i class="ri-eye-line me-2"></i>View</a></li>
                                    <li><a class="dropdown-item" href="#"><i class="ri-edit-line me-2"></i>Edit</a></li>
                                    <li><a class="dropdown-item" href="#"><i class="ri-translate-2 me-2"></i>Translate</a></li>
                                    <li><a class="dropdown-item" href="#"><i class="ri-file-copy-line me-2"></i>Duplicate</a></li>
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
    <div class="card-footer">
        <div class="d-flex align-items-center justify-content-between">
            <div class="text-muted fs-13">Showing 1-6 of 67</div>
            <nav>
                <ul class="pagination pagination-sm mb-0">
                    <li class="page-item disabled"><a class="page-link" href="#">Previous</a></li>
                    <li class="page-item active"><a class="page-link" href="#">1</a></li>
                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                    <li class="page-item"><a class="page-link" href="#">...</a></li>
                    <li class="page-item"><a class="page-link" href="#">12</a></li>
                    <li class="page-item"><a class="page-link" href="#">Next</a></li>
                </ul>
            </nav>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
    // Check All checkbox
    document.getElementById('checkAll')?.addEventListener('change', function() {
        var checkboxes = document.querySelectorAll('tbody .form-check-input');
        checkboxes.forEach(function(cb) { cb.checked = this.checked; }.bind(this));
    });
</script>
@endsection
