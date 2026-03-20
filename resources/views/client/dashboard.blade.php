@extends('partials.layouts.master-client')
@section('title', 'Dashboard — WinCase Client Portal')

@section('nav')
<div class="d-none d-md-flex align-items-center gap-1">
    <a href="/client-dashboard" class="nav-link active"><i class="ri-dashboard-line me-1"></i><span data-lang="wc-dashboard">Dashboard</span></a>
    <a href="#" class="nav-link" onclick="showSection('documents')"><i class="ri-file-text-line me-1"></i><span data-lang="wc-documents">Documents</span></a>
    <a href="#" class="nav-link" onclick="showSection('messages')"><i class="ri-message-2-line me-1"></i><span data-lang="wc-cp-messages">Messages</span> <span class="badge bg-danger rounded-pill ms-1" id="nav-unread-badge" style="display:none;">0</span></a>
    <a href="#" class="nav-link" onclick="showSection('payments')"><i class="ri-bank-card-line me-1"></i><span data-lang="wc-payments">Payments</span></a>
    <a href="#" class="nav-link" onclick="showSection('profile')"><i class="ri-user-line me-1"></i><span data-lang="wc-profile">Profile</span></a>
</div>
@endsection

@section('nav-right')
<div class="dropdown">
    <button class="btn btn-sm btn-light d-flex align-items-center gap-2" data-bs-toggle="dropdown">
        <div class="rounded-circle bg-success text-white d-flex align-items-center justify-content-center" id="nav-avatar" style="width:30px;height:30px;font-size:.75rem;font-weight:700;">--</div>
        <span class="d-none d-sm-inline small fw-medium" id="nav-username">...</span>
    </button>
    <ul class="dropdown-menu dropdown-menu-end">
        <li><a class="dropdown-item" href="#" onclick="showSection('profile')"><i class="ri-user-line me-2"></i><span data-lang="wc-cp-my-profile">My Profile</span></a></li>
        <li><a class="dropdown-item" href="#" onclick="showSection('settings')"><i class="ri-settings-3-line me-2"></i><span data-lang="wc-settings">Settings</span></a></li>
        <li><hr class="dropdown-divider"></li>
        <li><a class="dropdown-item text-danger" href="#" onclick="doLogout()"><i class="ri-logout-box-line me-2"></i><span data-lang="wc-sign-out">Sign Out</span></a></li>
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
    .wc-spinner-overlay {
        position: fixed; top: 0; left: 0; width: 100%; height: 100%;
        background: rgba(255,255,255,.85); z-index: 9999;
        display: flex; align-items: center; justify-content: center; flex-direction: column;
    }
    [data-bs-theme="dark"] .wc-spinner-overlay { background: rgba(21,24,33,.9); }
    @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
</style>
@endsection

@section('content')

<!-- Loading Spinner -->
<div id="pageSpinner" class="wc-spinner-overlay">
    <div class="spinner-border text-success" style="width:3rem;height:3rem;" role="status"></div>
    <p class="mt-3 text-muted fw-medium">Loading your portal...</p>
