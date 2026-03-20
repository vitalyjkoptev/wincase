@extends('partials.layouts.master')

@section('title', 'Client Finance | WinCase CRM')
@section('sub-title', 'Client Finance')
@section('sub-title-lang', 'wc-client-finance')
@section('pagetitle', 'CRM')
@section('pagetitle-lang', 'wc-title-crm')

@section('content')
<!-- Client Finance Header -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                    <div class="d-flex align-items-center gap-3">
                        <a href="crm-clients" class="btn btn-subtle-secondary btn-sm"><i class="ri-arrow-left-line"></i></a>
                        <div class="avatar avatar-md avatar-rounded bg-primary-subtle text-primary">
                            <span class="fs-20" id="clientInitials">OP</span>
                        </div>
                        <div>
                            <h5 class="mb-0" id="clientName">Oleksandr Petrov</h5>
                            <div class="d-flex gap-2 mt-1">
                                <span class="badge bg-success-subtle text-success" id="clientStatus">Active</span>
                                <span class="badge bg-info-subtle text-info" id="clientNationality">Ukrainian</span>
                                <span class="text-muted fs-12" id="clientPhone">+48 512 345 678</span>
                                <span class="text-muted fs-12" id="clientEmail">petrov@email.com</span>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="crm-client-detail" class="btn btn-subtle-info btn-sm" id="linkProfile"><i class="ri-user-3-line me-1"></i>Profile</a>
                        <a href="crm-cases" class="btn btn-subtle-primary btn-sm"><i class="ri-briefcase-line me-1"></i>Cases</a>
                        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#newInvoiceModal"><i class="ri-add-line me-1"></i>New Invoice</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Finance Summary Cards -->
<div class="row">
    <div class="col-xl-3 col-md-6">
        <div class="card card-h-100">
            <div class="card-body">
                <div class="d-flex align-items-center gap-3">
                    <div class="avatar avatar-sm bg-primary-subtle text-primary rounded-2">
                        <i class="ri-file-text-line fs-18"></i>
                    </div>
                    <div>
                        <p class="text-muted mb-0 fs-13">Total Contract Value</p>
                        <h4 class="mb-0 fw-semibold" id="totalContract">PLN 14,800.00</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card card-h-100">
            <div class="card-body">
                <div class="d-flex align-items-center gap-3">
                    <div class="avatar avatar-sm bg-success-subtle text-success rounded-2">
                        <i class="ri-checkbox-circle-line fs-18"></i>
                    </div>
                    <div>
                        <p class="text-muted mb-0 fs-13">Total Paid</p>
                        <h4 class="mb-0 fw-semibold text-success" id="totalPaid">PLN 9,600.00</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card card-h-100">
            <div class="card-body">
                <div class="d-flex align-items-center gap-3">
                    <div class="avatar avatar-sm bg-danger-subtle text-danger rounded-2">
                        <i class="ri-error-warning-line fs-18"></i>
                    </div>
                    <div>
                        <p class="text-muted mb-0 fs-13">Outstanding Debt</p>
                        <h4 class="mb-0 fw-semibold text-danger" id="totalDebt">PLN 5,200.00</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card card-h-100">
            <div class="card-body">
                <div class="d-flex align-items-center gap-3">
                    <div class="avatar avatar-sm bg-info-subtle text-info rounded-2">
                        <i class="ri-percent-line fs-18"></i>
                    </div>
                    <div>
                        <p class="text-muted mb-0 fs-13">Payment Progress</p>
                        <h4 class="mb-0 fw-semibold" id="paymentPercent">64.9%</h4>
                        <div class="progress mt-1" style="height: 4px; width: 100px;">
                            <div class="progress-bar bg-success" id="paymentProgressBar" style="width: 64.9%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- LEFT: Invoices + Payments -->
    <div class="col-xl-8">
        <!-- Invoices Table -->
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h5 class="card-title mb-0"><i class="ri-file-list-3-line me-1"></i>Invoices</h5>
                <div class="d-flex gap-2">
                    <select class="form-select form-select-sm" style="width: 140px;" id="invoiceStatusFilter">
                        <option selected>All Statuses</option>
                        <option>Paid</option>
                        <option>Partially Paid</option>
                        <option>Unpaid</option>
                        <option>Overdue</option>
                    </select>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" id="invoicesTable">
                        <thead class="table-light">
                            <tr>
                                <th>Invoice #</th>
                                <th>Case</th>
                                <th>Description</th>
                                <th>Issue Date</th>
                                <th>Due Date</th>
                                <th>Amount</th>
                                <th>Paid</th>
                                <th>Balance</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="invoicesTableBody">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Payment History -->
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h5 class="card-title mb-0"><i class="ri-exchange-dollar-line me-1"></i>Payment History</h5>
                <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#recordPaymentModal"><i class="ri-add-line me-1"></i>Record Payment</button>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Date</th>
                                <th>Invoice</th>
                                <th>Case</th>
                                <th>Amount</th>
                                <th>Method</th>
                                <th>Receipt</th>
                                <th>Status</th>
                                <th>Note</th>
                            </tr>
                        </thead>
                        <tbody id="paymentsTableBody">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Upcoming Payments / Schedule -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0"><i class="ri-calendar-todo-line me-1"></i>Payment Schedule</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Due Date</th>
                                <th>Invoice</th>
                                <th>Case</th>
                                <th>Amount Due</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="scheduleTableBody">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- RIGHT: Financial Profile + Payment Methods -->
    <div class="col-xl-4">
        <!-- Financial Profile -->
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h5 class="card-title mb-0"><i class="ri-bank-card-line me-1"></i>Financial Profile</h5>
                <button class="btn btn-subtle-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editFinanceProfileModal"><i class="ri-settings-3-line"></i></button>
            </div>
            <div class="card-body p-0">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex justify-content-between">
                        <span class="text-muted">Currency</span>
                        <span class="fw-semibold" id="fpCurrency">PLN (Polish Zloty)</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between">
                        <span class="text-muted">Payment Terms</span>
                        <span class="fw-semibold" id="fpTerms">Net 14 days</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between">
                        <span class="text-muted">Installments Allowed</span>
                        <span class="fw-semibold text-success" id="fpInstallments"><i class="ri-check-line"></i> Yes</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between">
                        <span class="text-muted">Max Installments</span>
                        <span class="fw-semibold" id="fpMaxInstallments">4</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between">
                        <span class="text-muted">Auto Reminders</span>
                        <span class="fw-semibold text-success" id="fpReminders"><i class="ri-check-line"></i> Enabled</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between">
                        <span class="text-muted">Discount</span>
                        <span class="fw-semibold" id="fpDiscount">—</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between">
                        <span class="text-muted">Tax ID (NIP)</span>
                        <span class="fw-semibold" id="fpNip">—</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between">
                        <span class="text-muted">Invoice Language</span>
                        <span class="fw-semibold" id="fpInvoiceLang">Polish</span>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Payment Methods -->
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h5 class="card-title mb-0"><i class="ri-wallet-3-line me-1"></i>Payment Methods</h5>
                <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addPaymentMethodModal"><i class="ri-add-line"></i></button>
            </div>
            <div class="card-body" id="paymentMethodsList">
            </div>
        </div>

        <!-- Debt by Case -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0"><i class="ri-pie-chart-line me-1"></i>Debt by Case</h5>
            </div>
            <div class="card-body p-0" id="debtByCaseList">
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0"><i class="ri-flashlight-line me-1"></i>Quick Actions</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <button class="btn btn-subtle-primary btn-sm text-start" data-bs-toggle="modal" data-bs-target="#newInvoiceModal"><i class="ri-file-add-line me-2"></i>Generate New Invoice</button>
                    <button class="btn btn-subtle-success btn-sm text-start" data-bs-toggle="modal" data-bs-target="#recordPaymentModal"><i class="ri-money-dollar-circle-line me-2"></i>Record Payment</button>
                    <button class="btn btn-subtle-warning btn-sm text-start" id="btnSendReminder"><i class="ri-notification-3-line me-2"></i>Send Payment Reminder</button>
                    <button class="btn btn-subtle-info btn-sm text-start" id="btnExportStatement"><i class="ri-download-2-line me-2"></i>Export Financial Statement</button>
                    <button class="btn btn-subtle-secondary btn-sm text-start" id="btnPrintAll"><i class="ri-printer-line me-2"></i>Print All Invoices</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ============================================ -->
