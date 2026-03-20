@extends('partials.layouts.master-employee')
@section('title', 'My Cases — WinCase Staff')
@section('page-title', 'My Cases')

@section('content')
<!-- Stats -->
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="card emp-stat-card">
            <div class="card-body text-center py-3">
                <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-2" style="width:42px;height:42px;background:rgba(1,94,167,.1);">
                    <i class="ri-folder-open-line text-success fs-5"></i>
                </div>
                <h4 class="mb-0 fw-bold">12</h4>
                <small class="text-muted">Active Cases</small>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card emp-stat-card">
            <div class="card-body text-center py-3">
                <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-2" style="width:42px;height:42px;background:rgba(255,193,7,.1);">
                    <i class="ri-time-line text-warning fs-5"></i>
                </div>
                <h4 class="mb-0 fw-bold">3</h4>
                <small class="text-muted">Awaiting Decision</small>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card emp-stat-card">
            <div class="card-body text-center py-3">
                <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-2" style="width:42px;height:42px;background:rgba(13,202,240,.1);">
                    <i class="ri-fingerprint-line text-info fs-5"></i>
                </div>
                <h4 class="mb-0 fw-bold">2</h4>
                <small class="text-muted">Fingerprints Pending</small>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card emp-stat-card">
            <div class="card-body text-center py-3">
                <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-2" style="width:42px;height:42px;background:rgba(25,135,84,.1);">
                    <i class="ri-check-double-line text-success fs-5"></i>
                </div>
                <h4 class="mb-0 fw-bold">28</h4>
                <small class="text-muted">Completed (Total)</small>
            </div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="card mb-4">
    <div class="card-body py-2">
        <div class="d-flex flex-wrap align-items-center gap-2">
            <div class="input-group input-group-sm" style="max-width:240px;">
                <span class="input-group-text"><i class="ri-search-line"></i></span>
                <input type="text" class="form-control" placeholder="Search cases..." id="caseSearch">
            </div>
            <div class="d-flex flex-wrap gap-1 ms-auto" id="stageFilter">
                <button class="btn btn-sm btn-success" data-filter="all">All <span class="badge bg-white text-success">12</span></button>
                <button class="btn btn-sm btn-outline-secondary" data-filter="submitted">Submitted</button>
                <button class="btn btn-sm btn-outline-secondary" data-filter="fingerprints">Fingerprints</button>
                <button class="btn btn-sm btn-outline-secondary" data-filter="awaiting">Awaiting Decision</button>
                <button class="btn btn-sm btn-outline-secondary" data-filter="completed">Completed</button>
            </div>
        </div>
    </div>
</div>

