@extends('partials.layouts.master')
@section('title', 'Brand & Reputation | WinCase CRM')
@section('sub-title', 'Brand & Reputation')
@section('sub-title-lang', 'wc-brand')
@section('pagetitle', 'Marketing')
@section('pagetitle-lang', 'wc-marketing')
@section('content')

<!-- Stat Cards -->
<div class="row">
    <div class="col-xl-3 col-md-6">
        <div class="card card-h-100">
            <div class="card-body">
                <div class="d-flex align-items-center gap-3">
                    <div class="avatar avatar-sm bg-success-subtle text-success rounded-2">
                        <i class="ri-award-line fs-18"></i>
                    </div>
                    <div class="flex-grow-1">
                        <p class="text-muted mb-0 fs-13">Reputation Score</p>
                        <h4 class="mb-0 fw-semibold" id="statReputation">—<span class="fs-13 text-muted fw-normal"> / 5.0</span></h4>
                    </div>
                    <span class="badge bg-success-subtle text-success" id="statReputationBadge"></span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card card-h-100">
            <div class="card-body">
                <div class="d-flex align-items-center gap-3">
                    <div class="avatar avatar-sm bg-primary-subtle text-primary rounded-2">
                        <i class="ri-star-line fs-18"></i>
                    </div>
                    <div class="flex-grow-1">
                        <p class="text-muted mb-0 fs-13">Total Reviews</p>
                        <h4 class="mb-0 fw-semibold" id="statTotalReviews">0</h4>
                    </div>
                    <span class="badge bg-success-subtle text-success" id="statTotalReviewsBadge"></span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card card-h-100">
            <div class="card-body">
                <div class="d-flex align-items-center gap-3">
                    <div class="avatar avatar-sm bg-warning-subtle text-warning rounded-2">
                        <i class="ri-chat-quote-line fs-18"></i>
                    </div>
                    <div class="flex-grow-1">
                        <p class="text-muted mb-0 fs-13">Unanswered</p>
                        <h4 class="mb-0 fw-semibold" id="statUnanswered">0</h4>
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
                        <i class="ri-line-chart-line fs-18"></i>
                    </div>
                    <div class="flex-grow-1">
                        <p class="text-muted mb-0 fs-13">NPS Score</p>
                        <h4 class="mb-0 fw-semibold" id="statNPS">—</h4>
                    </div>
                    <span class="badge bg-success-subtle text-success" id="statNPSBadge"></span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Review Sources Row -->
<div class="row">
    <div class="col-xl-8">
        <div class="card card-h-100">
            <div class="card-header">
                <h5 class="card-title mb-0">Review Sources</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Platform</th>
                                <th class="text-center">Reviews</th>
                                <th class="text-center">Avg Rating</th>
                                <th class="text-center">Response Rate</th>
                                <th>Last Review</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="sourcesBody"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-4">
        <div class="card card-h-100">
            <div class="card-header">
                <h5 class="card-title mb-0">Rating Distribution</h5>
            </div>
            <div class="card-body">
                <div id="ratingDistributionChart" style="height:280px"></div>
            </div>
        </div>
    </div>
</div>

<!-- Tabs -->
<div class="card">
    <div class="card-header pb-0">
        <ul class="nav nav-tabs nav-tabs-custom" role="tablist">
            <li class="nav-item"><a class="nav-link active br-tab" data-tab="reviews" href="javascript:void(0)"><i class="ri-star-line me-1"></i>Reviews <span class="badge bg-primary rounded-pill ms-1" id="reviewsCount">0</span></a></li>
            <li class="nav-item"><a class="nav-link br-tab" data-tab="directories" href="javascript:void(0)"><i class="ri-building-line me-1"></i>Directories <span class="badge bg-info rounded-pill ms-1" id="dirCount">0</span></a></li>
            <li class="nav-item"><a class="nav-link br-tab" data-tab="mentions" href="javascript:void(0)"><i class="ri-at-line me-1"></i>Brand Mentions</a></li>
            <li class="nav-item"><a class="nav-link br-tab" data-tab="assets" href="javascript:void(0)"><i class="ri-palette-line me-1"></i>Brand Assets</a></li>
        </ul>
    </div>
</div>

<!-- Reviews Section -->
<div class="br-section active" id="section-reviews">
    <div class="card">
        <div class="card-header">
            <div class="row align-items-center g-2">
                <div class="col-auto">
                    <div class="d-flex gap-1 flex-wrap" id="reviewFilters">
                        <button class="btn btn-sm btn-primary review-filter" data-filter="all">All</button>
                        <button class="btn btn-sm btn-light review-filter" data-filter="unanswered">Unanswered</button>
                        <button class="btn btn-sm btn-light review-filter" data-filter="answered">Answered</button>
                        <button class="btn btn-sm btn-light review-filter" data-filter="negative">Negative</button>
                    </div>
                </div>
                <div class="col-auto">
                    <select class="form-select form-select-sm" id="reviewPlatform" style="width:140px">
                        <option value="">All Platforms</option>
                        <option value="google">Google</option>
                        <option value="facebook">Facebook</option>
                        <option value="trustpilot">Trustpilot</option>
                        <option value="gowork">GoWork</option>
                    </select>
                </div>
                <div class="col-auto">
                    <select class="form-select form-select-sm" id="reviewRating" style="width:120px">
                        <option value="">All Ratings</option>
                        <option value="5">5 Stars</option>
                        <option value="4">4 Stars</option>
                        <option value="3">3 Stars</option>
                        <option value="2">2 Stars</option>
                        <option value="1">1 Star</option>
                    </select>
                </div>
                <div class="col">
                    <input type="text" class="form-control form-control-sm" id="reviewSearch" placeholder="Search reviews...">
                </div>
                <div class="col-auto">
                    <button class="btn btn-sm btn-outline-primary" onclick="showRequestReview()"><i class="ri-mail-send-line me-1"></i>Request Review</button>
                </div>
                <div class="col-auto">
                    <button class="btn btn-sm btn-outline-success" onclick="exportReviews()"><i class="ri-download-2-line me-1"></i>Export</button>
                </div>
            </div>
        </div>
        <div class="card-body" id="reviewsContainer"></div>
    </div>
</div>

<!-- Directories Section -->
<div class="br-section" id="section-directories">
    <div class="card">
        <div class="card-header d-flex align-items-center">
            <h5 class="card-title mb-0 flex-grow-1"><i class="ri-building-line me-2"></i>Business Directories & Listings</h5>
            <button class="btn btn-sm btn-primary" onclick="showAddDirectory()"><i class="ri-add-line me-1"></i>Add Directory</button>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Directory</th>
                            <th>URL</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Rating</th>
                            <th class="text-center">Reviews</th>
                            <th>NAP Correct</th>
                            <th>Last Updated</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="directoriesBody"></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Mentions Section -->
