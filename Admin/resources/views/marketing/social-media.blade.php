@extends('partials.layouts.master')
@section('title', 'Social Media | WinCase CRM')
@section('sub-title', 'Social Media')
@section('sub-title-lang', 'wc-social-media')
@section('pagetitle', 'Marketing')
@section('pagetitle-lang', 'wc-marketing')
@section('buttonTitle', 'Create Post')
@section('buttonTitle-lang', 'wc-create-post')
@section('modalTarget', 'createPostModal')
@section('content')

<!-- Stat Cards -->
<div class="row">
    <div class="col-xl-3 col-md-6">
        <div class="card card-h-100">
            <div class="card-body">
                <div class="d-flex align-items-center gap-3">
                    <div class="avatar avatar-sm bg-primary-subtle text-primary rounded-2">
                        <i class="ri-group-line fs-18"></i>
                    </div>
                    <div class="flex-grow-1">
                        <p class="text-muted mb-0 fs-13">Total Followers</p>
                        <h4 class="mb-0 fw-semibold">29,720</h4>
                    </div>
                    <span class="badge bg-success-subtle text-success"><i class="ri-arrow-up-s-line"></i> +3.2%</span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card card-h-100">
            <div class="card-body">
                <div class="d-flex align-items-center gap-3">
                    <div class="avatar avatar-sm bg-success-subtle text-success rounded-2">
                        <i class="ri-article-line fs-18"></i>
                    </div>
                    <div class="flex-grow-1">
                        <p class="text-muted mb-0 fs-13">Posts This Month</p>
                        <h4 class="mb-0 fw-semibold">24</h4>
                    </div>
                    <span class="badge bg-success-subtle text-success"><i class="ri-arrow-up-s-line"></i> +6</span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card card-h-100">
            <div class="card-body">
                <div class="d-flex align-items-center gap-3">
                    <div class="avatar avatar-sm bg-warning-subtle text-warning rounded-2">
                        <i class="ri-heart-3-line fs-18"></i>
                    </div>
                    <div class="flex-grow-1">
                        <p class="text-muted mb-0 fs-13">Engagement Rate</p>
                        <h4 class="mb-0 fw-semibold">4.7%</h4>
                    </div>
                    <span class="badge bg-success-subtle text-success"><i class="ri-arrow-up-s-line"></i> +0.5%</span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card card-h-100">
            <div class="card-body">
                <div class="d-flex align-items-center gap-3">
                    <div class="avatar avatar-sm bg-info-subtle text-info rounded-2">
                        <i class="ri-eye-line fs-18"></i>
                    </div>
                    <div class="flex-grow-1">
                        <p class="text-muted mb-0 fs-13">Total Reach</p>
                        <h4 class="mb-0 fw-semibold">187,400</h4>
                    </div>
                    <span class="badge bg-success-subtle text-success"><i class="ri-arrow-up-s-line"></i> +12%</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Platform Cards -->
<div class="row" id="platformCards">
    <div class="col-xl-3 col-md-6">
        <div class="card card-h-100 border-start border-primary border-3">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div class="d-flex align-items-center gap-2">
                        <i class="ri-facebook-fill fs-22 text-primary"></i>
                        <h6 class="card-title mb-0">Facebook</h6>
                    </div>
                    <span class="badge bg-success-subtle text-success fs-11">Connected</span>
                </div>
                <div class="row text-center">
                    <div class="col-4">
                        <h6 class="mb-0">12,400</h6>
                        <small class="text-muted">Followers</small>
                    </div>
                    <div class="col-4">
                        <h6 class="mb-0">156</h6>
                        <small class="text-muted">Posts</small>
                    </div>
                    <div class="col-4">
                        <h6 class="mb-0 text-success">5.1%</h6>
                        <small class="text-muted">Engage</small>
                    </div>
                </div>
                <div class="mt-2 d-flex justify-content-between align-items-center">
                    <small class="text-muted"><i class="ri-arrow-up-s-line text-success"></i> +280 this month</small>
                    <a href="https://www.facebook.com/profile.php?id=100083419746646" target="_blank" class="btn btn-sm btn-soft-primary px-2 py-0"><i class="ri-external-link-line"></i></a>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card card-h-100 border-start border-danger border-3">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div class="d-flex align-items-center gap-2">
                        <i class="ri-instagram-line fs-22 text-danger"></i>
                        <h6 class="card-title mb-0">Instagram</h6>
                    </div>
                    <span class="badge bg-success-subtle text-success fs-11">Connected</span>
                </div>
                <div class="row text-center">
                    <div class="col-4">
                        <h6 class="mb-0">8,200</h6>
                        <small class="text-muted">Followers</small>
                    </div>
                    <div class="col-4">
                        <h6 class="mb-0">89</h6>
                        <small class="text-muted">Posts</small>
                    </div>
                    <div class="col-4">
                        <h6 class="mb-0 text-success">4.8%</h6>
                        <small class="text-muted">Engage</small>
                    </div>
                </div>
                <div class="mt-2 d-flex justify-content-between align-items-center">
                    <small class="text-muted"><i class="ri-arrow-up-s-line text-success"></i> +190 this month</small>
                    <a href="https://www.instagram.com/wincase.legalization.pl" target="_blank" class="btn btn-sm btn-soft-danger px-2 py-0"><i class="ri-external-link-line"></i></a>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card card-h-100 border-start border-dark border-3">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div class="d-flex align-items-center gap-2">
                        <i class="ri-tiktok-line fs-22"></i>
                        <h6 class="card-title mb-0">TikTok</h6>
                    </div>
                    <span class="badge bg-success-subtle text-success fs-11">Connected</span>
                </div>
                <div class="row text-center">
                    <div class="col-4">
                        <h6 class="mb-0">3,100</h6>
                        <small class="text-muted">Followers</small>
                    </div>
                    <div class="col-4">
                        <h6 class="mb-0">23</h6>
                        <small class="text-muted">Videos</small>
                    </div>
                    <div class="col-4">
                        <h6 class="mb-0 text-success">7.2%</h6>
                        <small class="text-muted">Engage</small>
                    </div>
                </div>
                <div class="mt-2 d-flex justify-content-between align-items-center">
                    <small class="text-muted"><i class="ri-arrow-up-s-line text-success"></i> +420 this month</small>
                    <a href="https://www.tiktok.com/@wincase.legalization.pl" target="_blank" class="btn btn-sm btn-soft-dark px-2 py-0"><i class="ri-external-link-line"></i></a>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card card-h-100 border-start border-info border-3">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div class="d-flex align-items-center gap-2">
                        <i class="ri-linkedin-fill fs-22 text-info"></i>
                        <h6 class="card-title mb-0">LinkedIn</h6>
                    </div>
                    <span class="badge bg-success-subtle text-success fs-11">Connected</span>
                </div>
                <div class="row text-center">
                    <div class="col-4">
                        <h6 class="mb-0">800</h6>
                        <small class="text-muted">Followers</small>
                    </div>
                    <div class="col-4">
                        <h6 class="mb-0">12</h6>
                        <small class="text-muted">Posts</small>
                    </div>
                    <div class="col-4">
                        <h6 class="mb-0 text-success">2.3%</h6>
                        <small class="text-muted">Engage</small>
                    </div>
                </div>
                <div class="mt-2 d-flex justify-content-between align-items-center">
                    <small class="text-muted"><i class="ri-arrow-up-s-line text-success"></i> +35 this month</small>
                    <a href="https://linkedin.com/company/wincase" target="_blank" class="btn btn-sm btn-soft-info px-2 py-0"><i class="ri-external-link-line"></i></a>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-xl-3 col-md-6">
        <div class="card card-h-100 border-start border-danger border-3">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div class="d-flex align-items-center gap-2">
                        <i class="ri-youtube-fill fs-22 text-danger"></i>
                        <h6 class="card-title mb-0">YouTube</h6>
                    </div>
                    <span class="badge bg-success-subtle text-success fs-11">Connected</span>
                </div>
                <div class="row text-center">
                    <div class="col-4">
                        <h6 class="mb-0">1,450</h6>
                        <small class="text-muted">Subs</small>
                    </div>
                    <div class="col-4">
                        <h6 class="mb-0">34</h6>
                        <small class="text-muted">Videos</small>
                    </div>
                    <div class="col-4">
                        <h6 class="mb-0 text-success">2,340</h6>
                        <small class="text-muted">Avg Views</small>
                    </div>
                </div>
                <div class="mt-2 d-flex justify-content-between align-items-center">
                    <small class="text-muted"><i class="ri-arrow-up-s-line text-success"></i> +85 this month</small>
                    <a href="https://www.youtube.com/@WinCase" target="_blank" class="btn btn-sm btn-soft-danger px-2 py-0"><i class="ri-external-link-line"></i></a>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card card-h-100 border-start border-info border-3">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div class="d-flex align-items-center gap-2">
                        <i class="ri-telegram-fill fs-22 text-info"></i>
                        <h6 class="card-title mb-0">Telegram</h6>
                    </div>
                    <span class="badge bg-success-subtle text-success fs-11">Connected</span>
                </div>
                <div class="row text-center">
                    <div class="col-4">
                        <h6 class="mb-0">2,870</h6>
                        <small class="text-muted">Members</small>
                    </div>
                    <div class="col-4">
                        <h6 class="mb-0">67</h6>
                        <small class="text-muted">Posts</small>
                    </div>
                    <div class="col-4">
                        <h6 class="mb-0 text-success">1,120</h6>
                        <small class="text-muted">Avg Views</small>
                    </div>
                </div>
                <div class="mt-2 d-flex justify-content-between align-items-center">
                    <small class="text-muted"><i class="ri-arrow-up-s-line text-success"></i> +310 this month</small>
                    <a href="https://t.me/wincase_eu" target="_blank" class="btn btn-sm btn-soft-info px-2 py-0"><i class="ri-external-link-line"></i></a>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card card-h-100 border-start border-danger border-3">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div class="d-flex align-items-center gap-2">
                        <i class="ri-pinterest-fill fs-22 text-danger"></i>
                        <h6 class="card-title mb-0">Pinterest</h6>
                    </div>
                    <span class="badge bg-success-subtle text-success fs-11">Connected</span>
                </div>
                <div class="row text-center">
                    <div class="col-4">
                        <h6 class="mb-0">560</h6>
                        <small class="text-muted">Followers</small>
                    </div>
                    <div class="col-4">
                        <h6 class="mb-0">45</h6>
                        <small class="text-muted">Pins</small>
                    </div>
                    <div class="col-4">
                        <h6 class="mb-0 text-success">8,900</h6>
                        <small class="text-muted">Mo Views</small>
                    </div>
                </div>
                <div class="mt-2 d-flex justify-content-between align-items-center">
                    <small class="text-muted"><i class="ri-arrow-up-s-line text-success"></i> +45 this month</small>
                    <a href="https://www.pinterest.com/wincasepro" target="_blank" class="btn btn-sm btn-soft-danger px-2 py-0"><i class="ri-external-link-line"></i></a>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card card-h-100 border-start border-secondary border-3">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div class="d-flex align-items-center gap-2">
                        <i class="ri-threads-fill fs-22"></i>
                        <h6 class="card-title mb-0">Threads</h6>
                    </div>
                    <span class="badge bg-success-subtle text-success fs-11">Connected</span>
                </div>
                <div class="row text-center">
                    <div class="col-4">
                        <h6 class="mb-0">340</h6>
                        <small class="text-muted">Followers</small>
                    </div>
                    <div class="col-4">
                        <h6 class="mb-0">18</h6>
                        <small class="text-muted">Posts</small>
                    </div>
                    <div class="col-4">
                        <h6 class="mb-0 text-success">3.5%</h6>
                        <small class="text-muted">Engage</small>
                    </div>
                </div>
                <div class="mt-2 d-flex justify-content-between align-items-center">
                    <small class="text-muted"><i class="ri-arrow-up-s-line text-success"></i> +55 this month</small>
                    <a href="https://threads.net/@wincase.eu" target="_blank" class="btn btn-sm btn-soft-secondary px-2 py-0"><i class="ri-external-link-line"></i></a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Tabs -->
