@extends('partials.layouts.master')

@section('title', 'Clients | WinCase CRM')
@section('sub-title', 'Clients')
@section('sub-title-lang', 'wc-clients')
@section('pagetitle', 'CRM')
@section('pagetitle-lang', 'wc-title-crm')
@section('buttonTitle', 'Add Client')
@section('buttonTitle-lang', 'wc-add-client')
@section('modalTarget', 'addClientModal')

@section('content')
<div class="row">
    <!-- Stats Cards -->
    <div class="col-xl-3 col-md-6">
        <div class="card card-h-100">
            <div class="card-body">
                <div class="d-flex align-items-center gap-3">
                    <div class="avatar avatar-sm bg-primary-subtle text-primary rounded-2">
                        <i class="ri-group-line fs-18"></i>
                    </div>
                    <div>
                        <p class="text-muted mb-0 fs-13" data-lang="wc-cl-total-clients">Total Clients</p>
                        <h4 class="mb-0 fw-semibold">389</h4>
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
                        <i class="ri-user-star-line fs-18"></i>
                    </div>
                    <div>
                        <p class="text-muted mb-0 fs-13" data-lang="wc-cl-active">Active</p>
                        <h4 class="mb-0 fw-semibold">312</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card card-h-100">
            <div class="card-body">
                <div class="d-flex align-items-center gap-3">
                    <div class="avatar avatar-sm bg-info-subtle text-info rounded-2">
                        <i class="ri-calendar-check-line fs-18"></i>
                    </div>
                    <div>
                        <p class="text-muted mb-0 fs-13" data-lang="wc-cl-new-this-month">New This Month</p>
                        <h4 class="mb-0 fw-semibold">15</h4>
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
                        <i class="ri-arrow-up-down-line fs-18"></i>
                    </div>
                    <div>
                        <p class="text-muted mb-0 fs-13" data-lang="wc-cl-vs-last-month">vs Last Month</p>
                        <h4 class="mb-0 fw-semibold text-success">+25%</h4>
                        <span class="text-muted fs-11">12 last month</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Period Comparison -->
<div class="card">
    <div class="card-header d-flex align-items-center justify-content-between">
        <h5 class="card-title mb-0" data-lang="wc-cl-growth-comparison">Client Growth Comparison</h5>
        <select class="form-select form-select-sm" style="width: 180px;" id="periodSelector">
            <option data-lang="wc-cl-today">Today</option>
            <option data-lang="wc-cl-this-week">This Week</option>
            <option selected data-lang="wc-cl-new-this-month">This Month</option>
            <option data-lang="wc-cl-this-quarter">This Quarter</option>
            <option data-lang="wc-cl-this-year">This Year</option>
        </select>
    </div>
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-3">
                <div class="border rounded p-3 text-center">
                    <p class="text-muted mb-1 fs-13" data-lang="wc-cl-new-this-month">New This Month</p>
                    <h3 class="mb-0 fw-semibold text-primary">15</h3>
                    <span class="text-muted fs-12" data-lang="wc-cl-new-clients">new clients</span>
                </div>
            </div>
            <div class="col-md-3">
                <div class="border rounded p-3 text-center">
                    <p class="text-muted mb-1 fs-13" data-lang="wc-cl-new-last-month">New Last Month</p>
                    <h3 class="mb-0 fw-semibold">12</h3>
                    <span class="text-muted fs-12" data-lang="wc-cl-new-clients">new clients</span>
                </div>
            </div>
            <div class="col-md-3">
                <div class="border rounded p-3 text-center">
                    <p class="text-muted mb-1 fs-13" data-lang="wc-cl-pct-change">% Change (Growth)</p>
                    <h3 class="mb-0 fw-semibold text-success"><i class="ri-arrow-up-line"></i> +25%</h3>
                    <span class="text-muted fs-12" data-lang="wc-cl-vs-prev-period">vs previous period</span>
                </div>
            </div>
            <div class="col-md-3">
                <div class="border rounded p-3 text-center">
                    <p class="text-muted mb-1 fs-13" data-lang="wc-cl-quarter-total">Quarter Total</p>
                    <h3 class="mb-0 fw-semibold">42</h3>
                    <span class="text-muted fs-12" data-lang="wc-cl-new-clients">new clients</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="card">
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-2">
                <input type="text" class="form-control" placeholder="Search by name, phone, email..." data-lang-placeholder="wc-cl-search-ph">
            </div>
            <div class="col-md-2">
                <select class="form-select">
                    <option selected data-lang="wc-cl-all-periods">All Periods</option>
                    <option data-lang="wc-cl-today">Today</option>
                    <option data-lang="wc-cl-this-week">This Week</option>
                    <option data-lang="wc-cl-new-this-month">New This Month</option>
                    <option data-lang="wc-cl-new-last-month">New Last Month</option>
                    <option data-lang="wc-cl-this-quarter">This Quarter</option>
                    <option data-lang="wc-cl-this-year">This Year</option>
                </select>
            </div>
            <div class="col-md-2">
                <select class="form-select">
                    <option selected data-lang="wc-cl-client-type">Client Type</option>
                    <option data-lang="wc-cl-new-client">New Client</option>
                    <option data-lang="wc-cl-returning-client">Returning Client</option>
                    <option data-lang="wc-cl-referral">Referral</option>
                    <option data-lang="wc-cl-from-agency">From Agency</option>
                </select>
            </div>
            <div class="col-md-2">
                <select class="form-select">
                    <option selected data-lang="wc-cl-source">Source</option>
                    <option>Instagram</option>
                    <option>Instagram Ads</option>
                    <option>Facebook Ads</option>
                    <option>Meta Ads</option>
                    <option>TikTok</option>
                    <option>TikTok Ads</option>
                    <option>Google</option>
                    <option>Google Ads</option>
                    <option>YouTube Ads</option>
                    <option>LinkedIn Ads</option>
                    <option>Referral</option>
                    <option>Website</option>
                </select>
            </div>
            <div class="col-md-2">
                <select class="form-select">
                    <option selected data-lang="wc-cl-all-nationalities">All Nationalities</option>
                    <option>Ukrainian</option>
                    <option>Belarusian</option>
                    <option>Georgian</option>
                    <option>Indian</option>
                    <option>Other</option>
                </select>
            </div>
            <div class="col-md-1">
                <select class="form-select">
                    <option selected data-lang="wc-cl-manager">Manager</option>
                    <option>Jan Nowak</option>
                    <option>Anna Wiśniewska</option>
                    <option>Piotr Kowalczyk</option>
                </select>
            </div>
            <div class="col-md-1">
                <button class="btn btn-primary w-100"><i class="ri-filter-3-line"></i></button>
            </div>
        </div>
    </div>
</div>

