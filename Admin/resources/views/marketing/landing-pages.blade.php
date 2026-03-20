@extends('partials.layouts.master')
@section('title', 'Landing Pages | WinCase CRM')
@section('sub-title', 'Landing Pages')
@section('sub-title-lang', 'wc-landing-pages')
@section('pagetitle', 'Marketing')
@section('pagetitle-lang', 'wc-marketing')
@section('buttonTitle', 'Create Page')
@section('buttonTitle-lang', 'wc-create-page')
@section('modalTarget', 'createPageModal')
@section('content')

<!-- Stat Cards -->
<div class="row">
    <div class="col-xl-3 col-md-6">
        <div class="card card-h-100">
            <div class="card-body">
                <div class="d-flex align-items-center gap-3">
                    <div class="avatar avatar-sm bg-primary-subtle text-primary rounded-2"><i class="ri-pages-line fs-18"></i></div>
                    <div class="flex-grow-1">
                        <p class="text-muted mb-0 fs-13">Total Pages</p>
                        <h4 class="mb-0 fw-semibold" id="statPages">0</h4>
                    </div>
                    <span class="badge bg-success-subtle text-success"><i class="ri-arrow-up-s-line"></i> +2</span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card card-h-100">
            <div class="card-body">
                <div class="d-flex align-items-center gap-3">
                    <div class="avatar avatar-sm bg-success-subtle text-success rounded-2"><i class="ri-user-follow-line fs-18"></i></div>
                    <div class="flex-grow-1">
                        <p class="text-muted mb-0 fs-13">Total Leads</p>
                        <h4 class="mb-0 fw-semibold" id="statLeads">0</h4>
                    </div>
                    <span class="badge bg-success-subtle text-success"><i class="ri-arrow-up-s-line"></i> +18%</span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card card-h-100">
            <div class="card-body">
                <div class="d-flex align-items-center gap-3">
                    <div class="avatar avatar-sm bg-warning-subtle text-warning rounded-2"><i class="ri-flask-line fs-18"></i></div>
                    <div class="flex-grow-1">
                        <p class="text-muted mb-0 fs-13">A/B Tests Running</p>
                        <h4 class="mb-0 fw-semibold">2</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card card-h-100">
            <div class="card-body">
                <div class="d-flex align-items-center gap-3">
                    <div class="avatar avatar-sm bg-info-subtle text-info rounded-2"><i class="ri-line-chart-line fs-18"></i></div>
                    <div class="flex-grow-1">
                        <p class="text-muted mb-0 fs-13">Avg Conversion</p>
                        <h4 class="mb-0 fw-semibold" id="statAvgConv">0%</h4>
                    </div>
                    <span class="badge bg-success-subtle text-success"><i class="ri-arrow-up-s-line"></i> +0.4%</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Tabs -->
<div class="card">
    <div class="card-header pb-0">
        <ul class="nav nav-tabs nav-tabs-custom" role="tablist">
            <li class="nav-item"><a class="nav-link active lp-tab" data-tab="pages" href="javascript:void(0)"><i class="ri-pages-line me-1"></i>Pages <span class="badge bg-primary rounded-pill ms-1" id="pagesCount">0</span></a></li>
            <li class="nav-item"><a class="nav-link lp-tab" data-tab="leads" href="javascript:void(0)"><i class="ri-user-follow-line me-1"></i>Form Submissions <span class="badge bg-success rounded-pill ms-1" id="leadsCount">0</span></a></li>
            <li class="nav-item"><a class="nav-link lp-tab" data-tab="abtests" href="javascript:void(0)"><i class="ri-flask-line me-1"></i>A/B Tests</a></li>
        </ul>
    </div>
</div>

<!-- Pages Section -->
<div class="lp-section active" id="section-pages">
    <div class="card">
        <div class="card-header">
            <div class="row align-items-center g-2">
                <div class="col-auto">
                    <div class="d-flex gap-1 flex-wrap">
                        <button class="btn btn-sm btn-primary page-filter" data-filter="all">All</button>
                        <button class="btn btn-sm btn-light page-filter" data-filter="active">Active</button>
                        <button class="btn btn-sm btn-light page-filter" data-filter="draft">Draft</button>
                        <button class="btn btn-sm btn-light page-filter" data-filter="paused">Paused</button>
                    </div>
                </div>
                <div class="col-auto">
                    <select class="form-select form-select-sm" id="pageDomainFilter" style="width:160px">
                        <option value="">All Domains</option>
                        <option value="wincase.eu">wincase.eu</option>
                        <option value="wincasejobs.com">wincasejobs.com</option>
                        <option value="wincase.pro">wincase.pro</option>
                    </select>
                </div>
                <div class="col">
                    <input type="text" class="form-control form-control-sm" id="pageSearch" placeholder="Search pages...">
                </div>
                <div class="col-auto">
                    <button class="btn btn-sm btn-outline-success" onclick="exportPages()"><i class="ri-download-2-line me-1"></i>Export</button>
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Page Name</th>
                            <th>URL</th>
                            <th class="text-center">Visitors</th>
                            <th class="text-center">Leads</th>
                            <th class="text-center">Conv %</th>
                            <th class="text-center">Bounce</th>
                            <th class="text-center">Speed</th>
                            <th>Status</th>
                            <th>Modified</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="pagesBody"></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Leads Section -->
<div class="lp-section" id="section-leads">
    <div class="card">
        <div class="card-header">
            <div class="row align-items-center g-2">
                <div class="col-auto">
                    <div class="d-flex gap-1">
                        <button class="btn btn-sm btn-primary lead-filter" data-filter="all">All</button>
                        <button class="btn btn-sm btn-light lead-filter" data-filter="new">New</button>
                        <button class="btn btn-sm btn-light lead-filter" data-filter="contacted">Contacted</button>
                        <button class="btn btn-sm btn-light lead-filter" data-filter="converted">Converted</button>
                    </div>
                </div>
                <div class="col-auto">
                    <select class="form-select form-select-sm" id="leadPageFilter" style="width:180px">
                        <option value="">All Pages</option>
                    </select>
                </div>
                <div class="col">
                    <input type="text" class="form-control form-control-sm" id="leadSearch" placeholder="Search leads...">
                </div>
                <div class="col-auto">
                    <button class="btn btn-sm btn-outline-success" onclick="exportLeads()"><i class="ri-download-2-line me-1"></i>Export</button>
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Name</th>
                            <th>Email / Phone</th>
                            <th>Landing Page</th>
                            <th>Service</th>
                            <th>Source</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="leadsBody"></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- A/B Tests Section -->
<div class="lp-section" id="section-abtests">
    <div class="card">
        <div class="card-header d-flex align-items-center">
            <h5 class="card-title mb-0 flex-grow-1"><i class="ri-flask-line me-2"></i>A/B Tests</h5>
            <button class="btn btn-sm btn-primary" onclick="showNewTest()"><i class="ri-add-line me-1"></i>New Test</button>
        </div>
        <div class="card-body" id="abTestsContainer"></div>
    </div>
</div>

