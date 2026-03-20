@extends('partials.layouts.master-client')
@section('title', 'Dashboard — WinCase Client Portal')

@section('nav')
<div class="d-none d-md-flex align-items-center gap-1">
    <a href="/client-dashboard" class="nav-link active"><i class="ri-dashboard-line me-1"></i><span data-lang="wc-dashboard">Dashboard</span></a>
    <a href="#" class="nav-link" onclick="showSection('documents')"><i class="ri-file-text-line me-1"></i><span data-lang="wc-documents">Documents</span></a>
    <a href="#" class="nav-link" onclick="showSection('messages')"><i class="ri-message-2-line me-1"></i><span data-lang="wc-cp-messages">Messages</span> <span class="badge bg-danger rounded-pill ms-1">2</span></a>
    <a href="#" class="nav-link" onclick="showSection('payments')"><i class="ri-bank-card-line me-1"></i><span data-lang="wc-payments">Payments</span></a>
    <a href="#" class="nav-link" onclick="showSection('profile')"><i class="ri-user-line me-1"></i><span data-lang="wc-profile">Profile</span></a>
</div>
@endsection

@section('nav-right')
<div class="dropdown">
    <button class="btn btn-sm btn-light d-flex align-items-center gap-2" data-bs-toggle="dropdown">
        <div class="rounded-circle bg-success text-white d-flex align-items-center justify-content-center" style="width:30px;height:30px;font-size:.75rem;font-weight:700;">OK</div>
        <span class="d-none d-sm-inline small fw-medium">Olena K.</span>
    </button>
    <ul class="dropdown-menu dropdown-menu-end">
        <li><a class="dropdown-item" href="#" onclick="showSection('profile')"><i class="ri-user-line me-2"></i><span data-lang="wc-cp-my-profile">My Profile</span></a></li>
        <li><a class="dropdown-item" href="#" onclick="showSection('settings')"><i class="ri-settings-3-line me-2"></i><span data-lang="wc-settings">Settings</span></a></li>
        <li><hr class="dropdown-divider"></li>
        <li><a class="dropdown-item text-danger" href="/client-login"><i class="ri-logout-box-line me-2"></i><span data-lang="wc-sign-out">Sign Out</span></a></li>
    </ul>
</div>
@endsection

@section('css')
<style>
    .section-panel { display: none; }
    .section-panel.active { display: block; }
    .doc-icon { width: 42px; height: 42px; border-radius: .5rem; display: flex; align-items: center; justify-content: center; font-size: 1.25rem; flex-shrink: 0; }
    .msg-unread { background: rgba(var(--wc-primary-rgb),.04); border-left: 3px solid var(--wc-primary); }
    .payment-badge { font-size: .7rem; padding: .2em .5em; }
    .mobile-nav { display: none; }
    @media(max-width:767px){ .mobile-nav { display: flex; } }
</style>
@endsection

@section('content')

<!-- Mobile Nav -->
<div class="mobile-nav d-md-none mb-3 gap-1 flex-wrap">
    <button class="btn btn-sm btn-outline-success active mob-nav" data-sec="main"><i class="ri-dashboard-line"></i></button>
    <button class="btn btn-sm btn-outline-secondary mob-nav" data-sec="documents"><i class="ri-file-text-line"></i></button>
    <button class="btn btn-sm btn-outline-secondary mob-nav" data-sec="messages"><i class="ri-message-2-line"></i></button>
    <button class="btn btn-sm btn-outline-secondary mob-nav" data-sec="payments"><i class="ri-bank-card-line"></i></button>
    <button class="btn btn-sm btn-outline-secondary mob-nav" data-sec="profile"><i class="ri-user-line"></i></button>
</div>

