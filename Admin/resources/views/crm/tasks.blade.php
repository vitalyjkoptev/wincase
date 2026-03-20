@extends('partials.layouts.master')

@section('title', 'Tasks | WinCase CRM')
@section('sub-title', 'Tasks')
@section('sub-title-lang', 'wc-tasks')
@section('pagetitle', 'CRM')
@section('pagetitle-lang', 'wc-title-crm')
@section('buttonTitle', 'Add Task')
@section('buttonTitle-lang', 'wc-add-task')
@section('modalTarget', 'addTaskModal')

@section('content')
<div class="row">
    <!-- Stats Cards -->
    <div class="col-xl-3 col-md-6">
        <div class="card card-h-100">
            <div class="card-body">
                <div class="d-flex align-items-center gap-3">
                    <div class="avatar avatar-sm bg-primary-subtle text-primary rounded-2">
                        <i class="ri-task-line fs-18"></i>
                    </div>
                    <div>
                        <p class="text-muted mb-0 fs-13">Total</p>
                        <h4 class="mb-0 fw-semibold">156</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card card-h-100">
            <div class="card-body">
                <div class="d-flex align-items-center gap-3">
                    <div class="avatar avatar-sm bg-warning-subtle text-warning rounded-2">
                        <i class="ri-loader-4-line fs-18"></i>
                    </div>
                    <div>
                        <p class="text-muted mb-0 fs-13">In Progress</p>
                        <h4 class="mb-0 fw-semibold">34</h4>
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
                        <i class="ri-check-double-line fs-18"></i>
                    </div>
                    <div>
                        <p class="text-muted mb-0 fs-13">Completed</p>
                        <h4 class="mb-0 fw-semibold">110</h4>
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
                        <i class="ri-alarm-warning-line fs-18"></i>
                    </div>
                    <div>
                        <p class="text-muted mb-0 fs-13">Overdue</p>
                        <h4 class="mb-0 fw-semibold">12</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="card">
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-3">
                <input type="text" class="form-control" placeholder="Search tasks...">
            </div>
            <div class="col-md-2">
                <select class="form-select">
                    <option selected>All Statuses</option>
                    <option>Pending</option>
                    <option>In Progress</option>
                    <option>Completed</option>
                    <option>Overdue</option>
                </select>
            </div>
            <div class="col-md-2">
                <select class="form-select">
                    <option selected>Task Type</option>
                    <option>Call Client</option>
                    <option>Request Document</option>
                    <option>Submit Application</option>
                    <option>Check Status at Office</option>
                    <option>Payment Reminder</option>
                </select>
            </div>
            <div class="col-md-2">
                <select class="form-select">
                    <option selected>All Assignees</option>
                    <option>Jan Nowak</option>
                    <option>Anna Wiśniewska</option>
                    <option>Piotr Kowalczyk</option>
                </select>
            </div>
            <div class="col-md-2">
                <input type="date" class="form-control">
            </div>
            <div class="col-md-1">
                <button class="btn btn-primary w-100"><i class="ri-filter-3-line"></i></button>
            </div>
        </div>
    </div>
</div>

