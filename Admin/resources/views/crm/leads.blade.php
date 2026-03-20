@extends('partials.layouts.master')

@section('title', 'Leads — Sales | WinCase CRM')
@section('sub-title', 'Leads')
@section('sub-title-lang', 'wc-leads')
@section('pagetitle', 'Sales Department')
@section('pagetitle-lang', 'wc-ld-sales-department')
@section('buttonTitle', 'Add Lead')
@section('buttonTitle-lang', 'wc-ld-add-lead')
@section('modalTarget', 'addLeadModal')

@section('content')
<div class="row">
    <!-- Stats Cards -->
    <div class="col-xl-3 col-md-6">
        <div class="card card-h-100">
            <div class="card-body">
                <div class="d-flex align-items-center gap-3">
                    <div class="avatar avatar-sm bg-primary-subtle text-primary rounded-2">
                        <i class="ri-user-follow-line fs-18"></i>
                    </div>
                    <div>
                        <p class="text-muted mb-0 fs-13" data-lang="wc-ld-total-leads">Total Leads</p>
                        <h4 class="mb-0 fw-semibold">1,247</h4>
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
                        <i class="ri-time-line fs-18"></i>
                    </div>
                    <div>
                        <p class="text-muted mb-0 fs-13" data-lang="wc-ld-new-today">New Today</p>
                        <h4 class="mb-0 fw-semibold">12</h4>
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
                        <p class="text-muted mb-0 fs-13" data-lang="wc-ld-converted">Converted</p>
                        <h4 class="mb-0 fw-semibold">342</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card card-h-100">
            <div class="card-body">
                <div class="d-flex align-items-center gap-3">
                    <div class="avatar avatar-sm bg-danger-subtle text-danger rounded-2">
                        <i class="ri-user-unfollow-line fs-18"></i>
                    </div>
                    <div>
                        <p class="text-muted mb-0 fs-13" data-lang="wc-ld-unassigned">Unassigned</p>
                        <h4 class="mb-0 fw-semibold">8</h4>
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
                <input type="text" class="form-control" placeholder="Search leads..." data-lang-placeholder="wc-ld-search-ph">
            </div>
            <div class="col-md-2">
                <select class="form-select">
                    <option selected data-lang="wc-ld-all-statuses">All Statuses</option>
                    <option data-lang="wc-ld-status-new">New</option>
                    <option data-lang="wc-ld-status-in-progress">In Progress</option>
                    <option data-lang="wc-ld-status-consultation-scheduled">Consultation Scheduled</option>
                    <option data-lang="wc-ld-status-consultation-done">Consultation Done</option>
                    <option data-lang="wc-ld-status-awaiting-payment">Awaiting Payment</option>
                    <option data-lang="wc-ld-status-no-response">No Response</option>
                </select>
            </div>
            <div class="col-md-2">
                <select class="form-select">
                    <option selected data-lang="wc-ld-all-sources">All Sources</option>
                    <option>Instagram</option>
                    <option>Instagram Ads</option>
                    <option>Facebook Ads</option>
                    <option>Meta Ads</option>
                    <option>TikTok</option>
                    <option>TikTok Ads</option>
                    <option>Google</option>
                    <option>Google Ads</option>
                    <option>YouTube Ads</option>
                    <option>LinkedIn Ads</option>
                    <option>Referral</option>
                    <option>Phone</option>
                    <option>Walk-in</option>
                    <option>Website</option>
                </select>
            </div>
            <div class="col-md-2">
                <select class="form-select">
                    <option selected data-lang="wc-ld-all-services">All Services</option>
                    <option data-lang="wc-ld-svc-temp-residence">Temporary Residence Card</option>
                    <option data-lang="wc-ld-svc-perm-residence">Permanent Residence</option>
                    <option data-lang="wc-ld-svc-citizenship">Citizenship</option>
                    <option data-lang="wc-ld-svc-speedup">Speedup</option>
                    <option data-lang="wc-ld-svc-appeal">Appeal</option>
                    <option data-lang="wc-ld-svc-deportation">Deportation Cancellation</option>
                </select>
            </div>
            <div class="col-md-2">
                <select class="form-select">
                    <option selected data-lang="wc-ld-all-managers">All Managers</option>
                    <option>Jan Nowak</option>
                    <option>Anna Wiśniewska</option>
                    <option>Piotr Kowalczyk</option>
                </select>
            </div>
            <div class="col-md-1">
                <button class="btn btn-primary w-100"><i class="ri-filter-3-line"></i></button>
            </div>
        </div>
    </div>
</div>

