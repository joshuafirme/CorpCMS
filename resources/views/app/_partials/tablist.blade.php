<div class="nav flex-column nav-pills me-3" id="v-pills-tab" role="tablist" aria-orientation="vertical">
    <a class="nav-link {{ $route == 'users.account' ? 'active' : '' }}" href="{{ route('users.account') }}">Account</a>
    @if ($user->user_type == 'customer')
        <a class="nav-link {{ $route == 'users.serviceRequests' ? 'active' : '' }}"
            href="{{ route('users.serviceRequests') }}">Service Requests</a>
    @elseif ($user->user_type == 'service_provider')
        <a class="nav-link {{ $route == 'users.wallet' ? 'active' : '' }}"
            href="{{ route('users.wallet') }}">Wallet</a>
        <a class="nav-link {{ $route == 'tracker' ? 'active' : '' }}" href="{{ url('real-time-tracker') }}">Real-time
            tracker</a>
        <a class="nav-link {{ $route == 'users.serviceRequests' ? 'active' : '' }}"
            href="{{ url('service-requests?status=accepted') }}">Service Requests</a>
        <a class="nav-link {{ $route == 'users.services' ? 'active' : '' }}"
            href="{{ route('users.services') }}">Service Categories</a>
        <a class="nav-link {{ $route == 'users.serviceArea' ? 'active' : '' }}"
            href="{{ route('users.serviceArea') }}">Service Areas</a>
        <a class="nav-link {{ $route == 'users.availability' ? 'active' : '' }}"
            href="{{ route('users.availability') }}">Availability</a>
    @else
        <a class="nav-link {{ $route == 'users.serviceRequests' ? 'active' : '' }}"
            href="{{ route('users.serviceRequests') }}">Service Requests</a>
    @endif
    <a class="nav-link {{ $route == 'users.notifications' ? 'active' : '' }}"
        href="{{ route('users.notifications') }}">Notifications</a>
</div>