<!-- Cases Cards (Kanban-style) -->
<div class="row g-3" id="casesGrid">
    <!-- Case 1 -->
    <div class="col-md-6 col-xl-4 case-item" data-stage="awaiting" data-name="Olena Kovalenko">
        <div class="card emp-stat-card h-100" style="cursor:pointer;" data-bs-toggle="modal" data-bs-target="#caseModal"
             data-case="WC-2026-0847" data-client="Olena Kovalenko" data-service="Temporary Residence Permit" data-stage="Awaiting Decision" data-nat="🇺🇦 Ukrainian">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <span class="badge bg-dark-subtle text-dark fw-semibold">#WC-2026-0847</span>
                    <span class="badge bg-warning text-dark">Awaiting Decision</span>
                </div>
                <div class="d-flex align-items-center gap-2 mb-3">
                    <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width:36px;height:36px;background:rgba(1,94,167,.15);color:#015EA7;font-weight:700;font-size:.8rem;">OK</div>
                    <div>
                        <div class="fw-semibold" style="font-size:.9rem;">Olena Kovalenko</div>
                        <small class="text-muted">🇺🇦 Ukrainian • Temporary Residence Permit</small>
                    </div>
                </div>
                <div class="d-flex gap-4 mb-3" style="font-size:.8rem;">
                    <div><i class="ri-calendar-line text-muted me-1"></i>Filed: 15 Jan 2026</div>
                    <div><i class="ri-time-line text-warning me-1"></i>Day 47 / ~90</div>
                </div>
                <!-- Mini progress -->
                <div class="d-flex gap-1 mb-2">
                    <div class="flex-fill rounded" style="height:4px;background:#015EA7;"></div>
                    <div class="flex-fill rounded" style="height:4px;background:#015EA7;"></div>
                    <div class="flex-fill rounded" style="height:4px;background:#015EA7;"></div>
                    <div class="flex-fill rounded" style="height:4px;background:#015EA7;"></div>
                    <div class="flex-fill rounded" style="height:4px;background:#ffc107;"></div>
                    <div class="flex-fill rounded" style="height:4px;background:#dee2e6;"></div>
                    <div class="flex-fill rounded" style="height:4px;background:#dee2e6;"></div>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <small class="text-muted">5/7 stages</small>
                    <small class="text-danger fw-semibold"><i class="ri-error-warning-line me-1"></i>Next: Check decision status</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Case 2 -->
    <div class="col-md-6 col-xl-4 case-item" data-stage="fingerprints" data-name="Dmytro Bondarenko">
        <div class="card emp-stat-card h-100" style="cursor:pointer;">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <span class="badge bg-dark-subtle text-dark fw-semibold">#WC-2026-0851</span>
                    <span class="badge bg-info text-white">Fingerprint Appointment</span>
                </div>
                <div class="d-flex align-items-center gap-2 mb-3">
                    <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width:36px;height:36px;background:rgba(13,110,253,.15);color:#0d6efd;font-weight:700;font-size:.8rem;">DB</div>
                    <div>
                        <div class="fw-semibold" style="font-size:.9rem;">Dmytro Bondarenko</div>
                        <small class="text-muted">🇺🇦 Ukrainian • Work Permit</small>
                    </div>
                </div>
                <div class="d-flex gap-4 mb-3" style="font-size:.8rem;">
                    <div><i class="ri-calendar-line text-muted me-1"></i>Filed: 1 Feb 2026</div>
                    <div><i class="ri-time-line text-info me-1"></i>Day 30</div>
                </div>
                <div class="d-flex gap-1 mb-2">
                    <div class="flex-fill rounded" style="height:4px;background:#015EA7;"></div>
                    <div class="flex-fill rounded" style="height:4px;background:#015EA7;"></div>
                    <div class="flex-fill rounded" style="height:4px;background:#ffc107;"></div>
                    <div class="flex-fill rounded" style="height:4px;background:#dee2e6;"></div>
                    <div class="flex-fill rounded" style="height:4px;background:#dee2e6;"></div>
                    <div class="flex-fill rounded" style="height:4px;background:#dee2e6;"></div>
                    <div class="flex-fill rounded" style="height:4px;background:#dee2e6;"></div>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <small class="text-muted">3/7 stages</small>
                    <small class="text-warning fw-semibold"><i class="ri-fingerprint-line me-1"></i>Appointment: 5 Mar 2026</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Case 3 -->
    <div class="col-md-6 col-xl-4 case-item" data-stage="submitted" data-name="Rahul Sharma">
        <div class="card emp-stat-card h-100" style="cursor:pointer;">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <span class="badge bg-dark-subtle text-dark fw-semibold">#WC-2026-0862</span>
                    <span class="badge bg-secondary">Submitted</span>
                </div>
                <div class="d-flex align-items-center gap-2 mb-3">
                    <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width:36px;height:36px;background:rgba(255,87,34,.15);color:#ff5722;font-weight:700;font-size:.8rem;">RS</div>
                    <div>
                        <div class="fw-semibold" style="font-size:.9rem;">Rahul Sharma</div>
                        <small class="text-muted">🇮🇳 Indian • Temporary Residence Permit</small>
                    </div>
                </div>
                <div class="d-flex gap-4 mb-3" style="font-size:.8rem;">
                    <div><i class="ri-calendar-line text-muted me-1"></i>Filed: 25 Feb 2026</div>
                    <div><i class="ri-time-line text-muted me-1"></i>Day 5</div>
                </div>
                <div class="d-flex gap-1 mb-2">
                    <div class="flex-fill rounded" style="height:4px;background:#ffc107;"></div>
                    <div class="flex-fill rounded" style="height:4px;background:#dee2e6;"></div>
                    <div class="flex-fill rounded" style="height:4px;background:#dee2e6;"></div>
                    <div class="flex-fill rounded" style="height:4px;background:#dee2e6;"></div>
                    <div class="flex-fill rounded" style="height:4px;background:#dee2e6;"></div>
                    <div class="flex-fill rounded" style="height:4px;background:#dee2e6;"></div>
                    <div class="flex-fill rounded" style="height:4px;background:#dee2e6;"></div>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <small class="text-muted">1/7 stages</small>
                    <small class="text-primary fw-semibold"><i class="ri-file-list-line me-1"></i>Collect missing documents</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Case 4 -->
    <div class="col-md-6 col-xl-4 case-item" data-stage="awaiting" data-name="Irina Kozlova">
        <div class="card emp-stat-card h-100" style="cursor:pointer;">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <span class="badge bg-dark-subtle text-dark fw-semibold">#WC-2026-0839</span>
                    <span class="badge bg-warning text-dark">Awaiting Decision</span>
                </div>
                <div class="d-flex align-items-center gap-2 mb-3">
                    <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width:36px;height:36px;background:rgba(156,39,176,.15);color:#9c27b0;font-weight:700;font-size:.8rem;">IK</div>
                    <div>
                        <div class="fw-semibold" style="font-size:.9rem;">Irina Kozlova</div>
                        <small class="text-muted">🇧🇾 Belarusian • Permanent Residence</small>
                    </div>
                </div>
                <div class="d-flex gap-4 mb-3" style="font-size:.8rem;">
                    <div><i class="ri-calendar-line text-muted me-1"></i>Filed: 10 Dec 2025</div>
                    <div><i class="ri-time-line text-warning me-1"></i>Day 83 / ~90</div>
                </div>
                <div class="d-flex gap-1 mb-2">
                    <div class="flex-fill rounded" style="height:4px;background:#015EA7;"></div>
                    <div class="flex-fill rounded" style="height:4px;background:#015EA7;"></div>
                    <div class="flex-fill rounded" style="height:4px;background:#015EA7;"></div>
                    <div class="flex-fill rounded" style="height:4px;background:#015EA7;"></div>
                    <div class="flex-fill rounded" style="height:4px;background:#ffc107;"></div>
                    <div class="flex-fill rounded" style="height:4px;background:#dee2e6;"></div>
                    <div class="flex-fill rounded" style="height:4px;background:#dee2e6;"></div>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <small class="text-muted">5/7 stages</small>
                    <small class="text-danger fw-semibold"><i class="ri-alarm-warning-line me-1"></i>Near deadline! Check status</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Case 5 -->
    <div class="col-md-6 col-xl-4 case-item" data-stage="fingerprints" data-name="Mehmet Yilmaz">
        <div class="card emp-stat-card h-100" style="cursor:pointer;">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <span class="badge bg-dark-subtle text-dark fw-semibold">#WC-2026-0858</span>
                    <span class="badge bg-info text-white">Awaiting Fingerprints</span>
                </div>
                <div class="d-flex align-items-center gap-2 mb-3">
                    <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width:36px;height:36px;background:rgba(233,30,99,.15);color:#e91e63;font-weight:700;font-size:.8rem;">MY</div>
                    <div>
                        <div class="fw-semibold" style="font-size:.9rem;">Mehmet Yilmaz</div>
                        <small class="text-muted">🇹🇷 Turkish • Temporary Residence Permit</small>
                    </div>
                </div>
                <div class="d-flex gap-4 mb-3" style="font-size:.8rem;">
                    <div><i class="ri-calendar-line text-muted me-1"></i>Filed: 15 Feb 2026</div>
                    <div><i class="ri-time-line text-info me-1"></i>Day 15</div>
                </div>
                <div class="d-flex gap-1 mb-2">
                    <div class="flex-fill rounded" style="height:4px;background:#015EA7;"></div>
                    <div class="flex-fill rounded" style="height:4px;background:#ffc107;"></div>
                    <div class="flex-fill rounded" style="height:4px;background:#dee2e6;"></div>
                    <div class="flex-fill rounded" style="height:4px;background:#dee2e6;"></div>
                    <div class="flex-fill rounded" style="height:4px;background:#dee2e6;"></div>
                    <div class="flex-fill rounded" style="height:4px;background:#dee2e6;"></div>
                    <div class="flex-fill rounded" style="height:4px;background:#dee2e6;"></div>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <small class="text-muted">2/7 stages</small>
                    <small class="text-info fw-semibold"><i class="ri-calendar-todo-line me-1"></i>Schedule fingerprint</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Case 6 -->
    <div class="col-md-6 col-xl-4 case-item" data-stage="completed" data-name="Nguyen Van Minh">
        <div class="card emp-stat-card h-100" style="cursor:pointer;opacity:.85;">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <span class="badge bg-dark-subtle text-dark fw-semibold">#WC-2025-0798</span>
                    <span class="badge bg-success">Card Issued ✓</span>
                </div>
                <div class="d-flex align-items-center gap-2 mb-3">
                    <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width:36px;height:36px;background:rgba(76,175,80,.15);color:#4caf50;font-weight:700;font-size:.8rem;">NV</div>
                    <div>
                        <div class="fw-semibold" style="font-size:.9rem;">Nguyen Van Minh</div>
                        <small class="text-muted">🇻🇳 Vietnamese • Work Permit</small>
                    </div>
                </div>
                <div class="d-flex gap-4 mb-3" style="font-size:.8rem;">
                    <div><i class="ri-calendar-line text-muted me-1"></i>Filed: 1 Oct 2025</div>
                    <div><i class="ri-check-line text-success me-1"></i>Completed: 20 Feb 2026</div>
                </div>
                <div class="d-flex gap-1 mb-2">
                    <div class="flex-fill rounded" style="height:4px;background:#015EA7;"></div>
                    <div class="flex-fill rounded" style="height:4px;background:#015EA7;"></div>
                    <div class="flex-fill rounded" style="height:4px;background:#015EA7;"></div>
                    <div class="flex-fill rounded" style="height:4px;background:#015EA7;"></div>
                    <div class="flex-fill rounded" style="height:4px;background:#015EA7;"></div>
                    <div class="flex-fill rounded" style="height:4px;background:#015EA7;"></div>
                    <div class="flex-fill rounded" style="height:4px;background:#015EA7;"></div>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <small class="text-success">7/7 stages — Completed</small>
                    <small class="text-muted">143 days total</small>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Case Detail Modal -->
