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
const TOKEN = localStorage.getItem('wc_token');
const API = '/api/v1';
const HEADERS = {'Authorization':'Bearer '+TOKEN,'Accept':'application/json'};

function toast(msg,type='success'){const t=document.createElement('div');t.className=`alert alert-${type} position-fixed top-0 end-0 m-3 shadow`;t.style.zIndex='9999';t.innerHTML=msg;document.body.appendChild(t);setTimeout(()=>{t.style.opacity='0';setTimeout(()=>t.remove(),300)},3000);}
function n(v){return v?Number(v).toLocaleString('pl-PL'):'0';}
function loader(el){if(typeof el==='string')el=document.querySelector(el);if(el)el.innerHTML='<div class="text-center py-4"><div class="spinner-border text-primary spinner-border-sm"></div></div>';}

// ── State ──
let pages = [];
let analyticsData = null;
let analyticsMap = {};
let currentPageId = null;
let chartConv = null, chartTrend = null, chartSources = null, chartDevice = null;

// ── API helpers ──
async function apiFetch(url) {
    try {
        const r = await fetch(API + url, {headers: HEADERS});
        if (r.status === 401) { window.location = '/login'; return null; }
        if (!r.ok) return null;
        const j = await r.json();
        return j.success !== false ? (j.data !== undefined ? j.data : j) : null;
    } catch(e) { console.warn('API GET error:', url, e); return null; }
}

async function apiPost(url, data) {
    try {
        const fd = new FormData();
        Object.entries(data).forEach(([k,v]) => { if(v !== null && v !== undefined) fd.append(k, v); });
        const r = await fetch(API + url, {method:'POST', headers:{'Authorization':'Bearer '+TOKEN,'Accept':'application/json'}, body: fd});
        if (r.status === 401) { window.location = '/login'; return null; }
        const j = await r.json();
        return j;
    } catch(e) { console.warn('API POST error:', url, e); toast('Request failed: '+e.message,'danger'); return null; }
}

async function apiPut(url, data) {
    try {
        const fd = new FormData();
        fd.append('_method', 'PUT');
        Object.entries(data).forEach(([k,v]) => { if(v !== null && v !== undefined) fd.append(k, v); });
        const r = await fetch(API + url, {method:'POST', headers:{'Authorization':'Bearer '+TOKEN,'Accept':'application/json'}, body: fd});
        if (r.status === 401) { window.location = '/login'; return null; }
        const j = await r.json();
        return j;
    } catch(e) { console.warn('API PUT error:', url, e); toast('Request failed: '+e.message,'danger'); return null; }
}

// ── Load Landings (list) ──
async function loadLandings() {
    loader('#pagesBody');
    try {
        const data = await apiFetch('/landings');
        if (!data) { pages = []; renderPages(); return; }
        pages = Array.isArray(data) ? data : [];
        // Merge analytics if already loaded
        if (Object.keys(analyticsMap).length) mergeAnalytics();
        renderPages();
        populateLeadPageFilter();
        renderAbTests();
    } catch(e) {
        console.warn('loadLandings error:', e);
        pages = [];
        renderPages();
        toast('Failed to load landing pages','danger');
    }
}

// ── Load Analytics ──
async function loadAnalytics() {
    try {
        const data = await apiFetch('/landings/analytics?days=30');
        if (!data) { analyticsData = null; renderCharts(); renderTopConv(); return; }
        analyticsData = data;
        // Build lookup by landing id
        analyticsMap = {};
        if (data.landings && Array.isArray(data.landings)) {
            data.landings.forEach(l => { analyticsMap[l.id] = l; });
        }
        // Merge into pages if already loaded
        if (pages.length) mergeAnalytics();
        // Update stat cards
        document.getElementById('statLeads').textContent = n(data.total_conversions || 0);
        document.getElementById('statAvgConv').textContent = (data.overall_cr != null ? parseFloat(data.overall_cr).toFixed(1) : '0') + '%';
        renderPages();
        renderCharts();
        renderTopConv();
    } catch(e) {
        console.warn('loadAnalytics error:', e);
        analyticsData = null;
        renderCharts();
        renderTopConv();
    }
}