<div class="card">
    <div class="card-header pb-0">
        <ul class="nav nav-tabs nav-tabs-custom" role="tablist">
            <li class="nav-item"><a class="nav-link active sm-tab" data-tab="posts" href="javascript:void(0)"><i class="ri-file-list-3-line me-1"></i>Posts <span class="badge bg-primary rounded-pill ms-1" id="postsCount">0</span></a></li>
            <li class="nav-item"><a class="nav-link sm-tab" data-tab="scheduled" href="javascript:void(0)"><i class="ri-calendar-schedule-line me-1"></i>Scheduled <span class="badge bg-warning rounded-pill ms-1" id="scheduledCount">0</span></a></li>
            <li class="nav-item"><a class="nav-link sm-tab" data-tab="inbox" href="javascript:void(0)"><i class="ri-message-3-line me-1"></i>Inbox <span class="badge bg-danger rounded-pill ms-1" id="inboxCount">0</span></a></li>
            <li class="nav-item"><a class="nav-link sm-tab" data-tab="hashtags" href="javascript:void(0)"><i class="ri-hashtag me-1"></i>Hashtags</a></li>
        </ul>
    </div>
</div>

<!-- Posts Section -->
<div class="sm-section active" id="section-posts">
    <div class="card">
        <div class="card-header">
            <div class="row align-items-center g-2">
                <div class="col-auto">
                    <div class="d-flex gap-1 flex-wrap" id="postFilters">
                        <button class="btn btn-sm btn-primary post-filter-pill" data-filter="all">All</button>
                        <button class="btn btn-sm btn-light post-filter-pill" data-filter="published">Published</button>
                        <button class="btn btn-sm btn-light post-filter-pill" data-filter="draft">Draft</button>
                    </div>
                </div>
                <div class="col-auto">
                    <select class="form-select form-select-sm" id="postPlatformFilter" style="width:140px">
                        <option value="">All Platforms</option>
                        <option value="facebook">Facebook</option>
                        <option value="instagram">Instagram</option>
                        <option value="tiktok">TikTok</option>
                        <option value="linkedin">LinkedIn</option>
                        <option value="youtube">YouTube</option>
                        <option value="telegram">Telegram</option>
                        <option value="pinterest">Pinterest</option>
                        <option value="threads">Threads</option>
                    </select>
                </div>
                <div class="col-auto">
                    <select class="form-select form-select-sm" id="postTypeFilter" style="width:130px">
                        <option value="">All Types</option>
                        <option value="image">Image</option>
                        <option value="video">Video</option>
                        <option value="reel">Reel</option>
                        <option value="story">Story</option>
                        <option value="carousel">Carousel</option>
                        <option value="text">Text</option>
                        <option value="article">Article</option>
                        <option value="pin">Pin</option>
                    </select>
                </div>
                <div class="col">
                    <input type="text" class="form-control form-control-sm" id="postSearch" placeholder="Search posts...">
                </div>
                <div class="col-auto">
                    <button class="btn btn-sm btn-outline-success" onclick="exportPostsCSV()"><i class="ri-download-2-line me-1"></i>Export</button>
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Platform</th>
                            <th style="min-width:220px">Content</th>
                            <th>Type</th>
                            <th>Date</th>
                            <th>Status</th>
                            <th class="text-center">Likes</th>
                            <th class="text-center">Comments</th>
                            <th class="text-center">Shares</th>
                            <th class="text-center">Reach</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="postsTableBody"></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Scheduled Section -->
<div class="sm-section" id="section-scheduled">
    <div class="card">
        <div class="card-header d-flex align-items-center">
            <h5 class="card-title mb-0 flex-grow-1"><i class="ri-calendar-schedule-line me-2"></i>Scheduled Posts</h5>
            <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#createPostModal"><i class="ri-add-line me-1"></i>Schedule Post</button>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Platforms</th>
                            <th style="min-width:220px">Content</th>
                            <th>Type</th>
                            <th>Scheduled For</th>
                            <th>Created By</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="scheduledTableBody"></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Inbox Section -->
<div class="sm-section" id="section-inbox">
    <div class="card">
        <div class="card-header">
            <div class="row align-items-center g-2">
                <div class="col-auto">
                    <div class="d-flex gap-1" id="inboxFilters">
                        <button class="btn btn-sm btn-primary inbox-filter-pill" data-filter="all">All</button>
                        <button class="btn btn-sm btn-light inbox-filter-pill" data-filter="unread">Unread</button>
                        <button class="btn btn-sm btn-light inbox-filter-pill" data-filter="comments">Comments</button>
                        <button class="btn btn-sm btn-light inbox-filter-pill" data-filter="messages">Messages</button>
                    </div>
                </div>
                <div class="col-auto">
                    <select class="form-select form-select-sm" id="inboxPlatformFilter" style="width:140px">
                        <option value="">All Platforms</option>
                        <option value="facebook">Facebook</option>
                        <option value="instagram">Instagram</option>
                        <option value="tiktok">TikTok</option>
                        <option value="linkedin">LinkedIn</option>
                        <option value="youtube">YouTube</option>
                        <option value="telegram">Telegram</option>
                    </select>
                </div>
                <div class="col">
                    <input type="text" class="form-control form-control-sm" id="inboxSearch" placeholder="Search messages...">
                </div>
                <div class="col-auto">
                    <button class="btn btn-sm btn-outline-primary" onclick="markAllRead()"><i class="ri-check-double-line me-1"></i>Mark All Read</button>
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th style="width:30px"></th>
                            <th>Platform</th>
                            <th>From</th>
                            <th style="min-width:250px">Message</th>
                            <th>Type</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="inboxTableBody"></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Hashtags Section -->
<div class="sm-section" id="section-hashtags">
    <div class="card">
        <div class="card-header d-flex align-items-center">
            <h5 class="card-title mb-0 flex-grow-1"><i class="ri-hashtag me-2"></i>Tracked Hashtags</h5>
            <button class="btn btn-sm btn-primary" onclick="showAddHashtag()"><i class="ri-add-line me-1"></i>Add Hashtag</button>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Hashtag</th>
                            <th class="text-center">Our Posts</th>
                            <th class="text-center">Total Posts</th>
                            <th class="text-center">Avg Reach</th>
                            <th class="text-center">Avg Engagement</th>
                            <th>Trend</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="hashtagsTableBody"></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Charts Row -->
<div class="row">
    <div class="col-xl-8">
        <div class="card">
            <div class="card-header d-flex align-items-center">
                <h5 class="card-title mb-0 flex-grow-1">Engagement Overview (Last 7 Days)</h5>
                <div class="d-flex gap-1">
                    <button class="btn btn-sm btn-soft-primary active chart-range" data-range="7d">7D</button>
                    <button class="btn btn-sm btn-soft-primary chart-range" data-range="30d">30D</button>
                    <button class="btn btn-sm btn-soft-primary chart-range" data-range="90d">90D</button>
                </div>
            </div>
            <div class="card-body">
                <div id="engagementChart" style="height:350px"></div>
            </div>
        </div>
    </div>
    <div class="col-xl-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Followers Distribution</h5>
            </div>
            <div class="card-body">
                <div id="followersDonut" style="height:350px"></div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xl-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Content Performance by Type</h5>
            </div>
            <div class="card-body">
                <div id="contentTypeChart" style="height:300px"></div>
            </div>
        </div>
    </div>
    <div class="col-xl-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Followers Growth (Last 6 Months)</h5>
            </div>
            <div class="card-body">
                <div id="followersGrowthChart" style="height:300px"></div>
            </div>
        </div>
    </div>
</div>

<!-- Best Performing Posts -->
<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0"><i class="ri-trophy-line me-2 text-warning"></i>Top Performing Posts This Month</h5>
    </div>
    <div class="card-body">
        <div class="row" id="topPostsRow"></div>
    </div>
</div>

<!-- Quick Links -->
<div class="row mb-3">
    <div class="col-12">
        <div class="d-flex gap-2 flex-wrap">
            <a href="/marketing-advertising" class="btn btn-sm btn-outline-primary"><i class="ri-megaphone-line me-1"></i>Advertising</a>
            <a href="/marketing-seo" class="btn btn-sm btn-outline-success"><i class="ri-search-eye-line me-1"></i>SEO</a>
            <a href="/marketing-brand" class="btn btn-sm btn-outline-warning"><i class="ri-star-line me-1"></i>Brand & Reputation</a>
            <a href="/marketing-landing-pages" class="btn btn-sm btn-outline-info"><i class="ri-pages-line me-1"></i>Landing Pages</a>
            <a href="/content-articles" class="btn btn-sm btn-outline-secondary"><i class="ri-newspaper-line me-1"></i>News Pipeline</a>
        </div>
    </div>
</div>