<div class="modal fade" id="caseModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="ri-folder-open-line me-2"></i>Case <span id="cm_case"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <ul class="nav nav-tabs mb-3" role="tablist">
                    <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab" href="#cmOverview">Overview</a></li>
                    <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#cmTimeline">Timeline</a></li>
                    <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#cmDocs">Documents</a></li>
                    <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#cmNotes">Notes</a></li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane fade show active" id="cmOverview">
                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="fw-semibold mb-3"><i class="ri-user-line me-1"></i>Client</h6>
                                <table class="table table-sm table-borderless">
                                    <tr><td class="text-muted" style="width:120px;">Name</td><td class="fw-semibold" id="cm_client"></td></tr>
                                    <tr><td class="text-muted">Nationality</td><td id="cm_nat"></td></tr>
                                    <tr><td class="text-muted">Phone</td><td>+48 579 123 456</td></tr>
                                    <tr><td class="text-muted">Email</td><td>client@email.com</td></tr>
                                    <tr><td class="text-muted">PESEL</td><td>02271012345</td></tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <h6 class="fw-semibold mb-3"><i class="ri-folder-line me-1"></i>Case Details</h6>
                                <table class="table table-sm table-borderless">
                                    <tr><td class="text-muted" style="width:120px;">Service</td><td class="fw-semibold" id="cm_service"></td></tr>
                                    <tr><td class="text-muted">Stage</td><td><span class="badge bg-warning text-dark" id="cm_stage"></span></td></tr>
                                    <tr><td class="text-muted">Filed</td><td>15 January 2026</td></tr>
                                    <tr><td class="text-muted">Voivodeship</td><td>Mazowieckie</td></tr>
                                    <tr><td class="text-muted">Assigned</td><td>Anya Petrova</td></tr>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="cmTimeline">
                        <div class="case-timeline">
                            <div class="tl-item done"><div class="tl-dot"></div><div class="fw-semibold" style="font-size:.85rem;">Submitted</div><small class="text-muted">15 Jan 2026 — Documents submitted to Urząd</small></div>
                            <div class="tl-item done"><div class="tl-dot"></div><div class="fw-semibold" style="font-size:.85rem;">Awaiting Fingerprints</div><small class="text-muted">20 Jan 2026 — Invitation letter received</small></div>
                            <div class="tl-item done"><div class="tl-dot"></div><div class="fw-semibold" style="font-size:.85rem;">Fingerprint Appointment</div><small class="text-muted">5 Feb 2026 — Fingerprints taken at Urząd</small></div>
                            <div class="tl-item done"><div class="tl-dot"></div><div class="fw-semibold" style="font-size:.85rem;">Fingerprints Submitted</div><small class="text-muted">5 Feb 2026 — Confirmed by office</small></div>
                            <div class="tl-item current"><div class="tl-dot"></div><div class="fw-semibold" style="font-size:.85rem;">Awaiting Decision</div><small class="text-muted">In progress — estimated 4-6 weeks</small></div>
                            <div class="tl-item"><div class="tl-dot"></div><div class="fw-semibold text-muted" style="font-size:.85rem;">Decision Signed</div><small class="text-muted">—</small></div>
                            <div class="tl-item"><div class="tl-dot"></div><div class="fw-semibold text-muted" style="font-size:.85rem;">Card Issued</div><small class="text-muted">—</small></div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="cmDocs">
                        <div class="list-group list-group-flush">
                            <div class="list-group-item d-flex align-items-center"><i class="ri-file-text-line text-success me-2"></i>Passport Copy<span class="badge bg-success ms-auto">Approved</span></div>
                            <div class="list-group-item d-flex align-items-center"><i class="ri-file-text-line text-success me-2"></i>Health Insurance<span class="badge bg-success ms-auto">Approved</span></div>
                            <div class="list-group-item d-flex align-items-center"><i class="ri-file-text-line text-success me-2"></i>Rental Agreement<span class="badge bg-success ms-auto">Approved</span></div>
                            <div class="list-group-item d-flex align-items-center"><i class="ri-file-text-line text-info me-2"></i>Bank Statement (Feb)<span class="badge bg-info ms-auto">Uploaded</span></div>
                            <div class="list-group-item d-flex align-items-center"><i class="ri-file-text-line text-warning me-2"></i>Bank Statement (Mar)<span class="badge bg-warning text-dark ms-auto">Required</span></div>
                            <div class="list-group-item d-flex align-items-center"><i class="ri-file-text-line text-success me-2"></i>Photo 3.5x4.5<span class="badge bg-success ms-auto">Approved</span></div>
                            <div class="list-group-item d-flex align-items-center"><i class="ri-file-text-line text-danger me-2"></i>Employment Certificate<span class="badge bg-danger ms-auto">Missing</span></div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="cmNotes">
                        <div class="mb-3 p-3 bg-light rounded" style="font-size:.85rem;">
                            <div class="d-flex justify-content-between mb-1"><strong>Anya Petrova</strong><small class="text-muted">28 Feb 2026</small></div>
                            Called Urząd — they confirmed case is under review. Expected decision within 2-3 weeks.
                        </div>
                        <div class="mb-3 p-3 bg-light rounded" style="font-size:.85rem;">
                            <div class="d-flex justify-content-between mb-1"><strong>Anya Petrova</strong><small class="text-muted">5 Feb 2026</small></div>
                            Client completed fingerprints successfully. All biometric data submitted.
                        </div>
                        <hr>
                        <div class="d-flex gap-2">
                            <textarea class="form-control form-control-sm" rows="2" placeholder="Add internal note..."></textarea>
                            <button class="btn btn-success btn-sm align-self-end"><i class="ri-send-plane-line"></i></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