// ── Merge analytics into page objects ──
function mergeAnalytics() {
    pages.forEach(p => {
        const a = analyticsMap[p.id];
        if (a) {
            p._visitors = a.total_visits || 0;
            p._leads = a.total_conversions || 0;
            p._conv = a.conversion_rate || 0;
            p._variants = a.variants || [];
        } else {
            p._visitors = 0;
            p._leads = 0;
            p._conv = 0;
            p._variants = [];
        }
    });
}

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
        if(search&&!(p.title||'').toLowerCase().includes(search)&&!(p.slug||'').toLowerCase().includes(search)) return false;
        return true;
    });
    document.getElementById('pagesCount').textContent=filtered.length;
    document.getElementById('statPages').textContent=pages.length;
    // Count A/B tests running (pages with >1 active variant)
    let abRunning = 0;
    pages.forEach(p => {
        const vars = p._variants || p.variants || [];
        const activeVars = vars.filter(v => v.is_active);
        if (activeVars.length > 1) abRunning++;
    });
    const abTestCard = document.querySelector('#statPages')?.closest('.row')?.querySelectorAll('.card-h-100')[2];
    if (abTestCard) {
        const abH4 = abTestCard.querySelector('h4');
        if (abH4) abH4.textContent = abRunning;
    }
    // If analytics not loaded yet, compute from page-level data
    if (!analyticsData) {
        const totalLeads = pages.reduce((a,p) => a + (p._leads||0), 0);
        document.getElementById('statLeads').textContent = n(totalLeads);
        const activeP = pages.filter(p => p.status === 'active' && (p._visitors||0) > 0);
        const avgConv = activeP.length ? activeP.reduce((a,p) => a + parseFloat(p._conv||0), 0) / activeP.length : 0;
        document.getElementById('statAvgConv').textContent = avgConv.toFixed(1) + '%';
    }
    const tbody=document.getElementById('pagesBody');
    if(!filtered.length){tbody.innerHTML='<tr><td colspan="10" class="text-center text-muted py-4">No pages found</td></tr>';return;}
    tbody.innerHTML=filtered.map(p=>{
        const visitors = p._visitors || 0;
        const leads = p._leads || 0;
        const conv = visitors > 0 ? (leads / visitors * 100).toFixed(1) : (p._conv ? parseFloat(p._conv).toFixed(1) : '0');
        const convNum = parseFloat(conv);
        const convClass = convNum >= 4 ? 'success' : convNum >= 2.5 ? 'warning' : 'danger';
        const stBadge = p.status === 'active' ? '<span class="badge bg-success-subtle text-success">Active</span>' : p.status === 'draft' ? '<span class="badge bg-secondary-subtle text-secondary">Draft</span>' : '<span class="badge bg-warning-subtle text-warning">Paused</span>';
        const url = (p.domain||'') + '/' + (p.slug||'');
        const modified = p.updated_at ? p.updated_at.substring(0,10) : (p.created_at ? p.created_at.substring(0,10) : '');
        return `<tr data-id="${p.id}">
            <td><span class="fw-medium">${p.title||p.slug||''}</span><br><small class="text-muted">${p.language||''}</small></td>
            <td><code class="fs-12">${url}</code></td>
            <td class="text-center">${visitors>0?n(visitors):'—'}</td>
            <td class="text-center">${leads>0?n(leads):'—'}</td>
            <td class="text-center"><span class="fw-semibold text-${convClass}">${conv}%</span></td>
            <td class="text-center">—</td>
            <td class="text-center">—</td>
            <td>${stBadge}</td>
            <td>${modified}</td>
            <td><div class="d-flex gap-1">
                <button class="btn btn-sm btn-soft-primary btn-view-page" title="Analytics"><i class="ri-bar-chart-line"></i></button>
                <button class="btn btn-sm btn-soft-warning btn-edit-page" title="Edit"><i class="ri-edit-line"></i></button>
                <button class="btn btn-sm btn-soft-info btn-dup-page" title="Duplicate"><i class="ri-file-copy-line"></i></button>
                ${p.status==='active'?`<button class="btn btn-sm btn-soft-danger btn-pause-page" title="Pause"><i class="ri-pause-line"></i></button>`:p.status==='paused'?`<button class="btn btn-sm btn-soft-success btn-activate-page" title="Activate"><i class="ri-play-line"></i></button>`:p.status==='draft'?`<button class="btn btn-sm btn-soft-success btn-activate-page" title="Publish"><i class="ri-send-plane-line"></i></button>`:''}
            </div></td>
        </tr>`;
    }).join('');
}