<!-- Charts -->
<div class="row">
    <div class="col-xl-6">
        <div class="card">
            <div class="card-header"><h5 class="card-title mb-0">Conversions by Page</h5></div>
            <div class="card-body"><div id="convByPageChart" style="height:320px"></div></div>
        </div>
    </div>
    <div class="col-xl-6">
        <div class="card">
            <div class="card-header"><h5 class="card-title mb-0">Traffic Trend (Last 6 Months)</h5></div>
            <div class="card-body"><div id="trafficTrendChart" style="height:320px"></div></div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-xl-4">
        <div class="card">
            <div class="card-header"><h5 class="card-title mb-0">Traffic Sources</h5></div>
            <div class="card-body"><div id="trafficSourcesChart" style="height:300px"></div></div>
        </div>
    </div>
    <div class="col-xl-4">
        <div class="card">
            <div class="card-header"><h5 class="card-title mb-0">Device Breakdown</h5></div>
            <div class="card-body"><div id="deviceChart" style="height:300px"></div></div>
        </div>
    </div>
    <div class="col-xl-4">
        <div class="card">
            <div class="card-header"><h5 class="card-title mb-0">Top Converting Pages</h5></div>
            <div class="card-body" id="topConvPages"></div>
        </div>
    </div>
</div>

<!-- Quick Links -->
<div class="row mb-3">
    <div class="col-12">
        <div class="d-flex gap-2 flex-wrap">
            <a href="/marketing-advertising" class="btn btn-sm btn-outline-primary"><i class="ri-megaphone-line me-1"></i>Advertising</a>
            <a href="/marketing-seo" class="btn btn-sm btn-outline-success"><i class="ri-search-eye-line me-1"></i>SEO</a>
            <a href="/marketing-social-media" class="btn btn-sm btn-outline-info"><i class="ri-share-line me-1"></i>Social Media</a>
            <a href="/marketing-brand" class="btn btn-sm btn-outline-warning"><i class="ri-star-line me-1"></i>Brand & Reputation</a>
        </div>
    </div>
</div>

<!-- View Page Modal -->
<div class="modal fade" id="viewPageModal" tabindex="-1"><div class="modal-dialog modal-xl"><div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title"><i class="ri-pages-line me-2"></i>Page Analytics</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
    </div>
    <div class="modal-body">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <div>
                <h5 class="mb-1" id="vpName"></h5>
                <a href="#" target="_blank" class="text-primary fs-13" id="vpUrl"></a>
            </div>
            <span id="vpStatus"></span>
        </div>
        <div class="row g-3 mb-4">
            <div class="col-md-2 col-4"><div class="bg-primary-subtle rounded p-3 text-center"><h4 class="mb-0 text-primary" id="vpVisitors">0</h4><small class="text-muted">Visitors</small></div></div>
            <div class="col-md-2 col-4"><div class="bg-success-subtle rounded p-3 text-center"><h4 class="mb-0 text-success" id="vpLeads">0</h4><small class="text-muted">Leads</small></div></div>
            <div class="col-md-2 col-4"><div class="bg-warning-subtle rounded p-3 text-center"><h4 class="mb-0 text-warning" id="vpConv">0%</h4><small class="text-muted">Conv Rate</small></div></div>
            <div class="col-md-2 col-4"><div class="bg-danger-subtle rounded p-3 text-center"><h4 class="mb-0 text-danger" id="vpBounce">0%</h4><small class="text-muted">Bounce</small></div></div>
            <div class="col-md-2 col-4"><div class="bg-info-subtle rounded p-3 text-center"><h4 class="mb-0 text-info" id="vpAvgTime">0s</h4><small class="text-muted">Avg Time</small></div></div>
            <div class="col-md-2 col-4"><div class="border rounded p-3 text-center"><h4 class="mb-0" id="vpSpeed">0</h4><small class="text-muted">Speed Score</small></div></div>
        </div>
        <div class="row g-3 mb-3">
            <div class="col-md-4">
                <div class="border rounded p-3">
                    <h6 class="text-muted mb-2">Traffic Sources</h6>
                    <div id="vpSources"></div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="border rounded p-3">
                    <h6 class="text-muted mb-2">UTM Campaigns</h6>
                    <div id="vpUtm"></div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="border rounded p-3">
                    <h6 class="text-muted mb-2">SEO Info</h6>
                    <div id="vpSeo"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button class="btn btn-outline-warning btn-sm" onclick="editFromView()"><i class="ri-edit-line me-1"></i>Edit</button>
        <a href="#" target="_blank" class="btn btn-outline-info btn-sm" id="vpVisitBtn"><i class="ri-external-link-line me-1"></i>Visit Page</a>
        <button class="btn btn-light" data-bs-dismiss="modal">Close</button>
    </div>
</div></div></div>

<!-- Edit Page Modal -->
<div class="modal fade" id="editPageModal" tabindex="-1"><div class="modal-dialog modal-lg"><div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title"><i class="ri-edit-line me-2"></i>Edit Landing Page</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
    </div>
    <div class="modal-body">
        <input type="hidden" id="editIdx">
        <div class="row">
            <div class="col-md-8 mb-3"><label class="form-label">Page Name</label><input type="text" class="form-control" id="editName"></div>
            <div class="col-md-4 mb-3"><label class="form-label">Status</label>
                <select class="form-select" id="editStatus"><option value="active">Active</option><option value="draft">Draft</option><option value="paused">Paused</option></select>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-3"><label class="form-label">Slug</label><input type="text" class="form-control" id="editSlug"></div>
            <div class="col-md-6 mb-3"><label class="form-label">Domain</label>
                <select class="form-select" id="editDomain"><option value="wincase.eu">wincase.eu</option><option value="wincasejobs.com">wincasejobs.com</option><option value="wincase.pro">wincase.pro</option></select>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-3"><label class="form-label">Template</label>
                <select class="form-select" id="editTemplate"><option value="service">Service</option><option value="consultation">Consultation</option><option value="lead-magnet">Lead Magnet</option><option value="webinar">Webinar</option><option value="promo">Promo</option><option value="blank">Blank</option></select>
            </div>
            <div class="col-md-6 mb-3"><label class="form-label">Campaign</label><input type="text" class="form-control" id="editCampaign"></div>
        </div>
        <hr>
        <h6 class="fw-semibold mb-3">SEO Settings</h6>
        <div class="mb-3"><label class="form-label">Meta Title</label><input type="text" class="form-control" id="editMetaTitle"><small class="text-muted"><span id="editMetaTitleCount">0</span>/60 characters</small></div>
        <div class="mb-3"><label class="form-label">Meta Description</label><textarea class="form-control" id="editMetaDesc" rows="2"></textarea><small class="text-muted"><span id="editMetaDescCount">0</span>/160 characters</small></div>
        <div class="mb-3"><label class="form-label">Keywords</label><input type="text" class="form-control" id="editKeywords" placeholder="pozwolenie na prace, work permit, zezwolenie"></div>
    </div>
    <div class="modal-footer">
        <button class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
        <button class="btn btn-primary" onclick="saveEdit()"><i class="ri-check-line me-1"></i>Save Changes</button>
    </div>
</div></div></div>

