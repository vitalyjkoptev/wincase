@extends('partials.layouts.master-employee')
@section('title', 'Dashboard — WinCase Staff')
@section('page-title', 'Dashboard')

@section('content')
<!-- Welcome -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card bg-success bg-opacity-10 border-0">
            <div class="card-body py-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="avatar-md rounded-circle bg-success bg-opacity-25 d-flex align-items-center justify-content-center">
                        <i class="ri-user-smile-line fs-3 text-success"></i>
                    </div>
                    <div>
                        <h5 class="mb-0"><span data-lang="wc-staff-good-morning">Good morning</span>, Anna! <span style="font-size:1.2rem;">&#128075;</span></h5>
                        <small class="text-muted">Monday, March 2, 2026 &bull; You have <strong class="text-success">5 <span data-lang="wc-staff-tasks-due-today">tasks due today</span></strong></small>
                    </div>
                    <div class="ms-auto d-none d-md-block">
                        <span class="badge bg-success rounded-pill px-3 py-2"><i class="ri-time-line me-1"></i><span data-lang="wc-staff-shift">Shift</span>: 09:00 - 18:00</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- My Stats -->
<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3">
        <div class="card">
            <div class="card-body text-center py-3">
                <div class="avatar-sm mx-auto mb-2 rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center">
                    <i class="ri-group-line text-primary fs-5"></i>
                </div>
                <h4 class="mb-0">12</h4>
                <small class="text-muted" data-lang="wc-staff-my-clients">My Clients</small>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card">
            <div class="card-body text-center py-3">
                <div class="avatar-sm mx-auto mb-2 rounded-circle bg-warning bg-opacity-10 d-flex align-items-center justify-content-center">
                    <i class="ri-folder-open-line text-warning fs-5"></i>
                </div>
                <h4 class="mb-0">8</h4>
                <small class="text-muted" data-lang="wc-staff-active-cases">Active Cases</small>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card">
            <div class="card-body text-center py-3">
                <div class="avatar-sm mx-auto mb-2 rounded-circle bg-info bg-opacity-10 d-flex align-items-center justify-content-center">
                    <i class="ri-task-line text-info fs-5"></i>
                </div>
                <h4 class="mb-0">5</h4>
                <small class="text-muted" data-lang="wc-staff-tasks-today">Tasks Today</small>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card">
            <div class="card-body text-center py-3">
                <div class="avatar-sm mx-auto mb-2 rounded-circle bg-success bg-opacity-10 d-flex align-items-center justify-content-center">
                    <i class="ri-check-double-line text-success fs-5"></i>
                </div>
                <h4 class="mb-0">28</h4>
                <small class="text-muted" data-lang="wc-staff-cases-completed">Cases Completed</small>
            </div>
        </div>
    </div>
</div>

