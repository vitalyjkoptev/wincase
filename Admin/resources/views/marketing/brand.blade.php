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
                        <h4 class="mb-0 fw-semibold">4.6<span class="fs-13 text-muted fw-normal"> / 5.0</span></h4>
                    </div>
                    <span class="badge bg-success-subtle text-success"><i class="ri-arrow-up-s-line"></i> +0.2</span>
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
                    <span class="badge bg-success-subtle text-success"><i class="ri-arrow-up-s-line"></i> +14</span>
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
                        <h4 class="mb-0 fw-semibold">72</h4>
                    </div>
                    <span class="badge bg-success-subtle text-success">Excellent</span>
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
function toast(msg,type='success'){const t=document.createElement('div');t.className=`alert alert-${type} position-fixed top-0 end-0 m-3 shadow`;t.style.zIndex='9999';t.innerHTML=msg;document.body.appendChild(t);setTimeout(()=>{t.style.opacity='0';setTimeout(()=>t.remove(),300)},3000);}
function n(v){return v?v.toLocaleString('pl-PL'):'0';}
function stars(r){let s='';for(let i=1;i<=5;i++){s+=i<=Math.floor(r)?'<i class="ri-star-fill"></i>':i-0.5<=r?'<i class="ri-star-half-fill"></i>':'<i class="ri-star-line"></i>';}return `<span class="text-warning">${s}</span>`;}

const platformCfg = {
    google:{icon:'ri-google-fill',color:'danger',label:'Google'},
    facebook:{icon:'ri-facebook-fill',color:'primary',label:'Facebook'},
    trustpilot:{icon:'ri-star-smile-line',color:'success',label:'Trustpilot'},
    gowork:{icon:'ri-briefcase-line',color:'info',label:'GoWork'},
};
function pBadge(p){const c=platformCfg[p];return c?`<span class="badge bg-${c.color}-subtle text-${c.color}"><i class="${c.icon} me-1"></i>${c.label}</span>`:`<span class="badge bg-light text-dark">${p}</span>`;}