<!-- ═══════════ MAIN DASHBOARD ═══════════ -->
<div class="section-panel active" id="sec-main">
    <!-- Welcome -->
    <div class="card border-0 text-white mb-4" style="border-radius:.75rem;background:linear-gradient(135deg, #015EA7 0%, #014D8A 100%);">
        <div class="card-body p-4">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h4 class="fw-bold mb-1"><span data-lang="wc-cp-welcome-back">Welcome back</span>, Olena!</h4>
                    <p class="mb-2 opacity-75" data-lang="wc-cp-welcome-text">Your immigration case is being processed. Here's your latest status.</p>
                    <div class="d-flex gap-2 flex-wrap">
                        <span class="badge bg-white" style="color:#015EA7;"><i class="ri-briefcase-line me-1"></i>Case #WC-2026-0847</span>
                        <span class="badge bg-white bg-opacity-25 text-white" data-lang="wc-cp-temp-residence">Temporary Residence Permit</span>
                    </div>
                </div>
                <div class="col-md-4 text-md-end mt-3 mt-md-0">
                    <div class="d-inline-block text-center">
                        <div style="font-size:2.5rem;font-weight:800;line-height:1;">68%</div>
                        <small class="opacity-75" data-lang="wc-cp-case-progress">Case Progress</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <!-- Status Cards -->
        <div class="col-6 col-lg-3">
            <div class="card stat-card h-100">
                <div class="card-body p-3 text-center">
                    <div class="rounded-circle bg-primary-subtle text-primary mx-auto mb-2 d-flex align-items-center justify-content-center" style="width:44px;height:44px;"><i class="ri-file-list-3-line fs-5"></i></div>
                    <h5 class="fw-bold mb-0">12</h5>
                    <small class="text-muted" data-lang="wc-documents">Documents</small>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card stat-card h-100">
                <div class="card-body p-3 text-center">
                    <div class="rounded-circle bg-warning-subtle text-warning mx-auto mb-2 d-flex align-items-center justify-content-center" style="width:44px;height:44px;"><i class="ri-time-line fs-5"></i></div>
                    <h5 class="fw-bold mb-0">3</h5>
                    <small class="text-muted" data-lang="wc-cp-pending-actions">Pending Actions</small>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card stat-card h-100">
                <div class="card-body p-3 text-center">
                    <div class="rounded-circle bg-success-subtle text-success mx-auto mb-2 d-flex align-items-center justify-content-center" style="width:44px;height:44px;"><i class="ri-message-2-line fs-5"></i></div>
                    <h5 class="fw-bold mb-0">2</h5>
                    <small class="text-muted" data-lang="wc-cp-new-messages">New Messages</small>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card stat-card h-100">
                <div class="card-body p-3 text-center">
                    <div class="rounded-circle bg-info-subtle text-info mx-auto mb-2 d-flex align-items-center justify-content-center" style="width:44px;height:44px;"><i class="ri-calendar-check-line fs-5"></i></div>
                    <h5 class="fw-bold mb-0">14 Mar</h5>
                    <small class="text-muted" data-lang="wc-cp-next-appt">Next Appointment</small>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Case Timeline -->
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm" style="border-radius:.75rem;">
                <div class="card-header bg-transparent border-0 d-flex justify-content-between align-items-center">
                    <h6 class="fw-semibold mb-0"><i class="ri-route-line me-1 text-success"></i><span data-lang="wc-cp-case-progress">Case Progress</span></h6>
                    <span class="badge bg-warning-subtle text-warning">Stage 4 / 7</span>
                </div>
                <div class="card-body">
                    <div class="case-timeline">
                        <div class="tl-item done">
                            <div class="tl-dot"></div>
                            <h6 class="fw-semibold mb-0 small" data-lang="wc-cp-tl-app-submitted">Application Submitted</h6>
                            <small class="text-muted">15 Jan 2026 — Documents package sent to Urzad Wojewodzki</small>
                        </div>
                        <div class="tl-item done">
                            <div class="tl-dot"></div>
                            <h6 class="fw-semibold mb-0 small" data-lang="wc-cp-tl-await-fingerpr">Awaiting Fingerprints</h6>
                            <small class="text-muted">22 Jan 2026 — Notification received, appointment scheduled</small>
                        </div>
                        <div class="tl-item done">
                            <div class="tl-dot"></div>
                            <h6 class="fw-semibold mb-0 small" data-lang="wc-cp-tl-fingerpr-appt">Fingerprint Appointment</h6>
                            <small class="text-muted">5 Feb 2026 — Fingerprints taken at Urzad Wojewodzki</small>
                        </div>
                        <div class="tl-item current">
                            <div class="tl-dot"></div>
                            <h6 class="fw-semibold mb-0 small" data-lang="wc-cp-tl-fingerpr-done">Fingerprints Submitted</h6>
                            <small class="text-muted">5 Feb 2026 — Biometric data recorded, case under review</small>
                        </div>
                        <div class="tl-item">
                            <div class="tl-dot"></div>
                            <h6 class="fw-semibold mb-0 small text-muted" data-lang="wc-cp-tl-await-decision">Awaiting Decision</h6>
                            <small class="text-muted">Estimated: March-April 2026</small>
                        </div>
                        <div class="tl-item">
                            <div class="tl-dot"></div>
                            <h6 class="fw-semibold mb-0 small text-muted" data-lang="wc-cp-tl-decision-signed">Decision Signed</h6>
                            <small class="text-muted">—</small>
                        </div>
                        <div class="tl-item" style="padding-bottom:0;">
                            <div class="tl-dot"></div>
                            <h6 class="fw-semibold mb-0 small text-muted" data-lang="wc-cp-tl-card-issued">Card Issued</h6>
                            <small class="text-muted">—</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pending Actions -->
            <div class="card border-0 shadow-sm mt-4" style="border-radius:.75rem;">
                <div class="card-header bg-transparent border-0">
                    <h6 class="fw-semibold mb-0"><i class="ri-alert-line me-1 text-warning"></i><span data-lang="wc-cp-pending-actions">Pending Actions</span></h6>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        <div class="list-group-item d-flex align-items-start gap-3 px-4 py-3">
                            <div class="doc-icon bg-danger-subtle text-danger"><i class="ri-upload-cloud-2-line"></i></div>
                            <div class="flex-grow-1">
                                <h6 class="fw-semibold mb-1 small" data-lang="wc-cp-upload-bank">Upload Bank Statement</h6>
                                <p class="text-muted small mb-1" data-lang="wc-cp-upload-bank-desc">Last 3 months bank statement required for financial proof</p>
                                <span class="badge bg-danger-subtle text-danger">Due: 5 Mar 2026</span>
                            </div>
                            <button class="btn btn-sm btn-outline-success" onclick="showSection('documents')" data-lang="wc-cp-upload-btn">Upload</button>
                        </div>
                        <div class="list-group-item d-flex align-items-start gap-3 px-4 py-3">
                            <div class="doc-icon bg-warning-subtle text-warning"><i class="ri-file-edit-line"></i></div>
                            <div class="flex-grow-1">
                                <h6 class="fw-semibold mb-1 small" data-lang="wc-cp-sign-cert">Sign Employment Certificate</h6>
                                <p class="text-muted small mb-1" data-lang="wc-cp-sign-cert-desc">Your employer's certificate needs your signature</p>
                                <span class="badge bg-warning-subtle text-warning">Due: 10 Mar 2026</span>
                            </div>
                            <button class="btn btn-sm btn-outline-success" data-lang="wc-cp-view-btn">View</button>
                        </div>
                        <div class="list-group-item d-flex align-items-start gap-3 px-4 py-3">
                            <div class="doc-icon bg-info-subtle text-info"><i class="ri-calendar-event-line"></i></div>
                            <div class="flex-grow-1">
                                <h6 class="fw-semibold mb-1 small" data-lang="wc-cp-confirm-appt">Confirm Appointment</h6>
                                <p class="text-muted small mb-1" data-lang="wc-cp-confirm-appt-desc">Consultation meeting at WinCase office — 14 Mar 2026, 10:00</p>
                                <span class="badge bg-info-subtle text-info">14 Mar 2026</span>
                            </div>
                            <button class="btn btn-sm btn-success" data-lang="wc-cp-confirm-btn">Confirm</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column -->
        <div class="col-lg-5">
            <!-- Case Info -->
            <div class="card border-0 shadow-sm" style="border-radius:.75rem;">
                <div class="card-header bg-transparent border-0">
                    <h6 class="fw-semibold mb-0"><i class="ri-briefcase-line me-1 text-success"></i><span data-lang="wc-cp-case-details">Case Details</span></h6>
                </div>
                <div class="card-body pt-0">
                    <table class="table table-sm table-borderless mb-0">
                        <tr><td class="text-muted" style="width:40%" data-lang="wc-cp-case-number">Case Number</td><td class="fw-medium">WC-2026-0847</td></tr>
                        <tr><td class="text-muted" data-lang="wc-cp-type">Type</td><td data-lang="wc-cp-temp-residence">Temporary Residence Permit</td></tr>
                        <tr><td class="text-muted" data-lang="wc-cp-submitted">Submitted</td><td>15 January 2026</td></tr>
                        <tr><td class="text-muted" data-lang="wc-cp-voivodeship">Voivodeship</td><td>Mazowieckie (Warsaw)</td></tr>
                        <tr><td class="text-muted" data-lang="wc-cp-purpose">Purpose</td><td data-lang="wc-cp-employment">Employment</td></tr>
                        <tr><td class="text-muted" data-lang="wc-cp-assigned-manager">Assigned Manager</td><td>Anya Petrova</td></tr>
                        <tr><td class="text-muted" data-lang="wc-cp-status">Status</td><td><span class="badge bg-warning-subtle text-warning" data-lang="wc-cp-in-progress">In Progress</span></td></tr>
                    </table>
                </div>
            </div>

            <!-- Your Manager -->
            <div class="card border-0 shadow-sm mt-4" style="border-radius:.75rem;">
                <div class="card-header bg-transparent border-0">
                    <h6 class="fw-semibold mb-0"><i class="ri-customer-service-2-line me-1 text-success"></i><span data-lang="wc-cp-your-manager">Your Manager</span></h6>
                </div>
                <div class="card-body pt-0">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="rounded-circle bg-success text-white d-flex align-items-center justify-content-center" style="width:50px;height:50px;font-weight:700;">AP</div>
                        <div>
                            <h6 class="fw-semibold mb-0">Anya Petrova</h6>
                            <small class="text-muted" data-lang="wc-cp-senior-consultant">Senior Immigration Consultant</small>
                        </div>
                    </div>
                    <div class="d-flex flex-column gap-2">
                        <a href="tel:+48579266493" class="btn btn-sm btn-outline-secondary text-start"><i class="ri-phone-line me-2"></i>+48 579 266 493</a>
                        <a href="tel:+48739581300" class="btn btn-sm btn-outline-secondary text-start"><i class="ri-phone-line me-2"></i>+48 739 581 300</a>
                        <a href="tel:+48729344585" class="btn btn-sm btn-outline-secondary text-start"><i class="ri-phone-line me-2"></i>+48 729 344 585</a>
                        <a href="mailto:anya@wincase.eu" class="btn btn-sm btn-outline-secondary text-start"><i class="ri-mail-line me-2"></i>anya@wincase.eu</a>
                        <a href="mailto:info@wincase.eu" class="btn btn-sm btn-outline-secondary text-start"><i class="ri-mail-line me-2"></i>info@wincase.eu</a>
                        <button class="btn btn-sm btn-success" onclick="showSection('messages')"><i class="ri-message-2-line me-2"></i><span data-lang="wc-cp-send-message">Send Message</span></button>
                    </div>
                </div>
            </div>

            <!-- Upcoming Events -->
            <div class="card border-0 shadow-sm mt-4" style="border-radius:.75rem;">
                <div class="card-header bg-transparent border-0">
                    <h6 class="fw-semibold mb-0"><i class="ri-calendar-line me-1 text-success"></i><span data-lang="wc-cp-upcoming">Upcoming</span></h6>
                </div>
                <div class="card-body pt-0">
                    <div class="d-flex gap-3 mb-3 pb-3 border-bottom">
                        <div class="text-center" style="min-width:44px;">
                            <div class="fw-bold text-success" style="font-size:1.25rem;">14</div>
                            <small class="text-muted">Mar</small>
                        </div>
                        <div>
                            <h6 class="fw-semibold mb-0 small" data-lang="wc-cp-office-consult">Office Consultation</h6>
                            <small class="text-muted">10:00 — WinCase Office, Warsaw</small>
                        </div>
                    </div>
                    <div class="d-flex gap-3 mb-3 pb-3 border-bottom">
                        <div class="text-center" style="min-width:44px;">
                            <div class="fw-bold text-warning" style="font-size:1.25rem;">28</div>
                            <small class="text-muted">Mar</small>
                        </div>
                        <div>
                            <h6 class="fw-semibold mb-0 small" data-lang="wc-cp-work-permit-expiry">Work Permit Expiry</h6>
                            <small class="text-muted">Employer's Declaration expires — renewal needed</small>
                        </div>
                    </div>
                    <div class="d-flex gap-3">
                        <div class="text-center" style="min-width:44px;">
                            <div class="fw-bold text-info" style="font-size:1.25rem;">15</div>
                            <small class="text-muted">Apr</small>
                        </div>
                        <div>
                            <h6 class="fw-semibold mb-0 small" data-lang="wc-cp-expected-decision">Expected Decision Date</h6>
                            <small class="text-muted">Approximate — subject to Urzad processing time</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payments Summary -->
            <div class="card border-0 shadow-sm mt-4" style="border-radius:.75rem;">
                <div class="card-header bg-transparent border-0 d-flex justify-content-between align-items-center">
                    <h6 class="fw-semibold mb-0"><i class="ri-money-dollar-circle-line me-1 text-success"></i><span data-lang="wc-payments">Payments</span></h6>
                    <a href="#" class="small text-success" onclick="showSection('payments')" data-lang="wc-view-all">View All</a>
                </div>
                <div class="card-body pt-0">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted small" data-lang="wc-cp-total-fee">Total Service Fee</span>
                        <span class="fw-semibold">3 500 PLN</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted small" data-lang="wc-cp-paid">Paid</span>
                        <span class="fw-semibold text-success">2 000 PLN</span>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-muted small" data-lang="wc-cp-remaining">Remaining</span>
                        <span class="fw-semibold text-danger">1 500 PLN</span>
                    </div>
                    <div class="progress" style="height:6px;">
                        <div class="progress-bar bg-success" style="width:57%"></div>
                    </div>
                    <small class="text-muted d-block mt-1">57% paid — next payment due 20 Mar 2026</small>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ═══════════ DOCUMENTS SECTION ═══════════ -->
