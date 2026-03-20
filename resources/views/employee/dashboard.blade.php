@extends('partials.layouts.master-employee')
@section('title', 'Dashboard — WinCase Staff')
@section('page-title', 'Dashboard')

@section('content')
<!-- Welcome -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card bg-success bg-opacity-10 border-0">
            <div class="card-body py-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="avatar-md rounded-circle bg-success bg-opacity-25 d-flex align-items-center justify-content-center">
                        <i class="ri-user-smile-line fs-3 text-success"></i>
                    </div>
                    <div>
                        <h5 class="mb-0"><span data-lang="wc-staff-good-morning">Good morning</span>, Anna! <span style="font-size:1.2rem;">&#128075;</span></h5>
                        <small class="text-muted">Monday, March 2, 2026 &bull; You have <strong class="text-success">5 <span data-lang="wc-staff-tasks-due-today">tasks due today</span></strong></small>
                    </div>
                    <div class="ms-auto d-none d-md-block">
                        <span class="badge bg-success rounded-pill px-3 py-2"><i class="ri-time-line me-1"></i><span data-lang="wc-staff-shift">Shift</span>: 09:00 - 18:00</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- My Stats -->
<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3">
        <div class="card">
            <div class="card-body text-center py-3">
                <div class="avatar-sm mx-auto mb-2 rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center">
                    <i class="ri-group-line text-primary fs-5"></i>
                </div>
                <h4 class="mb-0">12</h4>
                <small class="text-muted" data-lang="wc-staff-my-clients">My Clients</small>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card">
            <div class="card-body text-center py-3">
                <div class="avatar-sm mx-auto mb-2 rounded-circle bg-warning bg-opacity-10 d-flex align-items-center justify-content-center">
                    <i class="ri-folder-open-line text-warning fs-5"></i>
                </div>
                <h4 class="mb-0">8</h4>
                <small class="text-muted" data-lang="wc-staff-active-cases">Active Cases</small>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card">
            <div class="card-body text-center py-3">
                <div class="avatar-sm mx-auto mb-2 rounded-circle bg-info bg-opacity-10 d-flex align-items-center justify-content-center">
                    <i class="ri-task-line text-info fs-5"></i>
                </div>
                <h4 class="mb-0">5</h4>
                <small class="text-muted" data-lang="wc-staff-tasks-today">Tasks Today</small>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card">
            <div class="card-body text-center py-3">
                <div class="avatar-sm mx-auto mb-2 rounded-circle bg-success bg-opacity-10 d-flex align-items-center justify-content-center">
                    <i class="ri-check-double-line text-success fs-5"></i>
                </div>
                <h4 class="mb-0">28</h4>
                <small class="text-muted" data-lang="wc-staff-cases-completed">Cases Completed</small>
            </div>
        </div>
    </div>
</div>