// ── Reviews Data ──
const reviews = [
    {id:'r1',platform:'google',rating:5,author:'Olena Kovalenko',avatar:'OK',date:'2026-02-28',text:'Bardzo profesjonalna obsluga. Pani Marta pomogla mi z karta pobytu w rekordowym czasie. Od zlozenia dokumentow do odbioru karty minelo zaledwie 6 tygodni. Polecam kazdemu, kto potrzebuje pomocy z legalizacja pobytu!',lang:'PL',answered:true,reply:'Dziekujemy serdecznie za mila opinie! Cieszymy sie, ze proces przebiegl sprawnie. Zapraszamy ponownie!',replyDate:'2026-02-28'},
    {id:'r2',platform:'facebook',rating:4,author:'Andrii Petrov',avatar:'AP',date:'2026-02-26',text:'Szybka i sprawna pomoc przy rejestracji firmy. Jedyny minus — dlugi czas oczekiwania na pierwsza konsultacje (okolo 5 dni). Ale sama obsluga na najwyzszym poziomie.',lang:'PL',answered:false,reply:'',replyDate:''},
    {id:'r3',platform:'trustpilot',rating:5,author:'Maria Nowak',avatar:'MN',date:'2026-02-24',text:'Doskonale biuro imigracyjne w Warszawie. Pomagali mi od poczatku do konca z calym procesem uzyskiwania karty pobytu. Dokumenty byly przygotowane bezblednie, a prawnik odpowiadal na kazde pytanie.',lang:'PL',answered:false,reply:'',replyDate:''},
    {id:'r4',platform:'gowork',rating:3,author:'Igor Shevchenko',avatar:'IS',date:'2026-02-22',text:'Ogolnie OK, ale cennik mogl byc bardziej przejrzysty. Usluga wykonana poprawnie, dokumenty zlozone na czas. Brakuje mi jednak jasnej informacji o dodatkowych oplatach.',lang:'PL',answered:true,reply:'Dziekujemy za opinie. Pracujemy nad lepsza transparentnoscia cennika. Od marca wprowadzamy nowy, szczegolowy cennik na stronie.',replyDate:'2026-02-23'},
    {id:'r5',platform:'google',rating:5,author:'Li Wei',avatar:'LW',date:'2026-02-20',text:'Very professional service. They helped me with my work permit application in English, which was very convenient. The whole process was transparent and well-organized. Highly recommended!',lang:'EN',answered:true,reply:'Thank you for your kind words, Li! We are happy to assist in English. Welcome back anytime!',replyDate:'2026-02-21'},
    {id:'r6',platform:'google',rating:5,author:'Fatima Hassan',avatar:'FH',date:'2026-02-18',text:'The best immigration office in Warsaw! They helped me get my temporary residence permit within 2 months. The staff is very friendly and speaks multiple languages.',lang:'EN',answered:true,reply:'Thank you, Fatima! We are glad the process was smooth. If you need anything in the future, we are here to help!',replyDate:'2026-02-19'},
    {id:'r7',platform:'facebook',rating:5,author:'Raj Patel',avatar:'RP',date:'2026-02-16',text:'Wspaniale doswiadczenie! Zespol WinCase pomogl mi i mojej zonie z kartami pobytu. Wszystko sprawnie i bez stresu. Bardzo polecam!',lang:'PL',answered:true,reply:'Dziekujemy Raj! Milo nam slyszec, ze oboje Panstwo sa zadowoleni z naszych uslug!',replyDate:'2026-02-17'},
    {id:'r8',platform:'google',rating:2,author:'Dmitro Bondarenko',avatar:'DB',date:'2026-02-14',text:'Dlugo czekalem na odpowiedz na maile. Po 3 dniach dostalem odpowiedz, ale bez konkretow. Musialem sam dzwonic zeby uzyskac informacje. Obsluga mogla byc lepsza.',lang:'PL',answered:false,reply:'',replyDate:''},
    {id:'r9',platform:'trustpilot',rating:5,author:'Anna Zelinska',avatar:'AZ',date:'2026-02-12',text:'Profesjonalizm na najwyzszym poziomie! Pan Marek przeprowadzil mnie przez caly proces krok po kroku. Karta pobytu w 8 tygodni! Super!',lang:'PL',answered:true,reply:'Dziekujemy Anna! Pan Marek na pewno ucieszy sie z tych slow. Do zobaczenia!',replyDate:'2026-02-13'},
    {id:'r10',platform:'gowork',rating:5,author:'Thanh Nguyen',avatar:'TN',date:'2026-02-10',text:'Swietna obsluga po angielsku. Wszystkie dokumenty przygotowane profesjonalnie. Cena adekwatna do jakosci uslugi.',lang:'PL',answered:true,reply:'Dziekujemy Thanh! Staramy sie zapewniac obsluge w wielu jezykach.',replyDate:'2026-02-11'},
    {id:'r11',platform:'google',rating:4,author:'Carlos Rodriguez',avatar:'CR',date:'2026-02-08',text:'Good service overall. They helped with my business visa. Minor issues with communication timing, but the end result was perfect. Would recommend.',lang:'EN',answered:false,reply:'',replyDate:''},
    {id:'r12',platform:'facebook',rating:1,author:'Svitlana Melnyk',avatar:'SM',date:'2026-02-05',text:'Niestety nie jestem zadowolona. Obiecali zalatwienic sprawe w 4 tygodnie, a czekalam 3 miesiace. Komunikacja bardzo slaba. Nie polecam.',lang:'PL',answered:true,reply:'Przepraszamy za opoznienie, Pani Svitlano. Niestety urzad wojewodzki wydluzyl czasy rozpatrywania wnioskow. Kontaktujemy sie z Pania mailowo w celu wyjasnienia sytuacji.',replyDate:'2026-02-06'},
    {id:'r13',platform:'google',rating:5,author:'Piotr Kowalczyk',avatar:'PK',date:'2026-02-03',text:'Polecam! Pomogli mi z pozwoleniem na prace dla moich pracownikow z Ukrainy. Szybko, sprawnie i profesjonalnie. Obsluguja rowniez firmy, nie tylko osoby prywatne.',lang:'PL',answered:true,reply:'Dziekujemy Panie Piotrze! Cieszymy sie, ze moglimy pomoc w rekrutacji miedzynarodowej.',replyDate:'2026-02-04'},
    {id:'r14',platform:'trustpilot',rating:4,author:'Elena Popova',avatar:'EP',date:'2026-01-28',text:'Dobra obsluga, profesjonalni prawnicy. Jedyny minus to brak mozliwosci spotkania w weekend. Ale ogolnie bardzo zadowolona z efektu.',lang:'PL',answered:false,reply:'',replyDate:''},
    {id:'r15',platform:'google',rating:5,author:'Ahmed Al-Rashid',avatar:'AR',date:'2026-01-25',text:'Outstanding service! WinCase team made my immigration process stress-free. They handle everything from paperwork to appointments. Five stars from me!',lang:'EN',answered:true,reply:'Thank you, Ahmed! We appreciate your trust in our services. Welcome to Poland!',replyDate:'2026-01-26'},
    {id:'r16',platform:'gowork',rating:4,author:'Katarzyna Wisniewski',avatar:'KW',date:'2026-01-20',text:'Koleżanka polecila mi WinCase i nie zawiodlam sie. Pomoc przy karcie stalego pobytu przebiegla bez problemow. Jedyny minus — parking pod biurem.',lang:'PL',answered:true,reply:'Dziekujemy za opinie! Niestety parking nie jest pod naszym zarzadem, ale rekomendujemy parking podziemny obok.',replyDate:'2026-01-21'},
];