<div class="section-panel" id="sec-documents">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h5 class="fw-semibold mb-0"><i class="ri-file-text-line me-2 text-success"></i><span data-lang="wc-cp-my-documents">My Documents</span></h5>
        <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#uploadModal"><i class="ri-upload-2-line me-1"></i><span data-lang="wc-cp-upload-document">Upload Document</span></button>
    </div>

    <div class="card border-0 shadow-sm" style="border-radius:.75rem;">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th data-lang="wc-cp-th-document">Document</th>
                            <th data-lang="wc-cp-th-type">Type</th>
                            <th data-lang="wc-cp-th-uploaded">Uploaded</th>
                            <th data-lang="wc-cp-th-status">Status</th>
                            <th class="text-end" data-lang="wc-cp-th-action">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><div class="d-flex align-items-center gap-2"><div class="doc-icon bg-danger-subtle text-danger" style="width:32px;height:32px;font-size:.875rem;"><i class="ri-file-pdf-2-line"></i></div><span class="fw-medium small">Passport_Scan.pdf</span></div></td>
                            <td><span class="badge bg-primary-subtle text-primary" data-lang="wc-cp-doc-identity">Identity</span></td>
                            <td class="text-muted small">12 Jan 2026</td>
                            <td><span class="badge bg-success-subtle text-success" data-lang="wc-cp-status-approved">Approved</span></td>
                            <td class="text-end"><button class="btn btn-sm btn-light"><i class="ri-download-line"></i></button></td>
                        </tr>
                        <tr>
                            <td><div class="d-flex align-items-center gap-2"><div class="doc-icon bg-primary-subtle text-primary" style="width:32px;height:32px;font-size:.875rem;"><i class="ri-file-word-2-line"></i></div><span class="fw-medium small">Employment_Certificate.docx</span></div></td>
                            <td><span class="badge bg-info-subtle text-info" data-lang="wc-cp-doc-employment">Employment</span></td>
                            <td class="text-muted small">20 Jan 2026</td>
                            <td><span class="badge bg-success-subtle text-success" data-lang="wc-cp-status-approved">Approved</span></td>
                            <td class="text-end"><button class="btn btn-sm btn-light"><i class="ri-download-line"></i></button></td>
                        </tr>
                        <tr>
                            <td><div class="d-flex align-items-center gap-2"><div class="doc-icon bg-success-subtle text-success" style="width:32px;height:32px;font-size:.875rem;"><i class="ri-file-image-line"></i></div><span class="fw-medium small">Photo_3.5x4.5.jpg</span></div></td>
                            <td><span class="badge bg-secondary-subtle text-secondary" data-lang="wc-cp-doc-photo">Photo</span></td>
                            <td class="text-muted small">12 Jan 2026</td>
                            <td><span class="badge bg-success-subtle text-success" data-lang="wc-cp-status-approved">Approved</span></td>
                            <td class="text-end"><button class="btn btn-sm btn-light"><i class="ri-download-line"></i></button></td>
                        </tr>
                        <tr>
                            <td><div class="d-flex align-items-center gap-2"><div class="doc-icon bg-danger-subtle text-danger" style="width:32px;height:32px;font-size:.875rem;"><i class="ri-file-pdf-2-line"></i></div><span class="fw-medium small">Umowa_o_prace.pdf</span></div></td>
                            <td><span class="badge bg-info-subtle text-info" data-lang="wc-cp-doc-employment">Employment</span></td>
                            <td class="text-muted small">15 Jan 2026</td>
                            <td><span class="badge bg-success-subtle text-success" data-lang="wc-cp-status-approved">Approved</span></td>
                            <td class="text-end"><button class="btn btn-sm btn-light"><i class="ri-download-line"></i></button></td>
                        </tr>
                        <tr>
                            <td><div class="d-flex align-items-center gap-2"><div class="doc-icon bg-danger-subtle text-danger" style="width:32px;height:32px;font-size:.875rem;"><i class="ri-file-pdf-2-line"></i></div><span class="fw-medium small">Zameldowanie.pdf</span></div></td>
                            <td><span class="badge bg-warning-subtle text-warning" data-lang="wc-cp-doc-address">Address</span></td>
                            <td class="text-muted small">15 Jan 2026</td>
                            <td><span class="badge bg-success-subtle text-success" data-lang="wc-cp-status-approved">Approved</span></td>
                            <td class="text-end"><button class="btn btn-sm btn-light"><i class="ri-download-line"></i></button></td>
                        </tr>
                        <tr>
                            <td><div class="d-flex align-items-center gap-2"><div class="doc-icon bg-danger-subtle text-danger" style="width:32px;height:32px;font-size:.875rem;"><i class="ri-file-pdf-2-line"></i></div><span class="fw-medium small">ZUS_RMUA.pdf</span></div></td>
                            <td><span class="badge bg-info-subtle text-info" data-lang="wc-cp-doc-insurance">Insurance</span></td>
                            <td class="text-muted small">18 Jan 2026</td>
                            <td><span class="badge bg-success-subtle text-success" data-lang="wc-cp-status-approved">Approved</span></td>
                            <td class="text-end"><button class="btn btn-sm btn-light"><i class="ri-download-line"></i></button></td>
                        </tr>
                        <tr>
                            <td><div class="d-flex align-items-center gap-2"><div class="doc-icon bg-danger-subtle text-danger" style="width:32px;height:32px;font-size:.875rem;"><i class="ri-file-pdf-2-line"></i></div><span class="fw-medium small">PIT-37_2025.pdf</span></div></td>
                            <td><span class="badge bg-secondary-subtle text-secondary" data-lang="wc-cp-doc-tax">Tax</span></td>
                            <td class="text-muted small">1 Feb 2026</td>
                            <td><span class="badge bg-success-subtle text-success" data-lang="wc-cp-status-approved">Approved</span></td>
                            <td class="text-end"><button class="btn btn-sm btn-light"><i class="ri-download-line"></i></button></td>
                        </tr>
                        <tr>
                            <td><div class="d-flex align-items-center gap-2"><div class="doc-icon bg-danger-subtle text-danger" style="width:32px;height:32px;font-size:.875rem;"><i class="ri-file-pdf-2-line"></i></div><span class="fw-medium small">Wniosek_Karta_Pobytu.pdf</span></div></td>
                            <td><span class="badge bg-primary-subtle text-primary" data-lang="wc-cp-doc-application">Application</span></td>
                            <td class="text-muted small">10 Jan 2026</td>
                            <td><span class="badge bg-success-subtle text-success" data-lang="wc-cp-status-submitted">Submitted</span></td>
                            <td class="text-end"><button class="btn btn-sm btn-light"><i class="ri-download-line"></i></button></td>
                        </tr>
                        <tr>
                            <td><div class="d-flex align-items-center gap-2"><div class="doc-icon bg-danger-subtle text-danger" style="width:32px;height:32px;font-size:.875rem;"><i class="ri-file-pdf-2-line"></i></div><span class="fw-medium small">Pelnomocnictwo_signed.pdf</span></div></td>
                            <td><span class="badge bg-primary-subtle text-primary" data-lang="wc-cp-doc-legal">Legal</span></td>
                            <td class="text-muted small">10 Jan 2026</td>
                            <td><span class="badge bg-success-subtle text-success" data-lang="wc-cp-status-approved">Approved</span></td>
                            <td class="text-end"><button class="btn btn-sm btn-light"><i class="ri-download-line"></i></button></td>
                        </tr>
                        <tr class="table-warning">
                            <td><div class="d-flex align-items-center gap-2"><div class="doc-icon bg-warning-subtle text-warning" style="width:32px;height:32px;font-size:.875rem;"><i class="ri-upload-cloud-2-line"></i></div><span class="fw-medium small text-warning">Bank_Statement.pdf</span></div></td>
                            <td><span class="badge bg-warning-subtle text-warning" data-lang="wc-cp-doc-financial">Financial</span></td>
                            <td class="text-muted small">—</td>
                            <td><span class="badge bg-danger-subtle text-danger" data-lang="wc-cp-status-required">Required</span></td>
                            <td class="text-end"><button class="btn btn-sm btn-warning text-white" data-bs-toggle="modal" data-bs-target="#uploadModal"><i class="ri-upload-2-line me-1"></i><span data-lang="wc-cp-upload-btn">Upload</span></button></td>
                        </tr>
                        <tr class="table-warning">
                            <td><div class="d-flex align-items-center gap-2"><div class="doc-icon bg-warning-subtle text-warning" style="width:32px;height:32px;font-size:.875rem;"><i class="ri-upload-cloud-2-line"></i></div><span class="fw-medium small text-warning">Health_Insurance.pdf</span></div></td>
                            <td><span class="badge bg-info-subtle text-info" data-lang="wc-cp-doc-insurance">Insurance</span></td>
                            <td class="text-muted small">—</td>
                            <td><span class="badge bg-danger-subtle text-danger" data-lang="wc-cp-status-required">Required</span></td>
                            <td class="text-end"><button class="btn btn-sm btn-warning text-white" data-bs-toggle="modal" data-bs-target="#uploadModal"><i class="ri-upload-2-line me-1"></i><span data-lang="wc-cp-upload-btn">Upload</span></button></td>
                        </tr>
                        <tr>
                            <td><div class="d-flex align-items-center gap-2"><div class="doc-icon bg-danger-subtle text-danger" style="width:32px;height:32px;font-size:.875rem;"><i class="ri-file-pdf-2-line"></i></div><span class="fw-medium small">Oswiadczenie_pracodawcy.pdf</span></div></td>
                            <td><span class="badge bg-info-subtle text-info" data-lang="wc-cp-doc-employment">Employment</span></td>
                            <td class="text-muted small">20 Feb 2026</td>
                            <td><span class="badge bg-info-subtle text-info" data-lang="wc-cp-status-review">Under Review</span></td>
                            <td class="text-end"><button class="btn btn-sm btn-light"><i class="ri-download-line"></i></button></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- ═══════════ MESSAGES SECTION ═══════════ -->