<!-- Create Page Modal -->
<div class="modal fade" id="createPageModal" tabindex="-1"><div class="modal-dialog modal-lg"><div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title"><i class="ri-pages-line me-2"></i>Create Landing Page</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
    </div>
    <div class="modal-body">
        <div class="row">
            <div class="col-md-8 mb-3"><label class="form-label">Page Name</label><input type="text" class="form-control" id="cpName" placeholder="e.g. Pozwolenie na prace"></div>
            <div class="col-md-4 mb-3"><label class="form-label">Slug</label><input type="text" class="form-control" id="cpSlug" placeholder="pozwolenie-na-prace"></div>
        </div>
        <div class="row">
            <div class="col-md-4 mb-3"><label class="form-label">Domain</label>
                <select class="form-select" id="cpDomain"><option value="wincase.eu">wincase.eu</option><option value="wincasejobs.com">wincasejobs.com</option><option value="wincase.pro">wincase.pro</option></select>
            </div>
            <div class="col-md-4 mb-3"><label class="form-label">Template</label>
                <select class="form-select" id="cpTemplate"><option value="service">Service</option><option value="consultation">Consultation</option><option value="lead-magnet">Lead Magnet</option><option value="webinar">Webinar</option><option value="promo">Promo</option><option value="blank">Blank</option></select>
            </div>
            <div class="col-md-4 mb-3"><label class="form-label">Campaign</label><input type="text" class="form-control" id="cpCampaign" placeholder="Optional"></div>
        </div>
        <hr>
        <h6 class="fw-semibold mb-3">SEO Settings</h6>
        <div class="mb-3"><label class="form-label">Meta Title</label><input type="text" class="form-control" id="cpMetaTitle" placeholder="SEO title (max 60 characters)"></div>
        <div class="mb-3"><label class="form-label">Meta Description</label><textarea class="form-control" id="cpMetaDesc" rows="2" placeholder="SEO description (max 160 characters)"></textarea></div>
    </div>
    <div class="modal-footer">
        <button class="btn btn-outline-secondary" onclick="createAsDraft()"><i class="ri-draft-line me-1"></i>Save Draft</button>
        <button class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
        <button class="btn btn-primary" onclick="createPage('active')"><i class="ri-add-line me-1"></i>Create & Publish</button>
    </div>
</div></div></div>

<!-- View Lead Modal -->
<div class="modal fade" id="viewLeadModal" tabindex="-1"><div class="modal-dialog"><div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title"><i class="ri-user-follow-line me-2"></i>Lead Details</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
    </div>
    <div class="modal-body">
        <table class="table table-sm table-borderless">
            <tr><td class="text-muted" style="width:120px">Name</td><td id="vlName" class="fw-semibold"></td></tr>
            <tr><td class="text-muted">Email</td><td id="vlEmail"></td></tr>
            <tr><td class="text-muted">Phone</td><td id="vlPhone"></td></tr>
            <tr><td class="text-muted">Service</td><td id="vlService"></td></tr>
            <tr><td class="text-muted">Landing Page</td><td id="vlPage"></td></tr>
            <tr><td class="text-muted">Source</td><td id="vlSource"></td></tr>
            <tr><td class="text-muted">Status</td><td id="vlStatus"></td></tr>
            <tr><td class="text-muted">Date</td><td id="vlDate"></td></tr>
            <tr><td class="text-muted">Message</td><td id="vlMessage"></td></tr>
        </table>
    </div>
    <div class="modal-footer">
        <button class="btn btn-outline-success btn-sm" onclick="convertLead()"><i class="ri-user-add-line me-1"></i>Convert to CRM Lead</button>
        <button class="btn btn-light" data-bs-dismiss="modal">Close</button>
    </div>
</div></div></div>

<!-- New A/B Test Modal -->
<div class="modal fade" id="newTestModal" tabindex="-1"><div class="modal-dialog"><div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title"><i class="ri-flask-line me-2"></i>New A/B Test</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
    </div>
    <div class="modal-body">
        <div class="mb-3"><label class="form-label">Test Name</label><input type="text" class="form-control" id="ntName" placeholder="e.g. CTA Button Color Test"></div>
        <div class="mb-3"><label class="form-label">Landing Page</label>
            <select class="form-select" id="ntPage"></select>
        </div>
        <div class="mb-3"><label class="form-label">Element to Test</label>
            <select class="form-select" id="ntElement"><option value="cta">CTA Button</option><option value="hero">Hero Image</option><option value="headline">Headline</option><option value="form">Form Layout</option><option value="pricing">Pricing Display</option></select>
        </div>
        <div class="row">
            <div class="col-md-6 mb-3"><label class="form-label">Variant A</label><input type="text" class="form-control" id="ntVarA" placeholder="Original"></div>
            <div class="col-md-6 mb-3"><label class="form-label">Variant B</label><input type="text" class="form-control" id="ntVarB" placeholder="New version"></div>
        </div>
        <div class="mb-3"><label class="form-label">Traffic Split</label>
            <select class="form-select" id="ntSplit"><option value="50/50">50% / 50%</option><option value="70/30">70% / 30%</option><option value="80/20">80% / 20%</option></select>
        </div>
    </div>
    <div class="modal-footer">
        <button class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
        <button class="btn btn-primary" onclick="createTest()"><i class="ri-play-line me-1"></i>Start Test</button>
    </div>
</div></div></div>

@endsection

@section('js')
<script src="{{ asset('assets/libs/apexcharts/apexcharts.min.js') }}"></script>
<script>
function toast(msg,type='success'){const t=document.createElement('div');t.className=`alert alert-${type} position-fixed top-0 end-0 m-3 shadow`;t.style.zIndex='9999';t.innerHTML=msg;document.body.appendChild(t);setTimeout(()=>{t.style.opacity='0';setTimeout(()=>t.remove(),300)},3000);}
function n(v){return v?v.toLocaleString('pl-PL'):'0';}

