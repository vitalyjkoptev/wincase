@extends('partials.layouts.master')

@section('title', 'Roles & Permissions | WinCase CRM')
@section('sub-title', 'Roles & Permissions')
@section('sub-title-lang', 'wc-roles')
@section('pagetitle', 'Admin')
@section('pagetitle-lang', 'wc-admin')
@section('buttonTitle', 'Add Role')
@section('buttonTitle-lang', 'wc-add-role')
@section('modalTarget', 'addRoleModal')

@section('content')

<!-- Role Cards -->
<div class="row">
    <div class="col-xl-3 col-md-6">
        <div class="card border-danger border-2 border-top card-h-100">
            <div class="card-body text-center p-4">
                <div class="avatar avatar-md avatar-rounded bg-danger-subtle text-danger mx-auto mb-3">
                    <i class="ri-shield-star-line fs-24"></i>
                </div>
                <h5 class="fw-semibold mb-1">Admin</h5>
                <p class="text-muted fs-13 mb-3">Full system access</p>
                <div class="d-flex justify-content-center gap-2 mb-3">
                    <span class="badge bg-danger-subtle text-danger">All Modules</span>
                </div>
                <div class="d-flex align-items-center justify-content-center gap-2 mb-3">
                    <div class="avatar-group">
                        <div class="avatar avatar-xxs avatar-rounded bg-primary text-white"><span>JN</span></div>
                        <div class="avatar avatar-xxs avatar-rounded bg-success text-white"><span>MK</span></div>
                    </div>
                    <span class="text-muted fs-12">2 users</span>
                </div>
                <a href="#" class="btn btn-sm btn-subtle-danger w-100" data-bs-toggle="modal" data-bs-target="#editPermissionsModal" data-role="admin">
                    <i class="ri-settings-3-line me-1"></i>Edit Permissions
                </a>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card border-primary border-2 border-top card-h-100">
            <div class="card-body text-center p-4">
                <div class="avatar avatar-md avatar-rounded bg-primary-subtle text-primary mx-auto mb-3">
                    <i class="ri-user-settings-line fs-24"></i>
                </div>
                <h5 class="fw-semibold mb-1">Manager</h5>
                <p class="text-muted fs-13 mb-3">CRM, Cases, Clients, Tasks</p>
                <div class="d-flex justify-content-center gap-1 flex-wrap mb-3">
                    <span class="badge bg-primary-subtle text-primary">CRM</span>
                    <span class="badge bg-primary-subtle text-primary">Cases</span>
                    <span class="badge bg-primary-subtle text-primary">Clients</span>
                    <span class="badge bg-primary-subtle text-primary">Tasks</span>
                </div>
                <div class="d-flex align-items-center justify-content-center gap-2 mb-3">
                    <div class="avatar-group">
                        <div class="avatar avatar-xxs avatar-rounded bg-info text-white"><span>AW</span></div>
                        <div class="avatar avatar-xxs avatar-rounded bg-warning text-white"><span>KZ</span></div>
                        <div class="avatar avatar-xxs avatar-rounded bg-danger text-white"><span>TM</span></div>
                    </div>
                    <span class="text-muted fs-12">3 users</span>
                </div>
                <a href="#" class="btn btn-sm btn-subtle-primary w-100" data-bs-toggle="modal" data-bs-target="#editPermissionsModal" data-role="manager">
                    <i class="ri-settings-3-line me-1"></i>Edit Permissions
                </a>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card border-success border-2 border-top card-h-100">
            <div class="card-body text-center p-4">
                <div class="avatar avatar-md avatar-rounded bg-success-subtle text-success mx-auto mb-3">
                    <i class="ri-money-euro-circle-line fs-24"></i>
                </div>
                <h5 class="fw-semibold mb-1">Accountant</h5>
                <p class="text-muted fs-13 mb-3">Finance, Invoices, Payments, POS</p>
                <div class="d-flex justify-content-center gap-1 flex-wrap mb-3">
                    <span class="badge bg-success-subtle text-success">Finance</span>
                    <span class="badge bg-success-subtle text-success">Invoices</span>
                    <span class="badge bg-success-subtle text-success">Payments</span>
                    <span class="badge bg-success-subtle text-success">POS</span>
                </div>
                <div class="d-flex align-items-center justify-content-center gap-2 mb-3">
                    <div class="avatar-group">
                        <div class="avatar avatar-xxs avatar-rounded bg-success text-white"><span>PK</span></div>
                    </div>
                    <span class="text-muted fs-12">1 user</span>
                </div>
                <a href="#" class="btn btn-sm btn-subtle-success w-100" data-bs-toggle="modal" data-bs-target="#editPermissionsModal" data-role="accountant">
                    <i class="ri-settings-3-line me-1"></i>Edit Permissions
                </a>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card border-secondary border-2 border-top card-h-100">
            <div class="card-body text-center p-4">
                <div class="avatar avatar-md avatar-rounded bg-secondary-subtle text-secondary mx-auto mb-3">
                    <i class="ri-eye-line fs-24"></i>
                </div>
                <h5 class="fw-semibold mb-1">Viewer</h5>
                <p class="text-muted fs-13 mb-3">Read-only access</p>
                <div class="d-flex justify-content-center gap-2 mb-3">
                    <span class="badge bg-secondary-subtle text-secondary">Read Only</span>
                </div>
                <div class="d-flex align-items-center justify-content-center gap-2 mb-3">
                    <span class="text-muted fs-12">0 users</span>
                </div>
                <a href="#" class="btn btn-sm btn-subtle-secondary w-100" data-bs-toggle="modal" data-bs-target="#editPermissionsModal" data-role="viewer">
                    <i class="ri-settings-3-line me-1"></i>Edit Permissions
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Permissions Matrix -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h5 class="card-title mb-0">Permissions Matrix</h5>
                <div class="d-flex gap-2">
                    <span class="badge bg-success-subtle text-success fs-12"><i class="ri-check-line me-1"></i>Full Access</span>
                    <span class="badge bg-danger-subtle text-danger fs-12"><i class="ri-close-line me-1"></i>No Access</span>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th style="min-width: 180px;">Module</th>
                                <th class="text-center">Admin</th>
                                <th class="text-center">Manager</th>
                                <th class="text-center">Accountant</th>
                                <th class="text-center">Viewer</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><i class="ri-dashboard-line me-2 text-muted"></i>Dashboard</td>
                                <td class="text-center"><span class="text-success fs-18"><i class="ri-checkbox-circle-fill"></i></span></td>
                                <td class="text-center"><span class="text-success fs-18"><i class="ri-checkbox-circle-fill"></i></span></td>
                                <td class="text-center"><span class="text-success fs-18"><i class="ri-checkbox-circle-fill"></i></span></td>
                                <td class="text-center"><span class="text-success fs-18"><i class="ri-checkbox-circle-fill"></i></span></td>
                            </tr>
                            <tr>
                                <td><i class="ri-user-follow-line me-2 text-muted"></i>Leads</td>
                                <td class="text-center"><span class="text-success fs-18"><i class="ri-checkbox-circle-fill"></i></span></td>
                                <td class="text-center"><span class="text-success fs-18"><i class="ri-checkbox-circle-fill"></i></span></td>
                                <td class="text-center"><span class="text-danger fs-18"><i class="ri-close-circle-fill"></i></span></td>
                                <td class="text-center"><span class="text-success fs-18"><i class="ri-checkbox-circle-fill"></i></span></td>
                            </tr>
                            <tr>
                                <td><i class="ri-group-line me-2 text-muted"></i>Clients</td>
                                <td class="text-center"><span class="text-success fs-18"><i class="ri-checkbox-circle-fill"></i></span></td>
                                <td class="text-center"><span class="text-success fs-18"><i class="ri-checkbox-circle-fill"></i></span></td>
                                <td class="text-center"><span class="text-danger fs-18"><i class="ri-close-circle-fill"></i></span></td>
                                <td class="text-center"><span class="text-success fs-18"><i class="ri-checkbox-circle-fill"></i></span></td>
                            </tr>
                            <tr>
                                <td><i class="ri-briefcase-line me-2 text-muted"></i>Cases</td>
                                <td class="text-center"><span class="text-success fs-18"><i class="ri-checkbox-circle-fill"></i></span></td>
                                <td class="text-center"><span class="text-success fs-18"><i class="ri-checkbox-circle-fill"></i></span></td>
                                <td class="text-center"><span class="text-danger fs-18"><i class="ri-close-circle-fill"></i></span></td>
                                <td class="text-center"><span class="text-success fs-18"><i class="ri-checkbox-circle-fill"></i></span></td>
                            </tr>
                            <tr>
                                <td><i class="ri-task-line me-2 text-muted"></i>Tasks</td>
                                <td class="text-center"><span class="text-success fs-18"><i class="ri-checkbox-circle-fill"></i></span></td>
                                <td class="text-center"><span class="text-success fs-18"><i class="ri-checkbox-circle-fill"></i></span></td>
                                <td class="text-center"><span class="text-danger fs-18"><i class="ri-close-circle-fill"></i></span></td>
                                <td class="text-center"><span class="text-danger fs-18"><i class="ri-close-circle-fill"></i></span></td>
                            </tr>
                            <tr>
                                <td><i class="ri-file-copy-line me-2 text-muted"></i>Documents</td>
                                <td class="text-center"><span class="text-success fs-18"><i class="ri-checkbox-circle-fill"></i></span></td>
                                <td class="text-center"><span class="text-success fs-18"><i class="ri-checkbox-circle-fill"></i></span></td>
                                <td class="text-center"><span class="text-danger fs-18"><i class="ri-close-circle-fill"></i></span></td>
                                <td class="text-center"><span class="text-success fs-18"><i class="ri-checkbox-circle-fill"></i></span></td>
                            </tr>
                            <tr>
                                <td><i class="ri-calendar-line me-2 text-muted"></i>Calendar</td>
                                <td class="text-center"><span class="text-success fs-18"><i class="ri-checkbox-circle-fill"></i></span></td>
                                <td class="text-center"><span class="text-success fs-18"><i class="ri-checkbox-circle-fill"></i></span></td>
                                <td class="text-center"><span class="text-danger fs-18"><i class="ri-close-circle-fill"></i></span></td>
                                <td class="text-center"><span class="text-danger fs-18"><i class="ri-close-circle-fill"></i></span></td>
                            </tr>
                            <tr>
                                <td><i class="ri-file-list-3-line me-2 text-muted"></i>Invoices</td>
                                <td class="text-center"><span class="text-success fs-18"><i class="ri-checkbox-circle-fill"></i></span></td>
                                <td class="text-center"><span class="text-danger fs-18"><i class="ri-close-circle-fill"></i></span></td>
                                <td class="text-center"><span class="text-success fs-18"><i class="ri-checkbox-circle-fill"></i></span></td>
                                <td class="text-center"><span class="text-success fs-18"><i class="ri-checkbox-circle-fill"></i></span></td>
                            </tr>
                            <tr>
                                <td><i class="ri-bank-card-line me-2 text-muted"></i>Payments</td>
                                <td class="text-center"><span class="text-success fs-18"><i class="ri-checkbox-circle-fill"></i></span></td>
                                <td class="text-center"><span class="text-danger fs-18"><i class="ri-close-circle-fill"></i></span></td>
                                <td class="text-center"><span class="text-success fs-18"><i class="ri-checkbox-circle-fill"></i></span></td>
                                <td class="text-center"><span class="text-danger fs-18"><i class="ri-close-circle-fill"></i></span></td>
                            </tr>
                            <tr>
                                <td><i class="ri-terminal-box-line me-2 text-muted"></i>POS</td>
                                <td class="text-center"><span class="text-success fs-18"><i class="ri-checkbox-circle-fill"></i></span></td>
                                <td class="text-center"><span class="text-danger fs-18"><i class="ri-close-circle-fill"></i></span></td>
                                <td class="text-center"><span class="text-success fs-18"><i class="ri-checkbox-circle-fill"></i></span></td>
                                <td class="text-center"><span class="text-danger fs-18"><i class="ri-close-circle-fill"></i></span></td>
                            </tr>
                            <tr>
                                <td><i class="ri-wallet-3-line me-2 text-muted"></i>Expenses</td>
                                <td class="text-center"><span class="text-success fs-18"><i class="ri-checkbox-circle-fill"></i></span></td>
                                <td class="text-center"><span class="text-danger fs-18"><i class="ri-close-circle-fill"></i></span></td>
                                <td class="text-center"><span class="text-success fs-18"><i class="ri-checkbox-circle-fill"></i></span></td>
                                <td class="text-center"><span class="text-danger fs-18"><i class="ri-close-circle-fill"></i></span></td>
                            </tr>
                            <tr>
                                <td><i class="ri-megaphone-line me-2 text-muted"></i>Marketing</td>
                                <td class="text-center"><span class="text-success fs-18"><i class="ri-checkbox-circle-fill"></i></span></td>
                                <td class="text-center"><span class="text-success fs-18"><i class="ri-checkbox-circle-fill"></i></span></td>
                                <td class="text-center"><span class="text-danger fs-18"><i class="ri-close-circle-fill"></i></span></td>
                                <td class="text-center"><span class="text-danger fs-18"><i class="ri-close-circle-fill"></i></span></td>
                            </tr>
                            <tr>
                                <td><i class="ri-article-line me-2 text-muted"></i>Content</td>
                                <td class="text-center"><span class="text-success fs-18"><i class="ri-checkbox-circle-fill"></i></span></td>
                                <td class="text-center"><span class="text-success fs-18"><i class="ri-checkbox-circle-fill"></i></span></td>
                                <td class="text-center"><span class="text-danger fs-18"><i class="ri-close-circle-fill"></i></span></td>
                                <td class="text-center"><span class="text-danger fs-18"><i class="ri-close-circle-fill"></i></span></td>
                            </tr>
                            <tr>
                                <td><i class="ri-bar-chart-box-line me-2 text-muted"></i>Analytics</td>
                                <td class="text-center"><span class="text-success fs-18"><i class="ri-checkbox-circle-fill"></i></span></td>
                                <td class="text-center"><span class="text-success fs-18"><i class="ri-checkbox-circle-fill"></i></span></td>
                                <td class="text-center"><span class="text-success fs-18"><i class="ri-checkbox-circle-fill"></i></span></td>
                                <td class="text-center"><span class="text-success fs-18"><i class="ri-checkbox-circle-fill"></i></span></td>
                            </tr>
                            <tr>
                                <td><i class="ri-user-line me-2 text-muted"></i>Users</td>
                                <td class="text-center"><span class="text-success fs-18"><i class="ri-checkbox-circle-fill"></i></span></td>
                                <td class="text-center"><span class="text-danger fs-18"><i class="ri-close-circle-fill"></i></span></td>
                                <td class="text-center"><span class="text-danger fs-18"><i class="ri-close-circle-fill"></i></span></td>
                                <td class="text-center"><span class="text-danger fs-18"><i class="ri-close-circle-fill"></i></span></td>
                            </tr>
                            <tr>
                                <td><i class="ri-settings-4-line me-2 text-muted"></i>Settings</td>
                                <td class="text-center"><span class="text-success fs-18"><i class="ri-checkbox-circle-fill"></i></span></td>
                                <td class="text-center"><span class="text-danger fs-18"><i class="ri-close-circle-fill"></i></span></td>
                                <td class="text-center"><span class="text-danger fs-18"><i class="ri-close-circle-fill"></i></span></td>
                                <td class="text-center"><span class="text-danger fs-18"><i class="ri-close-circle-fill"></i></span></td>
                            </tr>
                            <tr>
                                <td><i class="ri-server-line me-2 text-muted"></i>System</td>
                                <td class="text-center"><span class="text-success fs-18"><i class="ri-checkbox-circle-fill"></i></span></td>
                                <td class="text-center"><span class="text-danger fs-18"><i class="ri-close-circle-fill"></i></span></td>
                                <td class="text-center"><span class="text-danger fs-18"><i class="ri-close-circle-fill"></i></span></td>
                                <td class="text-center"><span class="text-danger fs-18"><i class="ri-close-circle-fill"></i></span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Role Modal -->
