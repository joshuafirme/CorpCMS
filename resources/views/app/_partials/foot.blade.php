@php
    $settings = settings();
@endphp

<div id="layoutDefault_footer">
    <footer class="footer pt-10 pb-5 mt-auto bg-dark footer-light bg-primary">
        <div class="container px-5">
            <div class="row gx-5">
                <div class="col-lg-3">
                    <div class="footer-brand">{{ $settings->app_name }}</div>
                    <div class="icon-list-social mb-5 gap-2">
                        @if (isset($settings->facebook) && $settings->facebook)
                            <a class="icon-list-social-link" href="{{ $settings->facebook }}"><i
                                    class="fab fa-facebook"></i></a>
                        @endif
                        @if (isset($settings->instagram) && $settings->instagram)
                            <a class="icon-list-social-link" href="{{ $settings->instagram }}"><i
                                    class="fab fa-instagram"></i></a>
                        @endif
                        @if (isset($settings->linkedin) && $settings->linkedin)
                            <a class="icon-list-social-link" href="{{ $settings->linkedin }}"><i
                                    class="fab fa-linkedin"></i></a>
                        @endif
                        @if (isset($settings->twitter) && $settings->twitter)
                            <a class="icon-list-social-link" href="{{ $settings->twitter }}"><i
                                    class="fab fa-twitter"></i></a>
                        @endif
                        @if (isset($settings->tiktok) && $settings->tiktok)
                            <a class="icon-list-social-link" href="{{ $settings->tiktok }}"><i
                                    class="fab fa-tiktok"></i></a>
                        @endif
                    </div>
                </div>
                <div class="col-lg-9">
                    <div class="row gx-5">
                        <div class="col-lg-3 col-md-6 mb-5 mb-lg-0">
                            <div class="text-uppercase-expanded text-xs mb-4">Product</div>
                            <ul class="list-unstyled mb-0">
                                <li class="mb-2"><a href="#!">Landing</a></li>
                                <li class="mb-2"><a href="#!">Pages</a></li>
                                <li class="mb-2"><a href="#!">Sections</a></li>
                                <li class="mb-2"><a href="#!">Documentation</a></li>
                                <li><a href="#!">Changelog</a></li>
                            </ul>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="text-uppercase-expanded text-xs mb-4">Legal</div>
                            <ul class="list-unstyled mb-0">
                                <li class="mb-2"><a href="/privacy-policy">Privacy Policy</a></li>
                                <li class="mb-2"><a href="/terms-and-conditions">Terms and Conditions</a></li>
                                <li><a href="#!">License</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <hr class="my-5" />
            <div class="row gx-5 align-items-center">
                <div class="col-md-6 small">Copyright © {{ $settings->app_name }} {{ date('Y') }}</div>
            </div>
        </div>
    </footer>
</div>