(function(){
    // Filter
    document.querySelectorAll('#stageFilter button').forEach(function(btn){
        btn.addEventListener('click', function(){
            document.querySelectorAll('#stageFilter button').forEach(function(b){ b.className = 'btn btn-sm btn-outline-secondary'; });
            this.className = 'btn btn-sm btn-success';
            var f = this.dataset.filter;
            document.querySelectorAll('.case-item').forEach(function(c){
                c.style.display = (f === 'all' || c.dataset.stage === f) ? '' : 'none';
            });
        });
    });
    // Search
    document.getElementById('caseSearch').addEventListener('input', function(){
        var q = this.value.toLowerCase();
        document.querySelectorAll('.case-item').forEach(function(c){
            c.style.display = c.dataset.name.toLowerCase().includes(q) ? '' : 'none';
        });
    });
    // Modal populate
    document.getElementById('caseModal').addEventListener('show.bs.modal', function(e){
        var t = e.relatedTarget;
        if(!t) return;
        document.getElementById('cm_case').textContent = t.dataset.case || '';
        document.getElementById('cm_client').textContent = t.dataset.client || '';
        document.getElementById('cm_service').textContent = t.dataset.service || '';
        document.getElementById('cm_stage').textContent = t.dataset.stage || '';
        document.getElementById('cm_nat').textContent = t.dataset.nat || '';
    });
})();
</script>
@endsection