<!-- NEW INVOICE MODAL -->
<!-- ============================================ -->
<div class="modal fade" id="newInvoiceModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="ri-file-add-line me-2"></i>Generate New Invoice</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Case <span class="text-danger">*</span></label>
                        <select class="form-select" id="newInvCase">
                            <option selected disabled>Select case...</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Invoice Type <span class="text-danger">*</span></label>
                        <select class="form-select" id="newInvType">
                            <option>Service Fee</option>
                            <option>Consultation</option>
                            <option>Government Fee</option>
                            <option>Translation Fee</option>
                            <option>Notary Fee</option>
                            <option>Other</option>
                        </select>
                    </div>
                    <div class="col-md-12">
                        <label class="form-label">Description <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="newInvDesc" placeholder="e.g. Legal services for temporary residence permit">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Amount (PLN) <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="newInvAmount" placeholder="0.00" step="0.01">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Issue Date</label>
                        <input type="date" class="form-control" id="newInvIssueDate">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Due Date</label>
                        <input type="date" class="form-control" id="newInvDueDate">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Payment Method</label>
                        <select class="form-select" id="newInvMethod">
                            <option>Bank Transfer</option>
                            <option>Card</option>
                            <option>Cash</option>
                            <option>BLIK</option>
                            <option>PayU</option>
                            <option>Przelewy24</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Installments</label>
                        <select class="form-select" id="newInvInstallments">
                            <option value="1">Full Payment</option>
                            <option value="2">2 Installments</option>
                            <option value="3">3 Installments</option>
                            <option value="4">4 Installments</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Notes</label>
                        <textarea class="form-control" rows="2" id="newInvNotes" placeholder="Additional notes..."></textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="saveNewInvoiceBtn"><i class="ri-save-line me-1"></i>Generate Invoice</button>
            </div>
        </div>
    </div>
</div>

<!-- ============================================ -->
<!-- RECORD PAYMENT MODAL -->
<!-- ============================================ -->
<div class="modal fade" id="recordPaymentModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-success-subtle">
                <h5 class="modal-title text-success"><i class="ri-money-dollar-circle-line me-2"></i>Record Payment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Invoice <span class="text-danger">*</span></label>
                        <select class="form-select" id="payInvoice">
                            <option selected disabled>Select invoice...</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Amount (PLN) <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="payAmount" placeholder="0.00" step="0.01">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Payment Date <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" id="payDate">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Payment Method <span class="text-danger">*</span></label>
                        <select class="form-select" id="payMethod">
                            <option>Bank Transfer</option>
                            <option>Card</option>
                            <option>Cash</option>
                            <option>BLIK</option>
                            <option>PayU</option>
                            <option>Przelewy24</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Receipt #</label>
                        <input type="text" class="form-control" id="payReceipt" placeholder="Auto-generated">
                    </div>
                    <div class="col-12">
                        <label class="form-label">Note</label>
                        <input type="text" class="form-control" id="payNote" placeholder="e.g. First installment">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-success" id="savePaymentBtn"><i class="ri-check-line me-1"></i>Record Payment</button>
            </div>
        </div>
    </div>
</div>

