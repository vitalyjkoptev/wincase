@extends('partials.layouts.master')
@section('title', 'Audit Log | WinCase CRM')
@section('sub-title', 'Audit Log')
@section('sub-title-lang', 'wc-audit-log')
@section('pagetitle', 'Admin')
@section('pagetitle-lang', 'wc-admin')
@section('content')
<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr><th>Time</th><th>User</th><th>Action</th><th>Entity</th><th>Details</th><th>IP</th></tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="text-muted fs-12">Feb 24, 10:32</td>
                        <td>Jan Nowak</td>
                        <td><span class="badge bg-success-subtle text-success">create</span></td>
                        <td>Lead</td>
                        <td class="text-muted fs-12">Created lead: Anna Kowalska</td>
                        <td class="text-muted fs-12">192.168.1.10</td>
                    </tr>
                    <tr>
                        <td class="text-muted fs-12">Feb 24, 10:15</td>
                        <td>Anna Wiśniewska</td>
                        <td><span class="badge bg-warning-subtle text-warning">update</span></td>
                        <td>Case #WC-0188</td>
                        <td class="text-muted fs-12">Status: new → documents</td>
                        <td class="text-muted fs-12">192.168.1.15</td>
                    </tr>
                    <tr>
                        <td class="text-muted fs-12">Feb 24, 09:30</td>
                        <td>System</td>
                        <td><span class="badge bg-primary-subtle text-primary">payment</span></td>
                        <td>POS #TXN-0892</td>
                        <td class="text-muted fs-12">Payment $1,200 received from Petrov</td>
                        <td class="text-muted fs-12">—</td>
                    </tr>
                    <tr>
                        <td class="text-muted fs-12">Feb 24, 09:15</td>
                        <td>Jan Nowak</td>
                        <td><span class="badge bg-info-subtle text-info">login</span></td>
                        <td>User</td>
                        <td class="text-muted fs-12">Logged in successfully</td>
                        <td class="text-muted fs-12">192.168.1.10</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
