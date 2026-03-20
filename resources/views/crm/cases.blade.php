@extends('partials.layouts.master')

@section('title', 'Cases | WinCase CRM')
@section('sub-title', 'Cases')
@section('sub-title-lang', 'wc-cases')
@section('pagetitle', 'CRM')
@section('pagetitle-lang', 'wc-title-crm')
@section('buttonTitle', 'New Case')
@section('buttonTitle-lang', 'wc-new-case')
@section('modalTarget', 'addCaseModal')

@section('content')
<div class="row">
    <!-- Stats Cards -->
    <div class="col-xl-3 col-md-6">
        <div class="card card-h-100">
            <div class="card-body">
                <div class="d-flex align-items-center gap-3">
                    <div class="avatar avatar-sm bg-primary-subtle text-primary rounded-2">
                        <i class="ri-briefcase-line fs-18"></i>
                    </div>
                    <div>
                        <p class="text-muted mb-0 fs-13">Total Cases</p>
                        <h4 class="mb-0 fw-semibold">234</h4>
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
                        <i class="ri-loader-4-line fs-18"></i>
                    </div>
                    <div>
                        <p class="text-muted mb-0 fs-13">In Progress</p>
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
                        <i class="ri-quill-pen-line fs-18"></i>
                    </div>
                    <div>
                        <p class="text-muted mb-0 fs-13">Decision Signed</p>
                        <h4 class="mb-0 fw-semibold">38</h4>
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
                        <i class="ri-bank-card-line fs-18"></i>
                    </div>
                    <div>
                        <p class="text-muted mb-0 fs-13">Card Collected / Completed</p>
                        <h4 class="mb-0 fw-semibold">145</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Case Type Block -->
<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0"><i class="ri-folder-line me-2"></i>Case Type</h5>
    </div>
    <div class="card-body">
        <div class="row g-2" id="caseTypeFilter">
            <div class="col"><button class="btn btn-primary btn-sm w-100 active" data-type="all">All Types <span class="badge bg-white text-primary ms-1">5</span></button></div>
            <div class="col"><button class="btn btn-outline-primary btn-sm w-100" data-type="Temporary Residence Card">Temp. Residence <span class="badge bg-primary-subtle text-primary ms-1">1</span></button></div>
            <div class="col"><button class="btn btn-outline-primary btn-sm w-100" data-type="Permanent Residence">Perm. Residence <span class="badge bg-primary-subtle text-primary ms-1">1</span></button></div>
            <div class="col"><button class="btn btn-outline-primary btn-sm w-100" data-type="Long-term Resident">Long-term Resident <span class="badge bg-primary-subtle text-primary ms-1">0</span></button></div>
            <div class="col"><button class="btn btn-outline-primary btn-sm w-100" data-type="Citizenship">Citizenship <span class="badge bg-primary-subtle text-primary ms-1">1</span></button></div>
            <div class="col"><button class="btn btn-outline-primary btn-sm w-100" data-type="Speedup">Speedup <span class="badge bg-primary-subtle text-primary ms-1">1</span></button></div>
            <div class="col"><button class="btn btn-outline-primary btn-sm w-100" data-type="Appeal">Appeal <span class="badge bg-primary-subtle text-primary ms-1">1</span></button></div>
            <div class="col"><button class="btn btn-outline-primary btn-sm w-100" data-type="Fingerprint Return">Fingerprint Return <span class="badge bg-primary-subtle text-primary ms-1">0</span></button></div>
            <div class="col"><button class="btn btn-outline-primary btn-sm w-100" data-type="Court Certificate">Court Certificate <span class="badge bg-primary-subtle text-primary ms-1">0</span></button></div>
            <div class="col"><button class="btn btn-outline-primary btn-sm w-100" data-type="Deportation Cancellation">Deport. Cancel <span class="badge bg-primary-subtle text-primary ms-1">0</span></button></div>
            <div class="col"><button class="btn btn-outline-primary btn-sm w-100" data-type="Other">Other <span class="badge bg-primary-subtle text-primary ms-1">0</span></button></div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="card">
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-3">
                <input type="text" class="form-control" placeholder="Search cases...">
            </div>
            <div class="col-md-2">
                <select class="form-select">
                    <option selected>All Statuses</option>
                    <option>Submitted to Office</option>
                    <option>Awaiting Fingerprints</option>
                    <option>Fingerprint Appointment</option>
                    <option>Fingerprints Submitted</option>
                    <option>Awaiting Decision</option>
                    <option>Decision Signed</option>
                    <option>Card Issued</option>
                </select>
            </div>
            <div class="col-md-2">
                <select class="form-select">
                    <option selected>All Managers</option>
                    <option>Jan Nowak</option>
                    <option>Anna Wiśniewska</option>
                    <option>Piotr Kowalczyk</option>
                </select>
            </div>
            <div class="col-md-2">
                <select class="form-select">
                    <option selected>Submission City</option>
                    <option>Warszawa</option>
                    <option>Kraków</option>
                    <option>Wrocław</option>
                    <option>Gdańsk</option>
                    <option>Poznań</option>
                    <option>Łódź</option>
                    <option>Lublin</option>
                    <option>Katowice</option>
                </select>
            </div>
            <div class="col-md-2">
                <select class="form-select">
                    <option selected>Sort</option>
                    <option>Newest First</option>
                    <option>Oldest First</option>
                    <option>By Status</option>
                </select>
            </div>
            <div class="col-md-1">
                <button class="btn btn-primary w-100"><i class="ri-filter-3-line"></i></button>
            </div>
        </div>
    </div>
</div>

