@extends('partials.Layouts.master')
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
                        <img src="{{ asset('assets/images/avatar/yulia.png') }}" alt="avatar" class="img-fluid rounded-circle">
                        <span class="position-absolute bottom-0 end-0 border-2 border border-white h-16px w-16px rounded-circle bg-success"></span>
                    </div>
                </div>
                <h5 class="mb-1">Yulia Biletska</h5>
                <p class="text-muted mb-3">CEO — WinCase Immigration Bureau</p>
                <div class="d-flex justify-content-center gap-2 mb-3">
                    <span class="badge bg-primary-subtle text-primary">Admin</span>
                    <span class="badge bg-success-subtle text-success">Active</span>
                </div>
                <div class="text-start">
                    <div class="d-flex align-items-center mb-3">
                        <i class="ri-mail-line text-muted me-2 fs-16"></i>
                        <span class="text-muted">yulia@wincase.eu</span>
                    </div>
                    <div class="d-flex align-items-center mb-3">
                        <i class="ri-phone-line text-muted me-2 fs-16"></i>
                        <span class="text-muted">+48 XXX XXX XXX</span>
                    </div>
                    <div class="d-flex align-items-center mb-3">
                        <i class="ri-map-pin-line text-muted me-2 fs-16"></i>
                        <span class="text-muted">Warsaw, Poland</span>
                    </div>
                    <div class="d-flex align-items-center">
                        <i class="ri-calendar-line text-muted me-2 fs-16"></i>
                        <span class="text-muted">Joined: Jan 2024</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Skills / Specializations -->
        <div class="card">
            <div class="card-header">
                <h6 class="card-title mb-0">Specializations</h6>
            </div>
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
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="avatar-sm flex-shrink-0">
                                <span class="avatar-title bg-primary-subtle text-primary rounded">
                                    <i class="ri-briefcase-line fs-20"></i>
                                </span>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <p class="text-muted mb-1 fs-12">Active Cases</p>
                                <h4 class="mb-0">47</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="avatar-sm flex-shrink-0">
                                <span class="avatar-title bg-success-subtle text-success rounded">
                                    <i class="ri-checkbox-circle-line fs-20"></i>
                                </span>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <p class="text-muted mb-1 fs-12">Completed</p>
                                <h4 class="mb-0">234</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="avatar-sm flex-shrink-0">
                                <span class="avatar-title bg-warning-subtle text-warning rounded">
                                    <i class="ri-user-follow-line fs-20"></i>
                                </span>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <p class="text-muted mb-1 fs-12">Clients</p>
                                <h4 class="mb-0">189</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="avatar-sm flex-shrink-0">
                                <span class="avatar-title bg-info-subtle text-info rounded">
                                    <i class="ri-star-line fs-20"></i>
                                </span>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <p class="text-muted mb-1 fs-12">Success Rate</p>
                                <h4 class="mb-0">96%</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="card">
            <div class="card-header">
                <h6 class="card-title mb-0">Recent Activity</h6>
            </div>
            <div class="card-body">
                <div class="acitivity-timeline acitivity-main">
                    <div class="acitivity-item d-flex pb-3">
                        <div class="flex-shrink-0 avatar-xs acitivity-avatar">
                            <div class="avatar-title bg-success-subtle text-success rounded-circle">
                                <i class="ri-checkbox-circle-line"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-1">Case #1247 Approved</h6>
                            <p class="text-muted mb-1">Temporary Residence Permit for Petro Shevchenko</p>
                            <small class="text-muted">2 hours ago</small>
                        </div>
                    </div>
                    <div class="acitivity-item d-flex pb-3">
                        <div class="flex-shrink-0 avatar-xs acitivity-avatar">
                            <div class="avatar-title bg-primary-subtle text-primary rounded-circle">
                                <i class="ri-user-add-line"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-1">New Lead Assigned</h6>
                            <p class="text-muted mb-1">Anna Kowalska — Work Permit application</p>
                            <small class="text-muted">5 hours ago</small>
                        </div>
                    </div>
                    <div class="acitivity-item d-flex pb-3">
                        <div class="flex-shrink-0 avatar-xs acitivity-avatar">
                            <div class="avatar-title bg-warning-subtle text-warning rounded-circle">
                                <i class="ri-file-upload-line"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-1">Document Uploaded</h6>
                            <p class="text-muted mb-1">Passport scan for Case #1250</p>
                            <small class="text-muted">Yesterday</small>
                        </div>
                    </div>
                    <div class="acitivity-item d-flex">
                        <div class="flex-shrink-0 avatar-xs acitivity-avatar">
                            <div class="avatar-title bg-info-subtle text-info rounded-circle">
                                <i class="ri-money-dollar-circle-line"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-1">Invoice #INV-2024-089 Paid</h6>
                            <p class="text-muted mb-1">Payment received — 3,500 PLN</p>
                            <small class="text-muted">2 days ago</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