<!-- Leads Table -->
<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" id="leadsTable">
                <thead class="table-light">
                    <tr>
                        <th><input class="form-check-input" type="checkbox" id="selectAllLeads"></th>
                        <th data-lang="wc-ld-name">Name</th>
                        <th data-lang="wc-ld-phone">Phone</th>
                        <th data-lang="wc-ld-source">Source</th>
                        <th data-lang="wc-ld-service">Service</th>
                        <th data-lang="wc-ld-status">Status</th>
                        <th data-lang="wc-ld-manager">Manager</th>
                        <th data-lang="wc-ld-created">Created</th>
                        <th data-lang="wc-ld-actions">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr data-lead-id="1" data-name="Anna Kowalska" data-phone="+48 512 345 678" data-email="kowalska@email.com" data-source="Instagram" data-service="Temporary Residence Card" data-status="New" data-manager="" data-language="Ukrainian" data-created="Mar 1, 2026, 10:15" data-notes="Interested in temporary residence card, found us on Instagram. Wants to schedule a consultation.">
                        <td><input class="form-check-input row-check" type="checkbox"></td>
                        <td><a href="#" class="fw-semibold text-body lead-view-link">Anna Kowalska</a></td>
                        <td>+48 512 345 678</td>
                        <td><span class="badge bg-danger-subtle text-danger">Instagram</span></td>
                        <td>Temporary Residence Card</td>
                        <td><span class="badge bg-warning-subtle text-warning" data-lang="wc-ld-status-new">New</span></td>
                        <td>—</td>
                        <td class="text-muted fs-12">Mar 1, 10:15</td>
                        <td>
                            <div class="dropdown">
                                <button class="btn btn-sm btn-subtle-secondary" data-bs-toggle="dropdown"><i class="ri-more-2-fill"></i></button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item action-view" href="#"><i class="ri-eye-line me-2"></i><span data-lang="wc-ld-view">View</span></a></li>
                                    <li><a class="dropdown-item action-edit" href="#"><i class="ri-edit-line me-2"></i><span data-lang="wc-ld-edit">Edit</span></a></li>
                                    <li><a class="dropdown-item action-assign" href="#"><i class="ri-user-shared-line me-2"></i><span data-lang="wc-ld-assign">Assign</span></a></li>
                                    <li><a class="dropdown-item action-convert" href="#"><i class="ri-exchange-line me-2"></i><span data-lang="wc-ld-convert-to-client">Convert to Client</span></a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item text-danger action-delete" href="#"><i class="ri-delete-bin-line me-2"></i><span data-lang="wc-ld-delete">Delete</span></a></li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                    <tr data-lead-id="2" data-name="Igor Bondarenko" data-phone="+48 698 765 432" data-email="bondarenko@email.com" data-source="Google" data-service="Permanent Residence" data-status="In Progress" data-manager="Jan Nowak" data-language="Ukrainian" data-created="Feb 28, 2026, 14:30" data-notes="Already has temporary residence, wants permanent. Documents partially collected.">
                        <td><input class="form-check-input row-check" type="checkbox"></td>
                        <td><a href="#" class="fw-semibold text-body lead-view-link">Igor Bondarenko</a></td>
                        <td>+48 698 765 432</td>
                        <td><span class="badge bg-primary-subtle text-primary">Google</span></td>
                        <td>Permanent Residence</td>
                        <td><span class="badge bg-primary-subtle text-primary" data-lang="wc-ld-status-in-progress">In Progress</span></td>
                        <td>Jan Nowak</td>
                        <td class="text-muted fs-12">Feb 28, 14:30</td>
                        <td>
                            <div class="dropdown">
                                <button class="btn btn-sm btn-subtle-secondary" data-bs-toggle="dropdown"><i class="ri-more-2-fill"></i></button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item action-view" href="#"><i class="ri-eye-line me-2"></i><span data-lang="wc-ld-view">View</span></a></li>
                                    <li><a class="dropdown-item action-edit" href="#"><i class="ri-edit-line me-2"></i><span data-lang="wc-ld-edit">Edit</span></a></li>
                                    <li><a class="dropdown-item action-convert" href="#"><i class="ri-exchange-line me-2"></i><span data-lang="wc-ld-convert-to-client">Convert to Client</span></a></li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                    <tr data-lead-id="3" data-name="Natalia Kravchuk" data-phone="+48 501 234 567" data-email="kravchuk@email.com" data-source="TikTok" data-service="Citizenship" data-status="Consultation Scheduled" data-manager="Anna Wiśniewska" data-language="Ukrainian" data-created="Feb 27, 2026, 09:45" data-notes="Consultation scheduled for Mar 3. Has been in Poland for 5+ years, meets citizenship criteria.">
                        <td><input class="form-check-input row-check" type="checkbox"></td>
                        <td><a href="#" class="fw-semibold text-body lead-view-link">Natalia Kravchuk</a></td>
                        <td>+48 501 234 567</td>
                        <td><span class="badge bg-info-subtle text-info">TikTok</span></td>
                        <td>Citizenship</td>
                        <td><span class="badge bg-success-subtle text-success" data-lang="wc-ld-status-consultation-scheduled">Consultation Scheduled</span></td>
                        <td>Anna Wiśniewska</td>
                        <td class="text-muted fs-12">Feb 27, 09:45</td>
                        <td>
                            <div class="dropdown">
                                <button class="btn btn-sm btn-subtle-secondary" data-bs-toggle="dropdown"><i class="ri-more-2-fill"></i></button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item action-view" href="#"><i class="ri-eye-line me-2"></i><span data-lang="wc-ld-view">View</span></a></li>
                                    <li><a class="dropdown-item action-edit" href="#"><i class="ri-edit-line me-2"></i><span data-lang="wc-ld-edit">Edit</span></a></li>
                                    <li><a class="dropdown-item action-convert" href="#"><i class="ri-exchange-line me-2"></i><span data-lang="wc-ld-convert-to-client">Convert to Client</span></a></li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                    <tr data-lead-id="4" data-name="Andriy Melnyk" data-phone="+48 600 111 222" data-email="melnyk@email.com" data-source="Referral" data-service="Speedup" data-status="Consultation Done" data-manager="Piotr Kowalczyk" data-language="Ukrainian" data-created="Feb 26, 2026, 16:20" data-notes="Referred by existing client Petrov. Needs speedup for pending case. Ready to sign contract.">
                        <td><input class="form-check-input row-check" type="checkbox"></td>
                        <td><a href="#" class="fw-semibold text-body lead-view-link">Andriy Melnyk</a></td>
                        <td>+48 600 111 222</td>
                        <td><span class="badge bg-warning-subtle text-warning">Referral</span></td>
                        <td>Speedup</td>
                        <td><span class="badge bg-info-subtle text-info" data-lang="wc-ld-status-consultation-done">Consultation Done</span></td>
                        <td>Piotr Kowalczyk</td>
                        <td class="text-muted fs-12">Feb 26, 16:20</td>
                        <td>
                            <div class="dropdown">
                                <button class="btn btn-sm btn-subtle-secondary" data-bs-toggle="dropdown"><i class="ri-more-2-fill"></i></button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item action-view" href="#"><i class="ri-eye-line me-2"></i><span data-lang="wc-ld-view">View</span></a></li>
                                    <li><a class="dropdown-item action-edit" href="#"><i class="ri-edit-line me-2"></i><span data-lang="wc-ld-edit">Edit</span></a></li>
                                    <li><a class="dropdown-item action-convert" href="#"><i class="ri-exchange-line me-2"></i><span data-lang="wc-ld-convert-to-client">Convert to Client</span></a></li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                    <tr data-lead-id="5" data-name="Olena Shevchenko" data-phone="+48 512 999 888" data-email="shevchenko@email.com" data-source="Meta Ads" data-service="Temporary Residence Card" data-status="Awaiting Payment" data-manager="Jan Nowak" data-language="Russian" data-created="Feb 25, 2026, 11:00" data-notes="Consultation completed. Contract prepared, waiting for first payment installment. Follow up on Mar 3.">
                        <td><input class="form-check-input row-check" type="checkbox"></td>
                        <td><a href="#" class="fw-semibold text-body lead-view-link">Olena Shevchenko</a></td>
                        <td>+48 512 999 888</td>
                        <td><span class="badge bg-secondary-subtle text-secondary">Meta Ads</span></td>
                        <td>Temporary Residence Card</td>
                        <td><span class="badge bg-secondary-subtle text-secondary" data-lang="wc-ld-status-awaiting-payment">Awaiting Payment</span></td>
                        <td>Jan Nowak</td>
                        <td class="text-muted fs-12">Feb 25, 11:00</td>
                        <td>
                            <div class="dropdown">
                                <button class="btn btn-sm btn-subtle-secondary" data-bs-toggle="dropdown"><i class="ri-more-2-fill"></i></button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item action-view" href="#"><i class="ri-eye-line me-2"></i><span data-lang="wc-ld-view">View</span></a></li>
                                    <li><a class="dropdown-item action-edit" href="#"><i class="ri-edit-line me-2"></i><span data-lang="wc-ld-edit">Edit</span></a></li>
                                    <li><a class="dropdown-item action-convert" href="#"><i class="ri-exchange-line me-2"></i><span data-lang="wc-ld-convert-to-client">Convert to Client</span></a></li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                    <tr data-lead-id="6" data-name="Dmitro Boyko" data-phone="+48 555 333 111" data-email="boyko@email.com" data-source="Instagram" data-service="Appeal" data-status="No Response" data-manager="Anna Wiśniewska" data-language="Ukrainian" data-created="Feb 24, 2026, 08:30" data-notes="Called 3 times, no answer. Sent SMS and WhatsApp. Mark as lost if no response by Mar 5.">
                        <td><input class="form-check-input row-check" type="checkbox"></td>
                        <td><a href="#" class="fw-semibold text-body lead-view-link">Dmitro Boyko</a></td>
                        <td>+48 555 333 111</td>
                        <td><span class="badge bg-danger-subtle text-danger">Instagram</span></td>
                        <td>Appeal</td>
                        <td><span class="badge bg-danger-subtle text-danger" data-lang="wc-ld-status-no-response">No Response</span></td>
                        <td>Anna Wiśniewska</td>
                        <td class="text-muted fs-12">Feb 24, 08:30</td>
                        <td>
                            <div class="dropdown">
                                <button class="btn btn-sm btn-subtle-secondary" data-bs-toggle="dropdown"><i class="ri-more-2-fill"></i></button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item action-view" href="#"><i class="ri-eye-line me-2"></i><span data-lang="wc-ld-view">View</span></a></li>
                                    <li><a class="dropdown-item action-edit" href="#"><i class="ri-edit-line me-2"></i><span data-lang="wc-ld-edit">Edit</span></a></li>
                                    <li><a class="dropdown-item action-assign" href="#"><i class="ri-user-shared-line me-2"></i><span data-lang="wc-ld-assign">Assign</span></a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item text-danger action-delete" href="#"><i class="ri-delete-bin-line me-2"></i><span data-lang="wc-ld-delete">Delete</span></a></li>
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
            <div class="text-muted fs-13">Showing 1-6 of 1,247 leads</div>
            <nav>
                <ul class="pagination pagination-sm mb-0">
                    <li class="page-item disabled"><a class="page-link" href="#">Previous</a></li>
                    <li class="page-item active"><a class="page-link" href="#">1</a></li>
                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                    <li class="page-item"><a class="page-link" href="#">...</a></li>
                    <li class="page-item"><a class="page-link" href="#">208</a></li>
                    <li class="page-item"><a class="page-link" href="#">Next</a></li>
                </ul>
            </nav>
        </div>
    </div>