<!-- Tasks Table -->
<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" id="tasksTable">
                <thead class="table-light">
                    <tr>
                        <th><input class="form-check-input" type="checkbox" id="selectAllTasks"></th>
                        <th>Task</th>
                        <th>Type</th>
                        <th>Related To</th>
                        <th>Priority</th>
                        <th>Assignee</th>
                        <th>Due Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr data-task-id="1" data-title="Call client — payment reminder" data-type="Payment Reminder" data-case="WC-2026-0147" data-client="Oleksandr Petrov" data-priority="Urgent" data-assignee="Jan Nowak" data-due="2026-03-02" data-due-time="14:00" data-status="In Progress" data-description="Call Petrov regarding overdue payment for case WC-2026-0155. Second installment PLN 1,500 due.">
                        <td><input class="form-check-input row-check" type="checkbox"></td>
                        <td><a href="#" class="fw-semibold text-body task-view-link">Call client — payment reminder</a></td>
                        <td><span class="badge bg-danger-subtle text-danger fs-10">Payment Reminder</span></td>
                        <td><a href="#" class="text-muted fs-12">Case WC-2026-0147 — Petrov</a></td>
                        <td><span class="badge bg-danger-subtle text-danger">Urgent</span></td>
                        <td>Jan Nowak</td>
                        <td><span class="text-danger fw-semibold">Today</span></td>
                        <td><span class="badge bg-warning-subtle text-warning">In Progress</span></td>
                        <td>
                            <div class="dropdown">
                                <button class="btn btn-sm btn-subtle-secondary" data-bs-toggle="dropdown"><i class="ri-more-2-fill"></i></button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item action-view" href="#"><i class="ri-eye-line me-2"></i>View</a></li>
                                    <li><a class="dropdown-item action-edit" href="#"><i class="ri-edit-line me-2"></i>Edit</a></li>
                                    <li><a class="dropdown-item action-reassign" href="#"><i class="ri-user-shared-line me-2"></i>Reassign</a></li>
                                    <li><a class="dropdown-item text-success action-complete" href="#"><i class="ri-check-line me-2"></i>Complete</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item text-danger action-delete" href="#"><i class="ri-delete-bin-line me-2"></i>Delete</a></li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                    <tr data-task-id="2" data-title="Request document from Ivanova" data-type="Request Document" data-case="WC-2026-0146" data-client="Maria Ivanova" data-priority="High" data-assignee="Anna Wiśniewska" data-due="2026-03-03" data-due-time="10:00" data-status="Pending" data-description="Request marriage certificate translation from Ivanova for permanent residence application. Notarized copy needed.">
                        <td><input class="form-check-input row-check" type="checkbox"></td>
                        <td><a href="#" class="fw-semibold text-body task-view-link">Request document from Ivanova</a></td>
                        <td><span class="badge bg-info-subtle text-info fs-10">Request Document</span></td>
                        <td><a href="#" class="text-muted fs-12">Case WC-2026-0146 — Ivanova</a></td>
                        <td><span class="badge bg-danger-subtle text-danger">High</span></td>
                        <td>Anna Wiśniewska</td>
                        <td><span class="text-warning fw-semibold">Mar 3</span></td>
                        <td><span class="badge bg-primary-subtle text-primary">Pending</span></td>
                        <td>
                            <div class="dropdown">
                                <button class="btn btn-sm btn-subtle-secondary" data-bs-toggle="dropdown"><i class="ri-more-2-fill"></i></button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item action-view" href="#"><i class="ri-eye-line me-2"></i>View</a></li>
                                    <li><a class="dropdown-item action-edit" href="#"><i class="ri-edit-line me-2"></i>Edit</a></li>
                                    <li><a class="dropdown-item action-reassign" href="#"><i class="ri-user-shared-line me-2"></i>Reassign</a></li>
                                    <li><a class="dropdown-item text-success action-complete" href="#"><i class="ri-check-line me-2"></i>Complete</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item text-danger action-delete" href="#"><i class="ri-delete-bin-line me-2"></i>Delete</a></li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                    <tr data-task-id="3" data-title="Submit application for Sydorenko" data-type="Submit Application" data-case="WC-2026-0152" data-client="Tetiana Sydorenko" data-priority="Medium" data-assignee="Anna Wiśniewska" data-due="2026-03-05" data-due-time="09:00" data-status="In Progress" data-description="Submit appeal application at Gdańsk voivodeship office. All documents prepared and verified.">
                        <td><input class="form-check-input row-check" type="checkbox"></td>
                        <td><a href="#" class="fw-semibold text-body task-view-link">Submit application for Sydorenko</a></td>
                        <td><span class="badge bg-primary-subtle text-primary fs-10">Submit Application</span></td>
                        <td><a href="#" class="text-muted fs-12">Case WC-2026-0152 — Sydorenko</a></td>
                        <td><span class="badge bg-warning-subtle text-warning">Medium</span></td>
                        <td>Anna Wiśniewska</td>
                        <td class="text-muted">Mar 5</td>
                        <td><span class="badge bg-warning-subtle text-warning">In Progress</span></td>
                        <td>
                            <div class="dropdown">
                                <button class="btn btn-sm btn-subtle-secondary" data-bs-toggle="dropdown"><i class="ri-more-2-fill"></i></button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item action-view" href="#"><i class="ri-eye-line me-2"></i>View</a></li>
                                    <li><a class="dropdown-item action-edit" href="#"><i class="ri-edit-line me-2"></i>Edit</a></li>
                                    <li><a class="dropdown-item action-reassign" href="#"><i class="ri-user-shared-line me-2"></i>Reassign</a></li>
                                    <li><a class="dropdown-item text-success action-complete" href="#"><i class="ri-check-line me-2"></i>Complete</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item text-danger action-delete" href="#"><i class="ri-delete-bin-line me-2"></i>Delete</a></li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                    <tr data-task-id="4" data-title="Check status at office — Kazlou" data-type="Check Status at Office" data-case="WC-2026-0139" data-client="Aliaksandr Kazlou" data-priority="Low" data-assignee="Piotr Kowalczyk" data-due="2026-03-07" data-due-time="" data-status="Pending" data-description="Visit Wrocław voivodeship office to check case status. Fingerprints submitted Jan 20.">
                        <td><input class="form-check-input row-check" type="checkbox"></td>
                        <td><a href="#" class="fw-semibold text-body task-view-link">Check status at office — Kazlou</a></td>
                        <td><span class="badge bg-warning-subtle text-warning fs-10">Check Status at Office</span></td>
                        <td><a href="#" class="text-muted fs-12">Case WC-2026-0139 — Kazlou</a></td>
                        <td><span class="badge bg-info-subtle text-info">Low</span></td>
                        <td>Piotr Kowalczyk</td>
                        <td class="text-muted">Mar 7</td>
                        <td><span class="badge bg-primary-subtle text-primary">Pending</span></td>
                        <td>
                            <div class="dropdown">
                                <button class="btn btn-sm btn-subtle-secondary" data-bs-toggle="dropdown"><i class="ri-more-2-fill"></i></button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item action-view" href="#"><i class="ri-eye-line me-2"></i>View</a></li>
                                    <li><a class="dropdown-item action-edit" href="#"><i class="ri-edit-line me-2"></i>Edit</a></li>
                                    <li><a class="dropdown-item action-reassign" href="#"><i class="ri-user-shared-line me-2"></i>Reassign</a></li>
                                    <li><a class="dropdown-item text-success action-complete" href="#"><i class="ri-check-line me-2"></i>Complete</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item text-danger action-delete" href="#"><i class="ri-delete-bin-line me-2"></i>Delete</a></li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                    <tr data-task-id="5" data-title="Call client — Tsiklauri follow-up" data-type="Call Client" data-case="" data-client="Giorgi Tsiklauri" data-priority="High" data-assignee="Jan Nowak" data-due="2026-02-26" data-due-time="11:00" data-status="Overdue" data-description="Follow-up call to Tsiklauri regarding archived case. Client may need new services." class="table-danger bg-opacity-10">
                        <td><input class="form-check-input row-check" type="checkbox"></td>
                        <td><a href="#" class="fw-semibold text-body task-view-link">Call client — Tsiklauri follow-up</a></td>
                        <td><span class="badge bg-success-subtle text-success fs-10">Call Client</span></td>
                        <td><a href="#" class="text-muted fs-12">Giorgi Tsiklauri</a></td>
                        <td><span class="badge bg-danger-subtle text-danger">High</span></td>
                        <td>Jan Nowak</td>
                        <td><span class="text-danger fw-semibold">Feb 26 (4d overdue)</span></td>
                        <td><span class="badge bg-danger-subtle text-danger">Overdue</span></td>
                        <td>
                            <div class="dropdown">
                                <button class="btn btn-sm btn-subtle-secondary" data-bs-toggle="dropdown"><i class="ri-more-2-fill"></i></button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item action-view" href="#"><i class="ri-eye-line me-2"></i>View</a></li>
                                    <li><a class="dropdown-item action-edit" href="#"><i class="ri-edit-line me-2"></i>Edit</a></li>
                                    <li><a class="dropdown-item action-reassign" href="#"><i class="ri-user-shared-line me-2"></i>Reassign</a></li>
                                    <li><a class="dropdown-item text-success action-complete" href="#"><i class="ri-check-line me-2"></i>Complete</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item text-danger action-delete" href="#"><i class="ri-delete-bin-line me-2"></i>Delete</a></li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                    <tr data-task-id="6" data-title="Call Kumar — work permit docs" data-type="Call Client" data-case="WC-2026-0180" data-client="Rajesh Kumar" data-priority="Medium" data-assignee="Piotr Kowalczyk" data-due="2026-03-04" data-due-time="15:00" data-status="Pending" data-description="Call Kumar to collect missing work permit documents. Employer letter still pending.">
                        <td><input class="form-check-input row-check" type="checkbox"></td>
                        <td><a href="#" class="fw-semibold text-body task-view-link">Call Kumar — work permit docs</a></td>
                        <td><span class="badge bg-success-subtle text-success fs-10">Call Client</span></td>
                        <td><a href="#" class="text-muted fs-12">Case WC-2026-0180 — Kumar</a></td>
                        <td><span class="badge bg-warning-subtle text-warning">Medium</span></td>
                        <td>Piotr Kowalczyk</td>
                        <td class="text-muted">Mar 4</td>
                        <td><span class="badge bg-primary-subtle text-primary">Pending</span></td>
                        <td>
                            <div class="dropdown">
                                <button class="btn btn-sm btn-subtle-secondary" data-bs-toggle="dropdown"><i class="ri-more-2-fill"></i></button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item action-view" href="#"><i class="ri-eye-line me-2"></i>View</a></li>
                                    <li><a class="dropdown-item action-edit" href="#"><i class="ri-edit-line me-2"></i>Edit</a></li>
                                    <li><a class="dropdown-item action-reassign" href="#"><i class="ri-user-shared-line me-2"></i>Reassign</a></li>
                                    <li><a class="dropdown-item text-success action-complete" href="#"><i class="ri-check-line me-2"></i>Complete</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item text-danger action-delete" href="#"><i class="ri-delete-bin-line me-2"></i>Delete</a></li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer">
        <div class="d-flex align-items-center justify-content-between">
            <div class="text-muted fs-13">Showing 1-6 of 156 tasks</div>
            <nav>
                <ul class="pagination pagination-sm mb-0">
                    <li class="page-item disabled"><a class="page-link" href="#">Previous</a></li>
                    <li class="page-item active"><a class="page-link" href="#">1</a></li>
                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                    <li class="page-item"><a class="page-link" href="#">...</a></li>
                    <li class="page-item"><a class="page-link" href="#">26</a></li>
                    <li class="page-item"><a class="page-link" href="#">Next</a></li>
                </ul>
            </nav>
        </div>
    </div>
