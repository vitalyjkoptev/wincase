@extends('partials.layouts.master')
@section('title', 'Client Registrations | WinCase CRM')
@section('sub-title', 'Registrations')
@section('sub-title-lang', 'wc-registrations')
@section('pagetitle', 'CRM')
@section('pagetitle-lang', 'wc-title-crm')
@section('css')
@include('partials.head-css')
<style>
    .reg-badge { font-size: .7rem; padding: .2em .5em; }
    .detail-label { font-weight: 600; color: #495057; font-size: .8rem; min-width: 160px; }
    .detail-val { font-size: .8rem; }
    .detail-section { border-bottom: 1px solid #eee; padding-bottom: .75rem; margin-bottom: .75rem; }
    .detail-section:last-child { border: none; margin: 0; padding: 0; }
    .filter-pill { cursor: pointer; }
    .filter-pill.active { background: var(--bs-primary) !important; color: #fff !important; }
    .reg-row { cursor: pointer; transition: background .15s; }
    .reg-row:hover { background: rgba(1,94,167,.04); }
    .family-chip { display: inline-block; background: #f0f4f8; border-radius: .375rem; padding: .2rem .5rem; margin: .125rem; font-size: .75rem; }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0"><i class="ri-user-add-line me-2"></i>Client Portal Registrations</h4>
                <div class="d-flex gap-2">
                    <button class="btn btn-sm btn-outline-primary" id="btnRefresh"><i class="ri-refresh-line me-1"></i>Refresh</button>
                    <button class="btn btn-sm btn-outline-success" id="btnExport"><i class="ri-download-line me-1"></i>Export CSV</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row g-3 mb-4" id="statsRow">
        <div class="col-6 col-lg-3">
            <div class="card shadow-sm border-0">
                <div class="card-body p-3 d-flex align-items-center gap-3">
                    <div class="avatar-item avatar bg-primary-subtle text-primary rounded-circle"><i class="ri-user-add-line fs-5"></i></div>
                    <div><h4 class="mb-0 fw-bold" id="statTotal">0</h4><small class="text-muted">Total</small></div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card shadow-sm border-0">
                <div class="card-body p-3 d-flex align-items-center gap-3">
                    <div class="avatar-item avatar bg-warning-subtle text-warning rounded-circle"><i class="ri-time-line fs-5"></i></div>
                    <div><h4 class="mb-0 fw-bold" id="statNew">0</h4><small class="text-muted">New</small></div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card shadow-sm border-0">
                <div class="card-body p-3 d-flex align-items-center gap-3">
                    <div class="avatar-item avatar bg-info-subtle text-info rounded-circle"><i class="ri-eye-line fs-5"></i></div>
                    <div><h4 class="mb-0 fw-bold" id="statReviewed">0</h4><small class="text-muted">Reviewed</small></div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card shadow-sm border-0">
                <div class="card-body p-3 d-flex align-items-center gap-3">
                    <div class="avatar-item avatar bg-success-subtle text-success rounded-circle"><i class="ri-check-double-line fs-5"></i></div>
                    <div><h4 class="mb-0 fw-bold" id="statApproved">0</h4><small class="text-muted">Approved</small></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body py-3">
            <div class="d-flex flex-wrap align-items-center gap-2">
                <span class="fw-medium small me-2">Status:</span>
                <span class="badge bg-light text-dark filter-pill active" data-filter="all">All</span>
                <span class="badge bg-warning-subtle text-warning filter-pill" data-filter="new">New</span>
                <span class="badge bg-info-subtle text-info filter-pill" data-filter="reviewed">Reviewed</span>
                <span class="badge bg-success-subtle text-success filter-pill" data-filter="approved">Approved</span>
                <span class="badge bg-danger-subtle text-danger filter-pill" data-filter="rejected">Rejected</span>
                <div class="ms-auto">
                    <input type="text" class="form-control form-control-sm" id="searchInput" placeholder="Search name, email, phone..." style="min-width:240px;">
                </div>
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th style="width:40px;">#</th>
                            <th>Full Name</th>
                            <th>Email / Phone</th>
                            <th>Nationality</th>
                            <th>Service Needed</th>
                            <th>Status</th>
                            <th>Registered</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="regTableBody">
                        <tr><td colspan="8" class="text-center py-5 text-muted"><span class="spinner-border spinner-border-sm me-2"></span>Loading registrations...</td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- View Registration Detail Modal -->
<div class="modal fade" id="viewModal" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-semibold"><i class="ri-user-3-line me-2"></i>Registration Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="viewModalBody">
                <!-- Filled by JS -->
            </div>
            <div class="modal-footer">
                <button class="btn btn-light" data-bs-dismiss="modal">Close</button>
                <button class="btn btn-danger btn-sm" id="btnReject"><i class="ri-close-line me-1"></i>Reject</button>
                <button class="btn btn-info btn-sm" id="btnMarkReviewed"><i class="ri-eye-line me-1"></i>Mark Reviewed</button>
                <button class="btn btn-success btn-sm" id="btnApprove"><i class="ri-check-line me-1"></i>Approve & Create Client</button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('js')
@include('partials.vendor-scripts')
<script>
(function(){
    var allRegs = [];
    var currentFilter = 'all';
    var currentSearch = '';
    var viewingId = null;

    var statusCfg = {
        new:      { cls: 'bg-warning-subtle text-warning', label: 'New' },
        reviewed: { cls: 'bg-info-subtle text-info', label: 'Reviewed' },
        approved: { cls: 'bg-success-subtle text-success', label: 'Approved' },
        rejected: { cls: 'bg-danger-subtle text-danger', label: 'Rejected' }
    };

    function loadData(){
        fetch('/api/client/registrations')
            .then(function(r){ return r.json(); })
            .then(function(data){
                allRegs = data;
                updateStats();
                render();
            })
            .catch(function(){ document.getElementById('regTableBody').innerHTML = '<tr><td colspan="8" class="text-center py-4 text-danger">Failed to load</td></tr>'; });
    }

    function updateStats(){
        var total = allRegs.length;
        var nw = allRegs.filter(function(r){ return r.status === 'new'; }).length;
        var rv = allRegs.filter(function(r){ return r.status === 'reviewed'; }).length;
        var ap = allRegs.filter(function(r){ return r.status === 'approved'; }).length;
        document.getElementById('statTotal').textContent = total;
        document.getElementById('statNew').textContent = nw;
        document.getElementById('statReviewed').textContent = rv;
        document.getElementById('statApproved').textContent = ap;
    }

    function render(){
        var filtered = allRegs.filter(function(r){
            if(currentFilter !== 'all' && r.status !== currentFilter) return false;
            if(currentSearch) {
                var s = currentSearch.toLowerCase();
                var searchable = ((r.first_name||'') + ' ' + (r.last_name||'') + ' ' + (r.email||'') + ' ' + (r.phone||'') + ' ' + (r.passport_number||'')).toLowerCase();
                if(searchable.indexOf(s) === -1) return false;
            }
            return true;
        });

        var tbody = document.getElementById('regTableBody');
        if(!filtered.length){
            tbody.innerHTML = '<tr><td colspan="8" class="text-center py-5 text-muted">No registrations found</td></tr>';
            return;
        }

        var html = '';
        filtered.forEach(function(r){
            var sc = statusCfg[r.status] || statusCfg.new;
            var date = r.created_at ? new Date(r.created_at).toLocaleDateString('en-GB', {day:'2-digit',month:'short',year:'numeric',hour:'2-digit',minute:'2-digit'}) : '—';
            html += '<tr class="reg-row" data-id="'+r.id+'">'
                + '<td class="fw-medium text-muted">' + r.id + '</td>'
                + '<td><div class="fw-semibold">' + esc(r.first_name) + ' ' + esc(r.last_name) + '</div>'
                + (r.middle_name ? '<small class="text-muted">' + esc(r.middle_name) + '</small>' : '') + '</td>'
                + '<td><div class="small">' + esc(r.email) + '</div><small class="text-muted">' + esc(r.phone || '—') + '</small></td>'
                + '<td>' + esc(r.nationality || '—') + '</td>'
                + '<td><span class="badge bg-light text-dark" style="font-size:.7rem;">' + esc(r.service_needed || '—') + '</span></td>'
                + '<td><span class="badge ' + sc.cls + ' reg-badge">' + sc.label + '</span></td>'
                + '<td class="small text-muted">' + date + '</td>'
                + '<td class="text-end">'
                + '<button class="btn btn-sm btn-outline-primary btn-view" data-id="'+r.id+'"><i class="ri-eye-line"></i></button> '
                + '<button class="btn btn-sm btn-outline-danger btn-del" data-id="'+r.id+'"><i class="ri-delete-bin-line"></i></button>'
                + '</td></tr>';
        });
        tbody.innerHTML = html;
    }

    function esc(s){ if(!s) return ''; var d = document.createElement('div'); d.textContent = s; return d.innerHTML; }

    function fmtDate(d){ if(!d) return '—'; return new Date(d).toLocaleDateString('en-GB',{day:'2-digit',month:'short',year:'numeric'}); }

    function showDetail(id){
        viewingId = id;
        var r = allRegs.find(function(x){ return x.id == id; });
        if(!r) return;

        var familyHtml = '';
        if(r.family_members && r.family_members.length) {
            var members = typeof r.family_members === 'string' ? JSON.parse(r.family_members) : r.family_members;
            members.forEach(function(m){
                if(m.full_name) familyHtml += '<span class="family-chip"><strong>' + esc(m.relation) + ':</strong> ' + esc(m.full_name) + (m.dob ? ' (' + m.dob + ')' : '') + '</span>';
            });
        }
        if(!familyHtml) familyHtml = '<span class="text-muted small">No family members listed</span>';

        var sc = statusCfg[r.status] || statusCfg.new;

        var body = ''
        + '<div class="d-flex justify-content-between align-items-center mb-4">'
        + '<div><h4 class="fw-bold mb-0">' + esc(r.first_name) + ' ' + esc(r.last_name) + '</h4>'
        + '<small class="text-muted">ID #' + r.id + ' &middot; Registered ' + fmtDate(r.created_at) + '</small></div>'
        + '<span class="badge ' + sc.cls + ' fs-6">' + sc.label + '</span></div>'

        // Personal
        + '<div class="detail-section"><h6 class="fw-semibold text-success mb-3"><i class="ri-user-3-line me-1"></i>Personal Information</h6>'
        + '<div class="row g-2">'
        + dRow('First Name', r.first_name) + dRow('Last Name', r.last_name) + dRow('Middle Name', r.middle_name)
        + dRow('Maiden Name', r.maiden_name) + dRow('Date of Birth', fmtDate(r.date_of_birth)) + dRow('Place of Birth', r.place_of_birth)
        + dRow('Gender', r.gender) + dRow('Nationality', r.nationality) + dRow('Citizenship', r.citizenship)
        + dRow('Phone', r.phone) + dRow('Phone 2', r.phone2) + dRow('Messenger', r.messenger)
        + dRow('Email', r.email) + dRow('Language', r.preferred_language)
        + '</div></div>'

        // Documents
        + '<div class="detail-section"><h6 class="fw-semibold text-success mb-3"><i class="ri-passport-line me-1"></i>Documents</h6>'
        + '<div class="row g-2">'
        + dRow('Passport', r.passport_number) + dRow('Passport Issued', fmtDate(r.passport_issue_date))
        + dRow('Passport Expires', fmtDate(r.passport_expiry_date)) + dRow('Issuing Authority', r.passport_authority)
        + dRow('Issuing Country', r.passport_country) + dRow('PESEL', r.pesel)
        + dRow('NIP', r.nip) + dRow('REGON', r.regon) + dRow('National ID', r.national_id)
        + dRow('Driver License', r.driver_license) + dRow('Prev Passport', r.prev_passport) + dRow('ZUS', r.zus_number)
        + '</div></div>'

        // Address
        + '<div class="detail-section"><h6 class="fw-semibold text-success mb-3"><i class="ri-map-pin-line me-1"></i>Address</h6>'
        + '<div class="row g-2">'
        + dRow('PL Street', r.pl_street) + dRow('Apartment', r.pl_apartment)
        + dRow('Postal Code', r.pl_postal_code) + dRow('City', r.pl_city) + dRow('Voivodeship', r.pl_voivodeship)
        + dRow('Zameldowanie', r.zameldowanie) + dRow('Living Since', fmtDate(r.pl_living_since))
        + dRow('Home Address', r.home_address) + dRow('Home Country', r.home_country) + dRow('Home Phone', r.home_phone)
        + '</div></div>'

        // Immigration
        + '<div class="detail-section"><h6 class="fw-semibold text-success mb-3"><i class="ri-flight-takeoff-line me-1"></i>Immigration</h6>'
        + '<div class="row g-2">'
        + dRow('Status', r.immigration_status) + dRow('Purpose', r.stay_purpose)
        + dRow('Arrival Date', fmtDate(r.arrival_date)) + dRow('Permit From', fmtDate(r.permit_from))
        + dRow('Permit Until', fmtDate(r.permit_until)) + dRow('Permit No.', r.permit_number)
        + dRow('Karta Pobytu', r.karta_pobytu) + dRow('Prev Application', r.previous_application)
        + dRow('Entry Ban', r.entry_ban ? 'YES' : 'No') + dRow('Criminal', r.criminal_record ? 'YES' : 'No')
        + dRow('Service Needed', r.service_needed)
        + '</div>'
        + (r.immigration_notes ? '<div class="mt-2"><small class="text-muted fw-medium">Notes:</small><p class="small mb-0">' + esc(r.immigration_notes) + '</p></div>' : '')
        + '</div>'

        // Family
        + '<div class="detail-section"><h6 class="fw-semibold text-success mb-3"><i class="ri-group-line me-1"></i>Family</h6>'
        + '<div class="row g-2">'
        + dRow('Marital Status', r.marital_status) + dRow('Children', r.num_children) + dRow('Dependents in PL', r.dependents_in_poland)
        + '</div><div class="mt-2">' + familyHtml + '</div></div>'

        // Education & Work
        + '<div class="detail-section"><h6 class="fw-semibold text-success mb-3"><i class="ri-graduation-cap-line me-1"></i>Education & Work</h6>'
        + '<div class="row g-2">'
        + dRow('Education', r.education_level) + dRow('Field', r.field_of_study) + dRow('Institution', r.institution)
        + dRow('Graduation', r.graduation_year) + dRow('Edu Country', r.education_country)
        + dRow('Polish Level', r.polish_level) + dRow('Other Langs', r.other_languages)
        + dRow('Employment', r.employment_status) + dRow('Profession', r.profession)
        + dRow('Employer', r.employer_name) + dRow('Emp NIP', r.employer_nip) + dRow('Emp Address', r.employer_address)
        + dRow('Since', fmtDate(r.employment_since)) + dRow('Salary', r.salary ? r.salary + ' PLN' : null)
        + dRow('Work Permit', r.work_permit_type) + dRow('WP Expiry', fmtDate(r.work_permit_expiry))
        + dRow('Health Ins.', r.health_insurance) + dRow('Bank in PL', r.bank_account_poland ? 'Yes' : 'No')
        + dRow('Tax Residency', r.tax_residency)
        + '</div></div>'

        // Agreements
        + '<div class="detail-section"><h6 class="fw-semibold text-success mb-3"><i class="ri-shield-check-line me-1"></i>Agreements</h6>'
        + '<div class="row g-2">'
        + dRowBool('Terms of Service', r.agreed_terms) + dRowBool('RODO/GDPR', r.agreed_rodo)
        + dRowBool('Power of Attorney', r.agreed_poa) + dRowBool('Data Sharing', r.agreed_data_sharing)
        + dRowBool('Marketing', r.agreed_marketing)
        + dRow('Digital Signature', r.digital_signature) + dRow('Signed At', fmtDate(r.agreements_signed_at))
        + '</div></div>'

        // Meta
        + '<div class="detail-section"><h6 class="fw-semibold text-muted mb-3"><i class="ri-information-line me-1"></i>Meta</h6>'
        + '<div class="row g-2">'
        + dRow('IP Address', r.ip_address) + dRow('User Agent', r.user_agent ? r.user_agent.substring(0, 80) + '...' : null)
        + '</div></div>';

        document.getElementById('viewModalBody').innerHTML = body;
        new bootstrap.Modal(document.getElementById('viewModal')).show();
    }

    function dRow(label, val){
        return '<div class="col-md-4 col-sm-6"><div class="d-flex gap-2"><span class="detail-label">' + label + '</span><span class="detail-val">' + esc(val || '—') + '</span></div></div>';
    }
    function dRowBool(label, val){
        var icon = val ? '<i class="ri-check-line text-success"></i> Yes' : '<i class="ri-close-line text-danger"></i> No';
        return '<div class="col-md-4 col-sm-6"><div class="d-flex gap-2"><span class="detail-label">' + label + '</span><span class="detail-val">' + icon + '</span></div></div>';
    }

    function updateStatus(id, status){
        fetch('/api/client/registrations/' + id + '/status', {
            method: 'PATCH',
            headers: {'Content-Type':'application/json', 'X-CSRF-TOKEN':'{{ csrf_token() }}', 'Accept':'application/json'},
            body: JSON.stringify({status: status})
        })
        .then(function(r){ return r.json(); })
        .then(function(){
            bootstrap.Modal.getInstance(document.getElementById('viewModal')).hide();
            loadData();
            showToast('Status updated to: ' + status, 'success');
        });
    }

    function deleteReg(id){
        Swal.fire({
            title:'Delete Registration?',
            text:'This cannot be undone.',
            icon:'warning',
            showCancelButton:true,
            confirmButtonColor:'#dc3545',
            confirmButtonText:'Delete'
        }).then(function(result){
            if(!result.isConfirmed) return;
            fetch('/api/client/registrations/' + id, {
                method:'DELETE',
                headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}','Accept':'application/json'}
            })
            .then(function(){ loadData(); showToast('Registration deleted','success'); });
        });
    }

    // Filter pills
    document.querySelectorAll('.filter-pill').forEach(function(p){
        p.addEventListener('click', function(){
            document.querySelectorAll('.filter-pill').forEach(function(x){ x.classList.remove('active'); });
            this.classList.add('active');
            currentFilter = this.getAttribute('data-filter');
            render();
        });
    });

    // Search
    document.getElementById('searchInput').addEventListener('input', function(){
        currentSearch = this.value;
        render();
    });

    // Table clicks
    document.getElementById('regTableBody').addEventListener('click', function(e){
        var viewBtn = e.target.closest('.btn-view');
        var delBtn = e.target.closest('.btn-del');
        var row = e.target.closest('.reg-row');
        if(viewBtn){ showDetail(viewBtn.getAttribute('data-id')); return; }
        if(delBtn){ deleteReg(delBtn.getAttribute('data-id')); return; }
        if(row && !viewBtn && !delBtn) showDetail(row.getAttribute('data-id'));
    });

    // Modal actions
    document.getElementById('btnApprove').addEventListener('click', function(){ if(viewingId) updateStatus(viewingId, 'approved'); });
    document.getElementById('btnMarkReviewed').addEventListener('click', function(){ if(viewingId) updateStatus(viewingId, 'reviewed'); });
    document.getElementById('btnReject').addEventListener('click', function(){ if(viewingId) updateStatus(viewingId, 'rejected'); });

    // Refresh
    document.getElementById('btnRefresh').addEventListener('click', loadData);

    // Export CSV
    document.getElementById('btnExport').addEventListener('click', function(){
        if(!allRegs.length){ showToast('No data to export','info'); return; }
        var headers = ['ID','First Name','Last Name','Email','Phone','Nationality','Service','Status','Registered'];
        var rows = allRegs.map(function(r){
            return [r.id, r.first_name, r.last_name, r.email, r.phone, r.nationality, r.service_needed, r.status, r.created_at].map(function(v){
                return '"' + String(v||'').replace(/"/g,'""') + '"';
            }).join(',');
        });
        var csv = headers.join(',') + '\n' + rows.join('\n');
        var blob = new Blob(['\uFEFF' + csv], {type:'text/csv;charset=utf-8;'});
        var a = document.createElement('a');
        a.href = URL.createObjectURL(blob);
        a.download = 'client_registrations_' + new Date().toISOString().slice(0,10) + '.csv';
        a.click();
    });

    function showToast(msg, type){
        var t = document.createElement('div');
        t.className = 'position-fixed top-0 end-0 m-3 alert alert-' + (type==='success'?'success':'info') + ' shadow-sm';
        t.style.zIndex = '9999';
        t.innerHTML = '<i class="ri-' + (type==='success'?'check-line':'information-line') + ' me-1"></i>' + msg;
        document.body.appendChild(t);
        setTimeout(function(){ t.style.opacity = '0'; t.style.transition = 'opacity .3s'; setTimeout(function(){ t.remove(); }, 300); }, 3000);
    }

    // Init
    loadData();
})();
</script>
@endsection