<!-- Clients Table -->
<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" id="clientsTable">
                <thead class="table-light">
                    <tr>
                        <th><input class="form-check-input" type="checkbox" id="selectAllClients"></th>
                        <th data-lang="wc-cl-client">Client</th>
                        <th data-lang="wc-cl-phone">Phone</th>
                        <th data-lang="wc-cl-email">Email</th>
                        <th data-lang="wc-cl-type">Type</th>
                        <th data-lang="wc-cl-source">Source</th>
                        <th data-lang="wc-cl-cases">Cases</th>
                        <th data-lang="wc-cl-status">Status</th>
                        <th data-lang="wc-cl-manager">Manager</th>
                        <th data-lang="wc-cl-registered">Registered</th>
                        <th data-lang="wc-cl-actions">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr data-client-id="1" data-name="Oleksandr Petrov" data-phone="+48 512 345 678" data-email="petrov@email.com" data-nationality="Ukrainian" data-type="New Client" data-source="Instagram" data-cases="3" data-status="Active" data-manager="Jan Nowak" data-registered="Jan 15, 2026" data-dob="1990-03-15" data-passport="FE 123456" data-pesel="90031512345" data-city="Warszawa" data-address="ul. Marszałkowska 10/5" data-language="Ukrainian" data-notes="Important client, 3 active cases. Referred by colleague.">
                        <td><input class="form-check-input row-check" type="checkbox"></td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="avatar avatar-xs avatar-rounded bg-primary-subtle text-primary">OP</div>
                                <div>
                                    <a href="#" class="fw-semibold text-body d-block client-view-link">Oleksandr Petrov</a>
                                    <span class="badge bg-info-subtle text-info fs-10">Ukrainian</span>
                                </div>
                            </div>
                        </td>
                        <td><a href="tel:+48512345678" class="text-body">+48 512 345 678</a></td>
                        <td class="text-muted fs-12"><a href="mailto:petrov@email.com">petrov@email.com</a></td>
                        <td><span class="badge bg-primary-subtle text-primary">New Client</span></td>
                        <td><span class="badge bg-danger-subtle text-danger">Instagram</span></td>
                        <td><a href="crm-cases" class="badge bg-primary">3</a></td>
                        <td><span class="badge bg-success-subtle text-success">Active</span></td>
                        <td>Jan Nowak</td>
                        <td class="text-muted fs-12">Jan 15, 2026</td>
                        <td>
                            <div class="dropdown">
                                <button class="btn btn-sm btn-subtle-secondary" data-bs-toggle="dropdown"><i class="ri-more-2-fill"></i></button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item action-view" href="#"><i class="ri-eye-line me-2"></i><span data-lang="wc-cl-view-profile">View Profile</span></a></li>
                                    <li><a class="dropdown-item action-edit" href="#"><i class="ri-edit-line me-2"></i><span data-lang="wc-cl-edit">Edit</span></a></li>
                                    <li><a class="dropdown-item" href="crm-cases"><i class="ri-briefcase-line me-2"></i><span data-lang="wc-cl-cases">Cases</span></a></li>
                                    <li><a class="dropdown-item" href="crm-documents"><i class="ri-file-text-line me-2"></i><span data-lang="wc-cl-documents">Documents</span></a></li>
                                    <li><a class="dropdown-item action-invoices" href="crm-client-invoices?client=1"><i class="ri-money-dollar-circle-line me-2"></i><span data-lang="wc-cl-invoices">Invoices</span></a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item text-warning action-archive" href="#"><i class="ri-archive-line me-2"></i><span data-lang="wc-cl-archive">Archive</span></a></li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                    <tr data-client-id="2" data-name="Maria Ivanova" data-phone="+48 698 765 432" data-email="ivanova@email.com" data-nationality="Ukrainian" data-type="Referral" data-source="Referral" data-cases="1" data-status="Active" data-manager="Anna Wiśniewska" data-registered="Feb 10, 2026" data-dob="1985-07-22" data-passport="FK 654321" data-pesel="85072298765" data-city="Kraków" data-address="ul. Floriańska 15/3" data-language="Ukrainian" data-notes="Client from referral program. Needs assistance with residency.">
                        <td><input class="form-check-input row-check" type="checkbox"></td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="avatar avatar-xs avatar-rounded bg-success-subtle text-success">MI</div>
                                <div>
                                    <a href="#" class="fw-semibold text-body d-block client-view-link">Maria Ivanova</a>
                                    <span class="badge bg-info-subtle text-info fs-10">Ukrainian</span>
                                </div>
                            </div>
                        </td>
                        <td><a href="tel:+48698765432" class="text-body">+48 698 765 432</a></td>
                        <td class="text-muted fs-12"><a href="mailto:ivanova@email.com">ivanova@email.com</a></td>
                        <td><span class="badge bg-warning-subtle text-warning">Referral</span></td>
                        <td><span class="badge bg-warning-subtle text-warning">Referral</span></td>
                        <td><a href="crm-cases" class="badge bg-primary">1</a></td>
                        <td><span class="badge bg-success-subtle text-success">Active</span></td>
                        <td>Anna Wiśniewska</td>
                        <td class="text-muted fs-12">Feb 10, 2026</td>
                        <td>
                            <div class="dropdown">
                                <button class="btn btn-sm btn-subtle-secondary" data-bs-toggle="dropdown"><i class="ri-more-2-fill"></i></button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item action-view" href="#"><i class="ri-eye-line me-2"></i><span data-lang="wc-cl-view-profile">View Profile</span></a></li>
                                    <li><a class="dropdown-item action-edit" href="#"><i class="ri-edit-line me-2"></i><span data-lang="wc-cl-edit">Edit</span></a></li>
                                    <li><a class="dropdown-item" href="crm-cases"><i class="ri-briefcase-line me-2"></i><span data-lang="wc-cl-cases">Cases</span></a></li>
                                    <li><a class="dropdown-item" href="crm-documents"><i class="ri-file-text-line me-2"></i><span data-lang="wc-cl-documents">Documents</span></a></li>
                                    <li><a class="dropdown-item action-invoices" href="crm-client-invoices?client=2"><i class="ri-money-dollar-circle-line me-2"></i><span data-lang="wc-cl-invoices">Invoices</span></a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item text-warning action-archive" href="#"><i class="ri-archive-line me-2"></i><span data-lang="wc-cl-archive">Archive</span></a></li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                    <tr data-client-id="3" data-name="Aliaksandr Kazlou" data-phone="+48 501 234 567" data-email="kazlou@email.com" data-nationality="Belarusian" data-type="Returning Client" data-source="Google" data-cases="2" data-status="Active" data-manager="Piotr Kowalczyk" data-registered="Nov 5, 2025" data-dob="1988-11-30" data-passport="AB 789012" data-pesel="88113078901" data-city="Wrocław" data-address="ul. Świdnicka 22/8" data-language="Russian" data-notes="Returning client. Previous case completed successfully.">
                        <td><input class="form-check-input row-check" type="checkbox"></td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="avatar avatar-xs avatar-rounded bg-warning-subtle text-warning">AK</div>
                                <div>
                                    <a href="#" class="fw-semibold text-body d-block client-view-link">Aliaksandr Kazlou</a>
                                    <span class="badge bg-secondary-subtle text-secondary fs-10">Belarusian</span>
                                </div>
                            </div>
                        </td>
                        <td><a href="tel:+48501234567" class="text-body">+48 501 234 567</a></td>
                        <td class="text-muted fs-12"><a href="mailto:kazlou@email.com">kazlou@email.com</a></td>
                        <td><span class="badge bg-info-subtle text-info">Returning Client</span></td>
                        <td><span class="badge bg-primary-subtle text-primary">Google</span></td>
                        <td><a href="crm-cases" class="badge bg-primary">2</a></td>
                        <td><span class="badge bg-success-subtle text-success">Active</span></td>
                        <td>Piotr Kowalczyk</td>
                        <td class="text-muted fs-12">Nov 5, 2025</td>
                        <td>
                            <div class="dropdown">
                                <button class="btn btn-sm btn-subtle-secondary" data-bs-toggle="dropdown"><i class="ri-more-2-fill"></i></button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item action-view" href="#"><i class="ri-eye-line me-2"></i><span data-lang="wc-cl-view-profile">View Profile</span></a></li>
                                    <li><a class="dropdown-item action-edit" href="#"><i class="ri-edit-line me-2"></i><span data-lang="wc-cl-edit">Edit</span></a></li>
                                    <li><a class="dropdown-item" href="crm-cases"><i class="ri-briefcase-line me-2"></i><span data-lang="wc-cl-cases">Cases</span></a></li>
                                    <li><a class="dropdown-item" href="crm-documents"><i class="ri-file-text-line me-2"></i><span data-lang="wc-cl-documents">Documents</span></a></li>
                                    <li><a class="dropdown-item action-invoices" href="crm-client-invoices?client=3"><i class="ri-money-dollar-circle-line me-2"></i><span data-lang="wc-cl-invoices">Invoices</span></a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item text-warning action-archive" href="#"><i class="ri-archive-line me-2"></i><span data-lang="wc-cl-archive">Archive</span></a></li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                    <tr data-client-id="4" data-name="Giorgi Tsiklauri" data-phone="+48 600 111 222" data-email="tsiklauri@email.com" data-nationality="Georgian" data-type="From Agency" data-source="TikTok" data-cases="0" data-status="Archived" data-manager="Jan Nowak" data-registered="Aug 20, 2025" data-dob="1992-05-18" data-passport="GE 456789" data-pesel="92051845678" data-city="Gdańsk" data-address="ul. Długa 5/2" data-language="English" data-notes="From partner agency. Currently archived — no active cases.">
                        <td><input class="form-check-input row-check" type="checkbox"></td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="avatar avatar-xs avatar-rounded bg-danger-subtle text-danger">GT</div>
                                <div>
                                    <a href="#" class="fw-semibold text-body d-block client-view-link">Giorgi Tsiklauri</a>
                                    <span class="badge bg-success-subtle text-success fs-10">Georgian</span>
                                </div>
                            </div>
                        </td>
                        <td><a href="tel:+48600111222" class="text-body">+48 600 111 222</a></td>
                        <td class="text-muted fs-12"><a href="mailto:tsiklauri@email.com">tsiklauri@email.com</a></td>
                        <td><span class="badge bg-dark-subtle text-dark">From Agency</span></td>
                        <td><span class="badge bg-info-subtle text-info">TikTok</span></td>
                        <td><span class="badge bg-secondary">0</span></td>
                        <td><span class="badge bg-secondary-subtle text-secondary">Archived</span></td>
                        <td>Jan Nowak</td>
                        <td class="text-muted fs-12">Aug 20, 2025</td>
                        <td>
                            <div class="dropdown">
                                <button class="btn btn-sm btn-subtle-secondary" data-bs-toggle="dropdown"><i class="ri-more-2-fill"></i></button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item action-view" href="#"><i class="ri-eye-line me-2"></i><span data-lang="wc-cl-view-profile">View Profile</span></a></li>
                                    <li><a class="dropdown-item action-edit" href="#"><i class="ri-edit-line me-2"></i><span data-lang="wc-cl-edit">Edit</span></a></li>
                                    <li><a class="dropdown-item" href="crm-cases"><i class="ri-briefcase-line me-2"></i><span data-lang="wc-cl-cases">Cases</span></a></li>
                                    <li><a class="dropdown-item" href="crm-documents"><i class="ri-file-text-line me-2"></i><span data-lang="wc-cl-documents">Documents</span></a></li>
                                    <li><a class="dropdown-item action-invoices" href="crm-client-invoices?client=4"><i class="ri-money-dollar-circle-line me-2"></i><span data-lang="wc-cl-invoices">Invoices</span></a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item text-success action-restore" href="#"><i class="ri-inbox-unarchive-line me-2"></i>Restore</a></li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                    <tr data-client-id="5" data-name="Tetiana Sydorenko" data-phone="+48 512 999 888" data-email="sydorenko@email.com" data-nationality="Ukrainian" data-type="New Client" data-source="Meta Ads" data-cases="1" data-status="Active" data-manager="Anna Wiśniewska" data-registered="Feb 18, 2026" data-dob="1995-09-03" data-passport="FH 345678" data-pesel="95090334567" data-city="Warszawa" data-address="ul. Puławska 45/12" data-language="Ukrainian" data-notes="New client from Meta Ads campaign.">
                        <td><input class="form-check-input row-check" type="checkbox"></td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="avatar avatar-xs avatar-rounded bg-info-subtle text-info">TS</div>
                                <div>
                                    <a href="#" class="fw-semibold text-body d-block client-view-link">Tetiana Sydorenko</a>
                                    <span class="badge bg-info-subtle text-info fs-10">Ukrainian</span>
                                </div>
                            </div>
                        </td>
                        <td><a href="tel:+48512999888" class="text-body">+48 512 999 888</a></td>
                        <td class="text-muted fs-12"><a href="mailto:sydorenko@email.com">sydorenko@email.com</a></td>
                        <td><span class="badge bg-primary-subtle text-primary">New Client</span></td>
                        <td><span class="badge bg-secondary-subtle text-secondary">Meta Ads</span></td>
                        <td><a href="crm-cases" class="badge bg-primary">1</a></td>
                        <td><span class="badge bg-success-subtle text-success">Active</span></td>
                        <td>Anna Wiśniewska</td>
                        <td class="text-muted fs-12">Feb 18, 2026</td>
                        <td>
                            <div class="dropdown">
                                <button class="btn btn-sm btn-subtle-secondary" data-bs-toggle="dropdown"><i class="ri-more-2-fill"></i></button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item action-view" href="#"><i class="ri-eye-line me-2"></i><span data-lang="wc-cl-view-profile">View Profile</span></a></li>
                                    <li><a class="dropdown-item action-edit" href="#"><i class="ri-edit-line me-2"></i><span data-lang="wc-cl-edit">Edit</span></a></li>
                                    <li><a class="dropdown-item" href="crm-cases"><i class="ri-briefcase-line me-2"></i><span data-lang="wc-cl-cases">Cases</span></a></li>
                                    <li><a class="dropdown-item" href="crm-documents"><i class="ri-file-text-line me-2"></i><span data-lang="wc-cl-documents">Documents</span></a></li>
                                    <li><a class="dropdown-item action-invoices" href="crm-client-invoices?client=5"><i class="ri-money-dollar-circle-line me-2"></i><span data-lang="wc-cl-invoices">Invoices</span></a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item text-warning action-archive" href="#"><i class="ri-archive-line me-2"></i><span data-lang="wc-cl-archive">Archive</span></a></li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                    <tr data-client-id="6" data-name="Rajesh Kumar" data-phone="+48 505 333 444" data-email="kumar@email.com" data-nationality="Indian" data-type="New Client" data-source="Google Ads" data-cases="1" data-status="Active" data-manager="Piotr Kowalczyk" data-registered="Feb 25, 2026" data-dob="1991-12-10" data-passport="IN 112233" data-pesel="91121056789" data-city="Warszawa" data-address="ul. Nowy Świat 30/7" data-language="English" data-notes="English-speaking client. Work permit case.">
                        <td><input class="form-check-input row-check" type="checkbox"></td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="avatar avatar-xs avatar-rounded bg-secondary-subtle text-secondary">RK</div>
                                <div>
                                    <a href="#" class="fw-semibold text-body d-block client-view-link">Rajesh Kumar</a>
                                    <span class="badge bg-warning-subtle text-warning fs-10">Indian</span>
                                </div>
                            </div>
                        </td>
                        <td><a href="tel:+48505333444" class="text-body">+48 505 333 444</a></td>
                        <td class="text-muted fs-12"><a href="mailto:kumar@email.com">kumar@email.com</a></td>
                        <td><span class="badge bg-primary-subtle text-primary">New Client</span></td>
                        <td><span class="badge bg-primary-subtle text-primary">Google Ads</span></td>
                        <td><a href="crm-cases" class="badge bg-primary">1</a></td>
                        <td><span class="badge bg-success-subtle text-success">Active</span></td>
                        <td>Piotr Kowalczyk</td>
                        <td class="text-muted fs-12">Feb 25, 2026</td>
                        <td>
                            <div class="dropdown">
                                <button class="btn btn-sm btn-subtle-secondary" data-bs-toggle="dropdown"><i class="ri-more-2-fill"></i></button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item action-view" href="#"><i class="ri-eye-line me-2"></i><span data-lang="wc-cl-view-profile">View Profile</span></a></li>
                                    <li><a class="dropdown-item action-edit" href="#"><i class="ri-edit-line me-2"></i><span data-lang="wc-cl-edit">Edit</span></a></li>
                                    <li><a class="dropdown-item" href="crm-cases"><i class="ri-briefcase-line me-2"></i><span data-lang="wc-cl-cases">Cases</span></a></li>
                                    <li><a class="dropdown-item" href="crm-documents"><i class="ri-file-text-line me-2"></i><span data-lang="wc-cl-documents">Documents</span></a></li>
                                    <li><a class="dropdown-item action-invoices" href="crm-client-invoices?client=6"><i class="ri-money-dollar-circle-line me-2"></i><span data-lang="wc-cl-invoices">Invoices</span></a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item text-warning action-archive" href="#"><i class="ri-archive-line me-2"></i><span data-lang="wc-cl-archive">Archive</span></a></li>
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
            <div class="text-muted fs-13">Showing 1-6 of 389 clients</div>
            <nav>
                <ul class="pagination pagination-sm mb-0">
                    <li class="page-item disabled"><a class="page-link" href="#">Previous</a></li>
                    <li class="page-item active"><a class="page-link" href="#">1</a></li>
                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                    <li class="page-item"><a class="page-link" href="#">...</a></li>
                    <li class="page-item"><a class="page-link" href="#">65</a></li>
                    <li class="page-item"><a class="page-link" href="#">Next</a></li>
                </ul>
            </nav>
        </div>
    </div>
