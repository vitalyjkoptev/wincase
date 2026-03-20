@extends('partials.layouts.master')

@section('title', 'Sales Quota | WinCase CRM')
@section('sub-title', 'Sales Quota')
@section('sub-title-lang', 'wc-sales-quota')
@section('pagetitle', 'Analytics')
@section('pagetitle-lang', 'wc-analytics')

@section('content')
<div class="row">
    <!-- Period Selector -->
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <h5 class="card-title mb-0">Monthly Sales Quota</h5>
                    <div class="d-flex gap-2">
                        <select class="form-select form-select-sm" style="width: 150px;">
                            <option>March 2026</option>
                            <option>February 2026</option>
                            <option>January 2026</option>
                            <option>December 2025</option>
                        </select>
                        <button class="btn btn-sm btn-primary"><i class="ri-refresh-line"></i></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Total Sales Stats -->
<div class="row">
    <div class="col-xl-3 col-md-6">
        <div class="card card-h-100">
            <div class="card-body">
                <div class="d-flex align-items-center gap-3">
                    <div class="avatar avatar-sm bg-primary-subtle text-primary rounded-2">
                        <i class="ri-money-dollar-circle-line fs-18"></i>
                    </div>
                    <div>
                        <p class="text-muted mb-0 fs-13">Total Sales (Month)</p>
                        <h4 class="mb-0 fw-semibold">PLN 52,400</h4>
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
                        <i class="ri-user-follow-line fs-18"></i>
                    </div>
                    <div>
                        <p class="text-muted mb-0 fs-13">New Cases (Month)</p>
                        <h4 class="mb-0 fw-semibold">18</h4>
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
                        <i class="ri-bar-chart-line fs-18"></i>
                    </div>
                    <div>
                        <p class="text-muted mb-0 fs-13">Monthly Target</p>
                        <h4 class="mb-0 fw-semibold">PLN 80,000</h4>
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
                        <i class="ri-percent-line fs-18"></i>
                    </div>
                    <div>
                        <p class="text-muted mb-0 fs-13">Quota Completion</p>
                        <h4 class="mb-0 fw-semibold">65.5%</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Per-Manager Sales Quota -->
<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">Sales by Manager — March 2026</h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Manager</th>
                        <th>Monthly Quota</th>
                        <th>Sales Amount</th>
                        <th>Cases Sold</th>
                        <th>Quota %</th>
                        <th>Progress</th>
                        <th>Avg. Deal</th>
                        <th>Conversion</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="avatar avatar-xs avatar-rounded bg-primary-subtle text-primary">JN</div>
                                <span class="fw-semibold">Jan Nowak</span>
                            </div>
                        </td>
                        <td>PLN 30,000</td>
                        <td class="fw-semibold text-success">PLN 24,200</td>
                        <td><span class="badge bg-primary">8</span></td>
                        <td class="fw-semibold">80.7%</td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="progress flex-grow-1" style="height: 8px; width: 120px;">
                                    <div class="progress-bar bg-success" style="width: 80.7%"></div>
                                </div>
                            </div>
                        </td>
                        <td class="text-muted">PLN 3,025</td>
                        <td><span class="badge bg-success-subtle text-success">72%</span></td>
                    </tr>
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="avatar avatar-xs avatar-rounded bg-success-subtle text-success">AW</div>
                                <span class="fw-semibold">Anna Wiśniewska</span>
                            </div>
                        </td>
                        <td>PLN 30,000</td>
                        <td class="fw-semibold text-success">PLN 19,800</td>
                        <td><span class="badge bg-primary">7</span></td>
                        <td class="fw-semibold">66.0%</td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="progress flex-grow-1" style="height: 8px; width: 120px;">
                                    <div class="progress-bar bg-warning" style="width: 66%"></div>
                                </div>
                            </div>
                        </td>
                        <td class="text-muted">PLN 2,829</td>
                        <td><span class="badge bg-warning-subtle text-warning">58%</span></td>
                    </tr>
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="avatar avatar-xs avatar-rounded bg-warning-subtle text-warning">PK</div>
                                <span class="fw-semibold">Piotr Kowalczyk</span>
                            </div>
                        </td>
                        <td>PLN 20,000</td>
                        <td class="fw-semibold text-warning">PLN 8,400</td>
                        <td><span class="badge bg-primary">3</span></td>
                        <td class="fw-semibold text-danger">42.0%</td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="progress flex-grow-1" style="height: 8px; width: 120px;">
                                    <div class="progress-bar bg-danger" style="width: 42%"></div>
                                </div>
                            </div>
                        </td>
                        <td class="text-muted">PLN 2,800</td>
                        <td><span class="badge bg-danger-subtle text-danger">35%</span></td>
                    </tr>
                </tbody>
                <tfoot class="table-light">
                    <tr>
                        <td class="fw-semibold">TOTAL</td>
                        <td class="fw-semibold">PLN 80,000</td>
                        <td class="fw-semibold text-success">PLN 52,400</td>
                        <td><span class="badge bg-primary">18</span></td>
                        <td class="fw-semibold">65.5%</td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="progress flex-grow-1" style="height: 8px; width: 120px;">
                                    <div class="progress-bar bg-primary" style="width: 65.5%"></div>
                                </div>
                            </div>
                        </td>
                        <td class="fw-semibold">PLN 2,911</td>
                        <td>—</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