// ── Page filters ──
document.querySelectorAll('.page-filter').forEach(pill=>{
    pill.addEventListener('click',function(){
        document.querySelectorAll('.page-filter').forEach(p=>{p.classList.remove('btn-primary');p.classList.add('btn-light');});
        this.classList.remove('btn-light');this.classList.add('btn-primary');renderPages();
    });
});
document.getElementById('pageDomainFilter').addEventListener('change',renderPages);
document.getElementById('pageSearch').addEventListener('input',renderPages);

// ── Page actions (delegated) ──
document.getElementById('pagesBody').addEventListener('click',function(e){
    const btn=e.target.closest('button');if(!btn)return;
    const tr=btn.closest('tr');
    const id=tr?.dataset.id;
    const p=pages.find(x=>String(x.id)===String(id));
    if(!p)return;
    currentPageId=p.id;
    if(btn.classList.contains('btn-view-page')) viewPage(p.id);
    else if(btn.classList.contains('btn-edit-page')) editPage(p);
    else if(btn.classList.contains('btn-dup-page')) duplicatePage(p);
    else if(btn.classList.contains('btn-pause-page')) updatePageStatus(p.id,'paused');
    else if(btn.classList.contains('btn-activate-page')) updatePageStatus(p.id,'active');
});

// ── Update page status via PUT ──
async function updatePageStatus(id, status) {
    const p = pages.find(x => String(x.id) === String(id));
    if (!p) return;
    try {
        const res = await apiPut('/landings/' + id, {
            domain: p.domain,
            slug: p.slug,
            language: p.language || 'pl',
            title: p.title || p.slug,
            meta_description: p.meta_description || '',
            status: status
        });
        if (res && res.success !== false) {
            p.status = status;
            renderPages();
            toast(status === 'active' ? 'Page activated' : 'Page paused', status === 'active' ? 'success' : 'warning');
        } else {
            toast('Failed to update status: ' + (res?.message || 'Unknown error'), 'danger');
        }
    } catch(e) {
        toast('Error updating status', 'danger');
    }
}

