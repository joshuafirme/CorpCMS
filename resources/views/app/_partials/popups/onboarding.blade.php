<div class="position-fixed bottom-0 p-3" style="z-index: 11">
    <div id="providerOnboardingToast" class="toast hide" role="alert" aria-live="assertive" aria-atomic="true"
        data-bs-autohide="false">
        <div class="toast-header">
            {{-- <img src="..." class="rounded me-2" alt="..."> --}}
            <strong class="me-auto">Complete Your Profile</strong>
            <small></small>
            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body">
            <p class="text-center">To get started, please complete your profile.</p>
            <div class="timeline">
                <div class="timeline-item">
                    <div class="timeline-item-marker">
                        <div class="timeline-item-marker-indicator {{ $email_verified ? 'bg-primary-soft text-success' : '' }}"><i
                                data-feather="check"></i></div>
                    </div>
                    <div class="timeline-item-content"><a href="{{ route('users.account') }}">Account setup</a>
                        <div class="small">Upload a profile picture, entering personal details (name, phone, address),
                            and valid ID for verification.</div>

                    </div>
                </div>
                <div class="timeline-item">
                    <div class="timeline-item-marker">
                        <div class="timeline-item-marker-indicator {{ $services_count > 0 ? 'bg-primary-soft text-success' : '' }}"><i data-feather="check"></i></div>
                    </div>
                    <div class="timeline-item-content">
                        <a href="{{ route('users.serviceArea') }}">Service categories</a>

                        <div class="small">Choose your service categories</div>
                    </div>
                </div>
                <div class="timeline-item">
                    <div class="timeline-item-marker">
                        <div class="timeline-item-marker-indicator {{ $service_area_count > 0 ? 'bg-primary-soft text-success' : '' }}"><i data-feather="check"></i></div>
                    </div>
                    <div class="timeline-item-content">
                        <a href="{{ route('users.serviceArea') }}">Service Area</a>

                        <div class="small">Setting up geofences for barangays</div>
                    </div>
                </div>
                <div class="timeline-item">
                    <div class="timeline-item-marker">
                        <div class="timeline-item-marker-indicator {{ $availabilities_count > 0 ? 'bg-primary-soft text-success' : '' }}"><i data-feather="check"></i></div>
                    </div>
                    <div class="timeline-item-content"><a href="{{ route('users.availability') }}">Availability</a>
                        <div class="small">Specify your working hours and the days of the week availability.</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="position-fixed bottom-0 p-3" style="z-index: 11">
    <div id="customerOnboardingToast" class="toast hide" role="alert" aria-live="assertive" aria-atomic="true"
        data-bs-autohide="false">
        <div class="toast-header">
            {{-- <img src="..." class="rounded me-2" alt="..."> --}}
            <strong class="me-auto">Complete Your Profile</strong>
            <small></small>
            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body">
            <p class="text-center">To get started, please complete your profile.</p>
            <div class="timeline">
                <div class="timeline-item">
                    <div class="timeline-item-marker">
                        <div
                            class="timeline-item-marker-indicator {{ $email_verified ? 'bg-primary-soft text-success' : '' }}">
                            <i data-feather="check"></i>
                        </div>
                    </div>
                    @if ($email_verified)
                        <div class="timeline-item-content">{{ $user->email }}</div>
                    @else
                        <div class="timeline-item-content"><a href="{{ route('users.account') }}">Verify your email</a>
                            <div class="small">Confirm your email to become eligible for booking a service.</div>
                        </div>
                    @endif
                </div>
                <div class="timeline-item">
                    <div class="timeline-item-marker">
                        <div
                            class="timeline-item-marker-indicator {{ $formatted_address ? 'bg-primary-soft text-success' : '' }}">
                            <i data-feather="check"></i>
                        </div>
                    </div>
                    <div class="timeline-item-content">
                        @if ($formatted_address)
                            <div class="small">{{ $user->formatted_address }}</div>
                        @else
                            <a href="{{ route('users.account') }}">Address</a>

                            <div class="small">Please add your address to ensure the service provider can easily find
                                your
                                location.</div>
                        @endif
                    </div>
                </div>
                <div class="timeline-item">
                    <div class="timeline-item-marker">
                        <div
                            class="timeline-item-marker-indicator {{ $phone_verified ? 'bg-primary-soft text-success' : '' }}">
                            <i data-feather="check"></i>
                        </div>
                    </div>
                    @if ($phone_verified)
                        <div class="timeline-item-content">{{ $user->phone }}</div>
                    @else
                        <div class="timeline-item-content"><a href="{{ route('users.availability') }}">Verify your phone
                                number</a>
                            <div class="small">Confirm your phone number to enhance account security</div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