</div>

<!-- ============================================ -->
<!-- VIEW LEAD MODAL -->
<!-- ============================================ -->
<div class="modal fade" id="viewLeadModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="ri-eye-line me-2"></i><span data-lang="wc-ld-view-lead">Lead Details</span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <!-- Left: Info -->
                    <div class="col-md-6">
                        <div class="text-center mb-3">
                            <div class="avatar avatar-lg avatar-rounded bg-primary-subtle text-primary mx-auto mb-2">
                                <span class="fs-24" id="viewLeadInitials">AK</span>
                            </div>
                            <h5 class="mb-1" id="viewLeadName">Anna Kowalska</h5>
                            <span class="badge bg-warning-subtle text-warning" id="viewLeadStatusBadge">New</span>
                        </div>
                        <table class="table table-borderless table-sm mb-0">
                            <tbody>
                                <tr>
                                    <td class="text-muted fw-medium" style="width:40%" data-lang="wc-ld-phone">Phone</td>
                                    <td id="viewLeadPhone"><a href="tel:+48512345678">+48 512 345 678</a></td>
                                </tr>
                                <tr>
                                    <td class="text-muted fw-medium" data-lang="wc-ld-email">Email</td>
                                    <td id="viewLeadEmail"><a href="mailto:kowalska@email.com">kowalska@email.com</a></td>
                                </tr>
                                <tr>
                                    <td class="text-muted fw-medium" data-lang="wc-ld-source">Source</td>
                                    <td id="viewLeadSource"><span class="badge bg-danger-subtle text-danger">Instagram</span></td>
                                </tr>
                                <tr>
                                    <td class="text-muted fw-medium" data-lang="wc-ld-service">Service</td>
                                    <td id="viewLeadService">Temporary Residence Card</td>
                                </tr>
                                <tr>
                                    <td class="text-muted fw-medium" data-lang="wc-ld-language">Language</td>
                                    <td id="viewLeadLanguage">Ukrainian</td>
                                </tr>
                                <tr>
                                    <td class="text-muted fw-medium" data-lang="wc-ld-manager">Manager</td>
                                    <td id="viewLeadManager">—</td>
                                </tr>
                                <tr>
                                    <td class="text-muted fw-medium" data-lang="wc-ld-created">Created</td>
                                    <td id="viewLeadCreated">Mar 1, 2026, 10:15</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <!-- Right: Notes & Activity -->
                    <div class="col-md-6">
                        <h6 class="text-muted text-uppercase fs-11 mb-2" data-lang="wc-ld-notes">Notes</h6>
                        <div class="border rounded p-3 mb-3 bg-light" id="viewLeadNotes" style="min-height:80px;">
                            Interested in temporary residence card...
                        </div>

                        <h6 class="text-muted text-uppercase fs-11 mb-2" data-lang="wc-ld-activity-timeline">Activity Timeline</h6>
                        <div class="timeline-sm" id="viewLeadTimeline">
                            <div class="d-flex align-items-start mb-3">
                                <div class="me-3">
                                    <div class="avatar avatar-xs avatar-rounded bg-primary-subtle text-primary"><i class="ri-add-line fs-14"></i></div>
                                </div>
                                <div>
                                    <h6 class="mb-0 fs-13" data-lang="wc-ld-lead-created">Lead created</h6>
                                    <span class="text-muted fs-12" id="viewLeadTimelineCreated">Mar 1, 2026, 10:15</span>
                                </div>
                            </div>
                            <div class="d-flex align-items-start mb-3">
                                <div class="me-3">
                                    <div class="avatar avatar-xs avatar-rounded bg-info-subtle text-info"><i class="ri-phone-line fs-14"></i></div>
                                </div>
                                <div>
                                    <h6 class="mb-0 fs-13" data-lang="wc-ld-first-contact">First contact attempt</h6>
                                    <span class="text-muted fs-12" data-lang="wc-ld-system-auto">System auto-task</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal" data-lang="wc-ld-close">Close</button>
                <button type="button" class="btn btn-outline-primary" id="viewToEditBtn"><i class="ri-edit-line me-1"></i><span data-lang="wc-ld-edit">Edit</span></button>
                <button type="button" class="btn btn-primary" id="viewToConvertBtn"><i class="ri-exchange-line me-1"></i><span data-lang="wc-ld-convert-to-client">Convert to Client</span></button>
            </div>
        </div>
    </div>
