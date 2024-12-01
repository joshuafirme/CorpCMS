@php
    $permissions = App\Models\UserRole::permissions();
@endphp
<div id="layoutSidenav_nav">
    <nav class="sidenav shadow-right sidenav-light">
        <div class="sidenav-menu">
            <div class="nav accordion" id="accordionSidenav">
                <!-- Sidenav Menu Heading (Core)-->
                <div class="sidenav-menu-heading">Core</div>
                <!-- Sidenav Accordion (Dashboard)-->
                {{-- @if (in_array('dashboard', $permissions))
                    <a class="nav-link" href="{{ url('admin/dashboard') }}">
                        <div class="nav-link-icon"><i data-feather="activity"></i></div>
                        Dashboard
                    </a>
                @endif --}}

                <a class="nav-link" href="{{ url('admin/news') }}">
                    <div class="nav-link-icon"><i class="fa-regular fa-newspaper"></i></div>
                    News
                </a>

                <a class="nav-link" href="{{ url('admin/sliders') }}">
                    <div class="nav-link-icon"><i class="fa-solid fa-sliders"></i></div>
                    Sliders
                </a>

                <a class="nav-link" href="{{ url('admin/gallery') }}">
                    <div class="nav-link-icon"><i class="fa-solid fa-images"></i></div>
                    Gallery
                </a>

                <a class="nav-link" href="{{ url('admin/messages') }}">
                    <div class="nav-link-icon"><i class="fa-regular fa-message"></i></div>
                    Messages
                </a>

                <a class="nav-link collapsed" href="javascript:void(0);" data-bs-toggle="collapse"
                    data-bs-target="#collapsePages" aria-expanded="false" aria-controls="collapsePages">
                    <div class="nav-link-icon"><i class="fa-solid fa-pager"></i></div>
                    Pages
                    <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse" id="collapsePages" data-bs-parent="#accordionSidenav">
                    <nav class="sidenav-menu-nested nav">
                        <a class="nav-link" href="{{ url("admin/page-content/about-us") }}">About us</a>
                        <a class="nav-link" href="{{ url('admin/page-content/contact-us') }}">Contact us</a>
                        <a class="nav-link" href="{{ url('admin/page-content/privacy-policy') }}">Privacy policy</a>
                        <a class="nav-link" href="{{ url('admin/page-content/terms-of-service') }}">Terms of service</a>
                    </nav>
                </div>

                <a class="nav-link" href="{{ url('admin/settings') }}">
                    <div class="nav-link-icon"><i class="fa-solid fa-gear"></i></div>
                    General Settings
                </a>

                @php
                    $administration_modules = ['users', 'roles'];
                @endphp
                @if (array_intersect($administration_modules, $permissions))
                    <a class="nav-link collapsed" href="javascript:void(0);" data-bs-toggle="collapse"
                        data-bs-target="#collapseAdministration" aria-expanded="false"
                        aria-controls="collapseAdministration">
                        <div class="nav-link-icon"><i data-feather="user"></i></div>
                        Administration
                        <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                    </a>
                    <div class="collapse" id="collapseAdministration" data-bs-parent="#accordionSidenav">
                        <nav class="sidenav-menu-nested nav">
                            <a class="nav-link" href="{{ url('/admin/users') }}">Users</a>
                            <a class="nav-link" href="{{ url('/admin/user-roles') }}">Roles & Permissions</a>
                        </nav>
                    </div>
                @endif

            </div>
        </div>
    </nav>
</div>