<!-- Cases Table -->
<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" id="casesTable">
                <thead class="table-light">
                    <tr>
                        <th><input class="form-check-input" type="checkbox"></th>
                        <th>Case #</th>
                        <th>Client</th>
                        <th>Case Type</th>
                        <th>Status</th>
                        <th>City</th>
                        <th>Manager</th>
                        <th>Sold By</th>
                        <th>Submission Date</th>
                        <th>Progress</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Row 1: Oleksandr Petrov -->
                    <tr data-case-id="147"
                        data-number="WC-2026-0147"
                        data-client="Oleksandr Petrov"
                        data-type="Temporary Residence Card"
                        data-status="Fingerprint Appointment"
                        data-city="Warszawa"
                        data-manager="Jan Nowak"
                        data-sold-by="Anna W."
                        data-submitted="2026-01-20"
                        data-fingerprint-date="2026-03-15"
                        data-decision-date=""
                        data-card-date=""
                        data-contract="4800"
                        data-paid="4800"
                        data-progress="3">
                        <td><input class="form-check-input" type="checkbox"></td>
                        <td><a href="#" class="fw-semibold text-primary case-view-link">WC-2026-0147</a></td>
                        <td><a href="#" class="fw-semibold text-dark case-view-link">Oleksandr Petrov</a></td>
                        <td><span class="badge bg-primary-subtle text-primary">Temporary Residence Card</span></td>
                        <td><span class="badge bg-warning-subtle text-warning">Fingerprint Appointment</span></td>
                        <td>Warszawa</td>
                        <td>Jan Nowak</td>
                        <td><span class="text-muted fs-12">Anna W.</span></td>
                        <td class="text-muted fs-12">Jan 20, 2026</td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="progress flex-grow-1" style="height: 6px; width: 80px;">
                                    <div class="progress-bar bg-warning" style="width: 43%"></div>
                                </div>
                                <span class="text-muted fs-12">3/7</span>
                            </div>
                        </td>
                        <td>
                            <div class="dropdown">
                                <button class="btn btn-sm btn-subtle-secondary" data-bs-toggle="dropdown"><i class="ri-more-2-fill"></i></button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item action-view" href="#"><i class="ri-eye-line me-2"></i>View</a></li>
                                    <li><a class="dropdown-item action-edit" href="#"><i class="ri-edit-line me-2"></i>Edit</a></li>
                                    <li><a class="dropdown-item action-docs" href="#"><i class="ri-file-text-line me-2"></i>Documents</a></li>
                                    <li><a class="dropdown-item action-timeline" href="#"><i class="ri-time-line me-2"></i>Timeline</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item text-success action-advance" href="#"><i class="ri-check-line me-2"></i>Advance Status</a></li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                    <!-- Row 2: Maria Ivanova -->
                    <tr data-case-id="146"
                        data-number="WC-2026-0146"
                        data-client="Maria Ivanova"
                        data-type="Permanent Residence"
                        data-status="Submitted to Office"
                        data-city="Kraków"
                        data-manager="Anna Wiśniewska"
                        data-sold-by="Jan N."
                        data-submitted="2026-02-14"
                        data-fingerprint-date=""
                        data-decision-date=""
                        data-card-date=""
                        data-contract="5500"
                        data-paid="2000"
                        data-progress="1">
                        <td><input class="form-check-input" type="checkbox"></td>
                        <td><a href="#" class="fw-semibold text-primary case-view-link">WC-2026-0146</a></td>
                        <td><a href="#" class="fw-semibold text-dark case-view-link">Maria Ivanova</a></td>
                        <td><span class="badge bg-info-subtle text-info">Permanent Residence</span></td>
                        <td><span class="badge bg-primary-subtle text-primary">Submitted to Office</span></td>
                        <td>Kraków</td>
                        <td>Anna Wiśniewska</td>
                        <td><span class="text-muted fs-12">Jan N.</span></td>
                        <td class="text-muted fs-12">Feb 14, 2026</td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="progress flex-grow-1" style="height: 6px; width: 80px;">
                                    <div class="progress-bar bg-info" style="width: 14%"></div>
                                </div>
                                <span class="text-muted fs-12">1/7</span>
                            </div>
                        </td>
                        <td>
                            <div class="dropdown">
                                <button class="btn btn-sm btn-subtle-secondary" data-bs-toggle="dropdown"><i class="ri-more-2-fill"></i></button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item action-view" href="#"><i class="ri-eye-line me-2"></i>View</a></li>
                                    <li><a class="dropdown-item action-edit" href="#"><i class="ri-edit-line me-2"></i>Edit</a></li>
                                    <li><a class="dropdown-item action-docs" href="#"><i class="ri-file-text-line me-2"></i>Documents</a></li>
                                    <li><a class="dropdown-item action-timeline" href="#"><i class="ri-time-line me-2"></i>Timeline</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item text-success action-advance" href="#"><i class="ri-check-line me-2"></i>Advance Status</a></li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                    <!-- Row 3: Aliaksandr Kazlou -->
                    <tr data-case-id="139"
                        data-number="WC-2026-0139"
                        data-client="Aliaksandr Kazlou"
                        data-type="Citizenship"
                        data-status="Fingerprints Submitted"
                        data-city="Wrocław"
                        data-manager="Piotr Kowalczyk"
                        data-sold-by="Piotr K."
                        data-submitted="2025-12-05"
                        data-fingerprint-date="2026-01-20"
                        data-decision-date=""
                        data-card-date=""
                        data-contract="7000"
                        data-paid="3300"
                        data-progress="4">
                        <td><input class="form-check-input" type="checkbox"></td>
                        <td><a href="#" class="fw-semibold text-primary case-view-link">WC-2026-0139</a></td>
                        <td><a href="#" class="fw-semibold text-dark case-view-link">Aliaksandr Kazlou</a></td>
                        <td><span class="badge bg-success-subtle text-success">Citizenship</span></td>
                        <td><span class="badge bg-info-subtle text-info">Fingerprints Submitted</span></td>
                        <td>Wrocław</td>
                        <td>Piotr Kowalczyk</td>
                        <td><span class="text-muted fs-12">Piotr K.</span></td>
                        <td class="text-muted fs-12">Dec 5, 2025</td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="progress flex-grow-1" style="height: 6px; width: 80px;">
                                    <div class="progress-bar bg-primary" style="width: 57%"></div>
                                </div>
                                <span class="text-muted fs-12">4/7</span>
                            </div>
                        </td>
                        <td>
                            <div class="dropdown">
                                <button class="btn btn-sm btn-subtle-secondary" data-bs-toggle="dropdown"><i class="ri-more-2-fill"></i></button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item action-view" href="#"><i class="ri-eye-line me-2"></i>View</a></li>
                                    <li><a class="dropdown-item action-edit" href="#"><i class="ri-edit-line me-2"></i>Edit</a></li>
                                    <li><a class="dropdown-item action-docs" href="#"><i class="ri-file-text-line me-2"></i>Documents</a></li>
                                    <li><a class="dropdown-item action-timeline" href="#"><i class="ri-time-line me-2"></i>Timeline</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item text-success action-advance" href="#"><i class="ri-check-line me-2"></i>Advance Status</a></li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                    <!-- Row 4: Giorgi Tsiklauri -->
                    <tr data-case-id="98"
                        data-number="WC-2025-0098"
                        data-client="Giorgi Tsiklauri"
                        data-type="Speedup"
                        data-status="Decision Signed"
                        data-city="Warszawa"
                        data-manager="Jan Nowak"
                        data-sold-by="Anna W."
                        data-submitted="2025-09-10"
                        data-fingerprint-date="2025-10-20"
                        data-decision-date="2026-02-28"
                        data-card-date=""
                        data-contract="3000"
                        data-paid="3000"
                        data-progress="6">
                        <td><input class="form-check-input" type="checkbox"></td>
                        <td><a href="#" class="fw-semibold text-primary case-view-link">WC-2025-0098</a></td>
                        <td><a href="#" class="fw-semibold text-dark case-view-link">Giorgi Tsiklauri</a></td>
                        <td><span class="badge bg-warning-subtle text-warning">Speedup</span></td>
                        <td><span class="badge bg-success-subtle text-success">Decision Signed</span></td>
                        <td>Warszawa</td>
                        <td>Jan Nowak</td>
                        <td><span class="text-muted fs-12">Anna W.</span></td>
                        <td class="text-muted fs-12">Sep 10, 2025</td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="progress flex-grow-1" style="height: 6px; width: 80px;">
                                    <div class="progress-bar bg-success" style="width: 86%"></div>
                                </div>
                                <span class="text-muted fs-12">6/7</span>
                            </div>
                        </td>
                        <td>
                            <div class="dropdown">
                                <button class="btn btn-sm btn-subtle-secondary" data-bs-toggle="dropdown"><i class="ri-more-2-fill"></i></button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item action-view" href="#"><i class="ri-eye-line me-2"></i>View</a></li>
                                    <li><a class="dropdown-item action-edit" href="#"><i class="ri-edit-line me-2"></i>Edit</a></li>
                                    <li><a class="dropdown-item action-docs" href="#"><i class="ri-file-text-line me-2"></i>Documents</a></li>
                                    <li><a class="dropdown-item action-timeline" href="#"><i class="ri-time-line me-2"></i>Timeline</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item text-success action-advance" href="#"><i class="ri-check-line me-2"></i>Mark Card Issued</a></li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                    <!-- Row 5: Tetiana Sydorenko -->
                    <tr data-case-id="152"
                        data-number="WC-2026-0152"
                        data-client="Tetiana Sydorenko"
                        data-type="Appeal"
                        data-status="Awaiting Decision"
                        data-city="Gdańsk"
                        data-manager="Anna Wiśniewska"
                        data-sold-by="Jan N."
                        data-submitted="2026-02-20"
                        data-fingerprint-date="2026-03-05"
                        data-decision-date=""
                        data-card-date=""
                        data-contract="4000"
                        data-paid="1500"
                        data-progress="5">
                        <td><input class="form-check-input" type="checkbox"></td>
                        <td><a href="#" class="fw-semibold text-primary case-view-link">WC-2026-0152</a></td>
                        <td><a href="#" class="fw-semibold text-dark case-view-link">Tetiana Sydorenko</a></td>
                        <td><span class="badge bg-danger-subtle text-danger">Appeal</span></td>
                        <td><span class="badge bg-secondary-subtle text-secondary">Awaiting Decision</span></td>
                        <td>Gdańsk</td>
                        <td>Anna Wiśniewska</td>
                        <td><span class="text-muted fs-12">Jan N.</span></td>
                        <td class="text-muted fs-12">Feb 20, 2026</td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="progress flex-grow-1" style="height: 6px; width: 80px;">
                                    <div class="progress-bar bg-warning" style="width: 71%"></div>
                                </div>
                                <span class="text-muted fs-12">5/7</span>
                            </div>
                        </td>
                        <td>
                            <div class="dropdown">
                                <button class="btn btn-sm btn-subtle-secondary" data-bs-toggle="dropdown"><i class="ri-more-2-fill"></i></button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item action-view" href="#"><i class="ri-eye-line me-2"></i>View</a></li>
                                    <li><a class="dropdown-item action-edit" href="#"><i class="ri-edit-line me-2"></i>Edit</a></li>
                                    <li><a class="dropdown-item action-docs" href="#"><i class="ri-file-text-line me-2"></i>Documents</a></li>
                                    <li><a class="dropdown-item action-timeline" href="#"><i class="ri-time-line me-2"></i>Timeline</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item text-success action-advance" href="#"><i class="ri-check-line me-2"></i>Advance Status</a></li>
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
            <div class="text-muted fs-13">Showing 1-5 of 234 cases</div>
            <nav>
                <ul class="pagination pagination-sm mb-0">
                    <li class="page-item disabled"><a class="page-link" href="#">Previous</a></li>
                    <li class="page-item active"><a class="page-link" href="#">1</a></li>
                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                    <li class="page-item"><a class="page-link" href="#">...</a></li>
                    <li class="page-item"><a class="page-link" href="#">47</a></li>
                    <li class="page-item"><a class="page-link" href="#">Next</a></li>
                </ul>
            </nav>
        </div>
    </div>