<div class="row g-3">
    <!-- Today's Tasks -->
    <div class="col-lg-7">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="mb-0"><i class="ri-checkbox-circle-line text-success me-1"></i><span data-lang="wc-staff-todays-tasks">Today's Tasks</span></h6>
                <a href="/staff-tasks" class="btn btn-sm btn-light" data-lang="wc-staff-view-all">View All</a>
            </div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    <label class="list-group-item d-flex align-items-center gap-3">
                        <input type="checkbox" class="form-check-input mt-0 flex-shrink-0">
                        <div class="flex-grow-1">
                            <div class="fw-semibold" style="font-size:.85rem;">Submit bank statement — Olena Kovalenko</div>
                            <small class="text-muted">Case #WC-2026-0847 &bull; Due by 12:00</small>
                        </div>
                        <span class="badge bg-danger-subtle text-danger">Urgent</span>
                    </label>
                    <label class="list-group-item d-flex align-items-center gap-3">
                        <input type="checkbox" class="form-check-input mt-0 flex-shrink-0">
                        <div class="flex-grow-1">
                            <div class="fw-semibold" style="font-size:.85rem;">Schedule fingerprint — Dmytro Bondarenko</div>
                            <small class="text-muted">Case #WC-2026-0812 &bull; Due by 15:00</small>
                        </div>
                        <span class="badge bg-warning-subtle text-warning">Medium</span>
                    </label>
                    <label class="list-group-item d-flex align-items-center gap-3">
                        <input type="checkbox" class="form-check-input mt-0 flex-shrink-0">
                        <div class="flex-grow-1">
                            <div class="fw-semibold" style="font-size:.85rem;">Verify passport copies — Rahul Sharma</div>
                            <small class="text-muted">Case #WC-2026-0831 &bull; Due by 17:00</small>
                        </div>
                        <span class="badge bg-info-subtle text-info">Normal</span>
                    </label>
                    <label class="list-group-item d-flex align-items-center gap-3">
                        <input type="checkbox" class="form-check-input mt-0 flex-shrink-0" checked>
                        <div class="flex-grow-1 text-decoration-line-through text-muted">
                            <div style="font-size:.85rem;">Upload translated certificate — Irina Kozlova</div>
                            <small>Case #WC-2026-0798 &bull; Completed at 09:15</small>
                        </div>
                        <span class="badge bg-success-subtle text-success">Done</span>
                    </label>
                    <label class="list-group-item d-flex align-items-center gap-3">
                        <input type="checkbox" class="form-check-input mt-0 flex-shrink-0">
                        <div class="flex-grow-1">
                            <div class="fw-semibold" style="font-size:.85rem;">Prepare application packet — Mehmet Yilmaz</div>
                            <small class="text-muted">Case #WC-2026-0855 &bull; Due by EOD</small>
                        </div>
                        <span class="badge bg-info-subtle text-info">Normal</span>
                    </label>
                </div>
            </div>
        </div>
    </div>

    <!-- Upcoming + Messages -->
    <div class="col-lg-5">
        <!-- Upcoming Deadlines -->
        <div class="card mb-3">
            <div class="card-header">
                <h6 class="mb-0"><i class="ri-calendar-event-line text-warning me-1"></i><span data-lang="wc-staff-upcoming-deadlines">Upcoming Deadlines</span></h6>
            </div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    <div class="list-group-item">
                        <div class="d-flex justify-content-between">
                            <span style="font-size:.85rem;"><strong>Mar 5</strong> — Bank statement deadline</span>
                            <span class="badge bg-danger rounded-pill">3 days</span>
                        </div>
                        <small class="text-muted">Olena Kovalenko &bull; #WC-2026-0847</small>
                    </div>
                    <div class="list-group-item">
                        <div class="d-flex justify-content-between">
                            <span style="font-size:.85rem;"><strong>Mar 8</strong> — Fingerprint appointment</span>
                            <span class="badge bg-warning rounded-pill">6 days</span>
                        </div>
                        <small class="text-muted">Dmytro Bondarenko &bull; #WC-2026-0812</small>
                    </div>
                    <div class="list-group-item">
                        <div class="d-flex justify-content-between">
                            <span style="font-size:.85rem;"><strong>Mar 10</strong> — Decision expected</span>
                            <span class="badge bg-info rounded-pill">8 days</span>
                        </div>
                        <small class="text-muted">Rahul Sharma &bull; #WC-2026-0831</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Unread Messages -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="mb-0"><i class="ri-message-3-line text-primary me-1"></i><span data-lang="wc-staff-unread-messages">Unread Messages</span></h6>
                <span class="badge bg-primary rounded-pill">4</span>
            </div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    <a href="/staff-messages" class="list-group-item list-group-item-action">
                        <div class="d-flex align-items-center gap-2">
                            <div class="rounded-circle bg-success bg-opacity-10 d-flex align-items-center justify-content-center" style="width:32px;height:32px;font-size:.7rem;font-weight:700;color:#015EA7;">OK</div>
                            <div class="flex-grow-1 min-width-0">
                                <div class="fw-semibold" style="font-size:.8rem;">Olena Kovalenko</div>
                                <div class="text-muted text-truncate" style="font-size:.7rem;">Thank you, I will send the bank statement...</div>
                            </div>
                            <small class="text-muted">10:32</small>
                        </div>
                    </a>
                    <a href="/staff-messages" class="list-group-item list-group-item-action">
                        <div class="d-flex align-items-center gap-2">
                            <div class="rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center" style="width:32px;height:32px;font-size:.7rem;font-weight:700;color:#0d6efd;">DB</div>
                            <div class="flex-grow-1 min-width-0">
                                <div class="fw-semibold" style="font-size:.8rem;">Dmytro Bondarenko</div>
                                <div class="text-muted text-truncate" style="font-size:.7rem;">When is my fingerprint appointment?</div>
                            </div>
                            <small class="text-muted">09:15</small>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="row mt-3">
    <div class="col-12">
        <div class="card">
            <div class="card-body py-3">
                <div class="d-flex flex-wrap gap-2">
                    <a href="/staff-clients" class="btn btn-sm btn-outline-primary"><i class="ri-group-line me-1"></i><span data-lang="wc-staff-my-clients">My Clients</span></a>
                    <a href="/staff-cases" class="btn btn-sm btn-outline-warning"><i class="ri-folder-open-line me-1"></i><span data-lang="wc-staff-my-cases">My Cases</span></a>
                    <a href="/staff-calendar" class="btn btn-sm btn-outline-info"><i class="ri-calendar-line me-1"></i><span data-lang="wc-staff-calendar">Calendar</span></a>
                    <a href="/staff-documents" class="btn btn-sm btn-outline-secondary"><i class="ri-file-text-line me-1"></i><span data-lang="wc-staff-documents">Documents</span></a>
                    <a href="/staff-boss-chat" class="btn btn-sm btn-outline-success"><i class="ri-lock-line me-1"></i><span data-lang="wc-staff-boss-chat">Boss Chat</span></a>
                    <a href="/staff-knowledge" class="btn btn-sm btn-outline-dark"><i class="ri-book-open-line me-1"></i><span data-lang="wc-staff-knowledge">Knowledge Base</span></a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