</div>

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
                    <h4 class="fw-bold mb-1"><span data-lang="wc-cp-welcome-back">Welcome back</span>, <span id="welcome-name">...</span>!</h4>
                    <p class="mb-2 opacity-75" data-lang="wc-cp-welcome-text">Your immigration case is being processed. Here's your latest status.</p>
                    <div class="d-flex gap-2 flex-wrap" id="welcome-badges">
                        <span class="badge bg-white" style="color:#015EA7;" id="welcome-case-number"><i class="ri-briefcase-line me-1"></i>—</span>
                        <span class="badge bg-white bg-opacity-25 text-white" id="welcome-service-type">—</span>
                    </div>
                </div>
                <div class="col-md-4 text-md-end mt-3 mt-md-0">
                    <div class="d-inline-block text-center">
                        <div style="font-size:2.5rem;font-weight:800;line-height:1;" id="welcome-progress">0%</div>
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
                    <h5 class="fw-bold mb-0" id="stat-documents">0</h5>
                    <small class="text-muted" data-lang="wc-documents">Documents</small>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card stat-card h-100">
                <div class="card-body p-3 text-center">
                    <div class="rounded-circle bg-warning-subtle text-warning mx-auto mb-2 d-flex align-items-center justify-content-center" style="width:44px;height:44px;"><i class="ri-time-line fs-5"></i></div>
                    <h5 class="fw-bold mb-0" id="stat-pending">0</h5>
                    <small class="text-muted" data-lang="wc-cp-pending-actions">Pending Actions</small>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card stat-card h-100">
                <div class="card-body p-3 text-center">
                    <div class="rounded-circle bg-success-subtle text-success mx-auto mb-2 d-flex align-items-center justify-content-center" style="width:44px;height:44px;"><i class="ri-message-2-line fs-5"></i></div>
                    <h5 class="fw-bold mb-0" id="stat-messages">0</h5>
                    <small class="text-muted" data-lang="wc-cp-new-messages">New Messages</small>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card stat-card h-100">
                <div class="card-body p-3 text-center">
                    <div class="rounded-circle bg-info-subtle text-info mx-auto mb-2 d-flex align-items-center justify-content-center" style="width:44px;height:44px;"><i class="ri-calendar-check-line fs-5"></i></div>
                    <h5 class="fw-bold mb-0" id="stat-next-appt">—</h5>
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
                    <span class="badge bg-warning-subtle text-warning" id="timeline-stage-badge">—</span>
                </div>
                <div class="card-body">
                    <div class="case-timeline" id="case-timeline-container">
                        <div class="text-center text-muted py-4">
                            <div class="spinner-border spinner-border-sm me-2"></div>Loading timeline...
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
                    <div class="list-group list-group-flush" id="pending-actions-list">
                        <div class="text-center text-muted py-4">
                            <div class="spinner-border spinner-border-sm me-2"></div>Loading...
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
                        <tr><td class="text-muted" style="width:40%" data-lang="wc-cp-case-number">Case Number</td><td class="fw-medium" id="case-number">—</td></tr>
                        <tr><td class="text-muted" data-lang="wc-cp-type">Type</td><td id="case-type">—</td></tr>
                        <tr><td class="text-muted" data-lang="wc-cp-submitted">Submitted</td><td id="case-submitted">—</td></tr>
                        <tr><td class="text-muted" data-lang="wc-cp-voivodeship">Voivodeship</td><td id="case-voivodeship">—</td></tr>
                        <tr><td class="text-muted" data-lang="wc-cp-priority">Priority</td><td id="case-priority">—</td></tr>
                        <tr><td class="text-muted" data-lang="wc-cp-assigned-manager">Assigned Manager</td><td id="case-manager">—</td></tr>
                        <tr><td class="text-muted" data-lang="wc-cp-status">Status</td><td id="case-status-cell">—</td></tr>
                    </table>
                </div>
            </div>

            <!-- Your Manager -->
            <div class="card border-0 shadow-sm mt-4" style="border-radius:.75rem;">
                <div class="card-header bg-transparent border-0">
                    <h6 class="fw-semibold mb-0"><i class="ri-customer-service-2-line me-1 text-success"></i><span data-lang="wc-cp-your-manager">Your Manager</span></h6>
                </div>
                <div class="card-body pt-0" id="manager-card-body">
                    <div class="text-center text-muted py-3">Loading...</div>
                </div>
            </div>

            <!-- Upcoming Events -->
            <div class="card border-0 shadow-sm mt-4" style="border-radius:.75rem;">
                <div class="card-header bg-transparent border-0">
                    <h6 class="fw-semibold mb-0"><i class="ri-calendar-line me-1 text-success"></i><span data-lang="wc-cp-upcoming">Upcoming</span></h6>
                </div>
                <div class="card-body pt-0" id="upcoming-events-body">
                    <div class="text-center text-muted py-3">Loading...</div>
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
                        <span class="fw-semibold" id="summary-total-fee">—</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted small" data-lang="wc-cp-paid">Paid</span>
                        <span class="fw-semibold text-success" id="summary-paid">—</span>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-muted small" data-lang="wc-cp-remaining">Remaining</span>
                        <span class="fw-semibold text-danger" id="summary-remaining">—</span>
                    </div>
                    <div class="progress" style="height:6px;">
                        <div class="progress-bar bg-success" id="summary-progress-bar" style="width:0%"></div>
                    </div>
                    <small class="text-muted d-block mt-1" id="summary-progress-text">—</small>
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
                    <tbody id="documents-tbody">
                        <tr><td colspan="5" class="text-center text-muted py-4"><div class="spinner-border spinner-border-sm me-2"></div>Loading documents...</td></tr>
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
            <div class="list-group list-group-flush" id="messages-list">
                <div class="text-center text-muted py-4"><div class="spinner-border spinner-border-sm me-2"></div>Loading messages...</div>
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
                    <h4 class="fw-bold text-success mb-0" id="pay-total-paid">—</h4>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card stat-card border-start border-danger border-3">
                <div class="card-body p-3">
                    <small class="text-muted" data-lang="wc-cp-remaining">Remaining</small>
                    <h4 class="fw-bold text-danger mb-0" id="pay-remaining">—</h4>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card stat-card border-start border-warning border-3">
                <div class="card-body p-3">
                    <small class="text-muted" data-lang="wc-cp-next-payment">Next Payment</small>
                    <h4 class="fw-bold mb-0" id="pay-next-date">—</h4>
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
                    <tbody id="payments-tbody">
                        <tr><td colspan="6" class="text-center text-muted py-4"><div class="spinner-border spinner-border-sm me-2"></div>Loading payments...</td></tr>
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

    <!-- Profile View Mode -->
    <div id="profile-view-mode">
        <div class="row g-4">
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm text-center" style="border-radius:.75rem;">
                    <div class="card-body p-4">
                        <div class="rounded-circle bg-success text-white mx-auto d-flex align-items-center justify-content-center mb-3" id="profile-avatar" style="width:80px;height:80px;font-size:2rem;font-weight:700;">--</div>
                        <h5 class="fw-bold mb-1" id="profile-fullname">—</h5>
                        <p class="text-muted small mb-2"><span data-lang="wc-cp-client-since">Client since</span> <span id="profile-since">—</span></p>
                        <span class="badge bg-success-subtle text-success" id="profile-status-badge" data-lang="wc-cp-active-client">Active Client</span>
                        <hr>
                        <div class="text-start">
                            <p class="small mb-1"><i class="ri-mail-line me-2 text-muted"></i><span id="profile-email">—</span></p>
                            <p class="small mb-1"><i class="ri-phone-line me-2 text-muted"></i><span id="profile-phone">—</span></p>
                            <p class="small mb-1"><i class="ri-map-pin-line me-2 text-muted"></i><span id="profile-city">—</span></p>
                            <p class="small mb-0"><i class="ri-flag-line me-2 text-muted"></i><span id="profile-nationality">—</span></p>
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
                            <div class="col-md-6"><label class="small text-muted" data-lang="wc-cp-full-name">Full Name</label><p class="fw-medium mb-0" id="profile-name-full">—</p></div>
                            <div class="col-md-6"><label class="small text-muted" data-lang="wc-cp-dob">Date of Birth</label><p class="fw-medium mb-0" id="profile-dob">—</p></div>
                            <div class="col-md-6"><label class="small text-muted" data-lang="wc-cp-nationality">Nationality</label><p class="fw-medium mb-0" id="profile-nationality-full">—</p></div>
                            <div class="col-md-6"><label class="small text-muted" data-lang="wc-cp-passport">Passport</label><p class="fw-medium mb-0" id="profile-passport">—</p></div>
                            <div class="col-md-6"><label class="small text-muted" data-lang="wc-cp-pesel">PESEL</label><p class="fw-medium mb-0" id="profile-pesel">—</p></div>
                            <div class="col-md-6"><label class="small text-muted" data-lang="wc-cp-phone">Phone</label><p class="fw-medium mb-0" id="profile-phone-full">—</p></div>
                            <div class="col-md-12"><label class="small text-muted" data-lang="wc-cp-address-poland">Address in Poland</label><p class="fw-medium mb-0" id="profile-address">—</p></div>
                        </div>
                    </div>
                </div>
                <div class="card border-0 shadow-sm mt-3" style="border-radius:.75rem;">
                    <div class="card-header bg-transparent border-0">
                        <h6 class="fw-semibold mb-0" data-lang="wc-cp-employment-title">Employment</h6>
                    </div>
                    <div class="card-body pt-0">
                        <div class="row g-3">
                            <div class="col-md-6"><label class="small text-muted" data-lang="wc-cp-employer">Employer</label><p class="fw-medium mb-0" id="profile-employer">—</p></div>
                            <div class="col-md-6"><label class="small text-muted" data-lang="wc-cp-position">Position</label><p class="fw-medium mb-0" id="profile-position">—</p></div>
                            <div class="col-md-6"><label class="small text-muted" data-lang="wc-cp-nip">NIP</label><p class="fw-medium mb-0" id="profile-nip">—</p></div>
                            <div class="col-md-6"><label class="small text-muted" data-lang="wc-cp-language">Preferred Language</label><p class="fw-medium mb-0" id="profile-lang">—</p></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Profile Edit Mode -->
    <div id="profile-edit-mode" style="display:none;">
        <div class="card border-0 shadow-sm" style="border-radius:.75rem;">
            <div class="card-header bg-transparent border-0 d-flex justify-content-between align-items-center">
                <h6 class="fw-semibold mb-0" data-lang="wc-cp-edit-profile">Edit Profile</h6>
                <button class="btn btn-sm btn-outline-secondary" onclick="toggleProfileEdit(false)"><i class="ri-close-line me-1"></i>Cancel</button>
            </div>
            <div class="card-body pt-0">
                <form id="profileEditForm">
                    <div class="row g-3">
                        <div class="col-md-6"><label class="form-label small" data-lang="wc-cp-first-name">First Name</label><input type="text" class="form-control" id="edit-first-name"></div>
                        <div class="col-md-6"><label class="form-label small" data-lang="wc-cp-last-name">Last Name</label><input type="text" class="form-control" id="edit-last-name"></div>
                        <div class="col-md-6"><label class="form-label small" data-lang="wc-cp-phone">Phone</label><input type="text" class="form-control" id="edit-phone"></div>
                        <div class="col-md-6"><label class="form-label small" data-lang="wc-cp-email">Email</label><input type="email" class="form-control" id="edit-email" disabled></div>
                        <div class="col-md-12"><label class="form-label small" data-lang="wc-cp-address-poland">Address</label><input type="text" class="form-control" id="edit-address"></div>
                        <div class="col-md-4"><label class="form-label small" data-lang="wc-cp-city">City</label><input type="text" class="form-control" id="edit-city"></div>
                        <div class="col-md-4"><label class="form-label small" data-lang="wc-cp-postal">Postal Code</label><input type="text" class="form-control" id="edit-postal"></div>
                        <div class="col-md-4"><label class="form-label small" data-lang="wc-cp-voivodeship">Voivodeship</label><input type="text" class="form-control" id="edit-voivodeship"></div>
                        <div class="col-12 mt-3">
                            <button type="submit" class="btn btn-success" id="btnSaveProfile"><i class="ri-save-line me-1"></i><span data-lang="wc-cp-save">Save Changes</span></button>
                        </div>
                    </div>
                </form>
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
                    <form id="passwordForm">
                        <div class="mb-3"><label class="form-label small" data-lang="wc-cp-current-password">Current Password</label><input type="password" class="form-control" id="pw-current"></div>
                        <div class="mb-3"><label class="form-label small" data-lang="wc-cp-new-password">New Password</label><input type="password" class="form-control" id="pw-new"></div>
                        <div class="mb-3"><label class="form-label small" data-lang="wc-cp-confirm-password">Confirm New Password</label><input type="password" class="form-control" id="pw-confirm"></div>
                        <button type="submit" class="btn btn-success btn-sm" id="btnChangePassword" data-lang="wc-cp-update-password">Update Password</button>
                    </form>
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
                        <option value="photo" data-lang="wc-cp-opt-photo">Photo 3.5x4.5</option>
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
                    <label class="form-label fw-medium" data-lang="wc-cp-subject-label">Subject</label>
                    <input type="text" class="form-control" id="msgSubject" placeholder="Message subject" data-lang-placeholder="wc-cp-subject-placeholder">
                </div>
                <div class="mb-3">
                    <label class="form-label fw-medium" data-lang="wc-cp-message-label">Message</label>
                    <textarea class="form-control" rows="5" id="msgBody" placeholder="Write your message..." data-lang-placeholder="wc-cp-message-placeholder"></textarea>
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
   API Helper & Auth
   ═══════════════════════════════════════════ */