<!-- View Post Modal -->
<div class="modal fade" id="viewPostModal" tabindex="-1"><div class="modal-dialog modal-lg"><div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title"><i class="ri-article-line me-2"></i>Post Details</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
    </div>
    <div class="modal-body">
        <div class="row mb-3">
            <div class="col-md-6">
                <table class="table table-sm table-borderless mb-0">
                    <tr><td class="text-muted" style="width:120px">Platform</td><td id="vpPlatform" class="fw-semibold"></td></tr>
                    <tr><td class="text-muted">Type</td><td id="vpType"></td></tr>
                    <tr><td class="text-muted">Status</td><td id="vpStatus"></td></tr>
                    <tr><td class="text-muted">Published</td><td id="vpDate"></td></tr>
                    <tr><td class="text-muted">Author</td><td id="vpAuthor"></td></tr>
                </table>
            </div>
            <div class="col-md-6">
                <table class="table table-sm table-borderless mb-0">
                    <tr><td class="text-muted" style="width:120px">Hashtags</td><td id="vpHashtags"></td></tr>
                    <tr><td class="text-muted">Link</td><td id="vpLink"></td></tr>
                    <tr><td class="text-muted">Campaign</td><td id="vpCampaign"></td></tr>
                </table>
            </div>
        </div>
        <div class="mb-3">
            <label class="form-label fw-semibold">Content</label>
            <div class="bg-light rounded p-3" id="vpContent" style="white-space:pre-wrap"></div>
        </div>
        <h6 class="fw-semibold mb-3"><i class="ri-bar-chart-2-line me-1"></i>Performance Metrics</h6>
        <div class="row g-3 mb-3">
            <div class="col-3"><div class="bg-primary-subtle rounded p-3 text-center"><h4 class="mb-0 text-primary" id="vpLikes">0</h4><small class="text-muted">Likes</small></div></div>
            <div class="col-3"><div class="bg-success-subtle rounded p-3 text-center"><h4 class="mb-0 text-success" id="vpComments">0</h4><small class="text-muted">Comments</small></div></div>
            <div class="col-3"><div class="bg-warning-subtle rounded p-3 text-center"><h4 class="mb-0 text-warning" id="vpShares">0</h4><small class="text-muted">Shares</small></div></div>
            <div class="col-3"><div class="bg-info-subtle rounded p-3 text-center"><h4 class="mb-0 text-info" id="vpReach">0</h4><small class="text-muted">Reach</small></div></div>
        </div>
        <div class="row g-3">
            <div class="col-4"><div class="border rounded p-3 text-center"><h5 class="mb-0" id="vpImpressions">0</h5><small class="text-muted">Impressions</small></div></div>
            <div class="col-4"><div class="border rounded p-3 text-center"><h5 class="mb-0" id="vpClicks">0</h5><small class="text-muted">Link Clicks</small></div></div>
            <div class="col-4"><div class="border rounded p-3 text-center"><h5 class="mb-0" id="vpEngRate">0%</h5><small class="text-muted">Engage Rate</small></div></div>
        </div>
    </div>
    <div class="modal-footer">
        <button class="btn btn-outline-info btn-sm" onclick="duplicatePost()"><i class="ri-file-copy-line me-1"></i>Duplicate</button>
        <button class="btn btn-outline-warning btn-sm" onclick="openEditFromView()"><i class="ri-edit-line me-1"></i>Edit</button>
        <button class="btn btn-light" data-bs-dismiss="modal">Close</button>
    </div>
</div></div></div>

<!-- Edit Post Modal -->
<div class="modal fade" id="editPostModal" tabindex="-1"><div class="modal-dialog modal-lg"><div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title"><i class="ri-edit-line me-2"></i>Edit Post</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
    </div>
    <div class="modal-body">
        <input type="hidden" id="editPostIdx">
        <div class="mb-3">
            <label class="form-label">Platform</label>
            <select class="form-select" id="editPlatform" disabled>
                <option value="facebook">Facebook</option><option value="instagram">Instagram</option>
                <option value="tiktok">TikTok</option><option value="linkedin">LinkedIn</option>
                <option value="youtube">YouTube</option><option value="telegram">Telegram</option>
                <option value="pinterest">Pinterest</option><option value="threads">Threads</option>
            </select>
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Post Type</label>
                <select class="form-select" id="editType">
                    <option value="image">Image</option><option value="video">Video</option>
                    <option value="reel">Reel</option><option value="story">Story</option>
                    <option value="carousel">Carousel</option><option value="text">Text</option>
                    <option value="article">Article</option><option value="pin">Pin</option>
                </select>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Status</label>
                <select class="form-select" id="editStatus">
                    <option value="published">Published</option><option value="draft">Draft</option>
                </select>
            </div>
        </div>
        <div class="mb-3">
            <label class="form-label">Content</label>
            <textarea class="form-control" id="editContent" rows="4"></textarea>
            <div class="d-flex justify-content-end mt-1"><small class="text-muted"><span id="editCharCount">0</span> characters</small></div>
        </div>
        <div class="mb-3">
            <label class="form-label">Hashtags</label>
            <input type="text" class="form-control" id="editHashtags" placeholder="#immigration #poland #kartapobytu">
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Campaign Link</label>
                <input type="text" class="form-control" id="editCampaign" placeholder="Campaign name (optional)">
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Link URL</label>
                <input type="url" class="form-control" id="editLink" placeholder="https://wincase.eu/...">
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
        <button class="btn btn-primary" onclick="saveEditPost()"><i class="ri-check-line me-1"></i>Save Changes</button>
    </div>
</div></div></div>

<!-- Create Post Modal -->
<div class="modal fade" id="createPostModal" tabindex="-1"><div class="modal-dialog modal-lg"><div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title"><i class="ri-quill-pen-line me-2"></i>Create Post</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
    </div>
    <div class="modal-body">
        <div class="mb-3">
            <label class="form-label fw-semibold">Platforms</label>
            <div class="d-flex gap-3 flex-wrap">
                <div class="form-check"><input class="form-check-input" type="checkbox" id="cpFB" value="facebook" checked><label class="form-check-label" for="cpFB"><i class="ri-facebook-fill text-primary me-1"></i>Facebook</label></div>
                <div class="form-check"><input class="form-check-input" type="checkbox" id="cpIG" value="instagram"><label class="form-check-label" for="cpIG"><i class="ri-instagram-line text-danger me-1"></i>Instagram</label></div>
                <div class="form-check"><input class="form-check-input" type="checkbox" id="cpTT" value="tiktok"><label class="form-check-label" for="cpTT"><i class="ri-tiktok-line me-1"></i>TikTok</label></div>
                <div class="form-check"><input class="form-check-input" type="checkbox" id="cpLI" value="linkedin"><label class="form-check-label" for="cpLI"><i class="ri-linkedin-fill text-info me-1"></i>LinkedIn</label></div>
                <div class="form-check"><input class="form-check-input" type="checkbox" id="cpYT" value="youtube"><label class="form-check-label" for="cpYT"><i class="ri-youtube-fill text-danger me-1"></i>YouTube</label></div>
                <div class="form-check"><input class="form-check-input" type="checkbox" id="cpTG" value="telegram"><label class="form-check-label" for="cpTG"><i class="ri-telegram-fill text-info me-1"></i>Telegram</label></div>
                <div class="form-check"><input class="form-check-input" type="checkbox" id="cpPN" value="pinterest"><label class="form-check-label" for="cpPN"><i class="ri-pinterest-fill text-danger me-1"></i>Pinterest</label></div>
                <div class="form-check"><input class="form-check-input" type="checkbox" id="cpTH" value="threads"><label class="form-check-label" for="cpTH"><i class="ri-threads-fill me-1"></i>Threads</label></div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Post Type</label>
                <select class="form-select" id="cpType">
                    <option value="image">Image Post</option><option value="video">Video</option>
                    <option value="reel">Reel / Short</option><option value="story">Story</option>
                    <option value="carousel">Carousel</option><option value="text">Text Only</option>
                    <option value="article">Article</option><option value="pin">Pin</option>
                </select>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Author</label>
                <select class="form-select" id="cpAuthor">
                    <option value="Anna Kowalska">Anna Kowalska</option>
                    <option value="Marek Wisniewski">Marek Wisniewski</option>
                    <option value="Piotr Zielinski">Piotr Zielinski</option>
                    <option value="AI Assistant">AI Assistant</option>
                </select>
            </div>
        </div>
        <div class="mb-3">
            <label class="form-label">Content</label>
            <textarea class="form-control" id="cpContent" rows="5" placeholder="Write your post content..."></textarea>
            <div class="d-flex justify-content-between mt-1">
                <small class="text-muted">Max: FB 63,206 | IG 2,200 | TT 2,200 | LI 3,000 | TW 280</small>
                <small class="text-muted"><span id="cpCharCount">0</span> characters</small>
            </div>
        </div>
        <div class="mb-3">
            <label class="form-label">Hashtags</label>
            <input type="text" class="form-control" id="cpHashtags" placeholder="#immigration #poland #kartapobytu #praca">
        </div>
        <div class="mb-3">
            <label class="form-label">Media Upload</label>
            <input type="file" class="form-control" id="cpMedia" accept="image/*,video/*" multiple>
            <div class="form-text">JPG, PNG, MP4, MOV — max 100MB per file</div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Link URL (optional)</label>
                <input type="url" class="form-control" id="cpLink" placeholder="https://wincase.eu/...">
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Campaign (optional)</label>
                <input type="text" class="form-control" id="cpCampaign" placeholder="Campaign name">
            </div>
        </div>
        <hr>
        <div class="row align-items-center">
            <div class="col-md-6 mb-3">
                <label class="form-label">Schedule Date & Time</label>
                <input type="datetime-local" class="form-control" id="cpSchedule">
            </div>
            <div class="col-md-6 mb-3 pt-md-4">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="cpPostNow" checked>
                    <label class="form-check-label" for="cpPostNow">Post now (ignore schedule)</label>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button class="btn btn-outline-secondary" onclick="saveAsDraft()"><i class="ri-draft-line me-1"></i>Save Draft</button>
        <button class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
        <button class="btn btn-primary" onclick="publishPost()"><i class="ri-send-plane-line me-1"></i>Publish</button>
    </div>
</div></div></div>

<!-- Reply Modal -->
<div class="modal fade" id="replyModal" tabindex="-1"><div class="modal-dialog"><div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title"><i class="ri-reply-line me-2"></i>Reply</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
    </div>
    <div class="modal-body">
        <input type="hidden" id="replyIdx">
        <div class="bg-light rounded p-3 mb-3">
            <div class="d-flex align-items-center gap-2 mb-2">
                <strong id="replyFrom"></strong>
                <span id="replyPlatformBadge"></span>
            </div>
            <p class="mb-0 text-muted" id="replyOriginal"></p>
        </div>
        <div class="mb-3">
            <label class="form-label">Your Reply</label>
            <textarea class="form-control" id="replyText" rows="3" placeholder="Type your reply..."></textarea>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-sm btn-outline-secondary" onclick="insertQuickReply('thank')"><i class="ri-heart-line me-1"></i>Thank you!</button>
            <button class="btn btn-sm btn-outline-secondary" onclick="insertQuickReply('contact')"><i class="ri-phone-line me-1"></i>Contact us</button>
            <button class="btn btn-sm btn-outline-secondary" onclick="insertQuickReply('dm')"><i class="ri-message-2-line me-1"></i>DM us</button>
        </div>
    </div>
    <div class="modal-footer">
        <button class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
        <button class="btn btn-primary" onclick="sendReply()"><i class="ri-send-plane-line me-1"></i>Send Reply</button>
    </div>