// ── Directories Data ──
const directories = [
    {id:'d1',name:'Google Business Profile',icon:'ri-google-fill',color:'danger',url:'https://g.co/wincase-eu',status:'active',rating:4.7,reviews:98,nap:true,updated:'2026-02-28'},
    {id:'d2',name:'Facebook Page',icon:'ri-facebook-fill',color:'primary',url:'https://www.facebook.com/profile.php?id=100083419746646',status:'active',rating:4.5,reviews:52,nap:true,updated:'2026-02-26'},
    {id:'d3',name:'Trustpilot',icon:'ri-star-smile-line',color:'success',url:'https://trustpilot.com/wincase.eu',status:'active',rating:4.3,reviews:24,nap:true,updated:'2026-02-20'},
    {id:'d4',name:'GoWork.pl',icon:'ri-briefcase-line',color:'info',url:'https://gowork.pl/wincase',status:'active',rating:4.8,reviews:13,nap:true,updated:'2026-02-15'},
    {id:'d5',name:'Panorama Firm',icon:'ri-building-4-line',color:'primary',url:'https://panoramafirm.pl/wincase',status:'active',rating:4.4,reviews:8,nap:true,updated:'2026-02-10'},
    {id:'d6',name:'PKT.pl',icon:'ri-map-pin-line',color:'warning',url:'https://pkt.pl/wincase',status:'active',rating:4.2,reviews:6,nap:true,updated:'2026-01-28'},
    {id:'d7',name:'Aleo.com',icon:'ri-building-line',color:'info',url:'https://aleo.com/pl/firma/wincase',status:'active',rating:0,reviews:0,nap:true,updated:'2026-01-15'},
    {id:'d8',name:'Firmy.net',icon:'ri-store-line',color:'success',url:'https://firmy.net/wincase',status:'active',rating:4.6,reviews:4,nap:false,updated:'2026-01-10'},
    {id:'d9',name:'Zumi.pl',icon:'ri-compass-line',color:'warning',url:'https://zumi.pl/wincase',status:'pending',rating:0,reviews:0,nap:false,updated:'2025-12-20'},
    {id:'d10',name:'Yelp',icon:'ri-star-line',color:'danger',url:'',status:'not_listed',rating:0,reviews:0,nap:false,updated:''},
    {id:'d11',name:'Cylex.pl',icon:'ri-global-line',color:'info',url:'https://cylex.pl/wincase',status:'active',rating:4.0,reviews:3,nap:true,updated:'2025-12-15'},
    {id:'d12',name:'Targeo.pl',icon:'ri-road-map-line',color:'primary',url:'',status:'not_listed',rating:0,reviews:0,nap:false,updated:''},
];

// ── Mentions Data ──
const mentions = [
    {id:'mt1',source:'Reddit r/poland',author:'u/immig_helper',content:'Has anyone used WinCase for their residence card? I had a great experience — they handled everything from A to Z. Definitely recommend for anyone in Warsaw.',sentiment:'positive',reach:2400,date:'2026-03-01',url:'https://reddit.com/r/poland/comments/abc123'},
    {id:'mt2',source:'Forum Gazeta.pl',author:'anna_waw',content:'Szukalam biura imigracyjnego i trafila na WinCase. Opinie w Google sa bardzo dobre, ktos korzystal? Ceny sa wyzsze niz u konkurencji ale moze warto?',sentiment:'neutral',reach:850,date:'2026-02-28',url:'https://forum.gazeta.pl/thread/xyz'},
    {id:'mt3',source:'Blog imigracja.info',author:'imigracja.info',content:'WinCase EU — jedno z najlepszych biur imigracyjnych w Warszawie. W naszym rankingu zajeli 2. miejsce za kompleksowa obsluge klientow z calego swiata.',sentiment:'positive',reach:12000,date:'2026-02-25',url:'https://imigracja.info/ranking-2026'},
    {id:'mt4',source:'Twitter/X',author:'@warsaw_expats',content:'Just got my karta pobytu thanks to @WinCaseEU! Process was smooth and the team was very helpful. If you need immigration help in Poland, check them out.',sentiment:'positive',reach:3200,date:'2026-02-22',url:'https://x.com/warsaw_expats/status/123'},
    {id:'mt5',source:'Facebook Group',author:'Ukraincy w Polsce',content:'Ktos slyszal o WinCase? Kolezanka miala problem z nimi — dlugo czekala na odpowiedz. Ale potem sprawa zostala zalatwiona. Mam mieszane uczucia.',sentiment:'neutral',reach:5600,date:'2026-02-20',url:'https://facebook.com/groups/ukraincy/posts/456'},
    {id:'mt6',source:'Wykop.pl',author:'immig2026',content:'Mialem zle doswiadczenie z jednym biurem imigracyjnym (nie WinCase, ale innym). WinCase chyba jest lepszy, widzialem dobre opinie.',sentiment:'neutral',reach:1800,date:'2026-02-18',url:'https://wykop.pl/link/7891011'},
    {id:'mt7',source:'YouTube Comment',author:'NewInPoland',content:'Thanks for the video! I used WinCase and can confirm — their service is top notch. My work permit was ready in 5 weeks.',sentiment:'positive',reach:1200,date:'2026-02-15',url:'https://youtube.com/watch?v=comment123'},
    {id:'mt8',source:'TikTok Comment',author:'@poland_life_tips',content:'WinCase reklama? Czy naprawde sa dobrzy? Widzialem ze maja duzo reklam w internecie ale zero negatywnych opinii, podejrzane.',sentiment:'negative',reach:8900,date:'2026-02-12',url:'https://tiktok.com/@poland_life_tips/video/789'},
    {id:'mt9',source:'Forum Expats.pl',author:'JohnUK',content:'Used WinCase for my EU Blue Card application. Everything went smoothly. Recommended by my employer as well. They specialize in corporate immigration too.',sentiment:'positive',reach:920,date:'2026-02-10',url:'https://expats.pl/thread/bluecard'},
    {id:'mt10',source:'Google News',author:'Rzeczpospolita',content:'Biura imigracyjne w Polsce notuja rekordowy wzrost klientow. Wsrod liderow rynku wymieniane sa m.in. WinCase EU, Migrant.pl i LegalStay.',sentiment:'positive',reach:45000,date:'2026-02-05',url:'https://rzeczpospolita.pl/artykul/biura-imigracyjne-2026'},
];