<div class="row g-3">
    <!-- Today's Tasks -->
    <div class="col-lg-7">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="mb-0"><i class="ri-checkbox-circle-line text-success me-1"></i><span data-lang="wc-staff-todays-tasks">Today's Tasks</span></h6>
                <a href="/staff-tasks" class="btn btn-sm btn-light" data-lang="wc-staff-view-all">View All</a>
            </div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    <label class="list-group-item d-flex align-items-center gap-3">
                        <input type="checkbox" class="form-check-input mt-0 flex-shrink-0">
                        <div class="flex-grow-1">
                            <div class="fw-semibold" style="font-size:.85rem;">Submit bank statement — Olena Kovalenko</div>
                            <small class="text-muted">Case #WC-2026-0847 &bull; Due by 12:00</small>
                        </div>
                        <span class="badge bg-danger-subtle text-danger">Urgent</span>
                    </label>
                    <label class="list-group-item d-flex align-items-center gap-3">
                        <input type="checkbox" class="form-check-input mt-0 flex-shrink-0">
                        <div class="flex-grow-1">
                            <div class="fw-semibold" style="font-size:.85rem;">Schedule fingerprint — Dmytro Bondarenko</div>
                            <small class="text-muted">Case #WC-2026-0812 &bull; Due by 15:00</small>
                        </div>
                        <span class="badge bg-warning-subtle text-warning">Medium</span>
                    </label>
                    <label class="list-group-item d-flex align-items-center gap-3">
                        <input type="checkbox" class="form-check-input mt-0 flex-shrink-0">
                        <div class="flex-grow-1">
                            <div class="fw-semibold" style="font-size:.85rem;">Verify passport copies — Rahul Sharma</div>
                            <small class="text-muted">Case #WC-2026-0831 &bull; Due by 17:00</small>
                        </div>
                        <span class="badge bg-info-subtle text-info">Normal</span>
                    </label>
                    <label class="list-group-item d-flex align-items-center gap-3">
                        <input type="checkbox" class="form-check-input mt-0 flex-shrink-0" checked>
                        <div class="flex-grow-1 text-decoration-line-through text-muted">
                            <div style="font-size:.85rem;">Upload translated certificate — Irina Kozlova</div>
                            <small>Case #WC-2026-0798 &bull; Completed at 09:15</small>
                        </div>
                        <span class="badge bg-success-subtle text-success">Done</span>
                    </label>
                    <label class="list-group-item d-flex align-items-center gap-3">
                        <input type="checkbox" class="form-check-input mt-0 flex-shrink-0">
                        <div class="flex-grow-1">
                            <div class="fw-semibold" style="font-size:.85rem;">Prepare application packet — Mehmet Yilmaz</div>
                            <small class="text-muted">Case #WC-2026-0855 &bull; Due by EOD</small>
                        </div>
                        <span class="badge bg-info-subtle text-info">Normal</span>
                    </label>
                </div>
            </div>
        </div>
    </div>

    <!-- Upcoming + Messages -->
    <div class="col-lg-5">
        <!-- Upcoming Deadlines -->
        <div class="card mb-3">
            <div class="card-header">
                <h6 class="mb-0"><i class="ri-calendar-event-line text-warning me-1"></i><span data-lang="wc-staff-upcoming-deadlines">Upcoming Deadlines</span></h6>
            </div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    <div class="list-group-item">
                        <div class="d-flex justify-content-between">
                            <span style="font-size:.85rem;"><strong>Mar 5</strong> — Bank statement deadline</span>
                            <span class="badge bg-danger rounded-pill">3 days</span>
                        </div>
                        <small class="text-muted">Olena Kovalenko &bull; #WC-2026-0847</small>
                    </div>
                    <div class="list-group-item">
                        <div class="d-flex justify-content-between">
                            <span style="font-size:.85rem;"><strong>Mar 8</strong> — Fingerprint appointment</span>
                            <span class="badge bg-warning rounded-pill">6 days</span>
                        </div>
                        <small class="text-muted">Dmytro Bondarenko &bull; #WC-2026-0812</small>
                    </div>
                    <div class="list-group-item">
                        <div class="d-flex justify-content-between">
                            <span style="font-size:.85rem;"><strong>Mar 10</strong> — Decision expected</span>
                            <span class="badge bg-info rounded-pill">8 days</span>
                        </div>
                        <small class="text-muted">Rahul Sharma &bull; #WC-2026-0831</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Unread Messages -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="mb-0"><i class="ri-message-3-line text-primary me-1"></i><span data-lang="wc-staff-unread-messages">Unread Messages</span></h6>
                <span class="badge bg-primary rounded-pill">4</span>
            </div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    <a href="/staff-messages" class="list-group-item list-group-item-action">
                        <div class="d-flex align-items-center gap-2">
                            <div class="rounded-circle bg-success bg-opacity-10 d-flex align-items-center justify-content-center" style="width:32px;height:32px;font-size:.7rem;font-weight:700;color:#015EA7;">OK</div>
                            <div class="flex-grow-1 min-width-0">
                                <div class="fw-semibold" style="font-size:.8rem;">Olena Kovalenko</div>
                                <div class="text-muted text-truncate" style="font-size:.7rem;">Thank you, I will send the bank statement...</div>
                            </div>
                            <small class="text-muted">10:32</small>
                        </div>
                    </a>
                    <a href="/staff-messages" class="list-group-item list-group-item-action">
                        <div class="d-flex align-items-center gap-2">
                            <div class="rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center" style="width:32px;height:32px;font-size:.7rem;font-weight:700;color:#0d6efd;">DB</div>
                            <div class="flex-grow-1 min-width-0">
                                <div class="fw-semibold" style="font-size:.8rem;">Dmytro Bondarenko</div>
                                <div class="text-muted text-truncate" style="font-size:.7rem;">When is my fingerprint appointment?</div>
                            </div>
                            <small class="text-muted">09:15</small>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="row mt-3">
    <div class="col-12">
        <div class="card">
            <div class="card-body py-3">
                <div class="d-flex flex-wrap gap-2">
                    <a href="/staff-clients" class="btn btn-sm btn-outline-primary"><i class="ri-group-line me-1"></i><span data-lang="wc-staff-my-clients">My Clients</span></a>
                    <a href="/staff-cases" class="btn btn-sm btn-outline-warning"><i class="ri-folder-open-line me-1"></i><span data-lang="wc-staff-my-cases">My Cases</span></a>
                    <a href="/staff-calendar" class="btn btn-sm btn-outline-info"><i class="ri-calendar-line me-1"></i><span data-lang="wc-staff-calendar">Calendar</span></a>
                    <a href="/staff-documents" class="btn btn-sm btn-outline-secondary"><i class="ri-file-text-line me-1"></i><span data-lang="wc-staff-documents">Documents</span></a>
                    <a href="/staff-boss-chat" class="btn btn-sm btn-outline-success"><i class="ri-lock-line me-1"></i><span data-lang="wc-staff-boss-chat">Boss Chat</span></a>
                    <a href="/staff-knowledge" class="btn btn-sm btn-outline-dark"><i class="ri-book-open-line me-1"></i><span data-lang="wc-staff-knowledge">Knowledge Base</span></a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