</div></div></div>

<!-- Post Stats Modal -->
<div class="modal fade" id="postStatsModal" tabindex="-1"><div class="modal-dialog modal-lg"><div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title"><i class="ri-bar-chart-2-line me-2"></i>Post Analytics</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
    </div>
    <div class="modal-body">
        <div class="row g-3 mb-4">
            <div class="col-md-3"><div class="border rounded p-3 text-center"><i class="ri-eye-line fs-24 text-info mb-1 d-block"></i><h4 class="mb-0" id="psImpressions">0</h4><small class="text-muted">Impressions</small></div></div>
            <div class="col-md-3"><div class="border rounded p-3 text-center"><i class="ri-group-line fs-24 text-primary mb-1 d-block"></i><h4 class="mb-0" id="psReach">0</h4><small class="text-muted">Reach</small></div></div>
            <div class="col-md-3"><div class="border rounded p-3 text-center"><i class="ri-heart-3-line fs-24 text-danger mb-1 d-block"></i><h4 class="mb-0" id="psEngagements">0</h4><small class="text-muted">Engagements</small></div></div>
            <div class="col-md-3"><div class="border rounded p-3 text-center"><i class="ri-percent-line fs-24 text-success mb-1 d-block"></i><h4 class="mb-0" id="psEngRate">0%</h4><small class="text-muted">Eng. Rate</small></div></div>
        </div>
        <h6 class="fw-semibold mb-3">Engagement Breakdown</h6>
        <div class="row g-3 mb-4">
            <div class="col-md-2 col-4"><div class="bg-primary-subtle rounded p-2 text-center"><h5 class="mb-0 text-primary" id="psLikes">0</h5><small>Likes</small></div></div>
            <div class="col-md-2 col-4"><div class="bg-success-subtle rounded p-2 text-center"><h5 class="mb-0 text-success" id="psComments">0</h5><small>Comments</small></div></div>
            <div class="col-md-2 col-4"><div class="bg-warning-subtle rounded p-2 text-center"><h5 class="mb-0 text-warning" id="psShares">0</h5><small>Shares</small></div></div>
            <div class="col-md-2 col-4"><div class="bg-info-subtle rounded p-2 text-center"><h5 class="mb-0 text-info" id="psSaves">0</h5><small>Saves</small></div></div>
            <div class="col-md-2 col-4"><div class="bg-secondary-subtle rounded p-2 text-center"><h5 class="mb-0" id="psClicks">0</h5><small>Clicks</small></div></div>
            <div class="col-md-2 col-4"><div class="bg-danger-subtle rounded p-2 text-center"><h5 class="mb-0 text-danger" id="psReplies">0</h5><small>Replies</small></div></div>
        </div>
        <h6 class="fw-semibold mb-3">Audience</h6>
        <div class="row g-3">
            <div class="col-md-4"><div class="border rounded p-3"><h6 class="text-muted mb-2">Top Locations</h6><div id="psLocations"></div></div></div>
            <div class="col-md-4"><div class="border rounded p-3"><h6 class="text-muted mb-2">Age Groups</h6><div id="psAges"></div></div></div>
            <div class="col-md-4"><div class="border rounded p-3"><h6 class="text-muted mb-2">Gender</h6><div id="psGender"></div></div></div>
        </div>
    </div>
    <div class="modal-footer">
        <button class="btn btn-light" data-bs-dismiss="modal">Close</button>
    </div>
</div></div></div>

<!-- Add Hashtag Modal -->
<div class="modal fade" id="addHashtagModal" tabindex="-1"><div class="modal-dialog"><div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title"><i class="ri-hashtag me-2"></i>Add Hashtag to Track</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
    </div>
    <div class="modal-body">
        <div class="mb-3">
            <label class="form-label">Hashtag</label>
            <div class="input-group">
                <span class="input-group-text">#</span>
                <input type="text" class="form-control" id="newHashtag" placeholder="immigration">
            </div>
        </div>
        <div class="mb-3">
            <label class="form-label">Platforms to Track</label>
            <div class="d-flex gap-3 flex-wrap">
                <div class="form-check"><input class="form-check-input" type="checkbox" id="htFB" checked><label class="form-check-label" for="htFB">Facebook</label></div>
                <div class="form-check"><input class="form-check-input" type="checkbox" id="htIG" checked><label class="form-check-label" for="htIG">Instagram</label></div>
                <div class="form-check"><input class="form-check-input" type="checkbox" id="htTT" checked><label class="form-check-label" for="htTT">TikTok</label></div>
                <div class="form-check"><input class="form-check-input" type="checkbox" id="htLI"><label class="form-check-label" for="htLI">LinkedIn</label></div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
        <button class="btn btn-primary" onclick="addHashtag()"><i class="ri-add-line me-1"></i>Start Tracking</button>
    </div>
</div></div></div>

@endsection

@section('js')
<script src="{{ asset('assets/libs/apexcharts/apexcharts.min.js') }}"></script>
<script>
// ── Platform Config ──
const platformCfg = {
    facebook:  { icon:'ri-facebook-fill', color:'primary', label:'Facebook' },
    instagram: { icon:'ri-instagram-line', color:'danger', label:'Instagram' },
    tiktok:    { icon:'ri-tiktok-line', color:'dark', label:'TikTok' },
    linkedin:  { icon:'ri-linkedin-fill', color:'info', label:'LinkedIn' },
    youtube:   { icon:'ri-youtube-fill', color:'danger', label:'YouTube' },
    telegram:  { icon:'ri-telegram-fill', color:'info', label:'Telegram' },
    pinterest: { icon:'ri-pinterest-fill', color:'danger', label:'Pinterest' },
    threads:   { icon:'ri-threads-fill', color:'secondary', label:'Threads' }
};
const typeCfg = {
    image:    { color:'info', label:'Image' },
    video:    { color:'success', label:'Video' },
    reel:     { color:'warning', label:'Reel' },
    story:    { color:'purple', label:'Story' },
    carousel: { color:'primary', label:'Carousel' },
    text:     { color:'secondary', label:'Text' },
    article:  { color:'dark', label:'Article' },
    pin:      { color:'danger', label:'Pin' }
};
function pBadge(p){const c=platformCfg[p];return c?`<span class="badge bg-${c.color}-subtle text-${c.color}"><i class="${c.icon} me-1"></i>${c.label}</span>`:`<span class="badge bg-light text-dark">${p}</span>`;}
function tBadge(t){const c=typeCfg[t];return c?`<span class="badge bg-${c.color}-subtle text-${c.color}">${c.label}</span>`:`<span class="badge bg-light">${t}</span>`;}
function n(v){return v?v.toLocaleString('pl-PL'):'0';}
function toast(msg,type='success'){const t=document.createElement('div');t.className=`alert alert-${type} position-fixed top-0 end-0 m-3 shadow`;t.style.zIndex='9999';t.innerHTML=msg;document.body.appendChild(t);setTimeout(()=>{t.style.opacity='0';setTimeout(()=>t.remove(),300)},3000);}

