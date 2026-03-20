@extends('partials.layouts.master')
@section('title', 'Staff Management | WinCase CRM')
@section('sub-title', 'Staff Management')
@section('sub-title-lang', 'wc-staff-management')
@section('pagetitle', 'Admin')
@section('pagetitle-lang', 'wc-admin')

@section('content')

<!-- Stats Row -->
<div class="row mb-4" id="userStats">
    <div class="col-sm-6 col-xl-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center gap-3">
                    <div class="avatar avatar-md bg-danger-subtle text-danger rounded"><i class="ri-shield-user-line fs-20"></i></div>
                    <div><p class="text-muted mb-1 fs-12">Boss / Admins</p><h5 class="mb-0" id="statBoss">0</h5></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center gap-3">
                    <div class="avatar avatar-md bg-primary-subtle text-primary rounded"><i class="ri-team-line fs-20"></i></div>
                    <div><p class="text-muted mb-1 fs-12">Staff</p><h5 class="mb-0" id="statStaff">0</h5></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center gap-3">
                    <div class="avatar avatar-md bg-success-subtle text-success rounded"><i class="ri-user-follow-line fs-20"></i></div>
                    <div><p class="text-muted mb-1 fs-12">Active</p><h5 class="mb-0" id="statActive">0</h5></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center gap-3">
                    <div class="avatar avatar-md bg-warning-subtle text-warning rounded"><i class="ri-user-unfollow-line fs-20"></i></div>
                    <div><p class="text-muted mb-1 fs-12">Inactive</p><h5 class="mb-0" id="statInactive">0</h5></div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Staff Table -->
<div class="card mb-3">
    <div class="card-body py-2">
        <div class="row align-items-center g-2">
            <div class="col-md-5">
                <input type="text" class="form-control form-control-sm" id="staffSearch" placeholder="Search by name, email or phone...">
            </div>
            <div class="col-md-3">
                <select class="form-select form-select-sm" id="staffStatusFilter">
                    <option value="">All Status</option>
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>
            <div class="col-md-4 text-end">
                <button class="btn btn-sm btn-subtle-primary me-1" onclick="loadUsers()"><i class="ri-refresh-line"></i></button>
                <button class="btn btn-sm btn-primary" onclick="openAddModal('staff')"><i class="ri-user-add-line me-1"></i>Add Staff</button>
            </div>
        </div>
    </div>
</div>
<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Role</th>
                        <th>Department</th>
                        <th>Status</th>
                        <th>Last Login</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody id="staffTableBody">
                    <tr><td colspan="8" class="text-center py-4 text-muted">Loading...</td></tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add/Edit User Modal -->
<div class="modal fade" id="addUserModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="userModalTitle">Add User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="userForm">
                    <input type="hidden" id="userId" value="">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Full Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="userName" required placeholder="Ivan Petrov">
                            <small class="text-muted">Latin letters recommended. Email will be auto-generated.</small>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Role <span class="text-danger">*</span></label>
                            <select class="form-select" id="userRole" required>
                                <option value="boss">Boss (Full Access)</option>
                                <option value="staff" selected>Staff</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email <span class="text-muted fs-11">(auto-generated)</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="ri-mail-line"></i></span>
                                <input type="text" class="form-control bg-light" id="userEmail" readonly>
                            </div>
                            <small class="text-info" id="emailHint">Enter name and select role — email will be generated automatically</small>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Password <span class="text-danger" id="pwdRequired">*</span></label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="userPassword">
                                <button class="btn btn-outline-secondary" type="button" onclick="generatePassword()"><i class="ri-key-2-line"></i> Generate</button>
                            </div>
                            <small class="text-muted" id="pwdHint">Min 8 characters. Leave empty when editing to keep current.</small>
                            <div class="text-warning fs-12 mt-1 d-none" id="staffPwdNote">
                                <i class="ri-lock-line me-1"></i>Staff cannot change their password. Only Boss can set/change it.
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Phone</label>
                            <input type="text" class="form-control" id="userPhone" placeholder="+48 ...">
                        </div>
                        <div class="col-md-4" id="departmentGroup">
                            <label class="form-label">Department</label>
                            <select class="form-select" id="userDepartment">
                                <option value="">— Select —</option>
                                <option value="Management">Management</option>
                                <option value="Immigration">Immigration</option>
                                <option value="Legal">Legal</option>
                                <option value="Finance">Finance</option>
                                <option value="Marketing">Marketing</option>
                                <option value="HR">HR</option>
                                <option value="Support">Support</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Status</label>
                            <select class="form-select" id="userStatus">
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                    </div>
                    <div class="mt-3 p-3 border rounded bg-light d-none" id="credentialsSummary">
                        <h6 class="mb-2"><i class="ri-file-copy-line me-1"></i>Credentials to share</h6>
                        <div class="row g-2">
                            <div class="col-md-6">
                                <small class="text-muted">Login (Email or Phone):</small>
                                <div class="d-flex gap-2 align-items-center">
                                    <code id="summaryEmail" class="fs-13">—</code>
                                    <button type="button" class="btn btn-sm btn-outline-secondary py-0 px-1" onclick="copyText('summaryEmail')"><i class="ri-file-copy-line"></i></button>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <small class="text-muted">Password:</small>
                                <div class="d-flex gap-2 align-items-center">
                                    <code id="summaryPassword" class="fs-13">—</code>
                                    <button type="button" class="btn btn-sm btn-outline-secondary py-0 px-1" onclick="copyText('summaryPassword')"><i class="ri-file-copy-line"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mt-3 p-3 bg-light rounded" id="accessInfo">
                        <h6 class="mb-2"><i class="ri-information-line me-1"></i>Platform Access</h6>
                        <div id="accessDetails" class="fs-13 text-muted"></div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-subtle-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="saveUser()" id="saveUserBtn">
                    <i class="ri-save-line me-1"></i>Save User
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('js')
<script>
const API_BASE = '/api/v1';
const BOSS_ROLES = ['boss'];
let allUsers = [];

