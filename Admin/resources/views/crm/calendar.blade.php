@extends('partials.layouts.master')

@section('title', 'Calendar | WinCase CRM')
@section('sub-title', 'Calendar')
@section('sub-title-lang', 'wc-calendar')
@section('pagetitle', 'CRM')
@section('pagetitle-lang', 'wc-title-crm')

@section('css')
<style>
    #crmCalendar { min-height: 600px; }
    .fc .fc-toolbar-title { font-size: 1.2rem; font-weight: 600; }
    .fc .fc-button { font-size: 0.85rem; padding: 0.3rem 0.6rem; }
    .fc .fc-daygrid-day-number { font-size: 0.85rem; }
    .fc-event { cursor: pointer; border: none !important; padding: 2px 4px; font-size: 0.8rem; }
    .fc-event:hover { opacity: 0.85; }
    .event-type-dot { width: 10px; height: 10px; border-radius: 50%; display: inline-block; }
    .upcoming-event { transition: background-color 0.2s; cursor: pointer; }
    .upcoming-event:hover { background-color: rgba(88,101,242,0.07); }
    .fc .fc-daygrid-event { border-radius: 4px; }
    .fc .fc-timegrid-event { border-radius: 4px; }
    .stat-card { border-radius: 8px; padding: 12px 16px; }
    .stat-card .stat-value { font-size: 1.5rem; font-weight: 700; }
    .stat-card .stat-label { font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px; }
    .filter-btn { transition: all 0.2s; }
    .filter-btn:hover { transform: translateY(-1px); }
    .filter-btn.active { box-shadow: 0 2px 8px rgba(0,0,0,0.15); }
    .filter-btn.inactive { opacity: 0.45; }
    .mini-calendar-date { font-size: 2.5rem; font-weight: 700; line-height: 1; }
    .mini-calendar-month { font-size: 0.85rem; font-weight: 500; }
    .view-detail-row { padding: 8px 0; border-bottom: 1px solid #f0f0f0; }
    .view-detail-row:last-child { border-bottom: none; }
    .view-detail-label { font-weight: 600; font-size: 0.85rem; color: #6c757d; min-width: 110px; }
    .view-detail-value { font-size: 0.9rem; }
</style>
@endsection

@section('content')
<div class="row">
    <!-- LEFT SIDEBAR -->
    <div class="col-xl-3">
        <!-- Today Mini Card -->
        <div class="card border-0 bg-primary text-white">
            <div class="card-body text-center py-3">
                <div class="mini-calendar-month" id="todayMonth"></div>
                <div class="mini-calendar-date" id="todayDate"></div>
                <div class="mini-calendar-month" id="todayDay"></div>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="row g-2 mb-3">
            <div class="col-6">
                <div class="stat-card bg-soft-primary text-primary">
                    <div class="stat-value" id="statToday">0</div>
                    <div class="stat-label">Today</div>
                </div>
            </div>
            <div class="col-6">
                <div class="stat-card bg-soft-warning text-warning">
                    <div class="stat-value" id="statWeek">0</div>
                    <div class="stat-label">This Week</div>
                </div>
            </div>
            <div class="col-6">
                <div class="stat-card bg-soft-info text-info">
                    <div class="stat-value" id="statMonth">0</div>
                    <div class="stat-label">This Month</div>
                </div>
            </div>
            <div class="col-6">
                <div class="stat-card bg-soft-danger text-danger">
                    <div class="stat-value" id="statOverdue">0</div>
                    <div class="stat-label">Overdue</div>
                </div>
            </div>
        </div>

        <!-- New Event Button -->
        <button class="btn btn-primary w-100 mb-3" data-bs-toggle="modal" data-bs-target="#addEventModal">
            <i class="ri-add-line me-1"></i> New Event
        </button>

        <!-- Event Type Filters -->
        <div class="card">
            <div class="card-header py-2">
                <h6 class="card-title mb-0 fs-13"><i class="ri-filter-3-line me-1"></i>Event Types</h6>
            </div>
            <div class="card-body py-2">
                <div class="d-flex flex-column gap-1">
                    <div class="form-check form-switch">
                        <input class="form-check-input event-filter" type="checkbox" checked data-type="consultation" id="typeConsultation">
                        <label class="form-check-label fs-13" for="typeConsultation">
                            <span class="event-type-dot bg-primary me-1"></span>Consultation <span class="badge bg-primary-subtle text-primary ms-1 filter-count" data-type="consultation">0</span>
                        </label>
                    </div>
                    <div class="form-check form-switch">
                        <input class="form-check-input event-filter" type="checkbox" checked data-type="submission" id="typeSubmission">
                        <label class="form-check-label fs-13" for="typeSubmission">
                            <span class="event-type-dot bg-info me-1"></span>Submission <span class="badge bg-info-subtle text-info ms-1 filter-count" data-type="submission">0</span>
                        </label>
                    </div>
                    <div class="form-check form-switch">
                        <input class="form-check-input event-filter" type="checkbox" checked data-type="fingerprints" id="typeFingerprints">
                        <label class="form-check-label fs-13" for="typeFingerprints">
                            <span class="event-type-dot bg-warning me-1"></span>Fingerprints <span class="badge bg-warning-subtle text-warning ms-1 filter-count" data-type="fingerprints">0</span>
                        </label>
                    </div>
                    <div class="form-check form-switch">
                        <input class="form-check-input event-filter" type="checkbox" checked data-type="decision" id="typeDecision">
                        <label class="form-check-label fs-13" for="typeDecision">
                            <span class="event-type-dot bg-success me-1"></span>Decision <span class="badge bg-success-subtle text-success ms-1 filter-count" data-type="decision">0</span>
                        </label>
                    </div>
                    <div class="form-check form-switch">
                        <input class="form-check-input event-filter" type="checkbox" checked data-type="appeal" id="typeAppeal">
                        <label class="form-check-label fs-13" for="typeAppeal">
                            <span class="event-type-dot bg-danger me-1"></span>Appeal <span class="badge bg-danger-subtle text-danger ms-1 filter-count" data-type="appeal">0</span>
                        </label>
                    </div>
                    <div class="form-check form-switch">
                        <input class="form-check-input event-filter" type="checkbox" checked data-type="payment" id="typePayment">
                        <label class="form-check-label fs-13" for="typePayment">
                            <span class="event-type-dot bg-secondary me-1"></span>Payment <span class="badge bg-secondary-subtle text-secondary ms-1 filter-count" data-type="payment">0</span>
                        </label>
                    </div>
                    <div class="form-check form-switch">
                        <input class="form-check-input event-filter" type="checkbox" checked data-type="document_expiry" id="typeDocExpiry">
                        <label class="form-check-label fs-13" for="typeDocExpiry">
                            <span class="event-type-dot bg-dark me-1"></span>Doc Expiry <span class="badge bg-dark-subtle text-dark ms-1 filter-count" data-type="document_expiry">0</span>
                        </label>
                    </div>
                    <div class="form-check form-switch">
                        <input class="form-check-input event-filter" type="checkbox" checked data-type="meeting" id="typeMeeting">
                        <label class="form-check-label fs-13" for="typeMeeting">
                            <span class="event-type-dot me-1" style="background:#8b5cf6"></span>Meeting <span class="badge ms-1 filter-count" style="background:rgba(139,92,246,0.15);color:#8b5cf6" data-type="meeting">0</span>
                        </label>
                    </div>
                    <div class="form-check form-switch">
                        <input class="form-check-input event-filter" type="checkbox" checked data-type="reminder" id="typeReminder">
                        <label class="form-check-label fs-13" for="typeReminder">
                            <span class="event-type-dot me-1" style="background:#f97316"></span>Reminder <span class="badge ms-1 filter-count" style="background:rgba(249,115,22,0.15);color:#f97316" data-type="reminder">0</span>
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <!-- Upcoming Events -->
        <div class="card">
            <div class="card-header py-2 d-flex justify-content-between align-items-center">
                <h6 class="card-title mb-0 fs-13"><i class="ri-time-line me-1"></i>Upcoming</h6>
                <span class="badge bg-primary-subtle text-primary" id="upcomingCount">0</span>
            </div>
            <div class="card-body p-0" id="upcomingList" style="max-height: 300px; overflow-y: auto;">
                <!-- JS fills -->
            </div>
        </div>
    </div>

    <!-- MAIN CALENDAR -->
    <div class="col-xl-9">
        <div class="card">
            <div class="card-body">
                <div id="crmCalendar"></div>
            </div>
        </div>
    </div>
</div>

<!-- ===================== VIEW EVENT MODAL ===================== -->
<div class="modal fade" id="viewEventModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" id="viewEventHeader">
                <h5 class="modal-title"><i class="ri-calendar-event-line me-2"></i><span id="viewEventTitle"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="view-detail-row d-flex">
                    <span class="view-detail-label"><i class="ri-price-tag-3-line me-1"></i>Type</span>
                    <span class="view-detail-value" id="viewEventType"></span>
                </div>
                <div class="view-detail-row d-flex">
                    <span class="view-detail-label"><i class="ri-calendar-line me-1"></i>Start</span>
                    <span class="view-detail-value" id="viewEventStart"></span>
                </div>
                <div class="view-detail-row d-flex">
                    <span class="view-detail-label"><i class="ri-calendar-check-line me-1"></i>End</span>
                    <span class="view-detail-value" id="viewEventEnd"></span>
                </div>
                <div class="view-detail-row d-flex" id="viewClientRow">
                    <span class="view-detail-label"><i class="ri-user-line me-1"></i>Client</span>
                    <span class="view-detail-value" id="viewEventClient"></span>
                </div>
                <div class="view-detail-row d-flex" id="viewCaseRow">
                    <span class="view-detail-label"><i class="ri-briefcase-line me-1"></i>Case</span>
                    <span class="view-detail-value" id="viewEventCase"></span>
                </div>
                <div class="view-detail-row d-flex" id="viewAssigneeRow">
                    <span class="view-detail-label"><i class="ri-user-star-line me-1"></i>Assignee</span>
                    <span class="view-detail-value" id="viewEventAssignee"></span>
                </div>
                <div class="view-detail-row d-flex" id="viewLocationRow">
                    <span class="view-detail-label"><i class="ri-map-pin-line me-1"></i>Location</span>
                    <span class="view-detail-value" id="viewEventLocation"></span>
                </div>
                <div class="view-detail-row" id="viewNotesRow">
                    <div class="view-detail-label mb-1"><i class="ri-sticky-note-line me-1"></i>Notes</div>
                    <div class="view-detail-value text-muted" id="viewEventNotes"></div>
                </div>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-outline-danger btn-sm" id="viewDeleteBtn"><i class="ri-delete-bin-line me-1"></i>Delete</button>
                <div>
                    <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary btn-sm" id="viewEditBtn"><i class="ri-pencil-line me-1"></i>Edit</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ===================== ADD/EDIT EVENT MODAL ===================== -->
<div class="modal fade" id="addEventModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addEventTitle"><i class="ri-add-line me-2"></i>New Event</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="eventEditId">
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label">Title <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="eventFormTitle" placeholder="Event title">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Type <span class="text-danger">*</span></label>
                        <select class="form-select" id="eventFormType">
                            <option selected disabled value="">Select type...</option>
                            <option value="consultation">Consultation</option>
                            <option value="submission">Submission</option>
                            <option value="fingerprints">Fingerprints</option>
                            <option value="decision">Decision Signing</option>
                            <option value="appeal">Appeal Deadline</option>
                            <option value="payment">Payment Date</option>
                            <option value="document_expiry">Document Expiry</option>
                            <option value="meeting">Meeting</option>
                            <option value="reminder">Reminder</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Client</label>
                        <select class="form-select" id="eventFormClient">
                            <option selected value="">None</option>
                            <option value="Oleksandr Petrov">Oleksandr Petrov</option>
                            <option value="Maria Ivanova">Maria Ivanova</option>
                            <option value="Aliaksandr Kazlou">Aliaksandr Kazlou</option>
                            <option value="Tetiana Sydorenko">Tetiana Sydorenko</option>
                            <option value="Dmytro Boyko">Dmytro Boyko</option>
                            <option value="Rajesh Kumar">Rajesh Kumar</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Related Case</label>
                        <select class="form-select" id="eventFormCase">
                            <option selected value="">None</option>
                            <option value="#WC-2026-0147">#WC-2026-0147 — Petrov</option>
                            <option value="#WC-2026-0146">#WC-2026-0146 — Ivanova</option>
                            <option value="#WC-2026-0139">#WC-2026-0139 — Kazlou</option>
                            <option value="#WC-2026-0152">#WC-2026-0152 — Sydorenko</option>
                            <option value="#WC-2026-0133">#WC-2026-0133 — Boyko</option>
                            <option value="#WC-2026-0155">#WC-2026-0155 — Kumar</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Start Date <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" id="eventFormStartDate">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Start Time</label>
                        <input type="time" class="form-control" id="eventFormStartTime">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">End Date</label>
                        <input type="date" class="form-control" id="eventFormEndDate">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">End Time</label>
                        <input type="time" class="form-control" id="eventFormEndTime">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Assignee</label>
                        <select class="form-select" id="eventFormAssignee">
                            <option selected value="">Not assigned</option>
                            <option value="Anna Kowalska">Anna Kowalska</option>
                            <option value="Piotr Nowak">Piotr Nowak</option>
                            <option value="Katarzyna Wiśniewska">Katarzyna Wiśniewska</option>
                            <option value="Marek Zieliński">Marek Zieliński</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Location</label>
                        <input type="text" class="form-control" id="eventFormLocation" placeholder="Office, Urząd, Online...">
                    </div>
                    <div class="col-12">
                        <label class="form-label">Notes</label>
                        <textarea class="form-control" rows="2" id="eventFormNotes" placeholder="Additional notes..."></textarea>
                    </div>
                    <div class="col-12">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="eventFormAllDay">
                            <label class="form-check-label" for="eventFormAllDay">All day event</label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="saveEventBtn"><i class="ri-check-line me-1"></i>Save Event</button>
            </div>
        </div>
    </div>
</div>

<!-- ===================== DELETE EVENT MODAL ===================== -->
<div class="modal fade" id="deleteEventModal" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title"><i class="ri-delete-bin-line me-2"></i>Delete Event</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <i class="ri-error-warning-line text-danger" style="font-size:3rem"></i>
                <p class="mt-2 mb-0">Delete <strong id="deleteEventName"></strong>?</p>
                <p class="text-muted fs-12">This action cannot be undone.</p>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteBtn"><i class="ri-delete-bin-line me-1"></i>Delete</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script src="{{ asset('assets/libs/fullcalendar/index.global.min.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('crmCalendar');
    if (!calendarEl) return;

    // ========== TYPE COLORS ==========
    var TYPE_COLORS = {
        consultation:    { bg: '#5865F2', text: '#fff' },
        submission:      { bg: '#0dcaf0', text: '#fff' },
        fingerprints:    { bg: '#ffc107', text: '#000' },
        decision:        { bg: '#198754', text: '#fff' },
        appeal:          { bg: '#dc3545', text: '#fff' },
        payment:         { bg: '#6c757d', text: '#fff' },
        document_expiry: { bg: '#212529', text: '#fff' },
        meeting:         { bg: '#8b5cf6', text: '#fff' },
        reminder:        { bg: '#f97316', text: '#fff' }
    };

    var TYPE_LABELS = {
        consultation: 'Consultation', submission: 'Submission', fingerprints: 'Fingerprints',
        decision: 'Decision Signing', appeal: 'Appeal Deadline', payment: 'Payment Date',
        document_expiry: 'Document Expiry', meeting: 'Meeting', reminder: 'Reminder'
    };

    // ========== TODAY MINI CARD ==========
    var now = new Date();
    var months = ['January','February','March','April','May','June','July','August','September','October','November','December'];
    var days = ['Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'];
    document.getElementById('todayMonth').textContent = months[now.getMonth()];
    document.getElementById('todayDate').textContent = now.getDate();
    document.getElementById('todayDay').textContent = days[now.getDay()];
    var todayStr = now.toISOString().split('T')[0];

    // ========== DEMO EVENTS DATA ==========
    var nextId = 100;
    var allEvents = [
        { id: '1',  title: 'Consultation: Maria Ivanova', start: todayStr+'T10:00:00', end: todayStr+'T11:00:00', type: 'consultation', client: 'Maria Ivanova', caseRef: '#WC-2026-0146', assignee: 'Anna Kowalska', location: 'Office Room 3', notes: 'Initial consultation about temporary residence permit' },
        { id: '2',  title: 'Submission: Sydorenko appeal', start: '2026-03-03', type: 'submission', client: 'Tetiana Sydorenko', caseRef: '#WC-2026-0152', assignee: 'Piotr Nowak', notes: 'Submit appeal documents to Urząd Wojewódzki' },
        { id: '3',  title: 'Consultation: New client', start: '2026-03-04T11:00:00', end: '2026-03-04T12:00:00', type: 'consultation', assignee: 'Anna Kowalska', location: 'Office Room 1', notes: 'New client inquiry about work permit' },
        { id: '4',  title: 'Fingerprints: Petrov', start: '2026-03-05T09:00:00', end: '2026-03-05T09:30:00', type: 'fingerprints', client: 'Oleksandr Petrov', caseRef: '#WC-2026-0147', assignee: 'Katarzyna Wiśniewska', location: 'Urząd Wojewódzki, ul. Marszałkowska 3', notes: 'Bring passport original + 2 copies' },
        { id: '5',  title: 'Meeting: Team weekly', start: '2026-03-06T09:00:00', end: '2026-03-06T10:00:00', type: 'meeting', assignee: 'Anna Kowalska', location: 'Conference Room', notes: 'Weekly team sync - case updates' },
        { id: '6',  title: 'Consultation: Boyko follow-up', start: '2026-03-07T15:00:00', end: '2026-03-07T16:00:00', type: 'consultation', client: 'Dmytro Boyko', caseRef: '#WC-2026-0133', assignee: 'Marek Zieliński', location: 'Office Room 2' },
        { id: '7',  title: 'Decision Signing: Kazlou', start: '2026-03-10T14:00:00', end: '2026-03-10T15:00:00', type: 'decision', client: 'Aliaksandr Kazlou', caseRef: '#WC-2026-0139', assignee: 'Piotr Nowak', location: 'Urząd Wojewódzki', notes: 'Client must bring passport and stamp fee 50 PLN' },
        { id: '8',  title: 'Fingerprints: Kazlou', start: '2026-03-12T10:00:00', end: '2026-03-12T10:30:00', type: 'fingerprints', client: 'Aliaksandr Kazlou', caseRef: '#WC-2026-0139', assignee: 'Katarzyna Wiśniewska', location: 'Urząd Wojewódzki' },
        { id: '9',  title: 'Reminder: Kumar docs deadline', start: '2026-03-13', type: 'reminder', client: 'Rajesh Kumar', caseRef: '#WC-2026-0155', notes: 'Remind client to submit missing employment certificate' },
        { id: '10', title: 'Appeal Deadline: Boyko case', start: '2026-03-15', type: 'appeal', client: 'Dmytro Boyko', caseRef: '#WC-2026-0133', assignee: 'Marek Zieliński', notes: 'CRITICAL: Last day for appeal submission' },
        { id: '11', title: 'Payment: Ivanova invoice', start: '2026-03-15', type: 'payment', client: 'Maria Ivanova', caseRef: '#WC-2026-0146', notes: 'Invoice #INV-2026-0042 — 2,500 PLN due' },
        { id: '12', title: 'Submission: Petrov docs', start: '2026-03-18', type: 'submission', client: 'Oleksandr Petrov', caseRef: '#WC-2026-0147', assignee: 'Piotr Nowak', notes: 'Submit full package to voivode office' },
        { id: '13', title: 'Payment: Petrov', start: '2026-03-20', type: 'payment', client: 'Oleksandr Petrov', caseRef: '#WC-2026-0147', notes: 'Installment 2/3 — 1,800 PLN' },
        { id: '14', title: 'Meeting: Client review', start: '2026-03-22T14:00:00', end: '2026-03-22T15:30:00', type: 'meeting', assignee: 'Anna Kowalska', location: 'Conference Room', notes: 'Monthly portfolio review' },
        { id: '15', title: 'Document Expiry: Boyko work permit', start: '2026-04-05', type: 'document_expiry', client: 'Dmytro Boyko', caseRef: '#WC-2026-0133', notes: 'Work permit Type A expires — renewal needed' },
        { id: '16', title: 'Consultation: Kumar visa', start: '2026-03-25T10:00:00', end: '2026-03-25T11:00:00', type: 'consultation', client: 'Rajesh Kumar', caseRef: '#WC-2026-0155', assignee: 'Katarzyna Wiśniewska', location: 'Office Room 1' },
    ];

    // ========== CONVERT TO FC FORMAT ==========
    function toFcEvent(ev) {
        var c = TYPE_COLORS[ev.type] || TYPE_COLORS.consultation;
        return {
            id: ev.id,
            title: ev.title,
            start: ev.start,
            end: ev.end || null,
            backgroundColor: c.bg,
            borderColor: c.bg,
            textColor: c.text,
            extendedProps: {
                type: ev.type,
                client: ev.client || '',
                caseRef: ev.caseRef || '',
                assignee: ev.assignee || '',
                location: ev.location || '',
                notes: ev.notes || ''
            }
        };
    }

    function findEventData(id) {
        return allEvents.find(function(e) { return e.id === id; });
    }

    // ========== ACTIVE FILTERS ==========
    var activeFilters = {};
    document.querySelectorAll('.event-filter').forEach(function(cb) {
        activeFilters[cb.dataset.type] = true;
    });

    // ========== CALENDAR INIT ==========
    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        height: 'auto',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
        },
        editable: true,
        selectable: true,
        dayMaxEvents: true,
        initialDate: todayStr,
        eventSources: [{
            events: function(info, successCallback) {
                var filtered = allEvents.filter(function(ev) {
                    return activeFilters[ev.type] !== false;
                });
                successCallback(filtered.map(toFcEvent));
            }
        }],
        // Click on event → View Modal
        eventClick: function(info) {
            showViewModal(info.event);
        },
        // Click on date → Add Event with pre-filled date
        dateClick: function(info) {
            resetForm();
            document.getElementById('eventFormStartDate').value = info.dateStr.substring(0, 10);
            document.getElementById('addEventTitle').innerHTML = '<i class="ri-add-line me-2"></i>New Event';
            new bootstrap.Modal(document.getElementById('addEventModal')).show();
        },
        // Drag event → update date
        eventDrop: function(info) {
            var ev = findEventData(info.event.id);
            if (ev) {
                ev.start = info.event.startStr;
                ev.end = info.event.endStr || '';
                showToast('Event moved to ' + formatDate(info.event.start), 'info');
            }
        },
        // Resize event → update end
        eventResize: function(info) {
            var ev = findEventData(info.event.id);
            if (ev) {
                ev.end = info.event.endStr;
                showToast('Event duration updated', 'info');
            }
        }
    });
    calendar.render();

    // ========== STATS ==========
    function updateStats() {
        var todayCount = 0, weekCount = 0, monthCount = 0, overdueCount = 0;
        var todayD = new Date(todayStr);
        var weekEnd = new Date(todayD); weekEnd.setDate(weekEnd.getDate() + 7);
        var monthEnd = new Date(todayD.getFullYear(), todayD.getMonth() + 1, 0);

        // Type counts
        var typeCounts = {};
        allEvents.forEach(function(ev) {
            typeCounts[ev.type] = (typeCounts[ev.type] || 0) + 1;
            var evDate = new Date(ev.start.substring(0, 10));
            if (ev.start.substring(0, 10) === todayStr) todayCount++;
            if (evDate >= todayD && evDate <= weekEnd) weekCount++;
            if (evDate.getMonth() === todayD.getMonth() && evDate.getFullYear() === todayD.getFullYear()) monthCount++;
            if (evDate < todayD && (ev.type === 'appeal' || ev.type === 'payment' || ev.type === 'document_expiry')) overdueCount++;
        });

        document.getElementById('statToday').textContent = todayCount;
        document.getElementById('statWeek').textContent = weekCount;
        document.getElementById('statMonth').textContent = monthCount;
        document.getElementById('statOverdue').textContent = overdueCount;

        // Filter counts
        document.querySelectorAll('.filter-count').forEach(function(badge) {
            badge.textContent = typeCounts[badge.dataset.type] || 0;
        });
    }
    updateStats();

    // ========== UPCOMING LIST ==========
    function updateUpcoming() {
        var upcoming = allEvents.filter(function(ev) {
            return ev.start.substring(0, 10) >= todayStr;
        }).sort(function(a, b) {
            return a.start.localeCompare(b.start);
        }).slice(0, 8);

        document.getElementById('upcomingCount').textContent = upcoming.length;
        var html = '<ul class="list-group list-group-flush">';
        upcoming.forEach(function(ev) {
            var c = TYPE_COLORS[ev.type] || TYPE_COLORS.consultation;
            var timeStr = ev.start.includes('T') ? ev.start.substring(11, 16) : 'All day';
            var dateStr = ev.start.substring(0, 10) === todayStr ? 'Today' : formatDateShort(ev.start.substring(0, 10));
            html += '<li class="list-group-item upcoming-event px-3 py-2" data-event-id="' + ev.id + '">' +
                '<div class="d-flex gap-2">' +
                '<div class="border-start border-3 ps-2" style="border-color:' + c.bg + ' !important">' +
                '<h6 class="mb-0 fs-13">' + ev.title + '</h6>' +
                '<span class="text-muted fs-12">' + dateStr + (timeStr !== 'All day' ? ', ' + timeStr : '') + '</span>' +
                '</div></div></li>';
        });
        html += '</ul>';
        document.getElementById('upcomingList').innerHTML = html;

        // Click upcoming → view
        document.querySelectorAll('.upcoming-event').forEach(function(item) {
            item.addEventListener('click', function() {
                var evId = this.dataset.eventId;
                var fcEvent = calendar.getEventById(evId);
                if (fcEvent) showViewModal(fcEvent);
            });
        });
    }
    updateUpcoming();

    // ========== FILTERS ==========
    document.querySelectorAll('.event-filter').forEach(function(cb) {
        cb.addEventListener('change', function() {
            activeFilters[this.dataset.type] = this.checked;
            calendar.refetchEvents();
        });
    });

    // ========== VIEW MODAL ==========
    var activeEventId = null;

    function showViewModal(fcEvent) {
        activeEventId = fcEvent.id;
        var props = fcEvent.extendedProps;
        var c = TYPE_COLORS[props.type] || TYPE_COLORS.consultation;

        document.getElementById('viewEventHeader').style.background = c.bg;
        document.getElementById('viewEventHeader').style.color = c.text;
        document.getElementById('viewEventTitle').textContent = fcEvent.title;
        document.getElementById('viewEventType').innerHTML = '<span class="badge" style="background:' + c.bg + ';color:' + c.text + '">' + (TYPE_LABELS[props.type] || props.type) + '</span>';
        document.getElementById('viewEventStart').textContent = formatDateTime(fcEvent.start);
        document.getElementById('viewEventEnd').textContent = fcEvent.end ? formatDateTime(fcEvent.end) : '—';

        toggleRow('viewClientRow', props.client, 'viewEventClient');
        toggleRow('viewCaseRow', props.caseRef, 'viewEventCase');
        toggleRow('viewAssigneeRow', props.assignee, 'viewEventAssignee');
        toggleRow('viewLocationRow', props.location, 'viewEventLocation');

        if (props.notes) {
            document.getElementById('viewNotesRow').style.display = '';
            document.getElementById('viewEventNotes').textContent = props.notes;
        } else {
            document.getElementById('viewNotesRow').style.display = 'none';
        }

        new bootstrap.Modal(document.getElementById('viewEventModal')).show();
    }

    function toggleRow(rowId, value, valueId) {
        if (value) {
            document.getElementById(rowId).style.display = '';
            document.getElementById(valueId).textContent = value;
        } else {
            document.getElementById(rowId).style.display = 'none';
        }
    }

    // ========== VIEW → EDIT ==========
    document.getElementById('viewEditBtn').addEventListener('click', function() {
        bootstrap.Modal.getInstance(document.getElementById('viewEventModal')).hide();
        setTimeout(function() {
            var ev = findEventData(activeEventId);
            if (!ev) return;
            populateForm(ev);
            document.getElementById('addEventTitle').innerHTML = '<i class="ri-pencil-line me-2"></i>Edit Event';
            new bootstrap.Modal(document.getElementById('addEventModal')).show();
        }, 300);
    });

    // ========== VIEW → DELETE ==========
    document.getElementById('viewDeleteBtn').addEventListener('click', function() {
        bootstrap.Modal.getInstance(document.getElementById('viewEventModal')).hide();
        setTimeout(function() {
            var ev = findEventData(activeEventId);
            if (ev) document.getElementById('deleteEventName').textContent = ev.title;
            new bootstrap.Modal(document.getElementById('deleteEventModal')).show();
        }, 300);
    });

    // ========== CONFIRM DELETE ==========
    document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
        allEvents = allEvents.filter(function(e) { return e.id !== activeEventId; });
        var fcEvent = calendar.getEventById(activeEventId);
        if (fcEvent) fcEvent.remove();
        bootstrap.Modal.getInstance(document.getElementById('deleteEventModal')).hide();
        updateStats();
        updateUpcoming();
        showToast('Event deleted', 'danger');
    });

    // ========== SAVE EVENT (ADD/EDIT) ==========
    document.getElementById('saveEventBtn').addEventListener('click', function() {
        var title = document.getElementById('eventFormTitle').value.trim();
        var type = document.getElementById('eventFormType').value;
        var startDate = document.getElementById('eventFormStartDate').value;
        if (!title || !type || !startDate) {
            showToast('Please fill Title, Type and Start Date', 'warning');
            return;
        }

        var startTime = document.getElementById('eventFormStartTime').value;
        var endDate = document.getElementById('eventFormEndDate').value;
        var endTime = document.getElementById('eventFormEndTime').value;
        var allDay = document.getElementById('eventFormAllDay').checked;

        var startStr = startDate + (startTime && !allDay ? 'T' + startTime + ':00' : '');
        var endStr = '';
        if (endDate) endStr = endDate + (endTime && !allDay ? 'T' + endTime + ':00' : '');

        var evData = {
            title: title,
            start: startStr,
            end: endStr,
            type: type,
            client: document.getElementById('eventFormClient').value,
            caseRef: document.getElementById('eventFormCase').value,
            assignee: document.getElementById('eventFormAssignee').value,
            location: document.getElementById('eventFormLocation').value,
            notes: document.getElementById('eventFormNotes').value.trim()
        };

        var editId = document.getElementById('eventEditId').value;
        if (editId) {
            // Update existing
            var idx = allEvents.findIndex(function(e) { return e.id === editId; });
            if (idx !== -1) {
                evData.id = editId;
                allEvents[idx] = evData;
                var fcEvent = calendar.getEventById(editId);
                if (fcEvent) fcEvent.remove();
            }
            showToast('Event updated', 'success');
        } else {
            // Add new
            evData.id = String(nextId++);
            allEvents.push(evData);
            showToast('Event created', 'success');
        }

        calendar.refetchEvents();
        updateStats();
        updateUpcoming();
        bootstrap.Modal.getInstance(document.getElementById('addEventModal')).hide();
        resetForm();
    });

    // ========== FORM HELPERS ==========
    function resetForm() {
        document.getElementById('eventEditId').value = '';
        document.getElementById('eventFormTitle').value = '';
        document.getElementById('eventFormType').value = '';
        document.getElementById('eventFormStartDate').value = '';
        document.getElementById('eventFormStartTime').value = '';
        document.getElementById('eventFormEndDate').value = '';
        document.getElementById('eventFormEndTime').value = '';
        document.getElementById('eventFormClient').value = '';
        document.getElementById('eventFormCase').value = '';
        document.getElementById('eventFormAssignee').value = '';
        document.getElementById('eventFormLocation').value = '';
        document.getElementById('eventFormNotes').value = '';
        document.getElementById('eventFormAllDay').checked = false;
    }

    function populateForm(ev) {
        document.getElementById('eventEditId').value = ev.id;
        document.getElementById('eventFormTitle').value = ev.title;
        document.getElementById('eventFormType').value = ev.type || '';
        var dateTimeParts = ev.start.split('T');
        document.getElementById('eventFormStartDate').value = dateTimeParts[0] || '';
        document.getElementById('eventFormStartTime').value = dateTimeParts[1] ? dateTimeParts[1].substring(0, 5) : '';
        if (ev.end) {
            var endParts = ev.end.split('T');
            document.getElementById('eventFormEndDate').value = endParts[0] || '';
            document.getElementById('eventFormEndTime').value = endParts[1] ? endParts[1].substring(0, 5) : '';
        } else {
            document.getElementById('eventFormEndDate').value = '';
            document.getElementById('eventFormEndTime').value = '';
        }
        setSelect('eventFormClient', ev.client || '');
        setSelect('eventFormCase', ev.caseRef || '');
        setSelect('eventFormAssignee', ev.assignee || '');
        document.getElementById('eventFormLocation').value = ev.location || '';
        document.getElementById('eventFormNotes').value = ev.notes || '';
    }

    function setSelect(id, val) {
        var sel = document.getElementById(id);
        for (var i = 0; i < sel.options.length; i++) {
            if (sel.options[i].value === val) { sel.selectedIndex = i; return; }
        }
        sel.selectedIndex = 0;
    }

    // ========== FORMAT HELPERS ==========
    function formatDate(d) {
        if (!d) return '';
        if (typeof d === 'string') d = new Date(d);
        return d.toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' });
    }
    function formatDateTime(d) {
        if (!d) return '';
        if (typeof d === 'string') d = new Date(d);
        var date = d.toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' });
        var hrs = d.getHours(), mins = d.getMinutes();
        if (hrs === 0 && mins === 0) return date;
        return date + ', ' + String(hrs).padStart(2, '0') + ':' + String(mins).padStart(2, '0');
    }
    function formatDateShort(str) {
        var d = new Date(str);
        return d.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
    }

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
});
</script>
@endsection