let currentReviewIdx = -1;

// ── Tabs ──
document.querySelectorAll('.br-tab').forEach(tab => {
    tab.addEventListener('click', function(){
        document.querySelectorAll('.br-tab').forEach(t => t.classList.remove('active'));
        this.classList.add('active');
        document.querySelectorAll('.br-section').forEach(s => s.classList.remove('active'));
        document.getElementById('section-'+this.dataset.tab).classList.add('active');
    });
});

// ── Review Sources ──
function renderSources(){
    const sources = [
        {platform:'google',reviews:reviews.filter(r=>r.platform==='google').length,avg:0,last:''},
        {platform:'facebook',reviews:reviews.filter(r=>r.platform==='facebook').length,avg:0,last:''},
        {platform:'trustpilot',reviews:reviews.filter(r=>r.platform==='trustpilot').length,avg:0,last:''},
        {platform:'gowork',reviews:reviews.filter(r=>r.platform==='gowork').length,avg:0,last:''},
    ];
    sources.forEach(s=>{
        const pReviews = reviews.filter(r=>r.platform===s.platform);
        s.avg = pReviews.length ? (pReviews.reduce((a,r)=>a+r.rating,0)/pReviews.length).toFixed(1) : 0;
        s.last = pReviews.length ? pReviews.sort((a,b)=>b.date.localeCompare(a.date))[0].date : '—';
        const answered = pReviews.filter(r=>r.answered).length;
        s.responseRate = pReviews.length ? Math.round(answered/pReviews.length*100) : 0;
    });
    document.getElementById('sourcesBody').innerHTML = sources.map(s=>{
        const rateClass = s.responseRate>=80?'success':s.responseRate>=50?'warning':'danger';
        return `<tr>
            <td><div class="d-flex align-items-center gap-2"><i class="${platformCfg[s.platform].icon} text-${platformCfg[s.platform].color} fs-18"></i><span class="fw-medium">${platformCfg[s.platform].label}</span></div></td>
            <td class="text-center fw-semibold">${s.reviews}</td>
            <td class="text-center">${stars(parseFloat(s.avg))} <span class="ms-1 fw-medium">${s.avg}</span></td>
            <td class="text-center"><span class="badge bg-${rateClass}-subtle text-${rateClass}">${s.responseRate}%</span></td>
            <td>${s.last}</td>
            <td><a href="#" onclick="filterByPlatform('${s.platform}');return false;" class="btn btn-sm btn-soft-primary"><i class="ri-eye-line me-1"></i>View</a></td>
        </tr>`;
    }).join('');
}
function filterByPlatform(p){
    document.querySelector('.br-tab[data-tab="reviews"]').click();
    document.getElementById('reviewPlatform').value=p;
    renderReviews();
}