const EMAIL_DOMAINS = { boss: 'admin.wincase.eu', staff: 'staff.wincase.eu' };

const TRANSLIT = {
    'а':'a','б':'b','в':'v','г':'g','д':'d','е':'e','ё':'yo','ж':'zh',
    'з':'z','и':'i','й':'y','к':'k','л':'l','м':'m','н':'n','о':'o',
    'п':'p','р':'r','с':'s','т':'t','у':'u','ф':'f','х':'kh','ц':'ts',
    'ч':'ch','ш':'sh','щ':'shch','ъ':'','ы':'y','ь':'','э':'e','ю':'yu','я':'ya',
    'є':'ye','і':'i','ї':'yi','ґ':'g',
    'ą':'a','ć':'c','ę':'e','ł':'l','ń':'n','ó':'o','ś':'s','ź':'z','ż':'z'
};

function transliterate(text) {
    return text.toLowerCase().split('').map(ch => TRANSLIT[ch] || ch).join('');
}

function generateEmail() {
    const name = document.getElementById('userName').value.trim();
    const role = document.getElementById('userRole').value;
    const isBoss = BOSS_ROLES.includes(role);
    const isClient = role === 'user';

    if (isClient) {
        document.getElementById('userEmail').value = '';
        document.getElementById('emailHint').innerHTML = '<i class="ri-information-line text-muted me-1"></i>Clients login by phone. Email is optional.';
        updateCredentialsSummary();
        return;
    }

    const domain = isBoss ? EMAIL_DOMAINS.boss : EMAIL_DOMAINS.staff;
    const domainLabel = isBoss ? 'Boss domain' : 'Staff domain';
    const domainColor = isBoss ? 'danger' : 'success';

    if (!name) {
        document.getElementById('userEmail').value = '___@' + domain;
        document.getElementById('emailHint').innerHTML =
            `<i class="ri-edit-line text-${domainColor} me-1"></i>Domain: <strong>@${domain}</strong> <span class="badge bg-${domainColor}-subtle text-${domainColor} ms-1">${domainLabel}</span> — enter name to complete`;
        updateCredentialsSummary();
        return;
    }

    const parts = transliterate(name).split(/\s+/).filter(p => p.length > 0);
    let prefix = parts.length >= 2 ? parts[parts.length - 1] + '.' + parts[0] : (parts[0] || 'user');
    prefix = prefix.replace(/[^a-z0-9.\-]/g, '');
    const email = prefix + '@' + domain;
    document.getElementById('userEmail').value = email;
    document.getElementById('emailHint').innerHTML =
        `<i class="ri-check-line text-success me-1"></i>Email: <strong>${email}</strong> <span class="badge bg-${domainColor}-subtle text-${domainColor} ms-1">${domainLabel}</span>`;
    updateCredentialsSummary();
}