var API_BASE = '/api/v1/client-portal';
var _token = localStorage.getItem('wc_token');
if (!_token) { window.location.href = '/client-login'; }

function api(method, url, body) {
    var opts = {
        method: method.toUpperCase(),
        headers: { 'Authorization': 'Bearer ' + _token, 'Accept': 'application/json' }
    };
    if (body) {
        // Always use FormData for POST/PUT/PATCH (Apache/cPanel compatibility)
        if (body instanceof FormData) {
            opts.body = body;
        } else {
            var fd = new FormData();
            Object.keys(body).forEach(function(k) { fd.append(k, body[k]); });
            opts.body = fd;
        }
    }
    return fetch(API_BASE + url, opts).then(function(r) {
        if (r.status === 401) {
            localStorage.removeItem('wc_token');
            localStorage.removeItem('wc_user');
            window.location.href = '/client-login';
            throw new Error('Unauthorized');
        }
        if (!r.ok) return r.json().then(function(err) { throw err; });
        return r.json();
    });
}

function doLogout() {
    localStorage.removeItem('wc_token');
    localStorage.removeItem('wc_user');
    window.location.href = '/client-login';
}

/* ═══════════════════════════════════════════
   Formatters
   ═══════════════════════════════════════════ */
var _t = function(k, fb) { return window.WcI18n ? WcI18n.t(k) : fb; };

function fmtDate(d) {
    if (!d) return '—';
    var dt = new Date(d);
    if (isNaN(dt.getTime())) return d;
    var months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
    return dt.getDate() + ' ' + months[dt.getMonth()] + ' ' + dt.getFullYear();
}

function fmtDateShort(d) {
    if (!d) return '—';
    var dt = new Date(d);
    if (isNaN(dt.getTime())) return d;
    var months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
    return dt.getDate() + ' ' + months[dt.getMonth()];
}

function fmtMonthYear(d) {
    if (!d) return '—';
    var dt = new Date(d);
    if (isNaN(dt.getTime())) return d;
    var months = ['January','February','March','April','May','June','July','August','September','October','November','December'];
    return months[dt.getMonth()] + ' ' + dt.getFullYear();
}

function fmtMoney(amount) {
    if (amount === null || amount === undefined) return '—';
    var n = parseFloat(amount);
    if (isNaN(n)) return amount;
    return n.toLocaleString('pl-PL', { minimumFractionDigits: 0, maximumFractionDigits: 0 }).replace(/,/g, ' ') + ' PLN';
}

function initials(name) {
    if (!name) return '--';
    var parts = name.trim().split(/\s+/);
    if (parts.length >= 2) return (parts[0][0] + parts[parts.length - 1][0]).toUpperCase();
    return parts[0].substring(0, 2).toUpperCase();
}

function esc(s) {
    if (!s) return '';
    var d = document.createElement('div');
    d.textContent = s;
    return d.innerHTML;
}

function statusBadge(status) {
    var map = {
        'approved': { cls: 'success', label: 'Approved' },
        'pending': { cls: 'warning', label: 'Pending' },
        'required': { cls: 'danger', label: 'Required' },
        'submitted': { cls: 'success', label: 'Submitted' },
        'review': { cls: 'info', label: 'Under Review' },
        'under_review': { cls: 'info', label: 'Under Review' },
        'rejected': { cls: 'danger', label: 'Rejected' },
        'paid': { cls: 'success', label: 'Paid' },
        'unpaid': { cls: 'danger', label: 'Unpaid' },
        'overdue': { cls: 'danger', label: 'Overdue' },
        'upcoming': { cls: 'secondary', label: 'Upcoming' },
        'in_progress': { cls: 'warning', label: 'In Progress' },
        'active': { cls: 'success', label: 'Active' },
        'completed': { cls: 'success', label: 'Completed' },
        'cancelled': { cls: 'secondary', label: 'Cancelled' }
    };
    var key = (status || '').toLowerCase().replace(/\s+/g, '_');
    var m = map[key] || { cls: 'secondary', label: status || '—' };
    return '<span class="badge bg-' + m.cls + '-subtle text-' + m.cls + '">' + esc(m.label) + '</span>';
}

function docIcon(name) {
    if (!name) return { icon: 'ri-file-line', color: 'secondary' };
    var ext = name.split('.').pop().toLowerCase();
    if (ext === 'pdf') return { icon: 'ri-file-pdf-2-line', color: 'danger' };
    if (['jpg','jpeg','png','gif'].indexOf(ext) >= 0) return { icon: 'ri-file-image-line', color: 'success' };
    if (['doc','docx'].indexOf(ext) >= 0) return { icon: 'ri-file-word-2-line', color: 'primary' };
    return { icon: 'ri-file-line', color: 'secondary' };
}

function hideSpinner() {
    var sp = document.getElementById('pageSpinner');
    if (sp) sp.style.display = 'none';
}

/* ═══════════════════════════════════════════
   Section tracking — load data on first visit
   ═══════════════════════════════════════════ */
