@extends('partials.layouts.master-employee')
@section('title', 'Multichat — WinCase Staff')
@section('page-title', 'My Multichat')

@section('css')
<style>
    /* ===== 3-column layout ===== */
    .mc-wrap { display:flex; height:calc(100vh - 60px); border:1px solid rgba(0,0,0,.08); border-radius:.5rem; overflow:hidden; background:#fff; position:relative; }
    [data-bs-theme="dark"] .mc-wrap { background:#1e2128; border-color:rgba(255,255,255,.06); }

    /* ===== Toggle buttons ===== */
    .mc-toggle { position:absolute; top:50%; z-index:10; width:24px; height:48px; border:1px solid rgba(0,0,0,.1); display:flex; align-items:center; justify-content:center; cursor:pointer; border-radius:0 6px 6px 0; background:#fff; transition:all .2s; font-size:.7rem; color:#6c757d; }
    .mc-toggle:hover { background:#015EA7; color:#fff; border-color:#015EA7; }
    [data-bs-theme="dark"] .mc-toggle { background:#2a2d35; border-color:rgba(255,255,255,.1); }
    .mc-toggle-left { left:0; transform:translateY(-50%); }
    .mc-toggle-right { right:0; transform:translateY(-50%); border-radius:6px 0 0 6px; }

    /* ===== LEFT: Clients panel ===== */
    .mc-left { width:280px; min-width:280px; border-right:1px solid rgba(0,0,0,.08); display:flex; flex-direction:column; transition:all .3s ease; overflow:hidden; }
    .mc-left.collapsed { width:0; min-width:0; border:none; }
    [data-bs-theme="dark"] .mc-left { border-color:rgba(255,255,255,.06); }
    .mc-left-head { padding:.6rem .75rem; border-bottom:1px solid rgba(0,0,0,.06); display:flex; align-items:center; gap:.5rem; }
    .mc-left-head .mc-title { font-weight:700; font-size:.8rem; text-transform:uppercase; color:#6c757d; letter-spacing:.4px; flex:1; }
    .mc-left-search { padding:.5rem .75rem; }
    .mc-left-tabs { display:flex; border-bottom:1px solid rgba(0,0,0,.06); }
    .mc-left-tabs .lt { flex:1; text-align:center; padding:.4rem; font-size:.68rem; font-weight:600; cursor:pointer; border-bottom:2px solid transparent; color:#999; transition:all .15s; white-space:nowrap; }
    .mc-left-tabs .lt.active { color:#015EA7; border-bottom-color:#015EA7; }
    .mc-left-tabs .lt:hover { color:#015EA7; }
    .mc-clients { flex:1; overflow-y:auto; }

    /* Client item */
    .mc-cl { padding:.65rem .75rem; cursor:pointer; border-bottom:1px solid rgba(0,0,0,.03); transition:all .15s; display:flex; align-items:center; gap:.6rem; }
    .mc-cl:hover { background:rgba(1,94,167,.04); }
    .mc-cl.active { background:rgba(1,94,167,.1); border-left:3px solid #015EA7; }
    .mc-cl-av { width:38px; height:38px; border-radius:50%; display:flex; align-items:center; justify-content:center; font-weight:700; font-size:.7rem; flex-shrink:0; position:relative; }
    .mc-cl-av .ch-dots { position:absolute; bottom:-2px; right:-4px; display:flex; gap:1px; }
    .mc-cl-av .ch-dot { width:10px; height:10px; border-radius:50%; border:1.5px solid #fff; }
    .mc-cl-body { flex:1; min-width:0; }
    .mc-cl-row1 { display:flex; align-items:center; gap:.3rem; }
    .mc-cl-name { font-weight:600; font-size:.8rem; flex:1; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
    .mc-cl-time { font-size:.6rem; color:#aaa; flex-shrink:0; }
    .mc-cl-msg { font-size:.7rem; color:#888; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; margin-top:1px; }
    .mc-cl-meta { display:flex; align-items:center; gap:.3rem; margin-top:2px; }
    .mc-badge { font-size:.5rem; padding:1px 4px; border-radius:3px; font-weight:700; color:#fff; }
    .mc-cl-unread { background:#dc3545; color:#fff; font-size:.55rem; padding:1px 5px; border-radius:10px; font-weight:700; margin-left:auto; }

    /* ===== CENTER: Chat ===== */
    .mc-center { flex:1; display:flex; flex-direction:column; min-width:0; position:relative; }
    .mc-chat-head { padding:.6rem 1rem; border-bottom:1px solid rgba(0,0,0,.08); display:flex; align-items:center; gap:.75rem; min-height:56px; }
    [data-bs-theme="dark"] .mc-chat-head { border-color:rgba(255,255,255,.06); }
    .mc-chat-av { width:40px; height:40px; border-radius:50%; display:flex; align-items:center; justify-content:center; font-weight:700; font-size:.8rem; flex-shrink:0; }
    .mc-chat-head-channels { display:flex; gap:3px; flex-wrap:wrap; }
    .mc-chat-head-actions { display:flex; gap:4px; }

    /* Messages area */
    .mc-msgs { flex:1; overflow-y:auto; padding:1rem 1.25rem; }
    .mc-date { text-align:center; margin:.75rem 0; }
    .mc-date span { background:#f0f2f5; padding:.15rem .6rem; border-radius:1rem; font-size:.6rem; color:#888; }
    [data-bs-theme="dark"] .mc-date span { background:#2a2d35; }

    .mc-row { display:flex; gap:.4rem; margin-bottom:.6rem; align-items:flex-end; }
    .mc-row.out { justify-content:flex-end; }
    .mc-bub { max-width:60%; padding:.55rem .85rem; border-radius:.8rem; font-size:.8rem; line-height:1.45; position:relative; transition:all .25s; }
    .mc-bub.in { background:#f0f2f5; border-bottom-left-radius:.15rem; }
    [data-bs-theme="dark"] .mc-bub.in { background:#2a2d35; color:#e0e0e0; }
    .mc-bub.out { background:#015EA7; color:#fff; border-bottom-right-radius:.15rem; }
    .mc-bub .mc-t { font-size:.55rem; opacity:.6; margin-top:.1rem; }
    .mc-bub .mc-ch { font-size:.52rem; font-weight:600; opacity:.7; }
    .mc-bub .mc-who { font-size:.6rem; font-weight:700; margin-bottom:.1rem; }
    .mc-bub.out .mc-who { color:rgba(255,255,255,.8); }
    .mc-bub.in .mc-who { color:#015EA7; }
    .mc-bub .mc-file { display:inline-flex; align-items:center; gap:.25rem; background:rgba(0,0,0,.05); padding:.15rem .4rem; border-radius:.25rem; font-size:.65rem; margin-top:.25rem; }
    .mc-bub.out .mc-file { background:rgba(255,255,255,.15); }

    /* Highlighted messages when client selected */
    .mc-bub.highlighted { box-shadow:0 0 0 2px #015EA7; transform:scale(1.01); }
    .mc-bub.dimmed { opacity:.35; }
    /* Channel filter active state */
    .mc-ch-filter { transition:all .15s; border:2px solid transparent; }
    .mc-ch-filter:hover { transform:scale(1.15); }
    .mc-ch-filter.ch-active { border-color:#fff; box-shadow:0 0 0 2px #333; transform:scale(1.15); }

    /* Boss message in worker's stream */
    .mc-bub.boss-msg { background:linear-gradient(135deg,#1F3864,#2a4a7f); color:#fff; border:1px solid rgba(255,215,0,.3); }
    .mc-bub.boss-msg .mc-who { color:rgba(255,215,0,.9); }
    .mc-bub.boss-msg .mc-ch { color:rgba(255,255,255,.6); }

    /* Input */
    .mc-input { border-top:1px solid rgba(0,0,0,.08); padding:.6rem .75rem; }
    [data-bs-theme="dark"] .mc-input { border-color:rgba(255,255,255,.06); }
    .mc-reply-bar { display:flex; align-items:center; gap:.4rem; margin-bottom:.4rem; font-size:.7rem; color:#888; flex-wrap:wrap; }
    .mc-rch { padding:2px 7px; border-radius:4px; font-size:.6rem; font-weight:600; cursor:pointer; border:1px solid rgba(0,0,0,.1); transition:all .15s; }
    .mc-rch.active { background:#015EA7; color:#fff; border-color:#015EA7; }
    .mc-qbtns { display:flex; gap:.3rem; flex-wrap:wrap; margin-top:.4rem; }
    .mc-qb { font-size:.6rem; padding:2px 7px; border-radius:1rem; border:1px solid rgba(1,94,167,.25); color:#015EA7; background:transparent; cursor:pointer; transition:all .15s; }
    .mc-qb:hover { background:#015EA7; color:#fff; }

    /* Drag & drop quote */
    .mc-bub[draggable="true"] { cursor:grab; }
    .mc-bub[draggable="true"]:active { cursor:grabbing; opacity:.7; }
    .mc-bub.dragging { opacity:.4; transform:scale(.95); }
    .mc-input.drag-over { background:rgba(1,94,167,.08); border-top:2px dashed #015EA7; }
    .mc-input.drag-over textarea { border-color:#015EA7; }
    .mc-quote { display:none; background:rgba(1,94,167,.08); border-left:3px solid #015EA7; border-radius:0 .4rem .4rem 0; padding:.4rem .6rem; margin-bottom:.5rem; font-size:.75rem; position:relative; animation:slideDown .2s ease; }
    .mc-quote.visible { display:block; }
    .mc-quote .mc-quote-ch { font-size:.6rem; font-weight:700; margin-bottom:.15rem; }
    .mc-quote .mc-quote-text { color:#555; line-height:1.3; max-height:40px; overflow:hidden; }
    [data-bs-theme="dark"] .mc-quote .mc-quote-text { color:#bbb; }
    .mc-quote .mc-quote-close { position:absolute; top:2px; right:6px; cursor:pointer; font-size:.8rem; color:#999; line-height:1; }
    .mc-quote .mc-quote-close:hover { color:#dc3545; }
    @keyframes slideDown { from { opacity:0; transform:translateY(-8px); } to { opacity:1; transform:translateY(0); } }
    .mc-drag-hint { text-align:center; font-size:.6rem; color:#aaa; margin-top:.2rem; }

    /* ===== Attachment dropup menu ===== */
    .mc-attach-wrap { position:relative; }
    .mc-attach-menu { display:none; position:absolute; bottom:100%; left:0; margin-bottom:6px; background:#fff; border:1px solid rgba(0,0,0,.1); border-radius:.5rem; box-shadow:0 4px 20px rgba(0,0,0,.12); min-width:220px; z-index:100; overflow:hidden; animation:slideDown .15s ease; }
    .mc-attach-menu.visible { display:block; }
    [data-bs-theme="dark"] .mc-attach-menu { background:#2a2d35; border-color:rgba(255,255,255,.1); }
    .mc-attach-menu .mc-am-title { font-size:.55rem; font-weight:700; text-transform:uppercase; color:#999; letter-spacing:.3px; padding:.5rem .75rem .2rem; }
    .mc-attach-menu .mc-am-item { display:flex; align-items:center; gap:.5rem; padding:.45rem .75rem; font-size:.75rem; cursor:pointer; transition:all .1s; color:#333; }
    [data-bs-theme="dark"] .mc-attach-menu .mc-am-item { color:#ddd; }
    .mc-attach-menu .mc-am-item:hover { background:rgba(1,94,167,.08); color:#015EA7; }
    .mc-attach-menu .mc-am-item i { font-size:1rem; width:20px; text-align:center; }
    .mc-attach-menu .mc-am-sep { height:1px; background:rgba(0,0,0,.06); margin:.2rem 0; }
    [data-bs-theme="dark"] .mc-attach-menu .mc-am-sep { background:rgba(255,255,255,.06); }

    /* ===== RIGHT: Client info ===== */
    .mc-right { width:260px; min-width:260px; border-left:1px solid rgba(0,0,0,.08); overflow-y:auto; display:flex; flex-direction:column; gap:.6rem; padding:.6rem; transition:all .3s ease; }
    .mc-right.collapsed { width:0; min-width:0; padding:0; border:none; overflow:hidden; }
    [data-bs-theme="dark"] .mc-right { border-color:rgba(255,255,255,.06); }
    .mc-right-head { display:flex; align-items:center; gap:.5rem; padding:.25rem 0; }
    .mc-right-head .mc-title { font-weight:700; font-size:.75rem; text-transform:uppercase; color:#6c757d; letter-spacing:.3px; flex:1; }
    .mc-card { background:#f8f9fa; border-radius:.5rem; padding:.5rem .65rem; }
    [data-bs-theme="dark"] .mc-card { background:#2a2d35; }
    .mc-card-title { font-size:.65rem; font-weight:700; color:#6c757d; text-transform:uppercase; letter-spacing:.25px; margin-bottom:.3rem; }
    .mc-card-row { font-size:.72rem; padding:.2rem 0; display:flex; justify-content:space-between; align-items:center; }
    .mc-card-row + .mc-card-row { border-top:1px solid rgba(0,0,0,.04); }

    /* Boss instructions card */
    .mc-card-boss { background:linear-gradient(135deg,rgba(31,56,100,.08),rgba(31,56,100,.03)); border:1px solid rgba(31,56,100,.15); }
    [data-bs-theme="dark"] .mc-card-boss { background:linear-gradient(135deg,rgba(31,56,100,.3),rgba(31,56,100,.15)); border-color:rgba(255,215,0,.15); }
    .mc-card-boss .mc-card-title { color:#1F3864; }
    [data-bs-theme="dark"] .mc-card-boss .mc-card-title { color:#ffd700; }
</style>
@endsection

@section('content')
<!-- Stats mini — worker-specific -->
<div class="row g-2 mb-2">
    <div class="col"><div class="card mb-0"><div class="card-body py-2 text-center"><div class="fs-4 fw-bold text-success">8</div><div class="text-muted" style="font-size:.65rem;">My Clients</div></div></div></div>
    <div class="col"><div class="card mb-0"><div class="card-body py-2 text-center"><div class="fs-4 fw-bold text-primary">5</div><div class="text-muted" style="font-size:.65rem;">Active Chats</div></div></div></div>
    <div class="col"><div class="card mb-0"><div class="card-body py-2 text-center"><div class="fs-4 fw-bold text-warning">3</div><div class="text-muted" style="font-size:.65rem;">Pending Reply</div></div></div></div>
    <div class="col"><div class="card mb-0"><div class="card-body py-2 text-center"><div class="fs-4 fw-bold text-danger">1</div><div class="text-muted" style="font-size:.65rem;">Overdue >24h</div></div></div></div>
    <div class="col"><div class="card mb-0"><div class="card-body py-2 text-center"><div class="fs-4 fw-bold" style="color:#1F3864;">2</div><div class="text-muted" style="font-size:.65rem;">Boss Notes</div></div></div></div>
</div>

<!-- ===== 3-column multichat ===== -->
<div class="mc-wrap">

    <!-- LEFT: my client list -->
    <div class="mc-left" id="mcLeft">
        <div class="mc-left-head">
            <i class="ri-message-3-line text-success"></i>
            <span class="mc-title">My Clients</span>
            <button class="btn btn-sm btn-light py-0 px-1" onclick="toggleLeft()" title="Collapse"><i class="ri-arrow-left-s-line"></i></button>
        </div>
        <div class="mc-left-search">
            <input type="text" class="form-control form-control-sm" placeholder="Search my clients..." id="clSearch">
        </div>
        <div class="mc-left-tabs">
            <div class="lt active" data-f="all">All <span class="badge bg-secondary rounded-pill" style="font-size:.5rem;">8</span></div>
            <div class="lt" data-f="unread">Unread <span class="badge bg-danger rounded-pill" style="font-size:.5rem;">3</span></div>
            <div class="lt" data-f="overdue">Overdue <span class="badge bg-warning rounded-pill" style="font-size:.5rem;">1</span></div>
        </div>
        <div class="mc-clients" id="clientList">
            <!-- Worker's client 1: Olena -->
            <div class="mc-cl active" data-client="olena" data-unread="3">
                <div class="mc-cl-av" style="background:rgba(1,94,167,.15);color:#015EA7;">OK
                    <div class="ch-dots"><div class="ch-dot" style="background:#25D366;"></div><div class="ch-dot" style="background:#015EA7;"></div><div class="ch-dot" style="background:#000;"></div></div>
                </div>
                <div class="mc-cl-body">
                    <div class="mc-cl-row1"><span class="mc-cl-name">Olena Kovalenko</span><span class="mc-cl-time">13:20</span></div>
                    <div class="mc-cl-msg">@wincase_eu can I submit documents electronically?</div>
                    <div class="mc-cl-meta">
                        <span class="mc-badge" style="background:#25D366;">WA</span>
                        <span class="mc-badge" style="background:#015EA7;">WC</span>
                        <span class="mc-badge" style="background:#6c757d;">EM</span>
                        <span class="mc-badge" style="background:#000;">TH</span>
                        <span class="mc-cl-unread">3</span>
                    </div>
                </div>
            </div>
            <!-- Worker's client 2: Dmytro -->
            <div class="mc-cl" data-client="dmytro" data-unread="1">
                <div class="mc-cl-av" style="background:rgba(13,110,253,.15);color:#0d6efd;">DB
                    <div class="ch-dots"><div class="ch-dot" style="background:#0088cc;"></div><div class="ch-dot" style="background:#1877F2;"></div></div>
                </div>
                <div class="mc-cl-body">
                    <div class="mc-cl-row1"><span class="mc-cl-name">Dmytro Bondarenko</span><span class="mc-cl-time">14:05</span></div>
                    <div class="mc-cl-msg">Is fingerprint appointment March 10?</div>
                    <div class="mc-cl-meta">
                        <span class="mc-badge" style="background:#0088cc;">TG</span>
                        <span class="mc-badge" style="background:#1877F2;">FB</span>
                        <span class="mc-cl-unread">1</span>
                    </div>
                </div>
            </div>
            <!-- Worker's client 3: Rahul -->
            <div class="mc-cl" data-client="rahul">
                <div class="mc-cl-av" style="background:rgba(255,87,34,.15);color:#ff5722;">RS
                    <div class="ch-dots"><div class="ch-dot" style="background:#015EA7;"></div><div class="ch-dot" style="background:#E60023;"></div></div>
                </div>
                <div class="mc-cl-body">
                    <div class="mc-cl-row1"><span class="mc-cl-name">Rahul Sharma</span><span class="mc-cl-time">14:20</span></div>
                    <div class="mc-cl-msg">Saved your pin about documents checklist!</div>
                    <div class="mc-cl-meta">
                        <span class="mc-badge" style="background:#015EA7;">WC</span>
                        <span class="mc-badge" style="background:#E60023;">PIN</span>
                    </div>
                </div>
            </div>
            <!-- Worker's client 4: Viktor -->
            <div class="mc-cl" data-client="viktor">
                <div class="mc-cl-av" style="background:rgba(63,81,181,.15);color:#3f51b5;">VM
                    <div class="ch-dots"><div class="ch-dot" style="background:#25D366;"></div><div class="ch-dot" style="background:#E4405F;"></div><div class="ch-dot" style="background:#010101;"></div></div>
                </div>
                <div class="mc-cl-body">
                    <div class="mc-cl-row1"><span class="mc-cl-name">Viktor Morozov</span><span class="mc-cl-time">15:10</span></div>
                    <div class="mc-cl-msg">Great video about legalization process!</div>
                    <div class="mc-cl-meta">
                        <span class="mc-badge" style="background:#25D366;">WA</span>
                        <span class="mc-badge" style="background:#E4405F;">IG</span>
                        <span class="mc-badge" style="background:#010101;">TT</span>
                    </div>
                </div>
            </div>
            <!-- Worker's client 5: Irina -->
            <div class="mc-cl" data-client="irina" data-unread="1">
                <div class="mc-cl-av" style="background:rgba(156,39,176,.15);color:#9c27b0;">IK
                    <div class="ch-dots"><div class="ch-dot" style="background:#0088cc;"></div><div class="ch-dot" style="background:#E4405F;"></div></div>
                </div>
                <div class="mc-cl-body">
                    <div class="mc-cl-row1"><span class="mc-cl-name">Irina Kozlova</span><span class="mc-cl-time">12:15</span></div>
                    <div class="mc-cl-msg">Hi! I saw your reel about karta pobytu...</div>
                    <div class="mc-cl-meta">
                        <span class="mc-badge" style="background:#0088cc;">TG</span>
                        <span class="mc-badge" style="background:#E4405F;">IG</span>
                        <span class="mc-badge" style="background:#6c757d;">EM</span>
                        <span class="mc-cl-unread">1</span>
                    </div>
                </div>
            </div>
            <!-- Worker's client 6: Mehmet -->
            <div class="mc-cl" data-client="mehmet">
                <div class="mc-cl-av" style="background:rgba(233,30,99,.15);color:#e91e63;">MY
                    <div class="ch-dots"><div class="ch-dot" style="background:#25D366;"></div><div class="ch-dot" style="background:#1877F2;"></div></div>
                </div>
                <div class="mc-cl-body">
                    <div class="mc-cl-row1"><span class="mc-cl-name">Mehmet Yilmaz</span><span class="mc-cl-time">11:30</span></div>
                    <div class="mc-cl-msg">I saw your post about work permits...</div>
                    <div class="mc-cl-meta">
                        <span class="mc-badge" style="background:#25D366;">WA</span>
                        <span class="mc-badge" style="background:#1877F2;">FB</span>
                        <span class="mc-badge" style="background:#0d6efd;">SMS</span>
                    </div>
                </div>
            </div>
            <!-- Worker's client 7: Chen Wei - overdue -->
            <div class="mc-cl" data-client="chen" data-overdue="1">
                <div class="mc-cl-av" style="background:rgba(255,152,0,.15);color:#ff9800;">CW
                    <div class="ch-dots"><div class="ch-dot" style="background:#6c757d;"></div><div class="ch-dot" style="background:#010101;"></div></div>
                </div>
                <div class="mc-cl-body">
                    <div class="mc-cl-row1"><span class="mc-cl-name">Chen Wei</span><span class="mc-cl-time">15:45</span></div>
                    <div class="mc-cl-msg">I saw your TikTok about birth certificate...</div>
                    <div class="mc-cl-meta">
                        <span class="mc-badge" style="background:#6c757d;">EM</span>
                        <span class="mc-badge" style="background:#010101;">TT</span>
                        <span class="ms-auto badge bg-danger" style="font-size:.5rem;">OVERDUE</span>
                    </div>
                </div>
            </div>
            <!-- Worker's client 8: Maria Garcia -->
            <div class="mc-cl" data-client="maria-g">
                <div class="mc-cl-av" style="background:rgba(121,85,72,.15);color:#795548;">MG
                    <div class="ch-dots"><div class="ch-dot" style="background:#6c757d;"></div></div>
                </div>
                <div class="mc-cl-body">
                    <div class="mc-cl-row1"><span class="mc-cl-name">Maria Garcia</span><span class="mc-cl-time">Feb 27</span></div>
                    <div class="mc-cl-msg">Please confirm my appointment date</div>
                    <div class="mc-cl-meta">
                        <span class="mc-badge" style="background:#6c757d;">EM</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Toggle left -->
    <div class="mc-toggle mc-toggle-left" id="toggleLeftBtn" onclick="toggleLeft()" style="display:none;"><i class="ri-arrow-right-s-line"></i></div>

    <!-- ===== CENTER: Multichat stream ===== -->
    <div class="mc-center">
        <div class="mc-chat-head">
            <div class="mc-chat-av" id="chatAvatar" style="background:rgba(1,94,167,.15);color:#015EA7;">OK</div>
            <div class="flex-grow-1">
                <div class="fw-semibold" id="chatName">Olena Kovalenko</div>
                <div style="font-size:.68rem;color:#888;" id="chatMeta">Case #WC-2026-0847 &bull; Temporary Residence Permit &bull; Stage: <strong class="text-warning">Awaiting Decision</strong></div>
            </div>
            <div class="mc-chat-head-channels" id="chatChannels">
                <span class="mc-badge mc-ch-filter" style="background:#25D366;cursor:pointer;" data-channel="whatsapp" title="Filter WhatsApp">WA</span>
                <span class="mc-badge mc-ch-filter" style="background:#0088cc;cursor:pointer;" data-channel="telegram" title="Filter Telegram">TG</span>
                <span class="mc-badge mc-ch-filter" style="background:#015EA7;cursor:pointer;" data-channel="portal" title="Filter Portal">Portal</span>
                <span class="mc-badge mc-ch-filter" style="background:#6c757d;cursor:pointer;" data-channel="email" title="Filter Email">Email</span>
                <span class="mc-badge mc-ch-filter" style="background:#0d6efd;cursor:pointer;" data-channel="sms" title="Filter SMS">SMS</span>
                <span class="mc-badge mc-ch-filter" style="background:#1877F2;cursor:pointer;" data-channel="facebook" title="Filter Facebook">FB</span>
                <span class="mc-badge mc-ch-filter" style="background:linear-gradient(45deg,#f09433,#e6683c,#dc2743,#cc2366,#bc1888);cursor:pointer;" data-channel="instagram" title="Filter Instagram">IG</span>
                <span class="mc-badge mc-ch-filter" style="background:#000;cursor:pointer;" data-channel="threads" title="Filter Threads">TH</span>
                <span class="mc-badge mc-ch-filter" style="background:#E60023;cursor:pointer;" data-channel="pinterest" title="Filter Pinterest">PIN</span>
                <span class="mc-badge mc-ch-filter" style="background:#010101;cursor:pointer;" data-channel="tiktok" title="Filter TikTok">TT</span>
            </div>
            <div class="mc-chat-head-actions">
                <button class="btn btn-sm btn-light py-0" title="Toggle info" onclick="toggleRight()"><i class="ri-layout-right-line"></i></button>
            </div>
        </div>

        <!-- All messages stream -->
        <div class="mc-msgs" id="mcMsgs">
            <div class="mc-date"><span>February 28, 2026</span></div>

            <div class="mc-row" data-from="olena" data-channel="telegram">
                <div class="mc-bub in">
                    <div class="mc-ch"><i class="ri-telegram-line"></i> Telegram</div>
                    Добрый день! Есть новости по моему делу?
                    <div class="mc-t">14:22</div>
                </div>
            </div>
            <div class="mc-row out" data-from="olena" data-channel="portal">
                <div class="mc-bub out">
                    <div class="mc-who"><i class="ri-user-line"></i> Anna Kowalska (Me)</div>
                    <div class="mc-ch"><i class="ri-globe-line"></i> Portal</div>
                    Добрый день, Олена! Ваше дело на рассмотрении. Ждём решение в течение 2-3 недель.
                    <div class="mc-t">14:35</div>
                </div>
            </div>
            <div class="mc-row" data-from="mehmet" data-channel="whatsapp">
                <div class="mc-bub in">
                    <div class="mc-who" style="color:#e91e63;">Mehmet Yilmaz</div>
                    <div class="mc-ch"><i class="ri-whatsapp-line" style="color:#25D366;"></i> WhatsApp</div>
                    Hello, can I come to your office tomorrow at 14:00?
                    <div class="mc-t">15:10</div>
                </div>
            </div>
            <div class="mc-row out" data-from="mehmet" data-channel="whatsapp">
                <div class="mc-bub out">
                    <div class="mc-who"><i class="ri-user-line"></i> Anna Kowalska (Me)</div>
                    <div class="mc-ch"><i class="ri-whatsapp-line"></i> WhatsApp</div>
                    Yes, 14:00 works. Please bring your passport and work permit.
                    <div class="mc-t">15:25</div>
                </div>
            </div>

            <div class="mc-date"><span>March 1, 2026</span></div>

            <div class="mc-row" data-from="olena" data-channel="email">
                <div class="mc-bub in">
                    <div class="mc-ch"><i class="ri-mail-line"></i> Email</div>
                    <strong>Subject: Bank statement</strong><br>Hello, attached is my bank statement for February. Please confirm you received it.
                    <div class="mc-file"><i class="ri-attachment-line"></i> bank_feb_2026.pdf <span class="text-muted">(1.2 MB)</span></div>
                    <div class="mc-t">11:05</div>
                </div>
            </div>
            <div class="mc-row out" data-from="olena" data-channel="email">
                <div class="mc-bub out">
                    <div class="mc-who"><i class="ri-user-line"></i> Anna Kowalska (Me)</div>
                    <div class="mc-ch"><i class="ri-mail-line"></i> Email reply</div>
                    Received, thank you! Document uploaded to your case file.
                    <div class="mc-t">11:20</div>
                </div>
            </div>
            <!-- Boss instruction message — worker can see but NOT the full boss panel -->
            <div class="mc-row out" data-from="olena" data-channel="internal">
                <div class="mc-bub boss-msg">
                    <div class="mc-who"><i class="ri-shield-keyhole-line"></i> Dmitry Sokolov (Director)</div>
                    <div class="mc-ch"><i class="ri-lock-line"></i> Internal Note</div>
                    Anna, push Olena for the March bank statement by March 5. Also check if her employer letter is up to date.
                    <div class="mc-t">11:45 &bull; <em>Only you can see this</em></div>
                </div>
            </div>
            <div class="mc-row" data-from="chen" data-channel="email">
                <div class="mc-bub in">
                    <div class="mc-who" style="color:#ff9800;">Chen Wei</div>
                    <div class="mc-ch"><i class="ri-mail-line"></i> Email</div>
                    I need help with the translated birth certificate. Where can I get it certified?
                    <div class="mc-t">14:30</div>
                </div>
            </div>
            <div class="mc-row" data-from="irina" data-channel="telegram">
                <div class="mc-bub in">
                    <div class="mc-who" style="color:#9c27b0;">Irina Kozlova</div>
                    <div class="mc-ch"><i class="ri-telegram-line" style="color:#0088cc;"></i> Telegram</div>
                    Any news about the decision on my permanent residence?
                    <div class="mc-t">16:45</div>
                </div>
            </div>

            <div class="mc-date"><span>Today, March 4, 2026</span></div>

            <div class="mc-row" data-from="olena" data-channel="whatsapp">
                <div class="mc-bub in">
                    <div class="mc-ch" style="color:#25D366;"><i class="ri-whatsapp-line"></i> WhatsApp</div>
                    Hi! Quick question — when do I need the March bank statement?
                    <div class="mc-t">09:30</div>
                </div>
            </div>
            <div class="mc-row" data-from="dmytro" data-channel="telegram">
                <div class="mc-bub in">
                    <div class="mc-who" style="color:#0d6efd;">Dmytro Bondarenko</div>
                    <div class="mc-ch" style="color:#0088cc;"><i class="ri-telegram-line"></i> Telegram</div>
                    When is my fingerprint appointment? I forgot the date.
                    <div class="mc-t">09:15</div>
                </div>
            </div>
            <div class="mc-row" data-from="olena" data-channel="portal">
                <div class="mc-bub in">
                    <div class="mc-ch" style="color:#015EA7;"><i class="ri-globe-line"></i> Client Portal</div>
                    Hello! I wanted to ask about the bank statement for March — when do I need to submit it?
                    <div class="mc-t">09:45</div>
                </div>
            </div>
            <div class="mc-row out" data-from="olena" data-channel="portal">
                <div class="mc-bub out">
                    <div class="mc-who"><i class="ri-user-line"></i> Anna Kowalska (Me)</div>
                    <div class="mc-ch"><i class="ri-globe-line"></i> Portal</div>
                    Hi Olena! Submit by March 5. Upload through portal or bring to office.
                    <div class="mc-t">09:52</div>
                </div>
            </div>
            <div class="mc-row" data-from="olena" data-channel="sms">
                <div class="mc-bub in">
                    <div class="mc-ch" style="color:#0d6efd;"><i class="ri-message-2-line"></i> SMS</div>
                    OK thx I'll upload tomorrow
                    <div class="mc-t">10:33</div>
                </div>
            </div>
            <div class="mc-row" data-from="mehmet" data-channel="facebook">
                <div class="mc-bub in">
                    <div class="mc-who" style="color:#e91e63;">Mehmet Yilmaz</div>
                    <div class="mc-ch" style="color:#1877F2;"><i class="ri-facebook-line"></i> Facebook Comment</div>
                    I saw your post about work permits — does it apply to Turkish citizens too?
                    <div class="mc-t">11:30 &bull; fb.com/wincase/post/12847</div>
                </div>
            </div>
            <div class="mc-row out" data-from="mehmet" data-channel="facebook">
                <div class="mc-bub out">
                    <div class="mc-who"><i class="ri-user-line"></i> Anna Kowalska (Me)</div>
                    <div class="mc-ch"><i class="ri-facebook-line"></i> FB Reply</div>
                    Yes, absolutely! Turkish citizens are eligible. DM us for a free consultation.
                    <div class="mc-t">11:45</div>
                </div>
            </div>
            <div class="mc-row" data-from="irina" data-channel="instagram">
                <div class="mc-bub in">
                    <div class="mc-who" style="color:#9c27b0;">Irina Kozlova</div>
                    <div class="mc-ch" style="color:#E4405F;"><i class="ri-instagram-line"></i> Instagram DM</div>
                    Hi! I saw your reel about karta pobytu. How long does the process take now?
                    <div class="mc-t">12:15</div>
                </div>
            </div>
            <div class="mc-row out" data-from="irina" data-channel="instagram">
                <div class="mc-bub out">
                    <div class="mc-who"><i class="ri-user-line"></i> Anna Kowalska (Me)</div>
                    <div class="mc-ch"><i class="ri-instagram-line"></i> IG Reply</div>
                    Hi Irina! Current processing time is ~60-90 days. Your case is at day 83, almost there!
                    <div class="mc-t">12:30</div>
                </div>
            </div>
            <div class="mc-row" data-from="viktor" data-channel="instagram">
                <div class="mc-bub in">
                    <div class="mc-who" style="color:#3f51b5;">Viktor Morozov</div>
                    <div class="mc-ch" style="color:#E4405F;"><i class="ri-instagram-line"></i> IG Comment</div>
                    Best lawyers in Warsaw! Got my card in 5 months. Highly recommend!
                    <div class="mc-t">13:00 &bull; @wincase_eu reel</div>
                </div>
            </div>
            <div class="mc-row" data-from="olena" data-channel="threads">
                <div class="mc-bub in">
                    <div class="mc-ch" style="color:#000;"><i class="ri-threads-line"></i> Threads</div>
                    @wincase_eu can I submit documents electronically or only in person?
                    <div class="mc-t">13:20</div>
                </div>
            </div>
            <div class="mc-row out" data-from="olena" data-channel="threads">
                <div class="mc-bub out">
                    <div class="mc-who"><i class="ri-user-line"></i> Anna Kowalska (Me)</div>
                    <div class="mc-ch"><i class="ri-threads-line"></i> Threads Reply</div>
                    You can upload via our client portal or bring to office. Both accepted!
                    <div class="mc-t">13:35</div>
                </div>
            </div>
            <div class="mc-row" data-from="dmytro" data-channel="facebook">
                <div class="mc-bub in">
                    <div class="mc-who" style="color:#0d6efd;">Dmytro Bondarenko</div>
                    <div class="mc-ch" style="color:#1877F2;"><i class="ri-facebook-line"></i> Facebook DM</div>
                    Hello, I sent you a message on Telegram too. Just wanted to make sure — is fingerprint appointment March 10?
                    <div class="mc-t">14:05</div>
                </div>
            </div>
            <div class="mc-row" data-from="rahul" data-channel="pinterest">
                <div class="mc-bub in">
                    <div class="mc-who" style="color:#ff5722;">Rahul Sharma</div>
                    <div class="mc-ch" style="color:#E60023;"><i class="ri-pinterest-line"></i> Pinterest Comment</div>
                    Saved your pin about documents checklist! Very helpful. Is this list still up to date for 2026?
                    <div class="mc-t">14:20 &bull; pin/wincase/docs-checklist</div>
                </div>
            </div>
            <div class="mc-row" data-from="viktor" data-channel="tiktok">
                <div class="mc-bub in">
                    <div class="mc-who" style="color:#3f51b5;">Viktor Morozov</div>
                    <div class="mc-ch" style="color:#010101;"><i class="ri-tiktok-line"></i> TikTok Comment</div>
                    Great video about the legalization process! Shared with friends who need help too
                    <div class="mc-t">15:10 &bull; @wincase_eu video</div>
                </div>
            </div>
            <div class="mc-row" data-from="chen" data-channel="tiktok">
                <div class="mc-bub in">
                    <div class="mc-who" style="color:#ff9800;">Chen Wei</div>
                    <div class="mc-ch" style="color:#010101;"><i class="ri-tiktok-line"></i> TikTok DM</div>
                    Hi, I saw your TikTok about birth certificate translation. Can you help me with mine from China?
                    <div class="mc-t">15:45</div>
                </div>
            </div>
            <!-- Boss note visible to worker -->
            <div class="mc-row out" data-from="chen" data-channel="internal">
                <div class="mc-bub boss-msg">
                    <div class="mc-who"><i class="ri-shield-keyhole-line"></i> Dmitry Sokolov (Director)</div>
                    <div class="mc-ch"><i class="ri-lock-line"></i> Internal Note</div>
                    Anna, help Chen with the Chinese birth certificate. Use our translator contact: Ling (+48 500 XXX). Priority case.
                    <div class="mc-t">16:10 &bull; <em>Only you can see this</em></div>
                </div>
            </div>
        </div>

        <!-- Input -->
        <div class="mc-input">
            <div class="mc-reply-bar">
                <span>Reply via:</span>
                <span class="mc-rch active" data-ch="portal"><i class="ri-globe-line"></i> Portal</span>
                <span class="mc-rch" data-ch="email"><i class="ri-mail-line"></i> Email</span>
                <span class="mc-rch" data-ch="sms"><i class="ri-message-2-line"></i> SMS</span>
                <span class="mc-rch" data-ch="wa"><i class="ri-whatsapp-line"></i> WhatsApp</span>
                <span class="mc-rch" data-ch="tg"><i class="ri-telegram-line"></i> TG</span>
                <span class="mc-rch" data-ch="fb"><i class="ri-facebook-line"></i> FB</span>
                <span class="mc-rch" data-ch="ig"><i class="ri-instagram-line"></i> IG</span>
                <span class="mc-rch" data-ch="threads"><i class="ri-threads-line"></i> Threads</span>
                <span class="mc-rch" data-ch="pin"><i class="ri-pinterest-line"></i> PIN</span>
                <span class="mc-rch" data-ch="tt"><i class="ri-tiktok-line"></i> TikTok</span>
                <span class="ms-auto" style="font-size:.6rem;color:#015EA7;"><i class="ri-user-line"></i> as <strong>Anna Kowalska</strong></span>
            </div>
            <div class="mc-quote" id="mcQuote">
                <span class="mc-quote-close" onclick="clearQuote()">&times;</span>
                <div class="mc-quote-ch" id="mcQuoteCh"></div>
                <div class="mc-quote-text" id="mcQuoteText"></div>
            </div>
            <div class="d-flex gap-2 align-items-end">
                <div class="mc-attach-wrap">
                    <button class="btn btn-light btn-sm" type="button" id="attachBtn" onclick="toggleAttachMenu()"><i class="ri-attachment-line"></i></button>
                    <div class="mc-attach-menu" id="attachMenu">
                        <div class="mc-am-title">Upload</div>
                        <div class="mc-am-item" onclick="attachAction('file')"><i class="ri-file-upload-line text-primary"></i> Upload File</div>
                        <div class="mc-am-item" onclick="attachAction('photo')"><i class="ri-image-add-line text-success"></i> Photo / Image</div>
                        <div class="mc-am-item" onclick="attachAction('camera')"><i class="ri-camera-line text-warning"></i> Take Photo</div>
                        <div class="mc-am-item" onclick="attachAction('scan')"><i class="ri-scan-line" style="color:#9c27b0;"></i> Scan Document</div>
                        <div class="mc-am-sep"></div>
                        <div class="mc-am-title">Save to</div>
                        <div class="mc-am-item" onclick="attachAction('case')"><i class="ri-folder-add-line text-success"></i> Case File</div>
                        <div class="mc-am-item" onclick="attachAction('gdrive')"><i class="ri-google-line" style="color:#4285F4;"></i> Google Drive</div>
                        <div class="mc-am-item" onclick="attachAction('obsidian')"><i class="ri-book-2-line" style="color:#7C3AED;"></i> Obsidian Vault</div>
                        <div class="mc-am-item" onclick="attachAction('notion')"><i class="ri-file-list-3-line" style="color:#000;"></i> Notion</div>
                        <div class="mc-am-sep"></div>
                        <div class="mc-am-title">Templates</div>
                        <div class="mc-am-item" onclick="attachAction('template')"><i class="ri-file-copy-line text-info"></i> From Template</div>
                        <div class="mc-am-item" onclick="attachAction('docgen')"><i class="ri-magic-line" style="color:#ff9800;"></i> AI Generate Doc</div>
                    </div>
                </div>
                <textarea class="form-control" placeholder="Type a message as Anna Kowalska..." id="staffInput" rows="4" style="resize:vertical;min-height:80px;font-size:.85rem;"></textarea>
                <button class="btn btn-success btn-sm px-3" type="button" id="staffSend"><i class="ri-send-plane-line"></i></button>
            </div>
            <div class="mc-drag-hint"><i class="ri-drag-move-line"></i> Drag any message here to quote & reply via its channel</div>
            <div class="mc-qbtns">
                <span class="mc-qb"><i class="ri-question-line"></i> Ask Boss</span>
                <span class="mc-qb"><i class="ri-arrow-up-circle-line"></i> Escalate</span>
                <span class="mc-qb"><i class="ri-file-list-line"></i> Template</span>
                <span class="mc-qb"><i class="ri-time-line"></i> Remind Later</span>
                <span class="mc-qb"><i class="ri-flag-line"></i> Urgent</span>
            </div>
        </div>
    </div>

    <!-- Toggle right -->
    <div class="mc-toggle mc-toggle-right" id="toggleRightBtn" onclick="toggleRight()" style="display:none;"><i class="ri-arrow-left-s-line"></i></div>

    <!-- ===== RIGHT: Client info ===== -->
    <div class="mc-right" id="mcRight">
        <div class="mc-right-head">
            <i class="ri-user-3-line text-success"></i>
            <span class="mc-title">Client Info</span>
            <button class="btn btn-sm btn-light py-0 px-1" onclick="toggleRight()" title="Collapse"><i class="ri-arrow-right-s-line"></i></button>
        </div>
        <!-- Client -->
        <div class="mc-card" id="infoClient">
            <div class="mc-card-title"><i class="ri-user-3-line me-1"></i>Contact</div>
            <div class="mc-card-row"><span>Name</span><strong>Olena Kovalenko</strong></div>
            <div class="mc-card-row"><span>Country</span><span>Ukraine</span></div>
            <div class="mc-card-row"><span>Age</span><span>28</span></div>
            <div class="mc-card-row"><span>Phone</span><span>+48 512 XXX</span></div>
            <div class="mc-card-row"><span>Email</span><span style="font-size:.65rem;">olena@gmail.com</span></div>
        </div>
        <!-- Case -->
        <div class="mc-card" id="infoCase">
            <div class="mc-card-title"><i class="ri-briefcase-line me-1"></i>Case</div>
            <div class="mc-card-row"><span>Number</span><strong>#WC-2026-0847</strong></div>
            <div class="mc-card-row"><span>Type</span><span>TRP</span></div>
            <div class="mc-card-row"><span>Stage</span><span class="badge bg-warning" style="font-size:.6rem;">Awaiting Decision</span></div>
            <div class="mc-card-row"><span>Day</span><span>47 / ~90</span></div>
        </div>
        <!-- Boss Instructions — worker sees boss notes here -->
        <div class="mc-card mc-card-boss" id="infoBoss">
            <div class="mc-card-title"><i class="ri-shield-keyhole-line me-1"></i>Boss Instructions</div>
            <div style="font-size:.7rem;line-height:1.4;color:#555;">Push for March bank statement by March 5. Check employer letter status.</div>
            <div style="font-size:.55rem;color:#999;margin-top:.3rem;">Mar 1, 11:45 — Dmitry Sokolov</div>
        </div>
        <!-- Channels -->
        <div class="mc-card" id="infoChannels">
            <div class="mc-card-title"><i class="ri-chat-check-line me-1"></i>Channels (11)</div>
            <div class="mc-card-row"><span><span class="mc-badge" style="background:#25D366;">WA</span> WhatsApp</span><span>12 msgs</span></div>
            <div class="mc-card-row"><span><span class="mc-badge" style="background:#0088cc;">TG</span> Telegram</span><span>3 msgs</span></div>
            <div class="mc-card-row"><span><span class="mc-badge" style="background:#015EA7;">WC</span> Portal</span><span>8 msgs</span></div>
            <div class="mc-card-row"><span><span class="mc-badge" style="background:#6c757d;">EM</span> Email</span><span>5 msgs</span></div>
            <div class="mc-card-row"><span><span class="mc-badge" style="background:#0d6efd;">SMS</span> SMS</span><span>2 msgs</span></div>
            <div class="mc-card-row"><span><span class="mc-badge" style="background:#1877F2;">FB</span> Facebook</span><span>4 msgs</span></div>
            <div class="mc-card-row"><span><span class="mc-badge" style="background:#E4405F;">IG</span> Instagram</span><span>6 msgs</span></div>
            <div class="mc-card-row"><span><span class="mc-badge" style="background:#000;">TH</span> Threads</span><span>2 msgs</span></div>
            <div class="mc-card-row"><span><span class="mc-badge" style="background:#E60023;">PIN</span> Pinterest</span><span>1 msg</span></div>
            <div class="mc-card-row"><span><span class="mc-badge" style="background:#010101;">TT</span> TikTok</span><span>3 msgs</span></div>
        </div>
        <!-- Response -->
        <div class="mc-card">
            <div class="mc-card-title"><i class="ri-timer-line me-1"></i>My Response</div>
            <div class="mc-card-row"><span>Avg reply</span><strong>18 min</strong></div>
            <div class="mc-card-row"><span>Last reply</span><span>13:35</span></div>
            <div class="mc-card-row"><span>SLA</span><span class="badge bg-success" style="font-size:.6rem;">OK</span></div>
        </div>
        <!-- Files -->
        <div class="mc-card" id="infoFiles">
            <div class="mc-card-title"><i class="ri-file-list-3-line me-1"></i>Shared Files</div>
            <div class="mc-card-row"><span><i class="ri-file-pdf-2-line text-danger"></i> bank_feb.pdf</span><span class="text-muted">1.2MB</span></div>
            <div class="mc-card-row"><span><i class="ri-image-line text-primary"></i> passport.jpg</span><span class="text-muted">3.4MB</span></div>
            <div class="mc-card-row"><span><i class="ri-file-pdf-2-line text-danger"></i> contract.pdf</span><span class="text-muted">0.8MB</span></div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
// ===== Toggle panels =====
function toggleLeft(){
    var el = document.getElementById('mcLeft');
    var btn = document.getElementById('toggleLeftBtn');
    el.classList.toggle('collapsed');
    btn.style.display = el.classList.contains('collapsed') ? 'flex' : 'none';
}
function toggleRight(){
    var el = document.getElementById('mcRight');
    var btn = document.getElementById('toggleRightBtn');
    el.classList.toggle('collapsed');
    btn.style.display = el.classList.contains('collapsed') ? 'flex' : 'none';
}

// ===== Client selection: highlight messages =====
var currentClient = 'olena';

document.querySelectorAll('.mc-cl').forEach(function(cl){
    cl.addEventListener('click', function(){
        document.querySelectorAll('.mc-cl').forEach(function(x){ x.classList.remove('active'); });
        this.classList.add('active');
        currentClient = this.dataset.client;
        highlightMessages(currentClient);
        updateHeader(this);
    });
});

function highlightMessages(clientId){
    document.querySelectorAll('.mc-row').forEach(function(row){
        var bub = row.querySelector('.mc-bub');
        if(row.dataset.from === clientId){
            bub.classList.add('highlighted');
            bub.classList.remove('dimmed');
        } else {
            bub.classList.remove('highlighted');
            bub.classList.add('dimmed');
        }
    });
    setTimeout(function(){
        document.querySelectorAll('.mc-bub.dimmed').forEach(function(b){ b.classList.remove('dimmed'); });
    }, 2500);
}

// Client info data (worker's assigned clients)
var clientData = {
    olena:   { name:'Olena Kovalenko', av:'OK', color:'rgba(1,94,167,.15)', textColor:'#015EA7', country:'Ukraine', age:28, phone:'+48 512 XXX', email:'olena@gmail.com', caseNum:'#WC-2026-0847', caseType:'TRP', stage:'Awaiting Decision', stageClass:'bg-warning', day:'47 / ~90', bossNote:'Push for March bank statement by March 5. Check employer letter status.', bossNoteDate:'Mar 1, 11:45' },
    dmytro:  { name:'Dmytro Bondarenko', av:'DB', color:'rgba(13,110,253,.15)', textColor:'#0d6efd', country:'Ukraine', age:35, phone:'+48 501 XXX', email:'dmytro@ukr.net', caseNum:'#WC-2026-0812', caseType:'Work Permit', stage:'Fingerprint Appt.', stageClass:'bg-info', day:'30', bossNote:'Confirm fingerprint appointment March 10. Prepare documents package.', bossNoteDate:'Mar 2, 09:00' },
    chen:    { name:'Chen Wei', av:'CW', color:'rgba(255,152,0,.15)', textColor:'#ff9800', country:'China', age:33, phone:'+48 505 XXX', email:'chen@qq.com', caseNum:'#WC-2026-0870', caseType:'TRP', stage:'Pending Docs', stageClass:'bg-danger', day:'12', bossNote:'Help with Chinese birth certificate. Use translator Ling (+48 500 XXX). Priority!', bossNoteDate:'Mar 4, 16:10' },
    rahul:   { name:'Rahul Sharma', av:'RS', color:'rgba(255,87,34,.15)', textColor:'#ff5722', country:'India', age:31, phone:'+48 509 XXX', email:'rahul@gmail.com', caseNum:'#WC-2026-0831', caseType:'TRP', stage:'Awaiting Decision', stageClass:'bg-warning', day:'5', bossNote:'', bossNoteDate:'' },
    mehmet:  { name:'Mehmet Yilmaz', av:'MY', color:'rgba(233,30,99,.15)', textColor:'#e91e63', country:'Turkey', age:29, phone:'+48 515 XXX', email:'mehmet@hot.com', caseNum:'#WC-2026-0855', caseType:'Work Permit Ext.', stage:'Submitted', stageClass:'bg-primary', day:'3', bossNote:'', bossNoteDate:'' },
    irina:   { name:'Irina Kozlova', av:'IK', color:'rgba(156,39,176,.15)', textColor:'#9c27b0', country:'Belarus', age:42, phone:'+48 520 XXX', email:'irina@mail.ru', caseNum:'#WC-2026-0798', caseType:'Permanent Res.', stage:'Awaiting Decision', stageClass:'bg-warning', day:'83 / ~90', bossNote:'Almost at decision date. Keep client informed daily.', bossNoteDate:'Mar 3, 14:00' },
    'maria-g': { name:'Maria Garcia', av:'MG', color:'rgba(121,85,72,.15)', textColor:'#795548', country:'Spain', age:27, phone:'+48 530 XXX', email:'maria@esp.com', caseNum:'#WC-2026-0878', caseType:'Student Visa', stage:'Pending Docs', stageClass:'bg-danger', day:'8', bossNote:'', bossNoteDate:'' },
    viktor:  { name:'Viktor Morozov', av:'VM', color:'rgba(63,81,181,.15)', textColor:'#3f51b5', country:'Ukraine', age:45, phone:'+48 502 XXX', email:'vik@ukr.net', caseNum:'#WC-2026-0790', caseType:'Work Permit', stage:'Card Issued', stageClass:'bg-success', day:'148', bossNote:'Card issued. Follow up for review/testimonial request.', bossNoteDate:'Mar 2, 10:30' }
};

function updateHeader(el){
    var d = clientData[el.dataset.client];
    if(!d) return;
    document.getElementById('chatAvatar').textContent = d.av;
    document.getElementById('chatAvatar').style.background = d.color;
    document.getElementById('chatAvatar').style.color = d.textColor;
    document.getElementById('chatName').textContent = d.name;
    document.getElementById('chatMeta').innerHTML = 'Case ' + d.caseNum + ' &bull; ' + d.caseType + ' &bull; Stage: <strong class="text-' + (d.stageClass.replace('bg-','')) + '">' + d.stage + '</strong>';
    // Update right panel
    document.getElementById('infoClient').innerHTML = '<div class="mc-card-title"><i class="ri-user-3-line me-1"></i>Contact</div>' +
        '<div class="mc-card-row"><span>Name</span><strong>' + d.name + '</strong></div>' +
        '<div class="mc-card-row"><span>Country</span><span>' + d.country + '</span></div>' +
        '<div class="mc-card-row"><span>Age</span><span>' + d.age + '</span></div>' +
        '<div class="mc-card-row"><span>Phone</span><span>' + d.phone + '</span></div>' +
        '<div class="mc-card-row"><span>Email</span><span style="font-size:.65rem;">' + d.email + '</span></div>';
    document.getElementById('infoCase').innerHTML = '<div class="mc-card-title"><i class="ri-briefcase-line me-1"></i>Case</div>' +
        '<div class="mc-card-row"><span>Number</span><strong>' + d.caseNum + '</strong></div>' +
        '<div class="mc-card-row"><span>Type</span><span>' + d.caseType + '</span></div>' +
        '<div class="mc-card-row"><span>Stage</span><span class="badge ' + d.stageClass + '" style="font-size:.6rem;">' + d.stage + '</span></div>' +
        '<div class="mc-card-row"><span>Day</span><span>' + d.day + '</span></div>';
    // Boss instructions
    if(d.bossNote){
        document.getElementById('infoBoss').innerHTML = '<div class="mc-card-title"><i class="ri-shield-keyhole-line me-1"></i>Boss Instructions</div>' +
            '<div style="font-size:.7rem;line-height:1.4;color:#555;">' + d.bossNote + '</div>' +
            '<div style="font-size:.55rem;color:#999;margin-top:.3rem;">' + d.bossNoteDate + ' — Dmitry Sokolov</div>';
        document.getElementById('infoBoss').style.display = '';
    } else {
        document.getElementById('infoBoss').innerHTML = '<div class="mc-card-title"><i class="ri-shield-keyhole-line me-1"></i>Boss Instructions</div>' +
            '<div style="font-size:.7rem;color:#aaa;font-style:italic;">No instructions for this client</div>';
    }
}

// ===== Channel filter: click badge to highlight that channel =====
var activeChannelFilter = null;
document.querySelectorAll('.mc-ch-filter').forEach(function(badge){
    badge.addEventListener('click', function(){
        var ch = this.dataset.channel;
        if(activeChannelFilter === ch){
            activeChannelFilter = null;
            document.querySelectorAll('.mc-ch-filter').forEach(function(b){ b.classList.remove('ch-active'); });
            document.querySelectorAll('.mc-row').forEach(function(row){
                row.querySelector('.mc-bub').classList.remove('highlighted','dimmed');
            });
            return;
        }
        activeChannelFilter = ch;
        document.querySelectorAll('.mc-ch-filter').forEach(function(b){ b.classList.remove('ch-active'); });
        this.classList.add('ch-active');
        document.querySelectorAll('.mc-row').forEach(function(row){
            var bub = row.querySelector('.mc-bub');
            if(row.dataset.channel === ch){
                bub.classList.add('highlighted');
                bub.classList.remove('dimmed');
            } else {
                bub.classList.remove('highlighted');
                bub.classList.add('dimmed');
            }
        });
    });
});

// ===== Send message =====
document.getElementById('staffSend').addEventListener('click', sendStaffMsg);
document.getElementById('staffInput').addEventListener('keydown', function(e){ if(e.key==='Enter' && !e.shiftKey){ e.preventDefault(); sendStaffMsg(); } });
function sendStaffMsg(){
    var input = document.getElementById('staffInput');
    if(!input.value.trim()) return;
    var area = document.getElementById('mcMsgs');
    var ch = document.querySelector('.mc-rch.active');
    var chName = ch ? ch.textContent.trim() : 'Portal';
    var quoteHtml = '';
    if(quotedData){
        var qChLabel = channelLabels[quotedData.channel] || quotedData.channel;
        quoteHtml = '<div style="border-left:2px solid rgba(255,255,255,.4);padding:2px 6px;margin-bottom:4px;font-size:.65rem;opacity:.8;"><strong>' + (quotedData.who||quotedData.from) + '</strong> via ' + qChLabel + '<br>' + quotedData.text.substring(0,80) + '</div>';
    }
    var row = document.createElement('div');
    row.className = 'mc-row out';
    row.dataset.from = currentClient;
    row.dataset.channel = ch ? ch.dataset.ch : 'portal';
    row.innerHTML = '<div class="mc-bub out highlighted"><div class="mc-who"><i class="ri-user-line"></i> Anna Kowalska (Me)</div><div class="mc-ch"><i class="ri-globe-line"></i> ' + chName + '</div>' + quoteHtml + input.value.replace(/\n/g,'<br>') + '<div class="mc-t">' + new Date().toLocaleTimeString('en',{hour:'2-digit',minute:'2-digit'}) + '</div></div>';
    area.appendChild(row);
    input.value = '';
    area.scrollTop = area.scrollHeight;
    clearQuote();
}

// Channel select
document.querySelectorAll('.mc-rch').forEach(function(ch){
    ch.addEventListener('click', function(){
        document.querySelectorAll('.mc-rch').forEach(function(c){ c.classList.remove('active'); });
        this.classList.add('active');
    });
});

// Left tabs — filter clients
document.querySelectorAll('.mc-left-tabs .lt').forEach(function(t){
    t.addEventListener('click', function(){
        document.querySelectorAll('.mc-left-tabs .lt').forEach(function(x){ x.classList.remove('active'); });
        this.classList.add('active');
        var filter = this.dataset.f;
        document.querySelectorAll('.mc-cl').forEach(function(c){
            if(filter === 'all'){
                c.style.display = '';
            } else if(filter === 'unread'){
                c.style.display = c.dataset.unread ? '' : 'none';
            } else if(filter === 'overdue'){
                c.style.display = c.dataset.overdue ? '' : 'none';
            }
        });
    });
});

// Search
document.getElementById('clSearch').addEventListener('input', function(){
    var q = this.value.toLowerCase();
    document.querySelectorAll('.mc-cl').forEach(function(c){
        c.style.display = c.textContent.toLowerCase().includes(q) ? '' : 'none';
    });
});

// ===== Drag & Drop: quote message into reply =====
var channelMap = {
    whatsapp:'wa', telegram:'tg', portal:'portal', email:'email', sms:'sms',
    facebook:'fb', instagram:'ig', threads:'threads', pinterest:'pin', tiktok:'tt'
};
var channelLabels = {
    whatsapp:'WhatsApp', telegram:'Telegram', portal:'Portal', email:'Email', sms:'SMS',
    facebook:'Facebook', instagram:'Instagram', threads:'Threads', pinterest:'Pinterest', tiktok:'TikTok'
};
var quotedData = null;

// Make all bubbles draggable
document.querySelectorAll('.mc-row').forEach(function(row){
    var bub = row.querySelector('.mc-bub');
    bub.setAttribute('draggable','true');
    bub.addEventListener('dragstart', function(e){
        this.classList.add('dragging');
        var ch = row.dataset.channel || '';
        var who = row.querySelector('.mc-who');
        var whoText = who ? who.textContent.trim() : '';
        var clone = bub.cloneNode(true);
        ['mc-ch','mc-who','mc-t','mc-file'].forEach(function(cls){ clone.querySelectorAll('.'+cls).forEach(function(el){ el.remove(); }); });
        var msgText = clone.textContent.trim();
        e.dataTransfer.setData('text/plain', JSON.stringify({ channel:ch, who:whoText, text:msgText, from:row.dataset.from }));
        e.dataTransfer.effectAllowed = 'copy';
    });
    bub.addEventListener('dragend', function(){ this.classList.remove('dragging'); });
});

// Drop zone
var inputArea = document.querySelector('.mc-input');
inputArea.addEventListener('dragover', function(e){ e.preventDefault(); e.dataTransfer.dropEffect='copy'; this.classList.add('drag-over'); });
inputArea.addEventListener('dragleave', function(){ this.classList.remove('drag-over'); });
inputArea.addEventListener('drop', function(e){
    e.preventDefault();
    this.classList.remove('drag-over');
    try {
        var data = JSON.parse(e.dataTransfer.getData('text/plain'));
        quotedData = data;
        var q = document.getElementById('mcQuote');
        var chLabel = channelLabels[data.channel] || data.channel;
        document.getElementById('mcQuoteCh').innerHTML = '<i class="ri-reply-line"></i> Replying to <strong>' + (data.who || data.from) + '</strong> via <strong>' + chLabel + '</strong>';
        document.getElementById('mcQuoteText').textContent = data.text.substring(0,120) + (data.text.length>120?'...':'');
        q.classList.add('visible');
        var rchKey = channelMap[data.channel];
        if(rchKey){
            document.querySelectorAll('.mc-rch').forEach(function(c){ c.classList.remove('active'); });
            var target = document.querySelector('.mc-rch[data-ch="'+rchKey+'"]');
            if(target) target.classList.add('active');
        }
        document.getElementById('staffInput').focus();
    } catch(ex){}
});

function clearQuote(){
    document.getElementById('mcQuote').classList.remove('visible');
    quotedData = null;
}

// ===== Attachment dropup menu =====
function toggleAttachMenu(){
    var m = document.getElementById('attachMenu');
    m.classList.toggle('visible');
}
document.addEventListener('click', function(e){
    var m = document.getElementById('attachMenu');
    if(!e.target.closest('.mc-attach-wrap')) m.classList.remove('visible');
});
function attachAction(type){
    document.getElementById('attachMenu').classList.remove('visible');
    var labels = {
        file:'Upload File', photo:'Upload Photo', camera:'Take Photo', scan:'Scan Document',
        'case':'Save to Case File', gdrive:'Save to Google Drive', obsidian:'Save to Obsidian',
        notion:'Save to Notion', template:'Insert Template', docgen:'AI Generate Document'
    };
    alert('Action: ' + (labels[type]||type) + '\n\nThis will connect to the ' + type + ' integration.');
}

// Scroll to bottom
document.getElementById('mcMsgs').scrollTop = document.getElementById('mcMsgs').scrollHeight;
</script>
@endsection
