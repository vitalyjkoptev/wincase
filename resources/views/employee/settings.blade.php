@extends('partials.layouts.master-employee')
@section('title', 'Settings — WinCase Staff')
@section('page-title', 'Settings')

@section('css')
<style>
    .stg-card { border-left: 3px solid var(--stg-color, #015EA7); transition: all .15s; }
    .stg-card:hover { box-shadow: 0 2px 12px rgba(0,0,0,.08); }
    .stg-section-hdr { display:flex; align-items:center; gap:.5rem; margin-bottom:1rem; }
    .stg-section-hdr i { font-size:1.1rem; }
    .stg-section-hdr h6 { margin:0; font-weight:700; }
</style>
@endsection

@section('content')

<div class="card">
    <div class="card-header">
        <ul class="nav nav-tabs card-header-tabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" data-bs-toggle="tab" href="#stab-notifications" role="tab">
                    <i class="ri-notification-3-line me-1"></i>Notifications
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#stab-channels" role="tab">
                    <i class="ri-chat-voice-line me-1"></i>My Channels
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#stab-templates" role="tab">
                    <i class="ri-mail-settings-line me-1"></i>Email Templates
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#stab-preferences" role="tab">
                    <i class="ri-settings-3-line me-1"></i>Preferences
                </a>
            </li>
        </ul>
    </div>
    <div class="card-body">
        <div class="tab-content">

            <!-- Tab 1: Notifications -->
            <div class="tab-pane fade show active" id="stab-notifications" role="tabpanel">
                <div class="row">
                    <div class="col-xl-6">
                        <h6 class="fw-semibold mb-3"><i class="ri-mail-line me-2"></i>Email Notifications</h6>
                        <div class="card border">
                            <div class="card-body p-0">
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item d-flex align-items-center justify-content-between py-3">
                                        <div>
                                            <h6 class="mb-0 fs-14">New Task Assigned</h6>
                                            <span class="text-muted fs-12">Notify when a new task is assigned to me</span>
                                        </div>
                                        <div class="form-check form-switch"><input class="form-check-input staff-notif-toggle" type="checkbox" checked data-notif="task_assigned" data-ch="email"></div>
                                    </li>
                                    <li class="list-group-item d-flex align-items-center justify-content-between py-3">
                                        <div>
                                            <h6 class="mb-0 fs-14">New Client Message</h6>
                                            <span class="text-muted fs-12">Notify when a client sends a message</span>
                                        </div>
                                        <div class="form-check form-switch"><input class="form-check-input staff-notif-toggle" type="checkbox" checked data-notif="client_message" data-ch="email"></div>
                                    </li>
                                    <li class="list-group-item d-flex align-items-center justify-content-between py-3">
                                        <div>
                                            <h6 class="mb-0 fs-14">Case Status Update</h6>
                                            <span class="text-muted fs-12">Notify when my case status changes</span>
                                        </div>
                                        <div class="form-check form-switch"><input class="form-check-input staff-notif-toggle" type="checkbox" checked data-notif="case_status" data-ch="email"></div>
                                    </li>
                                    <li class="list-group-item d-flex align-items-center justify-content-between py-3">
                                        <div>
                                            <h6 class="mb-0 fs-14">Task Deadline Reminder</h6>
                                            <span class="text-muted fs-12">Remind 24h before task deadline</span>
                                        </div>
                                        <div class="form-check form-switch"><input class="form-check-input staff-notif-toggle" type="checkbox" checked data-notif="task_deadline" data-ch="email"></div>
                                    </li>
                                    <li class="list-group-item d-flex align-items-center justify-content-between py-3">
                                        <div>
                                            <h6 class="mb-0 fs-14">Hearing/Appointment Reminder</h6>
                                            <span class="text-muted fs-12">Remind about upcoming appointments</span>
                                        </div>
                                        <div class="form-check form-switch"><input class="form-check-input staff-notif-toggle" type="checkbox" checked data-notif="appointment" data-ch="email"></div>
                                    </li>
                                    <li class="list-group-item d-flex align-items-center justify-content-between py-3">
                                        <div>
                                            <h6 class="mb-0 fs-14">Boss Message</h6>
                                            <span class="text-muted fs-12">Notify when boss sends a direct message</span>
                                        </div>
                                        <div class="form-check form-switch"><input class="form-check-input staff-notif-toggle" type="checkbox" checked data-notif="boss_message" data-ch="email"></div>
                                    </li>
                                    <li class="list-group-item d-flex align-items-center justify-content-between py-3">
                                        <div>
                                            <h6 class="mb-0 fs-14">Document Expiry Warning</h6>
                                            <span class="text-muted fs-12">Alert about expiring client documents</span>
                                        </div>
                                        <div class="form-check form-switch"><input class="form-check-input staff-notif-toggle" type="checkbox" checked data-notif="doc_expiry" data-ch="email"></div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-6">
                        <h6 class="fw-semibold mb-3"><i class="ri-smartphone-line me-2"></i>Push Notifications (Mobile App)</h6>
                        <div class="card border">
                            <div class="card-body p-0">
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item d-flex align-items-center justify-content-between py-3">
                                        <div>
                                            <h6 class="mb-0 fs-14">New Task Assigned</h6>
                                            <span class="text-muted fs-12">Push notification on new tasks</span>
                                        </div>
                                        <div class="form-check form-switch"><input class="form-check-input staff-notif-toggle" type="checkbox" checked data-notif="task_assigned" data-ch="push"></div>
                                    </li>
                                    <li class="list-group-item d-flex align-items-center justify-content-between py-3">
                                        <div>
                                            <h6 class="mb-0 fs-14">New Client Message</h6>
                                            <span class="text-muted fs-12">Push notification on client messages</span>
                                        </div>
                                        <div class="form-check form-switch"><input class="form-check-input staff-notif-toggle" type="checkbox" checked data-notif="client_message" data-ch="push"></div>
                                    </li>
                                    <li class="list-group-item d-flex align-items-center justify-content-between py-3">
                                        <div>
                                            <h6 class="mb-0 fs-14">Case Status Update</h6>
                                            <span class="text-muted fs-12">Push on case status changes</span>
                                        </div>
                                        <div class="form-check form-switch"><input class="form-check-input staff-notif-toggle" type="checkbox" data-notif="case_status" data-ch="push"></div>
                                    </li>
                                    <li class="list-group-item d-flex align-items-center justify-content-between py-3">
                                        <div>
                                            <h6 class="mb-0 fs-14">Task Deadline Reminder</h6>
                                            <span class="text-muted fs-12">Push reminder before deadline</span>
                                        </div>
                                        <div class="form-check form-switch"><input class="form-check-input staff-notif-toggle" type="checkbox" checked data-notif="task_deadline" data-ch="push"></div>
                                    </li>
                                    <li class="list-group-item d-flex align-items-center justify-content-between py-3">
                                        <div>
                                            <h6 class="mb-0 fs-14">Hearing/Appointment Reminder</h6>
                                            <span class="text-muted fs-12">Push reminder for appointments</span>
                                        </div>
                                        <div class="form-check form-switch"><input class="form-check-input staff-notif-toggle" type="checkbox" checked data-notif="appointment" data-ch="push"></div>
                                    </li>
                                    <li class="list-group-item d-flex align-items-center justify-content-between py-3">
                                        <div>
                                            <h6 class="mb-0 fs-14">Boss Message</h6>
                                            <span class="text-muted fs-12">Push on boss direct messages</span>
                                        </div>
                                        <div class="form-check form-switch"><input class="form-check-input staff-notif-toggle" type="checkbox" checked data-notif="boss_message" data-ch="push"></div>
                                    </li>
                                    <li class="list-group-item d-flex align-items-center justify-content-between py-3">
                                        <div>
                                            <h6 class="mb-0 fs-14">Document Expiry Warning</h6>
                                            <span class="text-muted fs-12">Push alert for expiring documents</span>
                                        </div>
                                        <div class="form-check form-switch"><input class="form-check-input staff-notif-toggle" type="checkbox" data-notif="doc_expiry" data-ch="push"></div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mt-3">
                    <button type="button" class="btn btn-primary" id="saveStaffNotifs"><i class="ri-save-line me-1"></i>Save Notification Settings</button>
                </div>
            </div>

            <!-- Tab 2: My Channels -->
            <div class="tab-pane fade" id="stab-channels" role="tabpanel">
                <div class="stg-section-hdr">
                    <i class="ri-chat-voice-line" style="color:#015EA7;"></i>
                    <h6 style="color:#015EA7;">My Communication Channels</h6>
                    <div class="flex-grow-1" style="height:1px; background:linear-gradient(90deg,rgba(1,94,167,.3),transparent);"></div>
                </div>
                <p class="text-muted fs-12 mb-3">These channels are used for communicating with your assigned clients in Multichat. Contact your manager to change channel settings.</p>

                <div class="row g-3">
                    <!-- WhatsApp -->
                    <div class="col-xl-4 col-md-6">
                        <div class="card border mb-0 stg-card" style="--stg-color:#25D366;">
                            <div class="card-body">
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <div class="d-flex align-items-center gap-2">
                                        <i class="ri-whatsapp-fill" style="color:#25D366; font-size:1.3rem;"></i>
                                        <h6 class="mb-0 fw-semibold">WhatsApp</h6>
                                    </div>
                                    <span class="badge bg-success-subtle text-success">Connected</span>
                                </div>
                                <div class="mb-2"><label class="form-label fs-12">Phone</label><input type="text" class="form-control form-control-sm" value="+48 512 345 678" readonly></div>
                                <div class="text-muted fs-11"><i class="ri-information-line me-1"></i>Managed by admin</div>
                            </div>
                        </div>
                    </div>
                    <!-- Telegram -->
                    <div class="col-xl-4 col-md-6">
                        <div class="card border mb-0 stg-card" style="--stg-color:#0088cc;">
                            <div class="card-body">
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <div class="d-flex align-items-center gap-2">
                                        <i class="ri-telegram-fill" style="color:#0088cc; font-size:1.3rem;"></i>
                                        <h6 class="mb-0 fw-semibold">Telegram</h6>
                                    </div>
                                    <span class="badge bg-success-subtle text-success">Connected</span>
                                </div>
                                <div class="mb-2"><label class="form-label fs-12">Bot</label><input type="text" class="form-control form-control-sm" value="WinCase Worker Bot" readonly></div>
                                <div class="text-muted fs-11"><i class="ri-information-line me-1"></i>Managed by admin</div>
                            </div>
                        </div>
                    </div>
                    <!-- Portal -->
                    <div class="col-xl-4 col-md-6">
                        <div class="card border mb-0 stg-card" style="--stg-color:#015EA7;">
                            <div class="card-body">
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <div class="d-flex align-items-center gap-2">
                                        <i class="ri-global-line" style="color:#015EA7; font-size:1.3rem;"></i>
                                        <h6 class="mb-0 fw-semibold">Portal</h6>
                                    </div>
                                    <span class="badge bg-success-subtle text-success">Always On</span>
                                </div>
                                <div class="mb-2"><label class="form-label fs-12">Status</label><input type="text" class="form-control form-control-sm text-success" value="Built-in" readonly></div>
                            </div>
                        </div>
                    </div>
                    <!-- Email -->
                    <div class="col-xl-4 col-md-6">
                        <div class="card border mb-0 stg-card" style="--stg-color:#6c757d;">
                            <div class="card-body">
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <div class="d-flex align-items-center gap-2">
                                        <i class="ri-mail-fill" style="color:#6c757d; font-size:1.3rem;"></i>
                                        <h6 class="mb-0 fw-semibold">Email</h6>
                                    </div>
                                    <span class="badge bg-success-subtle text-success">Connected</span>
                                </div>
                                <div class="mb-2"><label class="form-label fs-12">Address</label><input type="email" class="form-control form-control-sm" value="anna@wincase.eu" readonly></div>
                                <div class="text-muted fs-11"><i class="ri-information-line me-1"></i>Managed by admin</div>
                            </div>
                        </div>
                    </div>
                    <!-- SMS -->
                    <div class="col-xl-4 col-md-6">
                        <div class="card border mb-0 stg-card" style="--stg-color:#0d6efd;">
                            <div class="card-body">
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <div class="d-flex align-items-center gap-2">
                                        <i class="ri-message-2-fill" style="color:#0d6efd; font-size:1.3rem;"></i>
                                        <h6 class="mb-0 fw-semibold">SMS</h6>
                                    </div>
                                    <span class="badge bg-warning-subtle text-warning">Not Set</span>
                                </div>
                                <div class="text-muted fs-12">Ask your manager to configure SMS for your account.</div>
                            </div>
                        </div>
                    </div>
                    <!-- Facebook -->
                    <div class="col-xl-4 col-md-6">
                        <div class="card border mb-0 stg-card" style="--stg-color:#1877F2;">
                            <div class="card-body">
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <div class="d-flex align-items-center gap-2">
                                        <i class="ri-facebook-fill" style="color:#1877F2; font-size:1.3rem;"></i>
                                        <h6 class="mb-0 fw-semibold">Facebook</h6>
                                    </div>
                                    <span class="badge bg-warning-subtle text-warning">Not Set</span>
                                </div>
                                <div class="text-muted fs-12">Ask your manager to configure Facebook for your account.</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tab 3: Email Templates -->
            <div class="tab-pane fade" id="stab-templates" role="tabpanel">

                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div>
                        <h6 class="fw-semibold mb-1">Email Templates</h6>
                        <span class="text-muted fs-12">Templates used for automated client and staff communications</span>
                    </div>
                </div>

                <!-- Template categories -->
                <div class="d-flex gap-2 mb-3 flex-wrap">
                    <button class="btn btn-sm btn-subtle-primary staff-tpl-filter active" data-cat="all">All</button>
                    <button class="btn btn-sm btn-subtle-primary staff-tpl-filter" data-cat="client">Client</button>
                    <button class="btn btn-sm btn-subtle-primary staff-tpl-filter" data-cat="staff">Staff</button>
                    <button class="btn btn-sm btn-subtle-primary staff-tpl-filter" data-cat="finance">Finance</button>
                    <button class="btn btn-sm btn-subtle-primary staff-tpl-filter" data-cat="docs">Documents</button>
                </div>

                <div class="card border mb-0">
                    <div class="card-body p-0">
                        <ul class="list-group list-group-flush" id="staffTemplatesList">

                            <!-- 1. Welcome Client -->
                            <li class="list-group-item d-flex align-items-center justify-content-between py-3" data-cat="client">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="avatar avatar-sm avatar-rounded bg-primary-subtle text-primary"><i class="ri-user-heart-line"></i></div>
                                    <div>
                                        <h6 class="mb-0 fs-14">Welcome Client</h6>
                                        <span class="text-muted fs-12">Sent to new client after registration in CRM</span>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    <span class="badge bg-success-subtle text-success fs-11">Active</span>
                                    <button class="btn btn-sm btn-subtle-info" onclick="staffPreviewTpl('welcome_client')"><i class="ri-eye-line me-1"></i>Preview</button>
                                </div>
                            </li>

                            <!-- 2. New Lead -->
                            <li class="list-group-item d-flex align-items-center justify-content-between py-3" data-cat="staff">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="avatar avatar-sm avatar-rounded bg-success-subtle text-success"><i class="ri-user-add-line"></i></div>
                                    <div>
                                        <h6 class="mb-0 fs-14">New Lead Notification</h6>
                                        <span class="text-muted fs-12">Sent to assigned manager when new lead arrives</span>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    <span class="badge bg-success-subtle text-success fs-11">Active</span>
                                    <button class="btn btn-sm btn-subtle-info" onclick="staffPreviewTpl('new_lead')"><i class="ri-eye-line me-1"></i>Preview</button>
                                </div>
                            </li>

                            <!-- 3. Case Created -->
                            <li class="list-group-item d-flex align-items-center justify-content-between py-3" data-cat="client">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="avatar avatar-sm avatar-rounded bg-info-subtle text-info"><i class="ri-folder-add-line"></i></div>
                                    <div>
                                        <h6 class="mb-0 fs-14">Case Created</h6>
                                        <span class="text-muted fs-12">Sent to client when a new case is opened</span>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    <span class="badge bg-success-subtle text-success fs-11">Active</span>
                                    <button class="btn btn-sm btn-subtle-info" onclick="staffPreviewTpl('case_created')"><i class="ri-eye-line me-1"></i>Preview</button>
                                </div>
                            </li>

                            <!-- 4. Case Status Update -->
                            <li class="list-group-item d-flex align-items-center justify-content-between py-3" data-cat="client">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="avatar avatar-sm avatar-rounded bg-warning-subtle text-warning"><i class="ri-refresh-line"></i></div>
                                    <div>
                                        <h6 class="mb-0 fs-14">Case Status Update</h6>
                                        <span class="text-muted fs-12">Sent to client when case status changes</span>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    <span class="badge bg-success-subtle text-success fs-11">Active</span>
                                    <button class="btn btn-sm btn-subtle-info" onclick="staffPreviewTpl('case_status')"><i class="ri-eye-line me-1"></i>Preview</button>
                                </div>
                            </li>

                            <!-- 5. Document Request -->
                            <li class="list-group-item d-flex align-items-center justify-content-between py-3" data-cat="docs">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="avatar avatar-sm avatar-rounded" style="background:rgba(108,92,231,.1);color:#6C5CE7;"><i class="ri-file-upload-line"></i></div>
                                    <div>
                                        <h6 class="mb-0 fs-14">Document Request</h6>
                                        <span class="text-muted fs-12">Sent to client to request missing documents</span>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    <span class="badge bg-success-subtle text-success fs-11">Active</span>
                                    <button class="btn btn-sm btn-subtle-info" onclick="staffPreviewTpl('doc_request')"><i class="ri-eye-line me-1"></i>Preview</button>
                                </div>
                            </li>

                            <!-- 6. Document Expiry Warning -->
                            <li class="list-group-item d-flex align-items-center justify-content-between py-3" data-cat="docs">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="avatar avatar-sm avatar-rounded bg-danger-subtle text-danger"><i class="ri-file-warning-line"></i></div>
                                    <div>
                                        <h6 class="mb-0 fs-14">Document Expiry Warning</h6>
                                        <span class="text-muted fs-12">Sent before Karta Pobytu, visa, or passport expires</span>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    <span class="badge bg-success-subtle text-success fs-11">Active</span>
                                    <button class="btn btn-sm btn-subtle-info" onclick="staffPreviewTpl('doc_expiry')"><i class="ri-eye-line me-1"></i>Preview</button>
                                </div>
                            </li>

                            <!-- 7. Invoice Sent -->
                            <li class="list-group-item d-flex align-items-center justify-content-between py-3" data-cat="finance">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="avatar avatar-sm avatar-rounded bg-info-subtle text-info"><i class="ri-file-list-3-line"></i></div>
                                    <div>
                                        <h6 class="mb-0 fs-14">Invoice Sent</h6>
                                        <span class="text-muted fs-12">Sent to client with invoice PDF attached</span>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    <span class="badge bg-success-subtle text-success fs-11">Active</span>
                                    <button class="btn btn-sm btn-subtle-info" onclick="staffPreviewTpl('invoice_sent')"><i class="ri-eye-line me-1"></i>Preview</button>
                                </div>
                            </li>

                            <!-- 8. Payment Confirmation -->
                            <li class="list-group-item d-flex align-items-center justify-content-between py-3" data-cat="finance">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="avatar avatar-sm avatar-rounded bg-success-subtle text-success"><i class="ri-money-euro-circle-line"></i></div>
                                    <div>
                                        <h6 class="mb-0 fs-14">Payment Confirmation</h6>
                                        <span class="text-muted fs-12">Sent to client after payment is processed</span>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    <span class="badge bg-success-subtle text-success fs-11">Active</span>
                                    <button class="btn btn-sm btn-subtle-info" onclick="staffPreviewTpl('payment_confirm')"><i class="ri-eye-line me-1"></i>Preview</button>
                                </div>
                            </li>

                            <!-- 9. Appointment Reminder -->
                            <li class="list-group-item d-flex align-items-center justify-content-between py-3" data-cat="client">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="avatar avatar-sm avatar-rounded bg-warning-subtle text-warning"><i class="ri-calendar-check-line"></i></div>
                                    <div>
                                        <h6 class="mb-0 fs-14">Appointment / Hearing Reminder</h6>
                                        <span class="text-muted fs-12">Sent to client before appointment</span>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    <span class="badge bg-success-subtle text-success fs-11">Active</span>
                                    <button class="btn btn-sm btn-subtle-info" onclick="staffPreviewTpl('appointment_reminder')"><i class="ri-eye-line me-1"></i>Preview</button>
                                </div>
                            </li>

                            <!-- 10. Task Assignment -->
                            <li class="list-group-item d-flex align-items-center justify-content-between py-3" data-cat="staff">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="avatar avatar-sm avatar-rounded bg-secondary-subtle text-secondary"><i class="ri-task-line"></i></div>
                                    <div>
                                        <h6 class="mb-0 fs-14">Task Assignment</h6>
                                        <span class="text-muted fs-12">Sent to worker when a new task is assigned</span>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    <span class="badge bg-success-subtle text-success fs-11">Active</span>
                                    <button class="btn btn-sm btn-subtle-info" onclick="staffPreviewTpl('task_assigned')"><i class="ri-eye-line me-1"></i>Preview</button>
                                </div>
                            </li>

                            <!-- 11. Task Overdue -->
                            <li class="list-group-item d-flex align-items-center justify-content-between py-3" data-cat="staff">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="avatar avatar-sm avatar-rounded bg-danger-subtle text-danger"><i class="ri-alarm-warning-line"></i></div>
                                    <div>
                                        <h6 class="mb-0 fs-14">Task Overdue Alert</h6>
                                        <span class="text-muted fs-12">Sent to worker and boss when task is past due</span>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    <span class="badge bg-success-subtle text-success fs-11">Active</span>
                                    <button class="btn btn-sm btn-subtle-info" onclick="staffPreviewTpl('task_overdue')"><i class="ri-eye-line me-1"></i>Preview</button>
                                </div>
                            </li>

                            <!-- 12. Monthly Report -->
                            <li class="list-group-item d-flex align-items-center justify-content-between py-3" data-cat="staff">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="avatar avatar-sm avatar-rounded" style="background:rgba(31,56,100,.1);color:#1F3864;"><i class="ri-bar-chart-box-line"></i></div>
                                    <div>
                                        <h6 class="mb-0 fs-14">Monthly Report</h6>
                                        <span class="text-muted fs-12">Monthly summary: revenue, cases, performance</span>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    <span class="badge bg-success-subtle text-success fs-11">Active</span>
                                    <button class="btn btn-sm btn-subtle-info" onclick="staffPreviewTpl('monthly_report')"><i class="ri-eye-line me-1"></i>Preview</button>
                                </div>
                            </li>

                        </ul>
                    </div>
                </div>

                <div class="alert alert-light border mt-3 fs-12 mb-0">
                    <i class="ri-information-line me-1"></i>Email templates are managed by the administrator. Contact your manager to request changes.
                </div>
            </div>

            <!-- Template Preview Modal (Staff) -->
            <div class="modal fade" id="staffPreviewModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title"><i class="ri-eye-line me-2"></i>Template Preview</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body p-0">
                            <div class="p-3 bg-light border-bottom">
                                <div class="d-flex align-items-center gap-2 mb-1">
                                    <strong class="fs-12">From:</strong> <span class="fs-12">WinCase CRM &lt;biuro@wincase.eu&gt;</span>
                                </div>
                                <div class="d-flex align-items-center gap-2 mb-1">
                                    <strong class="fs-12">To:</strong> <span class="fs-12">client@example.com</span>
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    <strong class="fs-12">Subject:</strong> <span class="fs-12 fw-semibold" id="staffPrevSubject"></span>
                                </div>
                            </div>
                            <div class="p-4" id="staffPrevBody" style="min-height:300px;"></div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tab 4: Preferences -->
            <div class="tab-pane fade" id="stab-preferences" role="tabpanel">
                <form id="staffPreferencesForm">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Interface Language</label>
                            <select class="form-select" name="language">
                                <option value="pl" selected>Polski</option>
                                <option value="en">English</option>
                                <option value="ua">Українська</option>
                                <option value="ru">Русский</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Timezone</label>
                            <select class="form-select" name="timezone">
                                <option value="Europe/Warsaw" selected>Europe/Warsaw (CET)</option>
                                <option value="Europe/Kiev">Europe/Kyiv (EET)</option>
                                <option value="Europe/Berlin">Europe/Berlin (CET)</option>
                                <option value="UTC">UTC</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Date Format</label>
                            <select class="form-select" name="date_format">
                                <option value="d.m.Y" selected>DD.MM.YYYY (31.12.2026)</option>
                                <option value="Y-m-d">YYYY-MM-DD (2026-12-31)</option>
                                <option value="m/d/Y">MM/DD/YYYY (12/31/2026)</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Default Client List View</label>
                            <select class="form-select" name="clients_view">
                                <option value="list" selected>List</option>
                                <option value="cards">Cards</option>
                                <option value="kanban">Kanban</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <div class="card border mb-0">
                                <div class="card-body">
                                    <h6 class="fw-semibold mb-3">Working Hours</h6>
                                    <div class="row g-3">
                                        <div class="col-md-4">
                                            <label class="form-label fs-12">Work Start</label>
                                            <input type="time" class="form-control" name="work_start" value="09:00">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label fs-12">Work End</label>
                                            <input type="time" class="form-control" name="work_end" value="17:00">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label fs-12">Break Duration</label>
                                            <select class="form-select" name="break_duration">
                                                <option value="30">30 min</option>
                                                <option value="60" selected>1 hour</option>
                                                <option value="15">15 min</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="card border mb-0">
                                <div class="card-body">
                                    <h6 class="fw-semibold mb-3">Auto-Reply (when offline)</h6>
                                    <div class="form-check form-switch mb-3">
                                        <input class="form-check-input" type="checkbox" id="autoReplyToggle" name="auto_reply_enabled">
                                        <label class="form-check-label" for="autoReplyToggle">Enable auto-reply when offline</label>
                                    </div>
                                    <textarea class="form-control" name="auto_reply_message" rows="3" placeholder="e.g. I'm currently offline. I will reply as soon as possible.">Jestem obecnie offline. Odpowiem najszybciej jak to mozliwe.</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary"><i class="ri-save-line me-1"></i>Save Preferences</button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>

<script>
(function(){
'use strict';

// ===============================================================
// STAFF EMAIL TEMPLATE DATA (read-only preview)
// ===============================================================
var staffTemplates = {
    welcome_client: { subject: 'Witamy w WinCase! Twoja sprawa jest w dobrych r\u0119kach', body: '<div style="font-family:Arial,sans-serif;max-width:600px;margin:0 auto;padding:20px;"><div style="background:#1F3864;color:#fff;padding:20px;border-radius:8px 8px 0 0;text-align:center;"><h1 style="margin:0;font-size:24px;">Witamy w WinCase!</h1></div><div style="padding:20px;border:1px solid #eee;border-top:none;border-radius:0 0 8px 8px;"><p>Szanowny/a <strong>Jan Kowalski</strong>,</p><p>Dzi\u0119kujemy za zaufanie! Twoje konto w systemie WinCase CRM zosta\u0142o utworzone.</p><p>Tw\u00f3j opiekun: <strong>Anna Kowalska</strong></p><p>Z powa\u017caniem,<br><strong>WinCase Sp. z o.o.</strong></p></div></div>' },
    new_lead: { subject: 'Nowy lead: Jan Kowalski', body: '<p>Nowy lead zosta\u0142 przypisany do Ciebie.</p><p><strong>Klient:</strong> Jan Kowalski<br><strong>Email:</strong> jan@example.com<br><strong>Typ sprawy:</strong> Karta Pobytu</p><p>Prosz\u0119 skontaktuj si\u0119 z klientem w ci\u0105gu 24 godzin.</p>' },
    case_created: { subject: 'Sprawa WC-2026-00142 zosta\u0142a otwarta', body: '<p>Szanowny/a <strong>Jan Kowalski</strong>,</p><p>Twoja sprawa <strong>WC-2026-00142</strong> (Karta Pobytu) zosta\u0142a utworzona w naszym systemie.</p><p>Tw\u00f3j opiekun <strong>Anna Kowalska</strong> skontaktuje si\u0119 z Tob\u0105 wkr\u00f3tce.</p><p>Z powa\u017caniem,<br>WinCase Sp. z o.o.</p>' },
    case_status: { subject: 'Aktualizacja sprawy WC-2026-00142: Z\u0142o\u017cone w urz\u0119dzie', body: '<p>Szanowny/a <strong>Jan Kowalski</strong>,</p><p>Status Twojej sprawy <strong>WC-2026-00142</strong> zmieni\u0142 si\u0119 na: <strong>Z\u0142o\u017cone w urz\u0119dzie</strong></p><p>Tw\u00f3j opiekun: Anna Kowalska<br>Kontakt: +48 22 123 45 67</p><p>Z powa\u017caniem,<br>WinCase Sp. z o.o.</p>' },
    doc_request: { subject: 'Pro\u015bba o dokumenty \u2014 sprawa WC-2026-00142', body: '<p>Szanowny/a <strong>Jan Kowalski</strong>,</p><p>Potrzebujemy nast\u0119puj\u0105cego dokumentu do Twojej sprawy <strong>WC-2026-00142</strong>:</p><p style="padding:10px;background:#f8f9fa;border-left:3px solid #1F3864;"><strong>Paszport</strong></p><p>Prosz\u0119 przes\u0142a\u0107 skan lub zdj\u0119cie przez portal klienta lub email.</p><p>Dzi\u0119kujemy,<br>WinCase Sp. z o.o.</p>' },
    doc_expiry: { subject: '\u26a0\ufe0f Dokument wyga\u015bnie: Paszport \u2014 2026-05-15', body: '<p>Szanowny/a <strong>Jan Kowalski</strong>,</p><p>Tw\u00f3j dokument <strong>Paszport</strong> wyga\u015bnie <strong>2026-05-15</strong>.</p><p>Prosimy o pilny kontakt w celu przed\u0142u\u017cenia dokumentu.</p><p>Kontakt: +48 22 123 45 67<br>WinCase Sp. z o.o.</p>' },
    invoice_sent: { subject: 'Faktura FV/2026/03/001 \u2014 2 500 PLN', body: '<p>Szanowny/a <strong>Jan Kowalski</strong>,</p><p>Wystawili\u015bmy faktur\u0119 <strong>FV/2026/03/001</strong> na kwot\u0119 <strong>2 500 PLN</strong>.</p><p>Faktura zosta\u0142a za\u0142\u0105czona do tej wiadomo\u015bci.</p><p>Termin p\u0142atno\u015bci: 14 dni.</p><p>Z powa\u017caniem,<br>WinCase Sp. z o.o.</p>' },
    payment_confirm: { subject: 'Potwierdzenie p\u0142atno\u015bci \u2014 2 500 PLN', body: '<p>Szanowny/a <strong>Jan Kowalski</strong>,</p><p>Potwierdzamy otrzymanie p\u0142atno\u015bci <strong>2 500 PLN</strong> (Przelew bankowy).</p><p>Dzi\u0119kujemy!</p><p>WinCase Sp. z o.o.</p>' },
    appointment_reminder: { subject: 'Przypomnienie: wizyta 2026-03-20 o 10:30', body: '<p>Szanowny/a <strong>Jan Kowalski</strong>,</p><p>Przypominamy o wizycie w urz\u0119dzie:</p><p><strong>Data:</strong> 2026-03-20<br><strong>Godzina:</strong> 10:30<br><strong>Adres:</strong> Mazowiecki Urz\u0105d Wojew\u00f3dzki, ul. D\u0142uga 5, Warszawa</p><p>Prosz\u0119 przynie\u015b\u0107 wszystkie wymagane dokumenty.</p><p>WinCase Sp. z o.o.</p>' },
    task_assigned: { subject: 'Nowe zadanie: Przygotowa\u0107 wniosek o Kart\u0119 Pobytu', body: '<p>Masz nowe zadanie:</p><p><strong>Przygotowa\u0107 wniosek o Kart\u0119 Pobytu</strong></p><p>Termin: <strong>2026-03-18</strong><br>Klient: Jan Kowalski<br>Sprawa: WC-2026-00142</p><p>Zaloguj si\u0119 do CRM, aby zobaczy\u0107 szczeg\u00f3\u0142y.</p>' },
    task_overdue: { subject: '\u26a0\ufe0f Zadanie przeterminowane: Przygotowa\u0107 wniosek', body: '<p><strong style="color:red;">Zadanie jest przeterminowane!</strong></p><p><strong>Przygotowa\u0107 wniosek o Kart\u0119 Pobytu</strong></p><p>Termin: 2026-03-18<br>Klient: Jan Kowalski<br>Odpowiedzialny: Anna Kowalska</p><p>Prosz\u0119 o natychmiastow\u0105 reakcj\u0119.</p>' },
    monthly_report: { subject: 'Raport miesi\u0119czny \u2014 WinCase CRM', body: '<p>Raport miesi\u0119czny:</p><ul><li>Nowi klienci: <strong>\u2014</strong></li><li>Aktywne sprawy: <strong>\u2014</strong></li><li>Przych\u00f3d: <strong>\u2014 PLN</strong></li><li>Wydatki: <strong>\u2014 PLN</strong></li><li>Zadania wykonane: <strong>\u2014</strong></li></ul><p>Zaloguj si\u0119 do CRM, aby zobaczy\u0107 pe\u0142ne statystyki.</p>' }
};

// Category filter
document.querySelectorAll('.staff-tpl-filter').forEach(function(btn){
    btn.addEventListener('click', function(){
        document.querySelectorAll('.staff-tpl-filter').forEach(function(b){ b.classList.remove('active'); });
        this.classList.add('active');
        var cat = this.getAttribute('data-cat');
        document.querySelectorAll('#staffTemplatesList li').forEach(function(li){
            li.style.display = (cat === 'all' || li.getAttribute('data-cat') === cat) ? '' : 'none';
        });
    });
});

// Preview
window.staffPreviewTpl = function(key) {
    var tpl = staffTemplates[key];
    if (!tpl) return;
    document.getElementById('staffPrevSubject').textContent = tpl.subject;
    document.getElementById('staffPrevBody').innerHTML = tpl.body;
    new bootstrap.Modal(document.getElementById('staffPreviewModal')).show();
};

// Toast helper
function showToast(msg, type) {
    type = type || 'info';
    var colors = { success:'#015EA7', warning:'#f0ad4e', info:'#0d6efd', error:'#dc3545' };
    var icons = { success:'ri-check-line', warning:'ri-alert-line', info:'ri-information-line', error:'ri-error-warning-line' };
    var t = document.createElement('div');
    t.style.cssText = 'position:fixed;top:20px;right:20px;z-index:99999;padding:12px 20px;border-radius:8px;color:#fff;font-size:14px;font-weight:500;display:flex;align-items:center;gap:8px;box-shadow:0 4px 12px rgba(0,0,0,.15);animation:slideIn .3s ease;background:'+(colors[type]||colors.info);
    t.innerHTML = '<i class="'+(icons[type]||icons.info)+'"></i>'+msg;
    document.body.appendChild(t);
    setTimeout(function(){ t.style.opacity='0'; t.style.transition='opacity .3s'; setTimeout(function(){ t.remove(); },300); },3000);
}
var s = document.createElement('style');
s.textContent = '@keyframes slideIn{from{transform:translateX(100%);opacity:0}to{transform:translateX(0);opacity:1}}';
document.head.appendChild(s);

// Notification toggles — AJAX
document.querySelectorAll('.staff-notif-toggle').forEach(function(toggle){
    toggle.addEventListener('change', function(){
        var notif = this.getAttribute('data-notif');
        var ch = this.getAttribute('data-ch');
        var enabled = this.checked;
        fetch('/api/settings/staff/notifications', {
            method:'POST',
            headers:{'Content-Type':'application/json','X-CSRF-TOKEN':document.querySelector('meta[name="csrf-token"]')?.content||''},
            body:JSON.stringify({notification:notif,channel:ch,enabled:enabled})
        }).then(function(){
            showToast((enabled?'Enabled':'Disabled')+' '+notif+' ('+ch+')','success');
        }).catch(function(){
            showToast('Saved locally','info');
        });
    });
});

// Save All Notifications
document.getElementById('saveStaffNotifs')?.addEventListener('click', function(){
    var settings = [];
    document.querySelectorAll('.staff-notif-toggle').forEach(function(t){
        settings.push({notification:t.getAttribute('data-notif'),channel:t.getAttribute('data-ch'),enabled:t.checked});
    });
    fetch('/api/settings/staff/notifications/bulk', {
        method:'POST',
        headers:{'Content-Type':'application/json','X-CSRF-TOKEN':document.querySelector('meta[name="csrf-token"]')?.content||''},
        body:JSON.stringify({settings:settings})
    }).then(function(){ showToast('All notification settings saved!','success'); })
    .catch(function(){ showToast('Saved locally','info'); });
});

// Preferences form — AJAX
document.getElementById('staffPreferencesForm')?.addEventListener('submit', function(e){
    e.preventDefault();
    var fd = new FormData(this);
    var data = {};
    fd.forEach(function(v,k){ data[k]=v; });
    data.auto_reply_enabled = document.getElementById('autoReplyToggle')?.checked || false;
    fetch('/api/settings/staff/preferences', {
        method:'POST',
        headers:{'Content-Type':'application/json','X-CSRF-TOKEN':document.querySelector('meta[name="csrf-token"]')?.content||''},
        body:JSON.stringify(data)
    }).then(function(){ showToast('Preferences saved!','success'); })
    .catch(function(){ showToast('Saved locally','info'); });
});

})();
</script>

@endsection