// ── Render Reviews ──
function renderReviews(){
    const filter = document.querySelector('.review-filter.btn-primary')?.dataset.filter||'all';
    const pf = document.getElementById('reviewPlatform').value;
    const rf = document.getElementById('reviewRating').value;
    const search = document.getElementById('reviewSearch').value.toLowerCase();
    let filtered = reviews.filter(r=>{
        if(filter==='unanswered' && r.answered) return false;
        if(filter==='answered' && !r.answered) return false;
        if(filter==='negative' && r.rating>3) return false;
        if(pf && r.platform!==pf) return false;
        if(rf && r.rating!==parseInt(rf)) return false;
        if(search && !r.text.toLowerCase().includes(search) && !r.author.toLowerCase().includes(search)) return false;
        return true;
    });
    document.getElementById('reviewsCount').textContent=filtered.length;
    document.getElementById('statTotalReviews').textContent=reviews.length;
    document.getElementById('statUnanswered').textContent=reviews.filter(r=>!r.answered).length;
    if(!filtered.length){document.getElementById('reviewsContainer').innerHTML='<div class="text-center text-muted py-4">No reviews found</div>';return;}
    document.getElementById('reviewsContainer').innerHTML='<div class="row">'+filtered.map((r,i)=>{
        const idx=reviews.indexOf(r);
        const borderClass=!r.answered?'border-warning':'';
        const ratingColor=r.rating>=4?'success':r.rating===3?'warning':'danger';
        return `<div class="col-xl-6 mb-3"><div class="border rounded-3 p-3 h-100 ${borderClass}" data-idx="${idx}">
            <div class="d-flex align-items-center justify-content-between mb-2">
                ${pBadge(r.platform)}
                <div class="d-flex align-items-center gap-2">
                    ${!r.answered?'<span class="badge bg-warning-subtle text-warning">Unanswered</span>':'<span class="badge bg-success-subtle text-success"><i class="ri-check-line me-1"></i>Replied</span>'}
                    ${stars(r.rating)}
                </div>
            </div>
            <div class="d-flex align-items-center gap-2 mb-2">
                <div class="avatar avatar-xs bg-${ratingColor}-subtle text-${ratingColor} rounded-circle"><span class="fs-12">${r.avatar}</span></div>
                <span class="fw-medium">${r.author}</span>
                <small class="text-muted">${r.date}</small>
                <span class="badge bg-light text-dark ms-auto">${r.lang}</span>
            </div>
            <p class="text-muted mb-2 fs-13">${r.text.length>180?r.text.substring(0,180)+'...':r.text}</p>
            ${r.answered?`<div class="bg-primary-subtle rounded p-2 mb-2 fs-13"><i class="ri-reply-line me-1 text-primary"></i>${r.reply.substring(0,100)}...</div>`:''}
            <div class="d-flex gap-1">
                <button class="btn btn-sm btn-soft-primary btn-view-rev" onclick="viewReview(${idx})"><i class="ri-eye-line me-1"></i>View</button>
                ${!r.answered?`<button class="btn btn-sm btn-soft-success btn-reply-rev" onclick="openReplyReview(${idx})"><i class="ri-reply-line me-1"></i>Reply</button>`:`<button class="btn btn-sm btn-soft-warning btn-edit-reply" onclick="openReplyReview(${idx})"><i class="ri-edit-line me-1"></i>Edit Reply</button>`}
                <button class="btn btn-sm btn-soft-danger" onclick="flagReviewByIdx(${idx})"><i class="ri-flag-line"></i></button>
            </div>
        </div></div>`;
    }).join('')+'</div>';
}
document.querySelectorAll('.review-filter').forEach(pill=>{
    pill.addEventListener('click',function(){
        document.querySelectorAll('.review-filter').forEach(p=>{p.classList.remove('btn-primary');p.classList.add('btn-light');});
        this.classList.remove('btn-light');this.classList.add('btn-primary');
        renderReviews();
    });
});
document.getElementById('reviewPlatform').addEventListener('change',renderReviews);
document.getElementById('reviewRating').addEventListener('change',renderReviews);
document.getElementById('reviewSearch').addEventListener('input',renderReviews);

