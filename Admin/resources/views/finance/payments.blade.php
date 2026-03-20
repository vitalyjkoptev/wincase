@extends('partials.layouts.master')
@section('title', 'Payments | WinCase CRM')
@section('sub-title', 'Payments')
@section('sub-title-lang', 'wc-payments')
@section('pagetitle', 'Finance')
@section('pagetitle-lang', 'wc-finance')
@section('buttonTitle', 'Record Payment')
@section('buttonTitle-lang', 'wc-record-payment')
@section('modalTarget', 'addPaymentModal')

@section('content')
<style>
    .pay-filter-pill{cursor:pointer;padding:6px 16px;border-radius:20px;font-size:.8rem;font-weight:600;border:1px solid #e9ecef;background:#fff;transition:.2s;display:inline-flex;align-items:center;gap:4px}
    .pay-filter-pill:hover{border-color:#845adf}
    .pay-filter-pill.active{background:#845adf;color:#fff;border-color:#845adf}
    .method-badge{display:inline-flex;align-items:center;gap:4px;padding:4px 10px;border-radius:6px;font-size:.75rem;font-weight:600}
    .receipt-box{font-family:'Courier New',monospace;font-size:.85rem;background:#fafafa;border:1px solid #dee2e6;border-radius:8px;padding:20px}
    .receipt-line{display:flex;justify-content:space-between;padding:2px 0}
    .receipt-line.total{font-weight:700;font-size:1.05rem;border-top:2px dashed #dee2e6;padding-top:8px;margin-top:6px}
</style>

<!-- ============ STAT CARDS ============ -->
<div class="row">
    <div class="col-xl-3 col-md-6">
        <div class="card card-h-100"><div class="card-body">
            <div class="d-flex align-items-center">
                <div class="flex-grow-1">
                    <p class="text-uppercase fw-medium text-muted text-truncate mb-2 fs-12">Total Received</p>
                    <h4 class="fs-22 fw-semibold mb-0">187 450,00 PLN</h4>
                    <p class="text-muted mt-1 mb-0"><span class="badge bg-success-subtle text-success me-1"><i class="ri-arrow-up-s-line"></i> 12.5%</span> vs last month</p>
                </div>
                <div class="avatar-sm flex-shrink-0"><span class="avatar-title bg-primary-subtle text-primary rounded-circle fs-3"><i class="ri-wallet-line"></i></span></div>
            </div>
        </div></div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card card-h-100"><div class="card-body">
            <div class="d-flex align-items-center">
                <div class="flex-grow-1">
                    <p class="text-uppercase fw-medium text-muted text-truncate mb-2 fs-12">This Month</p>
                    <h4 class="fs-22 fw-semibold mb-0">34 200,00 PLN</h4>
                    <p class="text-muted mt-1 mb-0"><span class="badge bg-success-subtle text-success me-1"><i class="ri-arrow-up-s-line"></i> 8.3%</span> vs previous</p>
                </div>
                <div class="avatar-sm flex-shrink-0"><span class="avatar-title bg-info-subtle text-info rounded-circle fs-3"><i class="ri-calendar-check-line"></i></span></div>
            </div>
        </div></div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card card-h-100"><div class="card-body">
            <div class="d-flex align-items-center">
                <div class="flex-grow-1">
                    <p class="text-uppercase fw-medium text-muted text-truncate mb-2 fs-12">Pending</p>
                    <h4 class="fs-22 fw-semibold mb-0">12 800,00 PLN</h4>
                    <p class="text-muted mt-1 mb-0"><span class="badge bg-warning-subtle text-warning me-1">7 payments</span> awaiting</p>
                </div>
                <div class="avatar-sm flex-shrink-0"><span class="avatar-title bg-warning-subtle text-warning rounded-circle fs-3"><i class="ri-time-line"></i></span></div>
            </div>
        </div></div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card card-h-100"><div class="card-body">
            <div class="d-flex align-items-center">
                <div class="flex-grow-1">
                    <p class="text-uppercase fw-medium text-muted text-truncate mb-2 fs-12">Refunded</p>
                    <h4 class="fs-22 fw-semibold mb-0">3 200,00 PLN</h4>
                    <p class="text-muted mt-1 mb-0"><span class="badge bg-info-subtle text-info me-1">2 refunds</span> processed</p>
                </div>
                <div class="avatar-sm flex-shrink-0"><span class="avatar-title bg-danger-subtle text-danger rounded-circle fs-3"><i class="ri-refund-2-line"></i></span></div>
            </div>
        </div></div>
    </div>
</div>

<!-- ============ PAYMENTS TABLE ============ -->
<div class="card">
    <div class="card-header">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
            <h5 class="card-title mb-0">All Payments</h5>
            <div class="d-flex gap-2">
                <button class="btn btn-sm btn-outline-primary" id="exportPayCsvBtn"><i class="ri-download-line me-1"></i>Export CSV</button>
                <a href="finance-pos" class="btn btn-sm btn-outline-info"><i class="ri-bank-card-line me-1"></i>POS Terminal</a>
                <a href="finance-invoices" class="btn btn-sm btn-outline-secondary"><i class="ri-file-list-3-line me-1"></i>Invoices</a>
            </div>
        </div>
        <div class="d-flex gap-1 mt-2 flex-wrap" id="payFilterPills">
            <span class="pay-filter-pill active" data-filter="all">All <span class="badge bg-secondary ms-1" id="pCntAll">0</span></span>
            <span class="pay-filter-pill" data-filter="completed">Completed <span class="badge bg-success ms-1" id="pCntComp">0</span></span>
            <span class="pay-filter-pill" data-filter="pending">Pending <span class="badge bg-warning ms-1" id="pCntPend">0</span></span>
            <span class="pay-filter-pill" data-filter="failed">Failed <span class="badge bg-danger ms-1" id="pCntFail">0</span></span>
            <span class="pay-filter-pill" data-filter="refunded">Refunded <span class="badge bg-info ms-1" id="pCntRef">0</span></span>
        </div>
        <div class="d-flex gap-2 mt-2 flex-wrap">
            <input type="text" class="form-control form-control-sm" id="paySearch" placeholder="Search client, ID..." style="max-width:220px">
            <select class="form-select form-select-sm" id="payMethodFilter" style="max-width:160px">
                <option value="all">All Methods</option>
                <option value="card">Card</option><option value="cash">Cash</option>
                <option value="transfer">Transfer</option><option value="blik">BLIK</option>
                <option value="payu">PayU</option><option value="p24">Przelewy24</option>
                <option value="paypal">PayPal</option><option value="wise">Wise</option>
                <option value="revolut">Revolut</option>
            </select>
            <input type="date" class="form-control form-control-sm" id="payDateFrom" style="max-width:145px">
            <input type="date" class="form-control form-control-sm" id="payDateTo" style="max-width:145px">
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width:35px"><div class="form-check"><input class="form-check-input" type="checkbox" id="checkAllPay"></div></th>
                        <th>Payment ID</th>
                        <th>Invoice</th>
                        <th>Client</th>
                        <th>Amount</th>
                        <th>Method</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody id="payTableBody"></tbody>
            </table>
        </div>
    </div>
    <div class="card-footer d-flex align-items-center justify-content-between">
        <span class="text-muted fs-12" id="payShowing">Showing 0 payments</span>
        <div class="d-flex gap-1">
            <a href="finance-expenses" class="btn btn-sm btn-outline-secondary"><i class="ri-wallet-3-line me-1"></i>Expenses</a>
        </div>
    </div>
</div>

<!-- ============ VIEW PAYMENT MODAL ============ -->
<div class="modal fade" id="viewPayModal" tabindex="-1"><div class="modal-dialog"><div class="modal-content">
    <div class="modal-header bg-primary text-white">
        <h6 class="modal-title text-white"><i class="ri-eye-line me-2"></i>Payment Details</h6>
        <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
    </div>
    <div class="modal-body">
        <div class="row g-3">
            <div class="col-6"><label class="fs-11 text-muted">Payment ID</label><p class="fw-semibold mb-0" id="vpId"></p></div>
            <div class="col-6"><label class="fs-11 text-muted">Invoice</label><p class="fw-semibold mb-0" id="vpInvoice"></p></div>
            <div class="col-6"><label class="fs-11 text-muted">Client</label><p class="fw-semibold mb-0" id="vpClient"></p></div>
            <div class="col-6"><label class="fs-11 text-muted">Case</label><p class="fw-semibold mb-0" id="vpCase"></p></div>
            <div class="col-6"><label class="fs-11 text-muted">Amount</label><p class="fw-bold fs-5 text-success mb-0" id="vpAmount"></p></div>
            <div class="col-6"><label class="fs-11 text-muted">Method</label><p class="fw-semibold mb-0" id="vpMethod"></p></div>
            <div class="col-6"><label class="fs-11 text-muted">Status</label><p class="mb-0" id="vpStatus"></p></div>
            <div class="col-6"><label class="fs-11 text-muted">Date</label><p class="fw-semibold mb-0" id="vpDate"></p></div>
            <div class="col-12"><label class="fs-11 text-muted">Reference</label><p class="mb-0" id="vpRef"></p></div>
        </div>
    </div>
    <div class="modal-footer">
        <button class="btn btn-sm btn-outline-success" id="vpReceiptBtn"><i class="ri-receipt-line me-1"></i>Receipt</button>
        <button class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Close</button>
    </div>
</div></div></div>

<!-- ============ RECEIPT MODAL ============ -->
<div class="modal fade" id="receiptModal" tabindex="-1"><div class="modal-dialog modal-sm"><div class="modal-content">
    <div class="modal-header"><h6 class="modal-title"><i class="ri-receipt-line me-1"></i>Receipt</h6><button class="btn-close" data-bs-dismiss="modal"></button></div>
    <div class="modal-body"><div class="receipt-box" id="receiptBody"></div></div>
    <div class="modal-footer"><button class="btn btn-sm btn-primary" onclick="window.print()"><i class="ri-printer-line me-1"></i>Print</button><button class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Close</button></div>
</div></div></div>

<!-- ============ REFUND MODAL ============ -->
<div class="modal fade" id="refundModal" tabindex="-1"><div class="modal-dialog"><div class="modal-content">
    <div class="modal-header bg-warning">
        <h6 class="modal-title"><i class="ri-refund-2-line me-2"></i>Process Refund</h6>
        <button class="btn-close" data-bs-dismiss="modal"></button>
    </div>
    <div class="modal-body">
        <div class="alert alert-warning fs-12 mb-3"><i class="ri-alert-line me-1"></i>This action cannot be undone.</div>
        <div class="row g-3">
            <div class="col-6"><label class="fs-11 text-muted">Payment</label><p class="fw-semibold mb-0" id="rfId"></p></div>
            <div class="col-6"><label class="fs-11 text-muted">Original Amount</label><p class="fw-bold text-success mb-0" id="rfOrigAmt"></p></div>
            <div class="col-6"><label class="form-label fs-12 fw-semibold">Refund Amount (PLN)</label><input type="number" class="form-control" id="rfAmount" step="0.01" min="0"></div>
            <div class="col-6">
                <label class="form-label fs-12 fw-semibold">Reason</label>
                <select class="form-select" id="rfReason">
                    <option>Client Request</option><option>Duplicate Payment</option><option>Service Issue</option>
                    <option>Overcharge</option><option>Case Closed</option><option>Other</option>
                </select>
            </div>
            <div class="col-12"><label class="form-label fs-12 fw-semibold">Notes</label><textarea class="form-control" id="rfNotes" rows="2"></textarea></div>
        </div>
    </div>
    <div class="modal-footer">
        <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button class="btn btn-warning" id="confirmRefundBtn"><i class="ri-refund-2-line me-1"></i>Process Refund</button>
    </div>
</div></div></div>

<!-- ============ RECORD PAYMENT MODAL ============ -->
<div class="modal fade" id="addPaymentModal" tabindex="-1"><div class="modal-dialog modal-lg"><div class="modal-content">
    <div class="modal-header bg-success text-white">
        <h6 class="modal-title text-white"><i class="ri-money-euro-circle-line me-2"></i>Record Payment</h6>
        <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
    </div>
    <div class="modal-body">
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label fs-12 fw-semibold">Client <span class="text-danger">*</span></label>
                <select class="form-select" id="newPayClient">
                    <option value="">-- Select --</option>
                    <option value="cl1">Oleksandr Petrov</option><option value="cl2">Maria Ivanova</option>
                    <option value="cl3">Viktor Kovalenko</option><option value="cl4">Anna Shevchenko</option>
                    <option value="cl5">Dmytro Bondarenko</option><option value="cl6">Iryna Melnyk</option>
                    <option value="cl7">Pavlo Tkachenko</option><option value="cl8">Natalia Moroz</option>
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label fs-12 fw-semibold">Invoice</label>
                <select class="form-select" id="newPayInvoice">
                    <option value="">-- Select invoice --</option>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label fs-12 fw-semibold">Amount (PLN) <span class="text-danger">*</span></label>
                <input type="number" class="form-control" id="newPayAmount" step="0.01" min="0" placeholder="0,00">
            </div>
            <div class="col-md-4">
                <label class="form-label fs-12 fw-semibold">Method <span class="text-danger">*</span></label>
                <select class="form-select" id="newPayMethod">
                    <option value="card">Card</option><option value="cash">Cash</option>
                    <option value="transfer">Bank Transfer</option><option value="blik">BLIK</option>
                    <option value="payu">PayU</option><option value="p24">Przelewy24</option>
                    <option value="paypal">PayPal</option><option value="wise">Wise</option>
                    <option value="revolut">Revolut</option>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label fs-12 fw-semibold">Date <span class="text-danger">*</span></label>
                <input type="date" class="form-control" id="newPayDate">
            </div>
            <div class="col-md-6">
                <label class="form-label fs-12 fw-semibold">Reference</label>
                <input type="text" class="form-control" id="newPayRef" placeholder="Transaction reference...">
            </div>
            <div class="col-md-6">
                <label class="form-label fs-12 fw-semibold">Case</label>
                <select class="form-select" id="newPayCase">
                    <option value="">-- Select case --</option>
                </select>
            </div>
            <div class="col-12">
                <label class="form-label fs-12 fw-semibold">Notes</label>
                <textarea class="form-control" id="newPayNotes" rows="2" placeholder="Additional notes..."></textarea>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button class="btn btn-success" id="saveNewPayBtn"><i class="ri-check-line me-1"></i>Record Payment</button>
    </div>
</div></div></div>

<script>
document.addEventListener('DOMContentLoaded', function(){

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
    const CLIENT_NAMES = {cl1:'Oleksandr Petrov',cl2:'Maria Ivanova',cl3:'Viktor Kovalenko',cl4:'Anna Shevchenko',cl5:'Dmytro Bondarenko',cl6:'Iryna Melnyk',cl7:'Pavlo Tkachenko',cl8:'Natalia Moroz'};

    const METHOD_ICONS = {card:'ri-bank-card-line text-primary',cash:'ri-money-euro-circle-line text-success',transfer:'ri-bank-line text-info',blik:'ri-smartphone-line text-danger',payu:'ri-secure-payment-line text-warning',p24:'ri-exchange-funds-line text-primary',paypal:'ri-paypal-line text-info',wise:'ri-global-line text-success',revolut:'ri-refresh-line text-dark'};
    const METHOD_LABELS = {card:'Card',cash:'Cash',transfer:'Bank Transfer',blik:'BLIK',payu:'PayU',p24:'Przelewy24',paypal:'PayPal',wise:'Wise',revolut:'Revolut'};
    const METHOD_BG = {card:'bg-primary-subtle text-primary',cash:'bg-success-subtle text-success',transfer:'bg-info-subtle text-info',blik:'bg-danger-subtle text-danger',payu:'bg-warning-subtle text-warning',p24:'bg-primary-subtle text-primary',paypal:'bg-info-subtle text-info',wise:'bg-success-subtle text-success',revolut:'bg-dark-subtle text-dark'};

    const STATUS_BADGES = {completed:'<span class="badge bg-success-subtle text-success">Completed</span>',pending:'<span class="badge bg-warning-subtle text-warning">Pending</span>',failed:'<span class="badge bg-danger-subtle text-danger">Failed</span>',refunded:'<span class="badge bg-info-subtle text-info">Refunded</span>'};

    let payments = [
        {id:'PAY-1052',inv:'FV/2026/03/091',client:'Oleksandr Petrov',clientId:'cl1',caseId:'#WC-0189',amount:4800,method:'transfer',status:'completed',date:'2026-03-02',ref:'TRX-20260302-001'},
        {id:'PAY-1051',inv:'FV/2026/03/090',client:'Maria Ivanova',clientId:'cl2',caseId:'#WC-0188',amount:3500,method:'card',status:'completed',date:'2026-03-01',ref:'TRX-20260301-004'},
        {id:'PAY-1050',inv:'FV/2026/03/089',client:'Viktor Kovalenko',clientId:'cl3',caseId:'#WC-0185',amount:1800,method:'transfer',status:'completed',date:'2026-02-28',ref:'TRX-20260228-002'},
        {id:'PAY-1049',inv:'FV/2026/03/087',client:'Anna Shevchenko',clientId:'cl4',caseId:'#WC-0190',amount:5500,method:'p24',status:'completed',date:'2026-02-25',ref:'P24-887654'},
        {id:'PAY-1048',inv:'FV/2026/02/086',client:'Dmytro Bondarenko',clientId:'cl5',caseId:'#WC-0191',amount:1900,method:'card',status:'pending',date:'2026-03-01',ref:''},
        {id:'PAY-1047',inv:'FV/2026/02/085',client:'Iryna Melnyk',clientId:'cl6',caseId:'#WC-0192',amount:8500,method:'transfer',status:'completed',date:'2026-02-20',ref:'TRX-20260220-001'},
        {id:'PAY-1046',inv:'FV/2026/02/084',client:'Pavlo Tkachenko',clientId:'cl7',caseId:'#WC-0193',amount:4200,method:'cash',status:'completed',date:'2026-02-15',ref:'CASH-0215'},
        {id:'PAY-1045',inv:'FV/2026/02/083',client:'Natalia Moroz',clientId:'cl8',caseId:'#WC-0194',amount:3000,method:'wise',status:'completed',date:'2026-02-14',ref:'WISE-4478890'},
        {id:'PAY-1044',inv:'FV/2026/02/082',client:'Viktor Kovalenko',clientId:'cl3',caseId:'#WC-0199',amount:5200,method:'revolut',status:'completed',date:'2026-02-12',ref:'REV-99271'},
        {id:'PAY-1043',inv:'FV/2026/02/081',client:'Dmytro Shevchenko',clientId:'cl5',caseId:'#WC-0191',amount:3500,method:'payu',status:'failed',date:'2026-02-10',ref:'PAYU-ERR-2210'},
        {id:'PAY-1042',inv:'FV/2026/01/080',client:'Tetiana Sydorenko',clientId:'cl7',caseId:'#WC-0193',amount:3000,method:'blik',status:'completed',date:'2026-02-08',ref:'BLIK-442901'},
        {id:'PAY-1041',inv:'FV/2026/01/079',client:'Oleksandr Petrov',clientId:'cl1',caseId:'#WC-0201',amount:3600,method:'card',status:'completed',date:'2026-01-31',ref:'TRX-20260131-003'},
        {id:'PAY-1040',inv:'FV/2026/03/088',client:'Anastasia Nowak',clientId:'cl4',caseId:'#WC-0190',amount:1500,method:'paypal',status:'pending',date:'2026-03-02',ref:'PP-8876544'},
        {id:'PAY-1039',inv:'FV/2026/01/078',client:'Maria Ivanova',clientId:'cl2',caseId:'#WC-0188',amount:2500,method:'cash',status:'completed',date:'2026-01-20',ref:'CASH-0120'},
        {id:'PAY-1038',inv:'FV/2026/02/082',client:'Dmytro Bondarenko',clientId:'cl5',caseId:'#WC-0191',amount:1200,method:'card',status:'refunded',date:'2026-02-05',ref:'REF-TRX-0205'},
        {id:'PAY-1037',inv:'FV/2026/01/080',client:'Natalia Moroz',clientId:'cl8',caseId:'#WC-0194',amount:2000,method:'transfer',status:'refunded',date:'2026-01-28',ref:'REF-TRX-0128'}
    ];

    let payCounter = 1053;
    let currentFilter = 'all';
    function fmt(n){return n.toLocaleString('pl-PL',{minimumFractionDigits:2,maximumFractionDigits:2});}

    function renderTable(){
        const search = (document.getElementById('paySearch').value||'').toLowerCase();
        const methodF = document.getElementById('payMethodFilter').value;
        const tbody = document.getElementById('payTableBody');
        tbody.innerHTML = '';
        let count = 0;
        payments.forEach((p,idx)=>{
            if(currentFilter!=='all' && p.status!==currentFilter) return;
            if(search && !p.client.toLowerCase().includes(search) && !p.id.toLowerCase().includes(search) && !p.inv.toLowerCase().includes(search)) return;
            if(methodF!=='all' && p.method!==methodF) return;
            count++;
            const mb = METHOD_BG[p.method]||'bg-secondary-subtle text-secondary';
            let actions = '';
            if(p.status==='completed'){
                actions = `<div class="d-flex gap-1 justify-content-center">
                    <button class="btn btn-sm btn-subtle-primary act-view" title="View"><i class="ri-eye-line"></i></button>
                    <button class="btn btn-sm btn-subtle-success act-receipt" title="Receipt"><i class="ri-receipt-line"></i></button>
                    <button class="btn btn-sm btn-subtle-warning act-refund" title="Refund"><i class="ri-refund-2-line"></i></button>
                </div>`;
            } else if(p.status==='pending'){
                actions = `<div class="d-flex gap-1 justify-content-center">
                    <button class="btn btn-sm btn-subtle-primary act-view" title="View"><i class="ri-eye-line"></i></button>
                    <button class="btn btn-sm btn-subtle-success act-approve" title="Approve"><i class="ri-check-line"></i></button>
                    <button class="btn btn-sm btn-subtle-danger act-reject" title="Reject"><i class="ri-close-line"></i></button>
                </div>`;
            } else {
                actions = `<div class="d-flex gap-1 justify-content-center">
                    <button class="btn btn-sm btn-subtle-primary act-view" title="View"><i class="ri-eye-line"></i></button>
                    <button class="btn btn-sm btn-subtle-success act-receipt" title="Receipt"><i class="ri-receipt-line"></i></button>
                </div>`;
            }
            tbody.innerHTML += `<tr data-idx="${idx}">
                <td><div class="form-check"><input class="form-check-input pay-check" type="checkbox"></div></td>
                <td><a href="#" class="fw-semibold text-body act-view">#${p.id}</a></td>
                <td><a href="finance-invoices" class="text-primary fs-12">${p.inv}</a></td>
                <td><a href="crm-client-invoices?client=${p.clientId}" class="text-body">${p.client}</a></td>
                <td class="fw-semibold ${p.status==='refunded'?'text-info':p.status==='failed'?'text-danger':'text-success'}">${fmt(p.amount)} PLN</td>
                <td><span class="method-badge ${mb}"><i class="${METHOD_ICONS[p.method]} me-1"></i>${METHOD_LABELS[p.method]}</span></td>
                <td>${STATUS_BADGES[p.status]}</td>
                <td class="text-muted fs-12">${p.date}</td>
                <td>${actions}</td>
            </tr>`;
        });
        document.getElementById('payShowing').textContent = `Showing ${count} payments`;
    }

    function updateCounts(){
        let all=0,comp=0,pend=0,fail=0,ref=0;
        payments.forEach(p=>{all++;if(p.status==='completed')comp++;if(p.status==='pending')pend++;if(p.status==='failed')fail++;if(p.status==='refunded')ref++;});
        document.getElementById('pCntAll').textContent=all;document.getElementById('pCntComp').textContent=comp;
        document.getElementById('pCntPend').textContent=pend;document.getElementById('pCntFail').textContent=fail;
        document.getElementById('pCntRef').textContent=ref;
    }

    renderTable(); updateCounts();

    // ============ FILTERS ============
    document.getElementById('payFilterPills').addEventListener('click',function(e){
        const p=e.target.closest('.pay-filter-pill'); if(!p)return;
        document.querySelectorAll('.pay-filter-pill').forEach(x=>x.classList.remove('active'));
        p.classList.add('active');currentFilter=p.dataset.filter;renderTable();
    });
    document.getElementById('paySearch').addEventListener('input',()=>renderTable());
    document.getElementById('payMethodFilter').addEventListener('change',()=>renderTable());

    // ============ TABLE ACTIONS ============
    let currentPayIdx = -1;
    document.getElementById('payTableBody').addEventListener('click',function(e){
        const btn = e.target.closest('button')||e.target.closest('a.act-view');
        if(!btn)return; e.preventDefault();
        const row = btn.closest('tr');
        const idx = parseInt(row.dataset.idx);
        const p = payments[idx]; if(!p) return;
        currentPayIdx = idx;

        if(btn.classList.contains('act-view')){
            document.getElementById('vpId').textContent='#'+p.id;
            document.getElementById('vpInvoice').textContent=p.inv;
            document.getElementById('vpClient').textContent=p.client;
            document.getElementById('vpCase').textContent=p.caseId;
            document.getElementById('vpAmount').textContent=fmt(p.amount)+' PLN';
            document.getElementById('vpMethod').innerHTML=`<i class="${METHOD_ICONS[p.method]} me-1"></i>${METHOD_LABELS[p.method]}`;
            document.getElementById('vpStatus').innerHTML=STATUS_BADGES[p.status];
            document.getElementById('vpDate').textContent=p.date;
            document.getElementById('vpRef').textContent=p.ref||'—';
            new bootstrap.Modal(document.getElementById('viewPayModal')).show();
        }
        if(btn.classList.contains('act-receipt')){showReceipt(p);}
        if(btn.classList.contains('act-approve')){p.status='completed';renderTable();updateCounts();showToast('#'+p.id+' approved','success');}
        if(btn.classList.contains('act-reject')){p.status='failed';renderTable();updateCounts();showToast('#'+p.id+' rejected','danger');}
        if(btn.classList.contains('act-refund')){
            document.getElementById('rfId').textContent='#'+p.id;
            document.getElementById('rfOrigAmt').textContent=fmt(p.amount)+' PLN';
            document.getElementById('rfAmount').value=p.amount;
            new bootstrap.Modal(document.getElementById('refundModal')).show();
        }
    });

    // View → Receipt
    document.getElementById('vpReceiptBtn').addEventListener('click',function(){
        const p=payments[currentPayIdx]; if(!p)return;
        bootstrap.Modal.getInstance(document.getElementById('viewPayModal')).hide();
        setTimeout(()=>showReceipt(p),300);
    });

    // ============ RECEIPT ============
    function showReceipt(p){
        document.getElementById('receiptBody').innerHTML=`
            <div style="text-align:center;border-bottom:2px dashed #dee2e6;padding-bottom:12px;margin-bottom:12px">
                <div style="font-weight:700;font-size:1.2rem">WinCase</div>
                <div style="font-size:.78rem;color:#888">Immigration Bureau</div>
                <div style="font-size:.78rem;color:#888">ul. Marszalkowska 1, 00-001 Warszawa</div>
                <div style="font-size:.78rem;color:#888">NIP: 5252811122</div>
            </div>
            <div class="receipt-line"><span>Payment:</span><span class="fw-semibold">#${p.id}</span></div>
            <div class="receipt-line"><span>Invoice:</span><span>${p.inv}</span></div>
            <div class="receipt-line"><span>Date:</span><span>${p.date}</span></div>
            <div class="receipt-line"><span>Client:</span><span>${p.client}</span></div>
            <div class="receipt-line"><span>Case:</span><span>${p.caseId}</span></div>
            <div class="receipt-line"><span>Method:</span><span>${METHOD_LABELS[p.method]}</span></div>
            ${p.ref?`<div class="receipt-line"><span>Reference:</span><span>${p.ref}</span></div>`:''}
            <div class="receipt-line total"><span>TOTAL:</span><span>${fmt(p.amount)} PLN</span></div>
            <div style="text-align:center;margin-top:12px;padding-top:8px;border-top:2px dashed #dee2e6">
                <div style="font-size:.78rem;color:#888">Thank you! | www.wincase.eu</div>
            </div>
        `;
        new bootstrap.Modal(document.getElementById('receiptModal')).show();
    }

    // ============ REFUND ============
    document.getElementById('confirmRefundBtn').addEventListener('click',function(){
        const p=payments[currentPayIdx]; if(!p)return;
        p.status='refunded';
        renderTable();updateCounts();
        bootstrap.Modal.getInstance(document.getElementById('refundModal')).hide();
        showToast('#'+p.id+' refunded','info');
    });

    // ============ NEW PAYMENT ============
    document.getElementById('newPayClient').addEventListener('change',function(){
        const inv=document.getElementById('newPayInvoice');inv.innerHTML='<option value="">-- Select invoice --</option>';
        const cs=document.getElementById('newPayCase');cs.innerHTML='<option value="">-- Select case --</option>';
        const cl=this.value;
        if(cl&&CLIENT_CASES[cl]){
            CLIENT_CASES[cl].forEach(c=>{
                cs.innerHTML+=`<option value="${c.id}">${c.id} — ${c.type}</option>`;
                inv.innerHTML+=`<option value="FV-${c.id}">FV — ${c.id} (${c.type})</option>`;
            });
        }
    });

    document.getElementById('saveNewPayBtn').addEventListener('click',function(){
        const cl=document.getElementById('newPayClient').value;
        const amt=parseFloat(document.getElementById('newPayAmount').value)||0;
        if(!cl||amt<=0){showToast('Fill required fields','warning');return;}
        const method=document.getElementById('newPayMethod').value;
        const caseId=document.getElementById('newPayCase').value||'—';
        const inv=document.getElementById('newPayInvoice').value||'—';
        const date=document.getElementById('newPayDate').value||new Date().toISOString().slice(0,10);
        const ref=document.getElementById('newPayRef').value;
        payments.unshift({
            id:'PAY-'+payCounter++,inv:inv,client:CLIENT_NAMES[cl],clientId:cl,
            caseId:caseId,amount:amt,method:method,
            status:(method==='cash'||method==='card')?'completed':'pending',
            date:date,ref:ref||''
        });
        renderTable();updateCounts();
        bootstrap.Modal.getInstance(document.getElementById('addPaymentModal')).hide();
        showToast('Payment '+fmt(amt)+' PLN recorded','success');
    });

    // ============ EXPORT CSV ============
    document.getElementById('exportPayCsvBtn').addEventListener('click',function(){
        let csv='ID,Invoice,Client,Case,Amount,Method,Status,Date,Reference\n';
        payments.forEach(p=>{csv+=`${p.id},${p.inv},"${p.client}",${p.caseId},${p.amount},${METHOD_LABELS[p.method]},${p.status},${p.date},${p.ref}\n`;});
        const blob=new Blob([csv],{type:'text/csv'});
        const url=URL.createObjectURL(blob);const a=document.createElement('a');
        a.href=url;a.download='payments_'+new Date().toISOString().slice(0,10)+'.csv';a.click();URL.revokeObjectURL(url);
        showToast('CSV exported','success');
    });

    // ============ CHECK ALL ============
    document.getElementById('checkAllPay').addEventListener('change',function(){document.querySelectorAll('.pay-check').forEach(c=>c.checked=this.checked);});

    // ============ TOAST ============
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
