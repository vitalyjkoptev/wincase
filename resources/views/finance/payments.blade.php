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
                    <h4 class="fs-22 fw-semibold mb-0" id="statTotalReceived">—</h4>
                    <p class="text-muted mt-1 mb-0"><span class="badge bg-success-subtle text-success me-1"><i class="ri-wallet-line"></i> total</span></p>
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
                    <h4 class="fs-22 fw-semibold mb-0" id="statThisMonth">—</h4>
                    <p class="text-muted mt-1 mb-0"><span class="badge bg-info-subtle text-info me-1"><i class="ri-calendar-check-line"></i> this month</span></p>
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
                    <h4 class="fs-22 fw-semibold mb-0" id="statPending">—</h4>
                    <p class="text-muted mt-1 mb-0"><span class="badge bg-warning-subtle text-warning me-1" id="statPendingCnt">0 payments</span> awaiting</p>
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
                    <h4 class="fs-22 fw-semibold mb-0" id="statRefunded">—</h4>
                    <p class="text-muted mt-1 mb-0"><span class="badge bg-info-subtle text-info me-1" id="statRefundedCnt">0 refunds</span> processed</p>
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

    const API = '/api/v1';
    const TOKEN = localStorage.getItem('wc_token');
    const headers = { 'Accept': 'application/json', 'Authorization': 'Bearer ' + TOKEN };

    const METHOD_ICONS = {card:'ri-bank-card-line text-primary',cash:'ri-money-euro-circle-line text-success',transfer:'ri-bank-line text-info',bank_transfer:'ri-bank-line text-info',blik:'ri-smartphone-line text-danger',payu:'ri-secure-payment-line text-warning',p24:'ri-exchange-funds-line text-primary',przelewy24:'ri-exchange-funds-line text-primary',paypal:'ri-paypal-line text-info',wise:'ri-global-line text-success',revolut:'ri-refresh-line text-dark',stripe:'ri-bank-card-line text-primary'};
    const METHOD_LABELS = {card:'Card',cash:'Cash',transfer:'Bank Transfer',bank_transfer:'Bank Transfer',blik:'BLIK',payu:'PayU',p24:'Przelewy24',przelewy24:'Przelewy24',paypal:'PayPal',wise:'Wise',revolut:'Revolut',stripe:'Stripe'};
    const METHOD_BG = {card:'bg-primary-subtle text-primary',cash:'bg-success-subtle text-success',transfer:'bg-info-subtle text-info',bank_transfer:'bg-info-subtle text-info',blik:'bg-danger-subtle text-danger',payu:'bg-warning-subtle text-warning',p24:'bg-primary-subtle text-primary',przelewy24:'bg-primary-subtle text-primary',paypal:'bg-info-subtle text-info',wise:'bg-success-subtle text-success',revolut:'bg-dark-subtle text-dark',stripe:'bg-primary-subtle text-primary'};
    const STATUS_BADGES = {completed:'<span class="badge bg-success-subtle text-success">Completed</span>',pending:'<span class="badge bg-warning-subtle text-warning">Pending</span>',failed:'<span class="badge bg-danger-subtle text-danger">Failed</span>',refunded:'<span class="badge bg-info-subtle text-info">Refunded</span>',partially_refunded:'<span class="badge bg-info-subtle text-info">Partial Refund</span>'};

    let payments = [];
    let currentFilter = 'all';
    function fmt(n){return Number(n||0).toLocaleString('pl-PL',{minimumFractionDigits:2,maximumFractionDigits:2});}

    // ============ LOAD DATA FROM API ============
    async function loadPayments(){
        try {
            const r = await fetch(API + '/payments', { headers });
            if(!r.ok) throw new Error(r.status);
            const j = await r.json();
            if(j.success && j.data){
                const raw = j.data.payments || [];
                payments = raw.map(p => ({
                    id: p.id,
                    inv: p.invoice ? p.invoice.invoice_number : '—',
                    invoiceId: p.invoice_id,
                    client: p.client ? (p.client.first_name + ' ' + p.client.last_name) : '—',
                    clientId: p.client_id,
                    caseId: p.case ? ('#WC-' + String(p.case.case_number||p.case_id||'').padStart(4,'0')) : (p.case_id ? '#' + p.case_id : '—'),
                    amount: parseFloat(p.amount) || 0,
                    method: p.payment_method || p.gateway || 'transfer',
                    status: p.status || 'pending',
                    date: p.payment_date || p.created_at?.substring(0,10) || '',
                    ref: p.reference || p.reference_number || p.gateway_payment_id || '',
                    gateway: p.gateway || null,
                }));
                // Stats
                const s = j.data.stats;
                if(s){
                    document.getElementById('statTotalReceived').textContent = fmt(s.total_received) + ' PLN';
                    document.getElementById('statThisMonth').textContent = fmt(s.this_month) + ' PLN';
                    document.getElementById('statPending').textContent = fmt(s.pending_amount) + ' PLN';
                    document.getElementById('statPendingCnt').textContent = s.pending_count + ' payments';
                    document.getElementById('statRefunded').textContent = fmt(s.refunded_amount) + ' PLN';
                    document.getElementById('statRefundedCnt').textContent = s.refunded_count + ' refunds';
                }
                renderTable(); updateCounts();
            }
        } catch(e) {
            console.error('Load payments failed:', e);
            showToast('Failed to load payments', 'danger');
        }
    }

    // Load clients for "Record Payment" modal
    async function loadClients(){
        try {
            const r = await fetch(API + '/clients', { headers });
            if(!r.ok) return;
            const j = await r.json();
            const clients = j.data || j.clients || j || [];
            const sel = document.getElementById('newPayClient');
            (Array.isArray(clients) ? clients : (clients.data || [])).forEach(c => {
                const name = (c.first_name||'') + ' ' + (c.last_name||'');
                sel.innerHTML += `<option value="${c.id}">${name.trim()}</option>`;
            });
        } catch(e) { console.error('Load clients failed:', e); }
    }

    loadPayments();
    loadClients();

    function renderTable(){
        const search = (document.getElementById('paySearch').value||'').toLowerCase();
        const methodF = document.getElementById('payMethodFilter').value;
        const tbody = document.getElementById('payTableBody');
        tbody.innerHTML = '';
        let count = 0;
        payments.forEach((p,idx)=>{
            if(currentFilter!=='all' && p.status!==currentFilter) return;
            if(search && !p.client.toLowerCase().includes(search) && !String(p.id).toLowerCase().includes(search) && !p.inv.toLowerCase().includes(search)) return;
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
                <td><a href="#" class="fw-semibold text-body act-view">#PAY-${p.id}</a></td>
                <td><a href="finance-invoices" class="text-primary fs-12">${p.inv}</a></td>
                <td><a href="crm-client-invoices?client=${p.clientId}" class="text-body">${p.client}</a></td>
                <td class="fw-semibold ${p.status==='refunded'?'text-info':p.status==='failed'?'text-danger':'text-success'}">${fmt(p.amount)} PLN</td>
                <td><span class="method-badge ${mb}"><i class="${(METHOD_ICONS[p.method]||'ri-money-euro-circle-line text-secondary')} me-1"></i>${METHOD_LABELS[p.method]||p.method}</span></td>
                <td>${STATUS_BADGES[p.status]||'<span class="badge bg-secondary-subtle text-secondary">'+p.status+'</span>'}</td>
                <td class="text-muted fs-12">${p.date}</td>
                <td>${actions}</td>
            </tr>`;
        });
        document.getElementById('payShowing').textContent = `Showing ${count} payments`;
    }

    function updateCounts(){
        let all=0,comp=0,pend=0,fail=0,ref=0;
        payments.forEach(p=>{all++;if(p.status==='completed')comp++;if(p.status==='pending')pend++;if(p.status==='failed')fail++;if(p.status==='refunded'||p.status==='partially_refunded')ref++;});
        document.getElementById('pCntAll').textContent=all;document.getElementById('pCntComp').textContent=comp;
        document.getElementById('pCntPend').textContent=pend;document.getElementById('pCntFail').textContent=fail;
        document.getElementById('pCntRef').textContent=ref;
    }

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
            document.getElementById('vpId').textContent='#PAY-'+p.id;
            document.getElementById('vpInvoice').textContent=p.inv;
            document.getElementById('vpClient').textContent=p.client;
            document.getElementById('vpCase').textContent=p.caseId;
            document.getElementById('vpAmount').textContent=fmt(p.amount)+' PLN';
            document.getElementById('vpMethod').innerHTML=`<i class="${METHOD_ICONS[p.method]||'ri-money-euro-circle-line'} me-1"></i>${METHOD_LABELS[p.method]||p.method}`;
            document.getElementById('vpStatus').innerHTML=STATUS_BADGES[p.status]||p.status;
            document.getElementById('vpDate').textContent=p.date;
            document.getElementById('vpRef').textContent=p.ref||'—';
            new bootstrap.Modal(document.getElementById('viewPayModal')).show();
        }
        if(btn.classList.contains('act-receipt')){showReceipt(p);}
        if(btn.classList.contains('act-approve')){apiAction(p.id,'completed');}
        if(btn.classList.contains('act-reject')){apiAction(p.id,'failed');}
        if(btn.classList.contains('act-refund')){
            document.getElementById('rfId').textContent='#PAY-'+p.id;
            document.getElementById('rfOrigAmt').textContent=fmt(p.amount)+' PLN';
            document.getElementById('rfAmount').value=p.amount;
            new bootstrap.Modal(document.getElementById('refundModal')).show();
        }
    });

    // Approve/Reject via API — updates status locally as fallback
    async function apiAction(payId, newStatus){
        const p = payments[currentPayIdx];
        // Try API first
        try {
            const fd = new FormData();
            fd.append('status', newStatus);
            await fetch(API + '/payments/' + payId + '/status', { method:'POST', headers:{'Accept':'application/json','Authorization':'Bearer '+TOKEN}, body: fd });
        } catch(e) {}
        // Update locally
        if(p) p.status = newStatus;
        renderTable(); updateCounts();
        showToast('#PAY-'+payId+' → '+newStatus, newStatus==='completed'?'success':'danger');
    }

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
            <div class="receipt-line"><span>Payment:</span><span class="fw-semibold">#PAY-${p.id}</span></div>
            <div class="receipt-line"><span>Invoice:</span><span>${p.inv}</span></div>
            <div class="receipt-line"><span>Date:</span><span>${p.date}</span></div>
            <div class="receipt-line"><span>Client:</span><span>${p.client}</span></div>
            <div class="receipt-line"><span>Case:</span><span>${p.caseId}</span></div>
            <div class="receipt-line"><span>Method:</span><span>${METHOD_LABELS[p.method]||p.method}</span></div>
            ${p.ref?`<div class="receipt-line"><span>Reference:</span><span>${p.ref}</span></div>`:''}
            <div class="receipt-line total"><span>TOTAL:</span><span>${fmt(p.amount)} PLN</span></div>
            <div style="text-align:center;margin-top:12px;padding-top:8px;border-top:2px dashed #dee2e6">
                <div style="font-size:.78rem;color:#888">Thank you! | www.wincase.eu</div>
            </div>
        `;
        new bootstrap.Modal(document.getElementById('receiptModal')).show();
    }

    // ============ REFUND VIA API ============
    document.getElementById('confirmRefundBtn').addEventListener('click', async function(){
        const p=payments[currentPayIdx]; if(!p)return;
        const amt = parseFloat(document.getElementById('rfAmount').value)||p.amount;
        const reason = document.getElementById('rfReason').value;
        try {
            const fd = new FormData();
            fd.append('amount', amt);
            fd.append('reason', reason);
            await fetch(API + '/payments/' + p.id + '/refund', { method:'POST', headers:{'Accept':'application/json','Authorization':'Bearer '+TOKEN}, body: fd });
        } catch(e) {}
        p.status='refunded';
        renderTable();updateCounts();
        bootstrap.Modal.getInstance(document.getElementById('refundModal')).hide();
        showToast('#PAY-'+p.id+' refunded','info');
    });

    // ============ NEW PAYMENT — load cases/invoices for selected client ============
    document.getElementById('newPayClient').addEventListener('change', async function(){
        const inv=document.getElementById('newPayInvoice');inv.innerHTML='<option value="">-- Select invoice --</option>';
        const cs=document.getElementById('newPayCase');cs.innerHTML='<option value="">-- Select case --</option>';
        const cl=this.value;
        if(!cl) return;
        // Load cases
        try {
            const r = await fetch(API + '/clients/' + cl + '/cases', { headers });
            if(r.ok){
                const j = await r.json();
                const cases = j.data || j.cases || j || [];
                (Array.isArray(cases)?cases:(cases.data||[])).forEach(c => {
                    cs.innerHTML += `<option value="${c.id}">#WC-${String(c.case_number||c.id).padStart(4,'0')} — ${c.case_type||c.type||''}</option>`;
                });
            }
        } catch(e) {}
        // Load invoices
        try {
            const r2 = await fetch(API + '/accounting/invoices?client_id=' + cl, { headers });
            if(r2.ok){
                const j2 = await r2.json();
                const invoices = j2.data || j2.invoices || j2 || [];
                (Array.isArray(invoices)?invoices:(invoices.data||[])).forEach(i => {
                    if(i.status !== 'paid') inv.innerHTML += `<option value="${i.id}">${i.invoice_number} — ${fmt(i.total_amount||i.gross_amount)} PLN</option>`;
                });
            }
        } catch(e) {}
    });

    // ============ SAVE NEW PAYMENT VIA API ============
    document.getElementById('saveNewPayBtn').addEventListener('click', async function(){
        const cl=document.getElementById('newPayClient').value;
        const amt=parseFloat(document.getElementById('newPayAmount').value)||0;
        if(!cl||amt<=0){showToast('Fill required fields','warning');return;}

        const fd = new FormData();
        fd.append('client_id', cl);
        fd.append('amount', amt);
        fd.append('payment_method', document.getElementById('newPayMethod').value);
        fd.append('payment_date', document.getElementById('newPayDate').value || new Date().toISOString().slice(0,10));
        const inv = document.getElementById('newPayInvoice').value;
        if(inv) fd.append('invoice_id', inv);
        const caseId = document.getElementById('newPayCase').value;
        if(caseId) fd.append('case_id', caseId);
        const ref = document.getElementById('newPayRef').value;
        if(ref) fd.append('reference', ref);
        const notes = document.getElementById('newPayNotes').value;
        if(notes) fd.append('notes', notes);

        try {
            const r = await fetch(API + '/payments', {
                method: 'POST',
                headers: { 'Accept': 'application/json', 'Authorization': 'Bearer ' + TOKEN },
                body: fd
            });
            const j = await r.json();
            if(j.success){
                bootstrap.Modal.getInstance(document.getElementById('addPaymentModal')).hide();
                showToast('Payment '+fmt(amt)+' PLN recorded','success');
                loadPayments(); // Reload from API
            } else {
                showToast(j.message || 'Error saving payment','danger');
            }
        } catch(e) {
            showToast('Network error','danger');
        }
    });

    // ============ EXPORT CSV ============
    document.getElementById('exportPayCsvBtn').addEventListener('click',function(){
        let csv='ID,Invoice,Client,Case,Amount,Method,Status,Date,Reference\n';
        payments.forEach(p=>{csv+=`PAY-${p.id},${p.inv},"${p.client}",${p.caseId},${p.amount},${METHOD_LABELS[p.method]||p.method},${p.status},${p.date},${p.ref}\n`;});
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