// ── View Page (detail modal) ──
async function viewPage(id) {
    const p = pages.find(x => String(x.id) === String(id));
    if (!p) return;
    currentPageId = id;
    document.getElementById('vpName').textContent = p.title || p.slug || '';
    const fullUrl = 'https://' + (p.domain||'') + '/' + (p.slug||'');
    document.getElementById('vpUrl').textContent = fullUrl;
    document.getElementById('vpUrl').href = fullUrl;
    document.getElementById('vpVisitBtn').href = fullUrl;
    document.getElementById('vpStatus').innerHTML = p.status === 'active' ? '<span class="badge bg-success fs-12">Active</span>' : p.status === 'paused' ? '<span class="badge bg-warning fs-12">Paused</span>' : '<span class="badge bg-secondary fs-12">Draft</span>';
    // Analytics data
    const visitors = p._visitors || 0;
    const leads = p._leads || 0;
    const conv = visitors > 0 ? (leads / visitors * 100).toFixed(1) : '0';
    document.getElementById('vpVisitors').textContent = n(visitors);
    document.getElementById('vpLeads').textContent = n(leads);
    document.getElementById('vpConv').textContent = conv + '%';
    document.getElementById('vpBounce').textContent = '—';
    document.getElementById('vpAvgTime').textContent = '—';
    document.getElementById('vpSpeed').textContent = '—';
    // UTM sources from analytics
    let srcHtml = '';
    if (analyticsData && analyticsData.utm_sources) {
        analyticsData.utm_sources.slice(0, 6).forEach(s => {
            srcHtml += `<div class="d-flex justify-content-between mb-1"><span class="text-capitalize">${s.utm_source||'direct'}</span><span class="fw-semibold">${n(s.visits)}</span></div>`;
        });
    }
    document.getElementById('vpSources').innerHTML = srcHtml || '<span class="text-muted">No data</span>';
    document.getElementById('vpUtm').innerHTML = '<span class="text-muted">See Traffic Sources chart</span>';
    document.getElementById('vpSeo').innerHTML = `<div class="mb-1"><small class="text-muted">Title:</small> ${p.title || '<span class="text-danger">Not set</span>'}</div><div class="mb-1"><small class="text-muted">Description:</small> ${p.meta_description || '<span class="text-danger">Not set</span>'}</div>`;
    // Try to load detail from API for fresher data
    try {
        const detail = await apiFetch('/landings/' + id);
        if (detail) {
            if (detail.variants && detail.variants.length) {
                let totalVisits = detail.variants.reduce((a,v) => a + (v.visits_count||0), 0);
                let totalConv = detail.variants.reduce((a,v) => a + (v.conversions_count||0), 0);
                if (totalVisits > 0) {
                    document.getElementById('vpVisitors').textContent = n(totalVisits);
                    document.getElementById('vpLeads').textContent = n(totalConv);
                    document.getElementById('vpConv').textContent = (totalConv/totalVisits*100).toFixed(1) + '%';
                }
            }
        }
    } catch(e) { /* use cached data */ }
    new bootstrap.Modal(document.getElementById('viewPageModal')).show();
}

// ── Edit Page ──
function editPage(p) {
    if (!p) return;
    currentPageId = p.id;
    document.getElementById('editIdx').value = p.id;
    document.getElementById('editName').value = p.title || '';
    document.getElementById('editSlug').value = p.slug || '';
    document.getElementById('editDomain').value = p.domain || 'wincase.eu';
    document.getElementById('editStatus').value = p.status || 'draft';
    document.getElementById('editTemplate').value = '';
    document.getElementById('editCampaign').value = '';
    document.getElementById('editMetaTitle').value = p.title || '';
    document.getElementById('editMetaDesc').value = p.meta_description || '';
    document.getElementById('editKeywords').value = '';
    document.getElementById('editMetaTitleCount').textContent = (p.title || '').length;
    document.getElementById('editMetaDescCount').textContent = (p.meta_description || '').length;
    new bootstrap.Modal(document.getElementById('editPageModal')).show();
}
document.getElementById('editMetaTitle')?.addEventListener('input',function(){document.getElementById('editMetaTitleCount').textContent=this.value.length;});
document.getElementById('editMetaDesc')?.addEventListener('input',function(){document.getElementById('editMetaDescCount').textContent=this.value.length;});

async function saveEdit(){
    const id = document.getElementById('editIdx').value;
    const data = {
        title: document.getElementById('editName').value,
        slug: document.getElementById('editSlug').value,
        domain: document.getElementById('editDomain').value,
        status: document.getElementById('editStatus').value,
        language: 'pl',
        meta_description: document.getElementById('editMetaDesc').value
    };
    try {
        const res = await apiPut('/landings/' + id, data);
        if (res && res.success !== false) {
            bootstrap.Modal.getInstance(document.getElementById('editPageModal')).hide();
            toast('Page updated successfully');
            await loadLandings();
        } else {
            toast('Failed to save: ' + (res?.message || 'Unknown error'), 'danger');
        }
    } catch(e) {
        toast('Error saving page', 'danger');
    }
}

function editFromView(){
    bootstrap.Modal.getInstance(document.getElementById('viewPageModal')).hide();
    const p = pages.find(x => String(x.id) === String(currentPageId));
    setTimeout(() => editPage(p), 300);
}