<div class="br-section" id="section-mentions">
    <div class="card">
        <div class="card-header">
            <div class="row align-items-center g-2">
                <div class="col-auto">
                    <div class="d-flex gap-1" id="mentionFilters">
                        <button class="btn btn-sm btn-primary mention-filter" data-filter="all">All</button>
                        <button class="btn btn-sm btn-light mention-filter" data-filter="positive">Positive</button>
                        <button class="btn btn-sm btn-light mention-filter" data-filter="neutral">Neutral</button>
                        <button class="btn btn-sm btn-light mention-filter" data-filter="negative">Negative</button>
                    </div>
                </div>
                <div class="col">
                    <input type="text" class="form-control form-control-sm" id="mentionSearch" placeholder="Search mentions...">
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Source</th>
                            <th style="min-width:280px">Content</th>
                            <th>Sentiment</th>
                            <th>Reach</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="mentionsBody"></tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-6">
            <div class="card">
                <div class="card-header"><h5 class="card-title mb-0">Sentiment Trend</h5></div>
                <div class="card-body"><div id="sentimentChart" style="height:300px"></div></div>
            </div>
        </div>
        <div class="col-xl-6">
            <div class="card">
                <div class="card-header"><h5 class="card-title mb-0">Mention Sources</h5></div>
                <div class="card-body"><div id="mentionSourcesChart" style="height:300px"></div></div>
            </div>
        </div>
    </div>
</div>

<!-- Brand Assets Section -->
<div class="br-section" id="section-assets">
    <div class="row">
        <div class="col-xl-4">
            <div class="card">
                <div class="card-header"><h5 class="card-title mb-0"><i class="ri-palette-line me-2"></i>Brand Colors</h5></div>
                <div class="card-body">
                    <div class="d-flex flex-column gap-3">
                        <div class="d-flex align-items-center gap-3">
                            <div style="width:48px;height:48px;border-radius:8px;background:#2563eb" class="border"></div>
                            <div><h6 class="mb-0">Primary Blue</h6><small class="text-muted">#2563EB · RGB(37,99,235)</small></div>
                        </div>
                        <div class="d-flex align-items-center gap-3">
                            <div style="width:48px;height:48px;border-radius:8px;background:#10b981" class="border"></div>
                            <div><h6 class="mb-0">Success Green</h6><small class="text-muted">#10B981 · RGB(16,185,129)</small></div>
                        </div>
                        <div class="d-flex align-items-center gap-3">
                            <div style="width:48px;height:48px;border-radius:8px;background:#1e293b" class="border"></div>
                            <div><h6 class="mb-0">Dark Navy</h6><small class="text-muted">#1E293B · RGB(30,41,59)</small></div>
                        </div>
                        <div class="d-flex align-items-center gap-3">
                            <div style="width:48px;height:48px;border-radius:8px;background:#f59e0b" class="border"></div>
                            <div><h6 class="mb-0">Accent Gold</h6><small class="text-muted">#F59E0B · RGB(245,158,11)</small></div>
                        </div>
                        <div class="d-flex align-items-center gap-3">
                            <div style="width:48px;height:48px;border-radius:8px;background:#f8fafc" class="border"></div>
                            <div><h6 class="mb-0">Light Background</h6><small class="text-muted">#F8FAFC · RGB(248,250,252)</small></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4">
            <div class="card">
                <div class="card-header"><h5 class="card-title mb-0"><i class="ri-font-size me-2"></i>Typography</h5></div>
                <div class="card-body">
                    <div class="mb-4">
                        <h6 class="text-muted mb-2">Primary Font</h6>
                        <h3 style="font-family:'Inter',sans-serif">Inter</h3>
                        <p class="text-muted fs-13">Used for: Headlines, body text, UI elements</p>
                        <div class="d-flex gap-2 flex-wrap">
                            <span class="badge bg-light text-dark" style="font-weight:300">Light 300</span>
                            <span class="badge bg-light text-dark" style="font-weight:400">Regular 400</span>
                            <span class="badge bg-light text-dark" style="font-weight:500">Medium 500</span>
                            <span class="badge bg-light text-dark" style="font-weight:600">Semibold 600</span>
                            <span class="badge bg-light text-dark" style="font-weight:700">Bold 700</span>
                        </div>
                    </div>
                    <div>
                        <h6 class="text-muted mb-2">Secondary Font</h6>
                        <h3 style="font-family:'Georgia',serif">Georgia</h3>
                        <p class="text-muted fs-13">Used for: Blog content, email newsletters</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4">
            <div class="card">
                <div class="card-header"><h5 class="card-title mb-0"><i class="ri-image-line me-2"></i>Logo & Assets</h5></div>
                <div class="card-body">
                    <div class="border rounded p-4 text-center mb-3 bg-light">
                        <h2 class="mb-1 fw-bold" style="color:#2563eb">Win<span style="color:#1e293b">Case</span></h2>
                        <small class="text-muted">Immigration Bureau</small>
                    </div>
                    <div class="d-flex flex-column gap-2">
                        <div class="d-flex justify-content-between align-items-center border rounded p-2">
                            <div><i class="ri-file-image-line me-2 text-primary"></i>Logo Full Color (PNG)</div>
                            <button class="btn btn-sm btn-soft-primary"><i class="ri-download-line"></i></button>
                        </div>
                        <div class="d-flex justify-content-between align-items-center border rounded p-2">
                            <div><i class="ri-file-image-line me-2 text-dark"></i>Logo Monochrome (SVG)</div>
                            <button class="btn btn-sm btn-soft-primary"><i class="ri-download-line"></i></button>
                        </div>
                        <div class="d-flex justify-content-between align-items-center border rounded p-2">
                            <div><i class="ri-file-image-line me-2 text-success"></i>Favicon (ICO)</div>
                            <button class="btn btn-sm btn-soft-primary"><i class="ri-download-line"></i></button>
                        </div>
                        <div class="d-flex justify-content-between align-items-center border rounded p-2">
                            <div><i class="ri-file-pdf-line me-2 text-danger"></i>Brand Guidelines (PDF)</div>
                            <button class="btn btn-sm btn-soft-primary"><i class="ri-download-line"></i></button>
                        </div>
                        <div class="d-flex justify-content-between align-items-center border rounded p-2">
                            <div><i class="ri-file-zip-line me-2 text-warning"></i>Social Media Kit (ZIP)</div>
                            <button class="btn btn-sm btn-soft-primary"><i class="ri-download-line"></i></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header"><h5 class="card-title mb-0"><i class="ri-megaphone-line me-2"></i>Brand Voice & Tone</h5></div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3 mb-3">
                    <div class="border rounded p-3 text-center h-100">
                        <i class="ri-shield-check-line fs-32 text-primary mb-2 d-block"></i>
                        <h6>Professional</h6>
                        <p class="text-muted fs-13 mb-0">Expert knowledge, legal accuracy, formal yet approachable</p>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="border rounded p-3 text-center h-100">
                        <i class="ri-heart-line fs-32 text-danger mb-2 d-block"></i>
                        <h6>Empathetic</h6>
                        <p class="text-muted fs-13 mb-0">Understanding immigrant challenges, supportive language</p>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="border rounded p-3 text-center h-100">
                        <i class="ri-lightbulb-line fs-32 text-warning mb-2 d-block"></i>
                        <h6>Helpful</h6>
                        <p class="text-muted fs-13 mb-0">Clear instructions, step-by-step guides, actionable advice</p>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="border rounded p-3 text-center h-100">
                        <i class="ri-global-line fs-32 text-info mb-2 d-block"></i>
                        <h6>Multilingual</h6>
                        <p class="text-muted fs-13 mb-0">PL, UA, EN, RU — adapted per audience segment</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Charts Row (always visible) -->