<div class="modal fade" id="addRoleModal" tabindex="-1" aria-labelledby="addRoleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addRoleModalLabel"><i class="ri-shield-user-line me-2"></i>Add New Role</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addRoleForm">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Role Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="name" placeholder="Enter role name" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Color <span class="text-danger">*</span></label>
                            <select class="form-select" name="color" required>
                                <option value="">Select color...</option>
                                <option value="primary">Blue</option>
                                <option value="success">Green</option>
                                <option value="danger">Red</option>
                                <option value="warning">Yellow</option>
                                <option value="info">Cyan</option>
                                <option value="secondary">Gray</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Description</label>
                            <textarea class="form-control" name="description" rows="2" placeholder="Describe this role's purpose..."></textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold mb-3">Permissions</label>

                            <div class="row g-3">
                                <div class="col-md-4">
                                    <h6 class="text-muted fs-12 text-uppercase mb-2">CRM</h6>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" id="perm_dashboard" value="dashboard">
                                        <label class="form-check-label" for="perm_dashboard">Dashboard</label>
                                    </div>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" id="perm_leads" value="leads">
                                        <label class="form-check-label" for="perm_leads">Leads</label>
                                    </div>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" id="perm_clients" value="clients">
                                        <label class="form-check-label" for="perm_clients">Clients</label>
                                    </div>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" id="perm_cases" value="cases">
                                        <label class="form-check-label" for="perm_cases">Cases</label>
                                    </div>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" id="perm_tasks" value="tasks">
                                        <label class="form-check-label" for="perm_tasks">Tasks</label>
                                    </div>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" id="perm_documents" value="documents">
                                        <label class="form-check-label" for="perm_documents">Documents</label>
                                    </div>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" id="perm_calendar" value="calendar">
                                        <label class="form-check-label" for="perm_calendar">Calendar</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <h6 class="text-muted fs-12 text-uppercase mb-2">Finance</h6>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" id="perm_invoices" value="invoices">
                                        <label class="form-check-label" for="perm_invoices">Invoices</label>
                                    </div>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" id="perm_payments" value="payments">
                                        <label class="form-check-label" for="perm_payments">Payments</label>
                                    </div>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" id="perm_pos" value="pos">
                                        <label class="form-check-label" for="perm_pos">POS</label>
                                    </div>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" id="perm_expenses" value="expenses">
                                        <label class="form-check-label" for="perm_expenses">Expenses</label>
                                    </div>
                                    <h6 class="text-muted fs-12 text-uppercase mt-3 mb-2">Marketing</h6>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" id="perm_marketing" value="marketing">
                                        <label class="form-check-label" for="perm_marketing">Marketing</label>
                                    </div>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" id="perm_content" value="content">
                                        <label class="form-check-label" for="perm_content">Content</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <h6 class="text-muted fs-12 text-uppercase mb-2">System</h6>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" id="perm_analytics" value="analytics">
                                        <label class="form-check-label" for="perm_analytics">Analytics</label>
                                    </div>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" id="perm_users" value="users">
                                        <label class="form-check-label" for="perm_users">Users</label>
                                    </div>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" id="perm_settings" value="settings">
                                        <label class="form-check-label" for="perm_settings">Settings</label>
                                    </div>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" id="perm_system" value="system">
                                        <label class="form-check-label" for="perm_system">System</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="document.getElementById('addRoleForm').submit()">
                    <i class="ri-add-line me-1"></i>Create Role
                </button>
            </div>
        </div>
    </div>
</div>

@endsection