// ── Duplicate Page (create copy via POST) ──
async function duplicatePage(p) {
    if (!p) return;
    try {
        const res = await apiPost('/landings', {
            domain: p.domain,
            slug: (p.slug||'') + '-copy',
            language: p.language || 'pl',
            title: '[COPY] ' + (p.title || p.slug || ''),
            meta_description: p.meta_description || '',
            status: 'draft'
        });
        if (res && res.success !== false) {
            toast('Page duplicated as draft');
            await loadLandings();
        } else {
            toast('Failed to duplicate: ' + (res?.message || 'Unknown error'), 'danger');
        }
    } catch(e) {
        toast('Error duplicating page', 'danger');
    }
}

// ── Create Page ──
async function createPage(status){
    const name = document.getElementById('cpName').value.trim();
    if(!name){toast('Enter page name','danger');return;}
    const slug = document.getElementById('cpSlug').value.trim() || name.toLowerCase().replace(/\s+/g,'-').replace(/[^a-z0-9-]/g,'');
    try {
        const res = await apiPost('/landings', {
            title: name,
            slug: slug,
            domain: document.getElementById('cpDomain').value,
            language: 'pl',
            meta_description: document.getElementById('cpMetaDesc').value || '',
            status: status
        });
        if (res && res.success !== false) {
            bootstrap.Modal.getInstance(document.getElementById('createPageModal')).hide();
            document.getElementById('cpName').value = '';
            document.getElementById('cpSlug').value = '';
            document.getElementById('cpMetaTitle').value = '';
            document.getElementById('cpMetaDesc').value = '';
            toast(status === 'active' ? 'Page created and published!' : 'Page saved as draft');
            await loadLandings();
        } else {
            toast('Failed to create: ' + (res?.message || 'Unknown error'), 'danger');
        }
    } catch(e) {
        toast('Error creating page', 'danger');
    }
}
function createAsDraft(){createPage('draft');}

// ── Render Leads (from CRM — no dedicated landing leads API) ──
function renderLeads(){
    const tbody = document.getElementById('leadsBody');
    document.getElementById('leadsCount').textContent = '0';
    tbody.innerHTML = `<tr><td colspan="8" class="text-center py-4">
        <div class="text-muted mb-2"><i class="ri-information-line fs-20"></i></div>
        <p class="text-muted mb-2">Form submissions are tracked via the CRM Leads module.</p>
        <a href="/crm-leads" class="btn btn-sm btn-primary"><i class="ri-external-link-line me-1"></i>Go to CRM Leads</a>
    </td></tr>`;
}
document.querySelectorAll('.lead-filter').forEach(pill=>{
    pill.addEventListener('click',function(){
        document.querySelectorAll('.lead-filter').forEach(p=>{p.classList.remove('btn-primary');p.classList.add('btn-light');});
        this.classList.remove('btn-light');this.classList.add('btn-primary');renderLeads();
    });
});
document.getElementById('leadPageFilter').addEventListener('change',renderLeads);
document.getElementById('leadSearch').addEventListener('input',renderLeads);
document.getElementById('leadsBody').addEventListener('click',function(e){});

function viewLead(idx){}
function convertLead(){
    bootstrap.Modal.getInstance(document.getElementById('viewLeadModal'))?.hide();
    toast('Use CRM Leads to manage conversions','info');
}

// ── Populate lead page filter ──
function populateLeadPageFilter(){
    const sel=document.getElementById('leadPageFilter');
    // Clear existing options except first
    while(sel.options.length > 1) sel.remove(1);
    pages.forEach(p=>{
        const opt=document.createElement('option');
        opt.value=p.id;
        opt.textContent=p.title||p.slug||'Landing #'+p.id;
        sel.appendChild(opt);
    });
}