<div class="section-panel" id="sec-messages">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h5 class="fw-semibold mb-0"><i class="ri-message-2-line me-2 text-success"></i><span data-lang="wc-cp-messages">Messages</span></h5>
        <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#newMsgModal"><i class="ri-edit-2-line me-1"></i><span data-lang="wc-cp-new-msg">New Message</span></button>
    </div>

    <div class="card border-0 shadow-sm" style="border-radius:.75rem;">
        <div class="card-body p-0">
            <div class="list-group list-group-flush">
                <div class="list-group-item px-4 py-3 msg-unread">
                    <div class="d-flex align-items-start gap-3">
                        <div class="rounded-circle bg-success text-white d-flex align-items-center justify-content-center flex-shrink-0" style="width:40px;height:40px;font-size:.75rem;font-weight:700;">AP</div>
                        <div class="flex-grow-1">
                            <div class="d-flex justify-content-between mb-1">
                                <h6 class="fw-semibold mb-0 small">Anya Petrova</h6>
                                <small class="text-muted">Today, 09:15</small>
                            </div>
                            <p class="mb-1 small"><strong>Bank statement required</strong></p>
                            <p class="text-muted small mb-0">Hello Olena, we need your bank statement for the last 3 months. Please upload it by March 5th. This is needed for the financial proof requirement...</p>
                        </div>
                    </div>
                </div>
                <div class="list-group-item px-4 py-3 msg-unread">
                    <div class="d-flex align-items-start gap-3">
                        <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center flex-shrink-0" style="width:40px;height:40px;font-size:.75rem;font-weight:700;">WC</div>
                        <div class="flex-grow-1">
                            <div class="d-flex justify-content-between mb-1">
                                <h6 class="fw-semibold mb-0 small">WinCase System</h6>
                                <small class="text-muted">Yesterday, 14:22</small>
                            </div>
                            <p class="mb-1 small"><strong>Appointment Confirmation</strong></p>
                            <p class="text-muted small mb-0">Your consultation has been scheduled for March 14, 2026 at 10:00 at WinCase office. Please confirm your attendance.</p>
                        </div>
                    </div>
                </div>
                <div class="list-group-item px-4 py-3">
                    <div class="d-flex align-items-start gap-3">
                        <div class="rounded-circle bg-success text-white d-flex align-items-center justify-content-center flex-shrink-0" style="width:40px;height:40px;font-size:.75rem;font-weight:700;">AP</div>
                        <div class="flex-grow-1">
                            <div class="d-flex justify-content-between mb-1">
                                <h6 class="fw-semibold mb-0 small">Anya Petrova</h6>
                                <small class="text-muted">27 Feb, 11:30</small>
                            </div>
                            <p class="mb-1 small"><strong>Fingerprints completed</strong></p>
                            <p class="text-muted small mb-0">Great news! Your fingerprints have been successfully submitted. The case is now under review by the Voivodeship Office.</p>
                        </div>
                    </div>
                </div>
                <div class="list-group-item px-4 py-3">
                    <div class="d-flex align-items-start gap-3">
                        <div class="rounded-circle bg-info text-white d-flex align-items-center justify-content-center flex-shrink-0" style="width:40px;height:40px;font-size:.75rem;font-weight:700;">OK</div>
                        <div class="flex-grow-1">
                            <div class="d-flex justify-content-between mb-1">
                                <h6 class="fw-semibold mb-0 small" data-lang="wc-cp-you">You</h6>
                                <small class="text-muted">25 Feb, 16:45</small>
                            </div>
                            <p class="mb-1 small"><strong>Re: Fingerprint appointment</strong></p>
                            <p class="text-muted small mb-0">Thank you for scheduling. I will be there on February 5th at 09:00. Is there anything I need to bring?</p>
                        </div>
                    </div>
                </div>
                <div class="list-group-item px-4 py-3">
                    <div class="d-flex align-items-start gap-3">
                        <div class="rounded-circle bg-success text-white d-flex align-items-center justify-content-center flex-shrink-0" style="width:40px;height:40px;font-size:.75rem;font-weight:700;">AP</div>
                        <div class="flex-grow-1">
                            <div class="d-flex justify-content-between mb-1">
                                <h6 class="fw-semibold mb-0 small">Anya Petrova</h6>
                                <small class="text-muted">24 Feb, 10:00</small>
                            </div>
                            <p class="mb-1 small"><strong>Fingerprint appointment scheduled</strong></p>
                            <p class="text-muted small mb-0">Your fingerprint appointment at Urzad Wojewodzki has been scheduled for February 5, 2026 at 09:00. Please bring your passport and stempel/stamp.</p>
                        </div>
                    </div>
                </div>
                <div class="list-group-item px-4 py-3">
                    <div class="d-flex align-items-start gap-3">
                        <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center flex-shrink-0" style="width:40px;height:40px;font-size:.75rem;font-weight:700;">WC</div>
                        <div class="flex-grow-1">
                            <div class="d-flex justify-content-between mb-1">
                                <h6 class="fw-semibold mb-0 small">WinCase System</h6>
                                <small class="text-muted">22 Jan, 09:00</small>
                            </div>
                            <p class="mb-1 small"><strong>Application submitted successfully</strong></p>
                            <p class="text-muted small mb-0">Your application for Temporary Residence Permit has been submitted to the Mazowieckie Voivodeship Office. Case number: WC-2026-0847.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ═══════════ PAYMENTS SECTION ═══════════ -->