// ── Posts Data ──
const posts = [
    {id:'p1',platform:'facebook',type:'image',status:'published',date:'2026-02-28',content:'Jak uzyskac karte pobytu w 2026? Kompleksowy poradnik krok po kroku. Sprawdz, jakie dokumenty sa potrzebne i ile trwa caly proces.',author:'Anna Kowalska',hashtags:'#kartapobytu #poland #immigration #legalizacja',likes:245,comments:38,shares:56,reach:6200,impressions:9800,clicks:340,saves:89,replies:12,campaign:'Karta Pobytu Guide',link:'https://wincase.eu/karta-pobytu'},
    {id:'p2',platform:'instagram',type:'reel',status:'published',date:'2026-02-27',content:'Nasi klienci o nas: historia sukcesu Oleny z Ukrainy. Od wniosku do karty pobytu w 45 dni! 🇵🇱✨',author:'Marek Wisniewski',hashtags:'#wincase #successstory #ukraine #kartapobytu #polska',likes:512,comments:67,shares:134,reach:12400,impressions:18900,clicks:520,saves:210,replies:23,campaign:'Client Stories',link:'https://wincase.eu/testimonials'},
    {id:'p3',platform:'tiktok',type:'video',status:'published',date:'2026-02-26',content:'3 bledy przy skladaniu wniosku o pozwolenie na prace! Unikaj ich, zeby nie stracic czasu i pieniedzy. #immigration #praca #poland',author:'Piotr Zielinski',hashtags:'#immigration #praca #poland #pozwolenie #tiktok',likes:823,comments:97,shares:256,reach:25200,impressions:38500,clicks:180,saves:340,replies:45,campaign:'Work Permit Tips',link:'https://wincase.eu/work-permit'},
    {id:'p4',platform:'facebook',type:'image',status:'published',date:'2026-02-25',content:'Zmiany w prawie imigracyjnym 2026 - co musisz wiedziec? Nowe przepisy wchodza w zycie od marca. Przeczytaj nasz przewodnik.',author:'Anna Kowalska',hashtags:'#prawo #imigracja #polska2026 #zmiany',likes:178,comments:25,shares:42,reach:4800,impressions:7200,clicks:290,saves:56,replies:8,campaign:'Legal Updates',link:'https://wincase.eu/blog/zmiany-2026'},
    {id:'p5',platform:'linkedin',type:'article',status:'published',date:'2026-02-24',content:'WinCase rozszerza zespol prawnikow. Szukamy specjalistow ds. prawa imigracyjnego i legalizacji pobytu. Dolacz do naszego zespolu!',author:'Anna Kowalska',hashtags:'#hiring #legalteam #wincase #career #warsaw',likes:67,comments:12,shares:18,reach:2800,impressions:4500,clicks:145,saves:23,replies:5,campaign:'',link:'https://wincase.eu/careers'},
    {id:'p6',platform:'youtube',type:'video',status:'published',date:'2026-02-23',content:'Karta pobytu 2026 — pelny poradnik wideo. Jak zlozyc wniosek, jakie dokumenty przygotowac, ile czekac na decyzje.',author:'Piotr Zielinski',hashtags:'#kartapobytu #poradnik #polska #youtube',likes:334,comments:41,shares:28,reach:8900,impressions:14200,clicks:420,saves:180,replies:15,campaign:'Video Guides',link:'https://youtube.com/watch?v=demo1'},
    {id:'p7',platform:'telegram',type:'text',status:'published',date:'2026-02-22',content:'Wazne! Od marca 2026 zmienia sie procedura skladania wnioskow o pozwolenie na prace. Szczegoly w naszym kanale.',author:'AI Assistant',hashtags:'','likes':0,comments:0,shares:42,reach:1890,impressions:2100,clicks:320,saves:0,replies:0,campaign:'','link':''},
    {id:'p8',platform:'pinterest',type:'pin',status:'published',date:'2026-02-21',content:'Infografika: 5 krokow do uzyskania karty pobytu w Polsce. Zapisz i udostepnij znajomym, ktorzy tego potrzebuja!',author:'Anna Kowalska',hashtags:'#infographic #kartapobytu #poland #steps',likes:87,comments:3,shares:134,reach:5400,impressions:8900,clicks:210,saves:290,replies:0,campaign:'Infographics',link:'https://wincase.eu/karta-pobytu'},
    {id:'p9',platform:'threads',type:'text',status:'published',date:'2026-02-20',content:'Czy wiesz, ze mozesz sprawdzic status swojego wniosku online? Oto jak to zrobic — krok po kroku. Wejdz na strone urzedu i...',author:'Marek Wisniewski',hashtags:'#tips #wniosek #status #polska',likes:38,comments:7,shares:12,reach:1200,impressions:1800,clicks:90,saves:15,replies:3,campaign:'','link':''},
    {id:'p10',platform:'instagram',type:'carousel',status:'published',date:'2026-02-19',content:'TOP 5 najczesciej zadawanych pytan o legalizacje pobytu w Polsce. Przesun, zeby poznac odpowiedzi! 👉',author:'Anna Kowalska',hashtags:'#FAQ #legalizacja #polska #carousel #wincase',likes:423,comments:56,shares:98,reach:9800,impressions:15600,clicks:380,saves:320,replies:18,campaign:'FAQ Series',link:'https://wincase.eu/faq'},
    {id:'p11',platform:'facebook',type:'video',status:'published',date:'2026-02-18',content:'Webinar: Jak zatrudnic pracownika z zagranicy? Praktyczny przewodnik dla pracodawcow. Zapis dostepny na naszym kanale.',author:'Piotr Zielinski',hashtags:'#webinar #pracodawca #zatrudnienie #cudzoziemcy',likes:156,comments:28,shares:45,reach:5600,impressions:8400,clicks:210,saves:78,replies:9,campaign:'Employer Webinars',link:'https://wincase.eu/webinars'},
    {id:'p12',platform:'tiktok',type:'reel',status:'published',date:'2026-02-17',content:'POV: Dostajesz karte pobytu po 3 miesiacach czekania 🎉😭 #immigration #kartapobytu #sukces #polska',author:'Marek Wisniewski',hashtags:'#POV #kartapobytu #sukces #polska #viral',likes:1240,comments:145,shares:380,reach:42000,impressions:68000,clicks:90,saves:520,replies:67,campaign:'Viral Content',link:''},
    {id:'p13',platform:'instagram',type:'story',status:'published',date:'2026-02-16',content:'Behind the scenes w biurze WinCase 🏢 Jak wyglada nasz dzien pracy? Sprawdz w relacji!',author:'Anna Kowalska',hashtags:'#behindthescenes #office #wincase #team',likes:189,comments:12,shares:8,reach:3400,impressions:4200,clicks:45,saves:12,replies:4,campaign:'','link':''},
    {id:'p14',platform:'linkedin',type:'text',status:'published',date:'2026-02-15',content:'Nowy raport: 78% firm w Polsce planuje zatrudnic pracownikow z zagranicy w 2026 roku. Jak przygotowac firme na proces legalizacji?',author:'Anna Kowalska',hashtags:'#raport #HR #zatrudnienie #polska #business',likes:89,comments:15,shares:34,reach:3200,impressions:5100,clicks:180,saves:45,replies:7,campaign:'B2B Content',link:'https://wincase.eu/raport-2026'},
    {id:'p15',platform:'youtube',type:'video',status:'published',date:'2026-02-14',content:'Czym sie rozni karta pobytu od wizy? Wyjasniamy roznice, koszty i czas oczekiwania. Pelne porownanie w jednym filmie.',author:'Piotr Zielinski',hashtags:'#wiza #kartapobytu #porownanie #edukacja',likes:267,comments:35,shares:22,reach:7200,impressions:11800,clicks:350,saves:145,replies:12,campaign:'Video Guides',link:'https://youtube.com/watch?v=demo2'},
    {id:'p16',platform:'facebook',type:'carousel',status:'published',date:'2026-02-13',content:'Checklist: 10 dokumentow, ktore musisz przygotowac do wniosku o karte pobytu. Pobierz darmowy PDF z naszej strony!',author:'Anna Kowalska',hashtags:'#checklist #dokumenty #kartapobytu #darmowy',likes:312,comments:42,shares:87,reach:8400,impressions:12600,clicks:560,saves:340,replies:15,campaign:'Lead Magnet',link:'https://wincase.eu/checklist'},
    {id:'p17',platform:'telegram',type:'text',status:'published',date:'2026-02-12',content:'Przypomnienie: termin na zlozenie wniosku o przedluzenie karty pobytu — minimum 30 dni przed wygasnieciem!',author:'AI Assistant',hashtags:'','likes':0,comments:0,shares:28,reach:1650,impressions:1900,clicks:180,saves:0,replies:0,campaign:'','link':''},
    {id:'p18',platform:'instagram',type:'image',status:'published',date:'2026-02-11',content:'Nasz zespol gotowy do pomocy! 💪 Specjalizujemy sie w legalizacji pobytu i pracy w Polsce od 2018 roku.',author:'Marek Wisniewski',hashtags:'#team #wincase #legalizacja #polska #work',likes:278,comments:19,shares:24,reach:5600,impressions:8200,clicks:120,saves:67,replies:6,campaign:'Brand Awareness',link:'https://wincase.eu/about'},
    // Draft posts
    {id:'p19',platform:'facebook',type:'image',status:'draft',date:'2026-03-01',content:'[DRAFT] Nowa usluga: pomoc w uzyskaniu obywatelstwa polskiego. Sprawdz, czy sie kwalifikujesz!',author:'Anna Kowalska',hashtags:'#obywatelstwo #polska #newservice',likes:0,comments:0,shares:0,reach:0,impressions:0,clicks:0,saves:0,replies:0,campaign:'Citizenship Launch',link:'https://wincase.eu/citizenship'},
    {id:'p20',platform:'instagram',type:'reel',status:'draft',date:'2026-03-01',content:'[DRAFT] Animacja: Proces legalizacji pobytu w 60 sekund. Od zlozenia wniosku do odbioru karty.',author:'Piotr Zielinski',hashtags:'#animation #kartapobytu #60sekund',likes:0,comments:0,shares:0,reach:0,impressions:0,clicks:0,saves:0,replies:0,campaign:'Video Guides',link:''},
];

// ── Scheduled Posts ──
const scheduled = [
    {id:'s1',platforms:['facebook','instagram'],type:'image',content:'Wielka Promocja Wiosenna! -20% na uslugi legalizacyjne do konca marca. Skontaktuj sie z nami juz dzis!',scheduledFor:'2026-03-03 10:00',author:'Anna Kowalska',hashtags:'#promocja #wiosna #wincase'},
    {id:'s2',platforms:['tiktok','instagram'],type:'reel',content:'Jak wyglada wizyta w urzedzie ds. cudzoziemcow? Nagrywamy caly proces od wejscia do wyjscia.',scheduledFor:'2026-03-04 14:00',author:'Piotr Zielinski',hashtags:'#urzad #cudzoziemcy #vlog'},
    {id:'s3',platforms:['linkedin','facebook'],type:'article',content:'Raport rynku pracy: Sektory z najwiekszym zapotrzebowaniem na pracownikow z zagranicy w Q1 2026.',scheduledFor:'2026-03-05 09:00',author:'Anna Kowalska',hashtags:'#raport #rynekpracy #Q1'},
    {id:'s4',platforms:['telegram'],type:'text',content:'Nowe godziny pracy urzedu wojewodzkiego w Warszawie od marca 2026. Sprawdzcie zanim pojdziecie!',scheduledFor:'2026-03-03 08:00',author:'AI Assistant',hashtags:''},
    {id:'s5',platforms:['youtube'],type:'video',content:'Wywiad z prawnikiem: Najnowsze zmiany w ustawie o cudzoziemcach. Co to oznacza dla Twojego wniosku?',scheduledFor:'2026-03-06 12:00',author:'Piotr Zielinski',hashtags:'#wywiad #prawo #cudzoziemcy'},
    {id:'s6',platforms:['facebook','instagram','threads'],type:'carousel',content:'Infografika: Porownanie typow kart pobytu — czasowa, stala, rezydenta UE. Ktora jest dla Ciebie?',scheduledFor:'2026-03-07 11:00',author:'Anna Kowalska',hashtags:'#infografika #kartapobytu #porownanie'},
];

