@php
    $permissions = App\Models\UserRole::permissions();
@endphp
<div id="layoutSidenav_nav">
    <nav class="sidenav shadow-right sidenav-light">
        <div class="sidenav-menu">
            <div class="nav accordion" id="accordionSidenav">
                <!-- Sidenav Menu Heading (Account)-->
                <!-- * * Note: * * Visible only on and above the sm breakpoint-->
                <div class="sidenav-menu-heading d-sm-none">Account</div>
                <!-- Sidenav Link (Alerts)-->
                <!-- * * Note: * * Visible only on and above the sm breakpoint-->
                <a class="nav-link d-sm-none" href="#!">
                    <div class="nav-link-icon"><i data-feather="bell"></i></div>
                    Alerts
                    <span class="badge bg-warning-soft text-warning ms-auto">4 New!</span>
                </a>
                <!-- Sidenav Link (Messages)-->
                <!-- * * Note: * * Visible only on and above the sm breakpoint-->
                <a class="nav-link d-sm-none" href="#!">
                    <div class="nav-link-icon"><i data-feather="mail"></i></div>
                    Messages
                    <span class="badge bg-success-soft text-success ms-auto">2 New!</span>
                </a>
                <!-- Sidenav Menu Heading (Core)-->
                <div class="sidenav-menu-heading">Core</div>
                <!-- Sidenav Accordion (Dashboard)-->
                @if (in_array('dashboard', $permissions))
                    <a class="nav-link" href="{{ url('admin/dashboard') }}">
                        <div class="nav-link-icon"><i data-feather="activity"></i></div>
                        Dashboard
                    </a>
                @endif
                @if (in_array('service_requests', $permissions))
                    <a class="nav-link" href="{{ url('/admin/service-requests') }}">
                        <div class="nav-link-icon"><i data-feather="briefcase"></i></div>
                        Service Requests
                    </a>
                @endif
                @php
                    $user_management_modules = ['customers', 'service_providers'];
                @endphp
                @if (array_intersect($user_management_modules, $permissions))
                    <a class="nav-link collapsed" href="javascript:void(0);" data-bs-toggle="collapse"
                        data-bs-target="#collapseUserManagement" aria-expanded="false"
                        aria-controls="collapseUserManagement">
                        <div class="nav-link-icon"><i data-feather="users"></i></div>
                        User Management
                        <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                    </a>
                    <div class="collapse" id="collapseUserManagement" data-bs-parent="#accordionSidenav">
                        <nav class="sidenav-menu-nested nav">
                            @if (in_array('customers', $permissions))
                                <a class="nav-link" href="{{ url('/admin/customers') }}">Customers</a>
                            @endif
                            @if (in_array('service_providers', $permissions))
                                <a class="nav-link" href="{{ url('/admin/providers') }}">Service Providers</a>
                            @endif
                        </nav>
                    </div>
                @endif

                @php
                    $categories_modules = ['service_categories', 'service_subcategories'];
                @endphp
                @if (array_intersect($categories_modules, $permissions))
                    <a class="nav-link collapsed" href="javascript:void(0);" data-bs-toggle="collapse"
                        data-bs-target="#collapseCategories" aria-expanded="false" aria-controls="collapseCategories">
                        <div class="nav-link-icon"><i data-feather="table"></i></div>
                        Service Categories
                        <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                    </a>
                    <div class="collapse" id="collapseCategories" data-bs-parent="#accordionSidenav">
                        <nav class="sidenav-menu-nested nav">
                            @if (in_array('service_categories', $permissions))
                                <a class="nav-link" href="{{ url('/admin/service-categories') }}">Categories</a>
                            @endif
                            @if (in_array('service_subcategories', $permissions))
                                <a class="nav-link" href="{{ url('/admin/service-subcategories') }}">Subcategories</a>
                            @endif
                        </nav>
                    </div>
                @endif

                @if (in_array('operational_areas', $permissions))
                    <a class="nav-link" href="{{ url('admin/operational-areas') }}">
                        <div class="nav-link-icon"><i data-feather="map"></i></div>
                        Operational Areas
                    </a>
                @endif

                <a class="nav-link collapsed" href="javascript:void(0);" data-bs-toggle="collapse"
                    data-bs-target="#collapseSettings" aria-expanded="false" aria-controls="collapseSettings">
                    <div class="nav-link-icon"><i data-feather="settings"></i></div>
                    Settings
                    <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse" id="collapseSettings" data-bs-parent="#accordionSidenav">
                    <nav class="sidenav-menu-nested nav">
                        <a class="nav-link" href="/web-content">Web content</a>
                        <a class="nav-link" href="/payment-gateway">Payment gateway</a>
                        <a class="nav-link" href="/commissions">Commissions</a>
                    </nav>
                </div>
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
        <!-- Sidenav Footer-->
        <div class="sidenav-footer">
            <div class="sidenav-footer-content">
                <div class="sidenav-footer-subtitle">Logged in as:</div>
                <div class="sidenav-footer-title">Valerie Luna</div>
            </div>
        </div>
    </nav>
</div>
