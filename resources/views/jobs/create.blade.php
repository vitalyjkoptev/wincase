@extends('partials.layouts.master')
@section('title', 'Create Listing | WinCase CRM')
@section('sub-title', 'Create Listing')
@section('pagetitle', 'Jobs Portal')

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header"><h5 class="card-title mb-0">New Job Listing</h5></div>
            <div class="card-body">
                <form id="createForm">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label">Title <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="title" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Category</label>
                            <input type="text" class="form-control" name="category" placeholder="e.g. Production, Warehouse, IT">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">City</label>
                            <input type="text" class="form-control" name="city">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Employer</label>
                            <select class="form-select" name="employer_id" id="selEmployer">
                                <option value="">— No employer —</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Employment Type</label>
                            <select class="form-select" name="employment_type">
                                <option value="full-time">Full-time</option>
                                <option value="part-time">Part-time</option>
                                <option value="contract">Contract</option>
                                <option value="temporary">Temporary</option>
                                <option value="internship">Internship</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Salary From</label>
                            <input type="number" class="form-control" name="salary_from" placeholder="e.g. 4000">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Salary To</label>
                            <input type="number" class="form-control" name="salary_to" placeholder="e.g. 6000">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Currency</label>
                            <select class="form-select" name="currency">
                                <option value="PLN">PLN</option>
                                <option value="EUR">EUR</option>
                                <option value="USD">USD</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Description <span class="text-danger">*</span></label>
                            <textarea class="form-control" name="description" rows="6" required></textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Requirements</label>
                            <textarea class="form-control" name="requirements" rows="4"></textarea>
                        </div>
                        <div class="col-md-4">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="work_permit_provided" id="chkPermit">
                                <label class="form-check-label" for="chkPermit">Work Permit Provided</label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="accommodation_provided" id="chkAccom">
                                <label class="form-check-label" for="chkAccom">Accommodation</label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="transport_provided" id="chkTransport">
                                <label class="form-check-label" for="chkTransport">Transport</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Source</label>
                            <input type="text" class="form-control" name="source" placeholder="e.g. manual, n8n, olx">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Source URL</label>
                            <input type="url" class="form-control" name="source_url" placeholder="https://...">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Expires At</label>
                            <input type="date" class="form-control" name="expires_at">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Status</label>
                            <select class="form-select" name="status">
                                <option value="draft">Draft</option>
                                <option value="active">Active</option>
                            </select>
                        </div>
                    </div>
                </form>
            </div>
            <div class="card-footer text-end">
                <a href="jobs-listings" class="btn btn-light me-2">Cancel</a>
                <button type="button" class="btn btn-success" id="btnSave"><i class="ri-save-line me-1"></i>Create Listing</button>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header"><h5 class="card-title mb-0">Tips</h5></div>
            <div class="card-body">
                <ul class="list-unstyled text-muted fs-13 mb-0">
                    <li class="mb-2"><i class="ri-checkbox-circle-line text-success me-1"></i>Use clear, specific job title</li>
                    <li class="mb-2"><i class="ri-checkbox-circle-line text-success me-1"></i>Include salary range for better visibility</li>
                    <li class="mb-2"><i class="ri-checkbox-circle-line text-success me-1"></i>Mention perks (accommodation, transport)</li>
                    <li class="mb-2"><i class="ri-checkbox-circle-line text-success me-1"></i>Set as "Active" to publish immediately</li>
                    <li><i class="ri-checkbox-circle-line text-success me-1"></i>Link employer for company branding</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
(function() {
    var TOKEN = localStorage.getItem('wc_token');
    var H = { 'Accept': 'application/json', 'Authorization': 'Bearer ' + TOKEN };

    // Load employers for dropdown
    fetch('/api/v1/jobs/employers?per_page=100', { headers: H }).then(r => r.json()).then(j => {
        var items = (j.data && j.data.data) || [];
        var sel = document.getElementById('selEmployer');
        items.forEach(function(e) {
            var opt = document.createElement('option');
            opt.value = e.id;
            opt.textContent = e.company_name + (e.city ? ' (' + e.city + ')' : '');
            sel.appendChild(opt);
        });
    }).catch(function() {});

    document.getElementById('btnSave').addEventListener('click', function() {
        var form = document.getElementById('createForm');
        if (!form.checkValidity()) { form.reportValidity(); return; }

        var fd = new FormData(form);
        // Handle checkboxes
        fd.set('work_permit_provided', document.getElementById('chkPermit').checked ? '1' : '0');
        fd.set('accommodation_provided', document.getElementById('chkAccom').checked ? '1' : '0');
        fd.set('transport_provided', document.getElementById('chkTransport').checked ? '1' : '0');

        fetch('/api/v1/jobs/vacancies', {
            method: 'POST',
            headers: { 'Accept': 'application/json', 'Authorization': 'Bearer ' + TOKEN },
            body: fd,
        }).then(r => r.json()).then(j => {
            if (j.success) {
                window.location.href = 'jobs-listings';
            } else {
                alert(j.message || 'Error creating listing');
            }
        }).catch(function() { alert('Network error'); });
    });
})();
</script>
@endsection
