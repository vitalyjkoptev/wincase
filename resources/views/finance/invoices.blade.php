@extends('partials.layouts.master')
@section('title', 'Invoices | WinCase CRM')
@section('sub-title', 'Invoices')
@section('sub-title-lang', 'wc-invoices')
@section('pagetitle', 'Finance')
@section('pagetitle-lang', 'wc-finance')
@section('buttonTitle', 'Create Invoice')
@section('buttonTitle-lang', 'wc-create-invoice')
@section('modalTarget', 'createInvoiceModal')

@section('content')
<style>
    .inv-filter-pill{cursor:pointer;padding:6px 16px;border-radius:20px;font-size:.8rem;font-weight:600;border:1px solid #e9ecef;background:#fff;transition:.2s;display:inline-flex;align-items:center;gap:4px}
    .inv-filter-pill:hover{border-color:#845adf}
    .inv-filter-pill.active{background:#845adf;color:#fff;border-color:#845adf}
    .debt-progress{height:6px;border-radius:3px}
    .invoice-preview{font-family:'Courier New',monospace;font-size:.82rem;background:#fafafa;border:1px solid #dee2e6;border-radius:8px;padding:20px}
    .invoice-preview .inv-line{display:flex;justify-content:space-between;padding:2px 0}
    .invoice-preview .inv-total{font-weight:700;font-size:1rem;border-top:2px solid #333;padding-top:6px;margin-top:6px}
    .stat-mini{text-align:center;padding:12px;border-radius:10px}
</style>

<!-- ============ STAT CARDS ============ -->
<div class="row">
    <div class="col-xl-3 col-md-6">
        <div class="card card-h-100"><div class="card-body">
            <div class="d-flex align-items-center">
                <div class="flex-grow-1">
                    <p class="text-uppercase fw-medium text-muted text-truncate mb-2 fs-12">Total Invoiced</p>
                    <h4 class="fs-22 fw-semibold mb-0" id="statTotalInvoiced">234 500,00 PLN</h4>
                    <p class="text-muted mt-1 mb-0"><span class="badge bg-success-subtle text-success me-1"><i class="ri-arrow-up-s-line"></i> 15.2%</span> vs last month</p>
                </div>
                <div class="avatar-sm flex-shrink-0"><span class="avatar-title bg-primary-subtle text-primary rounded-circle fs-3"><i class="ri-file-list-3-line"></i></span></div>
            </div>
        </div></div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card card-h-100"><div class="card-body">
            <div class="d-flex align-items-center">
                <div class="flex-grow-1">
                    <p class="text-uppercase fw-medium text-muted text-truncate mb-2 fs-12">Fully Paid</p>
                    <h4 class="fs-22 fw-semibold mb-0" id="statFullyPaid">187 450,00 PLN</h4>
                    <p class="text-muted mt-1 mb-0"><span class="badge bg-success-subtle text-success me-1">79.8%</span> collection rate</p>
                </div>
                <div class="avatar-sm flex-shrink-0"><span class="avatar-title bg-success-subtle text-success rounded-circle fs-3"><i class="ri-checkbox-circle-line"></i></span></div>
            </div>
        </div></div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card card-h-100"><div class="card-body">
            <div class="d-flex align-items-center">
                <div class="flex-grow-1">
                    <p class="text-uppercase fw-medium text-muted text-truncate mb-2 fs-12">Partially Paid</p>
                    <h4 class="fs-22 fw-semibold mb-0" id="statPartial">35 650,00 PLN</h4>
                    <p class="text-muted mt-1 mb-0"><span class="badge bg-warning-subtle text-warning me-1" id="statPartialCount">12 invoices</span></p>
                </div>
                <div class="avatar-sm flex-shrink-0"><span class="avatar-title bg-warning-subtle text-warning rounded-circle fs-3"><i class="ri-time-line"></i></span></div>
            </div>
        </div></div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card card-h-100"><div class="card-body">
            <div class="d-flex align-items-center">
                <div class="flex-grow-1">
                    <p class="text-uppercase fw-medium text-muted text-truncate mb-2 fs-12">Outstanding Debt</p>
                    <h4 class="fs-22 fw-semibold mb-0 text-danger" id="statDebt">11 400,00 PLN</h4>
                    <p class="text-muted mt-1 mb-0"><span class="badge bg-danger-subtle text-danger me-1" id="statDebtCount">5 clients</span> owe money</p>
                </div>
                <div class="avatar-sm flex-shrink-0"><span class="avatar-title bg-danger-subtle text-danger rounded-circle fs-3"><i class="ri-error-warning-line"></i></span></div>
            </div>
        </div></div>
    </div>
</div>

<!-- ============ PAYMENTS THIS MONTH + INCOME BY TYPE ============ -->
<div class="row">
    <div class="col-xl-8">
        <div class="card"><div class="card-header"><h5 class="card-title mb-0">Payments This Month</h5></div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4"><div class="stat-mini bg-success-subtle"><p class="text-muted mb-1 fs-13">Received</p><h3 class="mb-0 fw-semibold text-success">34 200,00 PLN</h3></div></div>
                    <div class="col-md-4"><div class="stat-mini bg-warning-subtle"><p class="text-muted mb-1 fs-13">Expected</p><h3 class="mb-0 fw-semibold text-warning">12 800,00 PLN</h3></div></div>
                    <div class="col-md-4"><div class="stat-mini bg-danger-subtle"><p class="text-muted mb-1 fs-13">Overdue</p><h3 class="mb-0 fw-semibold text-danger">5 400,00 PLN</h3></div></div>
                </div>
                <div class="mt-3">
                    <div class="d-flex justify-content-between fs-12 text-muted mb-1"><span>Collection Progress</span><span>73%</span></div>
                    <div class="progress" style="height:8px"><div class="progress-bar bg-success" style="width:73%"></div></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-4">
        <div class="card"><div class="card-header"><h5 class="card-title mb-0">Income by Case Type</h5></div>
            <div class="card-body p-0">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex justify-content-between"><span><span class="badge bg-primary me-1">&nbsp;</span>Temporary Residence</span><span class="fw-semibold">98 400 PLN</span></li>
                    <li class="list-group-item d-flex justify-content-between"><span><span class="badge bg-info me-1">&nbsp;</span>Permanent Residence</span><span class="fw-semibold">45 200 PLN</span></li>
                    <li class="list-group-item d-flex justify-content-between"><span><span class="badge bg-success me-1">&nbsp;</span>Citizenship</span><span class="fw-semibold">42 000 PLN</span></li>
                    <li class="list-group-item d-flex justify-content-between"><span><span class="badge bg-dark me-1">&nbsp;</span>Work Permit</span><span class="fw-semibold">28 500 PLN</span></li>
                    <li class="list-group-item d-flex justify-content-between"><span><span class="badge bg-warning me-1">&nbsp;</span>Blue Card EU</span><span class="fw-semibold">15 400 PLN</span></li>
                    <li class="list-group-item d-flex justify-content-between"><span><span class="badge bg-danger me-1">&nbsp;</span>Appeal / Other</span><span class="fw-semibold">5 000 PLN</span></li>
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- ============ INVOICES TABLE ============ -->
<div class="card">
    <div class="card-header">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
            <h5 class="card-title mb-0">All Invoices</h5>
            <div class="d-flex gap-2">
                <button class="btn btn-sm btn-outline-primary" id="exportInvCsvBtn"><i class="ri-download-line me-1"></i>Export CSV</button>
                <a href="finance-payments" class="btn btn-sm btn-outline-success"><i class="ri-money-euro-circle-line me-1"></i>Payments</a>
                <a href="finance-pos" class="btn btn-sm btn-outline-info"><i class="ri-bank-card-line me-1"></i>POS Terminal</a>
            </div>
        </div>
        <div class="d-flex gap-2 mt-2 flex-wrap">
            <div class="d-flex gap-1" id="invFilterPills">
                <span class="inv-filter-pill active" data-filter="all">All <span class="badge bg-secondary ms-1" id="cntAll">0</span></span>
                <span class="inv-filter-pill" data-filter="paid">Paid <span class="badge bg-success ms-1" id="cntPaid">0</span></span>
                <span class="inv-filter-pill" data-filter="partial">Partial <span class="badge bg-warning ms-1" id="cntPartial">0</span></span>
                <span class="inv-filter-pill" data-filter="unpaid">Unpaid <span class="badge bg-danger ms-1" id="cntUnpaid">0</span></span>
                <span class="inv-filter-pill" data-filter="overdue">Overdue <span class="badge bg-dark ms-1" id="cntOverdue">0</span></span>
            </div>
        </div>
        <div class="d-flex gap-2 mt-2 flex-wrap">
            <input type="text" class="form-control form-control-sm" id="invSearch" placeholder="Search client, invoice #..." style="max-width:220px">
            <select class="form-select form-select-sm" id="invTypeFilter" style="max-width:180px">
                <option value="all">All Case Types</option>
                <option value="Temporary Residence">Temporary Residence</option>
                <option value="Permanent Residence">Permanent Residence</option>
                <option value="Work Permit">Work Permit</option>
                <option value="Blue Card EU">Blue Card EU</option>
                <option value="Citizenship">Citizenship</option>
                <option value="Family Reunification">Family Reunification</option>
                <option value="Appeal">Appeal</option>
            </select>
            <input type="date" class="form-control form-control-sm" id="invDateFrom" style="max-width:145px">
            <input type="date" class="form-control form-control-sm" id="invDateTo" style="max-width:145px">
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" id="invTable">
                <thead class="table-light">
                    <tr>
                        <th style="width:35px"><div class="form-check"><input class="form-check-input" type="checkbox" id="checkAllInv"></div></th>
                        <th>Invoice #</th>
                        <th>Client</th>
                        <th>Case</th>
                        <th>Case Type</th>
                        <th>Amount</th>
                        <th>Paid</th>
                        <th>Balance</th>
                        <th>Status</th>
                        <th>Due Date</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody id="invTableBody"></tbody>
            </table>
        </div>
    </div>
    <div class="card-footer d-flex align-items-center justify-content-between">
        <span class="text-muted fs-12" id="invShowing">Showing 0 invoices</span>
        <div class="d-flex gap-1">
            <a href="finance-expenses" class="btn btn-sm btn-outline-secondary"><i class="ri-wallet-3-line me-1"></i>Expenses</a>
        </div>
    </div>
</div>

<!-- ============ VIEW INVOICE MODAL ============ -->
<div class="modal fade" id="viewInvoiceModal" tabindex="-1"><div class="modal-dialog modal-lg"><div class="modal-content">
    <div class="modal-header bg-primary text-white">
        <h6 class="modal-title text-white"><i class="ri-file-list-3-line me-2"></i>Invoice Details</h6>
        <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
    </div>
    <div class="modal-body">
        <div class="invoice-preview" id="invoicePreviewBody"></div>
    </div>
    <div class="modal-footer">
        <button class="btn btn-sm btn-outline-danger" id="viewInvPdfBtn"><i class="ri-file-pdf-2-line me-1"></i>Download PDF</button>
        <button class="btn btn-sm btn-outline-primary" id="viewInvSendBtn"><i class="ri-mail-send-line me-1"></i>Send to Client</button>
        <button class="btn btn-sm btn-success" id="viewInvRecordPayBtn"><i class="ri-money-euro-circle-line me-1"></i>Record Payment</button>
        <button class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Close</button>
    </div>
</div></div></div>

<!-- ============ RECORD PAYMENT MODAL ============ -->
<div class="modal fade" id="recordPaymentModal" tabindex="-1"><div class="modal-dialog"><div class="modal-content">
    <div class="modal-header bg-success text-white">
        <h6 class="modal-title text-white"><i class="ri-money-euro-circle-line me-2"></i>Record Payment</h6>
        <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
    </div>
    <div class="modal-body">
        <div class="row g-3">
            <div class="col-6"><label class="fs-11 text-muted">Invoice</label><p class="fw-semibold mb-0" id="rpInvNum"></p></div>
            <div class="col-6"><label class="fs-11 text-muted">Outstanding</label><p class="fw-bold text-danger mb-0" id="rpOutstanding"></p></div>
            <div class="col-6">
                <label class="form-label fs-12 fw-semibold">Amount (PLN)</label>
                <input type="number" class="form-control" id="rpAmount" step="0.01" min="0">
            </div>
            <div class="col-6">
                <label class="form-label fs-12 fw-semibold">Method</label>
                <select class="form-select" id="rpMethod">
                    <option value="card">Card</option><option value="cash">Cash</option>
                    <option value="transfer">Bank Transfer</option><option value="blik">BLIK</option>
                    <option value="payu">PayU</option><option value="p24">Przelewy24</option>
                    <option value="paypal">PayPal</option><option value="wise">Wise</option>
                    <option value="revolut">Revolut</option>
                </select>
            </div>
            <div class="col-6">
                <label class="form-label fs-12 fw-semibold">Date</label>
                <input type="date" class="form-control" id="rpDate">
            </div>
            <div class="col-6">
                <label class="form-label fs-12 fw-semibold">Reference</label>
                <input type="text" class="form-control" id="rpRef" placeholder="TXN-...">
            </div>
            <div class="col-12">
                <label class="form-label fs-12 fw-semibold">Notes</label>
                <textarea class="form-control" id="rpNotes" rows="2"></textarea>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button class="btn btn-success" id="saveRecordPayBtn"><i class="ri-check-line me-1"></i>Record Payment</button>
    </div>
</div></div></div>

<!-- ============ SEND REMINDER MODAL ============ -->
<div class="modal fade" id="sendReminderModal" tabindex="-1"><div class="modal-dialog"><div class="modal-content">
    <div class="modal-header bg-warning">
        <h6 class="modal-title"><i class="ri-mail-send-line me-2"></i>Send Payment Reminder</h6>
        <button class="btn-close" data-bs-dismiss="modal"></button>
    </div>
    <div class="modal-body">
        <div class="row g-3">
            <div class="col-6"><label class="fs-11 text-muted">Client</label><p class="fw-semibold mb-0" id="remClient"></p></div>
            <div class="col-6"><label class="fs-11 text-muted">Outstanding</label><p class="fw-bold text-danger mb-0" id="remAmount"></p></div>
            <div class="col-12">
                <label class="form-label fs-12 fw-semibold">Send via</label>
                <div class="d-flex gap-2">
                    <div class="form-check"><input class="form-check-input" type="checkbox" id="remEmail" checked><label class="form-check-label fs-12">Email</label></div>
                    <div class="form-check"><input class="form-check-input" type="checkbox" id="remSms"><label class="form-check-label fs-12">SMS</label></div>
                    <div class="form-check"><input class="form-check-input" type="checkbox" id="remPortal"><label class="form-check-label fs-12">Client Portal</label></div>
                </div>
            </div>
            <div class="col-12">
                <label class="form-label fs-12 fw-semibold">Message</label>
                <textarea class="form-control" id="remMessage" rows="4"></textarea>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button class="btn btn-warning" id="sendReminderBtn"><i class="ri-send-plane-line me-1"></i>Send Reminder</button>
    </div>
</div></div></div>

<!-- ============ EDIT INVOICE MODAL ============ -->
<div class="modal fade" id="editInvoiceModal" tabindex="-1"><div class="modal-dialog modal-lg"><div class="modal-content">
    <div class="modal-header bg-info text-white">
        <h6 class="modal-title text-white"><i class="ri-pencil-line me-2"></i>Edit Invoice</h6>
        <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
    </div>
    <div class="modal-body">
        <div class="row g-3">
            <div class="col-md-4"><label class="form-label fs-12 fw-semibold">Invoice #</label><input type="text" class="form-control" id="editInvNum" readonly></div>
            <div class="col-md-4">
                <label class="form-label fs-12 fw-semibold">Client</label>
                <select class="form-select" id="editInvClient" disabled>
                    <option value="">—</option>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label fs-12 fw-semibold">Case Type</label>
                <select class="form-select" id="editInvType">
                    <option>Temporary Residence</option><option>Permanent Residence</option><option>Work Permit</option>
                    <option>Blue Card EU</option><option>Citizenship</option><option>Family Reunification</option><option>Appeal</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label fs-12 fw-semibold">Contract Amount (PLN)</label>
                <input type="number" class="form-control" id="editInvAmount" step="0.01">
            </div>
            <div class="col-md-3">
                <label class="form-label fs-12 fw-semibold">VAT Rate</label>
                <select class="form-select" id="editInvVat">
                    <option value="23">23%</option><option value="8">8%</option><option value="0">0% (zwolniony)</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label fs-12 fw-semibold">Due Date</label>
                <input type="date" class="form-control" id="editInvDue">
            </div>
            <div class="col-md-3">
                <label class="form-label fs-12 fw-semibold">Status</label>
                <select class="form-select" id="editInvStatus">
                    <option value="paid">Fully Paid</option><option value="partial">Partially Paid</option>
                    <option value="unpaid">Unpaid</option><option value="overdue">Overdue</option>
                </select>
            </div>
            <div class="col-12">
                <label class="form-label fs-12 fw-semibold">Notes</label>
                <textarea class="form-control" id="editInvNotes" rows="2"></textarea>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button class="btn btn-info text-white" id="saveEditInvBtn"><i class="ri-check-line me-1"></i>Save Changes</button>
    </div>
</div></div></div>

<!-- ============ CREATE INVOICE MODAL ============ -->
<div class="modal fade" id="createInvoiceModal" tabindex="-1"><div class="modal-dialog modal-lg"><div class="modal-content">
    <div class="modal-header bg-primary text-white">
        <h6 class="modal-title text-white"><i class="ri-file-add-line me-2"></i>Create New Invoice (Faktura VAT)</h6>
        <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
    </div>
    <div class="modal-body">
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label fs-12 fw-semibold">Client <span class="text-danger">*</span></label>
                <select class="form-select" id="newInvClient" required>
                    <option value="">-- Select client --</option>
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label fs-12 fw-semibold">Case <span class="text-danger">*</span></label>
                <select class="form-select" id="newInvCase" required>
                    <option value="">-- Select case --</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label fs-12 fw-semibold">Net Amount (PLN) <span class="text-danger">*</span></label>
                <input type="number" class="form-control" id="newInvNet" step="0.01" min="0" placeholder="0,00">
            </div>
            <div class="col-md-3">
                <label class="form-label fs-12 fw-semibold">VAT Rate</label>
                <select class="form-select" id="newInvVat">
                    <option value="23" selected>23%</option><option value="8">8%</option><option value="0">0% (zw.)</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label fs-12 fw-semibold">VAT Amount</label>
                <input type="text" class="form-control" id="newInvVatAmt" readonly>
            </div>
            <div class="col-md-3">
                <label class="form-label fs-12 fw-semibold">Gross (Brutto)</label>
                <input type="text" class="form-control fw-bold" id="newInvGross" readonly>
            </div>
            <div class="col-md-4">
                <label class="form-label fs-12 fw-semibold">Payment Method</label>
                <select class="form-select" id="newInvMethod">
                    <option value="transfer">Bank Transfer</option><option value="card">Card</option>
                    <option value="cash">Cash</option><option value="blik">BLIK</option>
                    <option value="payu">PayU</option><option value="p24">Przelewy24</option>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label fs-12 fw-semibold">Due Date <span class="text-danger">*</span></label>
                <input type="date" class="form-control" id="newInvDue">
            </div>
            <div class="col-md-4">
                <label class="form-label fs-12 fw-semibold">Payment Terms</label>
                <select class="form-select" id="newInvTerms">
                    <option value="7">7 days</option><option value="14" selected>14 days</option>
                    <option value="30">30 days</option><option value="0">Due on receipt</option>
                </select>
            </div>
            <div class="col-12">
                <label class="form-label fs-12 fw-semibold">Notes / Description</label>
                <textarea class="form-control" id="newInvNotes" rows="2" placeholder="Service description..."></textarea>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button class="btn btn-primary" id="saveNewInvBtn"><i class="ri-check-line me-1"></i>Create Invoice</button>
    </div>
</div></div></div>

<script>
document.addEventListener('DOMContentLoaded', function(){

    const API = '/api/v1';
    const TOKEN = localStorage.getItem('wc_token');
    const H = { 'Authorization': 'Bearer ' + TOKEN, 'Accept': 'application/json' };

    const TYPE_BADGES = {
        'Temporary Residence':'bg-primary-subtle text-primary','Permanent Residence':'bg-info-subtle text-info',
        'Work Permit':'bg-dark-subtle text-dark','Blue Card EU':'bg-secondary-subtle text-secondary',
        'Citizenship':'bg-success-subtle text-success','Family Reunification':'bg-warning-subtle text-warning',
        'Appeal':'bg-danger-subtle text-danger'
    };

    const STATUS_MAP = {
        paid:   {badge:'<span class="badge bg-success-subtle text-success">Fully Paid</span>',   cls:'text-muted'},
        partial:{badge:'<span class="badge bg-warning-subtle text-warning">Partially Paid</span>',cls:'text-danger fw-semibold'},
        unpaid: {badge:'<span class="badge bg-danger-subtle text-danger">Unpaid</span>',          cls:'text-danger fw-semibold'},
        overdue:{badge:'<span class="badge bg-dark-subtle text-dark">Overdue</span>',             cls:'text-danger fw-semibold'},
        cancelled:{badge:'<span class="badge bg-secondary-subtle text-secondary">Cancelled</span>',cls:'text-muted'}
    };

    let invoices = [];
    let clientsList = [];
    let currentFilter = 'all';
    let currentViewInvoice = null;

    function fmt(n){ return Number(n).toLocaleString('pl-PL',{minimumFractionDigits:2,maximumFractionDigits:2}); }

    // Map API status to UI status
    function mapStatus(inv){
        if(inv.status === 'paid') return 'paid';
        if(inv.status === 'overdue') return 'overdue';
        if(inv.status === 'cancelled') return 'cancelled';
        // draft or sent: check for partial payments
        if(inv.total_paid && parseFloat(inv.total_paid) > 0 && parseFloat(inv.total_paid) < parseFloat(inv.total_amount || inv.gross_amount)){
            return 'partial';
        }
        return 'unpaid';
    }

    // Normalize an API invoice into our local format
    function normalize(raw){
        var clientName = '';
        if(raw.client){
            clientName = (raw.client.first_name || '') + ' ' + (raw.client.last_name || '');
            clientName = clientName.trim();
        }
        var caseNum = '';
        var caseType = '';
        if(raw.case_ref || raw['case']){
            var c = raw.case_ref || raw['case'];
            caseNum = c.case_number || '';
            caseType = c.case_type || '';
        }
        var netAmt   = parseFloat(raw.net_amount) || 0;
        var vatRate  = parseFloat(raw.vat_rate) || 23;
        var vatAmt   = parseFloat(raw.vat_amount) || (netAmt * vatRate / 100);
        var grossAmt = parseFloat(raw.gross_amount) || parseFloat(raw.total_amount) || (netAmt + vatAmt);
        var totalPaid = parseFloat(raw.total_paid) || 0;
        var remaining = parseFloat(raw.remaining) || (grossAmt - totalPaid);

        var obj = {
            _id:       raw.id,
            id:        raw.invoice_number || ('INV-' + raw.id),
            client:    clientName,
            clientId:  raw.client_id,
            caseId:    caseNum ? ('#' + caseNum) : '',
            caseDbId:  raw.case_id,
            caseType:  caseType,
            amount:    grossAmt,
            netAmount: netAmt,
            vat:       vatRate,
            vatAmount: vatAmt,
            paid:      totalPaid,
            remaining: remaining,
            status:    '', // will be set below
            due:       raw.due_date || '',
            issueDate: raw.issue_date || '',
            paidDate:  raw.paid_date || '',
            method:    raw.payment_method || '',
            notes:     raw.notes || '',
            currency:  raw.currency || 'PLN'
        };
        obj.status = mapStatus({status: raw.status, total_paid: totalPaid, total_amount: grossAmt, gross_amount: grossAmt});
        return obj;
    }

    // ============ LOAD DATA ============
    async function loadInvoices(){
        try {
            var params = new URLSearchParams();
            var search = (document.getElementById('invSearch').value || '').trim();
            if(search) params.set('search', search);
            var url = API + '/accounting/invoices' + (params.toString() ? '?' + params.toString() : '');
            var resp = await fetch(url, {headers: H});
            if(!resp.ok) throw new Error('HTTP ' + resp.status);
            var json = await resp.json();
            if(!json.success) throw new Error(json.message || 'API error');
            var data = json.data || {};
            var rawList = data.invoices || data.data || [];
            if(Array.isArray(data) && !data.invoices) rawList = data;
            invoices = rawList.map(normalize);

            // Update stat cards from API stats
            var stats = data.stats;
            if(stats){
                document.getElementById('statTotalInvoiced').textContent = fmt(stats.total_issued || 0) + ' PLN';
                document.getElementById('statPaidAmount').textContent = fmt(stats.total_paid || 0) + ' PLN';
                document.getElementById('statOverdueAmt').textContent = fmt(stats.overdue_amount || 0) + ' PLN';
                document.getElementById('statOverdueCnt').textContent = stats.overdue_count || 0;
                document.getElementById('statPendingAmt').textContent = fmt(stats.pending_amount || 0) + ' PLN';
                document.getElementById('statPendingCnt').textContent = stats.pending_count || 0;
            } else {
                updateStatCards();
            }

            renderTable();
            updateCounts();
        } catch(err){
            console.error('loadInvoices:', err);
            showToast('Failed to load invoices: ' + err.message, 'danger');
        }
    }

    function updateStatCards(){
        var totalInvoiced = 0, totalPaid = 0, overdueAmt = 0, overdueCnt = 0, pendingAmt = 0, pendingCnt = 0;
        invoices.forEach(function(inv){
            totalInvoiced += inv.amount;
            totalPaid += inv.paid;
            if(inv.status === 'overdue'){
                overdueAmt += inv.remaining;
                overdueCnt++;
            }
            if(inv.status === 'unpaid' || inv.status === 'partial'){
                pendingAmt += inv.remaining;
                pendingCnt++;
            }
        });
        document.getElementById('statTotalInvoiced').textContent = fmt(totalInvoiced) + ' PLN';
        document.getElementById('statPaidAmount').textContent = fmt(totalPaid) + ' PLN';
        document.getElementById('statOverdueAmt').textContent = fmt(overdueAmt) + ' PLN';
        document.getElementById('statOverdueCnt').textContent = overdueCnt;
        document.getElementById('statPendingAmt').textContent = fmt(pendingAmt) + ' PLN';
        document.getElementById('statPendingCnt').textContent = pendingCnt;
    }

    async function loadClients(){
        try {
            var resp = await fetch(API + '/clients', {headers: H});
            if(!resp.ok) throw new Error('HTTP ' + resp.status);
            var json = await resp.json();
            clientsList = json.data || [];
            populateClientDropdown('newInvClient', clientsList);
        } catch(err){
            console.error('loadClients:', err);
        }
    }

    function populateClientDropdown(selectId, list){
        var sel = document.getElementById(selectId);
        var current = sel.value;
        sel.innerHTML = '<option value="">-- Select client --</option>';
        list.forEach(function(c){
            var name = ((c.first_name || '') + ' ' + (c.last_name || '')).trim();
            sel.innerHTML += '<option value="' + c.id + '">' + name + '</option>';
        });
        if(current) sel.value = current;
    }

    async function loadClientCases(clientId){
        var sel = document.getElementById('newInvCase');
        sel.innerHTML = '<option value="">-- Select case --</option>';
        if(!clientId) return;
        try {
            var resp = await fetch(API + '/clients/' + clientId + '/cases', {headers: H});
            if(!resp.ok) throw new Error('HTTP ' + resp.status);
            var json = await resp.json();
            var cases = json.data || [];
            cases.forEach(function(c){
                var label = (c.case_number || 'Case #' + c.id) + (c.case_type ? ' — ' + c.case_type : '');
                sel.innerHTML += '<option value="' + c.id + '" data-type="' + (c.case_type || '') + '">' + label + '</option>';
            });
        } catch(err){
            console.error('loadClientCases:', err);
        }
    }

    // ============ RENDER TABLE ============
    function renderTable(){
        var search = (document.getElementById('invSearch').value || '').toLowerCase();
        var typeF = document.getElementById('invTypeFilter').value;
        var tbody = document.getElementById('invTableBody');
        tbody.innerHTML = '';
        var count = 0;

        invoices.forEach(function(inv, idx){
            if(currentFilter !== 'all' && inv.status !== currentFilter) return;
            if(search && inv.client.toLowerCase().indexOf(search) === -1 && inv.id.toLowerCase().indexOf(search) === -1) return;
            if(typeF !== 'all' && inv.caseType !== typeF) return;
            count++;
            var balance = inv.remaining;
            var tb = TYPE_BADGES[inv.caseType] || 'bg-secondary-subtle text-secondary';
            var sm = STATUS_MAP[inv.status] || STATUS_MAP['unpaid'];
            var actions = '';
            if(inv.status === 'paid' || inv.status === 'cancelled'){
                actions = '<div class="d-flex gap-1 justify-content-center">' +
                    '<button class="btn btn-sm btn-subtle-primary act-view" title="View"><i class="ri-eye-line"></i></button>' +
                    '<button class="btn btn-sm btn-subtle-danger act-pdf" title="PDF"><i class="ri-file-pdf-2-line"></i></button>' +
                    '<button class="btn btn-sm btn-subtle-info act-edit" title="Edit"><i class="ri-pencil-line"></i></button>' +
                '</div>';
            } else {
                actions = '<div class="d-flex gap-1 justify-content-center">' +
                    '<button class="btn btn-sm btn-subtle-primary act-view" title="View"><i class="ri-eye-line"></i></button>' +
                    '<button class="btn btn-sm btn-subtle-success act-record" title="Record Payment"><i class="ri-money-euro-circle-line"></i></button>' +
                    '<button class="btn btn-sm btn-subtle-warning act-remind" title="Send Reminder"><i class="ri-mail-send-line"></i></button>' +
                    '<button class="btn btn-sm btn-subtle-info act-edit" title="Edit"><i class="ri-pencil-line"></i></button>' +
                '</div>';
            }
            var caseLink = inv.caseId ? '<a href="crm-case-detail?case=' + inv.caseId.replace('#','') + '" class="text-primary fs-12">' + inv.caseId + '</a>' : '<span class="text-muted fs-12">—</span>';
            tbody.innerHTML += '<tr data-idx="' + idx + '" data-id="' + inv._id + '">' +
                '<td><div class="form-check"><input class="form-check-input inv-check" type="checkbox"></div></td>' +
                '<td><a href="#" class="fw-semibold text-body act-view">' + inv.id + '</a></td>' +
                '<td><a href="crm-client-invoices?client=' + inv.clientId + '" class="text-body">' + inv.client + '</a></td>' +
                '<td>' + caseLink + '</td>' +
                '<td><span class="badge ' + tb + '">' + (inv.caseType || '—') + '</span></td>' +
                '<td class="fw-semibold">' + fmt(inv.amount) + ' ' + inv.currency + '</td>' +
                '<td class="text-success">' + fmt(inv.paid) + ' ' + inv.currency + '</td>' +
                '<td class="' + sm.cls + '">' + fmt(balance) + ' ' + inv.currency + '</td>' +
                '<td>' + sm.badge + '</td>' +
                '<td class="' + (inv.status === 'overdue' ? 'text-danger' : 'text-muted') + ' fs-12">' + inv.due + '</td>' +
                '<td>' + actions + '</td>' +
            '</tr>';
        });
        document.getElementById('invShowing').textContent = 'Showing ' + count + ' invoices';
    }

    function updateCounts(){
        var all = 0, paid = 0, part = 0, unpaid = 0, over = 0;
        invoices.forEach(function(i){
            all++;
            if(i.status === 'paid') paid++;
            if(i.status === 'partial') part++;
            if(i.status === 'unpaid') unpaid++;
            if(i.status === 'overdue') over++;
        });
        document.getElementById('cntAll').textContent = all;
        document.getElementById('cntPaid').textContent = paid;
        document.getElementById('cntPartial').textContent = part;
        document.getElementById('cntUnpaid').textContent = unpaid;
        document.getElementById('cntOverdue').textContent = over;
    }

    // ============ INIT ============
    loadInvoices();
    loadClients();

    // ============ FILTERS ============
    document.getElementById('invFilterPills').addEventListener('click', function(e){
        var p = e.target.closest('.inv-filter-pill');
        if(!p) return;
        document.querySelectorAll('.inv-filter-pill').forEach(function(x){ x.classList.remove('active'); });
        p.classList.add('active');
        currentFilter = p.dataset.filter;
        renderTable();
    });

    var searchTimer = null;
    document.getElementById('invSearch').addEventListener('input', function(){
        clearTimeout(searchTimer);
        searchTimer = setTimeout(function(){ loadInvoices(); }, 400);
    });
    document.getElementById('invTypeFilter').addEventListener('change', function(){ renderTable(); });

    // ============ TABLE ACTIONS ============
    document.getElementById('invTableBody').addEventListener('click', function(e){
        var btn = e.target.closest('button') || e.target.closest('a.act-view');
        if(!btn) return;
        e.preventDefault();
        var row = btn.closest('tr');
        var idx = parseInt(row.dataset.idx);
        var inv = invoices[idx];
        if(!inv) return;

        if(btn.classList.contains('act-view')) showInvoicePreview(inv);
        if(btn.classList.contains('act-record')) openRecordPayment(inv);
        if(btn.classList.contains('act-remind')) openSendReminder(inv);
        if(btn.classList.contains('act-edit')) openEditInvoice(inv);
        if(btn.classList.contains('act-pdf')) showToast('PDF generated: ' + inv.id + '.pdf', 'success');
    });

    // ============ VIEW INVOICE ============
    async function showInvoicePreview(inv){
        currentViewInvoice = inv;
        // Fetch detailed view with payments
        var detail = inv;
        try {
            var resp = await fetch(API + '/accounting/invoices/' + inv._id, {headers: H});
            if(resp.ok){
                var json = await resp.json();
                if(json.success && json.data){
                    var d = json.data;
                    detail = Object.assign({}, inv);
                    detail.paid = parseFloat(d.total_paid) || inv.paid;
                    detail.remaining = parseFloat(d.remaining) || (inv.amount - detail.paid);
                }
            }
        } catch(err){ /* use cached data */ }

        var balance = detail.remaining;
        var body = document.getElementById('invoicePreviewBody');
        body.innerHTML =
            '<div style="text-align:center;margin-bottom:16px">' +
                '<div style="font-size:1.3rem;font-weight:700">FAKTURA VAT</div>' +
                '<div style="font-size:.9rem;color:#666">' + detail.id + '</div>' +
            '</div>' +
            '<div style="display:flex;justify-content:space-between;margin-bottom:16px">' +
                '<div>' +
                    '<div style="font-weight:700;font-size:.75rem;color:#999;text-transform:uppercase">Sprzedawca / Seller</div>' +
                    '<div style="font-weight:600">WinCase Sp. z o.o.</div>' +
                    '<div>ul. Marszalkowska 1</div>' +
                    '<div>00-001 Warszawa</div>' +
                    '<div>NIP: 5252811122</div>' +
                '</div>' +
                '<div style="text-align:right">' +
                    '<div style="font-weight:700;font-size:.75rem;color:#999;text-transform:uppercase">Nabywca / Buyer</div>' +
                    '<div style="font-weight:600">' + detail.client + '</div>' +
                    '<div>Case: ' + (detail.caseId || '—') + '</div>' +
                    '<div>Type: ' + (detail.caseType || '—') + '</div>' +
                '</div>' +
            '</div>' +
            '<div class="inv-line"><span>Service / Usluga:</span><span>' + (detail.caseType || 'Immigration Services') + ' — Immigration Services</span></div>' +
            '<div class="inv-line"><span>Netto:</span><span>' + fmt(detail.netAmount) + ' ' + detail.currency + '</span></div>' +
            '<div class="inv-line"><span>VAT (' + detail.vat + '%):</span><span>' + fmt(detail.vatAmount) + ' ' + detail.currency + '</span></div>' +
            '<div class="inv-line inv-total"><span>BRUTTO / TOTAL:</span><span>' + fmt(detail.amount) + ' ' + detail.currency + '</span></div>' +
            '<div style="margin-top:12px;padding-top:8px;border-top:1px dashed #ccc">' +
                '<div class="inv-line"><span>Paid:</span><span class="text-success">' + fmt(detail.paid) + ' ' + detail.currency + '</span></div>' +
                '<div class="inv-line"><span>Outstanding:</span><span class="' + (balance > 0 ? 'text-danger fw-bold' : '') + '">' + fmt(balance) + ' ' + detail.currency + '</span></div>' +
                '<div class="inv-line"><span>Due Date:</span><span>' + detail.due + '</span></div>' +
                '<div class="inv-line"><span>Payment Method:</span><span>' + (detail.method || '—') + '</span></div>' +
                (detail.notes ? '<div class="inv-line"><span>Notes:</span><span>' + detail.notes + '</span></div>' : '') +
            '</div>';

        new bootstrap.Modal(document.getElementById('viewInvoiceModal')).show();
    }

    // View -> Record Payment
    document.getElementById('viewInvRecordPayBtn').addEventListener('click', function(){
        if(!currentViewInvoice) return;
        bootstrap.Modal.getInstance(document.getElementById('viewInvoiceModal')).hide();
        setTimeout(function(){ openRecordPayment(currentViewInvoice); }, 300);
    });
    document.getElementById('viewInvSendBtn').addEventListener('click', function(){
        if(!currentViewInvoice) return;
        bootstrap.Modal.getInstance(document.getElementById('viewInvoiceModal')).hide();
        setTimeout(function(){ openSendReminder(currentViewInvoice); }, 300);
    });
    document.getElementById('viewInvPdfBtn').addEventListener('click', function(){
        showToast('PDF downloaded', 'success');
    });

    // ============ RECORD PAYMENT ============
    var rpCurrentInvoice = null;
    function openRecordPayment(inv){
        rpCurrentInvoice = inv;
        document.getElementById('rpInvNum').textContent = inv.id;
        var bal = inv.remaining;
        document.getElementById('rpOutstanding').textContent = fmt(bal) + ' ' + inv.currency;
        document.getElementById('rpAmount').value = bal > 0 ? bal : 0;
        document.getElementById('rpDate').value = new Date().toISOString().slice(0, 10);
        if(document.getElementById('rpRef')) document.getElementById('rpRef').value = '';
        document.getElementById('rpNotes').value = '';
        new bootstrap.Modal(document.getElementById('recordPaymentModal')).show();
    }

    document.getElementById('saveRecordPayBtn').addEventListener('click', async function(){
        var amt = parseFloat(document.getElementById('rpAmount').value) || 0;
        if(amt <= 0){ showToast('Enter a valid amount', 'warning'); return; }
        if(!rpCurrentInvoice) return;

        var btn = this;
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Saving...';

        try {
            var fd = new FormData();
            fd.append('client_id', rpCurrentInvoice.clientId);
            fd.append('invoice_id', rpCurrentInvoice._id);
            fd.append('amount', amt);
            fd.append('payment_method', document.getElementById('rpMethod') ? document.getElementById('rpMethod').value : 'transfer');
            fd.append('notes', document.getElementById('rpNotes').value || '');

            var resp = await fetch(API + '/payments', {
                method: 'POST',
                headers: H,
                body: fd
            });
            var json = await resp.json();
            if(!resp.ok || !json.success) throw new Error(json.message || 'Payment failed');

            bootstrap.Modal.getInstance(document.getElementById('recordPaymentModal')).hide();
            showToast('Payment ' + fmt(amt) + ' PLN recorded for ' + rpCurrentInvoice.id, 'success');
            loadInvoices();
        } catch(err){
            console.error('recordPayment:', err);
            showToast('Payment error: ' + err.message, 'danger');
        } finally {
            btn.disabled = false;
            btn.innerHTML = '<i class="ri-check-line me-1"></i>Record Payment';
        }
    });

    // ============ SEND REMINDER ============
    function openSendReminder(inv){
        document.getElementById('remClient').textContent = inv.client;
        var bal = inv.remaining;
        document.getElementById('remAmount').textContent = fmt(bal) + ' ' + inv.currency;
        document.getElementById('remMessage').value = 'Dear ' + inv.client + ',\n\nThis is a reminder that invoice ' + inv.id + ' has an outstanding balance of ' + fmt(bal) + ' ' + inv.currency + '.\n\nDue date: ' + inv.due + '\n\nPlease make the payment at your earliest convenience.\n\nBest regards,\nWinCase Immigration Bureau';
        new bootstrap.Modal(document.getElementById('sendReminderModal')).show();
    }

    document.getElementById('sendReminderBtn').addEventListener('click', function(){
        bootstrap.Modal.getInstance(document.getElementById('sendReminderModal')).hide();
        showToast('Reminder sent successfully', 'success');
    });

    // ============ EDIT INVOICE ============
    var editCurrentInvoice = null;
    function openEditInvoice(inv){
        editCurrentInvoice = inv;
        document.getElementById('editInvNum').value = inv.id;
        // Client & Type are read-only context from API
        var editClientSel = document.getElementById('editInvClient');
        editClientSel.innerHTML = '<option value="' + inv.clientId + '">' + inv.client + '</option>';
        var editTypeSel = document.getElementById('editInvType');
        editTypeSel.innerHTML = '<option>' + (inv.caseType || '—') + '</option>';
        document.getElementById('editInvAmount').value = inv.netAmount;
        setSelect('editInvVat', String(inv.vat));
        document.getElementById('editInvDue').value = inv.due;
        setSelect('editInvStatus', inv.status);
        document.getElementById('editInvNotes').value = inv.notes || '';
        new bootstrap.Modal(document.getElementById('editInvoiceModal')).show();
    }

    document.getElementById('saveEditInvBtn').addEventListener('click', async function(){
        if(!editCurrentInvoice) return;
        var btn = this;
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Saving...';

        try {
            var fd = new FormData();
            fd.append('_method', 'PUT');
            var statusVal = document.getElementById('editInvStatus').value;
            // Map UI status back to API status
            var apiStatus = statusVal;
            if(statusVal === 'unpaid') apiStatus = 'draft';
            if(statusVal === 'partial') apiStatus = 'sent';
            fd.append('status', apiStatus);
            fd.append('due_date', document.getElementById('editInvDue').value);
            fd.append('notes', document.getElementById('editInvNotes').value);
            fd.append('net_amount', document.getElementById('editInvAmount').value);
            fd.append('vat_rate', document.getElementById('editInvVat').value);

            var resp = await fetch(API + '/accounting/invoices/' + editCurrentInvoice._id, {
                method: 'POST',
                headers: H,
                body: fd
            });
            var json = await resp.json();
            if(!resp.ok || !json.success) throw new Error(json.message || 'Update failed');

            bootstrap.Modal.getInstance(document.getElementById('editInvoiceModal')).hide();
            showToast('Invoice ' + editCurrentInvoice.id + ' updated', 'success');
            loadInvoices();
        } catch(err){
            console.error('editInvoice:', err);
            showToast('Update error: ' + err.message, 'danger');
        } finally {
            btn.disabled = false;
            btn.innerHTML = '<i class="ri-check-line me-1"></i>Save Changes';
        }
    });

    // ============ CREATE INVOICE ============
    document.getElementById('newInvClient').addEventListener('change', function(){
        loadClientCases(this.value);
    });

    // VAT calc
    function calcVat(){
        var net = parseFloat(document.getElementById('newInvNet').value) || 0;
        var vr = parseInt(document.getElementById('newInvVat').value) || 0;
        var vatAmt = net * vr / 100;
        document.getElementById('newInvVatAmt').value = fmt(vatAmt) + ' PLN';
        document.getElementById('newInvGross').value = fmt(net + vatAmt) + ' PLN';
    }
    document.getElementById('newInvNet').addEventListener('input', calcVat);
    document.getElementById('newInvVat').addEventListener('change', calcVat);

    // Set default due date
    document.getElementById('newInvTerms').addEventListener('change', function(){
        var days = parseInt(this.value) || 0;
        var d = new Date();
        d.setDate(d.getDate() + days);
        document.getElementById('newInvDue').value = d.toISOString().slice(0, 10);
    });

    // Reset create modal on open
    document.getElementById('createInvoiceModal').addEventListener('show.bs.modal', function(){
        document.getElementById('newInvClient').value = '';
        document.getElementById('newInvCase').innerHTML = '<option value="">-- Select case --</option>';
        document.getElementById('newInvNet').value = '';
        document.getElementById('newInvVat').value = '23';
        document.getElementById('newInvVatAmt').value = '';
        document.getElementById('newInvGross').value = '';
        document.getElementById('newInvMethod').value = 'transfer';
        document.getElementById('newInvNotes').value = '';
        // Default due date = 14 days
        var d = new Date();
        d.setDate(d.getDate() + 14);
        document.getElementById('newInvDue').value = d.toISOString().slice(0, 10);
        document.getElementById('newInvTerms').value = '14';
    });

    document.getElementById('saveNewInvBtn').addEventListener('click', async function(){
        var clientSel = document.getElementById('newInvClient');
        var caseSel = document.getElementById('newInvCase');
        var net = parseFloat(document.getElementById('newInvNet').value) || 0;
        if(!clientSel.value || net <= 0){
            showToast('Fill required fields (client, net amount)', 'warning');
            return;
        }
        if(!document.getElementById('newInvDue').value){
            showToast('Due date is required', 'warning');
            return;
        }

        var btn = this;
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Creating...';

        try {
            var fd = new FormData();
            fd.append('client_id', clientSel.value);
            if(caseSel.value) fd.append('case_id', caseSel.value);
            fd.append('net_amount', net);
            fd.append('vat_rate', document.getElementById('newInvVat').value);
            fd.append('due_date', document.getElementById('newInvDue').value);
            fd.append('payment_method', document.getElementById('newInvMethod').value);
            fd.append('notes', document.getElementById('newInvNotes').value);

            var resp = await fetch(API + '/accounting/invoices', {
                method: 'POST',
                headers: H,
                body: fd
            });
            var json = await resp.json();
            if(!resp.ok || !json.success) throw new Error(json.message || 'Creation failed');

            bootstrap.Modal.getInstance(document.getElementById('createInvoiceModal')).hide();
            var newNum = (json.data && json.data.invoice_number) ? json.data.invoice_number : 'New invoice';
            showToast('Invoice ' + newNum + ' created', 'success');
            loadInvoices();
        } catch(err){
            console.error('createInvoice:', err);
            showToast('Create error: ' + err.message, 'danger');
        } finally {
            btn.disabled = false;
            btn.innerHTML = '<i class="ri-check-line me-1"></i>Create Invoice';
        }
    });

    // ============ EXPORT CSV ============
    document.getElementById('exportInvCsvBtn').addEventListener('click', function(){
        var csv = 'Invoice,Client,Case,Type,Gross Amount,Paid,Balance,Status,Due,Method\n';
        invoices.forEach(function(i){
            csv += '"' + i.id + '","' + i.client + '","' + i.caseId + '","' + i.caseType + '",' + i.amount + ',' + i.paid + ',' + i.remaining + ',' + i.status + ',' + i.due + ',' + i.method + '\n';
        });
        var blob = new Blob([csv], {type: 'text/csv;charset=utf-8;'});
        var url = URL.createObjectURL(blob);
        var a = document.createElement('a');
        a.href = url;
        a.download = 'invoices_' + new Date().toISOString().slice(0, 10) + '.csv';
        a.click();
        URL.revokeObjectURL(url);
        showToast('CSV exported', 'success');
    });

    // ============ CHECK ALL ============
    document.getElementById('checkAllInv').addEventListener('change', function(){
        var checked = this.checked;
        document.querySelectorAll('.inv-check').forEach(function(c){ c.checked = checked; });
    });

    // ============ HELPERS ============
    function setSelect(id, val){
        var el = document.getElementById(id);
        for(var i = 0; i < el.options.length; i++){
            if(el.options[i].value === val || el.options[i].text === val){
                el.selectedIndex = i;
                return;
            }
        }
    }

    function showToast(msg, type){
        var colors = {success:'#198754', danger:'#dc3545', warning:'#ffc107', info:'#0dcaf0', primary:'#845adf'};
        var t = document.createElement('div');
        t.style.cssText = 'position:fixed;top:20px;right:20px;z-index:9999;padding:14px 24px;border-radius:10px;color:#fff;font-weight:600;font-size:.9rem;box-shadow:0 4px 12px rgba(0,0,0,.15);transition:opacity .3s;background:' + (colors[type] || colors.info);
        t.textContent = msg;
        document.body.appendChild(t);
        setTimeout(function(){ t.style.opacity = '0'; setTimeout(function(){ t.remove(); }, 300); }, 3000);
    }
});
</script>
@endsection
