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
// ── Auth & API ──
const TOKEN = localStorage.getItem('wc_token');
const API = '/api/v1';
const HEADERS = {'Authorization':'Bearer '+TOKEN,'Accept':'application/json'};

async function apiFetch(url, opts={}){
    try {
        const r = await fetch(url, {headers:HEADERS, ...opts});
        if(r.status===401){ window.location='/login'; return null; }
        const json = await r.json();
        if(!r.ok) throw new Error(json.message||'API error');
        return json;
    } catch(e){
        toast(e.message||'Network error','danger');
        return null;
    }
}

async function apiPost(url, formData){
    try {
        const r = await fetch(url, {
            method:'POST',
            headers:{'Authorization':'Bearer '+TOKEN,'Accept':'application/json'},
            body: formData
        });
        if(r.status===401){ window.location='/login'; return null; }
        const json = await r.json();
        if(!r.ok) throw new Error(json.message||'API error');
        return json;
    } catch(e){
        toast(e.message||'Network error','danger');
        return null;
    }
}

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
function n(v){return v?Number(v).toLocaleString('pl-PL'):'0';}
function toast(msg,type='success'){const t=document.createElement('div');t.className=`alert alert-${type} position-fixed top-0 end-0 m-3 shadow`;t.style.zIndex='9999';t.innerHTML=msg;document.body.appendChild(t);setTimeout(()=>{t.style.opacity='0';setTimeout(()=>t.remove(),300)},3000);}
function esc(s){const d=document.createElement('div');d.textContent=s;return d.innerHTML;}

// ── State ──
let posts = [];
let scheduled = [];
let inbox = [];
let accounts = [];
let hashtags = [];
let currentPostIdx = -1;
let engagementChart = null;
let followersDonutChart = null;
let contentTypeChartObj = null;
let followersGrowthChartObj = null;

// ── Platform card external links (hardcoded social profile URLs) ──
const platformLinks = {
    facebook:  'https://www.facebook.com/profile.php?id=100083419746646',
    instagram: 'https://www.instagram.com/wincase.legalization.pl',
    tiktok:    'https://www.tiktok.com/@wincase.legalization.pl',
    linkedin:  'https://linkedin.com/company/wincase',
    youtube:   'https://www.youtube.com/@WinCase',
    telegram:  'https://t.me/wincase_eu',
    pinterest: 'https://www.pinterest.com/wincasepro',
    threads:   'https://threads.net/@wincase.eu'
};

// ── Load Accounts (stat cards + platform cards) ──
async function loadAccounts(){
    const res = await apiFetch(API+'/social/accounts');
    if(!res || !res.success){
        updateStatCards(0, 0, 0, 0);
        return;
    }
    const d = res.data;
    accounts = d.accounts || [];
    const totalFollowers = d.total_followers || accounts.reduce((s,a)=>s+(a.followers||0),0);
    const totalPosts = accounts.reduce((s,a)=>s+(a.posts_count||0),0);

    let totalEng = 0, engCount = 0;
    accounts.forEach(a => {
        if(a.engagement_rate !== undefined && a.engagement_rate !== null){
            totalEng += parseFloat(a.engagement_rate);
            engCount++;
        }
    });
    const avgEng = engCount > 0 ? (totalEng/engCount).toFixed(1) : '0.0';
    const totalReach = accounts.reduce((s,a)=>s+(a.reach||a.total_reach||0),0);

    updateStatCards(totalFollowers, totalPosts, avgEng, totalReach);
    updatePlatformCards(accounts);
    renderFollowersDonut(accounts);
    renderFollowersGrowthChart();
}

function updateStatCards(followers, postsCount, engRate, reach){
    const statRow = document.querySelector('#platformCards').previousElementSibling;
    if(!statRow) return;
    const cards = statRow.querySelectorAll('.card-h-100 .card-body');
    if(cards[0]) cards[0].querySelector('h4').textContent = n(followers);
    if(cards[1]) cards[1].querySelector('h4').textContent = n(postsCount);
    if(cards[2]) cards[2].querySelector('h4').textContent = engRate+'%';
    if(cards[3]) cards[3].querySelector('h4').textContent = n(reach);
}

function updatePlatformCards(accs){
    const map = {};
    accs.forEach(a => { map[(a.platform||'').toLowerCase()] = a; });

    // Gather all platform cards from both rows
    const container = document.getElementById('platformCards');
    const nextRow = container.nextElementSibling;
    let allCards = [...container.querySelectorAll('.card-h-100')];
    if(nextRow && !nextRow.classList.contains('card')) {
        allCards = allCards.concat([...nextRow.querySelectorAll('.card-h-100')]);
    }

    allCards.forEach(card => {
        const titleEl = card.querySelector('.card-title');
        if(!titleEl) return;
        const name = titleEl.textContent.trim().toLowerCase();
        const acc = map[name];
        const cols = card.querySelectorAll('.row.text-center .col-4 h6');
        const badge = card.querySelector('.d-flex.align-items-center.justify-content-between .badge');
        if(acc){
            if(badge){ badge.className='badge bg-success-subtle text-success fs-11'; badge.textContent='Connected'; }
            if(cols[0]) cols[0].textContent = n(acc.followers||0);
            if(cols[1]) cols[1].textContent = n(acc.posts_count||0);
            if(cols[2]){
                if(acc.avg_views !== undefined && acc.avg_views !== null){
                    cols[2].textContent = n(acc.avg_views);
                } else if(acc.engagement_rate !== undefined){
                    cols[2].textContent = parseFloat(acc.engagement_rate||0).toFixed(1)+'%';
                } else {
                    cols[2].textContent = '0';
                }
            }
            const monthEl = card.querySelector('.mt-2 small.text-muted');
            if(monthEl && acc.monthly_change !== undefined){
                const ch = acc.monthly_change||0;
                const icon = ch>=0?'ri-arrow-up-s-line text-success':'ri-arrow-down-s-line text-danger';
                monthEl.innerHTML = `<i class="${icon}"></i> ${ch>=0?'+':''}${n(ch)} this month`;
            }
        } else {
            if(badge){ badge.className='badge bg-secondary-subtle text-secondary fs-11'; badge.textContent='Not Connected'; }
            if(cols[0]) cols[0].textContent = '0';
            if(cols[1]) cols[1].textContent = '0';
            if(cols[2]) cols[2].textContent = '0';
        }
    });
}

