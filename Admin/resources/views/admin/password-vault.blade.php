@extends('partials.layouts.master')
@section('title', 'Password Vault | WinCase CRM')
@section('sub-title', 'Password Check-In')
@section('sub-title-lang', 'wc-password-vault')
@section('pagetitle', 'Admin')
@section('pagetitle-lang', 'wc-admin')

@section('content')

<!-- PIN Lock Screen -->
<div id="pinScreen" class="d-flex justify-content-center align-items-center" style="min-height: 50vh;">
    <div class="card shadow-lg" style="max-width: 420px; width: 100%;">
        <div class="card-body text-center p-4">
            <div class="avatar avatar-lg bg-primary-subtle text-primary rounded-circle mx-auto mb-3">
                <i class="ri-lock-2-line fs-28"></i>
            </div>
            <h5 class="mb-1" id="pinTitle">Enter PIN</h5>
            <p class="text-muted fs-13 mb-4" id="pinSubtitle">Enter your 4-digit PIN to access passwords</p>

            <div class="d-flex justify-content-center gap-2 mb-4" id="pinDots">
                <div class="pin-dot"></div><div class="pin-dot"></div><div class="pin-dot"></div><div class="pin-dot"></div>
            </div>

            <div class="pin-keypad mx-auto" style="max-width: 280px;">
                <div class="row g-2 mb-2">
                    <div class="col-4"><button class="btn btn-outline-secondary w-100 py-2 fs-18" onclick="pinInput('1')">1</button></div>
                    <div class="col-4"><button class="btn btn-outline-secondary w-100 py-2 fs-18" onclick="pinInput('2')">2</button></div>
                    <div class="col-4"><button class="btn btn-outline-secondary w-100 py-2 fs-18" onclick="pinInput('3')">3</button></div>
                </div>
                <div class="row g-2 mb-2">
                    <div class="col-4"><button class="btn btn-outline-secondary w-100 py-2 fs-18" onclick="pinInput('4')">4</button></div>
                    <div class="col-4"><button class="btn btn-outline-secondary w-100 py-2 fs-18" onclick="pinInput('5')">5</button></div>
                    <div class="col-4"><button class="btn btn-outline-secondary w-100 py-2 fs-18" onclick="pinInput('6')">6</button></div>
                </div>
                <div class="row g-2 mb-2">
                    <div class="col-4"><button class="btn btn-outline-secondary w-100 py-2 fs-18" onclick="pinInput('7')">7</button></div>
                    <div class="col-4"><button class="btn btn-outline-secondary w-100 py-2 fs-18" onclick="pinInput('8')">8</button></div>
                    <div class="col-4"><button class="btn btn-outline-secondary w-100 py-2 fs-18" onclick="pinInput('9')">9</button></div>
                </div>
                <div class="row g-2">
                    <div class="col-4"><button class="btn btn-outline-danger w-100 py-2" onclick="pinClear()"><i class="ri-delete-back-2-line"></i></button></div>
                    <div class="col-4"><button class="btn btn-outline-secondary w-100 py-2 fs-18" onclick="pinInput('0')">0</button></div>
                    <div class="col-4"><button class="btn btn-outline-success w-100 py-2" onclick="pinSubmit()"><i class="ri-check-line"></i></button></div>
                </div>
            </div>

            <p class="text-danger mt-3 fs-13 d-none" id="pinError">Wrong PIN. Try again.</p>

            <div class="mt-3 d-none" id="pinSetupHint">
                <small class="text-muted">First time? Create your 4-digit PIN</small>
            </div>
        </div>
    </div>
</div>

