@extends('partials.layouts.master-employee')
@section('title', 'Boss Chat — WinCase Staff')
@section('page-title', 'Boss Chat')

@section('css')
<style>
    .boss-chat-container { height: calc(100vh - 200px); display: flex; flex-direction: column; }
    .boss-messages { flex: 1; overflow-y: auto; padding: 1.5rem; }
    .boss-bubble { max-width: 75%; padding: .75rem 1rem; border-radius: 1rem; margin-bottom: .75rem; font-size: .875rem; line-height: 1.5; }
    .boss-bubble.sent { background: #015EA7; color: #fff; margin-left: auto; border-bottom-right-radius: .25rem; }
    .boss-bubble.received { background: #f0f2f5; border-bottom-left-radius: .25rem; }
    [data-bs-theme="dark"] .boss-bubble.received { background: #2a2d35; color: #e0e0e0; }
    .boss-bubble .time { font-size: .65rem; opacity: .7; margin-top: .25rem; }
    .boss-input-area { border-top: 1px solid rgba(0,0,0,.08); padding: 1rem; }
    [data-bs-theme="dark"] .boss-input-area { border-color: rgba(255,255,255,.06); }
    .pinned-msg { background: rgba(1,94,167,.05); border-left: 3px solid #015EA7; padding: .75rem 1rem; font-size: .8rem; }
</style>
@endsection

@section('content')
<div class="row g-3">
    <div class="col-lg-8">
        <div class="card mb-0">
            <!-- Header -->
            <div class="card-header d-flex align-items-center gap-3">
                <div class="rounded-circle bg-dark bg-opacity-10 d-flex align-items-center justify-content-center" style="width:40px;height:40px;">
                    <i class="ri-shield-star-line fs-5 text-success"></i>
                </div>
                <div>
                    <div class="fw-semibold">Dmitry Sokolov <span class="badge bg-success-subtle text-success ms-1" style="font-size:.6rem;">BOSS</span></div>
                    <small class="text-muted"><span class="text-success"><i class="ri-checkbox-blank-circle-fill" style="font-size:.5rem;"></i></span> Online &bull; Director</small>
                </div>
                <div class="ms-auto">
                    <span class="badge bg-danger-subtle text-danger"><i class="ri-lock-line me-1"></i><span data-lang="wc-staff-private-channel">Private Channel</span></span>
                </div>
            </div>

            <!-- Chat -->
            <div class="boss-chat-container">
                <!-- Pinned Message -->
                <div class="pinned-msg">
                    <i class="ri-pushpin-line text-success me-1"></i>
                    <strong>Pinned:</strong> Monthly report deadline is every last Friday. Submit case statistics by 17:00.
                </div>

                <div class="boss-messages" id="bossMessages">
                    <div class="text-center mb-3"><small class="badge bg-light text-muted">Today, March 2, 2026</small></div>

                    <div class="boss-bubble received">
                        Good morning, Anna. How is the Kovalenko case going? We need to make sure the bank statement arrives on time.
                        <div class="time">08:30</div>
                    </div>
                    <div class="boss-bubble sent">
                        Good morning! I contacted Olena yesterday. She promised to send the February statement today. The deadline is March 5, so we have time.
                        <div class="time">08:35</div>
                    </div>
                    <div class="boss-bubble received">
                        Good. What about Chen Wei's case? I noticed the translated birth certificate is overdue.
                        <div class="time">08:40</div>
                    </div>
                    <div class="boss-bubble sent">
                        Yes, I sent a reminder on Friday. Chen Wei said the translator needs 2 more days. Should be ready by March 4.
                        <div class="time">08:45</div>
                    </div>
                    <div class="boss-bubble received">
                        OK. Also, I want to discuss the Bondarenko fingerprint appointment. Make sure we have all the documents prepared before March 8. Let's do a quick review tomorrow at 10:00.
                        <div class="time">09:00</div>
                    </div>
                    <div class="boss-bubble sent">
                        Understood. I'll prepare the checklist for the Bondarenko case today. See you tomorrow at 10:00.
                        <div class="time">09:05</div>
                    </div>
                    <div class="boss-bubble received">
                        One more thing — we have a new client inquiry for a Blue Card application. I'll forward you the details after lunch. Please review and create a preliminary checklist.
                        <div class="time">11:15</div>
                    </div>
                </div>

                <!-- Input -->
                <div class="boss-input-area">
                    <div class="input-group">
                        <button class="btn btn-light" type="button" title="Attach file"><i class="ri-attachment-line"></i></button>
                        <input type="text" class="form-control" placeholder="Type a message to Boss..." id="bossInput">
                        <button class="btn btn-success" type="button" onclick="sendBossMsg()"><i class="ri-send-plane-line"></i></button>
                    </div>
                    <div class="d-flex gap-2 mt-2">
                        <button class="btn btn-sm btn-outline-secondary"><i class="ri-checkbox-circle-line me-1"></i><span data-lang="wc-staff-request-approval">Request Approval</span></button>
                        <button class="btn btn-sm btn-outline-secondary"><i class="ri-file-list-3-line me-1"></i><span data-lang="wc-staff-send-report">Send Report</span></button>
                        <button class="btn btn-sm btn-outline-secondary"><i class="ri-calendar-check-line me-1"></i><span data-lang="wc-staff-schedule-meeting">Schedule Meeting</span></button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="col-lg-4">
        <!-- Quick Reports -->
        <div class="card mb-3">
            <div class="card-header">
                <h6 class="mb-0"><i class="ri-bar-chart-2-line text-success me-1"></i><span data-lang="wc-staff-quick-reports">Quick Reports to Boss</span></h6>
            </div>
            <div class="list-group list-group-flush">
                <a href="#" class="list-group-item list-group-item-action d-flex align-items-center gap-2">
                    <i class="ri-file-chart-line text-primary"></i>
                    <div class="flex-grow-1">
                        <div style="font-size:.85rem;">Weekly Case Summary</div>
                        <small class="text-muted">Auto-generated from your cases</small>
                    </div>
                    <i class="ri-arrow-right-s-line text-muted"></i>
                </a>
                <a href="#" class="list-group-item list-group-item-action d-flex align-items-center gap-2">
                    <i class="ri-task-line text-warning"></i>
                    <div class="flex-grow-1">
                        <div style="font-size:.85rem;">Task Completion Report</div>
                        <small class="text-muted">Tasks completed this week</small>
                    </div>
                    <i class="ri-arrow-right-s-line text-muted"></i>
                </a>
                <a href="#" class="list-group-item list-group-item-action d-flex align-items-center gap-2">
                    <i class="ri-error-warning-line text-danger"></i>
                    <div class="flex-grow-1">
                        <div style="font-size:.85rem;">Issues & Blockers</div>
                        <small class="text-muted">Pending issues requiring attention</small>
                    </div>
                    <i class="ri-arrow-right-s-line text-muted"></i>
                </a>
            </div>
        </div>

        <!-- Pending Approvals -->
        <div class="card mb-3">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="mb-0"><i class="ri-checkbox-circle-line text-warning me-1"></i><span data-lang="wc-staff-pending-approvals">Pending Approvals</span></h6>
                <span class="badge bg-warning rounded-pill">2</span>
            </div>
            <div class="list-group list-group-flush">
                <div class="list-group-item">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div style="font-size:.8rem;" class="fw-semibold">Application submission — Mehmet Yilmaz</div>
                            <small class="text-muted">Sent Feb 28 &bull; Case #WC-2026-0855</small>
                        </div>
                        <span class="badge bg-warning-subtle text-warning">Waiting</span>
                    </div>
                </div>
                <div class="list-group-item">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div style="font-size:.8rem;" class="fw-semibold">Case reassignment — Viktor Morozov</div>
                            <small class="text-muted">Sent Mar 1 &bull; Case #WC-2026-0790</small>
                        </div>
                        <span class="badge bg-warning-subtle text-warning">Waiting</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Shared Notes -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="mb-0"><i class="ri-sticky-note-line text-info me-1"></i><span data-lang="wc-staff-shared-notes">Shared Notes</span></h6>
                <button class="btn btn-sm btn-light"><i class="ri-add-line"></i></button>
            </div>
            <div class="card-body" style="font-size:.8rem;">
                <div class="mb-2 p-2 rounded" style="background:rgba(255,193,7,.08);">
                    <strong>Meeting tomorrow 10:00</strong><br>
                    Review Bondarenko case checklist before fingerprint appointment.
                    <div class="text-muted mt-1"><small>Boss — Mar 2, 09:00</small></div>
                </div>
                <div class="p-2 rounded" style="background:rgba(1,94,167,.05);">
                    <strong>New Blue Card inquiry</strong><br>
                    Details coming after lunch. Need preliminary checklist ASAP.
                    <div class="text-muted mt-1"><small>Boss — Mar 2, 11:15</small></div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
function sendBossMsg(){
    var input = document.getElementById('bossInput');
    if(!input.value.trim()) return;
    var area = document.getElementById('bossMessages');
    var div = document.createElement('div');
    div.className = 'boss-bubble sent';
    div.innerHTML = input.value + '<div class="time">' + new Date().toLocaleTimeString('en',{hour:'2-digit',minute:'2-digit'}) + '</div>';
    area.appendChild(div);
    input.value = '';
    area.scrollTop = area.scrollHeight;
}
document.getElementById('bossInput').addEventListener('keydown', function(e){ if(e.key==='Enter') sendBossMsg(); });
</script>
@endsection