</div>

<!-- ============================================ -->
<!-- VIEW TASK MODAL -->
<!-- ============================================ -->
<div class="modal fade" id="viewTaskModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="ri-task-line me-2"></i>Task Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-7">
                        <h5 id="viewTaskTitle" class="mb-3"></h5>
                        <div class="table-responsive">
                            <table class="table table-borderless table-sm mb-0">
                                <tbody id="viewTaskInfoTable"></tbody>
                            </table>
                        </div>
                        <div class="card border mt-3 mb-0">
                            <div class="card-header py-2"><h6 class="card-title mb-0 fs-13"><i class="ri-file-text-line me-1"></i>Description</h6></div>
                            <div class="card-body"><p class="text-muted mb-0 fs-13" id="viewTaskDescription">No description</p></div>
                        </div>
                    </div>
                    <div class="col-lg-5">
                        <div class="d-grid gap-2">
                            <button class="btn btn-subtle-primary btn-sm text-start" id="viewToEditBtn"><i class="ri-edit-line me-2"></i>Edit Task</button>
                            <button class="btn btn-subtle-info btn-sm text-start" id="viewToReassignBtn"><i class="ri-user-shared-line me-2"></i>Reassign</button>
                            <button class="btn btn-success btn-sm text-start" id="viewToCompleteBtn"><i class="ri-check-line me-2"></i>Mark as Completed</button>
                            <button class="btn btn-subtle-danger btn-sm text-start" id="viewToDeleteBtn"><i class="ri-delete-bin-line me-2"></i>Delete Task</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ============================================ -->
