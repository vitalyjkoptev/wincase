@extends('partials.layouts.master-employee')
@section('title', 'Messages — WinCase Staff')
@section('page-title', 'Messages')

@section('css')
<style>
    .chat-sidebar { border-right: 1px solid rgba(0,0,0,.08); height: calc(100vh - 180px); overflow-y: auto; }
    [data-bs-theme="dark"] .chat-sidebar { border-color: rgba(255,255,255,.06); }
    .chat-contact { padding: .75rem 1rem; cursor: pointer; border-bottom: 1px solid rgba(0,0,0,.04); transition: background .15s; }
    .chat-contact:hover, .chat-contact.active { background: rgba(1,94,167,.06); }
    .chat-contact.active { border-left: 3px solid #015EA7; }
    .chat-avatar { width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: .8rem; flex-shrink: 0; }
    .chat-body { height: calc(100vh - 180px); display: flex; flex-direction: column; }
    .chat-messages { flex: 1; overflow-y: auto; padding: 1.5rem; }
    .chat-bubble { max-width: 70%; padding: .75rem 1rem; border-radius: 1rem; margin-bottom: .75rem; font-size: .875rem; line-height: 1.5; }
    .chat-bubble.sent { background: #015EA7; color: #fff; margin-left: auto; border-bottom-right-radius: .25rem; }
    .chat-bubble.received { background: #f0f2f5; border-bottom-left-radius: .25rem; }
    [data-bs-theme="dark"] .chat-bubble.received { background: #2a2d35; color: #e0e0e0; }
    .chat-bubble .time { font-size: .65rem; opacity: .7; margin-top: .25rem; }
    .chat-input-area { border-top: 1px solid rgba(0,0,0,.08); padding: 1rem; }
    .source-badge { font-size: .6rem; padding: 2px 6px; border-radius: 4px; font-weight: 600; }
</style>
@endsection

@section('content')
<!-- Tabs: Client Messages | Multichat -->
<ul class="nav nav-tabs mb-0" role="tablist">
    <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab" href="#tabClientMsg"><i class="ri-message-3-line me-1"></i><span data-lang="wc-staff-client-messages">Client Messages</span></a></li>
    <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#tabMultichat"><i class="ri-chat-history-line me-1"></i><span data-lang="wc-staff-multichat">Multichat</span> <span class="badge bg-info rounded-pill">All channels</span></a></li>
</ul>

<div class="tab-content">
    <!-- ============ TAB 1: Client Messages ============ -->
    <div class="tab-pane fade show active" id="tabClientMsg">
        <div class="card mb-0" style="border-top-left-radius:0;border-top-right-radius:0;">
            <div class="row g-0">
                <!-- Contact list -->
                <div class="col-md-4 col-lg-3 chat-sidebar">
                    <div class="p-2">
                        <input type="text" class="form-control form-control-sm" placeholder="Search contacts..." id="contactSearch" data-lang-placeholder="wc-staff-search-contacts">
                    </div>
                    <div class="chat-contact active" data-contact="olena">
                        <div class="d-flex align-items-center gap-2">
                            <div class="chat-avatar" style="background:rgba(1,94,167,.15);color:#015EA7;">OK</div>
                            <div class="flex-grow-1 min-width-0">
                                <div class="d-flex justify-content-between">
                                    <strong style="font-size:.85rem;">Olena Kovalenko</strong>
                                    <small class="text-muted">10:32</small>
                                </div>
                                <div class="text-muted text-truncate" style="font-size:.75rem;">Thank you, I will send the bank statement...</div>
                            </div>
                            <span class="badge bg-success rounded-pill">2</span>
                        </div>
                    </div>
                    <div class="chat-contact" data-contact="dmytro">
                        <div class="d-flex align-items-center gap-2">
                            <div class="chat-avatar" style="background:rgba(13,110,253,.15);color:#0d6efd;">DB</div>
                            <div class="flex-grow-1 min-width-0">
                                <div class="d-flex justify-content-between">
                                    <strong style="font-size:.85rem;">Dmytro Bondarenko</strong>
                                    <small class="text-muted">09:15</small>
                                </div>
                                <div class="text-muted text-truncate" style="font-size:.75rem;">When is my fingerprint appointment?</div>
                            </div>
                        </div>
                    </div>
                    <div class="chat-contact" data-contact="rahul">
                        <div class="d-flex align-items-center gap-2">
                            <div class="chat-avatar" style="background:rgba(255,87,34,.15);color:#ff5722;">RS</div>
                            <div class="flex-grow-1 min-width-0">
                                <div class="d-flex justify-content-between">
                                    <strong style="font-size:.85rem;">Rahul Sharma</strong>
                                    <small class="text-muted">Yesterday</small>
                                </div>
                                <div class="text-muted text-truncate" style="font-size:.75rem;">I uploaded the passport copy</div>
                            </div>
                        </div>
                    </div>
                    <div class="chat-contact" data-contact="irina">
                        <div class="d-flex align-items-center gap-2">
                            <div class="chat-avatar" style="background:rgba(156,39,176,.15);color:#9c27b0;">IK</div>
                            <div class="flex-grow-1 min-width-0">
                                <div class="d-flex justify-content-between">
                                    <strong style="font-size:.85rem;">Irina Kozlova</strong>
                                    <small class="text-muted">Feb 28</small>
                                </div>
                                <div class="text-muted text-truncate" style="font-size:.75rem;">Any news about the decision?</div>
                            </div>
                        </div>
                    </div>
                    <div class="chat-contact" data-contact="mehmet">
                        <div class="d-flex align-items-center gap-2">
                            <div class="chat-avatar" style="background:rgba(233,30,99,.15);color:#e91e63;">MY</div>
                            <div class="flex-grow-1 min-width-0">
                                <div class="d-flex justify-content-between">
                                    <strong style="font-size:.85rem;">Mehmet Yilmaz</strong>
                                    <small class="text-muted">Feb 27</small>
                                </div>
                                <div class="text-muted text-truncate" style="font-size:.75rem;">OK, I will come to the office tomorrow</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Chat area -->
                <div class="col-md-8 col-lg-9">
                    <div class="chat-body">
                        <!-- Chat header -->
                        <div class="d-flex align-items-center gap-3 p-3 border-bottom">
                            <div class="chat-avatar" style="background:rgba(1,94,167,.15);color:#015EA7;">OK</div>
                            <div>
                                <div class="fw-semibold">Olena Kovalenko</div>
                                <small class="text-muted">Case #WC-2026-0847 • Temporary Residence Permit</small>
                            </div>
                            <div class="ms-auto d-flex gap-2">
                                <button class="btn btn-sm btn-light" title="View client"><i class="ri-user-line"></i></button>
                                <button class="btn btn-sm btn-light" title="View case"><i class="ri-folder-line"></i></button>
                                <button class="btn btn-sm btn-light" title="Attach file"><i class="ri-attachment-line"></i></button>
                            </div>
                        </div>

                        <!-- Messages -->
                        <div class="chat-messages">
                            <div class="text-center mb-3"><small class="badge bg-light text-muted">Today, March 2</small></div>

                            <div class="chat-bubble received">
                                Hello! I wanted to ask about the bank statement for March — when do I need to submit it?
                                <div class="time">09:45</div>
                            </div>
                            <div class="chat-bubble sent">
                                Hi Olena! You need to submit the March bank statement by March 5. You can upload it through the client portal or bring it to the office.
                                <div class="time">09:52</div>
                            </div>
                            <div class="chat-bubble received">
                                OK, should it be from the beginning of March or the full month?
                                <div class="time">10:15</div>
                            </div>
                            <div class="chat-bubble sent">
                                Just the latest statement available — even from the end of February is fine. The main thing is to show regular income and the balance.
                                <div class="time">10:20</div>
                            </div>
                            <div class="chat-bubble received">
                                Thank you, I will send the bank statement tomorrow. Also, do you have any updates on the case decision?
                                <div class="time">10:32</div>
                            </div>
                        </div>

                        <!-- Input -->
                        <div class="chat-input-area">
                            <div class="input-group">
                                <button class="btn btn-light" type="button"><i class="ri-attachment-line"></i></button>
                                <input type="text" class="form-control" placeholder="Type a message..." id="msgInput">
                                <button class="btn btn-success" type="button" onclick="sendMsg()"><i class="ri-send-plane-line"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ============ TAB 2: Multichat (all channels) ============ -->
    <div class="tab-pane fade" id="tabMultichat">
        <div class="card" style="border-top-left-radius:0;border-top-right-radius:0;">
            <div class="card-header py-2">
                <div class="d-flex align-items-center gap-3">
                    <span class="fw-semibold">All Communications — Olena Kovalenko</span>
                    <div class="ms-auto d-flex gap-1">
                        <span class="source-badge" style="background:#25D366;color:#fff;">WhatsApp</span>
                        <span class="source-badge" style="background:#0088cc;color:#fff;">Telegram</span>
                        <span class="source-badge" style="background:#015EA7;color:#fff;">Portal</span>
                        <span class="source-badge" style="background:#6c757d;color:#fff;">Email</span>
                        <span class="source-badge" style="background:#0d6efd;color:#fff;">SMS</span>
                    </div>
                </div>
            </div>
            <div class="card-body" style="height:calc(100vh - 300px);overflow-y:auto;">
                <div class="text-center mb-3"><small class="badge bg-light text-muted">February 28, 2026</small></div>

                <div class="d-flex gap-2 mb-3">
                    <span class="source-badge" style="background:#0088cc;color:#fff;">TG</span>
                    <div class="chat-bubble received flex-grow-0">
                        Добрый день! Есть новости по моему делу?
                        <div class="time">14:22 • Telegram</div>
                    </div>
                </div>
                <div class="d-flex gap-2 mb-3 justify-content-end">
                    <div class="chat-bubble sent flex-grow-0">
                        Добрый день, Олена! Ваше дело на рассмотрении. Ждём решение в течение 2-3 недель.
                        <div class="time">14:35 • Portal</div>
                    </div>
                    <span class="source-badge" style="background:#015EA7;color:#fff;">WC</span>
                </div>

                <div class="text-center mb-3"><small class="badge bg-light text-muted">March 1, 2026</small></div>

                <div class="d-flex gap-2 mb-3">
                    <span class="source-badge" style="background:#6c757d;color:#fff;">EM</span>
                    <div class="chat-bubble received flex-grow-0">
                        <strong>Subject: Bank statement</strong><br>
                        Hello, attached is my bank statement for February. Please confirm you received it.
                        <div class="mt-1"><span class="badge bg-light text-dark"><i class="ri-attachment-line me-1"></i>bank_feb_2026.pdf</span></div>
                        <div class="time">11:05 • Email</div>
                    </div>
                </div>
                <div class="d-flex gap-2 mb-3 justify-content-end">
                    <div class="chat-bubble sent flex-grow-0">
                        Received, thank you! Document uploaded to your case file.
                        <div class="time">11:20 • Email reply</div>
                    </div>
                    <span class="source-badge" style="background:#6c757d;color:#fff;">EM</span>
                </div>

                <div class="text-center mb-3"><small class="badge bg-light text-muted">Today, March 2, 2026</small></div>

                <div class="d-flex gap-2 mb-3">
                    <span class="source-badge" style="background:#25D366;color:#fff;">WA</span>
                    <div class="chat-bubble received flex-grow-0">
                        Hi! Quick question — when do I need the March bank statement?
                        <div class="time">09:30 • WhatsApp</div>
                    </div>
                </div>
                <div class="d-flex gap-2 mb-3">
                    <span class="source-badge" style="background:#015EA7;color:#fff;">WC</span>
                    <div class="chat-bubble received flex-grow-0">
                        Hello! I wanted to ask about the bank statement for March — when do I need to submit it?
                        <div class="time">09:45 • Client Portal</div>
                    </div>
                </div>
                <div class="d-flex gap-2 mb-3 justify-content-end">
                    <div class="chat-bubble sent flex-grow-0">
                        By March 5. Upload through portal or bring to office.
                        <div class="time">09:52 • Portal reply</div>
                    </div>
                    <span class="source-badge" style="background:#015EA7;color:#fff;">WC</span>
                </div>
                <div class="d-flex gap-2 mb-3">
                    <span class="source-badge" style="background:#0d6efd;color:#fff;">SMS</span>
                    <div class="chat-bubble received flex-grow-0">
                        OK thx I'll upload tomorrow
                        <div class="time">10:33 • SMS</div>
                    </div>
                </div>
            </div>
            <div class="card-footer py-2">
                <div class="input-group input-group-sm">
                    <select class="form-select" style="max-width:140px;">
                        <option>Reply via Portal</option>
                        <option>Reply via Email</option>
                        <option>Reply via SMS</option>
                    </select>
                    <input type="text" class="form-control" placeholder="Type reply...">
                    <button class="btn btn-success"><i class="ri-send-plane-line"></i></button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
function sendMsg(){
    var input = document.getElementById('msgInput');
    if(!input.value.trim()) return;
    var area = document.querySelector('.chat-messages');
    var div = document.createElement('div');
    div.className = 'chat-bubble sent';
    div.innerHTML = input.value + '<div class="time">' + new Date().toLocaleTimeString('en',{hour:'2-digit',minute:'2-digit'}) + '</div>';
    area.appendChild(div);
    input.value = '';
    area.scrollTop = area.scrollHeight;
}
document.getElementById('msgInput').addEventListener('keydown', function(e){ if(e.key==='Enter') sendMsg(); });

document.getElementById('contactSearch').addEventListener('input', function(){
    var q = this.value.toLowerCase();
    document.querySelectorAll('.chat-contact').forEach(function(c){
        c.style.display = c.textContent.toLowerCase().includes(q) ? '' : 'none';
    });
});
</script>
@endsection