// ── A/B Tests (rendered from landing variants) ──
function renderAbTests(){
    const container = document.getElementById('abTestsContainer');
    // Collect all pages with multiple variants
    let tests = [];
    pages.forEach(p => {
        const variants = p._variants || p.variants || [];
        if (variants.length > 1) {
            tests.push({page: p, variants: variants});
        }
    });
    if (!tests.length) {
        container.innerHTML = '<div class="text-center text-muted py-4"><i class="ri-flask-line fs-24 d-block mb-2"></i>No A/B tests running. Create variants on a landing page to start testing.</div>';
        return;
    }
    container.innerHTML = tests.map((t, ti) => {
        const pageName = t.page.title || t.page.slug || '';
        let varHtml = '';
        const maxConv = Math.max(...t.variants.map(v => parseFloat(v.conversion_rate||0)));
        t.variants.forEach((v, vi) => {
            const cr = parseFloat(v.conversion_rate || 0);
            const isLeading = cr === maxConv && cr > 0;
            const trafficPct = v.traffic_pct || 0;
            varHtml += `<div class="col-${Math.max(Math.floor(12/t.variants.length),4)} mb-2">
                <div class="border rounded p-3 text-center ${isLeading?'border-success':''}">
                    <p class="text-muted mb-1 fs-12">Variant ${vi+1}</p>
                    <p class="mb-1 fs-13">${v.name||v.variant_name||'Variant '+(vi+1)}</p>
                    <h4 class="mb-1 fw-semibold ${isLeading?'text-success':'text-primary'}">${cr.toFixed(1)}%</h4>
                    ${isLeading?'<span class="badge bg-success-subtle text-success fs-10">Leading</span>':''}
                    <p class="text-muted mb-0 fs-12">${n(v.visits||v.visits_count||0)} visits · ${trafficPct}% traffic</p>
                    <span class="badge ${v.is_active?'bg-success':'bg-secondary'} fs-10 mt-1">${v.is_active?'Active':'Inactive'}</span>
                </div>
            </div>`;
        });
        return `<div class="border rounded-3 p-3 mb-3">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div><h6 class="mb-0 fw-semibold">${pageName}</h6><small class="text-muted">${t.variants.length} variants · ${t.page.domain||''}/${t.page.slug||''}</small></div>
                <span class="badge bg-success-subtle text-success">Running</span>
            </div>
            <div class="row text-center">${varHtml}</div>
        </div>`;
    }).join('');
}

function endTest(idx, winner) { /* handled via API variants */ }

function showNewTest(){
    const sel=document.getElementById('ntPage');sel.innerHTML='';
    pages.filter(p=>p.status==='active').forEach(p=>{
        const opt=document.createElement('option');
        opt.value=p.id;
        opt.textContent=p.title||p.slug||'Landing #'+p.id;
        sel.appendChild(opt);
    });
    new bootstrap.Modal(document.getElementById('newTestModal')).show();
}

async function createTest(){
    const name=document.getElementById('ntName').value.trim();
    if(!name){toast('Enter test name','danger');return;}
    const pageId=document.getElementById('ntPage').value;
    if(!pageId){toast('Select a landing page','danger');return;}
    const split = document.getElementById('ntSplit').value;
    const parts = split.split('/');
    const trafficA = parseInt(parts[0]) || 50;
    const trafficB = parseInt(parts[1]) || 50;
    const varAName = document.getElementById('ntVarA').value || 'Original';
    const varBName = document.getElementById('ntVarB').value || 'New version';
    try {
        // Create variant A
        const resA = await apiPost('/landings/' + pageId + '/variants', {
            variant_name: varAName,
            traffic_pct: trafficA,
            headline: name + ' — ' + varAName,
            cta_text: 'Start',
            cta_color: '#3b82f6'
        });
        // Create variant B
        const resB = await apiPost('/landings/' + pageId + '/variants', {
            variant_name: varBName,
            traffic_pct: trafficB,
            headline: name + ' — ' + varBName,
            cta_text: 'Start',
            cta_color: '#10b981'
        });
        if ((resA && resA.success !== false) || (resB && resB.success !== false)) {
            bootstrap.Modal.getInstance(document.getElementById('newTestModal')).hide();
            document.getElementById('ntName').value = '';
            document.getElementById('ntVarA').value = '';
            document.getElementById('ntVarB').value = '';
            toast('A/B test created!');
            await loadLandings();
        } else {
            toast('Failed to create test variants', 'danger');
        }
    } catch(e) {
        toast('Error creating A/B test', 'danger');
    }
}

