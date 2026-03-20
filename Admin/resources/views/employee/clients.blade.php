@extends('partials.layouts.master-employee')
@section('title', 'My Clients — WinCase Staff')
@section('page-title', 'My Clients')

@section('css')
<style>
    .client-card { transition: box-shadow .2s, transform .15s; cursor: pointer; }
    .client-card:hover { box-shadow: 0 4px 15px rgba(1,94,167,.12); transform: translateY(-2px); }
    .client-avatar { width: 48px; height: 48px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: .85rem; flex-shrink: 0; }
    .status-dot { width: 8px; height: 8px; border-radius: 50%; display: inline-block; }
</style>
@endsection

@section('content')
<!-- Filters -->
<div class="row mb-3">
    <div class="col-12">
        <div class="card">
            <div class="card-body py-2">
                <div class="d-flex flex-wrap align-items-center gap-3">
                    <div class="d-flex align-items-center gap-2">
                        <span class="fw-semibold text-muted" style="font-size:.8rem;">Filter:</span>
                        <button class="btn btn-sm btn-success active" data-filter="all">All (12)</button>
                        <button class="btn btn-sm btn-outline-secondary" data-filter="active">Active (8)</button>
                        <button class="btn btn-sm btn-outline-secondary" data-filter="pending">Pending (3)</button>
                        <button class="btn btn-sm btn-outline-secondary" data-filter="completed">Completed (1)</button>
                    </div>
                    <div class="ms-auto">
                        <input type="text" class="form-control form-control-sm" placeholder="Search clients..." style="width:200px;" id="clientSearch">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Client Cards -->