var _sectionLoaded = { main: false, documents: false, messages: false, payments: false, profile: false, settings: true };

/* ═══════════════════════════════════════════
   MAIN DASHBOARD — load on page init
   ═══════════════════════════════════════════ */
var _dashData = null;

function loadDashboard() {
    api('GET', '/dashboard').then(function(data) {
        _dashData = data;
        _sectionLoaded.main = true;
        renderDashboard(data);
        hideSpinner();
    }).catch(function(err) {
        console.error('Dashboard load error:', err);
        hideSpinner();
        if (err && err.message === 'Unauthorized') return;
        Swal.fire({ icon: 'error', title: 'Error', text: 'Failed to load dashboard data. Please refresh.', confirmButtonColor: '#015EA7' });
    });
}

function renderDashboard(data) {
    var user = data.user || {};
    var client = data.client;
    var activeCase = data.active_case;
    var manager = data.manager;
    var stats = data.stats || {};
    var pending = data.pending_actions || {};
    var events = data.upcoming_events || [];
    var timeline = data.case_timeline || [];

    // -- Nav avatar & name --
    var ini = initials(user.name);
    document.getElementById('nav-avatar').textContent = ini;
    document.getElementById('nav-username').textContent = user.name || 'User';

    // -- Welcome section --
    var firstName = user.name ? user.name.split(' ')[0] : 'User';
    document.getElementById('welcome-name').textContent = firstName;

    if (activeCase) {
        document.getElementById('welcome-case-number').innerHTML = '<i class="ri-briefcase-line me-1"></i>' + esc(activeCase.case_number || '—');
        document.getElementById('welcome-service-type').textContent = activeCase.service_type || '—';
        document.getElementById('welcome-progress').textContent = (activeCase.progress || 0) + '%';
    } else {
        document.getElementById('welcome-case-number').innerHTML = '<i class="ri-briefcase-line me-1"></i>No active case';
        document.getElementById('welcome-service-type').textContent = '';
        document.getElementById('welcome-progress').textContent = '—';
    }

    // -- Stats cards --
    document.getElementById('stat-documents').textContent = stats.documents_total || 0;
    var pendingCount = ((pending.documents || []).length) + ((pending.tasks || []).length);
    document.getElementById('stat-pending').textContent = pendingCount;
    document.getElementById('stat-messages').textContent = stats.unread_messages || 0;

    // Unread badge in nav
    var unread = stats.unread_messages || 0;
    var badge = document.getElementById('nav-unread-badge');
    if (unread > 0) {
        badge.textContent = unread;
        badge.style.display = 'inline-block';
    }

    // Next appointment from events
    if (events.length > 0) {
        document.getElementById('stat-next-appt').textContent = fmtDateShort(events[0].date || events[0].start_date);
    } else {
        document.getElementById('stat-next-appt').textContent = '—';
    }

    // -- Case Details --
    if (activeCase) {
        document.getElementById('case-number').textContent = activeCase.case_number || '—';
        document.getElementById('case-type').textContent = activeCase.service_type || '—';
        document.getElementById('case-submitted').textContent = fmtDate(activeCase.submission_date);
        document.getElementById('case-voivodeship').textContent = activeCase.voivodeship || '—';
        document.getElementById('case-priority').textContent = activeCase.priority || '—';
        document.getElementById('case-manager').textContent = manager ? manager.name : '—';
        document.getElementById('case-status-cell').innerHTML = statusBadge(activeCase.status);
    } else {
        document.getElementById('case-number').textContent = '—';
        document.getElementById('case-type').textContent = 'No active case';
    }

    // -- Manager card --
    renderManager(manager);

    // -- Upcoming events --
    renderUpcomingEvents(events);

    // -- Payments summary (from stats) --
    document.getElementById('summary-total-fee').textContent = fmtMoney(stats.total_fee);
    document.getElementById('summary-paid').textContent = fmtMoney(stats.total_paid);
    document.getElementById('summary-remaining').textContent = fmtMoney(stats.remaining);
    var paidPct = stats.paid_percentage || 0;
    document.getElementById('summary-progress-bar').style.width = paidPct + '%';
    document.getElementById('summary-progress-text').textContent = paidPct + '% paid';

    // -- Timeline --
    renderTimeline(timeline);

    // -- Pending actions --
    renderPendingActions(pending);

    // If no client profile
    if (!client) {
        var mainEl = document.getElementById('sec-main');
        var alertDiv = document.createElement('div');
        alertDiv.className = 'alert alert-warning mt-3';
        alertDiv.innerHTML = '<i class="ri-information-line me-1"></i><strong>Profile not linked.</strong> Please contact your manager or support to link your client profile.';
        mainEl.insertBefore(alertDiv, mainEl.children[1]);
    }
}

function renderManager(manager) {
    var el = document.getElementById('manager-card-body');
    if (!manager) {
        el.innerHTML = '<p class="text-muted small">No manager assigned yet.</p>';
        return;
    }
    var ini = initials(manager.name);
    var html = '<div class="d-flex align-items-center gap-3 mb-3">'
        + '<div class="rounded-circle bg-success text-white d-flex align-items-center justify-content-center" style="width:50px;height:50px;font-weight:700;">' + esc(ini) + '</div>'
        + '<div>'
        + '<h6 class="fw-semibold mb-0">' + esc(manager.name) + '</h6>'
        + '<small class="text-muted">' + esc(manager.position || 'Immigration Consultant') + '</small>'
        + '</div></div>'
        + '<div class="d-flex flex-column gap-2">';
    if (manager.phone) {
        html += '<a href="tel:' + esc(manager.phone) + '" class="btn btn-sm btn-outline-secondary text-start"><i class="ri-phone-line me-2"></i>' + esc(manager.phone) + '</a>';
    }
    if (manager.email) {
        html += '<a href="mailto:' + esc(manager.email) + '" class="btn btn-sm btn-outline-secondary text-start"><i class="ri-mail-line me-2"></i>' + esc(manager.email) + '</a>';
    }
    html += '<a href="mailto:info@wincase.eu" class="btn btn-sm btn-outline-secondary text-start"><i class="ri-mail-line me-2"></i>info@wincase.eu</a>';
    html += '<button class="btn btn-sm btn-success" onclick="showSection(\'messages\')"><i class="ri-message-2-line me-2"></i><span data-lang="wc-cp-send-message">Send Message</span></button>';
    html += '</div>';
    el.innerHTML = html;
}

function renderUpcomingEvents(events) {
    var el = document.getElementById('upcoming-events-body');
    if (!events || events.length === 0) {
        el.innerHTML = '<p class="text-muted small text-center py-2">No upcoming events.</p>';
        return;
    }
    var colors = ['success', 'warning', 'info', 'primary', 'danger'];
    var html = '';
    events.forEach(function(ev, i) {
        var dt = new Date(ev.date || ev.start_date);
        var day = isNaN(dt.getTime()) ? '—' : dt.getDate();
        var months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
        var month = isNaN(dt.getTime()) ? '' : months[dt.getMonth()];
        var color = colors[i % colors.length];
        var isLast = i === events.length - 1;
        html += '<div class="d-flex gap-3' + (isLast ? '' : ' mb-3 pb-3 border-bottom') + '">'
            + '<div class="text-center" style="min-width:44px;">'
            + '<div class="fw-bold text-' + color + '" style="font-size:1.25rem;">' + day + '</div>'
            + '<small class="text-muted">' + month + '</small>'
            + '</div><div>'
            + '<h6 class="fw-semibold mb-0 small">' + esc(ev.title || ev.name || 'Event') + '</h6>'
            + '<small class="text-muted">' + esc(ev.description || ev.location || '') + '</small>'
            + '</div></div>';
    });
    el.innerHTML = html;
}

