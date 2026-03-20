@extends('partials.layouts.master')

@section('title', 'Kanban Board | WinCase CRM')
@section('sub-title', 'Kanban Board')
@section('sub-title-lang', 'wc-kanban')
@section('pagetitle', 'CRM')
@section('pagetitle-lang', 'wc-title-crm')

@section('css')
<style>
    .kanban-container { display: flex; gap: 12px; overflow-x: auto; padding-bottom: 16px; min-height: calc(100vh - 200px); }
    .kanban-column { min-width: 280px; max-width: 300px; flex-shrink: 0; display: flex; flex-direction: column; }
    .kanban-column-header { padding: 12px 16px; border-radius: 8px 8px 0 0; display: flex; justify-content: space-between; align-items: center; }
    .kanban-column-header h6 { margin: 0; font-size: 0.85rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; }
    .kanban-column-body { background: #f8f9fa; border-radius: 0 0 8px 8px; padding: 8px; flex: 1; overflow-y: auto; max-height: calc(100vh - 280px); min-height: 200px; }
    .kanban-card { background: #fff; border-radius: 8px; padding: 14px; margin-bottom: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.08); cursor: grab; transition: all 0.2s; border-left: 4px solid transparent; }
    .kanban-card:hover { box-shadow: 0 4px 12px rgba(0,0,0,0.12); transform: translateY(-1px); }
    .kanban-card.dragging { opacity: 0.5; transform: rotate(2deg); }
    .kanban-card .card-case-id { font-size: 0.75rem; font-weight: 600; color: #5865F2; }
    .kanban-card .card-client { font-size: 0.9rem; font-weight: 600; margin: 4px 0; }
    .kanban-card .card-type { font-size: 0.7rem; }
    .kanban-card .card-meta { font-size: 0.75rem; color: #6c757d; }
    .kanban-card .card-footer-row { display: flex; justify-content: space-between; align-items: center; margin-top: 8px; padding-top: 8px; border-top: 1px solid #f0f0f0; }
    .kanban-card .avatar-xs { width: 24px; height: 24px; border-radius: 50%; background: #5865F2; color: #fff; display: flex; align-items: center; justify-content: center; font-size: 0.65rem; font-weight: 600; }
    .priority-dot { width: 8px; height: 8px; border-radius: 50%; display: inline-block; }
    .drag-over { background: #e8f4fd !important; }
    .column-count { min-width: 22px; height: 22px; border-radius: 11px; display: flex; align-items: center; justify-content: center; font-size: 0.75rem; font-weight: 600; }
    .kanban-add-card { border: 2px dashed #dee2e6; border-radius: 8px; padding: 12px; text-align: center; cursor: pointer; color: #6c757d; font-size: 0.85rem; transition: all 0.2s; }
    .kanban-add-card:hover { border-color: #5865F2; color: #5865F2; background: rgba(88,101,242,0.05); }
    .progress-thin { height: 4px; border-radius: 2px; }
    .days-badge { font-size: 0.7rem; padding: 2px 6px; border-radius: 4px; }
    .filter-bar { background: #fff; border-radius: 8px; padding: 12px 16px; margin-bottom: 16px; box-shadow: 0 1px 3px rgba(0,0,0,0.08); }
    .view-detail-row { padding: 8px 0; border-bottom: 1px solid #f0f0f0; }
    .view-detail-row:last-child { border-bottom: none; }
    .view-detail-label { font-weight: 600; font-size: 0.85rem; color: #6c757d; min-width: 120px; }
    .stage-indicator { display: flex; gap: 4px; margin-top: 10px; }
    .stage-dot { width: 100%; height: 4px; border-radius: 2px; background: #e9ecef; }
    .stage-dot.completed { background: #198754; }
    .stage-dot.current { background: #5865F2; }
</style>
@endsection

@section('content')
<!-- Filter Bar -->
<div class="filter-bar d-flex flex-wrap align-items-center gap-3">
    <div class="d-flex align-items-center gap-2">
        <label class="form-label mb-0 fs-13 fw-semibold text-muted">Case Type:</label>
        <select class="form-select form-select-sm" style="width:180px" id="filterType">
            <option value="all">All Types</option>
            <option value="Temporary Residence">Temporary Residence</option>
            <option value="Permanent Residence">Permanent Residence</option>
            <option value="Work Permit">Work Permit</option>
            <option value="EU Blue Card">EU Blue Card</option>
            <option value="Citizenship">Citizenship</option>
            <option value="Family Reunification">Family Reunification</option>
            <option value="Appeal">Appeal</option>
            <option value="Deportation Cancel">Deportation Cancel</option>
        </select>
    </div>
    <div class="d-flex align-items-center gap-2">
        <label class="form-label mb-0 fs-13 fw-semibold text-muted">Manager:</label>
        <select class="form-select form-select-sm" style="width:180px" id="filterManager">
            <option value="all">All Managers</option>
            <option value="Anna Kowalska">Anna Kowalska</option>
            <option value="Piotr Nowak">Piotr Nowak</option>
            <option value="Katarzyna Wiśniewska">Katarzyna Wiśniewska</option>
            <option value="Marek Zieliński">Marek Zieliński</option>
        </select>
    </div>
    <div class="d-flex align-items-center gap-2">
        <label class="form-label mb-0 fs-13 fw-semibold text-muted">Priority:</label>
        <select class="form-select form-select-sm" style="width:140px" id="filterPriority">
            <option value="all">All</option>
            <option value="Critical">Critical</option>
            <option value="High">High</option>
            <option value="Medium">Medium</option>
            <option value="Low">Low</option>
        </select>
    </div>
    <div class="ms-auto d-flex gap-2">
        <span class="badge bg-light text-dark fs-12" id="totalCasesCount">0 cases</span>
        <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addCaseKanbanModal">
            <i class="ri-add-line me-1"></i>New Case
        </button>
    </div>
</div>

<!-- Kanban Board -->
<div class="kanban-container" id="kanbanBoard">
    <!-- Columns generated by JS -->
</div>

<!-- ===================== VIEW CASE MODAL ===================== -->
<div class="modal fade" id="viewCaseKanbanModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" id="viewKanbanHeader">
                <h5 class="modal-title"><i class="ri-briefcase-line me-2"></i><span id="viewKanbanTitle"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-7">
                        <div class="view-detail-row d-flex">
                            <span class="view-detail-label">Case ID</span>
                            <span id="vkCaseId" class="fw-semibold text-primary"></span>
                        </div>
                        <div class="view-detail-row d-flex">
                            <span class="view-detail-label">Client</span>
                            <span id="vkClient"></span>
                        </div>
                        <div class="view-detail-row d-flex">
                            <span class="view-detail-label">Type</span>
                            <span id="vkType"></span>
                        </div>
                        <div class="view-detail-row d-flex">
                            <span class="view-detail-label">Priority</span>
                            <span id="vkPriority"></span>
                        </div>
                        <div class="view-detail-row d-flex">
                            <span class="view-detail-label">Manager</span>
                            <span id="vkManager"></span>
                        </div>
                        <div class="view-detail-row d-flex">
                            <span class="view-detail-label">Status</span>
                            <span id="vkStatus"></span>
                        </div>
                        <div class="view-detail-row d-flex">
                            <span class="view-detail-label">Filed Date</span>
                            <span id="vkFiled"></span>
                        </div>
                        <div class="view-detail-row d-flex">
                            <span class="view-detail-label">Days in Stage</span>
                            <span id="vkDays"></span>
                        </div>
                        <div class="view-detail-row d-flex">
                            <span class="view-detail-label">Nationality</span>
                            <span id="vkNationality"></span>
                        </div>
                        <div class="view-detail-row">
                            <span class="view-detail-label">Notes</span>
                            <p class="text-muted mb-0 mt-1" id="vkNotes"></p>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <!-- Progress -->
                        <div class="card bg-light border-0 mb-3">
                            <div class="card-body py-3">
                                <h6 class="fs-13 text-muted mb-2">Case Progress</h6>
                                <div class="progress progress-thin mb-2">
                                    <div class="progress-bar bg-primary" id="vkProgressBar" style="width:0%"></div>
                                </div>
                                <span class="fs-12 text-muted" id="vkProgressText"></span>
                                <div class="stage-indicator mt-3" id="vkStageIndicator"></div>
                            </div>
                        </div>
                        <!-- Quick Actions -->
                        <div class="card bg-light border-0">
                            <div class="card-body py-3">
                                <h6 class="fs-13 text-muted mb-2">Quick Actions</h6>
                                <div class="d-grid gap-2">
                                    <button class="btn btn-sm btn-primary" id="vkAdvanceBtn"><i class="ri-arrow-right-line me-1"></i>Advance Stage</button>
                                    <button class="btn btn-sm btn-outline-secondary" id="vkEditBtn"><i class="ri-pencil-line me-1"></i>Edit Case</button>
                                    <a class="btn btn-sm btn-outline-info" id="vkOpenCasesLink" href="#"><i class="ri-external-link-line me-1"></i>Open Case Page</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- ===================== ADD CASE MODAL ===================== -->
<div class="modal fade" id="addCaseKanbanModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="ri-add-line me-2"></i>New Case</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label">Client Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="newCaseClient" placeholder="Client full name">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Case Type <span class="text-danger">*</span></label>
                        <select class="form-select" id="newCaseType">
                            <option selected disabled value="">Select type...</option>
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
                        <select class="form-select" id="newCasePriority">
                            <option>Medium</option>
                            <option>Low</option>
                            <option>High</option>
                            <option>Critical</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Manager</label>
                        <select class="form-select" id="newCaseManager">
                            <option value="">Not assigned</option>
                            <option>Anna Kowalska</option>
                            <option>Piotr Nowak</option>
                            <option>Katarzyna Wiśniewska</option>
                            <option>Marek Zieliński</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Nationality</label>
                        <input type="text" class="form-control" id="newCaseNationality" placeholder="e.g. Ukrainian">
                    </div>
                    <div class="col-12">
                        <label class="form-label">Notes</label>
                        <textarea class="form-control" rows="2" id="newCaseNotes" placeholder="Case notes..."></textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="saveCaseKanbanBtn"><i class="ri-check-line me-1"></i>Create Case</button>
            </div>
        </div>
    </div>
</div>

<!-- ===================== EDIT CASE MODAL ===================== -->
<div class="modal fade" id="editCaseKanbanModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="ri-pencil-line me-2"></i>Edit Case</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label">Client Name</label>
                        <input type="text" class="form-control" id="editKClient">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Case Type</label>
                        <select class="form-select" id="editKType">
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
                        <select class="form-select" id="editKPriority">
                            <option>Low</option>
                            <option>Medium</option>
                            <option>High</option>
                            <option>Critical</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Manager</label>
                        <select class="form-select" id="editKManager">
                            <option value="">Not assigned</option>
                            <option>Anna Kowalska</option>
                            <option>Piotr Nowak</option>
                            <option>Katarzyna Wiśniewska</option>
                            <option>Marek Zieliński</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Nationality</label>
                        <input type="text" class="form-control" id="editKNationality">
                    </div>
                    <div class="col-12">
                        <label class="form-label">Notes</label>
                        <textarea class="form-control" rows="2" id="editKNotes"></textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="saveEditKanbanBtn"><i class="ri-check-line me-1"></i>Save Changes</button>
            </div>
        </div>
    </div>
</div>

<!-- ===================== ADVANCE STAGE MODAL ===================== -->
<div class="modal fade" id="advanceStageModal" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title"><i class="ri-arrow-right-line me-2"></i>Advance Stage</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <p class="mb-1">Move case to next stage:</p>
                <div class="d-flex align-items-center justify-content-center gap-2 my-3">
                    <span class="badge bg-secondary" id="advFromStage"></span>
                    <i class="ri-arrow-right-line text-primary fs-5"></i>
                    <span class="badge bg-primary" id="advToStage"></span>
                </div>
                <p class="text-muted fs-12" id="advCaseName"></p>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="confirmAdvanceBtn"><i class="ri-check-line me-1"></i>Advance</button>
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
        { id: 'submitted',     label: 'Submitted to Office',    color: '#6c757d', bgLight: '#f8f9fa' },
        { id: 'fingerprint_wait', label: 'Awaiting Fingerprints', color: '#f97316', bgLight: '#fff7ed' },
        { id: 'fingerprint_apt',  label: 'Fingerprint Appt',     color: '#eab308', bgLight: '#fefce8' },
        { id: 'fingerprint_done', label: 'Prints Submitted',     color: '#0dcaf0', bgLight: '#ecfeff' },
        { id: 'awaiting',      label: 'Awaiting Decision',      color: '#5865F2', bgLight: '#eef2ff' },
        { id: 'decision',      label: 'Decision Signed',        color: '#8b5cf6', bgLight: '#f5f3ff' },
        { id: 'card_issued',   label: 'Card Issued',            color: '#198754', bgLight: '#f0fdf4' }
    ];

    var TYPE_COLORS = {
        'Temporary Residence': '#5865F2', 'Permanent Residence': '#8b5cf6', 'Work Permit': '#0dcaf0',
        'EU Blue Card': '#0d6efd', 'Citizenship': '#198754', 'Family Reunification': '#e91e8d',
        'Appeal': '#dc3545', 'Deportation Cancel': '#f97316'
    };

    var PRIORITY_COLORS = { Critical: '#dc3545', High: '#f97316', Medium: '#ffc107', Low: '#198754' };

    // ========== DEMO CASES ==========
    var nextCaseNum = 160;
    var allCases = [
        { id:'c1', caseId:'#WC-2026-0147', client:'Oleksandr Petrov', type:'Temporary Residence', priority:'High', manager:'Anna Kowalska', stage:'awaiting', filed:'2026-01-15', days:46, nationality:'Ukrainian', notes:'Full package submitted, waiting for voivode decision' },
        { id:'c2', caseId:'#WC-2026-0146', client:'Maria Ivanova', type:'Work Permit', priority:'Medium', manager:'Piotr Nowak', stage:'fingerprint_done', filed:'2026-01-20', days:12, nationality:'Ukrainian', notes:'Type A work permit, employer: TechCorp Sp. z o.o.' },
        { id:'c3', caseId:'#WC-2026-0139', client:'Aliaksandr Kazlou', type:'Permanent Residence', priority:'Medium', manager:'Piotr Nowak', stage:'decision', filed:'2025-11-10', days:5, nationality:'Belarusian', notes:'10+ years in Poland, decision positive, awaiting card' },
        { id:'c4', caseId:'#WC-2026-0152', client:'Tetiana Sydorenko', type:'Appeal', priority:'Critical', manager:'Marek Zieliński', stage:'submitted', filed:'2026-02-20', days:10, nationality:'Ukrainian', notes:'Appeal against negative decision, deadline March 15' },
        { id:'c5', caseId:'#WC-2026-0133', client:'Dmytro Boyko', type:'Deportation Cancel', priority:'Critical', manager:'Marek Zieliński', stage:'awaiting', filed:'2025-10-05', days:30, nationality:'Ukrainian', notes:'Deportation order cancellation - urgent case' },
        { id:'c6', caseId:'#WC-2026-0155', client:'Rajesh Kumar', type:'EU Blue Card', priority:'High', manager:'Katarzyna Wiśniewska', stage:'fingerprint_wait', filed:'2026-02-25', days:5, nationality:'Indian', notes:'IT specialist, salary meets threshold' },
        { id:'c7', caseId:'#WC-2026-0128', client:'Li Wei', type:'Work Permit', priority:'Low', manager:'Anna Kowalska', stage:'card_issued', filed:'2025-09-01', days:0, nationality:'Chinese', notes:'Card collected, case closed' },
        { id:'c8', caseId:'#WC-2026-0141', client:'Oksana Shevchenko', type:'Temporary Residence', priority:'Medium', manager:'Katarzyna Wiśniewska', stage:'fingerprint_apt', filed:'2026-01-05', days:8, nationality:'Ukrainian', notes:'Appointment scheduled for March 12' },
        { id:'c9', caseId:'#WC-2026-0149', client:'Giorgi Beridze', type:'Family Reunification', priority:'High', manager:'Piotr Nowak', stage:'submitted', filed:'2026-02-10', days:20, nationality:'Georgian', notes:'Wife and 2 children, all docs submitted' },
        { id:'c10', caseId:'#WC-2026-0153', client:'Priya Sharma', type:'EU Blue Card', priority:'Medium', manager:'Anna Kowalska', stage:'fingerprint_done', filed:'2026-02-01', days:7, nationality:'Indian', notes:'Senior developer, company sponsorship' },
        { id:'c11', caseId:'#WC-2026-0156', client:'Andrii Koval', type:'Citizenship', priority:'Low', manager:'Piotr Nowak', stage:'awaiting', filed:'2025-06-15', days:90, nationality:'Ukrainian', notes:'Long-term resident 15 years, Polish language certificate B1' },
        { id:'c12', caseId:'#WC-2026-0144', client:'Natalia Kravchuk', type:'Temporary Residence', priority:'High', manager:'Marek Zieliński', stage:'submitted', filed:'2026-02-18', days:12, nationality:'Ukrainian', notes:'Student visa conversion to TRP' },
    ];

    // ========== RENDER BOARD ==========
    function renderBoard() {
        var board = document.getElementById('kanbanBoard');
        board.innerHTML = '';

        var filterType = document.getElementById('filterType').value;
        var filterManager = document.getElementById('filterManager').value;
        var filterPriority = document.getElementById('filterPriority').value;

        var filtered = allCases.filter(function(c) {
            if (filterType !== 'all' && c.type !== filterType) return false;
            if (filterManager !== 'all' && c.manager !== filterManager) return false;
            if (filterPriority !== 'all' && c.priority !== filterPriority) return false;
            return true;
        });

        var totalCount = 0;

        STAGES.forEach(function(stage) {
            var stageCases = filtered.filter(function(c) { return c.stage === stage.id; });
            totalCount += stageCases.length;

            var col = document.createElement('div');
            col.className = 'kanban-column';
            col.innerHTML =
                '<div class="kanban-column-header" style="background:' + stage.color + ';color:#fff">' +
                    '<h6>' + stage.label + '</h6>' +
                    '<span class="column-count" style="background:rgba(255,255,255,0.25);color:#fff">' + stageCases.length + '</span>' +
                '</div>' +
                '<div class="kanban-column-body" data-stage="' + stage.id + '">' +
                    stageCases.map(function(c) { return renderCard(c); }).join('') +
                    '<div class="kanban-add-card" data-stage="' + stage.id + '"><i class="ri-add-line"></i> Add Case</div>' +
                '</div>';
            board.appendChild(col);
        });

        document.getElementById('totalCasesCount').textContent = totalCount + ' cases';

        // Init drag & drop
        initDragDrop();

        // Card clicks
        board.querySelectorAll('.kanban-card').forEach(function(card) {
            card.addEventListener('click', function(e) {
                if (e.target.closest('.card-action-btn')) return;
                showCaseModal(this.dataset.caseId);
            });
        });

        // Add card buttons
        board.querySelectorAll('.kanban-add-card').forEach(function(btn) {
            btn.addEventListener('click', function() {
                document.getElementById('addCaseKanbanModal') && new bootstrap.Modal(document.getElementById('addCaseKanbanModal')).show();
            });
        });
    }

    function renderCard(c) {
        var tc = TYPE_COLORS[c.type] || '#6c757d';
        var pc = PRIORITY_COLORS[c.priority] || '#ffc107';
        var stageIdx = STAGES.findIndex(function(s) { return s.id === c.stage; });
        var progress = Math.round(((stageIdx + 1) / STAGES.length) * 100);
        var initials = c.client.split(' ').map(function(w) { return w[0]; }).join('');

        var daysClass = 'bg-light text-muted';
        if (c.days > 30) daysClass = 'bg-danger-subtle text-danger';
        else if (c.days > 14) daysClass = 'bg-warning-subtle text-warning';

        return '<div class="kanban-card" draggable="true" data-case-id="' + c.id + '" style="border-left-color:' + tc + '">' +
            '<div class="d-flex justify-content-between align-items-start">' +
                '<span class="card-case-id">' + c.caseId + '</span>' +
                '<span class="priority-dot" style="background:' + pc + '" title="' + c.priority + '"></span>' +
            '</div>' +
            '<div class="card-client">' + c.client + '</div>' +
            '<span class="badge card-type" style="background:' + tc + ';color:#fff;font-weight:500">' + c.type + '</span>' +
            '<div class="progress progress-thin mt-2"><div class="progress-bar" style="width:' + progress + '%;background:' + tc + '"></div></div>' +
            '<div class="card-footer-row">' +
                '<div class="d-flex align-items-center gap-1">' +
                    '<div class="avatar-xs" title="' + c.manager + '">' + getInitials(c.manager) + '</div>' +
                    '<span class="card-meta">' + c.manager.split(' ')[0] + '</span>' +
                '</div>' +
                '<span class="days-badge ' + daysClass + '">' + c.days + 'd</span>' +
            '</div>' +
        '</div>';
    }

    function getInitials(name) {
        return name.split(' ').map(function(w) { return w[0]; }).join('').substring(0, 2);
    }

    // ========== DRAG & DROP ==========
    function initDragDrop() {
        var cards = document.querySelectorAll('.kanban-card');
        var columns = document.querySelectorAll('.kanban-column-body');

        cards.forEach(function(card) {
            card.addEventListener('dragstart', function(e) {
                e.dataTransfer.setData('text/plain', card.dataset.caseId);
                card.classList.add('dragging');
            });
            card.addEventListener('dragend', function() {
                card.classList.remove('dragging');
                columns.forEach(function(col) { col.classList.remove('drag-over'); });
            });
        });

        columns.forEach(function(col) {
            col.addEventListener('dragover', function(e) {
                e.preventDefault();
                col.classList.add('drag-over');
            });
            col.addEventListener('dragleave', function() {
                col.classList.remove('drag-over');
            });
            col.addEventListener('drop', function(e) {
                e.preventDefault();
                col.classList.remove('drag-over');
                var caseId = e.dataTransfer.getData('text/plain');
                var newStage = col.dataset.stage;
                var c = allCases.find(function(x) { return x.id === caseId; });
                if (c && c.stage !== newStage) {
                    var oldLabel = STAGES.find(function(s) { return s.id === c.stage; }).label;
                    var newLabel = STAGES.find(function(s) { return s.id === newStage; }).label;
                    c.stage = newStage;
                    c.days = 0;
                    renderBoard();
                    showToast(c.client + ': ' + oldLabel + ' → ' + newLabel, 'success');
                }
            });
        });
    }

    // ========== VIEW CASE MODAL ==========
    var activeCase = null;

    function showCaseModal(caseId) {
        var c = allCases.find(function(x) { return x.id === caseId; });
        if (!c) return;
        activeCase = c;

        var stage = STAGES.find(function(s) { return s.id === c.stage; });
        var tc = TYPE_COLORS[c.type] || '#6c757d';
        var pc = PRIORITY_COLORS[c.priority] || '#ffc107';
        var stageIdx = STAGES.findIndex(function(s) { return s.id === c.stage; });
        var progress = Math.round(((stageIdx + 1) / STAGES.length) * 100);

        document.getElementById('viewKanbanHeader').style.background = tc;
        document.getElementById('viewKanbanHeader').style.color = '#fff';
        document.getElementById('viewKanbanTitle').textContent = c.client + ' — ' + c.type;
        document.getElementById('vkCaseId').textContent = c.caseId;
        document.getElementById('vkClient').textContent = c.client;
        document.getElementById('vkType').innerHTML = '<span class="badge" style="background:' + tc + '">' + c.type + '</span>';
        document.getElementById('vkPriority').innerHTML = '<span class="badge" style="background:' + pc + ';color:' + (c.priority === 'Medium' ? '#000' : '#fff') + '">' + c.priority + '</span>';
        document.getElementById('vkManager').textContent = c.manager;
        document.getElementById('vkStatus').innerHTML = '<span class="badge" style="background:' + stage.color + '">' + stage.label + '</span>';
        document.getElementById('vkFiled').textContent = c.filed;
        document.getElementById('vkDays').textContent = c.days + ' days';
        document.getElementById('vkNationality').textContent = c.nationality || '—';
        document.getElementById('vkNotes').textContent = c.notes || 'No notes';

        document.getElementById('vkProgressBar').style.width = progress + '%';
        document.getElementById('vkProgressBar').style.background = tc;
        document.getElementById('vkProgressText').textContent = 'Stage ' + (stageIdx + 1) + ' of ' + STAGES.length + ' (' + progress + '%)';

        // Stage indicator dots
        var dotsHtml = '';
        STAGES.forEach(function(s, i) {
            var cls = 'stage-dot';
            if (i < stageIdx) cls += ' completed';
            else if (i === stageIdx) cls += ' current';
            dotsHtml += '<div class="' + cls + '" title="' + s.label + '"></div>';
        });
        document.getElementById('vkStageIndicator').innerHTML = dotsHtml;

        // Advance button state
        var advBtn = document.getElementById('vkAdvanceBtn');
        if (stageIdx >= STAGES.length - 1) {
            advBtn.disabled = true;
            advBtn.innerHTML = '<i class="ri-check-double-line me-1"></i>Completed';
        } else {
            advBtn.disabled = false;
            advBtn.innerHTML = '<i class="ri-arrow-right-line me-1"></i>Advance to ' + STAGES[stageIdx + 1].label;
        }

        // Open Case Page link → personal case detail
        document.getElementById('vkOpenCasesLink').href = 'crm-case-detail?case=' + c.id;

        new bootstrap.Modal(document.getElementById('viewCaseKanbanModal')).show();
    }

    // ========== ADVANCE STAGE ==========
    document.getElementById('vkAdvanceBtn').addEventListener('click', function() {
        if (!activeCase) return;
        var stageIdx = STAGES.findIndex(function(s) { return s.id === activeCase.stage; });
        if (stageIdx >= STAGES.length - 1) return;

        document.getElementById('advFromStage').textContent = STAGES[stageIdx].label;
        document.getElementById('advToStage').textContent = STAGES[stageIdx + 1].label;
        document.getElementById('advCaseName').textContent = activeCase.caseId + ' — ' + activeCase.client;

        bootstrap.Modal.getInstance(document.getElementById('viewCaseKanbanModal')).hide();
        setTimeout(function() {
            new bootstrap.Modal(document.getElementById('advanceStageModal')).show();
        }, 300);
    });

    document.getElementById('confirmAdvanceBtn').addEventListener('click', function() {
        if (!activeCase) return;
        var stageIdx = STAGES.findIndex(function(s) { return s.id === activeCase.stage; });
        if (stageIdx < STAGES.length - 1) {
            var oldLabel = STAGES[stageIdx].label;
            var newLabel = STAGES[stageIdx + 1].label;
            activeCase.stage = STAGES[stageIdx + 1].id;
            activeCase.days = 0;
            renderBoard();
            showToast(activeCase.client + ': ' + oldLabel + ' → ' + newLabel, 'success');
        }
        bootstrap.Modal.getInstance(document.getElementById('advanceStageModal')).hide();
    });

    // ========== EDIT from view ==========
    document.getElementById('vkEditBtn').addEventListener('click', function() {
        if (!activeCase) return;
        bootstrap.Modal.getInstance(document.getElementById('viewCaseKanbanModal')).hide();
        setTimeout(function() {
            document.getElementById('editKClient').value = activeCase.client;
            setSelect('editKType', activeCase.type);
            setSelect('editKPriority', activeCase.priority);
            setSelect('editKManager', activeCase.manager);
            document.getElementById('editKNationality').value = activeCase.nationality || '';
            document.getElementById('editKNotes').value = activeCase.notes || '';
            new bootstrap.Modal(document.getElementById('editCaseKanbanModal')).show();
        }, 300);
    });

    // ========== SAVE EDIT ==========
    document.getElementById('saveEditKanbanBtn').addEventListener('click', function() {
        if (!activeCase) return;
        activeCase.client = document.getElementById('editKClient').value.trim();
        activeCase.type = document.getElementById('editKType').value;
        activeCase.priority = document.getElementById('editKPriority').value;
        activeCase.manager = document.getElementById('editKManager').value;
        activeCase.nationality = document.getElementById('editKNationality').value.trim();
        activeCase.notes = document.getElementById('editKNotes').value.trim();
        renderBoard();
        bootstrap.Modal.getInstance(document.getElementById('editCaseKanbanModal')).hide();
        showToast('Case ' + activeCase.caseId + ' updated', 'success');
    });

    function setSelect(id, val) {
        var sel = document.getElementById(id);
        for (var i = 0; i < sel.options.length; i++) {
            if (sel.options[i].value === val || sel.options[i].textContent === val) { sel.selectedIndex = i; return; }
        }
    }

    // ========== ADD NEW CASE ==========
    document.getElementById('saveCaseKanbanBtn').addEventListener('click', function() {
        var client = document.getElementById('newCaseClient').value.trim();
        var type = document.getElementById('newCaseType').value;
        if (!client || !type) {
            showToast('Please fill Client and Type', 'warning');
            return;
        }
        var newCase = {
            id: 'c' + (nextCaseNum),
            caseId: '#WC-2026-0' + nextCaseNum,
            client: client,
            type: type,
            priority: document.getElementById('newCasePriority').value || 'Medium',
            manager: document.getElementById('newCaseManager').value || 'Not assigned',
            stage: 'submitted',
            filed: new Date().toISOString().split('T')[0],
            days: 0,
            nationality: document.getElementById('newCaseNationality').value || '',
            notes: document.getElementById('newCaseNotes').value.trim()
        };
        nextCaseNum++;
        allCases.push(newCase);
        renderBoard();
        bootstrap.Modal.getInstance(document.getElementById('addCaseKanbanModal')).hide();
        showToast('Case ' + newCase.caseId + ' created', 'success');

        // Reset form
        document.getElementById('newCaseClient').value = '';
        document.getElementById('newCaseType').value = '';
        document.getElementById('newCasePriority').value = 'Medium';
        document.getElementById('newCaseManager').value = '';
        document.getElementById('newCaseNationality').value = '';
        document.getElementById('newCaseNotes').value = '';
    });

    // ========== FILTERS ==========
    ['filterType', 'filterManager', 'filterPriority'].forEach(function(id) {
        document.getElementById(id).addEventListener('change', renderBoard);
    });

    // ========== TOAST ==========
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

    // ========== INITIAL RENDER ==========
    renderBoard();
});
</script>
@endsection