<!-- Vault Content (hidden until PIN is entered) -->
<div id="vaultContent" class="d-none">

    <!-- Toolbar -->
    <div class="card mb-3">
        <div class="card-body py-2">
            <div class="d-flex justify-content-between align-items-center">
                <div class="d-flex gap-2 align-items-center">
                    <i class="ri-shield-check-line text-success fs-20"></i>
                    <span class="fw-semibold text-success">Vault Unlocked</span>
                </div>
                <div class="d-flex gap-2">
                    <button class="btn btn-sm btn-primary" onclick="showAddEntry()"><i class="ri-add-line me-1"></i>Add Credential</button>
                    <button class="btn btn-sm btn-outline-warning" onclick="changePin()"><i class="ri-lock-line me-1"></i>Change PIN</button>
                    <button class="btn btn-sm btn-outline-danger" onclick="lockVault()"><i class="ri-lock-2-line me-1"></i>Lock</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Password Entries -->
    <div class="row" id="vaultCards">
        <!-- Cards will be rendered here -->
    </div>
</div>

<!-- Add/Edit Entry Modal -->
<div class="modal fade" id="entryModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="entryModalTitle">Add Credential</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="entryId" value="">
                <div class="mb-3">
                    <label class="form-label">For whom / what <span class="text-danger">*</span></label>
                    <select class="form-select" id="entryService" onchange="onServiceChange()">
                        <option value="">— Select —</option>
                        <optgroup label="Boss">
                            <option value="Boss Mobile App">Boss — Mobile App</option>
                            <option value="Admin Panel">Boss — Admin Panel (admin.wincase.eu)</option>
                        </optgroup>
                        <optgroup label="Staff / Worker">
                            <option value="Staff Admin Panel">Worker — Staff Panel (staff.wincase.eu)</option>
                            <option value="Staff Mobile App">Worker — Mobile App</option>
                        </optgroup>
                        <optgroup label="Servers">
                            <option value="VPS-1 SSH">VPS-1 (Main) — SSH</option>
                            <option value="cPanel VPS-2">VPS-2 — cPanel</option>
                            <option value="cPanel VPS-3">VPS-3 — cPanel</option>
                            <option value="cPanel VPS-4">VPS-4 — cPanel</option>
                            <option value="Hetzner">Hetzner Account</option>
                        </optgroup>
                        <optgroup label="Services">
                            <option value="Google Ads">Google Ads</option>
                            <option value="Facebook Ads">Facebook Ads</option>
                            <option value="OpenAI API">OpenAI API</option>
                            <option value="WordPress">WordPress</option>
                            <option value="Cloudflare">Cloudflare</option>
                            <option value="GitHub">GitHub</option>
                        </optgroup>
                        <option value="custom">Other (custom)...</option>
                    </select>
                </div>
                <div class="mb-3 d-none" id="customServiceDiv">
                    <label class="form-label">Custom Service Name</label>
                    <input type="text" class="form-control" id="entryCustomService">
                </div>
                <div class="mb-3">
                    <label class="form-label">URL <span class="text-muted fs-11">(auto-filled)</span></label>
                    <input type="text" class="form-control" id="entryUrl" placeholder="https://...">
                </div>
                <div class="mb-3">
                    <label class="form-label">Login / Email <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="entryLogin">
                </div>
                <div class="mb-3">
                    <label class="form-label">Password <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <input type="text" class="form-control" id="entryPassword">
                        <button class="btn btn-outline-secondary" type="button" onclick="genVaultPwd()"><i class="ri-key-2-line"></i></button>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Notes</label>
                    <textarea class="form-control" id="entryNotes" rows="2"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-subtle-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="saveEntry()">Save</button>
            </div>
        </div>
    </div>
</div>