<!-- ============================================ -->
<!-- EDIT FINANCIAL PROFILE MODAL -->
<!-- ============================================ -->
<div class="modal fade" id="editFinanceProfileModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="ri-settings-3-line me-2"></i>Financial Profile Settings</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Currency</label>
                        <select class="form-select" id="fpEditCurrency">
                            <option selected>PLN (Polish Zloty)</option>
                            <option>EUR (Euro)</option>
                            <option>USD (US Dollar)</option>
                            <option>UAH (Hryvnia)</option>
                            <option>GBP (British Pound)</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Payment Terms</label>
                        <select class="form-select" id="fpEditTerms">
                            <option>Immediate</option>
                            <option>Net 7 days</option>
                            <option selected>Net 14 days</option>
                            <option>Net 30 days</option>
                            <option>Custom</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Installments Allowed</label>
                        <select class="form-select" id="fpEditInstallments">
                            <option value="yes" selected>Yes</option>
                            <option value="no">No</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Max Installments</label>
                        <select class="form-select" id="fpEditMaxInstallments">
                            <option>2</option>
                            <option>3</option>
                            <option selected>4</option>
                            <option>6</option>
                            <option>12</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Auto Reminders</label>
                        <select class="form-select" id="fpEditReminders">
                            <option value="enabled" selected>Enabled</option>
                            <option value="disabled">Disabled</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Discount (%)</label>
                        <input type="number" class="form-control" id="fpEditDiscount" placeholder="0" min="0" max="100">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Tax ID (NIP)</label>
                        <input type="text" class="form-control" id="fpEditNip" placeholder="e.g. 1234567890" maxlength="10">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Invoice Language</label>
                        <select class="form-select" id="fpEditInvoiceLang">
                            <option selected>Polish</option>
                            <option>English</option>
                            <option>Ukrainian</option>
                            <option>Russian</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="saveFinanceProfileBtn"><i class="ri-save-line me-1"></i>Save Settings</button>
            </div>
        </div>
    </div>
</div>

<!-- ============================================ -->
<!-- ADD PAYMENT METHOD MODAL -->
<!-- ============================================ -->
<div class="modal fade" id="addPaymentMethodModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="ri-bank-card-line me-2"></i>Add Payment Method</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label">Method Type <span class="text-danger">*</span></label>
                        <select class="form-select" id="newMethodType">
                            <option selected disabled>Select method...</option>
                            <option>Bank Transfer</option>
                            <option>Credit/Debit Card</option>
                            <option>BLIK</option>
                            <option>PayU</option>
                            <option>Przelewy24</option>
                            <option>PayPal</option>
                            <option>Cash</option>
                            <option>Wise (TransferWise)</option>
                            <option>Revolut</option>
                        </select>
                    </div>
                    <div class="col-12" id="bankDetailsSection" style="display:none;">
                        <label class="form-label">Bank Name</label>
                        <input type="text" class="form-control" id="newMethodBank" placeholder="e.g. PKO Bank Polski">
                    </div>
                    <div class="col-12" id="accountSection" style="display:none;">
                        <label class="form-label">Account / Card Number (last 4 digits)</label>
                        <input type="text" class="form-control" id="newMethodAccount" placeholder="e.g. **** 1234">
                    </div>
                    <div class="col-12">
                        <label class="form-label">Label (optional)</label>
                        <input type="text" class="form-control" id="newMethodLabel" placeholder="e.g. Main account, Business card">
                    </div>
                    <div class="col-12">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="newMethodDefault">
                            <label class="form-check-label" for="newMethodDefault">Set as preferred method</label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="savePaymentMethodBtn"><i class="ri-save-line me-1"></i>Add Method</button>
            </div>
        </div>
    </div>
</div>

