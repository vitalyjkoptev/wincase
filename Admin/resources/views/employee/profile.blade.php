@extends('partials.layouts.master-employee')
@section('title', 'My Profile — WinCase Staff')
@section('page-title', 'My Profile')

@section('css')
<style>
    .profile-header { background: linear-gradient(135deg, #015EA7 0%, #014d8a 100%); border-radius: .75rem; padding: 2rem; color: #fff; position: relative; overflow: hidden; }
    .profile-header::after { content: ''; position: absolute; right: -30px; top: -30px; width: 200px; height: 200px; background: rgba(255,255,255,.05); border-radius: 50%; }
    .profile-avatar { width: 80px; height: 80px; border-radius: 50%; background: rgba(255,255,255,.2); display: flex; align-items: center; justify-content: center; font-size: 2rem; font-weight: 700; border: 3px solid rgba(255,255,255,.3); }
    .stat-pill { display: inline-flex; align-items: center; gap: .5rem; padding: .5rem 1rem; border-radius: 2rem; background: rgba(255,255,255,.15); font-size: .8rem; }
    .info-row { padding: .75rem 0; border-bottom: 1px solid rgba(0,0,0,.04); display: flex; align-items: center; }
    .info-row:last-child { border-bottom: none; }
    [data-bs-theme="dark"] .info-row { border-color: rgba(255,255,255,.04); }
    .info-label { width: 140px; font-size: .8rem; color: #6c757d; flex-shrink: 0; }
    .info-value { font-size: .85rem; font-weight: 500; }
    .achievement-badge { width: 48px; height: 48px; border-radius: .75rem; display: flex; align-items: center; justify-content: center; font-size: 1.3rem; }
</style>
@endsection

@section('content')
<!-- Profile Header -->
<div class="profile-header mb-4">
    <div class="d-flex align-items-center gap-4">
        <div class="profile-avatar">AK</div>
        <div>
            <h4 class="mb-1">Anna Kowalska</h4>
            <div class="mb-2 opacity-75">Senior Immigration Specialist</div>
            <div class="d-flex flex-wrap gap-2">
                <div class="stat-pill"><i class="ri-briefcase-line"></i> 28 cases completed</div>
                <div class="stat-pill"><i class="ri-group-line"></i> 12 active clients</div>
                <div class="stat-pill"><i class="ri-calendar-line"></i> Since Jan 2025</div>
            </div>
        </div>
        <div class="ms-auto d-none d-lg-block">
            <button class="btn btn-light btn-sm"><i class="ri-edit-line me-1"></i>Edit Profile</button>
        </div>
    </div>
</div>

<div class="row g-3">
    <!-- Left: Info -->
    <div class="col-lg-8">
        <!-- Personal Information -->
        <div class="card mb-3">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="mb-0"><i class="ri-user-line text-success me-1"></i><span data-lang="wc-staff-personal-info">Personal Information</span></h6>
                <button class="btn btn-sm btn-light"><i class="ri-edit-line"></i></button>
            </div>
            <div class="card-body">
                <div class="info-row">
                    <div class="info-label" data-lang="wc-staff-full-name">Full Name</div>
                    <div class="info-value">Anna Kowalska</div>
                </div>
                <div class="info-row">
                    <div class="info-label" data-lang="wc-staff-email">Email</div>
                    <div class="info-value">anna.kowalska@wincase.eu</div>
                </div>
                <div class="info-row">
                    <div class="info-label" data-lang="wc-staff-phone">Phone</div>
                    <div class="info-value">+48 512 345 678</div>
                </div>
                <div class="info-row">
                    <div class="info-label" data-lang="wc-staff-position">Position</div>
                    <div class="info-value">Senior Immigration Specialist</div>
                </div>
                <div class="info-row">
                    <div class="info-label" data-lang="wc-staff-department">Department</div>
                    <div class="info-value">Immigration Services</div>
                </div>
                <div class="info-row">
                    <div class="info-label" data-lang="wc-staff-employee-id">Employee ID</div>
                    <div class="info-value">WC-EMP-004</div>
                </div>
                <div class="info-row">
                    <div class="info-label" data-lang="wc-staff-start-date">Start Date</div>
                    <div class="info-value">January 15, 2025</div>
                </div>
            </div>
        </div>

        <!-- Work Schedule -->
        <div class="card mb-3">
            <div class="card-header">
                <h6 class="mb-0"><i class="ri-time-line text-info me-1"></i><span data-lang="wc-staff-work-schedule">Work Schedule</span></h6>
            </div>
            <div class="card-body">
                <div class="row g-2">
                    @foreach(['Mon' => '09:00 – 18:00', 'Tue' => '09:00 – 18:00', 'Wed' => '09:00 – 18:00', 'Thu' => '09:00 – 18:00', 'Fri' => '09:00 – 17:00'] as $day => $hours)
                    <div class="col">
                        <div class="text-center p-2 rounded" style="background:rgba(1,94,167,.05);">
                            <div class="fw-bold text-success" style="font-size:.8rem;">{{ $day }}</div>
                            <div style="font-size:.7rem;" class="text-muted">{{ $hours }}</div>
                        </div>
                    </div>
                    @endforeach
                    @foreach(['Sat', 'Sun'] as $day)
                    <div class="col">
                        <div class="text-center p-2 rounded" style="background:rgba(108,117,125,.05);">
                            <div class="fw-bold text-muted" style="font-size:.8rem;">{{ $day }}</div>
                            <div style="font-size:.7rem;" class="text-muted">Off</div>
                        </div>
                    </div>
                    @endforeach
                </div>
                <div class="mt-3 d-flex gap-3" style="font-size:.8rem;">
                    <div><strong>Total:</strong> 41 hours/week</div>
                    <div><strong>Vacation left:</strong> <span class="text-success">18 days</span></div>
                    <div><strong>Sick leave used:</strong> 2 days</div>
                </div>
            </div>
        </div>

        <!-- Performance -->
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0"><i class="ri-line-chart-line text-warning me-1"></i><span data-lang="wc-staff-performance">Performance</span> — March 2026</h6>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="d-flex justify-content-between mb-1" style="font-size:.8rem;">
                            <span>Cases Completed</span>
                            <strong>28 / 30</strong>
                        </div>
                        <div class="progress" style="height:6px;">
                            <div class="progress-bar bg-success" style="width:93%;"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex justify-content-between mb-1" style="font-size:.8rem;">
                            <span>Tasks On Time</span>
                            <strong>92%</strong>
                        </div>
                        <div class="progress" style="height:6px;">
                            <div class="progress-bar bg-primary" style="width:92%;"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex justify-content-between mb-1" style="font-size:.8rem;">
                            <span>Client Satisfaction</span>
                            <strong>4.8 / 5.0</strong>
                        </div>
                        <div class="progress" style="height:6px;">
                            <div class="progress-bar bg-warning" style="width:96%;"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex justify-content-between mb-1" style="font-size:.8rem;">
                            <span>Response Time</span>
                            <strong>Avg. 25 min</strong>
                        </div>
                        <div class="progress" style="height:6px;">
                            <div class="progress-bar bg-info" style="width:85%;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Right: Sidebar -->
    <div class="col-lg-4">
        <!-- Skills / Specializations -->
        <div class="card mb-3">
            <div class="card-header">
                <h6 class="mb-0"><i class="ri-star-line text-warning me-1"></i><span data-lang="wc-staff-specializations">Specializations</span></h6>
            </div>
            <div class="card-body">
                <div class="d-flex flex-wrap gap-2">
                    <span class="badge bg-success-subtle text-success px-3 py-2">Temporary Residence</span>
                    <span class="badge bg-primary-subtle text-primary px-3 py-2">Work Permits</span>
                    <span class="badge bg-info-subtle text-info px-3 py-2">Blue Card</span>
                    <span class="badge bg-warning-subtle text-warning px-3 py-2">Family Reunification</span>
                    <span class="badge bg-secondary-subtle text-secondary px-3 py-2">Document Verification</span>
                </div>
            </div>
        </div>

        <!-- Languages -->
        <div class="card mb-3">
            <div class="card-header">
                <h6 class="mb-0"><i class="ri-translate-2 text-primary me-1"></i><span data-lang="wc-staff-languages">Languages</span></h6>
            </div>
            <div class="list-group list-group-flush">
                <div class="list-group-item d-flex justify-content-between align-items-center">
                    <span style="font-size:.85rem;">Polish</span>
                    <span class="badge bg-success">Native</span>
                </div>
                <div class="list-group-item d-flex justify-content-between align-items-center">
                    <span style="font-size:.85rem;">English</span>
                    <span class="badge bg-primary">Fluent</span>
                </div>
                <div class="list-group-item d-flex justify-content-between align-items-center">
                    <span style="font-size:.85rem;">Ukrainian</span>
                    <span class="badge bg-info">Conversational</span>
                </div>
                <div class="list-group-item d-flex justify-content-between align-items-center">
                    <span style="font-size:.85rem;">Russian</span>
                    <span class="badge bg-info">Conversational</span>
                </div>
            </div>
        </div>

        <!-- Achievements -->
        <div class="card mb-3">
            <div class="card-header">
                <h6 class="mb-0"><i class="ri-trophy-line text-warning me-1"></i><span data-lang="wc-staff-achievements">Achievements</span></h6>
            </div>
            <div class="card-body">
                <div class="d-flex flex-wrap gap-3">
                    <div class="text-center">
                        <div class="achievement-badge mx-auto mb-1" style="background:rgba(255,193,7,.1);color:#ffc107;"><i class="ri-trophy-line"></i></div>
                        <div style="font-size:.65rem;" class="text-muted">Top Performer<br>Q4 2025</div>
                    </div>
                    <div class="text-center">
                        <div class="achievement-badge mx-auto mb-1" style="background:rgba(1,94,167,.1);color:#015EA7;"><i class="ri-speed-line"></i></div>
                        <div style="font-size:.65rem;" class="text-muted">Fast Responder<br>Feb 2026</div>
                    </div>
                    <div class="text-center">
                        <div class="achievement-badge mx-auto mb-1" style="background:rgba(13,110,253,.1);color:#0d6efd;"><i class="ri-star-smile-line"></i></div>
                        <div style="font-size:.65rem;" class="text-muted">Client Favorite<br>Jan 2026</div>
                    </div>
                    <div class="text-center">
                        <div class="achievement-badge mx-auto mb-1" style="background:rgba(111,66,193,.1);color:#6f42c1;"><i class="ri-graduation-cap-line"></i></div>
                        <div style="font-size:.65rem;" class="text-muted">Certified<br>Immigration Law</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Security -->
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0"><i class="ri-shield-check-line text-danger me-1"></i><span data-lang="wc-staff-security">Security</span></h6>
            </div>
            <div class="list-group list-group-flush">
                <a href="#" class="list-group-item list-group-item-action d-flex align-items-center gap-2" style="font-size:.85rem;">
                    <i class="ri-lock-password-line text-muted"></i>
                    <span data-lang="wc-staff-change-password">Change Password</span>
                    <i class="ri-arrow-right-s-line ms-auto text-muted"></i>
                </a>
                <a href="#" class="list-group-item list-group-item-action d-flex align-items-center gap-2" style="font-size:.85rem;">
                    <i class="ri-smartphone-line text-muted"></i>
                    <span data-lang="wc-staff-2fa">Two-Factor Authentication</span>
                    <span class="badge bg-success ms-auto">Enabled</span>
                </a>
                <a href="#" class="list-group-item list-group-item-action d-flex align-items-center gap-2" style="font-size:.85rem;">
                    <i class="ri-history-line text-muted"></i>
                    <span data-lang="wc-staff-login-history">Login History</span>
                    <i class="ri-arrow-right-s-line ms-auto text-muted"></i>
                </a>
                <a href="#" class="list-group-item list-group-item-action d-flex align-items-center gap-2" style="font-size:.85rem;">
                    <i class="ri-notification-3-line text-muted"></i>
                    <span data-lang="wc-staff-notif-settings">Notification Settings</span>
                    <i class="ri-arrow-right-s-line ms-auto text-muted"></i>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