</div>

<!-- ============================================ -->
<!-- EDIT LEAD MODAL -->
<!-- ============================================ -->
<div class="modal fade" id="editLeadModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="ri-edit-line me-2"></i><span data-lang="wc-ld-edit-lead">Edit Lead</span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="editLeadId">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label" data-lang="wc-ld-full-name">Full Name</label> <span class="text-danger">*</span>
                        <input type="text" class="form-control" id="editLeadNameInput">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" data-lang="wc-ld-phone">Phone</label> <span class="text-danger">*</span>
                        <input type="text" class="form-control" id="editLeadPhoneInput">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" data-lang="wc-ld-email">Email</label>
                        <input type="email" class="form-control" id="editLeadEmailInput">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" data-lang="wc-ld-source">Source</label> <span class="text-danger">*</span>
                        <select class="form-select" id="editLeadSourceInput">
                            <option disabled data-lang="wc-ld-select-source">Select source...</option>
                            <option>Instagram</option>
                            <option>Instagram Ads</option>
                            <option>Facebook Ads</option>
                            <option>Meta Ads</option>
                            <option>TikTok</option>
                            <option>TikTok Ads</option>
                            <option>Google</option>
                            <option>Google Ads</option>
                            <option>YouTube Ads</option>
                            <option>LinkedIn Ads</option>
                            <option>Referral</option>
                            <option>Phone</option>
                            <option>Walk-in</option>
                            <option>Website</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" data-lang="wc-ld-service-type">Service Type</label> <span class="text-danger">*</span>
                        <select class="form-select" id="editLeadServiceInput">
                            <option disabled data-lang="wc-ld-select-service">Select service...</option>
                            <option data-lang="wc-ld-svc-temp-residence">Temporary Residence Card</option>
                            <option data-lang="wc-ld-svc-perm-residence">Permanent Residence</option>
                            <option data-lang="wc-ld-svc-longterm">Long-term Resident</option>
                            <option data-lang="wc-ld-svc-citizenship">Citizenship</option>
                            <option data-lang="wc-ld-svc-speedup">Speedup</option>
                            <option data-lang="wc-ld-svc-appeal">Appeal</option>
                            <option data-lang="wc-ld-svc-fingerprint">Fingerprint Return</option>
                            <option data-lang="wc-ld-svc-court-cert">Court Certificate</option>
                            <option data-lang="wc-ld-svc-deportation">Deportation Cancellation</option>
                            <option data-lang="wc-ld-svc-other">Other</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" data-lang="wc-ld-status">Status</label>
                        <select class="form-select" id="editLeadStatusInput">
                            <option data-lang="wc-ld-status-new">New</option>
                            <option data-lang="wc-ld-status-in-progress">In Progress</option>
                            <option data-lang="wc-ld-status-consultation-scheduled">Consultation Scheduled</option>
                            <option data-lang="wc-ld-status-consultation-done">Consultation Done</option>
                            <option data-lang="wc-ld-status-awaiting-payment">Awaiting Payment</option>
                            <option data-lang="wc-ld-status-no-response">No Response</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" data-lang="wc-ld-language">Language</label>
                        <select class="form-select" id="editLeadLanguageInput">
                            <option data-lang="wc-ld-lang-ukrainian">Ukrainian</option>
                            <option data-lang="wc-ld-lang-russian">Russian</option>
                            <option data-lang="wc-ld-lang-polish">Polish</option>
                            <option data-lang="wc-ld-lang-english">English</option>
                            <option data-lang="wc-ld-svc-other">Other</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" data-lang="wc-ld-assigned-manager">Assigned Manager</label>
                        <select class="form-select" id="editLeadManagerInput">
                            <option value="" data-lang="wc-ld-not-assigned">— Not Assigned —</option>
                            <option>Jan Nowak</option>
                            <option>Anna Wiśniewska</option>
                            <option>Piotr Kowalczyk</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label" data-lang="wc-ld-notes">Notes</label>
                        <textarea class="form-control" rows="3" id="editLeadNotesInput"></textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal" data-lang="wc-ld-cancel">Cancel</button>
                <button type="button" class="btn btn-primary" id="saveEditLeadBtn"><i class="ri-save-line me-1"></i><span data-lang="wc-ld-save-changes">Save Changes</span></button>
            </div>
        </div>
    </div>