<!-- ============================================ -->
<!-- VIEW INVOICE MODAL -->
<!-- ============================================ -->
<div class="modal fade" id="viewInvoiceModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="ri-file-text-line me-2"></i>Invoice Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="viewInvoiceBody">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-subtle-secondary" id="printInvoiceBtn"><i class="ri-printer-line me-1"></i>Print</button>
                <button type="button" class="btn btn-subtle-info" id="downloadInvoiceBtn"><i class="ri-download-2-line me-1"></i>Download PDF</button>
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('js')
<script>
document.addEventListener('DOMContentLoaded', function() {

    // ===== CLIENT DATA (demo, loaded from URL param) =====
    var CLIENTS = {
        '1': {
            name: 'Oleksandr Petrov', phone: '+48 512 345 678', email: 'petrov@email.com',
            nationality: 'Ukrainian', status: 'Active',
            totalContract: 14800, totalPaid: 9600, totalDebt: 5200,
            invoices: [
                { id: 'INV-2026-0301', caseId: 'WC-2026-0147', caseType: 'Temp. Residence', desc: 'Legal services — Temporary Residence Permit', issueDate: '2026-01-20', dueDate: '2026-02-03', amount: 4800, paid: 4800, status: 'Paid' },
                { id: 'INV-2026-0315', caseId: 'WC-2026-0155', caseType: 'Speedup', desc: 'Speedup procedure — case acceleration', issueDate: '2026-02-25', dueDate: '2026-03-11', amount: 3000, paid: 1500, status: 'Partially Paid' },
                { id: 'INV-2026-0320', caseId: 'WC-2026-0160', caseType: 'Citizenship', desc: 'Citizenship application — legal representation', issueDate: '2025-12-15', dueDate: '2026-01-15', amount: 7000, paid: 3300, status: 'Partially Paid' }
            ],
            payments: [
                { date: '2026-02-24', invoiceId: 'INV-2026-0301', caseId: 'WC-2026-0147', amount: 4800, method: 'Bank Transfer', receipt: 'RCP-0024', status: 'Completed', note: 'Full payment' },
                { date: '2026-02-25', invoiceId: 'INV-2026-0315', caseId: 'WC-2026-0155', amount: 1500, method: 'Card', receipt: 'RCP-0025', status: 'Completed', note: '1st installment' },
                { date: '2026-01-10', invoiceId: 'INV-2026-0320', caseId: 'WC-2026-0160', amount: 3300, method: 'Cash', receipt: 'RCP-0010', status: 'Completed', note: '1st installment' }
            ],
            schedule: [
                { dueDate: '2026-03-11', invoiceId: 'INV-2026-0315', caseId: 'WC-2026-0155', amount: 1500, status: 'upcoming' },
                { dueDate: '2026-03-15', invoiceId: 'INV-2026-0320', caseId: 'WC-2026-0160', amount: 3700, status: 'upcoming' }
            ],
            methods: [
                { type: 'Bank Transfer', icon: 'ri-bank-line', color: 'primary', label: 'PKO BP Main Account', detail: 'PL61 1020 **** **** **** 1234', isDefault: true },
                { type: 'Card', icon: 'ri-bank-card-line', color: 'info', label: 'Visa Debit', detail: '**** **** **** 5678', isDefault: false },
                { type: 'Cash', icon: 'ri-cash-line', color: 'success', label: 'Cash at Office', detail: 'Accepted at WinCase office', isDefault: false }
            ],
            cases: [
                { id: 'WC-2026-0147', type: 'Temp. Residence', contract: 4800, paid: 4800, debt: 0 },
                { id: 'WC-2026-0155', type: 'Speedup', contract: 3000, paid: 1500, debt: 1500 },
                { id: 'WC-2026-0160', type: 'Citizenship', contract: 7000, paid: 3300, debt: 3700 }
            ]
        },
        '2': {
            name: 'Maria Ivanova', phone: '+48 698 765 432', email: 'ivanova@email.com',
            nationality: 'Ukrainian', status: 'Active',
            totalContract: 4500, totalPaid: 2000, totalDebt: 2500,
            invoices: [
                { id: 'INV-2026-0340', caseId: 'WC-2026-0162', caseType: 'Temp. Residence', desc: 'Legal services — Temporary Residence Permit', issueDate: '2026-02-10', dueDate: '2026-02-24', amount: 4500, paid: 2000, status: 'Partially Paid' }
            ],
            payments: [
                { date: '2026-02-12', invoiceId: 'INV-2026-0340', caseId: 'WC-2026-0162', amount: 2000, method: 'Bank Transfer', receipt: 'RCP-0032', status: 'Completed', note: '1st installment' }
            ],
            schedule: [
                { dueDate: '2026-03-10', invoiceId: 'INV-2026-0340', caseId: 'WC-2026-0162', amount: 2500, status: 'upcoming' }
            ],
            methods: [
                { type: 'Bank Transfer', icon: 'ri-bank-line', color: 'primary', label: 'mBank Account', detail: 'PL42 1140 **** **** **** 5678', isDefault: true },
                { type: 'BLIK', icon: 'ri-smartphone-line', color: 'danger', label: 'BLIK', detail: 'Linked to mBank', isDefault: false }
            ],
            cases: [
                { id: 'WC-2026-0162', type: 'Temp. Residence', contract: 4500, paid: 2000, debt: 2500 }
            ]
        },
        '3': {
            name: 'Aliaksandr Kazlou', phone: '+48 501 234 567', email: 'kazlou@email.com',
            nationality: 'Belarusian', status: 'Active',
            totalContract: 9500, totalPaid: 7500, totalDebt: 2000,
            invoices: [
                { id: 'INV-2025-0198', caseId: 'WC-2025-0098', caseType: 'Work Permit', desc: 'Work Permit — legal representation', issueDate: '2025-11-10', dueDate: '2025-11-24', amount: 3500, paid: 3500, status: 'Paid' },
                { id: 'INV-2026-0355', caseId: 'WC-2026-0170', caseType: 'Perm. Residence', desc: 'Permanent Residence — legal services', issueDate: '2026-02-05', dueDate: '2026-02-19', amount: 6000, paid: 4000, status: 'Partially Paid' }
            ],
            payments: [
                { date: '2025-12-15', invoiceId: 'INV-2025-0198', caseId: 'WC-2025-0098', amount: 3500, method: 'Card', receipt: 'RCP-0198', status: 'Completed', note: 'Full payment' },
                { date: '2026-02-20', invoiceId: 'INV-2026-0355', caseId: 'WC-2026-0170', amount: 4000, method: 'Bank Transfer', receipt: 'RCP-0040', status: 'Completed', note: '1st installment' }
            ],
            schedule: [
                { dueDate: '2026-03-19', invoiceId: 'INV-2026-0355', caseId: 'WC-2026-0170', amount: 2000, status: 'upcoming' }
            ],
            methods: [
                { type: 'Bank Transfer', icon: 'ri-bank-line', color: 'primary', label: 'ING Bank', detail: 'PL77 1050 **** **** **** 9012', isDefault: true },
                { type: 'Card', icon: 'ri-bank-card-line', color: 'info', label: 'Mastercard', detail: '**** **** **** 3456', isDefault: false },
                { type: 'Revolut', icon: 'ri-exchange-line', color: 'secondary', label: 'Revolut', detail: 'kazlou@revolut', isDefault: false }
            ],
            cases: [
                { id: 'WC-2025-0098', type: 'Work Permit', contract: 3500, paid: 3500, debt: 0 },
                { id: 'WC-2026-0170', type: 'Perm. Residence', contract: 6000, paid: 4000, debt: 2000 }
            ]
        },
        '4': {
            name: 'Giorgi Tsiklauri', phone: '+48 600 111 222', email: 'tsiklauri@email.com',
            nationality: 'Georgian', status: 'Archived',
            totalContract: 0, totalPaid: 0, totalDebt: 0,
            invoices: [], payments: [], schedule: [],
            methods: [
                { type: 'Cash', icon: 'ri-cash-line', color: 'success', label: 'Cash at Office', detail: 'Accepted at WinCase office', isDefault: true }
            ],
            cases: []
        },
        '5': {
            name: 'Tetiana Sydorenko', phone: '+48 512 999 888', email: 'sydorenko@email.com',
            nationality: 'Ukrainian', status: 'Active',
            totalContract: 4800, totalPaid: 2400, totalDebt: 2400,
            invoices: [
                { id: 'INV-2026-0360', caseId: 'WC-2026-0175', caseType: 'Temp. Residence', desc: 'Temporary Residence — full legal package', issueDate: '2026-02-18', dueDate: '2026-03-04', amount: 4800, paid: 2400, status: 'Partially Paid' }
            ],
            payments: [
                { date: '2026-02-20', invoiceId: 'INV-2026-0360', caseId: 'WC-2026-0175', amount: 2400, method: 'Card', receipt: 'RCP-0045', status: 'Completed', note: '1st installment (50%)' }
            ],
            schedule: [
                { dueDate: '2026-03-04', invoiceId: 'INV-2026-0360', caseId: 'WC-2026-0175', amount: 2400, status: 'overdue' }
            ],
            methods: [
                { type: 'Card', icon: 'ri-bank-card-line', color: 'info', label: 'Visa — Monobank', detail: '**** **** **** 7890', isDefault: true },
                { type: 'BLIK', icon: 'ri-smartphone-line', color: 'danger', label: 'BLIK via Santander', detail: 'Linked to Santander', isDefault: false }
            ],
            cases: [
                { id: 'WC-2026-0175', type: 'Temp. Residence', contract: 4800, paid: 2400, debt: 2400 }
            ]
        },
        '6': {
            name: 'Rajesh Kumar', phone: '+48 505 333 444', email: 'kumar@email.com',
            nationality: 'Indian', status: 'Active',
            totalContract: 3500, totalPaid: 1750, totalDebt: 1750,
            invoices: [
                { id: 'INV-2026-0375', caseId: 'WC-2026-0180', caseType: 'Work Permit', desc: 'Work Permit — legal representation', issueDate: '2026-02-25', dueDate: '2026-03-11', amount: 3500, paid: 1750, status: 'Partially Paid' }
            ],
            payments: [
                { date: '2026-02-28', invoiceId: 'INV-2026-0375', caseId: 'WC-2026-0180', amount: 1750, method: 'Cash', receipt: 'RCP-0050', status: 'Completed', note: '1st installment (50%)' }
            ],
            schedule: [
                { dueDate: '2026-03-11', invoiceId: 'INV-2026-0375', caseId: 'WC-2026-0180', amount: 1750, status: 'upcoming' }
            ],
            methods: [
                { type: 'Cash', icon: 'ri-cash-line', color: 'success', label: 'Cash at Office', detail: 'Accepted at WinCase office', isDefault: true },
                { type: 'PayU', icon: 'ri-secure-payment-line', color: 'warning', label: 'PayU', detail: 'kumar@email.com', isDefault: false },
                { type: 'Wise', icon: 'ri-exchange-line', color: 'success', label: 'Wise (TransferWise)', detail: 'INR → PLN transfers', isDefault: false }
            ],
            cases: [
                { id: 'WC-2026-0180', type: 'Work Permit', contract: 3500, paid: 1750, debt: 1750 }
            ]
        }
    };

    // Get client ID from URL
    var params = new URLSearchParams(window.location.search);
    var clientId = params.get('client') || '1';
    var client = CLIENTS[clientId] || CLIENTS['1'];

    // ===== HELPERS =====
    function getInitials(n) { var p = n.split(' '); return (p[0]?p[0][0]:'')+(p[1]?p[1][0]:''); }
    function fmt(n) { return 'PLN ' + n.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ','); }
    function fmtShort(n) { return 'PLN ' + n.toLocaleString('en'); }

    function showToast(msg, type) {
        var t = document.createElement('div');
        t.className = 'position-fixed top-0 end-0 p-3';
        t.style.zIndex = '9999';
        t.innerHTML = '<div class="alert alert-'+type+' alert-dismissible fade show shadow" role="alert">'+msg+'<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>';
        document.body.appendChild(t);
        setTimeout(function(){ t.remove(); }, 3000);
    }

    var STATUS_COLORS = { 'Paid': 'success', 'Partially Paid': 'warning', 'Unpaid': 'danger', 'Overdue': 'danger' };
    var METHOD_COLORS = { 'Bank Transfer': 'primary', 'Card': 'info', 'Cash': 'success', 'BLIK': 'danger', 'PayU': 'warning', 'Przelewy24': 'secondary', 'PayPal': 'info', 'Wise': 'success', 'Revolut': 'secondary' };
    var CASE_TYPE_COLORS = { 'Temp. Residence': 'primary', 'Perm. Residence': 'success', 'Work Permit': 'warning', 'Speedup': 'success', 'Citizenship': 'info' };

    // ===== POPULATE HEADER =====
    document.getElementById('clientInitials').textContent = getInitials(client.name);
    document.getElementById('clientName').textContent = client.name;
    document.getElementById('clientStatus').textContent = client.status;
    document.getElementById('clientStatus').className = 'badge bg-' + (client.status === 'Active' ? 'success' : 'secondary') + '-subtle text-' + (client.status === 'Active' ? 'success' : 'secondary');
    document.getElementById('clientNationality').textContent = client.nationality;
    document.getElementById('clientPhone').textContent = client.phone;
    document.getElementById('clientEmail').textContent = client.email;

    // ===== FINANCE SUMMARY =====
    document.getElementById('totalContract').textContent = fmt(client.totalContract);
    document.getElementById('totalPaid').textContent = fmt(client.totalPaid);
    document.getElementById('totalDebt').textContent = fmt(client.totalDebt);
    var pct = client.totalContract > 0 ? ((client.totalPaid / client.totalContract) * 100).toFixed(1) : 0;
    document.getElementById('paymentPercent').textContent = pct + '%';
    document.getElementById('paymentProgressBar').style.width = pct + '%';

    // ===== INVOICES TABLE =====
    function renderInvoices() {
        var html = '';
        for (var i = 0; i < client.invoices.length; i++) {
            var inv = client.invoices[i];
            var balance = inv.amount - inv.paid;
            var sc = STATUS_COLORS[inv.status] || 'secondary';
            var tc = CASE_TYPE_COLORS[inv.caseType] || 'secondary';
            html += '<tr>';
            html += '<td><a href="#" class="fw-semibold text-primary inv-view-link" data-inv-idx="'+i+'">' + inv.id + '</a></td>';
            html += '<td><span class="badge bg-'+tc+'-subtle text-'+tc+'">' + inv.caseId + '</span></td>';
            html += '<td class="fs-12">' + inv.desc + '</td>';
            html += '<td class="text-muted fs-12">' + inv.issueDate + '</td>';
            html += '<td class="text-muted fs-12">' + inv.dueDate + '</td>';
            html += '<td class="fw-semibold">' + fmtShort(inv.amount) + '</td>';
            html += '<td class="text-success">' + fmtShort(inv.paid) + '</td>';
            html += '<td class="' + (balance > 0 ? 'text-danger fw-semibold' : 'text-muted') + '">' + (balance > 0 ? fmtShort(balance) : '—') + '</td>';
            html += '<td><span class="badge bg-'+sc+'-subtle text-'+sc+'">' + inv.status + '</span></td>';
            html += '<td><div class="dropdown"><button class="btn btn-sm btn-subtle-secondary" data-bs-toggle="dropdown"><i class="ri-more-2-fill"></i></button>';
            html += '<ul class="dropdown-menu">';
            html += '<li><a class="dropdown-item inv-view-link" href="#" data-inv-idx="'+i+'"><i class="ri-eye-line me-2"></i>View</a></li>';
            html += '<li><a class="dropdown-item" href="#"><i class="ri-printer-line me-2"></i>Print</a></li>';
            html += '<li><a class="dropdown-item" href="#"><i class="ri-download-2-line me-2"></i>Download PDF</a></li>';
            if (balance > 0) html += '<li><a class="dropdown-item text-success inv-pay-link" href="#" data-inv-idx="'+i+'"><i class="ri-money-dollar-circle-line me-2"></i>Record Payment</a></li>';
            html += '</ul></div></td>';
            html += '</tr>';
        }
        if (client.invoices.length === 0) {
            html = '<tr><td colspan="10" class="text-center text-muted py-4">No invoices found</td></tr>';
        }
        document.getElementById('invoicesTableBody').innerHTML = html;
    }
    renderInvoices();

    // ===== PAYMENTS TABLE =====
    function renderPayments() {
        var html = '';
        for (var i = 0; i < client.payments.length; i++) {
            var pm = client.payments[i];
            var mc = METHOD_COLORS[pm.method] || 'secondary';
            html += '<tr>';
            html += '<td class="text-muted fs-12">' + pm.date + '</td>';
            html += '<td><span class="text-primary fw-semibold fs-12">' + pm.invoiceId + '</span></td>';
            html += '<td class="fs-12">' + pm.caseId + '</td>';
            html += '<td class="fw-semibold">' + fmtShort(pm.amount) + '</td>';
            html += '<td><span class="badge bg-'+mc+'-subtle text-'+mc+'">' + pm.method + '</span></td>';
            html += '<td class="text-muted fs-12">' + pm.receipt + '</td>';
            html += '<td><span class="badge bg-success-subtle text-success">' + pm.status + '</span></td>';
            html += '<td class="text-muted fs-12">' + (pm.note || '') + '</td>';
            html += '</tr>';
        }
        if (client.payments.length === 0) {
            html = '<tr><td colspan="8" class="text-center text-muted py-4">No payments recorded</td></tr>';
        }
        document.getElementById('paymentsTableBody').innerHTML = html;
    }
    renderPayments();

    // ===== SCHEDULE TABLE =====
    function renderSchedule() {
        var html = '';
        var today = new Date().toISOString().split('T')[0];
        for (var i = 0; i < client.schedule.length; i++) {
            var s = client.schedule[i];
            var isOverdue = s.dueDate < today || s.status === 'overdue';
            html += '<tr>';
            html += '<td class="' + (isOverdue ? 'text-danger fw-semibold' : 'text-muted') + ' fs-12">' + s.dueDate + (isOverdue ? ' <span class="badge bg-danger">OVERDUE</span>' : '') + '</td>';
            html += '<td class="text-primary fw-semibold fs-12">' + s.invoiceId + '</td>';
            html += '<td class="fs-12">' + s.caseId + '</td>';
            html += '<td class="fw-semibold ' + (isOverdue ? 'text-danger' : '') + '">' + fmtShort(s.amount) + '</td>';
            html += '<td><span class="badge bg-' + (isOverdue ? 'danger' : 'warning') + '-subtle text-' + (isOverdue ? 'danger' : 'warning') + '">' + (isOverdue ? 'Overdue' : 'Upcoming') + '</span></td>';
            html += '<td><button class="btn btn-sm btn-success schedule-pay-btn" data-idx="'+i+'"><i class="ri-money-dollar-circle-line me-1"></i>Pay</button></td>';
            html += '</tr>';
        }
        if (client.schedule.length === 0) {
            html = '<tr><td colspan="6" class="text-center text-muted py-4">No upcoming payments</td></tr>';
        }
        document.getElementById('scheduleTableBody').innerHTML = html;
    }
    renderSchedule();

    // ===== PAYMENT METHODS =====
    function renderPaymentMethods() {
        var html = '';
        for (var i = 0; i < client.methods.length; i++) {
            var m = client.methods[i];
            html += '<div class="d-flex align-items-center justify-content-between ' + (i < client.methods.length - 1 ? 'mb-3 pb-3 border-bottom' : '') + '">';
            html += '<div class="d-flex align-items-center gap-3">';
            html += '<div class="avatar avatar-sm bg-'+m.color+'-subtle text-'+m.color+' rounded-2"><i class="'+m.icon+' fs-18"></i></div>';
            html += '<div><h6 class="mb-0 fs-13">' + m.type + (m.isDefault ? ' <span class="badge bg-primary-subtle text-primary fs-10">Preferred</span>' : '') + '</h6>';
            html += '<span class="text-muted fs-12">' + (m.label ? m.label + ' — ' : '') + m.detail + '</span></div>';
            html += '</div>';
            html += '<button class="btn btn-sm btn-subtle-danger remove-method-btn" data-idx="'+i+'" title="Remove"><i class="ri-delete-bin-line"></i></button>';
            html += '</div>';
        }
        if (client.methods.length === 0) {
            html = '<p class="text-muted text-center mb-0">No payment methods configured</p>';
        }
        document.getElementById('paymentMethodsList').innerHTML = html;
    }
    renderPaymentMethods();

    // ===== DEBT BY CASE =====
    function renderDebtByCase() {
        var html = '<ul class="list-group list-group-flush">';
        var hasDebt = false;
        for (var i = 0; i < client.cases.length; i++) {
            var c = client.cases[i];
            var pctCase = c.contract > 0 ? ((c.paid / c.contract) * 100).toFixed(0) : 100;
            var tc = CASE_TYPE_COLORS[c.type] || 'secondary';
            html += '<li class="list-group-item">';
            html += '<div class="d-flex justify-content-between mb-1"><span class="fw-semibold text-primary fs-12">' + c.id + '</span><span class="badge bg-'+tc+'-subtle text-'+tc+'">' + c.type + '</span></div>';
            html += '<div class="d-flex justify-content-between fs-12 text-muted mb-1"><span>Contract: ' + fmtShort(c.contract) + '</span><span>Paid: <span class="text-success">' + fmtShort(c.paid) + '</span></span></div>';
            if (c.debt > 0) {
                hasDebt = true;
                html += '<div class="d-flex justify-content-between fs-12 mb-1"><span class="text-muted">Debt:</span><span class="text-danger fw-semibold">' + fmtShort(c.debt) + '</span></div>';
            }
            html += '<div class="progress" style="height: 4px;"><div class="progress-bar bg-' + (parseInt(pctCase) >= 100 ? 'success' : 'primary') + '" style="width: ' + pctCase + '%"></div></div>';
            html += '</li>';
        }
        html += '</ul>';
        if (client.cases.length === 0) {
            html = '<div class="p-3 text-muted text-center fs-13">No cases found</div>';
        }
        document.getElementById('debtByCaseList').innerHTML = html;
    }
    renderDebtByCase();

    // ===== POPULATE MODAL SELECTS =====
    function populateCaseSelect() {
        var sel = document.getElementById('newInvCase');
        sel.innerHTML = '<option selected disabled>Select case...</option>';
        for (var i = 0; i < client.cases.length; i++) {
            sel.innerHTML += '<option value="' + client.cases[i].id + '">' + client.cases[i].id + ' — ' + client.cases[i].type + '</option>';
        }
    }
    populateCaseSelect();

    function populateInvoiceSelect() {
        var sel = document.getElementById('payInvoice');
        sel.innerHTML = '<option selected disabled>Select invoice...</option>';
        for (var i = 0; i < client.invoices.length; i++) {
            var inv = client.invoices[i];
            var bal = inv.amount - inv.paid;
            if (bal > 0) {
                sel.innerHTML += '<option value="' + inv.id + '">' + inv.id + ' — Balance: ' + fmtShort(bal) + '</option>';
            }
        }
    }
    populateInvoiceSelect();

    // Set today dates
    var today = new Date().toISOString().split('T')[0];
    if (document.getElementById('newInvIssueDate')) document.getElementById('newInvIssueDate').value = today;
    if (document.getElementById('payDate')) document.getElementById('payDate').value = today;

    // ===== VIEW INVOICE =====
    document.addEventListener('click', function(e) {
        var link = e.target.closest('.inv-view-link');
        if (!link) return;
        e.preventDefault();
        var idx = parseInt(link.dataset.invIdx);
        var inv = client.invoices[idx];
        if (!inv) return;

        var balance = inv.amount - inv.paid;
        var sc = STATUS_COLORS[inv.status] || 'secondary';

        var html = '<div class="row">';
        html += '<div class="col-md-6"><div class="border rounded p-3 mb-3">';
        html += '<h6 class="text-muted mb-2">Invoice</h6>';
        html += '<h4 class="text-primary fw-bold">' + inv.id + '</h4>';
        html += '<span class="badge bg-'+sc+'-subtle text-'+sc+' fs-12">' + inv.status + '</span>';
        html += '</div></div>';
        html += '<div class="col-md-6"><div class="border rounded p-3 mb-3">';
        html += '<h6 class="text-muted mb-2">Client</h6>';
        html += '<h5 class="mb-1">' + client.name + '</h5>';
        html += '<span class="text-muted fs-12">' + client.email + ' | ' + client.phone + '</span>';
        html += '</div></div>';
        html += '</div>';

        html += '<div class="table-responsive mb-3"><table class="table table-borderless table-sm mb-0"><tbody>';
        html += '<tr><td class="text-muted" style="width:30%">Case</td><td class="fw-semibold">' + inv.caseId + ' — ' + inv.caseType + '</td></tr>';
        html += '<tr><td class="text-muted">Description</td><td>' + inv.desc + '</td></tr>';
        html += '<tr><td class="text-muted">Issue Date</td><td>' + inv.issueDate + '</td></tr>';
        html += '<tr><td class="text-muted">Due Date</td><td>' + inv.dueDate + '</td></tr>';
        html += '</tbody></table></div>';

        html += '<div class="row g-3 mb-3">';
        html += '<div class="col-4"><div class="border rounded p-3 text-center"><p class="text-muted mb-1 fs-12">Total Amount</p><h5 class="mb-0 fw-bold">' + fmtShort(inv.amount) + '</h5></div></div>';
        html += '<div class="col-4"><div class="border rounded p-3 text-center"><p class="text-muted mb-1 fs-12">Paid</p><h5 class="mb-0 fw-bold text-success">' + fmtShort(inv.paid) + '</h5></div></div>';
        html += '<div class="col-4"><div class="border rounded p-3 text-center"><p class="text-muted mb-1 fs-12">Balance</p><h5 class="mb-0 fw-bold ' + (balance > 0 ? 'text-danger' : 'text-muted') + '">' + (balance > 0 ? fmtShort(balance) : '—') + '</h5></div></div>';
        html += '</div>';

        // Related payments
        var relPay = client.payments.filter(function(p) { return p.invoiceId === inv.id; });
        if (relPay.length > 0) {
            html += '<h6 class="mt-3 mb-2"><i class="ri-exchange-dollar-line me-1"></i>Related Payments</h6>';
            html += '<div class="table-responsive"><table class="table table-sm table-hover mb-0"><thead class="table-light"><tr><th>Date</th><th>Amount</th><th>Method</th><th>Receipt</th><th>Note</th></tr></thead><tbody>';
            for (var j = 0; j < relPay.length; j++) {
                var rp = relPay[j];
                var mc = METHOD_COLORS[rp.method] || 'secondary';
                html += '<tr><td class="fs-12">' + rp.date + '</td><td class="fw-semibold">' + fmtShort(rp.amount) + '</td><td><span class="badge bg-'+mc+'-subtle text-'+mc+'">' + rp.method + '</span></td><td class="fs-12">' + rp.receipt + '</td><td class="fs-12 text-muted">' + (rp.note || '') + '</td></tr>';
            }
            html += '</tbody></table></div>';
        }

        document.getElementById('viewInvoiceBody').innerHTML = html;
        new bootstrap.Modal(document.getElementById('viewInvoiceModal')).show();
    });

    // ===== INVOICE PAY LINK =====
    document.addEventListener('click', function(e) {
        var link = e.target.closest('.inv-pay-link');
        if (!link) return;
        e.preventDefault();
        var idx = parseInt(link.dataset.invIdx);
        var inv = client.invoices[idx];
        if (!inv) return;
        var balance = inv.amount - inv.paid;
        document.getElementById('payInvoice').value = inv.id;
        document.getElementById('payAmount').value = balance;
        new bootstrap.Modal(document.getElementById('recordPaymentModal')).show();
    });

    // Schedule Pay button
    document.addEventListener('click', function(e) {
        var btn = e.target.closest('.schedule-pay-btn');
        if (!btn) return;
        var idx = parseInt(btn.dataset.idx);
        var s = client.schedule[idx];
        if (!s) return;
        document.getElementById('payInvoice').value = s.invoiceId;
        document.getElementById('payAmount').value = s.amount;
        new bootstrap.Modal(document.getElementById('recordPaymentModal')).show();
    });

    // ===== SAVE NEW INVOICE =====
    document.getElementById('saveNewInvoiceBtn').addEventListener('click', function() {
        var caseId = document.getElementById('newInvCase').value;
        var amount = parseFloat(document.getElementById('newInvAmount').value);
        if (!caseId || !amount) { showToast('Please fill required fields', 'danger'); return; }
        showToast('<i class="ri-check-line me-1"></i> Invoice generated successfully', 'success');
        bootstrap.Modal.getInstance(document.getElementById('newInvoiceModal')).hide();
    });

    // ===== SAVE PAYMENT =====
    document.getElementById('savePaymentBtn').addEventListener('click', function() {
        var invId = document.getElementById('payInvoice').value;
        var amount = parseFloat(document.getElementById('payAmount').value);
        if (!invId || !amount) { showToast('Please fill required fields', 'danger'); return; }
        showToast('<i class="ri-check-line me-1"></i> Payment recorded successfully', 'success');
        bootstrap.Modal.getInstance(document.getElementById('recordPaymentModal')).hide();
    });

    // ===== SAVE FINANCIAL PROFILE =====
    document.getElementById('saveFinanceProfileBtn').addEventListener('click', function() {
        document.getElementById('fpCurrency').textContent = document.getElementById('fpEditCurrency').value;
        document.getElementById('fpTerms').textContent = document.getElementById('fpEditTerms').value;

        var inst = document.getElementById('fpEditInstallments').value;
        document.getElementById('fpInstallments').innerHTML = inst === 'yes' ? '<i class="ri-check-line"></i> Yes' : '<i class="ri-close-line"></i> No';
        document.getElementById('fpInstallments').className = 'fw-semibold text-' + (inst === 'yes' ? 'success' : 'danger');
        document.getElementById('fpMaxInstallments').textContent = document.getElementById('fpEditMaxInstallments').value;

        var rem = document.getElementById('fpEditReminders').value;
        document.getElementById('fpReminders').innerHTML = rem === 'enabled' ? '<i class="ri-check-line"></i> Enabled' : '<i class="ri-close-line"></i> Disabled';
        document.getElementById('fpReminders').className = 'fw-semibold text-' + (rem === 'enabled' ? 'success' : 'danger');

        var disc = document.getElementById('fpEditDiscount').value;
        document.getElementById('fpDiscount').textContent = disc ? disc + '%' : '—';
        document.getElementById('fpNip').textContent = document.getElementById('fpEditNip').value || '—';
        document.getElementById('fpInvoiceLang').textContent = document.getElementById('fpEditInvoiceLang').value;

        bootstrap.Modal.getInstance(document.getElementById('editFinanceProfileModal')).hide();
        showToast('<i class="ri-check-line me-1"></i> Financial profile updated', 'success');
    });

    // ===== ADD PAYMENT METHOD =====
    document.getElementById('newMethodType').addEventListener('change', function() {
        var v = this.value;
        document.getElementById('bankDetailsSection').style.display = (v === 'Bank Transfer') ? 'block' : 'none';
        document.getElementById('accountSection').style.display = (v === 'Bank Transfer' || v === 'Credit/Debit Card') ? 'block' : 'none';
    });

    document.getElementById('savePaymentMethodBtn').addEventListener('click', function() {
        var type = document.getElementById('newMethodType').value;
        if (!type || type === 'Select method...') { showToast('Please select a method type', 'danger'); return; }
        showToast('<i class="ri-check-line me-1"></i> Payment method added', 'success');
        bootstrap.Modal.getInstance(document.getElementById('addPaymentMethodModal')).hide();
    });

    // ===== REMOVE PAYMENT METHOD =====
    document.addEventListener('click', function(e) {
        var btn = e.target.closest('.remove-method-btn');
        if (!btn) return;
        var idx = parseInt(btn.dataset.idx);
        client.methods.splice(idx, 1);
        renderPaymentMethods();
        showToast('<i class="ri-delete-bin-line me-1"></i> Payment method removed', 'warning');
    });

    // ===== QUICK ACTIONS =====
    document.getElementById('btnSendReminder').addEventListener('click', function() {
        showToast('<i class="ri-notification-3-line me-1"></i> Payment reminder sent to ' + client.email, 'info');
    });
    document.getElementById('btnExportStatement').addEventListener('click', function() {
        showToast('<i class="ri-download-2-line me-1"></i> Financial statement exported', 'success');
    });
    document.getElementById('btnPrintAll').addEventListener('click', function() {
        showToast('<i class="ri-printer-line me-1"></i> Preparing invoices for print...', 'info');
    });
});
</script>
@endsection