// ── Inbox ──
const inbox = [
    {id:'m1',platform:'facebook',from:'Olena Kovalenko',avatar:'OK',message:'Dzien dobry, chcialam zapytac o koszty pomocy przy karcie pobytu. Moj maz tez potrzebuje, czy jest znizka dla rodzin?',type:'message',date:'2026-03-02 09:15',read:false,postRef:''},
    {id:'m2',platform:'instagram',from:'ahmed_work_pl',avatar:'AW',message:'Super reel! Dokladnie tak to wygladalo u mnie 😂 Polecam WinCase kazdemu!',type:'comment',date:'2026-03-02 08:40',read:false,postRef:'p12'},
    {id:'m3',platform:'facebook',from:'Nguyen Van Tuan',avatar:'NT',message:'Prosze o informacje dotyczace pozwolenia na prace typu A. Ile trwa caly proces i jakie dokumenty potrzebuje?',type:'message',date:'2026-03-01 22:10',read:false,postRef:''},
    {id:'m4',platform:'instagram',from:'maria_design_pl',avatar:'MD',message:'Piekna infografika! Mozna udostepnic na moim profilu z oznaczeniem was?',type:'comment',date:'2026-03-01 18:30',read:false,postRef:'p10'},
    {id:'m5',platform:'tiktok',from:'PolandLife2026',avatar:'PL',message:'Prosze zrobic wiecej takich filmow! Bardzo pomocne dla nowych imigrantow',type:'comment',date:'2026-03-01 15:20',read:true,postRef:'p3'},
    {id:'m6',platform:'linkedin',from:'Katarzyna Nowak (HR Director)',avatar:'KN',message:'Dzien dobry, nasza firma planuje zatrudnic 15 osob z Ukrainy. Czy mozemy umowic sie na spotkanie w sprawie legalizacji?',type:'message',date:'2026-03-01 11:00',read:false,postRef:''},
    {id:'m7',platform:'facebook',from:'Igor Petrov',avatar:'IP',message:'Dziekuje za pomoc! Dzis odebralem karte pobytu. Polecam WinCase wszystkim!',type:'comment',date:'2026-02-28 16:45',read:true,postRef:'p1'},
    {id:'m8',platform:'youtube',from:'ImmigrationPL',avatar:'IM',message:'Swietny film! Czy planujecie nagranie o wersji blue card?',type:'comment',date:'2026-02-28 14:20',read:true,postRef:'p6'},
    {id:'m9',platform:'telegram',from:'AnnaUA',avatar:'AU',message:'Mozhna zapysatys na konsultaciyu? Meni potribna dopomoha z kartoju pobytu.',type:'message',date:'2026-02-28 10:30',read:false,postRef:''},
    {id:'m10',platform:'instagram',from:'poland_expats',avatar:'PE',message:'Czy mozecie zrobic post o Blue Card EU? Duzo ludzi o to pyta',type:'comment',date:'2026-02-27 20:15',read:false,postRef:'p18'},
    {id:'m11',platform:'facebook',from:'Li Wei',avatar:'LW',message:'Hello, I need help with work permit for my wife. She is from China. Is it possible to get consultation in English?',type:'message',date:'2026-02-27 12:00',read:true,postRef:''},
    {id:'m12',platform:'tiktok',from:'visa_expert_warsaw',avatar:'VE',message:'Dobre rady! Ale punkt 2 sie troche zmienil od stycznia 2026, mozecie zaktualizowac?',type:'comment',date:'2026-02-27 09:30',read:true,postRef:'p3'},
];

// ── Hashtags ──
const hashtags = [
    {tag:'#kartapobytu',ourPosts:34,totalPosts:'125K',avgReach:8400,avgEng:'4.8%',trend:'up'},
    {tag:'#immigration',ourPosts:28,totalPosts:'2.1M',avgReach:6200,avgEng:'3.2%',trend:'up'},
    {tag:'#legalizacja',ourPosts:22,totalPosts:'45K',avgReach:5100,avgEng:'5.1%',trend:'up'},
    {tag:'#polska',ourPosts:18,totalPosts:'8.5M',avgReach:4800,avgEng:'2.1%',trend:'stable'},
    {tag:'#pozwolenienapraca',ourPosts:15,totalPosts:'32K',avgReach:3900,avgEng:'4.5%',trend:'up'},
    {tag:'#wincase',ourPosts:42,totalPosts:'1.2K',avgReach:7200,avgEng:'6.8%',trend:'up'},
    {tag:'#praca',ourPosts:12,totalPosts:'3.4M',avgReach:3200,avgEng:'1.8%',trend:'stable'},
    {tag:'#cudzoziemcy',ourPosts:10,totalPosts:'18K',avgReach:4100,avgEng:'4.2%',trend:'up'},
    {tag:'#wiza',ourPosts:8,totalPosts:'89K',avgReach:3600,avgEng:'3.5%',trend:'down'},
    {tag:'#warszawa',ourPosts:14,totalPosts:'5.2M',avgReach:2800,avgEng:'1.5%',trend:'stable'},
    {tag:'#obywatelstwo',ourPosts:5,totalPosts:'22K',avgReach:4500,avgEng:'5.3%',trend:'up'},
    {tag:'#pracodawca',ourPosts:7,totalPosts:'67K',avgReach:3100,avgEng:'2.9%',trend:'stable'},
];

let currentPostIdx = -1;

// ── Tab Navigation ──
document.querySelectorAll('.sm-tab').forEach(tab => {
    tab.addEventListener('click', function(){
        document.querySelectorAll('.sm-tab').forEach(t => t.classList.remove('active'));
        this.classList.add('active');
        document.querySelectorAll('.sm-section').forEach(s => s.classList.remove('active'));
        document.getElementById('section-'+this.dataset.tab).classList.add('active');
    });
});

// ── Render Posts ──
function renderPosts(){
    const filter = document.querySelector('.post-filter-pill.btn-primary')?.dataset.filter || 'all';
    const pf = document.getElementById('postPlatformFilter').value;
    const tf = document.getElementById('postTypeFilter').value;
    const search = document.getElementById('postSearch').value.toLowerCase();
    let filtered = posts.filter(p => {
        if(filter !== 'all' && p.status !== filter) return false;
        if(pf && p.platform !== pf) return false;
        if(tf && p.type !== tf) return false;
        if(search && !p.content.toLowerCase().includes(search) && !p.hashtags.toLowerCase().includes(search)) return false;
        return true;
    });
    document.getElementById('postsCount').textContent = filtered.length;
    const tbody = document.getElementById('postsTableBody');
    if(!filtered.length){tbody.innerHTML='<tr><td colspan="10" class="text-center text-muted py-4">No posts found</td></tr>';return;}
    tbody.innerHTML = filtered.map((p,i) => {
        const idx = posts.indexOf(p);
        const stBadge = p.status==='published'?'<span class="badge bg-success-subtle text-success">Published</span>':'<span class="badge bg-warning-subtle text-warning">Draft</span>';
        return `<tr data-idx="${idx}">
            <td>${pBadge(p.platform)}</td>
            <td><span class="d-inline-block text-truncate" style="max-width:220px" title="${p.content}">${p.content}</span></td>
            <td>${tBadge(p.type)}</td>
            <td>${p.date}</td>
            <td>${stBadge}</td>
            <td class="text-center">${p.status==='published'?n(p.likes):'—'}</td>
            <td class="text-center">${p.status==='published'?n(p.comments):'—'}</td>
            <td class="text-center">${p.status==='published'?n(p.shares):'—'}</td>
            <td class="text-center">${p.status==='published'?n(p.reach):'—'}</td>
            <td>
                <div class="d-flex gap-1">
                    <button class="btn btn-sm btn-soft-primary btn-view-post" title="View"><i class="ri-eye-line"></i></button>
                    ${p.status==='published'?`<button class="btn btn-sm btn-soft-info btn-stats-post" title="Analytics"><i class="ri-bar-chart-line"></i></button>`:''}
                    <button class="btn btn-sm btn-soft-warning btn-edit-post" title="Edit"><i class="ri-edit-line"></i></button>
                    <button class="btn btn-sm btn-soft-success btn-dup-post" title="Duplicate"><i class="ri-file-copy-line"></i></button>
                    <button class="btn btn-sm btn-soft-danger btn-del-post" title="Delete"><i class="ri-delete-bin-line"></i></button>
                </div>
            </td>
        </tr>`;
    }).join('');
}

// ── Post Filters ──
document.querySelectorAll('.post-filter-pill').forEach(pill => {
    pill.addEventListener('click', function(){
        document.querySelectorAll('.post-filter-pill').forEach(p => {p.classList.remove('btn-primary');p.classList.add('btn-light');});
        this.classList.remove('btn-light');this.classList.add('btn-primary');
        renderPosts();
    });
});
document.getElementById('postPlatformFilter').addEventListener('change', renderPosts);
document.getElementById('postTypeFilter').addEventListener('change', renderPosts);
document.getElementById('postSearch').addEventListener('input', renderPosts);

// ── Post Actions ──
document.getElementById('postsTableBody').addEventListener('click', function(e){
    const btn = e.target.closest('button');
    if(!btn) return;
    const tr = btn.closest('tr');
    const idx = parseInt(tr.dataset.idx);
    currentPostIdx = idx;
    const p = posts[idx];
    if(btn.classList.contains('btn-view-post')) showViewPost(p);
    else if(btn.classList.contains('btn-stats-post')) showPostStats(p);
    else if(btn.classList.contains('btn-edit-post')) showEditPost(p, idx);
    else if(btn.classList.contains('btn-dup-post')){ duplicatePostByIdx(idx); }
    else if(btn.classList.contains('btn-del-post')){
        if(confirm('Delete this post?')){posts.splice(idx,1);renderPosts();toast('Post deleted','warning');}
    }
});

function showViewPost(p){
    document.getElementById('vpPlatform').innerHTML = pBadge(p.platform);
    document.getElementById('vpType').innerHTML = tBadge(p.type);
    document.getElementById('vpStatus').innerHTML = p.status==='published'?'<span class="badge bg-success">Published</span>':'<span class="badge bg-warning">Draft</span>';
    document.getElementById('vpDate').textContent = p.date;
    document.getElementById('vpAuthor').textContent = p.author;
    document.getElementById('vpHashtags').innerHTML = p.hashtags ? p.hashtags.split(' ').map(h=>`<span class="badge bg-light text-dark me-1">${h}</span>`).join(''):'<span class="text-muted">—</span>';
    document.getElementById('vpLink').innerHTML = p.link?`<a href="${p.link}" target="_blank" class="text-primary">${p.link}</a>`:'<span class="text-muted">—</span>';
    document.getElementById('vpCampaign').textContent = p.campaign||'—';
    document.getElementById('vpContent').textContent = p.content;
    document.getElementById('vpLikes').textContent = n(p.likes);
    document.getElementById('vpComments').textContent = n(p.comments);
    document.getElementById('vpShares').textContent = n(p.shares);
    document.getElementById('vpReach').textContent = n(p.reach);
    document.getElementById('vpImpressions').textContent = n(p.impressions);
    document.getElementById('vpClicks').textContent = n(p.clicks);
    const eng = p.reach > 0 ? ((p.likes+p.comments+p.shares)/p.reach*100).toFixed(1) : '0';
    document.getElementById('vpEngRate').textContent = eng+'%';
    new bootstrap.Modal(document.getElementById('viewPostModal')).show();
}

function showEditPost(p, idx){
    document.getElementById('editPostIdx').value = idx;
    document.getElementById('editPlatform').value = p.platform;
    document.getElementById('editType').value = p.type;
    document.getElementById('editStatus').value = p.status;
    document.getElementById('editContent').value = p.content;
    document.getElementById('editHashtags').value = p.hashtags;
    document.getElementById('editCampaign').value = p.campaign||'';
    document.getElementById('editLink').value = p.link||'';
    document.getElementById('editCharCount').textContent = p.content.length;
    new bootstrap.Modal(document.getElementById('editPostModal')).show();
}
document.getElementById('editContent').addEventListener('input',function(){document.getElementById('editCharCount').textContent=this.value.length;});

