@extends('partials.layouts.master-employee')
@section('title', 'Documents — WinCase Staff')
@section('page-title', 'Documents')

@section('css')
<style>
    .doc-card { border: 1px solid rgba(0,0,0,.08); border-radius: .5rem; transition: all .15s; cursor: pointer; }
    .doc-card:hover { border-color: #015EA7; box-shadow: 0 2px 8px rgba(1,94,167,.1); }
    [data-bs-theme="dark"] .doc-card { border-color: rgba(255,255,255,.08); }
    [data-bs-theme="dark"] .doc-card:hover { border-color: #015EA7; }
    .doc-icon { width: 48px; height: 48px; border-radius: .5rem; display: flex; align-items: center; justify-content: center; font-size: 1.3rem; flex-shrink: 0; }
    .doc-icon.pdf { background: #dc354515; color: #dc3545; }
    .doc-icon.image { background: #0d6efd15; color: #0d6efd; }
    .doc-icon.doc { background: #0d6efd15; color: #0d6efd; }
    .doc-icon.scan { background: #6f42c115; color: #6f42c1; }
    .doc-icon.cert { background: #fd7e1415; color: #fd7e14; }
    .doc-icon.other { background: #6c757d15; color: #6c757d; }
    .upload-zone { border: 2px dashed rgba(1,94,167,.3); border-radius: .75rem; padding: 2rem; text-align: center; cursor: pointer; transition: all .2s; }
    .upload-zone:hover { border-color: #015EA7; background: rgba(1,94,167,.03); }
</style>
@endsection

@section('content')
<!-- Stats -->
<div class="row g-3 mb-3">
    <div class="col-6 col-lg-3">
        <div class="card border-0 bg-primary bg-opacity-10">
            <div class="card-body text-center py-3">
                <h4 class="text-primary mb-0">47</h4>
                <small class="text-muted" data-lang="wc-staff-total-docs">Total Documents</small>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card border-0 bg-success bg-opacity-10">
            <div class="card-body text-center py-3">
                <h4 class="text-success mb-0">38</h4>
                <small class="text-muted" data-lang="wc-staff-verified">Verified</small>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card border-0 bg-warning bg-opacity-10">
            <div class="card-body text-center py-3">
                <h4 class="text-warning mb-0">6</h4>
                <small class="text-muted" data-lang="wc-staff-pending-review">Pending Review</small>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card border-0 bg-danger bg-opacity-10">
            <div class="card-body text-center py-3">
                <h4 class="text-danger mb-0">3</h4>
                <small class="text-muted" data-lang="wc-staff-missing">Missing</small>
            </div>
        </div>
    </div>
</div>

<!-- Filters + Upload -->
<div class="row g-3 mb-3">
    <div class="col-lg-8">
        <div class="card mb-0">
            <div class="card-body py-2">
                <div class="d-flex flex-wrap align-items-center gap-2">
                    <button class="btn btn-sm btn-success active" data-df="all">All</button>
                    <button class="btn btn-sm btn-outline-warning" data-df="pending">Pending</button>
                    <button class="btn btn-sm btn-outline-danger" data-df="missing">Missing</button>
                    <button class="btn btn-sm btn-outline-success" data-df="verified">Verified</button>
                    <div class="ms-auto d-flex gap-2">
                        <select class="form-select form-select-sm" style="width:160px;">
                            <option>All Clients</option>
                            <option>Olena Kovalenko</option>
                            <option>Dmytro Bondarenko</option>
                            <option>Rahul Sharma</option>
                            <option>Chen Wei</option>
                            <option>Mehmet Yilmaz</option>
                        </select>
                        <input type="text" class="form-control form-control-sm" placeholder="Search docs..." style="width:180px;">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="upload-zone" onclick="document.getElementById('fileUpload').click()">
            <i class="ri-upload-cloud-2-line fs-2 text-success"></i>
            <div class="mt-1" style="font-size:.85rem;">Drop files or <strong class="text-success">browse</strong></div>
            <small class="text-muted">PDF, JPG, PNG up to 10 MB</small>
            <input type="file" id="fileUpload" class="d-none" multiple>
        </div>
    </div>
</div>

<!-- Recent Documents -->
<div class="card mb-3">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h6 class="mb-0"><i class="ri-time-line text-primary me-1"></i><span data-lang="wc-staff-recently-uploaded">Recently Uploaded</span></h6>
        <span class="badge bg-secondary">Last 7 days</span>
    </div>
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-6 col-xl-4">
                <div class="doc-card p-3 d-flex gap-3 align-items-start">
                    <div class="doc-icon pdf"><i class="ri-file-pdf-2-line"></i></div>
                    <div class="flex-grow-1 min-width-0">
                        <div class="fw-semibold text-truncate" style="font-size:.85rem;">Bank Statement Feb 2026</div>
                        <small class="text-muted d-block">Olena Kovalenko &bull; 1.2 MB</small>
                        <div class="d-flex align-items-center gap-2 mt-1">
                            <span class="badge bg-success-subtle text-success">Verified</span>
                            <small class="text-muted">Mar 1, 2026</small>
                        </div>
                    </div>
                    <button class="btn btn-sm btn-light flex-shrink-0"><i class="ri-download-line"></i></button>
                </div>
            </div>
            <div class="col-md-6 col-xl-4">
                <div class="doc-card p-3 d-flex gap-3 align-items-start">
                    <div class="doc-icon image"><i class="ri-image-line"></i></div>
                    <div class="flex-grow-1 min-width-0">
                        <div class="fw-semibold text-truncate" style="font-size:.85rem;">Passport Scan (front)</div>
                        <small class="text-muted d-block">Rahul Sharma &bull; 3.4 MB</small>
                        <div class="d-flex align-items-center gap-2 mt-1">
                            <span class="badge bg-warning-subtle text-warning">Pending</span>
                            <small class="text-muted">Mar 1, 2026</small>
                        </div>
                    </div>
                    <button class="btn btn-sm btn-light flex-shrink-0"><i class="ri-download-line"></i></button>
                </div>
            </div>
            <div class="col-md-6 col-xl-4">
                <div class="doc-card p-3 d-flex gap-3 align-items-start">
                    <div class="doc-icon cert"><i class="ri-award-line"></i></div>
                    <div class="flex-grow-1 min-width-0">
                        <div class="fw-semibold text-truncate" style="font-size:.85rem;">Translated Birth Certificate</div>
                        <small class="text-muted d-block">Irina Kozlova &bull; 0.8 MB</small>
                        <div class="d-flex align-items-center gap-2 mt-1">
                            <span class="badge bg-success-subtle text-success">Verified</span>
                            <small class="text-muted">Feb 28, 2026</small>
                        </div>
                    </div>
                    <button class="btn btn-sm btn-light flex-shrink-0"><i class="ri-download-line"></i></button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Documents by Client -->
<div class="card">
    <div class="card-header">
        <h6 class="mb-0"><i class="ri-folders-line text-success me-1"></i><span data-lang="wc-staff-docs-by-client">Documents by Client</span></h6>
    </div>
    <div class="accordion accordion-flush" id="docAccordion">
        <!-- Olena -->
        <div class="accordion-item">
            <h2 class="accordion-header">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#docOlena">
                    <div class="d-flex align-items-center gap-2 w-100 me-3">
                        <div class="rounded-circle bg-success bg-opacity-10 d-flex align-items-center justify-content-center" style="width:32px;height:32px;font-size:.7rem;font-weight:700;color:#015EA7;">OK</div>
                        <div>
                            <strong style="font-size:.85rem;">Olena Kovalenko</strong>
                            <small class="text-muted d-block">Case #WC-2026-0847 &bull; Temporary Residence Permit</small>
                        </div>
                        <div class="ms-auto d-flex gap-1">
                            <span class="badge bg-success">8 docs</span>
                            <span class="badge bg-warning">1 pending</span>
                        </div>
                    </div>
                </button>
            </h2>
            <div id="docOlena" class="accordion-collapse collapse">
                <div class="accordion-body p-0">
                    <div class="list-group list-group-flush">
                        <div class="list-group-item d-flex align-items-center gap-3">
                            <div class="doc-icon pdf" style="width:36px;height:36px;font-size:1rem;"><i class="ri-file-pdf-2-line"></i></div>
                            <div class="flex-grow-1">
                                <div style="font-size:.8rem;" class="fw-semibold">Passport copy</div>
                                <small class="text-muted">Uploaded Jan 15 &bull; 2.1 MB</small>
                            </div>
                            <span class="badge bg-success-subtle text-success">Verified</span>
                            <button class="btn btn-sm btn-light"><i class="ri-download-line"></i></button>
                        </div>
                        <div class="list-group-item d-flex align-items-center gap-3">
                            <div class="doc-icon pdf" style="width:36px;height:36px;font-size:1rem;"><i class="ri-file-pdf-2-line"></i></div>
                            <div class="flex-grow-1">
                                <div style="font-size:.8rem;" class="fw-semibold">Work contract</div>
                                <small class="text-muted">Uploaded Jan 15 &bull; 0.9 MB</small>
                            </div>
                            <span class="badge bg-success-subtle text-success">Verified</span>
                            <button class="btn btn-sm btn-light"><i class="ri-download-line"></i></button>
                        </div>
                        <div class="list-group-item d-flex align-items-center gap-3">
                            <div class="doc-icon pdf" style="width:36px;height:36px;font-size:1rem;"><i class="ri-file-pdf-2-line"></i></div>
                            <div class="flex-grow-1">
                                <div style="font-size:.8rem;" class="fw-semibold">Bank statement — February</div>
                                <small class="text-muted">Uploaded Mar 1 &bull; 1.2 MB</small>
                            </div>
                            <span class="badge bg-success-subtle text-success">Verified</span>
                            <button class="btn btn-sm btn-light"><i class="ri-download-line"></i></button>
                        </div>
                        <div class="list-group-item d-flex align-items-center gap-3">
                            <div class="doc-icon pdf" style="width:36px;height:36px;font-size:1rem;"><i class="ri-file-pdf-2-line"></i></div>
                            <div class="flex-grow-1">
                                <div style="font-size:.8rem;" class="fw-semibold">Bank statement — March</div>
                                <small class="text-muted">Deadline: Mar 5</small>
                            </div>
                            <span class="badge bg-warning-subtle text-warning">Pending</span>
                            <button class="btn btn-sm btn-outline-success btn-sm"><i class="ri-upload-line me-1"></i>Request</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Dmytro -->
        <div class="accordion-item">
            <h2 class="accordion-header">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#docDmytro">
                    <div class="d-flex align-items-center gap-2 w-100 me-3">
                        <div class="rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center" style="width:32px;height:32px;font-size:.7rem;font-weight:700;color:#0d6efd;">DB</div>
                        <div>
                            <strong style="font-size:.85rem;">Dmytro Bondarenko</strong>
                            <small class="text-muted d-block">Case #WC-2026-0812 &bull; Work Permit Extension</small>
                        </div>
                        <div class="ms-auto d-flex gap-1">
                            <span class="badge bg-success">6 docs</span>
                        </div>
                    </div>
                </button>
            </h2>
            <div id="docDmytro" class="accordion-collapse collapse">
                <div class="accordion-body py-3">
                    <div class="text-muted text-center" style="font-size:.85rem;"><i class="ri-check-double-line me-1"></i>All 6 documents verified</div>
                </div>
            </div>
        </div>

        <!-- Chen Wei -->
        <div class="accordion-item">
            <h2 class="accordion-header">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#docChen">
                    <div class="d-flex align-items-center gap-2 w-100 me-3">
                        <div class="rounded-circle bg-info bg-opacity-10 d-flex align-items-center justify-content-center" style="width:32px;height:32px;font-size:.7rem;font-weight:700;color:#0dcaf0;">CW</div>
                        <div>
                            <strong style="font-size:.85rem;">Chen Wei</strong>
                            <small class="text-muted d-block">Case #WC-2026-0870 &bull; Temporary Residence Permit</small>
                        </div>
                        <div class="ms-auto d-flex gap-1">
                            <span class="badge bg-success">4 docs</span>
                            <span class="badge bg-danger">2 missing</span>
                        </div>
                    </div>
                </button>
            </h2>
            <div id="docChen" class="accordion-collapse collapse">
                <div class="accordion-body p-0">
                    <div class="list-group list-group-flush">
                        <div class="list-group-item d-flex align-items-center gap-3 bg-danger bg-opacity-10">
                            <div class="doc-icon cert" style="width:36px;height:36px;font-size:1rem;"><i class="ri-award-line"></i></div>
                            <div class="flex-grow-1">
                                <div style="font-size:.8rem;" class="fw-semibold text-danger">Translated birth certificate</div>
                                <small class="text-muted">Required — overdue since Feb 28</small>
                            </div>
                            <span class="badge bg-danger">Missing</span>
                            <button class="btn btn-sm btn-outline-danger"><i class="ri-notification-line me-1"></i>Remind</button>
                        </div>
                        <div class="list-group-item d-flex align-items-center gap-3 bg-danger bg-opacity-10">
                            <div class="doc-icon scan" style="width:36px;height:36px;font-size:1rem;"><i class="ri-shield-check-line"></i></div>
                            <div class="flex-grow-1">
                                <div style="font-size:.8rem;" class="fw-semibold text-danger">Insurance document</div>
                                <small class="text-muted">Required — requested Mar 1</small>
                            </div>
                            <span class="badge bg-danger">Missing</span>
                            <button class="btn btn-sm btn-outline-danger"><i class="ri-notification-line me-1"></i>Remind</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