// ── Pages Data ──
const pages = [
    {id:'lp1',name:'Pozwolenie na prace',slug:'pozwolenie-na-prace',domain:'wincase.eu',status:'active',template:'service',campaign:'Google Ads — Work Permit',visitors:4820,leads:218,bounce:38,avgTime:'2:45',speed:92,modified:'2026-02-28',metaTitle:'Pozwolenie na prace w Polsce — WinCase',metaDesc:'Uzyskaj pozwolenie na prace w Polsce szybko i bezproblemowo. Kompleksowa pomoc prawna.',keywords:'pozwolenie na prace, work permit poland, zezwolenie na prace',sources:{google:42,facebook:28,direct:15,referral:10,organic:5},utms:['google_ads_work','fb_work_permit_2026','newsletter_feb']},
    {id:'lp2',name:'Karta pobytu',slug:'karta-pobytu',domain:'wincase.eu',status:'active',template:'service',campaign:'Google Ads — Karta Pobytu',visitors:3950,leads:172,bounce:35,avgTime:'3:12',speed:89,modified:'2026-02-26',metaTitle:'Karta pobytu — pomoc w uzyskaniu | WinCase',metaDesc:'Profesjonalna pomoc w uzyskaniu karty pobytu w Polsce. Doswiadczony zespol prawnikow.',keywords:'karta pobytu, residence card, pobyt czasowy',sources:{google:45,facebook:22,direct:18,referral:8,organic:7},utms:['google_ads_karta','fb_karta_2026']},
    {id:'lp3',name:'Rejestracja firmy dla obcokrajowcow',slug:'rejestracja-firmy',domain:'wincase.eu',status:'active',template:'service',campaign:'LinkedIn B2B',visitors:1850,leads:68,bounce:42,avgTime:'2:30',speed:94,modified:'2026-02-22',metaTitle:'Rejestracja firmy w Polsce — obcokrajowcy | WinCase',metaDesc:'Zaloz firme w Polsce jako obcokrajowiec. Pomoc prawna od A do Z.',keywords:'rejestracja firmy, zalozenie spolki, firma dla obcokrajowcow',sources:{google:35,linkedin:30,direct:20,referral:10,organic:5},utms:['linkedin_b2b','google_ads_firma']},
    {id:'lp4',name:'Darmowa konsultacja',slug:'darmowa-konsultacja',domain:'wincase.eu',status:'active',template:'consultation',campaign:'Facebook Lead Gen',visitors:5200,leads:312,bounce:28,avgTime:'1:55',speed:96,modified:'2026-02-25',metaTitle:'Darmowa konsultacja imigracyjna — WinCase',metaDesc:'Umow sie na darmowa konsultacje. Sprawdz swoje mozliwosci legalizacji pobytu.',keywords:'darmowa konsultacja, konsultacja imigracyjna, porada prawna',sources:{facebook:45,google:25,instagram:15,direct:10,referral:5},utms:['fb_free_consultation','ig_consultation','google_brand']},
    {id:'lp5',name:'EU Blue Card',slug:'eu-blue-card',domain:'wincase.eu',status:'paused',template:'service',campaign:'—',visitors:890,leads:24,bounce:52,avgTime:'2:10',speed:88,modified:'2026-01-30',metaTitle:'EU Blue Card — wysoko wykwalifikowani pracownicy | WinCase',metaDesc:'Uzyskaj EU Blue Card w Polsce. Dla specjalistow IT, inzynierow i menedzerow.',keywords:'eu blue card, niebieska karta, blue card polska',sources:{google:55,direct:25,referral:15,organic:5},utms:['google_ads_bluecard']},
    {id:'lp6',name:'Obywatelstwo polskie',slug:'obywatelstwo-polskie',domain:'wincase.eu',status:'active',template:'service',campaign:'Google Ads — Citizenship',visitors:1420,leads:45,bounce:40,avgTime:'3:30',speed:91,modified:'2026-02-20',metaTitle:'Obywatelstwo polskie — jak uzyskac | WinCase',metaDesc:'Kompleksowa pomoc w uzyskaniu obywatelstwa polskiego. Sprawdz wymagania.',keywords:'obywatelstwo polskie, citizenship poland, naturalizacja',sources:{google:50,direct:20,referral:15,facebook:10,organic:5},utms:['google_ads_citizenship']},
    {id:'lp7',name:'Praca w Polsce — oferty',slug:'praca-w-polsce',domain:'wincasejobs.com',status:'active',template:'lead-magnet',campaign:'Job Portal',visitors:6800,leads:420,bounce:32,avgTime:'4:15',speed:87,modified:'2026-02-27',metaTitle:'Praca w Polsce dla obcokrajowcow — WinCase Jobs',metaDesc:'Znajdz legalna prace w Polsce. Oferty z pozwoleniem na prace.',keywords:'praca w Polsce, praca dla obcokrajowcow, job Poland',sources:{google:40,direct:25,referral:20,facebook:10,tiktok:5},utms:['google_jobs','fb_jobs','tiktok_jobs']},
    {id:'lp8',name:'Webinar: Legalizacja 2026',slug:'webinar-legalizacja-2026',domain:'wincase.eu',status:'active',template:'webinar',campaign:'Webinar Series',visitors:1200,leads:185,bounce:22,avgTime:'1:40',speed:95,modified:'2026-02-24',metaTitle:'Bezplatny webinar: Legalizacja pobytu 2026 | WinCase',metaDesc:'Zapisz sie na webinar o zmianach w prawie imigracyjnym 2026.',keywords:'webinar legalizacja, webinar imigracja, zmiany prawo 2026',sources:{facebook:35,email:30,instagram:15,google:10,telegram:10},utms:['fb_webinar','email_webinar','ig_webinar']},
    {id:'lp9',name:'Checklist: Dokumenty karta pobytu',slug:'checklist-karta-pobytu',domain:'wincase.eu',status:'active',template:'lead-magnet',campaign:'Lead Magnet',visitors:2300,leads:890,bounce:18,avgTime:'1:20',speed:98,modified:'2026-02-23',metaTitle:'Checklist dokumentow — karta pobytu | WinCase',metaDesc:'Pobierz darmowy checklist dokumentow potrzebnych do karty pobytu.',keywords:'checklist karta pobytu, dokumenty karta pobytu, lista dokumentow',sources:{google:30,facebook:25,instagram:20,pinterest:15,referral:10},utms:['fb_checklist','ig_checklist','pinterest_checklist']},
    {id:'lp10',name:'Wiza pracownicza',slug:'wiza-pracownicza',domain:'wincase.pro',status:'draft',template:'service',campaign:'—',visitors:0,leads:0,bounce:0,avgTime:'0:00',speed:0,modified:'2026-03-01',metaTitle:'',metaDesc:'',keywords:'',sources:{},utms:[]},
    {id:'lp11',name:'Legalizacja pobytu — pakiety',slug:'pakiety-legalizacja',domain:'wincase.eu',status:'active',template:'promo',campaign:'Spring Promo 2026',visitors:1650,leads:78,bounce:35,avgTime:'2:55',speed:90,modified:'2026-02-21',metaTitle:'Pakiety legalizacji pobytu — promocja | WinCase',metaDesc:'Wybierz pakiet legalizacji pobytu. Promocyjne ceny na wiosne 2026.',keywords:'pakiety legalizacja, cennik legalizacja, promocja imigracja',sources:{google:35,facebook:30,email:20,direct:10,referral:5},utms:['fb_promo_spring','google_promo','email_spring']},
    {id:'lp12',name:'Rekrutacja miedzynarodowa — B2B',slug:'rekrutacja-b2b',domain:'wincasejobs.com',status:'active',template:'consultation',campaign:'LinkedIn B2B',visitors:980,leads:42,bounce:45,avgTime:'3:00',speed:93,modified:'2026-02-18',metaTitle:'Rekrutacja miedzynarodowa dla firm | WinCase Jobs',metaDesc:'Kompleksowa obsluga rekrutacji pracownikow z zagranicy.',keywords:'rekrutacja miedzynarodowa, outsourcing pracownikow, B2B imigracja',sources:{linkedin:45,google:25,direct:20,referral:10},utms:['linkedin_b2b_recruit','google_b2b']},
];

