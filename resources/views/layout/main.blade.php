<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title }}</title>
    <link rel="shortcut icon" type="image/png" href="{{ asset('assets/images/logos/favicon.png') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/styles.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/custom.css') }}" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
        integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

</head>

<body>
    <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
        data-sidebar-position="fixed" data-header-position="fixed">
        <aside class="left-sidebar">
            <div>
                <div class="brand-logo d-flex align-items-center justify-content-between">
                    <a href="./index.html" class="text-nowrap logo-img">
                        <img src="{{ asset('assets/images/logos/dark-logo.svg') }}" width="180" alt="" />
                    </a>
                    <div class="close-btn d-xl-none d-block sidebartoggler cursor-pointer" id="sidebarCollapse">
                        <i class="ti ti-x fs-8"></i>
                    </div>
                </div>
                <nav class="sidebar-nav scroll-sidebar" data-simplebar="">
                    <ul id="sidebarnav">
                        @if (Auth::user()->id_role === 1)
                            <li class="nav-small-cap">
                                <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                                <span class="hide-menu">Admin Menu</span>
                            </li>

                            <li class="sidebar-item">
                                <a class="sidebar-link {{ Request::routeIs('admin.dashboard') ? 'active' : '' }}"
                                    href="{{ route('admin.dashboard') }}" aria-expanded="false">
                                    <span>
                                        <i class="ti ti-layout-dashboard"></i>
                                    </span>
                                    <span class="hide-menu">Dashboard</span>
                                </a>
                            </li>

                            <li class="sidebar-item">
                                <a class="sidebar-link {{ Request::routeIs('absensi.dosen') ? 'active' : '' }}"
                                    href="{{ route('absensi.dosen') }}" aria-expanded="false">
                                    <span>
                                        <i class="ti ti-calendar"></i>
                                    </span>
                                    <span class="hide-menu">Absensi Dosen</span>
                                </a>
                            </li>

                            <li class="sidebar-item">
                                <a class="sidebar-link {{ Request::routeIs('data.dosen') ? 'active' : '' }}"
                                    href="{{ route('data.dosen') }}" aria-expanded="false">
                                    <span>
                                        <i class="ti ti-user"></i>
                                    </span>
                                    <span class="hide-menu">Data Dosen</span>
                                </a>
                            </li>

                            <li class="sidebar-item">
                                <a class="sidebar-link {{ Request::routeIs('akun.user') ? 'active' : '' }}"
                                    href="{{ route('akun.user') }}" aria-expanded="false">
                                    <span>
                                        <i class="ti ti-user"></i>
                                    </span>
                                    <span class="hide-menu">Akun User</span>
                                </a>
                            </li>
                        @elseif(Auth::user()->id_role === 2)
                            <li class="nav-small-cap">
                                <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                                <span class="hide-menu">User Menu</span>
                            </li>

                            <li class="sidebar-item">
                                <a class="sidebar-link {{ Request::routeIs('home') ? 'active' : '' }}"
                                    href="{{ route('home') }}" aria-expanded="false">
                                    <span>
                                        <i class="ti ti-layout-dashboard"></i>
                                    </span>
                                    <span class="hide-menu">Home</span>
                                </a>
                            </li>
                        @endif
                    </ul>
                </nav>
            </div>
        </aside>

        <div class="body-wrapper">
            <header class="app-header navmenu">
                <nav class="navbar navbar-expand-lg navbar-light">
                    <ul class="navbar-nav">
                        <li class="nav-item d-block d-xl-none">
                            <a class="nav-link sidebartoggler nav-icon-hover" id="headerCollapse" href="#">
                                <i class="ti ti-menu-2 text-white"></i>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link nav-icon-hover" href="#">
                                <i class="fa-solid fa-bell text-white"></i>
                                <div class="notification bg-light rounded-circle"></div>
                            </a>
                        </li>
                    </ul>
                    <div class="navbar-collapse justify-content-end px-0" id="navbarNav">
                        <ul class="navbar-nav flex-row ms-auto align-items-center justify-content-end">
                            <li class="nav-item dropdown">
                                <a class="nav-link nav-icon-hover" href="#" id="drop2"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    <img src="{{ asset('../assets/images/profile/user-1.jpg') }}" alt=""
                                        width="35" height="35" class="rounded-circle">
                                </a>
                                <div class="dropdown-menu dropdown-menu-end dropdown-menu-animate-up"
                                    aria-labelledby="drop2">
                                    <div class="message-body">
                                        <a href="#" class="d-flex align-items-center gap-2 dropdown-item">
                                            <i class="ti ti-user fs-6"></i>
                                            <p class="mb-0 fs-3">My Profile</p>
                                        </a>

                                        <form action="{{ route('logout') }}" method="POST">
                                            @csrf
                                            <button class="btn btn-danger mx-3 mt-2 d-block">
                                                <i class="ti ti-logout"></i> Logout
                                            </button>
                                        </form>

                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </nav>
            </header>

            @yield('content')

            <div class="py-6 px-6 text-center">
                <p class="mb-0 fs-4">Lean Studio</p>
            </div>

        </div>
    </div>
    <script src="{{ asset('../assets/libs/jquery/dist/jquery.min.js') }}"></script>
    <script src="{{ asset('../assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('../assets/js/sidebarmenu.js') }}"></script>
    <script src="{{ asset('../assets/js/app.min.js') }}"></script>
    <script src="{{ asset('../assets/libs/apexcharts/dist/apexcharts.min.js') }}"></script>
    <script src="{{ asset('../assets/libs/simplebar/dist/simplebar.js') }}"></script>
    <script src="{{ asset('../assets/js/dashboard.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @if (session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: '{{ session('success') }}',
                showConfirmButton: false,
                timer: 1500
            });
        </script>
    @endif
</body>

</html>