</div>

<!-- New Case Modal -->
<div class="modal fade" id="addCaseModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">New Case</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Client <span class="text-danger">*</span></label>
                        <select class="form-select">
                            <option selected disabled>Select client...</option>
                            <option>Oleksandr Petrov</option>
                            <option>Maria Ivanova</option>
                            <option>Aliaksandr Kazlou</option>
                            <option>Giorgi Tsiklauri</option>
                            <option>Tetiana Sydorenko</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Case Type <span class="text-danger">*</span></label>
                        <select class="form-select">
                            <option selected disabled>Select case type...</option>
                            <option>Temporary Residence Card</option>
                            <option>Permanent Residence</option>
                            <option>Long-term Resident</option>
                            <option>Citizenship</option>
                            <option>Speedup</option>
                            <option>Appeal</option>
                            <option>Fingerprint Return</option>
                            <option>Court Certificate</option>
                            <option>Deportation Cancellation</option>
                            <option>Other</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Status <span class="text-danger">*</span></label>
                        <select class="form-select">
                            <option selected>Submitted to Office</option>
                            <option>Awaiting Fingerprints</option>
                            <option>Fingerprint Appointment</option>
                            <option>Fingerprints Submitted</option>
                            <option>Awaiting Decision</option>
                            <option>Decision Signed</option>
                            <option>Card Issued</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Submission City <span class="text-danger">*</span></label>
                        <select class="form-select">
                            <option selected disabled>Select city...</option>
                            <option>Warszawa</option>
                            <option>Kraków</option>
                            <option>Wrocław</option>
                            <option>Gdańsk</option>
                            <option>Poznań</option>
                            <option>Łódź</option>
                            <option>Lublin</option>
                            <option>Katowice</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Case Number</label>
                        <input type="text" class="form-control" placeholder="Office case number">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Submission Date <span class="text-danger">*</span></label>
                        <input type="date" class="form-control">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Fingerprint Date</label>
                        <input type="date" class="form-control">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Decision Date</label>
                        <input type="date" class="form-control">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Card Issue Date</label>
                        <input type="date" class="form-control">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Assigned Manager <span class="text-danger">*</span></label>
                        <select class="form-select">
                            <option selected disabled>Select manager...</option>
                            <option>Jan Nowak</option>
                            <option>Anna Wiśniewska</option>
                            <option>Piotr Kowalczyk</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Sold By <span class="text-danger">*</span></label>
                        <select class="form-select">
                            <option selected disabled>Who made the sale?</option>
                            <option>Jan Nowak</option>
                            <option>Anna Wiśniewska</option>
                            <option>Piotr Kowalczyk</option>
                        </select>
                    </div>

                    <!-- Finance Section -->
                    <div class="col-12">
                        <hr>
                        <h6 class="text-muted text-uppercase fs-11 mb-3">Finance</h6>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Contract Amount (PLN) <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text">PLN</span>
                            <input type="number" class="form-control" placeholder="0.00" step="0.01">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Payment Status</label>
                        <select class="form-select">
                            <option>Unpaid</option>
                            <option>Partially Paid</option>
                            <option>Fully Paid</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Amount Paid (PLN)</label>
                        <div class="input-group">
                            <span class="input-group-text">PLN</span>
                            <input type="number" class="form-control" placeholder="0.00" step="0.01">
                        </div>
                    </div>

                    <div class="col-12">
                        <label class="form-label">Description</label>
                        <textarea class="form-control" rows="3" placeholder="Case description, special requirements, notes..."></textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary">Create Case</button>
            </div>
        </div>
    </div>