</div>

<!-- ============================================ -->
<!-- ASSIGN MANAGER MODAL -->
<!-- ============================================ -->
<div class="modal fade" id="assignLeadModal" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="ri-user-shared-line me-2"></i><span data-lang="wc-ld-assign-manager">Assign Manager</span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="assignLeadId">
                <p class="text-muted mb-2"><span data-lang="wc-ld-assign">Assign</span> <strong id="assignLeadName">Lead</strong> to:</p>
                <select class="form-select" id="assignManagerSelect">
                    <option disabled selected data-lang="wc-ld-select-manager">Select manager...</option>
                    <option>Jan Nowak</option>
                    <option>Anna Wiśniewska</option>
                    <option>Piotr Kowalczyk</option>
                </select>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal" data-lang="wc-ld-cancel">Cancel</button>
                <button type="button" class="btn btn-primary" id="saveAssignBtn"><i class="ri-check-line me-1"></i><span data-lang="wc-ld-assign">Assign</span></button>
            </div>
        </div>
    </div>
</div>

<!-- ============================================ -->
<!-- CONVERT TO CLIENT MODAL -->
<!-- ============================================ -->
<div class="modal fade" id="convertLeadModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-success-subtle">
                <h5 class="modal-title text-success"><i class="ri-exchange-line me-2"></i><span data-lang="wc-ld-convert-to-client">Convert to Client</span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="convertLeadId">
                <div class="text-center mb-3">
                    <div class="avatar avatar-md avatar-rounded bg-success-subtle text-success mx-auto mb-2">
                        <i class="ri-user-add-line fs-24"></i>
                    </div>
                    <h5 id="convertLeadName">Anna Kowalska</h5>
                    <p class="text-muted" data-lang="wc-ld-convert-desc">This lead will be converted to a client. All data will be transferred.</p>
                </div>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label" data-lang="wc-ld-client-type">Client Type</label> <span class="text-danger">*</span>
                        <select class="form-select" id="convertClientType">
                            <option selected data-lang="wc-ld-new-client">New Client</option>
                            <option data-lang="wc-ld-returning-client">Returning Client</option>
                            <option>Referral</option>
                            <option data-lang="wc-ld-from-agency">From Agency</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" data-lang="wc-ld-create-case">Create Case?</label>
                        <select class="form-select" id="convertCreateCase">
                            <option selected data-lang="wc-ld-yes-create-case">Yes — create case</option>
                            <option data-lang="wc-ld-no-just-client">No — just client</option>
                        </select>
                    </div>
                    <div class="col-12" id="convertCaseTypeRow">
                        <label class="form-label" data-lang="wc-ld-case-type">Case Type</label>
                        <select class="form-select" id="convertCaseType">
                            <option data-lang="wc-ld-svc-temp-residence">Temporary Residence Card</option>
                            <option data-lang="wc-ld-svc-perm-residence">Permanent Residence</option>
                            <option data-lang="wc-ld-svc-longterm">Long-term Resident</option>
                            <option data-lang="wc-ld-svc-citizenship">Citizenship</option>
                            <option data-lang="wc-ld-svc-speedup">Speedup</option>
                            <option data-lang="wc-ld-svc-appeal">Appeal</option>
                            <option data-lang="wc-ld-svc-fingerprint">Fingerprint Return</option>
                            <option data-lang="wc-ld-svc-court-cert">Court Certificate</option>
                            <option data-lang="wc-ld-svc-deportation">Deportation Cancellation</option>
                            <option data-lang="wc-ld-svc-other">Other</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal" data-lang="wc-ld-cancel">Cancel</button>
                <button type="button" class="btn btn-success" id="confirmConvertBtn"><i class="ri-check-line me-1"></i><span data-lang="wc-ld-convert">Convert</span></button>
            </div>
        </div>
    </div>
