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
                <select class="form-select" id="editInvClient">
                    <option>Oleksandr Petrov</option><option>Maria Ivanova</option><option>Viktor Kovalenko</option>
                    <option>Anna Shevchenko</option><option>Dmytro Bondarenko</option><option>Iryna Melnyk</option>
                    <option>Pavlo Tkachenko</option><option>Natalia Moroz</option><option>Anastasia Nowak</option>
                    <option>Dmytro Shevchenko</option><option>Tetiana Sydorenko</option>
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
                    <option value="cl1">Oleksandr Petrov</option><option value="cl2">Maria Ivanova</option>
                    <option value="cl3">Viktor Kovalenko</option><option value="cl4">Anna Shevchenko</option>
                    <option value="cl5">Dmytro Bondarenko</option><option value="cl6">Iryna Melnyk</option>
                    <option value="cl7">Pavlo Tkachenko</option><option value="cl8">Natalia Moroz</option>
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

    // ============ DATA ============
    const CLIENT_CASES = {
        cl1:[{id:'#WC-0189',type:'Temporary Residence'},{id:'#WC-0201',type:'Work Permit'}],
        cl2:[{id:'#WC-0188',type:'Permanent Residence'}],
        cl3:[{id:'#WC-0185',type:'Work Permit'},{id:'#WC-0199',type:'Blue Card EU'}],
        cl4:[{id:'#WC-0190',type:'Family Reunification'}],
        cl5:[{id:'#WC-0191',type:'Temporary Residence'}],
        cl6:[{id:'#WC-0192',type:'Citizenship'}],
        cl7:[{id:'#WC-0193',type:'Temporary Residence'}],
        cl8:[{id:'#WC-0194',type:'Appeal'}]
    };

    const TYPE_BADGES = {
        'Temporary Residence':'bg-primary-subtle text-primary','Permanent Residence':'bg-info-subtle text-info',
        'Work Permit':'bg-dark-subtle text-dark','Blue Card EU':'bg-secondary-subtle text-secondary',
        'Citizenship':'bg-success-subtle text-success','Family Reunification':'bg-warning-subtle text-warning',
        'Appeal':'bg-danger-subtle text-danger'
    };

    const STATUS_MAP = {
        paid:{badge:'<span class="badge bg-success-subtle text-success">Fully Paid</span>',cls:'text-muted'},
        partial:{badge:'<span class="badge bg-warning-subtle text-warning">Partially Paid</span>',cls:'text-danger fw-semibold'},
        unpaid:{badge:'<span class="badge bg-danger-subtle text-danger">Unpaid</span>',cls:'text-danger fw-semibold'},
        overdue:{badge:'<span class="badge bg-dark-subtle text-dark">Overdue</span>',cls:'text-danger fw-semibold'}
    };

    let invoices = [
        {id:'FV/2026/03/091',client:'Oleksandr Petrov',clientId:'cl1',caseId:'#WC-0189',caseType:'Temporary Residence',amount:4800,paid:4800,vat:23,status:'paid',due:'2026-02-28',method:'transfer',notes:'Full payment received'},
        {id:'FV/2026/03/090',client:'Maria Ivanova',clientId:'cl2',caseId:'#WC-0188',caseType:'Permanent Residence',amount:7000,paid:3500,vat:23,status:'partial',due:'2026-03-15',method:'card',notes:'Installment plan: 2 payments'},
        {id:'FV/2026/03/089',client:'Viktor Kovalenko',clientId:'cl3',caseId:'#WC-0185',caseType:'Work Permit',amount:4800,paid:1800,vat:23,status:'overdue',due:'2026-02-10',method:'transfer',notes:'Overdue - sent 2 reminders'},
        {id:'FV/2026/03/088',client:'Anastasia Nowak',clientId:'cl4',caseId:'#WC-0190',caseType:'Family Reunification',amount:3000,paid:0,vat:23,status:'unpaid',due:'2026-03-20',method:'blik',notes:'New client - first invoice'},
        {id:'FV/2026/03/087',client:'Anna Shevchenko',clientId:'cl4',caseId:'#WC-0190',caseType:'Family Reunification',amount:5500,paid:5500,vat:23,status:'paid',due:'2026-02-25',method:'p24',notes:''},
        {id:'FV/2026/02/086',client:'Dmytro Bondarenko',clientId:'cl5',caseId:'#WC-0191',caseType:'Temporary Residence',amount:3800,paid:1900,vat:23,status:'partial',due:'2026-03-10',method:'card',notes:'50% deposit received'},
        {id:'FV/2026/02/085',client:'Iryna Melnyk',clientId:'cl6',caseId:'#WC-0192',caseType:'Citizenship',amount:8500,paid:8500,vat:23,status:'paid',due:'2026-02-20',method:'transfer',notes:'Premium package'},
        {id:'FV/2026/02/084',client:'Pavlo Tkachenko',clientId:'cl7',caseId:'#WC-0193',caseType:'Temporary Residence',amount:4200,paid:4200,vat:23,status:'paid',due:'2026-02-15',method:'cash',notes:'Renewal application'},
        {id:'FV/2026/02/083',client:'Natalia Moroz',clientId:'cl8',caseId:'#WC-0194',caseType:'Appeal',amount:6000,paid:3000,vat:23,status:'partial',due:'2026-03-05',method:'wise',notes:'Appeal case - installments'},
        {id:'FV/2026/02/082',client:'Viktor Kovalenko',clientId:'cl3',caseId:'#WC-0199',caseType:'Blue Card EU',amount:5200,paid:5200,vat:23,status:'paid',due:'2026-02-12',method:'revolut',notes:'Blue Card application'},
        {id:'FV/2026/02/081',client:'Dmytro Shevchenko',clientId:'cl5',caseId:'#WC-0191',caseType:'Temporary Residence',amount:3500,paid:0,vat:23,status:'overdue',due:'2026-02-01',method:'transfer',notes:'3 reminders sent'},
        {id:'FV/2026/01/080',client:'Tetiana Sydorenko',clientId:'cl7',caseId:'#WC-0193',caseType:'Temporary Residence',amount:4000,paid:3000,vat:23,status:'partial',due:'2026-02-28',method:'payu',notes:'Remaining 1000 PLN due'},
        {id:'FV/2026/01/079',client:'Oleksandr Petrov',clientId:'cl1',caseId:'#WC-0201',caseType:'Work Permit',amount:3600,paid:3600,vat:23,status:'paid',due:'2026-01-31',method:'card',notes:'Work permit processing'},
        {id:'FV/2026/01/078',client:'Maria Ivanova',clientId:'cl2',caseId:'#WC-0188',caseType:'Permanent Residence',amount:2500,paid:2500,vat:23,status:'paid',due:'2026-01-20',method:'cash',notes:'Document preparation fee'}
    ];

    let invCounter = 92;
    let currentFilter = 'all';

    function fmt(n){ return n.toLocaleString('pl-PL',{minimumFractionDigits:2,maximumFractionDigits:2}); }

    // ============ RENDER ============
    function renderTable(){
        const search = (document.getElementById('invSearch').value||'').toLowerCase();
        const typeF = document.getElementById('invTypeFilter').value;
        const tbody = document.getElementById('invTableBody');
        tbody.innerHTML = '';
        let count = 0;

        invoices.forEach((inv,idx) => {
            if(currentFilter !== 'all' && inv.status !== currentFilter) return;
            if(search && !inv.client.toLowerCase().includes(search) && !inv.id.toLowerCase().includes(search)) return;
            if(typeF !== 'all' && inv.caseType !== typeF) return;
            count++;
            const balance = inv.amount - inv.paid;
            const tb = TYPE_BADGES[inv.caseType] || 'bg-secondary-subtle text-secondary';
            const sm = STATUS_MAP[inv.status];
            let actions = '';
            if(inv.status === 'paid'){
                actions = `<div class="d-flex gap-1 justify-content-center">
                    <button class="btn btn-sm btn-subtle-primary act-view" title="View"><i class="ri-eye-line"></i></button>
                    <button class="btn btn-sm btn-subtle-danger act-pdf" title="PDF"><i class="ri-file-pdf-2-line"></i></button>
                    <button class="btn btn-sm btn-subtle-info act-edit" title="Edit"><i class="ri-pencil-line"></i></button>
                </div>`;
            } else {
                actions = `<div class="d-flex gap-1 justify-content-center">
                    <button class="btn btn-sm btn-subtle-primary act-view" title="View"><i class="ri-eye-line"></i></button>
                    <button class="btn btn-sm btn-subtle-success act-record" title="Record Payment"><i class="ri-money-euro-circle-line"></i></button>
                    <button class="btn btn-sm btn-subtle-warning act-remind" title="Send Reminder"><i class="ri-mail-send-line"></i></button>
                    <button class="btn btn-sm btn-subtle-info act-edit" title="Edit"><i class="ri-pencil-line"></i></button>
                </div>`;
            }
            tbody.innerHTML += `<tr data-idx="${idx}">
                <td><div class="form-check"><input class="form-check-input inv-check" type="checkbox"></div></td>
                <td><a href="#" class="fw-semibold text-body act-view">${inv.id}</a></td>
                <td><a href="crm-client-invoices?client=${inv.clientId}" class="text-body">${inv.client}</a></td>
                <td><a href="crm-case-detail?case=${inv.caseId.replace('#','')}" class="text-primary fs-12">${inv.caseId}</a></td>
                <td><span class="badge ${tb}">${inv.caseType}</span></td>
                <td class="fw-semibold">${fmt(inv.amount)} PLN</td>
                <td class="text-success">${fmt(inv.paid)} PLN</td>
                <td class="${sm.cls}">${fmt(balance)} PLN</td>
                <td>${sm.badge}</td>
                <td class="${inv.status==='overdue'?'text-danger':'text-muted'} fs-12">${inv.due}</td>
                <td>${actions}</td>
            </tr>`;
        });
        document.getElementById('invShowing').textContent = `Showing ${count} invoices`;
    }

    function updateCounts(){
        let all=0,paid=0,part=0,unpaid=0,over=0;
        invoices.forEach(i=>{all++;if(i.status==='paid')paid++;if(i.status==='partial')part++;if(i.status==='unpaid')unpaid++;if(i.status==='overdue')over++;});
        document.getElementById('cntAll').textContent=all;
        document.getElementById('cntPaid').textContent=paid;
        document.getElementById('cntPartial').textContent=part;
        document.getElementById('cntUnpaid').textContent=unpaid;
        document.getElementById('cntOverdue').textContent=over;
    }

    renderTable(); updateCounts();

    // ============ FILTERS ============
    document.getElementById('invFilterPills').addEventListener('click',function(e){
        const p=e.target.closest('.inv-filter-pill'); if(!p)return;
        document.querySelectorAll('.inv-filter-pill').forEach(x=>x.classList.remove('active'));
        p.classList.add('active'); currentFilter=p.dataset.filter; renderTable();
    });
    document.getElementById('invSearch').addEventListener('input',()=>renderTable());
    document.getElementById('invTypeFilter').addEventListener('change',()=>renderTable());

    // ============ TABLE ACTIONS ============
    document.getElementById('invTableBody').addEventListener('click',function(e){
        const btn = e.target.closest('button') || e.target.closest('a.act-view');
        if(!btn) return; e.preventDefault();
        const row = btn.closest('tr');
        const idx = parseInt(row.dataset.idx);
        const inv = invoices[idx];
        if(!inv) return;

        if(btn.classList.contains('act-view')){
            showInvoicePreview(inv);
        }
        if(btn.classList.contains('act-record')){
            openRecordPayment(inv, idx);
        }
        if(btn.classList.contains('act-remind')){
            openSendReminder(inv);
        }
        if(btn.classList.contains('act-edit')){
            openEditInvoice(inv, idx);
        }
        if(btn.classList.contains('act-pdf')){
            showToast('PDF generated: '+inv.id+'.pdf','success');
        }
    });

    // ============ VIEW INVOICE ============
    function showInvoicePreview(inv){
        const balance = inv.amount - inv.paid;
        const vatAmt = inv.amount * inv.vat / 100;
        const gross = inv.amount + vatAmt;
        const body = document.getElementById('invoicePreviewBody');
        body.innerHTML = `
            <div style="text-align:center;margin-bottom:16px">
                <div style="font-size:1.3rem;font-weight:700">FAKTURA VAT</div>
                <div style="font-size:.9rem;color:#666">${inv.id}</div>
            </div>
            <div style="display:flex;justify-content:space-between;margin-bottom:16px">
                <div>
                    <div style="font-weight:700;font-size:.75rem;color:#999;text-transform:uppercase">Sprzedawca / Seller</div>
                    <div style="font-weight:600">WinCase Sp. z o.o.</div>
                    <div>ul. Marszalkowska 1</div>
                    <div>00-001 Warszawa</div>
                    <div>NIP: 5252811122</div>
                </div>
                <div style="text-align:right">
                    <div style="font-weight:700;font-size:.75rem;color:#999;text-transform:uppercase">Nabywca / Buyer</div>
                    <div style="font-weight:600">${inv.client}</div>
                    <div>Case: ${inv.caseId}</div>
                    <div>Type: ${inv.caseType}</div>
                </div>
            </div>
            <div class="inv-line"><span>Service / Usluga:</span><span>${inv.caseType} — Immigration Services</span></div>
            <div class="inv-line"><span>Netto:</span><span>${fmt(inv.amount)} PLN</span></div>
            <div class="inv-line"><span>VAT (${inv.vat}%):</span><span>${fmt(vatAmt)} PLN</span></div>
            <div class="inv-line inv-total"><span>BRUTTO / TOTAL:</span><span>${fmt(gross)} PLN</span></div>
            <div style="margin-top:12px;padding-top:8px;border-top:1px dashed #ccc">
                <div class="inv-line"><span>Paid:</span><span class="text-success">${fmt(inv.paid)} PLN</span></div>
                <div class="inv-line"><span>Outstanding:</span><span class="${balance>0?'text-danger fw-bold':''}">${fmt(balance)} PLN</span></div>
                <div class="inv-line"><span>Due Date:</span><span>${inv.due}</span></div>
                <div class="inv-line"><span>Payment Method:</span><span>${inv.method}</span></div>
                ${inv.notes?`<div class="inv-line"><span>Notes:</span><span>${inv.notes}</span></div>`:''}
            </div>
        `;
        document.getElementById('viewInvoiceModal').dataset.idx = invoices.indexOf(inv);
        new bootstrap.Modal(document.getElementById('viewInvoiceModal')).show();
    }

    // View → Record Payment
    document.getElementById('viewInvRecordPayBtn').addEventListener('click',function(){
        const idx = parseInt(document.getElementById('viewInvoiceModal').dataset.idx);
        const inv = invoices[idx]; if(!inv) return;
        bootstrap.Modal.getInstance(document.getElementById('viewInvoiceModal')).hide();
        setTimeout(()=>openRecordPayment(inv,idx),300);
    });
    document.getElementById('viewInvSendBtn').addEventListener('click',function(){
        const idx = parseInt(document.getElementById('viewInvoiceModal').dataset.idx);
        const inv = invoices[idx]; if(!inv) return;
        bootstrap.Modal.getInstance(document.getElementById('viewInvoiceModal')).hide();
        setTimeout(()=>openSendReminder(inv),300);
    });
    document.getElementById('viewInvPdfBtn').addEventListener('click',function(){
        showToast('PDF downloaded','success');
    });

    // ============ RECORD PAYMENT ============
    let rpCurrentIdx = -1;
    function openRecordPayment(inv, idx){
        rpCurrentIdx = idx;
        document.getElementById('rpInvNum').textContent = inv.id;
        const bal = inv.amount - inv.paid;
        document.getElementById('rpOutstanding').textContent = fmt(bal)+' PLN';
        document.getElementById('rpAmount').value = bal;
        document.getElementById('rpDate').value = new Date().toISOString().slice(0,10);
        document.getElementById('rpRef').value = '';
        document.getElementById('rpNotes').value = '';
        new bootstrap.Modal(document.getElementById('recordPaymentModal')).show();
    }

    document.getElementById('saveRecordPayBtn').addEventListener('click',function(){
        const amt = parseFloat(document.getElementById('rpAmount').value)||0;
        if(amt<=0){showToast('Enter amount','warning');return;}
        const inv = invoices[rpCurrentIdx];
        if(!inv) return;
        inv.paid = Math.min(inv.paid + amt, inv.amount);
        if(inv.paid >= inv.amount) inv.status = 'paid';
        else inv.status = 'partial';
        renderTable(); updateCounts();
        bootstrap.Modal.getInstance(document.getElementById('recordPaymentModal')).hide();
        showToast(`Payment ${fmt(amt)} PLN recorded for ${inv.id}`,'success');
    });

    // ============ SEND REMINDER ============
    function openSendReminder(inv){
        document.getElementById('remClient').textContent = inv.client;
        const bal = inv.amount - inv.paid;
        document.getElementById('remAmount').textContent = fmt(bal)+' PLN';
        document.getElementById('remMessage').value = `Dear ${inv.client},\n\nThis is a reminder that invoice ${inv.id} has an outstanding balance of ${fmt(bal)} PLN.\n\nDue date: ${inv.due}\n\nPlease make the payment at your earliest convenience.\n\nBest regards,\nWinCase Immigration Bureau`;
        new bootstrap.Modal(document.getElementById('sendReminderModal')).show();
    }

    document.getElementById('sendReminderBtn').addEventListener('click',function(){
        bootstrap.Modal.getInstance(document.getElementById('sendReminderModal')).hide();
        showToast('Reminder sent successfully','success');
    });

    // ============ EDIT INVOICE ============
    let editCurrentIdx = -1;
    function openEditInvoice(inv, idx){
        editCurrentIdx = idx;
        document.getElementById('editInvNum').value = inv.id;
        setSelect('editInvClient', inv.client);
        setSelect('editInvType', inv.caseType);
        document.getElementById('editInvAmount').value = inv.amount;
        setSelect('editInvVat', inv.vat.toString());
        document.getElementById('editInvDue').value = inv.due;
        setSelect('editInvStatus', inv.status);
        document.getElementById('editInvNotes').value = inv.notes || '';
        new bootstrap.Modal(document.getElementById('editInvoiceModal')).show();
    }

    document.getElementById('saveEditInvBtn').addEventListener('click',function(){
        const inv = invoices[editCurrentIdx]; if(!inv) return;
        inv.client = document.getElementById('editInvClient').value;
        inv.caseType = document.getElementById('editInvType').value;
        inv.amount = parseFloat(document.getElementById('editInvAmount').value)||0;
        inv.vat = parseInt(document.getElementById('editInvVat').value)||23;
        inv.due = document.getElementById('editInvDue').value;
        inv.status = document.getElementById('editInvStatus').value;
        inv.notes = document.getElementById('editInvNotes').value;
        if(inv.paid >= inv.amount) inv.status = 'paid';
        renderTable(); updateCounts();
        bootstrap.Modal.getInstance(document.getElementById('editInvoiceModal')).hide();
        showToast('Invoice '+inv.id+' updated','success');
    });

    // ============ CREATE INVOICE ============
    // Client → Case link
    document.getElementById('newInvClient').addEventListener('change',function(){
        const sel = document.getElementById('newInvCase');
        sel.innerHTML = '<option value="">-- Select case --</option>';
        const cl = this.value;
        if(cl && CLIENT_CASES[cl]){
            CLIENT_CASES[cl].forEach(c=>{sel.innerHTML+=`<option value="${c.id}" data-type="${c.type}">${c.id} — ${c.type}</option>`;});
        }
    });
    // VAT calc
    function calcVat(){
        const net = parseFloat(document.getElementById('newInvNet').value)||0;
        const vr = parseInt(document.getElementById('newInvVat').value)||0;
        const vatAmt = net * vr / 100;
        document.getElementById('newInvVatAmt').value = fmt(vatAmt)+' PLN';
        document.getElementById('newInvGross').value = fmt(net+vatAmt)+' PLN';
    }
    document.getElementById('newInvNet').addEventListener('input',calcVat);
    document.getElementById('newInvVat').addEventListener('change',calcVat);

    document.getElementById('saveNewInvBtn').addEventListener('click',function(){
        const client = document.getElementById('newInvClient');
        const caseEl = document.getElementById('newInvCase');
        const net = parseFloat(document.getElementById('newInvNet').value)||0;
        if(!client.value||net<=0){showToast('Fill required fields','warning');return;}
        const clientName = client.options[client.selectedIndex].text;
        const caseId = caseEl.value || '—';
        const caseOpt = caseEl.options[caseEl.selectedIndex];
        const caseType = caseOpt && caseOpt.dataset.type ? caseOpt.dataset.type : 'Temporary Residence';
        const vat = parseInt(document.getElementById('newInvVat').value)||23;
        const due = document.getElementById('newInvDue').value || new Date().toISOString().slice(0,10);
        const method = document.getElementById('newInvMethod').value;
        const notes = document.getElementById('newInvNotes').value;

        const now = new Date();
        const invId = `FV/${now.getFullYear()}/${String(now.getMonth()+1).padStart(2,'0')}/${String(invCounter++).padStart(3,'0')}`;
        invoices.unshift({
            id:invId,client:clientName,clientId:client.value,caseId:caseId,caseType:caseType,
            amount:net,paid:0,vat:vat,status:'unpaid',due:due,method:method,notes:notes
        });
        renderTable(); updateCounts();
        bootstrap.Modal.getInstance(document.getElementById('createInvoiceModal')).hide();
        showToast('Invoice '+invId+' created','success');
    });

    // ============ EXPORT CSV ============
    document.getElementById('exportInvCsvBtn').addEventListener('click',function(){
        let csv = 'Invoice,Client,Case,Type,Amount,Paid,Balance,Status,Due,Method\n';
        invoices.forEach(i=>{csv+=`${i.id},"${i.client}",${i.caseId},${i.caseType},${i.amount},${i.paid},${i.amount-i.paid},${i.status},${i.due},${i.method}\n`;});
        const blob = new Blob([csv],{type:'text/csv'});
        const url = URL.createObjectURL(blob);
        const a = document.createElement('a');a.href=url;a.download='invoices_'+new Date().toISOString().slice(0,10)+'.csv';a.click();URL.revokeObjectURL(url);
        showToast('CSV exported','success');
    });

    // ============ CHECK ALL ============
    document.getElementById('checkAllInv').addEventListener('change',function(){
        document.querySelectorAll('.inv-check').forEach(c=>c.checked=this.checked);
    });

    // ============ HELPERS ============
    function setSelect(id,val){
        const el=document.getElementById(id);
        for(let i=0;i<el.options.length;i++){if(el.options[i].value===val||el.options[i].text===val){el.selectedIndex=i;return;}}
    }

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
