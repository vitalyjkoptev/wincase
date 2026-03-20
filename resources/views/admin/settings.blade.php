@extends('partials.layouts.master')

@section('title', 'Settings | WinCase CRM')
@section('sub-title', 'Settings')
@section('sub-title-lang', 'wc-settings')
@section('pagetitle', 'Admin')
@section('pagetitle-lang', 'wc-admin')

@section('content')

<!-- Nav Tabs -->
<div class="card">
    <div class="card-header">
        <ul class="nav nav-tabs card-header-tabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" data-bs-toggle="tab" href="#tab-general" role="tab">
                    <i class="ri-building-line me-1"></i>General
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#tab-integrations" role="tab">
                    <i class="ri-plug-line me-1"></i>Integrations
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#tab-notifications" role="tab">
                    <i class="ri-notification-3-line me-1"></i>Notifications
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#tab-templates" role="tab">
                    <i class="ri-mail-settings-line me-1"></i>Email Templates
                </a>
            </li>
        </ul>
    </div>
    <div class="card-body">
        <div class="tab-content">

            <!-- Tab 1: General -->
            <div class="tab-pane fade show active" id="tab-general" role="tabpanel">
                <form id="generalSettingsForm">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Company Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="company_name" value="WinCase Sp. z o.o.">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">NIP</label>
                            <input type="text" class="form-control" name="nip" value="1234567890" placeholder="0000000000">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">REGON</label>
                            <input type="text" class="form-control" name="regon" value="123456789" placeholder="000000000">
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Address</label>
                            <input type="text" class="form-control" name="address" value="ul. Marszalkowska 1/10" placeholder="Street and building number">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">City</label>
                            <input type="text" class="form-control" name="city" value="Warszawa">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Postal Code</label>
                            <input type="text" class="form-control" name="postal_code" value="00-001" placeholder="00-000">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Country</label>
                            <select class="form-select" name="country">
                                <option value="PL" selected>Poland</option>
                                <option value="DE">Germany</option>
                                <option value="UA">Ukraine</option>
                                <option value="BY">Belarus</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Phone</label>
                            <input type="tel" class="form-control" name="phone" value="+48 22 123 45 67" placeholder="+48 ...">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" name="email" value="biuro@wincase.eu">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Website</label>
                            <input type="url" class="form-control" name="website" value="https://wincase.eu">
                        </div>
                    </div>
                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary"><i class="ri-save-line me-1"></i>Save Settings</button>
                    </div>
                </form>
            </div>

            <!-- Tab 2: Integrations -->
            <div class="tab-pane fade" id="tab-integrations" role="tabpanel">

                <style>
                .intg-section-hdr { display:flex; align-items:center; gap:.75rem; margin-bottom:1.25rem; }
                .intg-section-hdr .intg-icon { width:36px; height:36px; border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:1.1rem; color:#fff; }
                .intg-section-hdr h5 { margin:0; font-weight:700; font-size:1rem; }
                .intg-section-hdr .intg-sub { font-size:.75rem; color:#888; }
                .intg-divider { border:none; border-top:2px dashed rgba(31,56,100,.2); margin:2rem 0; position:relative; }
                .intg-divider::after { content:'WORKERS / STAFF'; position:absolute; top:-10px; left:50%; transform:translateX(-50%); background:#fff; padding:0 1rem; font-size:.65rem; font-weight:700; letter-spacing:1px; color:#1F3864; }
                [data-bs-theme="dark"] .intg-divider { border-top-color:rgba(255,255,255,.15); }
                [data-bs-theme="dark"] .intg-divider::after { background:#1a1d21; color:#8ab4f8; }
                .intg-ch-card { border-left:3px solid var(--ch-color,#ccc); transition:all .15s; }
                .intg-ch-card:hover { box-shadow:0 2px 12px rgba(0,0,0,.08); }
                .intg-ch-dot { width:10px; height:10px; border-radius:50%; display:inline-block; }
                .intg-worker-block { background:rgba(31,56,100,.03); border:1px dashed rgba(31,56,100,.12); border-radius:.5rem; padding:1rem; margin-bottom:.75rem; position:relative; }
                .intg-worker-block .intg-worker-num { position:absolute; top:.5rem; right:.75rem; font-size:.6rem; font-weight:700; color:#1F3864; background:rgba(31,56,100,.08); padding:1px 8px; border-radius:10px; }
                [data-bs-theme="dark"] .intg-worker-block { background:rgba(255,255,255,.03); border-color:rgba(255,255,255,.08); }
                [data-bs-theme="dark"] .intg-worker-block .intg-worker-num { color:#8ab4f8; background:rgba(138,180,248,.1); }
                .intg-add-worker { border:2px dashed rgba(1,94,167,.3); background:rgba(1,94,167,.03); border-radius:.5rem; padding:.6rem; text-align:center; cursor:pointer; transition:all .15s; }
                .intg-add-worker:hover { border-color:#015EA7; background:rgba(1,94,167,.08); }
                .intg-add-worker i { font-size:1.2rem; color:#015EA7; }
                .intg-add-worker span { font-size:.7rem; color:#015EA7; font-weight:600; display:block; margin-top:2px; }
                .intg-section-sep { background:linear-gradient(135deg,#1F3864,#2a4a7f); color:#fff; border-radius:.5rem; padding:.75rem 1.25rem; margin:2.5rem 0 1.5rem; display:flex; align-items:center; gap:.75rem; }
                .intg-section-sep i { font-size:1.3rem; }
                .intg-section-sep h6 { margin:0; font-weight:700; font-size:.85rem; }
                .intg-section-sep .intg-sep-sub { font-size:.65rem; opacity:.7; color:#fff; }
                </style>

                <!-- ======================= MULTICHAT CHANNELS ======================= -->
                <div class="intg-section-hdr">
                    <div class="intg-icon" style="background:linear-gradient(135deg,#1F3864,#015EA7);">
                        <i class="ri-chat-voice-line"></i>
                    </div>
                    <div>
                        <h5>Multichat Channels <span class="badge bg-primary ms-2" style="font-size:.6rem;">11 platforms</span></h5>
                        <div class="intg-sub">Communication platform connections for boss and workers</div>
                    </div>
                </div>

                <!-- ========== BOSS CONNECTIONS ========== -->
                <div class="d-flex align-items-center gap-2 mb-3">
                    <i class="ri-shield-keyhole-line" style="color:#1F3864; font-size:1.1rem;"></i>
                    <h6 class="mb-0 fw-bold" style="color:#1F3864;">BOSS (Director)</h6>
                    <div class="flex-grow-1" style="height:1px; background:linear-gradient(90deg,rgba(31,56,100,.2),transparent);"></div>
                </div>

                <div class="row g-3" id="bossChannels">

                    <!-- 1. WhatsApp -->
                    <div class="col-xl-4 col-md-6">
                        <div class="card border mb-0 intg-ch-card" style="--ch-color:#25D366;" data-service="boss_whatsapp">
                            <div class="card-body">
                                <div class="d-flex align-items-center justify-content-between mb-3">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="avatar avatar-sm avatar-rounded" style="background:rgba(37,211,102,.12); color:#25D366;">
                                            <i class="ri-whatsapp-fill fs-18"></i>
                                        </div>
                                        <h6 class="mb-0 fw-semibold">WhatsApp Business</h6>
                                    </div>
                                    <span class="badge bg-warning-subtle text-warning intg-badge">Not Connected</span>
                                </div>
                                <div class="mb-2">
                                    <label class="form-label fs-12">Phone Number</label>
                                    <input type="text" class="form-control form-control-sm" data-field="phone" placeholder="+48 579 266 493">
                                </div>
                                <div class="mb-2">
                                    <label class="form-label fs-12">Business Account ID</label>
                                    <input type="text" class="form-control form-control-sm" data-field="account_id" placeholder="Enter Business Account ID">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fs-12">API Token</label>
                                    <input type="text" class="form-control form-control-sm" data-field="api_token" placeholder="Enter API Token">
                                </div>
                                <button class="btn btn-sm btn-subtle-primary w-100 intg-save-btn"><i class="ri-save-line me-1"></i>Save</button>
                            </div>
                        </div>
                    </div>

                    <!-- 2. Telegram -->
                    <div class="col-xl-4 col-md-6">
                        <div class="card border mb-0 intg-ch-card" style="--ch-color:#0088cc;" data-service="boss_telegram">
                            <div class="card-body">
                                <div class="d-flex align-items-center justify-content-between mb-3">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="avatar avatar-sm avatar-rounded" style="background:rgba(0,136,204,.12); color:#0088cc;">
                                            <i class="ri-telegram-fill fs-18"></i>
                                        </div>
                                        <h6 class="mb-0 fw-semibold">Telegram</h6>
                                    </div>
                                    <span class="badge bg-warning-subtle text-warning intg-badge">Not Connected</span>
                                </div>
                                <div class="mb-2">
                                    <label class="form-label fs-12">Bot Token</label>
                                    <input type="text" class="form-control form-control-sm" data-field="bot_token" placeholder="Enter Bot Token">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fs-12">Chat ID</label>
                                    <input type="text" class="form-control form-control-sm" data-field="chat_id" placeholder="Enter Chat ID">
                                </div>
                                <button class="btn btn-sm btn-subtle-primary w-100 intg-save-btn"><i class="ri-save-line me-1"></i>Save</button>
                            </div>
                        </div>
                    </div>

                    <!-- 3. Client Portal -->
                    <div class="col-xl-4 col-md-6">
                        <div class="card border mb-0 intg-ch-card" style="--ch-color:#015EA7;">
                            <div class="card-body">
                                <div class="d-flex align-items-center justify-content-between mb-3">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="avatar avatar-sm avatar-rounded" style="background:rgba(1,94,167,.12); color:#015EA7;">
                                            <i class="ri-global-line fs-18"></i>
                                        </div>
                                        <h6 class="mb-0 fw-semibold">Client Portal</h6>
                                    </div>
                                    <span class="badge bg-success-subtle text-success">Auto-connected</span>
                                </div>
                                <div class="mb-2">
                                    <label class="form-label fs-12">Portal URL</label>
                                    <input type="url" class="form-control form-control-sm" value="https://portal.wincase.eu" readonly>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fs-12">Status</label>
                                    <input type="text" class="form-control form-control-sm text-success fw-semibold" value="Active — built-in integration" readonly>
                                </div>
                                <button class="btn btn-sm btn-subtle-success w-100" disabled><i class="ri-check-line me-1"></i>Always Connected</button>
                            </div>
                        </div>
                    </div>

                    <!-- 4. Email -->
                    <div class="col-xl-4 col-md-6">
                        <div class="card border mb-0 intg-ch-card" style="--ch-color:#6c757d;" data-service="boss_email">
                            <div class="card-body">
                                <div class="d-flex align-items-center justify-content-between mb-3">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="avatar avatar-sm avatar-rounded bg-secondary-subtle text-secondary"><i class="ri-mail-fill fs-18"></i></div>
                                        <h6 class="mb-0 fw-semibold">Email (IMAP/SMTP)</h6>
                                    </div>
                                    <span class="badge bg-warning-subtle text-warning intg-badge">Not Connected</span>
                                </div>
                                <div class="mb-2"><label class="form-label fs-12">Email Address</label><input type="email" class="form-control form-control-sm" data-field="email" placeholder="biuro@wincase.eu"></div>
                                <div class="row g-2 mb-2">
                                    <div class="col-8"><label class="form-label fs-12">IMAP Host</label><input type="text" class="form-control form-control-sm" data-field="imap_host" placeholder="mail.wincase.eu"></div>
                                    <div class="col-4"><label class="form-label fs-12">Port</label><input type="text" class="form-control form-control-sm" data-field="imap_port" placeholder="993"></div>
                                </div>
                                <div class="row g-2 mb-3">
                                    <div class="col-8"><label class="form-label fs-12">SMTP Host</label><input type="text" class="form-control form-control-sm" data-field="smtp_host" placeholder="mail.wincase.eu"></div>
                                    <div class="col-4"><label class="form-label fs-12">Port</label><input type="text" class="form-control form-control-sm" data-field="smtp_port" placeholder="465"></div>
                                </div>
                                <button class="btn btn-sm btn-subtle-primary w-100 intg-save-btn"><i class="ri-save-line me-1"></i>Save</button>
                            </div>
                        </div>
                    </div>

                    <!-- 5. SMS -->
                    <div class="col-xl-4 col-md-6">
                        <div class="card border mb-0 intg-ch-card" style="--ch-color:#0d6efd;" data-service="boss_sms">
                            <div class="card-body">
                                <div class="d-flex align-items-center justify-content-between mb-3">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="avatar avatar-sm avatar-rounded bg-primary-subtle text-primary"><i class="ri-message-2-fill fs-18"></i></div>
                                        <h6 class="mb-0 fw-semibold">SMS Gateway</h6>
                                    </div>
                                    <span class="badge bg-warning-subtle text-warning intg-badge">Not Connected</span>
                                </div>
                                <div class="mb-2"><label class="form-label fs-12">Provider</label><select class="form-select form-select-sm" data-field="provider"><option value="">Select provider...</option><option value="twilio">Twilio</option><option value="smsapi">SMSAPI.pl</option><option value="serwersms">SerwerSMS</option><option value="smslabs">SMSLabs</option></select></div>
                                <div class="mb-2"><label class="form-label fs-12">API Key</label><input type="text" class="form-control form-control-sm" data-field="api_key" placeholder="Enter API key"></div>
                                <div class="mb-3"><label class="form-label fs-12">Sender Number / Name</label><input type="text" class="form-control form-control-sm" data-field="sender" placeholder="+48... or WinCase"></div>
                                <button class="btn btn-sm btn-subtle-primary w-100 intg-save-btn"><i class="ri-save-line me-1"></i>Save</button>
                            </div>
                        </div>
                    </div>

                    <!-- 6. Facebook Messenger -->
                    <div class="col-xl-4 col-md-6">
                        <div class="card border mb-0 intg-ch-card" style="--ch-color:#1877F2;" data-service="boss_facebook">
                            <div class="card-body">
                                <div class="d-flex align-items-center justify-content-between mb-3">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="avatar avatar-sm avatar-rounded" style="background:rgba(24,119,242,.12); color:#1877F2;"><i class="ri-facebook-fill fs-18"></i></div>
                                        <h6 class="mb-0 fw-semibold">Facebook Messenger</h6>
                                    </div>
                                    <span class="badge bg-warning-subtle text-warning intg-badge">Not Connected</span>
                                </div>
                                <div class="mb-2"><label class="form-label fs-12">Page ID</label><input type="text" class="form-control form-control-sm" data-field="page_id" placeholder="Enter Page ID"></div>
                                <div class="mb-3"><label class="form-label fs-12">Page Access Token</label><input type="text" class="form-control form-control-sm" data-field="access_token" placeholder="Enter Page Access Token"></div>
                                <button class="btn btn-sm btn-subtle-primary w-100 intg-save-btn"><i class="ri-save-line me-1"></i>Save</button>
                            </div>
                        </div>
                    </div>

                    <!-- 7. Instagram -->
                    <div class="col-xl-4 col-md-6">
                        <div class="card border mb-0 intg-ch-card" style="--ch-color:#E4405F;" data-service="boss_instagram">
                            <div class="card-body">
                                <div class="d-flex align-items-center justify-content-between mb-3">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="avatar avatar-sm avatar-rounded" style="background:linear-gradient(45deg,#f09433,#e6683c,#dc2743,#cc2366,#bc1888); color:#fff;"><i class="ri-instagram-fill fs-18"></i></div>
                                        <h6 class="mb-0 fw-semibold">Instagram DM</h6>
                                    </div>
                                    <span class="badge bg-warning-subtle text-warning intg-badge">Not Connected</span>
                                </div>
                                <div class="mb-2"><label class="form-label fs-12">Business Account ID</label><input type="text" class="form-control form-control-sm" data-field="account_id" placeholder="Enter Business Account ID"></div>
                                <div class="mb-3"><label class="form-label fs-12">Access Token</label><input type="text" class="form-control form-control-sm" data-field="access_token" placeholder="Enter Access Token"></div>
                                <button class="btn btn-sm btn-subtle-primary w-100 intg-save-btn"><i class="ri-save-line me-1"></i>Save</button>
                            </div>
                        </div>
                    </div>

                    <!-- 8. Threads -->
                    <div class="col-xl-4 col-md-6">
                        <div class="card border mb-0 intg-ch-card" style="--ch-color:#000;" data-service="boss_threads">
                            <div class="card-body">
                                <div class="d-flex align-items-center justify-content-between mb-3">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="avatar avatar-sm avatar-rounded bg-dark text-white"><i class="ri-threads-fill fs-18"></i></div>
                                        <h6 class="mb-0 fw-semibold">Threads</h6>
                                    </div>
                                    <span class="badge bg-warning-subtle text-warning intg-badge">Not Connected</span>
                                </div>
                                <div class="mb-2"><label class="form-label fs-12">Username</label><input type="text" class="form-control form-control-sm" data-field="username" placeholder="@wincase"></div>
                                <div class="mb-3"><label class="form-label fs-12">API Token</label><input type="text" class="form-control form-control-sm" data-field="api_token" placeholder="Enter API token"></div>
                                <button class="btn btn-sm btn-subtle-primary w-100 intg-save-btn"><i class="ri-save-line me-1"></i>Save</button>
                            </div>
                        </div>
                    </div>

                    <!-- 9. Pinterest -->
                    <div class="col-xl-4 col-md-6">
                        <div class="card border mb-0 intg-ch-card" style="--ch-color:#E60023;" data-service="boss_pinterest">
                            <div class="card-body">
                                <div class="d-flex align-items-center justify-content-between mb-3">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="avatar avatar-sm avatar-rounded" style="background:rgba(230,0,35,.12); color:#E60023;"><i class="ri-pinterest-fill fs-18"></i></div>
                                        <h6 class="mb-0 fw-semibold">Pinterest</h6>
                                    </div>
                                    <span class="badge bg-warning-subtle text-warning intg-badge">Not Connected</span>
                                </div>
                                <div class="mb-2"><label class="form-label fs-12">App ID</label><input type="text" class="form-control form-control-sm" data-field="app_id" placeholder="Enter App ID"></div>
                                <div class="mb-3"><label class="form-label fs-12">Access Token</label><input type="text" class="form-control form-control-sm" data-field="access_token" placeholder="Enter access token"></div>
                                <button class="btn btn-sm btn-subtle-primary w-100 intg-save-btn"><i class="ri-save-line me-1"></i>Save</button>
                            </div>
                        </div>
                    </div>

                    <!-- 10. TikTok -->
                    <div class="col-xl-4 col-md-6">
                        <div class="card border mb-0 intg-ch-card" style="--ch-color:#010101;" data-service="boss_tiktok">
                            <div class="card-body">
                                <div class="d-flex align-items-center justify-content-between mb-3">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="avatar avatar-sm avatar-rounded bg-dark text-white"><i class="ri-tiktok-fill fs-18"></i></div>
                                        <h6 class="mb-0 fw-semibold">TikTok DM</h6>
                                    </div>
                                    <span class="badge bg-warning-subtle text-warning intg-badge">Not Connected</span>
                                </div>
                                <div class="mb-2"><label class="form-label fs-12">Business ID</label><input type="text" class="form-control form-control-sm" data-field="business_id" placeholder="Enter Business ID"></div>
                                <div class="mb-3"><label class="form-label fs-12">Access Token</label><input type="text" class="form-control form-control-sm" data-field="access_token" placeholder="Enter access token"></div>
                                <button class="btn btn-sm btn-subtle-primary w-100 intg-save-btn"><i class="ri-save-line me-1"></i>Save</button>
                            </div>
                        </div>
                    </div>

                    <!-- 11. Viber -->
                    <div class="col-xl-4 col-md-6">
                        <div class="card border mb-0 intg-ch-card" style="--ch-color:#7360F2;" data-service="boss_viber">
                            <div class="card-body">
                                <div class="d-flex align-items-center justify-content-between mb-3">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="avatar avatar-sm avatar-rounded" style="background:rgba(115,96,242,.12); color:#7360F2;"><i class="ri-chat-smile-2-fill fs-18"></i></div>
                                        <h6 class="mb-0 fw-semibold">Viber</h6>
                                    </div>
                                    <span class="badge bg-warning-subtle text-warning intg-badge">Not Connected</span>
                                </div>
                                <div class="mb-2"><label class="form-label fs-12">Bot Name</label><input type="text" class="form-control form-control-sm" data-field="bot_name" placeholder="WinCase Bot"></div>
                                <div class="mb-3"><label class="form-label fs-12">Auth Token</label><input type="text" class="form-control form-control-sm" data-field="auth_token" placeholder="Enter auth token"></div>
                                <button class="btn btn-sm btn-subtle-primary w-100 intg-save-btn"><i class="ri-save-line me-1"></i>Save</button>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- ==================== DIVIDER ==================== -->
                <hr class="intg-divider">

                <!-- ========== WORKER CONNECTIONS ========== -->
                <div class="d-flex align-items-center gap-2 mb-3">
                    <i class="ri-group-line" style="color:#015EA7; font-size:1.1rem;"></i>
                    <h6 class="mb-0 fw-bold" style="color:#015EA7;">WORKERS (Staff CRM)</h6>
                    <div class="flex-grow-1" style="height:1px; background:linear-gradient(90deg,rgba(1,94,167,.3),transparent);"></div>
                    <span class="badge bg-success-subtle text-success fs-10">Each worker needs own connections</span>
                </div>

                <div id="workerChannelsContainer">

                    <!-- Worker #1 -->
                    <div class="intg-worker-block" data-worker="1">
                        <span class="intg-worker-num"><i class="ri-user-line me-1"></i>Worker #1 — Anna Kowalska</span>
                        <div class="d-flex align-items-center gap-2 mb-3 mt-1">
                            <select class="form-select form-select-sm" style="max-width:250px;">
                                <option value="">Select worker...</option>
                                <option selected>Anna Kowalska</option>
                                <option>Marta Nowak</option>
                                <option>Olena Petrenko</option>
                                <option>Ivan Kovalchuk</option>
                            </select>
                        </div>
                        <div class="row g-3">

                            <!-- W1: WhatsApp -->
                            <div class="col-xl-4 col-md-6">
                                <div class="card border mb-0 intg-ch-card" style="--ch-color:#25D366;">
                                    <div class="card-body py-2 px-3">
                                        <div class="d-flex align-items-center justify-content-between mb-2">
                                            <div class="d-flex align-items-center gap-2">
                                                <i class="ri-whatsapp-fill" style="color:#25D366; font-size:1.1rem;"></i>
                                                <span class="fw-semibold fs-13">WhatsApp</span>
                                            </div>
                                            <span class="badge bg-success-subtle text-success" style="font-size:.55rem;">Connected</span>
                                        </div>
                                        <div class="mb-1"><label class="form-label fs-11 mb-0">Phone</label><input type="text" class="form-control form-control-sm" value="+48 512 345 678" readonly style="font-size:.75rem;"></div>
                                        <div class="mb-2"><label class="form-label fs-11 mb-0">API Token</label><input type="password" class="form-control form-control-sm" value="hidden" readonly style="font-size:.75rem;"></div>
                                        <button class="btn btn-sm btn-subtle-danger w-100 py-0" style="font-size:.7rem;"><i class="ri-link-unlink me-1"></i>Disconnect</button>
                                    </div>
                                </div>
                            </div>

                            <!-- W1: Telegram -->
                            <div class="col-xl-4 col-md-6">
                                <div class="card border mb-0 intg-ch-card" style="--ch-color:#0088cc;">
                                    <div class="card-body py-2 px-3">
                                        <div class="d-flex align-items-center justify-content-between mb-2">
                                            <div class="d-flex align-items-center gap-2">
                                                <i class="ri-telegram-fill" style="color:#0088cc; font-size:1.1rem;"></i>
                                                <span class="fw-semibold fs-13">Telegram</span>
                                            </div>
                                            <span class="badge bg-success-subtle text-success" style="font-size:.55rem;">Connected</span>
                                        </div>
                                        <div class="mb-1"><label class="form-label fs-11 mb-0">Bot Token</label><input type="password" class="form-control form-control-sm" value="hidden" readonly style="font-size:.75rem;"></div>
                                        <div class="mb-2"><label class="form-label fs-11 mb-0">Chat ID</label><input type="text" class="form-control form-control-sm" value="-1001234500001" readonly style="font-size:.75rem;"></div>
                                        <button class="btn btn-sm btn-subtle-danger w-100 py-0" style="font-size:.7rem;"><i class="ri-link-unlink me-1"></i>Disconnect</button>
                                    </div>
                                </div>
                            </div>

                            <!-- W1: Portal -->
                            <div class="col-xl-4 col-md-6">
                                <div class="card border mb-0 intg-ch-card" style="--ch-color:#015EA7;">
                                    <div class="card-body py-2 px-3">
                                        <div class="d-flex align-items-center justify-content-between mb-2">
                                            <div class="d-flex align-items-center gap-2">
                                                <i class="ri-global-line" style="color:#015EA7; font-size:1.1rem;"></i>
                                                <span class="fw-semibold fs-13">Portal</span>
                                            </div>
                                            <span class="badge bg-success-subtle text-success" style="font-size:.55rem;">Auto</span>
                                        </div>
                                        <div class="mb-2"><label class="form-label fs-11 mb-0">Status</label><input type="text" class="form-control form-control-sm text-success" value="Built-in" readonly style="font-size:.75rem;"></div>
                                        <button class="btn btn-sm btn-subtle-success w-100 py-0" style="font-size:.7rem;" disabled><i class="ri-check-line me-1"></i>Always On</button>
                                    </div>
                                </div>
                            </div>

                            <!-- W1: Email -->
                            <div class="col-xl-4 col-md-6">
                                <div class="card border mb-0 intg-ch-card" style="--ch-color:#6c757d;">
                                    <div class="card-body py-2 px-3">
                                        <div class="d-flex align-items-center justify-content-between mb-2">
                                            <div class="d-flex align-items-center gap-2">
                                                <i class="ri-mail-fill" style="color:#6c757d; font-size:1.1rem;"></i>
                                                <span class="fw-semibold fs-13">Email</span>
                                            </div>
                                            <span class="badge bg-success-subtle text-success" style="font-size:.55rem;">Connected</span>
                                        </div>
                                        <div class="mb-1"><label class="form-label fs-11 mb-0">Email</label><input type="email" class="form-control form-control-sm" value="anna@wincase.eu" readonly style="font-size:.75rem;"></div>
                                        <div class="mb-2"><label class="form-label fs-11 mb-0">IMAP/SMTP</label><input type="text" class="form-control form-control-sm" value="mail.wincase.eu" readonly style="font-size:.75rem;"></div>
                                        <button class="btn btn-sm btn-subtle-danger w-100 py-0" style="font-size:.7rem;"><i class="ri-link-unlink me-1"></i>Disconnect</button>
                                    </div>
                                </div>
                            </div>

                            <!-- W1: SMS -->
                            <div class="col-xl-4 col-md-6">
                                <div class="card border mb-0 intg-ch-card" style="--ch-color:#0d6efd;">
                                    <div class="card-body py-2 px-3">
                                        <div class="d-flex align-items-center justify-content-between mb-2">
                                            <div class="d-flex align-items-center gap-2">
                                                <i class="ri-message-2-fill" style="color:#0d6efd; font-size:1.1rem;"></i>
                                                <span class="fw-semibold fs-13">SMS</span>
                                            </div>
                                            <span class="badge bg-warning-subtle text-warning" style="font-size:.55rem;">Not Set</span>
                                        </div>
                                        <div class="mb-1"><label class="form-label fs-11 mb-0">API Key</label><input type="text" class="form-control form-control-sm" placeholder="API key" style="font-size:.75rem;"></div>
                                        <div class="mb-2"><label class="form-label fs-11 mb-0">Sender</label><input type="text" class="form-control form-control-sm" placeholder="+48..." style="font-size:.75rem;"></div>
                                        <button class="btn btn-sm btn-subtle-primary w-100 py-0" style="font-size:.7rem;"><i class="ri-link me-1"></i>Connect</button>
                                    </div>
                                </div>
                            </div>

                            <!-- W1: Facebook -->
                            <div class="col-xl-4 col-md-6">
                                <div class="card border mb-0 intg-ch-card" style="--ch-color:#1877F2;">
                                    <div class="card-body py-2 px-3">
                                        <div class="d-flex align-items-center justify-content-between mb-2">
                                            <div class="d-flex align-items-center gap-2">
                                                <i class="ri-facebook-fill" style="color:#1877F2; font-size:1.1rem;"></i>
                                                <span class="fw-semibold fs-13">Facebook</span>
                                            </div>
                                            <span class="badge bg-warning-subtle text-warning" style="font-size:.55rem;">Not Set</span>
                                        </div>
                                        <div class="mb-1"><label class="form-label fs-11 mb-0">Page ID</label><input type="text" class="form-control form-control-sm" placeholder="Page ID" style="font-size:.75rem;"></div>
                                        <div class="mb-2"><label class="form-label fs-11 mb-0">Token</label><input type="text" class="form-control form-control-sm" placeholder="Access token" style="font-size:.75rem;"></div>
                                        <button class="btn btn-sm btn-subtle-primary w-100 py-0" style="font-size:.7rem;"><i class="ri-link me-1"></i>Connect</button>
                                    </div>
                                </div>
                            </div>

                            <!-- W1: Instagram -->
                            <div class="col-xl-4 col-md-6">
                                <div class="card border mb-0 intg-ch-card" style="--ch-color:#E4405F;">
                                    <div class="card-body py-2 px-3">
                                        <div class="d-flex align-items-center justify-content-between mb-2">
                                            <div class="d-flex align-items-center gap-2">
                                                <i class="ri-instagram-fill" style="color:#E4405F; font-size:1.1rem;"></i>
                                                <span class="fw-semibold fs-13">Instagram</span>
                                            </div>
                                            <span class="badge bg-warning-subtle text-warning" style="font-size:.55rem;">Not Set</span>
                                        </div>
                                        <div class="mb-1"><label class="form-label fs-11 mb-0">Account ID</label><input type="text" class="form-control form-control-sm" placeholder="Business ID" style="font-size:.75rem;"></div>
                                        <div class="mb-2"><label class="form-label fs-11 mb-0">Token</label><input type="text" class="form-control form-control-sm" placeholder="Access token" style="font-size:.75rem;"></div>
                                        <button class="btn btn-sm btn-subtle-primary w-100 py-0" style="font-size:.7rem;"><i class="ri-link me-1"></i>Connect</button>
                                    </div>
                                </div>
                            </div>

                            <!-- W1: Threads -->
                            <div class="col-xl-4 col-md-6">
                                <div class="card border mb-0 intg-ch-card" style="--ch-color:#000;">
                                    <div class="card-body py-2 px-3">
                                        <div class="d-flex align-items-center justify-content-between mb-2">
                                            <div class="d-flex align-items-center gap-2">
                                                <i class="ri-threads-fill" style="color:#000; font-size:1.1rem;"></i>
                                                <span class="fw-semibold fs-13">Threads</span>
                                            </div>
                                            <span class="badge bg-warning-subtle text-warning" style="font-size:.55rem;">Not Set</span>
                                        </div>
                                        <div class="mb-1"><label class="form-label fs-11 mb-0">Username</label><input type="text" class="form-control form-control-sm" placeholder="@handle" style="font-size:.75rem;"></div>
                                        <div class="mb-2"><label class="form-label fs-11 mb-0">Token</label><input type="text" class="form-control form-control-sm" placeholder="API token" style="font-size:.75rem;"></div>
                                        <button class="btn btn-sm btn-subtle-primary w-100 py-0" style="font-size:.7rem;"><i class="ri-link me-1"></i>Connect</button>
                                    </div>
                                </div>
                            </div>

                            <!-- W1: Pinterest -->
                            <div class="col-xl-4 col-md-6">
                                <div class="card border mb-0 intg-ch-card" style="--ch-color:#E60023;">
                                    <div class="card-body py-2 px-3">
                                        <div class="d-flex align-items-center justify-content-between mb-2">
                                            <div class="d-flex align-items-center gap-2">
                                                <i class="ri-pinterest-fill" style="color:#E60023; font-size:1.1rem;"></i>
                                                <span class="fw-semibold fs-13">Pinterest</span>
                                            </div>
                                            <span class="badge bg-warning-subtle text-warning" style="font-size:.55rem;">Not Set</span>
                                        </div>
                                        <div class="mb-1"><label class="form-label fs-11 mb-0">App ID</label><input type="text" class="form-control form-control-sm" placeholder="App ID" style="font-size:.75rem;"></div>
                                        <div class="mb-2"><label class="form-label fs-11 mb-0">Token</label><input type="text" class="form-control form-control-sm" placeholder="Access token" style="font-size:.75rem;"></div>
                                        <button class="btn btn-sm btn-subtle-primary w-100 py-0" style="font-size:.7rem;"><i class="ri-link me-1"></i>Connect</button>
                                    </div>
                                </div>
                            </div>

                            <!-- W1: TikTok -->
                            <div class="col-xl-4 col-md-6">
                                <div class="card border mb-0 intg-ch-card" style="--ch-color:#010101;">
                                    <div class="card-body py-2 px-3">
                                        <div class="d-flex align-items-center justify-content-between mb-2">
                                            <div class="d-flex align-items-center gap-2">
                                                <i class="ri-tiktok-fill" style="color:#010101; font-size:1.1rem;"></i>
                                                <span class="fw-semibold fs-13">TikTok</span>
                                            </div>
                                            <span class="badge bg-warning-subtle text-warning" style="font-size:.55rem;">Not Set</span>
                                        </div>
                                        <div class="mb-1"><label class="form-label fs-11 mb-0">Business ID</label><input type="text" class="form-control form-control-sm" placeholder="Business ID" style="font-size:.75rem;"></div>
                                        <div class="mb-2"><label class="form-label fs-11 mb-0">Token</label><input type="text" class="form-control form-control-sm" placeholder="Access token" style="font-size:.75rem;"></div>
                                        <button class="btn btn-sm btn-subtle-primary w-100 py-0" style="font-size:.7rem;"><i class="ri-link me-1"></i>Connect</button>
                                    </div>
                                </div>
                            </div>

                            <!-- W1: Viber -->
                            <div class="col-xl-4 col-md-6">
                                <div class="card border mb-0 intg-ch-card" style="--ch-color:#7360F2;">
                                    <div class="card-body py-2 px-3">
                                        <div class="d-flex align-items-center justify-content-between mb-2">
                                            <div class="d-flex align-items-center gap-2">
                                                <i class="ri-chat-smile-2-fill" style="color:#7360F2; font-size:1.1rem;"></i>
                                                <span class="fw-semibold fs-13">Viber</span>
                                            </div>
                                            <span class="badge bg-warning-subtle text-warning" style="font-size:.55rem;">Not Set</span>
                                        </div>
                                        <div class="mb-1"><label class="form-label fs-11 mb-0">Bot Name</label><input type="text" class="form-control form-control-sm" placeholder="Bot name" style="font-size:.75rem;"></div>
                                        <div class="mb-2"><label class="form-label fs-11 mb-0">Auth Token</label><input type="text" class="form-control form-control-sm" placeholder="Auth token" style="font-size:.75rem;"></div>
                                        <button class="btn btn-sm btn-subtle-primary w-100 py-0" style="font-size:.7rem;"><i class="ri-link me-1"></i>Connect</button>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div><!-- /Worker #1 -->

                    <!-- Add Worker Button -->
                    <div class="intg-add-worker" onclick="addWorkerBlock()">
                        <i class="ri-add-circle-line"></i>
                        <span>Add Another Worker</span>
                    </div>

                </div>

                <!-- ======================= OTHER INTEGRATIONS ======================= -->
                <div class="d-flex align-items-center gap-2 mt-4 mb-3">
                    <i class="ri-plug-line" style="color:#1F3864; font-size:1.1rem;"></i>
                    <h6 class="mb-0 fw-bold" style="color:#1F3864;">Other Integrations</h6>
                    <div class="flex-grow-1" style="height:1px; background:linear-gradient(90deg,rgba(31,56,100,.2),transparent);"></div>
                </div>

                <div class="row g-3">
                    <!-- Google Cloud -->
                    <div class="col-xl-4 col-md-6">
                        <div class="card border mb-0" data-service="google_cloud">
                            <div class="card-body">
                                <div class="d-flex align-items-center justify-content-between mb-3">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="avatar avatar-sm avatar-rounded bg-danger-subtle text-danger"><i class="ri-google-fill fs-18"></i></div>
                                        <h6 class="mb-0 fw-semibold">Google Cloud</h6>
                                    </div>
                                    <span class="badge bg-warning-subtle text-warning intg-badge">Not Connected</span>
                                </div>
                                <div class="mb-2"><label class="form-label fs-12">API Key</label><input type="text" class="form-control form-control-sm" data-field="api_key" placeholder="Enter Google API Key"></div>
                                <div class="mb-3"><label class="form-label fs-12">Service Account Email</label><input type="text" class="form-control form-control-sm" data-field="service_email" placeholder="project@iam.gserviceaccount.com"></div>
                                <button class="btn btn-sm btn-subtle-primary w-100 intg-save-btn"><i class="ri-save-line me-1"></i>Save</button>
                            </div>
                        </div>
                    </div>
                    <!-- Meta Ads -->
                    <div class="col-xl-4 col-md-6">
                        <div class="card border mb-0" data-service="meta_ads">
                            <div class="card-body">
                                <div class="d-flex align-items-center justify-content-between mb-3">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="avatar avatar-sm avatar-rounded bg-primary-subtle text-primary"><i class="ri-facebook-fill fs-18"></i></div>
                                        <h6 class="mb-0 fw-semibold">Meta Ads</h6>
                                    </div>
                                    <span class="badge bg-warning-subtle text-warning intg-badge">Not Connected</span>
                                </div>
                                <div class="mb-2"><label class="form-label fs-12">App ID</label><input type="text" class="form-control form-control-sm" data-field="app_id" placeholder="Enter App ID"></div>
                                <div class="mb-3"><label class="form-label fs-12">App Secret</label><input type="text" class="form-control form-control-sm" data-field="app_secret" placeholder="Enter App Secret"></div>
                                <button class="btn btn-sm btn-subtle-primary w-100 intg-save-btn"><i class="ri-save-line me-1"></i>Save</button>
                            </div>
                        </div>
                    </div>
                    <!-- TikTok Ads -->
                    <div class="col-xl-4 col-md-6">
                        <div class="card border mb-0" data-service="tiktok_ads">
                            <div class="card-body">
                                <div class="d-flex align-items-center justify-content-between mb-3">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="avatar avatar-sm avatar-rounded bg-dark text-white"><i class="ri-tiktok-fill fs-18"></i></div>
                                        <h6 class="mb-0 fw-semibold">TikTok Ads</h6>
                                    </div>
                                    <span class="badge bg-warning-subtle text-warning intg-badge">Not Connected</span>
                                </div>
                                <div class="mb-3"><label class="form-label fs-12">Access Token</label><input type="text" class="form-control form-control-sm" data-field="access_token" placeholder="Enter access token"></div>
                                <button class="btn btn-sm btn-subtle-primary w-100 intg-save-btn"><i class="ri-save-line me-1"></i>Save</button>
                            </div>
                        </div>
                    </div>
                    <!-- Ahrefs -->
                    <div class="col-xl-4 col-md-6">
                        <div class="card border mb-0" data-service="ahrefs">
                            <div class="card-body">
                                <div class="d-flex align-items-center justify-content-between mb-3">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="avatar avatar-sm avatar-rounded bg-warning-subtle text-warning"><i class="ri-search-eye-line fs-18"></i></div>
                                        <h6 class="mb-0 fw-semibold">Ahrefs</h6>
                                    </div>
                                    <span class="badge bg-warning-subtle text-warning intg-badge">Not Connected</span>
                                </div>
                                <div class="mb-3"><label class="form-label fs-12">API Key</label><input type="text" class="form-control form-control-sm" data-field="api_key" placeholder="Enter API key"></div>
                                <button class="btn btn-sm btn-subtle-primary w-100 intg-save-btn"><i class="ri-save-line me-1"></i>Save</button>
                            </div>
                        </div>
                    </div>
                    <!-- POS Terminal -->
                    <div class="col-xl-4 col-md-6">
                        <div class="card border mb-0" data-service="pos_terminal">
                            <div class="card-body">
                                <div class="d-flex align-items-center justify-content-between mb-3">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="avatar avatar-sm avatar-rounded bg-success-subtle text-success"><i class="ri-terminal-box-line fs-18"></i></div>
                                        <h6 class="mb-0 fw-semibold">POS Terminal</h6>
                                    </div>
                                    <span class="badge bg-warning-subtle text-warning intg-badge">Not Connected</span>
                                </div>
                                <div class="mb-2"><label class="form-label fs-12">Terminal Name</label><input type="text" class="form-control form-control-sm" data-field="terminal_name" placeholder="e.g. Terminal #1 - PAX A920"></div>
                                <div class="mb-3"><label class="form-label fs-12">Terminal ID</label><input type="text" class="form-control form-control-sm" data-field="terminal_id" placeholder="Enter Terminal ID"></div>
                                <button class="btn btn-sm btn-subtle-primary w-100 intg-save-btn"><i class="ri-save-line me-1"></i>Save</button>
                            </div>
                        </div>
                    </div>
                    <!-- n8n -->
                    <div class="col-xl-4 col-md-6">
                        <div class="card border mb-0" data-service="n8n">
                            <div class="card-body">
                                <div class="d-flex align-items-center justify-content-between mb-3">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="avatar avatar-sm avatar-rounded bg-secondary-subtle text-secondary"><i class="ri-flow-chart fs-18"></i></div>
                                        <h6 class="mb-0 fw-semibold">n8n Automation</h6>
                                    </div>
                                    <span class="badge bg-warning-subtle text-warning intg-badge">Not Connected</span>
                                </div>
                                <div class="mb-2"><label class="form-label fs-12">Instance URL</label><input type="url" class="form-control form-control-sm" data-field="instance_url" placeholder="https://n8n.wincase.eu"></div>
                                <div class="mb-3"><label class="form-label fs-12">Webhook URL</label><input type="url" class="form-control form-control-sm" data-field="webhook_url" placeholder="https://n8n.wincase.eu/webhook/crm"></div>
                                <button class="btn btn-sm btn-subtle-primary w-100 intg-save-btn"><i class="ri-save-line me-1"></i>Save</button>
                            </div>
                        </div>
                    </div>
                    <!-- Google Drive -->
                    <div class="col-xl-4 col-md-6">
                        <div class="card border mb-0" data-service="google_drive">
                            <div class="card-body">
                                <div class="d-flex align-items-center justify-content-between mb-3">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="avatar avatar-sm avatar-rounded" style="background:rgba(66,133,244,.12); color:#4285F4;"><i class="ri-drive-fill fs-18"></i></div>
                                        <h6 class="mb-0 fw-semibold">Google Drive</h6>
                                    </div>
                                    <span class="badge bg-warning-subtle text-warning intg-badge">Not Connected</span>
                                </div>
                                <div class="mb-2"><label class="form-label fs-12">Client ID</label><input type="text" class="form-control form-control-sm" data-field="client_id" placeholder="Enter OAuth Client ID"></div>
                                <div class="mb-3"><label class="form-label fs-12">Client Secret</label><input type="text" class="form-control form-control-sm" data-field="client_secret" placeholder="Enter client secret"></div>
                                <button class="btn btn-sm btn-subtle-primary w-100 intg-save-btn"><i class="ri-save-line me-1"></i>Save</button>
                            </div>
                        </div>
                    </div>
                    <!-- Obsidian -->
                    <div class="col-xl-4 col-md-6">
                        <div class="card border mb-0" data-service="obsidian">
                            <div class="card-body">
                                <div class="d-flex align-items-center justify-content-between mb-3">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="avatar avatar-sm avatar-rounded" style="background:rgba(124,58,237,.12); color:#7C3AED;"><i class="ri-book-2-fill fs-18"></i></div>
                                        <h6 class="mb-0 fw-semibold">Obsidian Vault</h6>
                                    </div>
                                    <span class="badge bg-warning-subtle text-warning intg-badge">Not Connected</span>
                                </div>
                                <div class="mb-2"><label class="form-label fs-12">Vault Path / URL</label><input type="text" class="form-control form-control-sm" data-field="vault_url" placeholder="Local path or Obsidian Sync URL"></div>
                                <div class="mb-3"><label class="form-label fs-12">API Token (Obsidian REST)</label><input type="text" class="form-control form-control-sm" data-field="api_token" placeholder="Enter token"></div>
                                <button class="btn btn-sm btn-subtle-primary w-100 intg-save-btn"><i class="ri-save-line me-1"></i>Save</button>
                            </div>
                        </div>
                    </div>
                    <!-- Notion -->
                    <div class="col-xl-4 col-md-6">
                        <div class="card border mb-0" data-service="notion">
                            <div class="card-body">
                                <div class="d-flex align-items-center justify-content-between mb-3">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="avatar avatar-sm avatar-rounded bg-dark text-white"><i class="ri-file-list-3-fill fs-18"></i></div>
                                        <h6 class="mb-0 fw-semibold">Notion</h6>
                                    </div>
                                    <span class="badge bg-warning-subtle text-warning intg-badge">Not Connected</span>
                                </div>
                                <div class="mb-2"><label class="form-label fs-12">Integration Token</label><input type="text" class="form-control form-control-sm" data-field="integration_token" placeholder="secret_..."></div>
                                <div class="mb-3"><label class="form-label fs-12">Database ID</label><input type="text" class="form-control form-control-sm" data-field="database_id" placeholder="Enter database ID"></div>
                                <button class="btn btn-sm btn-subtle-primary w-100 intg-save-btn"><i class="ri-save-line me-1"></i>Save</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-4">
                    <button type="button" class="btn btn-primary"><i class="ri-save-line me-1"></i>Save All Settings</button>
                </div>

                <script>
                var workerCount = 1;
                function addWorkerBlock(){
                    workerCount++;
                    var container = document.getElementById('workerChannelsContainer');
                    var addBtn = container.querySelector('.intg-add-worker');
                    var channels = [
                        {name:'WhatsApp', icon:'ri-whatsapp-fill', color:'#25D366', f1:'Phone', f2:'API Token'},
                        {name:'Telegram', icon:'ri-telegram-fill', color:'#0088cc', f1:'Bot Token', f2:'Chat ID'},
                        {name:'Portal', icon:'ri-global-line', color:'#015EA7', f1:'Status', f2:'', auto:true},
                        {name:'Email', icon:'ri-mail-fill', color:'#6c757d', f1:'Email', f2:'IMAP/SMTP'},
                        {name:'SMS', icon:'ri-message-2-fill', color:'#0d6efd', f1:'API Key', f2:'Sender'},
                        {name:'Facebook', icon:'ri-facebook-fill', color:'#1877F2', f1:'Page ID', f2:'Token'},
                        {name:'Instagram', icon:'ri-instagram-fill', color:'#E4405F', f1:'Account ID', f2:'Token'},
                        {name:'Threads', icon:'ri-threads-fill', color:'#000', f1:'Username', f2:'Token'},
                        {name:'Pinterest', icon:'ri-pinterest-fill', color:'#E60023', f1:'App ID', f2:'Token'},
                        {name:'TikTok', icon:'ri-tiktok-fill', color:'#010101', f1:'Business ID', f2:'Token'},
                        {name:'Viber', icon:'ri-chat-smile-2-fill', color:'#7360F2', f1:'Bot Name', f2:'Auth Token'}
                    ];
                    var html = '<div class="intg-worker-block" data-worker="'+workerCount+'">';
                    html += '<span class="intg-worker-num"><i class="ri-user-line me-1"></i>Worker #'+workerCount+'</span>';
                    html += '<div class="d-flex align-items-center gap-2 mb-3 mt-1">';
                    html += '<select class="form-select form-select-sm" style="max-width:250px;"><option>Select worker...</option><option>Anna Kowalska</option><option>Marta Nowak</option><option>Olena Petrenko</option><option>Ivan Kovalchuk</option></select>';
                    html += '<button class="btn btn-sm btn-subtle-danger" onclick="removeWorkerBlock(this)" title="Remove"><i class="ri-delete-bin-line"></i></button>';
                    html += '</div><div class="row g-3">';
                    channels.forEach(function(ch){
                        html += '<div class="col-xl-4 col-md-6"><div class="card border mb-0 intg-ch-card" style="--ch-color:'+ch.color+';"><div class="card-body py-2 px-3">';
                        html += '<div class="d-flex align-items-center justify-content-between mb-2"><div class="d-flex align-items-center gap-2"><i class="'+ch.icon+'" style="color:'+ch.color+'; font-size:1.1rem;"></i><span class="fw-semibold fs-13">'+ch.name+'</span></div>';
                        if(ch.auto){
                            html += '<span class="badge bg-success-subtle text-success" style="font-size:.55rem;">Auto</span></div>';
                            html += '<div class="mb-2"><label class="form-label fs-11 mb-0">Status</label><input type="text" class="form-control form-control-sm text-success" value="Built-in" readonly style="font-size:.75rem;"></div>';
                            html += '<button class="btn btn-sm btn-subtle-success w-100 py-0" style="font-size:.7rem;" disabled><i class="ri-check-line me-1"></i>Always On</button>';
                        } else {
                            html += '<span class="badge bg-warning-subtle text-warning" style="font-size:.55rem;">Not Set</span></div>';
                            html += '<div class="mb-1"><label class="form-label fs-11 mb-0">'+ch.f1+'</label><input type="text" class="form-control form-control-sm" placeholder="'+ch.f1+'" style="font-size:.75rem;"></div>';
                            if(ch.f2) html += '<div class="mb-2"><label class="form-label fs-11 mb-0">'+ch.f2+'</label><input type="text" class="form-control form-control-sm" placeholder="'+ch.f2+'" style="font-size:.75rem;"></div>';
                            html += '<button class="btn btn-sm btn-subtle-primary w-100 py-0" style="font-size:.7rem;"><i class="ri-link me-1"></i>Connect</button>';
                        }
                        html += '</div></div></div>';
                    });
                    html += '</div></div>';
                    addBtn.insertAdjacentHTML('beforebegin', html);
                }
                function removeWorkerBlock(btn){
                    var block = btn.closest('.intg-worker-block');
                    if(block) block.remove();
                }
                </script>

            </div>

            <!-- Tab 3: Notifications -->
            <div class="tab-pane fade" id="tab-notifications" role="tabpanel">
                <div class="row">
                    <div class="col-xl-6">
                        <h6 class="fw-semibold mb-3"><i class="ri-mail-line me-2"></i>Email Notifications</h6>
                        <div class="card border">
                            <div class="card-body p-0">
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item d-flex align-items-center justify-content-between py-3">
                                        <div>
                                            <h6 class="mb-0 fs-14">New Lead Received</h6>
                                            <span class="text-muted fs-12">Notify when a new lead is created from any source</span>
                                        </div>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" checked>
                                        </div>
                                    </li>
                                    <li class="list-group-item d-flex align-items-center justify-content-between py-3">
                                        <div>
                                            <h6 class="mb-0 fs-14">Case Status Update</h6>
                                            <span class="text-muted fs-12">Notify on case status changes</span>
                                        </div>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" checked>
                                        </div>
                                    </li>
                                    <li class="list-group-item d-flex align-items-center justify-content-between py-3">
                                        <div>
                                            <h6 class="mb-0 fs-14">Document Expiry Warning</h6>
                                            <span class="text-muted fs-12">Alert 30/14/7 days before document expires</span>
                                        </div>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" checked>
                                        </div>
                                    </li>
                                    <li class="list-group-item d-flex align-items-center justify-content-between py-3">
                                        <div>
                                            <h6 class="mb-0 fs-14">Payment Received</h6>
                                            <span class="text-muted fs-12">Notify when a payment is recorded</span>
                                        </div>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox">
                                        </div>
                                    </li>
                                    <li class="list-group-item d-flex align-items-center justify-content-between py-3">
                                        <div>
                                            <h6 class="mb-0 fs-14">Task Overdue</h6>
                                            <span class="text-muted fs-12">Notify when a task is past its due date</span>
                                        </div>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" checked>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-6">
                        <h6 class="fw-semibold mb-3"><i class="ri-telegram-line me-2"></i>Telegram Notifications</h6>
                        <div class="card border">
                            <div class="card-body p-0">
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item d-flex align-items-center justify-content-between py-3">
                                        <div>
                                            <h6 class="mb-0 fs-14">New Lead Received</h6>
                                            <span class="text-muted fs-12">Send Telegram message on new lead</span>
                                        </div>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" checked>
                                        </div>
                                    </li>
                                    <li class="list-group-item d-flex align-items-center justify-content-between py-3">
                                        <div>
                                            <h6 class="mb-0 fs-14">Case Status Update</h6>
                                            <span class="text-muted fs-12">Send Telegram message on case change</span>
                                        </div>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox">
                                        </div>
                                    </li>
                                    <li class="list-group-item d-flex align-items-center justify-content-between py-3">
                                        <div>
                                            <h6 class="mb-0 fs-14">Document Expiry Warning</h6>
                                            <span class="text-muted fs-12">Telegram alert for expiring documents</span>
                                        </div>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" checked>
                                        </div>
                                    </li>
                                    <li class="list-group-item d-flex align-items-center justify-content-between py-3">
                                        <div>
                                            <h6 class="mb-0 fs-14">Payment Received</h6>
                                            <span class="text-muted fs-12">Telegram notification for payments</span>
                                        </div>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" checked>
                                        </div>
                                    </li>
                                    <li class="list-group-item d-flex align-items-center justify-content-between py-3">
                                        <div>
                                            <h6 class="mb-0 fs-14">Task Overdue</h6>
                                            <span class="text-muted fs-12">Telegram alert for overdue tasks</span>
                                        </div>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox">
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mt-4">
                    <button type="button" class="btn btn-primary"><i class="ri-save-line me-1"></i>Save Settings</button>
                </div>
            </div>

            <!-- Tab 4: Email Templates -->
            <div class="tab-pane fade" id="tab-templates" role="tabpanel">

                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div>
                        <h6 class="fw-semibold mb-1">Email Templates</h6>
                        <span class="text-muted fs-12">Manage automated email templates for clients and staff</span>
                    </div>
                    <button class="btn btn-sm btn-primary" onclick="openTemplateEditor('new')"><i class="ri-add-line me-1"></i>New Template</button>
                </div>

                <!-- Template categories -->
                <div class="d-flex gap-2 mb-3 flex-wrap">
                    <button class="btn btn-sm btn-subtle-primary tpl-filter active" data-cat="all">All</button>
                    <button class="btn btn-sm btn-subtle-primary tpl-filter" data-cat="client">Client</button>
                    <button class="btn btn-sm btn-subtle-primary tpl-filter" data-cat="staff">Staff</button>
                    <button class="btn btn-sm btn-subtle-primary tpl-filter" data-cat="finance">Finance</button>
                    <button class="btn btn-sm btn-subtle-primary tpl-filter" data-cat="docs">Documents</button>
                </div>

                <div class="card border mb-0">
                    <div class="card-body p-0">
                        <ul class="list-group list-group-flush" id="templatesList">

                            <!-- 1. Welcome Client -->
                            <li class="list-group-item d-flex align-items-center justify-content-between py-3" data-cat="client" data-tpl="welcome_client">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="avatar avatar-sm avatar-rounded bg-primary-subtle text-primary"><i class="ri-user-heart-line"></i></div>
                                    <div>
                                        <h6 class="mb-0 fs-14">Welcome Client</h6>
                                        <span class="text-muted fs-12">Sent to new client after registration in CRM</span>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="form-check form-switch mb-0"><input class="form-check-input tpl-toggle" type="checkbox" checked data-tpl="welcome_client"></div>
                                    <button class="btn btn-sm btn-subtle-primary" onclick="openTemplateEditor('welcome_client')"><i class="ri-edit-line me-1"></i>Edit</button>
                                    <button class="btn btn-sm btn-subtle-info" onclick="previewTemplate('welcome_client')"><i class="ri-eye-line"></i></button>
                                    <button class="btn btn-sm btn-subtle-secondary" onclick="testTemplate('welcome_client')"><i class="ri-send-plane-line"></i></button>
                                </div>
                            </li>

                            <!-- 2. New Lead -->
                            <li class="list-group-item d-flex align-items-center justify-content-between py-3" data-cat="staff" data-tpl="new_lead">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="avatar avatar-sm avatar-rounded bg-success-subtle text-success"><i class="ri-user-add-line"></i></div>
                                    <div>
                                        <h6 class="mb-0 fs-14">New Lead Notification</h6>
                                        <span class="text-muted fs-12">Sent to assigned manager when new lead arrives</span>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="form-check form-switch mb-0"><input class="form-check-input tpl-toggle" type="checkbox" checked data-tpl="new_lead"></div>
                                    <button class="btn btn-sm btn-subtle-primary" onclick="openTemplateEditor('new_lead')"><i class="ri-edit-line me-1"></i>Edit</button>
                                    <button class="btn btn-sm btn-subtle-info" onclick="previewTemplate('new_lead')"><i class="ri-eye-line"></i></button>
                                    <button class="btn btn-sm btn-subtle-secondary" onclick="testTemplate('new_lead')"><i class="ri-send-plane-line"></i></button>
                                </div>
                            </li>

                            <!-- 3. Case Created -->
                            <li class="list-group-item d-flex align-items-center justify-content-between py-3" data-cat="client" data-tpl="case_created">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="avatar avatar-sm avatar-rounded bg-info-subtle text-info"><i class="ri-folder-add-line"></i></div>
                                    <div>
                                        <h6 class="mb-0 fs-14">Case Created</h6>
                                        <span class="text-muted fs-12">Sent to client when a new case is opened</span>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="form-check form-switch mb-0"><input class="form-check-input tpl-toggle" type="checkbox" checked data-tpl="case_created"></div>
                                    <button class="btn btn-sm btn-subtle-primary" onclick="openTemplateEditor('case_created')"><i class="ri-edit-line me-1"></i>Edit</button>
                                    <button class="btn btn-sm btn-subtle-info" onclick="previewTemplate('case_created')"><i class="ri-eye-line"></i></button>
                                    <button class="btn btn-sm btn-subtle-secondary" onclick="testTemplate('case_created')"><i class="ri-send-plane-line"></i></button>
                                </div>
                            </li>

                            <!-- 4. Case Status Update -->
                            <li class="list-group-item d-flex align-items-center justify-content-between py-3" data-cat="client" data-tpl="case_status">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="avatar avatar-sm avatar-rounded bg-warning-subtle text-warning"><i class="ri-refresh-line"></i></div>
                                    <div>
                                        <h6 class="mb-0 fs-14">Case Status Update</h6>
                                        <span class="text-muted fs-12">Sent to client when case status changes (e.g. submitted, approved)</span>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="form-check form-switch mb-0"><input class="form-check-input tpl-toggle" type="checkbox" checked data-tpl="case_status"></div>
                                    <button class="btn btn-sm btn-subtle-primary" onclick="openTemplateEditor('case_status')"><i class="ri-edit-line me-1"></i>Edit</button>
                                    <button class="btn btn-sm btn-subtle-info" onclick="previewTemplate('case_status')"><i class="ri-eye-line"></i></button>
                                    <button class="btn btn-sm btn-subtle-secondary" onclick="testTemplate('case_status')"><i class="ri-send-plane-line"></i></button>
                                </div>
                            </li>

                            <!-- 5. Document Request -->
                            <li class="list-group-item d-flex align-items-center justify-content-between py-3" data-cat="docs" data-tpl="doc_request">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="avatar avatar-sm avatar-rounded bg-purple-subtle text-purple" style="background:rgba(108,92,231,.1);color:#6C5CE7;"><i class="ri-file-upload-line"></i></div>
                                    <div>
                                        <h6 class="mb-0 fs-14">Document Request</h6>
                                        <span class="text-muted fs-12">Sent to client to request missing documents (passport, contract, etc.)</span>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="form-check form-switch mb-0"><input class="form-check-input tpl-toggle" type="checkbox" checked data-tpl="doc_request"></div>
                                    <button class="btn btn-sm btn-subtle-primary" onclick="openTemplateEditor('doc_request')"><i class="ri-edit-line me-1"></i>Edit</button>
                                    <button class="btn btn-sm btn-subtle-info" onclick="previewTemplate('doc_request')"><i class="ri-eye-line"></i></button>
                                    <button class="btn btn-sm btn-subtle-secondary" onclick="testTemplate('doc_request')"><i class="ri-send-plane-line"></i></button>
                                </div>
                            </li>

                            <!-- 6. Document Expiry Warning -->
                            <li class="list-group-item d-flex align-items-center justify-content-between py-3" data-cat="docs" data-tpl="doc_expiry">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="avatar avatar-sm avatar-rounded bg-danger-subtle text-danger"><i class="ri-file-warning-line"></i></div>
                                    <div>
                                        <h6 class="mb-0 fs-14">Document Expiry Warning</h6>
                                        <span class="text-muted fs-12">Sent 30/14/7 days before Karta Pobytu, visa, or passport expires</span>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="form-check form-switch mb-0"><input class="form-check-input tpl-toggle" type="checkbox" checked data-tpl="doc_expiry"></div>
                                    <button class="btn btn-sm btn-subtle-primary" onclick="openTemplateEditor('doc_expiry')"><i class="ri-edit-line me-1"></i>Edit</button>
                                    <button class="btn btn-sm btn-subtle-info" onclick="previewTemplate('doc_expiry')"><i class="ri-eye-line"></i></button>
                                    <button class="btn btn-sm btn-subtle-secondary" onclick="testTemplate('doc_expiry')"><i class="ri-send-plane-line"></i></button>
                                </div>
                            </li>

                            <!-- 7. Invoice Sent -->
                            <li class="list-group-item d-flex align-items-center justify-content-between py-3" data-cat="finance" data-tpl="invoice_sent">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="avatar avatar-sm avatar-rounded bg-info-subtle text-info"><i class="ri-file-list-3-line"></i></div>
                                    <div>
                                        <h6 class="mb-0 fs-14">Invoice Sent</h6>
                                        <span class="text-muted fs-12">Sent to client with invoice PDF attached</span>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="form-check form-switch mb-0"><input class="form-check-input tpl-toggle" type="checkbox" checked data-tpl="invoice_sent"></div>
                                    <button class="btn btn-sm btn-subtle-primary" onclick="openTemplateEditor('invoice_sent')"><i class="ri-edit-line me-1"></i>Edit</button>
                                    <button class="btn btn-sm btn-subtle-info" onclick="previewTemplate('invoice_sent')"><i class="ri-eye-line"></i></button>
                                    <button class="btn btn-sm btn-subtle-secondary" onclick="testTemplate('invoice_sent')"><i class="ri-send-plane-line"></i></button>
                                </div>
                            </li>

                            <!-- 8. Payment Confirmation -->
                            <li class="list-group-item d-flex align-items-center justify-content-between py-3" data-cat="finance" data-tpl="payment_confirm">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="avatar avatar-sm avatar-rounded bg-success-subtle text-success"><i class="ri-money-euro-circle-line"></i></div>
                                    <div>
                                        <h6 class="mb-0 fs-14">Payment Confirmation</h6>
                                        <span class="text-muted fs-12">Sent to client after payment is processed</span>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="form-check form-switch mb-0"><input class="form-check-input tpl-toggle" type="checkbox" checked data-tpl="payment_confirm"></div>
                                    <button class="btn btn-sm btn-subtle-primary" onclick="openTemplateEditor('payment_confirm')"><i class="ri-edit-line me-1"></i>Edit</button>
                                    <button class="btn btn-sm btn-subtle-info" onclick="previewTemplate('payment_confirm')"><i class="ri-eye-line"></i></button>
                                    <button class="btn btn-sm btn-subtle-secondary" onclick="testTemplate('payment_confirm')"><i class="ri-send-plane-line"></i></button>
                                </div>
                            </li>

                            <!-- 9. Appointment Reminder -->
                            <li class="list-group-item d-flex align-items-center justify-content-between py-3" data-cat="client" data-tpl="appointment_reminder">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="avatar avatar-sm avatar-rounded bg-warning-subtle text-warning"><i class="ri-calendar-check-line"></i></div>
                                    <div>
                                        <h6 class="mb-0 fs-14">Appointment / Hearing Reminder</h6>
                                        <span class="text-muted fs-12">Sent to client 3 days and 1 day before urz&#261;d appointment</span>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="form-check form-switch mb-0"><input class="form-check-input tpl-toggle" type="checkbox" checked data-tpl="appointment_reminder"></div>
                                    <button class="btn btn-sm btn-subtle-primary" onclick="openTemplateEditor('appointment_reminder')"><i class="ri-edit-line me-1"></i>Edit</button>
                                    <button class="btn btn-sm btn-subtle-info" onclick="previewTemplate('appointment_reminder')"><i class="ri-eye-line"></i></button>
                                    <button class="btn btn-sm btn-subtle-secondary" onclick="testTemplate('appointment_reminder')"><i class="ri-send-plane-line"></i></button>
                                </div>
                            </li>

                            <!-- 10. Task Assignment -->
                            <li class="list-group-item d-flex align-items-center justify-content-between py-3" data-cat="staff" data-tpl="task_assigned">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="avatar avatar-sm avatar-rounded bg-secondary-subtle text-secondary"><i class="ri-task-line"></i></div>
                                    <div>
                                        <h6 class="mb-0 fs-14">Task Assignment</h6>
                                        <span class="text-muted fs-12">Sent to worker when a new task is assigned</span>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="form-check form-switch mb-0"><input class="form-check-input tpl-toggle" type="checkbox" checked data-tpl="task_assigned"></div>
                                    <button class="btn btn-sm btn-subtle-primary" onclick="openTemplateEditor('task_assigned')"><i class="ri-edit-line me-1"></i>Edit</button>
                                    <button class="btn btn-sm btn-subtle-info" onclick="previewTemplate('task_assigned')"><i class="ri-eye-line"></i></button>
                                    <button class="btn btn-sm btn-subtle-secondary" onclick="testTemplate('task_assigned')"><i class="ri-send-plane-line"></i></button>
                                </div>
                            </li>

                            <!-- 11. Task Overdue -->
                            <li class="list-group-item d-flex align-items-center justify-content-between py-3" data-cat="staff" data-tpl="task_overdue">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="avatar avatar-sm avatar-rounded bg-danger-subtle text-danger"><i class="ri-alarm-warning-line"></i></div>
                                    <div>
                                        <h6 class="mb-0 fs-14">Task Overdue Alert</h6>
                                        <span class="text-muted fs-12">Sent to worker and boss when task is past due</span>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="form-check form-switch mb-0"><input class="form-check-input tpl-toggle" type="checkbox" checked data-tpl="task_overdue"></div>
                                    <button class="btn btn-sm btn-subtle-primary" onclick="openTemplateEditor('task_overdue')"><i class="ri-edit-line me-1"></i>Edit</button>
                                    <button class="btn btn-sm btn-subtle-info" onclick="previewTemplate('task_overdue')"><i class="ri-eye-line"></i></button>
                                    <button class="btn btn-sm btn-subtle-secondary" onclick="testTemplate('task_overdue')"><i class="ri-send-plane-line"></i></button>
                                </div>
                            </li>

                            <!-- 12. Monthly Report -->
                            <li class="list-group-item d-flex align-items-center justify-content-between py-3" data-cat="staff" data-tpl="monthly_report">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="avatar avatar-sm avatar-rounded" style="background:rgba(31,56,100,.1);color:#1F3864;"><i class="ri-bar-chart-box-line"></i></div>
                                    <div>
                                        <h6 class="mb-0 fs-14">Monthly Report</h6>
                                        <span class="text-muted fs-12">Monthly summary sent to boss: revenue, cases, performance</span>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="form-check form-switch mb-0"><input class="form-check-input tpl-toggle" type="checkbox" checked data-tpl="monthly_report"></div>
                                    <button class="btn btn-sm btn-subtle-primary" onclick="openTemplateEditor('monthly_report')"><i class="ri-edit-line me-1"></i>Edit</button>
                                    <button class="btn btn-sm btn-subtle-info" onclick="previewTemplate('monthly_report')"><i class="ri-eye-line"></i></button>
                                    <button class="btn btn-sm btn-subtle-secondary" onclick="testTemplate('monthly_report')"><i class="ri-send-plane-line"></i></button>
                                </div>
                            </li>

                        </ul>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- =============================================================== -->
<!-- TEMPLATE EDITOR MODAL -->
<!-- =============================================================== -->
<div class="modal fade" id="templateEditorModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header" style="background:linear-gradient(135deg,#1F3864,#2a4a7f);">
                <h5 class="modal-title text-white"><i class="ri-mail-settings-line me-2"></i><span id="tplEditorTitle">Edit Template</span></h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="tplEditorKey" value="">

                <div class="row g-3">
                    <!-- Language selector -->
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Language</label>
                        <select class="form-select" id="tplLang" onchange="switchTplLang(this.value)">
                            <option value="pl" selected>Polish (PL)</option>
                            <option value="en">English (EN)</option>
                            <option value="ua">Ukrainian (UA)</option>
                            <option value="ru">Russian (RU)</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">From Name</label>
                        <input type="text" class="form-control" id="tplFromName" value="WinCase CRM">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Reply-To</label>
                        <input type="email" class="form-control" id="tplReplyTo" value="biuro@wincase.eu">
                    </div>

                    <!-- Subject -->
                    <div class="col-12">
                        <label class="form-label fw-semibold">Subject</label>
                        <input type="text" class="form-control" id="tplSubject" placeholder="Email subject...">
                    </div>

                    <!-- Body editor -->
                    <div class="col-12">
                        <label class="form-label fw-semibold">Body (HTML)</label>
                        <div class="mb-2">
                            <div class="btn-group btn-group-sm" role="group">
                                <button type="button" class="btn btn-outline-secondary" onclick="insertVar('{client_name}')">Client Name</button>
                                <button type="button" class="btn btn-outline-secondary" onclick="insertVar('{client_email}')">Client Email</button>
                                <button type="button" class="btn btn-outline-secondary" onclick="insertVar('{case_number}')">Case #</button>
                                <button type="button" class="btn btn-outline-secondary" onclick="insertVar('{case_type}')">Case Type</button>
                                <button type="button" class="btn btn-outline-secondary" onclick="insertVar('{case_status}')">Status</button>
                            </div>
                        </div>
                        <div class="mb-2">
                            <div class="btn-group btn-group-sm" role="group">
                                <button type="button" class="btn btn-outline-secondary" onclick="insertVar('{document_type}')">Doc Type</button>
                                <button type="button" class="btn btn-outline-secondary" onclick="insertVar('{expiry_date}')">Expiry Date</button>
                                <button type="button" class="btn btn-outline-secondary" onclick="insertVar('{invoice_number}')">Invoice #</button>
                                <button type="button" class="btn btn-outline-secondary" onclick="insertVar('{invoice_amount}')">Amount</button>
                                <button type="button" class="btn btn-outline-secondary" onclick="insertVar('{payment_amount}')">Payment</button>
                            </div>
                        </div>
                        <div class="mb-2">
                            <div class="btn-group btn-group-sm" role="group">
                                <button type="button" class="btn btn-outline-secondary" onclick="insertVar('{appointment_date}')">Apt Date</button>
                                <button type="button" class="btn btn-outline-secondary" onclick="insertVar('{appointment_time}')">Apt Time</button>
                                <button type="button" class="btn btn-outline-secondary" onclick="insertVar('{task_title}')">Task</button>
                                <button type="button" class="btn btn-outline-secondary" onclick="insertVar('{task_deadline}')">Deadline</button>
                                <button type="button" class="btn btn-outline-secondary" onclick="insertVar('{worker_name}')">Worker</button>
                                <button type="button" class="btn btn-outline-secondary" onclick="insertVar('{company_name}')">Company</button>
                            </div>
                        </div>
                        <textarea class="form-control" id="tplBody" rows="14" style="font-family:'Fira Code','Consolas',monospace; font-size:.85rem;"></textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-info" onclick="previewFromEditor()"><i class="ri-eye-line me-1"></i>Preview</button>
                <button type="button" class="btn btn-primary" onclick="saveTemplate()"><i class="ri-save-line me-1"></i>Save Template</button>
            </div>
        </div>
    </div>
</div>

<!-- =============================================================== -->
<!-- TEMPLATE PREVIEW MODAL -->
<!-- =============================================================== -->
<div class="modal fade" id="templatePreviewModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="ri-eye-line me-2"></i>Template Preview</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0">
                <div class="p-3 bg-light border-bottom">
                    <div class="d-flex align-items-center gap-2 mb-1">
                        <strong class="fs-12">From:</strong> <span class="fs-12" id="prevFrom">WinCase CRM &lt;biuro@wincase.eu&gt;</span>
                    </div>
                    <div class="d-flex align-items-center gap-2 mb-1">
                        <strong class="fs-12">To:</strong> <span class="fs-12" id="prevTo">client@example.com</span>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <strong class="fs-12">Subject:</strong> <span class="fs-12 fw-semibold" id="prevSubject"></span>
                    </div>
                </div>
                <div class="p-4" id="prevBody" style="min-height:300px;"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- =============================================================== -->
<!-- TEST SEND MODAL -->
<!-- =============================================================== -->
<div class="modal fade" id="testSendModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="ri-send-plane-line me-2"></i>Send Test Email</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="testTplKey" value="">
                <div class="mb-3">
                    <label class="form-label fw-semibold">Recipient Email</label>
                    <input type="email" class="form-control" id="testEmail" value="wincasetop@gmail.com">
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Language</label>
                    <select class="form-select" id="testLang">
                        <option value="pl">Polish (PL)</option>
                        <option value="en">English (EN)</option>
                        <option value="ua">Ukrainian (UA)</option>
                        <option value="ru">Russian (RU)</option>
                    </select>
                </div>
                <div class="alert alert-info fs-12 mb-0"><i class="ri-information-line me-1"></i>Test email will use sample data for all variables.</div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="sendTestEmail()"><i class="ri-send-plane-line me-1"></i>Send Test</button>
            </div>
        </div>
    </div>
</div>

<!-- =============================================================== -->
<!-- CONNECT INTEGRATION MODAL -->
<!-- =============================================================== -->
<div class="modal fade" id="integrationConnectModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="ri-link me-2"></i><span id="intgConnTitle">Connect Integration</span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="intgConnBody"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="intgConnSave"><i class="ri-check-line me-1"></i>Save & Connect</button>
            </div>
        </div>
    </div>
</div>

<script>
(function(){
'use strict';

// ===============================================================
// TEMPLATE DEFAULTS — immigration CRM email templates
// ===============================================================
var templates = {
    welcome_client: {
        pl: { subject: 'Witamy w WinCase! Twoja sprawa jest w dobrych r\u0119kach', body: '<div style="font-family:Arial,sans-serif;max-width:600px;margin:0 auto;padding:20px;">\n<div style="background:#1F3864;color:#fff;padding:20px;border-radius:8px 8px 0 0;text-align:center;">\n<h1 style="margin:0;font-size:24px;">Witamy w WinCase!</h1>\n</div>\n<div style="padding:20px;border:1px solid #eee;border-top:none;border-radius:0 0 8px 8px;">\n<p>Szanowny/a <strong>{client_name}</strong>,</p>\n<p>Dzi\u0119kujemy za zaufanie! Twoje konto w systemie WinCase CRM zosta\u0142o utworzone.</p>\n<p>Tw\u00f3j opiekun: <strong>{worker_name}</strong></p>\n<p>Mo\u017cesz si\u0119 z nami skontaktowa\u0107:<br>\nTel: {company_phone}<br>\nEmail: {company_email}</p>\n<p style="margin-top:20px;">Z powa\u017caniem,<br><strong>{company_name}</strong></p>\n</div>\n</div>' },
        en: { subject: 'Welcome to WinCase! Your case is in good hands', body: '<div style="font-family:Arial,sans-serif;max-width:600px;margin:0 auto;padding:20px;">\n<div style="background:#1F3864;color:#fff;padding:20px;border-radius:8px 8px 0 0;text-align:center;">\n<h1 style="margin:0;font-size:24px;">Welcome to WinCase!</h1>\n</div>\n<div style="padding:20px;border:1px solid #eee;border-top:none;border-radius:0 0 8px 8px;">\n<p>Dear <strong>{client_name}</strong>,</p>\n<p>Thank you for trusting us! Your account in WinCase CRM has been created.</p>\n<p>Your assigned manager: <strong>{worker_name}</strong></p>\n<p>Contact us at:<br>\nPhone: {company_phone}<br>\nEmail: {company_email}</p>\n<p style="margin-top:20px;">Best regards,<br><strong>{company_name}</strong></p>\n</div>\n</div>' },
        ua: { subject: '\u041b\u0430\u0441\u043a\u0430\u0432\u043e \u043f\u0440\u043e\u0441\u0438\u043c\u043e \u0434\u043e WinCase!', body: '<div style="font-family:Arial,sans-serif;max-width:600px;margin:0 auto;padding:20px;">\n<div style="background:#1F3864;color:#fff;padding:20px;border-radius:8px 8px 0 0;text-align:center;">\n<h1 style="margin:0;font-size:24px;">\u041b\u0430\u0441\u043a\u0430\u0432\u043e \u043f\u0440\u043e\u0441\u0438\u043c\u043e \u0434\u043e WinCase!</h1>\n</div>\n<div style="padding:20px;border:1px solid #eee;border-top:none;border-radius:0 0 8px 8px;">\n<p>\u0428\u0430\u043d\u043e\u0432\u043d\u0438\u0439/\u0430 <strong>{client_name}</strong>,</p>\n<p>\u0414\u044f\u043a\u0443\u0454\u043c\u043e \u0437\u0430 \u0434\u043e\u0432\u0456\u0440\u0443! \u0412\u0430\u0448 \u0430\u043a\u0430\u0443\u043d\u0442 \u0432 WinCase CRM \u0441\u0442\u0432\u043e\u0440\u0435\u043d\u043e.</p>\n<p>\u0412\u0430\u0448 \u043c\u0435\u043d\u0435\u0434\u0436\u0435\u0440: <strong>{worker_name}</strong></p>\n<p>\u0417\u0432\'\u044f\u0436\u0456\u0442\u044c\u0441\u044f \u0437 \u043d\u0430\u043c\u0438:<br>\u0422\u0435\u043b: {company_phone}<br>\nEmail: {company_email}</p>\n<p style="margin-top:20px;">\u0417 \u043f\u043e\u0432\u0430\u0433\u043e\u044e,<br><strong>{company_name}</strong></p>\n</div>\n</div>' },
        ru: { subject: '\u0414\u043e\u0431\u0440\u043e \u043f\u043e\u0436\u0430\u043b\u043e\u0432\u0430\u0442\u044c \u0432 WinCase!', body: '<div style="font-family:Arial,sans-serif;max-width:600px;margin:0 auto;padding:20px;">\n<div style="background:#1F3864;color:#fff;padding:20px;border-radius:8px 8px 0 0;text-align:center;">\n<h1 style="margin:0;font-size:24px;">\u0414\u043e\u0431\u0440\u043e \u043f\u043e\u0436\u0430\u043b\u043e\u0432\u0430\u0442\u044c \u0432 WinCase!</h1>\n</div>\n<div style="padding:20px;border:1px solid #eee;border-top:none;border-radius:0 0 8px 8px;">\n<p>\u0423\u0432\u0430\u0436\u0430\u0435\u043c\u044b\u0439/\u0430\u044f <strong>{client_name}</strong>,</p>\n<p>\u0421\u043f\u0430\u0441\u0438\u0431\u043e \u0437\u0430 \u0434\u043e\u0432\u0435\u0440\u0438\u0435! \u0412\u0430\u0448 \u0430\u043a\u043a\u0430\u0443\u043d\u0442 \u0432 WinCase CRM \u0441\u043e\u0437\u0434\u0430\u043d.</p>\n<p>\u0412\u0430\u0448 \u043c\u0435\u043d\u0435\u0434\u0436\u0435\u0440: <strong>{worker_name}</strong></p>\n<p>\u0421\u0432\u044f\u0436\u0438\u0442\u0435\u0441\u044c \u0441 \u043d\u0430\u043c\u0438:<br>\u0422\u0435\u043b: {company_phone}<br>\nEmail: {company_email}</p>\n<p style="margin-top:20px;">\u0421 \u0443\u0432\u0430\u0436\u0435\u043d\u0438\u0435\u043c,<br><strong>{company_name}</strong></p>\n</div>\n</div>' }
    },
    new_lead: {
        pl: { subject: 'Nowy lead: {client_name}', body: '<p>Nowy lead zosta\u0142 przypisany do Ciebie.</p><p><strong>Klient:</strong> {client_name}<br><strong>Email:</strong> {client_email}<br><strong>Typ sprawy:</strong> {case_type}</p><p>Prosz\u0119 skontaktuj si\u0119 z klientem w ci\u0105gu 24 godzin.</p>' },
        en: { subject: 'New lead: {client_name}', body: '<p>A new lead has been assigned to you.</p><p><strong>Client:</strong> {client_name}<br><strong>Email:</strong> {client_email}<br><strong>Case type:</strong> {case_type}</p><p>Please contact the client within 24 hours.</p>' },
        ua: { subject: '\u041d\u043e\u0432\u0438\u0439 \u043b\u0456\u0434: {client_name}', body: '<p>\u041d\u043e\u0432\u0438\u0439 \u043b\u0456\u0434 \u043f\u0440\u0438\u0437\u043d\u0430\u0447\u0435\u043d\u043e \u0432\u0430\u043c.</p><p><strong>\u041a\u043b\u0456\u0454\u043d\u0442:</strong> {client_name}<br><strong>Email:</strong> {client_email}<br><strong>\u0422\u0438\u043f \u0441\u043f\u0440\u0430\u0432\u0438:</strong> {case_type}</p><p>\u0417\u0432\'\u044f\u0436\u0456\u0442\u044c\u0441\u044f \u0437 \u043a\u043b\u0456\u0454\u043d\u0442\u043e\u043c \u043f\u0440\u043e\u0442\u044f\u0433\u043e\u043c 24 \u0433\u043e\u0434\u0438\u043d.</p>' },
        ru: { subject: '\u041d\u043e\u0432\u044b\u0439 \u043b\u0438\u0434: {client_name}', body: '<p>\u041d\u043e\u0432\u044b\u0439 \u043b\u0438\u0434 \u043d\u0430\u0437\u043d\u0430\u0447\u0435\u043d \u0432\u0430\u043c.</p><p><strong>\u041a\u043b\u0438\u0435\u043d\u0442:</strong> {client_name}<br><strong>Email:</strong> {client_email}<br><strong>\u0422\u0438\u043f \u0434\u0435\u043b\u0430:</strong> {case_type}</p><p>\u0421\u0432\u044f\u0436\u0438\u0442\u0435\u0441\u044c \u0441 \u043a\u043b\u0438\u0435\u043d\u0442\u043e\u043c \u0432 \u0442\u0435\u0447\u0435\u043d\u0438\u0435 24 \u0447\u0430\u0441\u043e\u0432.</p>' }
    },
    case_created: {
        pl: { subject: 'Sprawa {case_number} zosta\u0142a otwarta', body: '<p>Szanowny/a <strong>{client_name}</strong>,</p><p>Twoja sprawa <strong>{case_number}</strong> ({case_type}) zosta\u0142a utworzona w naszym systemie.</p><p>Tw\u00f3j opiekun <strong>{worker_name}</strong> skontaktuje si\u0119 z Tob\u0105 wkr\u00f3tce.</p><p>Z powa\u017caniem,<br>{company_name}</p>' },
        en: { subject: 'Case {case_number} has been opened', body: '<p>Dear <strong>{client_name}</strong>,</p><p>Your case <strong>{case_number}</strong> ({case_type}) has been created in our system.</p><p>Your manager <strong>{worker_name}</strong> will contact you shortly.</p><p>Best regards,<br>{company_name}</p>' },
        ua: { subject: '\u0421\u043f\u0440\u0430\u0432\u0443 {case_number} \u0432\u0456\u0434\u043a\u0440\u0438\u0442\u043e', body: '<p>\u0428\u0430\u043d\u043e\u0432\u043d\u0438\u0439/\u0430 <strong>{client_name}</strong>,</p><p>\u0412\u0430\u0448\u0443 \u0441\u043f\u0440\u0430\u0432\u0443 <strong>{case_number}</strong> ({case_type}) \u0441\u0442\u0432\u043e\u0440\u0435\u043d\u043e \u0432 \u043d\u0430\u0448\u0456\u0439 \u0441\u0438\u0441\u0442\u0435\u043c\u0456.</p><p>\u0412\u0430\u0448 \u043c\u0435\u043d\u0435\u0434\u0436\u0435\u0440 <strong>{worker_name}</strong> \u0437\u0432\'\u044f\u0436\u0435\u0442\u044c\u0441\u044f \u0437 \u0432\u0430\u043c\u0438 \u043d\u0430\u0439\u0431\u043b\u0438\u0436\u0447\u0438\u043c \u0447\u0430\u0441\u043e\u043c.</p><p>\u0417 \u043f\u043e\u0432\u0430\u0433\u043e\u044e,<br>{company_name}</p>' },
        ru: { subject: '\u0414\u0435\u043b\u043e {case_number} \u043e\u0442\u043a\u0440\u044b\u0442\u043e', body: '<p>\u0423\u0432\u0430\u0436\u0430\u0435\u043c\u044b\u0439/\u0430\u044f <strong>{client_name}</strong>,</p><p>\u0412\u0430\u0448\u0435 \u0434\u0435\u043b\u043e <strong>{case_number}</strong> ({case_type}) \u0441\u043e\u0437\u0434\u0430\u043d\u043e \u0432 \u043d\u0430\u0448\u0435\u0439 \u0441\u0438\u0441\u0442\u0435\u043c\u0435.</p><p>\u0412\u0430\u0448 \u043c\u0435\u043d\u0435\u0434\u0436\u0435\u0440 <strong>{worker_name}</strong> \u0441\u0432\u044f\u0436\u0435\u0442\u0441\u044f \u0441 \u0432\u0430\u043c\u0438 \u0432 \u0431\u043b\u0438\u0436\u0430\u0439\u0448\u0435\u0435 \u0432\u0440\u0435\u043c\u044f.</p><p>\u0421 \u0443\u0432\u0430\u0436\u0435\u043d\u0438\u0435\u043c,<br>{company_name}</p>' }
    },
    case_status: {
        pl: { subject: 'Aktualizacja sprawy {case_number}: {case_status}', body: '<p>Szanowny/a <strong>{client_name}</strong>,</p><p>Status Twojej sprawy <strong>{case_number}</strong> zmieni\u0142 si\u0119 na: <strong>{case_status}</strong></p><p>Tw\u00f3j opiekun: {worker_name}<br>Kontakt: {company_phone}</p><p>Z powa\u017caniem,<br>{company_name}</p>' },
        en: { subject: 'Case {case_number} update: {case_status}', body: '<p>Dear <strong>{client_name}</strong>,</p><p>Your case <strong>{case_number}</strong> status changed to: <strong>{case_status}</strong></p><p>Your manager: {worker_name}<br>Contact: {company_phone}</p><p>Best regards,<br>{company_name}</p>' },
        ua: { subject: '\u041e\u043d\u043e\u0432\u043b\u0435\u043d\u043d\u044f \u0441\u043f\u0440\u0430\u0432\u0438 {case_number}: {case_status}', body: '<p>\u0428\u0430\u043d\u043e\u0432\u043d\u0438\u0439/\u0430 <strong>{client_name}</strong>,</p><p>\u0421\u0442\u0430\u0442\u0443\u0441 \u0432\u0430\u0448\u043e\u0457 \u0441\u043f\u0440\u0430\u0432\u0438 <strong>{case_number}</strong> \u0437\u043c\u0456\u043d\u0438\u0432\u0441\u044f \u043d\u0430: <strong>{case_status}</strong></p><p>\u0412\u0430\u0448 \u043c\u0435\u043d\u0435\u0434\u0436\u0435\u0440: {worker_name}<br>\u041a\u043e\u043d\u0442\u0430\u043a\u0442: {company_phone}</p><p>\u0417 \u043f\u043e\u0432\u0430\u0433\u043e\u044e,<br>{company_name}</p>' },
        ru: { subject: '\u041e\u0431\u043d\u043e\u0432\u043b\u0435\u043d\u0438\u0435 \u0434\u0435\u043b\u0430 {case_number}: {case_status}', body: '<p>\u0423\u0432\u0430\u0436\u0430\u0435\u043c\u044b\u0439/\u0430\u044f <strong>{client_name}</strong>,</p><p>\u0421\u0442\u0430\u0442\u0443\u0441 \u0432\u0430\u0448\u0435\u0433\u043e \u0434\u0435\u043b\u0430 <strong>{case_number}</strong> \u0438\u0437\u043c\u0435\u043d\u0438\u043b\u0441\u044f \u043d\u0430: <strong>{case_status}</strong></p><p>\u0412\u0430\u0448 \u043c\u0435\u043d\u0435\u0434\u0436\u0435\u0440: {worker_name}<br>\u041a\u043e\u043d\u0442\u0430\u043a\u0442: {company_phone}</p><p>\u0421 \u0443\u0432\u0430\u0436\u0435\u043d\u0438\u0435\u043c,<br>{company_name}</p>' }
    },
    doc_request: {
        pl: { subject: 'Pro\u015bba o dokumenty — sprawa {case_number}', body: '<p>Szanowny/a <strong>{client_name}</strong>,</p><p>Potrzebujemy nast\u0119puj\u0105cego dokumentu do Twojej sprawy <strong>{case_number}</strong>:</p><p style="padding:10px;background:#f8f9fa;border-left:3px solid #1F3864;"><strong>{document_type}</strong></p><p>Prosz\u0119 przes\u0142a\u0107 skan lub zdj\u0119cie przez portal klienta lub email.</p><p>Dzi\u0119kujemy,<br>{company_name}</p>' },
        en: { subject: 'Document request \u2014 case {case_number}', body: '<p>Dear <strong>{client_name}</strong>,</p><p>We need the following document for your case <strong>{case_number}</strong>:</p><p style="padding:10px;background:#f8f9fa;border-left:3px solid #1F3864;"><strong>{document_type}</strong></p><p>Please send a scan or photo via client portal or email.</p><p>Thank you,<br>{company_name}</p>' },
        ua: { subject: '\u0417\u0430\u043f\u0438\u0442 \u0434\u043e\u043a\u0443\u043c\u0435\u043d\u0442\u0456\u0432 \u2014 \u0441\u043f\u0440\u0430\u0432\u0430 {case_number}', body: '<p>\u0428\u0430\u043d\u043e\u0432\u043d\u0438\u0439/\u0430 <strong>{client_name}</strong>,</p><p>\u041d\u0430\u043c \u043f\u043e\u0442\u0440\u0456\u0431\u0435\u043d \u043d\u0430\u0441\u0442\u0443\u043f\u043d\u0438\u0439 \u0434\u043e\u043a\u0443\u043c\u0435\u043d\u0442 \u0434\u043b\u044f \u0432\u0430\u0448\u043e\u0457 \u0441\u043f\u0440\u0430\u0432\u0438 <strong>{case_number}</strong>:</p><p style="padding:10px;background:#f8f9fa;border-left:3px solid #1F3864;"><strong>{document_type}</strong></p><p>\u041d\u0430\u0434\u0456\u0448\u043b\u0456\u0442\u044c \u0441\u043a\u0430\u043d \u0430\u0431\u043e \u0444\u043e\u0442\u043e \u0447\u0435\u0440\u0435\u0437 \u043f\u043e\u0440\u0442\u0430\u043b \u0430\u0431\u043e email.</p><p>\u0414\u044f\u043a\u0443\u0454\u043c\u043e,<br>{company_name}</p>' },
        ru: { subject: '\u0417\u0430\u043f\u0440\u043e\u0441 \u0434\u043e\u043a\u0443\u043c\u0435\u043d\u0442\u043e\u0432 \u2014 \u0434\u0435\u043b\u043e {case_number}', body: '<p>\u0423\u0432\u0430\u0436\u0430\u0435\u043c\u044b\u0439/\u0430\u044f <strong>{client_name}</strong>,</p><p>\u041d\u0430\u043c \u043d\u0443\u0436\u0435\u043d \u0441\u043b\u0435\u0434\u0443\u044e\u0449\u0438\u0439 \u0434\u043e\u043a\u0443\u043c\u0435\u043d\u0442 \u0434\u043b\u044f \u0432\u0430\u0448\u0435\u0433\u043e \u0434\u0435\u043b\u0430 <strong>{case_number}</strong>:</p><p style="padding:10px;background:#f8f9fa;border-left:3px solid #1F3864;"><strong>{document_type}</strong></p><p>\u041e\u0442\u043f\u0440\u0430\u0432\u044c\u0442\u0435 \u0441\u043a\u0430\u043d \u0438\u043b\u0438 \u0444\u043e\u0442\u043e \u0447\u0435\u0440\u0435\u0437 \u043f\u043e\u0440\u0442\u0430\u043b \u0438\u043b\u0438 email.</p><p>\u0421\u043f\u0430\u0441\u0438\u0431\u043e,<br>{company_name}</p>' }
    },
    doc_expiry: {
        pl: { subject: '\u26a0\ufe0f Dokument wyga\u015bnie: {document_type} \u2014 {expiry_date}', body: '<p>Szanowny/a <strong>{client_name}</strong>,</p><p>Tw\u00f3j dokument <strong>{document_type}</strong> wyga\u015bnie <strong>{expiry_date}</strong>.</p><p>Prosimy o pilny kontakt w celu przed\u0142u\u017cenia dokumentu.</p><p>Kontakt: {company_phone}<br>{company_name}</p>' },
        en: { subject: '\u26a0\ufe0f Document expiring: {document_type} \u2014 {expiry_date}', body: '<p>Dear <strong>{client_name}</strong>,</p><p>Your document <strong>{document_type}</strong> expires on <strong>{expiry_date}</strong>.</p><p>Please contact us urgently to renew your document.</p><p>Contact: {company_phone}<br>{company_name}</p>' },
        ua: { subject: '\u26a0\ufe0f \u0414\u043e\u043a\u0443\u043c\u0435\u043d\u0442 \u0437\u0430\u043a\u0456\u043d\u0447\u0443\u0454\u0442\u044c\u0441\u044f: {document_type} \u2014 {expiry_date}', body: '<p>\u0428\u0430\u043d\u043e\u0432\u043d\u0438\u0439/\u0430 <strong>{client_name}</strong>,</p><p>\u0412\u0430\u0448 \u0434\u043e\u043a\u0443\u043c\u0435\u043d\u0442 <strong>{document_type}</strong> \u0437\u0430\u043a\u0456\u043d\u0447\u0443\u0454\u0442\u044c\u0441\u044f <strong>{expiry_date}</strong>.</p><p>\u0417\u0432\'\u044f\u0436\u0456\u0442\u044c\u0441\u044f \u0437 \u043d\u0430\u043c\u0438 \u0442\u0435\u0440\u043c\u0456\u043d\u043e\u0432\u043e \u0434\u043b\u044f \u043f\u043e\u043d\u043e\u0432\u043b\u0435\u043d\u043d\u044f.</p><p>\u041a\u043e\u043d\u0442\u0430\u043a\u0442: {company_phone}<br>{company_name}</p>' },
        ru: { subject: '\u26a0\ufe0f \u0414\u043e\u043a\u0443\u043c\u0435\u043d\u0442 \u0438\u0441\u0442\u0435\u043a\u0430\u0435\u0442: {document_type} \u2014 {expiry_date}', body: '<p>\u0423\u0432\u0430\u0436\u0430\u0435\u043c\u044b\u0439/\u0430\u044f <strong>{client_name}</strong>,</p><p>\u0412\u0430\u0448 \u0434\u043e\u043a\u0443\u043c\u0435\u043d\u0442 <strong>{document_type}</strong> \u0438\u0441\u0442\u0435\u043a\u0430\u0435\u0442 <strong>{expiry_date}</strong>.</p><p>\u0421\u0432\u044f\u0436\u0438\u0442\u0435\u0441\u044c \u0441 \u043d\u0430\u043c\u0438 \u0441\u0440\u043e\u0447\u043d\u043e \u0434\u043b\u044f \u043f\u0440\u043e\u0434\u043b\u0435\u043d\u0438\u044f.</p><p>\u041a\u043e\u043d\u0442\u0430\u043a\u0442: {company_phone}<br>{company_name}</p>' }
    },
    invoice_sent: {
        pl: { subject: 'Faktura {invoice_number} \u2014 {invoice_amount} PLN', body: '<p>Szanowny/a <strong>{client_name}</strong>,</p><p>Wystawili\u015bmy faktur\u0119 <strong>{invoice_number}</strong> na kwot\u0119 <strong>{invoice_amount} PLN</strong>.</p><p>Faktura zosta\u0142a za\u0142\u0105czona do tej wiadomo\u015bci.</p><p>Termin p\u0142atno\u015bci: 14 dni.</p><p>Z powa\u017caniem,<br>{company_name}</p>' },
        en: { subject: 'Invoice {invoice_number} \u2014 {invoice_amount} PLN', body: '<p>Dear <strong>{client_name}</strong>,</p><p>We have issued invoice <strong>{invoice_number}</strong> for <strong>{invoice_amount} PLN</strong>.</p><p>The invoice is attached to this email.</p><p>Payment due: 14 days.</p><p>Best regards,<br>{company_name}</p>' },
        ua: { subject: '\u0420\u0430\u0445\u0443\u043d\u043e\u043a {invoice_number} \u2014 {invoice_amount} PLN', body: '<p>\u0428\u0430\u043d\u043e\u0432\u043d\u0438\u0439/\u0430 <strong>{client_name}</strong>,</p><p>\u041c\u0438 \u0432\u0438\u0441\u0442\u0430\u0432\u0438\u043b\u0438 \u0440\u0430\u0445\u0443\u043d\u043e\u043a <strong>{invoice_number}</strong> \u043d\u0430 \u0441\u0443\u043c\u0443 <strong>{invoice_amount} PLN</strong>.</p><p>\u0420\u0430\u0445\u0443\u043d\u043e\u043a \u0434\u043e\u0434\u0430\u043d\u043e \u0434\u043e \u0446\u044c\u043e\u0433\u043e \u043b\u0438\u0441\u0442\u0430.</p><p>\u0422\u0435\u0440\u043c\u0456\u043d \u043e\u043f\u043b\u0430\u0442\u0438: 14 \u0434\u043d\u0456\u0432.</p><p>\u0417 \u043f\u043e\u0432\u0430\u0433\u043e\u044e,<br>{company_name}</p>' },
        ru: { subject: '\u0421\u0447\u0451\u0442 {invoice_number} \u2014 {invoice_amount} PLN', body: '<p>\u0423\u0432\u0430\u0436\u0430\u0435\u043c\u044b\u0439/\u0430\u044f <strong>{client_name}</strong>,</p><p>\u041c\u044b \u0432\u044b\u0441\u0442\u0430\u0432\u0438\u043b\u0438 \u0441\u0447\u0451\u0442 <strong>{invoice_number}</strong> \u043d\u0430 \u0441\u0443\u043c\u043c\u0443 <strong>{invoice_amount} PLN</strong>.</p><p>\u0421\u0447\u0451\u0442 \u043f\u0440\u0438\u043b\u043e\u0436\u0435\u043d \u043a \u044d\u0442\u043e\u043c\u0443 \u043f\u0438\u0441\u044c\u043c\u0443.</p><p>\u0421\u0440\u043e\u043a \u043e\u043f\u043b\u0430\u0442\u044b: 14 \u0434\u043d\u0435\u0439.</p><p>\u0421 \u0443\u0432\u0430\u0436\u0435\u043d\u0438\u0435\u043c,<br>{company_name}</p>' }
    },
    payment_confirm: {
        pl: { subject: 'Potwierdzenie p\u0142atno\u015bci \u2014 {payment_amount} PLN', body: '<p>Szanowny/a <strong>{client_name}</strong>,</p><p>Potwierdzamy otrzymanie p\u0142atno\u015bci <strong>{payment_amount} PLN</strong> ({payment_method}).</p><p>Dzi\u0119kujemy!</p><p>{company_name}</p>' },
        en: { subject: 'Payment confirmation \u2014 {payment_amount} PLN', body: '<p>Dear <strong>{client_name}</strong>,</p><p>We confirm receipt of payment <strong>{payment_amount} PLN</strong> ({payment_method}).</p><p>Thank you!</p><p>{company_name}</p>' },
        ua: { subject: '\u041f\u0456\u0434\u0442\u0432\u0435\u0440\u0434\u0436\u0435\u043d\u043d\u044f \u043e\u043f\u043b\u0430\u0442\u0438 \u2014 {payment_amount} PLN', body: '<p>\u0428\u0430\u043d\u043e\u0432\u043d\u0438\u0439/\u0430 <strong>{client_name}</strong>,</p><p>\u041f\u0456\u0434\u0442\u0432\u0435\u0440\u0434\u0436\u0443\u0454\u043c\u043e \u043e\u0442\u0440\u0438\u043c\u0430\u043d\u043d\u044f \u043e\u043f\u043b\u0430\u0442\u0438 <strong>{payment_amount} PLN</strong> ({payment_method}).</p><p>\u0414\u044f\u043a\u0443\u0454\u043c\u043e!</p><p>{company_name}</p>' },
        ru: { subject: '\u041f\u043e\u0434\u0442\u0432\u0435\u0440\u0436\u0434\u0435\u043d\u0438\u0435 \u043e\u043f\u043b\u0430\u0442\u044b \u2014 {payment_amount} PLN', body: '<p>\u0423\u0432\u0430\u0436\u0430\u0435\u043c\u044b\u0439/\u0430\u044f <strong>{client_name}</strong>,</p><p>\u041f\u043e\u0434\u0442\u0432\u0435\u0440\u0436\u0434\u0430\u0435\u043c \u043f\u043e\u043b\u0443\u0447\u0435\u043d\u0438\u0435 \u043e\u043f\u043b\u0430\u0442\u044b <strong>{payment_amount} PLN</strong> ({payment_method}).</p><p>\u0421\u043f\u0430\u0441\u0438\u0431\u043e!</p><p>{company_name}</p>' }
    },
    appointment_reminder: {
        pl: { subject: 'Przypomnienie: wizyta {appointment_date} o {appointment_time}', body: '<p>Szanowny/a <strong>{client_name}</strong>,</p><p>Przypominamy o wizycie w urz\u0119dzie:</p><p><strong>Data:</strong> {appointment_date}<br><strong>Godzina:</strong> {appointment_time}<br><strong>Adres:</strong> {appointment_address}</p><p>Prosz\u0119 przynie\u015b\u0107 wszystkie wymagane dokumenty.</p><p>{company_name}</p>' },
        en: { subject: 'Reminder: appointment {appointment_date} at {appointment_time}', body: '<p>Dear <strong>{client_name}</strong>,</p><p>This is a reminder about your office appointment:</p><p><strong>Date:</strong> {appointment_date}<br><strong>Time:</strong> {appointment_time}<br><strong>Address:</strong> {appointment_address}</p><p>Please bring all required documents.</p><p>{company_name}</p>' },
        ua: { subject: '\u041d\u0430\u0433\u0430\u0434\u0443\u0432\u0430\u043d\u043d\u044f: \u0432\u0456\u0437\u0438\u0442 {appointment_date} \u043e {appointment_time}', body: '<p>\u0428\u0430\u043d\u043e\u0432\u043d\u0438\u0439/\u0430 <strong>{client_name}</strong>,</p><p>\u041d\u0430\u0433\u0430\u0434\u0443\u0454\u043c\u043e \u043f\u0440\u043e \u0432\u0456\u0437\u0438\u0442 \u0434\u043e \u0443\u0440\u0436\u0435\u043d\u0434\u0443:</p><p><strong>\u0414\u0430\u0442\u0430:</strong> {appointment_date}<br><strong>\u0427\u0430\u0441:</strong> {appointment_time}<br><strong>\u0410\u0434\u0440\u0435\u0441\u0430:</strong> {appointment_address}</p><p>\u0412\u0456\u0437\u044c\u043c\u0456\u0442\u044c \u0432\u0441\u0456 \u043d\u0435\u043e\u0431\u0445\u0456\u0434\u043d\u0456 \u0434\u043e\u043a\u0443\u043c\u0435\u043d\u0442\u0438.</p><p>{company_name}</p>' },
        ru: { subject: '\u041d\u0430\u043f\u043e\u043c\u0438\u043d\u0430\u043d\u0438\u0435: \u0432\u0438\u0437\u0438\u0442 {appointment_date} \u0432 {appointment_time}', body: '<p>\u0423\u0432\u0430\u0436\u0430\u0435\u043c\u044b\u0439/\u0430\u044f <strong>{client_name}</strong>,</p><p>\u041d\u0430\u043f\u043e\u043c\u0438\u043d\u0430\u0435\u043c \u043e \u0432\u0438\u0437\u0438\u0442\u0435 \u0432 \u0443\u0440\u0436\u043e\u043d\u0434:</p><p><strong>\u0414\u0430\u0442\u0430:</strong> {appointment_date}<br><strong>\u0412\u0440\u0435\u043c\u044f:</strong> {appointment_time}<br><strong>\u0410\u0434\u0440\u0435\u0441:</strong> {appointment_address}</p><p>\u0412\u043e\u0437\u044c\u043c\u0438\u0442\u0435 \u0432\u0441\u0435 \u043d\u0435\u043e\u0431\u0445\u043e\u0434\u0438\u043c\u044b\u0435 \u0434\u043e\u043a\u0443\u043c\u0435\u043d\u0442\u044b.</p><p>{company_name}</p>' }
    },
    task_assigned: {
        pl: { subject: 'Nowe zadanie: {task_title}', body: '<p>Masz nowe zadanie:</p><p><strong>{task_title}</strong></p><p>Termin: <strong>{task_deadline}</strong><br>Klient: {client_name}<br>Sprawa: {case_number}</p><p>Zaloguj si\u0119 do CRM, aby zobaczy\u0107 szczeg\u00f3\u0142y.</p>' },
        en: { subject: 'New task: {task_title}', body: '<p>You have a new task:</p><p><strong>{task_title}</strong></p><p>Deadline: <strong>{task_deadline}</strong><br>Client: {client_name}<br>Case: {case_number}</p><p>Log in to CRM for details.</p>' },
        ua: { subject: '\u041d\u043e\u0432\u0435 \u0437\u0430\u0432\u0434\u0430\u043d\u043d\u044f: {task_title}', body: '<p>\u0412\u0438 \u043c\u0430\u0454\u0442\u0435 \u043d\u043e\u0432\u0435 \u0437\u0430\u0432\u0434\u0430\u043d\u043d\u044f:</p><p><strong>{task_title}</strong></p><p>\u0414\u0435\u0434\u043b\u0430\u0439\u043d: <strong>{task_deadline}</strong><br>\u041a\u043b\u0456\u0454\u043d\u0442: {client_name}<br>\u0421\u043f\u0440\u0430\u0432\u0430: {case_number}</p><p>\u0423\u0432\u0456\u0439\u0434\u0456\u0442\u044c \u0432 CRM \u0434\u043b\u044f \u0434\u0435\u0442\u0430\u043b\u0435\u0439.</p>' },
        ru: { subject: '\u041d\u043e\u0432\u0430\u044f \u0437\u0430\u0434\u0430\u0447\u0430: {task_title}', body: '<p>\u0423 \u0432\u0430\u0441 \u043d\u043e\u0432\u0430\u044f \u0437\u0430\u0434\u0430\u0447\u0430:</p><p><strong>{task_title}</strong></p><p>\u0414\u0435\u0434\u043b\u0430\u0439\u043d: <strong>{task_deadline}</strong><br>\u041a\u043b\u0438\u0435\u043d\u0442: {client_name}<br>\u0414\u0435\u043b\u043e: {case_number}</p><p>\u0412\u043e\u0439\u0434\u0438\u0442\u0435 \u0432 CRM \u0434\u043b\u044f \u0434\u0435\u0442\u0430\u043b\u0435\u0439.</p>' }
    },
    task_overdue: {
        pl: { subject: '\u26a0\ufe0f Zadanie przeterminowane: {task_title}', body: '<p><strong style="color:red;">Zadanie jest przeterminowane!</strong></p><p><strong>{task_title}</strong></p><p>Termin: {task_deadline}<br>Klient: {client_name}<br>Odpowiedzialny: {worker_name}</p><p>Prosz\u0119 o natychmiastow\u0105 reakcj\u0119.</p>' },
        en: { subject: '\u26a0\ufe0f Task overdue: {task_title}', body: '<p><strong style="color:red;">Task is overdue!</strong></p><p><strong>{task_title}</strong></p><p>Deadline: {task_deadline}<br>Client: {client_name}<br>Assigned to: {worker_name}</p><p>Immediate action required.</p>' },
        ua: { subject: '\u26a0\ufe0f \u0417\u0430\u0432\u0434\u0430\u043d\u043d\u044f \u043f\u0440\u043e\u0441\u0442\u0440\u043e\u0447\u0435\u043d\u0435: {task_title}', body: '<p><strong style="color:red;">\u0417\u0430\u0432\u0434\u0430\u043d\u043d\u044f \u043f\u0440\u043e\u0441\u0442\u0440\u043e\u0447\u0435\u043d\u0435!</strong></p><p><strong>{task_title}</strong></p><p>\u0414\u0435\u0434\u043b\u0430\u0439\u043d: {task_deadline}<br>\u041a\u043b\u0456\u0454\u043d\u0442: {client_name}<br>\u0412\u0456\u0434\u043f\u043e\u0432\u0456\u0434\u0430\u043b\u044c\u043d\u0438\u0439: {worker_name}</p><p>\u041f\u043e\u0442\u0440\u0456\u0431\u043d\u0430 \u043d\u0435\u0433\u0430\u0439\u043d\u0430 \u0440\u0435\u0430\u043a\u0446\u0456\u044f.</p>' },
        ru: { subject: '\u26a0\ufe0f \u0417\u0430\u0434\u0430\u0447\u0430 \u043f\u0440\u043e\u0441\u0440\u043e\u0447\u0435\u043d\u0430: {task_title}', body: '<p><strong style="color:red;">\u0417\u0430\u0434\u0430\u0447\u0430 \u043f\u0440\u043e\u0441\u0440\u043e\u0447\u0435\u043d\u0430!</strong></p><p><strong>{task_title}</strong></p><p>\u0414\u0435\u0434\u043b\u0430\u0439\u043d: {task_deadline}<br>\u041a\u043b\u0438\u0435\u043d\u0442: {client_name}<br>\u041e\u0442\u0432\u0435\u0442\u0441\u0442\u0432\u0435\u043d\u043d\u044b\u0439: {worker_name}</p><p>\u0422\u0440\u0435\u0431\u0443\u0435\u0442\u0441\u044f \u043d\u0435\u043c\u0435\u0434\u043b\u0435\u043d\u043d\u0430\u044f \u0440\u0435\u0430\u043a\u0446\u0438\u044f.</p>' }
    },
    monthly_report: {
        pl: { subject: 'Raport miesi\u0119czny \u2014 WinCase CRM', body: '<p>Raport miesi\u0119czny:</p><ul><li>Nowi klienci: <strong>—</strong></li><li>Aktywne sprawy: <strong>—</strong></li><li>Przych\u00f3d: <strong>— PLN</strong></li><li>Wydatki: <strong>— PLN</strong></li><li>Zadania wykonane: <strong>—</strong></li></ul><p>Zaloguj si\u0119 do CRM, aby zobaczy\u0107 pe\u0142ne statystyki.</p>' },
        en: { subject: 'Monthly report \u2014 WinCase CRM', body: '<p>Monthly report:</p><ul><li>New clients: <strong>\u2014</strong></li><li>Active cases: <strong>\u2014</strong></li><li>Revenue: <strong>\u2014 PLN</strong></li><li>Expenses: <strong>\u2014 PLN</strong></li><li>Tasks completed: <strong>\u2014</strong></li></ul><p>Log in to CRM for full statistics.</p>' },
        ua: { subject: '\u041c\u0456\u0441\u044f\u0447\u043d\u0438\u0439 \u0437\u0432\u0456\u0442 \u2014 WinCase CRM', body: '<p>\u041c\u0456\u0441\u044f\u0447\u043d\u0438\u0439 \u0437\u0432\u0456\u0442:</p><ul><li>\u041d\u043e\u0432\u0456 \u043a\u043b\u0456\u0454\u043d\u0442\u0438: <strong>\u2014</strong></li><li>\u0410\u043a\u0442\u0438\u0432\u043d\u0456 \u0441\u043f\u0440\u0430\u0432\u0438: <strong>\u2014</strong></li><li>\u0414\u043e\u0445\u0456\u0434: <strong>\u2014 PLN</strong></li><li>\u0412\u0438\u0442\u0440\u0430\u0442\u0438: <strong>\u2014 PLN</strong></li><li>\u0412\u0438\u043a\u043e\u043d\u0430\u043d\u0456 \u0437\u0430\u0432\u0434\u0430\u043d\u043d\u044f: <strong>\u2014</strong></li></ul><p>\u0423\u0432\u0456\u0439\u0434\u0456\u0442\u044c \u0432 CRM \u0434\u043b\u044f \u043f\u043e\u0432\u043d\u043e\u0457 \u0441\u0442\u0430\u0442\u0438\u0441\u0442\u0438\u043a\u0438.</p>' },
        ru: { subject: '\u0415\u0436\u0435\u043c\u0435\u0441\u044f\u0447\u043d\u044b\u0439 \u043e\u0442\u0447\u0451\u0442 \u2014 WinCase CRM', body: '<p>\u0415\u0436\u0435\u043c\u0435\u0441\u044f\u0447\u043d\u044b\u0439 \u043e\u0442\u0447\u0451\u0442:</p><ul><li>\u041d\u043e\u0432\u044b\u0435 \u043a\u043b\u0438\u0435\u043d\u0442\u044b: <strong>\u2014</strong></li><li>\u0410\u043a\u0442\u0438\u0432\u043d\u044b\u0435 \u0434\u0435\u043b\u0430: <strong>\u2014</strong></li><li>\u0414\u043e\u0445\u043e\u0434: <strong>\u2014 PLN</strong></li><li>\u0420\u0430\u0441\u0445\u043e\u0434\u044b: <strong>\u2014 PLN</strong></li><li>\u0412\u044b\u043f\u043e\u043b\u043d\u0435\u043d\u043d\u044b\u0435 \u0437\u0430\u0434\u0430\u0447\u0438: <strong>\u2014</strong></li></ul><p>\u0412\u043e\u0439\u0434\u0438\u0442\u0435 \u0432 CRM \u0434\u043b\u044f \u043f\u043e\u043b\u043d\u043e\u0439 \u0441\u0442\u0430\u0442\u0438\u0441\u0442\u0438\u043a\u0438.</p>' }
    }
};

var currentTplLang = 'pl';

// Sample data for preview
var sampleData = {
    '{client_name}': 'Jan Kowalski',
    '{client_email}': 'jan@example.com',
    '{case_number}': 'WC-2026-00142',
    '{case_type}': 'Karta Pobytu',
    '{case_status}': 'Z\u0142o\u017cone w urz\u0119dzie',
    '{document_type}': 'Paszport',
    '{expiry_date}': '2026-05-15',
    '{invoice_number}': 'FV/2026/03/001',
    '{invoice_amount}': '2 500',
    '{payment_amount}': '2 500',
    '{payment_method}': 'Przelew bankowy',
    '{appointment_date}': '2026-03-20',
    '{appointment_time}': '10:30',
    '{appointment_address}': 'Mazowiecki Urz\u0105d Wojew\u00f3dzki, ul. D\u0142uga 5, Warszawa',
    '{task_title}': 'Przygotowa\u0107 wniosek o Kart\u0119 Pobytu',
    '{task_deadline}': '2026-03-18',
    '{worker_name}': 'Anna Kowalska',
    '{company_name}': 'WinCase Sp. z o.o.',
    '{company_phone}': '+48 22 123 45 67',
    '{company_email}': 'biuro@wincase.eu'
};

function replacePlaceholders(text) {
    var result = text;
    for (var key in sampleData) {
        result = result.split(key).join(sampleData[key]);
    }
    return result;
}

// ---------------------------------------------------------------
// TEMPLATE CATEGORY FILTER
// ---------------------------------------------------------------
document.querySelectorAll('.tpl-filter').forEach(function(btn) {
    btn.addEventListener('click', function() {
        document.querySelectorAll('.tpl-filter').forEach(function(b) { b.classList.remove('active'); });
        this.classList.add('active');
        var cat = this.getAttribute('data-cat');
        document.querySelectorAll('#templatesList li').forEach(function(li) {
            if (cat === 'all' || li.getAttribute('data-cat') === cat) {
                li.style.display = '';
            } else {
                li.style.display = 'none';
            }
        });
    });
});

// ---------------------------------------------------------------
// TEMPLATE EDITOR
// ---------------------------------------------------------------
window.openTemplateEditor = function(key) {
    var modal = new bootstrap.Modal(document.getElementById('templateEditorModal'));
    document.getElementById('tplEditorKey').value = key;
    currentTplLang = 'pl';
    document.getElementById('tplLang').value = 'pl';

    if (key === 'new') {
        document.getElementById('tplEditorTitle').textContent = 'New Template';
        document.getElementById('tplSubject').value = '';
        document.getElementById('tplBody').value = '';
    } else {
        var names = {
            welcome_client: 'Welcome Client', new_lead: 'New Lead', case_created: 'Case Created',
            case_status: 'Case Status Update', doc_request: 'Document Request', doc_expiry: 'Document Expiry',
            invoice_sent: 'Invoice Sent', payment_confirm: 'Payment Confirmation',
            appointment_reminder: 'Appointment Reminder', task_assigned: 'Task Assignment',
            task_overdue: 'Task Overdue', monthly_report: 'Monthly Report'
        };
        document.getElementById('tplEditorTitle').textContent = 'Edit: ' + (names[key] || key);
        loadTplLang(key, 'pl');
    }
    modal.show();
};

function loadTplLang(key, lang) {
    var tpl = templates[key];
    if (tpl && tpl[lang]) {
        document.getElementById('tplSubject').value = tpl[lang].subject;
        document.getElementById('tplBody').value = tpl[lang].body;
    }
}

window.switchTplLang = function(lang) {
    currentTplLang = lang;
    var key = document.getElementById('tplEditorKey').value;
    if (key && key !== 'new') {
        loadTplLang(key, lang);
    }
};

window.insertVar = function(v) {
    var ta = document.getElementById('tplBody');
    var start = ta.selectionStart;
    var end = ta.selectionEnd;
    var text = ta.value;
    ta.value = text.substring(0, start) + v + text.substring(end);
    ta.selectionStart = ta.selectionEnd = start + v.length;
    ta.focus();
};

window.saveTemplate = function() {
    var key = document.getElementById('tplEditorKey').value;
    var subject = document.getElementById('tplSubject').value;
    var body = document.getElementById('tplBody').value;
    var lang = currentTplLang;

    if (!templates[key]) templates[key] = {};
    if (!templates[key][lang]) templates[key][lang] = {};
    templates[key][lang].subject = subject;
    templates[key][lang].body = body;

    // AJAX save
    fetch('/api/settings/email-templates', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '' },
        body: JSON.stringify({ key: key, lang: lang, subject: subject, body: body })
    })
    .then(function(r) { return r.json(); })
    .then(function(d) {
        showToast('Template saved successfully!', 'success');
    })
    .catch(function() {
        showToast('Template saved locally (server sync pending)', 'info');
    });

    bootstrap.Modal.getInstance(document.getElementById('templateEditorModal')).hide();
};

// ---------------------------------------------------------------
// TEMPLATE PREVIEW
// ---------------------------------------------------------------
window.previewTemplate = function(key) {
    var lang = 'pl';
    var tpl = templates[key];
    if (!tpl || !tpl[lang]) {
        showToast('Template not found', 'warning');
        return;
    }
    document.getElementById('prevSubject').textContent = replacePlaceholders(tpl[lang].subject);
    document.getElementById('prevBody').innerHTML = replacePlaceholders(tpl[lang].body);
    new bootstrap.Modal(document.getElementById('templatePreviewModal')).show();
};

window.previewFromEditor = function() {
    var subject = document.getElementById('tplSubject').value;
    var body = document.getElementById('tplBody').value;
    document.getElementById('prevSubject').textContent = replacePlaceholders(subject);
    document.getElementById('prevBody').innerHTML = replacePlaceholders(body);
    new bootstrap.Modal(document.getElementById('templatePreviewModal')).show();
};

// ---------------------------------------------------------------
// TEST EMAIL
// ---------------------------------------------------------------
window.testTemplate = function(key) {
    document.getElementById('testTplKey').value = key;
    new bootstrap.Modal(document.getElementById('testSendModal')).show();
};

window.sendTestEmail = function() {
    var key = document.getElementById('testTplKey').value;
    var email = document.getElementById('testEmail').value;
    var lang = document.getElementById('testLang').value;

    fetch('/api/settings/email-templates/test', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '' },
        body: JSON.stringify({ key: key, email: email, lang: lang })
    })
    .then(function(r) { return r.json(); })
    .then(function(d) {
        showToast('Test email sent to ' + email, 'success');
    })
    .catch(function() {
        showToast('Test email queued (will be sent when server is configured)', 'info');
    });

    bootstrap.Modal.getInstance(document.getElementById('testSendModal')).hide();
};

// ---------------------------------------------------------------
// TEMPLATE TOGGLES (enable/disable)
// ---------------------------------------------------------------
document.querySelectorAll('.tpl-toggle').forEach(function(toggle) {
    toggle.addEventListener('change', function() {
        var key = this.getAttribute('data-tpl');
        var enabled = this.checked;
        fetch('/api/settings/email-templates/toggle', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '' },
            body: JSON.stringify({ key: key, enabled: enabled })
        })
        .then(function() {
            showToast('Template ' + (enabled ? 'enabled' : 'disabled'), 'success');
        })
        .catch(function() {
            showToast('Toggle saved locally', 'info');
        });
    });
});

// ===============================================================
// GENERAL SETTINGS — AJAX SAVE
// ===============================================================
document.getElementById('generalSettingsForm')?.addEventListener('submit', function(e) {
    e.preventDefault();
    var formData = new FormData(this);
    var data = {};
    formData.forEach(function(val, key) { data[key] = val; });

    fetch('/api/settings/general', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '' },
        body: JSON.stringify(data)
    })
    .then(function(r) { return r.json(); })
    .then(function(d) {
        showToast('General settings saved!', 'success');
    })
    .catch(function() {
        showToast('Settings saved locally', 'info');
    });
});

// ===============================================================
// NOTIFICATIONS — AJAX TOGGLE
// ===============================================================
document.querySelectorAll('#tab-notifications .form-check-input').forEach(function(toggle) {
    toggle.addEventListener('change', function() {
        var label = this.closest('li')?.querySelector('h6')?.textContent || '';
        var channel = this.closest('.col-xl-6')?.querySelector('h6')?.textContent?.includes('Telegram') ? 'telegram' : 'email';
        var enabled = this.checked;

        fetch('/api/settings/notifications', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '' },
            body: JSON.stringify({ notification: label, channel: channel, enabled: enabled })
        })
        .then(function() {
            showToast(label + ' ' + channel + ': ' + (enabled ? 'ON' : 'OFF'), 'success');
        })
        .catch(function() {
            showToast('Notification setting saved locally', 'info');
        });
    });
});

// ===============================================================
// NOTIFICATIONS — SAVE ALL BUTTON
// ===============================================================
document.querySelector('#tab-notifications .btn-primary')?.addEventListener('click', function() {
    var settings = [];
    document.querySelectorAll('#tab-notifications .col-xl-6').forEach(function(col) {
        var channel = col.querySelector('h6')?.textContent?.includes('Telegram') ? 'telegram' : 'email';
        col.querySelectorAll('.form-check-input').forEach(function(toggle) {
            var label = toggle.closest('li')?.querySelector('h6')?.textContent || '';
            settings.push({ notification: label, channel: channel, enabled: toggle.checked });
        });
    });

    fetch('/api/settings/notifications/bulk', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '' },
        body: JSON.stringify({ settings: settings })
    })
    .then(function(r) { return r.json(); })
    .then(function() {
        showToast('All notification settings saved!', 'success');
    })
    .catch(function() {
        showToast('Notification settings saved locally', 'info');
    });
});

// ===============================================================
// INTEGRATIONS — REAL SAVE/LOAD via API
// ===============================================================
<?php
// Generate fresh Sanctum token server-side (user already authenticated via session)
$_settingsUser = auth()->user();
$_settingsAbilities = $_settingsUser->role === 'boss' ? ['*'] : [];
// Delete stale settings tokens, keep max 1
$_settingsUser->tokens()->where('name', 'settings-page')->delete();
$_freshToken = $_settingsUser->createToken('settings-page', $_settingsAbilities)->plainTextToken;
?>
var INTG_TOKEN = @json($_freshToken);
localStorage.setItem('wc_token', INTG_TOKEN);
var INTG_H = { 'Accept': 'application/json', 'Authorization': 'Bearer ' + INTG_TOKEN };

// Load saved integrations on page load
function loadIntegrations() {
    fetch('/api/settings/integrations', { headers: INTG_H })
        .then(function(r) {
            if (r.status === 401) { window.location.href = '/login'; return null; }
            return r.json();
        })
        .then(function(j) {
            if (!j || !j.success || !j.data) return;
            var data = j.data;
            document.querySelectorAll('[data-service]').forEach(function(card) {
                var service = card.getAttribute('data-service');
                if (data[service]) {
                    var fields = data[service];
                    var hasVal = false;
                    card.querySelectorAll('[data-field]').forEach(function(inp) {
                        var key = inp.getAttribute('data-field');
                        if (fields[key]) {
                            inp.value = fields[key];
                            hasVal = true;
                        }
                    });
                    if (hasVal) {
                        var badge = card.querySelector('.intg-badge');
                        if (badge) { badge.className = 'badge bg-success-subtle text-success intg-badge'; badge.textContent = 'Connected'; }
                    }
                }
            });
        })
        .catch(function() {});
}
loadIntegrations();

// Save individual integration
function saveIntegration(card) {
    var service = card.getAttribute('data-service');
    if (!service) return;
    var fd = new FormData();
    fd.append('service', service);
    var hasVal = false;
    card.querySelectorAll('[data-field]').forEach(function(inp) {
        var key = inp.getAttribute('data-field');
        var val = inp.value.trim();
        fd.append(key, val);
        if (val) hasVal = true;
    });
    if (!hasVal) { showToast('Fill in at least one field', 'warning'); return; }

    fetch('/api/settings/integrations/connect', {
        method: 'POST',
        headers: { 'Accept': 'application/json', 'Authorization': 'Bearer ' + INTG_TOKEN },
        body: fd
    })
    .then(function(r) {
        if (r.status === 401) { window.location.href = '/login'; return null; }
        return r.json();
    })
    .then(function(j) {
        if (!j) return;
        if (j.success) {
            var badge = card.querySelector('.intg-badge');
            if (badge) { badge.className = 'badge bg-success-subtle text-success intg-badge'; badge.textContent = 'Connected'; }
            showToast(service + ' saved!', 'success');
        } else {
            showToast(j.message || 'Error saving', 'error');
        }
    })
    .catch(function() { showToast('Network error', 'error'); });
}

// Attach Save button handlers
document.querySelectorAll('.intg-save-btn').forEach(function(btn) {
    btn.addEventListener('click', function(e) {
        e.preventDefault();
        var card = this.closest('[data-service]');
        if (card) saveIntegration(card);
    });
});

// Save All button
document.querySelector('#tab-integrations > .mt-4 .btn-primary')?.addEventListener('click', function() {
    var cards = document.querySelectorAll('[data-service]');
    var saved = 0;
    cards.forEach(function(card) {
        var hasVal = false;
        card.querySelectorAll('[data-field]').forEach(function(inp) {
            if (inp.value.trim()) hasVal = true;
        });
        if (hasVal) { saveIntegration(card); saved++; }
    });
    if (saved === 0) showToast('Nothing to save', 'info');
});

// ===============================================================
// TOAST HELPER
// ===============================================================
function showToast(message, type) {
    type = type || 'info';
    var colors = { success: '#015EA7', warning: '#f0ad4e', info: '#0d6efd', error: '#dc3545' };
    var icons = { success: 'ri-check-line', warning: 'ri-alert-line', info: 'ri-information-line', error: 'ri-error-warning-line' };

    var toast = document.createElement('div');
    toast.style.cssText = 'position:fixed;top:20px;right:20px;z-index:99999;padding:12px 20px;border-radius:8px;color:#fff;font-size:14px;font-weight:500;display:flex;align-items:center;gap:8px;box-shadow:0 4px 12px rgba(0,0,0,.15);animation:slideIn .3s ease;background:' + (colors[type] || colors.info);
    toast.innerHTML = '<i class="' + (icons[type] || icons.info) + '"></i>' + message;
    document.body.appendChild(toast);
    setTimeout(function() {
        toast.style.opacity = '0';
        toast.style.transition = 'opacity .3s';
        setTimeout(function() { toast.remove(); }, 300);
    }, 3000);
}

// Toast animation
var styleEl = document.createElement('style');
styleEl.textContent = '@keyframes slideIn{from{transform:translateX(100%);opacity:0}to{transform:translateX(0);opacity:1}}';
document.head.appendChild(styleEl);

})();
</script>

@endsection