function renderTimeline(timeline) {
    var el = document.getElementById('case-timeline-container');
    var badgeEl = document.getElementById('timeline-stage-badge');

    if (!timeline || timeline.length === 0) {
        el.innerHTML = '<p class="text-muted small text-center py-2">No timeline data. Your case progress will appear here once processing begins.</p>';
        badgeEl.textContent = '—';
        return;
    }

    // Find current stage
    var currentIdx = -1;
    var doneCount = 0;
    timeline.forEach(function(item, i) {
        var s = (item.status || '').toLowerCase();
        if (s === 'current' || s === 'in_progress') currentIdx = i;
        if (s === 'done' || s === 'completed') doneCount++;
    });

    badgeEl.textContent = 'Stage ' + (currentIdx >= 0 ? (currentIdx + 1) : doneCount) + ' / ' + timeline.length;

    var html = '';
    timeline.forEach(function(item, i) {
        var s = (item.status || '').toLowerCase();
        var cls = '';
        if (s === 'done' || s === 'completed') cls = 'done';
        else if (s === 'current' || s === 'in_progress') cls = 'current';
        var isLast = i === timeline.length - 1;
        html += '<div class="tl-item ' + cls + '"' + (isLast ? ' style="padding-bottom:0;"' : '') + '>'
            + '<div class="tl-dot"></div>'
            + '<h6 class="fw-semibold mb-0 small' + (cls === '' ? ' text-muted' : '') + '">' + esc(item.title || item.name || 'Step ' + (i+1)) + '</h6>'
            + '<small class="text-muted">' + esc(item.description || (item.date ? fmtDate(item.date) : '—')) + '</small>'
            + '</div>';
    });
    el.innerHTML = html;
}

function renderPendingActions(pending) {
    var el = document.getElementById('pending-actions-list');
    var docs = pending.documents || [];
    var tasks = pending.tasks || [];
    var all = [];

    docs.forEach(function(d) { all.push({ type: 'document', icon: 'ri-upload-cloud-2-line', color: 'danger', title: d.title || d.name || 'Upload Document', desc: d.description || '', due: d.due_date || d.deadline, action: 'upload' }); });
    tasks.forEach(function(t) { all.push({ type: 'task', icon: 'ri-file-edit-line', color: 'warning', title: t.title || t.name || 'Task', desc: t.description || '', due: t.due_date || t.deadline, action: 'view' }); });

    if (all.length === 0) {
        el.innerHTML = '<div class="text-center text-muted py-4"><i class="ri-check-double-line fs-3 d-block mb-2 text-success"></i>No pending actions. You\'re all caught up!</div>';
        return;
    }

    var html = '';
    all.forEach(function(item) {
        html += '<div class="list-group-item d-flex align-items-start gap-3 px-4 py-3">'
            + '<div class="doc-icon bg-' + item.color + '-subtle text-' + item.color + '"><i class="' + item.icon + '"></i></div>'
            + '<div class="flex-grow-1">'
            + '<h6 class="fw-semibold mb-1 small">' + esc(item.title) + '</h6>'
            + '<p class="text-muted small mb-1">' + esc(item.desc) + '</p>';
        if (item.due) {
            html += '<span class="badge bg-' + item.color + '-subtle text-' + item.color + '">Due: ' + fmtDate(item.due) + '</span>';
        }
        html += '</div>';
        if (item.action === 'upload') {
            html += '<button class="btn btn-sm btn-outline-success" onclick="showSection(\'documents\')" data-lang="wc-cp-upload-btn">Upload</button>';
        } else {
            html += '<button class="btn btn-sm btn-outline-success" data-lang="wc-cp-view-btn">View</button>';
        }
        html += '</div>';
    });
    el.innerHTML = html;
}

/* ═══════════════════════════════════════════
   DOCUMENTS SECTION
   ═══════════════════════════════════════════ */
function loadDocuments() {
    if (_sectionLoaded.documents) return;
    api('GET', '/documents').then(function(data) {
        _sectionLoaded.documents = true;
        var docs = Array.isArray(data) ? data : (data.data || data.documents || []);
        renderDocuments(docs);
    }).catch(function(err) {
        console.error('Documents load error:', err);
        document.getElementById('documents-tbody').innerHTML = '<tr><td colspan="5" class="text-center text-danger py-4">Failed to load documents.</td></tr>';
    });
}

function renderDocuments(docs) {
    var tbody = document.getElementById('documents-tbody');
    if (!docs || docs.length === 0) {
        tbody.innerHTML = '<tr><td colspan="5" class="text-center text-muted py-4"><i class="ri-file-list-3-line fs-3 d-block mb-2"></i>No documents yet. Upload your first document above.</td></tr>';
        return;
    }

    var html = '';
    docs.forEach(function(doc) {
        var ic = docIcon(doc.name || doc.file_name || doc.original_name || '');
        var name = doc.name || doc.file_name || doc.original_name || 'Document';
        var docType = doc.type || doc.document_type || '';
        var uploaded = doc.uploaded_at || doc.created_at || '';
        var status = doc.status || 'pending';
        var isRequired = status.toLowerCase() === 'required';

        var typeBadgeColor = 'secondary';
        var typeMap = { 'identity': 'primary', 'passport': 'primary', 'employment': 'info', 'photo': 'secondary', 'address': 'warning', 'insurance': 'info', 'health': 'info', 'tax': 'secondary', 'application': 'primary', 'legal': 'primary', 'financial': 'warning', 'bank': 'warning', 'zus': 'info', 'pit': 'secondary', 'other': 'secondary' };
        typeBadgeColor = typeMap[docType.toLowerCase()] || 'secondary';

        html += '<tr' + (isRequired ? ' class="table-warning"' : '') + '>';
        html += '<td><div class="d-flex align-items-center gap-2">'
            + '<div class="doc-icon bg-' + ic.color + '-subtle text-' + ic.color + '" style="width:32px;height:32px;font-size:.875rem;"><i class="' + ic.icon + '"></i></div>'
            + '<span class="fw-medium small' + (isRequired ? ' text-warning' : '') + '">' + esc(name) + '</span></div></td>';
        html += '<td><span class="badge bg-' + typeBadgeColor + '-subtle text-' + typeBadgeColor + '">' + esc(docType) + '</span></td>';
        html += '<td class="text-muted small">' + (uploaded ? fmtDate(uploaded) : '—') + '</td>';
        html += '<td>' + statusBadge(status) + '</td>';
        html += '<td class="text-end">';
        if (isRequired) {
            html += '<button class="btn btn-sm btn-warning text-white" data-bs-toggle="modal" data-bs-target="#uploadModal"><i class="ri-upload-2-line me-1"></i><span data-lang="wc-cp-upload-btn">Upload</span></button>';
        } else if (doc.id) {
            html += '<button class="btn btn-sm btn-light" onclick="downloadDoc(' + doc.id + ')"><i class="ri-download-line"></i></button>';
        }
        html += '</td></tr>';
    });
    tbody.innerHTML = html;
}