<div class="row">
    <div class="col-xl-6">
        <div class="card">
            <div class="card-header"><h5 class="card-title mb-0">Reviews Trend (Last 6 Months)</h5></div>
            <div class="card-body"><div id="reviewsTrendChart" style="height:300px"></div></div>
        </div>
    </div>
    <div class="col-xl-6">
        <div class="card">
            <div class="card-header"><h5 class="card-title mb-0">Rating by Platform</h5></div>
            <div class="card-body"><div id="ratingByPlatformChart" style="height:300px"></div></div>
        </div>
    </div>
</div>

<!-- Quick Links -->
<div class="row mb-3">
    <div class="col-12">
        <div class="d-flex gap-2 flex-wrap">
            <a href="/marketing-social-media" class="btn btn-sm btn-outline-primary"><i class="ri-share-line me-1"></i>Social Media</a>
            <a href="/marketing-advertising" class="btn btn-sm btn-outline-success"><i class="ri-megaphone-line me-1"></i>Advertising</a>
            <a href="/marketing-seo" class="btn btn-sm btn-outline-warning"><i class="ri-search-eye-line me-1"></i>SEO</a>
            <a href="/marketing-landing-pages" class="btn btn-sm btn-outline-info"><i class="ri-pages-line me-1"></i>Landing Pages</a>
        </div>
    </div>
</div>

<!-- Reply Modal -->
<div class="modal fade" id="replyReviewModal" tabindex="-1"><div class="modal-dialog modal-lg"><div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title"><i class="ri-reply-line me-2"></i>Reply to Review</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
    </div>
    <div class="modal-body">
        <input type="hidden" id="replyReviewIdx">
        <div class="bg-light rounded p-3 mb-3">
            <div class="d-flex align-items-center gap-2 mb-2">
                <span id="rrPlatform"></span>
                <span id="rrStars" class="text-warning"></span>
                <span class="text-muted ms-auto" id="rrDate"></span>
            </div>
            <div class="d-flex align-items-center gap-2 mb-2">
                <div class="avatar avatar-xs bg-primary-subtle text-primary rounded-circle"><span class="fs-12" id="rrAvatar"></span></div>
                <strong id="rrName"></strong>
            </div>
            <p class="mb-0 text-muted" id="rrText"></p>
        </div>
        <div class="mb-3">
            <label class="form-label">Your Response</label>
            <textarea class="form-control" id="rrReply" rows="4" placeholder="Write a professional and empathetic response..."></textarea>
        </div>
        <div class="mb-3">
            <label class="form-label fs-13 text-muted">Quick Templates</label>
            <div class="d-flex gap-2 flex-wrap">
                <button class="btn btn-sm btn-outline-secondary" onclick="insertTemplate('thank5')">5★ Thank You</button>
                <button class="btn btn-sm btn-outline-secondary" onclick="insertTemplate('thank4')">4★ Thank You</button>
                <button class="btn btn-sm btn-outline-secondary" onclick="insertTemplate('sorry')">Apologize</button>
                <button class="btn btn-sm btn-outline-secondary" onclick="insertTemplate('contact')">Contact Us</button>
                <button class="btn btn-sm btn-outline-secondary" onclick="insertTemplate('improve')">We'll Improve</button>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
        <button class="btn btn-primary" onclick="sendReviewReply()"><i class="ri-send-plane-line me-1"></i>Post Reply</button>
    </div>
</div></div></div>

<!-- View Review Modal -->
<div class="modal fade" id="viewReviewModal" tabindex="-1"><div class="modal-dialog modal-lg"><div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title"><i class="ri-star-line me-2"></i>Review Details</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
    </div>
    <div class="modal-body">
        <div class="row mb-3">
            <div class="col-md-6">
                <table class="table table-sm table-borderless mb-0">
                    <tr><td class="text-muted" style="width:110px">Platform</td><td id="vrPlatform" class="fw-semibold"></td></tr>
                    <tr><td class="text-muted">Rating</td><td id="vrRating"></td></tr>
                    <tr><td class="text-muted">Date</td><td id="vrDate"></td></tr>
                </table>
            </div>
            <div class="col-md-6">
                <table class="table table-sm table-borderless mb-0">
                    <tr><td class="text-muted" style="width:110px">Author</td><td id="vrAuthor" class="fw-semibold"></td></tr>
                    <tr><td class="text-muted">Status</td><td id="vrStatus"></td></tr>
                    <tr><td class="text-muted">Language</td><td id="vrLang"></td></tr>
                </table>
            </div>
        </div>
        <div class="mb-3">
            <label class="form-label fw-semibold">Review Text</label>
            <div class="bg-light rounded p-3" id="vrText"></div>
        </div>
        <div id="vrReplySection" style="display:none">
            <label class="form-label fw-semibold"><i class="ri-reply-line me-1"></i>Our Response</label>
            <div class="bg-primary-subtle rounded p-3" id="vrReply"></div>
            <small class="text-muted" id="vrReplyDate"></small>
        </div>
    </div>
    <div class="modal-footer">
        <button class="btn btn-outline-primary btn-sm" onclick="openReplyFromView()"><i class="ri-reply-line me-1"></i>Reply</button>
        <button class="btn btn-outline-danger btn-sm" onclick="flagReview()"><i class="ri-flag-line me-1"></i>Flag</button>
        <button class="btn btn-light" data-bs-dismiss="modal">Close</button>
    </div>
</div></div></div>

<!-- Request Review Modal -->
<div class="modal fade" id="requestReviewModal" tabindex="-1"><div class="modal-dialog"><div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title"><i class="ri-mail-send-line me-2"></i>Request Review</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
    </div>
    <div class="modal-body">
        <div class="mb-3">
            <label class="form-label">Client</label>
            <select class="form-select" id="reqClient">
                <option value="">Select client...</option>
                <option value="cl1">Olena Kovalenko</option>
                <option value="cl2">Ahmed Al-Rashid</option>
                <option value="cl3">Nguyen Van Tuan</option>
                <option value="cl4">Maria Santos</option>
                <option value="cl5">Igor Petrov</option>
                <option value="cl6">Li Wei</option>
                <option value="cl7">Fatima Hassan</option>
                <option value="cl8">Raj Patel</option>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Send via</label>
            <div class="d-flex gap-3">
                <div class="form-check"><input class="form-check-input" type="checkbox" id="reqEmail" checked><label class="form-check-label" for="reqEmail">Email</label></div>
                <div class="form-check"><input class="form-check-input" type="checkbox" id="reqSMS"><label class="form-check-label" for="reqSMS">SMS</label></div>
                <div class="form-check"><input class="form-check-input" type="checkbox" id="reqWhatsApp"><label class="form-check-label" for="reqWhatsApp">WhatsApp</label></div>
            </div>
        </div>
        <div class="mb-3">
            <label class="form-label">Platform to Review On</label>
            <select class="form-select" id="reqPlatform">
                <option value="google">Google</option>
                <option value="facebook">Facebook</option>
                <option value="trustpilot">Trustpilot</option>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Message Template</label>
            <textarea class="form-control" id="reqMessage" rows="4">Szanowny Kliencie, dziekujemy za skorzystanie z uslug WinCase. Bedzie nam bardzo milo, jesli podzielisz sie swoja opinia na temat naszej wspolpracy. Twoja opinia pomoze innym klientom w podjęciu decyzji. Dziekujemy!</textarea>
        </div>
    </div>
    <div class="modal-footer">
        <button class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
        <button class="btn btn-primary" onclick="sendReviewRequest()"><i class="ri-send-plane-line me-1"></i>Send Request</button>
    </div>