</div>

<!-- ============================================ -->
<!-- VIEW CLIENT MODAL -->
<!-- ============================================ -->
<div class="modal fade" id="viewClientModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="ri-user-3-line me-2"></i>Client Profile</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <!-- Left Column: Client Info -->
                    <div class="col-lg-5">
                        <div class="text-center mb-3">
                            <div class="avatar avatar-lg avatar-rounded bg-primary-subtle text-primary mx-auto mb-2">
                                <span class="fs-24" id="viewClientInitials">OP</span>
                            </div>
                            <h5 class="mb-1" id="viewClientName">Client Name</h5>
                            <div id="viewClientBadges"></div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-borderless table-sm mb-0">
                                <tbody id="viewClientInfoTable"></tbody>
                            </table>
                        </div>
                        <div class="d-flex gap-2 mt-3">
                            <button class="btn btn-primary btn-sm flex-fill" id="viewToEditBtn"><i class="ri-edit-line me-1"></i><span data-lang="wc-cl-edit">Edit</span></button>
                            <a href="crm-client-detail" class="btn btn-subtle-info btn-sm flex-fill" id="viewToDetailLink"><i class="ri-external-link-line me-1"></i>Full Profile</a>
                        </div>
                    </div>
                    <!-- Right Column: Cases + Payments + Notes -->
                    <div class="col-lg-7">
                        <!-- Client Cases -->
                        <div class="card border mb-3">
                            <div class="card-header py-2 d-flex align-items-center justify-content-between">
                                <h6 class="card-title mb-0 fs-13"><i class="ri-briefcase-line me-1"></i><span data-lang="wc-cl-active-cases">Active Cases</span></h6>
                                <span class="badge bg-primary" id="viewCasesCount">0</span>
                            </div>
                            <div class="card-body p-0" id="viewClientCases">
                                <div class="p-3 text-muted text-center fs-13">No cases found</div>
                            </div>
                        </div>
                        <!-- Recent Payments -->
                        <div class="card border mb-3">
                            <div class="card-header py-2">
                                <h6 class="card-title mb-0 fs-13"><i class="ri-money-dollar-circle-line me-1"></i>Recent Payments</h6>
                            </div>
                            <div class="card-body p-0" id="viewClientPayments">
                                <div class="p-3 text-muted text-center fs-13">No payments found</div>
                            </div>
                        </div>
                        <!-- Notes -->
                        <div class="card border mb-0">
                            <div class="card-header py-2">
                                <h6 class="card-title mb-0 fs-13"><i class="ri-sticky-note-line me-1"></i>Notes</h6>
                            </div>
                            <div class="card-body">
                                <p class="text-muted mb-0 fs-13" id="viewClientNotes">No notes</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ============================================ -->