// ── Export ──
function exportPages(){
    if (!pages.length) { toast('No pages to export','warning'); return; }
    let csv='Name,Domain,Slug,Status,Visitors,Leads,ConvRate,Modified\n';
    pages.forEach(p=>{
        const visitors=p._visitors||0;
        const leads=p._leads||0;
        const conv=visitors>0?(leads/visitors*100).toFixed(1):'0';
        const modified=p.updated_at?p.updated_at.substring(0,10):'';
        csv+=`"${p.title||''}","${p.domain||''}","${p.slug||''}","${p.status||''}",${visitors},${leads},${conv}%,"${modified}"\n`;
    });
    const blob=new Blob([csv],{type:'text/csv'});const a=document.createElement('a');a.href=URL.createObjectURL(blob);a.download='landing-pages.csv';a.click();toast('Pages exported');
}
function exportLeads(){
    toast('Use CRM Leads export for lead data','info');
}

// ── Top Converting Pages ──
function renderTopConv(){
    const el = document.getElementById('topConvPages');
    if (!analyticsData || !analyticsData.landings || !analyticsData.landings.length) {
        el.innerHTML = '<div class="text-center text-muted py-3">No conversion data yet</div>';
        return;
    }
    const sorted = [...analyticsData.landings]
        .filter(l => (l.total_visits||0) > 0)
        .sort((a,b) => parseFloat(b.conversion_rate||0) - parseFloat(a.conversion_rate||0))
        .slice(0,5);
    if (!sorted.length) {
        el.innerHTML = '<div class="text-center text-muted py-3">No conversion data yet</div>';
        return;
    }
    el.innerHTML = sorted.map((l,i) => {
        const conv = parseFloat(l.conversion_rate||0).toFixed(1);
        const barWidth = Math.min(conv*10,100);
        const name = l.slug || 'Landing #'+l.id;
        return `<div class="mb-3"><div class="d-flex justify-content-between mb-1"><span class="fw-medium fs-13">${i+1}. ${name}</span><span class="fw-semibold text-success">${conv}%</span></div><div class="progress" style="height:6px"><div class="progress-bar bg-success" style="width:${barWidth}%"></div></div></div>`;
    }).join('');
}

