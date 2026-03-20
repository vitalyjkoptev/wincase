@extends('partials.layouts.master')
@section('title', 'System | WinCase CRM')
@section('sub-title', 'System')
@section('sub-title-lang', 'wc-system')
@section('pagetitle', 'Admin')
@section('pagetitle-lang', 'wc-admin')
@section('content')
<div class="row">
    <div class="col-xl-4">
        <div class="card"><div class="card-header"><h5 class="card-title mb-0">System Health</h5></div>
        <div class="card-body">
            <div class="d-flex justify-content-between mb-3"><span>Database</span><span class="badge bg-success">OK</span></div>
            <div class="d-flex justify-content-between mb-3"><span>Cache (Redis)</span><span class="badge bg-success">OK</span></div>
            <div class="d-flex justify-content-between mb-3"><span>Queue Worker</span><span class="badge bg-success">OK</span></div>
            <div class="d-flex justify-content-between mb-3"><span>Storage</span><span class="badge bg-success">OK</span></div>
            <div class="d-flex justify-content-between mb-3"><span>n8n Workflows</span><span class="badge bg-success">OK</span></div>
            <div class="d-flex justify-content-between mb-3"><span>Mail Service</span><span class="badge bg-success">OK</span></div>
            <div class="d-flex justify-content-between"><span>Scheduler</span><span class="badge bg-success">OK</span></div>
        </div></div>
    </div>
    <div class="col-xl-4">
        <div class="card card-h-100"><div class="card-header"><h5 class="card-title mb-0">Server Info</h5></div>
        <div class="card-body">
            <div class="d-flex justify-content-between mb-2"><span class="text-muted">PHP</span><span>8.3.30</span></div>
            <div class="d-flex justify-content-between mb-2"><span class="text-muted">Laravel</span><span>12.x</span></div>
            <div class="d-flex justify-content-between mb-2"><span class="text-muted">MySQL</span><span>8.0</span></div>
            <div class="d-flex justify-content-between mb-2"><span class="text-muted">Redis</span><span>7.2</span></div>
            <div class="d-flex justify-content-between mb-2"><span class="text-muted">Disk</span><span>42% used</span></div>
            <div class="d-flex justify-content-between"><span class="text-muted">Memory</span><span>68% used</span></div>
        </div></div>
    </div>
    <div class="col-xl-4">
        <div class="card card-h-100"><div class="card-header"><h5 class="card-title mb-0">Quick Actions</h5></div>
        <div class="card-body d-flex flex-column gap-2">
            <button class="btn btn-subtle-primary text-start"><i class="ri-refresh-line me-2"></i>Clear Cache</button>
            <button class="btn btn-subtle-primary text-start"><i class="ri-speed-up-line me-2"></i>Optimize Cache</button>
            <button class="btn btn-subtle-warning text-start"><i class="ri-tools-line me-2"></i>Enable Maintenance Mode</button>
            <button class="btn btn-subtle-info text-start"><i class="ri-database-line me-2"></i>Run Migrations</button>
        </div></div>
    </div>
</div>
@endsection