<!-- EDIT CLIENT MODAL -->
<!-- ============================================ -->
<div class="modal fade" id="editClientModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="ri-edit-line me-2"></i><span data-lang="wc-cl-edit-client">Edit Client</span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="editClientId">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label" data-lang="wc-cl-first-name">First Name</label> <span class="text-danger">*</span>
                        <input type="text" class="form-control" id="editClientFirstName">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" data-lang="wc-cl-last-name">Last Name</label> <span class="text-danger">*</span>
                        <input type="text" class="form-control" id="editClientLastName">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label" data-lang="wc-cl-dob">Date of Birth</label>
                        <input type="date" class="form-control" id="editClientDob">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label" data-lang="wc-cl-nationality">Citizenship</label>
                        <select class="form-select" id="editClientNationality">
                            <option>Ukrainian</option>
                            <option>Belarusian</option>
                            <option>Georgian</option>
                            <option>Indian</option>
                            <option>Uzbek</option>
                            <option>Moldovan</option>
                            <option>Russian</option>
                            <option>Other</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label" data-lang="wc-cl-passport">Passport Number</label>
                        <input type="text" class="form-control" id="editClientPassport">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" data-lang="wc-cl-phone">Phone</label> <span class="text-danger">*</span>
                        <input type="text" class="form-control" id="editClientPhone">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" data-lang="wc-cl-email">Email</label>
                        <input type="email" class="form-control" id="editClientEmail">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label" data-lang="wc-cl-client-type">Client Type</label>
                        <select class="form-select" id="editClientType">
                            <option data-lang="wc-cl-new-client">New Client</option>
                            <option data-lang="wc-cl-returning-client">Returning Client</option>
                            <option data-lang="wc-cl-referral">Referral</option>
                            <option data-lang="wc-cl-from-agency">From Agency</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label" data-lang="wc-cl-source">Source</label>
                        <select class="form-select" id="editClientSource">
                            <option>Instagram</option>
                            <option>Instagram Ads</option>
                            <option>Facebook Ads</option>
                            <option>Meta Ads</option>
                            <option>TikTok</option>
                            <option>TikTok Ads</option>
                            <option>Google</option>
                            <option>Google Ads</option>
                            <option>YouTube Ads</option>
                            <option>LinkedIn Ads</option>
                            <option>Referral</option>
                            <option>Website</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label" data-lang="wc-cl-language">Language</label>
                        <select class="form-select" id="editClientLanguage">
                            <option>Ukrainian</option>
                            <option>Russian</option>
                            <option>Polish</option>
                            <option>English</option>
                            <option>Other</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label" data-lang="wc-cl-pesel">PESEL</label>
                        <input type="text" class="form-control" id="editClientPesel" maxlength="11">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">City</label>
                        <input type="text" class="form-control" id="editClientCity">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label" data-lang="wc-cl-assigned-manager">Manager</label>
                        <select class="form-select" id="editClientManager">
                            <option>Jan Nowak</option>
                            <option>Anna Wiśniewska</option>
                            <option>Piotr Kowalczyk</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label" data-lang="wc-cl-address">Address</label>
                        <input type="text" class="form-control" id="editClientAddress">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal" data-lang="wc-cl-cancel">Cancel</button>
                <button type="button" class="btn btn-primary" id="saveEditClientBtn"><i class="ri-save-line me-1"></i><span data-lang="wc-cl-save-changes">Save Changes</span></button>
            </div>
        </div>
    </div>