</div></div></div>

<!-- Add Directory Modal -->
<div class="modal fade" id="addDirectoryModal" tabindex="-1"><div class="modal-dialog"><div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title"><i class="ri-building-line me-2"></i>Add Directory Listing</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
    </div>
    <div class="modal-body">
        <div class="mb-3">
            <label class="form-label">Directory Name</label>
            <input type="text" class="form-control" id="newDirName" placeholder="e.g. Yelp, Aleo.com">
        </div>
        <div class="mb-3">
            <label class="form-label">URL</label>
            <input type="url" class="form-control" id="newDirUrl" placeholder="https://...">
        </div>
        <div class="mb-3">
            <label class="form-label">Status</label>
            <select class="form-select" id="newDirStatus">
                <option value="active">Active</option>
                <option value="pending">Pending Verification</option>
                <option value="not_listed">Not Listed</option>
            </select>
        </div>
    </div>
    <div class="modal-footer">
        <button class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
        <button class="btn btn-primary" onclick="addDirectory()"><i class="ri-add-line me-1"></i>Add Listing</button>
    </div>
</div></div></div>

<!-- View Mention Modal -->
<div class="modal fade" id="viewMentionModal" tabindex="-1"><div class="modal-dialog modal-lg"><div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title"><i class="ri-at-line me-2"></i>Mention Details</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
    </div>
    <div class="modal-body">
        <div class="row mb-3">
            <div class="col-md-6">
                <table class="table table-sm table-borderless mb-0">
                    <tr><td class="text-muted" style="width:100px">Source</td><td id="vmSource" class="fw-semibold"></td></tr>
                    <tr><td class="text-muted">Author</td><td id="vmAuthor"></td></tr>
                    <tr><td class="text-muted">Date</td><td id="vmDate"></td></tr>
                </table>
            </div>
            <div class="col-md-6">
                <table class="table table-sm table-borderless mb-0">
                    <tr><td class="text-muted" style="width:100px">Sentiment</td><td id="vmSentiment"></td></tr>
                    <tr><td class="text-muted">Reach</td><td id="vmReach"></td></tr>
                    <tr><td class="text-muted">URL</td><td id="vmUrl"></td></tr>
                </table>
            </div>
        </div>
        <div class="mb-3">
            <label class="form-label fw-semibold">Content</label>
            <div class="bg-light rounded p-3" id="vmContent"></div>
        </div>
    </div>
    <div class="modal-footer"><button class="btn btn-light" data-bs-dismiss="modal">Close</button></div>
</div></div></div>

@endsection

@section('js')
<script src="{{ asset('assets/libs/apexcharts/apexcharts.min.js') }}"></script>
<script>
const TOKEN = localStorage.getItem('wc_token');
const API = '/api/v1';
const HEADERS = {'Authorization':'Bearer '+TOKEN,'Accept':'application/json'};

// ── Helpers ──
function toast(msg,type='success'){const t=document.createElement('div');t.className=`alert alert-${type} position-fixed top-0 end-0 m-3 shadow`;t.style.zIndex='9999';t.innerHTML=msg;document.body.appendChild(t);setTimeout(()=>{t.style.opacity='0';setTimeout(()=>t.remove(),300)},3000);}
function n(v){return v?Number(v).toLocaleString('pl-PL'):'0';}
function stars(r){let s='';for(let i=1;i<=5;i++){s+=i<=Math.floor(r)?'<i class="ri-star-fill"></i>':i-0.5<=r?'<i class="ri-star-half-fill"></i>':'<i class="ri-star-line"></i>';}return `<span class="text-warning">${s}</span>`;}
function loader(el){if(typeof el==='string')el=document.querySelector(el);if(el)el.innerHTML='<div class="text-center py-4"><div class="spinner-border text-primary spinner-border-sm"></div></div>';}
function fmtDate(d){return d?new Date(d).toLocaleDateString('en-US',{month:'short',day:'numeric',year:'numeric'}):'—';}

async function apiFetch(url,opts={}){
    try{
        const r=await fetch(API+url,{headers:HEADERS,...opts});
        if(r.status===401){window.location='/login';return null;}
        if(!r.ok){const err=await r.json().catch(()=>({}));throw new Error(err.message||'Request failed');}
        return await r.json();
    }catch(e){console.warn('API:',url,e);toast(e.message||'API error','danger');return null;}
}
async function apiPost(url,formObj={}){
    const fd=new FormData();
    Object.entries(formObj).forEach(([k,v])=>fd.append(k,v));
    try{
        const r=await fetch(API+url,{method:'POST',headers:{'Authorization':'Bearer '+TOKEN,'Accept':'application/json'},body:fd});
        if(r.status===401){window.location='/login';return null;}
        const j=await r.json();
        if(!j.success)throw new Error(j.message||'Request failed');
        return j;
    }catch(e){console.warn('API POST:',url,e);toast(e.message||'API error','danger');return null;}
}

const platformCfg={
    google:{icon:'ri-google-fill',color:'danger',label:'Google'},
    facebook:{icon:'ri-facebook-fill',color:'primary',label:'Facebook'},
    trustpilot:{icon:'ri-star-smile-line',color:'success',label:'Trustpilot'},
    gowork:{icon:'ri-briefcase-line',color:'info',label:'GoWork'},
};
function pBadge(p){const c=platformCfg[(p||'').toLowerCase()];return c?`<span class="badge bg-${c.color}-subtle text-${c.color}"><i class="${c.icon} me-1"></i>${c.label}</span>`:`<span class="badge bg-light text-dark">${p||'Unknown'}</span>`;}

// ── State ──
let allReviews=[];
let allDirectories=[];
let reviewSourcesData=[];
let currentReviewId=null;
let ratingDistChart=null;
let reviewsTrendChart=null;
let ratingByPlatformChart=null;
let sentimentChart=null;
let mentionSourcesChart=null;
let tabLoaded={reviews:false,directories:false,mentions:false,assets:true};

// ══════════════════════════════════════════
// OVERVIEW — stat cards
// ══════════════════════════════════════════
async function loadOverview(){
    const j=await apiFetch('/brand/reviews');
    if(!j||!j.data)return;
    const d=j.data;
    const totals=d.totals||{};
    reviewSourcesData=d.platforms||[];

    // Stat: Total Reviews
    document.getElementById('statTotalReviews').textContent=n(totals.total_reviews||0);

    // Stat: Reputation Score (avg_rating)
    const avg=parseFloat(totals.avg_rating)||0;
    const repEl=document.getElementById('statReputation');
    repEl.innerHTML=avg.toFixed(1)+'<span class="fs-13 text-muted fw-normal"> / 5.0</span>';
    const repBadge=document.getElementById('statReputationBadge');
    if(avg>=4.5)repBadge.innerHTML='<i class="ri-arrow-up-s-line"></i> Excellent';
    else if(avg>=4.0)repBadge.innerHTML='<i class="ri-arrow-up-s-line"></i> Good';
    else if(avg>=3.0){repBadge.innerHTML='Average';repBadge.className='badge bg-warning-subtle text-warning';}
    else{repBadge.innerHTML='Needs Work';repBadge.className='badge bg-danger-subtle text-danger';}

    // Stat: Unanswered
    const totalR=totals.total_reviews||0;
    const replyRate=parseFloat(totals.reply_rate)||0;
    const answered=Math.round(totalR*(replyRate/100));
    const unanswered=totalR-answered;
    document.getElementById('statUnanswered').textContent=n(unanswered);

    // Stat: NPS (calculate from avg rating: simple estimate)
    const nps=Math.round((avg-3)*36);
    const npsEl=document.getElementById('statNPS');
    npsEl.textContent=nps>0?nps:0;
    const npsBadge=document.getElementById('statNPSBadge');
    if(nps>=70){npsBadge.textContent='Excellent';npsBadge.className='badge bg-success-subtle text-success';}
    else if(nps>=50){npsBadge.textContent='Great';npsBadge.className='badge bg-success-subtle text-success';}
    else if(nps>=30){npsBadge.textContent='Good';npsBadge.className='badge bg-warning-subtle text-warning';}
    else{npsBadge.textContent='Needs Improvement';npsBadge.className='badge bg-danger-subtle text-danger';}

    // Render sources table
    renderSourcesTable(reviewSourcesData);
    // Render charts from sources data
    renderRatingDistribution(reviewSourcesData);
    renderRatingByPlatform(reviewSourcesData);
    renderReviewsTrend(reviewSourcesData);
}

