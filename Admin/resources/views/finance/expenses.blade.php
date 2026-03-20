@extends('partials.layouts.master')
@section('title', 'Expenses | WinCase CRM')
@section('sub-title', 'Expenses')
@section('sub-title-lang', 'wc-expenses')
@section('pagetitle', 'Finance')
@section('pagetitle-lang', 'wc-finance')
@section('buttonTitle', 'Add Expense')
@section('buttonTitle-lang', 'wc-add-expense')
@section('modalTarget', 'addExpenseModal')

@section('content')
<style>
    .exp-filter-pill{cursor:pointer;padding:6px 16px;border-radius:20px;font-size:.8rem;font-weight:600;border:1px solid #e9ecef;background:#fff;transition:.2s;display:inline-flex;align-items:center;gap:4px}
    .exp-filter-pill:hover{border-color:#845adf}
    .exp-filter-pill.active{background:#845adf;color:#fff;border-color:#845adf}
    .budget-bar{height:10px;border-radius:5px;overflow:hidden;background:#e9ecef}
    .budget-bar .fill{height:100%;border-radius:5px;transition:width .4s}
    .cat-card{border-radius:10px;padding:14px;text-align:center;transition:.2s}
    .cat-card:hover{transform:translateY(-2px);box-shadow:0 4px 12px rgba(0,0,0,.08)}
</style>

<!-- ============ STAT CARDS ============ -->
<div class="row">
    <div class="col-xl-3 col-md-6">
        <div class="card card-h-100"><div class="card-body">
            <div class="d-flex align-items-center">
                <div class="flex-grow-1">
                    <p class="text-uppercase fw-medium text-muted text-truncate mb-2 fs-12">Total Expenses</p>
                    <h4 class="fs-22 fw-semibold mb-0" id="statTotalExp">45 200,00 PLN</h4>
                    <p class="text-muted mt-1 mb-0"><span class="badge bg-danger-subtle text-danger me-1"><i class="ri-arrow-up-s-line"></i> 6.2%</span> vs last month</p>
                </div>
                <div class="avatar-sm flex-shrink-0"><span class="avatar-title bg-primary-subtle text-primary rounded-circle fs-3"><i class="ri-wallet-3-line"></i></span></div>
            </div>
        </div></div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card card-h-100"><div class="card-body">
            <div class="d-flex align-items-center">
                <div class="flex-grow-1">
                    <p class="text-uppercase fw-medium text-muted text-truncate mb-2 fs-12">This Month</p>
                    <h4 class="fs-22 fw-semibold mb-0" id="statMonthExp">8 750,00 PLN</h4>
                    <p class="text-muted mt-1 mb-0"><span class="badge bg-success-subtle text-success me-1"><i class="ri-arrow-down-s-line"></i> 3.1%</span> vs previous</p>
                </div>
                <div class="avatar-sm flex-shrink-0"><span class="avatar-title bg-info-subtle text-info rounded-circle fs-3"><i class="ri-calendar-check-line"></i></span></div>
            </div>
        </div></div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card card-h-100"><div class="card-body">
            <div class="d-flex align-items-center">
                <div class="flex-grow-1">
                    <p class="text-uppercase fw-medium text-muted text-truncate mb-2 fs-12">Pending Approval</p>
                    <h4 class="fs-22 fw-semibold mb-0" id="statPendingExp">4</h4>
                    <p class="text-muted mt-1 mb-0"><span class="badge bg-warning-subtle text-warning me-1" id="statPendingAmt">3 420,00 PLN</span></p>
                </div>
                <div class="avatar-sm flex-shrink-0"><span class="avatar-title bg-warning-subtle text-warning rounded-circle fs-3"><i class="ri-time-line"></i></span></div>
            </div>
        </div></div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card card-h-100"><div class="card-body">
            <div class="d-flex align-items-center">
                <div class="flex-grow-1">
                    <p class="text-uppercase fw-medium text-muted text-truncate mb-2 fs-12">Budget Remaining</p>
                    <h4 class="fs-22 fw-semibold mb-0">16 250,00 PLN</h4>
                    <div class="budget-bar mt-2"><div class="fill bg-success" style="width:65%"></div></div>
                    <p class="text-muted mt-1 mb-0 fs-11">65% of 25 000 PLN monthly budget</p>
                </div>
                <div class="avatar-sm flex-shrink-0"><span class="avatar-title bg-success-subtle text-success rounded-circle fs-3"><i class="ri-pie-chart-line"></i></span></div>
            </div>
        </div></div>
    </div>
</div>

<!-- ============ BUDGET BY CATEGORY ============ -->
<div class="row">
    <div class="col-xl-12">
        <div class="card"><div class="card-header"><h5 class="card-title mb-0">Expenses by Category (This Month)</h5></div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-xl col-md-4 col-6"><div class="cat-card bg-primary-subtle"><i class="ri-building-line fs-24 text-primary"></i><div class="fw-bold mt-1">4 500 PLN</div><div class="fs-11 text-muted">Office</div></div></div>
                    <div class="col-xl col-md-4 col-6"><div class="cat-card bg-info-subtle"><i class="ri-advertisement-line fs-24 text-info"></i><div class="fw-bold mt-1">1 850 PLN</div><div class="fs-11 text-muted">Marketing</div></div></div>
                    <div class="col-xl col-md-4 col-6"><div class="cat-card bg-danger-subtle"><i class="ri-scales-3-line fs-24 text-danger"></i><div class="fw-bold mt-1">2 200 PLN</div><div class="fs-11 text-muted">Legal Fees</div></div></div>
                    <div class="col-xl col-md-4 col-6"><div class="cat-card bg-secondary-subtle"><i class="ri-code-s-slash-line fs-24 text-secondary"></i><div class="fw-bold mt-1">890 PLN</div><div class="fs-11 text-muted">Software</div></div></div>
                    <div class="col-xl col-md-4 col-6"><div class="cat-card bg-warning-subtle"><i class="ri-flight-takeoff-line fs-24 text-warning"></i><div class="fw-bold mt-1">1 240 PLN</div><div class="fs-11 text-muted">Travel</div></div></div>
                    <div class="col-xl col-md-4 col-6"><div class="cat-card bg-success-subtle"><i class="ri-team-line fs-24 text-success"></i><div class="fw-bold mt-1">5 800 PLN</div><div class="fs-11 text-muted">Staff</div></div></div>
                    <div class="col-xl col-md-4 col-6"><div class="cat-card bg-dark-subtle"><i class="ri-more-line fs-24 text-dark"></i><div class="fw-bold mt-1">420 PLN</div><div class="fs-11 text-muted">Other</div></div></div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ============ EXPENSES TABLE ============ -->
<div class="card">
    <div class="card-header">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
            <h5 class="card-title mb-0">All Expenses</h5>
            <div class="d-flex gap-2">
                <button class="btn btn-sm btn-outline-primary" id="exportExpCsvBtn"><i class="ri-download-line me-1"></i>Export CSV</button>
                <a href="finance-invoices" class="btn btn-sm btn-outline-secondary"><i class="ri-file-list-3-line me-1"></i>Invoices</a>
                <a href="finance-payments" class="btn btn-sm btn-outline-success"><i class="ri-money-euro-circle-line me-1"></i>Payments</a>
            </div>
        </div>
        <div class="d-flex gap-1 mt-2 flex-wrap" id="expFilterPills">
            <span class="exp-filter-pill active" data-filter="all">All <span class="badge bg-secondary ms-1" id="eCntAll">0</span></span>
            <span class="exp-filter-pill" data-filter="approved">Approved <span class="badge bg-success ms-1" id="eCntAppr">0</span></span>
            <span class="exp-filter-pill" data-filter="pending">Pending <span class="badge bg-warning ms-1" id="eCntPend">0</span></span>
            <span class="exp-filter-pill" data-filter="rejected">Rejected <span class="badge bg-danger ms-1" id="eCntRej">0</span></span>
        </div>
        <div class="d-flex gap-2 mt-2 flex-wrap">
            <input type="text" class="form-control form-control-sm" id="expSearch" placeholder="Search description, ID..." style="max-width:220px">
            <select class="form-select form-select-sm" id="expCatFilter" style="max-width:160px">
                <option value="all">All Categories</option>
                <option value="office">Office</option><option value="marketing">Marketing</option>
                <option value="legal_fees">Legal Fees</option><option value="software">Software</option>
                <option value="travel">Travel</option><option value="staff">Staff</option>
                <option value="taxes">Taxes & Fees</option><option value="utilities">Utilities</option>
                <option value="other">Other</option>
            </select>
            <input type="date" class="form-control form-control-sm" id="expDateFrom" style="max-width:145px">
            <input type="date" class="form-control form-control-sm" id="expDateTo" style="max-width:145px">
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width:35px"><div class="form-check"><input class="form-check-input" type="checkbox" id="checkAllExp"></div></th>
                        <th>Expense ID</th>
                        <th>Description</th>
                        <th>Category</th>
                        <th>Amount</th>
                        <th>Submitted By</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody id="expTableBody"></tbody>
            </table>
        </div>
    </div>
    <div class="card-footer d-flex align-items-center justify-content-between">
        <span class="text-muted fs-12" id="expShowing">Showing 0 expenses</span>
        <div class="d-flex gap-2">
            <button class="btn btn-sm btn-outline-success" id="bulkApproveBtn"><i class="ri-check-double-line me-1"></i>Bulk Approve</button>
        </div>
    </div>
</div>

<!-- ============ VIEW EXPENSE MODAL ============ -->
<div class="modal fade" id="viewExpModal" tabindex="-1"><div class="modal-dialog"><div class="modal-content">
    <div class="modal-header bg-primary text-white">
        <h6 class="modal-title text-white"><i class="ri-eye-line me-2"></i>Expense Details</h6>
        <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
    </div>
    <div class="modal-body">
        <div class="row g-3">
            <div class="col-6"><label class="fs-11 text-muted">Expense ID</label><p class="fw-semibold mb-0" id="veId"></p></div>
            <div class="col-6"><label class="fs-11 text-muted">Date</label><p class="fw-semibold mb-0" id="veDate"></p></div>
            <div class="col-12"><label class="fs-11 text-muted">Description</label><p class="fw-semibold mb-0" id="veDesc"></p></div>
            <div class="col-6"><label class="fs-11 text-muted">Category</label><p class="mb-0" id="veCat"></p></div>
            <div class="col-6"><label class="fs-11 text-muted">Amount</label><p class="fw-bold fs-5 text-danger mb-0" id="veAmount"></p></div>
            <div class="col-6"><label class="fs-11 text-muted">Submitted By</label><p class="fw-semibold mb-0" id="veBy"></p></div>
            <div class="col-6"><label class="fs-11 text-muted">Status</label><p class="mb-0" id="veStatus"></p></div>
            <div class="col-12"><label class="fs-11 text-muted">Notes</label><p class="mb-0" id="veNotes"></p></div>
            <div class="col-12" id="veReceiptRow" style="display:none">
                <label class="fs-11 text-muted">Receipt</label>
                <p class="mb-0"><i class="ri-attachment-2 me-1"></i><a href="#" class="text-primary" id="veReceipt"></a></p>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button class="btn btn-sm btn-outline-info" id="veEditBtn"><i class="ri-pencil-line me-1"></i>Edit</button>
        <button class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Close</button>
    </div>
</div></div></div>

<!-- ============ EDIT EXPENSE MODAL ============ -->
<div class="modal fade" id="editExpModal" tabindex="-1"><div class="modal-dialog modal-lg"><div class="modal-content">
    <div class="modal-header bg-info text-white">
        <h6 class="modal-title text-white"><i class="ri-pencil-line me-2"></i>Edit Expense</h6>
        <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
    </div>
    <div class="modal-body">
        <div class="row g-3">
            <div class="col-md-4"><label class="form-label fs-12 fw-semibold">Expense ID</label><input type="text" class="form-control" id="eeId" readonly></div>
            <div class="col-md-4">
                <label class="form-label fs-12 fw-semibold">Category</label>
                <select class="form-select" id="eeCat">
                    <option value="office">Office</option><option value="marketing">Marketing</option>
                    <option value="legal_fees">Legal Fees</option><option value="software">Software</option>
                    <option value="travel">Travel</option><option value="staff">Staff</option>
                    <option value="taxes">Taxes & Fees</option><option value="utilities">Utilities</option>
                    <option value="other">Other</option>
                </select>
            </div>
            <div class="col-md-4"><label class="form-label fs-12 fw-semibold">Amount (PLN)</label><input type="number" class="form-control" id="eeAmount" step="0.01"></div>
            <div class="col-12"><label class="form-label fs-12 fw-semibold">Description</label><input type="text" class="form-control" id="eeDesc"></div>
            <div class="col-md-6"><label class="form-label fs-12 fw-semibold">Date</label><input type="date" class="form-control" id="eeDate"></div>
            <div class="col-md-6">
                <label class="form-label fs-12 fw-semibold">Status</label>
                <select class="form-select" id="eeStatus">
                    <option value="approved">Approved</option><option value="pending">Pending</option><option value="rejected">Rejected</option>
                </select>
            </div>
            <div class="col-12"><label class="form-label fs-12 fw-semibold">Notes</label><textarea class="form-control" id="eeNotes" rows="2"></textarea></div>
        </div>
    </div>
    <div class="modal-footer">
        <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button class="btn btn-info text-white" id="saveEditExpBtn"><i class="ri-check-line me-1"></i>Save Changes</button>
    </div>
</div></div></div>

<!-- ============ ADD EXPENSE MODAL ============ -->
<div class="modal fade" id="addExpenseModal" tabindex="-1"><div class="modal-dialog modal-lg"><div class="modal-content">
    <div class="modal-header bg-primary text-white">
        <h6 class="modal-title text-white"><i class="ri-add-circle-line me-2"></i>Add New Expense</h6>
        <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
    </div>
    <div class="modal-body">
        <div class="row g-3">
            <div class="col-12">
                <label class="form-label fs-12 fw-semibold">Description <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="newExpDesc" placeholder="e.g. Office supplies, Google Ads campaign...">
            </div>
            <div class="col-md-4">
                <label class="form-label fs-12 fw-semibold">Category <span class="text-danger">*</span></label>
                <select class="form-select" id="newExpCat">
                    <option value="">-- Select --</option>
                    <option value="office">Office</option><option value="marketing">Marketing</option>
                    <option value="legal_fees">Legal Fees</option><option value="software">Software</option>
                    <option value="travel">Travel</option><option value="staff">Staff</option>
                    <option value="taxes">Taxes & Fees</option><option value="utilities">Utilities</option>
                    <option value="other">Other</option>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label fs-12 fw-semibold">Amount (PLN) <span class="text-danger">*</span></label>
                <input type="number" class="form-control" id="newExpAmount" step="0.01" min="0" placeholder="0,00">
            </div>
            <div class="col-md-4">
                <label class="form-label fs-12 fw-semibold">Date <span class="text-danger">*</span></label>
                <input type="date" class="form-control" id="newExpDate">
            </div>
            <div class="col-md-6">
                <label class="form-label fs-12 fw-semibold">Submitted By</label>
                <select class="form-select" id="newExpBy">
                    <option value="Anna Kowalska">Anna Kowalska</option>
                    <option value="Marek Wisniewski">Marek Wisniewski</option>
                    <option value="Piotr Zielinski">Piotr Zielinski</option>
                    <option value="Katarzyna Nowak">Katarzyna Nowak</option>
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label fs-12 fw-semibold">Receipt</label>
                <input type="file" class="form-control" id="newExpReceipt" accept=".pdf,.jpg,.jpeg,.png">
                <div class="form-text fs-11">PDF, JPG or PNG. Max 5MB.</div>
            </div>
            <div class="col-md-6">
                <label class="form-label fs-12 fw-semibold">Payment Method</label>
                <select class="form-select" id="newExpMethod">
                    <option value="transfer">Bank Transfer</option><option value="card">Corporate Card</option>
                    <option value="cash">Cash</option><option value="invoice">Vendor Invoice</option>
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label fs-12 fw-semibold">Related Case (optional)</label>
                <select class="form-select" id="newExpCase">
                    <option value="">-- None --</option>
                    <option value="#WC-0189">#WC-0189 — Petrov</option>
                    <option value="#WC-0188">#WC-0188 — Ivanova</option>
                    <option value="#WC-0185">#WC-0185 — Kovalenko</option>
                    <option value="#WC-0190">#WC-0190 — Shevchenko</option>
                    <option value="#WC-0191">#WC-0191 — Bondarenko</option>
                    <option value="#WC-0192">#WC-0192 — Melnyk</option>
                </select>
            </div>
            <div class="col-12">
                <label class="form-label fs-12 fw-semibold">Notes</label>
                <textarea class="form-control" id="newExpNotes" rows="2" placeholder="Additional details..."></textarea>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button class="btn btn-primary" id="saveNewExpBtn"><i class="ri-check-line me-1"></i>Add Expense</button>
    </div>
</div></div></div>

<script>
document.addEventListener('DOMContentLoaded', function(){

    const CAT_ICONS = {office:'ri-building-line',marketing:'ri-advertisement-line',legal_fees:'ri-scales-3-line',software:'ri-code-s-slash-line',travel:'ri-flight-takeoff-line',staff:'ri-team-line',taxes:'ri-government-line',utilities:'ri-lightbulb-line',other:'ri-more-line'};
    const CAT_BADGES = {office:'bg-primary-subtle text-primary',marketing:'bg-info-subtle text-info',legal_fees:'bg-danger-subtle text-danger',software:'bg-secondary-subtle text-secondary',travel:'bg-warning-subtle text-warning',staff:'bg-success-subtle text-success',taxes:'bg-dark-subtle text-dark',utilities:'bg-primary-subtle text-primary',other:'bg-secondary-subtle text-secondary'};
    const CAT_LABELS = {office:'Office',marketing:'Marketing',legal_fees:'Legal Fees',software:'Software',travel:'Travel',staff:'Staff',taxes:'Taxes & Fees',utilities:'Utilities',other:'Other'};
    const STATUS_BADGES = {approved:'<span class="badge bg-success-subtle text-success">Approved</span>',pending:'<span class="badge bg-warning-subtle text-warning">Pending</span>',rejected:'<span class="badge bg-danger-subtle text-danger">Rejected</span>'};

    const STAFF = [
        {name:'Anna Kowalska',initials:'AK',color:'bg-primary-subtle text-primary'},
        {name:'Marek Wisniewski',initials:'MW',color:'bg-info-subtle text-info'},
        {name:'Piotr Zielinski',initials:'PZ',color:'bg-success-subtle text-success'},
        {name:'Katarzyna Nowak',initials:'KN',color:'bg-warning-subtle text-warning'}
    ];
    function getStaff(name){return STAFF.find(s=>s.name===name)||STAFF[0];}

    let expenses = [
        {id:'EXP-0320',desc:'Office rent - March 2026',cat:'office',amount:4500,by:'Anna Kowalska',status:'approved',date:'2026-03-01',notes:'Monthly office lease',receipt:'rent_march_2026.pdf',method:'transfer',caseId:''},
        {id:'EXP-0319',desc:'Google Ads - immigration keywords campaign',cat:'marketing',amount:1850,by:'Marek Wisniewski',status:'approved',date:'2026-02-28',notes:'Lead generation campaign Q1',receipt:'google_ads_feb.pdf',method:'card',caseId:''},
        {id:'EXP-0318',desc:'Ahrefs subscription - annual plan',cat:'software',amount:890,by:'Marek Wisniewski',status:'pending',date:'2026-02-27',notes:'SEO tool for satellite sites',receipt:'',method:'card',caseId:''},
        {id:'EXP-0317',desc:'Business trip Warsaw - client meeting & court',cat:'travel',amount:1240,by:'Piotr Zielinski',status:'pending',date:'2026-02-26',notes:'Train + hotel + meals',receipt:'travel_warsaw.pdf',method:'cash',caseId:'#WC-0190'},
        {id:'EXP-0316',desc:'Legal consultation - immigration law update 2026',cat:'legal_fees',amount:2200,by:'Anna Kowalska',status:'approved',date:'2026-02-25',notes:'Consultation with Kancelaria Prawna',receipt:'legal_inv_feb.pdf',method:'transfer',caseId:''},
        {id:'EXP-0315',desc:'Notarial fees - apostille documents',cat:'legal_fees',amount:650,by:'Piotr Zielinski',status:'approved',date:'2026-02-24',notes:'Notarial apostille for 3 clients',receipt:'',method:'cash',caseId:'#WC-0189'},
        {id:'EXP-0314',desc:'Zoom Business subscription - monthly',cat:'software',amount:320,by:'Marek Wisniewski',status:'approved',date:'2026-02-20',notes:'Video conferencing for client meetings',receipt:'zoom_feb.pdf',method:'card',caseId:''},
        {id:'EXP-0313',desc:'Office supplies - paper, toner, folders',cat:'office',amount:380,by:'Katarzyna Nowak',status:'approved',date:'2026-02-18',notes:'Monthly office supplies',receipt:'supplies_feb.jpg',method:'cash',caseId:''},
        {id:'EXP-0312',desc:'Salary - part-time assistant February',cat:'staff',amount:5800,by:'Anna Kowalska',status:'approved',date:'2026-02-28',notes:'Monthly salary + ZUS',receipt:'salary_feb.pdf',method:'transfer',caseId:''},
        {id:'EXP-0311',desc:'Courier DHL - document delivery to Urzad',cat:'other',amount:120,by:'Katarzyna Nowak',status:'approved',date:'2026-02-15',notes:'Express courier to Urzad Wojewodzki',receipt:'dhl_receipt.pdf',method:'cash',caseId:'#WC-0185'},
        {id:'EXP-0310',desc:'Facebook Ads - brand awareness',cat:'marketing',amount:950,by:'Marek Wisniewski',status:'pending',date:'2026-02-22',notes:'Social media campaign',receipt:'',method:'card',caseId:''},
        {id:'EXP-0309',desc:'VPS hosting - 4 servers Hetzner',cat:'software',amount:480,by:'Marek Wisniewski',status:'approved',date:'2026-02-01',notes:'Monthly hosting for all servers',receipt:'hetzner_feb.pdf',method:'card',caseId:''},
        {id:'EXP-0308',desc:'Business trip Krakow - conference',cat:'travel',amount:890,by:'Anna Kowalska',status:'rejected',date:'2026-02-12',notes:'Immigration law conference - rejected: not budgeted',receipt:'',method:'cash',caseId:''},
        {id:'EXP-0307',desc:'Translation services - 5 documents',cat:'legal_fees',amount:750,by:'Piotr Zielinski',status:'pending',date:'2026-02-28',notes:'Sworn translator - Ukrainian documents',receipt:'',method:'invoice',caseId:'#WC-0188'},
        {id:'EXP-0306',desc:'Electricity + Internet - office',cat:'utilities',amount:580,by:'Katarzyna Nowak',status:'approved',date:'2026-02-10',notes:'Monthly utilities',receipt:'utilities_feb.pdf',method:'transfer',caseId:''},
        {id:'EXP-0305',desc:'ZUS contributions - company',cat:'taxes',amount:1420,by:'Anna Kowalska',status:'approved',date:'2026-02-15',notes:'Monthly social security contributions',receipt:'zus_feb.pdf',method:'transfer',caseId:''}
    ];

    let expCounter = 321;
    let currentFilter = 'all';
    function fmt(n){return n.toLocaleString('pl-PL',{minimumFractionDigits:2,maximumFractionDigits:2});}

    function renderTable(){
        const search = (document.getElementById('expSearch').value||'').toLowerCase();
        const catF = document.getElementById('expCatFilter').value;
        const tbody = document.getElementById('expTableBody');
        tbody.innerHTML = '';
        let count = 0;
        expenses.forEach((exp,idx) => {
            if(currentFilter!=='all' && exp.status!==currentFilter) return;
            if(search && !exp.desc.toLowerCase().includes(search) && !exp.id.toLowerCase().includes(search)) return;
            if(catF!=='all' && exp.cat!==catF) return;
            count++;
            const s = getStaff(exp.by);
            const cb = CAT_BADGES[exp.cat]||'bg-secondary-subtle text-secondary';
            const ci = CAT_ICONS[exp.cat]||'ri-more-line';
            let actions = '';
            if(exp.status==='pending'){
                actions = `<div class="d-flex gap-1 justify-content-center">
                    <button class="btn btn-sm btn-subtle-primary act-view" title="View"><i class="ri-eye-line"></i></button>
                    <button class="btn btn-sm btn-subtle-success act-approve" title="Approve"><i class="ri-check-line"></i></button>
                    <button class="btn btn-sm btn-subtle-danger act-reject" title="Reject"><i class="ri-close-line"></i></button>
                    <button class="btn btn-sm btn-subtle-info act-edit" title="Edit"><i class="ri-pencil-line"></i></button>
                </div>`;
            } else {
                actions = `<div class="d-flex gap-1 justify-content-center">
                    <button class="btn btn-sm btn-subtle-primary act-view" title="View"><i class="ri-eye-line"></i></button>
                    <button class="btn btn-sm btn-subtle-info act-edit" title="Edit"><i class="ri-pencil-line"></i></button>
                    <button class="btn btn-sm btn-subtle-danger act-delete" title="Delete"><i class="ri-delete-bin-line"></i></button>
                </div>`;
            }
            tbody.innerHTML += `<tr data-idx="${idx}">
                <td><div class="form-check"><input class="form-check-input exp-check" type="checkbox"></div></td>
                <td><a href="#" class="fw-semibold text-body act-view">#${exp.id}</a></td>
                <td><div class="d-flex align-items-center gap-2"><i class="${ci} text-muted fs-16"></i><span>${exp.desc}</span></div></td>
                <td><span class="badge ${cb}">${CAT_LABELS[exp.cat]||exp.cat}</span></td>
                <td class="fw-semibold">${fmt(exp.amount)} PLN</td>
                <td><div class="d-flex align-items-center gap-2"><div class="avatar-xs"><span class="avatar-title ${s.color} rounded-circle fs-11">${s.initials}</span></div><span class="fs-12">${exp.by}</span></div></td>
                <td>${STATUS_BADGES[exp.status]}</td>
                <td class="text-muted fs-12">${exp.date}</td>
                <td>${actions}</td>
            </tr>`;
        });
        document.getElementById('expShowing').textContent = `Showing ${count} expenses`;
    }

    function updateCounts(){
        let all=0,appr=0,pend=0,rej=0;
        expenses.forEach(e=>{all++;if(e.status==='approved')appr++;if(e.status==='pending')pend++;if(e.status==='rejected')rej++;});
        document.getElementById('eCntAll').textContent=all;document.getElementById('eCntAppr').textContent=appr;
        document.getElementById('eCntPend').textContent=pend;document.getElementById('eCntRej').textContent=rej;
    }

    renderTable(); updateCounts();

    // ============ FILTERS ============
    document.getElementById('expFilterPills').addEventListener('click',function(e){
        const p=e.target.closest('.exp-filter-pill'); if(!p)return;
        document.querySelectorAll('.exp-filter-pill').forEach(x=>x.classList.remove('active'));
        p.classList.add('active');currentFilter=p.dataset.filter;renderTable();
    });
    document.getElementById('expSearch').addEventListener('input',()=>renderTable());
    document.getElementById('expCatFilter').addEventListener('change',()=>renderTable());

    // ============ TABLE ACTIONS ============
    let currentExpIdx = -1;
    document.getElementById('expTableBody').addEventListener('click',function(e){
        const btn=e.target.closest('button')||e.target.closest('a.act-view');
        if(!btn)return; e.preventDefault();
        const row=btn.closest('tr');
        const idx=parseInt(row.dataset.idx);
        const exp=expenses[idx]; if(!exp) return;
        currentExpIdx=idx;

        if(btn.classList.contains('act-view')){
            document.getElementById('veId').textContent='#'+exp.id;
            document.getElementById('veDate').textContent=exp.date;
            document.getElementById('veDesc').textContent=exp.desc;
            document.getElementById('veCat').innerHTML=`<span class="badge ${CAT_BADGES[exp.cat]}">${CAT_LABELS[exp.cat]}</span>`;
            document.getElementById('veAmount').textContent=fmt(exp.amount)+' PLN';
            document.getElementById('veBy').textContent=exp.by;
            document.getElementById('veStatus').innerHTML=STATUS_BADGES[exp.status];
            document.getElementById('veNotes').textContent=exp.notes||'—';
            if(exp.receipt){
                document.getElementById('veReceiptRow').style.display='';
                document.getElementById('veReceipt').textContent=exp.receipt;
            } else {
                document.getElementById('veReceiptRow').style.display='none';
            }
            new bootstrap.Modal(document.getElementById('viewExpModal')).show();
        }
        if(btn.classList.contains('act-approve')){exp.status='approved';renderTable();updateCounts();showToast('#'+exp.id+' approved','success');}
        if(btn.classList.contains('act-reject')){exp.status='rejected';renderTable();updateCounts();showToast('#'+exp.id+' rejected','danger');}
        if(btn.classList.contains('act-edit')){openEditExp(exp,idx);}
        if(btn.classList.contains('act-delete')){
            row.style.transition='opacity .3s';row.style.opacity='0';
            setTimeout(()=>{expenses.splice(idx,1);renderTable();updateCounts();showToast('#'+exp.id+' deleted','danger');},300);
        }
    });

    // View → Edit
    document.getElementById('veEditBtn').addEventListener('click',function(){
        const exp=expenses[currentExpIdx]; if(!exp)return;
        bootstrap.Modal.getInstance(document.getElementById('viewExpModal')).hide();
        setTimeout(()=>openEditExp(exp,currentExpIdx),300);
    });

    // ============ EDIT EXPENSE ============
    function openEditExp(exp,idx){
        currentExpIdx=idx;
        document.getElementById('eeId').value=exp.id;
        setSelect('eeCat',exp.cat);
        document.getElementById('eeAmount').value=exp.amount;
        document.getElementById('eeDesc').value=exp.desc;
        document.getElementById('eeDate').value=exp.date;
        setSelect('eeStatus',exp.status);
        document.getElementById('eeNotes').value=exp.notes||'';
        new bootstrap.Modal(document.getElementById('editExpModal')).show();
    }

    document.getElementById('saveEditExpBtn').addEventListener('click',function(){
        const exp=expenses[currentExpIdx]; if(!exp)return;
        exp.cat=document.getElementById('eeCat').value;
        exp.amount=parseFloat(document.getElementById('eeAmount').value)||0;
        exp.desc=document.getElementById('eeDesc').value;
        exp.date=document.getElementById('eeDate').value;
        exp.status=document.getElementById('eeStatus').value;
        exp.notes=document.getElementById('eeNotes').value;
        renderTable();updateCounts();
        bootstrap.Modal.getInstance(document.getElementById('editExpModal')).hide();
        showToast('#'+exp.id+' updated','success');
    });

    // ============ ADD EXPENSE ============
    document.getElementById('saveNewExpBtn').addEventListener('click',function(){
        const desc=document.getElementById('newExpDesc').value;
        const cat=document.getElementById('newExpCat').value;
        const amt=parseFloat(document.getElementById('newExpAmount').value)||0;
        if(!desc||!cat||amt<=0){showToast('Fill required fields','warning');return;}
        const date=document.getElementById('newExpDate').value||new Date().toISOString().slice(0,10);
        const by=document.getElementById('newExpBy').value;
        const notes=document.getElementById('newExpNotes').value;
        const method=document.getElementById('newExpMethod').value;
        const caseId=document.getElementById('newExpCase').value;
        expenses.unshift({
            id:'EXP-0'+expCounter++,desc:desc,cat:cat,amount:amt,
            by:by,status:'pending',date:date,notes:notes,receipt:'',method:method,caseId:caseId
        });
        renderTable();updateCounts();
        bootstrap.Modal.getInstance(document.getElementById('addExpenseModal')).hide();
        showToast('Expense '+fmt(amt)+' PLN added','success');
    });

    // ============ BULK APPROVE ============
    document.getElementById('bulkApproveBtn').addEventListener('click',function(){
        let cnt=0;
        document.querySelectorAll('.exp-check:checked').forEach(c=>{
            const row=c.closest('tr');
            const idx=parseInt(row.dataset.idx);
            if(expenses[idx]&&expenses[idx].status==='pending'){expenses[idx].status='approved';cnt++;}
        });
        if(cnt>0){renderTable();updateCounts();showToast(cnt+' expenses approved','success');}
        else showToast('No pending expenses selected','warning');
    });

    // ============ EXPORT CSV ============
    document.getElementById('exportExpCsvBtn').addEventListener('click',function(){
        let csv='ID,Description,Category,Amount,Submitted By,Status,Date,Notes\n';
        expenses.forEach(e=>{csv+=`${e.id},"${e.desc}",${CAT_LABELS[e.cat]||e.cat},${e.amount},"${e.by}",${e.status},${e.date},"${e.notes}"\n`;});
        const blob=new Blob([csv],{type:'text/csv'});
        const url=URL.createObjectURL(blob);const a=document.createElement('a');
        a.href=url;a.download='expenses_'+new Date().toISOString().slice(0,10)+'.csv';a.click();URL.revokeObjectURL(url);
        showToast('CSV exported','success');
    });

    // ============ CHECK ALL ============
    document.getElementById('checkAllExp').addEventListener('change',function(){document.querySelectorAll('.exp-check').forEach(c=>c.checked=this.checked);});

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