// =====================================================
// TOKEN / API
// =====================================================
function getToken() {
    return localStorage.getItem('wc_token') || getCookie('wc_token') || '';
}
function getCookie(name) {
    const v = document.cookie.match('(^|;)\\s*' + name + '\\s*=\\s*([^;]+)');
    return v ? v.pop() : '';
}
function apiHeaders() {
    return { 'Authorization': 'Bearer ' + getToken(), 'Content-Type': 'application/json', 'Accept': 'application/json' };
}

// =====================================================
// LOAD & RENDER
// =====================================================
async function loadUsers() {
    try {
        const res = await fetch(API_BASE + '/users', { headers: apiHeaders() });
        const json = await res.json();
        allUsers = json.data || [];
        renderAll();
    } catch (e) {
        document.getElementById('staffTableBody').innerHTML =
            '<tr><td colspan="8" class="text-center py-4 text-danger">Failed to load</td></tr>';
    }
}

function renderAll() {
    const staffUsers = allUsers.filter(u => u.role === 'boss' || u.role === 'staff');

    // Apply filters
    const staffSearch = document.getElementById('staffSearch').value.toLowerCase();
    const staffStatus = document.getElementById('staffStatusFilter').value;

    let filteredStaff = staffUsers;
    if (staffSearch) filteredStaff = filteredStaff.filter(u => u.name.toLowerCase().includes(staffSearch) || u.email.toLowerCase().includes(staffSearch) || (u.phone || '').includes(staffSearch));
    if (staffStatus) filteredStaff = filteredStaff.filter(u => (u.status || 'active') === staffStatus);

    renderStaffTable(filteredStaff);

    // Stats
    document.getElementById('statBoss').textContent = staffUsers.filter(u => u.role === 'boss').length;
    document.getElementById('statStaff').textContent = staffUsers.filter(u => u.role === 'staff').length;
    document.getElementById('statActive').textContent = staffUsers.filter(u => (u.status || 'active') === 'active').length;
    document.getElementById('statInactive').textContent = staffUsers.filter(u => u.status === 'inactive').length;
}

function renderStaffTable(users) {
    const tbody = document.getElementById('staffTableBody');
    if (!users.length) {
        tbody.innerHTML = '<tr><td colspan="8" class="text-center py-4 text-muted">No staff found</td></tr>';
        return;
    }
    tbody.innerHTML = users.map(u => {
        const initials = u.name.split(' ').map(p => p[0]).join('').substring(0, 2).toUpperCase();
        const color = u.role === 'boss' ? 'danger' : 'primary';
        const statusColor = (u.status || 'active') === 'active' ? 'success' : 'secondary';
        const lastLogin = u.last_login_at ? new Date(u.last_login_at).toLocaleDateString('en-GB', { day: '2-digit', month: 'short', hour: '2-digit', minute: '2-digit' }) : 'Never';
        return `<tr>
            <td><div class="d-flex align-items-center gap-2">
                <div class="avatar avatar-xs avatar-rounded bg-${color}-subtle text-${color}">${initials}</div>
                <strong>${u.name}</strong>
            </div></td>
            <td class="fs-12">${u.email}</td>
            <td class="fs-12">${u.phone || '—'}</td>
            <td><span class="badge bg-${color}-subtle text-${color}">${u.role === 'boss' ? 'Boss' : 'Staff'}</span></td>
            <td>${u.department || '—'}</td>
            <td><span class="badge bg-${statusColor}-subtle text-${statusColor}">${u.status || 'active'}</span></td>
            <td class="text-muted fs-12">${lastLogin}</td>
            <td class="text-end">
                <div class="btn-group btn-group-sm">
                    <button class="btn btn-subtle-primary" onclick="editUser(${u.id})" title="Edit"><i class="ri-pencil-line"></i></button>
                    <button class="btn btn-subtle-${(u.status||'active') === 'active' ? 'warning' : 'success'}" onclick="toggleStatus(${u.id}, '${u.status||'active'}')" title="${(u.status||'active') === 'active' ? 'Deactivate' : 'Activate'}">
                        <i class="ri-${(u.status||'active') === 'active' ? 'user-unfollow-line' : 'user-follow-line'}"></i>
                    </button>
                    <button class="btn btn-subtle-danger" onclick="deleteUser(${u.id}, '${u.name}')" title="Delete"><i class="ri-delete-bin-line"></i></button>
                </div>
            </td>
        </tr>`;
    }).join('');
}