function downloadDoc(id) {
    window.open(API_BASE + '/documents/' + id + '/download?token=' + _token, '_blank');
}

/* ═══════════════════════════════════════════
   MESSAGES SECTION
   ═══════════════════════════════════════════ */
function loadMessages() {
    if (_sectionLoaded.messages) return;
    api('GET', '/messages').then(function(data) {
        _sectionLoaded.messages = true;
        var msgs = Array.isArray(data) ? data : (data.data || data.messages || []);
        renderMessages(msgs);
    }).catch(function(err) {
        console.error('Messages load error:', err);
        document.getElementById('messages-list').innerHTML = '<div class="text-center text-danger py-4">Failed to load messages.</div>';
    });
}

function renderMessages(msgs) {
    var el = document.getElementById('messages-list');
    if (!msgs || msgs.length === 0) {
        el.innerHTML = '<div class="text-center text-muted py-4"><i class="ri-message-2-line fs-3 d-block mb-2"></i>No messages yet.</div>';
        return;
    }

    var currentUserId = _dashData && _dashData.user ? _dashData.user.id : null;
    var html = '';
    msgs.forEach(function(msg) {
        var isOwn = msg.sender_id == currentUserId || msg.is_mine;
        var isUnread = !msg.read_at && !isOwn;
        var senderName = isOwn ? 'You' : (msg.sender_name || msg.sender || 'WinCase');
        var ini = isOwn ? initials(_dashData && _dashData.user ? _dashData.user.name : 'You') : initials(senderName);
        var bgColor = isOwn ? 'info' : (senderName.indexOf('System') >= 0 || senderName.indexOf('WinCase') >= 0 ? 'primary' : 'success');
        var date = msg.created_at || msg.sent_at || '';
        var subject = msg.subject || '';
        var body = msg.body || msg.message || msg.content || '';
        // Truncate body for preview
        if (body.length > 200) body = body.substring(0, 200) + '...';

        html += '<div class="list-group-item px-4 py-3' + (isUnread ? ' msg-unread' : '') + '">'
            + '<div class="d-flex align-items-start gap-3">'
            + '<div class="rounded-circle bg-' + bgColor + ' text-white d-flex align-items-center justify-content-center flex-shrink-0" style="width:40px;height:40px;font-size:.75rem;font-weight:700;">' + esc(ini) + '</div>'
            + '<div class="flex-grow-1">'
            + '<div class="d-flex justify-content-between mb-1">'
            + '<h6 class="fw-semibold mb-0 small">' + esc(senderName) + '</h6>'
            + '<small class="text-muted">' + fmtDate(date) + '</small>'
            + '</div>';
        if (subject) html += '<p class="mb-1 small"><strong>' + esc(subject) + '</strong></p>';
        html += '<p class="text-muted small mb-0">' + esc(body) + '</p>'
            + '</div></div></div>';
    });
    el.innerHTML = html;
}

/* ═══════════════════════════════════════════
   PAYMENTS SECTION
   ═══════════════════════════════════════════ */
function loadPayments() {
    if (_sectionLoaded.payments) return;
    api('GET', '/payments').then(function(data) {
        _sectionLoaded.payments = true;
        renderPayments(data);
    }).catch(function(err) {
        console.error('Payments load error:', err);
        document.getElementById('payments-tbody').innerHTML = '<tr><td colspan="6" class="text-center text-danger py-4">Failed to load payments.</td></tr>';
    });
}

function renderPayments(data) {
    var summary = data.summary || data;
    var payments = data.payments || data.data || data.invoices || [];

    // Summary cards
    if (summary.total_paid !== undefined) {
        document.getElementById('pay-total-paid').textContent = fmtMoney(summary.total_paid);
        document.getElementById('pay-remaining').textContent = fmtMoney(summary.remaining);
    }

    // Find next pending payment date
    var nextDate = '—';
    payments.forEach(function(p) {
        var s = (p.status || '').toLowerCase();
        if ((s === 'pending' || s === 'unpaid') && p.due_date) {
            if (nextDate === '—') nextDate = fmtDate(p.due_date);
        }
    });
    document.getElementById('pay-next-date').textContent = nextDate;

    // Table
    var tbody = document.getElementById('payments-tbody');
    if (!payments || payments.length === 0) {
        tbody.innerHTML = '<tr><td colspan="6" class="text-center text-muted py-4">No payment records yet.</td></tr>';
        return;
    }

    var html = '';
    payments.forEach(function(p) {
        var status = (p.status || '').toLowerCase();
        var isPending = status === 'pending' || status === 'unpaid';
        var isUpcoming = status === 'upcoming' || status === 'scheduled';
        html += '<tr' + (isPending ? ' class="table-warning"' : '') + '>';
        html += '<td class="small">' + (p.date || p.paid_at || p.due_date ? fmtDate(p.date || p.paid_at || p.due_date) : '—') + '</td>';
        html += '<td class="small fw-medium">' + esc(p.description || p.title || p.name || '—') + '</td>';
        html += '<td class="fw-semibold' + (isPending ? ' text-danger' : (isUpcoming ? ' text-muted' : '')) + '">' + fmtMoney(p.amount) + '</td>';
        html += '<td class="small">' + esc(p.method || p.payment_method || '—') + '</td>';
        html += '<td>' + statusBadge(p.status) + '</td>';
        html += '<td>';
        if (isPending) {
            html += '<button class="btn btn-sm btn-success"><i class="ri-bank-card-line me-1"></i><span data-lang="wc-cp-pay-btn">Pay</span></button>';
        } else if (p.invoice_number || p.invoice_id) {
            html += '<button class="btn btn-sm btn-light"><i class="ri-download-line"></i> ' + esc(p.invoice_number || 'INV') + '</button>';
        } else {
            html += '—';
        }
        html += '</td></tr>';
    });
    tbody.innerHTML = html;
}

/* ═══════════════════════════════════════════
   PROFILE SECTION
   ═══════════════════════════════════════════ */
var _profileData = null;

function loadProfile() {
    if (_sectionLoaded.profile) return;
    api('GET', '/profile').then(function(data) {
        _sectionLoaded.profile = true;
        _profileData = data;
        renderProfile(data);
    }).catch(function(err) {
        console.error('Profile load error:', err);
    });
}

