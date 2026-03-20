@extends('partials.layouts.master')

@section('title', 'POS Terminal | WinCase CRM')
@section('sub-title', 'POS Terminal')
@section('sub-title-lang', 'wc-pos')
@section('pagetitle', 'Finance')
@section('pagetitle-lang', 'wc-finance')
@section('buttonTitle', 'New Payment')
@section('buttonTitle-lang', 'wc-new-payment')
@section('modalTarget', 'newPaymentModal')

@section('content')
<style>
    .pos-numpad .btn { height:56px; font-size:1.35rem; font-weight:600; border-radius:10px; transition:.15s }
    .pos-numpad .btn:active { transform:scale(.95) }
    .pos-amount-display { font-size:2.6rem; font-weight:700; letter-spacing:1px; min-height:68px }
    .pos-amount-display small { font-size:1.1rem; font-weight:400; color:#adb5bd }
    .method-card { cursor:pointer; border:2px solid #e9ecef; border-radius:12px; padding:14px 10px; text-align:center; transition:.2s }
    .method-card:hover { border-color:#845adf; background:#f8f6ff }
    .method-card.active { border-color:#845adf; background:#f0ebff; box-shadow:0 0 0 3px rgba(132,90,223,.15) }
    .method-card i { font-size:1.5rem; display:block; margin-bottom:4px }
    .method-card span { font-size:.75rem; font-weight:600 }
    .quick-amount { cursor:pointer; border:2px solid #e9ecef; border-radius:10px; padding:10px; text-align:center; font-weight:700; font-size:1rem; transition:.2s }
    .quick-amount:hover { border-color:#845adf; background:#f8f6ff; transform:translateY(-2px) }
    .txn-filter-pill { cursor:pointer; padding:6px 16px; border-radius:20px; font-size:.8rem; font-weight:600; border:1px solid #e9ecef; background:#fff; transition:.2s }
    .txn-filter-pill:hover { border-color:#845adf }
    .txn-filter-pill.active { background:#845adf; color:#fff; border-color:#845adf }
    .charge-btn { height:56px; font-size:1.2rem; font-weight:700; border-radius:12px; letter-spacing:.5px }
    .receipt-line { display:flex; justify-content:space-between; padding:3px 0; font-size:.85rem }
    .receipt-line.total { font-weight:700; font-size:1.1rem; border-top:2px dashed #dee2e6; padding-top:8px; margin-top:6px }
    .receipt-header { text-align:center; border-bottom:2px dashed #dee2e6; padding-bottom:12px; margin-bottom:12px }
    .stat-trend-up { color:#198754 }
    .stat-trend-down { color:#dc3545 }
</style>

<!-- ============ STAT CARDS ============ -->
<div class="row">
    <div class="col-xl-3 col-md-6">
        <div class="card card-h-100"><div class="card-body">
            <div class="d-flex align-items-center gap-3">
                <div class="avatar avatar-sm bg-success-subtle text-success rounded-2"><i class="ri-money-euro-circle-line fs-18"></i></div>
                <div>
                    <p class="text-muted mb-0 fs-13">Today's Revenue</p>
                    <h4 class="mb-0 fw-semibold" id="statTodayRev">8 450,00 PLN</h4>
                    <span class="fs-11 stat-trend-up"><i class="ri-arrow-up-s-fill"></i> +12% vs yesterday</span>
                </div>
            </div>
        </div></div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card card-h-100"><div class="card-body">
            <div class="d-flex align-items-center gap-3">
                <div class="avatar avatar-sm bg-primary-subtle text-primary rounded-2"><i class="ri-exchange-line fs-18"></i></div>
                <div>
                    <p class="text-muted mb-0 fs-13">Transactions Today</p>
                    <h4 class="mb-0 fw-semibold" id="statTxnCount">14</h4>
                    <span class="fs-11 text-muted">Avg: 603,57 PLN</span>
                </div>
            </div>
        </div></div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card card-h-100"><div class="card-body">
            <div class="d-flex align-items-center gap-3">
                <div class="avatar avatar-sm bg-warning-subtle text-warning rounded-2"><i class="ri-time-line fs-18"></i></div>
                <div>
                    <p class="text-muted mb-0 fs-13">Pending Approval</p>
                    <h4 class="mb-0 fw-semibold" id="statPending">3</h4>
                    <span class="fs-11 text-warning">2 400,00 PLN</span>
                </div>
            </div>
        </div></div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card card-h-100"><div class="card-body">
            <div class="d-flex align-items-center gap-3">
                <div class="avatar avatar-sm bg-info-subtle text-info rounded-2"><i class="ri-calendar-check-line fs-18"></i></div>
                <div>
                    <p class="text-muted mb-0 fs-13">Monthly Revenue</p>
                    <h4 class="mb-0 fw-semibold" id="statMonthRev">67 320,00 PLN</h4>
                    <span class="fs-11 stat-trend-up"><i class="ri-arrow-up-s-fill"></i> +8% vs last month</span>
                </div>
            </div>
        </div></div>
    </div>
</div>

<div class="row">
    <!-- ============ LEFT: POS TERMINAL ============ -->
    <div class="col-xl-5">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="card-title mb-0 text-white"><i class="ri-bank-card-line me-2"></i>POS Terminal</h5>
            </div>
            <div class="card-body">
                <!-- Client & Case Select -->
                <div class="row mb-3">
                    <div class="col-6">
                        <label class="form-label fs-12 fw-semibold">Client</label>
                        <select class="form-select form-select-sm" id="posClient">
                            <option value="">-- Select Client --</option>
                            <option value="cl1">Oleksandr Petrov</option>
                            <option value="cl2">Maria Ivanova</option>
                            <option value="cl3">Viktor Kovalenko</option>
                            <option value="cl4">Anna Shevchenko</option>
                            <option value="cl5">Dmytro Bondarenko</option>
                            <option value="cl6">Iryna Melnyk</option>
                            <option value="cl7">Pavlo Tkachenko</option>
                            <option value="cl8">Natalia Moroz</option>
                        </select>
                    </div>
                    <div class="col-6">
                        <label class="form-label fs-12 fw-semibold">Case</label>
                        <select class="form-select form-select-sm" id="posCase">
                            <option value="">-- Select Case --</option>
                        </select>
                    </div>
                </div>

                <!-- Amount Display -->
                <div class="bg-light rounded-3 p-3 mb-3 text-center">
                    <div class="pos-amount-display text-primary" id="posAmountDisplay">0,00 <small>PLN</small></div>
                </div>

                <!-- Quick Amounts -->
                <div class="row g-2 mb-3">
                    <div class="col-3"><div class="quick-amount" data-amount="100">100</div></div>
                    <div class="col-3"><div class="quick-amount" data-amount="250">250</div></div>
                    <div class="col-3"><div class="quick-amount" data-amount="500">500</div></div>
                    <div class="col-3"><div class="quick-amount" data-amount="1000">1 000</div></div>
                    <div class="col-3"><div class="quick-amount" data-amount="1500">1 500</div></div>
                    <div class="col-3"><div class="quick-amount" data-amount="2000">2 000</div></div>
                    <div class="col-3"><div class="quick-amount" data-amount="3000">3 000</div></div>
                    <div class="col-3"><div class="quick-amount" data-amount="5000">5 000</div></div>
                </div>

                <!-- Numpad -->
                <div class="pos-numpad mb-3">
                    <div class="row g-2 mb-2">
                        <div class="col-4"><button class="btn btn-outline-secondary w-100 numpad-btn" data-val="7">7</button></div>
                        <div class="col-4"><button class="btn btn-outline-secondary w-100 numpad-btn" data-val="8">8</button></div>
                        <div class="col-4"><button class="btn btn-outline-secondary w-100 numpad-btn" data-val="9">9</button></div>
                    </div>
                    <div class="row g-2 mb-2">
                        <div class="col-4"><button class="btn btn-outline-secondary w-100 numpad-btn" data-val="4">4</button></div>
                        <div class="col-4"><button class="btn btn-outline-secondary w-100 numpad-btn" data-val="5">5</button></div>
                        <div class="col-4"><button class="btn btn-outline-secondary w-100 numpad-btn" data-val="6">6</button></div>
                    </div>
                    <div class="row g-2 mb-2">
                        <div class="col-4"><button class="btn btn-outline-secondary w-100 numpad-btn" data-val="1">1</button></div>
                        <div class="col-4"><button class="btn btn-outline-secondary w-100 numpad-btn" data-val="2">2</button></div>
                        <div class="col-4"><button class="btn btn-outline-secondary w-100 numpad-btn" data-val="3">3</button></div>
                    </div>
                    <div class="row g-2">
                        <div class="col-4"><button class="btn btn-outline-secondary w-100 numpad-btn" data-val=",">,</button></div>
                        <div class="col-4"><button class="btn btn-outline-secondary w-100 numpad-btn" data-val="0">0</button></div>
                        <div class="col-4"><button class="btn btn-outline-warning w-100 numpad-btn" data-val="back"><i class="ri-delete-back-2-line fs-20"></i></button></div>
                    </div>
                </div>

                <!-- Payment Method -->
                <label class="form-label fs-12 fw-semibold mb-2">Payment Method</label>
                <div class="row g-2 mb-3">
                    <div class="col-4"><div class="method-card active" data-method="card"><i class="ri-bank-card-line text-primary"></i><span>Card</span></div></div>
                    <div class="col-4"><div class="method-card" data-method="cash"><i class="ri-money-euro-circle-line text-success"></i><span>Cash</span></div></div>
                    <div class="col-4"><div class="method-card" data-method="transfer"><i class="ri-bank-line text-info"></i><span>Transfer</span></div></div>
                    <div class="col-4"><div class="method-card" data-method="blik"><i class="ri-smartphone-line text-danger"></i><span>BLIK</span></div></div>
                    <div class="col-4"><div class="method-card" data-method="payu"><i class="ri-secure-payment-line text-warning"></i><span>PayU</span></div></div>
                    <div class="col-4"><div class="method-card" data-method="p24"><i class="ri-exchange-funds-line text-primary"></i><span>Przelewy24</span></div></div>
                    <div class="col-4"><div class="method-card" data-method="paypal"><i class="ri-paypal-line text-info"></i><span>PayPal</span></div></div>
                    <div class="col-4"><div class="method-card" data-method="wise"><i class="ri-global-line text-success"></i><span>Wise</span></div></div>
                    <div class="col-4"><div class="method-card" data-method="revolut"><i class="ri-refresh-line text-dark"></i><span>Revolut</span></div></div>
                </div>

                <!-- Description -->
                <div class="mb-3">
                    <label class="form-label fs-12 fw-semibold">Description</label>
                    <input type="text" class="form-control form-control-sm" id="posDescription" placeholder="Payment description...">
                </div>

                <!-- Charge Button -->
                <button class="btn btn-success w-100 charge-btn" id="chargeBtn">
                    <i class="ri-checkbox-circle-line me-2"></i>CHARGE <span id="chargeBtnAmount">0,00 PLN</span>
                </button>

                <!-- Clear -->
                <button class="btn btn-outline-secondary w-100 mt-2" id="clearPosBtn"><i class="ri-refresh-line me-1"></i>Clear</button>
            </div>
        </div>
    </div>

    <!-- ============ RIGHT: TRANSACTIONS ============ -->
    <div class="col-xl-7">
        <div class="card">
            <div class="card-header">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <h5 class="card-title mb-0">Recent Transactions</h5>
                    <div class="d-flex gap-2 flex-wrap">
                        <div class="d-flex gap-1" id="txnFilterPills">
                            <span class="txn-filter-pill active" data-filter="all">All <span class="badge bg-secondary ms-1" id="countAll">18</span></span>
                            <span class="txn-filter-pill" data-filter="completed">Completed <span class="badge bg-success ms-1" id="countCompleted">12</span></span>
                            <span class="txn-filter-pill" data-filter="pending">Pending <span class="badge bg-warning ms-1" id="countPending">3</span></span>
                            <span class="txn-filter-pill" data-filter="refunded">Refunded <span class="badge bg-info ms-1" id="countRefunded">2</span></span>
                            <span class="txn-filter-pill" data-filter="rejected">Rejected <span class="badge bg-danger ms-1" id="countRejected">1</span></span>
                        </div>
                    </div>
                </div>
                <div class="d-flex gap-2 mt-2 flex-wrap">
                    <input type="text" class="form-control form-control-sm" id="txnSearch" placeholder="Search by client, ID..." style="max-width:220px">
                    <input type="date" class="form-control form-control-sm" id="txnDateFrom" style="max-width:150px" title="From date">
                    <input type="date" class="form-control form-control-sm" id="txnDateTo" style="max-width:150px" title="To date">
                    <button class="btn btn-sm btn-outline-primary" id="exportCsvBtn"><i class="ri-download-line me-1"></i>Export CSV</button>
                    <button class="btn btn-sm btn-outline-success" id="dailyReportBtn"><i class="ri-file-chart-line me-1"></i>Daily Report</button>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" id="txnTable">
                        <thead class="table-light">
                            <tr>
                                <th>Transaction ID</th>
                                <th>Client</th>
                                <th>Case</th>
                                <th>Amount</th>
                                <th>Method</th>
                                <th>Status</th>
                                <th>Date</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="txnTableBody">
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer d-flex align-items-center justify-content-between">
                <span class="text-muted fs-12" id="txnShowing">Showing 18 transactions</span>
                <div class="d-flex gap-1">
                    <a href="crm-client-invoices" class="btn btn-sm btn-outline-primary"><i class="ri-file-list-3-line me-1"></i>Client Invoices</a>
                    <a href="finance-invoices" class="btn btn-sm btn-outline-secondary"><i class="ri-bill-line me-1"></i>All Invoices</a>
                    <a href="finance-payments" class="btn btn-sm btn-outline-secondary"><i class="ri-money-euro-circle-line me-1"></i>Payments</a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ============ VIEW TRANSACTION MODAL ============ -->
<div class="modal fade" id="viewTxnModal" tabindex="-1"><div class="modal-dialog"><div class="modal-content">
    <div class="modal-header bg-primary text-white">
        <h6 class="modal-title text-white"><i class="ri-file-list-3-line me-2"></i>Transaction Details</h6>
        <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
    </div>
    <div class="modal-body">
        <div class="row g-3">
            <div class="col-6"><label class="fs-11 text-muted">Transaction ID</label><p class="fw-semibold mb-0" id="viewTxnId"></p></div>
            <div class="col-6"><label class="fs-11 text-muted">Date & Time</label><p class="fw-semibold mb-0" id="viewTxnDate"></p></div>
            <div class="col-6"><label class="fs-11 text-muted">Client</label><p class="fw-semibold mb-0" id="viewTxnClient"></p></div>
            <div class="col-6"><label class="fs-11 text-muted">Case</label><p class="fw-semibold mb-0" id="viewTxnCase"></p></div>
            <div class="col-6"><label class="fs-11 text-muted">Amount</label><p class="fw-bold fs-5 text-success mb-0" id="viewTxnAmount"></p></div>
            <div class="col-6"><label class="fs-11 text-muted">Method</label><p class="fw-semibold mb-0" id="viewTxnMethod"></p></div>
            <div class="col-6"><label class="fs-11 text-muted">Status</label><p class="mb-0" id="viewTxnStatus"></p></div>
            <div class="col-6"><label class="fs-11 text-muted">Operator</label><p class="fw-semibold mb-0" id="viewTxnOperator"></p></div>
            <div class="col-12"><label class="fs-11 text-muted">Description</label><p class="mb-0" id="viewTxnDesc"></p></div>
        </div>
    </div>
    <div class="modal-footer">
        <button class="btn btn-sm btn-outline-primary" id="viewPrintReceiptBtn"><i class="ri-printer-line me-1"></i>Print Receipt</button>
        <button class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Close</button>
    </div>
</div></div></div>

<!-- ============ RECEIPT MODAL ============ -->
<div class="modal fade" id="receiptModal" tabindex="-1"><div class="modal-dialog modal-sm"><div class="modal-content">
    <div class="modal-header">
        <h6 class="modal-title"><i class="ri-receipt-line me-1"></i>Receipt</h6>
        <button class="btn-close" data-bs-dismiss="modal"></button>
    </div>
    <div class="modal-body" id="receiptBody" style="font-family:'Courier New',monospace; font-size:.85rem">
    </div>
    <div class="modal-footer">
        <button class="btn btn-sm btn-primary" onclick="window.print()"><i class="ri-printer-line me-1"></i>Print</button>
        <button class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Close</button>
    </div>
</div></div></div>

<!-- ============ REFUND MODAL ============ -->
<div class="modal fade" id="refundModal" tabindex="-1"><div class="modal-dialog"><div class="modal-content">
    <div class="modal-header bg-warning">
        <h6 class="modal-title"><i class="ri-refund-2-line me-1"></i>Process Refund</h6>
        <button class="btn-close" data-bs-dismiss="modal"></button>
    </div>
    <div class="modal-body">
        <div class="alert alert-warning fs-12 mb-3"><i class="ri-alert-line me-1"></i>You are about to refund this transaction. This action cannot be undone.</div>
        <div class="row g-3">
            <div class="col-6"><label class="fs-11 text-muted">Transaction</label><p class="fw-semibold mb-0" id="refundTxnId"></p></div>
            <div class="col-6"><label class="fs-11 text-muted">Original Amount</label><p class="fw-bold text-success mb-0" id="refundOrigAmount"></p></div>
            <div class="col-12">
                <label class="form-label fs-12 fw-semibold">Refund Amount (PLN)</label>
                <input type="number" class="form-control" id="refundAmount" step="0.01" min="0">
            </div>
            <div class="col-12">
                <label class="form-label fs-12 fw-semibold">Reason</label>
                <select class="form-select" id="refundReason">
                    <option value="client_request">Client Request</option>
                    <option value="service_issue">Service Issue</option>
                    <option value="duplicate">Duplicate Payment</option>
                    <option value="overcharge">Overcharge</option>
                    <option value="case_closed">Case Closed</option>
                    <option value="other">Other</option>
                </select>
            </div>
            <div class="col-12">
                <label class="form-label fs-12 fw-semibold">Notes</label>
                <textarea class="form-control" id="refundNotes" rows="2" placeholder="Additional notes..."></textarea>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button class="btn btn-warning" id="confirmRefundBtn"><i class="ri-refund-2-line me-1"></i>Process Refund</button>
    </div>
</div></div></div>

<!-- ============ NEW PAYMENT MODAL (from header button) ============ -->
<div class="modal fade" id="newPaymentModal" tabindex="-1"><div class="modal-dialog"><div class="modal-content">
    <div class="modal-header bg-success text-white">
        <h6 class="modal-title text-white"><i class="ri-add-circle-line me-2"></i>Quick New Payment</h6>
        <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
    </div>
    <div class="modal-body">
        <div class="row g-3">
            <div class="col-6">
                <label class="form-label fs-12 fw-semibold">Client</label>
                <select class="form-select" id="newPayClient">
                    <option value="">-- Select --</option>
                    <option>Oleksandr Petrov</option><option>Maria Ivanova</option>
                    <option>Viktor Kovalenko</option><option>Anna Shevchenko</option>
                    <option>Dmytro Bondarenko</option><option>Iryna Melnyk</option>
                    <option>Pavlo Tkachenko</option><option>Natalia Moroz</option>
                </select>
            </div>
            <div class="col-6">
                <label class="form-label fs-12 fw-semibold">Case</label>
                <select class="form-select" id="newPayCase">
                    <option value="">-- Select --</option>
                    <option>#WC-0189</option><option>#WC-0188</option>
                    <option>#WC-0185</option><option>#WC-0190</option>
                    <option>#WC-0191</option><option>#WC-0192</option>
                </select>
            </div>
            <div class="col-6">
                <label class="form-label fs-12 fw-semibold">Amount (PLN)</label>
                <input type="number" class="form-control" id="newPayAmount" step="0.01" min="0" placeholder="0,00">
            </div>
            <div class="col-6">
                <label class="form-label fs-12 fw-semibold">Method</label>
                <select class="form-select" id="newPayMethod">
                    <option value="card">Card</option><option value="cash">Cash</option>
                    <option value="transfer">Transfer</option><option value="blik">BLIK</option>
                    <option value="payu">PayU</option><option value="p24">Przelewy24</option>
                    <option value="paypal">PayPal</option><option value="wise">Wise</option>
                    <option value="revolut">Revolut</option>
                </select>
            </div>
            <div class="col-12">
                <label class="form-label fs-12 fw-semibold">Description</label>
                <input type="text" class="form-control" id="newPayDesc" placeholder="Payment description...">
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button class="btn btn-success" id="saveNewPayBtn"><i class="ri-checkbox-circle-line me-1"></i>Process Payment</button>
    </div>
</div></div></div>

<!-- ============ DAILY REPORT MODAL ============ -->
<div class="modal fade" id="dailyReportModal" tabindex="-1"><div class="modal-dialog modal-lg"><div class="modal-content">
    <div class="modal-header bg-success text-white">
        <h6 class="modal-title text-white"><i class="ri-file-chart-line me-2"></i>Daily Report &mdash; <span id="reportDate"></span></h6>
        <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
    </div>
    <div class="modal-body">
        <div class="row g-3 mb-4">
            <div class="col-md-3 col-6">
                <div class="bg-success-subtle rounded-3 p-3 text-center">
                    <div class="fs-11 text-muted mb-1">Total Revenue</div>
                    <div class="fw-bold fs-5 text-success" id="reportRevenue">8 450,00 PLN</div>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="bg-primary-subtle rounded-3 p-3 text-center">
                    <div class="fs-11 text-muted mb-1">Transactions</div>
                    <div class="fw-bold fs-5 text-primary" id="reportTxns">14</div>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="bg-warning-subtle rounded-3 p-3 text-center">
                    <div class="fs-11 text-muted mb-1">Pending</div>
                    <div class="fw-bold fs-5 text-warning" id="reportPending">3</div>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="bg-info-subtle rounded-3 p-3 text-center">
                    <div class="fs-11 text-muted mb-1">Refunds</div>
                    <div class="fw-bold fs-5 text-info" id="reportRefunds">1 200,00 PLN</div>
                </div>
            </div>
        </div>
        <h6 class="fw-semibold mb-2">Revenue by Payment Method</h6>
        <div class="table-responsive mb-3">
            <table class="table table-sm table-bordered">
                <thead class="table-light"><tr><th>Method</th><th>Count</th><th>Amount</th><th>Share</th></tr></thead>
                <tbody>
                    <tr><td><i class="ri-bank-card-line me-1 text-primary"></i>Card</td><td>5</td><td>3 200,00 PLN</td><td><div class="progress" style="height:6px"><div class="progress-bar bg-primary" style="width:38%"></div></div></td></tr>
                    <tr><td><i class="ri-money-euro-circle-line me-1 text-success"></i>Cash</td><td>3</td><td>1 800,00 PLN</td><td><div class="progress" style="height:6px"><div class="progress-bar bg-success" style="width:21%"></div></div></td></tr>
                    <tr><td><i class="ri-bank-line me-1 text-info"></i>Transfer</td><td>3</td><td>2 250,00 PLN</td><td><div class="progress" style="height:6px"><div class="progress-bar bg-info" style="width:27%"></div></div></td></tr>
                    <tr><td><i class="ri-smartphone-line me-1 text-danger"></i>BLIK</td><td>2</td><td>900,00 PLN</td><td><div class="progress" style="height:6px"><div class="progress-bar bg-danger" style="width:11%"></div></div></td></tr>
                    <tr><td><i class="ri-secure-payment-line me-1 text-warning"></i>Other</td><td>1</td><td>300,00 PLN</td><td><div class="progress" style="height:6px"><div class="progress-bar bg-warning" style="width:3%"></div></div></td></tr>
                </tbody>
            </table>
        </div>
        <h6 class="fw-semibold mb-2">Top Clients</h6>
        <div class="d-flex gap-2 flex-wrap">
            <span class="badge bg-primary-subtle text-primary p-2">Oleksandr Petrov — 2 500,00 PLN</span>
            <span class="badge bg-success-subtle text-success p-2">Viktor Kovalenko — 1 800,00 PLN</span>
            <span class="badge bg-info-subtle text-info p-2">Anna Shevchenko — 1 500,00 PLN</span>
        </div>
    </div>
    <div class="modal-footer">
        <button class="btn btn-sm btn-outline-primary" onclick="window.print()"><i class="ri-printer-line me-1"></i>Print Report</button>
        <button class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Close</button>
    </div>
</div></div></div>

<script>
document.addEventListener('DOMContentLoaded', function(){

    // ============ DEMO DATA ============
    const CLIENT_CASES = {
        cl1: [{id:'#WC-0189',type:'Temporary Residence'},{id:'#WC-0201',type:'Work Permit'}],
        cl2: [{id:'#WC-0188',type:'Permanent Residence'}],
        cl3: [{id:'#WC-0185',type:'Work Permit'},{id:'#WC-0199',type:'Blue Card EU'}],
        cl4: [{id:'#WC-0190',type:'Family Reunification'}],
        cl5: [{id:'#WC-0191',type:'Temporary Residence'}],
        cl6: [{id:'#WC-0192',type:'Citizenship'}],
        cl7: [{id:'#WC-0193',type:'Temporary Residence'}],
        cl8: [{id:'#WC-0194',type:'Appeal'}]
    };

    const CLIENT_NAMES = {
        cl1:'Oleksandr Petrov',cl2:'Maria Ivanova',cl3:'Viktor Kovalenko',
        cl4:'Anna Shevchenko',cl5:'Dmytro Bondarenko',cl6:'Iryna Melnyk',
        cl7:'Pavlo Tkachenko',cl8:'Natalia Moroz'
    };

    const METHOD_ICONS = {
        card:'ri-bank-card-line text-primary',cash:'ri-money-euro-circle-line text-success',
        transfer:'ri-bank-line text-info',blik:'ri-smartphone-line text-danger',
        payu:'ri-secure-payment-line text-warning',p24:'ri-exchange-funds-line text-primary',
        paypal:'ri-paypal-line text-info',wise:'ri-global-line text-success',
        revolut:'ri-refresh-line text-dark'
    };
    const METHOD_LABELS = {
        card:'Card',cash:'Cash',transfer:'Transfer',blik:'BLIK',
        payu:'PayU',p24:'Przelewy24',paypal:'PayPal',wise:'Wise',revolut:'Revolut'
    };

    const STATUS_BADGES = {
        completed:'<span class="badge bg-success-subtle text-success">Completed</span>',
        pending:'<span class="badge bg-warning-subtle text-warning">Pending</span>',
        refunded:'<span class="badge bg-info-subtle text-info">Refunded</span>',
        rejected:'<span class="badge bg-danger-subtle text-danger">Rejected</span>'
    };

    let transactions = [
        {id:'TXN-0901',client:'Oleksandr Petrov',clientId:'cl1',caseId:'#WC-0189',amount:2500,method:'card',status:'completed',date:'2026-03-02 09:30',operator:'Admin',desc:'Consultation fee + document preparation'},
        {id:'TXN-0900',client:'Maria Ivanova',clientId:'cl2',caseId:'#WC-0188',amount:1200,method:'cash',status:'pending',date:'2026-03-02 10:15',operator:'Admin',desc:'First installment - permanent residence'},
        {id:'TXN-0899',client:'Viktor Kovalenko',clientId:'cl3',caseId:'#WC-0185',amount:1800,method:'transfer',status:'completed',date:'2026-03-02 11:00',operator:'Admin',desc:'Work permit application fee'},
        {id:'TXN-0898',client:'Anna Shevchenko',clientId:'cl4',caseId:'#WC-0190',amount:950,method:'blik',status:'completed',date:'2026-03-02 11:45',operator:'Manager 1',desc:'Family reunification documents'},
        {id:'TXN-0897',client:'Dmytro Bondarenko',clientId:'cl5',caseId:'#WC-0191',amount:600,method:'payu',status:'pending',date:'2026-03-02 12:30',operator:'Admin',desc:'Translation services'},
        {id:'TXN-0896',client:'Oleksandr Petrov',clientId:'cl1',caseId:'#WC-0201',amount:1500,method:'p24',status:'completed',date:'2026-03-02 13:00',operator:'Admin',desc:'Work permit processing'},
        {id:'TXN-0895',client:'Iryna Melnyk',clientId:'cl6',caseId:'#WC-0192',amount:3200,method:'card',status:'completed',date:'2026-03-02 13:45',operator:'Manager 2',desc:'Citizenship application - full package'},
        {id:'TXN-0894',client:'Pavlo Tkachenko',clientId:'cl7',caseId:'#WC-0193',amount:800,method:'cash',status:'completed',date:'2026-03-02 14:15',operator:'Admin',desc:'Temporary residence renewal'},
        {id:'TXN-0893',client:'Natalia Moroz',clientId:'cl8',caseId:'#WC-0194',amount:1200,method:'transfer',status:'refunded',date:'2026-03-01 16:30',operator:'Admin',desc:'Appeal preparation - REFUNDED'},
        {id:'TXN-0892',client:'Viktor Kovalenko',clientId:'cl3',caseId:'#WC-0199',amount:2000,method:'wise',status:'completed',date:'2026-03-01 15:00',operator:'Manager 1',desc:'Blue Card EU application'},
        {id:'TXN-0891',client:'Maria Ivanova',clientId:'cl2',caseId:'#WC-0188',amount:800,method:'revolut',status:'pending',date:'2026-03-01 14:00',operator:'Admin',desc:'Second installment - permanent residence'},
        {id:'TXN-0890',client:'Anna Shevchenko',clientId:'cl4',caseId:'#WC-0190',amount:450,method:'blik',status:'completed',date:'2026-03-01 12:30',operator:'Admin',desc:'Notarial fees'},
        {id:'TXN-0889',client:'Dmytro Bondarenko',clientId:'cl5',caseId:'#WC-0191',amount:1200,method:'card',status:'refunded',date:'2026-03-01 10:00',operator:'Admin',desc:'Overpayment refund'},
        {id:'TXN-0888',client:'Oleksandr Petrov',clientId:'cl1',caseId:'#WC-0189',amount:300,method:'cash',status:'completed',date:'2026-02-28 16:00',operator:'Manager 2',desc:'Courier and stamp fees'},
        {id:'TXN-0887',client:'Pavlo Tkachenko',clientId:'cl7',caseId:'#WC-0193',amount:1500,method:'transfer',status:'completed',date:'2026-02-28 14:30',operator:'Admin',desc:'Full case service payment'},
        {id:'TXN-0886',client:'Iryna Melnyk',clientId:'cl6',caseId:'#WC-0192',amount:500,method:'paypal',status:'completed',date:'2026-02-28 11:00',operator:'Admin',desc:'Document translation fee'},
        {id:'TXN-0885',client:'Natalia Moroz',clientId:'cl8',caseId:'#WC-0194',amount:750,method:'card',status:'rejected',date:'2026-02-28 09:00',operator:'Admin',desc:'Card declined - retry needed'},
        {id:'TXN-0884',client:'Viktor Kovalenko',clientId:'cl3',caseId:'#WC-0185',amount:600,method:'blik',status:'completed',date:'2026-02-27 15:00',operator:'Manager 1',desc:'Supplementary documents'}
    ];

    let txnCounter = 902;

    // ============ CLIENT → CASE LINK ============
    const posClient = document.getElementById('posClient');
    const posCase = document.getElementById('posCase');
    posClient.addEventListener('change', function(){
        posCase.innerHTML = '<option value="">-- Select Case --</option>';
        const cl = this.value;
        if(cl && CLIENT_CASES[cl]){
            CLIENT_CASES[cl].forEach(c => {
                posCase.innerHTML += `<option value="${c.id}">${c.id} — ${c.type}</option>`;
            });
        }
    });

    // ============ NUMPAD ============
    let amountStr = '';
    function updateDisplay(){
        const num = parseFloat(amountStr || '0') / 100;
        const formatted = num.toLocaleString('pl-PL',{minimumFractionDigits:2,maximumFractionDigits:2});
        document.getElementById('posAmountDisplay').innerHTML = formatted + ' <small>PLN</small>';
        document.getElementById('chargeBtnAmount').textContent = formatted + ' PLN';
    }

    document.querySelectorAll('.numpad-btn').forEach(btn => {
        btn.addEventListener('click', function(){
            const v = this.dataset.val;
            if(v === 'back'){ amountStr = amountStr.slice(0,-1); }
            else if(v === ','){ /* ignore comma, we handle cents via integer */ }
            else { if(amountStr.length < 8) amountStr += v; }
            updateDisplay();
        });
    });

    // ============ QUICK AMOUNTS ============
    document.querySelectorAll('.quick-amount').forEach(el => {
        el.addEventListener('click', function(){
            amountStr = (parseInt(this.dataset.amount) * 100).toString();
            updateDisplay();
        });
    });

    // ============ PAYMENT METHOD SELECT ============
    let selectedMethod = 'card';
    document.querySelectorAll('.method-card').forEach(mc => {
        mc.addEventListener('click', function(){
            document.querySelectorAll('.method-card').forEach(m=>m.classList.remove('active'));
            this.classList.add('active');
            selectedMethod = this.dataset.method;
        });
    });

    // ============ CLEAR ============
    document.getElementById('clearPosBtn').addEventListener('click', function(){
        amountStr = '';
        updateDisplay();
        posClient.value = '';
        posCase.innerHTML = '<option value="">-- Select Case --</option>';
        document.getElementById('posDescription').value = '';
        selectedMethod = 'card';
        document.querySelectorAll('.method-card').forEach(m=>m.classList.remove('active'));
        document.querySelector('[data-method="card"]').classList.add('active');
    });

    // ============ CHARGE ============
    document.getElementById('chargeBtn').addEventListener('click', function(){
        const amount = parseFloat(amountStr || '0') / 100;
        if(amount <= 0){ showToast('Enter amount','warning'); return; }
        const client = posClient.value;
        if(!client){ showToast('Select a client','warning'); return; }
        const caseVal = posCase.value || '—';
        const desc = document.getElementById('posDescription').value || 'POS Payment';
        const now = new Date();
        const dateStr = now.toISOString().slice(0,10)+' '+now.toTimeString().slice(0,5);

        const newTxn = {
            id: 'TXN-0'+txnCounter++,
            client: CLIENT_NAMES[client],
            clientId: client,
            caseId: caseVal,
            amount: amount,
            method: selectedMethod,
            status: (selectedMethod==='cash'||selectedMethod==='card')?'completed':'pending',
            date: dateStr,
            operator: 'Admin',
            desc: desc
        };
        transactions.unshift(newTxn);
        renderTable();
        updateCounts();

        // Clear
        amountStr = '';
        updateDisplay();
        posClient.value = '';
        posCase.innerHTML = '<option value="">-- Select Case --</option>';
        document.getElementById('posDescription').value = '';
        showToast(`Payment ${newTxn.amount.toLocaleString('pl-PL',{minimumFractionDigits:2})} PLN processed — #${newTxn.id}`, 'success');
    });

    // ============ RENDER TRANSACTIONS TABLE ============
    function renderTable(filter, search){
        filter = filter || 'all';
        search = (search || '').toLowerCase();
        const tbody = document.getElementById('txnTableBody');
        tbody.innerHTML = '';
        let count = 0;
        transactions.forEach(t => {
            if(filter !== 'all' && t.status !== filter) return;
            if(search && !t.client.toLowerCase().includes(search) && !t.id.toLowerCase().includes(search) && !t.caseId.toLowerCase().includes(search)) return;
            count++;
            const amtFormatted = t.amount.toLocaleString('pl-PL',{minimumFractionDigits:2,maximumFractionDigits:2});
            let actions = '';
            if(t.status === 'completed'){
                actions = `
                    <div class="d-flex gap-1 justify-content-center">
                        <button class="btn btn-sm btn-subtle-primary action-view" title="View"><i class="ri-eye-line"></i></button>
                        <button class="btn btn-sm btn-subtle-success action-receipt" title="Receipt"><i class="ri-receipt-line"></i></button>
                        <button class="btn btn-sm btn-subtle-warning action-refund" title="Refund"><i class="ri-refund-2-line"></i></button>
                    </div>`;
            } else if(t.status === 'pending'){
                actions = `
                    <div class="d-flex gap-1 justify-content-center">
                        <button class="btn btn-sm btn-subtle-primary action-view" title="View"><i class="ri-eye-line"></i></button>
                        <button class="btn btn-sm btn-subtle-success action-approve" title="Approve"><i class="ri-check-line"></i></button>
                        <button class="btn btn-sm btn-subtle-danger action-reject" title="Reject"><i class="ri-close-line"></i></button>
                    </div>`;
            } else {
                actions = `
                    <div class="d-flex gap-1 justify-content-center">
                        <button class="btn btn-sm btn-subtle-primary action-view" title="View"><i class="ri-eye-line"></i></button>
                        <button class="btn btn-sm btn-subtle-success action-receipt" title="Receipt"><i class="ri-receipt-line"></i></button>
                    </div>`;
            }

            tbody.innerHTML += `
                <tr data-txn-id="${t.id}" data-client="${t.client}" data-client-id="${t.clientId}" data-case="${t.caseId}"
                    data-amount="${amtFormatted}" data-amount-raw="${t.amount}" data-method="${t.method}" data-status="${t.status}"
                    data-date="${t.date}" data-operator="${t.operator}" data-desc="${t.desc}">
                    <td><a href="#" class="fw-semibold text-body action-view">#${t.id}</a></td>
                    <td><a href="crm-client-invoices?client=${t.clientId}" class="text-body">${t.client}</a></td>
                    <td>${t.caseId}</td>
                    <td class="fw-semibold ${t.status==='refunded'?'text-info':t.status==='rejected'?'text-danger':'text-success'}">${amtFormatted} PLN</td>
                    <td><i class="${METHOD_ICONS[t.method]} me-1"></i>${METHOD_LABELS[t.method]}</td>
                    <td>${STATUS_BADGES[t.status]}</td>
                    <td class="text-muted fs-12">${t.date}</td>
                    <td>${actions}</td>
                </tr>`;
        });
        document.getElementById('txnShowing').textContent = `Showing ${count} transactions`;
    }

    function updateCounts(){
        let all=0,comp=0,pend=0,ref=0,rej=0;
        transactions.forEach(t=>{all++;if(t.status==='completed')comp++;if(t.status==='pending')pend++;if(t.status==='refunded')ref++;if(t.status==='rejected')rej++;});
        document.getElementById('countAll').textContent=all;
        document.getElementById('countCompleted').textContent=comp;
        document.getElementById('countPending').textContent=pend;
        document.getElementById('countRefunded').textContent=ref;
        document.getElementById('countRejected').textContent=rej;
    }

    // Initial render
    renderTable();

    // ============ FILTER PILLS ============
    let currentFilter = 'all';
    document.getElementById('txnFilterPills').addEventListener('click', function(e){
        const pill = e.target.closest('.txn-filter-pill');
        if(!pill) return;
        document.querySelectorAll('.txn-filter-pill').forEach(p=>p.classList.remove('active'));
        pill.classList.add('active');
        currentFilter = pill.dataset.filter;
        renderTable(currentFilter, document.getElementById('txnSearch').value);
    });

    // ============ SEARCH ============
    document.getElementById('txnSearch').addEventListener('input', function(){
        renderTable(currentFilter, this.value);
    });

    // ============ TABLE ACTIONS (event delegation) ============
    document.getElementById('txnTableBody').addEventListener('click', function(e){
        const btn = e.target.closest('button') || e.target.closest('a.action-view');
        if(!btn) return;
        e.preventDefault();
        const row = btn.closest('tr');
        const d = row.dataset;

        if(btn.classList.contains('action-view')){
            document.getElementById('viewTxnId').textContent = '#'+d.txnId;
            document.getElementById('viewTxnDate').textContent = d.date;
            document.getElementById('viewTxnClient').textContent = d.client;
            document.getElementById('viewTxnCase').textContent = d.case;
            document.getElementById('viewTxnAmount').textContent = d.amount + ' PLN';
            document.getElementById('viewTxnMethod').innerHTML = `<i class="${METHOD_ICONS[d.method]} me-1"></i>${METHOD_LABELS[d.method]}`;
            document.getElementById('viewTxnStatus').innerHTML = STATUS_BADGES[d.status];
            document.getElementById('viewTxnOperator').textContent = d.operator;
            document.getElementById('viewTxnDesc').textContent = d.desc;
            new bootstrap.Modal(document.getElementById('viewTxnModal')).show();
        }

        if(btn.classList.contains('action-receipt')){
            showReceipt(d);
        }

        if(btn.classList.contains('action-approve')){
            const txn = transactions.find(t=>t.id===d.txnId);
            if(txn){txn.status='completed'; renderTable(currentFilter,document.getElementById('txnSearch').value); updateCounts(); showToast('#'+d.txnId+' approved','success');}
        }

        if(btn.classList.contains('action-reject')){
            const txn = transactions.find(t=>t.id===d.txnId);
            if(txn){txn.status='rejected'; renderTable(currentFilter,document.getElementById('txnSearch').value); updateCounts(); showToast('#'+d.txnId+' rejected','danger');}
        }

        if(btn.classList.contains('action-refund')){
            document.getElementById('refundTxnId').textContent = '#'+d.txnId;
            document.getElementById('refundOrigAmount').textContent = d.amount + ' PLN';
            document.getElementById('refundAmount').value = d.amountRaw;
            document.getElementById('refundModal').dataset.txnId = d.txnId;
            new bootstrap.Modal(document.getElementById('refundModal')).show();
        }
    });

    // ============ VIEW → RECEIPT ============
    document.getElementById('viewPrintReceiptBtn').addEventListener('click', function(){
        const modal = bootstrap.Modal.getInstance(document.getElementById('viewTxnModal'));
        const d = {
            txnId: document.getElementById('viewTxnId').textContent.replace('#',''),
            client: document.getElementById('viewTxnClient').textContent,
            case: document.getElementById('viewTxnCase').textContent,
            amount: document.getElementById('viewTxnAmount').textContent.replace(' PLN',''),
            method: 'card',
            date: document.getElementById('viewTxnDate').textContent,
            desc: document.getElementById('viewTxnDesc').textContent
        };
        if(modal) modal.hide();
        setTimeout(()=>showReceipt(d), 300);
    });

    // ============ RECEIPT ============
    function showReceipt(d){
        const body = document.getElementById('receiptBody');
        body.innerHTML = `
            <div class="receipt-header">
                <div class="fw-bold fs-5">WinCase</div>
                <div class="fs-12 text-muted">Immigration Bureau</div>
                <div class="fs-12 text-muted">ul. Marszalkowska 1, 00-001 Warszawa</div>
                <div class="fs-12 text-muted">NIP: 5252811122 | Tel: +48 579 266 493</div>
            </div>
            <div class="receipt-line"><span>Transaction:</span><span class="fw-semibold">#${d.txnId}</span></div>
            <div class="receipt-line"><span>Date:</span><span>${d.date}</span></div>
            <div class="receipt-line"><span>Client:</span><span>${d.client}</span></div>
            <div class="receipt-line"><span>Case:</span><span>${d.case || '—'}</span></div>
            <div class="receipt-line"><span>Method:</span><span>${METHOD_LABELS[d.method]||d.method||'Card'}</span></div>
            <div class="receipt-line"><span>Description:</span><span>${d.desc||'—'}</span></div>
            <div class="receipt-line total"><span>TOTAL:</span><span>${d.amount} PLN</span></div>
            <div class="text-center mt-3 pt-2" style="border-top:2px dashed #dee2e6">
                <div class="fs-12 text-muted">Thank you for your payment!</div>
                <div class="fs-11 text-muted">www.wincase.eu</div>
            </div>
        `;
        new bootstrap.Modal(document.getElementById('receiptModal')).show();
    }

    // ============ REFUND ============
    document.getElementById('confirmRefundBtn').addEventListener('click', function(){
        const txnId = document.getElementById('refundModal').dataset.txnId;
        const txn = transactions.find(t=>t.id===txnId);
        if(txn){
            txn.status = 'refunded';
            txn.desc += ' [REFUNDED]';
            renderTable(currentFilter, document.getElementById('txnSearch').value);
            updateCounts();
        }
        bootstrap.Modal.getInstance(document.getElementById('refundModal')).hide();
        showToast('#'+txnId+' refunded successfully','info');
    });

    // ============ NEW PAYMENT (header modal) ============
    document.getElementById('saveNewPayBtn').addEventListener('click', function(){
        const client = document.getElementById('newPayClient').value;
        const caseId = document.getElementById('newPayCase').value;
        const amount = parseFloat(document.getElementById('newPayAmount').value);
        const method = document.getElementById('newPayMethod').value;
        const desc = document.getElementById('newPayDesc').value;
        if(!client||!amount){ showToast('Fill client and amount','warning'); return; }

        const now = new Date();
        const dateStr = now.toISOString().slice(0,10)+' '+now.toTimeString().slice(0,5);
        const clId = Object.keys(CLIENT_NAMES).find(k=>CLIENT_NAMES[k]===client) || 'cl1';

        transactions.unshift({
            id:'TXN-0'+txnCounter++,client:client,clientId:clId,
            caseId:caseId||'—',amount:amount,method:method,
            status:(method==='cash'||method==='card')?'completed':'pending',
            date:dateStr,operator:'Admin',desc:desc||'Quick payment'
        });
        renderTable(currentFilter,document.getElementById('txnSearch').value);
        updateCounts();
        bootstrap.Modal.getInstance(document.getElementById('newPaymentModal')).hide();
        showToast('Payment processed: '+amount.toLocaleString('pl-PL',{minimumFractionDigits:2})+' PLN','success');
    });

    // ============ EXPORT CSV ============
    document.getElementById('exportCsvBtn').addEventListener('click', function(){
        let csv = 'ID,Client,Case,Amount,Method,Status,Date,Description\n';
        transactions.forEach(t=>{
            csv += `${t.id},"${t.client}",${t.caseId},${t.amount},${METHOD_LABELS[t.method]},${t.status},${t.date},"${t.desc}"\n`;
        });
        const blob = new Blob([csv],{type:'text/csv'});
        const url = URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url; a.download = 'transactions_'+new Date().toISOString().slice(0,10)+'.csv';
        a.click(); URL.revokeObjectURL(url);
        showToast('CSV exported','success');
    });

    // ============ DAILY REPORT ============
    document.getElementById('dailyReportBtn').addEventListener('click', function(){
        const today = new Date().toLocaleDateString('pl-PL',{weekday:'long',year:'numeric',month:'long',day:'numeric'});
        document.getElementById('reportDate').textContent = today;
        new bootstrap.Modal(document.getElementById('dailyReportModal')).show();
    });

    // ============ TOAST ============
    function showToast(msg, type){
        type = type || 'info';
        const colors = {success:'#198754',danger:'#dc3545',warning:'#ffc107',info:'#0dcaf0',primary:'#845adf'};
        const toast = document.createElement('div');
        toast.style.cssText = 'position:fixed;top:20px;right:20px;z-index:9999;padding:14px 24px;border-radius:10px;color:#fff;font-weight:600;font-size:.9rem;box-shadow:0 4px 12px rgba(0,0,0,.15);transition:opacity .3s;background:'+(colors[type]||colors.info);
        toast.textContent = msg;
        document.body.appendChild(toast);
        setTimeout(()=>{toast.style.opacity='0';setTimeout(()=>toast.remove(),300);},3000);
    }

});
</script>
@endsection