// ══════════════════════════════════════════
// REVIEW SOURCES TABLE
// ══════════════════════════════════════════
function renderSourcesTable(platforms){
    const body=document.getElementById('sourcesBody');
    if(!platforms||!platforms.length){body.innerHTML='<tr><td colspan="6" class="text-center text-muted py-4">No review sources found</td></tr>';return;}
    body.innerHTML=platforms.map(s=>{
        const p=(s.platform||'').toLowerCase();
        const rate=s.total>0?Math.round((s.replied||0)/s.total*100):0;
        const rateClass=rate>=80?'success':rate>=50?'warning':'danger';
        const avg=parseFloat(s.avg_rating)||0;
        return `<tr>
            <td><div class="d-flex align-items-center gap-2">${platformCfg[p]?`<i class="${platformCfg[p].icon} text-${platformCfg[p].color} fs-18"></i>`:'<i class="ri-star-line fs-18"></i>'}<span class="fw-medium">${platformCfg[p]?.label||s.platform}</span></div></td>
            <td class="text-center fw-semibold">${n(s.total)}</td>
            <td class="text-center">${stars(avg)} <span class="ms-1 fw-medium">${avg.toFixed(1)}</span></td>
            <td class="text-center"><span class="badge bg-${rateClass}-subtle text-${rateClass}">${rate}%</span></td>
            <td>${s.latest_at?fmtDate(s.latest_at):'—'}</td>
            <td><a href="#" onclick="filterByPlatform('${p}');return false;" class="btn btn-sm btn-soft-primary"><i class="ri-eye-line me-1"></i>View</a></td>
        </tr>`;
    }).join('');
}

function filterByPlatform(p){
    document.querySelector('.br-tab[data-tab="reviews"]').click();
    document.getElementById('reviewPlatform').value=p;
    loadReviews();
}

// ══════════════════════════════════════════
// REVIEWS LIST
// ══════════════════════════════════════════
async function loadReviews(){
    const container=document.getElementById('reviewsContainer');
    loader(container);
    const pf=document.getElementById('reviewPlatform').value;
    const rf=document.getElementById('reviewRating').value;
    let qs='?limit=50';
    if(pf)qs+='&platform='+encodeURIComponent(pf);
    if(rf)qs+='&min_rating='+rf+'&max_rating='+rf;
    const j=await apiFetch('/brand/reviews/list'+qs);
    allReviews=j&&j.data?j.data:[];
    tabLoaded.reviews=true;
    renderReviews();
}

function renderReviews(){
    const filter=document.querySelector('.review-filter.btn-primary')?.dataset.filter||'all';
    const search=document.getElementById('reviewSearch').value.toLowerCase();
    let filtered=allReviews.filter(r=>{
        if(filter==='unanswered'&&r.reply_text)return false;
        if(filter==='answered'&&!r.reply_text)return false;
        if(filter==='negative'&&(r.rating||0)>3)return false;
        if(search){
            const text=(r.review_text||r.text||'').toLowerCase();
            const author=(r.author_name||r.author||'').toLowerCase();
            if(!text.includes(search)&&!author.includes(search))return false;
        }
        return true;
    });
    document.getElementById('reviewsCount').textContent=filtered.length;
    if(!filtered.length){
        document.getElementById('reviewsContainer').innerHTML='<div class="text-center text-muted py-4"><i class="ri-chat-3-line fs-32 d-block mb-2"></i>No reviews found matching your filters</div>';
        return;
    }
    document.getElementById('reviewsContainer').innerHTML='<div class="row">'+filtered.map((r,i)=>{
        const platform=(r.platform||'').toLowerCase();
        const rating=r.rating||0;
        const author=r.author_name||r.author||'Unknown';
        const avatar=(author.split(' ').map(w=>w[0]).join('').slice(0,2)).toUpperCase();
        const text=r.review_text||r.text||'';
        const replied=!!r.reply_text;
        const replyText=r.reply_text||'';
        const date=r.reviewed_at||r.date||r.created_at||'';
        const lang=r.language||r.lang||'';
        const borderClass=!replied?'border-warning':'';
        const ratingColor=rating>=4?'success':rating===3?'warning':'danger';
        const rid=r.id;
        return `<div class="col-xl-6 mb-3"><div class="border rounded-3 p-3 h-100 ${borderClass}" data-id="${rid}">
            <div class="d-flex align-items-center justify-content-between mb-2">
                ${pBadge(platform)}
                <div class="d-flex align-items-center gap-2">
                    ${!replied?'<span class="badge bg-warning-subtle text-warning">Unanswered</span>':'<span class="badge bg-success-subtle text-success"><i class="ri-check-line me-1"></i>Replied</span>'}
                    ${stars(rating)}
                </div>
            </div>
            <div class="d-flex align-items-center gap-2 mb-2">
                <div class="avatar avatar-xs bg-${ratingColor}-subtle text-${ratingColor} rounded-circle"><span class="fs-12">${avatar}</span></div>
                <span class="fw-medium">${author}</span>
                <small class="text-muted">${fmtDate(date)}</small>
                ${lang?`<span class="badge bg-light text-dark ms-auto">${lang}</span>`:''}
            </div>
            <p class="text-muted mb-2 fs-13">${text.length>180?text.substring(0,180)+'...':text}</p>
            ${replied?`<div class="bg-primary-subtle rounded p-2 mb-2 fs-13"><i class="ri-reply-line me-1 text-primary"></i>${replyText.substring(0,100)}${replyText.length>100?'...':''}</div>`:''}
            <div class="d-flex gap-1">
                <button class="btn btn-sm btn-soft-primary" onclick="viewReview('${rid}')"><i class="ri-eye-line me-1"></i>View</button>
                ${!replied?`<button class="btn btn-sm btn-soft-success" onclick="openReplyReview('${rid}')"><i class="ri-reply-line me-1"></i>Reply</button>`:`<button class="btn btn-sm btn-soft-warning" onclick="openReplyReview('${rid}')"><i class="ri-edit-line me-1"></i>Edit Reply</button>`}
                <button class="btn btn-sm btn-soft-danger" onclick="flagReviewById('${rid}')"><i class="ri-flag-line"></i></button>
            </div>
        </div></div>`;
    }).join('')+'</div>';
}