<div class="row g-3" id="clientGrid">
    <!-- Client 1 -->
    <div class="col-md-6 col-xl-4" data-status="active">
        <div class="card client-card" onclick="showClient('olena')">
            <div class="card-body">
                <div class="d-flex align-items-start gap-3">
                    <div class="client-avatar" style="background:rgba(1,94,167,.15);color:#015EA7;">OK</div>
                    <div class="flex-grow-1">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h6 class="mb-0">Olena Kovalenko</h6>
                                <small class="text-muted">Ukraine &bull; Age 28</small>
                            </div>
                            <span class="badge bg-success-subtle text-success">Active</span>
                        </div>
                        <div class="mt-2">
                            <small class="text-muted d-block"><i class="ri-folder-line me-1"></i>Case #WC-2026-0847</small>
                            <small class="text-muted d-block"><i class="ri-file-text-line me-1"></i>Temporary Residence Permit</small>
                            <small class="text-muted d-block"><i class="ri-calendar-line me-1"></i>Deadline: Mar 5, 2026</small>
                        </div>
                        <div class="mt-2">
                            <div class="progress" style="height:4px;">
                                <div class="progress-bar bg-success" style="width:57%"></div>
                            </div>
                            <small class="text-muted" style="font-size:.65rem;">4/7 stages — Awaiting Decision</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Client 2 -->
    <div class="col-md-6 col-xl-4" data-status="active">
        <div class="card client-card" onclick="showClient('dmytro')">
            <div class="card-body">
                <div class="d-flex align-items-start gap-3">
                    <div class="client-avatar" style="background:rgba(13,110,253,.15);color:#0d6efd;">DB</div>
                    <div class="flex-grow-1">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h6 class="mb-0">Dmytro Bondarenko</h6>
                                <small class="text-muted">Ukraine &bull; Age 35</small>
                            </div>
                            <span class="badge bg-warning-subtle text-warning">Fingerprints</span>
                        </div>
                        <div class="mt-2">
                            <small class="text-muted d-block"><i class="ri-folder-line me-1"></i>Case #WC-2026-0812</small>
                            <small class="text-muted d-block"><i class="ri-file-text-line me-1"></i>Work Permit</small>
                            <small class="text-muted d-block"><i class="ri-calendar-line me-1"></i>Fingerprint: Mar 8, 2026</small>
                        </div>
                        <div class="mt-2">
                            <div class="progress" style="height:4px;">
                                <div class="progress-bar bg-warning" style="width:28%"></div>
                            </div>
                            <small class="text-muted" style="font-size:.65rem;">2/7 stages — Fingerprint Appointment</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Client 3 -->
    <div class="col-md-6 col-xl-4" data-status="active">
        <div class="card client-card" onclick="showClient('rahul')">
            <div class="card-body">
                <div class="d-flex align-items-start gap-3">
                    <div class="client-avatar" style="background:rgba(255,87,34,.15);color:#ff5722;">RS</div>
                    <div class="flex-grow-1">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h6 class="mb-0">Rahul Sharma</h6>
                                <small class="text-muted">India &bull; Age 31</small>
                            </div>
                            <span class="badge bg-info-subtle text-info">Awaiting</span>
                        </div>
                        <div class="mt-2">
                            <small class="text-muted d-block"><i class="ri-folder-line me-1"></i>Case #WC-2026-0831</small>
                            <small class="text-muted d-block"><i class="ri-file-text-line me-1"></i>Temporary Residence Permit</small>
                            <small class="text-muted d-block"><i class="ri-calendar-line me-1"></i>Decision expected: Mar 10</small>
                        </div>
                        <div class="mt-2">
                            <div class="progress" style="height:4px;">
                                <div class="progress-bar bg-info" style="width:71%"></div>
                            </div>
                            <small class="text-muted" style="font-size:.65rem;">5/7 stages — Awaiting Decision</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Client 4 -->
    <div class="col-md-6 col-xl-4" data-status="active">
        <div class="card client-card" onclick="showClient('irina')">
            <div class="card-body">
                <div class="d-flex align-items-start gap-3">
                    <div class="client-avatar" style="background:rgba(156,39,176,.15);color:#9c27b0;">IK</div>
                    <div class="flex-grow-1">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h6 class="mb-0">Irina Kozlova</h6>
                                <small class="text-muted">Russia &bull; Age 42</small>
                            </div>
                            <span class="badge bg-success-subtle text-success">Active</span>
                        </div>
                        <div class="mt-2">
                            <small class="text-muted d-block"><i class="ri-folder-line me-1"></i>Case #WC-2026-0798</small>
                            <small class="text-muted d-block"><i class="ri-file-text-line me-1"></i>Permanent Residence</small>
                            <small class="text-muted d-block"><i class="ri-calendar-line me-1"></i>Submitted: Feb 10, 2026</small>
                        </div>
                        <div class="mt-2">
                            <div class="progress" style="height:4px;">
                                <div class="progress-bar bg-success" style="width:43%"></div>
                            </div>
                            <small class="text-muted" style="font-size:.65rem;">3/7 stages — Fingerprints Submitted</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Client 5 -->
    <div class="col-md-6 col-xl-4" data-status="active">
        <div class="card client-card" onclick="showClient('mehmet')">
            <div class="card-body">
                <div class="d-flex align-items-start gap-3">
                    <div class="client-avatar" style="background:rgba(233,30,99,.15);color:#e91e63;">MY</div>
                    <div class="flex-grow-1">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h6 class="mb-0">Mehmet Yilmaz</h6>
                                <small class="text-muted">Turkey &bull; Age 29</small>
                            </div>
                            <span class="badge bg-primary-subtle text-primary">Submitted</span>
                        </div>
                        <div class="mt-2">
                            <small class="text-muted d-block"><i class="ri-folder-line me-1"></i>Case #WC-2026-0855</small>
                            <small class="text-muted d-block"><i class="ri-file-text-line me-1"></i>Work Permit Extension</small>
                            <small class="text-muted d-block"><i class="ri-calendar-line me-1"></i>Submitted: Feb 28, 2026</small>
                        </div>
                        <div class="mt-2">
                            <div class="progress" style="height:4px;">
                                <div class="progress-bar bg-primary" style="width:14%"></div>
                            </div>
                            <small class="text-muted" style="font-size:.65rem;">1/7 stages — Submitted</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Client 6 -->
    <div class="col-md-6 col-xl-4" data-status="active">
        <div class="card client-card" onclick="showClient('anna')">
            <div class="card-body">
                <div class="d-flex align-items-start gap-3">
                    <div class="client-avatar" style="background:rgba(0,150,136,.15);color:#009688;">AP</div>
                    <div class="flex-grow-1">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h6 class="mb-0">Anna Petrova</h6>
                                <small class="text-muted">Belarus &bull; Age 26</small>
                            </div>
                            <span class="badge bg-warning-subtle text-warning">Fingerprints</span>
                        </div>
                        <div class="mt-2">
                            <small class="text-muted d-block"><i class="ri-folder-line me-1"></i>Case #WC-2026-0862</small>
                            <small class="text-muted d-block"><i class="ri-file-text-line me-1"></i>Student Visa</small>
                            <small class="text-muted d-block"><i class="ri-calendar-line me-1"></i>Appointment: Mar 12, 2026</small>
                        </div>
                        <div class="mt-2">
                            <div class="progress" style="height:4px;">
                                <div class="progress-bar bg-warning" style="width:28%"></div>
                            </div>
                            <small class="text-muted" style="font-size:.65rem;">2/7 stages — Awaiting Fingerprints</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Client 7 -->
    <div class="col-md-6 col-xl-4" data-status="pending">
        <div class="card client-card" onclick="showClient('chen')">
            <div class="card-body">
                <div class="d-flex align-items-start gap-3">
                    <div class="client-avatar" style="background:rgba(121,85,72,.15);color:#795548;">CW</div>
                    <div class="flex-grow-1">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h6 class="mb-0">Chen Wei</h6>
                                <small class="text-muted">China &bull; Age 33</small>
                            </div>
                            <span class="badge bg-secondary-subtle text-secondary">Pending Docs</span>
                        </div>
                        <div class="mt-2">
                            <small class="text-muted d-block"><i class="ri-folder-line me-1"></i>Case #WC-2026-0870</small>
                            <small class="text-muted d-block"><i class="ri-file-text-line me-1"></i>Business Permit</small>
                            <small class="text-muted d-block"><i class="ri-alert-line me-1 text-warning"></i>Missing: 2 documents</small>
                        </div>
                        <div class="mt-2">
                            <div class="progress" style="height:4px;">
                                <div class="progress-bar bg-secondary" style="width:7%"></div>
                            </div>
                            <small class="text-muted" style="font-size:.65rem;">Awaiting documents</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Client 8 -->
    <div class="col-md-6 col-xl-4" data-status="pending">
        <div class="card client-card" onclick="showClient('maria')">
            <div class="card-body">
                <div class="d-flex align-items-start gap-3">
                    <div class="client-avatar" style="background:rgba(255,152,0,.15);color:#ff9800;">MG</div>
                    <div class="flex-grow-1">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h6 class="mb-0">Maria Garcia</h6>
                                <small class="text-muted">Spain &bull; Age 27</small>
                            </div>
                            <span class="badge bg-secondary-subtle text-secondary">Pending Docs</span>
                        </div>
                        <div class="mt-2">
                            <small class="text-muted d-block"><i class="ri-folder-line me-1"></i>Case #WC-2026-0878</small>
                            <small class="text-muted d-block"><i class="ri-file-text-line me-1"></i>EU Registration</small>
                            <small class="text-muted d-block"><i class="ri-alert-line me-1 text-warning"></i>Missing: insurance doc</small>
                        </div>
                        <div class="mt-2">
                            <div class="progress" style="height:4px;">
                                <div class="progress-bar bg-secondary" style="width:7%"></div>
                            </div>
                            <small class="text-muted" style="font-size:.65rem;">Awaiting documents</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Client 9 -->
    <div class="col-md-6 col-xl-4" data-status="active">
        <div class="card client-card" onclick="showClient('viktor')">
            <div class="card-body">
                <div class="d-flex align-items-start gap-3">
                    <div class="client-avatar" style="background:rgba(63,81,181,.15);color:#3f51b5;">VM</div>
                    <div class="flex-grow-1">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h6 class="mb-0">Viktor Morozov</h6>
                                <small class="text-muted">Ukraine &bull; Age 45</small>
                            </div>
                            <span class="badge bg-purple-subtle text-purple" style="--bs-purple:#7c3aed;background:rgba(124,58,237,.1);color:#7c3aed;">Decision</span>
                        </div>
                        <div class="mt-2">
                            <small class="text-muted d-block"><i class="ri-folder-line me-1"></i>Case #WC-2026-0790</small>
                            <small class="text-muted d-block"><i class="ri-file-text-line me-1"></i>Permanent Residence</small>
                            <small class="text-muted d-block"><i class="ri-calendar-line me-1"></i>Decision signed: Feb 25</small>
                        </div>
                        <div class="mt-2">
                            <div class="progress" style="height:4px;">
                                <div class="progress-bar bg-success" style="width:86%"></div>
                            </div>
                            <small class="text-muted" style="font-size:.65rem;">6/7 stages — Decision Signed</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Client 10 -->
    <div class="col-md-6 col-xl-4" data-status="pending">
        <div class="card client-card" onclick="showClient('fatima')">
            <div class="card-body">
                <div class="d-flex align-items-start gap-3">
                    <div class="client-avatar" style="background:rgba(244,67,54,.15);color:#f44336;">FA</div>
                    <div class="flex-grow-1">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h6 class="mb-0">Fatima Al-Hassan</h6>
                                <small class="text-muted">Syria &bull; Age 30</small>
                            </div>
                            <span class="badge bg-secondary-subtle text-secondary">New</span>
                        </div>
                        <div class="mt-2">
                            <small class="text-muted d-block"><i class="ri-folder-line me-1"></i>Case #WC-2026-0885</small>
                            <small class="text-muted d-block"><i class="ri-file-text-line me-1"></i>Refugee Status</small>
                            <small class="text-muted d-block"><i class="ri-time-line me-1"></i>Registered: Mar 1, 2026</small>
                        </div>
                        <div class="mt-2">
                            <div class="progress" style="height:4px;">
                                <div class="progress-bar bg-secondary" style="width:3%"></div>
                            </div>
                            <small class="text-muted" style="font-size:.65rem;">Initial review</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Client 11 -->
    <div class="col-md-6 col-xl-4" data-status="active">
        <div class="card client-card" onclick="showClient('pawel')">
            <div class="card-body">
                <div class="d-flex align-items-start gap-3">
                    <div class="client-avatar" style="background:rgba(76,175,80,.15);color:#4caf50;">PK</div>
                    <div class="flex-grow-1">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h6 class="mb-0">Pavlo Kravchenko</h6>
                                <small class="text-muted">Ukraine &bull; Age 38</small>
                            </div>
                            <span class="badge bg-success-subtle text-success">Active</span>
                        </div>
                        <div class="mt-2">
                            <small class="text-muted d-block"><i class="ri-folder-line me-1"></i>Case #WC-2025-0722</small>
                            <small class="text-muted d-block"><i class="ri-file-text-line me-1"></i>Work Permit Renewal</small>
                            <small class="text-muted d-block"><i class="ri-calendar-line me-1"></i>Awaiting decision</small>
                        </div>
                        <div class="mt-2">
                            <div class="progress" style="height:4px;">
                                <div class="progress-bar bg-success" style="width:57%"></div>
                            </div>
                            <small class="text-muted" style="font-size:.65rem;">4/7 stages — Awaiting Decision</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Client 12 -->
    <div class="col-md-6 col-xl-4" data-status="completed">
        <div class="card client-card" onclick="showClient('ahmed')">
            <div class="card-body">
                <div class="d-flex align-items-start gap-3">
                    <div class="client-avatar" style="background:rgba(96,125,139,.15);color:#607d8b;">AH</div>
                    <div class="flex-grow-1">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h6 class="mb-0">Ahmed Hassan</h6>
                                <small class="text-muted">Egypt &bull; Age 40</small>
                            </div>
                            <span class="badge bg-success">Completed</span>
                        </div>
                        <div class="mt-2">
                            <small class="text-muted d-block"><i class="ri-folder-line me-1"></i>Case #WC-2025-0698</small>
                            <small class="text-muted d-block"><i class="ri-file-text-line me-1"></i>Temporary Residence Permit</small>
                            <small class="text-muted d-block"><i class="ri-check-line me-1 text-success"></i>Card issued: Feb 20, 2026</small>
                        </div>
                        <div class="mt-2">
                            <div class="progress" style="height:4px;">
                                <div class="progress-bar bg-success" style="width:100%"></div>
                            </div>
                            <small class="text-success" style="font-size:.65rem;">7/7 stages — Card Issued &#10003;</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Client Detail Modal -->