// =====================================================
// MODAL
// =====================================================
function openAddModal(defaultRole) {
    document.getElementById('userModalTitle').textContent = 'Add Staff';
    document.getElementById('userId').value = '';
    document.getElementById('userForm').reset();
    document.getElementById('pwdRequired').style.display = 'inline';
    document.getElementById('credentialsSummary').classList.add('d-none');
    document.getElementById('userRole').value = defaultRole || 'staff';
    toggleDepartmentField();
    setTimeout(() => { generateEmail(); updateAccessInfo(); }, 50);
    new bootstrap.Modal(document.getElementById('addUserModal')).show();
}

function toggleDepartmentField() {
    const role = document.getElementById('userRole').value;
    document.getElementById('departmentGroup').style.display = role === 'user' ? 'none' : '';
}

// =====================================================
// PASSWORD
// =====================================================
function generatePassword() {
    const chars = 'ABCDEFGHJKMNPQRSTUVWXYZabcdefghijkmnpqrstuvwxyz23456789';
    let pwd = '';
    for (let i = 0; i < 12; i++) pwd += chars.charAt(Math.floor(Math.random() * chars.length));
    document.getElementById('userPassword').value = pwd;
    updateCredentialsSummary();
}

function updateCredentialsSummary() {
    const email = document.getElementById('userEmail').value;
    const phone = document.getElementById('userPhone').value;
    const pwd = document.getElementById('userPassword').value;
    const panel = document.getElementById('credentialsSummary');
    const login = email || phone || '';

    if (login && pwd) {
        panel.classList.remove('d-none');
        document.getElementById('summaryEmail').textContent = login;
        document.getElementById('summaryPassword').textContent = pwd;
    } else {
        panel.classList.add('d-none');
    }
}

function copyText(elementId) {
    const text = document.getElementById(elementId).textContent;
    navigator.clipboard.writeText(text).then(() => {
        const btn = document.querySelector(`[onclick="copyText('${elementId}')"]`);
        const origHtml = btn.innerHTML;
        btn.innerHTML = '<i class="ri-check-line text-success"></i>';
        setTimeout(() => btn.innerHTML = origHtml, 1500);
    });
}

// =====================================================
// ACCESS INFO
// =====================================================
function updateAccessInfo() {
    const role = document.getElementById('userRole').value;
    const info = document.getElementById('accessDetails');
    const staffPwdNote = document.getElementById('staffPwdNote');

    if (BOSS_ROLES.includes(role)) {
        staffPwdNote.classList.add('d-none');
        info.innerHTML = `
            <div class="text-success"><i class="ri-check-line me-1"></i><strong>Boss Mobile App</strong> — full dashboard, multichat, workers, finances</div>
            <div class="text-success"><i class="ri-check-line me-1"></i><strong>Admin Panel</strong> — full CRM access</div>
            <div class="text-success"><i class="ri-check-line me-1"></i><strong>Staff Panel</strong> — can also use staff panel</div>
            <div class="mt-2 text-primary"><i class="ri-key-line me-1"></i>Can change own password: <strong>Yes</strong></div>`;
    } else if (role === 'staff') {
        staffPwdNote.classList.remove('d-none');
        info.innerHTML = `
            <div class="text-danger"><i class="ri-close-line me-1"></i><strong>Boss Mobile App</strong> — no access</div>
            <div class="text-danger"><i class="ri-close-line me-1"></i><strong>Admin Panel</strong> — no access</div>
            <div class="text-success"><i class="ri-check-line me-1"></i><strong>Staff Panel</strong> — assigned clients, cases, tasks</div>
            <div class="text-success"><i class="ri-check-line me-1"></i><strong>Mobile App (Worker mode)</strong> — assigned clients, cases, tasks</div>
            <div class="mt-2 text-warning"><i class="ri-lock-line me-1"></i>Can change own password: <strong>No</strong> — only Boss can set/change</div>`;
    } else {
        staffPwdNote.classList.add('d-none');
        info.innerHTML = `
            <div class="text-success"><i class="ri-check-line me-1"></i><strong>Client Portal</strong> — view cases, documents, payments</div>
            <div class="text-success"><i class="ri-check-line me-1"></i><strong>Mobile App (Client mode)</strong> — track case progress, chat</div>
            <div class="text-muted"><i class="ri-information-line me-1"></i>Login by phone number or email</div>`;
    }
}

// =====================================================
// EVENT LISTENERS
// =====================================================
document.getElementById('userRole').addEventListener('change', () => { generateEmail(); updateAccessInfo(); toggleDepartmentField(); });
document.getElementById('userName').addEventListener('input', generateEmail);
document.getElementById('userPassword').addEventListener('input', updateCredentialsSummary);
document.getElementById('userPhone').addEventListener('input', updateCredentialsSummary);