// ── Charts ──
function renderCharts(){
    // Destroy existing charts
    if(chartConv){chartConv.destroy();chartConv=null;}
    if(chartTrend){chartTrend.destroy();chartTrend=null;}
    if(chartSources){chartSources.destroy();chartSources=null;}
    if(chartDevice){chartDevice.destroy();chartDevice=null;}

    // ── Conversions by Page ──
    if (analyticsData && analyticsData.landings && analyticsData.landings.length) {
        const chartData = analyticsData.landings.filter(l=>(l.total_visits||0)>0).slice(0,8);
        if (chartData.length) {
            chartConv = new ApexCharts(document.querySelector("#convByPageChart"),{
                chart:{type:'bar',height:320,toolbar:{show:false}},
                series:[{name:'Conv Rate %',data:chartData.map(l=>parseFloat(parseFloat(l.conversion_rate||0).toFixed(1)))}],
                plotOptions:{bar:{borderRadius:4,columnWidth:'55%',distributed:true}},
                colors:['#3b82f6','#10b981','#f59e0b','#8b5cf6','#06b6d4','#ef4444','#ec4899','#6b7280'],
                xaxis:{categories:chartData.map(l=>{const name=l.slug||'#'+l.id;return name.length>15?name.substring(0,15)+'...':name;}),labels:{style:{fontSize:'11px'}}},
                yaxis:{title:{text:'Conversion Rate (%)'},max:Math.max(...chartData.map(l=>parseFloat(l.conversion_rate||0)))+2},
                dataLabels:{enabled:true,formatter:v=>v+'%',style:{fontSize:'12px',fontWeight:600}},
                legend:{show:false},grid:{borderColor:'#f1f1f1'}
            });
            chartConv.render();
        } else {
            document.querySelector("#convByPageChart").innerHTML='<div class="text-center text-muted py-5">No conversion data</div>';
        }
    } else {
        document.querySelector("#convByPageChart").innerHTML='<div class="text-center text-muted py-5">No conversion data</div>';
    }

    // ── Traffic Trend ──
    if (analyticsData && analyticsData.daily_trend && analyticsData.daily_trend.length) {
        const trend = analyticsData.daily_trend;
        chartTrend = new ApexCharts(document.querySelector("#trafficTrendChart"),{
            chart:{type:'area',height:320,toolbar:{show:false}},
            series:[{name:'Visitors',data:trend.map(d=>d.visits||0)}],
            xaxis:{categories:trend.map(d=>d.date||''),type:'category',labels:{rotate:-45,style:{fontSize:'10px'},formatter:function(val){if(!val)return '';const parts=String(val).split('-');return parts.length>=3?parts[1]+'/'+parts[2]:val;}}},
            colors:['#3b82f6'],
            stroke:{curve:'smooth',width:2},
            fill:{type:'gradient',gradient:{shadeIntensity:1,opacityFrom:0.3,opacityTo:0.1}},
            grid:{borderColor:'#f1f1f1'},legend:{position:'top'}
        });
        chartTrend.render();
    } else {
        document.querySelector("#trafficTrendChart").innerHTML='<div class="text-center text-muted py-5">No traffic trend data</div>';
    }

    // ── Traffic Sources (UTM) ──
    if (analyticsData && analyticsData.utm_sources && analyticsData.utm_sources.length) {
        const sources = analyticsData.utm_sources.slice(0,6);
        const srcColors = ['#3b82f6','#3b5998','#6b7280','#10b981','#f59e0b','#ec4899'];
        chartSources = new ApexCharts(document.querySelector("#trafficSourcesChart"),{
            chart:{type:'donut',height:300},
            series:sources.map(s=>s.visits||0),
            labels:sources.map(s=>s.utm_source||'direct'),
            colors:srcColors.slice(0,sources.length),
            legend:{position:'bottom'},
            plotOptions:{pie:{donut:{labels:{show:true,total:{show:true,label:'Sources'}}}}},
            dataLabels:{enabled:false}
        });
        chartSources.render();
    } else {
        document.querySelector("#trafficSourcesChart").innerHTML='<div class="text-center text-muted py-5">No source data</div>';
    }

    // ── Devices ──
    if (analyticsData && analyticsData.devices) {
        const dev = analyticsData.devices;
        const devSeries = [dev.desktop||0, dev.mobile||0, dev.tablet||0].filter(v=>v>0);
        const devLabels = [];
        if(dev.desktop)devLabels.push('Desktop');
        if(dev.mobile)devLabels.push('Mobile');
        if(dev.tablet)devLabels.push('Tablet');
        if (devSeries.length) {
            chartDevice = new ApexCharts(document.querySelector("#deviceChart"),{
                chart:{type:'donut',height:300},
                series:devSeries,
                labels:devLabels,
                colors:['#3b82f6','#10b981','#f59e0b'],
                legend:{position:'bottom'},
                plotOptions:{pie:{donut:{labels:{show:true,total:{show:true,label:'Devices'}}}}},
                dataLabels:{enabled:false}
            });
            chartDevice.render();
        } else {
            document.querySelector("#deviceChart").innerHTML='<div class="text-center text-muted py-5">No device data</div>';
        }
    } else {
        document.querySelector("#deviceChart").innerHTML='<div class="text-center text-muted py-5">No device data</div>';
    }
}

// ── Init ──
(async function init(){
    loader('#pagesBody');
    document.getElementById('abTestsContainer').innerHTML = '<div class="text-center py-3"><div class="spinner-border spinner-border-sm text-primary"></div></div>';
    document.getElementById('topConvPages').innerHTML = '<div class="text-center py-3"><div class="spinner-border spinner-border-sm text-primary"></div></div>';
    // Load both in parallel
    await Promise.all([loadLandings(), loadAnalytics()]);
    renderLeads();
})();
</script>
<style>
.lp-section{display:none}
.lp-section.active{display:block}
</style>
@endsection
