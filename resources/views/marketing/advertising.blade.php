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
                    <h4 class="mb-0 fw-semibold" id="statActive">--</h4>
                    <span class="stat-card-trend text-muted" id="statActiveTrend">Loading...</span>
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
                    <h4 class="mb-0 fw-semibold" id="statSpend">--</h4>
                    <span class="stat-card-trend text-muted" id="statSpendTrend">Loading...</span>
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
                    <h4 class="mb-0 fw-semibold" id="statLeads">--</h4>
                    <span class="stat-card-trend text-muted" id="statLeadsTrend">Loading...</span>
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
                    <h4 class="mb-0 fw-semibold" id="statCPL">--</h4>
                    <span class="stat-card-trend text-muted" id="statCPLTrend">Loading...</span>
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
                <span class="badge bg-success-subtle text-success" id="chartLeadsBadge"><i class="ri-arrow-up-line me-1"></i>Loading...</span>
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
                <button class="btn btn-sm btn-outline-secondary" id="syncAdsBtn"><i class="ri-refresh-line me-1"></i>Sync</button>
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
                        <tbody id="topAdsTableBody">
                            <tr><td colspan="6" class="text-center text-muted py-3"><div class="spinner-border spinner-border-sm text-primary"></div> Loading...</td></tr>
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

@endsection

