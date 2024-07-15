<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    <title>@yield('title')</title>
    <link rel="shortcut icon" type="image/png" href="{{ asset('/logo.png') }}" />
    <link rel="stylesheet" href="{{ asset('/css/styles.min.css') }}" />
    @yield("extra-css")
    @livewireStyles
</head>

<body>
    <!--  Body Wrapper -->
    <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
        data-sidebar-position="fixed" data-header-position="fixed">
        <!-- Sidebar Start -->
        <aside class="left-sidebar">
            <!-- Sidebar scroll-->
            <div>
                <div class="brand-logo d-flex align-items-center justify-content-between">
                    <a
                        href=""
                        class="text-nowrap logo-img"
                        style="padding-top: 1.5rem"
                    >
                        <img src="{{ asset('/logo-horizontal.png') }}" width="180" alt="" />
                    </a>
                    <div class="close-btn d-xl-none d-block sidebartoggler cursor-pointer" id="sidebarCollapse">
                        <i class="ti ti-x fs-8"></i>
                    </div>
                </div>
                <!-- Sidebar navigation-->
                <nav class="sidebar-nav scroll-sidebar" data-simplebar="">
                    <ul id="sidebarnav">
                        <li class="nav-small-cap">
                            <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                            <span class="hide-menu">Home</span>
                        </li>
                        <li class="sidebar-item">
                            <a class="sidebar-link" href="/" aria-expanded="false">
                                <span>
                                <i class="ti ti-layout-dashboard"></i>
                                </span>
                                <span class="hide-menu">Dashboard</span>
                            </a>
                        </li>
                        @if(auth()->user()->hasRole('base.role_superadmin'))
                        <li class="sidebar-item">
                            <a class="sidebar-link" href="/banner" aria-expanded="false">
                                <span>
                                    <i class="ti ti-flag"></i>
                                </span>
                                <span class="hide-menu">Banner</span>
                            </a>
                        </li>
                        @endif
                        @if(auth()->user()->hasRole('base.role_admin'))
                        <li class="sidebar-item">
                            <a class="sidebar-link" href="/product" aria-expanded="false">
                                <span>
                                    <i class="ti ti-box"></i>
                                </span>
                                <span class="hide-menu">Product</span>
                            </a>
                        </li>
                        @endif
                        @if(auth()->user()->hasRole('base.role_admin'))
                        <li class="sidebar-item">
                            <a class="sidebar-link" href="/transaction" aria-expanded="false">
                                <span>
                                    <i class="ti ti-receipt"></i>
                                </span>
                                <span class="hide-menu">Transaksi</span>
                            </a>
                        </li>
                        @endif
                        @if(auth()->user()->hasRole('base.role_superadmin'))
                        <li class="sidebar-item">
                            <a class="sidebar-link" href="/users" aria-expanded="false">
                                <span>
                                    <i class="ti ti-users"></i>
                                </span>
                                <span class="hide-menu">Users</span>
                            </a>
                        </li>
                        @endif
                    </ul>
                </nav>
                <!-- End Sidebar navigation -->
            </div>
            <!-- End Sidebar scroll-->
        </aside>
        <!--  Sidebar End -->
        <!--  Main wrapper -->
        <div class="body-wrapper">
            <!--  Header Start -->
            <header class="app-header">
                <nav class="navbar navbar-expand-lg navbar-light">
                    <ul class="navbar-nav">
                        <li class="nav-item d-block d-xl-none">
                            <a class="nav-link sidebartoggler nav-icon-hover" id="headerCollapse"
                                href="javascript:void(0)">
                                <i class="ti ti-menu-2"></i>
                            </a>
                        </li>

                    </ul>
                    <div class="navbar-collapse justify-content-end px-0" id="navbarNav">
                        <ul class="navbar-nav flex-row ms-auto align-items-center justify-content-end">
                            <li class="nav-item dropdown">
                                <a class="nav-link nav-icon-hover" href="javascript:void(0)" id="drop2"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    <img src="{{ asset(auth()->user()->profile??'images/profile/no-images.png') }}" alt="" width="35"
                                        height="35" class="rounded-circle">
                                </a>
                                <div class="dropdown-menu dropdown-menu-end dropdown-menu-animate-up"
                                    aria-labelledby="drop2">
                                    <div class="message-body">
                                        <button
                                            class="d-flex align-items-center gap-2 dropdown-item"
                                            data-bs-toggle="modal"
                                            data-bs-target="#modalProfileEdit{{auth()->user()->id}}"
                                        >
                                            <i class="ti ti-mail fs-6"></i>
                                            <p class="mb-0 fs-3">My Account</p>
                                        </button>
                                        <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button
                                            type="submit"
                                            class="btn btn-outline-primary mx-3 mt-2 d-block">Logout
                                        </button>
                                        </form>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </nav>
            </header>
            <!--  Header End -->
            @yield('main')

            <!-- Profile Change Modal -->
            <div class="modal fade" id="modalProfileEdit{{auth()->user()->id}}" tabindex="-1" role="dialog" aria-labelledby="modalProfileEdit{{auth()->user()->id}}Title" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <form enctype="multipart/form-data" method="POST" action="{{ route('users.edit', ['id' => auth()->user()->id]) }}">
                        @csrf
                        @method("PUT")
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalProfileEdit{{auth()->user()->id}}Title">My Profile</h5>
                            <button type="button" class="close btn btn-error" data-bs-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="row mb-3">
                                <div class="mb-3 col-sm-12 col-md-6">
                                    <label for="name" class="form-label">Name</label>
                                    <input type="text" value="{{ auth()->user()->name }}" name="name" class="form-control" id="name" aria-describedby="name">
                                </div>
                                <div class="mb-3 col-sm-12 col-md-6">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" value="{{ auth()->user()->email }}" name="email" class="form-control" id="email" aria-describedby="email">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="mb-3 col-sm-12 col-md-6">
                                    <label for="description" class="form-label">Description</label>
                                    <input type="text" value="{{ auth()->user()->description }}" name="description" class="form-control" id="description" aria-describedby="description">
                                </div>
                                <div class="mb-3 col-sm-12 col-md-6">
                                    <label for="address" class="form-label">Address</label>
                                    <input type="text" value="{{ auth()->user()->address }}" name="address" class="form-control" id="address" aria-describedby="address">
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" name="password" class="form-control" id="password" aria-describedby="password">
                            </div>
                            <div class="mb-3">
                                <label for="profile" class="form-label">Profile</label>
                                <input type="file" name="profile" class="form-control" id="profile" aria-describedby="profile">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- end -->
        </div>
    </div>
    <script src="{{ asset('/libs/jquery/dist/jquery.min.js') }}"></script>
    <script src="{{ asset('/libs/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('/js/sidebarmenu.js') }}"></script>
    <script src="{{ asset('/js/app.min.js') }}"></script>
    <script src="{{ asset('/libs/simplebar/dist/simplebar.js') }} "></script>
    @yield("extra-js")
    @livewireScripts
</body>

</html>