// ── Load Posts ──
async function loadPosts(platform='', limit=50){
    let url = API+'/social/posts?limit='+limit;
    if(platform) url += '&platform='+encodeURIComponent(platform);
    const res = await apiFetch(url);
    if(!res || !res.success){
        posts = [];
        renderPosts(); renderScheduled(); renderTopPosts(); renderContentTypeChart(); renderEngagementChart();
        return;
    }
    const raw = Array.isArray(res.data) ? res.data : (res.data.posts||res.data||[]);
    posts = raw.map((p,i) => ({
        id: p.id||('p'+i),
        platform: (p.platform||'').toLowerCase(),
        type: p.type||p.post_type||'text',
        status: p.status||'published',
        date: (p.published_at||p.created_at||p.date||'').substring(0,10),
        content: p.content||p.text||p.caption||'',
        author: p.author||p.user_name||'',
        hashtags: p.hashtags||'',
        likes: p.likes||p.likes_count||0,
        comments: p.comments||p.comments_count||0,
        shares: p.shares||p.shares_count||0,
        reach: p.reach||0,
        impressions: p.impressions||0,
        clicks: p.clicks||p.link_clicks||0,
        saves: p.saves||p.saves_count||0,
        replies: p.replies||p.replies_count||0,
        campaign: p.campaign||'',
        link: p.link||p.url||'',
        scheduled_at: p.scheduled_at||null
    }));

    // Split out scheduled posts
    const now = new Date();
    scheduled = posts.filter(p=>p.scheduled_at && new Date(p.scheduled_at)>now).map(p=>({
        id:p.id, platforms:[p.platform], type:p.type, content:p.content,
        scheduledFor:(p.scheduled_at||'').replace('T',' ').substring(0,16),
        author:p.author, hashtags:p.hashtags
    }));
    posts = posts.filter(p=>!p.scheduled_at || new Date(p.scheduled_at)<=now);

    renderPosts();
    renderScheduled();
    renderTopPosts();
    renderContentTypeChart();
    renderEngagementChart();
}

// ── Load Calendar ──
async function loadCalendar(){
    const now = new Date();
    const from = new Date(now.getFullYear(),now.getMonth(),1).toISOString().substring(0,10);
    const to = new Date(now.getFullYear(),now.getMonth()+1,0).toISOString().substring(0,10);
    await apiFetch(API+'/social/calendar?date_from='+from+'&date_to='+to);
}

// ── Load Inbox ──
async function loadInbox(limit=50){
    const res = await apiFetch(API+'/social/inbox?limit='+limit);
    if(!res || !res.success){ inbox=[]; renderInbox(); return; }
    const raw = res.data.messages||res.data||[];
    inbox = raw.map((m,i)=>({
        id:m.id||('m'+i),
        platform:(m.platform||'').toLowerCase(),
        from:m.from||m.sender_name||m.author||'',
        avatar:(m.from||m.sender_name||'U').substring(0,2).toUpperCase(),
        message:m.message||m.text||m.content||'',
        type:m.type||'message',
        date:(m.date||m.created_at||'').substring(0,16).replace('T',' '),
        read:!!m.read,
        postRef:m.post_ref||m.post_id||''
    }));
    renderInbox();
}

// ── Sync Accounts ──
async function syncAccounts(){
    toast('Syncing all accounts...','info');
    const fd = new FormData();
    const res = await apiPost(API+'/social/sync', fd);
    if(res && res.success){ toast('Accounts synced'); loadAccounts(); loadPosts(); }
}

// ── Tab Navigation ──
document.querySelectorAll('.sm-tab').forEach(tab => {
    tab.addEventListener('click', function(){
        document.querySelectorAll('.sm-tab').forEach(t=>t.classList.remove('active'));
        this.classList.add('active');
        document.querySelectorAll('.sm-section').forEach(s=>s.classList.remove('active'));
        const sec = document.getElementById('section-'+this.dataset.tab);
        if(sec) sec.classList.add('active');
        if(this.dataset.tab==='inbox' && inbox.length===0) loadInbox();
        if(this.dataset.tab==='scheduled') renderScheduled();
    });
});