</div>

<!-- ============================================ -->
<!-- DELETE LEAD MODAL -->
<!-- ============================================ -->
<div class="modal fade" id="deleteLeadModal" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header bg-danger-subtle">
                <h5 class="modal-title text-danger"><i class="ri-delete-bin-line me-2"></i><span data-lang="wc-ld-delete-lead">Delete Lead</span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <input type="hidden" id="deleteLeadId">
                <div class="avatar avatar-md avatar-rounded bg-danger-subtle text-danger mx-auto mb-3">
                    <i class="ri-error-warning-line fs-24"></i>
                </div>
                <h6 data-lang="wc-ld-are-you-sure">Are you sure?</h6>
                <p class="text-muted"><span data-lang="wc-ld-delete-confirm-prefix">Delete lead</span> <strong id="deleteLeadName">Lead</strong>? <span data-lang="wc-ld-cannot-undone">This action cannot be undone.</span></p>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal" data-lang="wc-ld-cancel">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteBtn"><i class="ri-delete-bin-line me-1"></i><span data-lang="wc-ld-delete">Delete</span></button>
            </div>
        </div>
    </div>
</div>

<!-- ============================================ -->
<!-- ADD LEAD MODAL (original) -->
<!-- ============================================ -->
<div class="modal fade" id="addLeadModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" data-lang="wc-ld-add-new-lead">Add New Lead</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label" data-lang="wc-ld-full-name">Full Name</label> <span class="text-danger">*</span>
                        <input type="text" class="form-control" placeholder="Enter full name" data-lang-placeholder="wc-ld-enter-full-name-ph">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" data-lang="wc-ld-phone">Phone</label> <span class="text-danger">*</span>
                        <input type="text" class="form-control" placeholder="+48 XXX XXX XXX">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" data-lang="wc-ld-email">Email</label>
                        <input type="email" class="form-control" placeholder="email@example.com">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" data-lang="wc-ld-source">Source</label> <span class="text-danger">*</span>
                        <select class="form-select">
                            <option selected disabled data-lang="wc-ld-select-source">Select source...</option>
                            <option>Instagram</option>
                            <option>Instagram Ads</option>
                            <option>Facebook Ads</option>
                            <option>Meta Ads</option>
                            <option>TikTok</option>
                            <option>TikTok Ads</option>
                            <option>Google</option>
                            <option>Google Ads</option>
                            <option>YouTube Ads</option>
                            <option>LinkedIn Ads</option>
                            <option>Referral</option>
                            <option>Phone</option>
                            <option>Walk-in</option>
                            <option>Website</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" data-lang="wc-ld-service-type">Service Type</label> <span class="text-danger">*</span>
                        <select class="form-select">
                            <option selected disabled data-lang="wc-ld-select-service">Select service...</option>
                            <option data-lang="wc-ld-svc-temp-residence">Temporary Residence Card</option>
                            <option data-lang="wc-ld-svc-perm-residence">Permanent Residence</option>
                            <option data-lang="wc-ld-svc-longterm">Long-term Resident</option>
                            <option data-lang="wc-ld-svc-citizenship">Citizenship</option>
                            <option data-lang="wc-ld-svc-speedup">Speedup</option>
                            <option data-lang="wc-ld-svc-appeal">Appeal</option>
                            <option data-lang="wc-ld-svc-fingerprint">Fingerprint Return</option>
                            <option data-lang="wc-ld-svc-court-cert">Court Certificate</option>
                            <option data-lang="wc-ld-svc-deportation">Deportation Cancellation</option>
                            <option data-lang="wc-ld-svc-other">Other</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" data-lang="wc-ld-status">Status</label>
                        <select class="form-select">
                            <option selected data-lang="wc-ld-status-new">New</option>
                            <option data-lang="wc-ld-status-in-progress">In Progress</option>
                            <option data-lang="wc-ld-status-consultation-scheduled">Consultation Scheduled</option>
                            <option data-lang="wc-ld-status-consultation-done">Consultation Done</option>
                            <option data-lang="wc-ld-status-awaiting-payment">Awaiting Payment</option>
                            <option data-lang="wc-ld-status-no-response">No Response</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" data-lang="wc-ld-language">Language</label>
                        <select class="form-select">
                            <option data-lang="wc-ld-lang-ukrainian">Ukrainian</option>
                            <option data-lang="wc-ld-lang-russian">Russian</option>
                            <option data-lang="wc-ld-lang-polish">Polish</option>
                            <option data-lang="wc-ld-lang-english">English</option>
                            <option data-lang="wc-ld-svc-other">Other</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" data-lang="wc-ld-assigned-manager">Assigned Manager</label>
                        <select class="form-select">
                            <option selected disabled data-lang="wc-ld-select-manager">Select manager...</option>
                            <option>Jan Nowak</option>
                            <option>Anna Wiśniewska</option>
                            <option>Piotr Kowalczyk</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label" data-lang="wc-ld-notes">Notes</label>
                        <textarea class="form-control" rows="3" placeholder="Additional notes..." data-lang-placeholder="wc-ld-notes-ph"></textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal" data-lang="wc-ld-cancel">Cancel</button>
                <button type="button" class="btn btn-primary" data-lang="wc-ld-save-lead">Save Lead</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