function saveEditPost(){
    const idx = parseInt(document.getElementById('editPostIdx').value);
    posts[idx].type = document.getElementById('editType').value;
    posts[idx].status = document.getElementById('editStatus').value;
    posts[idx].content = document.getElementById('editContent').value;
    posts[idx].hashtags = document.getElementById('editHashtags').value;
    posts[idx].campaign = document.getElementById('editCampaign').value;
    posts[idx].link = document.getElementById('editLink').value;
    bootstrap.Modal.getInstance(document.getElementById('editPostModal')).hide();
    renderPosts();
    toast('Post updated successfully');
}

function openEditFromView(){
    bootstrap.Modal.getInstance(document.getElementById('viewPostModal')).hide();
    setTimeout(()=>showEditPost(posts[currentPostIdx], currentPostIdx),300);
}
function duplicatePost(){
    bootstrap.Modal.getInstance(document.getElementById('viewPostModal')).hide();
    duplicatePostByIdx(currentPostIdx);
}
function duplicatePostByIdx(idx){
    const p = {...posts[idx]};
    p.id = 'p'+(posts.length+1);
    p.status = 'draft';
    p.date = '2026-03-02';
    p.content = '[COPY] '+p.content;
    p.likes=0;p.comments=0;p.shares=0;p.reach=0;p.impressions=0;p.clicks=0;p.saves=0;p.replies=0;
    posts.unshift(p);
    renderPosts();
    toast('Post duplicated as draft');
}

function showPostStats(p){
    document.getElementById('psImpressions').textContent = n(p.impressions);
    document.getElementById('psReach').textContent = n(p.reach);
    const totalEng = p.likes+p.comments+p.shares+p.saves;
    document.getElementById('psEngagements').textContent = n(totalEng);
    const engRate = p.reach > 0 ? ((totalEng)/p.reach*100).toFixed(1) : '0';
    document.getElementById('psEngRate').textContent = engRate+'%';
    document.getElementById('psLikes').textContent = n(p.likes);
    document.getElementById('psComments').textContent = n(p.comments);
    document.getElementById('psShares').textContent = n(p.shares);
    document.getElementById('psSaves').textContent = n(p.saves);
    document.getElementById('psClicks').textContent = n(p.clicks);
    document.getElementById('psReplies').textContent = n(p.replies);
    document.getElementById('psLocations').innerHTML = '<div class="mb-1">🇵🇱 Poland — 62%</div><div class="mb-1">🇺🇦 Ukraine — 18%</div><div class="mb-1">🇩🇪 Germany — 8%</div><div>🇬🇧 UK — 5%</div>';
    document.getElementById('psAges').innerHTML = '<div class="mb-1">18-24 — 22%</div><div class="mb-1">25-34 — 41%</div><div class="mb-1">35-44 — 24%</div><div>45+ — 13%</div>';
    document.getElementById('psGender').innerHTML = '<div class="mb-1">♀ Female — 46%</div><div class="mb-1">♂ Male — 52%</div><div>Other — 2%</div>';
    new bootstrap.Modal(document.getElementById('postStatsModal')).show();
}

// ── Render Scheduled ──
function renderScheduled(){
    document.getElementById('scheduledCount').textContent = scheduled.length;
    const tbody = document.getElementById('scheduledTableBody');
    if(!scheduled.length){tbody.innerHTML='<tr><td colspan="6" class="text-center text-muted py-4">No scheduled posts</td></tr>';return;}
    tbody.innerHTML = scheduled.map((s,i) => `<tr data-idx="${i}">
        <td>${s.platforms.map(p=>pBadge(p)).join(' ')}</td>
        <td><span class="d-inline-block text-truncate" style="max-width:220px" title="${s.content}">${s.content}</span></td>
        <td>${tBadge(s.type)}</td>
        <td><i class="ri-calendar-line me-1 text-muted"></i>${s.scheduledFor}</td>
        <td>${s.author}</td>
        <td>
            <div class="d-flex gap-1">
                <button class="btn btn-sm btn-soft-warning btn-edit-sched" title="Edit"><i class="ri-edit-line"></i></button>
                <button class="btn btn-sm btn-soft-success btn-pub-sched" title="Publish Now"><i class="ri-send-plane-line"></i></button>
                <button class="btn btn-sm btn-soft-danger btn-del-sched" title="Cancel"><i class="ri-close-line"></i></button>
            </div>
        </td>
    </tr>`).join('');
}
document.getElementById('scheduledTableBody').addEventListener('click', function(e){
    const btn = e.target.closest('button');
    if(!btn) return;
    const idx = parseInt(btn.closest('tr').dataset.idx);
    if(btn.classList.contains('btn-del-sched')){
        if(confirm('Cancel this scheduled post?')){scheduled.splice(idx,1);renderScheduled();toast('Scheduled post cancelled','warning');}
    } else if(btn.classList.contains('btn-pub-sched')){
        const s = scheduled[idx];
        const newPost = {id:'p'+(posts.length+1),platform:s.platforms[0],type:s.type,status:'published',date:'2026-03-02',content:s.content,author:s.author,hashtags:s.hashtags||'',likes:0,comments:0,shares:0,reach:0,impressions:0,clicks:0,saves:0,replies:0,campaign:'',link:''};
        posts.unshift(newPost);
        scheduled.splice(idx,1);
        renderScheduled();renderPosts();
        toast('Post published now!');
    } else if(btn.classList.contains('btn-edit-sched')){
        toast('Opening editor for scheduled post...','info');
    }
});

// ── Render Inbox ──
function renderInbox(){
    const filter = document.querySelector('.inbox-filter-pill.btn-primary')?.dataset.filter || 'all';
    const pf = document.getElementById('inboxPlatformFilter').value;
    const search = document.getElementById('inboxSearch').value.toLowerCase();
    let filtered = inbox.filter(m => {
        if(filter === 'unread' && m.read) return false;
        if(filter === 'comments' && m.type !== 'comment') return false;
        if(filter === 'messages' && m.type !== 'message') return false;
        if(pf && m.platform !== pf) return false;
        if(search && !m.message.toLowerCase().includes(search) && !m.from.toLowerCase().includes(search)) return false;
        return true;
    });
    document.getElementById('inboxCount').textContent = inbox.filter(m=>!m.read).length;
    const tbody = document.getElementById('inboxTableBody');
    if(!filtered.length){tbody.innerHTML='<tr><td colspan="7" class="text-center text-muted py-4">No messages</td></tr>';return;}
    tbody.innerHTML = filtered.map((m,i) => {
        const idx = inbox.indexOf(m);
        const rowClass = m.read ? '' : 'table-active';
        const dot = m.read ? '' : '<span class="badge bg-primary rounded-circle p-1"> </span>';
        const typeBadge = m.type==='comment'?'<span class="badge bg-info-subtle text-info">Comment</span>':'<span class="badge bg-primary-subtle text-primary">Message</span>';
        return `<tr class="${rowClass}" data-idx="${idx}">
            <td>${dot}</td>
            <td>${pBadge(m.platform)}</td>
            <td class="fw-semibold">${m.from}</td>
            <td><span class="d-inline-block text-truncate" style="max-width:250px">${m.message}</span></td>
            <td>${typeBadge}</td>
            <td><small class="text-muted">${m.date}</small></td>
            <td>
                <div class="d-flex gap-1">
                    <button class="btn btn-sm btn-soft-primary btn-reply-msg" title="Reply"><i class="ri-reply-line"></i></button>
                    ${!m.read?`<button class="btn btn-sm btn-soft-success btn-read-msg" title="Mark Read"><i class="ri-check-line"></i></button>`:''}
                    ${m.postRef?`<button class="btn btn-sm btn-soft-info btn-view-ref" title="View Post"><i class="ri-article-line"></i></button>`:''}
                </div>
            </td>
        </tr>`;
    }).join('');
}
document.querySelectorAll('.inbox-filter-pill').forEach(pill => {
    pill.addEventListener('click', function(){
        document.querySelectorAll('.inbox-filter-pill').forEach(p => {p.classList.remove('btn-primary');p.classList.add('btn-light');});
        this.classList.remove('btn-light');this.classList.add('btn-primary');
        renderInbox();
    });
});
document.getElementById('inboxPlatformFilter').addEventListener('change', renderInbox);
document.getElementById('inboxSearch').addEventListener('input', renderInbox);

document.getElementById('inboxTableBody').addEventListener('click', function(e){
    const btn = e.target.closest('button');
    if(!btn) return;
    const idx = parseInt(btn.closest('tr').dataset.idx);
    const m = inbox[idx];
    if(btn.classList.contains('btn-reply-msg')){
        document.getElementById('replyIdx').value = idx;
        document.getElementById('replyFrom').textContent = m.from;
        document.getElementById('replyPlatformBadge').innerHTML = pBadge(m.platform);
        document.getElementById('replyOriginal').textContent = m.message;
        document.getElementById('replyText').value = '';
        new bootstrap.Modal(document.getElementById('replyModal')).show();
    } else if(btn.classList.contains('btn-read-msg')){
        m.read = true;
        renderInbox();
        toast('Marked as read');
    } else if(btn.classList.contains('btn-view-ref')){
        const post = posts.find(p=>p.id===m.postRef);
        if(post) showViewPost(post);
    }
});

function insertQuickReply(type){
    const replies = {
        thank:'Dziekujemy za mily komentarz! 🙏 Cieszymy sie, ze moglimy pomoc!',
        contact:'Prosimy o kontakt: tel. +48 579 266 493 lub email wincasetop@gmail.com. Chetnie pomozemy!',
        dm:'Prosimy o wiadomosc prywatna — odpowiemy na wszystkie pytania szczegolow! 📩'
    };
    document.getElementById('replyText').value = replies[type]||'';
}
function sendReply(){
    const idx = parseInt(document.getElementById('replyIdx').value);
    inbox[idx].read = true;
    bootstrap.Modal.getInstance(document.getElementById('replyModal')).hide();
    renderInbox();
    toast('Reply sent successfully');
}
function markAllRead(){
    inbox.forEach(m=>m.read=true);
    renderInbox();
    toast('All messages marked as read');
}