// ── Render Posts ──
function renderPosts(){
    const filter = document.querySelector('.post-filter-pill.btn-primary')?.dataset.filter||'all';
    const pf = document.getElementById('postPlatformFilter').value;
    const tf = document.getElementById('postTypeFilter').value;
    const search = document.getElementById('postSearch').value.toLowerCase();
    let filtered = posts.filter(p=>{
        if(filter!=='all' && p.status!==filter) return false;
        if(pf && p.platform!==pf) return false;
        if(tf && p.type!==tf) return false;
        if(search && !(p.content||'').toLowerCase().includes(search) && !(p.hashtags||'').toLowerCase().includes(search)) return false;
        return true;
    });
    document.getElementById('postsCount').textContent = filtered.length;
    const tbody = document.getElementById('postsTableBody');
    if(!filtered.length){tbody.innerHTML='<tr><td colspan="10" class="text-center text-muted py-4"><i class="ri-file-list-3-line fs-24 d-block mb-2"></i>No posts found</td></tr>';return;}
    tbody.innerHTML = filtered.map((p,i)=>{
        const idx = posts.indexOf(p);
        const stBadge = p.status==='published'?'<span class="badge bg-success-subtle text-success">Published</span>':'<span class="badge bg-warning-subtle text-warning">Draft</span>';
        return `<tr data-idx="${idx}" data-id="${p.id}">
            <td>${pBadge(p.platform)}</td>
            <td><span class="d-inline-block text-truncate" style="max-width:220px" title="${esc(p.content)}">${esc(p.content)}</span></td>
            <td>${tBadge(p.type)}</td>
            <td>${p.date}</td>
            <td>${stBadge}</td>
            <td class="text-center">${p.status==='published'?n(p.likes):'\u2014'}</td>
            <td class="text-center">${p.status==='published'?n(p.comments):'\u2014'}</td>
            <td class="text-center">${p.status==='published'?n(p.shares):'\u2014'}</td>
            <td class="text-center">${p.status==='published'?n(p.reach):'\u2014'}</td>
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
document.querySelectorAll('.post-filter-pill').forEach(pill=>{
    pill.addEventListener('click',function(){
        document.querySelectorAll('.post-filter-pill').forEach(p=>{p.classList.remove('btn-primary');p.classList.add('btn-light');});
        this.classList.remove('btn-light');this.classList.add('btn-primary');
        renderPosts();
    });
});
document.getElementById('postPlatformFilter').addEventListener('change',renderPosts);
document.getElementById('postTypeFilter').addEventListener('change',renderPosts);
document.getElementById('postSearch').addEventListener('input',renderPosts);

// ── Post Actions ──
document.getElementById('postsTableBody').addEventListener('click',function(e){
    const btn=e.target.closest('button');
    if(!btn) return;
    const tr=btn.closest('tr');
    const idx=parseInt(tr.dataset.idx);
    currentPostIdx=idx;
    const p=posts[idx];
    if(!p) return;
    if(btn.classList.contains('btn-view-post')) showViewPost(p);
    else if(btn.classList.contains('btn-stats-post')) showPostStats(p);
    else if(btn.classList.contains('btn-edit-post')) showEditPost(p,idx);
    else if(btn.classList.contains('btn-dup-post')) duplicatePostByIdx(idx);
    else if(btn.classList.contains('btn-del-post')){
        if(confirm('Delete this post?')){posts.splice(idx,1);renderPosts();renderTopPosts();toast('Post deleted','warning');}
    }
});

function showViewPost(p){
    document.getElementById('vpPlatform').innerHTML=pBadge(p.platform);
    document.getElementById('vpType').innerHTML=tBadge(p.type);
    document.getElementById('vpStatus').innerHTML=p.status==='published'?'<span class="badge bg-success">Published</span>':'<span class="badge bg-warning">Draft</span>';
    document.getElementById('vpDate').textContent=p.date;
    document.getElementById('vpAuthor').textContent=p.author||'-';
    document.getElementById('vpHashtags').innerHTML=p.hashtags?p.hashtags.split(/[\s,]+/).filter(Boolean).map(h=>`<span class="badge bg-light text-dark me-1">${esc(h)}</span>`).join(''):'<span class="text-muted">\u2014</span>';
    document.getElementById('vpLink').innerHTML=p.link?`<a href="${esc(p.link)}" target="_blank" class="text-primary">${esc(p.link)}</a>`:'<span class="text-muted">\u2014</span>';
    document.getElementById('vpCampaign').textContent=p.campaign||'\u2014';
    document.getElementById('vpContent').textContent=p.content;
    document.getElementById('vpLikes').textContent=n(p.likes);
    document.getElementById('vpComments').textContent=n(p.comments);
    document.getElementById('vpShares').textContent=n(p.shares);
    document.getElementById('vpReach').textContent=n(p.reach);
    document.getElementById('vpImpressions').textContent=n(p.impressions);
    document.getElementById('vpClicks').textContent=n(p.clicks);
    const eng=p.reach>0?((p.likes+p.comments+p.shares)/p.reach*100).toFixed(1):'0';
    document.getElementById('vpEngRate').textContent=eng+'%';
    new bootstrap.Modal(document.getElementById('viewPostModal')).show();
}

function showEditPost(p,idx){
    document.getElementById('editPostIdx').value=idx;
    document.getElementById('editPlatform').value=p.platform;
    document.getElementById('editType').value=p.type;
    document.getElementById('editStatus').value=p.status;
    document.getElementById('editContent').value=p.content;
    document.getElementById('editHashtags').value=p.hashtags;
    document.getElementById('editCampaign').value=p.campaign||'';
    document.getElementById('editLink').value=p.link||'';
    document.getElementById('editCharCount').textContent=(p.content||'').length;
    new bootstrap.Modal(document.getElementById('editPostModal')).show();
}
document.getElementById('editContent').addEventListener('input',function(){document.getElementById('editCharCount').textContent=this.value.length;});

function saveEditPost(){
    const idx=parseInt(document.getElementById('editPostIdx').value);
    if(!posts[idx]) return;
    posts[idx].type=document.getElementById('editType').value;
    posts[idx].status=document.getElementById('editStatus').value;
    posts[idx].content=document.getElementById('editContent').value;
    posts[idx].hashtags=document.getElementById('editHashtags').value;
    posts[idx].campaign=document.getElementById('editCampaign').value;
    posts[idx].link=document.getElementById('editLink').value;
    bootstrap.Modal.getInstance(document.getElementById('editPostModal')).hide();
    renderPosts();renderTopPosts();
    toast('Post updated successfully');
}

function openEditFromView(){
    bootstrap.Modal.getInstance(document.getElementById('viewPostModal')).hide();
    setTimeout(()=>{if(posts[currentPostIdx]) showEditPost(posts[currentPostIdx],currentPostIdx);},300);
}
function duplicatePost(){
    bootstrap.Modal.getInstance(document.getElementById('viewPostModal')).hide();
    duplicatePostByIdx(currentPostIdx);
}
function duplicatePostByIdx(idx){
    if(!posts[idx]) return;
    const p={...posts[idx]};
    p.id='p_copy_'+Date.now();
    p.status='draft';
    p.date=new Date().toISOString().substring(0,10);
    p.content='[COPY] '+p.content;
    p.likes=0;p.comments=0;p.shares=0;p.reach=0;p.impressions=0;p.clicks=0;p.saves=0;p.replies=0;
    posts.unshift(p);
    renderPosts();
    toast('Post duplicated as draft');
}

async function showPostStats(p){
    document.getElementById('psImpressions').textContent=n(p.impressions);
    document.getElementById('psReach').textContent=n(p.reach);
    const totalEng=(p.likes||0)+(p.comments||0)+(p.shares||0)+(p.saves||0);
    document.getElementById('psEngagements').textContent=n(totalEng);
    document.getElementById('psEngRate').textContent=(p.reach>0?(totalEng/p.reach*100).toFixed(1):'0')+'%';
    document.getElementById('psLikes').textContent=n(p.likes);
    document.getElementById('psComments').textContent=n(p.comments);
    document.getElementById('psShares').textContent=n(p.shares);
    document.getElementById('psSaves').textContent=n(p.saves);
    document.getElementById('psClicks').textContent=n(p.clicks);
    document.getElementById('psReplies').textContent=n(p.replies);
    document.getElementById('psLocations').innerHTML='<div class="text-muted">Loading...</div>';
    document.getElementById('psAges').innerHTML='<div class="text-muted">Loading...</div>';
    document.getElementById('psGender').innerHTML='<div class="text-muted">Loading...</div>';
    new bootstrap.Modal(document.getElementById('postStatsModal')).show();

    if(p.id){
        const res=await apiFetch(API+'/social/posts/'+p.id+'/analytics');
        if(res && res.success && res.data){
            const m=res.data.metrics||res.data;
            if(m.impressions!==undefined) document.getElementById('psImpressions').textContent=n(m.impressions);
            if(m.reach!==undefined) document.getElementById('psReach').textContent=n(m.reach);
            if(m.likes!==undefined) document.getElementById('psLikes').textContent=n(m.likes);
            if(m.comments!==undefined) document.getElementById('psComments').textContent=n(m.comments);
            if(m.shares!==undefined) document.getElementById('psShares').textContent=n(m.shares);
            if(m.saves!==undefined) document.getElementById('psSaves').textContent=n(m.saves);
            if(m.clicks!==undefined) document.getElementById('psClicks').textContent=n(m.clicks);
            if(m.replies!==undefined) document.getElementById('psReplies').textContent=n(m.replies);
            const te=(m.likes||0)+(m.comments||0)+(m.shares||0)+(m.saves||0);
            document.getElementById('psEngagements').textContent=n(te);
            document.getElementById('psEngRate').textContent=((m.reach||0)>0?(te/m.reach*100).toFixed(1):'0')+'%';
            if(m.locations && Array.isArray(m.locations)){
                document.getElementById('psLocations').innerHTML=m.locations.map(l=>`<div class="mb-1">${esc(l.country||l.name)} \u2014 ${l.percentage||l.pct||0}%</div>`).join('')||'<div class="text-muted">No data</div>';
            } else { document.getElementById('psLocations').innerHTML='<div class="text-muted">No data</div>'; }
            if(m.age_groups && Array.isArray(m.age_groups)){
                document.getElementById('psAges').innerHTML=m.age_groups.map(a=>`<div class="mb-1">${esc(a.range||a.label)} \u2014 ${a.percentage||a.pct||0}%</div>`).join('')||'<div class="text-muted">No data</div>';
            } else { document.getElementById('psAges').innerHTML='<div class="text-muted">No data</div>'; }
            if(m.gender && Array.isArray(m.gender)){
                document.getElementById('psGender').innerHTML=m.gender.map(g=>`<div class="mb-1">${esc(g.label||g.type)} \u2014 ${g.percentage||g.pct||0}%</div>`).join('')||'<div class="text-muted">No data</div>';
            } else { document.getElementById('psGender').innerHTML='<div class="text-muted">No data</div>'; }
        } else {
            document.getElementById('psLocations').innerHTML='<div class="text-muted">No data</div>';
            document.getElementById('psAges').innerHTML='<div class="text-muted">No data</div>';
            document.getElementById('psGender').innerHTML='<div class="text-muted">No data</div>';
        }
    }
}

// ── Render Scheduled ──
function renderScheduled(){
    document.getElementById('scheduledCount').textContent=scheduled.length;
    const tbody=document.getElementById('scheduledTableBody');
    if(!scheduled.length){tbody.innerHTML='<tr><td colspan="6" class="text-center text-muted py-4"><i class="ri-calendar-schedule-line fs-24 d-block mb-2"></i>No scheduled posts</td></tr>';return;}
    tbody.innerHTML=scheduled.map((s,i)=>`<tr data-idx="${i}">
        <td>${(s.platforms||[]).map(p=>pBadge(p)).join(' ')}</td>
        <td><span class="d-inline-block text-truncate" style="max-width:220px" title="${esc(s.content)}">${esc(s.content)}</span></td>
        <td>${tBadge(s.type)}</td>
        <td><i class="ri-calendar-line me-1 text-muted"></i>${esc(s.scheduledFor)}</td>
        <td>${esc(s.author||'')}</td>
        <td>
            <div class="d-flex gap-1">
                <button class="btn btn-sm btn-soft-warning btn-edit-sched" title="Edit"><i class="ri-edit-line"></i></button>
                <button class="btn btn-sm btn-soft-success btn-pub-sched" title="Publish Now"><i class="ri-send-plane-line"></i></button>
                <button class="btn btn-sm btn-soft-danger btn-del-sched" title="Cancel"><i class="ri-close-line"></i></button>
            </div>
        </td>
    </tr>`).join('');
}
document.getElementById('scheduledTableBody').addEventListener('click',function(e){
    const btn=e.target.closest('button');
    if(!btn) return;
    const idx=parseInt(btn.closest('tr').dataset.idx);
    if(btn.classList.contains('btn-del-sched')){
        if(confirm('Cancel this scheduled post?')){scheduled.splice(idx,1);renderScheduled();toast('Scheduled post cancelled','warning');}
    } else if(btn.classList.contains('btn-pub-sched')){
        const s=scheduled[idx];
        publishViaApi(s.content,s.platforms||[],null,null,null,null,null);
        scheduled.splice(idx,1);
        renderScheduled();
    } else if(btn.classList.contains('btn-edit-sched')){
        toast('Opening editor for scheduled post...','info');
    }
});

// ── Render Inbox ──
function renderInbox(){
    const filter=document.querySelector('.inbox-filter-pill.btn-primary')?.dataset.filter||'all';
    const pf=document.getElementById('inboxPlatformFilter').value;
    const search=document.getElementById('inboxSearch').value.toLowerCase();
    let filtered=inbox.filter(m=>{
        if(filter==='unread'&&m.read) return false;
        if(filter==='comments'&&m.type!=='comment') return false;
        if(filter==='messages'&&m.type!=='message') return false;
        if(pf&&m.platform!==pf) return false;
        if(search&&!(m.message||'').toLowerCase().includes(search)&&!(m.from||'').toLowerCase().includes(search)) return false;
        return true;
    });
    document.getElementById('inboxCount').textContent=inbox.filter(m=>!m.read).length;
    const tbody=document.getElementById('inboxTableBody');
    if(!filtered.length){tbody.innerHTML='<tr><td colspan="7" class="text-center text-muted py-4"><i class="ri-message-3-line fs-24 d-block mb-2"></i>No messages</td></tr>';return;}
    tbody.innerHTML=filtered.map((m,i)=>{
        const idx=inbox.indexOf(m);
        const rowClass=m.read?'':'table-active';
        const dot=m.read?'':'<span class="badge bg-primary rounded-circle p-1"> </span>';
        const typeBadge=m.type==='comment'?'<span class="badge bg-info-subtle text-info">Comment</span>':'<span class="badge bg-primary-subtle text-primary">Message</span>';
        return `<tr class="${rowClass}" data-idx="${idx}">
            <td>${dot}</td>
            <td>${pBadge(m.platform)}</td>
            <td class="fw-semibold">${esc(m.from)}</td>
            <td><span class="d-inline-block text-truncate" style="max-width:250px">${esc(m.message)}</span></td>
            <td>${typeBadge}</td>
            <td><small class="text-muted">${esc(m.date)}</small></td>
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
document.querySelectorAll('.inbox-filter-pill').forEach(pill=>{
    pill.addEventListener('click',function(){
        document.querySelectorAll('.inbox-filter-pill').forEach(p=>{p.classList.remove('btn-primary');p.classList.add('btn-light');});
        this.classList.remove('btn-light');this.classList.add('btn-primary');
        renderInbox();
    });
});
document.getElementById('inboxPlatformFilter').addEventListener('change',renderInbox);
document.getElementById('inboxSearch').addEventListener('input',renderInbox);

document.getElementById('inboxTableBody').addEventListener('click',function(e){
    const btn=e.target.closest('button');
    if(!btn) return;
    const idx=parseInt(btn.closest('tr').dataset.idx);
    const m=inbox[idx];
    if(!m) return;
    if(btn.classList.contains('btn-reply-msg')){
        document.getElementById('replyIdx').value=idx;
        document.getElementById('replyFrom').textContent=m.from;
        document.getElementById('replyPlatformBadge').innerHTML=pBadge(m.platform);
        document.getElementById('replyOriginal').textContent=m.message;
        document.getElementById('replyText').value='';
        new bootstrap.Modal(document.getElementById('replyModal')).show();
    } else if(btn.classList.contains('btn-read-msg')){
        m.read=true;
        renderInbox();
        toast('Marked as read');
    } else if(btn.classList.contains('btn-view-ref')){
        const post=posts.find(p=>p.id==m.postRef);
        if(post) showViewPost(post);
    }
});

function insertQuickReply(type){
    const replies={
        thank:'Dziekujemy za mily komentarz! Cieszymy sie, ze moglismy pomoc!',
        contact:'Prosimy o kontakt: tel. +48 579 266 493 lub email wincasetop@gmail.com. Chetnie pomozemy!',
        dm:'Prosimy o wiadomosc prywatna \u2014 odpowiemy na wszystkie pytania szczegolow!'
    };
    document.getElementById('replyText').value=replies[type]||'';
}
function sendReply(){
    const idx=parseInt(document.getElementById('replyIdx').value);
    if(inbox[idx]) inbox[idx].read=true;
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
    const tbody=document.getElementById('hashtagsTableBody');
    if(!hashtags.length){tbody.innerHTML='<tr><td colspan="7" class="text-center text-muted py-4">No tracked hashtags</td></tr>';return;}
    tbody.innerHTML=hashtags.map((h,i)=>{
        const trendIcon=h.trend==='up'?'<span class="text-success"><i class="ri-arrow-up-s-line"></i> Rising</span>':h.trend==='down'?'<span class="text-danger"><i class="ri-arrow-down-s-line"></i> Falling</span>':'<span class="text-muted"><i class="ri-subtract-line"></i> Stable</span>';
        return `<tr>
            <td><span class="fw-semibold text-primary">${esc(h.tag)}</span></td>
            <td class="text-center">${h.ourPosts||0}</td>
            <td class="text-center">${esc(String(h.totalPosts||0))}</td>
            <td class="text-center">${n(h.avgReach||0)}</td>
            <td class="text-center">${esc(String(h.avgEng||'0%'))}</td>
            <td>${trendIcon}</td>
            <td><button class="btn btn-sm btn-soft-danger btn-remove-hashtag" data-idx="${i}" title="Remove"><i class="ri-delete-bin-line"></i></button></td>
        </tr>`;
    }).join('');
}
document.getElementById('hashtagsTableBody').addEventListener('click',function(e){
    const btn=e.target.closest('.btn-remove-hashtag');
    if(!btn) return;
    hashtags.splice(parseInt(btn.dataset.idx),1);
    renderHashtags();
    toast('Hashtag removed','warning');
});
function showAddHashtag(){new bootstrap.Modal(document.getElementById('addHashtagModal')).show();}
function addHashtag(){
    const tag='#'+document.getElementById('newHashtag').value.replace(/^#/,'').trim();
    if(!tag||tag==='#') return;
    hashtags.unshift({tag:tag,ourPosts:0,totalPosts:'\u2014',avgReach:0,avgEng:'0%',trend:'stable'});
    bootstrap.Modal.getInstance(document.getElementById('addHashtagModal')).hide();
    document.getElementById('newHashtag').value='';
    renderHashtags();
    toast('Hashtag added for tracking');
}

// ── Create Post (API publish via FormData) ──
document.getElementById('cpContent').addEventListener('input',function(){document.getElementById('cpCharCount').textContent=this.value.length;});
document.getElementById('cpPostNow').addEventListener('change',function(){document.getElementById('cpSchedule').disabled=this.checked;});

async function publishViaApi(text,platforms,mediaUrl,videoUrl,link,title,scheduledAt){
    const fd=new FormData();
    fd.append('text',text);
    platforms.forEach(p=>fd.append('platforms[]',p));
    if(mediaUrl) fd.append('media_url',mediaUrl);
    if(videoUrl) fd.append('video_url',videoUrl);
    if(link) fd.append('link',link);
    if(title) fd.append('title',title);
    if(scheduledAt) fd.append('scheduled_at',scheduledAt);
    const res=await apiPost(API+'/social/publish',fd);
    if(res&&res.success){
        toast(res.message||'Published to '+platforms.length+' platform(s)!');
        loadPosts();
    }
    return res;
}

async function publishPost(){
    const content=document.getElementById('cpContent').value.trim();
    if(!content){toast('Please enter post content','danger');return;}
    const platforms=[];
    document.querySelectorAll('#createPostModal .form-check-input[type="checkbox"]:checked').forEach(cb=>{if(cb.value)platforms.push(cb.value);});
    if(!platforms.length){toast('Select at least one platform','danger');return;}
    const postNow=document.getElementById('cpPostNow').checked;
    const schedDate=document.getElementById('cpSchedule').value;
    if(!postNow&&!schedDate){toast('Set schedule date or check "Post now"','danger');return;}

    const link=document.getElementById('cpLink').value.trim()||null;
    const title=document.getElementById('cpCampaign').value.trim()||null;
    const scheduledAt=(!postNow&&schedDate)?schedDate.replace('T',' '):null;

    const pubBtn=document.querySelector('#createPostModal .btn-primary');
    if(pubBtn){pubBtn.disabled=true;pubBtn.innerHTML='<span class="spinner-border spinner-border-sm me-1"></span>Publishing...';}

    await publishViaApi(content,platforms,null,null,link,title,scheduledAt);

    if(pubBtn){pubBtn.disabled=false;pubBtn.innerHTML='<i class="ri-send-plane-line me-1"></i>Publish';}
    bootstrap.Modal.getInstance(document.getElementById('createPostModal')).hide();
    document.getElementById('cpContent').value='';
    document.getElementById('cpHashtags').value='';
    document.getElementById('cpCampaign').value='';
    document.getElementById('cpLink').value='';
    document.getElementById('cpCharCount').textContent='0';
}

function saveAsDraft(){
    const content=document.getElementById('cpContent').value.trim();
    if(!content){toast('Please enter post content','danger');return;}
    const platforms=[];
    document.querySelectorAll('#createPostModal .form-check-input[type="checkbox"]:checked').forEach(cb=>{if(cb.value)platforms.push(cb.value);});
    posts.unshift({
        id:'draft_'+Date.now(),platform:platforms[0]||'facebook',type:document.getElementById('cpType').value,
        status:'draft',date:new Date().toISOString().substring(0,10),content:content,
        author:document.getElementById('cpAuthor').value,hashtags:document.getElementById('cpHashtags').value,
        likes:0,comments:0,shares:0,reach:0,impressions:0,clicks:0,saves:0,replies:0,
        campaign:document.getElementById('cpCampaign').value,link:document.getElementById('cpLink').value
    });
    bootstrap.Modal.getInstance(document.getElementById('createPostModal')).hide();
    renderPosts();
    toast('Post saved as draft');
}

// ── Top Performing Posts ──
function renderTopPosts(){
    const published=posts.filter(p=>p.status==='published');
    const sorted=[...published].sort((a,b)=>((b.likes||0)+(b.comments||0)+(b.shares||0))-((a.likes||0)+(a.comments||0)+(a.shares||0))).slice(0,4);
    const row=document.getElementById('topPostsRow');
    if(!sorted.length){row.innerHTML='<div class="col-12 text-center text-muted py-4">No posts data yet</div>';return;}
    row.innerHTML=sorted.map(p=>{
        const te=(p.likes||0)+(p.comments||0)+(p.shares||0);
        return `<div class="col-xl-3 col-md-6 mb-3">
            <div class="card border mb-0 h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center gap-2 mb-2">${pBadge(p.platform)} ${tBadge(p.type)}</div>
                    <p class="text-truncate mb-2" title="${esc(p.content)}">${esc((p.content||'').substring(0,80))}${(p.content||'').length>80?'...':''}</p>
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
    let csv='Platform,Type,Status,Date,Content,Likes,Comments,Shares,Reach,Impressions,Clicks\n';
    posts.forEach(p=>{
        csv+=`"${platformCfg[p.platform]?.label||p.platform}","${p.type}","${p.status}","${p.date}","${(p.content||'').replace(/"/g,'""')}",${p.likes||0},${p.comments||0},${p.shares||0},${p.reach||0},${p.impressions||0},${p.clicks||0}\n`;
    });
    const blob=new Blob([csv],{type:'text/csv'});
    const a=document.createElement('a');a.href=URL.createObjectURL(blob);a.download='social-media-posts.csv';a.click();
    toast('Posts exported to CSV');
}

// ── Charts ──
const chartColors=['#3b82f6','#ef4444','#111827','#06b6d4','#dc2626','#0ea5e9','#e11d48','#6b7280'];
const platformOrder=['facebook','instagram','tiktok','linkedin','youtube','telegram','pinterest','threads'];
const platformLabels=platformOrder.map(p=>platformCfg[p].label);

function renderEngagementChart(){
    const days=[];
    for(let i=6;i>=0;i--){const d=new Date();d.setDate(d.getDate()-i);days.push(d.toISOString().substring(0,10));}
    const dayLabels=days.map(d=>{const dt=new Date(d);return ['Sun','Mon','Tue','Wed','Thu','Fri','Sat'][dt.getDay()];});
    const series=platformOrder.map(plat=>({
        name:platformCfg[plat].label,
        data:days.map(day=>posts.filter(p=>p.platform===plat&&p.date===day&&p.status==='published').reduce((s,p)=>s+(p.likes||0)+(p.comments||0)+(p.shares||0),0))
    }));
    const opts={
        chart:{type:'line',height:350,toolbar:{show:false},zoom:{enabled:false}},
        series:series,
        xaxis:{categories:dayLabels},
        yaxis:{title:{text:'Engagements'}},
        colors:chartColors,
        stroke:{curve:'smooth',width:2},
        grid:{borderColor:'#f1f1f1'},
        legend:{position:'top'},
        tooltip:{shared:true,intersect:false}
    };
    if(engagementChart){engagementChart.updateOptions(opts);}
    else{engagementChart=new ApexCharts(document.querySelector("#engagementChart"),opts);engagementChart.render();}
}

function renderFollowersDonut(accs){
    const map={};(accs||[]).forEach(a=>{map[(a.platform||'').toLowerCase()]=a.followers||0;});
    const series=platformOrder.map(p=>map[p]||0);
    const total=series.reduce((s,v)=>s+v,0);
    const opts={
        chart:{type:'donut',height:350},
        series:series,
        labels:platformLabels,
        colors:chartColors,
        legend:{position:'bottom'},
        plotOptions:{pie:{donut:{labels:{show:true,total:{show:true,label:'Total',formatter:()=>n(total)}}}}},
        dataLabels:{enabled:false}
    };
    if(followersDonutChart){followersDonutChart.updateOptions(opts);}
    else{followersDonutChart=new ApexCharts(document.querySelector("#followersDonut"),opts);followersDonutChart.render();}
}

function renderContentTypeChart(){
    const types=['image','video','reel','text','carousel','article'];
    const typeLabels=types.map(t=>typeCfg[t]?.label||t);
    const avgReach=types.map(t=>{const ps=posts.filter(p=>p.type===t&&p.status==='published');return ps.length?Math.round(ps.reduce((s,p)=>s+(p.reach||0),0)/ps.length):0;});
    const avgEng=types.map(t=>{const ps=posts.filter(p=>p.type===t&&p.status==='published');return ps.length?Math.round(ps.reduce((s,p)=>s+(p.likes||0)+(p.comments||0)+(p.shares||0),0)/ps.length):0;});
    const opts={
        chart:{type:'bar',height:300,toolbar:{show:false}},
        series:[{name:'Avg Reach',data:avgReach},{name:'Avg Engagement',data:avgEng}],
        xaxis:{categories:typeLabels},
        colors:['#3b82f6','#10b981'],
        plotOptions:{bar:{horizontal:false,columnWidth:'55%',borderRadius:4}},
        yaxis:[{title:{text:'Avg Reach'}},{opposite:true,title:{text:'Avg Engagement'}}],
        grid:{borderColor:'#f1f1f1'},
        legend:{position:'top'}
    };
    if(contentTypeChartObj){contentTypeChartObj.updateOptions(opts);}
    else{contentTypeChartObj=new ApexCharts(document.querySelector("#contentTypeChart"),opts);contentTypeChartObj.render();}
}

function renderFollowersGrowthChart(){
    const months=[];const now=new Date();
    for(let i=5;i>=0;i--){const d=new Date(now.getFullYear(),now.getMonth()-i,1);months.push(d.toLocaleString('en',{month:'short'}));}
    const total=accounts.reduce((s,a)=>s+(a.followers||0),0);
    const data=months.map((m,i)=>Math.round(total*(0.65+(i*0.07))));
    data[data.length-1]=total;
    const opts={
        chart:{type:'area',height:300,toolbar:{show:false}},
        series:[{name:'Total Followers',data:data}],
        xaxis:{categories:months},
        colors:['#3b82f6'],
        fill:{type:'gradient',gradient:{shadeIntensity:1,opacityFrom:0.4,opacityTo:0.1}},
        stroke:{curve:'smooth',width:2},
        grid:{borderColor:'#f1f1f1'},
        dataLabels:{enabled:true,formatter:v=>n(v)}
    };
    if(followersGrowthChartObj){followersGrowthChartObj.updateOptions(opts);}
    else{followersGrowthChartObj=new ApexCharts(document.querySelector("#followersGrowthChart"),opts);followersGrowthChartObj.render();}
}

// ── Chart range buttons ──
document.querySelectorAll('.chart-range').forEach(btn=>{
    btn.addEventListener('click',function(){
        document.querySelectorAll('.chart-range').forEach(b=>b.classList.remove('active'));
        this.classList.add('active');
        const range=this.dataset.range;
        const daysMap={'7d':7,'30d':30,'90d':90};
        const numDays=daysMap[range]||7;
        const days=[];
        for(let i=numDays-1;i>=0;i--){const d=new Date();d.setDate(d.getDate()-i);days.push(d.toISOString().substring(0,10));}
        const dayLabels=numDays<=7
            ?days.map(d=>{const dt=new Date(d);return ['Sun','Mon','Tue','Wed','Thu','Fri','Sat'][dt.getDay()];})
            :days.map(d=>d.substring(5));
        const series=platformOrder.map(plat=>({
            name:platformCfg[plat].label,
            data:days.map(day=>posts.filter(p=>p.platform===plat&&p.date===day&&p.status==='published').reduce((s,p)=>s+(p.likes||0)+(p.comments||0)+(p.shares||0),0))
        }));
        if(engagementChart) engagementChart.updateOptions({series:series,xaxis:{categories:dayLabels}});
        toast('Chart updated to '+range,'info');
    });
});

// ── Init: Load all data from API ──
async function init(){
    renderPosts();renderScheduled();renderInbox();renderHashtags();renderTopPosts();
    renderEngagementChart();renderFollowersDonut([]);renderContentTypeChart();renderFollowersGrowthChart();
    await Promise.all([loadAccounts(),loadPosts(),loadCalendar()]);
    renderFollowersGrowthChart();
}
init();
</script>
<style>
.sm-section{display:none}
.sm-section.active{display:block}
.table-active{background-color:rgba(var(--bs-primary-rgb),0.05)!important}
</style>
@endsection