</div>

<!-- View Case Modal -->
<div class="modal fade" id="viewCaseModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-primary-subtle">
                <h5 class="modal-title"><i class="ri-briefcase-line me-2"></i>Case <span id="viewCaseNumber" class="fw-bold"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <!-- Left Column: Case Info -->
                    <div class="col-lg-7">
                        <div class="card border mb-3">
                            <div class="card-header bg-light">
                                <h6 class="card-title mb-0"><i class="ri-information-line me-2"></i>Case Information</h6>
                            </div>
                            <div class="card-body p-0">
                                <table class="table table-sm table-borderless mb-0">
                                    <tbody>
                                        <tr>
                                            <td class="text-muted fw-medium" style="width:160px;">Case #</td>
                                            <td id="viewInfoNumber" class="fw-semibold"></td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted fw-medium">Client</td>
                                            <td id="viewInfoClient"></td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted fw-medium">Case Type</td>
                                            <td id="viewInfoType"></td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted fw-medium">Status</td>
                                            <td id="viewInfoStatus"></td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted fw-medium">City</td>
                                            <td id="viewInfoCity"></td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted fw-medium">Manager</td>
                                            <td id="viewInfoManager"></td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted fw-medium">Sold By</td>
                                            <td id="viewInfoSoldBy"></td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted fw-medium">Submission Date</td>
                                            <td id="viewInfoSubmitted"></td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted fw-medium">Fingerprint Date</td>
                                            <td id="viewInfoFingerprint"></td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted fw-medium">Decision Date</td>
                                            <td id="viewInfoDecision"></td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted fw-medium">Card Issue Date</td>
                                            <td id="viewInfoCardDate"></td>
                                        </tr>
                                        <tr><td colspan="2"><hr class="my-1"></td></tr>
                                        <tr>
                                            <td class="text-muted fw-medium">Contract Amount</td>
                                            <td id="viewInfoContract" class="fw-semibold"></td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted fw-medium">Amount Paid</td>
                                            <td id="viewInfoPaid" class="text-success fw-semibold"></td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted fw-medium">Debt</td>
                                            <td id="viewInfoDebt" class="text-danger fw-semibold"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <!-- Right Column: Status Progress, Documents, Quick Actions -->
                    <div class="col-lg-5">
                        <!-- Status Progress -->
                        <div class="card border mb-3">
                            <div class="card-header bg-light">
                                <h6 class="card-title mb-0"><i class="ri-route-line me-2"></i>Status Progress</h6>
                            </div>
                            <div class="card-body" id="viewStatusProgress">
                                <!-- Filled by JS -->
                            </div>
                        </div>
                        <!-- Documents List -->
                        <div class="card border mb-3">
                            <div class="card-header bg-light d-flex align-items-center justify-content-between">
                                <h6 class="card-title mb-0"><i class="ri-file-text-line me-2"></i>Documents</h6>
                                <span class="badge bg-primary rounded-pill" id="viewDocsCount">0</span>
                            </div>
                            <div class="card-body p-0" id="viewDocsList">
                                <div class="list-group list-group-flush">
                                    <div class="list-group-item d-flex align-items-center gap-2 py-2">
                                        <i class="ri-file-pdf-line text-danger fs-18"></i>
                                        <div class="flex-grow-1">
                                            <span class="fs-13 fw-medium">Passport Copy.pdf</span>
                                            <br><span class="text-muted fs-11">Uploaded Jan 20, 2026</span>
                                        </div>
                                        <button class="btn btn-sm btn-subtle-primary"><i class="ri-download-line"></i></button>
                                    </div>
                                    <div class="list-group-item d-flex align-items-center gap-2 py-2">
                                        <i class="ri-file-pdf-line text-danger fs-18"></i>
                                        <div class="flex-grow-1">
                                            <span class="fs-13 fw-medium">Application Form.pdf</span>
                                            <br><span class="text-muted fs-11">Uploaded Jan 20, 2026</span>
                                        </div>
                                        <button class="btn btn-sm btn-subtle-primary"><i class="ri-download-line"></i></button>
                                    </div>
                                    <div class="list-group-item d-flex align-items-center gap-2 py-2">
                                        <i class="ri-file-image-line text-info fs-18"></i>
                                        <div class="flex-grow-1">
                                            <span class="fs-13 fw-medium">Photo 3.5x4.5.jpg</span>
                                            <br><span class="text-muted fs-11">Uploaded Jan 21, 2026</span>
                                        </div>
                                        <button class="btn btn-sm btn-subtle-primary"><i class="ri-download-line"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Quick Actions -->
                        <div class="card border mb-0">
                            <div class="card-header bg-light">
                                <h6 class="card-title mb-0"><i class="ri-flashlight-line me-2"></i>Quick Actions</h6>
                            </div>
                            <div class="card-body">
                                <div class="d-flex flex-wrap gap-2">
                                    <button class="btn btn-sm btn-subtle-primary" id="viewToEditBtn"><i class="ri-edit-line me-1"></i>Edit Case</button>
                                    <button class="btn btn-sm btn-subtle-success" id="viewToAdvanceBtn"><i class="ri-check-line me-1"></i>Advance Status</button>
                                    <button class="btn btn-sm btn-subtle-info"><i class="ri-upload-2-line me-1"></i>Upload Document</button>
                                    <button class="btn btn-sm btn-subtle-warning" id="viewToTimelineBtn"><i class="ri-time-line me-1"></i>Timeline</button>
                                    <button class="btn btn-sm btn-subtle-danger"><i class="ri-printer-line me-1"></i>Print</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Edit Case Modal -->
