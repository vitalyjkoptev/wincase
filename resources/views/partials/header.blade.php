<!-- START HEADER -->
<header class="app-header">
  <div class="container-fluid">
    <div class="nav-header">

      <div class="header-left hstack gap-3">
        <!-- HORIZONTAL BRAND LOGO -->
        {{-- LOGO: НЕ МЕНЯТЬ без явной просьбы пользователя! --}}
        <div class="app-sidebar-logo app-horizontal-logo justify-content-center align-items-center">
          <a href="/">
            <img src="{{ asset('assets/images/wincase-logo.png') }}" alt="WinCase" class="app-sidebar-logo-default" style="height:32px;">
            <img src="{{ asset('assets/images/wincase-icon.png') }}" alt="WC" class="app-sidebar-logo-minimize" style="height:28px;">
          </a>
        </div>

        <!-- Sidebar Toggle Btn -->
        <button type="button" class="btn btn-light-light icon-btn sidebar-toggle d-none d-md-block" aria-expanded="false" aria-controls="main-menu">
          <span class="visually-hidden">Toggle sidebar</span>
          <i class="ri-menu-2-fill"></i>
        </button>

        <!-- Sidebar Toggle for Mobile -->
        <button class="btn btn-light-light icon-btn d-md-none small-screen-toggle" type="button" aria-expanded="false" aria-controls="main-menu">
          <span class="visually-hidden">Sidebar toggle for mobile</span>
          <i class="ri-arrow-right-fill small-screen-toggle-icon"></i>
        </button>

        <!-- Sidebar Toggle for Horizontal Menu -->
        <button class="btn btn-light-light icon-btn d-lg-none small-screen-horizontal-toggle" type="button" ria-expanded="false" aria-controls="main-menu">
          <span class="visually-hidden">Sidebar toggle for horizontal</span>
          <i class="ri-arrow-right-fill"></i>
        </button>

        <!-- Search Dropdown -->
        <div class="dropdown features-dropdown">

          <!-- Search Input for Desktop -->
          <form class="d-none d-sm-block header-search" data-bs-toggle="dropdown" aria-expanded="false">
            <div class="form-icon">
              <input type="search" class="form-control form-control-icon" id="firstNameLayout4" placeholder="Search in WinCase" data-lang-placeholder="wc-search-placeholder" required>
              <i class="ri-search-2-line text-muted"></i>
            </div>
          </form>

          <!-- Search Button for Mobile -->
          <button type="button" class="btn btn-light-light icon-btn d-sm-none" data-bs-toggle="dropdown" aria-expanded="false">
            <span class="visually-hidden">Search</span>
            <i class="ri-search-2-line text-muted"></i>
          </button>

          <div class="dropdown-menu">
            <span class="dropdown-header fs-12" data-lang="wc-recent-searches">Recent searches</span>
            <div class="dropdown-item text-wrap bg-transparent">
              <span class="badge bg-light text-muted me-2" data-lang="wc-leads">Leads</span>
              <span class="badge bg-light text-muted me-2" data-lang="wc-cases">Cases</span>
              <span class="badge bg-light text-muted me-2" data-lang="wc-invoices">Invoices</span>
            </div>
            <div class="dropdown-divider"></div>
            <span class="dropdown-header fs-12" data-lang="wc-quick-links">Quick Links</span>
            <div class="dropdown-item">
              <div class="hstack gap-2 overflow-hidden">
                <button type="button" class="btn btn-light-light rounded-pill icon-btn-sm flex-shrink-0">
                  <i class="ri-user-follow-line m-0"></i>
                </button>
                <div class="flex-grow-1 text-truncate">
                  <a href="/crm-leads" class="text-body" data-lang="wc-new-lead">New Lead</a>
                </div>
              </div>
            </div>
            <div class="dropdown-item">
              <div class="hstack gap-2 overflow-hidden">
                <button type="button" class="btn btn-light-light rounded-pill icon-btn-sm flex-shrink-0">
                  <i class="ri-briefcase-line m-0"></i>
                </button>
                <div class="flex-grow-1 text-truncate">
                  <a href="/crm-cases" class="text-body" data-lang="wc-open-cases">Open Cases</a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="header-right hstack gap-3">
        <div class="hstack gap-0 gap-sm-1">

          <!-- Apps -->
          <div class="dropdown features-dropdown">
            <button type="button" class="btn icon-btn btn-text-primary rounded-circle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <span class="visually-hidden">Quick Access</span>
              <i class="bi bi-grid"></i>
            </button>
            <div class="dropdown-menu dropdown-menu-lg p-0 dropdown-menu-end">
              <div class="card shadow-none mb-0 border-0">
                <div class="card-header hstack gap-2">
                  <h5 class="card-title mb-0 flex-grow-1" data-lang="wc-quick-access">Quick Access</h5>
                </div>
                <div class="card-body px-3">
                  <div class="row g-0">
                    <div class="col">
                      <a class="dropdown-icon-item" href="/crm-calendar">
                        <div class="avatar border-0 avatar-item bg-light mx-auto mb-2">
                          <i class="bi bi-calendar-event align-middle text-muted"></i>
                        </div>
                        <p class="mb-1 h6 fw-medium" data-lang="wc-calendar">Calendar</p>
                        <p class="mb-0 text-muted fs-11" data-lang="wc-calendar-events">Events</p>
                      </a>
                    </div>

                    <div class="col">
                      <a class="dropdown-icon-item" href="/crm-tasks">
                        <div class="avatar border-0 avatar-item bg-light mx-auto mb-2">
                          <i class="bi bi-view-stacked align-middle text-muted"></i>
                        </div>
                        <p class="mb-1 h6 fw-medium" data-lang="wc-tasks">Tasks</p>
                        <p class="mb-0 text-muted fs-11" data-lang="wc-my-tasks">My Tasks</p>
                      </a>
                    </div>

                    <div class="col">
                      <a class="dropdown-icon-item" href="/crm-documents">
                        <div class="avatar border-0 avatar-item bg-light mx-auto mb-2">
                          <i class="ri-file-text-line align-middle text-muted"></i>
                        </div>
                        <p class="mb-1 h6 fw-medium" data-lang="wc-documents">Documents</p>
                        <p class="mb-0 text-muted fs-11" data-lang="wc-files">Files</p>
                      </a>
                    </div>
                  </div>

                  <div class="row g-0">
                    <div class="col">
                      <a class="dropdown-icon-item" href="/crm-leads">
                        <div class="avatar border-0 avatar-item bg-light mx-auto mb-2">
                          <i class="ri-user-follow-line align-middle text-muted"></i>
                        </div>
                        <p class="mb-1 h6 fw-medium" data-lang="wc-leads">Leads</p>
                        <p class="mb-0 text-muted fs-11" data-lang="wc-pipeline">Pipeline</p>
                      </a>
                    </div>

                    <div class="col">
                      <a class="dropdown-icon-item" href="/finance-invoices">
                        <div class="avatar border-0 avatar-item bg-light mx-auto mb-2">
                          <i class="ri-money-dollar-circle-line align-middle text-muted"></i>
                        </div>
                        <p class="mb-1 h6 fw-medium" data-lang="wc-invoices">Invoices</p>
                        <p class="mb-0 text-muted fs-11" data-lang="wc-finance">Finance</p>
                      </a>
                    </div>

                    <div class="col">
                      <a class="dropdown-icon-item" href="/analytics-sales">
                        <div class="avatar border-0 avatar-item bg-light mx-auto mb-2">
                          <i class="ri-bar-chart-box-line align-middle text-muted"></i>
                        </div>
                        <p class="mb-1 h6 fw-medium" data-lang="wc-reports">Reports</p>
                        <p class="mb-0 text-muted fs-11" data-lang="wc-analytics">Analytics</p>
                      </a>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Language -->
          <div class="dropdown features-dropdown" id="language-dropdown">
            <a href="#!" class="btn icon-btn btn-text-primary rounded-circle" data-bs-toggle="dropdown" aria-expanded="false">
              <div class="avatar-item avatar-xs">
                <img class="img-fluid avatar-xs" src="{{ asset('assets/images/flags/us.svg') }}" loading="lazy" alt="language flag">
              </div>
            </a>

            <div class="dropdown-menu header-language-scrollable dropdown-menu-end" data-simplebar>

              <a href="#!" class="dropdown-item py-2" data-lang-switch="en">
                <img src="{{ asset('assets/images/flags/us.svg') }}" alt="en" loading="lazy" class="me-2 rounded h-20px w-20px img-fluid object-fit-cover">
                <span class="align-middle">English</span>
              </a>

              <a href="#!" class="dropdown-item" data-lang-switch="pl">
                <img src="{{ asset('assets/images/flags/pl.svg') }}" alt="pl" loading="lazy" class="me-2 rounded h-20px w-20px img-fluid object-fit-cover">
                <span class="align-middle">Polski</span>
              </a>

              <a href="#!" class="dropdown-item" data-lang-switch="ua">
                <img src="{{ asset('assets/images/flags/ua.svg') }}" alt="ua" loading="lazy" class="me-2 rounded h-20px w-20px img-fluid object-fit-cover">
                <span class="align-middle">Українська</span>
              </a>

            </div>
          </div>

          <!-- Theme -->
          <div class="dropdown features-dropdown d-none d-sm-block">
            <button type="button" class="btn icon-btn btn-text-primary rounded-circle" data-bs-toggle="dropdown" aria-expanded="false">
              <span class="visually-hidden">Light or Dark Mode Switch</span>
              <i class="ri-sun-line fs-20"></i>
            </button>

            <div class="dropdown-menu dropdown-menu-end header-language-scrollable" data-simplebar>

              <div class="dropdown-item cursor-pointer" id="light-theme">
                <span class="hstack gap-2 align-middle"><i class="ri-sun-line"></i><span data-lang="wc-theme-light">Light</span></span>
              </div>
              <div class="dropdown-item cursor-pointer" id="dark-theme">
                <span class="hstack gap-2 align-middle"><i class="ri-moon-clear-line"></i><span data-lang="wc-theme-dark">Dark</span></span>
              </div>
              <div class="dropdown-item cursor-pointer" id="system-theme">
                <span class="hstack gap-2 align-middle"><i class="ri-computer-line"></i><span data-lang="wc-theme-system">System</span></span>
              </div>

            </div>
          </div>

          <!-- Customize (opens switcher offcanvas) -->
          <button type="button" class="btn icon-btn btn-text-primary rounded-circle d-none d-sm-inline-flex" data-bs-toggle="offcanvas" data-bs-target="#switcher" aria-label="Customize">
            <i class="ri-equalizer-line fs-20"></i>
          </button>

          <!-- Notification -->
          <div class="dropdown features-dropdown">
            <button type="button" class="btn icon-btn btn-text-primary rounded-circle position-relative" id="page-header-notifications-dropdown" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-haspopup="true" aria-expanded="false">
              <i class="ri-notification-2-line fs-20"></i>
              <span class="position-absolute translate-middle badge rounded-pill p-1 min-w-20px badge text-bg-danger">3</span>
            </button>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-0" aria-labelledby="page-header-notifications-dropdown">
              <div class="dropdown-header d-flex align-items-center py-3">
                <h6 class="mb-0 me-auto" data-lang="wc-notification">Notification</h6>
                <div class="d-flex align-items-center h6 mb-0">
                  <span class="badge bg-primary me-2">3 <span data-lang="wc-new">New</span></span>
                </div>
              </div>
              <ul class="list-group list-group-flush header-notification-scrollable" data-simplebar>
                <li class="list-group-item list-group-item-action border-start-0 border-end-0">
                  <div class="d-flex">
                    <div class="flex-shrink-0 me-3">
                      <div class="avatar-item avatar avatar-title bg-primary-subtle text-primary">
                        NL
                      </div>
                    </div>
                    <div class="flex-grow-1">
                      <h6 class="mb-1 small" data-lang="wc-notif-lead-assigned">New Lead Assigned</h6>
                      <small class="mb-1 d-block text-body" data-lang="wc-notif-lead-desc">Anna Kowalska — Work Permit application</small>
                      <small class="text-muted" data-lang="wc-notif-2hr">2hr ago</small>
                    </div>
                  </div>
                </li>
                <li class="list-group-item list-group-item-action border-start-0 border-end-0">
                  <div class="d-flex">
                    <div class="flex-shrink-0 me-3">
                      <div class="avatar-item avatar avatar-title bg-warning-subtle text-warning">
                        <i class="ri-file-warning-line"></i>
                      </div>
                    </div>
                    <div class="flex-grow-1">
                      <h6 class="mb-1 small" data-lang="wc-notif-doc-expiring">Document Expiring</h6>
                      <small class="mb-1 d-block text-body" data-lang="wc-notif-doc-desc">Visa for Petro Shevchenko expires in 7 days</small>
                      <small class="text-muted" data-lang="wc-notif-5hr">5hr ago</small>
                    </div>
                  </div>
                </li>
                <li class="list-group-item list-group-item-action border-start-0 border-end-0">
                  <div class="d-flex">
                    <div class="flex-shrink-0 me-3">
                      <div class="avatar-item avatar avatar-title bg-success-subtle text-success">
                        <i class="ri-checkbox-circle-line"></i>
                      </div>
                    </div>
                    <div class="flex-grow-1">
                      <h6 class="mb-1 small" data-lang="wc-notif-case-approved">Case Approved</h6>
                      <small class="mb-1 d-block text-body" data-lang="wc-notif-case-desc">Temporary Residence Permit — Case #1247</small>
                      <small class="text-muted" data-lang="wc-notif-1day">1 day ago</small>
                    </div>
                  </div>
                </li>
              </ul>
            </div>
          </div>

          <!-- Fullscreen -->
          <button type="button" id="fullscreen-button" class="btn icon-btn btn-text-primary rounded-circle custom-toggle d-none d-sm-block" aria-pressed="false">
            <span class="visually-hidden">Toggle Fullscreen</span>
            <span class="icon-on">
              <i class="ri-fullscreen-exit-line fs-16"></i>
            </span>
            <span class="icon-off">
              <i class="ri-fullscreen-line fs-16"></i>
            </span>
          </button>
        </div>

        <!-- Profile Section -->
        <div class="dropdown profile-dropdown features-dropdown">
          <button type="button" id="accountNavbarDropdown" class="btn profile-btn shadow-none px-0 hstack gap-0 gap-sm-3" data-bs-toggle="dropdown" aria-expanded="false" data-bs-auto-close="outside" data-bs-dropdown-animation>
            <span class="position-relative">
              <span class="avatar-item avatar overflow-hidden">
                <img class="img-fluid" src="{{ asset('assets/images/avatar/yulia.png') }}" alt="avatar image">
              </span>
              <span class="position-absolute border-2 border border-white h-12px w-12px rounded-circle bg-success end-0 bottom-0"></span>
            </span>
            <span>
              <span class="h6 d-none d-xl-inline-block text-start fw-semibold mb-0" id="headerUserName">{{ auth()->user()->name ?? 'User' }}</span>
              <span class="d-none d-xl-block fs-12 text-start text-muted" id="headerUserRole">{{ ucfirst(auth()->user()->role ?? 'user') }}</span>
            </span>
          </button>

          <div class="dropdown-menu dropdown-menu-end header-language-scrollable" aria-labelledby="accountNavbarDropdown">
            <div class="dropdown dropstart d-block">
              <a class="dropdown-item d-block w-100 text-start" href="#!" data-bs-toggle="dropdown" aria-expanded="false" data-lang="wc-set-status">
                Set status
              </a>
              <ul class="dropdown-menu">
                <li class="dropdown-item">
                  <span class="h-8px w-8px rounded-pill bg-success me-2"></span>
                  <span data-lang="wc-available">Available</span>
                </li>
                <li class="dropdown-item">
                  <span class="h-8px w-8px rounded-pill bg-danger me-2"></span>
                  <span data-lang="wc-busy">Busy</span>
                </li>
                <li class="dropdown-item">
                  <span class="h-8px w-8px rounded-pill bg-warning me-2"></span>
                  <span data-lang="wc-away">Away</span>
                </li>
                <li class="dropdown-divider"></li>
                <li class="dropdown-item" data-lang="wc-reset-status">
                  Reset status
                </li>
              </ul>
            </div>

            <a class="dropdown-item" href="/crm-profile" data-lang="wc-profile">Profile</a>
            <a class="dropdown-item" href="/crm-tasks" data-lang="wc-my-tasks">My Tasks</a>

            <div class="dropdown-divider"></div>

            <a class="dropdown-item" href="#!">
              <div class="d-flex align-items-center">
                <div class="flex-shrink-0">
                  <div class="avatar-item avatar avatar-title text-white bg-primary border-0 fs-12">
                    WC
                  </div>
                </div>
                <div class="flex-grow-1 ms-2">
                  <h6 class="mb-0 lh-1">WinCase <span class="badge bg-primary-subtle text-primary rounded-pill text-uppercase ms-1 mb-0 py-1 fs-10">CRM</span></h6>
                  <span class="card-text text-muted">wincase.eu</span>
                </div>
              </div>
            </a>

            <div class="dropdown-divider"></div>

            <a class="dropdown-item" href="/admin-settings" data-lang="wc-settings">Settings</a>

            <div class="dropdown-divider"></div>

            <form method="POST" action="/logout" class="d-inline">
              @csrf
              <button type="submit" class="dropdown-item" onclick="localStorage.removeItem('wc_token')">
                <i class="ri-logout-box-r-line me-1"></i> Sign out
              </button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>

</header>
<!-- END HEADER -->