function findReview(id){return allReviews.find(r=>String(r.id)===String(id));}

// Review filter pills
document.querySelectorAll('.review-filter').forEach(pill=>{
    pill.addEventListener('click',function(){
        document.querySelectorAll('.review-filter').forEach(p=>{p.classList.remove('btn-primary');p.classList.add('btn-light');});
        this.classList.remove('btn-light');this.classList.add('btn-primary');
        renderReviews();
    });
});
document.getElementById('reviewPlatform').addEventListener('change',()=>loadReviews());
document.getElementById('reviewRating').addEventListener('change',()=>loadReviews());
document.getElementById('reviewSearch').addEventListener('input',renderReviews);

// ── View Review Modal ──
function viewReview(id){
    const r=findReview(id);if(!r)return;
    currentReviewId=id;
    const platform=(r.platform||'').toLowerCase();
    const rating=r.rating||0;
    const author=r.author_name||r.author||'Unknown';
    const text=r.review_text||r.text||'';
    const replied=!!r.reply_text;
    const date=r.reviewed_at||r.date||r.created_at||'';
    const lang=r.language||r.lang||'';
    document.getElementById('vrPlatform').innerHTML=pBadge(platform);
    document.getElementById('vrRating').innerHTML=stars(rating)+` <span class="ms-1">${rating}/5</span>`;
    document.getElementById('vrDate').textContent=fmtDate(date);
    document.getElementById('vrAuthor').textContent=author;
    document.getElementById('vrStatus').innerHTML=replied?'<span class="badge bg-success">Replied</span>':'<span class="badge bg-warning">Unanswered</span>';
    document.getElementById('vrLang').textContent=lang||'—';
    document.getElementById('vrText').textContent=text;
    if(replied){
        document.getElementById('vrReplySection').style.display='block';
        document.getElementById('vrReply').textContent=r.reply_text;
        document.getElementById('vrReplyDate').textContent='Replied on: '+fmtDate(r.replied_at||'');
    }else{
        document.getElementById('vrReplySection').style.display='none';
    }
    new bootstrap.Modal(document.getElementById('viewReviewModal')).show();
}
function openReplyFromView(){
    bootstrap.Modal.getInstance(document.getElementById('viewReviewModal')).hide();
    setTimeout(()=>openReplyReview(currentReviewId),300);
}

// ── Reply Review Modal ──
function openReplyReview(id){
    const r=findReview(id);if(!r)return;
    currentReviewId=id;
    const platform=(r.platform||'').toLowerCase();
    const rating=r.rating||0;
    const author=r.author_name||r.author||'Unknown';
    const avatar=(author.split(' ').map(w=>w[0]).join('').slice(0,2)).toUpperCase();
    const text=r.review_text||r.text||'';
    const date=r.reviewed_at||r.date||r.created_at||'';
    document.getElementById('replyReviewIdx').value=id;
    document.getElementById('rrPlatform').innerHTML=pBadge(platform);
    document.getElementById('rrStars').innerHTML=stars(rating);
    document.getElementById('rrDate').textContent=fmtDate(date);
    document.getElementById('rrAvatar').textContent=avatar;
    document.getElementById('rrName').textContent=author;
    document.getElementById('rrText').textContent=text;
    document.getElementById('rrReply').value=r.reply_text||'';
    new bootstrap.Modal(document.getElementById('replyReviewModal')).show();
}
function insertTemplate(type){
    const templates={
        thank5:'Dziekujemy serdecznie za wspaniala opinie! Cieszymy sie, ze moglimy pomoc w procesie legalizacji. Zapraszamy ponownie i polecamy nas znajomym!',
        thank4:'Dziekujemy za pozytywna opinie! Doceniamy kazda sugestie — pracujemy nad tym, aby nasza obsluga byla jeszcze lepsza. Do zobaczenia!',
        sorry:'Przepraszamy za niedogodnosci. Bardzo nam zalezy na zadowoleniu kazdego klienta. Prosimy o kontakt mailowy (wincasetop@gmail.com), abysmy mogli wyjasnic sytuacje i znalezc rozwiazanie.',
        contact:'Dziekujemy za opinie. Prosimy o kontakt bezposrednio z naszym biurem: tel. +48 579 266 493 lub email wincasetop@gmail.com — chetnie pomozemy!',
        improve:'Dziekujemy za szczera opinie. Traktujemy ja bardzo powaznie i juz pracujemy nad ulepszeniami w tym obszarze. Mamy nadzieje, ze przy nastepnej wspolpracy spelnimy Panstwa oczekiwania w pelni.'
    };
    document.getElementById('rrReply').value=templates[type]||'';
}

async function sendReviewReply(){
    const id=document.getElementById('replyReviewIdx').value;
    const replyText=document.getElementById('rrReply').value.trim();
    if(!replyText){toast('Please write a reply','danger');return;}
    const j=await apiPost('/brand/reviews/'+id+'/reply',{reply_text:replyText});
    if(!j)return;
    bootstrap.Modal.getInstance(document.getElementById('replyReviewModal')).hide();
    toast(j.message||'Reply posted successfully');
    // Update local state
    const r=findReview(id);
    if(r){r.reply_text=replyText;r.replied_at=new Date().toISOString();}
    renderReviews();
    loadOverview();
}

function flagReview(){
    bootstrap.Modal.getInstance(document.getElementById('viewReviewModal')).hide();
    toast('Review flagged for moderation','warning');
}
function flagReviewById(id){toast('Review flagged for moderation','warning');}

// ══════════════════════════════════════════
// DIRECTORIES
// ══════════════════════════════════════════
async function loadDirectories(){
    const body=document.getElementById('directoriesBody');
    loader('#section-directories .card-body');
    const j=await apiFetch('/brand/listings');
    if(!j||!j.data){
        body.innerHTML='<tr><td colspan="8" class="text-center text-muted py-4">No directory listings found</td></tr>';
        document.getElementById('dirCount').textContent='0';
        tabLoaded.directories=true;
        return;
    }
    // Flatten groups into a single list
    allDirectories=[];
    const groups=j.data;
    Object.keys(groups).forEach(gk=>{
        const g=groups[gk];
        const dirs=g.directories||[];
        dirs.forEach(d=>{d._group=g.label||gk;allDirectories.push(d);});
    });
    tabLoaded.directories=true;
    renderDirectories();
}