<div class="modal fade" id="editCaseModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-warning-subtle">
                <h5 class="modal-title"><i class="ri-edit-line me-2"></i>Edit Case <span id="editCaseTitle" class="fw-bold"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="editCaseId">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Client <span class="text-danger">*</span></label>
                        <select class="form-select" id="editClient">
                            <option disabled>Select client...</option>
                            <option>Oleksandr Petrov</option>
                            <option>Maria Ivanova</option>
                            <option>Aliaksandr Kazlou</option>
                            <option>Giorgi Tsiklauri</option>
                            <option>Tetiana Sydorenko</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Case Type <span class="text-danger">*</span></label>
                        <select class="form-select" id="editCaseType">
                            <option disabled>Select case type...</option>
                            <option>Temporary Residence Card</option>
                            <option>Permanent Residence</option>
                            <option>Long-term Resident</option>
                            <option>Citizenship</option>
                            <option>Speedup</option>
                            <option>Appeal</option>
                            <option>Fingerprint Return</option>
                            <option>Court Certificate</option>
                            <option>Deportation Cancellation</option>
                            <option>Other</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Status <span class="text-danger">*</span></label>
                        <select class="form-select" id="editStatus">
                            <option>Submitted to Office</option>
                            <option>Awaiting Fingerprints</option>
                            <option>Fingerprint Appointment</option>
                            <option>Fingerprints Submitted</option>
                            <option>Awaiting Decision</option>
                            <option>Decision Signed</option>
                            <option>Card Issued</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Submission City <span class="text-danger">*</span></label>
                        <select class="form-select" id="editCity">
                            <option disabled>Select city...</option>
                            <option>Warszawa</option>
                            <option>Kraków</option>
                            <option>Wrocław</option>
                            <option>Gdańsk</option>
                            <option>Poznań</option>
                            <option>Łódź</option>
                            <option>Lublin</option>
                            <option>Katowice</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Case Number</label>
                        <input type="text" class="form-control" id="editCaseNumber" placeholder="Office case number">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Submission Date <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" id="editSubmitted">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Fingerprint Date</label>
                        <input type="date" class="form-control" id="editFingerprint">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Decision Date</label>
                        <input type="date" class="form-control" id="editDecision">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Card Issue Date</label>
                        <input type="date" class="form-control" id="editCardDate">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Assigned Manager <span class="text-danger">*</span></label>
                        <select class="form-select" id="editManager">
                            <option disabled>Select manager...</option>
                            <option>Jan Nowak</option>
                            <option>Anna Wiśniewska</option>
                            <option>Piotr Kowalczyk</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Sold By <span class="text-danger">*</span></label>
                        <select class="form-select" id="editSoldBy">
                            <option disabled>Who made the sale?</option>
                            <option>Jan Nowak</option>
                            <option>Anna Wiśniewska</option>
                            <option>Piotr Kowalczyk</option>
                            <option>Anna W.</option>
                            <option>Jan N.</option>
                            <option>Piotr K.</option>
                        </select>
                    </div>

                    <!-- Finance Section -->
                    <div class="col-12">
                        <hr>
                        <h6 class="text-muted text-uppercase fs-11 mb-3">Finance</h6>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Contract Amount (PLN) <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text">PLN</span>
                            <input type="number" class="form-control" id="editContract" placeholder="0.00" step="0.01">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Payment Status</label>
                        <select class="form-select" id="editPaymentStatus">
                            <option>Unpaid</option>
                            <option>Partially Paid</option>
                            <option>Fully Paid</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Amount Paid (PLN)</label>
                        <div class="input-group">
                            <span class="input-group-text">PLN</span>
                            <input type="number" class="form-control" id="editPaid" placeholder="0.00" step="0.01">
                        </div>
                    </div>

                    <div class="col-12">
                        <label class="form-label">Description</label>
                        <textarea class="form-control" rows="3" id="editDescription" placeholder="Case description, special requirements, notes..."></textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="editSaveBtn"><i class="ri-save-line me-1"></i>Save Changes</button>
            </div>
        </div>
    </div>
</div>

<!-- Timeline Modal -->
<div class="modal fade" id="timelineCaseModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-info-subtle">
                <h5 class="modal-title"><i class="ri-time-line me-2"></i>Timeline: <span id="timelineCaseTitle" class="fw-bold"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="timelineContent">
                    <!-- Filled by JS -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Advance Status Modal -->
<div class="modal fade" id="advanceCaseModal" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header bg-success-subtle">
                <h5 class="modal-title"><i class="ri-check-double-line me-2"></i>Advance Status</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <input type="hidden" id="advanceCaseId">
                <p class="mb-2 text-muted fs-13">Case <strong id="advanceCaseNumber"></strong></p>
                <div class="d-flex align-items-center justify-content-center gap-2 mb-3">
                    <span class="badge bg-secondary-subtle text-secondary fs-12 px-3 py-2" id="advanceCurrentStatus"></span>
                    <i class="ri-arrow-right-line fs-20 text-success"></i>
                    <span class="badge bg-success-subtle text-success fs-12 px-3 py-2" id="advanceNextStatus"></span>
                </div>
                <p class="text-muted fs-13">Are you sure you want to advance this case to the next status?</p>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-success btn-sm" id="advanceConfirmBtn"><i class="ri-check-line me-1"></i>Confirm Advance</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
