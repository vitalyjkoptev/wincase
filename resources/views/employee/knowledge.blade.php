@extends('partials.layouts.master-employee')
@section('title', 'Knowledge Base — WinCase Staff')
@section('page-title', 'Knowledge Base')

@section('css')
<style>
    .kb-card { border: 1px solid rgba(0,0,0,.06); border-radius: .75rem; padding: 1.25rem; transition: all .2s; cursor: pointer; height: 100%; }
    .kb-card:hover { border-color: #015EA7; transform: translateY(-2px); box-shadow: 0 4px 12px rgba(1,94,167,.08); }
    [data-bs-theme="dark"] .kb-card { border-color: rgba(255,255,255,.06); }
    .kb-icon { width: 48px; height: 48px; border-radius: .75rem; display: flex; align-items: center; justify-content: center; font-size: 1.4rem; margin-bottom: .75rem; }
    .kb-article { border-left: 3px solid transparent; padding: .75rem 1rem; transition: background .15s; cursor: pointer; }
    .kb-article:hover { background: rgba(1,94,167,.03); }
    .kb-article.popular { border-left-color: #015EA7; }
</style>
@endsection

@section('content')
<!-- Search -->
<div class="row mb-4">
    <div class="col-lg-8 mx-auto">
        <div class="text-center mb-3">
            <h4 class="mb-1"><i class="ri-book-open-line text-success me-2"></i><span data-lang="wc-staff-kb-title">Knowledge Base</span></h4>
            <p class="text-muted mb-0" data-lang="wc-staff-kb-subtitle">Find answers, procedures and templates</p>
        </div>
        <div class="input-group input-group-lg">
            <span class="input-group-text bg-transparent"><i class="ri-search-line"></i></span>
            <input type="text" class="form-control" placeholder="Search articles, procedures, templates..." id="kbSearch" data-lang-placeholder="wc-staff-kb-search">
        </div>
    </div>
</div>

<!-- Categories -->
<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3">
        <div class="kb-card text-center">
            <div class="kb-icon mx-auto" style="background:rgba(1,94,167,.1);color:#015EA7;"><i class="ri-file-list-3-line"></i></div>
            <h6 style="font-size:.9rem;" data-lang="wc-staff-procedures">Procedures</h6>
            <small class="text-muted">12 articles</small>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="kb-card text-center">
            <div class="kb-icon mx-auto" style="background:rgba(13,110,253,.1);color:#0d6efd;"><i class="ri-government-line"></i></div>
            <h6 style="font-size:.9rem;" data-lang="wc-staff-immigration-law">Immigration Law</h6>
            <small class="text-muted">18 articles</small>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="kb-card text-center">
            <div class="kb-icon mx-auto" style="background:rgba(253,126,20,.1);color:#fd7e14;"><i class="ri-draft-line"></i></div>
            <h6 style="font-size:.9rem;" data-lang="wc-staff-templates">Templates</h6>
            <small class="text-muted">24 templates</small>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="kb-card text-center">
            <div class="kb-icon mx-auto" style="background:rgba(111,66,193,.1);color:#6f42c1;"><i class="ri-question-answer-line"></i></div>
            <h6 style="font-size:.9rem;">FAQ</h6>
            <small class="text-muted">31 questions</small>
        </div>
    </div>
</div>

<div class="row g-3">
    <!-- Popular Articles -->
    <div class="col-lg-8">
        <div class="card mb-3">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="mb-0"><i class="ri-fire-line text-danger me-1"></i><span data-lang="wc-staff-popular-articles">Popular Articles</span></h6>
                <a href="#" class="btn btn-sm btn-light">View All</a>
            </div>
            <div class="list-group list-group-flush">
                <div class="list-group-item kb-article popular">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h6 style="font-size:.85rem;" class="mb-1"><i class="ri-article-line text-success me-1"></i>How to submit a Temporary Residence Permit application</h6>
                            <small class="text-muted">Step-by-step guide for TRP applications at the Voivodeship Office</small>
                        </div>
                        <div class="d-flex gap-1">
                            <span class="badge bg-success-subtle text-success">Procedure</span>
                            <span class="badge bg-light text-muted"><i class="ri-eye-line me-1"></i>142</span>
                        </div>
                    </div>
                </div>
                <div class="list-group-item kb-article popular">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h6 style="font-size:.85rem;" class="mb-1"><i class="ri-article-line text-success me-1"></i>Required documents checklist — Work Permit</h6>
                            <small class="text-muted">Complete list of documents needed for Type A work permit</small>
                        </div>
                        <div class="d-flex gap-1">
                            <span class="badge bg-primary-subtle text-primary">Checklist</span>
                            <span class="badge bg-light text-muted"><i class="ri-eye-line me-1"></i>98</span>
                        </div>
                    </div>
                </div>
                <div class="list-group-item kb-article popular">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h6 style="font-size:.85rem;" class="mb-1"><i class="ri-article-line text-success me-1"></i>EU Blue Card — eligibility and application process</h6>
                            <small class="text-muted">Requirements, salary thresholds, and processing timeline</small>
                        </div>
                        <div class="d-flex gap-1">
                            <span class="badge bg-info-subtle text-info">Law</span>
                            <span class="badge bg-light text-muted"><i class="ri-eye-line me-1"></i>87</span>
                        </div>
                    </div>
                </div>
                <div class="list-group-item kb-article">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h6 style="font-size:.85rem;" class="mb-1"><i class="ri-article-line text-muted me-1"></i>Fingerprint appointment preparation guide</h6>
                            <small class="text-muted">What to bring, what to expect, and common issues</small>
                        </div>
                        <div class="d-flex gap-1">
                            <span class="badge bg-success-subtle text-success">Procedure</span>
                            <span class="badge bg-light text-muted"><i class="ri-eye-line me-1"></i>65</span>
                        </div>
                    </div>
                </div>
                <div class="list-group-item kb-article">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h6 style="font-size:.85rem;" class="mb-1"><i class="ri-article-line text-muted me-1"></i>How to handle case status inquiries from clients</h6>
                            <small class="text-muted">Best practices for communicating delays and updates</small>
                        </div>
                        <div class="d-flex gap-1">
                            <span class="badge bg-warning-subtle text-warning">FAQ</span>
                            <span class="badge bg-light text-muted"><i class="ri-eye-line me-1"></i>54</span>
                        </div>
                    </div>
                </div>
                <div class="list-group-item kb-article">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h6 style="font-size:.85rem;" class="mb-1"><i class="ri-article-line text-muted me-1"></i>Bank statement requirements and acceptable formats</h6>
                            <small class="text-muted">Which bank statements are accepted, translations, certifications</small>
                        </div>
                        <div class="d-flex gap-1">
                            <span class="badge bg-primary-subtle text-primary">Checklist</span>
                            <span class="badge bg-light text-muted"><i class="ri-eye-line me-1"></i>43</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Templates -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="mb-0"><i class="ri-draft-line text-warning me-1"></i><span data-lang="wc-staff-doc-templates">Document Templates</span></h6>
                <a href="#" class="btn btn-sm btn-light">All Templates</a>
            </div>
            <div class="card-body">
                <div class="row g-2">
                    <div class="col-md-6">
                        <div class="d-flex align-items-center gap-2 p-2 rounded border" style="font-size:.8rem;">
                            <i class="ri-file-word-line fs-5 text-primary"></i>
                            <div class="flex-grow-1">
                                <div class="fw-semibold">Cover Letter — TRP Application</div>
                                <small class="text-muted">.docx &bull; Updated Feb 2026</small>
                            </div>
                            <button class="btn btn-sm btn-light"><i class="ri-download-line"></i></button>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex align-items-center gap-2 p-2 rounded border" style="font-size:.8rem;">
                            <i class="ri-file-word-line fs-5 text-primary"></i>
                            <div class="flex-grow-1">
                                <div class="fw-semibold">Employer Declaration</div>
                                <small class="text-muted">.docx &bull; Updated Jan 2026</small>
                            </div>
                            <button class="btn btn-sm btn-light"><i class="ri-download-line"></i></button>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex align-items-center gap-2 p-2 rounded border" style="font-size:.8rem;">
                            <i class="ri-file-pdf-2-line fs-5 text-danger"></i>
                            <div class="flex-grow-1">
                                <div class="fw-semibold">Wniosek o pobyt czasowy</div>
                                <small class="text-muted">.pdf &bull; Official form</small>
                            </div>
                            <button class="btn btn-sm btn-light"><i class="ri-download-line"></i></button>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex align-items-center gap-2 p-2 rounded border" style="font-size:.8rem;">
                            <i class="ri-file-excel-line fs-5 text-success"></i>
                            <div class="flex-grow-1">
                                <div class="fw-semibold">Document Checklist — Master</div>
                                <small class="text-muted">.xlsx &bull; Updated Mar 2026</small>
                            </div>
                            <button class="btn btn-sm btn-light"><i class="ri-download-line"></i></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="col-lg-4">
        <!-- Recently Updated -->
        <div class="card mb-3">
            <div class="card-header">
                <h6 class="mb-0"><i class="ri-time-line text-primary me-1"></i><span data-lang="wc-staff-recently-updated">Recently Updated</span></h6>
            </div>
            <div class="list-group list-group-flush">
                <a href="#" class="list-group-item list-group-item-action">
                    <div style="font-size:.8rem;" class="fw-semibold">Salary thresholds 2026 update</div>
                    <small class="text-muted">Updated Mar 1 &bull; Immigration Law</small>
                </a>
                <a href="#" class="list-group-item list-group-item-action">
                    <div style="font-size:.8rem;" class="fw-semibold">New appointment booking procedure</div>
                    <small class="text-muted">Updated Feb 28 &bull; Procedures</small>
                </a>
                <a href="#" class="list-group-item list-group-item-action">
                    <div style="font-size:.8rem;" class="fw-semibold">Document translation requirements</div>
                    <small class="text-muted">Updated Feb 25 &bull; Checklists</small>
                </a>
            </div>
        </div>

        <!-- Useful Links -->
        <div class="card mb-3">
            <div class="card-header">
                <h6 class="mb-0"><i class="ri-links-line text-info me-1"></i><span data-lang="wc-staff-useful-links">Useful Links</span></h6>
            </div>
            <div class="list-group list-group-flush">
                <a href="#" class="list-group-item list-group-item-action d-flex align-items-center gap-2" style="font-size:.8rem;">
                    <i class="ri-external-link-line text-muted"></i>
                    <span>Urząd Wojewódzki — Warsaw</span>
                </a>
                <a href="#" class="list-group-item list-group-item-action d-flex align-items-center gap-2" style="font-size:.8rem;">
                    <i class="ri-external-link-line text-muted"></i>
                    <span>Udsc.gov.pl — Office for Foreigners</span>
                </a>
                <a href="#" class="list-group-item list-group-item-action d-flex align-items-center gap-2" style="font-size:.8rem;">
                    <i class="ri-external-link-line text-muted"></i>
                    <span>ePUAP — Electronic government</span>
                </a>
                <a href="#" class="list-group-item list-group-item-action d-flex align-items-center gap-2" style="font-size:.8rem;">
                    <i class="ri-external-link-line text-muted"></i>
                    <span>ZUS — Social Insurance</span>
                </a>
            </div>
        </div>

        <!-- Tags -->
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0"><i class="ri-price-tag-3-line text-warning me-1"></i><span data-lang="wc-staff-tags">Tags</span></h6>
            </div>
            <div class="card-body">
                <div class="d-flex flex-wrap gap-1">
                    <span class="badge bg-light text-dark border">TRP</span>
                    <span class="badge bg-light text-dark border">Work Permit</span>
                    <span class="badge bg-light text-dark border">Blue Card</span>
                    <span class="badge bg-light text-dark border">Fingerprint</span>
                    <span class="badge bg-light text-dark border">Documents</span>
                    <span class="badge bg-light text-dark border">Bank Statement</span>
                    <span class="badge bg-light text-dark border">Voivodeship</span>
                    <span class="badge bg-light text-dark border">Translation</span>
                    <span class="badge bg-light text-dark border">PESEL</span>
                    <span class="badge bg-light text-dark border">Meldunek</span>
                    <span class="badge bg-light text-dark border">Insurance</span>
                    <span class="badge bg-light text-dark border">Appeal</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
document.getElementById('kbSearch').addEventListener('input', function(){
    var q = this.value.toLowerCase();
    document.querySelectorAll('.kb-article').forEach(function(a){
        a.style.display = a.textContent.toLowerCase().includes(q) ? '' : 'none';
    });
});
</script>
@endsection