<div class="section-panel" id="sec-payments">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h5 class="fw-semibold mb-0"><i class="ri-bank-card-line me-2 text-success"></i><span data-lang="wc-cp-payments-invoices">Payments & Invoices</span></h5>
    </div>

    <!-- Payment Summary -->
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card stat-card border-start border-success border-3">
                <div class="card-body p-3">
                    <small class="text-muted" data-lang="wc-cp-total-paid">Total Paid</small>
                    <h4 class="fw-bold text-success mb-0">2 000 PLN</h4>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card stat-card border-start border-danger border-3">
                <div class="card-body p-3">
                    <small class="text-muted" data-lang="wc-cp-remaining">Remaining</small>
                    <h4 class="fw-bold text-danger mb-0">1 500 PLN</h4>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card stat-card border-start border-warning border-3">
                <div class="card-body p-3">
                    <small class="text-muted" data-lang="wc-cp-next-payment">Next Payment</small>
                    <h4 class="fw-bold mb-0">20 Mar 2026</h4>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm" style="border-radius:.75rem;">
        <div class="card-header bg-transparent border-0">
            <h6 class="fw-semibold mb-0" data-lang="wc-cp-payment-history">Payment History</h6>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th data-lang="wc-cp-th-date">Date</th>
                            <th data-lang="wc-cp-th-description">Description</th>
                            <th data-lang="wc-cp-th-amount">Amount</th>
                            <th data-lang="wc-cp-th-method">Method</th>
                            <th data-lang="wc-cp-th-status">Status</th>
                            <th data-lang="wc-cp-th-invoice">Invoice</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="small">10 Jan 2026</td>
                            <td class="small fw-medium" data-lang="wc-cp-pay-initial">Initial Consultation Fee</td>
                            <td class="fw-semibold">500 PLN</td>
                            <td class="small">BLIK</td>
                            <td><span class="badge bg-success-subtle text-success" data-lang="wc-cp-status-paid">Paid</span></td>
                            <td><button class="btn btn-sm btn-light"><i class="ri-download-line"></i> INV-001</button></td>
                        </tr>
                        <tr>
                            <td class="small">15 Jan 2026</td>
                            <td class="small fw-medium" data-lang="wc-cp-pay-doc-prep">Document Preparation — Phase 1</td>
                            <td class="fw-semibold">1 000 PLN</td>
                            <td class="small">Transfer</td>
                            <td><span class="badge bg-success-subtle text-success" data-lang="wc-cp-status-paid">Paid</span></td>
                            <td><button class="btn btn-sm btn-light"><i class="ri-download-line"></i> INV-002</button></td>
                        </tr>
                        <tr>
                            <td class="small">1 Feb 2026</td>
                            <td class="small fw-medium" data-lang="wc-cp-pay-app-submit">Application Submission Fee</td>
                            <td class="fw-semibold">500 PLN</td>
                            <td class="small">Card</td>
                            <td><span class="badge bg-success-subtle text-success" data-lang="wc-cp-status-paid">Paid</span></td>
                            <td><button class="btn btn-sm btn-light"><i class="ri-download-line"></i> INV-003</button></td>
                        </tr>
                        <tr class="table-warning">
                            <td class="small">20 Mar 2026</td>
                            <td class="small fw-medium" data-lang="wc-cp-pay-case-mgmt">Case Management — Phase 2</td>
                            <td class="fw-semibold text-danger">1 000 PLN</td>
                            <td class="small">—</td>
                            <td><span class="badge bg-warning-subtle text-warning" data-lang="wc-cp-status-pending">Pending</span></td>
                            <td><button class="btn btn-sm btn-success"><i class="ri-bank-card-line me-1"></i><span data-lang="wc-cp-pay-btn">Pay</span></button></td>
                        </tr>
                        <tr>
                            <td class="small">—</td>
                            <td class="small fw-medium" data-lang="wc-cp-pay-final">Final Settlement (upon card issuance)</td>
                            <td class="fw-semibold text-muted">500 PLN</td>
                            <td class="small">—</td>
                            <td><span class="badge bg-secondary-subtle text-secondary" data-lang="wc-cp-status-upcoming">Upcoming</span></td>
                            <td>—</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="alert alert-info small mt-3">
        <i class="ri-information-line me-1"></i>
        <strong data-lang="wc-cp-payment-methods">Payment methods</strong>: Bank Transfer (mBank: 12 3456 7890 1234 5678 9012 3456), BLIK, Card (via Przelewy24), PayU, PayPal.
        For questions contact: finance@wincase.eu
    </div>
</div>

<!-- ═══════════ PROFILE SECTION ═══════════ -->
<div class="section-panel" id="sec-profile">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h5 class="fw-semibold mb-0"><i class="ri-user-line me-2 text-success"></i><span data-lang="wc-cp-my-profile">My Profile</span></h5>
        <button class="btn btn-outline-success btn-sm" id="btnEditProfile"><i class="ri-edit-line me-1"></i><span data-lang="wc-cp-edit-profile">Edit Profile</span></button>
    </div>

    <div class="row g-4">
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm text-center" style="border-radius:.75rem;">
                <div class="card-body p-4">
                    <div class="rounded-circle bg-success text-white mx-auto d-flex align-items-center justify-content-center mb-3" style="width:80px;height:80px;font-size:2rem;font-weight:700;">OK</div>
                    <h5 class="fw-bold mb-1">Olena Kovalenko</h5>
                    <p class="text-muted small mb-2"><span data-lang="wc-cp-client-since">Client since</span> January 2026</p>
                    <span class="badge bg-success-subtle text-success" data-lang="wc-cp-active-client">Active Client</span>
                    <hr>
                    <div class="text-start">
                        <p class="small mb-1"><i class="ri-mail-line me-2 text-muted"></i>olena.kovalenko@gmail.com</p>
                        <p class="small mb-1"><i class="ri-phone-line me-2 text-muted"></i>+48 512 345 678</p>
                        <p class="small mb-1"><i class="ri-map-pin-line me-2 text-muted"></i>Warsaw, Mazowieckie</p>
                        <p class="small mb-0"><i class="ri-flag-line me-2 text-muted"></i>Ukraine</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm" style="border-radius:.75rem;">
                <div class="card-header bg-transparent border-0">
                    <h6 class="fw-semibold mb-0" data-lang="wc-cp-personal-info">Personal Information</h6>
                </div>
                <div class="card-body pt-0">
                    <div class="row g-3">
                        <div class="col-md-6"><label class="small text-muted" data-lang="wc-cp-full-name">Full Name</label><p class="fw-medium mb-0">Olena Viktorivna Kovalenko</p></div>
                        <div class="col-md-6"><label class="small text-muted" data-lang="wc-cp-dob">Date of Birth</label><p class="fw-medium mb-0">15 March 1992</p></div>
                        <div class="col-md-6"><label class="small text-muted" data-lang="wc-cp-nationality">Nationality</label><p class="fw-medium mb-0">Ukrainian</p></div>
                        <div class="col-md-6"><label class="small text-muted" data-lang="wc-cp-passport">Passport</label><p class="fw-medium mb-0">FE123456 (exp. 10 Jan 2030)</p></div>
                        <div class="col-md-6"><label class="small text-muted" data-lang="wc-cp-pesel">PESEL</label><p class="fw-medium mb-0">92031512345</p></div>
                        <div class="col-md-6"><label class="small text-muted" data-lang="wc-cp-phone">Phone</label><p class="fw-medium mb-0">+48 512 345 678</p></div>
                        <div class="col-md-12"><label class="small text-muted" data-lang="wc-cp-address-poland">Address in Poland</label><p class="fw-medium mb-0">ul. Marszalkowska 55/12, 00-676 Warszawa</p></div>
                    </div>
                </div>
            </div>
            <div class="card border-0 shadow-sm mt-3" style="border-radius:.75rem;">
                <div class="card-header bg-transparent border-0">
                    <h6 class="fw-semibold mb-0" data-lang="wc-cp-employment-title">Employment</h6>
                </div>
                <div class="card-body pt-0">
                    <div class="row g-3">
                        <div class="col-md-6"><label class="small text-muted" data-lang="wc-cp-employer">Employer</label><p class="fw-medium mb-0">TechStar Sp. z o.o.</p></div>
                        <div class="col-md-6"><label class="small text-muted" data-lang="wc-cp-position">Position</label><p class="fw-medium mb-0">UX Designer</p></div>
                        <div class="col-md-6"><label class="small text-muted" data-lang="wc-cp-contract-type">Contract Type</label><p class="fw-medium mb-0">Umowa o prace</p></div>
                        <div class="col-md-6"><label class="small text-muted" data-lang="wc-cp-since">Since</label><p class="fw-medium mb-0">1 September 2024</p></div>
                    </div>
                </div>
            </div>
            <div class="card border-0 shadow-sm mt-3" style="border-radius:.75rem;">
                <div class="card-header bg-transparent border-0">
                    <h6 class="fw-semibold mb-0" data-lang="wc-cp-agreements">Agreements</h6>
                </div>
                <div class="card-body pt-0">
                    <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                        <div><i class="ri-check-line text-success me-2"></i><span class="small" data-lang="wc-cp-terms">Terms of Service</span></div>
                        <small class="text-muted">Accepted 10 Jan 2026</small>
                    </div>
                    <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                        <div><i class="ri-check-line text-success me-2"></i><span class="small" data-lang="wc-cp-rodo">RODO / GDPR Consent</span></div>
                        <small class="text-muted">Accepted 10 Jan 2026</small>
                    </div>
                    <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                        <div><i class="ri-check-line text-success me-2"></i><span class="small" data-lang="wc-cp-power-attorney">Power of Attorney</span></div>
                        <small class="text-muted">Accepted 10 Jan 2026</small>
                    </div>
                    <div class="d-flex justify-content-between align-items-center py-2">
                        <div><i class="ri-check-line text-success me-2"></i><span class="small" data-lang="wc-cp-data-consent">Data Processing Consent</span></div>
                        <small class="text-muted">Accepted 10 Jan 2026</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ═══════════ SETTINGS SECTION ═══════════ -->
