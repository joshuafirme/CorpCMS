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

                {{-- @if (in_array('news', $permissions))
                    <a class="nav-link" href="{{ url('admin/news') }}">
                        <div class="nav-link-icon"><i class="fa-regular fa-newspaper"></i></div>
                        News
                    </a>
                @endif --}}
                @php
                    array_push($permissions, 'orders');
                    array_push($permissions, 'products');
                @endphp
                @if (in_array('orders', $permissions))
                    <a class="nav-link" href="{{ url('admin/orders') }}">
                        <div class="nav-link-icon"><i class="fa-solid fa-box"></i></div>
                        Orders
                    </a>
                @endif
                @if (in_array('products', $permissions))
                    <a class="nav-link" href="{{ url('admin/products') }}">
                        <div class="nav-link-icon"><i class="fa-solid fa-cart-shopping"></i></div>
                        Products
                    </a>
                @endif
                @if (in_array('sliders', $permissions))
                    <a class="nav-link" href="{{ url('admin/sliders') }}">
                        <div class="nav-link-icon"><i class="fa-solid fa-sliders"></i></div>
                        Sliders
                    </a>
                @endif
                @if (in_array('gallery', $permissions))
                    <a class="nav-link" href="{{ url('admin/gallery') }}">
                        <div class="nav-link-icon"><i class="fa-solid fa-images"></i></div>
                        Gallery
                    </a>
                @endif
                @if (in_array('messages', $permissions))
                    <a class="nav-link" href="{{ url('admin/messages') }}">
                        <div class="nav-link-icon"><i class="fa-regular fa-message"></i></div>
                        Messages
                    </a>
                @endif
                @php
                    // $pages_modules = ['about_us', 'contact_us', 'privacy_policy', 'terms_of_service'];
                @endphp
                {{-- @if (array_intersect($pages_modules, $permissions)) --}}
                <a class="nav-link collapsed" href="javascript:void(0);" data-bs-toggle="collapse"
                    data-bs-target="#collapsePages" aria-expanded="false" aria-controls="collapsePages">
                    <div class="nav-link-icon"><i class="fa-solid fa-pager"></i></div>
                    Pages
                    <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse" id="collapsePages" data-bs-parent="#accordionSidenav">
                    <nav class="sidenav-menu-nested nav">
                        <a class="nav-link" href="{{ url('admin/page-content') }}">Lists</a>
                    </nav>
                </div>
                {{-- @endif --}}

                <a class="nav-link collapsed" href="javascript:void(0);" data-bs-toggle="collapse"
                    data-bs-target="#collapseArticles" aria-expanded="false" aria-controls="collapseArticles">
                    <div class="nav-link-icon"><i class="fa-solid fa-book"></i></div>
                    Articles
                    <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse" id="collapseArticles" data-bs-parent="#accordionSidenav">
                    <nav class="sidenav-menu-nested nav">
                        <a class="nav-link" href="{{ url('admin/articles') }}">Lists</a>
                    </nav>
                </div>



                @if (in_array('general_settings', $permissions))
                    <a class="nav-link" href="{{ url('admin/settings') }}">
                        <div class="nav-link-icon"><i class="fa-solid fa-gear"></i></div>
                        General Settings
                    </a>
                @endif

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
