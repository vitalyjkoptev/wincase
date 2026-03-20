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
                    <h4 class="mb-0 fw-semibold">5 230<span class="fs-13 text-muted fw-normal">/month</span></h4>
                    <span class="fs-11 change-up"><i class="ri-arrow-up-s-fill"></i> +12.3% vs last month</span>
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
                    <h4 class="mb-0 fw-semibold">47</h4>
                    <span class="fs-11 change-up"><i class="ri-arrow-up-s-fill"></i> +8 new this month</span>
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
                    <h4 class="mb-0 fw-semibold">1 245</h4>
                    <span class="fs-11 change-up"><i class="ri-arrow-up-s-fill"></i> +34 new</span>
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
                    <h4 class="mb-0 fw-semibold">32 <span class="fs-13 text-muted fw-normal">/ 100</span></h4>
                    <span class="fs-11 change-up"><i class="ri-arrow-up-s-fill"></i> +3 pts</span>
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
                <span class="badge bg-success-subtle text-success"><i class="ri-arrow-up-line me-1"></i>+12.3%</span>
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
    <span class="seo-tab active" data-tab="keywords"><i class="ri-key-2-line"></i> Keywords <span class="badge bg-white text-primary ms-1">47</span></span>
    <span class="seo-tab" data-tab="backlinks"><i class="ri-links-line"></i> Backlinks <span class="badge bg-white text-warning ms-1">1245</span></span>
    <span class="seo-tab" data-tab="pages"><i class="ri-pages-line"></i> Pages</span>
    <span class="seo-tab" data-tab="competitors"><i class="ri-spy-line"></i> Competitors</span>
    <span class="seo-tab" data-tab="satellites"><i class="ri-earth-line"></i> SEO Satellites <span class="badge bg-white text-success ms-1">8</span></span>
    <span class="seo-tab" data-tab="audit"><i class="ri-shield-check-line"></i> Site Audit</span>
</div>

<!-- ======== TAB: KEYWORDS ======== -->
<div class="seo-section active" id="sec-keywords">
    <div class="card">
        <div class="card-header">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <h5 class="card-title mb-0">Keyword Rankings</h5>
                <div class="d-flex gap-2">
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
                    <span class="badge bg-success-subtle text-success p-2">DoFollow: 845</span>
                    <span class="badge bg-secondary-subtle text-secondary p-2">NoFollow: 400</span>
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
                    <tbody>
                        <tr><td><code class="text-primary">/uslugi/pozwolenie-na-prace</code></td><td class="text-center fw-semibold">1 240</td><td class="text-center">18 500</td><td class="text-center text-success">6,7%</td><td class="text-center">4,2</td><td class="text-center">24</td></tr>
                        <tr><td><code class="text-primary">/uslugi/karta-pobytu</code></td><td class="text-center fw-semibold">980</td><td class="text-center">15 200</td><td class="text-center text-success">6,4%</td><td class="text-center">3,8</td><td class="text-center">18</td></tr>
                        <tr><td><code class="text-primary">/uslugi/rejestracja-firmy</code></td><td class="text-center fw-semibold">650</td><td class="text-center">9 800</td><td class="text-center text-success">6,6%</td><td class="text-center">5,1</td><td class="text-center">12</td></tr>
                        <tr><td><code class="text-primary">/blog/legalizacja-pobytu-2026</code></td><td class="text-center fw-semibold">520</td><td class="text-center">12 300</td><td class="text-center">4,2%</td><td class="text-center">7,3</td><td class="text-center">15</td></tr>
                        <tr><td><code class="text-primary">/uslugi/eu-blue-card</code></td><td class="text-center fw-semibold">310</td><td class="text-center">5 400</td><td class="text-center text-success">5,7%</td><td class="text-center">6,5</td><td class="text-center">9</td></tr>
                        <tr><td><code class="text-primary">/uslugi/obywatelstwo</code></td><td class="text-center fw-semibold">280</td><td class="text-center">4 800</td><td class="text-center text-success">5,8%</td><td class="text-center">5,9</td><td class="text-center">11</td></tr>
                        <tr><td><code class="text-primary">/blog/praca-w-polsce-2026</code></td><td class="text-center fw-semibold">410</td><td class="text-center">8 900</td><td class="text-center">4,6%</td><td class="text-center">8,1</td><td class="text-center">8</td></tr>
                        <tr><td><code class="text-primary">/uslugi/laczenie-rodzin</code></td><td class="text-center fw-semibold">190</td><td class="text-center">3 200</td><td class="text-center text-success">5,9%</td><td class="text-center">7,2</td><td class="text-center">6</td></tr>
                        <tr><td><code class="text-primary">/kontakt</code></td><td class="text-center fw-semibold">340</td><td class="text-center">2 100</td><td class="text-center text-success">16,2%</td><td class="text-center">2,1</td><td class="text-center">5</td></tr>
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
                        <tr class="table-primary"><td class="fw-bold"><i class="ri-star-fill me-1 text-warning"></i>wincase.eu (you)</td><td class="text-center"><span class="badge bg-primary">32</span></td><td class="text-center fw-bold">145</td><td class="text-center fw-bold">5 230</td><td class="text-center fw-bold">1 245</td><td class="text-center">—</td><td class="text-center">—</td></tr>
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
            <span class="badge bg-success p-2"><i class="ri-earth-line me-1"></i>8 satellites active</span>
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