function viewReview(idx){
    const r=reviews[idx]; currentReviewIdx=idx;
    document.getElementById('vrPlatform').innerHTML=pBadge(r.platform);
    document.getElementById('vrRating').innerHTML=stars(r.rating)+` <span class="ms-1">${r.rating}/5</span>`;
    document.getElementById('vrDate').textContent=r.date;
    document.getElementById('vrAuthor').textContent=r.author;
    document.getElementById('vrStatus').innerHTML=r.answered?'<span class="badge bg-success">Replied</span>':'<span class="badge bg-warning">Unanswered</span>';
    document.getElementById('vrLang').textContent=r.lang;
    document.getElementById('vrText').textContent=r.text;
    if(r.answered){
        document.getElementById('vrReplySection').style.display='block';
        document.getElementById('vrReply').textContent=r.reply;
        document.getElementById('vrReplyDate').textContent='Replied on: '+r.replyDate;
    } else {
        document.getElementById('vrReplySection').style.display='none';
    }
    new bootstrap.Modal(document.getElementById('viewReviewModal')).show();
}
function openReplyFromView(){
    bootstrap.Modal.getInstance(document.getElementById('viewReviewModal')).hide();
    setTimeout(()=>openReplyReview(currentReviewIdx),300);
}
function openReplyReview(idx){
    const r=reviews[idx]; currentReviewIdx=idx;
    document.getElementById('replyReviewIdx').value=idx;
    document.getElementById('rrPlatform').innerHTML=pBadge(r.platform);
    document.getElementById('rrStars').innerHTML=stars(r.rating);
    document.getElementById('rrDate').textContent=r.date;
    document.getElementById('rrAvatar').textContent=r.avatar;
    document.getElementById('rrName').textContent=r.author;
    document.getElementById('rrText').textContent=r.text;
    document.getElementById('rrReply').value=r.reply||'';
    new bootstrap.Modal(document.getElementById('replyReviewModal')).show();
}
function insertTemplate(type){
    const templates={
        thank5:'Dziekujemy serdecznie za wspaniala opinie! Cieszymy sie, ze moglimy pomoc w procesie legalizacji. Zapraszamy ponownie i polecamy nas znajomym! 🙏',
        thank4:'Dziekujemy za pozytywna opinie! Doceniamy kazda sugestie — pracujemy nad tym, aby nasza obsluga byla jeszcze lepsza. Do zobaczenia!',
        sorry:'Przepraszamy za niedogodnosci. Bardzo nam zalezy na zadowoleniu kazdego klienta. Prosimy o kontakt mailowy (wincasetop@gmail.com), abysmy mogli wyjasnic sytuacje i znalezc rozwiazanie.',
        contact:'Dziekujemy za opinie. Prosimy o kontakt bezposrednio z naszym biurem: tel. +48 579 266 493 lub email wincasetop@gmail.com — chetnie pomozemy!',
        improve:'Dziekujemy za szczera opinie. Traktujemy ja bardzo powaznie i juz pracujemy nad ulepszeniami w tym obszarze. Mamy nadzieje, ze przy nastepnej wspolpracy spelnimy Panstwa oczekiwania w pelni.'
    };
    document.getElementById('rrReply').value=templates[type]||'';
}
function sendReviewReply(){
    const idx=parseInt(document.getElementById('replyReviewIdx').value);
    const reply=document.getElementById('rrReply').value.trim();
    if(!reply){toast('Please write a reply','danger');return;}
    reviews[idx].answered=true;
    reviews[idx].reply=reply;
    reviews[idx].replyDate='2026-03-02';
    bootstrap.Modal.getInstance(document.getElementById('replyReviewModal')).hide();
    renderReviews();renderSources();
    toast('Reply posted successfully');
}
function flagReview(){
    bootstrap.Modal.getInstance(document.getElementById('viewReviewModal')).hide();
    toast('Review flagged for moderation','warning');
}
function flagReviewByIdx(idx){toast('Review flagged for moderation','warning');}

// ── Directories ──
function renderDirectories(){
    document.getElementById('dirCount').textContent=directories.length;
    document.getElementById('directoriesBody').innerHTML=directories.map((d,i)=>{
        const stBadge=d.status==='active'?'<span class="badge bg-success-subtle text-success">Active</span>':d.status==='pending'?'<span class="badge bg-warning-subtle text-warning">Pending</span>':'<span class="badge bg-secondary-subtle text-secondary">Not Listed</span>';
        const napBadge=d.nap?'<span class="badge bg-success-subtle text-success"><i class="ri-check-line"></i> Correct</span>':'<span class="badge bg-danger-subtle text-danger"><i class="ri-close-line"></i> Mismatch</span>';
        return `<tr>
            <td><div class="d-flex align-items-center gap-2"><i class="${d.icon} text-${d.color} fs-18"></i><span class="fw-medium">${d.name}</span></div></td>
            <td>${d.url?`<a href="${d.url}" target="_blank" class="text-primary fs-13">${d.url.replace('https://','')}</a>`:'<span class="text-muted">—</span>'}</td>
            <td class="text-center">${stBadge}</td>
            <td class="text-center">${d.rating>0?stars(d.rating)+' '+d.rating:'<span class="text-muted">—</span>'}</td>
            <td class="text-center">${d.reviews||'—'}</td>
            <td>${d.status!=='not_listed'?napBadge:'<span class="text-muted">—</span>'}</td>
            <td>${d.updated||'—'}</td>
            <td><div class="d-flex gap-1">
                ${d.url?`<a href="${d.url}" target="_blank" class="btn btn-sm btn-soft-primary"><i class="ri-external-link-line"></i></a>`:''}
                ${d.status==='not_listed'?`<button class="btn btn-sm btn-soft-success" onclick="claimDirectory(${i})"><i class="ri-add-line me-1"></i>Claim</button>`:`<button class="btn btn-sm btn-soft-warning" onclick="toast('Opening editor...','info')"><i class="ri-edit-line"></i></button>`}
                ${!d.nap&&d.status!=='not_listed'?`<button class="btn btn-sm btn-soft-danger" onclick="fixNap(${i})" title="Fix NAP"><i class="ri-tools-line"></i></button>`:''}
            </div></td>
        </tr>`;
    }).join('');
}
function claimDirectory(idx){directories[idx].status='pending';directories[idx].updated='2026-03-02';renderDirectories();toast('Claim request submitted');}
function fixNap(idx){directories[idx].nap=true;directories[idx].updated='2026-03-02';renderDirectories();toast('NAP information updated');}
function showAddDirectory(){new bootstrap.Modal(document.getElementById('addDirectoryModal')).show();}
function addDirectory(){
    const name=document.getElementById('newDirName').value.trim();
    if(!name){toast('Enter directory name','danger');return;}
    directories.push({id:'d'+(directories.length+1),name:name,icon:'ri-building-line',color:'info',url:document.getElementById('newDirUrl').value,status:document.getElementById('newDirStatus').value,rating:0,reviews:0,nap:false,updated:'2026-03-02'});
    bootstrap.Modal.getInstance(document.getElementById('addDirectoryModal')).hide();
    renderDirectories();toast('Directory listing added');
}