<!-- EDIT TASK MODAL -->
<!-- ============================================ -->
<div class="modal fade" id="editTaskModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="ri-edit-line me-2"></i>Edit Task</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="editTaskId">
                <div class="row g-3">
                    <div class="col-md-12">
                        <label class="form-label">Title <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="editTaskTitle">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Task Type</label>
                        <select class="form-select" id="editTaskType">
                            <option>Call Client</option>
                            <option>Request Document</option>
                            <option>Submit Application</option>
                            <option>Check Status at Office</option>
                            <option>Payment Reminder</option>
                            <option>Other</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Priority</label>
                        <select class="form-select" id="editTaskPriority">
                            <option>Low</option>
                            <option>Medium</option>
                            <option>High</option>
                            <option>Urgent</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Status</label>
                        <select class="form-select" id="editTaskStatus">
                            <option>Pending</option>
                            <option>In Progress</option>
                            <option>Completed</option>
                            <option>Overdue</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Assignee</label>
                        <select class="form-select" id="editTaskAssignee">
                            <option>Jan Nowak</option>
                            <option>Anna Wiśniewska</option>
                            <option>Piotr Kowalczyk</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Due Date</label>
                        <input type="date" class="form-control" id="editTaskDue">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Due Time</label>
                        <input type="time" class="form-control" id="editTaskTime">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Related Case</label>
                        <input type="text" class="form-control" id="editTaskCase" placeholder="e.g. WC-2026-0147">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Related Client</label>
                        <input type="text" class="form-control" id="editTaskClient">
                    </div>
                    <div class="col-12">
                        <label class="form-label">Description</label>
                        <textarea class="form-control" rows="3" id="editTaskDescription"></textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="saveEditTaskBtn"><i class="ri-save-line me-1"></i>Save Changes</button>
            </div>
        </div>
    </div>
