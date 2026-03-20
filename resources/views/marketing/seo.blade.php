@extends('partials.layouts.master')
@section('title', 'SEO | WinCase CRM')
@section('sub-title', 'SEO')
@section('sub-title-lang', 'wc-seo')
@section('pagetitle', 'Marketing')
@section('pagetitle-lang', 'wc-marketing')
@section('buttonTitle', 'Add Keyword')
@section('buttonTitle-lang', 'wc-add-keyword')
@section('modalTarget', 'addKeywordModal')
@section('content')
<style>
    .seo-tab{cursor:pointer;padding:8px 20px;border-radius:20px;font-size:.82rem;font-weight:600;border:1px solid #e9ecef;background:#fff;transition:.2s;display:inline-flex;align-items:center;gap:6px}
    .seo-tab:hover{border-color:#845adf}
    .seo-tab.active{background:#845adf;color:#fff;border-color:#845adf}
    .seo-section{display:none}
    .seo-section.active{display:block}
    .pos-badge{width:32px;height:32px;border-radius:8px;display:inline-flex;align-items:center;justify-content:center;font-weight:700;font-size:.85rem}
    .pos-1,.pos-2,.pos-3{background:#d1fae5;color:#065f46}
    .pos-4,.pos-5,.pos-6,.pos-7,.pos-8,.pos-9,.pos-10{background:#dbeafe;color:#1e40af}
    .pos-11-plus{background:#fef3c7;color:#92400e}
    .sat-card{border-radius:12px;border:1px solid #e9ecef;padding:16px;transition:.2s}
    .sat-card:hover{border-color:#845adf;box-shadow:0 4px 12px rgba(0,0,0,.06)}
    .sat-status{width:8px;height:8px;border-radius:50%;display:inline-block}
    .sat-status.online{background:#198754}
    .sat-status.offline{background:#dc3545}
    .kpi-mini{text-align:center;padding:10px;border-radius:8px}
    .change-up{color:#198754;font-weight:600}
    .change-down{color:#dc3545;font-weight:600}
    .change-same{color:#adb5bd}
</style>

<!-- ============ STAT CARDS ============ -->
<div class="row">
    <div class="col-xl-3 col-md-6">
        <div class="card card-h-100"><div class="card-body">
            <div class="d-flex align-items-center gap-3">
                <div class="avatar avatar-sm bg-primary-subtle text-primary rounded-2"><i class="ri-search-eye-line fs-18"></i></div>
                <div>
                    <p class="text-muted mb-0 fs-13">Organic Traffic</p>
                    <h4 class="mb-0 fw-semibold" id="statClicks">—<span class="fs-13 text-muted fw-normal">/month</span></h4>
                    <span class="fs-11 change-same" id="statClicksChange">loading...</span>
                </div>
            </div>
        </div></div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card card-h-100"><div class="card-body">
            <div class="d-flex align-items-center gap-3">
                <div class="avatar avatar-sm bg-success-subtle text-success rounded-2"><i class="ri-key-2-line fs-18"></i></div>
                <div>
                    <p class="text-muted mb-0 fs-13">Keywords Top 10</p>
                    <h4 class="mb-0 fw-semibold" id="statKeywords">—</h4>
                    <span class="fs-11 change-same" id="statKeywordsChange">loading...</span>
                </div>
            </div>
        </div></div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card card-h-100"><div class="card-body">
            <div class="d-flex align-items-center gap-3">
                <div class="avatar avatar-sm bg-warning-subtle text-warning rounded-2"><i class="ri-links-line fs-18"></i></div>
                <div>
                    <p class="text-muted mb-0 fs-13">Backlinks</p>
                    <h4 class="mb-0 fw-semibold" id="statBacklinks">—</h4>
                    <span class="fs-11 change-same" id="statBacklinksChange">loading...</span>
                </div>
            </div>
        </div></div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card card-h-100"><div class="card-body">
            <div class="d-flex align-items-center gap-3">
                <div class="avatar avatar-sm bg-info-subtle text-info rounded-2"><i class="ri-global-line fs-18"></i></div>
                <div>
                    <p class="text-muted mb-0 fs-13">Domain Rating</p>
                    <h4 class="mb-0 fw-semibold" id="statDA">— <span class="fs-13 text-muted fw-normal">/ 100</span></h4>
                    <span class="fs-11 change-same" id="statDAChange">loading...</span>
                </div>
            </div>
        </div></div>
    </div>
</div>

<!-- ============ CHARTS ============ -->
<div class="row">
    <div class="col-xl-8">
        <div class="card">
            <div class="card-header d-flex align-items-center">
                <h5 class="card-title mb-0 flex-grow-1">Organic Traffic Trend</h5>
                <span class="badge bg-success-subtle text-success" id="trafficTrendBadge"><i class="ri-arrow-up-line me-1"></i>—</span>
            </div>
            <div class="card-body"><div id="seoTrafficChart" style="height:350px"></div></div>
        </div>
    </div>
    <div class="col-xl-4">
        <div class="card card-h-100">
            <div class="card-header"><h5 class="card-title mb-0">Traffic by Source</h5></div>
            <div class="card-body"><div id="seoSourceDonut" style="height:300px"></div></div>
        </div>
    </div>
</div>

<!-- ============ TABS ============ -->
<div class="d-flex gap-2 mb-3 flex-wrap" id="seoTabs">
    <span class="seo-tab active" data-tab="keywords"><i class="ri-key-2-line"></i> Keywords <span class="badge bg-white text-primary ms-1" id="tabBadgeKw">0</span></span>
    <span class="seo-tab" data-tab="backlinks"><i class="ri-links-line"></i> Backlinks <span class="badge bg-white text-warning ms-1" id="tabBadgeBl">0</span></span>
    <span class="seo-tab" data-tab="pages"><i class="ri-pages-line"></i> Pages</span>
    <span class="seo-tab" data-tab="competitors"><i class="ri-spy-line"></i> Competitors</span>
    <span class="seo-tab" data-tab="satellites"><i class="ri-earth-line"></i> SEO Satellites <span class="badge bg-white text-success ms-1" id="tabBadgeSat">0</span></span>
    <span class="seo-tab" data-tab="audit"><i class="ri-shield-check-line"></i> Site Audit</span>
</div>

<!-- ======== TAB: KEYWORDS ======== -->
<div class="seo-section active" id="sec-keywords">
    <div class="card">
        <div class="card-header">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <h5 class="card-title mb-0">Keyword Rankings</h5>
                <div class="d-flex gap-2">
                    <select class="form-select form-select-sm" id="kwDomainFilter" style="max-width:200px">
                        <option value="">All Domains</option>
                    </select>
                    <button class="btn btn-sm btn-outline-primary" id="exportKwCsvBtn"><i class="ri-download-line me-1"></i>Export</button>
                </div>
            </div>
            <div class="d-flex gap-2 mt-2 flex-wrap">
                <input type="text" class="form-control form-control-sm" id="kwSearch" placeholder="Search keyword..." style="max-width:220px">
                <select class="form-select form-select-sm" id="kwPosFilter" style="max-width:150px">
                    <option value="all">All Positions</option>
                    <option value="top3">Top 3</option>
                    <option value="top10">Top 10</option>
                    <option value="top20">Top 20</option>
                    <option value="top50">Top 50</option>
                </select>
                <select class="form-select form-select-sm" id="kwGroupFilter" style="max-width:160px">
                    <option value="all">All Groups</option>
                    <option value="residence">Residence</option>
                    <option value="work">Work Permit</option>
                    <option value="citizenship">Citizenship</option>
                    <option value="business">Business</option>
                    <option value="general">General</option>
                </select>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Keyword</th>
                            <th>Group</th>
                            <th class="text-center">Position</th>
                            <th class="text-center">Change</th>
                            <th class="text-center">Volume</th>
                            <th class="text-center">KD</th>
                            <th class="text-center">Clicks</th>
                            <th class="text-center">Impr.</th>
                            <th class="text-center">CTR</th>
                            <th>URL</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="kwTableBody"></tbody>
                </table>
            </div>
        </div>
        <div class="card-footer"><span class="text-muted fs-12" id="kwShowing"></span></div>
    </div>
</div>

<!-- ======== TAB: BACKLINKS ======== -->
<div class="seo-section" id="sec-backlinks">
    <div class="card">
        <div class="card-header">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <h5 class="card-title mb-0">Backlink Profile</h5>
                <div class="d-flex gap-2">
                    <span class="badge bg-success-subtle text-success p-2" id="blDoFollowBadge">DoFollow: —</span>
                    <span class="badge bg-secondary-subtle text-secondary p-2" id="blNoFollowBadge">NoFollow: —</span>
                    <button class="btn btn-sm btn-outline-primary" id="exportBlCsvBtn"><i class="ri-download-line me-1"></i>Export</button>
                </div>
            </div>
            <div class="d-flex gap-2 mt-2 flex-wrap">
                <input type="text" class="form-control form-control-sm" id="blSearch" placeholder="Search domain..." style="max-width:220px">
                <select class="form-select form-select-sm" id="blTypeFilter" style="max-width:140px">
                    <option value="all">All Types</option>
                    <option value="dofollow">DoFollow</option>
                    <option value="nofollow">NoFollow</option>
                </select>
                <select class="form-select form-select-sm" id="blDomainFilter" style="max-width:200px">
                    <option value="">All Domains</option>
                </select>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light"><tr><th>Referring Domain</th><th class="text-center">DR</th><th>Type</th><th>Anchor Text</th><th>Target URL</th><th>First Seen</th><th class="text-center">Actions</th></tr></thead>
                    <tbody id="blTableBody"></tbody>
                </table>
            </div>
        </div>
        <div class="card-footer"><span class="text-muted fs-12" id="blShowing"></span></div>
    </div>
</div>

<!-- ======== TAB: PAGES ======== -->
<div class="seo-section" id="sec-pages">
    <div class="card">
        <div class="card-header"><h5 class="card-title mb-0">Page Performance (Google Search Console)</h5></div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light"><tr><th>URL</th><th class="text-center">Clicks</th><th class="text-center">Impressions</th><th class="text-center">CTR</th><th class="text-center">Avg. Pos.</th><th class="text-center">Keywords</th></tr></thead>
                    <tbody id="pagesTableBody">
                        <tr><td colspan="6" class="text-center text-muted py-4">Loading pages data...</td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- ======== TAB: COMPETITORS ======== -->
<div class="seo-section" id="sec-competitors">
    <div class="card">
        <div class="card-header"><h5 class="card-title mb-0">Competitor Analysis</h5></div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light"><tr><th>Domain</th><th class="text-center">DR</th><th class="text-center">Organic Keywords</th><th class="text-center">Organic Traffic</th><th class="text-center">Backlinks</th><th class="text-center">Common KW</th><th class="text-center">Gap</th></tr></thead>
                    <tbody>
                        <tr><td class="fw-semibold"><i class="ri-global-line me-1 text-primary"></i>migracyjna.pl</td><td class="text-center"><span class="badge bg-success">45</span></td><td class="text-center">320</td><td class="text-center fw-semibold">12 400</td><td class="text-center">2 340</td><td class="text-center">48</td><td class="text-center"><span class="badge bg-danger-subtle text-danger">272 KW gap</span></td></tr>
                        <tr><td class="fw-semibold"><i class="ri-global-line me-1 text-info"></i>legalpoland.com</td><td class="text-center"><span class="badge bg-success">41</span></td><td class="text-center">250</td><td class="text-center fw-semibold">9 200</td><td class="text-center">1 890</td><td class="text-center">35</td><td class="text-center"><span class="badge bg-warning-subtle text-warning">215 KW gap</span></td></tr>
                        <tr><td class="fw-semibold"><i class="ri-global-line me-1 text-success"></i>immigrationlaw.pl</td><td class="text-center"><span class="badge bg-warning">38</span></td><td class="text-center">180</td><td class="text-center fw-semibold">7 800</td><td class="text-center">1 120</td><td class="text-center">28</td><td class="text-center"><span class="badge bg-warning-subtle text-warning">152 KW gap</span></td></tr>
                        <tr><td class="fw-semibold"><i class="ri-global-line me-1 text-warning"></i>pomocprawna24.pl</td><td class="text-center"><span class="badge bg-warning">29</span></td><td class="text-center">95</td><td class="text-center fw-semibold">3 100</td><td class="text-center">560</td><td class="text-center">18</td><td class="text-center"><span class="badge bg-success-subtle text-success">77 KW gap</span></td></tr>
                        <tr class="table-primary"><td class="fw-bold"><i class="ri-star-fill me-1 text-warning"></i>wincase.eu (you)</td><td class="text-center"><span class="badge bg-primary" id="compDA">—</span></td><td class="text-center fw-bold" id="compKw">—</td><td class="text-center fw-bold" id="compTraffic">—</td><td class="text-center fw-bold" id="compBl">—</td><td class="text-center">—</td><td class="text-center">—</td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- ======== TAB: SATELLITES ======== -->
<div class="seo-section" id="sec-satellites">
    <div class="card">
        <div class="card-header d-flex align-items-center justify-content-between">
            <h5 class="card-title mb-0">SEO Satellite Network</h5>
            <span class="badge bg-success p-2" id="satCountBadge"><i class="ri-earth-line me-1"></i>— satellites</span>
        </div>
        <div class="card-body">
            <div class="row g-3" id="satGrid"></div>
        </div>
    </div>
</div>

<!-- ======== TAB: AUDIT ======== -->
<div class="seo-section" id="sec-audit">
    <div class="card">
        <div class="card-header d-flex align-items-center justify-content-between">
            <h5 class="card-title mb-0">Technical SEO Audit — wincase.eu</h5>
            <div class="d-flex gap-2">
                <span class="badge bg-success p-2 fs-12">Score: 87/100</span>
                <button class="btn btn-sm btn-primary"><i class="ri-refresh-line me-1"></i>Run Audit</button>
            </div>
        </div>
        <div class="card-body">
            <div class="row g-3 mb-4">
                <div class="col-md-2 col-4"><div class="kpi-mini bg-success-subtle"><div class="fs-11 text-muted">Performance</div><div class="fw-bold text-success fs-5">92</div></div></div>
                <div class="col-md-2 col-4"><div class="kpi-mini bg-success-subtle"><div class="fs-11 text-muted">Accessibility</div><div class="fw-bold text-success fs-5">88</div></div></div>
                <div class="col-md-2 col-4"><div class="kpi-mini bg-success-subtle"><div class="fs-11 text-muted">Best Practices</div><div class="fw-bold text-success fs-5">95</div></div></div>
                <div class="col-md-2 col-4"><div class="kpi-mini bg-warning-subtle"><div class="fs-11 text-muted">SEO</div><div class="fw-bold text-warning fs-5">82</div></div></div>
                <div class="col-md-2 col-4"><div class="kpi-mini bg-success-subtle"><div class="fs-11 text-muted">HTTPS</div><div class="fw-bold text-success fs-5"><i class="ri-check-line"></i></div></div></div>
                <div class="col-md-2 col-4"><div class="kpi-mini bg-success-subtle"><div class="fs-11 text-muted">Mobile</div><div class="fw-bold text-success fs-5"><i class="ri-check-line"></i></div></div></div>
            </div>
            <h6 class="fw-semibold mb-2">Issues Found</h6>
            <div class="table-responsive">
                <table class="table table-sm table-bordered mb-0">
                    <thead class="table-light"><tr><th>Issue</th><th>Severity</th><th>Pages</th><th>Status</th></tr></thead>
                    <tbody>
                        <tr><td><i class="ri-error-warning-line me-1 text-danger"></i>Missing meta descriptions</td><td><span class="badge bg-danger">Critical</span></td><td>3</td><td><span class="badge bg-warning-subtle text-warning">Open</span></td></tr>
                        <tr><td><i class="ri-error-warning-line me-1 text-warning"></i>Images without alt text</td><td><span class="badge bg-warning">Warning</span></td><td>12</td><td><span class="badge bg-warning-subtle text-warning">Open</span></td></tr>
                        <tr><td><i class="ri-information-line me-1 text-info"></i>Slow page load (>3s)</td><td><span class="badge bg-info">Info</span></td><td>2</td><td><span class="badge bg-success-subtle text-success">Fixed</span></td></tr>
                        <tr><td><i class="ri-error-warning-line me-1 text-warning"></i>Broken internal links</td><td><span class="badge bg-warning">Warning</span></td><td>4</td><td><span class="badge bg-warning-subtle text-warning">Open</span></td></tr>
                        <tr><td><i class="ri-information-line me-1 text-info"></i>Missing H1 tag</td><td><span class="badge bg-info">Info</span></td><td>1</td><td><span class="badge bg-success-subtle text-success">Fixed</span></td></tr>
                        <tr><td><i class="ri-information-line me-1 text-info"></i>Thin content (<300 words)</td><td><span class="badge bg-info">Info</span></td><td>5</td><td><span class="badge bg-warning-subtle text-warning">Open</span></td></tr>
                        <tr><td><i class="ri-check-line me-1 text-success"></i>Sitemap.xml present</td><td><span class="badge bg-success">OK</span></td><td>—</td><td><span class="badge bg-success-subtle text-success">Pass</span></td></tr>
                        <tr><td><i class="ri-check-line me-1 text-success"></i>Robots.txt configured</td><td><span class="badge bg-success">OK</span></td><td>—</td><td><span class="badge bg-success-subtle text-success">Pass</span></td></tr>
                        <tr><td><i class="ri-check-line me-1 text-success"></i>Structured data (Schema.org)</td><td><span class="badge bg-success">OK</span></td><td>—</td><td><span class="badge bg-success-subtle text-success">Pass</span></td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- ============ VIEW KEYWORD MODAL ============ -->
<div class="modal fade" id="viewKwModal" tabindex="-1"><div class="modal-dialog"><div class="modal-content">
    <div class="modal-header bg-primary text-white">
        <h6 class="modal-title text-white"><i class="ri-key-2-line me-2"></i>Keyword Details</h6>
        <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
    </div>
    <div class="modal-body">
        <h5 class="fw-bold mb-3" id="vkKeyword"></h5>
        <div class="row g-3">
            <div class="col-4"><div class="kpi-mini bg-primary-subtle"><div class="fs-11 text-muted">Position</div><div class="fw-bold text-primary fs-4" id="vkPos"></div></div></div>
            <div class="col-4"><div class="kpi-mini bg-success-subtle"><div class="fs-11 text-muted">Volume</div><div class="fw-bold text-success" id="vkVol"></div></div></div>
            <div class="col-4"><div class="kpi-mini bg-warning-subtle"><div class="fs-11 text-muted">KD</div><div class="fw-bold text-warning" id="vkKD"></div></div></div>
            <div class="col-4"><div class="kpi-mini bg-info-subtle"><div class="fs-11 text-muted">Clicks</div><div class="fw-bold text-info" id="vkClicks"></div></div></div>
            <div class="col-4"><div class="kpi-mini bg-danger-subtle"><div class="fs-11 text-muted">Impressions</div><div class="fw-bold text-danger" id="vkImpr"></div></div></div>
            <div class="col-4"><div class="kpi-mini bg-dark-subtle"><div class="fs-11 text-muted">CTR</div><div class="fw-bold" id="vkCTR"></div></div></div>
            <div class="col-12"><label class="fs-11 text-muted">Ranking URL</label><p class="mb-0"><code class="text-primary" id="vkUrl"></code></p></div>
            <div class="col-6"><label class="fs-11 text-muted">Group</label><p class="mb-0" id="vkGroup"></p></div>
            <div class="col-6"><label class="fs-11 text-muted">Change</label><p class="mb-0" id="vkChange"></p></div>
        </div>
    </div>
    <div class="modal-footer"><button class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Close</button></div>
</div></div></div>

<!-- ============ ADD KEYWORD MODAL ============ -->
<div class="modal fade" id="addKeywordModal" tabindex="-1"><div class="modal-dialog"><div class="modal-content">
    <div class="modal-header bg-success text-white">
        <h6 class="modal-title text-white"><i class="ri-add-circle-line me-2"></i>Add Keyword to Track</h6>
        <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
    </div>
    <div class="modal-body">
        <div class="row g-3">
            <div class="col-12"><label class="form-label fs-12 fw-semibold">Keyword <span class="text-danger">*</span></label><input type="text" class="form-control" id="nkKeyword" placeholder="e.g. karta pobytu warszawa"></div>
            <div class="col-6">
                <label class="form-label fs-12 fw-semibold">Group</label>
                <select class="form-select" id="nkGroup">
                    <option value="residence">Residence</option><option value="work">Work Permit</option>
                    <option value="citizenship">Citizenship</option><option value="business">Business</option><option value="general">General</option>
                </select>
            </div>
            <div class="col-6"><label class="form-label fs-12 fw-semibold">Target URL</label><input type="text" class="form-control" id="nkUrl" placeholder="/uslugi/..."></div>
            <div class="col-6"><label class="form-label fs-12 fw-semibold">Search Volume</label><input type="number" class="form-control" id="nkVol" placeholder="1000"></div>
            <div class="col-6"><label class="form-label fs-12 fw-semibold">Current Position</label><input type="number" class="form-control" id="nkPos" placeholder="—" min="1"></div>
        </div>
    </div>
    <div class="modal-footer">
        <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button class="btn btn-success" id="saveNewKwBtn"><i class="ri-check-line me-1"></i>Add Keyword</button>
    </div>
</div></div></div>

<!-- ============ VIEW BACKLINK MODAL ============ -->
<div class="modal fade" id="viewBlModal" tabindex="-1"><div class="modal-dialog"><div class="modal-content">
    <div class="modal-header bg-warning">
        <h6 class="modal-title"><i class="ri-links-line me-2"></i>Backlink Details</h6>
        <button class="btn-close" data-bs-dismiss="modal"></button>
    </div>
    <div class="modal-body">
        <div class="row g-3">
            <div class="col-12"><label class="fs-11 text-muted">Referring Domain</label><p class="fw-bold mb-0" id="vbDomain"></p></div>
            <div class="col-6"><label class="fs-11 text-muted">Domain Rating</label><p class="fw-semibold mb-0" id="vbDR"></p></div>
            <div class="col-6"><label class="fs-11 text-muted">Type</label><p class="mb-0" id="vbType"></p></div>
            <div class="col-12"><label class="fs-11 text-muted">Anchor Text</label><p class="mb-0" id="vbAnchor"></p></div>
            <div class="col-12"><label class="fs-11 text-muted">Target URL</label><p class="mb-0"><code class="text-primary" id="vbTarget"></code></p></div>
            <div class="col-6"><label class="fs-11 text-muted">First Seen</label><p class="mb-0" id="vbSeen"></p></div>
            <div class="col-6"><label class="fs-11 text-muted">Status</label><p class="mb-0" id="vbStatus"></p></div>
        </div>
    </div>
    <div class="modal-footer"><button class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Close</button></div>
</div></div></div>

<!-- ============ DATA SOURCE ============ -->
<div class="alert alert-info d-flex align-items-center gap-2 mt-3 mb-0">
    <i class="ri-information-line fs-18"></i>
    <div><strong>Data source:</strong> Google Search Console, Google Analytics 4, Ahrefs. Configure in <a href="admin-settings" class="alert-link">Settings &rarr; Integrations</a>.</div>
</div>
@endsection

@section('js')
<script src="{{ asset('assets/libs/apexcharts/apexcharts.min.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function(){

    // ============ CONFIG ============
    const TOKEN = localStorage.getItem('wc_token');
    const API = '/api/v1';
    const HEADERS = {'Authorization':'Bearer '+TOKEN,'Accept':'application/json'};

    function n(v){ return v ? v.toLocaleString('pl-PL') : '0'; }
    function pct(v){ return v != null ? parseFloat(v).toFixed(1)+'%' : '0%'; }

    function toast(msg, type){
        type = type || 'success';
        const colors = {success:'#198754',danger:'#dc3545',warning:'#ffc107',info:'#0dcaf0',primary:'#845adf'};
        const t = document.createElement('div');
        t.style.cssText = 'position:fixed;top:20px;right:20px;z-index:9999;padding:14px 24px;border-radius:10px;color:#fff;font-weight:600;font-size:.9rem;box-shadow:0 4px 12px rgba(0,0,0,.15);transition:opacity .3s;background:'+(colors[type]||colors.info);
        t.textContent = msg; document.body.appendChild(t);
        setTimeout(function(){ t.style.opacity='0'; setTimeout(function(){ t.remove(); },300); },3000);
    }
    window.showToast = toast;

    async function apiFetch(url){
        const r = await fetch(API + url, {headers: HEADERS});
        if(r.status === 401){ window.location = '/login'; return null; }
        if(!r.ok) throw new Error('HTTP '+r.status);
        const json = await r.json();
        if(!json.success) throw new Error(json.message || 'API error');
        return json.data;
    }

    // ============ STATE ============
    let keywords = [];
    let backlinks = [];
    let satellites = [];
    let overviewData = null;
    let domainsList = [];
    let trafficChart = null;
    let sourceDonut = null;
    let tabsLoaded = { keywords: false, backlinks: false, satellites: false, reviews: false };

    const GROUP_BADGES = {residence:'bg-primary-subtle text-primary',work:'bg-success-subtle text-success',citizenship:'bg-info-subtle text-info',business:'bg-warning-subtle text-warning',general:'bg-secondary-subtle text-secondary'};
    const GROUP_LABELS = {residence:'Residence',work:'Work Permit',citizenship:'Citizenship',business:'Business',general:'General'};

    // ============ TABS ============
    document.getElementById('seoTabs').addEventListener('click', function(e){
        const tab = e.target.closest('.seo-tab'); if(!tab) return;
        document.querySelectorAll('.seo-tab').forEach(function(t){ t.classList.remove('active'); });
        tab.classList.add('active');
        document.querySelectorAll('.seo-section').forEach(function(s){ s.classList.remove('active'); });
        document.getElementById('sec-'+tab.dataset.tab).classList.add('active');

        // Lazy-load tab data
        var tabName = tab.dataset.tab;
        if(tabName === 'keywords' && !tabsLoaded.keywords) loadKeywords();
        if(tabName === 'backlinks' && !tabsLoaded.backlinks) loadBacklinks();
        if(tabName === 'satellites' && !tabsLoaded.satellites) loadNetwork();
    });

    // ============ OVERVIEW ============
    async function loadOverview(){
        try {
            var data = await apiFetch('/seo/overview');
            if(!data) return;
            overviewData = data;

            // Stat cards
            var totals = data.totals || {};
            document.getElementById('statClicks').innerHTML = n(totals.clicks)+'<span class="fs-13 text-muted fw-normal">/month</span>';
            document.getElementById('statClicksChange').innerHTML = totals.clicks_change
                ? changeHtml(totals.clicks_change, ' vs last month')
                : '<span class="change-same">—</span>';

            // Keywords top 10 count from overview
            var kwTop10 = 0;
            if(data.domains && data.domains.length){
                data.domains.forEach(function(d){
                    if(d.gsc && d.gsc.avg_position && d.gsc.avg_position <= 10) kwTop10++;
                });
            }
            document.getElementById('statKeywords').textContent = n(kwTop10 || totals.keywords_top10 || 0);
            document.getElementById('statKeywordsChange').innerHTML = totals.keywords_change
                ? changeHtml(totals.keywords_change, ' new this month')
                : '<span class="change-same">—</span>';

            // Backlinks from ahrefs totals
            var totalBl = 0;
            if(data.domains){
                data.domains.forEach(function(d){
                    if(d.ahrefs && d.ahrefs.backlinks) totalBl += d.ahrefs.backlinks;
                });
            }
            document.getElementById('statBacklinks').textContent = n(totalBl || totals.backlinks || 0);
            document.getElementById('statBacklinksChange').innerHTML = totals.backlinks_change
                ? changeHtml(totals.backlinks_change, ' new')
                : '<span class="change-same">—</span>';

            // Domain Authority / Rating
            document.getElementById('statDA').innerHTML = n(totals.max_da || 0)+' <span class="fs-13 text-muted fw-normal">/ 100</span>';
            document.getElementById('statDAChange').innerHTML = totals.da_change
                ? changeHtml(totals.da_change, ' pts')
                : '<span class="change-same">—</span>';

            // Competitor row
            document.getElementById('compDA').textContent = totals.max_da || '—';
            document.getElementById('compTraffic').textContent = n(totals.clicks);
            document.getElementById('compBl').textContent = n(totalBl);
            document.getElementById('compKw').textContent = n(kwTop10);

            // Populate domain filter dropdowns
            domainsList = [];
            if(data.domains && data.domains.length){
                data.domains.forEach(function(d){ domainsList.push(d.domain); });
            }
            populateDomainFilters();

            // Render charts from overview
            renderTrafficChart(data);
            renderSourceDonut(data);

            // Traffic trend badge
            if(totals.clicks_change){
                var badge = document.getElementById('trafficTrendBadge');
                var val = parseFloat(totals.clicks_change);
                if(val >= 0){
                    badge.className = 'badge bg-success-subtle text-success';
                    badge.innerHTML = '<i class="ri-arrow-up-line me-1"></i>+'+val+'%';
                } else {
                    badge.className = 'badge bg-danger-subtle text-danger';
                    badge.innerHTML = '<i class="ri-arrow-down-line me-1"></i>'+val+'%';
                }
            }

        } catch(err) {
            console.error('loadOverview:', err);
            toast('Failed to load SEO overview: '+err.message, 'danger');
        }
    }

    function changeHtml(val, suffix){
        suffix = suffix || '';
        var v = parseFloat(val);
        if(v > 0) return '<span class="change-up"><i class="ri-arrow-up-s-fill"></i> +'+v+suffix+'</span>';
        if(v < 0) return '<span class="change-down"><i class="ri-arrow-down-s-fill"></i> '+v+suffix+'</span>';
        return '<span class="change-same"><i class="ri-subtract-line"></i> 0'+suffix+'</span>';
    }

    function populateDomainFilters(){
        var kwSel = document.getElementById('kwDomainFilter');
        var blSel = document.getElementById('blDomainFilter');
        [kwSel, blSel].forEach(function(sel){
            sel.innerHTML = '<option value="">All Domains</option>';
            domainsList.forEach(function(d){
                sel.innerHTML += '<option value="'+d+'">'+d+'</option>';
            });
        });
    }

    // ============ CHARTS ============
    function renderTrafficChart(data){
        var categories = [];
        var visitors = [];
        var conversions = [];

        if(data.domains && data.domains.length > 0){
            // Use first domain's trend or build from totals
            var d = data.domains[0];
            if(d.gsc && d.gsc.trend){
                d.gsc.trend.forEach(function(t){
                    categories.push(t.period || t.date || '');
                    visitors.push(t.clicks || 0);
                    conversions.push(t.conversions || 0);
                });
            }
        }

        // Fallback if no trend data
        if(categories.length === 0){
            categories = ['—'];
            visitors = [data.totals ? data.totals.clicks || 0 : 0];
            conversions = [data.totals ? data.totals.conversions || 0 : 0];
        }

        var opts = {
            chart:{type:'area',height:350,toolbar:{show:false},zoom:{enabled:false}},
            series:[
                {name:'Organic Visitors',data:visitors},
                {name:'Conversions',data:conversions}
            ],
            xaxis:{categories:categories},
            yaxis:[
                {title:{text:'Visitors'},labels:{formatter:function(v){return n(v);}}},
                {opposite:true,title:{text:'Conversions'}}
            ],
            colors:['#3b82f6','#10b981'],
            fill:{type:'gradient',gradient:{shadeIntensity:1,opacityFrom:0.4,opacityTo:0.1,stops:[0,90,100]}},
            dataLabels:{enabled:false},stroke:{curve:'smooth',width:2},
            grid:{borderColor:'#f1f1f1'},legend:{position:'top'},tooltip:{shared:true},
            noData:{text:'No traffic data available',style:{fontSize:'14px',color:'#adb5bd'}}
        };

        if(trafficChart){ trafficChart.destroy(); }
        trafficChart = new ApexCharts(document.querySelector("#seoTrafficChart"), opts);
        trafficChart.render();
    }

    function renderSourceDonut(data){
        var series = [];
        var labels = [];
        var totalTraffic = 0;

        if(data.totals){
            // Build from whatever the API provides
            if(data.totals.sources){
                data.totals.sources.forEach(function(s){
                    labels.push(s.name || s.source);
                    series.push(s.value || s.sessions || 0);
                    totalTraffic += (s.value || s.sessions || 0);
                });
            }
        }

        // Fallback: estimate from overview
        if(series.length === 0 && data.totals){
            var clicks = data.totals.clicks || 0;
            var sessions = data.totals.sessions || clicks;
            totalTraffic = sessions;
            series = [Math.round(sessions*0.52), Math.round(sessions*0.24), Math.round(sessions*0.12), Math.round(sessions*0.08), Math.round(sessions*0.04)];
            labels = ['Google','Direct','Satellites','Social','Referral'];
        }

        if(series.length === 0){
            series = [1]; labels = ['No data']; totalTraffic = 0;
        }

        var opts = {
            chart:{type:'donut',height:300},
            series:series,
            labels:labels,
            colors:['#3b82f6','#6366f1','#10b981','#f59e0b','#ef4444'],
            plotOptions:{pie:{donut:{size:'65%',labels:{show:true,total:{show:true,label:'Total',formatter:function(){ return n(totalTraffic); }}}}}},
            legend:{position:'bottom'},
            dataLabels:{enabled:true,formatter:function(v){return Math.round(v)+'%';}},
            noData:{text:'No source data',style:{fontSize:'14px',color:'#adb5bd'}}
        };

        if(sourceDonut){ sourceDonut.destroy(); }
        sourceDonut = new ApexCharts(document.querySelector("#seoSourceDonut"), opts);
        sourceDonut.render();
    }

    // ============ KEYWORDS ============
    async function loadKeywords(domain){
        try {
            var url = '/seo/keywords?limit=200';
            if(domain) url += '&domain='+encodeURIComponent(domain);
            var data = await apiFetch(url);
            if(!data) return;

            keywords = (data.keywords || []).map(function(k, i){
                return {
                    kw: k.keyword || k.kw || k.query || '',
                    group: k.group || k.category || 'general',
                    pos: k.position || k.pos || 99,
                    change: k.change || k.position_change || 0,
                    vol: k.volume || k.search_volume || k.vol || 0,
                    kd: k.difficulty || k.kd || 0,
                    clicks: k.clicks || 0,
                    impr: k.impressions || k.impr || 0,
                    ctr: k.ctr || 0,
                    url: k.url || k.page || k.landing_page || '/'
                };
            });

            tabsLoaded.keywords = true;
            document.getElementById('tabBadgeKw').textContent = keywords.filter(function(k){ return k.pos <= 10; }).length;
            renderKeywords();
        } catch(err){
            console.error('loadKeywords:', err);
            toast('Failed to load keywords: '+err.message, 'danger');
            document.getElementById('kwTableBody').innerHTML = '<tr><td colspan="12" class="text-center text-muted py-4">Failed to load keywords data</td></tr>';
            document.getElementById('kwShowing').textContent = '';
        }
    }

    function renderKeywords(){
        var search = (document.getElementById('kwSearch').value||'').toLowerCase();
        var posF = document.getElementById('kwPosFilter').value;
        var kwGroup = document.getElementById('kwGroupFilter').value;
        var tbody = document.getElementById('kwTableBody');
        tbody.innerHTML = '';

        if(keywords.length === 0){
            tbody.innerHTML = '<tr><td colspan="12" class="text-center text-muted py-4">No keywords found</td></tr>';
            document.getElementById('kwShowing').textContent = 'Showing 0 keywords';
            return;
        }

        var count = 0;
        var html = '';
        keywords.forEach(function(k, idx){
            if(search && k.kw.toLowerCase().indexOf(search) === -1) return;
            if(kwGroup !== 'all' && k.group !== kwGroup) return;
            if(posF === 'top3' && k.pos > 3) return;
            if(posF === 'top10' && k.pos > 10) return;
            if(posF === 'top20' && k.pos > 20) return;
            if(posF === 'top50' && k.pos > 50) return;
            count++;

            var posClass = k.pos <= 3 ? 'pos-1' : (k.pos <= 10 ? 'pos-4' : 'pos-11-plus');
            var ch = '';
            if(k.change > 0) ch = '<span class="change-up"><i class="ri-arrow-up-s-fill"></i>'+k.change+'</span>';
            else if(k.change < 0) ch = '<span class="change-down"><i class="ri-arrow-down-s-fill"></i>'+Math.abs(k.change)+'</span>';
            else ch = '<span class="change-same"><i class="ri-subtract-line"></i>0</span>';

            html += '<tr data-idx="'+idx+'">'
                +'<td class="text-muted fs-12">'+count+'</td>'
                +'<td class="fw-medium"><a href="#" class="text-body act-view-kw">'+k.kw+'</a></td>'
                +'<td><span class="badge '+(GROUP_BADGES[k.group]||GROUP_BADGES.general)+'">'+(GROUP_LABELS[k.group]||k.group)+'</span></td>'
                +'<td class="text-center"><span class="pos-badge '+posClass+'">'+k.pos+'</span></td>'
                +'<td class="text-center">'+ch+'</td>'
                +'<td class="text-center">'+n(k.vol)+'</td>'
                +'<td class="text-center">'+k.kd+'</td>'
                +'<td class="text-center fw-semibold">'+n(k.clicks)+'</td>'
                +'<td class="text-center">'+n(k.impr)+'</td>'
                +'<td class="text-center">'+pct(k.ctr)+'</td>'
                +'<td><code class="fs-11">'+k.url+'</code></td>'
                +'<td class="text-center"><button class="btn btn-sm btn-subtle-primary act-view-kw" title="View"><i class="ri-eye-line"></i></button></td>'
                +'</tr>';
        });

        tbody.innerHTML = html || '<tr><td colspan="12" class="text-center text-muted py-4">No matching keywords</td></tr>';
        document.getElementById('kwShowing').textContent = 'Showing '+count+' of '+keywords.length+' keywords';
    }

    document.getElementById('kwSearch').addEventListener('input', renderKeywords);
    document.getElementById('kwPosFilter').addEventListener('change', renderKeywords);
    document.getElementById('kwGroupFilter').addEventListener('change', renderKeywords);
    document.getElementById('kwDomainFilter').addEventListener('change', function(){
        loadKeywords(this.value);
    });

    // Keyword view modal
    document.getElementById('kwTableBody').addEventListener('click', function(e){
        var btn = e.target.closest('.act-view-kw'); if(!btn) return; e.preventDefault();
        var idx = parseInt(btn.closest('tr').dataset.idx);
        var k = keywords[idx]; if(!k) return;
        document.getElementById('vkKeyword').textContent = k.kw;
        document.getElementById('vkPos').textContent = k.pos;
        document.getElementById('vkVol').textContent = n(k.vol);
        document.getElementById('vkKD').textContent = k.kd+'/100';
        document.getElementById('vkClicks').textContent = n(k.clicks);
        document.getElementById('vkImpr').textContent = n(k.impr);
        document.getElementById('vkCTR').textContent = pct(k.ctr);
        document.getElementById('vkUrl').textContent = k.url;
        document.getElementById('vkGroup').innerHTML = '<span class="badge '+(GROUP_BADGES[k.group]||GROUP_BADGES.general)+'">'+(GROUP_LABELS[k.group]||k.group)+'</span>';
        var ch = '';
        if(k.change > 0) ch = '<span class="change-up"><i class="ri-arrow-up-s-fill"></i> +'+k.change+' positions</span>';
        else if(k.change < 0) ch = '<span class="change-down"><i class="ri-arrow-down-s-fill"></i> '+k.change+' positions</span>';
        else ch = '<span class="change-same">No change</span>';
        document.getElementById('vkChange').innerHTML = ch;
        new bootstrap.Modal(document.getElementById('viewKwModal')).show();
    });

    // Add keyword
    document.getElementById('saveNewKwBtn').addEventListener('click', function(){
        var kw = document.getElementById('nkKeyword').value;
        if(!kw){ toast('Enter keyword','warning'); return; }
        keywords.push({
            kw: kw,
            group: document.getElementById('nkGroup').value,
            pos: parseInt(document.getElementById('nkPos').value) || 99,
            change: 0,
            vol: parseInt(document.getElementById('nkVol').value) || 0,
            kd: 0, clicks: 0, impr: 0, ctr: 0,
            url: document.getElementById('nkUrl').value || '/'
        });
        renderKeywords();
        bootstrap.Modal.getInstance(document.getElementById('addKeywordModal')).hide();
        toast('Keyword "'+kw+'" added','success');
    });

    // Export keywords CSV
    document.getElementById('exportKwCsvBtn').addEventListener('click', function(){
        if(keywords.length === 0){ toast('No keywords to export','warning'); return; }
        var csv = 'Keyword,Group,Position,Change,Volume,KD,Clicks,Impressions,CTR,URL\n';
        keywords.forEach(function(k){
            csv += '"'+k.kw+'",'+(GROUP_LABELS[k.group]||k.group)+','+k.pos+','+k.change+','+k.vol+','+k.kd+','+k.clicks+','+k.impr+','+k.ctr+','+k.url+'\n';
        });
        downloadCsv(csv, 'keywords');
    });

    // ============ BACKLINKS ============
    async function loadBacklinks(domain){
        try {
            var url = '/seo/backlinks?days=90';
            if(domain) url += '&domain='+encodeURIComponent(domain);
            var data = await apiFetch(url);
            if(!data) return;

            // data.trend contains backlink entries, data.changes has summary
            var raw = data.trend || data.backlinks || [];
            backlinks = raw.map(function(b){
                return {
                    domain: b.referring_domain || b.domain || b.source || '',
                    dr: b.domain_rating || b.dr || b.authority || 0,
                    type: b.type || b.link_type || 'dofollow',
                    anchor: b.anchor_text || b.anchor || '',
                    target: b.target_url || b.target || b.page || '/',
                    seen: b.first_seen || b.discovered_at || b.seen || '—',
                    status: b.status || (b.is_lost ? 'lost' : 'active')
                };
            });

            tabsLoaded.backlinks = true;
            document.getElementById('tabBadgeBl').textContent = n(backlinks.length);

            // DoFollow / NoFollow counts
            var doFollow = backlinks.filter(function(b){ return b.type === 'dofollow'; }).length;
            var noFollow = backlinks.length - doFollow;
            document.getElementById('blDoFollowBadge').textContent = 'DoFollow: '+n(doFollow);
            document.getElementById('blNoFollowBadge').textContent = 'NoFollow: '+n(noFollow);

            renderBacklinks();
        } catch(err){
            console.error('loadBacklinks:', err);
            toast('Failed to load backlinks: '+err.message, 'danger');
            document.getElementById('blTableBody').innerHTML = '<tr><td colspan="7" class="text-center text-muted py-4">Failed to load backlinks data</td></tr>';
            document.getElementById('blShowing').textContent = '';
        }
    }

    function renderBacklinks(){
        var search = (document.getElementById('blSearch').value||'').toLowerCase();
        var typeF = document.getElementById('blTypeFilter').value;
        var tbody = document.getElementById('blTableBody');
        tbody.innerHTML = '';

        if(backlinks.length === 0){
            tbody.innerHTML = '<tr><td colspan="7" class="text-center text-muted py-4">No backlinks found</td></tr>';
            document.getElementById('blShowing').textContent = 'Showing 0 backlinks';
            return;
        }

        var count = 0;
        var html = '';
        backlinks.forEach(function(b, idx){
            if(search && b.domain.toLowerCase().indexOf(search) === -1 && b.anchor.toLowerCase().indexOf(search) === -1) return;
            if(typeF !== 'all' && b.type !== typeF) return;
            count++;

            var typeBadge = b.type === 'dofollow'
                ? '<span class="badge bg-success-subtle text-success">DoFollow</span>'
                : '<span class="badge bg-secondary-subtle text-secondary">NoFollow</span>';
            var statusBadge = b.status === 'active' ? '' : '<span class="badge bg-danger-subtle text-danger ms-1">Lost</span>';

            html += '<tr data-idx="'+idx+'">'
                +'<td class="fw-medium">'+b.domain+statusBadge+'</td>'
                +'<td class="text-center"><span class="badge '+(b.dr>=60?'bg-success':b.dr>=30?'bg-warning':'bg-secondary')+'">'+b.dr+'</span></td>'
                +'<td>'+typeBadge+'</td>'
                +'<td class="fs-12">'+b.anchor+'</td>'
                +'<td><code class="fs-11">'+b.target+'</code></td>'
                +'<td class="text-muted fs-12">'+b.seen+'</td>'
                +'<td class="text-center"><button class="btn btn-sm btn-subtle-primary act-view-bl" title="View"><i class="ri-eye-line"></i></button></td>'
                +'</tr>';
        });

        tbody.innerHTML = html || '<tr><td colspan="7" class="text-center text-muted py-4">No matching backlinks</td></tr>';
        document.getElementById('blShowing').textContent = 'Showing '+count+' of '+backlinks.length+' backlinks';
    }

    document.getElementById('blSearch').addEventListener('input', renderBacklinks);
    document.getElementById('blTypeFilter').addEventListener('change', renderBacklinks);
    document.getElementById('blDomainFilter').addEventListener('change', function(){
        loadBacklinks(this.value);
    });

    // Backlink view modal
    document.getElementById('blTableBody').addEventListener('click', function(e){
        var btn = e.target.closest('.act-view-bl'); if(!btn) return; e.preventDefault();
        var idx = parseInt(btn.closest('tr').dataset.idx);
        var b = backlinks[idx]; if(!b) return;
        document.getElementById('vbDomain').textContent = b.domain;
        document.getElementById('vbDR').textContent = b.dr+' / 100';
        document.getElementById('vbType').innerHTML = b.type === 'dofollow' ? '<span class="badge bg-success">DoFollow</span>' : '<span class="badge bg-secondary">NoFollow</span>';
        document.getElementById('vbAnchor').textContent = b.anchor;
        document.getElementById('vbTarget').textContent = b.target;
        document.getElementById('vbSeen').textContent = b.seen;
        document.getElementById('vbStatus').innerHTML = b.status === 'active' ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-danger">Lost</span>';
        new bootstrap.Modal(document.getElementById('viewBlModal')).show();
    });

    // Export backlinks CSV
    document.getElementById('exportBlCsvBtn').addEventListener('click', function(){
        if(backlinks.length === 0){ toast('No backlinks to export','warning'); return; }
        var csv = 'Domain,DR,Type,Anchor,Target,First Seen,Status\n';
        backlinks.forEach(function(b){
            csv += b.domain+','+b.dr+','+b.type+',"'+b.anchor+'",'+b.target+','+b.seen+','+b.status+'\n';
        });
        downloadCsv(csv, 'backlinks');
    });

    // ============ SATELLITES / NETWORK ============
    async function loadNetwork(){
        try {
            var data = await apiFetch('/seo/network');
            if(!data) return;

            satellites = Array.isArray(data) ? data : (data.sites || data.satellites || []);
            tabsLoaded.satellites = true;

            document.getElementById('tabBadgeSat').textContent = satellites.length;
            document.getElementById('satCountBadge').innerHTML = '<i class="ri-earth-line me-1"></i>'+satellites.length+' satellites'+(satellites.length > 0 ? ' active' : '');

            var grid = document.getElementById('satGrid');
            grid.innerHTML = '';

            if(satellites.length === 0){
                grid.innerHTML = '<div class="col-12 text-center text-muted py-4">No satellite sites found</div>';
                return;
            }

            var html = '';
            satellites.forEach(function(s){
                var domain = s.domain || s.url || '';
                var status = s.status || (s.is_active ? 'online' : 'offline');
                var desc = s.description || s.desc || s.name || '';
                var dr = s.domain_rating || s.dr || s.authority || 0;
                var kw = s.keywords || s.kw || s.organic_keywords || 0;
                var traffic = s.traffic || s.organic_traffic || s.visitors || 0;
                var bl = s.backlinks || s.referring_domains || 0;

                html += '<div class="col-xl-3 col-md-4 col-sm-6">'
                    +'<div class="sat-card">'
                    +'<div class="d-flex align-items-center justify-content-between mb-2">'
                    +'<span class="fw-bold fs-13">'+domain+'</span>'
                    +'<span class="sat-status '+status+'" title="'+status+'"></span>'
                    +'</div>'
                    +'<p class="text-muted fs-11 mb-2">'+desc+'</p>'
                    +'<div class="row g-1 text-center">'
                    +'<div class="col-3"><div class="fs-10 text-muted">DR</div><div class="fw-bold fs-12">'+dr+'</div></div>'
                    +'<div class="col-3"><div class="fs-10 text-muted">KW</div><div class="fw-bold fs-12">'+kw+'</div></div>'
                    +'<div class="col-3"><div class="fs-10 text-muted">Traffic</div><div class="fw-bold fs-12">'+n(traffic)+'</div></div>'
                    +'<div class="col-3"><div class="fs-10 text-muted">BL</div><div class="fw-bold fs-12">'+bl+'</div></div>'
                    +'</div>'
                    +'<div class="mt-2 d-flex gap-1">'
                    +'<a href="https://'+domain+'" target="_blank" class="btn btn-sm btn-subtle-primary flex-fill"><i class="ri-external-link-line me-1"></i>Visit</a>'
                    +'<button class="btn btn-sm btn-subtle-success flex-fill" onclick="showToast(\'Crawl started for '+domain+'\',\'info\')"><i class="ri-refresh-line me-1"></i>Crawl</button>'
                    +'</div>'
                    +'</div>'
                    +'</div>';
            });
            grid.innerHTML = html;
        } catch(err){
            console.error('loadNetwork:', err);
            toast('Failed to load satellite network: '+err.message, 'danger');
            document.getElementById('satGrid').innerHTML = '<div class="col-12 text-center text-muted py-4">Failed to load satellite data</div>';
        }
    }

    // ============ REVIEWS (loaded from brand endpoint) ============
    async function loadReviews(){
        try {
            var data = await apiFetch('/seo/reviews');
            if(!data) return;
            // Reviews data available if needed for future tabs
            tabsLoaded.reviews = true;
        } catch(err){
            console.error('loadReviews:', err);
        }
    }

    // ============ HELPERS ============
    function downloadCsv(csv, name){
        var blob = new Blob([csv], {type:'text/csv'});
        var url = URL.createObjectURL(blob);
        var a = document.createElement('a');
        a.href = url;
        a.download = name+'_'+new Date().toISOString().slice(0,10)+'.csv';
        a.click();
        URL.revokeObjectURL(url);
        toast('CSV exported','success');
    }

    // ============ INIT: Load all data ============
    loadOverview();
    loadKeywords();
    loadBacklinks();
    loadNetwork();

});
</script>
@endsection