<script>
document.addEventListener('DOMContentLoaded', function(){

    // ============ TABS ============
    document.getElementById('seoTabs').addEventListener('click', function(e){
        const tab = e.target.closest('.seo-tab'); if(!tab) return;
        document.querySelectorAll('.seo-tab').forEach(t=>t.classList.remove('active'));
        tab.classList.add('active');
        document.querySelectorAll('.seo-section').forEach(s=>s.classList.remove('active'));
        document.getElementById('sec-'+tab.dataset.tab).classList.add('active');
    });

    // ============ KEYWORDS DATA ============
    const GROUP_BADGES = {residence:'bg-primary-subtle text-primary',work:'bg-success-subtle text-success',citizenship:'bg-info-subtle text-info',business:'bg-warning-subtle text-warning',general:'bg-secondary-subtle text-secondary'};
    const GROUP_LABELS = {residence:'Residence',work:'Work Permit',citizenship:'Citizenship',business:'Business',general:'General'};

    let keywords = [
        {kw:'karta pobytu',group:'residence',pos:3,change:2,vol:8100,kd:42,clicks:980,impr:15200,ctr:6.4,url:'/uslugi/karta-pobytu'},
        {kw:'pozwolenie na prace',group:'work',pos:5,change:1,vol:6600,kd:38,clicks:720,impr:11400,ctr:6.3,url:'/uslugi/pozwolenie-na-prace'},
        {kw:'legalizacja pobytu',group:'residence',pos:2,change:3,vol:4400,kd:35,clicks:520,impr:8900,ctr:5.8,url:'/blog/legalizacja-pobytu-2026'},
        {kw:'wiza pracownicza',group:'work',pos:7,change:-1,vol:3200,kd:29,clicks:280,impr:5600,ctr:5.0,url:'/uslugi/pozwolenie-na-prace'},
        {kw:'rejestracja firmy polska',group:'business',pos:4,change:5,vol:2900,kd:44,clicks:650,impr:9800,ctr:6.6,url:'/uslugi/rejestracja-firmy'},
        {kw:'zezwolenie na pobyt',group:'residence',pos:6,change:0,vol:2400,kd:31,clicks:310,impr:5400,ctr:5.7,url:'/uslugi/karta-pobytu'},
        {kw:'eu blue card polska',group:'work',pos:8,change:4,vol:1800,kd:25,clicks:190,impr:3800,ctr:5.0,url:'/uslugi/eu-blue-card'},
        {kw:'doradztwo imigracyjne',group:'general',pos:10,change:-2,vol:1200,kd:22,clicks:120,impr:2800,ctr:4.3,url:'/'},
        {kw:'pobyt czasowy',group:'residence',pos:9,change:1,vol:3800,kd:36,clicks:240,impr:5200,ctr:4.6,url:'/uslugi/karta-pobytu'},
        {kw:'obywatelstwo polskie',group:'citizenship',pos:12,change:3,vol:5400,kd:55,clicks:180,impr:6400,ctr:2.8,url:'/uslugi/obywatelstwo'},
        {kw:'karta stalego pobytu',group:'residence',pos:4,change:2,vol:3600,kd:33,clicks:420,impr:7100,ctr:5.9,url:'/uslugi/karta-pobytu'},
        {kw:'praca dla cudzoziemcow',group:'work',pos:6,change:0,vol:4800,kd:41,clicks:380,impr:7800,ctr:4.9,url:'/uslugi/pozwolenie-na-prace'},
        {kw:'laczenie rodzin polska',group:'residence',pos:3,change:4,vol:1600,kd:18,clicks:190,impr:3200,ctr:5.9,url:'/uslugi/laczenie-rodzin'},
        {kw:'biuro imigracyjne warszawa',group:'general',pos:1,change:1,vol:900,kd:15,clicks:340,impr:2100,ctr:16.2,url:'/kontakt'},
        {kw:'karta pobytu dokumenty',group:'residence',pos:5,change:2,vol:2200,kd:28,clicks:210,impr:4500,ctr:4.7,url:'/uslugi/karta-pobytu'},
        {kw:'zezwolenie na prace typ a',group:'work',pos:11,change:-1,vol:2800,kd:30,clicks:140,impr:4200,ctr:3.3,url:'/uslugi/pozwolenie-na-prace'},
        {kw:'pobyt staly wymagania',group:'residence',pos:8,change:3,vol:1400,kd:24,clicks:110,impr:2600,ctr:4.2,url:'/uslugi/karta-pobytu'},
        {kw:'nabycie obywatelstwa',group:'citizenship',pos:15,change:2,vol:2100,kd:48,clicks:80,impr:3100,ctr:2.6,url:'/uslugi/obywatelstwo'},
        {kw:'firma w polsce cudzoziemiec',group:'business',pos:7,change:1,vol:1900,kd:35,clicks:160,impr:3400,ctr:4.7,url:'/uslugi/rejestracja-firmy'},
        {kw:'pomoc prawna imigracja',group:'general',pos:14,change:-3,vol:1100,kd:20,clicks:60,impr:2200,ctr:2.7,url:'/'},
        {kw:'odwolanie od decyzji wojewody',group:'general',pos:18,change:5,vol:800,kd:19,clicks:35,impr:1800,ctr:1.9,url:'/uslugi/odwolania'},
        {kw:'pesel dla cudzoziemca',group:'general',pos:13,change:0,vol:3200,kd:26,clicks:150,impr:4100,ctr:3.7,url:'/blog/pesel-cudzoziemiec'}
    ];

    let kwFilter = 'all', kwGroup = 'all';

    function renderKeywords(){
        const search = (document.getElementById('kwSearch').value||'').toLowerCase();
        const posF = document.getElementById('kwPosFilter').value;
        kwGroup = document.getElementById('kwGroupFilter').value;
        const tbody = document.getElementById('kwTableBody');
        tbody.innerHTML = '';
        let count = 0;
        keywords.forEach((k,idx) => {
            if(search && !k.kw.toLowerCase().includes(search)) return;
            if(kwGroup!=='all' && k.group!==kwGroup) return;
            if(posF==='top3' && k.pos>3) return;
            if(posF==='top10' && k.pos>10) return;
            if(posF==='top20' && k.pos>20) return;
            if(posF==='top50' && k.pos>50) return;
            count++;
            const posClass = k.pos<=3?'pos-1':k.pos<=10?'pos-4':'pos-11-plus';
            let changeHtml = '';
            if(k.change>0) changeHtml = `<span class="change-up"><i class="ri-arrow-up-s-fill"></i>${k.change}</span>`;
            else if(k.change<0) changeHtml = `<span class="change-down"><i class="ri-arrow-down-s-fill"></i>${Math.abs(k.change)}</span>`;
            else changeHtml = `<span class="change-same"><i class="ri-subtract-line"></i>0</span>`;

            tbody.innerHTML += `<tr data-idx="${idx}">
                <td class="text-muted fs-12">${count}</td>
                <td class="fw-medium"><a href="#" class="text-body act-view-kw">${k.kw}</a></td>
                <td><span class="badge ${GROUP_BADGES[k.group]}">${GROUP_LABELS[k.group]}</span></td>
                <td class="text-center"><span class="pos-badge ${posClass}">${k.pos}</span></td>
                <td class="text-center">${changeHtml}</td>
                <td class="text-center">${k.vol.toLocaleString('pl-PL')}</td>
                <td class="text-center">${k.kd}</td>
                <td class="text-center fw-semibold">${k.clicks.toLocaleString('pl-PL')}</td>
                <td class="text-center">${k.impr.toLocaleString('pl-PL')}</td>
                <td class="text-center">${k.ctr}%</td>
                <td><code class="fs-11">${k.url}</code></td>
                <td class="text-center"><button class="btn btn-sm btn-subtle-primary act-view-kw" title="View"><i class="ri-eye-line"></i></button></td>
            </tr>`;
        });
        document.getElementById('kwShowing').textContent = `Showing ${count} keywords`;
    }
    renderKeywords();
    document.getElementById('kwSearch').addEventListener('input',()=>renderKeywords());
    document.getElementById('kwPosFilter').addEventListener('change',()=>renderKeywords());
    document.getElementById('kwGroupFilter').addEventListener('change',()=>renderKeywords());

    // Keyword view
    document.getElementById('kwTableBody').addEventListener('click',function(e){
        const btn=e.target.closest('.act-view-kw'); if(!btn)return; e.preventDefault();
        const idx=parseInt(btn.closest('tr').dataset.idx);
        const k=keywords[idx]; if(!k) return;
        document.getElementById('vkKeyword').textContent=k.kw;
        document.getElementById('vkPos').textContent=k.pos;
        document.getElementById('vkVol').textContent=k.vol.toLocaleString('pl-PL');
        document.getElementById('vkKD').textContent=k.kd+'/100';
        document.getElementById('vkClicks').textContent=k.clicks.toLocaleString('pl-PL');
        document.getElementById('vkImpr').textContent=k.impr.toLocaleString('pl-PL');
        document.getElementById('vkCTR').textContent=k.ctr+'%';
        document.getElementById('vkUrl').textContent=k.url;
        document.getElementById('vkGroup').innerHTML=`<span class="badge ${GROUP_BADGES[k.group]}">${GROUP_LABELS[k.group]}</span>`;
        let ch=''; if(k.change>0) ch=`<span class="change-up"><i class="ri-arrow-up-s-fill"></i> +${k.change} positions</span>`;
        else if(k.change<0) ch=`<span class="change-down"><i class="ri-arrow-down-s-fill"></i> ${k.change} positions</span>`;
        else ch=`<span class="change-same">No change</span>`;
        document.getElementById('vkChange').innerHTML=ch;
        new bootstrap.Modal(document.getElementById('viewKwModal')).show();
    });

    // Add keyword
    document.getElementById('saveNewKwBtn').addEventListener('click',function(){
        const kw=document.getElementById('nkKeyword').value;
        if(!kw){showToast('Enter keyword','warning');return;}
        keywords.push({
            kw:kw,group:document.getElementById('nkGroup').value,
            pos:parseInt(document.getElementById('nkPos').value)||99,
            change:0,vol:parseInt(document.getElementById('nkVol').value)||0,
            kd:0,clicks:0,impr:0,ctr:0,
            url:document.getElementById('nkUrl').value||'/'
        });
        renderKeywords();
        bootstrap.Modal.getInstance(document.getElementById('addKeywordModal')).hide();
        showToast('Keyword "'+kw+'" added','success');
    });

    // Export kw
    document.getElementById('exportKwCsvBtn').addEventListener('click',function(){
        let csv='Keyword,Group,Position,Change,Volume,KD,Clicks,Impressions,CTR,URL\n';
        keywords.forEach(k=>{csv+=`"${k.kw}",${GROUP_LABELS[k.group]},${k.pos},${k.change},${k.vol},${k.kd},${k.clicks},${k.impr},${k.ctr},${k.url}\n`;});
        download(csv,'keywords');
    });

    // ============ BACKLINKS DATA ============
    let backlinks = [
        {domain:'prawo.pl',dr:72,type:'dofollow',anchor:'legalizacja pobytu w Polsce',target:'/uslugi/karta-pobytu',seen:'2025-12-15',status:'active'},
        {domain:'biznes.gov.pl',dr:85,type:'dofollow',anchor:'pozwolenie na prace cudzoziemca',target:'/uslugi/pozwolenie-na-prace',seen:'2025-11-28',status:'active'},
        {domain:'forum.ukraina.pl',dr:45,type:'nofollow',anchor:'WinCase karta pobytu pomoc',target:'/',seen:'2026-01-10',status:'active'},
        {domain:'gazetaprawna.pl',dr:68,type:'dofollow',anchor:'biuro imigracyjne Warszawa',target:'/kontakt',seen:'2026-01-22',status:'active'},
        {domain:'reddit.com/r/poland',dr:91,type:'nofollow',anchor:'wincase.eu immigration help',target:'/',seen:'2026-02-05',status:'active'},
        {domain:'money.pl',dr:78,type:'dofollow',anchor:'praca dla cudzoziemcow 2026',target:'/uslugi/pozwolenie-na-prace',seen:'2026-02-12',status:'active'},
        {domain:'infor.pl',dr:65,type:'dofollow',anchor:'firma w Polsce rejestracja',target:'/uslugi/rejestracja-firmy',seen:'2025-10-08',status:'active'},
        {domain:'polandpulse.news',dr:18,type:'dofollow',anchor:'WinCase immigration services',target:'/',seen:'2026-02-20',status:'active'},
        {domain:'eurogamingpost.com',dr:15,type:'dofollow',anchor:'work permit Poland',target:'/uslugi/pozwolenie-na-prace',seen:'2026-02-18',status:'active'},
        {domain:'quora.com',dr:93,type:'nofollow',anchor:'how to get karta pobytu',target:'/uslugi/karta-pobytu',seen:'2026-01-05',status:'active'},
        {domain:'legalizacja-polska.pl',dr:12,type:'dofollow',anchor:'legalizacja pobytu',target:'/uslugi/karta-pobytu',seen:'2026-02-22',status:'active'},
        {domain:'karta-pobytu.info',dr:10,type:'dofollow',anchor:'karta pobytu Warszawa',target:'/uslugi/karta-pobytu',seen:'2026-02-25',status:'active'},
        {domain:'work-permit-poland.com',dr:8,type:'dofollow',anchor:'work permit services',target:'/uslugi/pozwolenie-na-prace',seen:'2026-02-24',status:'active'},
        {domain:'wykop.pl',dr:72,type:'nofollow',anchor:'pomoc imigracyjna',target:'/',seen:'2026-01-18',status:'lost'},
        {domain:'olx.pl',dr:80,type:'nofollow',anchor:'WinCase',target:'/',seen:'2025-09-14',status:'lost'}
    ];

    function renderBacklinks(){
        const search=(document.getElementById('blSearch').value||'').toLowerCase();
        const typeF=document.getElementById('blTypeFilter').value;
        const tbody=document.getElementById('blTableBody');
        tbody.innerHTML='';
        let count=0;
        backlinks.forEach((b,idx)=>{
            if(search && !b.domain.toLowerCase().includes(search) && !b.anchor.toLowerCase().includes(search)) return;
            if(typeF!=='all' && b.type!==typeF) return;
            count++;
            const typeBadge=b.type==='dofollow'?'<span class="badge bg-success-subtle text-success">DoFollow</span>':'<span class="badge bg-secondary-subtle text-secondary">NoFollow</span>';
            const statusBadge=b.status==='active'?'':'<span class="badge bg-danger-subtle text-danger ms-1">Lost</span>';
            tbody.innerHTML+=`<tr data-idx="${idx}">
                <td class="fw-medium">${b.domain}${statusBadge}</td>
                <td class="text-center"><span class="badge ${b.dr>=60?'bg-success':b.dr>=30?'bg-warning':'bg-secondary'}">${b.dr}</span></td>
                <td>${typeBadge}</td>
                <td class="fs-12">${b.anchor}</td>
                <td><code class="fs-11">${b.target}</code></td>
                <td class="text-muted fs-12">${b.seen}</td>
                <td class="text-center"><button class="btn btn-sm btn-subtle-primary act-view-bl" title="View"><i class="ri-eye-line"></i></button></td>
            </tr>`;
        });
        document.getElementById('blShowing').textContent=`Showing ${count} backlinks`;
    }
    renderBacklinks();
    document.getElementById('blSearch').addEventListener('input',()=>renderBacklinks());
    document.getElementById('blTypeFilter').addEventListener('change',()=>renderBacklinks());

    document.getElementById('blTableBody').addEventListener('click',function(e){
        const btn=e.target.closest('.act-view-bl'); if(!btn)return; e.preventDefault();
        const idx=parseInt(btn.closest('tr').dataset.idx);
        const b=backlinks[idx]; if(!b) return;
        document.getElementById('vbDomain').textContent=b.domain;
        document.getElementById('vbDR').textContent=b.dr+' / 100';
        document.getElementById('vbType').innerHTML=b.type==='dofollow'?'<span class="badge bg-success">DoFollow</span>':'<span class="badge bg-secondary">NoFollow</span>';
        document.getElementById('vbAnchor').textContent=b.anchor;
        document.getElementById('vbTarget').textContent=b.target;
        document.getElementById('vbSeen').textContent=b.seen;
        document.getElementById('vbStatus').innerHTML=b.status==='active'?'<span class="badge bg-success">Active</span>':'<span class="badge bg-danger">Lost</span>';
        new bootstrap.Modal(document.getElementById('viewBlModal')).show();
    });

    document.getElementById('exportBlCsvBtn').addEventListener('click',function(){
        let csv='Domain,DR,Type,Anchor,Target,First Seen,Status\n';
        backlinks.forEach(b=>{csv+=`${b.domain},${b.dr},${b.type},"${b.anchor}",${b.target},${b.seen},${b.status}\n`;});
        download(csv,'backlinks');
    });

    // ============ SATELLITES ============
    const satellites = [
        {domain:'legalizacja-polska.pl',dr:12,kw:28,traffic:420,backlinks:85,status:'online',desc:'Legalization services PL'},
        {domain:'karta-pobytu.info',dr:10,kw:22,traffic:350,backlinks:62,status:'online',desc:'Karta pobytu info portal'},
        {domain:'work-permit-poland.com',dr:8,kw:18,traffic:280,backlinks:45,status:'online',desc:'Work permit EN portal'},
        {domain:'vnzh-polsha.com',dr:7,kw:15,traffic:210,backlinks:38,status:'online',desc:'VNZh portal for RU audience'},
        {domain:'praca-dla-obcokrajowcow.pl',dr:9,kw:24,traffic:390,backlinks:71,status:'online',desc:'Jobs for foreigners PL'},
        {domain:'posvidka-polshcha.com',dr:6,kw:12,traffic:180,backlinks:29,status:'online',desc:'Residence permit UA portal'},
        {domain:'immigration-warsaw.com',dr:11,kw:20,traffic:310,backlinks:54,status:'online',desc:'Immigration services EN'},
        {domain:'visa-polska.com',dr:8,kw:16,traffic:240,backlinks:42,status:'online',desc:'Visa services multilingual'}
    ];

    const satGrid=document.getElementById('satGrid');
    satellites.forEach(s=>{
        satGrid.innerHTML+=`<div class="col-xl-3 col-md-4 col-sm-6">
            <div class="sat-card">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="fw-bold fs-13">${s.domain}</span>
                    <span class="sat-status ${s.status}" title="${s.status}"></span>
                </div>
                <p class="text-muted fs-11 mb-2">${s.desc}</p>
                <div class="row g-1 text-center">
                    <div class="col-3"><div class="fs-10 text-muted">DR</div><div class="fw-bold fs-12">${s.dr}</div></div>
                    <div class="col-3"><div class="fs-10 text-muted">KW</div><div class="fw-bold fs-12">${s.kw}</div></div>
                    <div class="col-3"><div class="fs-10 text-muted">Traffic</div><div class="fw-bold fs-12">${s.traffic}</div></div>
                    <div class="col-3"><div class="fs-10 text-muted">BL</div><div class="fw-bold fs-12">${s.backlinks}</div></div>
                </div>
                <div class="mt-2 d-flex gap-1">
                    <a href="https://${s.domain}" target="_blank" class="btn btn-sm btn-subtle-primary flex-fill"><i class="ri-external-link-line me-1"></i>Visit</a>
                    <button class="btn btn-sm btn-subtle-success flex-fill" onclick="showToast('Crawl started for ${s.domain}','info')"><i class="ri-refresh-line me-1"></i>Crawl</button>
                </div>
            </div>
        </div>`;
    });

    // ============ HELPERS ============
    function download(csv,name){
        const blob=new Blob([csv],{type:'text/csv'});const url=URL.createObjectURL(blob);
        const a=document.createElement('a');a.href=url;a.download=name+'_'+new Date().toISOString().slice(0,10)+'.csv';a.click();URL.revokeObjectURL(url);
        showToast('CSV exported','success');
    }

    window.showToast = function(msg,type){
        const colors={success:'#198754',danger:'#dc3545',warning:'#ffc107',info:'#0dcaf0',primary:'#845adf'};
        const t=document.createElement('div');
        t.style.cssText='position:fixed;top:20px;right:20px;z-index:9999;padding:14px 24px;border-radius:10px;color:#fff;font-weight:600;font-size:.9rem;box-shadow:0 4px 12px rgba(0,0,0,.15);transition:opacity .3s;background:'+(colors[type]||colors.info);
        t.textContent=msg;document.body.appendChild(t);
        setTimeout(()=>{t.style.opacity='0';setTimeout(()=>t.remove(),300);},3000);
    };
});
</script>
@endsection