</div>

<!-- ============================================ -->
<!-- ARCHIVE CLIENT MODAL -->
<!-- ============================================ -->
<div class="modal fade" id="archiveClientModal" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header bg-warning-subtle">
                <h5 class="modal-title text-warning"><i class="ri-archive-line me-2"></i><span data-lang="wc-cl-archive-client">Archive Client</span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <input type="hidden" id="archiveClientId">
                <div class="avatar avatar-md avatar-rounded bg-warning-subtle text-warning mx-auto mb-3">
                    <i class="ri-archive-line fs-24"></i>
                </div>
                <h6>Archive <strong id="archiveClientName">Client</strong>?</h6>
                <p class="text-muted" data-lang="wc-cl-archive-desc">Client will be moved to archive. Active cases will not be affected.</p>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal" data-lang="wc-cl-cancel">Cancel</button>
                <button type="button" class="btn btn-warning" id="confirmArchiveBtn"><i class="ri-archive-line me-1"></i><span data-lang="wc-cl-archive">Archive</span></button>
            </div>
        </div>
    </div>
</div>

<!-- ============================================ -->
<!-- ADD CLIENT MODAL -->
<!-- ============================================ -->
<div class="modal fade" id="addClientModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="ri-user-add-line me-2"></i><span data-lang="wc-cl-add-new-client">Add New Client</span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label" data-lang="wc-cl-first-name">First Name</label> <span class="text-danger">*</span>
                        <input type="text" class="form-control" placeholder="Enter first name">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" data-lang="wc-cl-last-name">Last Name</label> <span class="text-danger">*</span>
                        <input type="text" class="form-control" placeholder="Enter last name">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label" data-lang="wc-cl-dob">Date of Birth</label> <span class="text-danger">*</span>
                        <input type="date" class="form-control">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label" data-lang="wc-cl-nationality">Citizenship</label> <span class="text-danger">*</span>
                        <select class="form-select">
                            <option selected disabled>Select citizenship...</option>
                            <option>Ukrainian</option>
                            <option>Belarusian</option>
                            <option>Georgian</option>
                            <option>Indian</option>
                            <option>Uzbek</option>
                            <option>Moldovan</option>
                            <option>Russian</option>
                            <option>Other</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label" data-lang="wc-cl-passport">Passport Number</label> <span class="text-danger">*</span>
                        <input type="text" class="form-control" placeholder="Enter passport number">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" data-lang="wc-cl-phone">Phone</label> <span class="text-danger">*</span>
                        <input type="text" class="form-control" placeholder="+48 XXX XXX XXX">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" data-lang="wc-cl-email">Email</label>
                        <input type="email" class="form-control" placeholder="email@example.com">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label" data-lang="wc-cl-client-type">Client Type</label> <span class="text-danger">*</span>
                        <select class="form-select">
                            <option selected disabled>Select type...</option>
                            <option data-lang="wc-cl-new-client">New Client</option>
                            <option data-lang="wc-cl-returning-client">Returning Client</option>
                            <option data-lang="wc-cl-referral">Referral</option>
                            <option data-lang="wc-cl-from-agency">From Agency</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label" data-lang="wc-cl-source">Source</label> <span class="text-danger">*</span>
                        <select class="form-select">
                            <option selected disabled>Select source...</option>
                            <option>Instagram</option>
                            <option>Instagram Ads</option>
                            <option>Facebook Ads</option>
                            <option>Meta Ads</option>
                            <option>TikTok</option>
                            <option>TikTok Ads</option>
                            <option>Google</option>
                            <option>Google Ads</option>
                            <option>YouTube Ads</option>
                            <option>LinkedIn Ads</option>
                            <option>Referral</option>
                            <option>Website</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label" data-lang="wc-cl-language">Preferred Language</label>
                        <select class="form-select">
                            <option selected>Ukrainian</option>
                            <option>Russian</option>
                            <option>Polish</option>
                            <option>English</option>
                            <option>Other</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" data-lang="wc-cl-pesel">PESEL</label>
                        <input type="text" class="form-control" placeholder="00000000000" maxlength="11">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">City</label>
                        <input type="text" class="form-control" placeholder="e.g. Warszawa">
                    </div>
                    <div class="col-12">
                        <label class="form-label" data-lang="wc-cl-address">Residential Address</label>
                        <input type="text" class="form-control" placeholder="Street, building, apartment">
                    </div>
                    <div class="col-12">
                        <label class="form-label" data-lang="wc-cl-notes">Notes</label>
                        <textarea class="form-control" rows="3" placeholder="Additional notes about the client..."></textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal" data-lang="wc-cl-cancel">Cancel</button>
                <button type="button" class="btn btn-primary"><i class="ri-save-line me-1"></i><span data-lang="wc-cl-save">Save Client</span></button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