// ── Form Submissions (Leads) ──
const leads = [
    {id:'fl1',name:'Olena Kovalenko',email:'olena.k@gmail.com',phone:'+48 512 345 678',page:'lp4',service:'Darmowa konsultacja',source:'Facebook Ads',status:'converted',date:'2026-03-01',message:'Potrzebuje pomocy z karta pobytu dla mnie i meza.'},
    {id:'fl2',name:'Ahmed Al-Rashid',email:'ahmed.ar@outlook.com',phone:'+48 600 111 222',page:'lp1',service:'Pozwolenie na prace',source:'Google Ads',status:'contacted',date:'2026-03-01',message:'Moj pracodawca potrzebuje pozwolenia na prace typu A.'},
    {id:'fl3',name:'Nguyen Van Tuan',email:'tuan.ng@yahoo.com',phone:'+48 515 444 333',page:'lp2',service:'Karta pobytu',source:'Google Organic',status:'new',date:'2026-02-28',message:'Chcialbym przedluzyc karte pobytu — wygasa za 2 miesiace.'},
    {id:'fl4',name:'Maria Santos',email:'maria.s@gmail.com',phone:'+48 790 555 666',page:'lp9',service:'Checklist download',source:'Instagram',status:'new',date:'2026-02-28',message:''},
    {id:'fl5',name:'Katarzyna Nowak',email:'k.nowak@firma.pl',phone:'+48 502 777 888',page:'lp12',service:'Rekrutacja B2B',source:'LinkedIn',status:'contacted',date:'2026-02-27',message:'Planujemy zatrudnic 20 pracownikow z Ukrainy w Q2.'},
    {id:'fl6',name:'Igor Petrov',email:'igor.p@mail.ua',phone:'+48 518 999 000',page:'lp4',service:'Darmowa konsultacja',source:'Facebook Ads',status:'converted',date:'2026-02-27',message:'Pytanie o mozliwosc uzyskania stalego pobytu po 5 latach.'},
    {id:'fl7',name:'Li Wei',email:'li.wei@company.cn',phone:'+48 600 222 333',page:'lp3',service:'Rejestracja firmy',source:'Google Ads',status:'contacted',date:'2026-02-26',message:'Want to register IT company in Poland. Need full legal support.'},
    {id:'fl8',name:'Fatima Hassan',email:'fatima.h@gmail.com',phone:'+48 512 444 555',page:'lp8',service:'Webinar signup',source:'Facebook',status:'converted',date:'2026-02-26',message:''},
    {id:'fl9',name:'Raj Patel',email:'raj.p@outlook.com',phone:'+48 790 666 777',page:'lp1',service:'Pozwolenie na prace',source:'Google Ads',status:'new',date:'2026-02-25',message:'My wife needs work permit. She is a dentist.'},
    {id:'fl10',name:'Anna Zelinska',email:'anna.z@wp.pl',phone:'+48 502 888 999',page:'lp6',service:'Obywatelstwo polskie',source:'Google Organic',status:'new',date:'2026-02-25',message:'Mieszkam w Polsce 10 lat, chce uzyskac obywatelstwo.'},
    {id:'fl11',name:'Carlos Rodriguez',email:'carlos.r@gmail.com',phone:'+48 515 111 222',page:'lp7',service:'Praca w Polsce',source:'TikTok',status:'new',date:'2026-02-24',message:'Looking for IT jobs in Warsaw with work permit sponsorship.'},
    {id:'fl12',name:'Svitlana Melnyk',email:'svitlana.m@ukr.net',phone:'+48 600 333 444',page:'lp9',service:'Checklist download',source:'Pinterest',status:'converted',date:'2026-02-24',message:''},
    {id:'fl13',name:'Piotr Kowalczyk',email:'p.kowalczyk@firma.pl',phone:'+48 518 555 666',page:'lp12',service:'Rekrutacja B2B',source:'LinkedIn',status:'contacted',date:'2026-02-23',message:'Firma budowlana — potrzebujemy 50 pracownikow z Nepalu.'},
    {id:'fl14',name:'Elena Popova',email:'elena.pop@gmail.com',phone:'+48 512 777 888',page:'lp4',service:'Darmowa konsultacja',source:'Instagram',status:'new',date:'2026-02-22',message:'Chce sie dowiedziec o mozliwosciach pracy na wlasna reke.'},
    {id:'fl15',name:'Thanh Nguyen',email:'thanh.n@gmail.com',phone:'+48 790 999 000',page:'lp2',service:'Karta pobytu',source:'Google Ads',status:'contacted',date:'2026-02-21',message:'Karta pobytu czasowa na podstawie pracy.'},
    {id:'fl16',name:'Dmitro Bondarenko',email:'dmitro.b@outlook.com',phone:'+48 502 111 222',page:'lp8',service:'Webinar signup',source:'Telegram',status:'converted',date:'2026-02-20',message:''},
];

// ── A/B Tests ──
const abTests = [
    {id:'t1',name:'CTA Button — Pozwolenie na prace',page:'lp1',element:'cta',status:'running',split:'50/50',varA:{name:'Original: "Rozpocznij teraz"',conv:4.2,visitors:2410},varB:{name:'New: "Uzyskaj pozwolenie"',conv:5.1,visitors:2410},startDate:'2026-02-15',confidence:94},
    {id:'t2',name:'Hero Image — Karta pobytu',page:'lp2',element:'hero',status:'running',split:'50/50',varA:{name:'Photo (office)',conv:4.5,visitors:1975},varB:{name:'Illustration (process)',conv:3.8,visitors:1975},startDate:'2026-02-18',confidence:87},
    {id:'t3',name:'Headline — Darmowa konsultacja',page:'lp4',element:'headline',status:'completed',split:'50/50',varA:{name:'"Umow sie na konsultacje"',conv:5.2,visitors:2600},varB:{name:'"Bezplatna porada prawna"',conv:6.8,visitors:2600},startDate:'2026-01-20',confidence:98,winner:'B'},
    {id:'t4',name:'Form Layout — Webinar',page:'lp8',element:'form',status:'completed',split:'50/50',varA:{name:'2-step form',conv:14.2,visitors:600},varB:{name:'1-step form',conv:16.5,visitors:600},startDate:'2026-02-01',confidence:92,winner:'B'},
];

let currentPageIdx = -1;
let currentLeadIdx = -1;

// ── Tabs ──
document.querySelectorAll('.lp-tab').forEach(tab=>{
    tab.addEventListener('click',function(){
        document.querySelectorAll('.lp-tab').forEach(t=>t.classList.remove('active'));
        this.classList.add('active');
        document.querySelectorAll('.lp-section').forEach(s=>s.classList.remove('active'));
        document.getElementById('section-'+this.dataset.tab).classList.add('active');
    });
});

// ── Render Pages ──
function renderPages(){
    const filter=document.querySelector('.page-filter.btn-primary')?.dataset.filter||'all';
    const df=document.getElementById('pageDomainFilter').value;
    const search=document.getElementById('pageSearch').value.toLowerCase();
    let filtered=pages.filter(p=>{
        if(filter!=='all'&&p.status!==filter) return false;
        if(df&&p.domain!==df) return false;
        if(search&&!p.name.toLowerCase().includes(search)&&!p.slug.toLowerCase().includes(search)) return false;
        return true;
    });
    document.getElementById('pagesCount').textContent=filtered.length;
    document.getElementById('statPages').textContent=pages.length;
    const activePages=pages.filter(p=>p.status==='active');
    const totalLeads=pages.reduce((a,p)=>a+p.leads,0);
    document.getElementById('statLeads').textContent=n(totalLeads);
    const avgConv=activePages.length?activePages.reduce((a,p)=>a+(p.visitors>0?p.leads/p.visitors*100:0),0)/activePages.length:0;
    document.getElementById('statAvgConv').textContent=avgConv.toFixed(1)+'%';
    const tbody=document.getElementById('pagesBody');
    if(!filtered.length){tbody.innerHTML='<tr><td colspan="10" class="text-center text-muted py-4">No pages found</td></tr>';return;}
    tbody.innerHTML=filtered.map((p,i)=>{
        const idx=pages.indexOf(p);
        const conv=p.visitors>0?(p.leads/p.visitors*100).toFixed(1):0;
        const convClass=conv>=4?'success':conv>=2.5?'warning':'danger';
        const stBadge=p.status==='active'?'<span class="badge bg-success-subtle text-success">Active</span>':p.status==='draft'?'<span class="badge bg-secondary-subtle text-secondary">Draft</span>':'<span class="badge bg-warning-subtle text-warning">Paused</span>';
        const speedClass=p.speed>=90?'success':p.speed>=70?'warning':'danger';
        const url=p.domain+'/'+p.slug;
        return `<tr data-idx="${idx}">
            <td><span class="fw-medium">${p.name}</span><br><small class="text-muted">${p.template}</small></td>
            <td><code class="fs-12">${url}</code></td>
            <td class="text-center">${p.visitors>0?n(p.visitors):'—'}</td>
            <td class="text-center">${p.leads>0?n(p.leads):'—'}</td>
            <td class="text-center"><span class="fw-semibold text-${convClass}">${conv}%</span></td>
            <td class="text-center">${p.bounce>0?p.bounce+'%':'—'}</td>
            <td class="text-center">${p.speed>0?`<span class="badge bg-${speedClass}-subtle text-${speedClass}">${p.speed}</span>`:'—'}</td>
            <td>${stBadge}</td>
            <td>${p.modified}</td>
            <td><div class="d-flex gap-1">
                <button class="btn btn-sm btn-soft-primary btn-view-page" title="Analytics"><i class="ri-bar-chart-line"></i></button>
                <button class="btn btn-sm btn-soft-warning btn-edit-page" title="Edit"><i class="ri-edit-line"></i></button>
                <button class="btn btn-sm btn-soft-info btn-dup-page" title="Duplicate"><i class="ri-file-copy-line"></i></button>
                ${p.status==='active'?`<button class="btn btn-sm btn-soft-danger btn-pause-page" title="Pause"><i class="ri-pause-line"></i></button>`:p.status==='paused'?`<button class="btn btn-sm btn-soft-success btn-activate-page" title="Activate"><i class="ri-play-line"></i></button>`:p.status==='draft'?`<button class="btn btn-sm btn-soft-success btn-activate-page" title="Publish"><i class="ri-send-plane-line"></i></button>`:''}
            </div></td>
        </tr>`;
    }).join('');
}
document.querySelectorAll('.page-filter').forEach(pill=>{
    pill.addEventListener('click',function(){
        document.querySelectorAll('.page-filter').forEach(p=>{p.classList.remove('btn-primary');p.classList.add('btn-light');});
        this.classList.remove('btn-light');this.classList.add('btn-primary');renderPages();
    });
});
document.getElementById('pageDomainFilter').addEventListener('change',renderPages);
document.getElementById('pageSearch').addEventListener('input',renderPages);