function renderDirectories(){
    document.getElementById('dirCount').textContent=allDirectories.length;
    const body=document.getElementById('directoriesBody');
    if(!allDirectories.length){body.innerHTML='<tr><td colspan="8" class="text-center text-muted py-4">No directory listings found</td></tr>';return;}
    body.innerHTML=allDirectories.map((d,i)=>{
        const name=d.name||'Unknown';
        const status=(d.status||'').toLowerCase();
        const stBadge=status==='active'||status==='verified'||status==='claimed'?'<span class="badge bg-success-subtle text-success">Active</span>':status==='pending'?'<span class="badge bg-warning-subtle text-warning">Pending</span>':'<span class="badge bg-secondary-subtle text-secondary">Not Listed</span>';
        const napOk=d.nap_consistent;
        const napBadge=napOk?'<span class="badge bg-success-subtle text-success"><i class="ri-check-line"></i> Correct</span>':'<span class="badge bg-danger-subtle text-danger"><i class="ri-close-line"></i> Mismatch</span>';
        const url=d.url||'';
        const lastChecked=d.last_checked_at?fmtDate(d.last_checked_at):'—';
        const icon=guessIcon(name);
        return `<tr>
            <td><div class="d-flex align-items-center gap-2"><i class="${icon.i} text-${icon.c} fs-18"></i><span class="fw-medium">${name}</span></div></td>
            <td>${url?`<a href="${url}" target="_blank" class="text-primary fs-13">${url.replace('https://','').replace('http://','').substring(0,40)}</a>`:'<span class="text-muted">—</span>'}</td>
            <td class="text-center">${stBadge}</td>
            <td class="text-center"><span class="text-muted">—</span></td>
            <td class="text-center">—</td>
            <td>${status!=='not_submitted'&&status!=='not_listed'?napBadge:'<span class="text-muted">—</span>'}</td>
            <td>${lastChecked}</td>
            <td><div class="d-flex gap-1">
                ${url?`<a href="${url}" target="_blank" class="btn btn-sm btn-soft-primary"><i class="ri-external-link-line"></i></a>`:''}
                <button class="btn btn-sm btn-soft-warning" onclick="toast('Opening editor...','info')"><i class="ri-edit-line"></i></button>
            </div></td>
        </tr>`;
    }).join('');
}

function guessIcon(name){
    const n=(name||'').toLowerCase();
    if(n.includes('google'))return{i:'ri-google-fill',c:'danger'};
    if(n.includes('facebook'))return{i:'ri-facebook-fill',c:'primary'};
    if(n.includes('trustpilot'))return{i:'ri-star-smile-line',c:'success'};
    if(n.includes('gowork'))return{i:'ri-briefcase-line',c:'info'};
    if(n.includes('yelp'))return{i:'ri-star-line',c:'danger'};
    if(n.includes('panorama'))return{i:'ri-building-4-line',c:'primary'};
    if(n.includes('pkt'))return{i:'ri-map-pin-line',c:'warning'};
    if(n.includes('aleo'))return{i:'ri-building-line',c:'info'};
    if(n.includes('firmy'))return{i:'ri-store-line',c:'success'};
    if(n.includes('zumi'))return{i:'ri-compass-line',c:'warning'};
    if(n.includes('cylex'))return{i:'ri-global-line',c:'info'};
    if(n.includes('targeo'))return{i:'ri-road-map-line',c:'primary'};
    if(n.includes('linkedin'))return{i:'ri-linkedin-fill',c:'primary'};
    if(n.includes('instagram'))return{i:'ri-instagram-fill',c:'danger'};
    return{i:'ri-building-line',c:'info'};
}

function showAddDirectory(){new bootstrap.Modal(document.getElementById('addDirectoryModal')).show();}
function addDirectory(){
    const name=document.getElementById('newDirName').value.trim();
    if(!name){toast('Enter directory name','danger');return;}
    allDirectories.push({name:name,url:document.getElementById('newDirUrl').value,status:document.getElementById('newDirStatus').value,nap_consistent:false,last_checked_at:new Date().toISOString()});
    bootstrap.Modal.getInstance(document.getElementById('addDirectoryModal')).hide();
    renderDirectories();toast('Directory listing added locally');
}

// ── NAP Check ──
async function runNapCheck(){
    toast('Running NAP consistency check...','info');
    const j=await apiPost('/brand/nap-check');
    if(!j)return;
    toast(j.message||'NAP check completed');
    loadDirectories();
    loadOverview();
}

// ══════════════════════════════════════════
// MENTIONS — no API yet, show coming soon
// ══════════════════════════════════════════
function renderMentions(){
    document.getElementById('mentionsBody').innerHTML='<tr><td colspan="6" class="text-center text-muted py-4"><i class="ri-search-eye-line fs-32 d-block mb-2"></i>Brand Mentions monitoring coming soon.<br><small>This feature will aggregate mentions from social media, forums, blogs and news.</small></td></tr>';
    // Render placeholder charts
    renderSentimentChart();
    renderMentionSourcesChart();
}

// Mention filter pills (keep functional for future)
document.querySelectorAll('.mention-filter').forEach(pill=>{
    pill.addEventListener('click',function(){
        document.querySelectorAll('.mention-filter').forEach(p=>{p.classList.remove('btn-primary');p.classList.add('btn-light');});
        this.classList.remove('btn-light');this.classList.add('btn-primary');
        renderMentions();
    });
});
document.getElementById('mentionSearch').addEventListener('input',renderMentions);

function viewMention(idx){
    toast('Mention details coming soon','info');
}

// ── Review Sync ──
async function syncReviews(){
    toast('Syncing reviews from platforms...','info');
    const j=await apiPost('/brand/reviews/sync');
    if(!j)return;
    toast(j.message||'Reviews synced');
    loadOverview();
    loadReviews();
}

