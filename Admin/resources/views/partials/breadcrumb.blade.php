<div class="hstack flex-wrap gap-3 mb-4">
    <div class="flex-grow-1">
        <h4 class="mb-1 fw-semibold" @hasSection('sub-title-lang') data-lang="@yield('sub-title-lang')" @endif>@yield('sub-title')</h4>
        <nav>
            <ol class="breadcrumb breadcrumb-arrow mb-0">
                <li class="breadcrumb-item">
                    <a href="{{ url('index') }}" @hasSection('pagetitle-lang') data-lang="@yield('pagetitle-lang')" @endif>@yield('pagetitle')</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page" @hasSection('sub-title-lang') data-lang="@yield('sub-title-lang')" @endif>@yield('sub-title')</li>
            </ol>
        </nav>
    </div>
    <div class="d-flex my-xl-auto align-items-center flex-shrink-0">
        @if (!empty(trim(View::yieldContent('modalTarget'))))
            <a href="javascript:void(0)"
               class="btn btn-sm btn-primary"
               data-bs-toggle="modal"
               data-bs-target="#@yield('modalTarget')"
               @hasSection('buttonTitle-lang') data-lang="@yield('buttonTitle-lang')" @endif>
                @yield('buttonTitle')
            </a>
        @else
            <a href="{{ url(trim(View::yieldContent('link'))) }}" class="btn btn-sm btn-primary"
               @hasSection('buttonTitle-lang') data-lang="@yield('buttonTitle-lang')" @endif>
                @yield('buttonTitle')
            </a>
        @endif
    </div>
  </div>
  