// ── Mentions ──
function renderMentions(){
    const filter=document.querySelector('.mention-filter.btn-primary')?.dataset.filter||'all';
    const search=document.getElementById('mentionSearch').value.toLowerCase();
    let filtered=mentions.filter(m=>{
        if(filter!=='all'&&m.sentiment!==filter) return false;
        if(search&&!m.content.toLowerCase().includes(search)&&!m.source.toLowerCase().includes(search)) return false;
        return true;
    });
    const sentBadge=s=>s==='positive'?'<span class="badge bg-success-subtle text-success"><i class="ri-emotion-happy-line me-1"></i>Positive</span>':s==='negative'?'<span class="badge bg-danger-subtle text-danger"><i class="ri-emotion-unhappy-line me-1"></i>Negative</span>':'<span class="badge bg-secondary-subtle text-secondary"><i class="ri-emotion-normal-line me-1"></i>Neutral</span>';
    document.getElementById('mentionsBody').innerHTML=filtered.length?filtered.map((m,i)=>{
        const idx=mentions.indexOf(m);
        return `<tr>
            <td><span class="fw-medium">${m.source}</span><br><small class="text-muted">${m.author}</small></td>
            <td><span class="d-inline-block text-truncate" style="max-width:280px" title="${m.content}">${m.content}</span></td>
            <td>${sentBadge(m.sentiment)}</td>
            <td>${n(m.reach)}</td>
            <td>${m.date}</td>
            <td><div class="d-flex gap-1">
                <button class="btn btn-sm btn-soft-primary" onclick="viewMention(${idx})"><i class="ri-eye-line"></i></button>
                <a href="${m.url}" target="_blank" class="btn btn-sm btn-soft-info"><i class="ri-external-link-line"></i></a>
            </div></td>
        </tr>`;
    }).join(''):'<tr><td colspan="6" class="text-center text-muted py-4">No mentions found</td></tr>';
}
document.querySelectorAll('.mention-filter').forEach(pill=>{
    pill.addEventListener('click',function(){
        document.querySelectorAll('.mention-filter').forEach(p=>{p.classList.remove('btn-primary');p.classList.add('btn-light');});
        this.classList.remove('btn-light');this.classList.add('btn-primary');
        renderMentions();
    });
});
document.getElementById('mentionSearch').addEventListener('input',renderMentions);

function viewMention(idx){
    const m=mentions[idx];
    document.getElementById('vmSource').textContent=m.source;
    document.getElementById('vmAuthor').textContent=m.author;
    document.getElementById('vmDate').textContent=m.date;
    const sentBadge=m.sentiment==='positive'?'<span class="badge bg-success">Positive</span>':m.sentiment==='negative'?'<span class="badge bg-danger">Negative</span>':'<span class="badge bg-secondary">Neutral</span>';
    document.getElementById('vmSentiment').innerHTML=sentBadge;
    document.getElementById('vmReach').textContent=n(m.reach);
    document.getElementById('vmUrl').innerHTML=`<a href="${m.url}" target="_blank" class="text-primary">${m.url}</a>`;
    document.getElementById('vmContent').textContent=m.content;
    new bootstrap.Modal(document.getElementById('viewMentionModal')).show();
}