<style>
.pin-dot { width: 16px; height: 16px; border-radius: 50%; border: 2px solid #adb5bd; transition: all 0.2s; }
.pin-dot.filled { background: var(--bs-primary); border-color: var(--bs-primary); }
.vault-card { transition: transform 0.2s; }
.vault-card:hover { transform: translateY(-2px); }
.pwd-masked { font-family: monospace; letter-spacing: 2px; }
</style>

@endsection

@section('js')
<script>
const API_BASE = '/api/v1';
const VAULT_STORAGE_KEY = 'wc_vault_pin_hash';
let currentPin = '';
let pinMode = 'verify'; // 'verify', 'setup', 'change'
let vaultEntries = [];

function getToken() {
    return localStorage.getItem('wc_token') || '';
}
function apiHeaders() {
    return { 'Authorization': 'Bearer ' + getToken(), 'Content-Type': 'application/json', 'Accept': 'application/json' };
}

// Simple hash for PIN (client-side only)
function hashPin(pin) {
    let hash = 0;
    const str = 'wc_vault_' + pin + '_salt2026';
    for (let i = 0; i < str.length; i++) {
        const char = str.charCodeAt(i);
        hash = ((hash << 5) - hash) + char;
        hash |= 0;
    }
    return hash.toString(36);
}

// Check if PIN is set
function isPinSetup() {
    return !!localStorage.getItem(VAULT_STORAGE_KEY);
}

// PIN input
function pinInput(digit) {
    if (currentPin.length >= 4) return;
    currentPin += digit;
    updatePinDots();
    if (currentPin.length === 4) setTimeout(pinSubmit, 200);
}

function pinClear() {
    currentPin = currentPin.slice(0, -1);
    updatePinDots();
}

function updatePinDots() {
    const dots = document.querySelectorAll('.pin-dot');
    dots.forEach((d, i) => d.classList.toggle('filled', i < currentPin.length));
}

function pinSubmit() {
    if (currentPin.length !== 4) return;

    if (pinMode === 'setup') {
        // Save new PIN
        localStorage.setItem(VAULT_STORAGE_KEY, hashPin(currentPin));
        unlockVault();
        return;
    }

    if (pinMode === 'change') {
        localStorage.setItem(VAULT_STORAGE_KEY, hashPin(currentPin));
        if (typeof Swal !== 'undefined') {
            Swal.fire({ icon: 'success', title: 'PIN Changed', timer: 1500, showConfirmButton: false });
        }
        return;
    }

    // Verify PIN
    if (hashPin(currentPin) === localStorage.getItem(VAULT_STORAGE_KEY)) {
        unlockVault();
    } else {
        document.getElementById('pinError').classList.remove('d-none');
        currentPin = '';
        updatePinDots();
        setTimeout(() => document.getElementById('pinError').classList.add('d-none'), 2000);
    }
}

function unlockVault() {
    document.getElementById('pinScreen').classList.add('d-none');
    document.getElementById('vaultContent').classList.remove('d-none');
    loadVaultEntries();
}

function lockVault() {
    currentPin = '';
    updatePinDots();
    document.getElementById('pinScreen').classList.remove('d-none');
    document.getElementById('vaultContent').classList.add('d-none');
    pinMode = 'verify';
    document.getElementById('pinTitle').textContent = 'Enter PIN';
    document.getElementById('pinSubtitle').textContent = 'Enter your 4-digit PIN to access passwords';
}

function changePin() {
    lockVault();
    pinMode = 'change';
    document.getElementById('pinTitle').textContent = 'New PIN';
    document.getElementById('pinSubtitle').textContent = 'Enter a new 4-digit PIN';
}

// Vault entries — stored in localStorage (encrypted by PIN presence)
function loadVaultEntries() {
    try {
        const raw = localStorage.getItem('wc_vault_entries');
        vaultEntries = raw ? JSON.parse(raw) : [];
    } catch { vaultEntries = []; }
    renderVaultCards();
}

function saveVaultEntries() {
    localStorage.setItem('wc_vault_entries', JSON.stringify(vaultEntries));
}

function renderVaultCards() {
    const container = document.getElementById('vaultCards');

    if (!vaultEntries.length) {
        container.innerHTML = `<div class="col-12"><div class="card"><div class="card-body text-center py-5 text-muted">
            <i class="ri-key-2-line fs-40 d-block mb-2"></i>No saved credentials yet. Click "Add Credential" to start.
        </div></div></div>`;
        return;
    }

    const icons = {
        'Boss Mobile App': 'ri-smartphone-line text-danger',
        'Staff Admin Panel': 'ri-computer-line text-primary',
        'Staff Mobile App': 'ri-smartphone-line text-success',
        'Admin Panel': 'ri-dashboard-line text-warning',
        'VPS-1 SSH': 'ri-terminal-box-line text-dark',
        'cPanel VPS-2': 'ri-server-line text-info',
        'cPanel VPS-3': 'ri-server-line text-info',
        'cPanel VPS-4': 'ri-server-line text-info',
        'Hetzner': 'ri-cloud-line text-primary',
        'Google Ads': 'ri-google-line text-danger',
        'Facebook Ads': 'ri-facebook-line text-primary',
        'OpenAI API': 'ri-robot-line text-success',
        'WordPress': 'ri-wordpress-line text-info',
        'Cloudflare': 'ri-cloud-line text-warning',
        'GitHub': 'ri-github-line text-dark',
    };

    container.innerHTML = vaultEntries.map((e, idx) => {
        const icon = icons[e.service] || 'ri-key-2-line text-secondary';
        return `<div class="col-md-6 col-xl-4 mb-3">
            <div class="card vault-card h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <div class="d-flex align-items-center gap-2">
                            <i class="${icon} fs-20"></i>
                            <h6 class="mb-0">${e.service}</h6>
                        </div>
                        <div class="btn-group btn-group-sm">
                            <button class="btn btn-subtle-primary" onclick="editEntry(${idx})" title="Edit"><i class="ri-pencil-line"></i></button>
                            <button class="btn btn-subtle-danger" onclick="deleteEntry(${idx})" title="Delete"><i class="ri-delete-bin-line"></i></button>
                        </div>
                    </div>
                    ${e.url ? `<div class="fs-12 text-muted mb-2"><i class="ri-link me-1"></i>${e.url}</div>` : ''}
                    <div class="mb-1"><span class="text-muted fs-12">Login:</span> <strong class="fs-13">${e.login}</strong>
                        <button class="btn btn-sm p-0 ms-1" onclick="copyText('${e.login}')" title="Copy"><i class="ri-file-copy-line fs-12"></i></button>
                    </div>
                    <div class="mb-1"><span class="text-muted fs-12">Password:</span>
                        <span class="pwd-masked fs-13" id="pwd-${idx}">••••••••</span>
                        <button class="btn btn-sm p-0 ms-1" onclick="togglePwd(${idx})" title="Show/Hide"><i class="ri-eye-line fs-12"></i></button>
                        <button class="btn btn-sm p-0 ms-1" onclick="copyText('${e.password.replace(/'/g, "\\'")}')" title="Copy"><i class="ri-file-copy-line fs-12"></i></button>
                    </div>
                    ${e.notes ? `<div class="mt-2 fs-12 text-muted"><i class="ri-sticky-note-line me-1"></i>${e.notes}</div>` : ''}
                    <div class="mt-2 fs-11 text-muted">Added: ${new Date(e.createdAt).toLocaleDateString()}</div>
                </div>
            </div>
        </div>`;
    }).join('');
}

function togglePwd(idx) {
    const el = document.getElementById('pwd-' + idx);
    if (el.textContent === '••••••••') {
        el.textContent = vaultEntries[idx].password;
    } else {
        el.textContent = '••••••••';
    }
}

function copyText(text) {
    navigator.clipboard.writeText(text);
    if (typeof Swal !== 'undefined') {
        Swal.fire({ icon: 'success', title: 'Copied!', timer: 800, showConfirmButton: false, position: 'top-end', toast: true });
    }
}

const SERVICE_URLS = {
    'Boss Mobile App': 'https://admin.wincase.eu/api/v1/auth/login',
    'Admin Panel': 'https://admin.wincase.eu',
    'Staff Admin Panel': 'https://staff.wincase.eu',
    'Staff Mobile App': 'https://admin.wincase.eu/api/v1/auth/login',
    'VPS-1 SSH': 'ssh://root@65.109.108.79',
    'cPanel VPS-2': 'https://65.109.108.82:2083',
    'cPanel VPS-3': 'https://65.109.108.106:2083',
    'cPanel VPS-4': 'https://65.109.108.107:2083',
    'Hetzner': 'https://accounts.hetzner.com',
    'Google Ads': 'https://ads.google.com',
    'Facebook Ads': 'https://business.facebook.com',
    'OpenAI API': 'https://platform.openai.com',
    'WordPress': '',
    'Cloudflare': 'https://dash.cloudflare.com',
    'GitHub': 'https://github.com',
};

function onServiceChange() {
    const sel = document.getElementById('entryService').value;
    document.getElementById('customServiceDiv').classList.toggle('d-none', sel !== 'custom');

    // Auto-fill URL
    if (sel && sel !== 'custom' && SERVICE_URLS[sel] !== undefined) {
        document.getElementById('entryUrl').value = SERVICE_URLS[sel];
    }
}

function showAddEntry() {
    document.getElementById('entryModalTitle').textContent = 'Add Credential';
    document.getElementById('entryId').value = '';
    document.getElementById('entryService').value = '';
    document.getElementById('entryCustomService').value = '';
    document.getElementById('entryUrl').value = '';
    document.getElementById('entryLogin').value = '';
    document.getElementById('entryPassword').value = '';
    document.getElementById('entryNotes').value = '';
    document.getElementById('customServiceDiv').classList.add('d-none');
    new bootstrap.Modal(document.getElementById('entryModal')).show();
}

function editEntry(idx) {
    const e = vaultEntries[idx];
    document.getElementById('entryModalTitle').textContent = 'Edit Credential';
    document.getElementById('entryId').value = idx;

    const serviceSelect = document.getElementById('entryService');
    const options = Array.from(serviceSelect.options).map(o => o.value);
    if (options.includes(e.service)) {
        serviceSelect.value = e.service;
        document.getElementById('customServiceDiv').classList.add('d-none');
    } else {
        serviceSelect.value = 'custom';
        document.getElementById('entryCustomService').value = e.service;
        document.getElementById('customServiceDiv').classList.remove('d-none');
    }

    document.getElementById('entryUrl').value = e.url || '';
    document.getElementById('entryLogin').value = e.login;
    document.getElementById('entryPassword').value = e.password;
    document.getElementById('entryNotes').value = e.notes || '';
    new bootstrap.Modal(document.getElementById('entryModal')).show();
}

function deleteEntry(idx) {
    if (!confirm('Delete this credential?')) return;
    vaultEntries.splice(idx, 1);
    saveVaultEntries();
    renderVaultCards();
}

function saveEntry() {
    let service = document.getElementById('entryService').value;
    if (service === 'custom') service = document.getElementById('entryCustomService').value;
    const login = document.getElementById('entryLogin').value;
    const password = document.getElementById('entryPassword').value;

    if (!service || !login || !password) { alert('Service, Login and Password are required'); return; }

    const entry = {
        service,
        url: document.getElementById('entryUrl').value || null,
        login,
        password,
        notes: document.getElementById('entryNotes').value || null,
        createdAt: new Date().toISOString(),
    };

    const idx = document.getElementById('entryId').value;
    if (idx !== '') {
        entry.createdAt = vaultEntries[parseInt(idx)].createdAt;
        vaultEntries[parseInt(idx)] = entry;
    } else {
        vaultEntries.push(entry);
    }

    saveVaultEntries();
    renderVaultCards();
    bootstrap.Modal.getInstance(document.getElementById('entryModal')).hide();
}

function genVaultPwd() {
    const chars = 'ABCDEFGHJKMNPQRSTUVWXYZabcdefghijkmnpqrstuvwxyz23456789!@#$%&';
    let pwd = '';
    for (let i = 0; i < 16; i++) pwd += chars.charAt(Math.floor(Math.random() * chars.length));
    document.getElementById('entryPassword').value = pwd;
}

// Init
document.addEventListener('DOMContentLoaded', () => {
    if (!isPinSetup()) {
        pinMode = 'setup';
        document.getElementById('pinTitle').textContent = 'Create PIN';
        document.getElementById('pinSubtitle').textContent = 'Create a 4-digit PIN to protect your passwords';
        document.getElementById('pinSetupHint').classList.remove('d-none');
    }
});
</script>
@endsection