document.getElementById('pagesBody').addEventListener('click',function(e){
    const btn=e.target.closest('button');if(!btn)return;
    const idx=parseInt(btn.closest('tr').dataset.idx);currentPageIdx=idx;
    if(btn.classList.contains('btn-view-page')) viewPage(idx);
    else if(btn.classList.contains('btn-edit-page')) editPage(idx);
    else if(btn.classList.contains('btn-dup-page')){duplicatePage(idx);}
    else if(btn.classList.contains('btn-pause-page')){pages[idx].status='paused';renderPages();toast('Page paused','warning');}
    else if(btn.classList.contains('btn-activate-page')){pages[idx].status='active';renderPages();toast('Page activated');}
});

function viewPage(idx){
    const p=pages[idx]; currentPageIdx=idx;
    document.getElementById('vpName').textContent=p.name;
    const fullUrl='https://'+p.domain+'/'+p.slug;
    document.getElementById('vpUrl').textContent=fullUrl;document.getElementById('vpUrl').href=fullUrl;
    document.getElementById('vpVisitBtn').href=fullUrl;
    document.getElementById('vpStatus').innerHTML=p.status==='active'?'<span class="badge bg-success fs-12">Active</span>':p.status==='paused'?'<span class="badge bg-warning fs-12">Paused</span>':'<span class="badge bg-secondary fs-12">Draft</span>';
    document.getElementById('vpVisitors').textContent=n(p.visitors);
    document.getElementById('vpLeads').textContent=n(p.leads);
    const conv=p.visitors>0?(p.leads/p.visitors*100).toFixed(1):'0';
    document.getElementById('vpConv').textContent=conv+'%';
    document.getElementById('vpBounce').textContent=p.bounce+'%';
    document.getElementById('vpAvgTime').textContent=p.avgTime;
    document.getElementById('vpSpeed').textContent=p.speed||'—';
    let srcHtml='';if(p.sources){Object.entries(p.sources).forEach(([k,v])=>{srcHtml+=`<div class="d-flex justify-content-between mb-1"><span class="text-capitalize">${k}</span><span class="fw-semibold">${v}%</span></div>`;});}
    document.getElementById('vpSources').innerHTML=srcHtml||'<span class="text-muted">No data</span>';
    document.getElementById('vpUtm').innerHTML=p.utms&&p.utms.length?p.utms.map(u=>`<span class="badge bg-light text-dark me-1 mb-1">${u}</span>`).join(''):'<span class="text-muted">No UTMs</span>';
    document.getElementById('vpSeo').innerHTML=`<div class="mb-1"><small class="text-muted">Title:</small> ${p.metaTitle||'<span class="text-danger">Not set</span>'}</div><div class="mb-1"><small class="text-muted">Keywords:</small> ${p.keywords||'<span class="text-danger">Not set</span>'}</div>`;
    new bootstrap.Modal(document.getElementById('viewPageModal')).show();
}

function editPage(idx){
    const p=pages[idx]; currentPageIdx=idx;
    document.getElementById('editIdx').value=idx;
    document.getElementById('editName').value=p.name;
    document.getElementById('editSlug').value=p.slug;
    document.getElementById('editDomain').value=p.domain;
    document.getElementById('editStatus').value=p.status;
    document.getElementById('editTemplate').value=p.template;
    document.getElementById('editCampaign').value=p.campaign||'';
    document.getElementById('editMetaTitle').value=p.metaTitle||'';
    document.getElementById('editMetaDesc').value=p.metaDesc||'';
    document.getElementById('editKeywords').value=p.keywords||'';
    document.getElementById('editMetaTitleCount').textContent=(p.metaTitle||'').length;
    document.getElementById('editMetaDescCount').textContent=(p.metaDesc||'').length;
    new bootstrap.Modal(document.getElementById('editPageModal')).show();
}
document.getElementById('editMetaTitle')?.addEventListener('input',function(){document.getElementById('editMetaTitleCount').textContent=this.value.length;});
document.getElementById('editMetaDesc')?.addEventListener('input',function(){document.getElementById('editMetaDescCount').textContent=this.value.length;});

function saveEdit(){
    const idx=parseInt(document.getElementById('editIdx').value);
    pages[idx].name=document.getElementById('editName').value;
    pages[idx].slug=document.getElementById('editSlug').value;
    pages[idx].domain=document.getElementById('editDomain').value;
    pages[idx].status=document.getElementById('editStatus').value;
    pages[idx].template=document.getElementById('editTemplate').value;
    pages[idx].campaign=document.getElementById('editCampaign').value;
    pages[idx].metaTitle=document.getElementById('editMetaTitle').value;
    pages[idx].metaDesc=document.getElementById('editMetaDesc').value;
    pages[idx].keywords=document.getElementById('editKeywords').value;
    pages[idx].modified='2026-03-02';
    bootstrap.Modal.getInstance(document.getElementById('editPageModal')).hide();
    renderPages();toast('Page updated successfully');
}
function editFromView(){
    bootstrap.Modal.getInstance(document.getElementById('viewPageModal')).hide();
    setTimeout(()=>editPage(currentPageIdx),300);
}
function duplicatePage(idx){
    const p={...pages[idx]};
    p.id='lp'+(pages.length+1);p.name='[COPY] '+p.name;p.slug=p.slug+'-copy';p.status='draft';
    p.visitors=0;p.leads=0;p.bounce=0;p.avgTime='0:00';p.speed=0;p.modified='2026-03-02';
    pages.push(p);renderPages();toast('Page duplicated as draft');
}