<div class="modal fade" id="clientModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="ri-user-line me-2"></i>Client Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab" href="#clInfo">Info</a></li>
                    <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#clDocs">Documents</a></li>
                    <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#clTimeline">Timeline</a></li>
                </ul>
                <div class="tab-content pt-3">
                    <div class="tab-pane fade show active" id="clInfo">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="text-muted small">Full Name</label>
                                <div class="fw-semibold">Olena Kovalenko</div>
                            </div>
                            <div class="col-md-6">
                                <label class="text-muted small">Nationality</label>
                                <div class="fw-semibold">Ukrainian</div>
                            </div>
                            <div class="col-md-6">
                                <label class="text-muted small">Phone</label>
                                <div class="fw-semibold">+48 512 345 678</div>
                            </div>
                            <div class="col-md-6">
                                <label class="text-muted small">Email</label>
                                <div class="fw-semibold">olena.k@gmail.com</div>
                            </div>
                            <div class="col-md-6">
                                <label class="text-muted small">Case Type</label>
                                <div class="fw-semibold">Temporary Residence Permit</div>
                            </div>
                            <div class="col-md-6">
                                <label class="text-muted small">Case Number</label>
                                <div class="fw-semibold">#WC-2026-0847</div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="clDocs">
                        <div class="list-group">
                            <div class="list-group-item d-flex align-items-center">
                                <i class="ri-file-pdf-line text-danger me-2 fs-5"></i>
                                <div class="flex-grow-1">
                                    <div style="font-size:.85rem;">Passport Copy</div>
                                    <small class="text-muted">Uploaded Feb 15, 2026</small>
                                </div>
                                <span class="badge bg-success">Verified</span>
                            </div>
                            <div class="list-group-item d-flex align-items-center">
                                <i class="ri-file-pdf-line text-danger me-2 fs-5"></i>
                                <div class="flex-grow-1">
                                    <div style="font-size:.85rem;">Bank Statement (Feb)</div>
                                    <small class="text-muted">Uploaded Mar 1, 2026</small>
                                </div>
                                <span class="badge bg-success">Verified</span>
                            </div>
                            <div class="list-group-item d-flex align-items-center">
                                <i class="ri-file-line text-warning me-2 fs-5"></i>
                                <div class="flex-grow-1">
                                    <div style="font-size:.85rem;">Bank Statement (Mar)</div>
                                    <small class="text-muted">Due by Mar 5, 2026</small>
                                </div>
                                <span class="badge bg-warning text-dark">Pending</span>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="clTimeline">
                        <div class="ms-3" style="border-left:2px solid #015EA7;">
                            <div class="ps-3 pb-3 position-relative">
                                <span class="position-absolute bg-success rounded-circle" style="width:10px;height:10px;left:-6px;top:4px;"></span>
                                <small class="text-muted">Feb 10, 2026</small>
                                <div style="font-size:.85rem;">Application submitted to voivodeship office</div>
                            </div>
                            <div class="ps-3 pb-3 position-relative">
                                <span class="position-absolute bg-success rounded-circle" style="width:10px;height:10px;left:-6px;top:4px;"></span>
                                <small class="text-muted">Feb 15, 2026</small>
                                <div style="font-size:.85rem;">All documents uploaded and verified</div>
                            </div>
                            <div class="ps-3 pb-3 position-relative">
                                <span class="position-absolute bg-success rounded-circle" style="width:10px;height:10px;left:-6px;top:4px;"></span>
                                <small class="text-muted">Feb 22, 2026</small>
                                <div style="font-size:.85rem;">Fingerprints completed at office</div>
                            </div>
                            <div class="ps-3 pb-3 position-relative">
                                <span class="position-absolute bg-warning rounded-circle" style="width:10px;height:10px;left:-6px;top:4px;"></span>
                                <small class="text-muted">Present</small>
                                <div style="font-size:.85rem;">Awaiting decision from authorities</div>
                            </div>
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
function showClient(id){ new bootstrap.Modal(document.getElementById('clientModal')).show(); }

document.getElementById('clientSearch').addEventListener('input', function(){
    var q = this.value.toLowerCase();
    document.querySelectorAll('#clientGrid > div').forEach(function(c){
        c.style.display = c.textContent.toLowerCase().includes(q) ? '' : 'none';
    });
});

document.querySelectorAll('[data-filter]').forEach(function(btn){
    btn.addEventListener('click', function(){
        document.querySelectorAll('[data-filter]').forEach(function(b){ b.classList.remove('btn-success','active'); b.classList.add('btn-outline-secondary'); });
        this.classList.remove('btn-outline-secondary'); this.classList.add('btn-success','active');
        var f = this.dataset.filter;
        document.querySelectorAll('#clientGrid > div').forEach(function(c){
            c.style.display = (f === 'all' || c.dataset.status === f) ? '' : 'none';
        });
    });
});
</script>
@endsection
