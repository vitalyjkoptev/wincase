@extends('partials.layouts.master')
@section('title', 'Advertising | WinCase CRM')
@section('sub-title', 'Advertising')
@section('sub-title-lang', 'wc-advertising')
@section('pagetitle', 'Marketing')
@section('pagetitle-lang', 'wc-marketing')
@section('buttonTitle', 'New Campaign')
@section('buttonTitle-lang', 'wc-new-campaign')
@section('modalTarget', 'newCampaignModal')
@section('content')
<style>
    .camp-filter-pill{cursor:pointer;padding:6px 16px;border-radius:20px;font-size:.8rem;font-weight:600;border:1px solid #e9ecef;background:#fff;transition:.2s;display:inline-flex;align-items:center;gap:4px}
    .camp-filter-pill:hover{border-color:#845adf}
    .camp-filter-pill.active{background:#845adf;color:#fff;border-color:#845adf}
    .budget-bar{height:6px;border-radius:3px;overflow:hidden;background:#e9ecef;margin-top:4px}
    .budget-bar .fill{height:100%;border-radius:3px;transition:width .4s}
    .platform-icon{width:28px;height:28px;border-radius:6px;display:inline-flex;align-items:center;justify-content:center;font-size:14px}
    .kpi-box{border-radius:10px;padding:16px;text-align:center;transition:.2s}
    .kpi-box:hover{transform:translateY(-2px);box-shadow:0 4px 12px rgba(0,0,0,.06)}
    .stat-card-trend{font-size:.7rem;font-weight:600}
</style>

<!-- ============ STAT CARDS ============ -->
<div class="row">
    <div class="col-xl-3 col-md-6">
        <div class="card card-h-100"><div class="card-body">
            <div class="d-flex align-items-center gap-3">
                <div class="avatar avatar-sm bg-primary-subtle text-primary rounded-2"><i class="ri-megaphone-line fs-18"></i></div>
                <div>
                    <p class="text-muted mb-0 fs-13">Active Campaigns</p>
                    <h4 class="mb-0 fw-semibold" id="statActive">7</h4>
                    <span class="stat-card-trend text-success"><i class="ri-arrow-up-s-fill"></i> +2 this month</span>
                </div>
            </div>
        </div></div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card card-h-100"><div class="card-body">
            <div class="d-flex align-items-center gap-3">
                <div class="avatar avatar-sm bg-success-subtle text-success rounded-2"><i class="ri-money-euro-circle-line fs-18"></i></div>
                <div>
                    <p class="text-muted mb-0 fs-13">Monthly Spend</p>
                    <h4 class="mb-0 fw-semibold" id="statSpend">8 450 PLN</h4>
                    <span class="stat-card-trend text-danger"><i class="ri-arrow-up-s-fill"></i> +6% vs last month</span>
                </div>
            </div>
        </div></div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card card-h-100"><div class="card-body">
            <div class="d-flex align-items-center gap-3">
                <div class="avatar avatar-sm bg-warning-subtle text-warning rounded-2"><i class="ri-user-follow-line fs-18"></i></div>
                <div>
                    <p class="text-muted mb-0 fs-13">Leads from Ads</p>
                    <h4 class="mb-0 fw-semibold" id="statLeads">89</h4>
                    <span class="stat-card-trend text-success"><i class="ri-arrow-up-s-fill"></i> +18% vs last month</span>
                </div>
            </div>
        </div></div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card card-h-100"><div class="card-body">
            <div class="d-flex align-items-center gap-3">
                <div class="avatar avatar-sm bg-info-subtle text-info rounded-2"><i class="ri-line-chart-line fs-18"></i></div>
                <div>
                    <p class="text-muted mb-0 fs-13">Avg. CPL</p>
                    <h4 class="mb-0 fw-semibold" id="statCPL">94,94 PLN</h4>
                    <span class="stat-card-trend text-success"><i class="ri-arrow-down-s-fill"></i> -5% improvement</span>
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
                <h5 class="card-title mb-0 flex-grow-1">Ad Spend vs Leads</h5>
                <span class="badge bg-success-subtle text-success"><i class="ri-arrow-up-line me-1"></i>+18% leads</span>
            </div>
            <div class="card-body"><div id="adsSpendLeadsChart" style="height:350px"></div></div>
        </div>
    </div>
    <div class="col-xl-4">
        <div class="card card-h-100">
            <div class="card-header"><h5 class="card-title mb-0">Spend by Platform</h5></div>
            <div class="card-body"><div id="adsPlatformDonut" style="height:300px"></div></div>
        </div>
    </div>
</div>

<!-- ============ CAMPAIGNS TABLE ============ -->
<div class="card">
    <div class="card-header">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
            <h5 class="card-title mb-0">All Campaigns</h5>
            <div class="d-flex gap-2">
                <button class="btn btn-sm btn-outline-primary" id="exportCampCsvBtn"><i class="ri-download-line me-1"></i>Export CSV</button>
                <a href="crm-leads" class="btn btn-sm btn-outline-success"><i class="ri-user-follow-line me-1"></i>CRM Leads</a>
                <a href="marketing-landing-pages" class="btn btn-sm btn-outline-info"><i class="ri-pages-line me-1"></i>Landing Pages</a>
            </div>
        </div>
        <div class="d-flex gap-1 mt-2 flex-wrap" id="campFilterPills">
            <span class="camp-filter-pill active" data-filter="all">All <span class="badge bg-secondary ms-1" id="cCntAll">0</span></span>
            <span class="camp-filter-pill" data-filter="active">Active <span class="badge bg-success ms-1" id="cCntActive">0</span></span>
            <span class="camp-filter-pill" data-filter="paused">Paused <span class="badge bg-warning ms-1" id="cCntPaused">0</span></span>
            <span class="camp-filter-pill" data-filter="completed">Completed <span class="badge bg-secondary ms-1" id="cCntCompleted">0</span></span>
            <span class="camp-filter-pill" data-filter="draft">Draft <span class="badge bg-info ms-1" id="cCntDraft">0</span></span>
        </div>
        <div class="d-flex gap-2 mt-2 flex-wrap">
            <input type="text" class="form-control form-control-sm" id="campSearch" placeholder="Search campaign..." style="max-width:220px">
            <select class="form-select form-select-sm" id="campPlatformFilter" style="max-width:160px">
                <option value="all">All Platforms</option>
                <option value="facebook">Facebook</option>
                <option value="google">Google Ads</option>
                <option value="tiktok">TikTok</option>
                <option value="instagram">Instagram</option>
                <option value="linkedin">LinkedIn</option>
                <option value="youtube">YouTube</option>
            </select>
            <select class="form-select form-select-sm" id="campObjFilter" style="max-width:160px">
                <option value="all">All Objectives</option>
                <option value="lead_gen">Lead Generation</option>
                <option value="traffic">Traffic</option>
                <option value="brand">Brand Awareness</option>
                <option value="conversions">Conversions</option>
                <option value="retargeting">Retargeting</option>
            </select>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Campaign</th>
                        <th>Platform</th>
                        <th>Objective</th>
                        <th class="text-end">Budget</th>
                        <th>Spent</th>
                        <th class="text-center">Leads</th>
                        <th class="text-end">CPL</th>
                        <th class="text-center">CTR</th>
                        <th>Status</th>
                        <th>Period</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody id="campTableBody"></tbody>
            </table>
        </div>
    </div>
    <div class="card-footer d-flex align-items-center justify-content-between">
        <span class="text-muted fs-12" id="campShowing">Showing 0 campaigns</span>
        <div class="d-flex gap-1">
            <a href="marketing-seo" class="btn btn-sm btn-outline-secondary"><i class="ri-search-eye-line me-1"></i>SEO</a>
            <a href="marketing-social-media" class="btn btn-sm btn-outline-secondary"><i class="ri-share-forward-line me-1"></i>Social Media</a>
        </div>
    </div>
</div>

<!-- ============ TOP ADS + DAILY ============ -->
<div class="row">
    <div class="col-xl-6">
        <div class="card card-h-100">
            <div class="card-header"><h5 class="card-title mb-0">Top Performing Ads</h5></div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light"><tr><th>Ad Name</th><th>Platform</th><th class="text-center">Clicks</th><th class="text-center">CTR</th><th class="text-center">Conv.</th><th class="text-end">Cost/Conv</th></tr></thead>
                        <tbody>
                            <tr><td class="fw-medium">Work Permit Video Ad</td><td><span class="badge bg-primary-subtle text-primary">Facebook</span></td><td class="text-center">1 245</td><td class="text-center text-success fw-semibold">3,8%</td><td class="text-center">18</td><td class="text-end">62,50 PLN</td></tr>
                            <tr><td class="fw-medium">Karta Pobytu Search</td><td><span class="badge bg-success-subtle text-success">Google</span></td><td class="text-center">890</td><td class="text-center text-success fw-semibold">5,2%</td><td class="text-center">12</td><td class="text-end">78,30 PLN</td></tr>
                            <tr><td class="fw-medium">Immigration Tips Reel</td><td><span class="badge bg-dark">TikTok</span></td><td class="text-center">2 340</td><td class="text-center text-success fw-semibold">6,1%</td><td class="text-center">9</td><td class="text-end">88,90 PLN</td></tr>
                            <tr><td class="fw-medium">Free Consultation CTA</td><td><span class="badge bg-primary-subtle text-primary">Facebook</span></td><td class="text-center">780</td><td class="text-center text-warning fw-semibold">4,5%</td><td class="text-center">14</td><td class="text-end">51,40 PLN</td></tr>
                            <tr><td class="fw-medium">Blue Card EU Carousel</td><td><span class="badge bg-gradient-secondary text-white">Instagram</span></td><td class="text-center">1 120</td><td class="text-center text-success fw-semibold">4,2%</td><td class="text-center">8</td><td class="text-end">72,00 PLN</td></tr>
                            <tr><td class="fw-medium">Company Registration</td><td><span class="badge bg-success-subtle text-success">Google</span></td><td class="text-center">560</td><td class="text-center fw-semibold">4,0%</td><td class="text-center">6</td><td class="text-end">95,00 PLN</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-6">
        <div class="card card-h-100">
            <div class="card-header"><h5 class="card-title mb-0">Daily Performance (Last 14 Days)</h5></div>
            <div class="card-body"><div id="adsDailyChart" style="height:280px"></div></div>
        </div>
    </div>
</div>

<!-- ============ VIEW CAMPAIGN MODAL ============ -->
<div class="modal fade" id="viewCampModal" tabindex="-1"><div class="modal-dialog modal-lg"><div class="modal-content">
    <div class="modal-header bg-primary text-white">
        <h6 class="modal-title text-white"><i class="ri-megaphone-line me-2"></i>Campaign Details</h6>
        <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
    </div>
    <div class="modal-body">
        <div class="row g-3 mb-3">
            <div class="col-12"><h5 class="fw-bold mb-0" id="vcName"></h5></div>
            <div class="col-md-3 col-6"><label class="fs-11 text-muted">Platform</label><p class="fw-semibold mb-0" id="vcPlatform"></p></div>
            <div class="col-md-3 col-6"><label class="fs-11 text-muted">Objective</label><p class="fw-semibold mb-0" id="vcObjective"></p></div>
            <div class="col-md-3 col-6"><label class="fs-11 text-muted">Status</label><p class="mb-0" id="vcStatus"></p></div>
            <div class="col-md-3 col-6"><label class="fs-11 text-muted">Period</label><p class="fw-semibold mb-0" id="vcPeriod"></p></div>
        </div>
        <div class="row g-3 mb-3">
            <div class="col-md-3 col-6"><label class="fs-11 text-muted">Target Audience</label><p class="mb-0 fs-12" id="vcAudience"></p></div>
            <div class="col-md-3 col-6"><label class="fs-11 text-muted">Landing Page</label><p class="mb-0" id="vcLanding"></p></div>
            <div class="col-md-6"><label class="fs-11 text-muted">UTM</label><p class="mb-0 fs-11 text-muted font-monospace" id="vcUtm"></p></div>
        </div>
        <hr>
        <div class="row g-3">
            <div class="col-md-2 col-4"><div class="kpi-box bg-primary-subtle"><div class="fs-11 text-muted">Budget</div><div class="fw-bold text-primary" id="vcBudget"></div></div></div>
            <div class="col-md-2 col-4"><div class="kpi-box bg-warning-subtle"><div class="fs-11 text-muted">Spent</div><div class="fw-bold text-warning" id="vcSpent"></div></div></div>
            <div class="col-md-2 col-4"><div class="kpi-box bg-success-subtle"><div class="fs-11 text-muted">Leads</div><div class="fw-bold text-success" id="vcLeads"></div></div></div>
            <div class="col-md-2 col-4"><div class="kpi-box bg-info-subtle"><div class="fs-11 text-muted">CPL</div><div class="fw-bold text-info" id="vcCPL"></div></div></div>
            <div class="col-md-2 col-4"><div class="kpi-box bg-danger-subtle"><div class="fs-11 text-muted">Clicks</div><div class="fw-bold text-danger" id="vcClicks"></div></div></div>
            <div class="col-md-2 col-4"><div class="kpi-box bg-dark-subtle"><div class="fs-11 text-muted">CTR</div><div class="fw-bold" id="vcCTR"></div></div></div>
        </div>
        <div class="mt-3">
            <label class="fs-11 text-muted">Budget Usage</label>
            <div class="budget-bar" style="height:10px"><div class="fill" id="vcBudgetBar"></div></div>
            <div class="d-flex justify-content-between fs-11 text-muted mt-1"><span id="vcBudgetPct"></span><span id="vcBudgetRemaining"></span></div>
        </div>
    </div>
    <div class="modal-footer">
        <button class="btn btn-sm btn-outline-info" id="vcEditBtn"><i class="ri-pencil-line me-1"></i>Edit</button>
        <button class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Close</button>
    </div>
</div></div></div>

<!-- ============ EDIT CAMPAIGN MODAL ============ -->
<div class="modal fade" id="editCampModal" tabindex="-1"><div class="modal-dialog modal-lg"><div class="modal-content">
    <div class="modal-header bg-info text-white">
        <h6 class="modal-title text-white"><i class="ri-pencil-line me-2"></i>Edit Campaign</h6>
        <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
    </div>
    <div class="modal-body">
        <div class="row g-3">
            <div class="col-md-8"><label class="form-label fs-12 fw-semibold">Campaign Name</label><input type="text" class="form-control" id="ecName"></div>
            <div class="col-md-4">
                <label class="form-label fs-12 fw-semibold">Platform</label>
                <select class="form-select" id="ecPlatform">
                    <option value="facebook">Facebook</option><option value="google">Google Ads</option>
                    <option value="tiktok">TikTok</option><option value="instagram">Instagram</option>
                    <option value="linkedin">LinkedIn</option><option value="youtube">YouTube</option>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label fs-12 fw-semibold">Objective</label>
                <select class="form-select" id="ecObjective">
                    <option value="lead_gen">Lead Generation</option><option value="traffic">Traffic</option>
                    <option value="brand">Brand Awareness</option><option value="conversions">Conversions</option>
                    <option value="retargeting">Retargeting</option>
                </select>
            </div>
            <div class="col-md-4"><label class="form-label fs-12 fw-semibold">Budget (PLN)</label><input type="number" class="form-control" id="ecBudget" step="0.01"></div>
            <div class="col-md-4">
                <label class="form-label fs-12 fw-semibold">Status</label>
                <select class="form-select" id="ecStatus">
                    <option value="active">Active</option><option value="paused">Paused</option>
                    <option value="completed">Completed</option><option value="draft">Draft</option>
                </select>
            </div>
            <div class="col-md-4"><label class="form-label fs-12 fw-semibold">Start Date</label><input type="date" class="form-control" id="ecStart"></div>
            <div class="col-md-4"><label class="form-label fs-12 fw-semibold">End Date</label><input type="date" class="form-control" id="ecEnd"></div>
            <div class="col-md-4"><label class="form-label fs-12 fw-semibold">Daily Budget (PLN)</label><input type="number" class="form-control" id="ecDaily" step="0.01"></div>
            <div class="col-12"><label class="form-label fs-12 fw-semibold">Target Audience</label><input type="text" class="form-control" id="ecAudience"></div>
            <div class="col-md-6"><label class="form-label fs-12 fw-semibold">Landing Page URL</label><input type="text" class="form-control" id="ecLanding"></div>
            <div class="col-md-6"><label class="form-label fs-12 fw-semibold">UTM Source</label><input type="text" class="form-control" id="ecUtm"></div>
            <div class="col-12"><label class="form-label fs-12 fw-semibold">Notes</label><textarea class="form-control" id="ecNotes" rows="2"></textarea></div>
        </div>
    </div>
    <div class="modal-footer">
        <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button class="btn btn-info text-white" id="saveEditCampBtn"><i class="ri-check-line me-1"></i>Save Changes</button>
    </div>
</div></div></div>

<!-- ============ NEW CAMPAIGN MODAL ============ -->
<div class="modal fade" id="newCampaignModal" tabindex="-1"><div class="modal-dialog modal-lg"><div class="modal-content">
    <div class="modal-header bg-primary text-white">
        <h6 class="modal-title text-white"><i class="ri-rocket-line me-2"></i>Create New Campaign</h6>
        <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
    </div>
    <div class="modal-body">
        <div class="row g-3">
            <div class="col-md-8"><label class="form-label fs-12 fw-semibold">Campaign Name <span class="text-danger">*</span></label><input type="text" class="form-control" id="ncName" placeholder="e.g. FB Work Permit Spring 2026"></div>
            <div class="col-md-4">
                <label class="form-label fs-12 fw-semibold">Platform <span class="text-danger">*</span></label>
                <select class="form-select" id="ncPlatform">
                    <option value="facebook">Facebook</option><option value="google">Google Ads</option>
                    <option value="tiktok">TikTok</option><option value="instagram">Instagram</option>
                    <option value="linkedin">LinkedIn</option><option value="youtube">YouTube</option>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label fs-12 fw-semibold">Objective</label>
                <select class="form-select" id="ncObjective">
                    <option value="lead_gen">Lead Generation</option><option value="traffic">Traffic</option>
                    <option value="brand">Brand Awareness</option><option value="conversions">Conversions</option>
                    <option value="retargeting">Retargeting</option>
                </select>
            </div>
            <div class="col-md-4"><label class="form-label fs-12 fw-semibold">Total Budget (PLN) <span class="text-danger">*</span></label><input type="number" class="form-control" id="ncBudget" placeholder="3000" step="0.01"></div>
            <div class="col-md-4"><label class="form-label fs-12 fw-semibold">Daily Budget (PLN)</label><input type="number" class="form-control" id="ncDaily" placeholder="100" step="0.01"></div>
            <div class="col-md-4"><label class="form-label fs-12 fw-semibold">Start Date</label><input type="date" class="form-control" id="ncStart"></div>
            <div class="col-md-4"><label class="form-label fs-12 fw-semibold">End Date</label><input type="date" class="form-control" id="ncEnd"></div>
            <div class="col-md-4">
                <label class="form-label fs-12 fw-semibold">Launch As</label>
                <select class="form-select" id="ncLaunchAs">
                    <option value="active">Active (launch now)</option>
                    <option value="draft">Draft (save for later)</option>
                </select>
            </div>
            <div class="col-12"><label class="form-label fs-12 fw-semibold">Target Audience</label><input type="text" class="form-control" id="ncAudience" placeholder="e.g. Ukrainians in Poland, 25-45, interested in immigration"></div>
            <div class="col-md-6"><label class="form-label fs-12 fw-semibold">Landing Page URL</label><input type="url" class="form-control" id="ncLanding" placeholder="https://wincase.eu/..."></div>
            <div class="col-md-6"><label class="form-label fs-12 fw-semibold">UTM Campaign</label><input type="text" class="form-control" id="ncUtm" placeholder="spring_2026_work_permit"></div>
            <div class="col-12"><label class="form-label fs-12 fw-semibold">Notes</label><textarea class="form-control" id="ncNotes" rows="2" placeholder="Campaign description, goals..."></textarea></div>
        </div>
    </div>
    <div class="modal-footer">
        <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button class="btn btn-primary" id="saveNewCampBtn"><i class="ri-rocket-line me-1"></i>Launch Campaign</button>
    </div>
</div></div></div>

<!-- ============ CAMPAIGN STATS MODAL ============ -->
<div class="modal fade" id="statsCampModal" tabindex="-1"><div class="modal-dialog modal-lg"><div class="modal-content">
    <div class="modal-header bg-success text-white">
        <h6 class="modal-title text-white"><i class="ri-bar-chart-box-line me-2"></i>Campaign Analytics</h6>
        <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
    </div>
    <div class="modal-body">
        <h5 class="fw-bold mb-3" id="scName"></h5>
        <div class="row g-3 mb-4">
            <div class="col-md-2 col-4"><div class="kpi-box bg-primary-subtle"><div class="fs-11 text-muted">Impressions</div><div class="fw-bold text-primary" id="scImpressions"></div></div></div>
            <div class="col-md-2 col-4"><div class="kpi-box bg-info-subtle"><div class="fs-11 text-muted">Reach</div><div class="fw-bold text-info" id="scReach"></div></div></div>
            <div class="col-md-2 col-4"><div class="kpi-box bg-success-subtle"><div class="fs-11 text-muted">Clicks</div><div class="fw-bold text-success" id="scClicks"></div></div></div>
            <div class="col-md-2 col-4"><div class="kpi-box bg-warning-subtle"><div class="fs-11 text-muted">CTR</div><div class="fw-bold text-warning" id="scCTR"></div></div></div>
            <div class="col-md-2 col-4"><div class="kpi-box bg-danger-subtle"><div class="fs-11 text-muted">Leads</div><div class="fw-bold text-danger" id="scLeads"></div></div></div>
            <div class="col-md-2 col-4"><div class="kpi-box bg-dark-subtle"><div class="fs-11 text-muted">Conv. Rate</div><div class="fw-bold" id="scConvRate"></div></div></div>
        </div>
        <div class="row g-3">
            <div class="col-md-3 col-6"><div class="border rounded p-3 text-center"><div class="fs-11 text-muted">CPC</div><div class="fw-bold" id="scCPC"></div></div></div>
            <div class="col-md-3 col-6"><div class="border rounded p-3 text-center"><div class="fs-11 text-muted">CPL</div><div class="fw-bold" id="scCPL"></div></div></div>
            <div class="col-md-3 col-6"><div class="border rounded p-3 text-center"><div class="fs-11 text-muted">CPM</div><div class="fw-bold" id="scCPM"></div></div></div>
            <div class="col-md-3 col-6"><div class="border rounded p-3 text-center"><div class="fs-11 text-muted">ROAS</div><div class="fw-bold text-success" id="scROAS"></div></div></div>
        </div>
        <hr>
        <h6 class="fw-semibold">Leads Breakdown</h6>
        <div class="table-responsive">
            <table class="table table-sm table-bordered mb-0">
                <thead class="table-light"><tr><th>Source</th><th>Leads</th><th>Quality</th><th>Converted</th></tr></thead>
                <tbody id="scLeadsTable"></tbody>
            </table>
        </div>
    </div>
    <div class="modal-footer">
        <button class="btn btn-sm btn-outline-primary" onclick="window.print()"><i class="ri-printer-line me-1"></i>Print Report</button>
        <button class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Close</button>
    </div>
</div></div></div>

<script>
document.addEventListener('DOMContentLoaded', function(){

    const PLATFORM_BADGES = {
        facebook:'<span class="badge bg-primary-subtle text-primary"><i class="ri-facebook-fill me-1"></i>Facebook</span>',
        google:'<span class="badge bg-success-subtle text-success"><i class="ri-google-fill me-1"></i>Google Ads</span>',
        tiktok:'<span class="badge bg-dark"><i class="ri-tiktok-fill me-1"></i>TikTok</span>',
        instagram:'<span class="badge bg-gradient-secondary text-white"><i class="ri-instagram-fill me-1"></i>Instagram</span>',
        linkedin:'<span class="badge bg-info-subtle text-info"><i class="ri-linkedin-fill me-1"></i>LinkedIn</span>',
        youtube:'<span class="badge bg-danger-subtle text-danger"><i class="ri-youtube-fill me-1"></i>YouTube</span>'
    };
    const PLATFORM_LABELS = {facebook:'Facebook',google:'Google Ads',tiktok:'TikTok',instagram:'Instagram',linkedin:'LinkedIn',youtube:'YouTube'};
    const OBJ_LABELS = {lead_gen:'Lead Generation',traffic:'Traffic',brand:'Brand Awareness',conversions:'Conversions',retargeting:'Retargeting'};
    const STATUS_BADGES = {
        active:'<span class="badge bg-success-subtle text-success">Active</span>',
        paused:'<span class="badge bg-warning-subtle text-warning">Paused</span>',
        completed:'<span class="badge bg-secondary-subtle text-secondary">Completed</span>',
        draft:'<span class="badge bg-info-subtle text-info">Draft</span>'
    };

    let campaigns = [
        {id:'c1',name:'FB Work Permit UA',platform:'facebook',objective:'lead_gen',budget:3000,spent:2340,leads:32,clicks:1245,impressions:32800,reach:18500,ctr:3.8,status:'active',start:'2026-02-01',end:'2026-03-31',daily:100,audience:'Ukrainians in Poland, 25-45, work permit seekers',landing:'https://wincase.eu/work-permit',utm:'fb_work_permit_ua',notes:'Main lead gen campaign'},
        {id:'c2',name:'Google Search Karta Pobytu',platform:'google',objective:'lead_gen',budget:2500,spent:1890,leads:18,clicks:890,impressions:14200,reach:12100,ctr:5.2,status:'active',start:'2026-01-15',end:'2026-04-15',daily:80,audience:'People searching for karta pobytu in Google',landing:'https://wincase.eu/karta-pobytu',utm:'google_karta_pobytu',notes:'Search campaign targeting immigration keywords'},
        {id:'c3',name:'TikTok Reels Immigration',platform:'tiktok',objective:'brand',budget:1500,spent:1200,leads:15,clicks:2340,impressions:89000,reach:45000,ctr:6.1,status:'active',start:'2026-02-10',end:'2026-03-31',daily:50,audience:'Young professionals 20-35, interested in immigration',landing:'https://wincase.eu/',utm:'tiktok_reels',notes:'Video content - immigration tips'},
        {id:'c4',name:'FB Lookalike Audience',platform:'facebook',objective:'lead_gen',budget:800,spent:650,leads:12,clicks:780,impressions:15400,reach:9200,ctr:4.5,status:'paused',start:'2026-01-20',end:'2026-03-20',daily:40,audience:'Lookalike based on existing clients',landing:'https://wincase.eu/consultation',utm:'fb_lookalike',notes:'Paused to optimize audience'},
        {id:'c5',name:'Google Display Retargeting',platform:'google',objective:'retargeting',budget:650,spent:650,leads:12,clicks:560,impressions:28000,reach:8400,ctr:4.0,status:'completed',start:'2026-01-01',end:'2026-02-28',daily:25,audience:'Website visitors last 30 days',landing:'https://wincase.eu/',utm:'google_retargeting',notes:'Completed - good ROAS'},
        {id:'c6',name:'Instagram Stories Blue Card',platform:'instagram',objective:'lead_gen',budget:1200,spent:890,leads:10,clicks:1120,impressions:34500,reach:19800,ctr:4.2,status:'active',start:'2026-02-15',end:'2026-04-15',daily:45,audience:'IT professionals, age 28-45, EU Blue Card',landing:'https://wincase.eu/blue-card',utm:'ig_blue_card',notes:'Targeting tech workers'},
        {id:'c7',name:'LinkedIn B2B Immigration',platform:'linkedin',objective:'lead_gen',budget:2000,spent:1450,leads:8,clicks:420,impressions:12000,reach:7800,ctr:3.5,status:'active',start:'2026-02-01',end:'2026-04-30',daily:60,audience:'HR managers, relocation specialists, B2B',landing:'https://wincase.eu/business',utm:'linkedin_b2b',notes:'B2B corporate clients'},
        {id:'c8',name:'YouTube Pre-Roll Immigration',platform:'youtube',objective:'brand',budget:1800,spent:920,leads:5,clicks:680,impressions:45000,reach:32000,ctr:1.5,status:'active',start:'2026-02-20',end:'2026-05-20',daily:30,audience:'Ukrainian/Belarusian diaspora in Poland',landing:'https://wincase.eu/',utm:'youtube_preroll',notes:'Brand awareness video campaign'},
        {id:'c9',name:'FB Citizenship Campaign',platform:'facebook',objective:'conversions',budget:1500,spent:0,leads:0,clicks:0,impressions:0,reach:0,ctr:0,status:'draft',start:'2026-04-01',end:'2026-06-30',daily:50,audience:'Long-term residents in Poland, 5+ years',landing:'https://wincase.eu/citizenship',utm:'fb_citizenship',notes:'Planned for April launch'},
        {id:'c10',name:'Google Perm Residence',platform:'google',objective:'lead_gen',budget:1800,spent:1650,leads:14,clicks:720,impressions:11500,reach:8900,ctr:4.8,status:'active',start:'2026-01-10',end:'2026-03-31',daily:65,audience:'People seeking permanent residence in Poland',landing:'https://wincase.eu/permanent-residence',utm:'google_perm_res',notes:'High conversion rate campaign'},
        {id:'c11',name:'TikTok Success Stories',platform:'tiktok',objective:'brand',budget:600,spent:600,leads:7,clicks:1890,impressions:67000,reach:38000,ctr:7.2,status:'completed',start:'2026-01-15',end:'2026-02-15',daily:20,audience:'Potential immigrants, all ages',landing:'https://wincase.eu/',utm:'tiktok_success',notes:'Client testimonials - completed successfully'},
        {id:'c12',name:'FB Family Reunification',platform:'facebook',objective:'lead_gen',budget:900,spent:420,leads:6,clicks:340,impressions:8900,reach:5600,ctr:3.2,status:'paused',start:'2026-02-10',end:'2026-04-10',daily:30,audience:'Families seeking reunification in Poland',landing:'https://wincase.eu/family',utm:'fb_family',notes:'Paused - budget reallocation'}
    ];

    let currentFilter = 'all';
    let currentIdx = -1;
    function fmt(n){return n.toLocaleString('pl-PL',{minimumFractionDigits:0,maximumFractionDigits:0});}
    function fmt2(n){return n.toLocaleString('pl-PL',{minimumFractionDigits:2,maximumFractionDigits:2});}

    function renderTable(){
        const search = (document.getElementById('campSearch').value||'').toLowerCase();
        const platF = document.getElementById('campPlatformFilter').value;
        const objF = document.getElementById('campObjFilter').value;
        const tbody = document.getElementById('campTableBody');
        tbody.innerHTML = '';
        let count = 0;

        campaigns.forEach((c,idx) => {
            if(currentFilter!=='all' && c.status!==currentFilter) return;
            if(search && !c.name.toLowerCase().includes(search)) return;
            if(platF!=='all' && c.platform!==platF) return;
            if(objF!=='all' && c.objective!==objF) return;
            count++;
            const cpl = c.leads>0 ? fmt2(c.spent/c.leads) : '—';
            const pct = c.budget>0 ? Math.round(c.spent/c.budget*100) : 0;
            const barColor = pct>=90?'bg-danger':pct>=70?'bg-warning':'bg-success';

            let actions = '';
            if(c.status==='active'){
                actions = `<div class="d-flex gap-1 justify-content-center">
                    <button class="btn btn-sm btn-subtle-primary act-view" title="View"><i class="ri-eye-line"></i></button>
                    <button class="btn btn-sm btn-subtle-warning act-pause" title="Pause"><i class="ri-pause-line"></i></button>
                    <button class="btn btn-sm btn-subtle-info act-edit" title="Edit"><i class="ri-pencil-line"></i></button>
                    <button class="btn btn-sm btn-subtle-success act-stats" title="Stats"><i class="ri-bar-chart-line"></i></button>
                </div>`;
            } else if(c.status==='paused'){
                actions = `<div class="d-flex gap-1 justify-content-center">
                    <button class="btn btn-sm btn-subtle-primary act-view" title="View"><i class="ri-eye-line"></i></button>
                    <button class="btn btn-sm btn-subtle-success act-resume" title="Resume"><i class="ri-play-line"></i></button>
                    <button class="btn btn-sm btn-subtle-info act-edit" title="Edit"><i class="ri-pencil-line"></i></button>
                    <button class="btn btn-sm btn-subtle-success act-stats" title="Stats"><i class="ri-bar-chart-line"></i></button>
                </div>`;
            } else if(c.status==='draft'){
                actions = `<div class="d-flex gap-1 justify-content-center">
                    <button class="btn btn-sm btn-subtle-primary act-view" title="View"><i class="ri-eye-line"></i></button>
                    <button class="btn btn-sm btn-subtle-success act-launch" title="Launch"><i class="ri-rocket-line"></i></button>
                    <button class="btn btn-sm btn-subtle-info act-edit" title="Edit"><i class="ri-pencil-line"></i></button>
                    <button class="btn btn-sm btn-subtle-danger act-delete" title="Delete"><i class="ri-delete-bin-line"></i></button>
                </div>`;
            } else {
                actions = `<div class="d-flex gap-1 justify-content-center">
                    <button class="btn btn-sm btn-subtle-primary act-view" title="View"><i class="ri-eye-line"></i></button>
                    <button class="btn btn-sm btn-subtle-info act-edit" title="Edit"><i class="ri-pencil-line"></i></button>
                    <button class="btn btn-sm btn-subtle-success act-stats" title="Stats"><i class="ri-bar-chart-line"></i></button>
                </div>`;
            }

            tbody.innerHTML += `<tr data-idx="${idx}">
                <td class="fw-medium">${c.name}</td>
                <td>${PLATFORM_BADGES[c.platform]||c.platform}</td>
                <td><span class="fs-12">${OBJ_LABELS[c.objective]||c.objective}</span></td>
                <td class="text-end fw-semibold">${fmt(c.budget)} PLN</td>
                <td>
                    <div class="fw-semibold">${fmt(c.spent)} PLN</div>
                    <div class="budget-bar"><div class="fill ${barColor}" style="width:${pct}%"></div></div>
                    <div class="fs-10 text-muted">${pct}% used</div>
                </td>
                <td class="text-center fw-semibold">${c.leads}</td>
                <td class="text-end">${cpl} PLN</td>
                <td class="text-center">${c.ctr>0?c.ctr.toFixed(1)+'%':'—'}</td>
                <td>${STATUS_BADGES[c.status]}</td>
                <td class="fs-11 text-muted">${c.start}<br>${c.end}</td>
                <td>${actions}</td>
            </tr>`;
        });
        document.getElementById('campShowing').textContent = `Showing ${count} campaigns`;
    }

    function updateCounts(){
        let all=0,act=0,pau=0,comp=0,dr=0;
        campaigns.forEach(c=>{all++;if(c.status==='active')act++;if(c.status==='paused')pau++;if(c.status==='completed')comp++;if(c.status==='draft')dr++;});
        document.getElementById('cCntAll').textContent=all;document.getElementById('cCntActive').textContent=act;
        document.getElementById('cCntPaused').textContent=pau;document.getElementById('cCntCompleted').textContent=comp;
        document.getElementById('cCntDraft').textContent=dr;
    }

    renderTable(); updateCounts();

    // ============ FILTERS ============
    document.getElementById('campFilterPills').addEventListener('click',function(e){
        const p=e.target.closest('.camp-filter-pill'); if(!p)return;
        document.querySelectorAll('.camp-filter-pill').forEach(x=>x.classList.remove('active'));
        p.classList.add('active');currentFilter=p.dataset.filter;renderTable();
    });
    document.getElementById('campSearch').addEventListener('input',()=>renderTable());
    document.getElementById('campPlatformFilter').addEventListener('change',()=>renderTable());
    document.getElementById('campObjFilter').addEventListener('change',()=>renderTable());

    // ============ TABLE ACTIONS ============
    document.getElementById('campTableBody').addEventListener('click',function(e){
        const btn=e.target.closest('button');
        if(!btn) return; e.preventDefault();
        const row=btn.closest('tr');
        const idx=parseInt(row.dataset.idx);
        const c=campaigns[idx]; if(!c) return;
        currentIdx=idx;

        if(btn.classList.contains('act-view')){ showView(c); }
        if(btn.classList.contains('act-edit')){ showEdit(c,idx); }
        if(btn.classList.contains('act-stats')){ showStats(c); }
        if(btn.classList.contains('act-pause')){c.status='paused';renderTable();updateCounts();showToast(c.name+' paused','warning');}
        if(btn.classList.contains('act-resume')){c.status='active';renderTable();updateCounts();showToast(c.name+' resumed','success');}
        if(btn.classList.contains('act-launch')){c.status='active';renderTable();updateCounts();showToast(c.name+' launched!','success');}
        if(btn.classList.contains('act-delete')){
            row.style.transition='opacity .3s';row.style.opacity='0';
            setTimeout(()=>{campaigns.splice(idx,1);renderTable();updateCounts();showToast(c.name+' deleted','danger');},300);
        }
    });

    // ============ VIEW ============
    function showView(c){
        document.getElementById('vcName').textContent=c.name;
        document.getElementById('vcPlatform').innerHTML=PLATFORM_BADGES[c.platform];
        document.getElementById('vcObjective').textContent=OBJ_LABELS[c.objective];
        document.getElementById('vcStatus').innerHTML=STATUS_BADGES[c.status];
        document.getElementById('vcPeriod').textContent=c.start+' — '+c.end;
        document.getElementById('vcAudience').textContent=c.audience;
        document.getElementById('vcLanding').innerHTML=c.landing?`<a href="${c.landing}" class="text-primary fs-12" target="_blank">${c.landing}</a>`:'—';
        document.getElementById('vcUtm').textContent=c.utm?`?utm_source=${c.platform}&utm_medium=cpc&utm_campaign=${c.utm}`:'—';
        document.getElementById('vcBudget').textContent=fmt(c.budget)+' PLN';
        document.getElementById('vcSpent').textContent=fmt(c.spent)+' PLN';
        document.getElementById('vcLeads').textContent=c.leads;
        document.getElementById('vcCPL').textContent=c.leads>0?fmt2(c.spent/c.leads)+' PLN':'—';
        document.getElementById('vcClicks').textContent=fmt(c.clicks);
        document.getElementById('vcCTR').textContent=c.ctr>0?c.ctr.toFixed(1)+'%':'—';
        const pct=c.budget>0?Math.round(c.spent/c.budget*100):0;
        const bar=document.getElementById('vcBudgetBar');
        bar.style.width=pct+'%';
        bar.className='fill '+(pct>=90?'bg-danger':pct>=70?'bg-warning':'bg-success');
        document.getElementById('vcBudgetPct').textContent=pct+'% used';
        document.getElementById('vcBudgetRemaining').textContent=fmt(c.budget-c.spent)+' PLN remaining';
        new bootstrap.Modal(document.getElementById('viewCampModal')).show();
    }

    // View → Edit
    document.getElementById('vcEditBtn').addEventListener('click',function(){
        const c=campaigns[currentIdx]; if(!c)return;
        bootstrap.Modal.getInstance(document.getElementById('viewCampModal')).hide();
        setTimeout(()=>showEdit(c,currentIdx),300);
    });

    // ============ EDIT ============
    function showEdit(c,idx){
        currentIdx=idx;
        document.getElementById('ecName').value=c.name;
        setSelect('ecPlatform',c.platform);
        setSelect('ecObjective',c.objective);
        document.getElementById('ecBudget').value=c.budget;
        setSelect('ecStatus',c.status);
        document.getElementById('ecStart').value=c.start;
        document.getElementById('ecEnd').value=c.end;
        document.getElementById('ecDaily').value=c.daily;
        document.getElementById('ecAudience').value=c.audience;
        document.getElementById('ecLanding').value=c.landing;
        document.getElementById('ecUtm').value=c.utm;
        document.getElementById('ecNotes').value=c.notes;
        new bootstrap.Modal(document.getElementById('editCampModal')).show();
    }

    document.getElementById('saveEditCampBtn').addEventListener('click',function(){
        const c=campaigns[currentIdx]; if(!c)return;
        c.name=document.getElementById('ecName').value;
        c.platform=document.getElementById('ecPlatform').value;
        c.objective=document.getElementById('ecObjective').value;
        c.budget=parseFloat(document.getElementById('ecBudget').value)||0;
        c.status=document.getElementById('ecStatus').value;
        c.start=document.getElementById('ecStart').value;
        c.end=document.getElementById('ecEnd').value;
        c.daily=parseFloat(document.getElementById('ecDaily').value)||0;
        c.audience=document.getElementById('ecAudience').value;
        c.landing=document.getElementById('ecLanding').value;
        c.utm=document.getElementById('ecUtm').value;
        c.notes=document.getElementById('ecNotes').value;
        renderTable();updateCounts();
        bootstrap.Modal.getInstance(document.getElementById('editCampModal')).hide();
        showToast(c.name+' updated','success');
    });

    // ============ STATS ============
    function showStats(c){
        document.getElementById('scName').textContent=c.name;
        document.getElementById('scImpressions').textContent=fmt(c.impressions);
        document.getElementById('scReach').textContent=fmt(c.reach);
        document.getElementById('scClicks').textContent=fmt(c.clicks);
        document.getElementById('scCTR').textContent=c.ctr>0?c.ctr.toFixed(1)+'%':'—';
        document.getElementById('scLeads').textContent=c.leads;
        const convRate=c.clicks>0?(c.leads/c.clicks*100).toFixed(2)+'%':'—';
        document.getElementById('scConvRate').textContent=convRate;
        const cpc=c.clicks>0?fmt2(c.spent/c.clicks)+' PLN':'—';
        document.getElementById('scCPC').textContent=cpc;
        document.getElementById('scCPL').textContent=c.leads>0?fmt2(c.spent/c.leads)+' PLN':'—';
        const cpm=c.impressions>0?fmt2(c.spent/c.impressions*1000)+' PLN':'—';
        document.getElementById('scCPM').textContent=cpm;
        const avgClientValue=4500;
        const roas=c.spent>0?((c.leads*avgClientValue*0.3)/c.spent).toFixed(1)+'x':'—';
        document.getElementById('scROAS').textContent=roas;

        // Leads breakdown
        const tbl=document.getElementById('scLeadsTable');
        tbl.innerHTML='';
        const sources=[{s:'Form Submission',l:Math.ceil(c.leads*0.5),q:'High',cv:Math.ceil(c.leads*0.2)},{s:'Phone Call',l:Math.ceil(c.leads*0.25),q:'Medium',cv:Math.ceil(c.leads*0.1)},{s:'Messenger',l:Math.floor(c.leads*0.15),q:'Medium',cv:Math.floor(c.leads*0.05)},{s:'WhatsApp',l:Math.floor(c.leads*0.1),q:'Low',cv:Math.floor(c.leads*0.02)}];
        sources.forEach(s=>{
            const qBadge=s.q==='High'?'bg-success-subtle text-success':s.q==='Medium'?'bg-warning-subtle text-warning':'bg-danger-subtle text-danger';
            tbl.innerHTML+=`<tr><td>${s.s}</td><td>${s.l}</td><td><span class="badge ${qBadge}">${s.q}</span></td><td>${s.cv}</td></tr>`;
        });
        new bootstrap.Modal(document.getElementById('statsCampModal')).show();
    }

    // ============ NEW CAMPAIGN ============
    document.getElementById('saveNewCampBtn').addEventListener('click',function(){
        const name=document.getElementById('ncName').value;
        const budget=parseFloat(document.getElementById('ncBudget').value)||0;
        if(!name||budget<=0){showToast('Fill name and budget','warning');return;}
        campaigns.unshift({
            id:'c'+(campaigns.length+1),name:name,
            platform:document.getElementById('ncPlatform').value,
            objective:document.getElementById('ncObjective').value,
            budget:budget,spent:0,leads:0,clicks:0,impressions:0,reach:0,ctr:0,
            status:document.getElementById('ncLaunchAs').value,
            start:document.getElementById('ncStart').value||new Date().toISOString().slice(0,10),
            end:document.getElementById('ncEnd').value||'',
            daily:parseFloat(document.getElementById('ncDaily').value)||0,
            audience:document.getElementById('ncAudience').value,
            landing:document.getElementById('ncLanding').value,
            utm:document.getElementById('ncUtm').value,
            notes:document.getElementById('ncNotes').value
        });
        renderTable();updateCounts();
        bootstrap.Modal.getInstance(document.getElementById('newCampaignModal')).hide();
        showToast('Campaign "'+name+'" created!','success');
    });

    // ============ EXPORT CSV ============
    document.getElementById('exportCampCsvBtn').addEventListener('click',function(){
        let csv='Name,Platform,Objective,Budget,Spent,Leads,CPL,CTR,Status,Start,End\n';
        campaigns.forEach(c=>{
            const cpl=c.leads>0?(c.spent/c.leads).toFixed(2):'0';
            csv+=`"${c.name}",${PLATFORM_LABELS[c.platform]},${OBJ_LABELS[c.objective]},${c.budget},${c.spent},${c.leads},${cpl},${c.ctr},${c.status},${c.start},${c.end}\n`;
        });
        const blob=new Blob([csv],{type:'text/csv'});const url=URL.createObjectURL(blob);
        const a=document.createElement('a');a.href=url;a.download='campaigns_'+new Date().toISOString().slice(0,10)+'.csv';a.click();URL.revokeObjectURL(url);
        showToast('CSV exported','success');
    });

    // ============ HELPERS ============
    function setSelect(id,val){const el=document.getElementById(id);for(let i=0;i<el.options.length;i++){if(el.options[i].value===val){el.selectedIndex=i;return;}}}

    function showToast(msg,type){
        const colors={success:'#198754',danger:'#dc3545',warning:'#ffc107',info:'#0dcaf0',primary:'#845adf'};
        const t=document.createElement('div');
        t.style.cssText='position:fixed;top:20px;right:20px;z-index:9999;padding:14px 24px;border-radius:10px;color:#fff;font-weight:600;font-size:.9rem;box-shadow:0 4px 12px rgba(0,0,0,.15);transition:opacity .3s;background:'+(colors[type]||colors.info);
        t.textContent=msg;document.body.appendChild(t);
        setTimeout(()=>{t.style.opacity='0';setTimeout(()=>t.remove(),300);},3000);
    }
});
</script>
@endsection

@section('js')
<script src="{{ asset('assets/libs/apexcharts/apexcharts.min.js') }}"></script>
<script>
// Ad Spend vs Leads — Dual-axis Area Chart
new ApexCharts(document.querySelector("#adsSpendLeadsChart"), {
    chart:{type:'area',height:350,toolbar:{show:false},zoom:{enabled:false}},
    series:[{name:'Spend (PLN)',type:'area',data:[5200,6100,7300,6800,7900,8450]},{name:'Leads',type:'column',data:[52,61,74,68,78,89]}],
    xaxis:{categories:['Oct 2025','Nov 2025','Dec 2025','Jan 2026','Feb 2026','Mar 2026']},
    yaxis:[{title:{text:'Spend (PLN)'},labels:{formatter:function(v){return v.toLocaleString('pl-PL')+' PLN';}}},{opposite:true,title:{text:'Leads'},labels:{formatter:function(v){return Math.round(v);}}}],
    colors:['#3b82f6','#10b981'],
    fill:{type:['gradient','solid'],gradient:{shadeIntensity:1,opacityFrom:0.3,opacityTo:0.05,stops:[0,90,100]}},
    stroke:{curve:'smooth',width:[2,0]},
    plotOptions:{bar:{columnWidth:'35%',borderRadius:4}},
    dataLabels:{enabled:false},grid:{borderColor:'#f1f1f1'},legend:{position:'top'},tooltip:{shared:true,intersect:false}
}).render();

// Spend by Platform — Donut Chart
new ApexCharts(document.querySelector("#adsPlatformDonut"), {
    chart:{type:'donut',height:300},
    series:[38,30,14,8,6,4],
    labels:['Facebook','Google','TikTok','Instagram','LinkedIn','YouTube'],
    colors:['#3b82f6','#10b981','#111827','#e11d48','#06b6d4','#dc2626'],
    plotOptions:{pie:{donut:{size:'65%',labels:{show:true,total:{show:true,label:'Total Spend',formatter:function(){return '8 450 PLN';}}}}}},
    legend:{position:'bottom'},
    dataLabels:{enabled:true,formatter:function(v){return Math.round(v)+'%';}}
}).render();

// Daily Performance — Line Chart
new ApexCharts(document.querySelector("#adsDailyChart"), {
    chart:{type:'line',height:280,toolbar:{show:false},zoom:{enabled:false}},
    series:[{name:'Spend (PLN)',data:[280,310,295,340,320,360,290,350,380,310,340,370,390,415]},{name:'Leads',data:[3,4,3,5,4,6,3,5,6,4,5,6,7,8]}],
    xaxis:{categories:['17 Feb','18','19','20','21','22','23','24','25','26','27','28','1 Mar','2 Mar']},
    yaxis:[{title:{text:'PLN'},labels:{formatter:function(v){return Math.round(v);}}},{opposite:true,title:{text:'Leads'},labels:{formatter:function(v){return Math.round(v);}}}],
    colors:['#f59e0b','#3b82f6'],stroke:{curve:'smooth',width:2},
    grid:{borderColor:'#f1f1f1'},legend:{position:'top'},tooltip:{shared:true,intersect:false}
}).render();
</script>
@endsection