// ── Create Page ──
function createPage(status){
    const name=document.getElementById('cpName').value.trim();
    if(!name){toast('Enter page name','danger');return;}
    const slug=document.getElementById('cpSlug').value.trim()||name.toLowerCase().replace(/\s+/g,'-').replace(/[^a-z0-9-]/g,'');
    pages.push({id:'lp'+(pages.length+1),name:name,slug:slug,domain:document.getElementById('cpDomain').value,status:status,template:document.getElementById('cpTemplate').value,campaign:document.getElementById('cpCampaign').value,visitors:0,leads:0,bounce:0,avgTime:'0:00',speed:0,modified:'2026-03-02',metaTitle:document.getElementById('cpMetaTitle').value,metaDesc:document.getElementById('cpMetaDesc').value,keywords:'',sources:{},utms:[]});
    bootstrap.Modal.getInstance(document.getElementById('createPageModal')).hide();
    document.getElementById('cpName').value='';document.getElementById('cpSlug').value='';
    renderPages();toast(status==='active'?'Page created and published!':'Page saved as draft');
}
function createAsDraft(){createPage('draft');}

// ── Render Leads ──
function renderLeads(){
    const filter=document.querySelector('.lead-filter.btn-primary')?.dataset.filter||'all';
    const pf=document.getElementById('leadPageFilter').value;
    const search=document.getElementById('leadSearch').value.toLowerCase();
    let filtered=leads.filter(l=>{
        if(filter!=='all'&&l.status!==filter) return false;
        if(pf&&l.page!==pf) return false;
        if(search&&!l.name.toLowerCase().includes(search)&&!l.email.toLowerCase().includes(search)) return false;
        return true;
    });
    document.getElementById('leadsCount').textContent=filtered.length;
    const stBadge=s=>s==='new'?'<span class="badge bg-primary-subtle text-primary">New</span>':s==='contacted'?'<span class="badge bg-warning-subtle text-warning">Contacted</span>':'<span class="badge bg-success-subtle text-success">Converted</span>';
    const tbody=document.getElementById('leadsBody');
    if(!filtered.length){tbody.innerHTML='<tr><td colspan="8" class="text-center text-muted py-4">No leads found</td></tr>';return;}
    tbody.innerHTML=filtered.map((l,i)=>{
        const idx=leads.indexOf(l);
        const pg=pages.find(p=>p.id===l.page);
        return `<tr data-idx="${idx}">
            <td class="fw-medium">${l.name}</td>
            <td><div>${l.email}</div><small class="text-muted">${l.phone}</small></td>
            <td><small>${pg?pg.name:'—'}</small></td>
            <td>${l.service}</td>
            <td><span class="badge bg-light text-dark">${l.source}</span></td>
            <td>${stBadge(l.status)}</td>
            <td>${l.date}</td>
            <td><div class="d-flex gap-1">
                <button class="btn btn-sm btn-soft-primary btn-view-lead"><i class="ri-eye-line"></i></button>
                ${l.status==='new'?`<button class="btn btn-sm btn-soft-warning btn-contact-lead" title="Mark Contacted"><i class="ri-phone-line"></i></button>`:''}
                ${l.status!=='converted'?`<button class="btn btn-sm btn-soft-success btn-convert-lead" title="Convert to CRM"><i class="ri-user-add-line"></i></button>`:''}
            </div></td>
        </tr>`;
    }).join('');
}
document.querySelectorAll('.lead-filter').forEach(pill=>{
    pill.addEventListener('click',function(){
        document.querySelectorAll('.lead-filter').forEach(p=>{p.classList.remove('btn-primary');p.classList.add('btn-light');});
        this.classList.remove('btn-light');this.classList.add('btn-primary');renderLeads();
    });
});
document.getElementById('leadPageFilter').addEventListener('change',renderLeads);
document.getElementById('leadSearch').addEventListener('input',renderLeads);

document.getElementById('leadsBody').addEventListener('click',function(e){
    const btn=e.target.closest('button');if(!btn)return;
    const idx=parseInt(btn.closest('tr').dataset.idx);currentLeadIdx=idx;
    if(btn.classList.contains('btn-view-lead')) viewLead(idx);
    else if(btn.classList.contains('btn-contact-lead')){leads[idx].status='contacted';renderLeads();toast('Marked as contacted');}
    else if(btn.classList.contains('btn-convert-lead')){leads[idx].status='converted';renderLeads();toast('Lead converted to CRM!');}
});
function viewLead(idx){
    const l=leads[idx]; const pg=pages.find(p=>p.id===l.page);
    document.getElementById('vlName').textContent=l.name;
    document.getElementById('vlEmail').textContent=l.email;
    document.getElementById('vlPhone').textContent=l.phone;
    document.getElementById('vlService').textContent=l.service;
    document.getElementById('vlPage').textContent=pg?pg.name:'—';
    document.getElementById('vlSource').textContent=l.source;
    document.getElementById('vlStatus').innerHTML=l.status==='new'?'<span class="badge bg-primary">New</span>':l.status==='contacted'?'<span class="badge bg-warning">Contacted</span>':'<span class="badge bg-success">Converted</span>';
    document.getElementById('vlDate').textContent=l.date;
    document.getElementById('vlMessage').textContent=l.message||'—';
    new bootstrap.Modal(document.getElementById('viewLeadModal')).show();
}
function convertLead(){
    if(currentLeadIdx>=0){leads[currentLeadIdx].status='converted';renderLeads();}
    bootstrap.Modal.getInstance(document.getElementById('viewLeadModal')).hide();
    toast('Lead converted and added to CRM Leads!');
}

// ── Populate lead page filter ──
function populateLeadPageFilter(){
    const sel=document.getElementById('leadPageFilter');
    pages.forEach(p=>{const opt=document.createElement('option');opt.value=p.id;opt.textContent=p.name;sel.appendChild(opt);});
}