<div class="section-panel" id="sec-settings">
    <h5 class="fw-semibold mb-4"><i class="ri-settings-3-line me-2 text-success"></i><span data-lang="wc-settings">Settings</span></h5>
    <div class="row g-4">
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm" style="border-radius:.75rem;">
                <div class="card-header bg-transparent border-0"><h6 class="fw-semibold mb-0" data-lang="wc-cp-change-password">Change Password</h6></div>
                <div class="card-body pt-0">
                    <div class="mb-3"><label class="form-label small" data-lang="wc-cp-current-password">Current Password</label><input type="password" class="form-control"></div>
                    <div class="mb-3"><label class="form-label small" data-lang="wc-cp-new-password">New Password</label><input type="password" class="form-control"></div>
                    <div class="mb-3"><label class="form-label small" data-lang="wc-cp-confirm-password">Confirm New Password</label><input type="password" class="form-control"></div>
                    <button class="btn btn-success btn-sm" data-lang="wc-cp-update-password">Update Password</button>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm" style="border-radius:.75rem;">
                <div class="card-header bg-transparent border-0"><h6 class="fw-semibold mb-0" data-lang="wc-cp-notifications">Notifications</h6></div>
                <div class="card-body pt-0">
                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" id="notifEmail" checked>
                        <label class="form-check-label small" for="notifEmail" data-lang="wc-cp-notif-email">Email notifications</label>
                    </div>
                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" id="notifSms" checked>
                        <label class="form-check-label small" for="notifSms" data-lang="wc-cp-notif-sms">SMS notifications</label>
                    </div>
                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" id="notifCase" checked>
                        <label class="form-check-label small" for="notifCase" data-lang="wc-cp-notif-case">Case status updates</label>
                    </div>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="notifMkt">
                        <label class="form-check-label small" for="notifMkt" data-lang="wc-cp-notif-marketing">Marketing messages</label>
                    </div>
                </div>
            </div>
            <div class="card border-0 shadow-sm mt-3" style="border-radius:.75rem;">
                <div class="card-header bg-transparent border-0"><h6 class="fw-semibold mb-0" data-lang="wc-cp-language">Language</h6></div>
                <div class="card-body pt-0">
                    <select class="form-select form-select-sm" id="langSelect" style="max-width:200px;">
                        <option value="en">English</option>
                        <option value="pl">Polski</option>
                        <option value="ua">Українська</option>
                    </select>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ═══════════ MODALS ═══════════ -->

<!-- Upload Document Modal -->
<div class="modal fade" id="uploadModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-semibold" data-lang="wc-cp-upload-document">Upload Document</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label fw-medium" data-lang="wc-cp-doc-type-label">Document Type</label>
                    <select class="form-select" id="uploadType">
                        <option value="bank" data-lang="wc-cp-opt-bank">Bank Statement</option>
                        <option value="health" data-lang="wc-cp-opt-health">Health Insurance</option>
                        <option value="employment" data-lang="wc-cp-opt-employ-cert">Employment Certificate</option>
                        <option value="passport" data-lang="wc-cp-opt-passport">Passport Scan</option>
                        <option value="tax" data-lang="wc-cp-opt-tax">Tax Document</option>
                        <option value="address" data-lang="wc-cp-opt-address-conf">Address Confirmation</option>
                        <option value="photo" data-lang="wc-cp-opt-photo">Photo 3.5×4.5</option>
                        <option value="power_of_attorney" data-lang="wc-cp-opt-poa">Power of Attorney</option>
                        <option value="work_contract" data-lang="wc-cp-opt-contract">Work Contract (Umowa)</option>
                        <option value="zus" data-lang="wc-cp-opt-zus">ZUS / RMUA</option>
                        <option value="pit" data-lang="wc-cp-opt-pit">PIT (Tax Return)</option>
                        <option value="zameldowanie" data-lang="wc-cp-opt-zamel">Zameldowanie (Registration)</option>
                        <option value="other" data-lang="wc-cp-opt-other">Other</option>
                    </select>
                </div>

                <!-- Format hint (dynamic) -->
                <div id="uploadFormatHint" class="alert alert-info py-2 px-3 small mb-3" style="display:none;">
                    <i class="ri-information-line me-1"></i><span id="uploadFormatText"></span>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-medium" data-lang="wc-cp-file-label">File</label>
                    <!-- Drag & Drop Zone -->
                    <div id="uploadDropZone" class="border border-2 border-dashed rounded-3 text-center p-4 position-relative" style="cursor:pointer;transition:all .2s;">
                        <div id="uploadDropContent">
                            <i class="ri-upload-cloud-2-line fs-1 text-muted"></i>
                            <p class="small text-muted mb-1" data-lang="wc-cp-drag-drop">Drag & drop file here or click to browse</p>
                            <p class="small text-muted mb-0" id="uploadAcceptText">PDF • Max 10 MB</p>
                        </div>
                        <div id="uploadFilePreview" class="d-none">
                            <div class="d-flex align-items-center justify-content-center gap-2">
                                <i class="ri-file-line fs-3 text-success"></i>
                                <div class="text-start">
                                    <p class="fw-medium small mb-0" id="uploadFileName">—</p>
                                    <p class="text-muted small mb-0" id="uploadFileSize">—</p>
                                </div>
                                <button type="button" class="btn btn-sm btn-outline-danger ms-2" id="uploadFileRemove"><i class="ri-close-line"></i></button>
                            </div>
                        </div>
                        <input type="file" class="position-absolute top-0 start-0 w-100 h-100 opacity-0" id="uploadFile" style="cursor:pointer;">
                    </div>
                    <div id="uploadError" class="invalid-feedback d-block" style="display:none !important;"></div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-medium" data-lang="wc-cp-notes-label">Notes</label>
                    <textarea class="form-control" rows="2" id="uploadNotes" placeholder="Optional description..." data-lang-placeholder="wc-cp-notes-placeholder"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-light" data-bs-dismiss="modal" data-lang="wc-cp-cancel">Cancel</button>
                <button class="btn btn-success" id="btnUpload" disabled><i class="ri-upload-2-line me-1"></i><span data-lang="wc-cp-upload-btn">Upload</span></button>
            </div>
        </div>
    </div>
</div>

<!-- New Message Modal -->
<div class="modal fade" id="newMsgModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-semibold" data-lang="wc-cp-new-msg">New Message</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label fw-medium" data-lang="wc-cp-to-label">To</label>
                    <select class="form-select">
                        <option>Anya Petrova (Your Manager)</option>
                        <option data-lang="wc-cp-opt-support">WinCase Support</option>
                        <option data-lang="wc-cp-opt-finance">Finance Department</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-medium" data-lang="wc-cp-subject-label">Subject</label>
                    <input type="text" class="form-control" placeholder="Message subject" data-lang-placeholder="wc-cp-subject-placeholder">
                </div>
                <div class="mb-3">
                    <label class="form-label fw-medium" data-lang="wc-cp-message-label">Message</label>
                    <textarea class="form-control" rows="5" placeholder="Write your message..." data-lang-placeholder="wc-cp-message-placeholder"></textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-medium" data-lang="wc-cp-attach-file">Attach File</label>
                    <input type="file" class="form-control" id="msgAttachFile" accept=".pdf,.jpg,.jpeg,.png,.doc,.docx">
                    <div class="form-text small">PDF, JPG, PNG, DOC/DOCX. Max 10 MB.</div>
                    <div id="msgAttachError" class="invalid-feedback d-block" style="display:none !important;"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-light" data-bs-dismiss="modal" data-lang="wc-cp-cancel">Cancel</button>
                <button class="btn btn-success" id="btnSendMsg"><i class="ri-send-plane-line me-1"></i><span data-lang="wc-cp-send-btn">Send</span></button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('js')
<script>
/* ═══════════════════════════════════════════
   Document Type → Validation Rules
   ═══════════════════════════════════════════ */