function renderProfile(data) {
    var user = data.user || data;
    var client = data.client || data;

    var fullName = (client.first_name || '') + ' ' + (client.last_name || '');
    if (fullName.trim() === '') fullName = user.name || '—';
    var ini = initials(fullName);

    document.getElementById('profile-avatar').textContent = ini;
    document.getElementById('profile-fullname').textContent = fullName.trim();
    document.getElementById('profile-since').textContent = user.created_at ? fmtMonthYear(user.created_at) : '—';
    document.getElementById('profile-email').textContent = client.email || user.email || '—';
    document.getElementById('profile-phone').textContent = client.phone || '—';
    document.getElementById('profile-city').textContent = (client.city || '') + (client.voivodeship ? ', ' + client.voivodeship : '');
    document.getElementById('profile-nationality').textContent = client.nationality || '—';

    // Status badge
    var statusEl = document.getElementById('profile-status-badge');
    var cStatus = (client.status || 'active').toLowerCase();
    statusEl.innerHTML = statusBadge(cStatus);

    // Personal info
    document.getElementById('profile-name-full').textContent = fullName.trim();
    document.getElementById('profile-dob').textContent = fmtDate(client.date_of_birth);
    document.getElementById('profile-nationality-full').textContent = client.nationality || '—';
    document.getElementById('profile-passport').textContent = client.passport_number || '—';
    document.getElementById('profile-pesel').textContent = client.pesel || '—';
    document.getElementById('profile-phone-full').textContent = client.phone || '—';
    document.getElementById('profile-address').textContent = (client.address || '—') + (client.postal_code ? ', ' + client.postal_code : '') + (client.city ? ' ' + client.city : '');

    // Employment
    document.getElementById('profile-employer').textContent = client.company_name || '—';
    document.getElementById('profile-position').textContent = client.position || '—';
    document.getElementById('profile-nip').textContent = client.nip || '—';
    document.getElementById('profile-lang').textContent = client.preferred_language || '—';
}

function toggleProfileEdit(show) {
    document.getElementById('profile-view-mode').style.display = show ? 'none' : 'block';
    document.getElementById('profile-edit-mode').style.display = show ? 'block' : 'none';

    if (show && _profileData) {
        var c = _profileData.client || _profileData;
        document.getElementById('edit-first-name').value = c.first_name || '';
        document.getElementById('edit-last-name').value = c.last_name || '';
        document.getElementById('edit-phone').value = c.phone || '';
        document.getElementById('edit-email').value = c.email || (_profileData.user || {}).email || '';
        document.getElementById('edit-address').value = c.address || '';
        document.getElementById('edit-city').value = c.city || '';
        document.getElementById('edit-postal').value = c.postal_code || '';
        document.getElementById('edit-voivodeship').value = c.voivodeship || '';
    }
}

/* ═══════════════════════════════════════════
   Navigation
   ═══════════════════════════════════════════ */
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

    // Lazy-load section data
    if (sec === 'documents') loadDocuments();
    else if (sec === 'messages') loadMessages();
    else if (sec === 'payments') loadPayments();
    else if (sec === 'profile') loadProfile();
}

document.querySelectorAll('.mob-nav').forEach(function(btn){
    btn.addEventListener('click', function(){ showSection(this.getAttribute('data-sec')); });
});

/* ═══════════════════════════════════════════
   Document Type -> Validation Rules
   ═══════════════════════════════════════════ */