</div>

<!-- ============================================ -->
<!-- REASSIGN TASK MODAL -->
<!-- ============================================ -->
<div class="modal fade" id="reassignTaskModal" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header bg-info-subtle">
                <h5 class="modal-title text-info"><i class="ri-user-shared-line me-2"></i>Reassign Task</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="reassignTaskId">
                <p class="text-muted fs-13 mb-2">Task: <strong id="reassignTaskTitle"></strong></p>
                <p class="text-muted fs-13 mb-3">Current: <strong id="reassignCurrentAssignee"></strong></p>
                <label class="form-label">New Assignee <span class="text-danger">*</span></label>
                <select class="form-select" id="reassignNewAssignee">
                    <option>Jan Nowak</option>
                    <option>Anna Wiśniewska</option>
                    <option>Piotr Kowalczyk</option>
                </select>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-info" id="confirmReassignBtn"><i class="ri-user-shared-line me-1"></i>Reassign</button>
            </div>
        </div>
    </div>
</div>

<!-- ============================================ -->
<!-- COMPLETE TASK MODAL -->
<!-- ============================================ -->
<div class="modal fade" id="completeTaskModal" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header bg-success-subtle">
                <h5 class="modal-title text-success"><i class="ri-check-double-line me-2"></i>Complete Task</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <input type="hidden" id="completeTaskId">
                <div class="avatar avatar-md avatar-rounded bg-success-subtle text-success mx-auto mb-3">
                    <i class="ri-check-line fs-24"></i>
                </div>
                <h6>Mark as completed?</h6>
                <p class="text-muted fs-13"><strong id="completeTaskTitle"></strong></p>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-success" id="confirmCompleteBtn"><i class="ri-check-line me-1"></i>Complete</button>
            </div>
        </div>
    </div>
</div>

<!-- ============================================ -->
<!-- DELETE TASK MODAL -->
<!-- ============================================ -->
<div class="modal fade" id="deleteTaskModal" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header bg-danger-subtle">
                <h5 class="modal-title text-danger"><i class="ri-delete-bin-line me-2"></i>Delete Task</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <input type="hidden" id="deleteTaskId">
                <div class="avatar avatar-md avatar-rounded bg-danger-subtle text-danger mx-auto mb-3">
                    <i class="ri-delete-bin-line fs-24"></i>
                </div>
                <h6>Delete this task?</h6>
                <p class="text-muted fs-13"><strong id="deleteTaskTitle"></strong></p>
                <p class="text-danger fs-12">This action cannot be undone.</p>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteBtn"><i class="ri-delete-bin-line me-1"></i>Delete</button>
            </div>
        </div>
    </div>
</div>

<!-- ============================================ -->
<!-- ADD TASK MODAL -->
<!-- ============================================ -->
<div class="modal fade" id="addTaskModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="ri-add-line me-2"></i>Add New Task</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Task Type <span class="text-danger">*</span></label>
                        <select class="form-select">
                            <option selected disabled>Select type...</option>
                            <option>Call Client</option>
                            <option>Request Document</option>
                            <option>Submit Application</option>
                            <option>Check Status at Office</option>
                            <option>Payment Reminder</option>
                            <option>Other</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Title <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" placeholder="Enter task title">
                    </div>
                    <div class="col-12">
                        <label class="form-label">Description</label>
                        <textarea class="form-control" rows="3" placeholder="Task description..."></textarea>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Related Case</label>
                        <select class="form-select">
                            <option selected disabled>Select case...</option>
                            <option>WC-2026-0147 — Petrov (Temp. Residence)</option>
                            <option>WC-2026-0146 — Ivanova (Perm. Residence)</option>
                            <option>WC-2026-0139 — Kazlou (Citizenship)</option>
                            <option>WC-2026-0152 — Sydorenko (Appeal)</option>
                            <option>WC-2026-0180 — Kumar (Work Permit)</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Related Client</label>
                        <select class="form-select">
                            <option selected disabled>Select client...</option>
                            <option>Oleksandr Petrov</option>
                            <option>Maria Ivanova</option>
                            <option>Aliaksandr Kazlou</option>
                            <option>Giorgi Tsiklauri</option>
                            <option>Tetiana Sydorenko</option>
                            <option>Rajesh Kumar</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Assignee <span class="text-danger">*</span></label>
                        <select class="form-select">
                            <option selected disabled>Select assignee...</option>
                            <option>Jan Nowak</option>
                            <option>Anna Wiśniewska</option>
                            <option>Piotr Kowalczyk</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Priority <span class="text-danger">*</span></label>
                        <select class="form-select">
                            <option>Low</option>
                            <option selected>Medium</option>
                            <option>High</option>
                            <option>Urgent</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Due Date <span class="text-danger">*</span></label>
                        <input type="date" class="form-control">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary"><i class="ri-save-line me-1"></i>Save Task</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