// ── Request Review ──
function showRequestReview(){new bootstrap.Modal(document.getElementById('requestReviewModal')).show();}
function sendReviewRequest(){
    const client=document.getElementById('reqClient').value;
    if(!client){toast('Select a client','danger');return;}
    bootstrap.Modal.getInstance(document.getElementById('requestReviewModal')).hide();
    toast('Review request sent successfully!');
}

// ── Export ──
function exportReviews(){
    let csv='Platform,Author,Rating,Date,Language,Status,Review,Reply\n';
    reviews.forEach(r=>{csv+=`"${platformCfg[r.platform]?.label}","${r.author}",${r.rating},"${r.date}","${r.lang}","${r.answered?'Answered':'Unanswered'}","${r.text.replace(/"/g,'""')}","${r.reply.replace(/"/g,'""')}"\n`;});
    const blob=new Blob([csv],{type:'text/csv'});
    const a=document.createElement('a');a.href=URL.createObjectURL(blob);a.download='reviews-export.csv';a.click();
    toast('Reviews exported to CSV');
}

// ── Charts ──
new ApexCharts(document.querySelector("#ratingDistributionChart"),{
    chart:{type:'bar',height:280,toolbar:{show:false}},
    series:[{name:'Reviews',data:[1,1,2,5,7]}],
    plotOptions:{bar:{horizontal:true,barHeight:'50%',borderRadius:4,distributed:true}},
    colors:['#ef4444','#f97316','#eab308','#84cc16','#22c55e'],
    xaxis:{categories:['1 Star','2 Stars','3 Stars','4 Stars','5 Stars'],title:{text:'Number of Reviews'}},
    dataLabels:{enabled:true,style:{fontSize:'13px',fontWeight:600}},
    legend:{show:false},grid:{borderColor:'#f1f1f1'},
    tooltip:{y:{formatter:v=>v+' reviews'}}
}).render();

new ApexCharts(document.querySelector("#reviewsTrendChart"),{
    chart:{type:'area',height:300,toolbar:{show:false}},
    series:[{name:'Reviews',data:[8,12,15,18,22,16]},{name:'Avg Rating',data:[4.3,4.4,4.5,4.5,4.6,4.7]}],
    xaxis:{categories:['Oct','Nov','Dec','Jan','Feb','Mar']},
    yaxis:[{title:{text:'Reviews Count'}},{opposite:true,title:{text:'Avg Rating'},min:3,max:5}],
    colors:['#3b82f6','#f59e0b'],
    stroke:{curve:'smooth',width:2},
    fill:{type:'gradient',gradient:{shadeIntensity:1,opacityFrom:0.3,opacityTo:0.1}},
    grid:{borderColor:'#f1f1f1'},legend:{position:'top'}
}).render();

new ApexCharts(document.querySelector("#ratingByPlatformChart"),{
    chart:{type:'radar',height:300,toolbar:{show:false}},
    series:[{name:'Rating',data:[4.7,4.5,4.3,4.8]}],
    xaxis:{categories:['Google','Facebook','Trustpilot','GoWork']},
    yaxis:{min:0,max:5,tickAmount:5},
    colors:['#3b82f6'],
    markers:{size:4},
    fill:{opacity:0.2}
}).render();

new ApexCharts(document.querySelector("#sentimentChart"),{
    chart:{type:'area',height:300,toolbar:{show:false},stacked:true},
    series:[{name:'Positive',data:[12,15,18,14,20,16]},{name:'Neutral',data:[5,8,6,7,4,6]},{name:'Negative',data:[2,1,3,1,2,1]}],
    xaxis:{categories:['Oct','Nov','Dec','Jan','Feb','Mar']},
    colors:['#22c55e','#94a3b8','#ef4444'],
    stroke:{curve:'smooth',width:2},
    fill:{type:'gradient',gradient:{shadeIntensity:1,opacityFrom:0.4,opacityTo:0.1}},
    grid:{borderColor:'#f1f1f1'},legend:{position:'top'}
}).render();

new ApexCharts(document.querySelector("#mentionSourcesChart"),{
    chart:{type:'donut',height:300},
    series:[4,2,3,1,1,1],
    labels:['Social Media','Forums','Blogs','News','YouTube','TikTok'],
    colors:['#3b82f6','#10b981','#f59e0b','#ef4444','#dc2626','#111827'],
    legend:{position:'bottom'},
    plotOptions:{pie:{donut:{labels:{show:true,total:{show:true,label:'Total',formatter:()=>'10'}}}}},
    dataLabels:{enabled:false}
}).render();

// ── Init ──
renderSources();
renderReviews();
renderDirectories();
renderMentions();
</script>
<style>
.br-section{display:none}
.br-section.active{display:block}
</style>
@endsection