// ── Render Hashtags ──
function renderHashtags(){
    const tbody = document.getElementById('hashtagsTableBody');
    tbody.innerHTML = hashtags.map((h,i) => {
        const trendIcon = h.trend==='up'?'<span class="text-success"><i class="ri-arrow-up-s-line"></i> Rising</span>':h.trend==='down'?'<span class="text-danger"><i class="ri-arrow-down-s-line"></i> Falling</span>':'<span class="text-muted"><i class="ri-subtract-line"></i> Stable</span>';
        return `<tr>
            <td><span class="fw-semibold text-primary">${h.tag}</span></td>
            <td class="text-center">${h.ourPosts}</td>
            <td class="text-center">${h.totalPosts}</td>
            <td class="text-center">${n(h.avgReach)}</td>
            <td class="text-center">${h.avgEng}</td>
            <td>${trendIcon}</td>
            <td><button class="btn btn-sm btn-soft-danger" onclick="hashtags.splice(${i},1);renderHashtags();toast('Hashtag removed','warning');" title="Remove"><i class="ri-delete-bin-line"></i></button></td>
        </tr>`;
    }).join('');
}
function showAddHashtag(){new bootstrap.Modal(document.getElementById('addHashtagModal')).show();}
function addHashtag(){
    const tag = '#'+document.getElementById('newHashtag').value.replace(/^#/,'').trim();
    if(!tag || tag==='#') return;
    hashtags.unshift({tag:tag,ourPosts:0,totalPosts:'—',avgReach:0,avgEng:'0%',trend:'stable'});
    bootstrap.Modal.getInstance(document.getElementById('addHashtagModal')).hide();
    renderHashtags();
    toast('Hashtag added for tracking');
}

// ── Create Post ──
document.getElementById('cpContent').addEventListener('input',function(){document.getElementById('cpCharCount').textContent=this.value.length;});
document.getElementById('cpPostNow').addEventListener('change',function(){document.getElementById('cpSchedule').disabled=this.checked;});

function publishPost(){
    const content = document.getElementById('cpContent').value.trim();
    if(!content){toast('Please enter post content','danger');return;}
    const platforms = [];
    document.querySelectorAll('#createPostModal .form-check-input[type="checkbox"]:checked').forEach(cb => {if(cb.value) platforms.push(cb.value);});
    if(!platforms.length){toast('Select at least one platform','danger');return;}
    const postNow = document.getElementById('cpPostNow').checked;
    const schedDate = document.getElementById('cpSchedule').value;
    if(!postNow && !schedDate){toast('Set schedule date or check "Post now"','danger');return;}
    if(!postNow && schedDate){
        // Schedule
        scheduled.unshift({
            id:'s'+(scheduled.length+1),
            platforms:platforms,
            type:document.getElementById('cpType').value,
            content:content,
            scheduledFor:schedDate.replace('T',' '),
            author:document.getElementById('cpAuthor').value,
            hashtags:document.getElementById('cpHashtags').value
        });
        renderScheduled();
        toast('Post scheduled successfully');
    } else {
        // Publish now
        platforms.forEach(pl => {
            posts.unshift({
                id:'p'+(posts.length+1),platform:pl,type:document.getElementById('cpType').value,
                status:'published',date:'2026-03-02',content:content,
                author:document.getElementById('cpAuthor').value,
                hashtags:document.getElementById('cpHashtags').value,
                likes:0,comments:0,shares:0,reach:0,impressions:0,clicks:0,saves:0,replies:0,
                campaign:document.getElementById('cpCampaign').value,
                link:document.getElementById('cpLink').value
            });
        });
        renderPosts();
        toast('Post published to '+platforms.length+' platform(s)!');
    }
    bootstrap.Modal.getInstance(document.getElementById('createPostModal')).hide();
    document.getElementById('cpContent').value='';
    document.getElementById('cpHashtags').value='';
    document.getElementById('cpCampaign').value='';
    document.getElementById('cpLink').value='';
}
function saveAsDraft(){
    const content = document.getElementById('cpContent').value.trim();
    if(!content){toast('Please enter post content','danger');return;}
    const platforms = [];
    document.querySelectorAll('#createPostModal .form-check-input[type="checkbox"]:checked').forEach(cb => {if(cb.value) platforms.push(cb.value);});
    posts.unshift({
        id:'p'+(posts.length+1),platform:platforms[0]||'facebook',type:document.getElementById('cpType').value,
        status:'draft',date:'2026-03-02',content:content,
        author:document.getElementById('cpAuthor').value,
        hashtags:document.getElementById('cpHashtags').value,
        likes:0,comments:0,shares:0,reach:0,impressions:0,clicks:0,saves:0,replies:0,
        campaign:document.getElementById('cpCampaign').value,
        link:document.getElementById('cpLink').value
    });
    bootstrap.Modal.getInstance(document.getElementById('createPostModal')).hide();
    renderPosts();
    toast('Post saved as draft');
}

// ── Top Performing Posts ──
function renderTopPosts(){
    const published = posts.filter(p=>p.status==='published');
    const sorted = [...published].sort((a,b)=>(b.likes+b.comments+b.shares)-(a.likes+a.comments+a.shares)).slice(0,4);
    document.getElementById('topPostsRow').innerHTML = sorted.map(p => {
        const totalEng = p.likes+p.comments+p.shares;
        return `<div class="col-xl-3 col-md-6 mb-3">
            <div class="card border mb-0 h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center gap-2 mb-2">${pBadge(p.platform)} ${tBadge(p.type)}</div>
                    <p class="text-truncate mb-2" title="${p.content}">${p.content.substring(0,80)}...</p>
                    <div class="d-flex justify-content-between">
                        <small><i class="ri-heart-3-line text-danger"></i> ${n(p.likes)}</small>
                        <small><i class="ri-chat-3-line text-primary"></i> ${n(p.comments)}</small>
                        <small><i class="ri-share-forward-line text-success"></i> ${n(p.shares)}</small>
                    </div>
                    <div class="mt-2 text-center">
                        <span class="badge bg-primary-subtle text-primary">Reach: ${n(p.reach)}</span>
                    </div>
                </div>
            </div>
        </div>`;
    }).join('');
}

// ── Export CSV ──
function exportPostsCSV(){
    let csv = 'Platform,Type,Status,Date,Content,Likes,Comments,Shares,Reach,Impressions,Clicks\n';
    posts.forEach(p => {
        csv += `"${platformCfg[p.platform]?.label||p.platform}","${p.type}","${p.status}","${p.date}","${p.content.replace(/"/g,'""')}",${p.likes},${p.comments},${p.shares},${p.reach},${p.impressions},${p.clicks}\n`;
    });
    const blob = new Blob([csv],{type:'text/csv'});
    const a = document.createElement('a');a.href=URL.createObjectURL(blob);a.download='social-media-posts.csv';a.click();
    toast('Posts exported to CSV');
}

// ── Charts ──
// Engagement Overview
new ApexCharts(document.querySelector("#engagementChart"), {
    chart:{type:'line',height:350,toolbar:{show:false},zoom:{enabled:false}},
    series:[
        {name:'Facebook',data:[320,345,332,368,355,378,390]},
        {name:'Instagram',data:[295,310,325,340,330,355,362]},
        {name:'TikTok',data:[400,380,450,510,480,540,590]},
        {name:'LinkedIn',data:[45,52,48,55,50,58,62]},
        {name:'YouTube',data:[185,192,178,205,210,195,220]},
        {name:'Telegram',data:[82,95,88,100,92,98,105]},
        {name:'Pinterest',data:[60,55,65,72,68,75,80]},
        {name:'Threads',data:[18,22,20,25,21,28,32]}
    ],
    xaxis:{categories:['Mon','Tue','Wed','Thu','Fri','Sat','Sun']},
    yaxis:{title:{text:'Engagements'}},
    colors:['#3b82f6','#ef4444','#111827','#06b6d4','#dc2626','#0ea5e9','#e11d48','#6b7280'],
    stroke:{curve:'smooth',width:2},
    grid:{borderColor:'#f1f1f1'},
    legend:{position:'top'},
    tooltip:{shared:true,intersect:false}
}).render();

// Followers Distribution
new ApexCharts(document.querySelector("#followersDonut"), {
    chart:{type:'donut',height:350},
    series:[12400,8200,3100,800,1450,2870,560,340],
    labels:['Facebook','Instagram','TikTok','LinkedIn','YouTube','Telegram','Pinterest','Threads'],
    colors:['#3b82f6','#ef4444','#111827','#06b6d4','#dc2626','#0ea5e9','#e11d48','#6b7280'],
    legend:{position:'bottom'},
    plotOptions:{pie:{donut:{labels:{show:true,total:{show:true,label:'Total',formatter:()=>'29,720'}}}}},
    dataLabels:{enabled:false}
}).render();

// Content Type Performance
new ApexCharts(document.querySelector("#contentTypeChart"), {
    chart:{type:'bar',height:300,toolbar:{show:false}},
    series:[
        {name:'Avg Reach',data:[5800,12400,18700,3400,9100,1890]},
        {name:'Avg Engagement',data:[280,420,680,120,340,65]}
    ],
    xaxis:{categories:['Image','Video','Reel','Text','Carousel','Article']},
    colors:['#3b82f6','#10b981'],
    plotOptions:{bar:{horizontal:false,columnWidth:'55%',borderRadius:4}},
    yaxis:[{title:{text:'Avg Reach'}},{opposite:true,title:{text:'Avg Engagement'}}],
    grid:{borderColor:'#f1f1f1'},
    legend:{position:'top'}
}).render();

// Followers Growth
new ApexCharts(document.querySelector("#followersGrowthChart"), {
    chart:{type:'area',height:300,toolbar:{show:false}},
    series:[{name:'Total Followers',data:[18200,20400,22100,24600,27100,29720]}],
    xaxis:{categories:['Oct','Nov','Dec','Jan','Feb','Mar']},
    colors:['#3b82f6'],
    fill:{type:'gradient',gradient:{shadeIntensity:1,opacityFrom:0.4,opacityTo:0.1}},
    stroke:{curve:'smooth',width:2},
    grid:{borderColor:'#f1f1f1'},
    dataLabels:{enabled:true,formatter:v=>n(v)}
}).render();

// ── Init ──
renderPosts();
renderScheduled();
renderInbox();
renderHashtags();
renderTopPosts();

// Chart range buttons (visual only)
document.querySelectorAll('.chart-range').forEach(btn => {
    btn.addEventListener('click', function(){
        document.querySelectorAll('.chart-range').forEach(b=>b.classList.remove('active'));
        this.classList.add('active');
        toast('Chart updated to '+this.dataset.range,'info');
    });
});
</script>
<style>
.sm-section{display:none}
.sm-section.active{display:block}
.table-active{background-color:rgba(var(--bs-primary-rgb),0.05)!important}
</style>
@endsection