let staffSearchTimer;
document.getElementById('staffSearch').addEventListener('input', () => { clearTimeout(staffSearchTimer); staffSearchTimer = setTimeout(renderAll, 300); });
document.getElementById('staffStatusFilter').addEventListener('change', renderAll);

// =====================================================
// EDIT USER
// =====================================================
async function editUser(id) {
    const user = allUsers.find(u => u.id === id);
    if (!user) return;

    document.getElementById('userModalTitle').textContent = 'Edit Staff';
    document.getElementById('userId').value = user.id;
    document.getElementById('userName').value = user.name;
    document.getElementById('userRole').value = user.role;
    document.getElementById('userEmail').value = user.email;
    document.getElementById('userPassword').value = '';
    document.getElementById('userPhone').value = user.phone || '';
    document.getElementById('userDepartment').value = user.department || '';
    document.getElementById('userStatus').value = user.status || 'active';
    document.getElementById('pwdRequired').style.display = 'none';
    document.getElementById('emailHint').innerHTML =
        '<i class="ri-information-line me-1"></i>Email set at creation. To change, delete and re-create user.';

    toggleDepartmentField();
    updateAccessInfo();
    updateCredentialsSummary();
    new bootstrap.Modal(document.getElementById('addUserModal')).show();
}

// =====================================================
// SAVE USER
// =====================================================
async function saveUser() {
    const id = document.getElementById('userId').value;
    const name = document.getElementById('userName').value.trim();
    const role = document.getElementById('userRole').value;

    if (!name) { alert('Name is required'); return; }

    const data = {
        name: name,
        role: role,
        phone: document.getElementById('userPhone').value || null,
        department: role !== 'user' ? (document.getElementById('userDepartment').value || null) : null,
        status: document.getElementById('userStatus').value,
    };

    if (!id) data.auto_email = true;
    data.email = document.getElementById('userEmail').value || null;

    const pwd = document.getElementById('userPassword').value;
    if (pwd) data.password = pwd;
    if (!id && !pwd) { alert('Password is required for new users'); return; }

    const btn = document.getElementById('saveUserBtn');
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Saving...';

    try {
        const url = id ? `${API_BASE}/users/${id}` : `${API_BASE}/users`;
        const method = id ? 'PUT' : 'POST';
        const res = await fetch(url, { method, headers: apiHeaders(), body: JSON.stringify(data) });
        const json = await res.json();

        if (!res.ok) {
            const errors = json.errors ? Object.values(json.errors).flat().join('\n') : (json.message || 'Error');
            alert(errors);
            return;
        }

        const createdLogin = json.data?.email || json.data?.phone || data.email || data.phone || '';
        bootstrap.Modal.getInstance(document.getElementById('addUserModal')).hide();
        loadUsers();

        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: 'success',
                title: id ? 'User Updated' : 'User Created',
                html: `<strong>${name}</strong><br>Login: <code>${createdLogin}</code>${pwd ? '<br>Password: <code>' + pwd + '</code>' : ''}`,
                timer: 5000,
                showConfirmButton: true,
                confirmButtonText: 'OK'
            });
        }
    } catch (e) {
        alert('Failed to save user: ' + e.message);
    } finally {
        btn.disabled = false;
        btn.innerHTML = '<i class="ri-save-line me-1"></i>Save User';
    }
}

// =====================================================
// TOGGLE STATUS / DELETE
// =====================================================
async function toggleStatus(id, currentStatus) {
    const action = currentStatus === 'active' ? 'deactivate' : 'activate';
    if (!confirm(`${action.charAt(0).toUpperCase() + action.slice(1)} this user?`)) return;
    try {
        await fetch(`${API_BASE}/users/${id}/${action}`, { method: 'POST', headers: apiHeaders() });
        loadUsers();
    } catch (e) { alert('Error: ' + e.message); }
}

async function deleteUser(id, name) {
    if (!confirm(`Delete user "${name}"? This action cannot be undone.`)) return;
    try {
        await fetch(`${API_BASE}/users/${id}`, { method: 'DELETE', headers: apiHeaders() });
        loadUsers();
    } catch (e) { alert('Error: ' + e.message); }
}

// =====================================================
// INIT
// =====================================================
document.addEventListener('DOMContentLoaded', () => {
    loadUsers();
    updateAccessInfo();
});
</script>
@endsection