@section('js')
<script src="{{ asset('assets/libs/apexcharts/apexcharts.min.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function(){

    /* ============================
     *  CONSTANTS & STATE
     * ============================ */
    const TOKEN = localStorage.getItem('wc_token');
    const API   = '/api/v1';
    const HEADERS = { 'Authorization': 'Bearer ' + TOKEN, 'Accept': 'application/json' };

    const PLATFORM_BADGES = {
        facebook:'<span class="badge bg-primary-subtle text-primary"><i class="ri-facebook-fill me-1"></i>Facebook</span>',
        google:'<span class="badge bg-success-subtle text-success"><i class="ri-google-fill me-1"></i>Google Ads</span>',
        google_ads:'<span class="badge bg-success-subtle text-success"><i class="ri-google-fill me-1"></i>Google Ads</span>',
        meta_ads:'<span class="badge bg-primary-subtle text-primary"><i class="ri-facebook-fill me-1"></i>Meta Ads</span>',
        tiktok:'<span class="badge bg-dark"><i class="ri-tiktok-fill me-1"></i>TikTok</span>',
        tiktok_ads:'<span class="badge bg-dark"><i class="ri-tiktok-fill me-1"></i>TikTok</span>',
        instagram:'<span class="badge bg-gradient-secondary text-white"><i class="ri-instagram-fill me-1"></i>Instagram</span>',
        linkedin:'<span class="badge bg-info-subtle text-info"><i class="ri-linkedin-fill me-1"></i>LinkedIn</span>',
        youtube:'<span class="badge bg-danger-subtle text-danger"><i class="ri-youtube-fill me-1"></i>YouTube</span>',
        youtube_ads:'<span class="badge bg-danger-subtle text-danger"><i class="ri-youtube-fill me-1"></i>YouTube</span>',
        pinterest_ads:'<span class="badge bg-danger-subtle text-danger"><i class="ri-pinterest-fill me-1"></i>Pinterest</span>'
    };
    const PLATFORM_LABELS = {
        facebook:'Facebook', google:'Google Ads', google_ads:'Google Ads', meta_ads:'Meta Ads',
        tiktok:'TikTok', tiktok_ads:'TikTok', instagram:'Instagram', linkedin:'LinkedIn',
        youtube:'YouTube', youtube_ads:'YouTube', pinterest_ads:'Pinterest'
    };
    const OBJ_LABELS = {lead_gen:'Lead Generation',traffic:'Traffic',brand:'Brand Awareness',conversions:'Conversions',retargeting:'Retargeting'};
    const STATUS_BADGES = {
        active:'<span class="badge bg-success-subtle text-success">Active</span>',
        paused:'<span class="badge bg-warning-subtle text-warning">Paused</span>',
        completed:'<span class="badge bg-secondary-subtle text-secondary">Completed</span>',
        draft:'<span class="badge bg-info-subtle text-info">Draft</span>'
    };

    let campaigns   = [];
    let overviewData = null;
    let budgetData   = null;
    let currentFilter = 'all';
    let currentIdx    = -1;

    // Chart instances
    let spendLeadsChart = null;
    let platformDonut   = null;
    let dailyChart      = null;

    /* ============================
     *  HELPERS
     * ============================ */
    function n(v) { return v ? Number(v).toLocaleString('pl-PL') : '0'; }
    function fmt(v) { return v != null ? Number(v).toLocaleString('pl-PL', {minimumFractionDigits:0, maximumFractionDigits:0}) : '0'; }
    function fmt2(v) { return v != null ? Number(v).toLocaleString('pl-PL', {minimumFractionDigits:2, maximumFractionDigits:2}) : '0,00'; }

    function toast(msg, type = 'success') {
        const colors = {success:'#198754', danger:'#dc3545', warning:'#ffc107', info:'#0dcaf0', primary:'#845adf'};
        const t = document.createElement('div');
        t.style.cssText = 'position:fixed;top:20px;right:20px;z-index:9999;padding:14px 24px;border-radius:10px;color:#fff;font-weight:600;font-size:.9rem;box-shadow:0 4px 12px rgba(0,0,0,.15);transition:opacity .3s;background:' + (colors[type] || colors.info);
        t.textContent = msg;
        document.body.appendChild(t);
        setTimeout(() => { t.style.opacity = '0'; setTimeout(() => t.remove(), 300); }, 3000);
    }

    function setSelect(id, val) {
        const el = document.getElementById(id);
        for (let i = 0; i < el.options.length; i++) {
            if (el.options[i].value === val) { el.selectedIndex = i; return; }
        }
    }

    function emptyRow(cols, msg) {
        return '<tr><td colspan="' + cols + '" class="text-center text-muted py-4">' + msg + '</td></tr>';
    }

    async function apiFetch(url) {
        try {
            const r = await fetch(API + url, { headers: HEADERS });
            if (r.status === 401) { window.location = '/login'; return null; }
            if (!r.ok) return null;
            const j = await r.json();
            return j.success !== false ? (j.data !== undefined ? j.data : j) : null;
        } catch (e) {
            console.warn('API fetch error:', url, e);
            return null;
        }
    }

    async function apiPostFormData(url) {
        try {
            const fd = new FormData();
            const r = await fetch(API + url, {
                method: 'POST',
                headers: { 'Authorization': 'Bearer ' + TOKEN, 'Accept': 'application/json' },
                body: fd
            });
            if (r.status === 401) { window.location = '/login'; return null; }
            const j = await r.json();
            return j;
        } catch (e) {
            console.warn('API POST error:', url, e);
            return null;
        }
    }

    /* ============================
     *  LOAD OVERVIEW (stat cards + platforms + charts)
     * ============================ */
    async function loadOverview() {
        try {
            const data = await apiFetch('/ads/overview');
            if (!data) {
                document.getElementById('statActive').textContent = '0';
                document.getElementById('statActiveTrend').innerHTML = '<span class="text-muted">No data</span>';
                document.getElementById('statSpend').textContent = '0 PLN';
                document.getElementById('statSpendTrend').innerHTML = '<span class="text-muted">No data</span>';
                document.getElementById('statLeads').textContent = '0';
                document.getElementById('statLeadsTrend').innerHTML = '<span class="text-muted">No data</span>';
                document.getElementById('statCPL').textContent = '-- PLN';
                document.getElementById('statCPLTrend').innerHTML = '<span class="text-muted">No data</span>';
                renderEmptyCharts();
                return;
            }
            overviewData = data;
            const totals = data.totals || {};
            const platforms = data.platforms || [];

            // Stat cards
            const activePlatforms = platforms.filter(p => p.total_impressions > 0).length;
            document.getElementById('statActive').textContent = activePlatforms || platforms.length;
            document.getElementById('statActiveTrend').innerHTML = '<span class="text-success"><i class="ri-bar-chart-line"></i> ' + platforms.length + ' platforms</span>';

            const totalCost = totals.cost || 0;
            document.getElementById('statSpend').textContent = fmt(totalCost) + ' PLN';
            document.getElementById('statSpendTrend').innerHTML = data.period ? '<span class="text-info">' + data.period + '</span>' : '';

            const totalLeads = totals.leads || 0;
            document.getElementById('statLeads').textContent = fmt(totalLeads);
            const ctrVal = totals.ctr || 0;
            document.getElementById('statLeadsTrend').innerHTML = '<span class="text-success">CTR: ' + (typeof ctrVal === 'number' ? ctrVal.toFixed(1) : ctrVal) + '%</span>';

            const cplVal = totals.cpl || 0;
            document.getElementById('statCPL').textContent = fmt2(cplVal) + ' PLN';
            const cpcVal = totals.cpc || 0;
            document.getElementById('statCPLTrend').innerHTML = '<span class="text-info">CPC: ' + fmt2(cpcVal) + ' PLN</span>';

            document.getElementById('chartLeadsBadge').innerHTML = '<i class="ri-arrow-up-line me-1"></i>' + fmt(totalLeads) + ' leads';

            // Build platform donut
            renderPlatformDonut(platforms, totalCost);

            // Build top ads from platform data
            renderTopAdsFromOverview(platforms);

        } catch (e) {
            console.error('loadOverview error:', e);
            toast('Failed to load advertising overview', 'danger');
        }
    }

    /* ============================
     *  LOAD BUDGET
     * ============================ */
    async function loadBudget() {
        try {
            const data = await apiFetch('/ads/budget');
            if (!data) return;
            budgetData = data;
            // Budget info can be used to enhance stat cards
            if (data.total_budget) {
                const pctUsed = data.pct_used || 0;
                document.getElementById('statSpendTrend').innerHTML =
                    '<span class="' + (pctUsed > 90 ? 'text-danger' : pctUsed > 70 ? 'text-warning' : 'text-success') + '">' +
                    fmt(pctUsed) + '% of budget used</span>';
            }
        } catch (e) {
            console.error('loadBudget error:', e);
        }
    }

    /* ============================
     *  LOAD ALL PLATFORM CAMPAIGNS
     * ============================ */
    async function loadAllCampaigns() {
        const tbody = document.getElementById('campTableBody');
        tbody.innerHTML = emptyRow(11, '<div class="spinner-border spinner-border-sm text-primary"></div> Loading campaigns...');

        const platformSlugs = ['google_ads', 'meta_ads', 'tiktok_ads', 'pinterest_ads', 'youtube_ads'];
        campaigns = [];

        const promises = platformSlugs.map(slug => apiFetch('/ads/' + slug + '/campaigns'));
        try {
            const results = await Promise.allSettled(promises);
            results.forEach((result, i) => {
                if (result.status === 'fulfilled' && result.value && Array.isArray(result.value)) {
                    result.value.forEach(c => {
                        c._platform_slug = platformSlugs[i];
                        campaigns.push(c);
                    });
                }
            });
        } catch (e) {
            console.error('loadAllCampaigns error:', e);
        }

        if (campaigns.length === 0) {
            tbody.innerHTML = emptyRow(11, 'No campaigns found. Connect ad platforms to see campaign data.');
        } else {
            renderTable();
        }
        updateCounts();
    }

    /* ============================
     *  LOAD PLATFORM DETAIL (for daily chart)
     * ============================ */
    async function loadPlatformDaily(platform) {
        try {
            const data = await apiFetch('/ads/' + platform);
            if (!data || !data.daily || !data.daily.length) {
                renderDailyChart([], []);
                return;
            }
            const dates = data.daily.map(d => d.date || d.day || '');
            const spendSeries = data.daily.map(d => d.cost || d.spend || 0);
            const leadsSeries = data.daily.map(d => d.leads || d.conversions || 0);
            renderDailyChart(dates, spendSeries, leadsSeries);
        } catch (e) {
            console.error('loadPlatformDaily error:', e);
        }
    }

    async function loadDailyFromAllPlatforms() {
        // Try loading daily data from first platform with data, or aggregate
        const platforms = ['google_ads', 'meta_ads', 'tiktok_ads'];
        for (const p of platforms) {
            const data = await apiFetch('/ads/' + p);
            if (data && data.daily && data.daily.length) {
                const dates = data.daily.map(d => d.date || d.day || '');
                const spendSeries = data.daily.map(d => d.cost || d.spend || 0);
                const leadsSeries = data.daily.map(d => d.leads || d.conversions || 0);
                renderDailyChart(dates, spendSeries, leadsSeries);
                return;
            }
        }
        // No daily data available
        renderDailyChart([], [], []);
    }

    /* ============================
     *  RENDER TABLE
     * ============================ */
    function renderTable() {
        const search = (document.getElementById('campSearch').value || '').toLowerCase();
        const platF = document.getElementById('campPlatformFilter').value;
        const objF = document.getElementById('campObjFilter').value;
        const tbody = document.getElementById('campTableBody');
        tbody.innerHTML = '';
        let count = 0;

        campaigns.forEach((c, idx) => {
            const status = (c.status || 'active').toLowerCase();
            const platform = c.platform || c._platform_slug || '';
            const objective = c.objective || '';
            const name = c.name || c.campaign_name || 'Unnamed';
            const budget = Number(c.budget || c.budget_total || 0);
            const spent = Number(c.spent || c.cost || c.total_cost || 0);
            const leads = Number(c.leads || c.total_leads || c.conversions || 0);
            const clicks = Number(c.clicks || c.total_clicks || 0);
            const impressions = Number(c.impressions || c.total_impressions || 0);
            const ctr = Number(c.ctr || (impressions > 0 ? (clicks / impressions * 100) : 0));
            const startDate = c.start || c.start_date || '';
            const endDate = c.end || c.end_date || '';

            if (currentFilter !== 'all' && status !== currentFilter) return;
            if (search && !name.toLowerCase().includes(search)) return;
            // Map platform filter values to match API platform slugs
            if (platF !== 'all') {
                const platMap = {facebook:'meta_ads', google:'google_ads', tiktok:'tiktok_ads', youtube:'youtube_ads'};
                const mappedFilter = platMap[platF] || platF;
                if (platform !== platF && platform !== mappedFilter) return;
            }
            if (objF !== 'all' && objective !== objF) return;

            count++;
            const cpl = leads > 0 ? fmt2(spent / leads) : '--';
            const pct = budget > 0 ? Math.round(spent / budget * 100) : 0;
            const barColor = pct >= 90 ? 'bg-danger' : pct >= 70 ? 'bg-warning' : 'bg-success';

            let actions = '';
            if (status === 'active') {
                actions = '<div class="d-flex gap-1 justify-content-center">' +
                    '<button class="btn btn-sm btn-subtle-primary act-view" title="View"><i class="ri-eye-line"></i></button>' +
                    '<button class="btn btn-sm btn-subtle-warning act-pause" title="Pause"><i class="ri-pause-line"></i></button>' +
                    '<button class="btn btn-sm btn-subtle-info act-edit" title="Edit"><i class="ri-pencil-line"></i></button>' +
                    '<button class="btn btn-sm btn-subtle-success act-stats" title="Stats"><i class="ri-bar-chart-line"></i></button></div>';
            } else if (status === 'paused') {
                actions = '<div class="d-flex gap-1 justify-content-center">' +
                    '<button class="btn btn-sm btn-subtle-primary act-view" title="View"><i class="ri-eye-line"></i></button>' +
                    '<button class="btn btn-sm btn-subtle-success act-resume" title="Resume"><i class="ri-play-line"></i></button>' +
                    '<button class="btn btn-sm btn-subtle-info act-edit" title="Edit"><i class="ri-pencil-line"></i></button>' +
                    '<button class="btn btn-sm btn-subtle-success act-stats" title="Stats"><i class="ri-bar-chart-line"></i></button></div>';
            } else if (status === 'draft') {
                actions = '<div class="d-flex gap-1 justify-content-center">' +
                    '<button class="btn btn-sm btn-subtle-primary act-view" title="View"><i class="ri-eye-line"></i></button>' +
                    '<button class="btn btn-sm btn-subtle-success act-launch" title="Launch"><i class="ri-rocket-line"></i></button>' +
                    '<button class="btn btn-sm btn-subtle-info act-edit" title="Edit"><i class="ri-pencil-line"></i></button>' +
                    '<button class="btn btn-sm btn-subtle-danger act-delete" title="Delete"><i class="ri-delete-bin-line"></i></button></div>';
            } else {
                actions = '<div class="d-flex gap-1 justify-content-center">' +
                    '<button class="btn btn-sm btn-subtle-primary act-view" title="View"><i class="ri-eye-line"></i></button>' +
                    '<button class="btn btn-sm btn-subtle-info act-edit" title="Edit"><i class="ri-pencil-line"></i></button>' +
                    '<button class="btn btn-sm btn-subtle-success act-stats" title="Stats"><i class="ri-bar-chart-line"></i></button></div>';
            }

            tbody.innerHTML += '<tr data-idx="' + idx + '">' +
                '<td class="fw-medium">' + name + '</td>' +
                '<td>' + (PLATFORM_BADGES[platform] || platform) + '</td>' +
                '<td><span class="fs-12">' + (OBJ_LABELS[objective] || objective || '--') + '</span></td>' +
                '<td class="text-end fw-semibold">' + fmt(budget) + ' PLN</td>' +
                '<td><div class="fw-semibold">' + fmt(spent) + ' PLN</div>' +
                    '<div class="budget-bar"><div class="fill ' + barColor + '" style="width:' + pct + '%"></div></div>' +
                    '<div class="fs-10 text-muted">' + pct + '% used</div></td>' +
                '<td class="text-center fw-semibold">' + leads + '</td>' +
                '<td class="text-end">' + cpl + ' PLN</td>' +
                '<td class="text-center">' + (ctr > 0 ? ctr.toFixed(1) + '%' : '--') + '</td>' +
                '<td>' + (STATUS_BADGES[status] || status) + '</td>' +
                '<td class="fs-11 text-muted">' + startDate + (endDate ? '<br>' + endDate : '') + '</td>' +
                '<td>' + actions + '</td></tr>';
        });

        if (count === 0 && campaigns.length > 0) {
            tbody.innerHTML = emptyRow(11, 'No campaigns match current filters.');
        }
        document.getElementById('campShowing').textContent = 'Showing ' + count + ' campaigns';
    }

    function updateCounts() {
        let all = 0, act = 0, pau = 0, comp = 0, dr = 0;
        campaigns.forEach(c => {
            const s = (c.status || 'active').toLowerCase();
            all++;
            if (s === 'active') act++;
            if (s === 'paused') pau++;
            if (s === 'completed') comp++;
            if (s === 'draft') dr++;
        });
        document.getElementById('cCntAll').textContent = all;
        document.getElementById('cCntActive').textContent = act;
        document.getElementById('cCntPaused').textContent = pau;
        document.getElementById('cCntCompleted').textContent = comp;
        document.getElementById('cCntDraft').textContent = dr;
    }

    /* ============================
     *  CHARTS
     * ============================ */
    function renderPlatformDonut(platforms, totalCost) {
        const series = [];
        const labels = [];
        const colors = [];
        const colorMap = {
            google_ads:'#10b981', meta_ads:'#3b82f6', tiktok_ads:'#111827',
            pinterest_ads:'#e11d48', youtube_ads:'#dc2626',
            facebook:'#3b82f6', google:'#10b981', tiktok:'#111827',
            instagram:'#e11d48', linkedin:'#06b6d4', youtube:'#dc2626'
        };

        if (platforms.length === 0) {
            if (platformDonut) platformDonut.destroy();
            document.getElementById('adsPlatformDonut').innerHTML = '<div class="text-center text-muted py-5">No platform data available</div>';
            return;
        }

        platforms.forEach(p => {
            const cost = Number(p.total_cost || 0);
            if (cost > 0) {
                series.push(cost);
                labels.push(p.label || PLATFORM_LABELS[p.platform] || p.platform);
                colors.push(colorMap[p.platform] || '#6c757d');
            }
        });

        if (series.length === 0) {
            document.getElementById('adsPlatformDonut').innerHTML = '<div class="text-center text-muted py-5">No spend data</div>';
            return;
        }

        const opts = {
            chart: { type: 'donut', height: 300 },
            series: series,
            labels: labels,
            colors: colors,
            plotOptions: { pie: { donut: { size: '65%', labels: { show: true, total: { show: true, label: 'Total Spend', formatter: function() { return fmt(totalCost) + ' PLN'; } } } } } },
            legend: { position: 'bottom' },
            dataLabels: { enabled: true, formatter: function(v) { return Math.round(v) + '%'; } }
        };

        if (platformDonut) platformDonut.destroy();
        platformDonut = new ApexCharts(document.querySelector('#adsPlatformDonut'), opts);
        platformDonut.render();
    }

    function renderSpendLeadsChart(dates, spendData, leadsData) {
        const opts = {
            chart: { type: 'area', height: 350, toolbar: { show: false }, zoom: { enabled: false } },
            series: [
                { name: 'Spend (PLN)', type: 'area', data: spendData },
                { name: 'Leads', type: 'column', data: leadsData }
            ],
            xaxis: { categories: dates },
            yaxis: [
                { title: { text: 'Spend (PLN)' }, labels: { formatter: function(v) { return fmt(v) + ' PLN'; } } },
                { opposite: true, title: { text: 'Leads' }, labels: { formatter: function(v) { return Math.round(v); } } }
            ],
            colors: ['#3b82f6', '#10b981'],
            fill: { type: ['gradient', 'solid'], gradient: { shadeIntensity: 1, opacityFrom: 0.3, opacityTo: 0.05, stops: [0, 90, 100] } },
            stroke: { curve: 'smooth', width: [2, 0] },
            plotOptions: { bar: { columnWidth: '35%', borderRadius: 4 } },
            dataLabels: { enabled: false },
            grid: { borderColor: '#f1f1f1' },
            legend: { position: 'top' },
            tooltip: { shared: true, intersect: false }
        };

        if (spendLeadsChart) spendLeadsChart.destroy();
        spendLeadsChart = new ApexCharts(document.querySelector('#adsSpendLeadsChart'), opts);
        spendLeadsChart.render();
    }

    function renderDailyChart(dates, spendData, leadsData) {
        if (!dates || dates.length === 0) {
            if (dailyChart) dailyChart.destroy();
            document.getElementById('adsDailyChart').innerHTML = '<div class="text-center text-muted py-5">No daily performance data available</div>';
            return;
        }

        const opts = {
            chart: { type: 'line', height: 280, toolbar: { show: false }, zoom: { enabled: false } },
            series: [
                { name: 'Spend (PLN)', data: spendData || [] },
                { name: 'Leads', data: leadsData || [] }
            ],
            xaxis: { categories: dates },
            yaxis: [
                { title: { text: 'PLN' }, labels: { formatter: function(v) { return Math.round(v); } } },
                { opposite: true, title: { text: 'Leads' }, labels: { formatter: function(v) { return Math.round(v); } } }
            ],
            colors: ['#f59e0b', '#3b82f6'],
            stroke: { curve: 'smooth', width: 2 },
            grid: { borderColor: '#f1f1f1' },
            legend: { position: 'top' },
            tooltip: { shared: true, intersect: false }
        };

        if (dailyChart) dailyChart.destroy();
        dailyChart = new ApexCharts(document.querySelector('#adsDailyChart'), opts);
        dailyChart.render();
    }

    function renderEmptyCharts() {
        renderSpendLeadsChart([], [], []);
        document.getElementById('adsPlatformDonut').innerHTML = '<div class="text-center text-muted py-5">No platform data available</div>';
        document.getElementById('adsDailyChart').innerHTML = '<div class="text-center text-muted py-5">No daily data available</div>';
    }

    function buildSpendLeadsFromOverview(platforms) {
        // Build a simple per-platform spend vs leads bar if no monthly historical data
        if (!platforms || platforms.length === 0) {
            renderSpendLeadsChart([], [], []);
            return;
        }
        const labels = platforms.map(p => p.label || PLATFORM_LABELS[p.platform] || p.platform);
        const spend = platforms.map(p => Number(p.total_cost || 0));
        const leads = platforms.map(p => Number(p.total_leads || 0));
        renderSpendLeadsChart(labels, spend, leads);
    }

    /* ============================
     *  TOP ADS TABLE (from overview platforms)
     * ============================ */
    function renderTopAdsFromOverview(platforms) {
        const tbody = document.getElementById('topAdsTableBody');
        if (!platforms || platforms.length === 0) {
            tbody.innerHTML = emptyRow(6, 'No ad performance data available.');
            return;
        }

        tbody.innerHTML = '';
        // Show each platform as a "top ad" entry sorted by ROAS desc
        const sorted = [...platforms].sort((a, b) => (Number(b.roas || 0)) - (Number(a.roas || 0)));
        sorted.forEach(p => {
            const clicks = Number(p.total_clicks || 0);
            const conversions = Number(p.total_conversions || p.total_leads || 0);
            const cost = Number(p.total_cost || 0);
            const ctr = p.total_impressions > 0 ? (clicks / Number(p.total_impressions) * 100).toFixed(1) : '0.0';
            const costPerConv = conversions > 0 ? fmt2(cost / conversions) : '--';
            const ctrClass = Number(ctr) >= 4 ? 'text-success' : Number(ctr) >= 2 ? 'text-warning' : 'text-muted';
            const badge = PLATFORM_BADGES[p.platform] || '<span class="badge bg-secondary">' + (p.label || p.platform) + '</span>';

            tbody.innerHTML += '<tr>' +
                '<td class="fw-medium">' + (p.label || PLATFORM_LABELS[p.platform] || p.platform) + '</td>' +
                '<td>' + badge + '</td>' +
                '<td class="text-center">' + fmt(clicks) + '</td>' +
                '<td class="text-center ' + ctrClass + ' fw-semibold">' + ctr + '%</td>' +
                '<td class="text-center">' + conversions + '</td>' +
                '<td class="text-end">' + costPerConv + ' PLN</td></tr>';
        });
    }

    /* ============================
     *  FILTERS
     * ============================ */
    document.getElementById('campFilterPills').addEventListener('click', function(e) {
        const p = e.target.closest('.camp-filter-pill');
        if (!p) return;
        document.querySelectorAll('.camp-filter-pill').forEach(x => x.classList.remove('active'));
        p.classList.add('active');
        currentFilter = p.dataset.filter;
        renderTable();
    });
    document.getElementById('campSearch').addEventListener('input', () => renderTable());
    document.getElementById('campPlatformFilter').addEventListener('change', () => renderTable());
    document.getElementById('campObjFilter').addEventListener('change', () => renderTable());

    /* ============================
     *  TABLE ACTIONS
     * ============================ */
    document.getElementById('campTableBody').addEventListener('click', function(e) {
        const btn = e.target.closest('button');
        if (!btn) return;
        e.preventDefault();
        const row = btn.closest('tr');
        const idx = parseInt(row.dataset.idx);
        const c = campaigns[idx];
        if (!c) return;
        currentIdx = idx;

        if (btn.classList.contains('act-view')) { showView(c); }
        if (btn.classList.contains('act-edit')) { showEdit(c, idx); }
        if (btn.classList.contains('act-stats')) { showStats(c); }
        if (btn.classList.contains('act-pause')) {
            toast('Campaign pause/resume is managed via the ad platform directly', 'info');
        }
        if (btn.classList.contains('act-resume')) {
            toast('Campaign pause/resume is managed via the ad platform directly', 'info');
        }
        if (btn.classList.contains('act-launch')) {
            toast('Campaign launch is managed via the ad platform directly', 'info');
        }
        if (btn.classList.contains('act-delete')) {
            toast('Campaign deletion is managed via the ad platform directly', 'info');
        }
    });

    /* ============================
     *  VIEW MODAL
     * ============================ */
    function showView(c) {
        const name = c.name || c.campaign_name || 'Unnamed';
        const platform = c.platform || c._platform_slug || '';
        const objective = c.objective || '';
        const status = (c.status || 'active').toLowerCase();
        const budget = Number(c.budget || c.budget_total || 0);
        const spent = Number(c.spent || c.cost || c.total_cost || 0);
        const leads = Number(c.leads || c.total_leads || c.conversions || 0);
        const clicks = Number(c.clicks || c.total_clicks || 0);
        const impressions = Number(c.impressions || c.total_impressions || 0);
        const ctr = Number(c.ctr || (impressions > 0 ? (clicks / impressions * 100) : 0));
        const startDate = c.start || c.start_date || '';
        const endDate = c.end || c.end_date || '';
        const audience = c.audience || c.target_audience || '';
        const landing = c.landing || c.landing_page || '';
        const utm = c.utm || c.utm_campaign || '';

        document.getElementById('vcName').textContent = name;
        document.getElementById('vcPlatform').innerHTML = PLATFORM_BADGES[platform] || platform;
        document.getElementById('vcObjective').textContent = OBJ_LABELS[objective] || objective || '--';
        document.getElementById('vcStatus').innerHTML = STATUS_BADGES[status] || status;
        document.getElementById('vcPeriod').textContent = startDate + (endDate ? ' \u2014 ' + endDate : '');
        document.getElementById('vcAudience').textContent = audience || '--';
        document.getElementById('vcLanding').innerHTML = landing ? '<a href="' + landing + '" class="text-primary fs-12" target="_blank">' + landing + '</a>' : '--';
        document.getElementById('vcUtm').textContent = utm ? '?utm_source=' + platform + '&utm_medium=cpc&utm_campaign=' + utm : '--';
        document.getElementById('vcBudget').textContent = fmt(budget) + ' PLN';
        document.getElementById('vcSpent').textContent = fmt(spent) + ' PLN';
        document.getElementById('vcLeads').textContent = leads;
        document.getElementById('vcCPL').textContent = leads > 0 ? fmt2(spent / leads) + ' PLN' : '--';
        document.getElementById('vcClicks').textContent = fmt(clicks);
        document.getElementById('vcCTR').textContent = ctr > 0 ? ctr.toFixed(1) + '%' : '--';
        const pct = budget > 0 ? Math.round(spent / budget * 100) : 0;
        const bar = document.getElementById('vcBudgetBar');
        bar.style.width = pct + '%';
        bar.className = 'fill ' + (pct >= 90 ? 'bg-danger' : pct >= 70 ? 'bg-warning' : 'bg-success');
        document.getElementById('vcBudgetPct').textContent = pct + '% used';
        document.getElementById('vcBudgetRemaining').textContent = fmt(Math.max(0, budget - spent)) + ' PLN remaining';
        new bootstrap.Modal(document.getElementById('viewCampModal')).show();
    }

    // View -> Edit
    document.getElementById('vcEditBtn').addEventListener('click', function() {
        const c = campaigns[currentIdx];
        if (!c) return;
        bootstrap.Modal.getInstance(document.getElementById('viewCampModal')).hide();
        setTimeout(() => showEdit(c, currentIdx), 300);
    });

    /* ============================
     *  EDIT MODAL
     * ============================ */
    function showEdit(c, idx) {
        currentIdx = idx;
        document.getElementById('ecName').value = c.name || c.campaign_name || '';
        setSelect('ecPlatform', c.platform || c._platform_slug || 'facebook');
        setSelect('ecObjective', c.objective || 'lead_gen');
        document.getElementById('ecBudget').value = c.budget || c.budget_total || 0;
        setSelect('ecStatus', (c.status || 'active').toLowerCase());
        document.getElementById('ecStart').value = c.start || c.start_date || '';
        document.getElementById('ecEnd').value = c.end || c.end_date || '';
        document.getElementById('ecDaily').value = c.daily || c.daily_budget || 0;
        document.getElementById('ecAudience').value = c.audience || c.target_audience || '';
        document.getElementById('ecLanding').value = c.landing || c.landing_page || '';
        document.getElementById('ecUtm').value = c.utm || c.utm_campaign || '';
        document.getElementById('ecNotes').value = c.notes || '';
        new bootstrap.Modal(document.getElementById('editCampModal')).show();
    }

    document.getElementById('saveEditCampBtn').addEventListener('click', function() {
        toast('Feature coming soon \u2014 campaigns are managed via ad platforms', 'info');
        bootstrap.Modal.getInstance(document.getElementById('editCampModal')).hide();
    });

    /* ============================
     *  STATS MODAL
     * ============================ */
    function showStats(c) {
        const name = c.name || c.campaign_name || 'Unnamed';
        const spent = Number(c.spent || c.cost || c.total_cost || 0);
        const leads = Number(c.leads || c.total_leads || c.conversions || 0);
        const clicks = Number(c.clicks || c.total_clicks || 0);
        const impressions = Number(c.impressions || c.total_impressions || 0);
        const reach = Number(c.reach || c.total_reach || Math.round(impressions * 0.6));
        const ctr = Number(c.ctr || (impressions > 0 ? (clicks / impressions * 100) : 0));

        document.getElementById('scName').textContent = name;
        document.getElementById('scImpressions').textContent = fmt(impressions);
        document.getElementById('scReach').textContent = fmt(reach);
        document.getElementById('scClicks').textContent = fmt(clicks);
        document.getElementById('scCTR').textContent = ctr > 0 ? ctr.toFixed(1) + '%' : '--';
        document.getElementById('scLeads').textContent = leads;

        const convRate = clicks > 0 ? (leads / clicks * 100).toFixed(2) + '%' : '--';
        document.getElementById('scConvRate').textContent = convRate;

        const cpc = clicks > 0 ? fmt2(spent / clicks) + ' PLN' : '--';
        document.getElementById('scCPC').textContent = cpc;
        document.getElementById('scCPL').textContent = leads > 0 ? fmt2(spent / leads) + ' PLN' : '--';
        const cpm = impressions > 0 ? fmt2(spent / impressions * 1000) + ' PLN' : '--';
        document.getElementById('scCPM').textContent = cpm;

        const roas = c.roas ? Number(c.roas).toFixed(1) + 'x' : (spent > 0 ? ((leads * 4500 * 0.3) / spent).toFixed(1) + 'x' : '--');
        document.getElementById('scROAS').textContent = roas;

        // Leads breakdown
        const tbl = document.getElementById('scLeadsTable');
        tbl.innerHTML = '';
        if (leads > 0) {
            const sources = [
                { s: 'Form Submission', l: Math.ceil(leads * 0.5), q: 'High', cv: Math.ceil(leads * 0.2) },
                { s: 'Phone Call', l: Math.ceil(leads * 0.25), q: 'Medium', cv: Math.ceil(leads * 0.1) },
                { s: 'Messenger', l: Math.floor(leads * 0.15), q: 'Medium', cv: Math.floor(leads * 0.05) },
                { s: 'WhatsApp', l: Math.floor(leads * 0.1), q: 'Low', cv: Math.floor(leads * 0.02) }
            ];
            sources.forEach(s => {
                const qBadge = s.q === 'High' ? 'bg-success-subtle text-success' : s.q === 'Medium' ? 'bg-warning-subtle text-warning' : 'bg-danger-subtle text-danger';
                tbl.innerHTML += '<tr><td>' + s.s + '</td><td>' + s.l + '</td><td><span class="badge ' + qBadge + '">' + s.q + '</span></td><td>' + s.cv + '</td></tr>';
            });
        } else {
            tbl.innerHTML = emptyRow(4, 'No leads data for this campaign.');
        }
        new bootstrap.Modal(document.getElementById('statsCampModal')).show();
    }

    /* ============================
     *  NEW CAMPAIGN
     * ============================ */
    document.getElementById('saveNewCampBtn').addEventListener('click', function() {
        toast('Feature coming soon \u2014 campaigns managed via ad platforms', 'info');
        bootstrap.Modal.getInstance(document.getElementById('newCampaignModal')).hide();
    });

    /* ============================
     *  SYNC BUTTON
     * ============================ */
    document.getElementById('syncAdsBtn').addEventListener('click', async function() {
        const btn = this;
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Syncing...';
        try {
            const result = await apiPostFormData('/ads/sync');
            if (result && result.success) {
                toast('Ad data synced successfully', 'success');
                // Reload all data
                await Promise.all([loadOverview(), loadBudget(), loadAllCampaigns(), loadDailyFromAllPlatforms()]);
            } else {
                toast(result && result.message ? result.message : 'Sync completed', 'info');
            }
        } catch (e) {
            toast('Sync request failed', 'danger');
        }
        btn.disabled = false;
        btn.innerHTML = '<i class="ri-refresh-line me-1"></i>Sync';
    });

    /* ============================
     *  EXPORT CSV
     * ============================ */
    document.getElementById('exportCampCsvBtn').addEventListener('click', function() {
        if (campaigns.length === 0) {
            toast('No campaigns to export', 'warning');
            return;
        }
        let csv = 'Name,Platform,Objective,Budget,Spent,Leads,CPL,CTR,Status,Start,End\n';
        campaigns.forEach(c => {
            const name = (c.name || c.campaign_name || '').replace(/"/g, '""');
            const platform = PLATFORM_LABELS[c.platform || c._platform_slug] || c.platform || '';
            const objective = OBJ_LABELS[c.objective] || c.objective || '';
            const budget = Number(c.budget || c.budget_total || 0);
            const spent = Number(c.spent || c.cost || c.total_cost || 0);
            const leads = Number(c.leads || c.total_leads || c.conversions || 0);
            const cpl = leads > 0 ? (spent / leads).toFixed(2) : '0';
            const ctr = c.ctr || 0;
            const status = c.status || 'active';
            const start = c.start || c.start_date || '';
            const end = c.end || c.end_date || '';
            csv += '"' + name + '",' + platform + ',' + objective + ',' + budget + ',' + spent + ',' + leads + ',' + cpl + ',' + ctr + ',' + status + ',' + start + ',' + end + '\n';
        });
        const blob = new Blob([csv], { type: 'text/csv' });
        const url = URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = 'campaigns_' + new Date().toISOString().slice(0, 10) + '.csv';
        a.click();
        URL.revokeObjectURL(url);
        toast('CSV exported', 'success');
    });

    /* ============================
     *  INIT — LOAD EVERYTHING
     * ============================ */
    (async function init() {
        // Show loaders
        document.getElementById('campTableBody').innerHTML = emptyRow(11, '<div class="spinner-border spinner-border-sm text-primary"></div> Loading...');
        CRM.loader('#adsSpendLeadsChart');
        CRM.loader('#adsDailyChart');

        // Fire all API calls in parallel
        await Promise.allSettled([
            loadOverview().then(function() {
                // After overview loads, build the spend vs leads chart from platform data
                if (overviewData && overviewData.platforms) {
                    buildSpendLeadsFromOverview(overviewData.platforms);
                } else {
                    renderSpendLeadsChart([], [], []);
                }
            }),
            loadBudget(),
            loadAllCampaigns(),
            loadDailyFromAllPlatforms()
        ]);
    })();

});
</script>
@endsection