var DOC_RULES = {
    bank:             { accept: '.pdf,.jpg,.jpeg,.png',              maxMB: 10, label: 'PDF, JPG, PNG',           hint: 'Bank statement — last 3 months, all pages. PDF or photo scan.' },
    health:           { accept: '.pdf,.jpg,.jpeg,.png',              maxMB: 10, label: 'PDF, JPG, PNG',           hint: 'Health insurance certificate / ZUS ZUA. PDF or photo scan.' },
    employment:       { accept: '.pdf,.doc,.docx,.jpg,.jpeg,.png',   maxMB: 10, label: 'PDF, DOC, JPG, PNG',      hint: 'Employment certificate from your employer.' },
    passport:         { accept: '.pdf,.jpg,.jpeg,.png',              maxMB: 10, label: 'PDF, JPG, PNG',           hint: 'Clear scan of passport data page. Color, min 300 DPI, no glare.' },
    tax:              { accept: '.pdf,.jpg,.jpeg,.png',              maxMB: 10, label: 'PDF, JPG, PNG',           hint: 'PIT-37 or PIT-36 tax return. Full document with all pages.' },
    address:          { accept: '.pdf,.jpg,.jpeg,.png',              maxMB: 10, label: 'PDF, JPG, PNG',           hint: 'Zameldowanie or utility bill confirming your address in Poland.' },
    photo:            { accept: '.jpg,.jpeg,.png',                   maxMB: 5,  label: 'JPG, JPEG, PNG',          hint: 'Biometric photo 3.5×4.5 cm, white background, 300+ DPI.', checkPhoto: true },
    power_of_attorney:{ accept: '.pdf,.jpg,.jpeg,.png',              maxMB: 10, label: 'PDF, JPG, PNG',           hint: 'Signed power of attorney (Pełnomocnictwo). Scanned with signature.' },
    work_contract:    { accept: '.pdf,.doc,.docx,.jpg,.jpeg,.png',   maxMB: 10, label: 'PDF, DOC, JPG, PNG',      hint: 'Work contract (Umowa o pracę / zlecenie / dzieło). All pages.' },
    zus:              { accept: '.pdf,.jpg,.jpeg,.png',              maxMB: 10, label: 'PDF, JPG, PNG',           hint: 'ZUS RMUA or ZUS ZUA confirmation document.' },
    pit:              { accept: '.pdf,.jpg,.jpeg,.png',              maxMB: 10, label: 'PDF, JPG, PNG',           hint: 'Annual tax return (PIT-37 / PIT-36) with UPO confirmation.' },
    zameldowanie:     { accept: '.pdf,.jpg,.jpeg,.png',              maxMB: 10, label: 'PDF, JPG, PNG',           hint: 'Registration confirmation (Zaświadczenie o zameldowaniu).' },
    other:            { accept: '.pdf,.jpg,.jpeg,.png,.doc,.docx',   maxMB: 10, label: 'PDF, JPG, PNG, DOC/DOCX', hint: 'Any supporting document for your case.' }
};

var _t = function(k, fb) { return window.WcI18n ? WcI18n.t(k) : fb; };

/* ═══════════ Navigation ═══════════ */
function showSection(sec) {
    document.querySelectorAll('.section-panel').forEach(function(p){ p.classList.remove('active'); });
    var el = document.getElementById('sec-' + sec);
    if(el) el.classList.add('active');
    document.querySelectorAll('.client-navbar .nav-link').forEach(function(l){ l.classList.remove('active'); });
    document.querySelectorAll('.mob-nav').forEach(function(b){
        b.classList.remove('btn-outline-success','active');
        b.classList.add('btn-outline-secondary');
        if(b.getAttribute('data-sec') === sec || (sec === 'main' && b.getAttribute('data-sec') === 'main')){
            b.classList.remove('btn-outline-secondary');
            b.classList.add('btn-outline-success','active');
        }
    });
    window.scrollTo({top:0, behavior:'smooth'});
}

document.querySelectorAll('.mob-nav').forEach(function(btn){
    btn.addEventListener('click', function(){ showSection(this.getAttribute('data-sec')); });
});

/* ═══════════ Upload Modal — Dynamic Validation ═══════════ */
var uploadType   = document.getElementById('uploadType');
var uploadFile   = document.getElementById('uploadFile');
var uploadDrop   = document.getElementById('uploadDropZone');
var uploadHint   = document.getElementById('uploadFormatHint');
var uploadHintTx = document.getElementById('uploadFormatText');
var uploadAccTx  = document.getElementById('uploadAcceptText');
var uploadPreview= document.getElementById('uploadFilePreview');
var uploadContent= document.getElementById('uploadDropContent');
var uploadFName  = document.getElementById('uploadFileName');
var uploadFSize  = document.getElementById('uploadFileSize');
var uploadFRemove= document.getElementById('uploadFileRemove');
var uploadError  = document.getElementById('uploadError');
var btnUpload    = document.getElementById('btnUpload');

function getRule() { return DOC_RULES[uploadType.value] || DOC_RULES.other; }

function updateAccept() {
    var rule = getRule();
    uploadFile.setAttribute('accept', rule.accept);
    uploadAccTx.textContent = rule.label + ' • Max ' + rule.maxMB + ' MB';
    uploadHintTx.textContent = rule.hint;
    uploadHint.style.display = 'block';
    // Reset file when type changes
    resetFile();
}

function resetFile() {
    uploadFile.value = '';
    uploadPreview.classList.add('d-none');
    uploadContent.classList.remove('d-none');
    uploadError.style.cssText = 'display:none !important;';
    uploadDrop.classList.remove('border-danger','border-success');
    uploadDrop.classList.add('border-dashed');
    btnUpload.disabled = true;
}

function formatSize(bytes) {
    if (bytes < 1024) return bytes + ' B';
    if (bytes < 1048576) return (bytes/1024).toFixed(1) + ' KB';
    return (bytes/1048576).toFixed(1) + ' MB';
}

function showError(msg) {
    uploadError.textContent = msg;
    uploadError.style.cssText = 'display:block !important;color:#dc3545;font-size:.8rem;margin-top:.25rem;';
    uploadDrop.classList.add('border-danger');
    uploadDrop.classList.remove('border-success','border-dashed');
    btnUpload.disabled = true;
}

function validateFile(file) {
    if (!file) return false;
    var rule = getRule();
    var ext = '.' + file.name.split('.').pop().toLowerCase();
    var allowed = rule.accept.split(',').map(function(e){ return e.trim().toLowerCase(); });

    // Check extension
    if (allowed.indexOf(ext) === -1) {
        showError('Wrong format! Allowed: ' + rule.label + '. You selected: ' + ext.toUpperCase());
        return false;
    }

    // Check MIME type for extra safety
    var mimeMap = {
        '.pdf': ['application/pdf'],
        '.jpg': ['image/jpeg'], '.jpeg': ['image/jpeg'],
        '.png': ['image/png'],
        '.doc': ['application/msword'], '.docx': ['application/vnd.openxmlformats-officedocument.wordprocessingml.document']
    };
    if (mimeMap[ext] && file.type && mimeMap[ext].indexOf(file.type) === -1) {
        showError('File type mismatch! Extension is ' + ext + ' but actual type is "' + file.type + '". Please upload a genuine ' + ext.toUpperCase() + ' file.');
        return false;
    }

    // Check size
    var maxBytes = rule.maxMB * 1024 * 1024;
    if (file.size > maxBytes) {
        showError('File too large! Max ' + rule.maxMB + ' MB. Your file: ' + formatSize(file.size));
        return false;
    }

    // Check minimum size (protect against empty files)
    if (file.size < 1024) {
        showError('File is too small (' + formatSize(file.size) + '). Please upload a valid document.');
        return false;
    }

    // Photo-specific validation
    if (rule.checkPhoto && (ext === '.jpg' || ext === '.jpeg')) {
        // We'll check dimensions via Image object
        return 'check_image';
    }

    return true;
}

function showFilePreview(file) {
    uploadContent.classList.add('d-none');
    uploadPreview.classList.remove('d-none');
    uploadFName.textContent = file.name;
    uploadFSize.textContent = formatSize(file.size);
    uploadError.style.cssText = 'display:none !important;';
    uploadDrop.classList.remove('border-danger','border-dashed');
    uploadDrop.classList.add('border-success');
    btnUpload.disabled = false;
}

function handleFileSelect(file) {
    var result = validateFile(file);
    if (result === false) return;

    if (result === 'check_image') {
        // Check photo dimensions
        var img = new Image();
        var url = URL.createObjectURL(file);
        img.onload = function() {
            URL.revokeObjectURL(url);
            var w = img.width, h = img.height;
            var ratio = h / w;
            // 3.5x4.5 ratio = 1.2857 (allow 1.1 to 1.5 range)
            if (ratio < 1.1 || ratio > 1.5) {
                showError('Photo proportions incorrect! Expected ~3.5×4.5 cm (ratio ~1.29). Your image: ' + w + '×' + h + 'px (ratio ' + ratio.toFixed(2) + '). Please upload a biometric photo.');
                return;
            }
            // Check minimum resolution (300 DPI for 3.5cm = ~413px width)
            if (w < 350 || h < 450) {
                showError('Photo resolution too low! Minimum 350×450 px (recommended 413×531 px for 300 DPI). Your image: ' + w + '×' + h + 'px.');
                return;
            }
            showFilePreview(file);
        };
        img.onerror = function() {
            URL.revokeObjectURL(url);
            showError('Cannot read image file. It may be corrupted.');
        };
        img.src = url;
    } else {
        showFilePreview(file);
    }
}

// Type change
uploadType.addEventListener('change', updateAccept);

// File input change
uploadFile.addEventListener('change', function() {
    if (this.files && this.files[0]) handleFileSelect(this.files[0]);
});