<!-- Detailed Sales List -->
<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">Sales This Month — Detailed</h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Date</th>
                        <th>Client</th>
                        <th>Case #</th>
                        <th>Case Type</th>
                        <th>Contract Amount</th>
                        <th>Sold By</th>
                        <th>Source</th>
                        <th>Payment Status</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="text-muted fs-12">Mar 1</td>
                        <td class="fw-semibold">Anna Kowalska</td>
                        <td><a href="#" class="text-primary">WC-2026-0165</a></td>
                        <td><span class="badge bg-primary-subtle text-primary">Temp. Residence</span></td>
                        <td class="fw-semibold">PLN 4,800</td>
                        <td>
                            <div class="d-flex align-items-center gap-1">
                                <div class="avatar avatar-xs avatar-rounded bg-primary-subtle text-primary">JN</div>
                                <span>Jan Nowak</span>
                            </div>
                        </td>
                        <td><span class="badge bg-danger-subtle text-danger">Instagram</span></td>
                        <td><span class="badge bg-warning-subtle text-warning">Partially Paid</span></td>
                    </tr>
                    <tr>
                        <td class="text-muted fs-12">Mar 1</td>
                        <td class="fw-semibold">Dmytro Boyko</td>
                        <td><a href="#" class="text-primary">WC-2026-0164</a></td>
                        <td><span class="badge bg-success-subtle text-success">Citizenship</span></td>
                        <td class="fw-semibold">PLN 7,000</td>
                        <td>
                            <div class="d-flex align-items-center gap-1">
                                <div class="avatar avatar-xs avatar-rounded bg-success-subtle text-success">AW</div>
                                <span>Anna Wiśniewska</span>
                            </div>
                        </td>
                        <td><span class="badge bg-primary-subtle text-primary">Google</span></td>
                        <td><span class="badge bg-success-subtle text-success">Fully Paid</span></td>
                    </tr>
                    <tr>
                        <td class="text-muted fs-12">Feb 28</td>
                        <td class="fw-semibold">Igor Bondarenko</td>
                        <td><a href="#" class="text-primary">WC-2026-0163</a></td>
                        <td><span class="badge bg-warning-subtle text-warning">Speedup</span></td>
                        <td class="fw-semibold">PLN 3,000</td>
                        <td>
                            <div class="d-flex align-items-center gap-1">
                                <div class="avatar avatar-xs avatar-rounded bg-primary-subtle text-primary">JN</div>
                                <span>Jan Nowak</span>
                            </div>
                        </td>
                        <td><span class="badge bg-info-subtle text-info">TikTok</span></td>
                        <td><span class="badge bg-danger-subtle text-danger">Unpaid</span></td>
                    </tr>
                    <tr>
                        <td class="text-muted fs-12">Feb 27</td>
                        <td class="fw-semibold">Natalia Kravchuk</td>
                        <td><a href="#" class="text-primary">WC-2026-0162</a></td>
                        <td><span class="badge bg-info-subtle text-info">Perm. Residence</span></td>
                        <td class="fw-semibold">PLN 5,500</td>
                        <td>
                            <div class="d-flex align-items-center gap-1">
                                <div class="avatar avatar-xs avatar-rounded bg-warning-subtle text-warning">PK</div>
                                <span>Piotr Kowalczyk</span>
                            </div>
                        </td>
                        <td><span class="badge bg-warning-subtle text-warning">Referral</span></td>
                        <td><span class="badge bg-success-subtle text-success">Fully Paid</span></td>
                    </tr>
                    <tr>
                        <td class="text-muted fs-12">Feb 26</td>
                        <td class="fw-semibold">Andriy Melnyk</td>
                        <td><a href="#" class="text-primary">WC-2026-0161</a></td>
                        <td><span class="badge bg-danger-subtle text-danger">Appeal</span></td>
                        <td class="fw-semibold">PLN 4,000</td>
                        <td>
                            <div class="d-flex align-items-center gap-1">
                                <div class="avatar avatar-xs avatar-rounded bg-success-subtle text-success">AW</div>
                                <span>Anna Wiśniewska</span>
                            </div>
                        </td>
                        <td><span class="badge bg-secondary-subtle text-secondary">Meta Ads</span></td>
                        <td><span class="badge bg-warning-subtle text-warning">Partially Paid</span></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer">
        <div class="d-flex align-items-center justify-content-between">
            <div class="text-muted fs-13">Showing 1-5 of 18 sales this month</div>
            <nav>
                <ul class="pagination pagination-sm mb-0">
                    <li class="page-item disabled"><a class="page-link" href="#">Previous</a></li>
                    <li class="page-item active"><a class="page-link" href="#">1</a></li>
                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                    <li class="page-item"><a class="page-link" href="#">4</a></li>
                    <li class="page-item"><a class="page-link" href="#">Next</a></li>
                </ul>
            </nav>
        </div>
    </div>
</div>
@endsection