@section('js')
<script src="{{ asset('assets/libs/apexcharts/apexcharts.min.js') }}"></script>
<script>
// Organic Traffic Trend
new ApexCharts(document.querySelector("#seoTrafficChart"), {
    chart:{type:'area',height:350,toolbar:{show:false},zoom:{enabled:false}},
    series:[{name:'Organic Visitors',data:[3420,3890,4150,4480,4870,5230]},{name:'Conversions',data:[42,51,58,62,71,89]}],
    xaxis:{categories:['Oct 2025','Nov 2025','Dec 2025','Jan 2026','Feb 2026','Mar 2026']},
    yaxis:[{title:{text:'Visitors'},labels:{formatter:function(v){return v.toLocaleString('pl-PL');}}},{opposite:true,title:{text:'Conversions'}}],
    colors:['#3b82f6','#10b981'],
    fill:{type:'gradient',gradient:{shadeIntensity:1,opacityFrom:0.4,opacityTo:0.1,stops:[0,90,100]}},
    dataLabels:{enabled:false},stroke:{curve:'smooth',width:2},
    grid:{borderColor:'#f1f1f1'},legend:{position:'top'},tooltip:{shared:true}
}).render();

// Traffic by Source — Donut
new ApexCharts(document.querySelector("#seoSourceDonut"), {
    chart:{type:'donut',height:300},
    series:[52,24,12,8,4],
    labels:['Google','Direct','Satellites','Social','Referral'],
    colors:['#3b82f6','#6366f1','#10b981','#f59e0b','#ef4444'],
    plotOptions:{pie:{donut:{size:'65%',labels:{show:true,total:{show:true,label:'Total',formatter:function(){return '5 230';}}}}}},
    legend:{position:'bottom'},
    dataLabels:{enabled:true,formatter:function(v){return Math.round(v)+'%';}}
}).render();
</script>
@endsection