var DOC_RULES = {
    bank:             { accept: '.pdf,.jpg,.jpeg,.png',              maxMB: 10, label: 'PDF, JPG, PNG',           hint: 'Bank statement — last 3 months, all pages. PDF or photo scan.' },
    health:           { accept: '.pdf,.jpg,.jpeg,.png',              maxMB: 10, label: 'PDF, JPG, PNG',           hint: 'Health insurance certificate / ZUS ZUA. PDF or photo scan.' },
    employment:       { accept: '.pdf,.doc,.docx,.jpg,.jpeg,.png',   maxMB: 10, label: 'PDF, DOC, JPG, PNG',      hint: 'Employment certificate from your employer.' },
    passport:         { accept: '.pdf,.jpg,.jpeg,.png',              maxMB: 10, label: 'PDF, JPG, PNG',           hint: 'Clear scan of passport data page. Color, min 300 DPI, no glare.' },
    tax:              { accept: '.pdf,.jpg,.jpeg,.png',              maxMB: 10, label: 'PDF, JPG, PNG',           hint: 'PIT-37 or PIT-36 tax return. Full document with all pages.' },
    address:          { accept: '.pdf,.jpg,.jpeg,.png',              maxMB: 10, label: 'PDF, JPG, PNG',           hint: 'Zameldowanie or utility bill confirming your address in Poland.' },
    photo:            { accept: '.jpg,.jpeg,.png',                   maxMB: 5,  label: 'JPG, JPEG, PNG',          hint: 'Biometric photo 3.5x4.5 cm, white background, 300+ DPI.', checkPhoto: true },
    power_of_attorney:{ accept: '.pdf,.jpg,.jpeg,.png',              maxMB: 10, label: 'PDF, JPG, PNG',           hint: 'Signed power of attorney (Pelnomocnictwo). Scanned with signature.' },
    work_contract:    { accept: '.pdf,.doc,.docx,.jpg,.jpeg,.png',   maxMB: 10, label: 'PDF, DOC, JPG, PNG',      hint: 'Work contract (Umowa o prace / zlecenie / dzielo). All pages.' },
    zus:              { accept: '.pdf,.jpg,.jpeg,.png',              maxMB: 10, label: 'PDF, JPG, PNG',           hint: 'ZUS RMUA or ZUS ZUA confirmation document.' },
    pit:              { accept: '.pdf,.jpg,.jpeg,.png',              maxMB: 10, label: 'PDF, JPG, PNG',           hint: 'Annual tax return (PIT-37 / PIT-36) with UPO confirmation.' },
    zameldowanie:     { accept: '.pdf,.jpg,.jpeg,.png',              maxMB: 10, label: 'PDF, JPG, PNG',           hint: 'Registration confirmation (Zaswiadczenie o zameldowaniu).' },
    other:            { accept: '.pdf,.jpg,.jpeg,.png,.doc,.docx',   maxMB: 10, label: 'PDF, JPG, PNG, DOC/DOCX', hint: 'Any supporting document for your case.' }
};

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

    if (allowed.indexOf(ext) === -1) {
        showError('Wrong format! Allowed: ' + rule.label + '. You selected: ' + ext.toUpperCase());
        return false;
    }

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

    var maxBytes = rule.maxMB * 1024 * 1024;
    if (file.size > maxBytes) {
        showError('File too large! Max ' + rule.maxMB + ' MB. Your file: ' + formatSize(file.size));
        return false;
    }

    if (file.size < 1024) {
        showError('File is too small (' + formatSize(file.size) + '). Please upload a valid document.');
        return false;
    }

    if (rule.checkPhoto && (ext === '.jpg' || ext === '.jpeg')) {
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
        var img = new Image();
        var url = URL.createObjectURL(file);
        img.onload = function() {
            URL.revokeObjectURL(url);
            var w = img.width, h = img.height;
            var ratio = h / w;
            if (ratio < 1.1 || ratio > 1.5) {
                showError('Photo proportions incorrect! Expected ~3.5x4.5 cm (ratio ~1.29). Your image: ' + w + 'x' + h + 'px (ratio ' + ratio.toFixed(2) + '). Please upload a biometric photo.');
                return;
            }
            if (w < 350 || h < 450) {
                showError('Photo resolution too low! Minimum 350x450 px (recommended 413x531 px for 300 DPI). Your image: ' + w + 'x' + h + 'px.');
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

// Upload button — sends file via API
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
    formData.append('name', uploadFile.files[0].name);
    formData.append('notes', document.getElementById('uploadNotes').value || '');

    api('POST', '/documents', formData)
    .then(function(data) {
        bootstrap.Modal.getInstance(document.getElementById('uploadModal')).hide();
        self.disabled = false;
        self.innerHTML = '<i class="ri-upload-2-line me-1"></i><span data-lang="wc-cp-upload-btn">' + _t('wc-cp-upload-btn','Upload') + '</span>';

        // Force reload documents on next visit
        _sectionLoaded.documents = false;

        // Update stat counter
        var statEl = document.getElementById('stat-documents');
        var cur = parseInt(statEl.textContent) || 0;
        statEl.textContent = cur + 1;

        Swal.fire({
            icon: 'success',
            title: _t('wc-cp-swal-uploaded','Uploaded!'),
            html: '<div class="text-center"><i class="ri-shield-check-line text-success fs-1 mb-2 d-block"></i>'
                + '<strong>' + esc(uploadFile.files[0] ? uploadFile.files[0].name : 'Document') + '</strong><br>'
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
    var subject = document.getElementById('msgSubject').value.trim();
    var body = document.getElementById('msgBody').value.trim();

    if (!subject || !body) {
        Swal.fire({ icon: 'warning', title: 'Missing fields', text: 'Please fill in both subject and message.', confirmButtonColor: '#015EA7' });
        return;
    }

    var self = this;
    self.disabled = true;
    self.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Sending...';

    var fd = new FormData();
    fd.append('subject', subject);
    fd.append('body', body);
    // Attach file if present
    var attachInput = document.getElementById('msgAttachFile');
    if (attachInput && attachInput.files && attachInput.files[0]) {
        fd.append('attachment', attachInput.files[0]);
    }

    api('POST', '/messages', fd)
    .then(function(data) {
        bootstrap.Modal.getInstance(document.getElementById('newMsgModal')).hide();
        self.disabled = false;
        self.innerHTML = '<i class="ri-send-plane-line me-1"></i><span data-lang="wc-cp-send-btn">' + _t('wc-cp-send-btn','Send') + '</span>';

        // Reset form
        document.getElementById('msgSubject').value = '';
        document.getElementById('msgBody').value = '';
        if (attachInput) attachInput.value = '';

        // Force reload messages on next visit
        _sectionLoaded.messages = false;

        Swal.fire({ icon: 'success', title: _t('wc-cp-swal-sent','Sent'), text: _t('wc-cp-swal-sent-text','Your message has been sent.'), confirmButtonColor: '#015EA7' });
    })
    .catch(function(err) {
        self.disabled = false;
        self.innerHTML = '<i class="ri-send-plane-line me-1"></i><span data-lang="wc-cp-send-btn">' + _t('wc-cp-send-btn','Send') + '</span>';

        var msg = 'Failed to send message. Please try again.';
        if (err && err.errors) {
            var first = Object.values(err.errors)[0];
            msg = Array.isArray(first) ? first[0] : first;
        } else if (err && err.message) { msg = err.message; }

        Swal.fire({ icon: 'error', title: _t('wc-cp-swal-error','Error'), text: msg, confirmButtonColor: '#dc3545' });
    });
});

/* ═══════════ Edit Profile ═══════════ */
document.getElementById('btnEditProfile').addEventListener('click', function(){
    if (!_sectionLoaded.profile) {
        loadProfile();
        setTimeout(function() { toggleProfileEdit(true); }, 500);
    } else {
        toggleProfileEdit(true);
    }
});

document.getElementById('profileEditForm').addEventListener('submit', function(e){
    e.preventDefault();
    var btn = document.getElementById('btnSaveProfile');
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Saving...';

    var fd = new FormData();
    fd.append('_method', 'PUT');
    fd.append('first_name', document.getElementById('edit-first-name').value);
    fd.append('last_name', document.getElementById('edit-last-name').value);
    fd.append('phone', document.getElementById('edit-phone').value);
    fd.append('address', document.getElementById('edit-address').value);
    fd.append('city', document.getElementById('edit-city').value);
    fd.append('postal_code', document.getElementById('edit-postal').value);
    fd.append('voivodeship', document.getElementById('edit-voivodeship').value);

    api('POST', '/profile', fd)
    .then(function(data) {
        btn.disabled = false;
        btn.innerHTML = '<i class="ri-save-line me-1"></i><span data-lang="wc-cp-save">Save Changes</span>';
        _sectionLoaded.profile = false;
        _profileData = null;
        toggleProfileEdit(false);
        loadProfile();
        Swal.fire({ icon: 'success', title: 'Updated', text: 'Your profile has been updated.', confirmButtonColor: '#015EA7' });
    })
    .catch(function(err) {
        btn.disabled = false;
        btn.innerHTML = '<i class="ri-save-line me-1"></i><span data-lang="wc-cp-save">Save Changes</span>';
        var msg = 'Failed to update profile.';
        if (err && err.errors) {
            var first = Object.values(err.errors)[0];
            msg = Array.isArray(first) ? first[0] : first;
        } else if (err && err.message) { msg = err.message; }
        Swal.fire({ icon: 'error', title: 'Error', text: msg, confirmButtonColor: '#dc3545' });
    });
});

/* ═══════════ Change Password ═══════════ */
document.getElementById('passwordForm').addEventListener('submit', function(e){
    e.preventDefault();
    var current = document.getElementById('pw-current').value;
    var newPw = document.getElementById('pw-new').value;
    var confirm = document.getElementById('pw-confirm').value;

    if (!current || !newPw || !confirm) {
        Swal.fire({ icon: 'warning', title: 'Missing fields', text: 'Please fill in all password fields.', confirmButtonColor: '#015EA7' });
        return;
    }
    if (newPw !== confirm) {
        Swal.fire({ icon: 'warning', title: 'Mismatch', text: 'New password and confirmation do not match.', confirmButtonColor: '#015EA7' });
        return;
    }
    if (newPw.length < 8) {
        Swal.fire({ icon: 'warning', title: 'Too short', text: 'Password must be at least 8 characters.', confirmButtonColor: '#015EA7' });
        return;
    }

    var btn = document.getElementById('btnChangePassword');
    btn.disabled = true;
    btn.textContent = 'Updating...';

    var fd = new FormData();
    fd.append('current_password', current);
    fd.append('password', newPw);
    fd.append('password_confirmation', confirm);

    api('POST', '/password', fd)
    .then(function(data) {
        btn.disabled = false;
        btn.textContent = _t('wc-cp-update-password', 'Update Password');
        document.getElementById('pw-current').value = '';
        document.getElementById('pw-new').value = '';
        document.getElementById('pw-confirm').value = '';
        Swal.fire({ icon: 'success', title: 'Done', text: 'Your password has been changed.', confirmButtonColor: '#015EA7' });
    })
    .catch(function(err) {
        btn.disabled = false;
        btn.textContent = _t('wc-cp-update-password', 'Update Password');
        var msg = 'Failed to change password.';
        if (err && err.errors) {
            var first = Object.values(err.errors)[0];
            msg = Array.isArray(first) ? first[0] : first;
        } else if (err && err.message) { msg = err.message; }
        Swal.fire({ icon: 'error', title: 'Error', text: msg, confirmButtonColor: '#dc3545' });
    });
});

/* ═══════════ Language ═══════════ */
var langSelect = document.getElementById('langSelect');
if (langSelect && window.WcI18n) {
    langSelect.value = WcI18n.current();
    langSelect.addEventListener('change', function(){ WcI18n.switchLang(this.value); });
}

/* ═══════════ Init ═══════════ */
loadDashboard();
</script>
@endsection