document.addEventListener('DOMContentLoaded', function() {

    // ============================
    // Constants & Helpers
    // ============================
    var STATUSES = [
        'Submitted to Office',
        'Awaiting Fingerprints',
        'Fingerprint Appointment',
        'Fingerprints Submitted',
        'Awaiting Decision',
        'Decision Signed',
        'Card Issued'
    ];

    var STATUS_COLORS = {
        'Submitted to Office': 'primary',
        'Awaiting Fingerprints': 'info',
        'Fingerprint Appointment': 'warning',
        'Fingerprints Submitted': 'info',
        'Awaiting Decision': 'secondary',
        'Decision Signed': 'success',
        'Card Issued': 'success'
    };

    var TYPE_COLORS = {
        'Temporary Residence Card': 'primary',
        'Permanent Residence': 'info',
        'Long-term Resident': 'dark',
        'Citizenship': 'success',
        'Speedup': 'warning',
        'Appeal': 'danger',
        'Fingerprint Return': 'secondary',
        'Court Certificate': 'dark',
        'Deportation Cancellation': 'danger',
        'Other': 'secondary'
    };

    // Reference to currently viewed row for quick actions
    window.viewCaseRow = null;

    function getRow(el) {
        return el.closest('tr');
    }

    function setSelectValue(selectEl, value) {
        if (!selectEl || !value) return;
        for (var i = 0; i < selectEl.options.length; i++) {
            if (selectEl.options[i].text === value || selectEl.options[i].value === value) {
                selectEl.selectedIndex = i;
                return;
            }
        }
    }

    function showToast(message, type) {
        type = type || 'success';
        var colors = { success: '#28a745', danger: '#dc3545', warning: '#ffc107', info: '#17a2b8' };
        var toast = document.createElement('div');
        toast.style.cssText = 'position:fixed;top:20px;right:20px;z-index:99999;padding:12px 24px;border-radius:8px;color:#fff;font-size:14px;font-weight:500;box-shadow:0 4px 12px rgba(0,0,0,0.15);transition:opacity 0.3s;background:' + (colors[type] || colors.success);
        toast.textContent = message;
        document.body.appendChild(toast);
        setTimeout(function() { toast.style.opacity = '0'; }, 2500);
        setTimeout(function() { toast.remove(); }, 3000);
    }

    function formatDate(dateStr) {
        if (!dateStr) return '---';
        var d = new Date(dateStr);
        if (isNaN(d.getTime())) return dateStr;
        var months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
        return months[d.getMonth()] + ' ' + d.getDate() + ', ' + d.getFullYear();
    }

    function getStatusIndex(status) {
        return STATUSES.indexOf(status);
    }

    function getProgressPercent(step) {
        return Math.round((step / 7) * 100);
    }

    function getProgressColor(step) {
        if (step <= 1) return 'info';
        if (step <= 3) return 'warning';
        if (step <= 5) return 'primary';
        return 'success';
    }

    // ============================
    // Case Type Filter Toggle
    // ============================
    var caseTypeFilter = document.getElementById('caseTypeFilter');
    var casesTable = document.getElementById('casesTable');
    if (caseTypeFilter && casesTable) {
        caseTypeFilter.addEventListener('click', function(e) {
            var btn = e.target.closest('button');
            if (!btn) return;
            // Toggle button styles
            caseTypeFilter.querySelectorAll('button').forEach(function(b) {
                b.classList.remove('btn-primary', 'active');
                b.classList.add('btn-outline-primary');
            });
            btn.classList.remove('btn-outline-primary');
            btn.classList.add('btn-primary', 'active');

            // Filter table rows
            var filterType = btn.dataset.type;
            var rows = casesTable.querySelectorAll('tbody tr[data-case-id]');
            var visibleCount = 0;
            rows.forEach(function(row) {
                if (filterType === 'all' || row.dataset.type === filterType) {
                    row.style.display = '';
                    visibleCount++;
                } else {
                    row.style.display = 'none';
                }
            });

            // Update counter text
            var counter = casesTable.closest('.card').querySelector('.card-footer .text-muted');
            if (counter) {
                if (filterType === 'all') {
                    counter.textContent = 'Showing 1-' + rows.length + ' of 234 cases';
                } else {
                    counter.textContent = 'Showing ' + visibleCount + ' of 234 cases (filtered: ' + filterType + ')';
                }
            }
        });
    }

    // ============================
    // Case View Link (click on case# or client name)
    // ============================
    document.querySelectorAll('.case-view-link').forEach(function(link) {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            var row = getRow(this);
            openViewModal(row);
        });
    });

    // ============================
    // Action: View
    // ============================
    document.querySelectorAll('.action-view').forEach(function(el) {
        el.addEventListener('click', function(e) {
            e.preventDefault();
            var row = getRow(this);
            openViewModal(row);
        });
    });

    function openViewModal(row) {
        if (!row) return;
        window.viewCaseRow = row;
        var d = row.dataset;
        document.getElementById('viewCaseNumber').textContent = d.number;
        document.getElementById('viewInfoNumber').textContent = d.number;
        document.getElementById('viewInfoClient').textContent = d.client;
        document.getElementById('viewInfoType').innerHTML = '<span class="badge bg-' + (TYPE_COLORS[d.type] || 'primary') + '-subtle text-' + (TYPE_COLORS[d.type] || 'primary') + '">' + d.type + '</span>';
        document.getElementById('viewInfoStatus').innerHTML = '<span class="badge bg-' + (STATUS_COLORS[d.status] || 'secondary') + '-subtle text-' + (STATUS_COLORS[d.status] || 'secondary') + '">' + d.status + '</span>';
        document.getElementById('viewInfoCity').textContent = d.city;
        document.getElementById('viewInfoManager').textContent = d.manager;
        document.getElementById('viewInfoSoldBy').textContent = d.soldBy;
        document.getElementById('viewInfoSubmitted').textContent = formatDate(d.submitted);
        document.getElementById('viewInfoFingerprint').textContent = formatDate(d.fingerprintDate);
        document.getElementById('viewInfoDecision').textContent = formatDate(d.decisionDate);
        document.getElementById('viewInfoCardDate').textContent = formatDate(d.cardDate);

        var contract = parseFloat(d.contract) || 0;
        var paid = parseFloat(d.paid) || 0;
        var debt = contract - paid;
        document.getElementById('viewInfoContract').textContent = contract.toLocaleString() + ' PLN';
        document.getElementById('viewInfoPaid').textContent = paid.toLocaleString() + ' PLN';
        document.getElementById('viewInfoDebt').textContent = (debt > 0 ? debt.toLocaleString() + ' PLN' : '0 PLN');
        document.getElementById('viewInfoDebt').className = debt > 0 ? 'text-danger fw-semibold' : 'text-success fw-semibold';

        // Docs count
        var docsCount = document.querySelectorAll('#viewDocsList .list-group-item').length;
        document.getElementById('viewDocsCount').textContent = docsCount;

        // Status progress
        var currentStep = parseInt(d.progress) || 1;
        var html = '<div class="d-flex flex-column gap-0">';
        for (var i = 0; i < STATUSES.length; i++) {
            var stepNum = i + 1;
            var isDone = stepNum < currentStep;
            var isCurrent = stepNum === currentStep;
            var isPending = stepNum > currentStep;

            var iconClass = isDone ? 'ri-checkbox-circle-fill text-success' : (isCurrent ? 'ri-focus-3-line text-primary' : 'ri-checkbox-blank-circle-line text-muted');
            var textClass = isDone ? 'text-success' : (isCurrent ? 'fw-semibold text-primary' : 'text-muted');
            var lineColor = isDone ? 'bg-success' : 'bg-light';

            html += '<div class="d-flex align-items-center gap-2 py-1">';
            html += '<i class="' + iconClass + ' fs-18"></i>';
            html += '<span class="fs-13 ' + textClass + '">' + stepNum + '. ' + STATUSES[i] + '</span>';
            if (isCurrent) html += ' <span class="badge bg-primary ms-auto fs-10">Current</span>';
            if (isDone) html += ' <i class="ri-check-line text-success ms-auto"></i>';
            html += '</div>';
            if (i < STATUSES.length - 1) {
                html += '<div style="margin-left:9px;width:2px;height:12px;" class="' + lineColor + '"></div>';
            }
        }
        html += '</div>';
        document.getElementById('viewStatusProgress').innerHTML = html;

        new bootstrap.Modal(document.getElementById('viewCaseModal')).show();
    }

    // ============================
    // Action: Edit
    // ============================
    document.querySelectorAll('.action-edit').forEach(function(el) {
        el.addEventListener('click', function(e) {
            e.preventDefault();
            var row = getRow(this);
            openEditModal(row);
        });
    });

    function openEditModal(row) {
        if (!row) return;
        var d = row.dataset;
        document.getElementById('editCaseId').value = d.caseId;
        document.getElementById('editCaseTitle').textContent = d.number;
        document.getElementById('editCaseNumber').value = d.number;
        setSelectValue(document.getElementById('editClient'), d.client);
        setSelectValue(document.getElementById('editCaseType'), d.type);
        setSelectValue(document.getElementById('editStatus'), d.status);
        setSelectValue(document.getElementById('editCity'), d.city);
        setSelectValue(document.getElementById('editManager'), d.manager);
        setSelectValue(document.getElementById('editSoldBy'), d.soldBy);
        document.getElementById('editSubmitted').value = d.submitted;
        document.getElementById('editFingerprint').value = d.fingerprintDate;
        document.getElementById('editDecision').value = d.decisionDate;
        document.getElementById('editCardDate').value = d.cardDate;
        document.getElementById('editContract').value = d.contract;
        document.getElementById('editPaid').value = d.paid;

        var contract = parseFloat(d.contract) || 0;
        var paid = parseFloat(d.paid) || 0;
        if (paid >= contract && contract > 0) {
            setSelectValue(document.getElementById('editPaymentStatus'), 'Fully Paid');
        } else if (paid > 0) {
            setSelectValue(document.getElementById('editPaymentStatus'), 'Partially Paid');
        } else {
            setSelectValue(document.getElementById('editPaymentStatus'), 'Unpaid');
        }

        // Store row reference for save
        document.getElementById('editCaseModal')._editRow = row;

        new bootstrap.Modal(document.getElementById('editCaseModal')).show();
    }

    // ============================
    // Save Edit
    // ============================
    document.getElementById('editSaveBtn').addEventListener('click', function() {
        var modal = document.getElementById('editCaseModal');
        var row = modal._editRow;
        if (!row) return;

        var newClient = document.getElementById('editClient').value;
        var newType = document.getElementById('editCaseType').value;
        var newStatus = document.getElementById('editStatus').value;
        var newCity = document.getElementById('editCity').value;
        var newManager = document.getElementById('editManager').value;
        var newSoldBy = document.getElementById('editSoldBy').value;
        var newSubmitted = document.getElementById('editSubmitted').value;
        var newFingerprint = document.getElementById('editFingerprint').value;
        var newDecision = document.getElementById('editDecision').value;
        var newCardDate = document.getElementById('editCardDate').value;
        var newContract = document.getElementById('editContract').value;
        var newPaid = document.getElementById('editPaid').value;
        var newNumber = document.getElementById('editCaseNumber').value;
        var newProgress = getStatusIndex(newStatus) + 1;

        // Update data attributes
        row.dataset.client = newClient;
        row.dataset.type = newType;
        row.dataset.status = newStatus;
        row.dataset.city = newCity;
        row.dataset.manager = newManager;
        row.dataset.soldBy = newSoldBy;
        row.dataset.submitted = newSubmitted;
        row.dataset.fingerprintDate = newFingerprint;
        row.dataset.decisionDate = newDecision;
        row.dataset.cardDate = newCardDate;
        row.dataset.contract = newContract;
        row.dataset.paid = newPaid;
        row.dataset.number = newNumber;
        row.dataset.progress = newProgress;

        // Update visible cells
        var cells = row.querySelectorAll('td');
        // Cell 1: Case #
        cells[1].querySelector('a').textContent = newNumber;
        // Cell 2: Client
        cells[2].querySelector('a').textContent = newClient;
        // Cell 3: Case Type
        var typeColor = TYPE_COLORS[newType] || 'primary';
        cells[3].innerHTML = '<span class="badge bg-' + typeColor + '-subtle text-' + typeColor + '">' + newType + '</span>';
        // Cell 4: Status
        var statusColor = STATUS_COLORS[newStatus] || 'secondary';
        cells[4].innerHTML = '<span class="badge bg-' + statusColor + '-subtle text-' + statusColor + '">' + newStatus + '</span>';
        // Cell 5: City
        cells[5].textContent = newCity;
        // Cell 6: Manager
        cells[6].textContent = newManager;
        // Cell 7: Sold By
        cells[7].innerHTML = '<span class="text-muted fs-12">' + newSoldBy + '</span>';
        // Cell 8: Submission Date
        cells[8].textContent = formatDate(newSubmitted);
        cells[8].className = 'text-muted fs-12';
        // Cell 9: Progress
        var pColor = getProgressColor(newProgress);
        var pPercent = getProgressPercent(newProgress);
        cells[9].innerHTML = '<div class="d-flex align-items-center gap-2"><div class="progress flex-grow-1" style="height:6px;width:80px;"><div class="progress-bar bg-' + pColor + '" style="width:' + pPercent + '%"></div></div><span class="text-muted fs-12">' + newProgress + '/7</span></div>';

        bootstrap.Modal.getInstance(document.getElementById('editCaseModal')).hide();
        showToast('Case ' + newNumber + ' updated successfully!', 'success');
    });

    // ============================
    // Action: Documents (placeholder)
    // ============================
    document.querySelectorAll('.action-docs').forEach(function(el) {
        el.addEventListener('click', function(e) {
            e.preventDefault();
            var row = getRow(this);
            openViewModal(row);
            // Scroll to docs after modal open
            setTimeout(function() {
                var docsEl = document.getElementById('viewDocsList');
                if (docsEl) docsEl.scrollIntoView({ behavior: 'smooth' });
            }, 500);
        });
    });

    // ============================
    // Action: Timeline
    // ============================
    document.querySelectorAll('.action-timeline').forEach(function(el) {
        el.addEventListener('click', function(e) {
            e.preventDefault();
            var row = getRow(this);
            openTimelineModal(row);
        });
    });

    function openTimelineModal(row) {
        if (!row) return;
        var d = row.dataset;
        var currentStep = parseInt(d.progress) || 1;

        document.getElementById('timelineCaseTitle').textContent = d.number + ' - ' + d.client;

        var events = [
            { label: 'Case Created', date: d.submitted, icon: 'ri-add-circle-line', color: 'primary' },
            { label: 'Submitted to Office', date: d.submitted, icon: 'ri-government-line', color: 'primary' },
            { label: 'Awaiting Fingerprints', date: currentStep >= 2 ? d.submitted : '', icon: 'ri-time-line', color: 'info' },
            { label: 'Fingerprint Appointment', date: d.fingerprintDate, icon: 'ri-calendar-check-line', color: 'warning' },
            { label: 'Fingerprints Submitted', date: currentStep >= 4 ? d.fingerprintDate : '', icon: 'ri-fingerprint-line', color: 'info' },
            { label: 'Awaiting Decision', date: currentStep >= 5 ? '' : '', icon: 'ri-hourglass-line', color: 'secondary' },
            { label: 'Decision Signed', date: d.decisionDate, icon: 'ri-quill-pen-line', color: 'success' },
            { label: 'Card Issued', date: d.cardDate, icon: 'ri-bank-card-line', color: 'success' }
        ];

        var html = '<div class="timeline-vertical">';
        for (var i = 0; i < events.length; i++) {
            var ev = events[i];
            var stepIndex = i; // 0-based, event 0 = created (special), events 1-7 = statuses
            var isCompleted = false;
            var isCurrent = false;

            if (i === 0) {
                isCompleted = true; // Created is always done
            } else {
                isCompleted = i < currentStep;
                isCurrent = i === currentStep;
            }

            var dotClass = isCompleted ? 'bg-' + ev.color : (isCurrent ? 'bg-' + ev.color + ' border border-3 border-white shadow' : 'bg-light border');
            var textStyle = isCompleted || isCurrent ? '' : 'opacity: 0.5;';
            var dateStr = ev.date ? formatDate(ev.date) : (isCompleted ? 'Completed' : (isCurrent ? 'In Progress' : 'Pending'));

            html += '<div class="d-flex gap-3 mb-0" style="' + textStyle + '">';
            html += '<div class="d-flex flex-column align-items-center" style="width:40px;">';
            html += '<div class="rounded-circle d-flex align-items-center justify-content-center ' + dotClass + '" style="width:36px;height:36px;min-width:36px;">';
            html += '<i class="' + ev.icon + ' ' + (isCompleted || isCurrent ? 'text-white' : 'text-muted') + ' fs-16"></i>';
            html += '</div>';
            if (i < events.length - 1) {
                html += '<div style="width:2px;height:30px;" class="' + (isCompleted ? 'bg-' + ev.color : 'bg-light') + '"></div>';
            }
            html += '</div>';
            html += '<div class="pb-3">';
            html += '<h6 class="mb-0 fs-14">' + ev.label;
            if (isCurrent) html += ' <span class="badge bg-primary ms-1 fs-10">Current</span>';
            html += '</h6>';
            html += '<small class="text-muted">' + dateStr + '</small>';
            html += '</div>';
            html += '</div>';
        }
        html += '</div>';

        document.getElementById('timelineContent').innerHTML = html;
        new bootstrap.Modal(document.getElementById('timelineCaseModal')).show();
    }

    // ============================
    // Action: Advance Status
    // ============================
    document.querySelectorAll('.action-advance').forEach(function(el) {
        el.addEventListener('click', function(e) {
            e.preventDefault();
            var row = getRow(this);
            openAdvanceModal(row);
        });
    });

    function openAdvanceModal(row) {
        if (!row) return;
        var d = row.dataset;
        var currentIdx = getStatusIndex(d.status);

        if (currentIdx >= STATUSES.length - 1) {
            showToast('This case is already at the final status (Card Issued).', 'warning');
            return;
        }

        var nextStatus = STATUSES[currentIdx + 1];

        document.getElementById('advanceCaseId').value = d.caseId;
        document.getElementById('advanceCaseNumber').textContent = d.number;
        document.getElementById('advanceCurrentStatus').textContent = d.status;
        document.getElementById('advanceNextStatus').textContent = nextStatus;

        // Store row reference
        document.getElementById('advanceCaseModal')._advRow = row;

        new bootstrap.Modal(document.getElementById('advanceCaseModal')).show();
    }

    document.getElementById('advanceConfirmBtn').addEventListener('click', function() {
        var modal = document.getElementById('advanceCaseModal');
        var row = modal._advRow;
        if (!row) return;

        var d = row.dataset;
        var currentIdx = getStatusIndex(d.status);
        if (currentIdx >= STATUSES.length - 1) return;

        var newStatus = STATUSES[currentIdx + 1];
        var newProgress = currentIdx + 2; // 1-based

        // Update data attributes
        row.dataset.status = newStatus;
        row.dataset.progress = newProgress;

        // Update visible cells
        var cells = row.querySelectorAll('td');
        // Cell 4: Status
        var statusColor = STATUS_COLORS[newStatus] || 'secondary';
        cells[4].innerHTML = '<span class="badge bg-' + statusColor + '-subtle text-' + statusColor + '">' + newStatus + '</span>';
        // Cell 9: Progress
        var pColor = getProgressColor(newProgress);
        var pPercent = getProgressPercent(newProgress);
        cells[9].innerHTML = '<div class="d-flex align-items-center gap-2"><div class="progress flex-grow-1" style="height:6px;width:80px;"><div class="progress-bar bg-' + pColor + '" style="width:' + pPercent + '%"></div></div><span class="text-muted fs-12">' + newProgress + '/7</span></div>';

        // Update advance button text if at Decision Signed
        if (newStatus === 'Decision Signed') {
            var advLink = row.querySelector('.action-advance');
            if (advLink) advLink.innerHTML = '<i class="ri-check-line me-2"></i>Mark Card Issued';
        }

        bootstrap.Modal.getInstance(modal).hide();
        showToast('Case ' + d.number + ' advanced to: ' + newStatus, 'success');
    });

    // ============================
    // View → Quick Action Buttons
    // ============================
    document.getElementById('viewToEditBtn').addEventListener('click', function() {
        var row = window.viewCaseRow;
        bootstrap.Modal.getInstance(document.getElementById('viewCaseModal')).hide();
        setTimeout(function() { if (row) openEditModal(row); }, 300);
    });
    document.getElementById('viewToAdvanceBtn').addEventListener('click', function() {
        var row = window.viewCaseRow;
        bootstrap.Modal.getInstance(document.getElementById('viewCaseModal')).hide();
        setTimeout(function() { if (row) openAdvanceModal(row); }, 300);
    });
    document.getElementById('viewToTimelineBtn').addEventListener('click', function() {
        var row = window.viewCaseRow;
        bootstrap.Modal.getInstance(document.getElementById('viewCaseModal')).hide();
        setTimeout(function() { if (row) openTimelineModal(row); }, 300);
    });

});
</script>
@endsection