// ── A/B Tests ──
function renderAbTests(){
    document.getElementById('abTestsContainer').innerHTML=abTests.map((t,i)=>{
        const pg=pages.find(p=>p.id===t.page);
        const stBadge=t.status==='running'?'<span class="badge bg-success-subtle text-success">Running</span>':'<span class="badge bg-secondary-subtle text-secondary">Completed</span>';
        const winnerA=t.winner==='A',winnerB=t.winner==='B';
        const aWin=!t.winner&&t.varA.conv>t.varB.conv,bWin=!t.winner&&t.varB.conv>t.varA.conv;
        const uplift=t.varA.conv>0?((Math.max(t.varA.conv,t.varB.conv)/Math.min(t.varA.conv,t.varB.conv)-1)*100).toFixed(1):0;
        return `<div class="border rounded-3 p-3 mb-3">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div><h6 class="mb-0 fw-semibold">${t.name}</h6><small class="text-muted">${pg?pg.name:''} · ${t.element} · Started ${t.startDate}</small></div>
                <div class="d-flex align-items-center gap-2">${stBadge} <span class="badge bg-light text-dark">${t.split}</span></div>
            </div>
            <div class="row text-center">
                <div class="col-6">
                    <div class="border rounded p-3 ${winnerA||aWin?'border-success':''}">
                        <p class="text-muted mb-1 fs-12">Variant A</p>
                        <p class="mb-1 fs-13">${t.varA.name}</p>
                        <h4 class="mb-1 fw-semibold ${winnerA||aWin?'text-success':'text-primary'}">${t.varA.conv}%</h4>
                        ${winnerA?'<span class="badge bg-success fs-10">Winner</span>':aWin?`<span class="badge bg-success-subtle text-success fs-10">Leading +${uplift}%</span>`:''}
                        <p class="text-muted mb-0 fs-12">${n(t.varA.visitors)} visitors</p>
                    </div>
                </div>
                <div class="col-6">
                    <div class="border rounded p-3 ${winnerB||bWin?'border-success':''}">
                        <p class="text-muted mb-1 fs-12">Variant B</p>
                        <p class="mb-1 fs-13">${t.varB.name}</p>
                        <h4 class="mb-1 fw-semibold ${winnerB||bWin?'text-success':'text-primary'}">${t.varB.conv}%</h4>
                        ${winnerB?'<span class="badge bg-success fs-10">Winner</span>':bWin?`<span class="badge bg-success-subtle text-success fs-10">Leading +${uplift}%</span>`:''}
                        <p class="text-muted mb-0 fs-12">${n(t.varB.visitors)} visitors</p>
                    </div>
                </div>
            </div>
            <div class="mt-2 d-flex justify-content-between align-items-center">
                <small class="text-muted">Confidence: <span class="fw-semibold ${t.confidence>=95?'text-success':t.confidence>=80?'text-warning':'text-danger'}">${t.confidence}%</span></small>
                ${t.status==='running'?`<div class="d-flex gap-1"><button class="btn btn-sm btn-soft-success" onclick="endTest(${i},'A')">Pick A</button><button class="btn btn-sm btn-soft-success" onclick="endTest(${i},'B')">Pick B</button><button class="btn btn-sm btn-soft-danger" onclick="endTest(${i},'')">Stop</button></div>`:''}
            </div>
        </div>`;
    }).join('');
}
function endTest(idx,winner){
    abTests[idx].status='completed';
    if(winner) abTests[idx].winner=winner;
    renderAbTests();toast(winner?`Variant ${winner} selected as winner`:'Test stopped','info');
}
function showNewTest(){
    const sel=document.getElementById('ntPage');sel.innerHTML='';
    pages.filter(p=>p.status==='active').forEach(p=>{const opt=document.createElement('option');opt.value=p.id;opt.textContent=p.name;sel.appendChild(opt);});
    new bootstrap.Modal(document.getElementById('newTestModal')).show();
}
function createTest(){
    const name=document.getElementById('ntName').value.trim();
    if(!name){toast('Enter test name','danger');return;}
    abTests.unshift({id:'t'+(abTests.length+1),name:name,page:document.getElementById('ntPage').value,element:document.getElementById('ntElement').value,status:'running',split:document.getElementById('ntSplit').value,varA:{name:document.getElementById('ntVarA').value||'Original',conv:0,visitors:0},varB:{name:document.getElementById('ntVarB').value||'New version',conv:0,visitors:0},startDate:'2026-03-02',confidence:0});
    bootstrap.Modal.getInstance(document.getElementById('newTestModal')).hide();
    renderAbTests();toast('A/B test started!');
}

// ── Export ──
function exportPages(){
    let csv='Name,Domain,Slug,Status,Visitors,Leads,ConvRate,Bounce,Speed,Modified\n';
    pages.forEach(p=>{const conv=p.visitors>0?(p.leads/p.visitors*100).toFixed(1):0;csv+=`"${p.name}","${p.domain}","${p.slug}","${p.status}",${p.visitors},${p.leads},${conv}%,${p.bounce}%,${p.speed},"${p.modified}"\n`;});
    const blob=new Blob([csv],{type:'text/csv'});const a=document.createElement('a');a.href=URL.createObjectURL(blob);a.download='landing-pages.csv';a.click();toast('Pages exported');
}
function exportLeads(){
    let csv='Name,Email,Phone,Page,Service,Source,Status,Date,Message\n';
    leads.forEach(l=>{const pg=pages.find(p=>p.id===l.page);csv+=`"${l.name}","${l.email}","${l.phone}","${pg?pg.name:''}","${l.service}","${l.source}","${l.status}","${l.date}","${l.message.replace(/"/g,'""')}"\n`;});
    const blob=new Blob([csv],{type:'text/csv'});const a=document.createElement('a');a.href=URL.createObjectURL(blob);a.download='landing-page-leads.csv';a.click();toast('Leads exported');
}

// ── Top Converting Pages ──
function renderTopConv(){
    const sorted=[...pages].filter(p=>p.visitors>0).sort((a,b)=>(b.leads/b.visitors)-(a.leads/a.visitors)).slice(0,5);
    document.getElementById('topConvPages').innerHTML=sorted.map((p,i)=>{
        const conv=(p.leads/p.visitors*100).toFixed(1);
        const barWidth=Math.min(conv*10,100);
        return `<div class="mb-3"><div class="d-flex justify-content-between mb-1"><span class="fw-medium fs-13">${i+1}. ${p.name}</span><span class="fw-semibold text-success">${conv}%</span></div><div class="progress" style="height:6px"><div class="progress-bar bg-success" style="width:${barWidth}%"></div></div></div>`;
    }).join('');
}

// ── Charts ──
const activeForChart=pages.filter(p=>p.visitors>0).slice(0,8);
new ApexCharts(document.querySelector("#convByPageChart"),{
    chart:{type:'bar',height:320,toolbar:{show:false}},
    series:[{name:'Conv Rate %',data:activeForChart.map(p=>parseFloat((p.leads/p.visitors*100).toFixed(1)))}],
    plotOptions:{bar:{borderRadius:4,columnWidth:'55%',distributed:true}},
    colors:['#3b82f6','#10b981','#f59e0b','#8b5cf6','#06b6d4','#ef4444','#ec4899','#6b7280'],
    xaxis:{categories:activeForChart.map(p=>p.name.length>15?p.name.substring(0,15)+'...':p.name),labels:{style:{fontSize:'11px'}}},
    yaxis:{title:{text:'Conversion Rate (%)'},max:Math.max(...activeForChart.map(p=>p.leads/p.visitors*100))+2},
    dataLabels:{enabled:true,formatter:v=>v+'%',style:{fontSize:'12px',fontWeight:600}},
    legend:{show:false},grid:{borderColor:'#f1f1f1'}
}).render();

new ApexCharts(document.querySelector("#trafficTrendChart"),{
    chart:{type:'area',height:320,toolbar:{show:false}},
    series:[{name:'Visitors',data:[12400,15200,18600,21300,24800,28200]},{name:'Leads',data:[420,560,720,890,1100,1340]}],
    xaxis:{categories:['Oct','Nov','Dec','Jan','Feb','Mar']},
    yaxis:[{title:{text:'Visitors'}},{opposite:true,title:{text:'Leads'}}],
    colors:['#3b82f6','#10b981'],
    stroke:{curve:'smooth',width:2},
    fill:{type:'gradient',gradient:{shadeIntensity:1,opacityFrom:0.3,opacityTo:0.1}},
    grid:{borderColor:'#f1f1f1'},legend:{position:'top'}
}).render();

new ApexCharts(document.querySelector("#trafficSourcesChart"),{
    chart:{type:'donut',height:300},
    series:[38,28,15,12,7],
    labels:['Google Ads','Facebook','Direct','Organic','Other'],
    colors:['#3b82f6','#3b5998','#6b7280','#10b981','#f59e0b'],
    legend:{position:'bottom'},
    plotOptions:{pie:{donut:{labels:{show:true,total:{show:true,label:'Sources'}}}}},
    dataLabels:{enabled:false}
}).render();

new ApexCharts(document.querySelector("#deviceChart"),{
    chart:{type:'donut',height:300},
    series:[62,28,10],
    labels:['Mobile','Desktop','Tablet'],
    colors:['#3b82f6','#10b981','#f59e0b'],
    legend:{position:'bottom'},
    plotOptions:{pie:{donut:{labels:{show:true,total:{show:true,label:'Devices'}}}}},
    dataLabels:{enabled:false}
}).render();

// ── Init ──
renderPages();
populateLeadPageFilter();
renderLeads();
renderAbTests();
renderTopConv();
</script>
<style>
.lp-section{display:none}
.lp-section.active{display:block}
</style>
@endsection