document.addEventListener('DOMContentLoaded', function() {
    var table = document.getElementById('tasksTable');
    if (!table) return;

    function getRow(el) { return el.closest('tr[data-task-id]'); }

    function setSelectValue(sel, val) {
        for (var i = 0; i < sel.options.length; i++) {
            if (sel.options[i].value === val || sel.options[i].text === val) { sel.selectedIndex = i; return; }
        }
    }

    function showToast(msg, type) {
        var t = document.createElement('div');
        t.className = 'position-fixed top-0 end-0 p-3';
        t.style.zIndex = '9999';
        t.innerHTML = '<div class="alert alert-' + type + ' alert-dismissible fade show shadow" role="alert">' + msg + '<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>';
        document.body.appendChild(t);
        setTimeout(function() { t.remove(); }, 3000);
    }

    function formatDate(d) {
        if (!d) return '—';
        var dt = new Date(d);
        if (isNaN(dt.getTime())) return d;
        var months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
        return months[dt.getMonth()] + ' ' + dt.getDate() + ', ' + dt.getFullYear();
    }

    var TYPE_COLORS = { 'Call Client': 'success', 'Request Document': 'info', 'Submit Application': 'primary', 'Check Status at Office': 'warning', 'Payment Reminder': 'danger', 'Other': 'secondary' };
    var PRIORITY_COLORS = { 'Low': 'info', 'Medium': 'warning', 'High': 'danger', 'Urgent': 'danger' };
    var STATUS_COLORS = { 'Pending': 'primary', 'In Progress': 'warning', 'Completed': 'success', 'Overdue': 'danger' };

    // Store active task id for cross-modal
    var activeTaskId = null;

    // ===== VIEW TASK =====
    function openViewModal(row) {
        var d = row.dataset;
        activeTaskId = d.taskId;
        document.getElementById('viewTaskTitle').textContent = d.title;
        var tc = TYPE_COLORS[d.type] || 'secondary';
        var pc = PRIORITY_COLORS[d.priority] || 'secondary';
        var sc = STATUS_COLORS[d.status] || 'secondary';
        var info = '';
        info += '<tr><td class="text-muted fw-medium" style="width:35%">Type</td><td><span class="badge bg-' + tc + '-subtle text-' + tc + '">' + d.type + '</span></td></tr>';
        info += '<tr><td class="text-muted fw-medium">Priority</td><td><span class="badge bg-' + pc + '-subtle text-' + pc + '">' + d.priority + '</span></td></tr>';
        info += '<tr><td class="text-muted fw-medium">Status</td><td><span class="badge bg-' + sc + '-subtle text-' + sc + '">' + d.status + '</span></td></tr>';
        info += '<tr><td class="text-muted fw-medium">Assignee</td><td>' + d.assignee + '</td></tr>';
        info += '<tr><td class="text-muted fw-medium">Due Date</td><td>' + formatDate(d.due) + (d.dueTime ? ' at ' + d.dueTime : '') + '</td></tr>';
        if (d.case) info += '<tr><td class="text-muted fw-medium">Related Case</td><td><a href="crm-cases" class="text-primary">' + d.case + '</a></td></tr>';
        if (d.client) info += '<tr><td class="text-muted fw-medium">Related Client</td><td>' + d.client + '</td></tr>';
        document.getElementById('viewTaskInfoTable').innerHTML = info;
        document.getElementById('viewTaskDescription').textContent = d.description || 'No description';
        new bootstrap.Modal(document.getElementById('viewTaskModal')).show();
    }

    table.addEventListener('click', function(e) {
        var link = e.target.closest('.task-view-link, .action-view');
        if (!link) return;
        e.preventDefault();
        var row = getRow(link);
        if (row) openViewModal(row);
    });

    // View → Edit
    document.getElementById('viewToEditBtn').addEventListener('click', function() {
        bootstrap.Modal.getInstance(document.getElementById('viewTaskModal')).hide();
        setTimeout(function() {
            var row = table.querySelector('tr[data-task-id="' + activeTaskId + '"]');
            if (row) openEditModal(row);
        }, 300);
    });
    // View → Reassign
    document.getElementById('viewToReassignBtn').addEventListener('click', function() {
        bootstrap.Modal.getInstance(document.getElementById('viewTaskModal')).hide();
        setTimeout(function() {
            var row = table.querySelector('tr[data-task-id="' + activeTaskId + '"]');
            if (row) openReassignModal(row);
        }, 300);
    });
    // View → Complete
    document.getElementById('viewToCompleteBtn').addEventListener('click', function() {
        bootstrap.Modal.getInstance(document.getElementById('viewTaskModal')).hide();
        setTimeout(function() {
            var row = table.querySelector('tr[data-task-id="' + activeTaskId + '"]');
            if (row) openCompleteModal(row);
        }, 300);
    });
    // View → Delete
    document.getElementById('viewToDeleteBtn').addEventListener('click', function() {
        bootstrap.Modal.getInstance(document.getElementById('viewTaskModal')).hide();
        setTimeout(function() {
            var row = table.querySelector('tr[data-task-id="' + activeTaskId + '"]');
            if (row) openDeleteModal(row);
        }, 300);
    });

    // ===== EDIT TASK =====
    function openEditModal(row) {
        var d = row.dataset;
        document.getElementById('editTaskId').value = d.taskId;
        document.getElementById('editTaskTitle').value = d.title;
        setSelectValue(document.getElementById('editTaskType'), d.type);
        setSelectValue(document.getElementById('editTaskPriority'), d.priority);
        setSelectValue(document.getElementById('editTaskStatus'), d.status);
        setSelectValue(document.getElementById('editTaskAssignee'), d.assignee);
        document.getElementById('editTaskDue').value = d.due || '';
        document.getElementById('editTaskTime').value = d.dueTime || '';
        document.getElementById('editTaskCase').value = d.case || '';
        document.getElementById('editTaskClient').value = d.client || '';
        document.getElementById('editTaskDescription').value = d.description || '';
        new bootstrap.Modal(document.getElementById('editTaskModal')).show();
    }

    table.addEventListener('click', function(e) {
        var link = e.target.closest('.action-edit');
        if (!link) return;
        e.preventDefault();
        var row = getRow(link);
        if (row) openEditModal(row);
    });

    document.getElementById('saveEditTaskBtn').addEventListener('click', function() {
        var id = document.getElementById('editTaskId').value;
        var row = table.querySelector('tr[data-task-id="' + id + '"]');
        if (!row) return;

        var title = document.getElementById('editTaskTitle').value.trim();
        var type = document.getElementById('editTaskType').value;
        var priority = document.getElementById('editTaskPriority').value;
        var status = document.getElementById('editTaskStatus').value;
        var assignee = document.getElementById('editTaskAssignee').value;
        var due = document.getElementById('editTaskDue').value;
        var dueTime = document.getElementById('editTaskTime').value;
        var caseNum = document.getElementById('editTaskCase').value;
        var client = document.getElementById('editTaskClient').value;
        var desc = document.getElementById('editTaskDescription').value;

        row.dataset.title = title;
        row.dataset.type = type;
        row.dataset.priority = priority;
        row.dataset.status = status;
        row.dataset.assignee = assignee;
        row.dataset.due = due;
        row.dataset.dueTime = dueTime;
        row.dataset.case = caseNum;
        row.dataset.client = client;
        row.dataset.description = desc;

        var cells = row.querySelectorAll('td');
        var tc = TYPE_COLORS[type] || 'secondary';
        var pc = PRIORITY_COLORS[priority] || 'secondary';
        var sc = STATUS_COLORS[status] || 'secondary';

        cells[1].innerHTML = '<a href="#" class="fw-semibold text-body task-view-link">' + title + '</a>';
        cells[2].innerHTML = '<span class="badge bg-' + tc + '-subtle text-' + tc + ' fs-10">' + type + '</span>';
        cells[3].innerHTML = '<a href="#" class="text-muted fs-12">' + (caseNum ? 'Case ' + caseNum + ' — ' : '') + client + '</a>';
        cells[4].innerHTML = '<span class="badge bg-' + pc + '-subtle text-' + pc + '">' + priority + '</span>';
        cells[5].textContent = assignee;
        cells[7].innerHTML = '<span class="badge bg-' + sc + '-subtle text-' + sc + '">' + status + '</span>';

        // Overdue row highlight
        row.className = status === 'Overdue' ? 'table-danger bg-opacity-10' : '';
        // Completed row
        if (status === 'Completed') row.style.opacity = '0.6';
        else row.style.opacity = '';

        bootstrap.Modal.getInstance(document.getElementById('editTaskModal')).hide();
        showToast('<i class="ri-check-line me-1"></i> Task updated successfully', 'success');
    });

    // ===== REASSIGN =====
    function openReassignModal(row) {
        var d = row.dataset;
        document.getElementById('reassignTaskId').value = d.taskId;
        document.getElementById('reassignTaskTitle').textContent = d.title;
        document.getElementById('reassignCurrentAssignee').textContent = d.assignee;
        setSelectValue(document.getElementById('reassignNewAssignee'), d.assignee);
        new bootstrap.Modal(document.getElementById('reassignTaskModal')).show();
    }

    table.addEventListener('click', function(e) {
        var link = e.target.closest('.action-reassign');
        if (!link) return;
        e.preventDefault();
        var row = getRow(link);
        if (row) openReassignModal(row);
    });

    document.getElementById('confirmReassignBtn').addEventListener('click', function() {
        var id = document.getElementById('reassignTaskId').value;
        var row = table.querySelector('tr[data-task-id="' + id + '"]');
        var newAssignee = document.getElementById('reassignNewAssignee').value;
        if (row) {
            row.dataset.assignee = newAssignee;
            row.querySelectorAll('td')[5].textContent = newAssignee;
        }
        bootstrap.Modal.getInstance(document.getElementById('reassignTaskModal')).hide();
        showToast('<i class="ri-user-shared-line me-1"></i> Task reassigned to ' + newAssignee, 'info');
    });

    // ===== COMPLETE =====
    function openCompleteModal(row) {
        var d = row.dataset;
        document.getElementById('completeTaskId').value = d.taskId;
        document.getElementById('completeTaskTitle').textContent = d.title;
        new bootstrap.Modal(document.getElementById('completeTaskModal')).show();
    }

    table.addEventListener('click', function(e) {
        var link = e.target.closest('.action-complete');
        if (!link) return;
        e.preventDefault();
        var row = getRow(link);
        if (row) openCompleteModal(row);
    });

    document.getElementById('confirmCompleteBtn').addEventListener('click', function() {
        var id = document.getElementById('completeTaskId').value;
        var row = table.querySelector('tr[data-task-id="' + id + '"]');
        if (row) {
            row.dataset.status = 'Completed';
            var cells = row.querySelectorAll('td');
            cells[7].innerHTML = '<span class="badge bg-success-subtle text-success">Completed</span>';
            row.className = '';
            row.style.opacity = '0.6';
            // Change Complete to Reopen
            var completeLink = row.querySelector('.action-complete');
            if (completeLink) {
                completeLink.className = 'dropdown-item text-warning action-reopen';
                completeLink.innerHTML = '<i class="ri-restart-line me-2"></i>Reopen';
            }
        }
        bootstrap.Modal.getInstance(document.getElementById('completeTaskModal')).hide();
        showToast('<i class="ri-check-double-line me-1"></i> Task completed', 'success');
    });

    // ===== REOPEN =====
    table.addEventListener('click', function(e) {
        var link = e.target.closest('.action-reopen');
        if (!link) return;
        e.preventDefault();
        var row = getRow(link);
        if (row) {
            row.dataset.status = 'In Progress';
            var cells = row.querySelectorAll('td');
            cells[7].innerHTML = '<span class="badge bg-warning-subtle text-warning">In Progress</span>';
            row.style.opacity = '';
            link.className = 'dropdown-item text-success action-complete';
            link.innerHTML = '<i class="ri-check-line me-2"></i>Complete';
            showToast('<i class="ri-restart-line me-1"></i> Task reopened', 'warning');
        }
    });

    // ===== DELETE =====
    function openDeleteModal(row) {
        var d = row.dataset;
        document.getElementById('deleteTaskId').value = d.taskId;
        document.getElementById('deleteTaskTitle').textContent = d.title;
        new bootstrap.Modal(document.getElementById('deleteTaskModal')).show();
    }

    table.addEventListener('click', function(e) {
        var link = e.target.closest('.action-delete');
        if (!link) return;
        e.preventDefault();
        var row = getRow(link);
        if (row) openDeleteModal(row);
    });

    document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
        var id = document.getElementById('deleteTaskId').value;
        var row = table.querySelector('tr[data-task-id="' + id + '"]');
        if (row) {
            row.style.transition = 'opacity 0.3s';
            row.style.opacity = '0';
            setTimeout(function() { row.remove(); }, 300);
        }
        bootstrap.Modal.getInstance(document.getElementById('deleteTaskModal')).hide();
        showToast('<i class="ri-delete-bin-line me-1"></i> Task deleted', 'danger');
    });

    // ===== SELECT ALL =====
    document.getElementById('selectAllTasks').addEventListener('change', function() {
        var checks = table.querySelectorAll('.row-check');
        for (var i = 0; i < checks.length; i++) { checks[i].checked = this.checked; }
    });
});
</script>
@endsection
