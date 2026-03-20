@extends('partials.layouts.master-employee')
@section('title', 'Tasks — WinCase Staff')
@section('page-title', 'My Tasks')

@section('css')
<style>
    .task-item { border-left: 3px solid transparent; transition: background .15s; }
    .task-item:hover { background: rgba(1,94,167,.03); }
    .task-item.priority-high { border-left-color: #dc3545; }
    .task-item.priority-medium { border-left-color: #ffc107; }
    .task-item.priority-normal { border-left-color: #0dcaf0; }
    .task-item.completed { opacity: .6; }
    .task-item.completed .task-title { text-decoration: line-through; }
</style>
@endsection

@section('content')
<!-- Stats -->
<div class="row g-3 mb-3">
    <div class="col-6 col-lg-3">
        <div class="card border-0 bg-danger bg-opacity-10">
            <div class="card-body text-center py-3">
                <h4 class="text-danger mb-0">3</h4>
                <small class="text-muted" data-lang="wc-staff-overdue">Overdue</small>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card border-0 bg-warning bg-opacity-10">
            <div class="card-body text-center py-3">
                <h4 class="text-warning mb-0">5</h4>
                <small class="text-muted" data-lang="wc-staff-due-today">Due Today</small>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card border-0 bg-primary bg-opacity-10">
            <div class="card-body text-center py-3">
                <h4 class="text-primary mb-0">8</h4>
                <small class="text-muted" data-lang="wc-staff-this-week">This Week</small>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card border-0 bg-success bg-opacity-10">
            <div class="card-body text-center py-3">
                <h4 class="text-success mb-0">42</h4>
                <small class="text-muted" data-lang="wc-staff-completed">Completed</small>
            </div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="card mb-3">
    <div class="card-body py-2">
        <div class="d-flex flex-wrap align-items-center gap-2">
            <button class="btn btn-sm btn-success active" data-tf="all" data-lang="wc-staff-all">All</button>
            <button class="btn btn-sm btn-outline-danger" data-tf="overdue" data-lang="wc-staff-overdue">Overdue</button>
            <button class="btn btn-sm btn-outline-warning" data-tf="today" data-lang="wc-staff-due-today">Today</button>
            <button class="btn btn-sm btn-outline-primary" data-tf="week" data-lang="wc-staff-this-week">This Week</button>
            <button class="btn btn-sm btn-outline-secondary" data-tf="done" data-lang="wc-staff-completed">Completed</button>
            <div class="ms-auto">
                <input type="text" class="form-control form-control-sm" placeholder="Search tasks..." style="width:200px;" id="taskSearch" data-lang-placeholder="wc-staff-search-tasks">
            </div>
        </div>
    </div>
</div>

<!-- Task List -->
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h6 class="mb-0"><i class="ri-task-line me-1 text-success"></i><span data-lang="wc-staff-task-list">Task List</span></h6>
        <span class="badge bg-secondary">16 tasks</span>
    </div>
    <div class="list-group list-group-flush" id="taskList">
        <!-- Overdue -->
        <div class="list-group-item bg-danger bg-opacity-10 py-2"><small class="fw-bold text-danger"><i class="ri-error-warning-line me-1"></i>OVERDUE</small></div>

        <label class="list-group-item task-item priority-high d-flex align-items-start gap-3">
            <input type="checkbox" class="form-check-input mt-1 flex-shrink-0">
            <div class="flex-grow-1">
                <div class="task-title fw-semibold" style="font-size:.85rem;">Upload translated birth certificate</div>
                <small class="text-muted d-block">Chen Wei &bull; Case #WC-2026-0870 &bull; <span class="text-danger">Due: Feb 28</span></small>
            </div>
            <span class="badge bg-danger">High</span>
        </label>

        <label class="list-group-item task-item priority-high d-flex align-items-start gap-3">
            <input type="checkbox" class="form-check-input mt-1 flex-shrink-0">
            <div class="flex-grow-1">
                <div class="task-title fw-semibold" style="font-size:.85rem;">Confirm appointment — Viktor Morozov</div>
                <small class="text-muted d-block">Case #WC-2026-0790 &bull; <span class="text-danger">Due: Mar 1</span></small>
            </div>
            <span class="badge bg-danger">High</span>
        </label>

        <label class="list-group-item task-item priority-medium d-flex align-items-start gap-3">
            <input type="checkbox" class="form-check-input mt-1 flex-shrink-0">
            <div class="flex-grow-1">
                <div class="task-title fw-semibold" style="font-size:.85rem;">Request missing insurance document</div>
                <small class="text-muted d-block">Maria Garcia &bull; Case #WC-2026-0878 &bull; <span class="text-danger">Due: Mar 1</span></small>
            </div>
            <span class="badge bg-warning text-dark">Medium</span>
        </label>

        <!-- Today -->
        <div class="list-group-item bg-warning bg-opacity-10 py-2"><small class="fw-bold text-warning"><i class="ri-calendar-todo-line me-1"></i>TODAY — March 2, 2026</small></div>

        <label class="list-group-item task-item priority-high d-flex align-items-start gap-3">
            <input type="checkbox" class="form-check-input mt-1 flex-shrink-0">
            <div class="flex-grow-1">
                <div class="task-title fw-semibold" style="font-size:.85rem;">Submit bank statement — Olena Kovalenko</div>
                <small class="text-muted d-block">Case #WC-2026-0847 &bull; Due: 12:00 today</small>
            </div>
            <span class="badge bg-danger">Urgent</span>
        </label>

        <label class="list-group-item task-item priority-medium d-flex align-items-start gap-3">
            <input type="checkbox" class="form-check-input mt-1 flex-shrink-0">
            <div class="flex-grow-1">
                <div class="task-title fw-semibold" style="font-size:.85rem;">Schedule fingerprint appointment — Dmytro Bondarenko</div>
                <small class="text-muted d-block">Case #WC-2026-0812 &bull; Due: 15:00 today</small>
            </div>
            <span class="badge bg-warning text-dark">Medium</span>
        </label>

        <label class="list-group-item task-item priority-normal d-flex align-items-start gap-3">
            <input type="checkbox" class="form-check-input mt-1 flex-shrink-0">
            <div class="flex-grow-1">
                <div class="task-title fw-semibold" style="font-size:.85rem;">Verify passport copies — Rahul Sharma</div>
                <small class="text-muted d-block">Case #WC-2026-0831 &bull; Due: 17:00 today</small>
            </div>
            <span class="badge bg-info">Normal</span>
        </label>

        <label class="list-group-item task-item priority-normal d-flex align-items-start gap-3 completed">
            <input type="checkbox" class="form-check-input mt-1 flex-shrink-0" checked>
            <div class="flex-grow-1">
                <div class="task-title" style="font-size:.85rem;">Upload translated certificate — Irina Kozlova</div>
                <small class="text-muted d-block">Case #WC-2026-0798 &bull; Completed at 09:15</small>
            </div>
            <span class="badge bg-success">Done</span>
        </label>

        <label class="list-group-item task-item priority-normal d-flex align-items-start gap-3">
            <input type="checkbox" class="form-check-input mt-1 flex-shrink-0">
            <div class="flex-grow-1">
                <div class="task-title fw-semibold" style="font-size:.85rem;">Prepare application packet — Mehmet Yilmaz</div>
                <small class="text-muted d-block">Case #WC-2026-0855 &bull; Due: EOD</small>
            </div>
            <span class="badge bg-info">Normal</span>
        </label>

        <!-- This Week -->
        <div class="list-group-item bg-primary bg-opacity-10 py-2"><small class="fw-bold text-primary"><i class="ri-calendar-line me-1"></i>THIS WEEK</small></div>

        <label class="list-group-item task-item priority-high d-flex align-items-start gap-3">
            <input type="checkbox" class="form-check-input mt-1 flex-shrink-0">
            <div class="flex-grow-1">
                <div class="task-title fw-semibold" style="font-size:.85rem;">Collect March bank statement — Olena Kovalenko</div>
                <small class="text-muted d-block">Case #WC-2026-0847 &bull; Due: Mar 5</small>
            </div>
            <span class="badge bg-danger">High</span>
        </label>

        <label class="list-group-item task-item priority-medium d-flex align-items-start gap-3">
            <input type="checkbox" class="form-check-input mt-1 flex-shrink-0">
            <div class="flex-grow-1">
                <div class="task-title fw-semibold" style="font-size:.85rem;">Follow up on missing documents — Chen Wei</div>
                <small class="text-muted d-block">Case #WC-2026-0870 &bull; Due: Mar 4</small>
            </div>
            <span class="badge bg-warning text-dark">Medium</span>
        </label>

        <label class="list-group-item task-item priority-normal d-flex align-items-start gap-3">
            <input type="checkbox" class="form-check-input mt-1 flex-shrink-0">
            <div class="flex-grow-1">
                <div class="task-title fw-semibold" style="font-size:.85rem;">Review case status — Pavlo Kravchenko</div>
                <small class="text-muted d-block">Case #WC-2025-0722 &bull; Due: Mar 6</small>
            </div>
            <span class="badge bg-info">Normal</span>
        </label>

        <label class="list-group-item task-item priority-normal d-flex align-items-start gap-3">
            <input type="checkbox" class="form-check-input mt-1 flex-shrink-0">
            <div class="flex-grow-1">
                <div class="task-title fw-semibold" style="font-size:.85rem;">Prepare documents for initial review — Fatima Al-Hassan</div>
                <small class="text-muted d-block">Case #WC-2026-0885 &bull; Due: Mar 7</small>
            </div>
            <span class="badge bg-info">Normal</span>
        </label>

        <label class="list-group-item task-item priority-medium d-flex align-items-start gap-3">
            <input type="checkbox" class="form-check-input mt-1 flex-shrink-0">
            <div class="flex-grow-1">
                <div class="task-title fw-semibold" style="font-size:.85rem;">Send fingerprint reminder to Anna Petrova</div>
                <small class="text-muted d-block">Case #WC-2026-0862 &bull; Due: Mar 7</small>
            </div>
            <span class="badge bg-warning text-dark">Medium</span>
        </label>
    </div>
</div>
@endsection

@section('js')
<script>
document.querySelectorAll('.task-item input[type=checkbox]').forEach(function(cb){
    cb.addEventListener('change', function(){
        this.closest('.task-item').classList.toggle('completed', this.checked);
    });
});
document.getElementById('taskSearch').addEventListener('input', function(){
    var q = this.value.toLowerCase();
    document.querySelectorAll('.task-item').forEach(function(t){
        t.style.display = t.textContent.toLowerCase().includes(q) ? '' : 'none';
    });
});
document.querySelectorAll('[data-tf]').forEach(function(btn){
    btn.addEventListener('click', function(){
        document.querySelectorAll('[data-tf]').forEach(function(b){ b.classList.remove('btn-success','btn-outline-danger','btn-outline-warning','btn-outline-primary','btn-outline-secondary','active'); b.classList.add('btn-outline-secondary'); });
        this.classList.remove('btn-outline-secondary'); this.classList.add('btn-success','active');
    });
});
</script>
@endsection
