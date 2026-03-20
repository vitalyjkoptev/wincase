@extends('partials.layouts.master')

@section('title', 'Case Detail | WinCase CRM')
@section('sub-title', 'Case Detail')
@section('sub-title-lang', 'wc-case-detail')
@section('pagetitle', 'CRM')
@section('pagetitle-lang', 'wc-title-crm')

@section('css')
<style>
    .case-header { border-radius: 10px; padding: 24px; color: #fff; position: relative; overflow: hidden; }
    .case-header::before { content: ''; position: absolute; top: -50%; right: -10%; width: 300px; height: 300px; background: rgba(255,255,255,0.05); border-radius: 50%; }
    .case-header .case-id-big { font-size: 0.85rem; opacity: 0.85; }
    .case-header .case-client-name { font-size: 1.6rem; font-weight: 700; }
    .case-header .case-type-badge { background: rgba(255,255,255,0.2); padding: 4px 12px; border-radius: 20px; font-size: 0.8rem; }
    .info-card { border-radius: 8px; border: none; box-shadow: 0 1px 4px rgba(0,0,0,0.06); }
    .info-card .info-label { font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px; color: #6c757d; font-weight: 600; }
    .info-card .info-value { font-size: 0.95rem; font-weight: 600; }
    .stage-pipeline { display: flex; gap: 0; width: 100%; }
    .stage-step { flex: 1; text-align: center; padding: 10px 4px; position: relative; font-size: 0.7rem; font-weight: 600; color: #adb5bd; background: #f8f9fa; border-right: 2px solid #fff; transition: all 0.3s; }
    .stage-step:first-child { border-radius: 8px 0 0 8px; }
    .stage-step:last-child { border-radius: 0 8px 8px 0; border-right: none; }
    .stage-step.completed { background: #d1e7dd; color: #0f5132; }
    .stage-step.current { background: #5865F2; color: #fff; font-weight: 700; }
    .stage-step .stage-num { display: block; font-size: 1.1rem; font-weight: 700; margin-bottom: 2px; }
    .timeline-item { position: relative; padding-left: 30px; padding-bottom: 20px; border-left: 2px solid #e9ecef; }
    .timeline-item:last-child { border-left: 2px solid transparent; padding-bottom: 0; }
    .timeline-item::before { content: ''; position: absolute; left: -6px; top: 4px; width: 10px; height: 10px; border-radius: 50%; background: #5865F2; border: 2px solid #fff; box-shadow: 0 0 0 2px #5865F2; }
    .timeline-item.completed::before { background: #198754; box-shadow: 0 0 0 2px #198754; }
    .timeline-date { font-size: 0.75rem; color: #6c757d; }
    .timeline-text { font-size: 0.9rem; }
    .doc-item { display: flex; align-items: center; padding: 10px 12px; border-radius: 6px; background: #f8f9fa; margin-bottom: 6px; transition: background 0.2s; cursor: pointer; }
    .doc-item:hover { background: #eef2ff; }
    .doc-icon { width: 36px; height: 36px; border-radius: 6px; display: flex; align-items: center; justify-content: center; font-size: 1rem; }
    .task-row { padding: 10px 0; border-bottom: 1px solid #f0f0f0; }
    .task-row:last-child { border-bottom: none; }
    .payment-row { padding: 8px 0; border-bottom: 1px solid #f0f0f0; }
    .payment-row:last-child { border-bottom: none; }
    .note-bubble { background: #f8f9fa; border-radius: 8px; padding: 12px; margin-bottom: 8px; border-left: 3px solid #5865F2; }
    .stat-mini { text-align: center; padding: 16px; border-radius: 8px; }
    .stat-mini .stat-num { font-size: 1.5rem; font-weight: 700; }
    .stat-mini .stat-lbl { font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.5px; color: #6c757d; }
</style>
@endsection

@section('content')
<div id="caseDetailPage">
    <!-- Case Header -->
    <div class="case-header mb-4" id="caseHeader">
        <div class="d-flex justify-content-between align-items-start">
            <div>
                <div class="case-id-big mb-1" id="hdCaseId"></div>
                <div class="case-client-name" id="hdClientName"></div>
                <div class="d-flex gap-2 mt-2">
                    <span class="case-type-badge" id="hdType"></span>
                    <span class="case-type-badge" id="hdPriority"></span>
                    <span class="case-type-badge" id="hdNationality"></span>
                </div>
            </div>
            <div class="d-flex gap-2">
                <button class="btn btn-sm btn-light" id="editCaseBtn"><i class="ri-pencil-line me-1"></i>Edit</button>
                <a href="crm-kanban" class="btn btn-sm btn-light"><i class="ri-layout-masonry-line me-1"></i>Kanban</a>
                <a href="crm-cases" class="btn btn-sm btn-light"><i class="ri-list-check me-1"></i>All Cases</a>
            </div>
        </div>
    </div>

    <!-- Stage Pipeline -->
    <div class="card info-card mb-4">
        <div class="card-body p-3">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <h6 class="mb-0 fs-13"><i class="ri-route-line me-1"></i>Case Pipeline</h6>
                <button class="btn btn-sm btn-primary" id="advanceStageBtn"><i class="ri-arrow-right-line me-1"></i>Advance</button>
            </div>
            <div class="stage-pipeline" id="stagePipeline"></div>
        </div>
    </div>

    <!-- Stats Row -->
    <div class="row g-3 mb-4">
        <div class="col-md-3 col-6">
            <div class="stat-mini bg-soft-primary">
                <div class="stat-num text-primary" id="statDaysInStage">0</div>
                <div class="stat-lbl">Days in Stage</div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="stat-mini bg-soft-info">
                <div class="stat-num text-info" id="statTotalDays">0</div>
                <div class="stat-lbl">Total Days</div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="stat-mini bg-soft-success">
                <div class="stat-num text-success" id="statPaid">0 PLN</div>
                <div class="stat-lbl">Paid</div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="stat-mini bg-soft-danger">
                <div class="stat-num text-danger" id="statDebt">0 PLN</div>
                <div class="stat-lbl">Debt</div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- LEFT COLUMN -->
        <div class="col-xl-8">
            <!-- Case Info -->
            <div class="card info-card mb-4">
                <div class="card-header d-flex justify-content-between">
                    <h6 class="card-title mb-0"><i class="ri-information-line me-1"></i>Case Information</h6>
                    <span class="badge bg-primary-subtle text-primary" id="infoManager"></span>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="info-label">Client</div>
                            <div class="info-value" id="infoClient"></div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-label">Case Type</div>
                            <div class="info-value" id="infoType"></div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-label">Filed Date</div>
                            <div class="info-value" id="infoFiled"></div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-label">Priority</div>
                            <div class="info-value" id="infoPriority"></div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-label">Nationality</div>
                            <div class="info-value" id="infoNationality"></div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-label">Current Status</div>
                            <div class="info-value" id="infoStatus"></div>
                        </div>
                        <div class="col-12">
                            <div class="info-label">Notes</div>
                            <div class="info-value text-muted" id="infoNotes"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Timeline -->
            <div class="card info-card mb-4">
                <div class="card-header">
                    <h6 class="card-title mb-0"><i class="ri-time-line me-1"></i>Case Timeline</h6>
                </div>
                <div class="card-body" id="timelineContainer">
                    <!-- JS fills -->
                </div>
            </div>

            <!-- Tasks -->
            <div class="card info-card mb-4">
                <div class="card-header d-flex justify-content-between">
                    <h6 class="card-title mb-0"><i class="ri-task-line me-1"></i>Related Tasks</h6>
                    <span class="badge bg-primary" id="tasksCount">0</span>
                </div>
                <div class="card-body" id="tasksContainer">
                    <!-- JS fills -->
                </div>
            </div>
        </div>

        <!-- RIGHT COLUMN -->
        <div class="col-xl-4">
            <!-- Documents -->
            <div class="card info-card mb-4">
                <div class="card-header d-flex justify-content-between">
                    <h6 class="card-title mb-0"><i class="ri-file-text-line me-1"></i>Documents</h6>
                    <button class="btn btn-sm btn-outline-primary"><i class="ri-upload-2-line me-1"></i>Upload</button>
                </div>
                <div class="card-body" id="docsContainer">
                    <!-- JS fills -->
                </div>
            </div>

            <!-- Payments -->
            <div class="card info-card mb-4">
                <div class="card-header d-flex justify-content-between">
                    <h6 class="card-title mb-0"><i class="ri-money-dollar-circle-line me-1"></i>Payments</h6>
                    <a class="btn btn-sm btn-outline-primary" id="clientInvoicesLink" href="#"><i class="ri-external-link-line me-1"></i>Invoices</a>
                </div>
                <div class="card-body" id="paymentsContainer">
                    <!-- JS fills -->
                </div>
            </div>

            <!-- Notes -->
            <div class="card info-card mb-4">
                <div class="card-header d-flex justify-content-between">
                    <h6 class="card-title mb-0"><i class="ri-sticky-note-line me-1"></i>Activity Notes</h6>
                    <button class="btn btn-sm btn-outline-primary" id="addNoteBtn"><i class="ri-add-line"></i></button>
                </div>
                <div class="card-body" id="notesContainer">
                    <!-- JS fills -->
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card info-card">
                <div class="card-header">
                    <h6 class="card-title mb-0"><i class="ri-flashlight-line me-1"></i>Quick Actions</h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <button class="btn btn-sm btn-primary" id="qaAdvanceBtn"><i class="ri-arrow-right-line me-1"></i>Advance Stage</button>
                        <button class="btn btn-sm btn-outline-secondary" id="qaEditBtn"><i class="ri-pencil-line me-1"></i>Edit Case</button>
                        <button class="btn btn-sm btn-outline-info" id="qaAddTaskBtn"><i class="ri-task-line me-1"></i>Add Task</button>
                        <button class="btn btn-sm btn-outline-success" id="qaRecordPayment"><i class="ri-money-dollar-circle-line me-1"></i>Record Payment</button>
                        <button class="btn btn-sm btn-outline-warning" id="qaAddNote"><i class="ri-sticky-note-line me-1"></i>Add Note</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- No Case Found -->
<div class="d-none" id="noCasePage">
    <div class="card">
        <div class="card-body text-center py-5">
            <i class="ri-briefcase-line text-muted" style="font-size:4rem"></i>
            <h5 class="mt-3">Case not found</h5>
            <p class="text-muted">The requested case does not exist or has been removed.</p>
            <a href="crm-cases" class="btn btn-primary"><i class="ri-list-check me-1"></i>All Cases</a>
            <a href="crm-kanban" class="btn btn-outline-primary ms-2"><i class="ri-layout-masonry-line me-1"></i>Kanban Board</a>
        </div>
    </div>
</div>

<!-- ===================== EDIT CASE MODAL ===================== -->
<div class="modal fade" id="editCaseModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="ri-pencil-line me-2"></i>Edit Case</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Client Name</label>
                        <input type="text" class="form-control" id="editClient">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Case Type</label>
                        <select class="form-select" id="editType">
                            <option>Temporary Residence</option>
                            <option>Permanent Residence</option>
                            <option>Work Permit</option>
                            <option>EU Blue Card</option>
                            <option>Citizenship</option>
                            <option>Family Reunification</option>
                            <option>Appeal</option>
                            <option>Deportation Cancel</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Priority</label>
                        <select class="form-select" id="editPriority">
                            <option>Low</option>
                            <option>Medium</option>
                            <option>High</option>
                            <option>Critical</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Manager</label>
                        <select class="form-select" id="editManager">
                            <option>Anna Kowalska</option>
                            <option>Piotr Nowak</option>
                            <option>Katarzyna Wiśniewska</option>
                            <option>Marek Zieliński</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Nationality</label>
                        <input type="text" class="form-control" id="editNationality">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Status</label>
                        <select class="form-select" id="editStage">
                            <option value="submitted">Submitted to Office</option>
                            <option value="fingerprint_wait">Awaiting Fingerprints</option>
                            <option value="fingerprint_apt">Fingerprint Appointment</option>
                            <option value="fingerprint_done">Fingerprints Submitted</option>
                            <option value="awaiting">Awaiting Decision</option>
                            <option value="decision">Decision Signed</option>
                            <option value="card_issued">Card Issued</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Notes</label>
                        <textarea class="form-control" rows="3" id="editNotes"></textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="saveEditBtn"><i class="ri-check-line me-1"></i>Save Changes</button>
            </div>
        </div>
    </div>
</div>

<!-- ===================== ADD NOTE MODAL ===================== -->
<div class="modal fade" id="addNoteModal" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="ri-sticky-note-line me-2"></i>Add Note</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <textarea class="form-control" rows="4" id="noteText" placeholder="Type your note..."></textarea>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="saveNoteBtn"><i class="ri-check-line me-1"></i>Save</button>
            </div>
        </div>
    </div>
</div>

<!-- ===================== ADVANCE STAGE MODAL ===================== -->
<div class="modal fade" id="advanceModal" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title"><i class="ri-arrow-right-line me-2"></i>Advance Stage</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <p class="mb-1">Move case to next stage:</p>
                <div class="d-flex align-items-center justify-content-center gap-2 my-3">
                    <span class="badge bg-secondary" id="advFrom"></span>
                    <i class="ri-arrow-right-line text-primary fs-5"></i>
                    <span class="badge bg-primary" id="advTo"></span>
                </div>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="confirmAdvBtn"><i class="ri-check-line me-1"></i>Advance</button>
            </div>
        </div>
    </div>
</div>

<!-- ===================== RECORD PAYMENT MODAL ===================== -->
<div class="modal fade" id="recordPaymentModal" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="ri-money-dollar-circle-line me-2"></i>Record Payment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Amount (PLN)</label>
                    <input type="number" class="form-control" id="paymentAmount" placeholder="0.00">
                </div>
                <div class="mb-3">
                    <label class="form-label">Method</label>
                    <select class="form-select" id="paymentMethod">
                        <option>Bank Transfer</option>
                        <option>Cash</option>
                        <option>Card</option>
                        <option>BLIK</option>
                        <option>PayU</option>
                    </select>
                </div>
                <div>
                    <label class="form-label">Note</label>
                    <input type="text" class="form-control" id="paymentNote" placeholder="Payment description...">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-success" id="savePaymentBtn"><i class="ri-check-line me-1"></i>Record</button>
            </div>
        </div>
    </div>
</div>

<!-- ===================== ADD TASK MODAL ===================== -->
<div class="modal fade" id="addTaskModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="ri-task-line me-2"></i>Add Task</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label">Task Title <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="taskTitle" placeholder="Task title">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Type</label>
                        <select class="form-select" id="taskType">
                            <option>Document Prep</option>
                            <option>Client Meeting</option>
                            <option>Office Visit</option>
                            <option>Follow-up</option>
                            <option>Payment</option>
                            <option>Other</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Due Date</label>
                        <input type="date" class="form-control" id="taskDue">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Priority</label>
                        <select class="form-select" id="taskPriority">
                            <option>Medium</option>
                            <option>Low</option>
                            <option>High</option>
                            <option>Critical</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Assignee</label>
                        <select class="form-select" id="taskAssignee">
                            <option>Anna Kowalska</option>
                            <option>Piotr Nowak</option>
                            <option>Katarzyna Wiśniewska</option>
                            <option>Marek Zieliński</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="saveTaskBtn"><i class="ri-check-line me-1"></i>Create Task</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
document.addEventListener('DOMContentLoaded', function() {

    // ========== STAGES ==========
    var STAGES = [
        { id: 'submitted',        label: 'Submitted',          color: '#6c757d' },
        { id: 'fingerprint_wait',  label: 'Await Prints',       color: '#f97316' },
        { id: 'fingerprint_apt',   label: 'Print Appt',         color: '#eab308' },
        { id: 'fingerprint_done',  label: 'Prints Done',        color: '#0dcaf0' },
        { id: 'awaiting',          label: 'Await Decision',     color: '#5865F2' },
        { id: 'decision',          label: 'Decision',           color: '#8b5cf6' },
        { id: 'card_issued',       label: 'Card Issued',        color: '#198754' }
    ];

    var TYPE_COLORS = {
        'Temporary Residence': '#5865F2', 'Permanent Residence': '#8b5cf6', 'Work Permit': '#0dcaf0',
        'EU Blue Card': '#0d6efd', 'Citizenship': '#198754', 'Family Reunification': '#e91e8d',
        'Appeal': '#dc3545', 'Deportation Cancel': '#f97316'
    };
    var PRIORITY_COLORS = { Critical: '#dc3545', High: '#f97316', Medium: '#ffc107', Low: '#198754' };

    // ========== ALL CASES DATA ==========
    var CASES = {
        'c1': {
            caseId: '#WC-2026-0147', client: 'Oleksandr Petrov', type: 'Temporary Residence', priority: 'High',
            manager: 'Anna Kowalska', stage: 'awaiting', filed: '2026-01-15', days: 46, nationality: 'Ukrainian',
            notes: 'Full package submitted, waiting for voivode decision. Client has valid PESEL and zameldowanie.',
            clientId: 1, totalContract: 4500, paid: 3000, debt: 1500,
            timeline: [
                { date: '2026-01-15', text: 'Case filed — Temporary Residence application submitted', type: 'milestone' },
                { date: '2026-01-20', text: 'Documents verified — all 14 documents complete', type: 'check' },
                { date: '2026-02-05', text: 'Fingerprint appointment scheduled for Feb 12', type: 'event' },
                { date: '2026-02-12', text: 'Fingerprints collected at Urząd Wojewódzki', type: 'milestone' },
                { date: '2026-02-15', text: 'Full package submitted to voivode office', type: 'milestone' },
                { date: '2026-02-20', text: 'Payment received — 1,500 PLN (installment 2/3)', type: 'payment' },
                { date: '2026-03-01', text: 'Status update: awaiting decision', type: 'info' }
            ],
            tasks: [
                { title: 'Follow up with voivode office', type: 'Follow-up', due: '2026-03-10', priority: 'High', assignee: 'Anna Kowalska', status: 'pending' },
                { title: 'Prepare additional documents if requested', type: 'Document Prep', due: '2026-03-15', priority: 'Medium', assignee: 'Anna Kowalska', status: 'pending' },
                { title: 'Collect final payment', type: 'Payment', due: '2026-03-20', priority: 'Medium', assignee: 'Piotr Nowak', status: 'pending' }
            ],
            documents: [
                { name: 'Passport copy', type: 'pdf', size: '2.1 MB', date: '2026-01-15' },
                { name: 'Employment contract', type: 'pdf', size: '1.4 MB', date: '2026-01-15' },
                { name: 'Zameldowanie', type: 'pdf', size: '0.8 MB', date: '2026-01-15' },
                { name: 'Health insurance', type: 'pdf', size: '1.2 MB', date: '2026-01-16' },
                { name: 'PESEL confirmation', type: 'pdf', size: '0.5 MB', date: '2026-01-16' },
                { name: 'Photo 35x45mm', type: 'img', size: '0.3 MB', date: '2026-01-15' }
            ],
            payments: [
                { date: '2026-01-15', amount: 1500, method: 'Bank Transfer', note: 'Installment 1/3' },
                { date: '2026-02-20', amount: 1500, method: 'BLIK', note: 'Installment 2/3' }
            ],
            activityNotes: [
                { date: '2026-03-01', author: 'Anna Kowalska', text: 'Called voivode office — case is being processed, no additional docs requested.' },
                { date: '2026-02-15', author: 'Piotr Nowak', text: 'Client confirmed all employment details are current.' }
            ]
        },
        'c2': {
            caseId: '#WC-2026-0146', client: 'Maria Ivanova', type: 'Work Permit', priority: 'Medium',
            manager: 'Piotr Nowak', stage: 'fingerprint_done', filed: '2026-01-20', days: 12, nationality: 'Ukrainian',
            notes: 'Type A work permit, employer: TechCorp Sp. z o.o.',
            clientId: 2, totalContract: 3500, paid: 2000, debt: 1500,
            timeline: [
                { date: '2026-01-20', text: 'Application submitted — Work Permit Type A', type: 'milestone' },
                { date: '2026-02-01', text: 'Employer documents received from TechCorp', type: 'check' },
                { date: '2026-02-10', text: 'Fingerprints collected', type: 'milestone' },
                { date: '2026-02-18', text: 'Package submitted to labor office', type: 'milestone' }
            ],
            tasks: [
                { title: 'Check labor office response', type: 'Follow-up', due: '2026-03-05', priority: 'Medium', assignee: 'Piotr Nowak', status: 'pending' }
            ],
            documents: [
                { name: 'Passport copy', type: 'pdf', size: '1.8 MB', date: '2026-01-20' },
                { name: 'Employment offer', type: 'pdf', size: '0.9 MB', date: '2026-01-20' },
                { name: 'Employer KRS extract', type: 'pdf', size: '1.1 MB', date: '2026-02-01' }
            ],
            payments: [
                { date: '2026-01-20', amount: 2000, method: 'Bank Transfer', note: 'Initial payment' }
            ],
            activityNotes: [
                { date: '2026-02-18', author: 'Piotr Nowak', text: 'All documents submitted. Waiting for labor market test results.' }
            ]
        },
        'c3': {
            caseId: '#WC-2026-0139', client: 'Aliaksandr Kazlou', type: 'Permanent Residence', priority: 'Medium',
            manager: 'Piotr Nowak', stage: 'decision', filed: '2025-11-10', days: 5, nationality: 'Belarusian',
            notes: '10+ years in Poland, decision positive, awaiting card production.',
            clientId: 3, totalContract: 6000, paid: 6000, debt: 0,
            timeline: [
                { date: '2025-11-10', text: 'Permanent residence application filed', type: 'milestone' },
                { date: '2025-12-01', text: 'Interview completed at voivode office', type: 'milestone' },
                { date: '2026-01-15', text: 'Additional language certificate submitted (B1)', type: 'check' },
                { date: '2026-02-20', text: 'POSITIVE DECISION received!', type: 'milestone' },
                { date: '2026-02-25', text: 'Decision signed by client', type: 'milestone' }
            ],
            tasks: [
                { title: 'Collect residence card from office', type: 'Office Visit', due: '2026-03-15', priority: 'Medium', assignee: 'Piotr Nowak', status: 'pending' }
            ],
            documents: [
                { name: 'Passport copy', type: 'pdf', size: '2.0 MB', date: '2025-11-10' },
                { name: 'Employment history (10 years)', type: 'pdf', size: '3.2 MB', date: '2025-11-10' },
                { name: 'B1 Polish certificate', type: 'pdf', size: '0.7 MB', date: '2026-01-15' },
                { name: 'Positive decision letter', type: 'pdf', size: '1.5 MB', date: '2026-02-20' }
            ],
            payments: [
                { date: '2025-11-10', amount: 3000, method: 'Bank Transfer', note: 'First payment' },
                { date: '2026-01-10', amount: 3000, method: 'Bank Transfer', note: 'Final payment' }
            ],
            activityNotes: [
                { date: '2026-02-20', author: 'Piotr Nowak', text: 'Decision is POSITIVE! Client informed. Card production takes ~2 weeks.' }
            ]
        },
        'c4': {
            caseId: '#WC-2026-0152', client: 'Tetiana Sydorenko', type: 'Appeal', priority: 'Critical',
            manager: 'Marek Zieliński', stage: 'submitted', filed: '2026-02-20', days: 10, nationality: 'Ukrainian',
            notes: 'Appeal against negative decision on temporary residence. Deadline March 15!',
            clientId: 4, totalContract: 5000, paid: 2500, debt: 2500,
            timeline: [
                { date: '2026-02-10', text: 'Negative decision received by client', type: 'milestone' },
                { date: '2026-02-15', text: 'Client consultation — appeal strategy discussed', type: 'event' },
                { date: '2026-02-20', text: 'Appeal documents prepared and submitted', type: 'milestone' }
            ],
            tasks: [
                { title: 'Prepare additional evidence for appeal', type: 'Document Prep', due: '2026-03-10', priority: 'Critical', assignee: 'Marek Zieliński', status: 'in_progress' },
                { title: 'Monitor appeal deadline March 15', type: 'Follow-up', due: '2026-03-15', priority: 'Critical', assignee: 'Marek Zieliński', status: 'pending' }
            ],
            documents: [
                { name: 'Negative decision', type: 'pdf', size: '2.3 MB', date: '2026-02-10' },
                { name: 'Appeal document', type: 'pdf', size: '4.1 MB', date: '2026-02-20' },
                { name: 'Supporting evidence', type: 'zip', size: '8.5 MB', date: '2026-02-20' }
            ],
            payments: [
                { date: '2026-02-15', amount: 2500, method: 'Cash', note: 'Initial appeal payment' }
            ],
            activityNotes: [
                { date: '2026-02-20', author: 'Marek Zieliński', text: 'URGENT: Appeal submitted. Need additional employment evidence by March 10.' }
            ]
        },
        'c5': {
            caseId: '#WC-2026-0133', client: 'Dmytro Boyko', type: 'Deportation Cancel', priority: 'Critical',
            manager: 'Marek Zieliński', stage: 'awaiting', filed: '2025-10-05', days: 30, nationality: 'Ukrainian',
            notes: 'Deportation order cancellation — urgent humanitarian case. Family with 2 children.',
            clientId: 5, totalContract: 8000, paid: 5000, debt: 3000,
            timeline: [
                { date: '2025-10-05', text: 'Deportation cancellation request filed', type: 'milestone' },
                { date: '2025-10-20', text: 'Humanitarian grounds evidence submitted', type: 'check' },
                { date: '2025-11-15', text: 'Court hearing — postponed to December', type: 'event' },
                { date: '2025-12-10', text: 'Court hearing — additional evidence requested', type: 'event' },
                { date: '2026-01-20', text: 'Additional evidence submitted (children school records)', type: 'check' },
                { date: '2026-02-01', text: 'Awaiting final court decision', type: 'info' }
            ],
            tasks: [
                { title: 'Follow up on court decision date', type: 'Follow-up', due: '2026-03-05', priority: 'Critical', assignee: 'Marek Zieliński', status: 'in_progress' },
                { title: 'Prepare backup appeal strategy', type: 'Document Prep', due: '2026-03-15', priority: 'High', assignee: 'Marek Zieliński', status: 'pending' }
            ],
            documents: [
                { name: 'Deportation order', type: 'pdf', size: '1.8 MB', date: '2025-10-01' },
                { name: 'Cancellation request', type: 'pdf', size: '5.2 MB', date: '2025-10-05' },
                { name: 'Children school records', type: 'pdf', size: '2.1 MB', date: '2026-01-20' },
                { name: 'Employer support letter', type: 'pdf', size: '0.9 MB', date: '2025-10-15' }
            ],
            payments: [
                { date: '2025-10-05', amount: 3000, method: 'Bank Transfer', note: 'Initial payment' },
                { date: '2025-12-01', amount: 2000, method: 'Cash', note: 'Second payment' }
            ],
            activityNotes: [
                { date: '2026-02-01', author: 'Marek Zieliński', text: 'Court decision expected within 30 days. Case looks favorable based on humanitarian grounds.' }
            ]
        },
        'c6': {
            caseId: '#WC-2026-0155', client: 'Rajesh Kumar', type: 'EU Blue Card', priority: 'High',
            manager: 'Katarzyna Wiśniewska', stage: 'fingerprint_wait', filed: '2026-02-25', days: 5, nationality: 'Indian',
            notes: 'IT specialist, salary meets EU Blue Card threshold. Employer: DataTech Warsaw.',
            clientId: 6, totalContract: 5500, paid: 2750, debt: 2750,
            timeline: [
                { date: '2026-02-25', text: 'EU Blue Card application filed', type: 'milestone' },
                { date: '2026-02-28', text: 'Employer documents verified', type: 'check' }
            ],
            tasks: [
                { title: 'Schedule fingerprint appointment', type: 'Office Visit', due: '2026-03-08', priority: 'High', assignee: 'Katarzyna Wiśniewska', status: 'pending' },
                { title: 'Request salary confirmation from employer', type: 'Document Prep', due: '2026-03-05', priority: 'Medium', assignee: 'Katarzyna Wiśniewska', status: 'pending' }
            ],
            documents: [
                { name: 'Passport copy', type: 'pdf', size: '2.0 MB', date: '2026-02-25' },
                { name: 'IT degree diploma', type: 'pdf', size: '1.5 MB', date: '2026-02-25' },
                { name: 'Employment contract', type: 'pdf', size: '1.1 MB', date: '2026-02-25' }
            ],
            payments: [
                { date: '2026-02-25', amount: 2750, method: 'Bank Transfer', note: 'Initial payment 50%' }
            ],
            activityNotes: [
                { date: '2026-02-28', author: 'Katarzyna Wiśniewska', text: 'All employer docs OK. Salary 18,000 PLN/month — exceeds Blue Card threshold.' }
            ]
        }
    };

    // Also map by caseId string
    Object.keys(CASES).forEach(function(k) {
        CASES[CASES[k].caseId] = CASES[k];
        CASES[CASES[k].caseId]._key = k;
    });

    // ========== GET CASE FROM URL ==========
    var params = new URLSearchParams(window.location.search);
    var caseKey = params.get('case') || params.get('id') || '';
    var caseData = CASES[caseKey];

    if (!caseData) {
        document.getElementById('caseDetailPage').classList.add('d-none');
        document.getElementById('noCasePage').classList.remove('d-none');
        return;
    }

    // ========== RENDER PAGE ==========
    var tc = TYPE_COLORS[caseData.type] || '#5865F2';
    var pc = PRIORITY_COLORS[caseData.priority] || '#ffc107';
    var stageIdx = STAGES.findIndex(function(s) { return s.id === caseData.stage; });

    // Header
    document.getElementById('caseHeader').style.background = 'linear-gradient(135deg, ' + tc + ', ' + shadeColor(tc, -30) + ')';
    document.getElementById('hdCaseId').textContent = caseData.caseId;
    document.getElementById('hdClientName').textContent = caseData.client;
    document.getElementById('hdType').textContent = caseData.type;
    document.getElementById('hdPriority').textContent = caseData.priority;
    document.getElementById('hdNationality').textContent = caseData.nationality;

    // Pipeline
    var pipeHtml = '';
    STAGES.forEach(function(s, i) {
        var cls = 'stage-step';
        if (i < stageIdx) cls += ' completed';
        else if (i === stageIdx) cls += ' current';
        pipeHtml += '<div class="' + cls + '"><span class="stage-num">' + (i + 1) + '</span>' + s.label + '</div>';
    });
    document.getElementById('stagePipeline').innerHTML = pipeHtml;

    // Stats
    var totalDays = Math.floor((new Date() - new Date(caseData.filed)) / 86400000);
    document.getElementById('statDaysInStage').textContent = caseData.days;
    document.getElementById('statTotalDays').textContent = totalDays;
    document.getElementById('statPaid').textContent = number_format(caseData.paid) + ' PLN';
    document.getElementById('statDebt').textContent = number_format(caseData.debt) + ' PLN';

    // Info
    document.getElementById('infoClient').textContent = caseData.client;
    document.getElementById('infoType').innerHTML = '<span class="badge" style="background:' + tc + '">' + caseData.type + '</span>';
    document.getElementById('infoFiled').textContent = caseData.filed;
    document.getElementById('infoPriority').innerHTML = '<span class="badge" style="background:' + pc + ';color:' + (caseData.priority === 'Medium' ? '#000' : '#fff') + '">' + caseData.priority + '</span>';
    document.getElementById('infoNationality').textContent = caseData.nationality;
    document.getElementById('infoStatus').innerHTML = '<span class="badge" style="background:' + STAGES[stageIdx].color + '">' + STAGES[stageIdx].label + '</span>';
    document.getElementById('infoNotes').textContent = caseData.notes;
    document.getElementById('infoManager').textContent = caseData.manager;

    // Client invoices link
    if (caseData.clientId) {
        document.getElementById('clientInvoicesLink').href = 'crm-client-invoices?client=' + caseData.clientId;
    }

    // Timeline
    var tlHtml = '';
    caseData.timeline.forEach(function(t, i) {
        var cls = i < caseData.timeline.length - 1 ? 'completed' : '';
        tlHtml += '<div class="timeline-item ' + cls + '">' +
            '<div class="timeline-date">' + t.date + '</div>' +
            '<div class="timeline-text">' + t.text + '</div></div>';
    });
    document.getElementById('timelineContainer').innerHTML = tlHtml;

    // Tasks
    document.getElementById('tasksCount').textContent = caseData.tasks.length;
    var tasksHtml = '';
    if (caseData.tasks.length === 0) {
        tasksHtml = '<div class="text-center text-muted py-3"><i class="ri-task-line fs-3 d-block mb-1"></i>No tasks</div>';
    } else {
        caseData.tasks.forEach(function(t) {
            var sc = t.status === 'in_progress' ? 'text-warning' : (t.status === 'completed' ? 'text-success' : 'text-muted');
            var si = t.status === 'in_progress' ? 'ri-loader-4-line' : (t.status === 'completed' ? 'ri-check-line' : 'ri-checkbox-blank-circle-line');
            var pp = PRIORITY_COLORS[t.priority] || '#ffc107';
            tasksHtml += '<div class="task-row d-flex justify-content-between align-items-center">' +
                '<div class="d-flex align-items-center gap-2"><i class="' + si + ' ' + sc + ' fs-5"></i><div>' +
                '<div class="fw-semibold fs-13">' + t.title + '</div>' +
                '<div class="fs-12 text-muted">' + t.type + ' • ' + t.assignee + '</div></div></div>' +
                '<div class="text-end"><span class="badge" style="background:' + pp + ';color:' + (t.priority === 'Medium' ? '#000' : '#fff') + '">' + t.priority + '</span>' +
                '<div class="fs-12 text-muted mt-1">' + t.due + '</div></div></div>';
        });
    }
    document.getElementById('tasksContainer').innerHTML = tasksHtml;

    // Documents
    var docIcons = { pdf: 'ri-file-pdf-line text-danger', img: 'ri-image-line text-success', zip: 'ri-folder-zip-line text-warning', doc: 'ri-file-word-line text-primary' };
    var docBgs = { pdf: 'bg-danger-subtle', img: 'bg-success-subtle', zip: 'bg-warning-subtle', doc: 'bg-primary-subtle' };
    var docsHtml = '';
    caseData.documents.forEach(function(d) {
        var icon = docIcons[d.type] || 'ri-file-line text-secondary';
        var bg = docBgs[d.type] || 'bg-light';
        docsHtml += '<div class="doc-item"><div class="doc-icon ' + bg + ' me-2"><i class="' + icon + '"></i></div>' +
            '<div class="flex-grow-1"><div class="fw-semibold fs-13">' + d.name + '</div>' +
            '<div class="fs-12 text-muted">' + d.size + ' • ' + d.date + '</div></div>' +
            '<i class="ri-download-line text-primary"></i></div>';
    });
    document.getElementById('docsContainer').innerHTML = docsHtml;

    // Payments
    var payHtml = '';
    caseData.payments.forEach(function(p) {
        payHtml += '<div class="payment-row d-flex justify-content-between align-items-center">' +
            '<div><div class="fw-semibold fs-13 text-success">+' + number_format(p.amount) + ' PLN</div>' +
            '<div class="fs-12 text-muted">' + p.method + ' • ' + p.note + '</div></div>' +
            '<span class="fs-12 text-muted">' + p.date + '</span></div>';
    });
    if (caseData.debt > 0) {
        payHtml += '<div class="mt-2 p-2 bg-danger-subtle rounded text-center"><span class="fw-semibold text-danger fs-13">Outstanding: ' + number_format(caseData.debt) + ' PLN</span></div>';
    } else {
        payHtml += '<div class="mt-2 p-2 bg-success-subtle rounded text-center"><span class="fw-semibold text-success fs-13">Fully Paid ✓</span></div>';
    }
    document.getElementById('paymentsContainer').innerHTML = payHtml;

    // Activity Notes
    var notesHtml = '';
    caseData.activityNotes.forEach(function(n) {
        notesHtml += '<div class="note-bubble"><div class="d-flex justify-content-between mb-1">' +
            '<span class="fw-semibold fs-13">' + n.author + '</span>' +
            '<span class="fs-12 text-muted">' + n.date + '</span></div>' +
            '<div class="fs-13 text-muted">' + n.text + '</div></div>';
    });
    document.getElementById('notesContainer').innerHTML = notesHtml;

    // ========== ADVANCE STAGE ==========
    var advBtn = document.getElementById('advanceStageBtn');
    var qaAdvBtn = document.getElementById('qaAdvanceBtn');
    if (stageIdx >= STAGES.length - 1) {
        advBtn.disabled = true; advBtn.innerHTML = '<i class="ri-check-double-line me-1"></i>Completed';
        qaAdvBtn.disabled = true; qaAdvBtn.innerHTML = '<i class="ri-check-double-line me-1"></i>Completed';
    }

    function openAdvance() {
        if (stageIdx >= STAGES.length - 1) return;
        document.getElementById('advFrom').textContent = STAGES[stageIdx].label;
        document.getElementById('advTo').textContent = STAGES[stageIdx + 1].label;
        new bootstrap.Modal(document.getElementById('advanceModal')).show();
    }
    advBtn.addEventListener('click', openAdvance);
    qaAdvBtn.addEventListener('click', openAdvance);

    document.getElementById('confirmAdvBtn').addEventListener('click', function() {
        if (stageIdx < STAGES.length - 1) {
            stageIdx++;
            caseData.stage = STAGES[stageIdx].id;
            caseData.days = 0;
            // Update pipeline
            var steps = document.querySelectorAll('.stage-step');
            steps.forEach(function(s, i) {
                s.className = 'stage-step';
                if (i < stageIdx) s.classList.add('completed');
                else if (i === stageIdx) s.classList.add('current');
            });
            document.getElementById('infoStatus').innerHTML = '<span class="badge" style="background:' + STAGES[stageIdx].color + '">' + STAGES[stageIdx].label + '</span>';
            document.getElementById('statDaysInStage').textContent = '0';
            if (stageIdx >= STAGES.length - 1) {
                advBtn.disabled = true; advBtn.innerHTML = '<i class="ri-check-double-line me-1"></i>Completed';
                qaAdvBtn.disabled = true; qaAdvBtn.innerHTML = '<i class="ri-check-double-line me-1"></i>Completed';
            }
            showToast('Stage advanced to ' + STAGES[stageIdx].label, 'success');
        }
        bootstrap.Modal.getInstance(document.getElementById('advanceModal')).hide();
    });

    // ========== EDIT CASE ==========
    function openEdit() {
        document.getElementById('editClient').value = caseData.client;
        setSelect('editType', caseData.type);
        setSelect('editPriority', caseData.priority);
        setSelect('editManager', caseData.manager);
        document.getElementById('editNationality').value = caseData.nationality;
        setSelect('editStage', caseData.stage);
        document.getElementById('editNotes').value = caseData.notes;
        new bootstrap.Modal(document.getElementById('editCaseModal')).show();
    }
    document.getElementById('editCaseBtn').addEventListener('click', openEdit);
    document.getElementById('qaEditBtn').addEventListener('click', openEdit);

    document.getElementById('saveEditBtn').addEventListener('click', function() {
        caseData.client = document.getElementById('editClient').value;
        caseData.type = document.getElementById('editType').value;
        caseData.priority = document.getElementById('editPriority').value;
        caseData.manager = document.getElementById('editManager').value;
        caseData.nationality = document.getElementById('editNationality').value;
        caseData.notes = document.getElementById('editNotes').value;
        var newStage = document.getElementById('editStage').value;
        if (newStage !== caseData.stage) {
            caseData.stage = newStage;
            stageIdx = STAGES.findIndex(function(s) { return s.id === newStage; });
            caseData.days = 0;
        }
        // Refresh header
        tc = TYPE_COLORS[caseData.type] || '#5865F2';
        pc = PRIORITY_COLORS[caseData.priority] || '#ffc107';
        document.getElementById('caseHeader').style.background = 'linear-gradient(135deg, ' + tc + ', ' + shadeColor(tc, -30) + ')';
        document.getElementById('hdClientName').textContent = caseData.client;
        document.getElementById('hdType').textContent = caseData.type;
        document.getElementById('hdPriority').textContent = caseData.priority;
        document.getElementById('hdNationality').textContent = caseData.nationality;
        document.getElementById('infoClient').textContent = caseData.client;
        document.getElementById('infoType').innerHTML = '<span class="badge" style="background:' + tc + '">' + caseData.type + '</span>';
        document.getElementById('infoPriority').innerHTML = '<span class="badge" style="background:' + pc + ';color:' + (caseData.priority === 'Medium' ? '#000' : '#fff') + '">' + caseData.priority + '</span>';
        document.getElementById('infoNationality').textContent = caseData.nationality;
        document.getElementById('infoNotes').textContent = caseData.notes;
        document.getElementById('infoManager').textContent = caseData.manager;
        document.getElementById('infoStatus').innerHTML = '<span class="badge" style="background:' + STAGES[stageIdx].color + '">' + STAGES[stageIdx].label + '</span>';
        // Pipeline
        var steps = document.querySelectorAll('.stage-step');
        steps.forEach(function(s, i) {
            s.className = 'stage-step';
            if (i < stageIdx) s.classList.add('completed');
            else if (i === stageIdx) s.classList.add('current');
        });
        bootstrap.Modal.getInstance(document.getElementById('editCaseModal')).hide();
        showToast('Case updated', 'success');
    });

    // ========== ADD NOTE ==========
    function openAddNote() {
        document.getElementById('noteText').value = '';
        new bootstrap.Modal(document.getElementById('addNoteModal')).show();
    }
    document.getElementById('addNoteBtn').addEventListener('click', openAddNote);
    document.getElementById('qaAddNote').addEventListener('click', openAddNote);

    document.getElementById('saveNoteBtn').addEventListener('click', function() {
        var text = document.getElementById('noteText').value.trim();
        if (!text) return;
        var note = { date: new Date().toISOString().split('T')[0], author: caseData.manager, text: text };
        caseData.activityNotes.unshift(note);
        var noteEl = document.createElement('div');
        noteEl.className = 'note-bubble';
        noteEl.innerHTML = '<div class="d-flex justify-content-between mb-1"><span class="fw-semibold fs-13">' + note.author + '</span><span class="fs-12 text-muted">' + note.date + '</span></div><div class="fs-13 text-muted">' + note.text + '</div>';
        document.getElementById('notesContainer').prepend(noteEl);
        bootstrap.Modal.getInstance(document.getElementById('addNoteModal')).hide();
        showToast('Note added', 'success');
    });

    // ========== RECORD PAYMENT ==========
    document.getElementById('qaRecordPayment').addEventListener('click', function() {
        document.getElementById('paymentAmount').value = '';
        document.getElementById('paymentNote').value = '';
        new bootstrap.Modal(document.getElementById('recordPaymentModal')).show();
    });

    document.getElementById('savePaymentBtn').addEventListener('click', function() {
        var amount = parseFloat(document.getElementById('paymentAmount').value);
        if (!amount || amount <= 0) { showToast('Enter valid amount', 'warning'); return; }
        var payment = {
            date: new Date().toISOString().split('T')[0],
            amount: amount,
            method: document.getElementById('paymentMethod').value,
            note: document.getElementById('paymentNote').value || 'Payment'
        };
        caseData.payments.push(payment);
        caseData.paid += amount;
        caseData.debt = Math.max(0, caseData.totalContract - caseData.paid);
        document.getElementById('statPaid').textContent = number_format(caseData.paid) + ' PLN';
        document.getElementById('statDebt').textContent = number_format(caseData.debt) + ' PLN';
        // Re-render payments
        var payEl = document.createElement('div');
        payEl.className = 'payment-row d-flex justify-content-between align-items-center';
        payEl.innerHTML = '<div><div class="fw-semibold fs-13 text-success">+' + number_format(payment.amount) + ' PLN</div><div class="fs-12 text-muted">' + payment.method + ' • ' + payment.note + '</div></div><span class="fs-12 text-muted">' + payment.date + '</span>';
        var container = document.getElementById('paymentsContainer');
        var outstanding = container.querySelector('.bg-danger-subtle, .bg-success-subtle');
        if (outstanding) container.insertBefore(payEl, outstanding);
        else container.appendChild(payEl);
        if (outstanding) {
            if (caseData.debt > 0) {
                outstanding.innerHTML = '<span class="fw-semibold text-danger fs-13">Outstanding: ' + number_format(caseData.debt) + ' PLN</span>';
                outstanding.className = 'mt-2 p-2 bg-danger-subtle rounded text-center';
            } else {
                outstanding.innerHTML = '<span class="fw-semibold text-success fs-13">Fully Paid ✓</span>';
                outstanding.className = 'mt-2 p-2 bg-success-subtle rounded text-center';
            }
        }
        bootstrap.Modal.getInstance(document.getElementById('recordPaymentModal')).hide();
        showToast('Payment of ' + number_format(amount) + ' PLN recorded', 'success');
    });

    // ========== ADD TASK ==========
    document.getElementById('qaAddTaskBtn').addEventListener('click', function() {
        document.getElementById('taskTitle').value = '';
        document.getElementById('taskDue').value = '';
        new bootstrap.Modal(document.getElementById('addTaskModal')).show();
    });

    document.getElementById('saveTaskBtn').addEventListener('click', function() {
        var title = document.getElementById('taskTitle').value.trim();
        if (!title) { showToast('Enter task title', 'warning'); return; }
        var task = {
            title: title,
            type: document.getElementById('taskType').value,
            due: document.getElementById('taskDue').value || 'No date',
            priority: document.getElementById('taskPriority').value,
            assignee: document.getElementById('taskAssignee').value,
            status: 'pending'
        };
        caseData.tasks.push(task);
        document.getElementById('tasksCount').textContent = caseData.tasks.length;
        var pp = PRIORITY_COLORS[task.priority] || '#ffc107';
        var taskEl = document.createElement('div');
        taskEl.className = 'task-row d-flex justify-content-between align-items-center';
        taskEl.innerHTML = '<div class="d-flex align-items-center gap-2"><i class="ri-checkbox-blank-circle-line text-muted fs-5"></i><div><div class="fw-semibold fs-13">' + task.title + '</div><div class="fs-12 text-muted">' + task.type + ' • ' + task.assignee + '</div></div></div><div class="text-end"><span class="badge" style="background:' + pp + ';color:' + (task.priority === 'Medium' ? '#000' : '#fff') + '">' + task.priority + '</span><div class="fs-12 text-muted mt-1">' + task.due + '</div></div>';
        document.getElementById('tasksContainer').appendChild(taskEl);
        bootstrap.Modal.getInstance(document.getElementById('addTaskModal')).hide();
        showToast('Task created', 'success');
    });

    // ========== HELPERS ==========
    function setSelect(id, val) {
        var sel = document.getElementById(id);
        for (var i = 0; i < sel.options.length; i++) {
            if (sel.options[i].value === val || sel.options[i].textContent === val) { sel.selectedIndex = i; return; }
        }
    }

    function number_format(n) {
        return n.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');
    }

    function shadeColor(color, percent) {
        var R = parseInt(color.substring(1, 3), 16);
        var G = parseInt(color.substring(3, 5), 16);
        var B = parseInt(color.substring(5, 7), 16);
        R = parseInt(R * (100 + percent) / 100);
        G = parseInt(G * (100 + percent) / 100);
        B = parseInt(B * (100 + percent) / 100);
        R = Math.min(255, Math.max(0, R));
        G = Math.min(255, Math.max(0, G));
        B = Math.min(255, Math.max(0, B));
        return '#' + ((1 << 24) + (R << 16) + (G << 8) + B).toString(16).slice(1);
    }

    function showToast(msg, type) {
        type = type || 'success';
        var icons = { success: 'ri-check-double-line', danger: 'ri-delete-bin-line', warning: 'ri-alert-line', info: 'ri-information-line' };
        var toast = document.createElement('div');
        toast.className = 'position-fixed top-0 end-0 p-3';
        toast.style.zIndex = '9999';
        toast.innerHTML = '<div class="toast show border-0 shadow" role="alert"><div class="toast-body d-flex align-items-center gap-2 text-' + type + '"><i class="' + (icons[type]||icons.success) + ' fs-5"></i><span>' + msg + '</span></div></div>';
        document.body.appendChild(toast);
        setTimeout(function() { toast.remove(); }, 3000);
    }
});
</script>
@endsection
