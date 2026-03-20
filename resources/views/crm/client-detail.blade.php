@extends('partials.layouts.master')

@section('title', 'Client Profile | WinCase CRM')
@section('sub-title', 'Client Detail')
@section('sub-title-lang', 'wc-client-detail')
@section('pagetitle', 'CRM')
@section('pagetitle-lang', 'wc-title-crm')

@section('content')
<div class="row">
    <!-- Client Info Card -->
    <div class="col-xl-4">
        <div class="card">
            <div class="card-body">
                <div class="text-center mb-3">
                    <div class="avatar avatar-lg avatar-rounded bg-primary-subtle text-primary mx-auto mb-2">
                        <span class="fs-24">OP</span>
                    </div>
                    <h5 class="mb-1">Oleksandr Petrov</h5>
                    <span class="badge bg-success-subtle text-success">Active</span>
                    <span class="badge bg-info-subtle text-info">Ukrainian</span>
                </div>

                <div class="table-responsive">
                    <table class="table table-borderless table-sm mb-0">
                        <tbody>
                            <tr>
                                <td class="text-muted fw-medium" style="width: 40%">First Name</td>
                                <td>Oleksandr</td>
                            </tr>
                            <tr>
                                <td class="text-muted fw-medium">Last Name</td>
                                <td>Petrov</td>
                            </tr>
                            <tr>
                                <td class="text-muted fw-medium">Date of Birth</td>
                                <td>15.03.1990</td>
                            </tr>
                            <tr>
                                <td class="text-muted fw-medium">Citizenship</td>
                                <td>Ukrainian</td>
                            </tr>
                            <tr>
                                <td class="text-muted fw-medium">Passport Number</td>
                                <td>FE 123456</td>
                            </tr>
                            <tr>
                                <td class="text-muted fw-medium">Phone</td>
                                <td><a href="tel:+48512345678">+48 512 345 678</a></td>
                            </tr>
                            <tr>
                                <td class="text-muted fw-medium">Email</td>
                                <td><a href="mailto:petrov@email.com">petrov@email.com</a></td>
                            </tr>
                            <tr>
                                <td class="text-muted fw-medium">Address</td>
                                <td>ul. Marszałkowska 10/5, Warszawa</td>
                            </tr>
                            <tr>
                                <td class="text-muted fw-medium">PESEL</td>
                                <td>90031512345</td>
                            </tr>
                            <tr>
                                <td class="text-muted fw-medium">Client Type</td>
                                <td><span class="badge bg-primary-subtle text-primary">New Client</span></td>
                            </tr>
                            <tr>
                                <td class="text-muted fw-medium">Source</td>
                                <td><span class="badge bg-danger-subtle text-danger">Instagram</span></td>
                            </tr>
                            <tr>
                                <td class="text-muted fw-medium">Manager</td>
                                <td>Jan Nowak</td>
                            </tr>
                            <tr>
                                <td class="text-muted fw-medium">Registered</td>
                                <td>Jan 15, 2026</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="d-flex gap-2 mt-3">
                    <button class="btn btn-primary btn-sm flex-fill"><i class="ri-edit-line me-1"></i>Edit</button>
                    <button class="btn btn-subtle-secondary btn-sm flex-fill"><i class="ri-file-text-line me-1"></i>Documents</button>
                </div>
            </div>
        </div>

        <!-- Finance Summary -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Finance Summary</h5>
            </div>
            <div class="card-body p-0">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex justify-content-between">
                        <span class="text-muted">Total Contract Value</span>
                        <span class="fw-semibold">PLN 14,800.00</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between">
                        <span class="text-muted">Total Paid</span>
                        <span class="fw-semibold text-success">PLN 9,600.00</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between">
                        <span class="text-muted">Outstanding Debt</span>
                        <span class="fw-semibold text-danger">PLN 5,200.00</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Cases & Activity -->
    <div class="col-xl-8">
        <!-- Active Cases -->
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h5 class="card-title mb-0">Cases (3)</h5>
                <button class="btn btn-primary btn-sm"><i class="ri-add-line me-1"></i>New Case</button>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Case #</th>
                                <th>Case Type</th>
                                <th>Status</th>
                                <th>City</th>
                                <th>Contract</th>
                                <th>Paid</th>
                                <th>Debt</th>
                                <th>Key Dates</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><a href="#" class="fw-semibold text-primary">WC-2026-0147</a></td>
                                <td><span class="badge bg-primary-subtle text-primary">Temp. Residence</span></td>
                                <td><span class="badge bg-warning-subtle text-warning">Awaiting Fingerprints</span></td>
                                <td>Warszawa</td>
                                <td class="fw-semibold">PLN 4,800</td>
                                <td class="text-success">PLN 4,800</td>
                                <td class="text-muted">—</td>
                                <td>
                                    <div class="fs-11 text-muted">
                                        <div>Submitted: 20.01.2026</div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td><a href="#" class="fw-semibold text-primary">WC-2026-0155</a></td>
                                <td><span class="badge bg-success-subtle text-success">Speedup</span></td>
                                <td><span class="badge bg-primary-subtle text-primary">Submitted to Office</span></td>
                                <td>Warszawa</td>
                                <td class="fw-semibold">PLN 3,000</td>
                                <td class="text-success">PLN 1,500</td>
                                <td class="text-danger fw-semibold">PLN 1,500</td>
                                <td>
                                    <div class="fs-11 text-muted">
                                        <div>Submitted: 25.02.2026</div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td><a href="#" class="fw-semibold text-primary">WC-2026-0160</a></td>
                                <td><span class="badge bg-info-subtle text-info">Citizenship</span></td>
                                <td><span class="badge bg-info-subtle text-info">Fingerprints Submitted</span></td>
                                <td>Warszawa</td>
                                <td class="fw-semibold">PLN 7,000</td>
                                <td class="text-success">PLN 3,300</td>
                                <td class="text-danger fw-semibold">PLN 3,700</td>
                                <td>
                                    <div class="fs-11 text-muted">
                                        <div>Submitted: 10.12.2025</div>
                                        <div>Fingerprints: 15.01.2026</div>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Upcoming Tasks -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Upcoming Tasks</h5>
            </div>
            <div class="card-body p-0">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item">
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center gap-2">
                                <span class="badge bg-danger">&nbsp;</span>
                                <div>
                                    <h6 class="mb-0 fs-13">Call client — payment reminder</h6>
                                    <span class="text-muted fs-12">Case WC-2026-0155</span>
                                </div>
                            </div>
                            <span class="text-danger fw-semibold fs-12">Today</span>
                        </div>
                    </li>
                    <li class="list-group-item">
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center gap-2">
                                <span class="badge bg-warning">&nbsp;</span>
                                <div>
                                    <h6 class="mb-0 fs-13">Request missing document</h6>
                                    <span class="text-muted fs-12">Case WC-2026-0160</span>
                                </div>
                            </div>
                            <span class="text-warning fw-semibold fs-12">Mar 3</span>
                        </div>
                    </li>
                    <li class="list-group-item">
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center gap-2">
                                <span class="badge bg-primary">&nbsp;</span>
                                <div>
                                    <h6 class="mb-0 fs-13">Check status at office</h6>
                                    <span class="text-muted fs-12">Case WC-2026-0147</span>
                                </div>
                            </div>
                            <span class="text-muted fs-12">Mar 5</span>
                        </div>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Payment History -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Payment History</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Date</th>
                                <th>Case</th>
                                <th>Amount</th>
                                <th>Method</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="text-muted fs-12">Feb 24, 2026</td>
                                <td><a href="#" class="text-primary fs-12">WC-2026-0147</a></td>
                                <td class="fw-semibold">PLN 4,800.00</td>
                                <td><span class="badge bg-primary-subtle text-primary">Bank Transfer</span></td>
                                <td><span class="badge bg-success-subtle text-success">Completed</span></td>
                            </tr>
                            <tr>
                                <td class="text-muted fs-12">Feb 25, 2026</td>
                                <td><a href="#" class="text-primary fs-12">WC-2026-0155</a></td>
                                <td class="fw-semibold">PLN 1,500.00</td>
                                <td><span class="badge bg-success-subtle text-success">Card</span></td>
                                <td><span class="badge bg-success-subtle text-success">Completed</span></td>
                            </tr>
                            <tr>
                                <td class="text-muted fs-12">Jan 10, 2026</td>
                                <td><a href="#" class="text-primary fs-12">WC-2026-0160</a></td>
                                <td class="fw-semibold">PLN 3,300.00</td>
                                <td><span class="badge bg-secondary-subtle text-secondary">Cash</span></td>
                                <td><span class="badge bg-success-subtle text-success">Completed</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