document.addEventListener('DOMContentLoaded', function() {
    var table = document.getElementById('clientsTable');
    if (!table) return;

    // ===== HELPERS =====
    function getRow(el) { return el.closest('tr[data-client-id]'); }

    function getInitials(name) {
        var p = name.split(' ');
        return (p[0] ? p[0][0] : '') + (p[1] ? p[1][0] : '');
    }

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

    var SOURCE_COLORS = {
        'Instagram': 'danger', 'Instagram Ads': 'danger', 'Facebook Ads': 'primary',
        'Meta Ads': 'secondary', 'TikTok': 'info', 'TikTok Ads': 'info',
        'Google': 'primary', 'Google Ads': 'primary', 'YouTube Ads': 'danger',
        'LinkedIn Ads': 'info', 'Referral': 'warning', 'Website': 'success'
    };
    var TYPE_COLORS = {
        'New Client': 'primary', 'Returning Client': 'info', 'Referral': 'warning', 'From Agency': 'dark'
    };
    var NATIONALITY_COLORS = {
        'Ukrainian': 'info', 'Belarusian': 'secondary', 'Georgian': 'success', 'Indian': 'warning', 'Other': 'dark'
    };

    function getSourceBadge(src) {
        var c = SOURCE_COLORS[src] || 'secondary';
        return '<span class="badge bg-' + c + '-subtle text-' + c + '">' + src + '</span>';
    }
    function getTypeBadge(t) {
        var c = TYPE_COLORS[t] || 'secondary';
        return '<span class="badge bg-' + c + '-subtle text-' + c + '">' + t + '</span>';
    }
    function getNationalityBadge(n) {
        var c = NATIONALITY_COLORS[n] || 'dark';
        return '<span class="badge bg-' + c + '-subtle text-' + c + '">' + n + '</span>';
    }

    // Sample cases data per client (for demo)
    var CLIENT_CASES = {
        '1': [
            { id: 'WC-2026-0147', type: 'Temp. Residence', status: 'Awaiting Fingerprints', contract: 'PLN 4,800', paid: 'PLN 4,800', debt: '—' },
            { id: 'WC-2026-0155', type: 'Speedup', status: 'Submitted to Office', contract: 'PLN 3,000', paid: 'PLN 1,500', debt: 'PLN 1,500' },
            { id: 'WC-2026-0160', type: 'Citizenship', status: 'Fingerprints Submitted', contract: 'PLN 7,000', paid: 'PLN 3,300', debt: 'PLN 3,700' }
        ],
        '2': [
            { id: 'WC-2026-0162', type: 'Temp. Residence', status: 'Submitted to Office', contract: 'PLN 4,500', paid: 'PLN 2,000', debt: 'PLN 2,500' }
        ],
        '3': [
            { id: 'WC-2025-0098', type: 'Work Permit', status: 'Card Issued', contract: 'PLN 3,500', paid: 'PLN 3,500', debt: '—' },
            { id: 'WC-2026-0170', type: 'Perm. Residence', status: 'Awaiting Decision', contract: 'PLN 6,000', paid: 'PLN 4,000', debt: 'PLN 2,000' }
        ],
        '5': [
            { id: 'WC-2026-0175', type: 'Temp. Residence', status: 'Submitted to Office', contract: 'PLN 4,800', paid: 'PLN 2,400', debt: 'PLN 2,400' }
        ],
        '6': [
            { id: 'WC-2026-0180', type: 'Work Permit', status: 'Awaiting Fingerprints', contract: 'PLN 3,500', paid: 'PLN 1,750', debt: 'PLN 1,750' }
        ]
    };

    var CLIENT_PAYMENTS = {
        '1': [
            { date: 'Feb 24, 2026', caseId: 'WC-2026-0147', amount: 'PLN 4,800', method: 'Bank Transfer' },
            { date: 'Feb 25, 2026', caseId: 'WC-2026-0155', amount: 'PLN 1,500', method: 'Card' },
            { date: 'Jan 10, 2026', caseId: 'WC-2026-0160', amount: 'PLN 3,300', method: 'Cash' }
        ],
        '2': [
            { date: 'Feb 12, 2026', caseId: 'WC-2026-0162', amount: 'PLN 2,000', method: 'Bank Transfer' }
        ],
        '3': [
            { date: 'Dec 15, 2025', caseId: 'WC-2025-0098', amount: 'PLN 3,500', method: 'Card' },
            { date: 'Feb 20, 2026', caseId: 'WC-2026-0170', amount: 'PLN 4,000', method: 'Bank Transfer' }
        ],
        '5': [
            { date: 'Feb 20, 2026', caseId: 'WC-2026-0175', amount: 'PLN 2,400', method: 'Card' }
        ],
        '6': [
            { date: 'Feb 28, 2026', caseId: 'WC-2026-0180', amount: 'PLN 1,750', method: 'Cash' }
        ]
    };

    var CASE_TYPE_COLORS = {
        'Temp. Residence': 'primary', 'Perm. Residence': 'success', 'Work Permit': 'warning',
        'Speedup': 'success', 'Citizenship': 'info', 'EU Residence Card': 'secondary', 'PESEL/NIP/Meldunek': 'dark'
    };
    var CASE_STATUS_COLORS = {
        'Submitted to Office': 'primary', 'Awaiting Fingerprints': 'warning', 'Fingerprint Appointment': 'info',
        'Fingerprints Submitted': 'info', 'Awaiting Decision': 'secondary', 'Decision Signed': 'success', 'Card Issued': 'success'
    };

    // ===== VIEW CLIENT MODAL =====
    function openViewModal(row) {
        var d = row.dataset;

        // Header
        document.getElementById('viewClientInitials').textContent = getInitials(d.name);
        document.getElementById('viewClientName').textContent = d.name;

        var badges = '';
        if (d.status === 'Active') badges += '<span class="badge bg-success-subtle text-success me-1">Active</span>';
        else badges += '<span class="badge bg-secondary-subtle text-secondary me-1">Archived</span>';
        badges += getNationalityBadge(d.nationality);
        document.getElementById('viewClientBadges').innerHTML = badges;

        // Info Table
        var info = '';
        var parts = d.name.split(' ');
        info += '<tr><td class="text-muted fw-medium" style="width:40%">First Name</td><td>' + (parts[0] || '') + '</td></tr>';
        info += '<tr><td class="text-muted fw-medium">Last Name</td><td>' + (parts.slice(1).join(' ') || '') + '</td></tr>';
        if (d.dob) info += '<tr><td class="text-muted fw-medium">Date of Birth</td><td>' + formatDate(d.dob) + '</td></tr>';
        info += '<tr><td class="text-muted fw-medium">Citizenship</td><td>' + d.nationality + '</td></tr>';
        if (d.passport) info += '<tr><td class="text-muted fw-medium">Passport</td><td>' + d.passport + '</td></tr>';
        info += '<tr><td class="text-muted fw-medium">Phone</td><td><a href="tel:' + d.phone.replace(/\s/g, '') + '">' + d.phone + '</a></td></tr>';
        info += '<tr><td class="text-muted fw-medium">Email</td><td><a href="mailto:' + d.email + '">' + d.email + '</a></td></tr>';
        if (d.address && d.city) info += '<tr><td class="text-muted fw-medium">Address</td><td>' + d.address + ', ' + d.city + '</td></tr>';
        if (d.pesel) info += '<tr><td class="text-muted fw-medium">PESEL</td><td>' + d.pesel + '</td></tr>';
        info += '<tr><td class="text-muted fw-medium">Client Type</td><td>' + getTypeBadge(d.type) + '</td></tr>';
        info += '<tr><td class="text-muted fw-medium">Source</td><td>' + getSourceBadge(d.source) + '</td></tr>';
        info += '<tr><td class="text-muted fw-medium">Language</td><td>' + (d.language || 'N/A') + '</td></tr>';
        info += '<tr><td class="text-muted fw-medium">Manager</td><td>' + d.manager + '</td></tr>';
        info += '<tr><td class="text-muted fw-medium">Registered</td><td>' + d.registered + '</td></tr>';
        document.getElementById('viewClientInfoTable').innerHTML = info;

        // Cases
        var cases = CLIENT_CASES[d.clientId] || [];
        document.getElementById('viewCasesCount').textContent = cases.length;
        var casesHtml = '';
        if (cases.length > 0) {
            casesHtml = '<div class="table-responsive"><table class="table table-sm table-hover align-middle mb-0"><thead class="table-light"><tr><th>Case #</th><th>Type</th><th>Status</th><th>Contract</th><th>Debt</th></tr></thead><tbody>';
            for (var i = 0; i < cases.length; i++) {
                var cs = cases[i];
                var tc = CASE_TYPE_COLORS[cs.type] || 'secondary';
                var sc = CASE_STATUS_COLORS[cs.status] || 'secondary';
                casesHtml += '<tr><td class="fw-semibold text-primary fs-12">' + cs.id + '</td>';
                casesHtml += '<td><span class="badge bg-' + tc + '-subtle text-' + tc + '">' + cs.type + '</span></td>';
                casesHtml += '<td><span class="badge bg-' + sc + '-subtle text-' + sc + '">' + cs.status + '</span></td>';
                casesHtml += '<td class="fw-semibold fs-12">' + cs.contract + '</td>';
                casesHtml += '<td class="' + (cs.debt !== '—' ? 'text-danger fw-semibold' : 'text-muted') + ' fs-12">' + cs.debt + '</td></tr>';
            }
            casesHtml += '</tbody></table></div>';
        } else {
            casesHtml = '<div class="p-3 text-muted text-center fs-13">No cases found</div>';
        }
        document.getElementById('viewClientCases').innerHTML = casesHtml;

        // Payments
        var payments = CLIENT_PAYMENTS[d.clientId] || [];
        var paymentsHtml = '';
        if (payments.length > 0) {
            paymentsHtml = '<ul class="list-group list-group-flush">';
            for (var j = 0; j < payments.length; j++) {
                var pm = payments[j];
                paymentsHtml += '<li class="list-group-item d-flex justify-content-between align-items-center py-2">';
                paymentsHtml += '<div><span class="fw-semibold fs-12">' + pm.amount + '</span> <span class="text-muted fs-11">— ' + pm.caseId + '</span></div>';
                paymentsHtml += '<div><span class="badge bg-primary-subtle text-primary">' + pm.method + '</span> <span class="text-muted fs-11 ms-1">' + pm.date + '</span></div>';
                paymentsHtml += '</li>';
            }
            paymentsHtml += '</ul>';
        } else {
            paymentsHtml = '<div class="p-3 text-muted text-center fs-13">No payments found</div>';
        }
        document.getElementById('viewClientPayments').innerHTML = paymentsHtml;

        // Notes
        document.getElementById('viewClientNotes').textContent = d.notes || 'No notes';

        // Store current client id for cross-modal navigation
        document.getElementById('viewClientModal').dataset.activeClientId = d.clientId;

        new bootstrap.Modal(document.getElementById('viewClientModal')).show();
    }

    function formatDate(dateStr) {
        if (!dateStr) return '';
        var d = new Date(dateStr);
        var dd = String(d.getDate()).padStart(2, '0');
        var mm = String(d.getMonth() + 1).padStart(2, '0');
        return dd + '.' + mm + '.' + d.getFullYear();
    }

    // Click on client name
    table.addEventListener('click', function(e) {
        var link = e.target.closest('.client-view-link');
        if (!link) return;
        e.preventDefault();
        var row = getRow(link);
        if (row) openViewModal(row);
    });

    // Click on View Profile action
    table.addEventListener('click', function(e) {
        var link = e.target.closest('.action-view');
        if (!link) return;
        e.preventDefault();
        var row = getRow(link);
        if (row) openViewModal(row);
    });

    // View → Edit button
    document.getElementById('viewToEditBtn').addEventListener('click', function() {
        var cid = document.getElementById('viewClientModal').dataset.activeClientId;
        bootstrap.Modal.getInstance(document.getElementById('viewClientModal')).hide();
        setTimeout(function() {
            var row = table.querySelector('tr[data-client-id="' + cid + '"]');
            if (row) openEditModal(row);
        }, 300);
    });

    // ===== EDIT CLIENT =====
    function openEditModal(row) {
        var d = row.dataset;
        document.getElementById('editClientId').value = d.clientId;
        var parts = d.name.split(' ');
        document.getElementById('editClientFirstName').value = parts[0] || '';
        document.getElementById('editClientLastName').value = parts.slice(1).join(' ') || '';
        document.getElementById('editClientDob').value = d.dob || '';
        setSelectValue(document.getElementById('editClientNationality'), d.nationality);
        document.getElementById('editClientPassport').value = d.passport || '';
        document.getElementById('editClientPhone').value = d.phone;
        document.getElementById('editClientEmail').value = d.email;
        setSelectValue(document.getElementById('editClientType'), d.type);
        setSelectValue(document.getElementById('editClientSource'), d.source);
        setSelectValue(document.getElementById('editClientLanguage'), d.language);
        document.getElementById('editClientPesel').value = d.pesel || '';
        document.getElementById('editClientCity').value = d.city || '';
        setSelectValue(document.getElementById('editClientManager'), d.manager);
        document.getElementById('editClientAddress').value = d.address || '';
        new bootstrap.Modal(document.getElementById('editClientModal')).show();
    }

    table.addEventListener('click', function(e) {
        var link = e.target.closest('.action-edit');
        if (!link) return;
        e.preventDefault();
        var row = getRow(link);
        if (row) openEditModal(row);
    });

    document.getElementById('saveEditClientBtn').addEventListener('click', function() {
        var id = document.getElementById('editClientId').value;
        var row = table.querySelector('tr[data-client-id="' + id + '"]');
        if (!row) return;

        var fn = document.getElementById('editClientFirstName').value.trim();
        var ln = document.getElementById('editClientLastName').value.trim();
        var fullName = fn + ' ' + ln;
        var phone = document.getElementById('editClientPhone').value.trim();
        var email = document.getElementById('editClientEmail').value.trim();
        var nationality = document.getElementById('editClientNationality').value;
        var type = document.getElementById('editClientType').value;
        var source = document.getElementById('editClientSource').value;
        var manager = document.getElementById('editClientManager').value;
        var dob = document.getElementById('editClientDob').value;
        var passport = document.getElementById('editClientPassport').value;
        var pesel = document.getElementById('editClientPesel').value;
        var city = document.getElementById('editClientCity').value;
        var address = document.getElementById('editClientAddress').value;
        var language = document.getElementById('editClientLanguage').value;

        // Update data attributes
        row.dataset.name = fullName;
        row.dataset.phone = phone;
        row.dataset.email = email;
        row.dataset.nationality = nationality;
        row.dataset.type = type;
        row.dataset.source = source;
        row.dataset.manager = manager;
        row.dataset.dob = dob;
        row.dataset.passport = passport;
        row.dataset.pesel = pesel;
        row.dataset.city = city;
        row.dataset.address = address;
        row.dataset.language = language;

        // Update visible cells
        var cells = row.querySelectorAll('td');
        // [0]=checkbox, [1]=client, [2]=phone, [3]=email, [4]=type, [5]=source, [6]=cases, [7]=status, [8]=manager, [9]=registered, [10]=actions
        var initials = getInitials(fullName);
        var natColor = NATIONALITY_COLORS[nationality] || 'dark';
        cells[1].innerHTML = '<div class="d-flex align-items-center gap-2"><div class="avatar avatar-xs avatar-rounded bg-primary-subtle text-primary">' + initials + '</div><div><a href="#" class="fw-semibold text-body d-block client-view-link">' + fullName + '</a><span class="badge bg-' + natColor + '-subtle text-' + natColor + ' fs-10">' + nationality + '</span></div></div>';
        cells[2].innerHTML = '<a href="tel:' + phone.replace(/\s/g, '') + '" class="text-body">' + phone + '</a>';
        cells[3].innerHTML = '<a href="mailto:' + email + '" class="text-muted fs-12">' + email + '</a>';
        cells[4].innerHTML = getTypeBadge(type);
        cells[5].innerHTML = getSourceBadge(source);
        cells[8].textContent = manager;

        bootstrap.Modal.getInstance(document.getElementById('editClientModal')).hide();
        showToast('<i class="ri-check-line me-1"></i> Client updated successfully', 'success');
    });

    // ===== ARCHIVE =====
    table.addEventListener('click', function(e) {
        var link = e.target.closest('.action-archive');
        if (!link) return;
        e.preventDefault();
        var row = getRow(link);
        if (!row) return;
        document.getElementById('archiveClientId').value = row.dataset.clientId;
        document.getElementById('archiveClientName').textContent = row.dataset.name;
        new bootstrap.Modal(document.getElementById('archiveClientModal')).show();
    });

    document.getElementById('confirmArchiveBtn').addEventListener('click', function() {
        var id = document.getElementById('archiveClientId').value;
        var row = table.querySelector('tr[data-client-id="' + id + '"]');
        if (row) {
            // Update status cell
            var cells = row.querySelectorAll('td');
            cells[7].innerHTML = '<span class="badge bg-secondary-subtle text-secondary">Archived</span>';
            row.dataset.status = 'Archived';

            // Switch Archive to Restore in dropdown
            var archiveLink = row.querySelector('.action-archive');
            if (archiveLink) {
                archiveLink.className = 'dropdown-item text-success action-restore';
                archiveLink.innerHTML = '<i class="ri-inbox-unarchive-line me-2"></i>Restore';
            }
        }
        bootstrap.Modal.getInstance(document.getElementById('archiveClientModal')).hide();
        showToast('<i class="ri-archive-line me-1"></i> Client archived', 'warning');
    });

    // ===== RESTORE =====
    table.addEventListener('click', function(e) {
        var link = e.target.closest('.action-restore');
        if (!link) return;
        e.preventDefault();
        var row = getRow(link);
        if (!row) return;

        // Update status cell
        var cells = row.querySelectorAll('td');
        cells[7].innerHTML = '<span class="badge bg-success-subtle text-success">Active</span>';
        row.dataset.status = 'Active';

        // Switch Restore back to Archive in dropdown
        link.className = 'dropdown-item text-warning action-archive';
        link.innerHTML = '<i class="ri-archive-line me-2"></i>Archive';

        showToast('<i class="ri-inbox-unarchive-line me-1"></i> Client restored', 'success');
    });

    // ===== SELECT ALL =====
    document.getElementById('selectAllClients').addEventListener('change', function() {
        var checks = table.querySelectorAll('.row-check');
        for (var i = 0; i < checks.length; i++) { checks[i].checked = this.checked; }
    });
});
</script>
@endsection