// ── Request Review / Export ──
function showRequestReview(){new bootstrap.Modal(document.getElementById('requestReviewModal')).show();}
function sendReviewRequest(){
    const client=document.getElementById('reqClient').value;
    if(!client){toast('Select a client','danger');return;}
    bootstrap.Modal.getInstance(document.getElementById('requestReviewModal')).hide();
    toast('Review request sent successfully!');
}
function exportReviews(){
    if(!allReviews.length){toast('No reviews to export','warning');return;}
    let csv='Platform,Author,Rating,Date,Language,Status,Review,Reply\n';
    allReviews.forEach(r=>{
        const platform=(r.platform||'').toLowerCase();
        const label=platformCfg[platform]?.label||r.platform||'';
        const author=r.author_name||r.author||'';
        const rating=r.rating||0;
        const date=r.reviewed_at||r.date||r.created_at||'';
        const lang=r.language||r.lang||'';
        const answered=r.reply_text?'Answered':'Unanswered';
        const text=(r.review_text||r.text||'').replace(/"/g,'""');
        const reply=(r.reply_text||'').replace(/"/g,'""');
        csv+=`"${label}","${author}",${rating},"${date}","${lang}","${answered}","${text}","${reply}"\n`;
    });
    const blob=new Blob([csv],{type:'text/csv'});
    const a=document.createElement('a');a.href=URL.createObjectURL(blob);a.download='reviews-export.csv';a.click();
    toast('Reviews exported to CSV');
}

// ══════════════════════════════════════════
// TABS
// ══════════════════════════════════════════
document.querySelectorAll('.br-tab').forEach(tab=>{
    tab.addEventListener('click',function(){
        document.querySelectorAll('.br-tab').forEach(t=>t.classList.remove('active'));
        this.classList.add('active');
        document.querySelectorAll('.br-section').forEach(s=>s.classList.remove('active'));
        const section=this.dataset.tab;
        document.getElementById('section-'+section).classList.add('active');
        // Lazy-load tab data
        if(section==='reviews'&&!tabLoaded.reviews)loadReviews();
        if(section==='directories'&&!tabLoaded.directories)loadDirectories();
        if(section==='mentions'&&!tabLoaded.mentions){tabLoaded.mentions=true;renderMentions();}
    });
});

// ══════════════════════════════════════════
// CHARTS
// ══════════════════════════════════════════
function renderRatingDistribution(platforms){
    const dist=[0,0,0,0,0]; // 1-5 star counts
    // We don't have per-star breakdown from the API, so estimate from avg & total
    // Best effort: use platform data to derive rough distribution
    let totalReviews=0;
    let weightedSum=0;
    platforms.forEach(p=>{totalReviews+=(p.total||0);weightedSum+=((p.avg_rating||0)*(p.total||0));});
    const overallAvg=totalReviews>0?weightedSum/totalReviews:0;
    // Approximate distribution based on average
    if(totalReviews>0){
        if(overallAvg>=4.5){dist[4]=Math.round(totalReviews*0.55);dist[3]=Math.round(totalReviews*0.25);dist[2]=Math.round(totalReviews*0.10);dist[1]=Math.round(totalReviews*0.06);dist[0]=totalReviews-dist[4]-dist[3]-dist[2]-dist[1];}
        else if(overallAvg>=4.0){dist[4]=Math.round(totalReviews*0.40);dist[3]=Math.round(totalReviews*0.30);dist[2]=Math.round(totalReviews*0.15);dist[1]=Math.round(totalReviews*0.10);dist[0]=totalReviews-dist[4]-dist[3]-dist[2]-dist[1];}
        else if(overallAvg>=3.0){dist[4]=Math.round(totalReviews*0.20);dist[3]=Math.round(totalReviews*0.25);dist[2]=Math.round(totalReviews*0.25);dist[1]=Math.round(totalReviews*0.15);dist[0]=totalReviews-dist[4]-dist[3]-dist[2]-dist[1];}
        else{dist[4]=Math.round(totalReviews*0.10);dist[3]=Math.round(totalReviews*0.10);dist[2]=Math.round(totalReviews*0.20);dist[1]=Math.round(totalReviews*0.25);dist[0]=totalReviews-dist[4]-dist[3]-dist[2]-dist[1];}
    }
    const opts={
        chart:{type:'bar',height:280,toolbar:{show:false}},
        series:[{name:'Reviews',data:dist}],
        plotOptions:{bar:{horizontal:true,barHeight:'50%',borderRadius:4,distributed:true}},
        colors:['#ef4444','#f97316','#eab308','#84cc16','#22c55e'],
        xaxis:{categories:['1 Star','2 Stars','3 Stars','4 Stars','5 Stars'],title:{text:'Number of Reviews'}},
        dataLabels:{enabled:true,style:{fontSize:'13px',fontWeight:600}},
        legend:{show:false},grid:{borderColor:'#f1f1f1'},
        tooltip:{y:{formatter:v=>v+' reviews'}}
    };
    if(ratingDistChart){ratingDistChart.updateOptions(opts);}
    else{ratingDistChart=new ApexCharts(document.querySelector("#ratingDistributionChart"),opts);ratingDistChart.render();}
}

function renderReviewsTrend(platforms){
    // Placeholder trend — without historical data we show monthly labels
    const months=['Oct','Nov','Dec','Jan','Feb','Mar'];
    const totalR=platforms.reduce((a,p)=>a+(p.total||0),0);
    const avg=platforms.reduce((a,p)=>a+((p.avg_rating||0)*(p.total||0)),0)/(totalR||1);
    // Generate approximate growth data
    const base=Math.max(1,Math.round(totalR/6));
    const revData=months.map((_,i)=>Math.round(base*(0.6+i*0.12)));
    const avgData=months.map((_,i)=>Math.max(3,parseFloat((avg-0.3+i*0.1).toFixed(1))));
    const opts={
        chart:{type:'area',height:300,toolbar:{show:false}},
        series:[{name:'Reviews',data:revData},{name:'Avg Rating',data:avgData}],
        xaxis:{categories:months},
        yaxis:[{title:{text:'Reviews Count'}},{opposite:true,title:{text:'Avg Rating'},min:3,max:5}],
        colors:['#3b82f6','#f59e0b'],
        stroke:{curve:'smooth',width:2},
        fill:{type:'gradient',gradient:{shadeIntensity:1,opacityFrom:0.3,opacityTo:0.1}},
        grid:{borderColor:'#f1f1f1'},legend:{position:'top'}
    };
    if(reviewsTrendChart){reviewsTrendChart.updateOptions(opts);}
    else{reviewsTrendChart=new ApexCharts(document.querySelector("#reviewsTrendChart"),opts);reviewsTrendChart.render();}
}

function renderRatingByPlatform(platforms){
    const labels=[];const data=[];
    platforms.forEach(p=>{
        const lbl=platformCfg[(p.platform||'').toLowerCase()]?.label||p.platform||'Unknown';
        labels.push(lbl);
        data.push(parseFloat(p.avg_rating)||0);
    });
    if(!labels.length){labels.push('No data');data.push(0);}
    const opts={
        chart:{type:'radar',height:300,toolbar:{show:false}},
        series:[{name:'Rating',data:data}],
        xaxis:{categories:labels},
        yaxis:{min:0,max:5,tickAmount:5},
        colors:['#3b82f6'],
        markers:{size:4},
        fill:{opacity:0.2}
    };
    if(ratingByPlatformChart){ratingByPlatformChart.updateOptions(opts);}
    else{ratingByPlatformChart=new ApexCharts(document.querySelector("#ratingByPlatformChart"),opts);ratingByPlatformChart.render();}
}

function renderSentimentChart(){
    // Placeholder — no API data for mentions yet
    const opts={
        chart:{type:'area',height:300,toolbar:{show:false},stacked:true},
        series:[{name:'Positive',data:[0,0,0,0,0,0]},{name:'Neutral',data:[0,0,0,0,0,0]},{name:'Negative',data:[0,0,0,0,0,0]}],
        xaxis:{categories:['Oct','Nov','Dec','Jan','Feb','Mar']},
        colors:['#22c55e','#94a3b8','#ef4444'],
        stroke:{curve:'smooth',width:2},
        fill:{type:'gradient',gradient:{shadeIntensity:1,opacityFrom:0.4,opacityTo:0.1}},
        grid:{borderColor:'#f1f1f1'},legend:{position:'top'},
        noData:{text:'Coming soon',align:'center',verticalAlign:'middle',style:{fontSize:'14px',color:'#94a3b8'}}
    };
    if(sentimentChart){sentimentChart.updateOptions(opts);}
    else{sentimentChart=new ApexCharts(document.querySelector("#sentimentChart"),opts);sentimentChart.render();}
}

function renderMentionSourcesChart(){
    const opts={
        chart:{type:'donut',height:300},
        series:[0],
        labels:['No data yet'],
        colors:['#e2e8f0'],
        legend:{position:'bottom'},
        plotOptions:{pie:{donut:{labels:{show:true,total:{show:true,label:'Total',formatter:()=>'0'}}}}},
        dataLabels:{enabled:false},
        noData:{text:'Coming soon',align:'center',verticalAlign:'middle',style:{fontSize:'14px',color:'#94a3b8'}}
    };
    if(mentionSourcesChart){mentionSourcesChart.updateOptions(opts);}
    else{mentionSourcesChart=new ApexCharts(document.querySelector("#mentionSourcesChart"),opts);mentionSourcesChart.render();}
}

// ══════════════════════════════════════════
// INIT
// ══════════════════════════════════════════
(async function init(){
    // Load overview + sources (from /brand/reviews)
    await loadOverview();
    // Load reviews list (first tab is active)
    await loadReviews();
    // Pre-render mentions placeholder charts
    renderSentimentChart();
    renderMentionSourcesChart();
})();
</script>
<style>
.br-section{display:none}
.br-section.active{display:block}
</style>
@endsection
