@extends('partials.layouts.master')

@section('title', 'Documents | WinCase CRM')
@section('sub-title', 'Document Management')
@section('sub-title-lang', 'wc-doc-management')
@section('pagetitle', 'CRM')
@section('pagetitle-lang', 'wc-title-crm')

@section('css')
<style>
    .doc-tabs .nav-link { font-weight: 600; font-size: 0.85rem; padding: 10px 20px; border-radius: 8px 8px 0 0; }
    .doc-tabs .nav-link.active { background: #fff; border-bottom: 2px solid #5865F2; color: #5865F2; }
    .template-card { border: 1px solid #e9ecef; border-radius: 10px; padding: 16px; transition: all 0.2s; cursor: pointer; height: 100%; }
    .template-card:hover { border-color: #5865F2; box-shadow: 0 4px 12px rgba(88,101,242,0.12); transform: translateY(-2px); }
    .template-card .template-icon { width: 48px; height: 48px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 1.3rem; }
    .template-card .template-title { font-weight: 600; font-size: 0.9rem; margin: 10px 0 4px; }
    .template-card .template-desc { font-size: 0.78rem; color: #6c757d; }
    .case-type-section { margin-bottom: 24px; }
    .case-type-section .section-header { padding: 10px 16px; border-radius: 8px; margin-bottom: 12px; display: flex; align-items: center; gap: 10px; }
    .case-type-section .section-header h6 { margin: 0; font-weight: 700; font-size: 0.9rem; }
    .sig-pad-area { border: 2px solid #dee2e6; border-radius: 8px; background: #fff; min-height: 150px; position: relative; cursor: crosshair; }
    .sig-pad-area canvas { border-radius: 6px; }
    .form-field-group { background: #f8f9fa; border-radius: 8px; padding: 16px; margin-bottom: 12px; }
    .form-field-group .field-label { font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px; color: #6c757d; font-weight: 600; margin-bottom: 4px; }
    .integration-card { border: 1px solid #e9ecef; border-radius: 10px; padding: 20px; text-align: center; transition: all 0.2s; }
    .integration-card:hover { box-shadow: 0 4px 12px rgba(0,0,0,0.08); }
    .integration-card .int-icon { width: 64px; height: 64px; border-radius: 16px; display: flex; align-items: center; justify-content: center; font-size: 2rem; margin: 0 auto 12px; }
    .integration-card .int-status { font-size: 0.8rem; }
    .doc-viewer { background: #f8f9fa; border-radius: 8px; min-height: 400px; display: flex; align-items: center; justify-content: center; }
    .sig-pending-card { border-left: 3px solid #f97316; }
    .sig-completed-card { border-left: 3px solid #198754; }
    .filter-pills .btn { font-size: 0.8rem; padding: 4px 12px; border-radius: 20px; }
    .filter-pills .btn.active { font-weight: 600; }
    .stat-card-mini { border-radius: 8px; padding: 16px; text-align: center; }
    .stat-card-mini .stat-num { font-size: 1.8rem; font-weight: 700; }
    .stat-card-mini .stat-label { font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.5px; }
    .form-preview-section { background: #fff; border: 1px solid #e9ecef; border-radius: 8px; padding: 24px; }
    .form-preview-section .form-header { text-align: center; border-bottom: 2px solid #333; padding-bottom: 12px; margin-bottom: 20px; }
    .form-preview-section .form-header h5 { font-weight: 700; text-transform: uppercase; letter-spacing: 1px; }
    .form-preview-section .form-header p { font-size: 0.85rem; color: #6c757d; }
    .client-sync-badge { padding: 4px 10px; border-radius: 20px; font-size: 0.75rem; font-weight: 600; }
</style>
@endsection

@section('content')
<!-- Stats -->
<div class="row g-3 mb-3">
    <div class="col-xl-2 col-md-4 col-6">
        <div class="stat-card-mini bg-soft-primary">
            <div class="stat-num text-primary" id="statTotal">1,247</div>
            <div class="stat-label">Documents</div>
        </div>
    </div>
    <div class="col-xl-2 col-md-4 col-6">
        <div class="stat-card-mini bg-soft-success">
            <div class="stat-num text-success" id="statTemplates">42</div>
            <div class="stat-label">Templates</div>
        </div>
    </div>
    <div class="col-xl-2 col-md-4 col-6">
        <div class="stat-card-mini bg-soft-info">
            <div class="stat-num text-info" id="statForms">18</div>
            <div class="stat-label">Forms Filled</div>
        </div>
    </div>
    <div class="col-xl-2 col-md-4 col-6">
        <div class="stat-card-mini bg-soft-warning">
            <div class="stat-num text-warning" id="statPendingSig">7</div>
            <div class="stat-label">Pending Sign</div>
        </div>
    </div>
    <div class="col-xl-2 col-md-4 col-6">
        <div class="stat-card-mini bg-soft-danger">
            <div class="stat-num text-danger" id="statExpiring">23</div>
            <div class="stat-label">Expiring</div>
        </div>
    </div>
    <div class="col-xl-2 col-md-4 col-6">
        <div class="stat-card-mini" style="background:rgba(139,92,246,0.1)">
            <div class="stat-num" style="color:#8b5cf6" id="statSynced">3</div>
            <div class="stat-label">API Synced</div>
        </div>
    </div>
</div>

<!-- Tabs -->
<ul class="nav nav-tabs doc-tabs mb-0" id="docTabs">
    <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab" href="#tabDocuments"><i class="ri-file-list-line me-1"></i>All Documents</a></li>
    <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#tabTemplates"><i class="ri-file-copy-line me-1"></i>Templates</a></li>
    <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#tabForms"><i class="ri-edit-box-line me-1"></i>Online Forms</a></li>
    <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#tabSignatures"><i class="ri-quill-pen-line me-1"></i>Signatures</a></li>
    <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#tabIntegrations"><i class="ri-link me-1"></i>Integrations</a></li>
    <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#tabVault" id="vaultTabLink"><i class="ri-shield-keyhole-line me-1"></i>Client Vaults <span class="badge bg-success-subtle text-success ms-1" id="vaultClientCount">0</span></a></li>
</ul>

<div class="tab-content">
<!-- ==================== TAB 1: ALL DOCUMENTS ==================== -->
<div class="tab-pane fade show active" id="tabDocuments">
<div class="card mb-0" style="border-radius: 0 0 8px 8px;">
    <div class="card-body">
        <div class="row g-2 mb-3">
            <div class="col-md-3"><input type="text" class="form-control form-control-sm" placeholder="Search documents..." id="docSearch"></div>
            <div class="col-md-2">
                <select class="form-select form-select-sm" id="docFilterType">
                    <option value="all">All Types</option>
                    <option>Passport</option><option>Work Permit</option><option>Residence Card</option>
                    <option>Contract</option><option>Application</option><option>Power of Attorney</option>
                    <option>Invoice</option><option>Certificate</option><option>Photo</option><option>Other</option>
                </select>
            </div>
            <div class="col-md-2">
                <select class="form-select form-select-sm" id="docFilterStatus">
                    <option value="all">All Statuses</option>
                    <option>Active</option><option>Expiring</option><option>Expired</option><option>Pending</option><option>Signed</option><option>Archived</option>
                </select>
            </div>
            <div class="col-md-2">
                <select class="form-select form-select-sm" id="docFilterClient">
                    <option value="all">All Clients</option>
                    <option>Oleksandr Petrov</option><option>Maria Ivanova</option><option>Aliaksandr Kazlou</option>
                    <option>Tetiana Sydorenko</option><option>Dmytro Boyko</option><option>Rajesh Kumar</option>
                </select>
            </div>
            <div class="col-md-2">
                <select class="form-select form-select-sm" id="docFilterCase">
                    <option value="all">All Cases</option>
                    <option>Temp. Residence</option><option>Perm. Residence</option><option>Work Permit</option>
                    <option>EU Blue Card</option><option>Citizenship</option><option>Appeal</option>
                </select>
            </div>
            <div class="col-md-1">
                <button class="btn btn-primary btn-sm w-100" data-bs-toggle="modal" data-bs-target="#uploadDocModal"><i class="ri-upload-2-line"></i></button>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" id="docsTable">
                <thead class="table-light">
                    <tr>
                        <th style="width:30px"><input class="form-check-input" type="checkbox" id="checkAll"></th>
                        <th>Document</th>
                        <th>Type</th>
                        <th>Client</th>
                        <th>Case</th>
                        <th>Status</th>
                        <th>Signature</th>
                        <th>Expiry</th>
                        <th>Sync</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="docsBody"></tbody>
            </table>
        </div>
    </div>
    <div class="card-footer">
        <div class="d-flex justify-content-between align-items-center">
            <span class="text-muted fs-13" id="docsFooter">Showing 0 documents</span>
            <div class="d-flex gap-2">
                <button class="btn btn-sm btn-outline-primary" id="bulkDownload" disabled><i class="ri-download-line me-1"></i>Download Selected</button>
                <button class="btn btn-sm btn-outline-warning" id="bulkArchive" disabled><i class="ri-archive-line me-1"></i>Archive Selected</button>
            </div>
        </div>
    </div>
</div>
</div>

<!-- ==================== TAB 2: TEMPLATES ==================== -->
<div class="tab-pane fade" id="tabTemplates">
<div class="card mb-0" style="border-radius: 0 0 8px 8px;">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div class="d-flex gap-2 align-items-center">
                <input type="text" class="form-control form-control-sm" placeholder="Search templates..." style="width:250px" id="templateSearch">
                <select class="form-select form-select-sm" style="width:200px" id="templateFilter">
                    <option value="all">All Case Types</option>
                    <option value="temp_res">Temporary Residence</option>
                    <option value="perm_res">Permanent Residence</option>
                    <option value="work">Work Permit</option>
                    <option value="blue">EU Blue Card</option>
                    <option value="citizen">Citizenship</option>
                    <option value="family">Family Reunification</option>
                    <option value="appeal">Appeal</option>
                    <option value="deport">Deportation Cancel</option>
                    <option value="general">General / Universal</option>
                </select>
            </div>
            <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#uploadTemplateModal"><i class="ri-add-line me-1"></i>Upload Template</button>
        </div>
        <div id="templatesContainer"></div>
    </div>
</div>
</div>

<!-- ==================== TAB 3: ONLINE FORMS ==================== -->
<div class="tab-pane fade" id="tabForms">
<div class="card mb-0" style="border-radius: 0 0 8px 8px;">
    <div class="card-body">
        <div class="row">
            <!-- Left: Form Selection -->
            <div class="col-xl-4 col-lg-5">
                <div class="mb-3">
                    <label class="form-label fw-semibold">1. Select Client</label>
                    <select class="form-select" id="formClient">
                        <option value="" selected disabled>Choose client...</option>
                        <option value="1">Oleksandr Petrov</option>
                        <option value="2">Maria Ivanova</option>
                        <option value="3">Aliaksandr Kazlou</option>
                        <option value="4">Tetiana Sydorenko</option>
                        <option value="5">Dmytro Boyko</option>
                        <option value="6">Rajesh Kumar</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">2. Select Case Type</label>
                    <select class="form-select" id="formCaseType">
                        <option value="" selected disabled>Choose case type...</option>
                        <option value="temp_res">Pobyt czasowy (Temporary Residence)</option>
                        <option value="perm_res">Pobyt stały (Permanent Residence)</option>
                        <option value="work">Zezwolenie na pracę (Work Permit)</option>
                        <option value="blue">Niebieska Karta UE (EU Blue Card)</option>
                        <option value="citizen">Obywatelstwo (Citizenship)</option>
                        <option value="family">Łączenie z rodziną (Family Reunification)</option>
                        <option value="appeal">Odwołanie (Appeal)</option>
                        <option value="deport">Uchylenie zobowiązania (Deportation Cancel)</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">3. Select Document</label>
                    <select class="form-select" id="formTemplate">
                        <option value="" selected disabled>Choose template...</option>
                    </select>
                </div>
                <button class="btn btn-primary w-100 mb-3" id="loadFormBtn"><i class="ri-edit-box-line me-1"></i>Load Form</button>

                <!-- Recent Forms -->
                <div class="card bg-light border-0">
                    <div class="card-body py-3">
                        <h6 class="fs-13 text-muted mb-2"><i class="ri-history-line me-1"></i>Recent Forms</h6>
                        <div class="d-flex flex-column gap-2" id="recentForms">
                            <div class="d-flex justify-content-between align-items-center p-2 bg-white rounded">
                                <div>
                                    <div class="fw-semibold fs-13">Wniosek — Petrov</div>
                                    <div class="fs-12 text-muted">Temp. Residence • 2 hours ago</div>
                                </div>
                                <span class="badge bg-warning-subtle text-warning">Draft</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center p-2 bg-white rounded">
                                <div>
                                    <div class="fw-semibold fs-13">Pełnomocnictwo — Ivanova</div>
                                    <div class="fs-12 text-muted">Work Permit • Yesterday</div>
                                </div>
                                <span class="badge bg-success-subtle text-success">Signed</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center p-2 bg-white rounded">
                                <div>
                                    <div class="fw-semibold fs-13">Oświadczenie — Kumar</div>
                                    <div class="fs-12 text-muted">EU Blue Card • Mar 1</div>
                                </div>
                                <span class="badge bg-info-subtle text-info">Pending Sign</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right: Form Preview / Fill -->
            <div class="col-xl-8 col-lg-7">
                <div id="formPreviewEmpty" class="doc-viewer flex-column">
                    <i class="ri-file-edit-line text-muted" style="font-size:3rem"></i>
                    <p class="text-muted mt-2">Select client, case type and document to start filling the form</p>
                </div>
                <div id="formPreviewFilled" class="d-none">
                    <div class="form-preview-section" id="formContent"></div>
                    <div class="d-flex justify-content-between mt-3">
                        <button class="btn btn-outline-secondary" id="formSaveDraft"><i class="ri-save-line me-1"></i>Save Draft</button>
                        <div class="d-flex gap-2">
                            <button class="btn btn-outline-primary" id="formPrint"><i class="ri-printer-line me-1"></i>Print</button>
                            <button class="btn btn-outline-info" id="formDownloadPdf"><i class="ri-file-pdf-line me-1"></i>Download PDF</button>
                            <button class="btn btn-primary" id="formSignBtn"><i class="ri-quill-pen-line me-1"></i>Sign Document</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

<!-- ==================== TAB 4: SIGNATURES ==================== -->
<div class="tab-pane fade" id="tabSignatures">
<div class="card mb-0" style="border-radius: 0 0 8px 8px;">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div class="filter-pills d-flex gap-2">
                <button class="btn btn-sm btn-primary active sig-filter" data-filter="all">All</button>
                <button class="btn btn-sm btn-outline-warning sig-filter" data-filter="pending">Pending <span class="badge bg-warning text-dark ms-1">7</span></button>
                <button class="btn btn-sm btn-outline-success sig-filter" data-filter="signed">Signed</button>
                <button class="btn btn-sm btn-outline-danger sig-filter" data-filter="expired">Expired</button>
            </div>
            <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#requestSignModal"><i class="ri-send-plane-line me-1"></i>Request Signature</button>
        </div>
        <div id="signaturesContainer"></div>
    </div>
</div>
</div>

<!-- ==================== TAB 5: INTEGRATIONS ==================== -->
<div class="tab-pane fade" id="tabIntegrations">
<div class="card mb-0" style="border-radius: 0 0 8px 8px;">
    <div class="card-body">
        <div class="row g-4">
            <!-- mObywatel -->
            <div class="col-md-4">
                <div class="integration-card">
                    <div class="int-icon bg-danger-subtle text-danger">
                        <i class="ri-government-line"></i>
                    </div>
                    <h6 class="fw-bold">mObywatel</h6>
                    <p class="text-muted fs-13">Aplikacja mObywatel — weryfikacja tożsamości, dokumenty cyfrowe</p>
                    <div class="mb-3">
                        <span class="client-sync-badge bg-warning-subtle text-warning"><i class="ri-loader-4-line me-1"></i>Configuration Required</span>
                    </div>
                    <div class="d-grid gap-2">
                        <button class="btn btn-sm btn-outline-danger" id="mobywatelConnect"><i class="ri-link me-1"></i>Configure API</button>
                        <button class="btn btn-sm btn-outline-secondary" disabled><i class="ri-refresh-line me-1"></i>Sync Documents</button>
                    </div>
                    <div class="mt-3 text-start">
                        <h6 class="fs-12 text-muted mb-2">API Capabilities:</h6>
                        <ul class="list-unstyled fs-12 text-muted">
                            <li><i class="ri-check-line text-success me-1"></i>Identity verification (PESEL)</li>
                            <li><i class="ri-check-line text-success me-1"></i>Digital document access</li>
                            <li><i class="ri-check-line text-success me-1"></i>Residence card status check</li>
                            <li><i class="ri-check-line text-success me-1"></i>Document validity check</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Profil Zaufany -->
            <div class="col-md-4">
                <div class="integration-card">
                    <div class="int-icon bg-primary-subtle text-primary">
                        <i class="ri-shield-user-line"></i>
                    </div>
                    <h6 class="fw-bold">Profil Zaufany</h6>
                    <p class="text-muted fs-13">ePUAP / login.gov.pl — trusted profile for digital signatures</p>
                    <div class="mb-3">
                        <span class="client-sync-badge bg-warning-subtle text-warning"><i class="ri-loader-4-line me-1"></i>Configuration Required</span>
                    </div>
                    <div class="d-grid gap-2">
                        <button class="btn btn-sm btn-outline-primary" id="profilZaufanyConnect"><i class="ri-link me-1"></i>Configure API</button>
                        <button class="btn btn-sm btn-outline-secondary" disabled><i class="ri-quill-pen-line me-1"></i>Sign via PZ</button>
                    </div>
                    <div class="mt-3 text-start">
                        <h6 class="fs-12 text-muted mb-2">API Capabilities:</h6>
                        <ul class="list-unstyled fs-12 text-muted">
                            <li><i class="ri-check-line text-success me-1"></i>Qualified digital signature</li>
                            <li><i class="ri-check-line text-success me-1"></i>Document authentication</li>
                            <li><i class="ri-check-line text-success me-1"></i>ePUAP submission</li>
                            <li><i class="ri-check-line text-success me-1"></i>Timestamp verification</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Client Portal -->
            <div class="col-md-4">
                <div class="integration-card">
                    <div class="int-icon bg-success-subtle text-success">
                        <i class="ri-user-shared-line"></i>
                    </div>
                    <h6 class="fw-bold">Client Portal Sync</h6>
                    <p class="text-muted fs-13">WinCase Client Cabinet — bidirectional document sync</p>
                    <div class="mb-3">
                        <span class="client-sync-badge bg-success-subtle text-success"><i class="ri-check-line me-1"></i>Active</span>
                    </div>
                    <div class="d-grid gap-2">
                        <button class="btn btn-sm btn-outline-success" id="clientPortalSync"><i class="ri-refresh-line me-1"></i>Sync Now</button>
                        <button class="btn btn-sm btn-outline-secondary" id="clientPortalSettings"><i class="ri-settings-3-line me-1"></i>Settings</button>
                    </div>
                    <div class="mt-3 text-start">
                        <h6 class="fs-12 text-muted mb-2">Sync Status:</h6>
                        <ul class="list-unstyled fs-12 text-muted">
                            <li><i class="ri-check-double-line text-success me-1"></i>Last sync: 5 min ago</li>
                            <li><i class="ri-check-double-line text-success me-1"></i>Documents synced: 847</li>
                            <li><i class="ri-check-double-line text-success me-1"></i>Pending uploads: 3</li>
                            <li><i class="ri-check-double-line text-success me-1"></i>Client access: 6 clients</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- API Configuration -->
        <div class="row g-4 mt-2">
            <div class="col-md-6">
                <div class="card bg-light border-0">
                    <div class="card-header bg-transparent">
                        <h6 class="card-title mb-0 fs-13"><i class="ri-key-2-line me-1"></i>API Keys & Configuration</h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label fs-12">mObywatel API Key</label>
                            <div class="input-group input-group-sm">
                                <input type="password" class="form-control" value="mob_sk_live_xxxxxxxxxxxx" id="mobApiKey">
                                <button class="btn btn-outline-secondary" onclick="toggleApiKey('mobApiKey')"><i class="ri-eye-line"></i></button>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fs-12">Profil Zaufany Client ID</label>
                            <div class="input-group input-group-sm">
                                <input type="password" class="form-control" value="pz_client_xxxxxxxx" id="pzClientId">
                                <button class="btn btn-outline-secondary" onclick="toggleApiKey('pzClientId')"><i class="ri-eye-line"></i></button>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fs-12">Profil Zaufany Secret</label>
                            <div class="input-group input-group-sm">
                                <input type="password" class="form-control" value="pz_secret_xxxxxxxx" id="pzSecret">
                                <button class="btn btn-outline-secondary" onclick="toggleApiKey('pzSecret')"><i class="ri-eye-line"></i></button>
                            </div>
                        </div>
                        <button class="btn btn-sm btn-primary w-100"><i class="ri-save-line me-1"></i>Save Configuration</button>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card bg-light border-0">
                    <div class="card-header bg-transparent">
                        <h6 class="card-title mb-0 fs-13"><i class="ri-exchange-line me-1"></i>Sync Settings</h6>
                    </div>
                    <div class="card-body">
                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" checked id="syncAuto">
                            <label class="form-check-label fs-13" for="syncAuto">Auto-sync every 15 minutes</label>
                        </div>
                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" checked id="syncNotify">
                            <label class="form-check-label fs-13" for="syncNotify">Notify on new client uploads</label>
                        </div>
                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" id="syncPz">
                            <label class="form-check-label fs-13" for="syncPz">Auto-verify via Profil Zaufany</label>
                        </div>
                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" checked id="syncMob">
                            <label class="form-check-label fs-13" for="syncMob">Check document validity via mObywatel</label>
                        </div>
                        <div class="mb-2">
                            <label class="form-label fs-12">Webhook URL (Client Portal)</label>
                            <input type="text" class="form-control form-control-sm" value="https://api.wincase.eu/webhooks/documents" readonly>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
</div>

<!-- ============ UPLOAD DOC MODAL ============ -->
<div class="modal fade" id="uploadDocModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="ri-upload-2-line me-2"></i>Upload Document</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-12">
                        <div class="border border-2 border-dashed rounded-3 p-4 text-center" id="dropZone" style="cursor:pointer">
                            <i class="ri-upload-cloud-2-line text-muted" style="font-size:2.5rem"></i>
                            <h6 class="mt-2 mb-1">Drop files here or click to upload</h6>
                            <p class="text-muted fs-13 mb-0">PDF, DOCX, JPG, PNG up to 10MB</p>
                            <input type="file" class="d-none" id="fileInput" accept=".pdf,.docx,.jpg,.jpeg,.png" multiple>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Document Type <span class="text-danger">*</span></label>
                        <select class="form-select" id="uploadDocType">
                            <option selected disabled value="">Select type...</option>
                            <option>Passport</option><option>Work Permit</option><option>Residence Card</option>
                            <option>Contract</option><option>Application</option><option>Power of Attorney</option>
                            <option>Certificate</option><option>Invoice</option><option>Photo</option><option>Other</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Client <span class="text-danger">*</span></label>
                        <select class="form-select" id="uploadClient">
                            <option selected disabled value="">Select client...</option>
                            <option>Oleksandr Petrov</option><option>Maria Ivanova</option><option>Aliaksandr Kazlou</option>
                            <option>Tetiana Sydorenko</option><option>Dmytro Boyko</option><option>Rajesh Kumar</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Related Case</label>
                        <select class="form-select" id="uploadCase">
                            <option selected value="">None</option>
                            <option>#WC-2026-0147 — Petrov</option><option>#WC-2026-0146 — Ivanova</option>
                            <option>#WC-2026-0139 — Kazlou</option><option>#WC-2026-0152 — Sydorenko</option>
                            <option>#WC-2026-0133 — Boyko</option><option>#WC-2026-0155 — Kumar</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Expiry Date</label>
                        <input type="date" class="form-control" id="uploadExpiry">
                    </div>
                    <div class="col-12">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="uploadRequireSig">
                            <label class="form-check-label" for="uploadRequireSig">Require client signature</label>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" checked id="uploadSyncPortal">
                            <label class="form-check-label" for="uploadSyncPortal">Sync to Client Portal</label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="uploadSaveBtn"><i class="ri-upload-2-line me-1"></i>Upload</button>
            </div>
        </div>
    </div>
</div>

<!-- ============ UPLOAD TEMPLATE MODAL ============ -->
<div class="modal fade" id="uploadTemplateModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="ri-file-add-line me-2"></i>Upload Template</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-12">
                        <div class="border border-2 border-dashed rounded-3 p-3 text-center" style="cursor:pointer">
                            <i class="ri-upload-cloud-2-line text-muted fs-3"></i>
                            <p class="text-muted fs-13 mb-0">Upload template file (PDF, DOCX)</p>
                        </div>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Template Name (PL) <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" placeholder="e.g. Wniosek o udzielenie zezwolenia...">
                    </div>
                    <div class="col-12">
                        <label class="form-label">Template Name (EN)</label>
                        <input type="text" class="form-control" placeholder="e.g. Application for temporary residence...">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Case Type <span class="text-danger">*</span></label>
                        <select class="form-select">
                            <option selected disabled value="">Select...</option>
                            <option value="temp_res">Temporary Residence</option>
                            <option value="perm_res">Permanent Residence</option>
                            <option value="work">Work Permit</option>
                            <option value="blue">EU Blue Card</option>
                            <option value="citizen">Citizenship</option>
                            <option value="family">Family Reunification</option>
                            <option value="appeal">Appeal</option>
                            <option value="deport">Deportation Cancel</option>
                            <option value="general">General</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Template Type</label>
                        <select class="form-select">
                            <option>Application Form</option>
                            <option>Attachment</option>
                            <option>Power of Attorney</option>
                            <option>Declaration</option>
                            <option>Contract</option>
                            <option>Other</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" checked>
                            <label class="form-check-label">Enable as online fillable form</label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary"><i class="ri-check-line me-1"></i>Save Template</button>
            </div>
        </div>
    </div>
</div>

<!-- ============ SIGNATURE MODAL ============ -->
<div class="modal fade" id="signDocModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="ri-quill-pen-line me-2"></i>Sign Document</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-12">
                        <div class="alert alert-info fs-13 mb-3">
                            <i class="ri-information-line me-1"></i>
                            Signing this document is legally binding. Choose your signature method below.
                        </div>
                    </div>
                    <div class="col-12">
                        <h6 class="fs-13 text-muted mb-2">Document: <span class="fw-bold text-dark" id="signDocName"></span></h6>
                        <h6 class="fs-13 text-muted">Client: <span class="fw-bold text-dark" id="signDocClient"></span></h6>
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold">Signature Method</label>
                        <div class="row g-2">
                            <div class="col-md-4">
                                <div class="border rounded-3 p-3 text-center sig-method-card active" data-method="draw" style="cursor:pointer">
                                    <i class="ri-quill-pen-line fs-3 text-primary d-block mb-1"></i>
                                    <div class="fw-semibold fs-13">Draw Signature</div>
                                    <div class="text-muted fs-12">Sign with mouse/touch</div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="border rounded-3 p-3 text-center sig-method-card" data-method="profil_zaufany" style="cursor:pointer">
                                    <i class="ri-shield-user-line fs-3 text-primary d-block mb-1"></i>
                                    <div class="fw-semibold fs-13">Profil Zaufany</div>
                                    <div class="text-muted fs-12">Qualified e-signature</div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="border rounded-3 p-3 text-center sig-method-card" data-method="mobywatel" style="cursor:pointer">
                                    <i class="ri-government-line fs-3 text-danger d-block mb-1"></i>
                                    <div class="fw-semibold fs-13">mObywatel</div>
                                    <div class="text-muted fs-12">Digital identity</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Draw signature area -->
                    <div class="col-12" id="sigDrawArea">
                        <label class="form-label">Draw your signature below:</label>
                        <div class="sig-pad-area" id="signaturePad">
                            <canvas id="sigCanvas" width="700" height="150" style="width:100%;height:150px;"></canvas>
                        </div>
                        <div class="d-flex justify-content-between mt-2">
                            <button class="btn btn-sm btn-outline-secondary" id="clearSigBtn"><i class="ri-eraser-line me-1"></i>Clear</button>
                            <div class="d-flex gap-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="sigLegalConsent">
                                    <label class="form-check-label fs-12" for="sigLegalConsent">I confirm this is my legal signature</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Profil Zaufany area -->
                    <div class="col-12 d-none" id="sigPzArea">
                        <div class="text-center py-4">
                            <i class="ri-shield-user-line text-primary" style="font-size:3rem"></i>
                            <p class="mt-2 mb-3">You will be redirected to login.gov.pl to sign this document with Profil Zaufany.</p>
                            <button class="btn btn-primary" id="pzSignBtn"><i class="ri-external-link-line me-1"></i>Sign via Profil Zaufany</button>
                        </div>
                    </div>
                    <!-- mObywatel area -->
                    <div class="col-12 d-none" id="sigMobArea">
                        <div class="text-center py-4">
                            <i class="ri-government-line text-danger" style="font-size:3rem"></i>
                            <p class="mt-2 mb-3">Verify your identity via mObywatel app and sign the document digitally.</p>
                            <button class="btn btn-danger" id="mobSignBtn"><i class="ri-smartphone-line me-1"></i>Send to mObywatel</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-success" id="confirmSignBtn"><i class="ri-check-double-line me-1"></i>Confirm & Sign</button>
            </div>
        </div>
    </div>
</div>

<!-- ============ REQUEST SIGNATURE MODAL ============ -->
<div class="modal fade" id="requestSignModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="ri-send-plane-line me-2"></i>Request Signature</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label">Document</label>
                        <select class="form-select">
                            <option selected disabled>Select document...</option>
                            <option>Pełnomocnictwo — Petrov</option>
                            <option>Wniosek — Ivanova</option>
                            <option>Umowa — Kumar</option>
                            <option>Oświadczenie — Sydorenko</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Send to Client via</label>
                        <div class="d-flex gap-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" checked id="sendEmail">
                                <label class="form-check-label" for="sendEmail">Email</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="sendSms">
                                <label class="form-check-label" for="sendSms">SMS</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" checked id="sendPortal">
                                <label class="form-check-label" for="sendPortal">Client Portal</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Signature Method</label>
                        <select class="form-select">
                            <option>Any method (client choice)</option>
                            <option>Draw signature only</option>
                            <option>Profil Zaufany required</option>
                            <option>mObywatel required</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Deadline</label>
                        <input type="date" class="form-control">
                    </div>
                    <div class="col-12">
                        <label class="form-label">Message to client</label>
                        <textarea class="form-control" rows="2" placeholder="Please sign this document..."></textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="sendSigRequest"><i class="ri-send-plane-line me-1"></i>Send Request</button>
            </div>
        </div>
    </div>
</div>

<!-- ============ VIEW DOC MODAL ============ -->
<div class="modal fade" id="viewDocModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="ri-file-text-line me-2"></i><span id="viewDocTitle"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-8">
                        <div class="doc-viewer" id="docViewerArea">
                            <div class="text-center">
                                <i class="ri-file-pdf-line text-danger" style="font-size:4rem"></i>
                                <p class="mt-2 text-muted">Document Preview</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <h6 class="fs-13 text-muted mb-2">Document Info</h6>
                        <div class="mb-2"><span class="fs-12 text-muted">Type:</span><br><span class="fw-semibold" id="viewDocType"></span></div>
                        <div class="mb-2"><span class="fs-12 text-muted">Client:</span><br><span class="fw-semibold" id="viewDocClient"></span></div>
                        <div class="mb-2"><span class="fs-12 text-muted">Case:</span><br><span class="fw-semibold" id="viewDocCase"></span></div>
                        <div class="mb-2"><span class="fs-12 text-muted">Status:</span><br><span id="viewDocStatus"></span></div>
                        <div class="mb-2"><span class="fs-12 text-muted">Signature:</span><br><span id="viewDocSig"></span></div>
                        <div class="mb-2"><span class="fs-12 text-muted">Uploaded:</span><br><span class="fw-semibold" id="viewDocUploaded"></span></div>
                        <div class="mb-2"><span class="fs-12 text-muted">Expiry:</span><br><span class="fw-semibold" id="viewDocExpiry"></span></div>
                        <div class="mb-3"><span class="fs-12 text-muted">Sync:</span><br><span id="viewDocSync"></span></div>
                        <div class="d-grid gap-2">
                            <button class="btn btn-sm btn-outline-primary"><i class="ri-download-line me-1"></i>Download</button>
                            <button class="btn btn-sm btn-outline-info view-sign-btn"><i class="ri-quill-pen-line me-1"></i>Sign</button>
                            <button class="btn btn-sm btn-outline-success"><i class="ri-send-plane-line me-1"></i>Send to Client</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ==================== TAB 6: CLIENT VAULTS ==================== -->
<div class="tab-pane fade" id="tabVault">
<div class="card mb-0" style="border-radius: 0 0 8px 8px;">
    <div class="card-body">
        <!-- Vault Header -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div class="d-flex gap-2 align-items-center">
                <input type="text" class="form-control form-control-sm" placeholder="Search clients..." style="width:250px" id="vaultSearch">
                <select class="form-select form-select-sm" style="width:180px" id="vaultFilterStatus">
                    <option value="all">All Statuses</option>
                    <option value="pending_review">Pending Review</option>
                    <option value="approved">Approved</option>
                    <option value="rejected">Rejected</option>
                </select>
            </div>
            <div class="d-flex gap-2">
                <button class="btn btn-sm btn-outline-success" onclick="loadVaultData()"><i class="ri-refresh-line me-1"></i>Refresh</button>
            </div>
        </div>

        <!-- Vault Folders (Accordion by Client) -->
        <div id="vaultAccordion" class="accordion">
            <div class="text-center py-5 text-muted" id="vaultLoading">
                <div class="spinner-border spinner-border-sm me-2" role="status"></div>Loading client vaults...
            </div>
        </div>

        <!-- Empty state -->
        <div class="text-center py-5 d-none" id="vaultEmpty">
            <i class="ri-folder-shield-2-line" style="font-size:3rem;color:#ccc;"></i>
            <p class="text-muted mt-2">No encrypted documents found. Client uploads will appear here.</p>
        </div>
    </div>
</div>
</div>

<!-- Vault Document Preview Modal -->
<div class="modal fade" id="vaultPreviewModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title"><i class="ri-shield-keyhole-line text-success me-2"></i><span id="vaultPreviewTitle">Document Preview</span></h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0" style="min-height:400px;">
                <div class="text-center py-5" id="vaultPreviewLoading">
                    <div class="spinner-border text-success"></div>
                    <p class="text-muted mt-2">Decrypting document...</p>
                </div>
                <iframe id="vaultPreviewFrame" style="width:100%;height:500px;border:none;display:none;"></iframe>
                <img id="vaultPreviewImg" style="max-width:100%;display:none;margin:0 auto;" class="d-block">
            </div>
            <div class="modal-footer">
                <span class="me-auto">
                    <span class="badge bg-success-subtle text-success"><i class="ri-lock-line me-1"></i>Encrypted (AES-256)</span>
                    <span class="text-muted ms-2 fs-12" id="vaultPreviewMeta"></span>
                </span>
                <a href="#" class="btn btn-sm btn-outline-primary" id="vaultDownloadBtn"><i class="ri-download-line me-1"></i>Download (Decrypted)</a>
                <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Vault Status Change Modal -->
<div class="modal fade" id="vaultStatusModal" tabindex="-1">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title">Change Status</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="vaultStatusDocId">
                <label class="form-label fw-semibold">Document Status</label>
                <select class="form-select" id="vaultStatusSelect">
                    <option value="pending_review">Pending Review</option>
                    <option value="approved">Approved</option>
                    <option value="rejected">Rejected</option>
                    <option value="needs_correction">Needs Correction</option>
                </select>
                <label class="form-label fw-semibold mt-3">Note (optional)</label>
                <textarea class="form-control" id="vaultStatusNote" rows="2" placeholder="Add a note for the client..."></textarea>
            </div>
            <div class="modal-footer">
                <button class="btn btn-sm btn-primary" onclick="saveVaultStatus()">Save</button>
            </div>
        </div>
    </div>
</div>

</div><!-- /tab-content -->
@endsection

@section('js')
<script>
document.addEventListener('DOMContentLoaded', function() {

    // ========== DATA ==========
    var TYPE_COLORS = {
        'Passport': '#5865F2', 'Work Permit': '#0dcaf0', 'Residence Card': '#198754',
        'Contract': '#8b5cf6', 'Application': '#f97316', 'Power of Attorney': '#dc3545',
        'Certificate': '#0d6efd', 'Invoice': '#6c757d', 'Photo': '#20c997', 'Other': '#adb5bd'
    };
    var STATUS_COLORS = {
        'Active': 'success', 'Expiring': 'warning', 'Expired': 'danger',
        'Pending': 'info', 'Signed': 'primary', 'Archived': 'secondary', 'Draft': 'light'
    };
    var SIG_COLORS = { 'Signed': 'success', 'Pending': 'warning', 'Not Required': 'secondary', 'Expired': 'danger' };

    var allDocs = [
        { id:1, name:'Passport_Petrov_O.pdf', type:'Passport', client:'Oleksandr Petrov', case:'#WC-2026-0147', caseType:'Temp. Residence', status:'Active', sig:'Signed', expiry:'2028-12-15', uploaded:'2026-01-15', uploader:'Anna Kowalska', sync:'portal', syncMob:true },
        { id:2, name:'WorkPermit_Kazlou_A.pdf', type:'Work Permit', client:'Aliaksandr Kazlou', case:'#WC-2026-0139', caseType:'Perm. Residence', status:'Expiring', sig:'Signed', expiry:'2026-03-10', uploaded:'2025-11-10', uploader:'Piotr Nowak', sync:'portal,mob', syncMob:true },
        { id:3, name:'Application_Ivanova_M.docx', type:'Application', client:'Maria Ivanova', case:'#WC-2026-0146', caseType:'Work Permit', status:'Pending', sig:'Pending', expiry:'', uploaded:'2026-02-20', uploader:'Anna Kowalska', sync:'portal' },
        { id:4, name:'ResidenceCard_Boyko_D.pdf', type:'Residence Card', client:'Dmytro Boyko', case:'#WC-2026-0133', caseType:'Deport. Cancel', status:'Expired', sig:'Signed', expiry:'2026-02-01', uploaded:'2024-02-01', uploader:'Marek Zieliński', sync:'portal,mob' },
        { id:5, name:'Pelnomocnictwo_Petrov.pdf', type:'Power of Attorney', client:'Oleksandr Petrov', case:'#WC-2026-0147', caseType:'Temp. Residence', status:'Active', sig:'Signed', expiry:'', uploaded:'2026-01-15', uploader:'Anna Kowalska', sync:'portal,pz' },
        { id:6, name:'Contract_Kumar_R.pdf', type:'Contract', client:'Rajesh Kumar', case:'#WC-2026-0155', caseType:'EU Blue Card', status:'Active', sig:'Signed', expiry:'', uploaded:'2026-02-25', uploader:'Katarzyna Wiśniewska', sync:'portal' },
        { id:7, name:'Invoice_2026_0042.pdf', type:'Invoice', client:'Maria Ivanova', case:'#WC-2026-0146', caseType:'Work Permit', status:'Active', sig:'Not Required', expiry:'', uploaded:'2026-02-15', uploader:'System', sync:'portal' },
        { id:8, name:'Photo_35x45_Sydorenko.jpg', type:'Photo', client:'Tetiana Sydorenko', case:'#WC-2026-0152', caseType:'Appeal', status:'Active', sig:'Not Required', expiry:'', uploaded:'2026-02-20', uploader:'Marek Zieliński', sync:'portal' },
        { id:9, name:'Wniosek_Petrov_TRC.pdf', type:'Application', client:'Oleksandr Petrov', case:'#WC-2026-0147', caseType:'Temp. Residence', status:'Signed', sig:'Signed', expiry:'', uploaded:'2026-01-15', uploader:'Anna Kowalska', sync:'portal,pz' },
        { id:10, name:'Zaswiadczenie_Kumar.pdf', type:'Certificate', client:'Rajesh Kumar', case:'#WC-2026-0155', caseType:'EU Blue Card', status:'Pending', sig:'Pending', expiry:'', uploaded:'2026-03-01', uploader:'Katarzyna Wiśniewska', sync:'portal' },
        { id:11, name:'Pelnomocnictwo_Sydorenko.pdf', type:'Power of Attorney', client:'Tetiana Sydorenko', case:'#WC-2026-0152', caseType:'Appeal', status:'Active', sig:'Pending', expiry:'', uploaded:'2026-02-20', uploader:'Marek Zieliński', sync:'' },
        { id:12, name:'Odwolanie_Sydorenko.pdf', type:'Application', client:'Tetiana Sydorenko', case:'#WC-2026-0152', caseType:'Appeal', status:'Active', sig:'Signed', expiry:'', uploaded:'2026-02-20', uploader:'Marek Zieliński', sync:'portal,pz' },
    ];

    // ========== TAB 1: DOCUMENTS TABLE ==========
    function renderDocs() {
        var search = document.getElementById('docSearch').value.toLowerCase();
        var fType = document.getElementById('docFilterType').value;
        var fStatus = document.getElementById('docFilterStatus').value;
        var fClient = document.getElementById('docFilterClient').value;

        var filtered = allDocs.filter(function(d) {
            if (search && d.name.toLowerCase().indexOf(search) === -1 && d.client.toLowerCase().indexOf(search) === -1) return false;
            if (fType !== 'all' && d.type !== fType) return false;
            if (fStatus !== 'all' && d.status !== fStatus) return false;
            if (fClient !== 'all' && d.client !== fClient) return false;
            return true;
        });

        var icons = { 'pdf': 'ri-file-pdf-2-line text-danger', 'docx': 'ri-file-word-line text-primary', 'jpg': 'ri-image-line text-success', 'xlsx': 'ri-file-excel-line text-success' };
        var html = '';
        filtered.forEach(function(d) {
            var ext = d.name.split('.').pop();
            var icon = icons[ext] || 'ri-file-line text-secondary';
            var tc = TYPE_COLORS[d.type] || '#6c757d';
            var rowClass = d.status === 'Expired' ? 'table-danger bg-opacity-10' : (d.status === 'Expiring' ? 'table-warning bg-opacity-10' : '');
            var syncBadges = '';
            if (d.sync.indexOf('portal') !== -1) syncBadges += '<span class="badge bg-success-subtle text-success me-1 fs-10">Portal</span>';
            if (d.sync.indexOf('pz') !== -1) syncBadges += '<span class="badge bg-primary-subtle text-primary me-1 fs-10">PZ</span>';
            if (d.sync.indexOf('mob') !== -1) syncBadges += '<span class="badge bg-danger-subtle text-danger fs-10">mOb</span>';
            if (!syncBadges) syncBadges = '<span class="text-muted fs-12">—</span>';

            html += '<tr class="' + rowClass + '" data-doc-id="' + d.id + '">' +
                '<td><input class="form-check-input doc-check" type="checkbox" data-id="' + d.id + '"></td>' +
                '<td><div class="d-flex align-items-center gap-2"><i class="' + icon + ' fs-20"></i><a href="#" class="fw-semibold text-body action-view">' + d.name + '</a></div></td>' +
                '<td><span class="badge" style="background:' + tc + ';color:#fff">' + d.type + '</span></td>' +
                '<td class="fs-13">' + d.client + '</td>' +
                '<td><span class="text-muted fs-12">' + d.case + '</span></td>' +
                '<td><span class="badge bg-' + STATUS_COLORS[d.status] + '-subtle text-' + STATUS_COLORS[d.status] + '">' + d.status + '</span></td>' +
                '<td><span class="badge bg-' + SIG_COLORS[d.sig] + '-subtle text-' + SIG_COLORS[d.sig] + '">' + d.sig + '</span></td>' +
                '<td class="fs-12 ' + (d.status === 'Expired' || d.status === 'Expiring' ? 'text-danger fw-semibold' : 'text-muted') + '">' + (d.expiry || '—') + '</td>' +
                '<td>' + syncBadges + '</td>' +
                '<td><div class="dropdown"><button class="btn btn-sm btn-subtle-secondary" data-bs-toggle="dropdown"><i class="ri-more-2-fill"></i></button>' +
                '<ul class="dropdown-menu"><li><a class="dropdown-item action-view" href="#"><i class="ri-eye-line me-2"></i>View</a></li>' +
                '<li><a class="dropdown-item" href="#"><i class="ri-download-line me-2"></i>Download</a></li>' +
                '<li><a class="dropdown-item action-sign" href="#"><i class="ri-quill-pen-line me-2"></i>Sign</a></li>' +
                '<li><a class="dropdown-item" href="#"><i class="ri-send-plane-line me-2"></i>Send to Client</a></li>' +
                '<li><a class="dropdown-item" href="#"><i class="ri-refresh-line me-2"></i>Sync</a></li>' +
                '<li><hr class="dropdown-divider"></li>' +
                '<li><a class="dropdown-item text-warning" href="#"><i class="ri-archive-line me-2"></i>Archive</a></li></ul></div></td></tr>';
        });
        document.getElementById('docsBody').innerHTML = html;
        document.getElementById('docsFooter').textContent = 'Showing ' + filtered.length + ' of ' + allDocs.length + ' documents';

        // Events
        document.querySelectorAll('.action-view').forEach(function(a) {
            a.addEventListener('click', function(e) {
                e.preventDefault();
                var row = this.closest('tr');
                var docId = parseInt(row.dataset.docId);
                showViewDoc(docId);
            });
        });
        document.querySelectorAll('.action-sign').forEach(function(a) {
            a.addEventListener('click', function(e) {
                e.preventDefault();
                var row = this.closest('tr');
                var docId = parseInt(row.dataset.docId);
                var doc = allDocs.find(function(d) { return d.id === docId; });
                if (doc) openSignModal(doc.name, doc.client);
            });
        });
    }
    renderDocs();

    // Filters
    ['docSearch','docFilterType','docFilterStatus','docFilterClient','docFilterCase'].forEach(function(id) {
        var el = document.getElementById(id);
        if (el) el.addEventListener(el.tagName === 'INPUT' ? 'input' : 'change', renderDocs);
    });

    // Bulk checkboxes
    document.getElementById('checkAll').addEventListener('change', function() {
        var checks = document.querySelectorAll('.doc-check');
        var checked = this.checked;
        checks.forEach(function(c) { c.checked = checked; });
        updateBulkBtns();
    });
    document.getElementById('docsBody').addEventListener('change', function(e) {
        if (e.target.classList.contains('doc-check')) updateBulkBtns();
    });
    function updateBulkBtns() {
        var any = document.querySelectorAll('.doc-check:checked').length > 0;
        document.getElementById('bulkDownload').disabled = !any;
        document.getElementById('bulkArchive').disabled = !any;
    }

    // View doc modal
    function showViewDoc(docId) {
        var d = allDocs.find(function(x) { return x.id === docId; });
        if (!d) return;
        document.getElementById('viewDocTitle').textContent = d.name;
        document.getElementById('viewDocType').textContent = d.type;
        document.getElementById('viewDocClient').textContent = d.client;
        document.getElementById('viewDocCase').textContent = d.case;
        document.getElementById('viewDocStatus').innerHTML = '<span class="badge bg-' + STATUS_COLORS[d.status] + '-subtle text-' + STATUS_COLORS[d.status] + '">' + d.status + '</span>';
        document.getElementById('viewDocSig').innerHTML = '<span class="badge bg-' + SIG_COLORS[d.sig] + '-subtle text-' + SIG_COLORS[d.sig] + '">' + d.sig + '</span>';
        document.getElementById('viewDocUploaded').textContent = d.uploaded;
        document.getElementById('viewDocExpiry').textContent = d.expiry || 'N/A';
        var syncHtml = '';
        if (d.sync.indexOf('portal') !== -1) syncHtml += '<span class="badge bg-success-subtle text-success me-1">Client Portal ✓</span>';
        if (d.sync.indexOf('pz') !== -1) syncHtml += '<span class="badge bg-primary-subtle text-primary me-1">Profil Zaufany ✓</span>';
        if (d.sync.indexOf('mob') !== -1) syncHtml += '<span class="badge bg-danger-subtle text-danger me-1">mObywatel ✓</span>';
        document.getElementById('viewDocSync').innerHTML = syncHtml || '<span class="text-muted fs-12">Not synced</span>';
        new bootstrap.Modal(document.getElementById('viewDocModal')).show();
    }

    // View modal sign btn
    document.querySelector('.view-sign-btn').addEventListener('click', function() {
        var name = document.getElementById('viewDocTitle').textContent;
        var client = document.getElementById('viewDocClient').textContent;
        bootstrap.Modal.getInstance(document.getElementById('viewDocModal')).hide();
        setTimeout(function() { openSignModal(name, client); }, 300);
    });

    // ========== TAB 2: TEMPLATES ==========
    var TEMPLATES = {
        temp_res: {
            label: 'Pobyt Czasowy (Temporary Residence)', color: '#5865F2',
            items: [
                { name: 'Wniosek o udzielenie zezwolenia na pobyt czasowy', nameEn: 'Application for temporary residence permit', icon: 'ri-file-text-line', fillable: true },
                { name: 'Załącznik nr 1 do wniosku', nameEn: 'Attachment No. 1 — employer declaration', icon: 'ri-attachment-line', fillable: true },
                { name: 'Załącznik nr 2 — wysokie kwalifikacje', nameEn: 'Attachment No. 2 — high qualifications', icon: 'ri-attachment-line', fillable: true },
                { name: 'Pełnomocnictwo', nameEn: 'Power of Attorney', icon: 'ri-shield-user-line', fillable: true },
                { name: 'Oświadczenie o zamieszkaniu', nameEn: 'Declaration of residence', icon: 'ri-home-line', fillable: true },
                { name: 'Zgoda na przetwarzanie danych', nameEn: 'GDPR consent form', icon: 'ri-lock-line', fillable: true },
            ]
        },
        perm_res: {
            label: 'Pobyt Stały (Permanent Residence)', color: '#8b5cf6',
            items: [
                { name: 'Wniosek o udzielenie zezwolenia na pobyt stały', nameEn: 'Application for permanent residence', icon: 'ri-file-text-line', fillable: true },
                { name: 'Zaświadczenie o znajomości języka polskiego', nameEn: 'Polish language proficiency certificate', icon: 'ri-translate-2', fillable: false },
                { name: 'Pełnomocnictwo', nameEn: 'Power of Attorney', icon: 'ri-shield-user-line', fillable: true },
                { name: 'Oświadczenie o niekaralności', nameEn: 'Criminal record declaration', icon: 'ri-shield-check-line', fillable: true },
            ]
        },
        work: {
            label: 'Zezwolenie na Pracę (Work Permit)', color: '#0dcaf0',
            items: [
                { name: 'Wniosek o wydanie zezwolenia na pracę Typ A', nameEn: 'Work permit application Type A', icon: 'ri-file-text-line', fillable: true },
                { name: 'Oświadczenie pracodawcy', nameEn: 'Employer declaration', icon: 'ri-building-line', fillable: true },
                { name: 'Informacja starosty (test rynku pracy)', nameEn: 'Starosta information (labor market test)', icon: 'ri-bar-chart-line', fillable: false },
                { name: 'Umowa o pracę / zlecenia', nameEn: 'Employment/civil contract template', icon: 'ri-file-shield-line', fillable: true },
                { name: 'Pełnomocnictwo', nameEn: 'Power of Attorney', icon: 'ri-shield-user-line', fillable: true },
            ]
        },
        blue: {
            label: 'Niebieska Karta UE (EU Blue Card)', color: '#0d6efd',
            items: [
                { name: 'Wniosek o Niebieską Kartę UE', nameEn: 'EU Blue Card application', icon: 'ri-file-text-line', fillable: true },
                { name: 'Potwierdzenie kwalifikacji zawodowych', nameEn: 'Professional qualifications confirmation', icon: 'ri-graduation-cap-line', fillable: false },
                { name: 'Oświadczenie pracodawcy — wynagrodzenie', nameEn: 'Employer salary declaration', icon: 'ri-money-dollar-circle-line', fillable: true },
                { name: 'Pełnomocnictwo', nameEn: 'Power of Attorney', icon: 'ri-shield-user-line', fillable: true },
            ]
        },
        citizen: {
            label: 'Obywatelstwo (Citizenship)', color: '#198754',
            items: [
                { name: 'Wniosek o nadanie obywatelstwa polskiego', nameEn: 'Application for Polish citizenship', icon: 'ri-file-text-line', fillable: true },
                { name: 'Kwestionariusz osobowy', nameEn: 'Personal questionnaire', icon: 'ri-user-line', fillable: true },
                { name: 'Oświadczenie o znajomości praw i obowiązków', nameEn: 'Rights & obligations declaration', icon: 'ri-scales-line', fillable: true },
            ]
        },
        family: {
            label: 'Łączenie z Rodziną (Family Reunification)', color: '#e91e8d',
            items: [
                { name: 'Wniosek o łączenie z rodziną', nameEn: 'Family reunification application', icon: 'ri-file-text-line', fillable: true },
                { name: 'Akt małżeństwa (tłumaczenie przysięgłe)', nameEn: 'Marriage certificate (sworn translation)', icon: 'ri-heart-line', fillable: false },
                { name: 'Pełnomocnictwo rodzinne', nameEn: 'Family power of attorney', icon: 'ri-shield-user-line', fillable: true },
            ]
        },
        appeal: {
            label: 'Odwołanie (Appeal)', color: '#dc3545',
            items: [
                { name: 'Odwołanie od decyzji Wojewody', nameEn: 'Appeal against Voivode decision', icon: 'ri-file-text-line', fillable: true },
                { name: 'Skarga do WSA', nameEn: 'Complaint to Administrative Court', icon: 'ri-scales-3-line', fillable: true },
                { name: 'Pełnomocnictwo procesowe', nameEn: 'Procedural power of attorney', icon: 'ri-shield-user-line', fillable: true },
                { name: 'Wniosek o wstrzymanie wykonania decyzji', nameEn: 'Request to suspend decision enforcement', icon: 'ri-pause-circle-line', fillable: true },
            ]
        },
        deport: {
            label: 'Uchylenie Zobowiązania (Deportation Cancel)', color: '#f97316',
            items: [
                { name: 'Wniosek o uchylenie decyzji o zobowiązaniu do powrotu', nameEn: 'Request to revoke return obligation', icon: 'ri-file-text-line', fillable: true },
                { name: 'Oświadczenie humanitarne', nameEn: 'Humanitarian declaration', icon: 'ri-heart-pulse-line', fillable: true },
                { name: 'Pełnomocnictwo', nameEn: 'Power of Attorney', icon: 'ri-shield-user-line', fillable: true },
            ]
        },
        general: {
            label: 'Ogólne / Universal', color: '#6c757d',
            items: [
                { name: 'Pełnomocnictwo ogólne', nameEn: 'General Power of Attorney', icon: 'ri-shield-user-line', fillable: true },
                { name: 'Oświadczenie RODO', nameEn: 'GDPR Declaration', icon: 'ri-lock-line', fillable: true },
                { name: 'Umowa z klientem', nameEn: 'Client service agreement', icon: 'ri-file-shield-line', fillable: true },
                { name: 'Potwierdzenie odbioru dokumentów', nameEn: 'Document receipt confirmation', icon: 'ri-checkbox-circle-line', fillable: true },
                { name: 'Upoważnienie do odbioru korespondencji', nameEn: 'Authorization to collect correspondence', icon: 'ri-mail-check-line', fillable: true },
            ]
        }
    };

    function renderTemplates() {
        var filter = document.getElementById('templateFilter').value;
        var search = (document.getElementById('templateSearch').value || '').toLowerCase();
        var container = document.getElementById('templatesContainer');
        var html = '';

        Object.keys(TEMPLATES).forEach(function(key) {
            if (filter !== 'all' && filter !== key) return;
            var section = TEMPLATES[key];
            var items = section.items.filter(function(t) {
                if (!search) return true;
                return t.name.toLowerCase().indexOf(search) !== -1 || t.nameEn.toLowerCase().indexOf(search) !== -1;
            });
            if (items.length === 0) return;

            html += '<div class="case-type-section" data-type="' + key + '">' +
                '<div class="section-header" style="background:' + section.color + '15;color:' + section.color + '">' +
                '<i class="ri-briefcase-line"></i><h6 style="color:' + section.color + '">' + section.label + '</h6>' +
                '<span class="badge ms-auto" style="background:' + section.color + ';color:#fff">' + items.length + ' templates</span></div>' +
                '<div class="row g-3">';
            items.forEach(function(t) {
                html += '<div class="col-xl-3 col-md-4 col-sm-6"><div class="template-card">' +
                    '<div class="template-icon" style="background:' + section.color + '15;color:' + section.color + '"><i class="' + t.icon + '"></i></div>' +
                    '<div class="template-title">' + t.name + '</div>' +
                    '<div class="template-desc">' + t.nameEn + '</div>' +
                    '<div class="d-flex gap-1 mt-2">' +
                    (t.fillable ? '<span class="badge bg-success-subtle text-success fs-10">Online Form</span>' : '<span class="badge bg-light text-muted fs-10">PDF Only</span>') +
                    '<span class="badge bg-primary-subtle text-primary fs-10">Download</span></div></div></div>';
            });
            html += '</div></div>';
        });
        container.innerHTML = html || '<div class="text-center py-4 text-muted">No templates found</div>';
    }
    renderTemplates();

    document.getElementById('templateFilter').addEventListener('change', renderTemplates);
    document.getElementById('templateSearch').addEventListener('input', renderTemplates);

    // ========== TAB 3: ONLINE FORMS — load templates into select ==========
    document.getElementById('formCaseType').addEventListener('change', function() {
        var key = this.value;
        var sel = document.getElementById('formTemplate');
        sel.innerHTML = '<option value="" selected disabled>Choose template...</option>';
        if (TEMPLATES[key]) {
            TEMPLATES[key].items.filter(function(t) { return t.fillable; }).forEach(function(t, i) {
                sel.innerHTML += '<option value="' + i + '">' + t.name + '</option>';
            });
        }
    });

    document.getElementById('loadFormBtn').addEventListener('click', function() {
        var clientSel = document.getElementById('formClient');
        var caseType = document.getElementById('formCaseType').value;
        var templateSel = document.getElementById('formTemplate');
        if (!clientSel.value || !caseType || !templateSel.value) {
            showToast('Select client, case type and document', 'warning');
            return;
        }
        var clientName = clientSel.options[clientSel.selectedIndex].textContent;
        var templateData = TEMPLATES[caseType];
        var fillables = templateData.items.filter(function(t) { return t.fillable; });
        var tmpl = fillables[parseInt(templateSel.value)];

        document.getElementById('formPreviewEmpty').classList.add('d-none');
        document.getElementById('formPreviewFilled').classList.remove('d-none');

        var formHtml = '<div class="form-header">' +
            '<p class="text-muted mb-1">RZECZPOSPOLITA POLSKA</p>' +
            '<h5>' + tmpl.name.toUpperCase() + '</h5>' +
            '<p>' + tmpl.nameEn + '</p></div>';

        formHtml += '<div class="form-field-group"><div class="field-label">Dane wnioskodawcy / Applicant data</div>' +
            '<div class="row g-2 mt-1">' +
            '<div class="col-md-6"><label class="form-label fs-12">Imię / First name</label><input class="form-control form-control-sm" value="' + clientName.split(' ')[0] + '"></div>' +
            '<div class="col-md-6"><label class="form-label fs-12">Nazwisko / Surname</label><input class="form-control form-control-sm" value="' + (clientName.split(' ')[1]||'') + '"></div>' +
            '<div class="col-md-4"><label class="form-label fs-12">Data urodzenia / Date of birth</label><input type="date" class="form-control form-control-sm"></div>' +
            '<div class="col-md-4"><label class="form-label fs-12">PESEL</label><input class="form-control form-control-sm" placeholder="00000000000"></div>' +
            '<div class="col-md-4"><label class="form-label fs-12">Obywatelstwo / Citizenship</label><input class="form-control form-control-sm" placeholder="e.g. Ukrainian"></div>' +
            '</div></div>';

        formHtml += '<div class="form-field-group"><div class="field-label">Dokument podróży / Travel document</div>' +
            '<div class="row g-2 mt-1">' +
            '<div class="col-md-4"><label class="form-label fs-12">Numer / Number</label><input class="form-control form-control-sm"></div>' +
            '<div class="col-md-4"><label class="form-label fs-12">Data wydania / Issue date</label><input type="date" class="form-control form-control-sm"></div>' +
            '<div class="col-md-4"><label class="form-label fs-12">Data ważności / Expiry</label><input type="date" class="form-control form-control-sm"></div>' +
            '</div></div>';

        formHtml += '<div class="form-field-group"><div class="field-label">Adres zamieszkania / Address of residence</div>' +
            '<div class="row g-2 mt-1">' +
            '<div class="col-md-6"><label class="form-label fs-12">Ulica / Street</label><input class="form-control form-control-sm"></div>' +
            '<div class="col-md-2"><label class="form-label fs-12">Nr domu</label><input class="form-control form-control-sm"></div>' +
            '<div class="col-md-2"><label class="form-label fs-12">Nr lokalu</label><input class="form-control form-control-sm"></div>' +
            '<div class="col-md-2"><label class="form-label fs-12">Kod pocztowy</label><input class="form-control form-control-sm" placeholder="00-000"></div>' +
            '<div class="col-md-4"><label class="form-label fs-12">Miasto / City</label><input class="form-control form-control-sm"></div>' +
            '<div class="col-md-4"><label class="form-label fs-12">Województwo</label><input class="form-control form-control-sm" value="mazowieckie"></div>' +
            '<div class="col-md-4"><label class="form-label fs-12">Telefon / Phone</label><input class="form-control form-control-sm" placeholder="+48"></div>' +
            '</div></div>';

        if (caseType === 'temp_res' || caseType === 'work' || caseType === 'blue') {
            formHtml += '<div class="form-field-group"><div class="field-label">Dane pracodawcy / Employer data</div>' +
                '<div class="row g-2 mt-1">' +
                '<div class="col-md-6"><label class="form-label fs-12">Nazwa firmy / Company</label><input class="form-control form-control-sm"></div>' +
                '<div class="col-md-3"><label class="form-label fs-12">NIP</label><input class="form-control form-control-sm"></div>' +
                '<div class="col-md-3"><label class="form-label fs-12">REGON</label><input class="form-control form-control-sm"></div>' +
                '<div class="col-md-6"><label class="form-label fs-12">Adres firmy / Company address</label><input class="form-control form-control-sm"></div>' +
                '<div class="col-md-3"><label class="form-label fs-12">Stanowisko / Position</label><input class="form-control form-control-sm"></div>' +
                '<div class="col-md-3"><label class="form-label fs-12">Wynagrodzenie / Salary</label><input class="form-control form-control-sm" placeholder="PLN"></div>' +
                '</div></div>';
        }

        formHtml += '<div class="form-field-group"><div class="field-label">Cel pobytu / Purpose of stay</div>' +
            '<textarea class="form-control form-control-sm mt-1" rows="3" placeholder="Opisz cel pobytu..."></textarea></div>';

        formHtml += '<div class="form-field-group"><div class="field-label">Podpis / Signature</div>' +
            '<div class="row g-2 mt-1">' +
            '<div class="col-md-6"><label class="form-label fs-12">Miejsce / Place</label><input class="form-control form-control-sm" value="Warszawa"></div>' +
            '<div class="col-md-6"><label class="form-label fs-12">Data / Date</label><input type="date" class="form-control form-control-sm" value="' + new Date().toISOString().split('T')[0] + '"></div>' +
            '<div class="col-12"><div class="border rounded p-4 text-center text-muted mt-2" style="min-height:80px"><i class="ri-quill-pen-line fs-3 d-block mb-1"></i>Click "Sign Document" to add signature</div></div>' +
            '</div></div>';

        document.getElementById('formContent').innerHTML = formHtml;
    });

    // Form buttons
    document.getElementById('formSaveDraft').addEventListener('click', function() { showToast('Draft saved', 'success'); });
    document.getElementById('formPrint').addEventListener('click', function() { window.print(); });
    document.getElementById('formDownloadPdf').addEventListener('click', function() { showToast('PDF generated — downloading...', 'info'); });
    document.getElementById('formSignBtn').addEventListener('click', function() {
        var client = document.getElementById('formClient');
        var clientName = client.options[client.selectedIndex].textContent;
        var tmplSel = document.getElementById('formTemplate');
        var docName = tmplSel.options[tmplSel.selectedIndex].textContent;
        openSignModal(docName, clientName);
    });

    // ========== TAB 4: SIGNATURES ==========
    var signatures = [
        { id:1, doc:'Pełnomocnictwo', client:'Oleksandr Petrov', status:'pending', method:'', sent:'2026-03-01', deadline:'2026-03-10', via:'Email, Portal' },
        { id:2, doc:'Wniosek TRC', client:'Oleksandr Petrov', status:'signed', method:'Profil Zaufany', sent:'2026-02-10', signed:'2026-02-12', via:'Portal' },
        { id:3, doc:'Oświadczenie', client:'Rajesh Kumar', status:'pending', method:'', sent:'2026-03-01', deadline:'2026-03-08', via:'Email' },
        { id:4, doc:'Umowa z klientem', client:'Maria Ivanova', status:'signed', method:'Draw', sent:'2026-02-15', signed:'2026-02-15', via:'In Office' },
        { id:5, doc:'Odwołanie', client:'Tetiana Sydorenko', status:'signed', method:'Profil Zaufany', sent:'2026-02-18', signed:'2026-02-19', via:'Portal, PZ' },
        { id:6, doc:'Pełnomocnictwo procesowe', client:'Tetiana Sydorenko', status:'pending', method:'', sent:'2026-02-28', deadline:'2026-03-10', via:'Email, Portal' },
        { id:7, doc:'Zgoda RODO', client:'Dmytro Boyko', status:'pending', method:'', sent:'2026-02-25', deadline:'2026-03-05', via:'SMS, Portal' },
        { id:8, doc:'Pełnomocnictwo', client:'Maria Ivanova', status:'signed', method:'Draw', sent:'2026-01-20', signed:'2026-01-20', via:'In Office' },
        { id:9, doc:'Wniosek Blue Card', client:'Rajesh Kumar', status:'pending', method:'', sent:'2026-03-01', deadline:'2026-03-12', via:'Portal' },
        { id:10, doc:'Umowa z klientem', client:'Dmytro Boyko', status:'expired', method:'', sent:'2026-01-15', deadline:'2026-02-15', via:'Email' },
    ];

    function renderSignatures(filter) {
        filter = filter || 'all';
        var filtered = signatures.filter(function(s) {
            if (filter === 'all') return true;
            return s.status === filter;
        });
        var html = '';
        filtered.forEach(function(s) {
            var cardClass = s.status === 'signed' ? 'sig-completed-card' : (s.status === 'pending' ? 'sig-pending-card' : '');
            var statusBadge = '<span class="badge bg-' + (s.status === 'signed' ? 'success' : s.status === 'pending' ? 'warning' : 'danger') + '-subtle text-' + (s.status === 'signed' ? 'success' : s.status === 'pending' ? 'warning' : 'danger') + '">' + s.status.charAt(0).toUpperCase() + s.status.slice(1) + '</span>';
            html += '<div class="card ' + cardClass + ' mb-2"><div class="card-body py-3">' +
                '<div class="d-flex justify-content-between align-items-center">' +
                '<div><h6 class="mb-1 fs-14">' + s.doc + '</h6>' +
                '<span class="fs-12 text-muted">' + s.client + ' • Sent: ' + s.sent + '</span></div>' +
                '<div class="d-flex align-items-center gap-2">' +
                (s.method ? '<span class="badge bg-primary-subtle text-primary">' + s.method + '</span>' : '') +
                statusBadge;
            if (s.status === 'pending') {
                html += '<button class="btn btn-sm btn-outline-warning"><i class="ri-notification-line me-1"></i>Remind</button>';
            }
            html += '</div></div>';
            if (s.status === 'signed') {
                html += '<div class="mt-1 fs-12 text-muted">Signed: ' + s.signed + ' via ' + s.via + '</div>';
            } else if (s.status === 'pending') {
                html += '<div class="mt-1 fs-12 text-muted">Deadline: <span class="text-danger fw-semibold">' + s.deadline + '</span> • Via: ' + s.via + '</div>';
            }
            html += '</div></div>';
        });
        document.getElementById('signaturesContainer').innerHTML = html || '<div class="text-center py-4 text-muted">No signatures found</div>';
    }
    renderSignatures();

    document.querySelectorAll('.sig-filter').forEach(function(btn) {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.sig-filter').forEach(function(b) { b.classList.remove('active', 'btn-primary', 'btn-outline-warning', 'btn-outline-success', 'btn-outline-danger'); b.classList.add('btn-outline-secondary'); });
            this.classList.remove('btn-outline-secondary');
            this.classList.add('active', 'btn-primary');
            renderSignatures(this.dataset.filter);
        });
    });

    document.getElementById('sendSigRequest').addEventListener('click', function() {
        bootstrap.Modal.getInstance(document.getElementById('requestSignModal')).hide();
        showToast('Signature request sent to client', 'success');
    });

    // ========== SIGNATURE MODAL ==========
    function openSignModal(docName, clientName) {
        document.getElementById('signDocName').textContent = docName;
        document.getElementById('signDocClient').textContent = clientName;
        document.getElementById('sigLegalConsent').checked = false;
        // Reset method cards
        document.querySelectorAll('.sig-method-card').forEach(function(c) { c.classList.remove('active', 'border-primary'); });
        document.querySelector('.sig-method-card[data-method="draw"]').classList.add('active', 'border-primary');
        document.getElementById('sigDrawArea').classList.remove('d-none');
        document.getElementById('sigPzArea').classList.add('d-none');
        document.getElementById('sigMobArea').classList.add('d-none');
        clearCanvas();
        new bootstrap.Modal(document.getElementById('signDocModal')).show();
    }

    document.querySelectorAll('.sig-method-card').forEach(function(card) {
        card.addEventListener('click', function() {
            document.querySelectorAll('.sig-method-card').forEach(function(c) { c.classList.remove('active', 'border-primary'); });
            this.classList.add('active', 'border-primary');
            var method = this.dataset.method;
            document.getElementById('sigDrawArea').classList.toggle('d-none', method !== 'draw');
            document.getElementById('sigPzArea').classList.toggle('d-none', method !== 'profil_zaufany');
            document.getElementById('sigMobArea').classList.toggle('d-none', method !== 'mobywatel');
        });
    });

    // Canvas drawing
    var canvas = document.getElementById('sigCanvas');
    var ctx = canvas ? canvas.getContext('2d') : null;
    var drawing = false;
    if (canvas) {
        canvas.addEventListener('mousedown', function(e) { drawing = true; ctx.beginPath(); ctx.moveTo(e.offsetX, e.offsetY); });
        canvas.addEventListener('mousemove', function(e) { if (!drawing) return; ctx.lineWidth = 2; ctx.lineCap = 'round'; ctx.strokeStyle = '#000'; ctx.lineTo(e.offsetX, e.offsetY); ctx.stroke(); });
        canvas.addEventListener('mouseup', function() { drawing = false; });
        canvas.addEventListener('mouseleave', function() { drawing = false; });
    }
    function clearCanvas() { if (ctx) ctx.clearRect(0, 0, canvas.width, canvas.height); }
    document.getElementById('clearSigBtn').addEventListener('click', clearCanvas);

    document.getElementById('confirmSignBtn').addEventListener('click', function() {
        var activeMethod = document.querySelector('.sig-method-card.active');
        if (activeMethod && activeMethod.dataset.method === 'draw' && !document.getElementById('sigLegalConsent').checked) {
            showToast('Please confirm legal signature consent', 'warning');
            return;
        }
        bootstrap.Modal.getInstance(document.getElementById('signDocModal')).hide();
        showToast('Document signed successfully!', 'success');
    });

    document.getElementById('pzSignBtn').addEventListener('click', function() {
        showToast('Redirecting to login.gov.pl for Profil Zaufany signature...', 'info');
    });
    document.getElementById('mobSignBtn').addEventListener('click', function() {
        showToast('Request sent to mObywatel app', 'info');
    });

    // ========== INTEGRATIONS ==========
    document.getElementById('mobywatelConnect').addEventListener('click', function() {
        showToast('Opening mObywatel API configuration...', 'info');
    });
    document.getElementById('profilZaufanyConnect').addEventListener('click', function() {
        showToast('Opening Profil Zaufany API configuration...', 'info');
    });
    document.getElementById('clientPortalSync').addEventListener('click', function() {
        this.innerHTML = '<i class="ri-loader-4-line me-1 spin"></i>Syncing...';
        var btn = this;
        setTimeout(function() {
            btn.innerHTML = '<i class="ri-check-line me-1"></i>Synced!';
            showToast('Client Portal synced — 847 documents', 'success');
            setTimeout(function() { btn.innerHTML = '<i class="ri-refresh-line me-1"></i>Sync Now'; }, 2000);
        }, 1500);
    });

    // ========== UPLOAD ==========
    var dropZone = document.getElementById('dropZone');
    var fileInput = document.getElementById('fileInput');
    if (dropZone) {
        dropZone.addEventListener('click', function() { fileInput.click(); });
        dropZone.addEventListener('dragover', function(e) { e.preventDefault(); dropZone.style.borderColor = '#5865F2'; });
        dropZone.addEventListener('dragleave', function() { dropZone.style.borderColor = '#dee2e6'; });
        dropZone.addEventListener('drop', function(e) { e.preventDefault(); dropZone.style.borderColor = '#dee2e6'; showToast('File ready to upload', 'info'); });
    }
    document.getElementById('uploadSaveBtn').addEventListener('click', function() {
        bootstrap.Modal.getInstance(document.getElementById('uploadDocModal')).hide();
        showToast('Document uploaded successfully', 'success');
    });

    // ========== HELPERS ==========
    function showToast(msg, type) {
        type = type || 'success';
        var icons = { success: 'ri-check-double-line', danger: 'ri-delete-bin-line', warning: 'ri-alert-line', info: 'ri-information-line' };
        var toast = document.createElement('div');
        toast.className = 'position-fixed top-0 end-0 p-3';
        toast.style.zIndex = '9999';
        toast.innerHTML = '<div class="toast show border-0 shadow" role="alert"><div class="toast-body d-flex align-items-center gap-2 text-' + type + '"><i class="' + (icons[type]||icons.success) + ' fs-5"></i><span>' + msg + '</span></div></div>';
        document.body.appendChild(toast);
        setTimeout(function() { toast.remove(); }, 3000);
    }
});

function toggleApiKey(id) {
    var el = document.getElementById(id);
    el.type = el.type === 'password' ? 'text' : 'password';
}

// ========== CLIENT VAULT FUNCTIONS ==========
var vaultData = [];

var VAULT_TYPE_LABELS = {
    bank:'Bank Statement', health:'Health Insurance', employment:'Employment Certificate',
    passport:'Passport Scan', tax:'Tax Document', address:'Address Confirmation',
    photo:'Photo 3.5×4.5', power_of_attorney:'Power of Attorney', work_contract:'Work Contract',
    zus:'ZUS / RMUA', pit:'PIT (Tax Return)', zameldowanie:'Zameldowanie', other:'Other'
};
var VAULT_TYPE_ICONS = {
    bank:'ri-bank-line', health:'ri-heart-pulse-line', employment:'ri-briefcase-line',
    passport:'ri-passport-line', tax:'ri-file-list-3-line', address:'ri-map-pin-line',
    photo:'ri-camera-line', power_of_attorney:'ri-quill-pen-line', work_contract:'ri-file-paper-line',
    zus:'ri-shield-check-line', pit:'ri-file-chart-line', zameldowanie:'ri-home-4-line', other:'ri-file-line'
};
var VAULT_TYPE_COLORS = {
    bank:'warning', health:'info', employment:'info', passport:'primary', tax:'secondary',
    address:'warning', photo:'secondary', power_of_attorney:'primary', work_contract:'info',
    zus:'info', pit:'secondary', zameldowanie:'warning', other:'secondary'
};
var VAULT_STATUS_MAP = {
    'pending_review': { label:'Pending Review', color:'warning', icon:'ri-time-line' },
    'approved':       { label:'Approved',       color:'success', icon:'ri-check-line' },
    'rejected':       { label:'Rejected',       color:'danger',  icon:'ri-close-line' },
    'needs_correction':{ label:'Needs Correction', color:'info', icon:'ri-edit-line' }
};

function loadVaultData() {
    var accordion = document.getElementById('vaultAccordion');
    var loading = document.getElementById('vaultLoading');
    var empty = document.getElementById('vaultEmpty');
    loading.classList.remove('d-none');
    empty.classList.add('d-none');

    fetch('/api/documents/admin-vault')
        .then(function(r) { return r.json(); })
        .then(function(data) {
            vaultData = data.clients || [];
            loading.classList.add('d-none');
            document.getElementById('vaultClientCount').textContent = vaultData.length;
            if (vaultData.length === 0) {
                empty.classList.remove('d-none');
                accordion.innerHTML = '';
                return;
            }
            renderVaultAccordion(vaultData);
        })
        .catch(function(err) {
            loading.classList.add('d-none');
            accordion.innerHTML = '<div class="text-center py-4 text-danger"><i class="ri-error-warning-line fs-3"></i><p class="mt-2">Failed to load vault data. ' + err.message + '</p></div>';
        });
}

function renderVaultAccordion(clients) {
    var accordion = document.getElementById('vaultAccordion');
    var html = '';
    var search = (document.getElementById('vaultSearch').value || '').toLowerCase();
    var statusFilter = document.getElementById('vaultFilterStatus').value;

    clients.forEach(function(client, idx) {
        // Filter by search
        if (search && (client.name || '').toLowerCase().indexOf(search) === -1) return;

        // Filter documents by status
        var docs = client.documents || [];
        if (statusFilter !== 'all') {
            docs = docs.filter(function(d) { return d.status === statusFilter; });
        }
        if (docs.length === 0 && statusFilter !== 'all') return;

        var totalSize = docs.reduce(function(s, d) { return s + (d.file_size || 0); }, 0);
        var pending = docs.filter(function(d) { return d.status === 'pending_review'; }).length;
        var approved = docs.filter(function(d) { return d.status === 'approved'; }).length;

        html += '<div class="accordion-item border mb-2" style="border-radius:8px!important;overflow:hidden;">';
        html += '<h2 class="accordion-header">';
        html += '<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#vaultClient' + idx + '">';
        html += '<div class="d-flex w-100 align-items-center justify-content-between me-3">';
        html += '<div class="d-flex align-items-center gap-3">';
        html += '<div class="rounded-circle bg-success-subtle text-success d-flex align-items-center justify-content-center" style="width:38px;height:38px;font-weight:700;font-size:.85rem;">' + (client.name || 'C').charAt(0).toUpperCase() + '</div>';
        html += '<div><span class="fw-bold">' + (client.name || 'Client #' + client.id) + '</span>';
        html += '<br><span class="text-muted fs-12">ID: ' + client.id + '</span></div>';
        html += '</div>';
        html += '<div class="d-flex gap-3 align-items-center">';
        html += '<span class="badge bg-primary-subtle text-primary">' + docs.length + ' docs</span>';
        if (pending > 0) html += '<span class="badge bg-warning-subtle text-warning">' + pending + ' pending</span>';
        if (approved > 0) html += '<span class="badge bg-success-subtle text-success">' + approved + ' approved</span>';
        html += '<span class="text-muted fs-12"><i class="ri-hard-drive-2-line me-1"></i>' + humanSize(totalSize) + '</span>';
        html += '<span class="badge bg-success-subtle text-success fs-11"><i class="ri-lock-line me-1"></i>Encrypted</span>';
        html += '</div></div>';
        html += '</button></h2>';

        html += '<div id="vaultClient' + idx + '" class="accordion-collapse collapse" data-bs-parent="#vaultAccordion">';
        html += '<div class="accordion-body p-0">';
        html += '<div class="table-responsive">';
        html += '<table class="table table-hover align-middle mb-0">';
        html += '<thead class="table-light"><tr>';
        html += '<th style="width:40px"></th><th>Document</th><th>Type</th><th>Size</th><th>Uploaded</th><th>Status</th><th style="width:140px">Actions</th>';
        html += '</tr></thead><tbody>';

        docs.forEach(function(doc) {
            var st = VAULT_STATUS_MAP[doc.status] || VAULT_STATUS_MAP.pending_review;
            var tc = VAULT_TYPE_COLORS[doc.type] || 'secondary';
            var ti = VAULT_TYPE_ICONS[doc.type] || 'ri-file-line';
            var tl = VAULT_TYPE_LABELS[doc.type] || doc.type;
            var ext = (doc.original_name || '').split('.').pop().toLowerCase();
            var isImage = ['jpg','jpeg','png'].indexOf(ext) >= 0;
            var isPdf = ext === 'pdf';

            html += '<tr>';
            html += '<td><div class="doc-icon bg-' + tc + '-subtle text-' + tc + '"><i class="' + ti + '"></i></div></td>';
            html += '<td><span class="fw-semibold">' + (doc.original_name || doc.name) + '</span><br>';
            html += '<span class="fs-11 text-muted"><i class="ri-lock-line"></i> AES-256 Encrypted</span></td>';
            html += '<td><span class="badge bg-' + tc + '-subtle text-' + tc + '">' + tl + '</span></td>';
            html += '<td class="text-muted fs-12">' + (doc.size || humanSize(doc.file_size || 0)) + '</td>';
            html += '<td class="text-muted fs-12">' + (doc.uploaded_at || '') + '</td>';
            html += '<td><span class="badge bg-' + st.color + '-subtle text-' + st.color + '"><i class="' + st.icon + ' me-1"></i>' + st.label + '</span></td>';
            html += '<td>';
            if (isImage || isPdf) {
                html += '<button class="btn btn-sm btn-outline-info me-1" title="Preview (Decrypt)" onclick="previewVaultDoc(' + doc.id + ',\'' + (doc.original_name || '').replace(/'/g, "\\'") + '\',\'' + (doc.mime_type || '') + '\',\'' + (doc.size || '') + '\')"><i class="ri-eye-line"></i></button>';
            }
            html += '<a href="/api/documents/' + doc.id + '/download" class="btn btn-sm btn-outline-primary me-1" title="Download (Decrypt)"><i class="ri-download-line"></i></a>';
            html += '<button class="btn btn-sm btn-outline-warning me-1" title="Change Status" onclick="openVaultStatus(' + doc.id + ',\'' + doc.status + '\')"><i class="ri-edit-line"></i></button>';
            html += '</td>';
            html += '</tr>';
        });

        html += '</tbody></table></div></div></div></div>';
    });

    accordion.innerHTML = html;
}

function previewVaultDoc(id, name, mime, size) {
    var modal = new bootstrap.Modal(document.getElementById('vaultPreviewModal'));
    document.getElementById('vaultPreviewTitle').textContent = name;
    document.getElementById('vaultPreviewMeta').textContent = size + ' • ' + mime;
    document.getElementById('vaultDownloadBtn').href = '/api/documents/' + id + '/download';
    document.getElementById('vaultPreviewLoading').style.display = 'block';
    document.getElementById('vaultPreviewFrame').style.display = 'none';
    document.getElementById('vaultPreviewImg').style.display = 'none';
    modal.show();

    var url = '/api/documents/' + id + '/preview';
    if (mime && mime.indexOf('image') >= 0) {
        var img = document.getElementById('vaultPreviewImg');
        img.src = url;
        img.onload = function() {
            document.getElementById('vaultPreviewLoading').style.display = 'none';
            img.style.display = 'block';
        };
    } else {
        var frame = document.getElementById('vaultPreviewFrame');
        frame.src = url;
        frame.onload = function() {
            document.getElementById('vaultPreviewLoading').style.display = 'none';
            frame.style.display = 'block';
        };
    }
}

function openVaultStatus(docId, currentStatus) {
    document.getElementById('vaultStatusDocId').value = docId;
    document.getElementById('vaultStatusSelect').value = currentStatus;
    document.getElementById('vaultStatusNote').value = '';
    new bootstrap.Modal(document.getElementById('vaultStatusModal')).show();
}

function saveVaultStatus() {
    var docId = document.getElementById('vaultStatusDocId').value;
    var status = document.getElementById('vaultStatusSelect').value;
    var note = document.getElementById('vaultStatusNote').value;

    fetch('/api/documents/' + docId + '/status', {
        method: 'PUT',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '' },
        body: JSON.stringify({ status: status, note: note })
    })
    .then(function(r) { return r.json(); })
    .then(function(data) {
        bootstrap.Modal.getInstance(document.getElementById('vaultStatusModal')).hide();
        loadVaultData();
    })
    .catch(function(err) { alert('Error: ' + err.message); });
}

function humanSize(bytes) {
    if (bytes < 1024) return bytes + ' B';
    if (bytes < 1048576) return (bytes / 1024).toFixed(1) + ' KB';
    return (bytes / 1048576).toFixed(1) + ' MB';
}

// Auto-load vault when tab is shown
document.getElementById('vaultTabLink')?.addEventListener('shown.bs.tab', function() {
    loadVaultData();
});

// Filter events
document.getElementById('vaultSearch')?.addEventListener('input', function() {
    if (vaultData.length) renderVaultAccordion(vaultData);
});
document.getElementById('vaultFilterStatus')?.addEventListener('change', function() {
    if (vaultData.length) renderVaultAccordion(vaultData);
});
</script>
@endsection
