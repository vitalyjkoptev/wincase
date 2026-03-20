@extends('partials.layouts.master')
@section('title', 'Profile | WinCase CRM')
@section('sub-title', 'Profile')
@section('sub-title-lang', 'wc-profile')
@section('pagetitle', 'CRM')
@section('pagetitle-lang', 'wc-title-crm')
@section('content')

<div class="row">
    <div class="col-xxl-3">
        <div class="card">
            <div class="card-body p-4 text-center">
                <div class="mb-4">
                    <div class="avatar-xxl mx-auto position-relative">
                        <div class="avatar-title bg-primary-subtle text-primary rounded-circle fs-1" id="profileAvatar">
                            <i class="ri-user-3-line"></i>
                        </div>
                        <span class="position-absolute bottom-0 end-0 border-2 border border-white h-16px w-16px rounded-circle bg-success"></span>
                    </div>
                </div>
                <h5 class="mb-1" id="profileName">Loading...</h5>
                <p class="text-muted mb-3" id="profileRole">—</p>
                <div class="d-flex justify-content-center gap-2 mb-3" id="profileBadges"></div>
                <div class="text-start">
                    <div class="d-flex align-items-center mb-3">
                        <i class="ri-mail-line text-muted me-2 fs-16"></i>
                        <span class="text-muted" id="profileEmail">—</span>
                    </div>
                    <div class="d-flex align-items-center mb-3">
                        <i class="ri-phone-line text-muted me-2 fs-16"></i>
                        <span class="text-muted" id="profilePhone">—</span>
                    </div>
                    <div class="d-flex align-items-center mb-3">
                        <i class="ri-map-pin-line text-muted me-2 fs-16"></i>
                        <span class="text-muted">Warsaw, Poland</span>
                    </div>
                    <div class="d-flex align-items-center">
                        <i class="ri-calendar-line text-muted me-2 fs-16"></i>
                        <span class="text-muted" id="profileJoined">—</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header"><h6 class="card-title mb-0">Specializations</h6></div>
            <div class="card-body">
                <div class="d-flex flex-wrap gap-2">
                    <span class="badge bg-light text-body">Work Permits</span>
                    <span class="badge bg-light text-body">Residency</span>
                    <span class="badge bg-light text-body">Business Immigration</span>
                    <span class="badge bg-light text-body">EU Blue Card</span>
                    <span class="badge bg-light text-body">Family Reunification</span>
                    <span class="badge bg-light text-body">Citizenship</span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xxl-9">
        <!-- Stats -->
        <div class="row">
            <div class="col-md-3">
                <div class="card"><div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar-sm flex-shrink-0"><span class="avatar-title bg-primary-subtle text-primary rounded"><i class="ri-briefcase-line fs-20"></i></span></div>
                        <div class="flex-grow-1 ms-3"><p class="text-muted mb-1 fs-12">Active Cases</p><h4 class="mb-0" id="statCases">—</h4></div>
                    </div>
                </div></div>
            </div>
            <div class="col-md-3">
                <div class="card"><div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar-sm flex-shrink-0"><span class="avatar-title bg-success-subtle text-success rounded"><i class="ri-user-follow-line fs-20"></i></span></div>
                        <div class="flex-grow-1 ms-3"><p class="text-muted mb-1 fs-12">Total Clients</p><h4 class="mb-0" id="statClients">—</h4></div>
                    </div>
                </div></div>
            </div>
            <div class="col-md-3">
                <div class="card"><div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar-sm flex-shrink-0"><span class="avatar-title bg-warning-subtle text-warning rounded"><i class="ri-task-line fs-20"></i></span></div>
                        <div class="flex-grow-1 ms-3"><p class="text-muted mb-1 fs-12">Pending Tasks</p><h4 class="mb-0" id="statTasks">—</h4></div>
                    </div>
                </div></div>
            </div>
            <div class="col-md-3">
                <div class="card"><div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar-sm flex-shrink-0"><span class="avatar-title bg-info-subtle text-info rounded"><i class="ri-file-list-line fs-20"></i></span></div>
                        <div class="flex-grow-1 ms-3"><p class="text-muted mb-1 fs-12">Leads This Month</p><h4 class="mb-0" id="statLeads">—</h4></div>
                    </div>
                </div></div>
            </div>
        </div>

        <!-- Change Password -->
        <div class="card">
            <div class="card-header"><h6 class="card-title mb-0">Security</h6></div>
            <div class="card-body">
                <form id="changePasswordForm" onsubmit="return changePassword(event)">
                    <div class="row g-3">
                        <div class="col-md-4"><label class="form-label">Current Password</label><input type="password" class="form-control" id="currentPassword" required></div>
                        <div class="col-md-4"><label class="form-label">New Password</label><input type="password" class="form-control" id="newPassword" required minlength="8"></div>
                        <div class="col-md-4"><label class="form-label">Confirm New Password</label><input type="password" class="form-control" id="confirmPassword" required minlength="8"></div>
                    </div>
                    <div class="mt-3"><button type="submit" class="btn btn-primary"><i class="ri-lock-password-line me-1"></i>Update Password</button></div>
                    <div class="alert alert-success d-none mt-3" id="pwdSuccess">Password updated successfully</div>
                    <div class="alert alert-danger d-none mt-3" id="pwdError"></div>
                </form>
            </div>
        </div>

        <!-- Account Info -->
        <div class="card">
            <div class="card-header"><h6 class="card-title mb-0">Account Information</h6></div>
            <div class="card-body">
                <table class="table table-borderless mb-0">
                    <tbody>
                        <tr><td class="text-muted" style="width:200px">User ID</td><td id="infoId">—</td></tr>
                        <tr><td class="text-muted">Position</td><td id="infoPosition">—</td></tr>
                        <tr><td class="text-muted">Department</td><td id="infoDepartment">—</td></tr>
                        <tr><td class="text-muted">Last Login</td><td id="infoLastLogin">—</td></tr>
                        <tr><td class="text-muted">Status</td><td id="infoStatus">—</td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection

@section('js')
<script>
const API_BASE = '/api/v1';
function getToken() { return localStorage.getItem('wc_token') || ''; }
function apiHeaders() { return { 'Authorization': 'Bearer ' + getToken(), 'Content-Type': 'application/json', 'Accept': 'application/json' }; }

document.addEventListener('DOMContentLoaded', loadProfile);

async function loadProfile() {
    try {
        const res = await fetch(API_BASE + '/auth/me', { headers: apiHeaders() });
        if (!res.ok) return;
        const json = await res.json();
        const user = json.data || json;

        document.getElementById('profileName').textContent = user.name || '—';
        document.getElementById('profileEmail').textContent = user.email || '—';
        document.getElementById('profilePhone').textContent = user.phone || 'Not set';
        document.getElementById('profileRole').textContent =
            user.role === 'boss' ? 'Boss — WinCase CRM' :
            user.role === 'staff' ? 'Staff — WinCase CRM' : 'Client';

        const initial = (user.name || '?').charAt(0).toUpperCase();
        document.getElementById('profileAvatar').textContent = initial;

        const roleBadge = user.role === 'boss'
            ? '<span class="badge bg-danger-subtle text-danger">Boss</span>'
            : user.role === 'staff'
            ? '<span class="badge bg-primary-subtle text-primary">Staff</span>'
            : '<span class="badge bg-info-subtle text-info">Client</span>';
        const statusBadge = (user.status === 'active' || !user.status)
            ? '<span class="badge bg-success-subtle text-success">Active</span>'
            : '<span class="badge bg-warning-subtle text-warning">' + (user.status || 'Unknown') + '</span>';
        document.getElementById('profileBadges').innerHTML = roleBadge + statusBadge;

        if (user.created_at) {
            document.getElementById('profileJoined').textContent = 'Joined: ' + new Date(user.created_at).toLocaleDateString('en-US', {month:'short', year:'numeric'});
        }

        document.getElementById('infoId').textContent = '#' + (user.id || '—');
        document.getElementById('infoPosition').textContent = user.position || '—';
        document.getElementById('infoDepartment').textContent = user.department || '—';
        document.getElementById('infoLastLogin').textContent = user.last_login_at ? new Date(user.last_login_at).toLocaleString() : '—';
        document.getElementById('infoStatus').innerHTML = statusBadge;
    } catch (e) { console.error('Profile load error:', e); }

    try {
        const res = await fetch(API_BASE + '/dashboard/kpi', { headers: apiHeaders() });
        if (res.ok) {
            const json = await res.json();
            const d = json.data || json;
            document.getElementById('statCases').textContent = d.active_cases ?? d.cases?.active ?? '0';
            document.getElementById('statClients').textContent = d.total_clients ?? d.clients?.total ?? '0';
            document.getElementById('statTasks').textContent = d.pending_tasks ?? d.tasks?.pending ?? '0';
            document.getElementById('statLeads').textContent = d.leads_this_month ?? d.leads?.this_month ?? '0';
        }
    } catch (e) { /* stats not critical */ }
}

async function changePassword(e) {
    e.preventDefault();
    const pwd = document.getElementById('newPassword').value;
    const confirm = document.getElementById('confirmPassword').value;
    document.getElementById('pwdSuccess').classList.add('d-none');
    document.getElementById('pwdError').classList.add('d-none');

    if (pwd !== confirm) {
        document.getElementById('pwdError').textContent = 'Passwords do not match';
        document.getElementById('pwdError').classList.remove('d-none');
        return false;
    }

    try {
        const res = await fetch(API_BASE + '/auth/password/change', {
            method: 'POST',
            headers: apiHeaders(),
            body: JSON.stringify({ current_password: document.getElementById('currentPassword').value, password: pwd, password_confirmation: confirm }),
        });
        if (res.ok) {
            document.getElementById('pwdSuccess').classList.remove('d-none');
            document.getElementById('changePasswordForm').reset();
        } else {
            const json = await res.json();
            document.getElementById('pwdError').textContent = json.message || 'Failed';
            document.getElementById('pwdError').classList.remove('d-none');
        }
    } catch (e) {
        document.getElementById('pwdError').textContent = 'Connection error';
        document.getElementById('pwdError').classList.remove('d-none');
    }
    return false;
}
</script>
@endsection