const API = '/api/v1/staff';
const TOKEN = localStorage.getItem('wc_token');
const H = { 'Accept': 'application/json', 'Authorization': 'Bearer ' + TOKEN };

async function api(url) {
    try {
        const r = await fetch(url, { headers: H });
        if (!r.ok) return null;
        const j = await r.json();
        return j.data || j;
    } catch(e) { return null; }
}

(async function() {
    // Dashboard data
    const d = await api(API + '/dashboard');
    if (!d) return;

    // Welcome
    const user = d.user || {};
    const welcome = document.querySelector('[data-lang="wc-staff-good-morning"]');
    if (welcome) welcome.closest('h5').innerHTML = `<span>Good morning</span>, ${user.name || '{{ auth()->user()->name }}'}! <span style="font-size:1.2rem;">&#128075;</span>`;
    const subtitle = welcome?.closest('.card-body')?.querySelector('small');
    if (subtitle) {
        const today = new Date().toLocaleDateString('en-US', { weekday: 'long', month: 'long', day: 'numeric', year: 'numeric' });
        subtitle.innerHTML = `${today} &bull; You have <strong class="text-success">${d.stats?.tasks_today||0} tasks due today</strong>`;
    }
    const schedule = user.today_schedule;
    const shiftBadge = document.querySelector('[data-lang="wc-staff-shift"]');
    if (shiftBadge && schedule) shiftBadge.parentElement.innerHTML = `<i class="ri-time-line me-1"></i>Shift: ${schedule}`;

    // Stats cards
    const stats = d.stats || {};
    const statCards = document.querySelectorAll('.col-6.col-lg-3 h4');
    if (statCards[0]) statCards[0].textContent = stats.my_clients || 0;
    if (statCards[1]) statCards[1].textContent = stats.active_cases || 0;
    if (statCards[2]) statCards[2].textContent = stats.tasks_today || 0;
    if (statCards[3]) statCards[3].textContent = stats.cases_completed || d.stats?.total_completed || 0;

    // Today's Tasks
    const tasks = d.today_tasks || [];
    const taskList = document.querySelector('[data-lang="wc-staff-todays-tasks"]')?.closest('.card')?.querySelector('.list-group');
    if (taskList && tasks.length) {
        taskList.innerHTML = tasks.map(t => {
            const done = t.status === 'completed';
            const prio = t.priority || 'medium';
            const prioColor = {urgent:'danger',high:'danger',medium:'warning',low:'info',normal:'info'}[prio]||'secondary';
            return `<label class="list-group-item d-flex align-items-center gap-3">
                <input type="checkbox" class="form-check-input mt-0 flex-shrink-0 task-cb" data-id="${t.id}" ${done?'checked':''}>
                <div class="flex-grow-1 ${done?'text-decoration-line-through text-muted':''}">
                    <div class="${done?'':'fw-semibold'}" style="font-size:.85rem;">${t.title||''}</div>
                    <small class="text-muted">${t.case?.case_number ? 'Case '+t.case.case_number+' &bull; ' : ''}Due by ${t.due_date ? new Date(t.due_date).toLocaleTimeString('en-US',{hour:'2-digit',minute:'2-digit'}) : 'EOD'}</small>
                </div>
                <span class="badge bg-${done?'success':prioColor}-subtle text-${done?'success':prioColor}">${done?'Done':prio.charAt(0).toUpperCase()+prio.slice(1)}</span>
            </label>`;
        }).join('');
        taskList.querySelectorAll('.task-cb').forEach(cb => {
            cb.addEventListener('change', async function() {
                if (this.checked) {
                    await fetch(API + '/tasks/' + this.dataset.id + '/complete', { method:'POST', headers: H });
                    this.closest('label').querySelector('.flex-grow-1').classList.add('text-decoration-line-through','text-muted');
                }
            });
        });
    }

    // Upcoming Deadlines
    const deadlines = d.deadlines || [];
    const deadlineList = document.querySelector('[data-lang="wc-staff-upcoming-deadlines"]')?.closest('.card')?.querySelector('.list-group');
    if (deadlineList && deadlines.length) {
        deadlineList.innerHTML = deadlines.map(dl => {
            const dDate = new Date(dl.deadline);
            const days = Math.ceil((dDate - new Date()) / 86400000);
            const color = days <= 3 ? 'danger' : days <= 7 ? 'warning' : 'info';
            return `<div class="list-group-item">
                <div class="d-flex justify-content-between">
                    <span style="font-size:.85rem;"><strong>${dDate.toLocaleDateString('en-US',{month:'short',day:'numeric'})}</strong> — ${dl.service_type||'Deadline'}</span>
                    <span class="badge bg-${color} rounded-pill">${days} days</span>
                </div>
                <small class="text-muted">${dl.client?.name||''} &bull; ${dl.case_number||'#'+dl.id}</small>
            </div>`;
        }).join('');
    }

    // Unread Messages
    const inbox = d.inbox || [];
    const unread = inbox.filter(m => true);
    const msgBadge = document.querySelector('[data-lang="wc-staff-unread-messages"]')?.closest('.card-header')?.querySelector('.badge');
    if (msgBadge) msgBadge.textContent = stats.unread_total || unread.length || 0;
    const msgList = document.querySelector('[data-lang="wc-staff-unread-messages"]')?.closest('.card')?.querySelector('.list-group');
    if (msgList && unread.length) {
        msgList.innerHTML = unread.slice(0,4).map(m => {
            const initials = (m.from_name||'??').split(' ').map(w=>w[0]).join('').slice(0,2).toUpperCase();
            return `<a href="/staff-messages" class="list-group-item list-group-item-action">
                <div class="d-flex align-items-center gap-2">
                    <div class="rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center" style="width:32px;height:32px;font-size:.7rem;font-weight:700;color:#015EA7;">${initials}</div>
                    <div class="flex-grow-1 min-width-0">
                        <div class="fw-semibold" style="font-size:.8rem;">${m.from_name||''}</div>
                        <div class="text-muted text-truncate" style="font-size:.7rem;">${m.preview||''}</div>
                    </div>
                    <small class="text-muted">${m.created_at ? new Date(m.created_at).toLocaleTimeString('en-US',{hour:'2-digit',minute:'2-digit'}) : ''}</small>
                </div>
            </a>`;
        }).join('');
    }
})();
</script>
@endsection