// Drag & Drop
uploadDrop.addEventListener('dragover', function(e) {
    e.preventDefault();
    this.classList.add('border-success');
    this.classList.remove('border-dashed');
});
uploadDrop.addEventListener('dragleave', function(e) {
    e.preventDefault();
    if (!uploadPreview.classList.contains('d-none')) return;
    this.classList.remove('border-success');
    this.classList.add('border-dashed');
});
uploadDrop.addEventListener('drop', function(e) {
    e.preventDefault();
    this.classList.remove('border-success');
    this.classList.add('border-dashed');
    if (e.dataTransfer.files && e.dataTransfer.files[0]) {
        // Manually set the file input (for form submission)
        var dt = new DataTransfer();
        dt.items.add(e.dataTransfer.files[0]);
        uploadFile.files = dt.files;
        handleFileSelect(e.dataTransfer.files[0]);
    }
});

// Remove file button
uploadFRemove.addEventListener('click', function(e) {
    e.stopPropagation();
    resetFile();
});

// Initialize
updateAccept();

// Modal reset on close
document.getElementById('uploadModal').addEventListener('hidden.bs.modal', function() {
    resetFile();
    uploadType.selectedIndex = 0;
    document.getElementById('uploadNotes').value = '';
    updateAccept();
});

// Upload button — sends file to encrypted vault
btnUpload.addEventListener('click', function(){
    if (!uploadFile.files || !uploadFile.files[0]) {
        showError('Please select a file first.');
        return;
    }
    var self = this;
    self.disabled = true;
    self.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span><span data-lang="wc-cp-uploading">Encrypting & Uploading...</span>';

    var formData = new FormData();
    formData.append('file', uploadFile.files[0]);
    formData.append('type', uploadType.value);
    formData.append('notes', document.getElementById('uploadNotes').value || '');

    // Get CSRF token
    var csrfMeta = document.querySelector('meta[name="csrf-token"]');
    var headers = {};
    if (csrfMeta) headers['X-CSRF-TOKEN'] = csrfMeta.content;

    fetch('/api/documents/upload', {
        method: 'POST',
        headers: headers,
        body: formData
    })
    .then(function(resp) {
        if (!resp.ok) return resp.json().then(function(err){ throw err; });
        return resp.json();
    })
    .then(function(data) {
        bootstrap.Modal.getInstance(document.getElementById('uploadModal')).hide();
        self.disabled = false;
        self.innerHTML = '<i class="ri-upload-2-line me-1"></i><span data-lang="wc-cp-upload-btn">' + _t('wc-cp-upload-btn','Upload') + '</span>';

        // Add document to the table dynamically
        if (data.document) {
            addDocumentToTable(data.document);
        }

        Swal.fire({
            icon: 'success',
            title: _t('wc-cp-swal-uploaded','Uploaded!'),
            html: '<div class="text-center"><i class="ri-shield-check-line text-success fs-1 mb-2 d-block"></i>'
                + '<strong>' + data.document.name + '</strong><br>'
                + '<span class="badge bg-success-subtle text-success mt-1"><i class="ri-lock-line me-1"></i>Encrypted & Stored</span><br>'
                + '<span class="text-muted small">' + _t('wc-cp-swal-upload-text','Document uploaded successfully. Our team will review it shortly.') + '</span></div>',
            confirmButtonColor: '#015EA7'
        });
    })
    .catch(function(err) {
        self.disabled = false;
        self.innerHTML = '<i class="ri-upload-2-line me-1"></i><span data-lang="wc-cp-upload-btn">' + _t('wc-cp-upload-btn','Upload') + '</span>';

        var msg = 'Upload failed. Please try again.';
        if (err && err.errors) {
            var first = Object.values(err.errors)[0];
            msg = Array.isArray(first) ? first[0] : first;
        } else if (err && err.message) {
            msg = err.message;
        }

        Swal.fire({
            icon: 'error',
            title: _t('wc-cp-swal-error','Error'),
            text: msg,
            confirmButtonColor: '#dc3545'
        });
    });
});

// Add uploaded doc row to the documents table
function addDocumentToTable(doc) {
    var tbody = document.querySelector('#sec-documents table tbody');
    if (!tbody) return;

    // Determine icon by mime
    var iconClass = 'ri-file-pdf-2-line';
    var iconColor = 'danger';
    if (doc.name.match(/\.(jpg|jpeg|png)$/i)) { iconClass = 'ri-file-image-line'; iconColor = 'success'; }
    else if (doc.name.match(/\.(doc|docx)$/i)) { iconClass = 'ri-file-word-2-line'; iconColor = 'primary'; }

    var tr = document.createElement('tr');
    tr.classList.add('table-success');
    tr.style.animation = 'fadeIn .5s';
    tr.innerHTML = '<td><div class="d-flex align-items-center gap-2">'
        + '<div class="doc-icon bg-' + iconColor + '-subtle text-' + iconColor + '" style="width:32px;height:32px;font-size:.875rem;"><i class="' + iconClass + '"></i></div>'
        + '<span class="fw-medium small">' + doc.name + '</span>'
        + '<i class="ri-lock-line text-success ms-1" title="Encrypted"></i>'
        + '</div></td>'
        + '<td><span class="badge bg-' + (doc.type_badge || 'secondary') + '-subtle text-' + (doc.type_badge || 'secondary') + '">' + doc.type_label + '</span></td>'
        + '<td class="text-muted small">' + doc.uploaded_at + '</td>'
        + '<td><span class="badge bg-warning-subtle text-warning">' + doc.status + '</span></td>'
        + '<td class="text-end"><a href="/api/documents/' + doc.id + '/download" class="btn btn-sm btn-light"><i class="ri-download-line"></i></a></td>';

    // Insert at top (before first required row or at end)
    var firstWarning = tbody.querySelector('tr.table-warning');
    if (firstWarning) {
        tbody.insertBefore(tr, firstWarning);
    } else {
        tbody.appendChild(tr);
    }

    // Update document count
    updateDocCount(1);
}

// Update stat counter
function updateDocCount(delta) {
    var statCards = document.querySelectorAll('#sec-main .stat-card h5');
    if (statCards[0]) {
        var current = parseInt(statCards[0].textContent) || 0;
        statCards[0].textContent = current + delta;
    }
}

// Load real uploaded documents on page load
function loadUploadedDocuments() {
    fetch('/api/documents')
    .then(function(r){ return r.json(); })
    .then(function(docs){
        if (!docs || !docs.length) return;
        docs.forEach(function(doc){ addDocumentToTable(doc); });
    })
    .catch(function(e){ console.log('Could not load vault documents:', e); });
}
loadUploadedDocuments();

/* ═══════════ Message Attachment Validation ═══════════ */
var msgAttach = document.getElementById('msgAttachFile');
var msgError  = document.getElementById('msgAttachError');
if (msgAttach) {
    msgAttach.addEventListener('change', function() {
        var file = this.files && this.files[0];
        if (!file) return;
        msgError.style.cssText = 'display:none !important;';

        var ext = '.' + file.name.split('.').pop().toLowerCase();
        var allowed = ['.pdf','.jpg','.jpeg','.png','.doc','.docx'];
        if (allowed.indexOf(ext) === -1) {
            msgError.textContent = 'Wrong format! Allowed: PDF, JPG, PNG, DOC/DOCX. You selected: ' + ext;
            msgError.style.cssText = 'display:block !important;color:#dc3545;font-size:.8rem;margin-top:.25rem;';
            this.value = '';
            return;
        }
        if (file.size > 10 * 1024 * 1024) {
            msgError.textContent = 'File too large! Max 10 MB. Your file: ' + (file.size/1048576).toFixed(1) + ' MB';
            msgError.style.cssText = 'display:block !important;color:#dc3545;font-size:.8rem;margin-top:.25rem;';
            this.value = '';
            return;
        }
    });
}

/* ═══════════ Send Message ═══════════ */
document.getElementById('btnSendMsg').addEventListener('click', function(){
    this.disabled = true;
    this.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Sending...';
    var self = this;
    setTimeout(function(){
        bootstrap.Modal.getInstance(document.getElementById('newMsgModal')).hide();
        self.disabled = false;
        self.innerHTML = '<i class="ri-send-plane-line me-1"></i><span data-lang="wc-cp-send-btn">' + _t('wc-cp-send-btn','Send') + '</span>';
        Swal.fire({icon:'success', title: _t('wc-cp-swal-sent','Sent'), text: _t('wc-cp-swal-sent-text','Your message has been sent.'), confirmButtonColor:'#015EA7'});
    }, 1000);
});

/* ═══════════ Edit Profile ═══════════ */
document.getElementById('btnEditProfile').addEventListener('click', function(){
    Swal.fire({icon:'info', title: _t('wc-cp-edit-profile','Edit Profile'), text: _t('wc-cp-swal-edit-text','Profile editing feature coming soon. Contact your manager for data updates.'), confirmButtonColor:'#015EA7'});
});

/* ═══════════ Language ═══════════ */
var langSelect = document.getElementById('langSelect');
if (langSelect && window.WcI18n) {
    langSelect.value = WcI18n.current();
    langSelect.addEventListener('change', function(){ WcI18n.switchLang(this.value); });
}
</script>
@endsection
