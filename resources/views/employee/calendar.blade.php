@extends('partials.layouts.master-employee')
@section('title', 'Calendar — WinCase Staff')
@section('page-title', 'Calendar')

@section('css')
<style>
    .cal-grid { display: grid; grid-template-columns: repeat(7,1fr); gap: 1px; background: rgba(0,0,0,.06); border-radius: .5rem; overflow: hidden; }
    [data-bs-theme="dark"] .cal-grid { background: rgba(255,255,255,.06); }
    .cal-header { background: #015EA7; color: #fff; padding: .5rem; text-align: center; font-weight: 600; font-size: .8rem; }
    .cal-day { background: var(--bs-body-bg, #fff); min-height: 100px; padding: .35rem; font-size: .75rem; position: relative; cursor: pointer; transition: background .15s; }
    .cal-day:hover { background: rgba(1,94,167,.04); }
    .cal-day.today { background: rgba(1,94,167,.08); }
    .cal-day.other-month { opacity: .35; }
    .cal-day .day-num { font-weight: 700; font-size: .85rem; margin-bottom: .25rem; }
    .cal-day.today .day-num { color: #015EA7; }
    .cal-event { padding: 2px 5px; border-radius: 3px; margin-bottom: 2px; font-size: .65rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; cursor: pointer; }
    .cal-event.type-deadline { background: #dc354520; color: #dc3545; border-left: 2px solid #dc3545; }
    .cal-event.type-appointment { background: #0d6efd20; color: #0d6efd; border-left: 2px solid #0d6efd; }
    .cal-event.type-hearing { background: #6f42c120; color: #6f42c1; border-left: 2px solid #6f42c1; }
    .cal-event.type-task { background: #ffc10720; color: #a07800; border-left: 2px solid #ffc107; }
    .cal-event.type-reminder { background: #29e06020; color: #1a8f42; border-left: 2px solid #015EA7; }
    .timeline-item { border-left: 3px solid transparent; padding: .75rem 1rem; transition: background .15s; }
    .timeline-item:hover { background: rgba(1,94,167,.03); }
</style>
@endsection

@section('content')
<!-- Controls -->
<div class="row g-3 mb-3">
    <div class="col-lg-8">
        <div class="card mb-0">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-3">
                    <button class="btn btn-sm btn-light" id="prevMonth"><i class="ri-arrow-left-s-line"></i></button>
                    <h5 class="mb-0" id="calTitle">March 2026</h5>
                    <button class="btn btn-sm btn-light" id="nextMonth"><i class="ri-arrow-right-s-line"></i></button>
                </div>
                <div class="d-flex gap-2">
                    <button class="btn btn-sm btn-success" id="todayBtn">Today</button>
                    <div class="btn-group btn-group-sm">
                        <button class="btn btn-outline-secondary active" data-view="month">Month</button>
                        <button class="btn btn-outline-secondary" data-view="week">Week</button>
                    </div>
                </div>
            </div>
            <div class="card-body p-2">
                <div class="cal-grid" id="calGrid">
                    <div class="cal-header">Mon</div>
                    <div class="cal-header">Tue</div>
                    <div class="cal-header">Wed</div>
                    <div class="cal-header">Thu</div>
                    <div class="cal-header">Fri</div>
                    <div class="cal-header">Sat</div>
                    <div class="cal-header">Sun</div>
                    <!-- Feb tail -->
                    <div class="cal-day other-month"><span class="day-num">23</span></div>
                    <div class="cal-day other-month"><span class="day-num">24</span></div>
                    <div class="cal-day other-month"><span class="day-num">25</span></div>
                    <div class="cal-day other-month"><span class="day-num">26</span></div>
                    <div class="cal-day other-month"><span class="day-num">27</span></div>
                    <div class="cal-day other-month"><span class="day-num">28</span></div>
                    <!-- March -->
                    <div class="cal-day"><span class="day-num">1</span></div>
                    <div class="cal-day today">
                        <span class="day-num">2</span>
                        <div class="cal-event type-task">Submit bank stmt — OK</div>
                        <div class="cal-event type-appointment">Fingerprint — DB</div>
                    </div>
                    <div class="cal-day">
                        <span class="day-num">3</span>
                        <div class="cal-event type-task">Verify passport — RS</div>
                    </div>
                    <div class="cal-day">
                        <span class="day-num">4</span>
                        <div class="cal-event type-task">Follow up docs — CW</div>
                    </div>
                    <div class="cal-day">
                        <span class="day-num">5</span>
                        <div class="cal-event type-deadline">Bank stmt deadline — OK</div>
                    </div>
                    <div class="cal-day"><span class="day-num">6</span>
                        <div class="cal-event type-task">Review case — PK</div>
                    </div>
                    <div class="cal-day"><span class="day-num">7</span>
                        <div class="cal-event type-task">Prepare docs — FAH</div>
                        <div class="cal-event type-reminder">Fingerprint reminder — AP</div>
                    </div>
                    <div class="cal-day">
                        <span class="day-num">8</span>
                        <div class="cal-event type-appointment">Fingerprint appt — DB</div>
                    </div>
                    <div class="cal-day"><span class="day-num">9</span></div>
                    <div class="cal-day">
                        <span class="day-num">10</span>
                        <div class="cal-event type-hearing">Decision expected — RS</div>
                    </div>
                    <div class="cal-day"><span class="day-num">11</span></div>
                    <div class="cal-day"><span class="day-num">12</span>
                        <div class="cal-event type-hearing">Hearing — VM</div>
                    </div>
                    <div class="cal-day"><span class="day-num">13</span></div>
                    <div class="cal-day"><span class="day-num">14</span></div>
                    <div class="cal-day"><span class="day-num">15</span>
                        <div class="cal-event type-deadline">Application deadline — MY</div>
                    </div>
                    <div class="cal-day"><span class="day-num">16</span></div>
                    <div class="cal-day"><span class="day-num">17</span></div>
                    <div class="cal-day"><span class="day-num">18</span>
                        <div class="cal-event type-appointment">Office visit — CW</div>
                    </div>
                    <div class="cal-day"><span class="day-num">19</span></div>
                    <div class="cal-day"><span class="day-num">20</span></div>
                    <div class="cal-day"><span class="day-num">21</span></div>
                    <div class="cal-day"><span class="day-num">22</span></div>
                    <div class="cal-day"><span class="day-num">23</span></div>
                    <div class="cal-day"><span class="day-num">24</span></div>
                    <div class="cal-day"><span class="day-num">25</span>
                        <div class="cal-event type-deadline">Permit expiry check — IK</div>
                    </div>
                    <div class="cal-day"><span class="day-num">26</span></div>
                    <div class="cal-day"><span class="day-num">27</span></div>
                    <div class="cal-day"><span class="day-num">28</span></div>
                    <div class="cal-day"><span class="day-num">29</span></div>
                    <div class="cal-day"><span class="day-num">30</span></div>
                    <div class="cal-day"><span class="day-num">31</span></div>
                    <div class="cal-day other-month"><span class="day-num">1</span></div>
                    <div class="cal-day other-month"><span class="day-num">2</span></div>
                    <div class="cal-day other-month"><span class="day-num">3</span></div>
                    <div class="cal-day other-month"><span class="day-num">4</span></div>
                    <div class="cal-day other-month"><span class="day-num">5</span></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Sidebar: Today + Legend -->
    <div class="col-lg-4">
        <!-- Today's Schedule -->
        <div class="card mb-3">
            <div class="card-header">
                <h6 class="mb-0"><i class="ri-calendar-todo-line text-success me-1"></i><span data-lang="wc-staff-today">Today</span> — March 2</h6>
            </div>
            <div class="list-group list-group-flush">
                <div class="list-group-item timeline-item" style="border-left-color:#ffc107;">
                    <div class="d-flex justify-content-between">
                        <strong style="font-size:.85rem;">09:00</strong>
                        <span class="badge bg-warning-subtle text-warning">Task</span>
                    </div>
                    <div style="font-size:.8rem;">Submit bank statement — Olena Kovalenko</div>
                    <small class="text-muted">Case #WC-2026-0847</small>
                </div>
                <div class="list-group-item timeline-item" style="border-left-color:#0d6efd;">
                    <div class="d-flex justify-content-between">
                        <strong style="font-size:.85rem;">11:00</strong>
                        <span class="badge bg-primary-subtle text-primary">Appointment</span>
                    </div>
                    <div style="font-size:.8rem;">Fingerprint appointment — Dmytro Bondarenko</div>
                    <small class="text-muted">Case #WC-2026-0812 &bull; Urząd Wojewódzki</small>
                </div>
                <div class="list-group-item timeline-item" style="border-left-color:#ffc107;">
                    <div class="d-flex justify-content-between">
                        <strong style="font-size:.85rem;">15:00</strong>
                        <span class="badge bg-warning-subtle text-warning">Task</span>
                    </div>
                    <div style="font-size:.8rem;">Verify passport copies — Rahul Sharma</div>
                    <small class="text-muted">Case #WC-2026-0831</small>
                </div>
                <div class="list-group-item timeline-item" style="border-left-color:#ffc107;">
                    <div class="d-flex justify-content-between">
                        <strong style="font-size:.85rem;">EOD</strong>
                        <span class="badge bg-warning-subtle text-warning">Task</span>
                    </div>
                    <div style="font-size:.8rem;">Prepare application packet — Mehmet Yilmaz</div>
                    <small class="text-muted">Case #WC-2026-0855</small>
                </div>
            </div>
        </div>

        <!-- Upcoming This Week -->
        <div class="card mb-3">
            <div class="card-header">
                <h6 class="mb-0"><i class="ri-calendar-event-line text-warning me-1"></i><span data-lang="wc-staff-this-week">This Week</span></h6>
            </div>
            <div class="list-group list-group-flush">
                <div class="list-group-item d-flex justify-content-between align-items-center">
                    <div>
                        <div style="font-size:.8rem;"><strong>Mar 5</strong> — Bank statement deadline</div>
                        <small class="text-muted">Olena Kovalenko</small>
                    </div>
                    <span class="badge bg-danger rounded-pill">3d</span>
                </div>
                <div class="list-group-item d-flex justify-content-between align-items-center">
                    <div>
                        <div style="font-size:.8rem;"><strong>Mar 7</strong> — Fingerprint reminder</div>
                        <small class="text-muted">Anna Petrova</small>
                    </div>
                    <span class="badge bg-warning rounded-pill">5d</span>
                </div>
                <div class="list-group-item d-flex justify-content-between align-items-center">
                    <div>
                        <div style="font-size:.8rem;"><strong>Mar 8</strong> — Fingerprint appointment</div>
                        <small class="text-muted">Dmytro Bondarenko</small>
                    </div>
                    <span class="badge bg-info rounded-pill">6d</span>
                </div>
            </div>
        </div>

        <!-- Legend -->
        <div class="card">
            <div class="card-header"><h6 class="mb-0"><i class="ri-palette-line text-info me-1"></i><span data-lang="wc-staff-legend">Legend</span></h6></div>
            <div class="card-body py-2">
                <div class="d-flex flex-column gap-2" style="font-size:.8rem;">
                    <div class="d-flex align-items-center gap-2"><span style="width:12px;height:12px;border-radius:2px;background:#dc3545;display:inline-block;"></span> Deadline</div>
                    <div class="d-flex align-items-center gap-2"><span style="width:12px;height:12px;border-radius:2px;background:#0d6efd;display:inline-block;"></span> Appointment</div>
                    <div class="d-flex align-items-center gap-2"><span style="width:12px;height:12px;border-radius:2px;background:#6f42c1;display:inline-block;"></span> Hearing / Decision</div>
                    <div class="d-flex align-items-center gap-2"><span style="width:12px;height:12px;border-radius:2px;background:#ffc107;display:inline-block;"></span> Task</div>
                    <div class="d-flex align-items-center gap-2"><span style="width:12px;height:12px;border-radius:2px;background:#015EA7;display:inline-block;"></span> Reminder</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