document.addEventListener('DOMContentLoaded', function() {
    var leadsTable = document.getElementById('leadsTable');
    if (!leadsTable) return;

    // ===== Helper: get lead row data =====
    function getRowData(row) {
        return {
            id: row.dataset.leadId,
            name: row.dataset.name,
            phone: row.dataset.phone,
            email: row.dataset.email,
            source: row.dataset.source,
            service: row.dataset.service,
            status: row.dataset.status,
            manager: row.dataset.manager,
            language: row.dataset.language,
            created: row.dataset.created,
            notes: row.dataset.notes
        };
    }

    function getRow(el) {
        return el.closest('tr[data-lead-id]');
    }

    function getInitials(name) {
        return name.split(' ').map(function(w){ return w[0]; }).join('').substring(0,2).toUpperCase();
    }

    function getSourceBadge(source) {
        var colors = { 'Instagram':'danger', 'Instagram Ads':'danger', 'Facebook Ads':'primary', 'Meta Ads':'secondary', 'TikTok':'info', 'TikTok Ads':'info', 'Google':'primary', 'Google Ads':'primary', 'YouTube Ads':'danger', 'LinkedIn Ads':'primary', 'Referral':'warning', 'Phone':'success', 'Walk-in':'dark', 'Website':'secondary' };
        var c = colors[source] || 'secondary';
        return '<span class="badge bg-'+c+'-subtle text-'+c+'">'+source+'</span>';
    }

    function getStatusBadge(status) {
        var colors = { 'New':'warning', 'In Progress':'primary', 'Consultation Scheduled':'success', 'Consultation Done':'info', 'Awaiting Payment':'secondary', 'No Response':'danger' };
        var c = colors[status] || 'secondary';
        return '<span class="badge bg-'+c+'-subtle text-'+c+'">'+status+'</span>';
    }

    function setSelectValue(sel, val) {
        for (var i = 0; i < sel.options.length; i++) {
            if (sel.options[i].value === val || sel.options[i].text === val) {
                sel.selectedIndex = i; return;
            }
        }
    }

    function showToast(msg, type) {
        var t = document.createElement('div');
        t.className = 'position-fixed top-0 end-0 p-3';
        t.style.zIndex = '9999';
        t.innerHTML = '<div class="alert alert-'+type+' alert-dismissible fade show shadow" role="alert">'+msg+'<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>';
        document.body.appendChild(t);
        setTimeout(function(){ t.remove(); }, 3000);
    }

    // Store current lead data for cross-modal actions
    var currentLead = null;

    // ===== VIEW =====
    leadsTable.addEventListener('click', function(e) {
        var link = e.target.closest('.action-view, .lead-view-link');
        if (!link) return;
        e.preventDefault();
        var row = getRow(link);
        if (!row) return;
        currentLead = getRowData(row);
        document.getElementById('viewLeadInitials').textContent = getInitials(currentLead.name);
        document.getElementById('viewLeadName').textContent = currentLead.name;
        document.getElementById('viewLeadStatusBadge').innerHTML = getStatusBadge(currentLead.status).replace('badge ', 'badge ');
        document.getElementById('viewLeadStatusBadge').className = '';
        var sb = document.createElement('div');
        sb.innerHTML = getStatusBadge(currentLead.status);
        var badge = sb.firstChild;
        document.getElementById('viewLeadStatusBadge').className = badge.className;
        document.getElementById('viewLeadStatusBadge').textContent = currentLead.status;
        document.getElementById('viewLeadPhone').innerHTML = '<a href="tel:'+currentLead.phone.replace(/\s/g,'')+'">'+currentLead.phone+'</a>';
        document.getElementById('viewLeadEmail').innerHTML = currentLead.email ? '<a href="mailto:'+currentLead.email+'">'+currentLead.email+'</a>' : '—';
        document.getElementById('viewLeadSource').innerHTML = getSourceBadge(currentLead.source);
        document.getElementById('viewLeadService').textContent = currentLead.service;
        document.getElementById('viewLeadLanguage').textContent = currentLead.language;
        document.getElementById('viewLeadManager').textContent = currentLead.manager || '— Not Assigned —';
        document.getElementById('viewLeadCreated').textContent = currentLead.created;
        document.getElementById('viewLeadNotes').textContent = currentLead.notes || 'No notes';
        document.getElementById('viewLeadTimelineCreated').textContent = currentLead.created;
        new bootstrap.Modal(document.getElementById('viewLeadModal')).show();
    });

    // View → Edit button
    document.getElementById('viewToEditBtn').addEventListener('click', function() {
        bootstrap.Modal.getInstance(document.getElementById('viewLeadModal')).hide();
        setTimeout(function(){ openEditModal(currentLead); }, 300);
    });

    // View → Convert button
    document.getElementById('viewToConvertBtn').addEventListener('click', function() {
        bootstrap.Modal.getInstance(document.getElementById('viewLeadModal')).hide();
        setTimeout(function(){ openConvertModal(currentLead); }, 300);
    });

    // ===== EDIT =====
    function openEditModal(data) {
        document.getElementById('editLeadId').value = data.id;
        document.getElementById('editLeadNameInput').value = data.name;
        document.getElementById('editLeadPhoneInput').value = data.phone;
        document.getElementById('editLeadEmailInput').value = data.email;
        setSelectValue(document.getElementById('editLeadSourceInput'), data.source);
        setSelectValue(document.getElementById('editLeadServiceInput'), data.service);
        setSelectValue(document.getElementById('editLeadStatusInput'), data.status);
        setSelectValue(document.getElementById('editLeadLanguageInput'), data.language);
        setSelectValue(document.getElementById('editLeadManagerInput'), data.manager || '');
        document.getElementById('editLeadNotesInput').value = data.notes;
        new bootstrap.Modal(document.getElementById('editLeadModal')).show();
    }

    leadsTable.addEventListener('click', function(e) {
        var link = e.target.closest('.action-edit');
        if (!link) return;
        e.preventDefault();
        var row = getRow(link);
        if (!row) return;
        openEditModal(getRowData(row));
    });

    document.getElementById('saveEditLeadBtn').addEventListener('click', function() {
        var id = document.getElementById('editLeadId').value;
        var row = leadsTable.querySelector('tr[data-lead-id="'+id+'"]');
        if (row) {
            row.dataset.name = document.getElementById('editLeadNameInput').value;
            row.dataset.phone = document.getElementById('editLeadPhoneInput').value;
            row.dataset.email = document.getElementById('editLeadEmailInput').value;
            row.dataset.source = document.getElementById('editLeadSourceInput').value;
            row.dataset.service = document.getElementById('editLeadServiceInput').value;
            row.dataset.status = document.getElementById('editLeadStatusInput').value;
            row.dataset.language = document.getElementById('editLeadLanguageInput').value;
            row.dataset.manager = document.getElementById('editLeadManagerInput').value;
            row.dataset.notes = document.getElementById('editLeadNotesInput').value;
            // Update visible cells
            var cells = row.querySelectorAll('td');
            cells[1].querySelector('a').textContent = row.dataset.name;
            cells[2].textContent = row.dataset.phone;
            cells[3].innerHTML = getSourceBadge(row.dataset.source);
            cells[4].textContent = row.dataset.service;
            cells[5].innerHTML = getStatusBadge(row.dataset.status);
            cells[6].textContent = row.dataset.manager || '—';
        }
        bootstrap.Modal.getInstance(document.getElementById('editLeadModal')).hide();
        showToast('<i class="ri-check-line me-1"></i> Lead updated successfully', 'success');
    });

    // ===== ASSIGN =====
    leadsTable.addEventListener('click', function(e) {
        var link = e.target.closest('.action-assign');
        if (!link) return;
        e.preventDefault();
        var row = getRow(link);
        if (!row) return;
        var data = getRowData(row);
        document.getElementById('assignLeadId').value = data.id;
        document.getElementById('assignLeadName').textContent = data.name;
        document.getElementById('assignManagerSelect').selectedIndex = 0;
        new bootstrap.Modal(document.getElementById('assignLeadModal')).show();
    });

    document.getElementById('saveAssignBtn').addEventListener('click', function() {
        var id = document.getElementById('assignLeadId').value;
        var manager = document.getElementById('assignManagerSelect').value;
        var row = leadsTable.querySelector('tr[data-lead-id="'+id+'"]');
        if (row && manager) {
            row.dataset.manager = manager;
            row.querySelectorAll('td')[6].textContent = manager;
        }
        bootstrap.Modal.getInstance(document.getElementById('assignLeadModal')).hide();
        showToast('<i class="ri-user-shared-line me-1"></i> Manager assigned: ' + manager, 'success');
    });

    // ===== CONVERT =====
    function openConvertModal(data) {
        document.getElementById('convertLeadId').value = data.id;
        document.getElementById('convertLeadName').textContent = data.name;
        setSelectValue(document.getElementById('convertCaseType'), data.service);
        new bootstrap.Modal(document.getElementById('convertLeadModal')).show();
    }

    leadsTable.addEventListener('click', function(e) {
        var link = e.target.closest('.action-convert');
        if (!link) return;
        e.preventDefault();
        var row = getRow(link);
        if (!row) return;
        openConvertModal(getRowData(row));
    });

    document.getElementById('convertCreateCase').addEventListener('change', function() {
        document.getElementById('convertCaseTypeRow').style.display = this.value.includes('Yes') ? '' : 'none';
    });

    document.getElementById('confirmConvertBtn').addEventListener('click', function() {
        var id = document.getElementById('convertLeadId').value;
        var row = leadsTable.querySelector('tr[data-lead-id="'+id+'"]');
        if (row) {
            row.querySelectorAll('td')[5].innerHTML = '<span class="badge bg-success">Converted</span>';
        }
        bootstrap.Modal.getInstance(document.getElementById('convertLeadModal')).hide();
        showToast('<i class="ri-exchange-line me-1"></i> Lead converted to client! <a href="crm-clients" class="text-white text-decoration-underline ms-1">View Clients</a>', 'success');
    });

    // ===== DELETE =====
    leadsTable.addEventListener('click', function(e) {
        var link = e.target.closest('.action-delete');
        if (!link) return;
        e.preventDefault();
        var row = getRow(link);
        if (!row) return;
        var data = getRowData(row);
        document.getElementById('deleteLeadId').value = data.id;
        document.getElementById('deleteLeadName').textContent = data.name;
        new bootstrap.Modal(document.getElementById('deleteLeadModal')).show();
    });

    document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
        var id = document.getElementById('deleteLeadId').value;
        var row = leadsTable.querySelector('tr[data-lead-id="'+id+'"]');
        if (row) {
            row.style.transition = 'opacity 0.3s';
            row.style.opacity = '0';
            setTimeout(function(){ row.remove(); }, 300);
        }
        bootstrap.Modal.getInstance(document.getElementById('deleteLeadModal')).hide();
        showToast('<i class="ri-delete-bin-line me-1"></i> Lead deleted', 'danger');
    });

    // ===== SELECT ALL =====
    document.getElementById('selectAllLeads').addEventListener('change', function() {
        var checks = leadsTable.querySelectorAll('.row-check');
        for (var i = 0; i < checks.length; i++) {
            checks[i].checked = this.checked;
        }
    });
});
</script>
@endsection
