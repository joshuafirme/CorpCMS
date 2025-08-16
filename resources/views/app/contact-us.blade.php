@extends('app._partials.app')

@section('content')
    <section class="bg-white py-5">
    <div class="container px-1 px-md-3 px-lg-5">
        @if (isset($data->show_contact_form) && $data->show_contact_form == 'on')
            @include('app._partials.alerts')

            <div class="row g-4 align-items-start">
                <!-- Newsletter Column -->
                <div class="col-lg-6">
                    <section class="newsletter p-4 shadow-sm rounded bg-light h-100">
                        <form action="/subscribe" method="post" class="newsletter-form">
                            <h2 class="title mb-2">Join Our Newsletter</h2>
                            <p class="subtitle text-muted mb-4">Stay updated with the latest blog posts & tips.</p>
                            <div class="form-group d-flex">
                                <input
                                    type="email"
                                    name="email"
                                    class="form-control me-2"
                                    placeholder="you@example.com"
                                    required
                                />
                                <button type="submit" class="btn btn-primary">Subscribe</button>
                            </div>
                        </form>
                    </section>
                </div>

                <!-- Contact Form Column -->
                <div class="col-lg-6">
                    <div class="card shadow-sm h-100">
                        <div class="card-body">
                            <h2 class="text-center text-primary mb-4">Contact Us</h2>
                            <form action="" method="post" class="row" id="contactForm">
                                @csrf
                                <div class="col-md-6 mb-3">
                                    <label class="form-label small">Name</label>
                                    <input type="text" class="form-control" name="name">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label small">Email</label>
                                    <input type="email" class="form-control" name="email">
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label class="form-label small">Message</label>
                                    <textarea class="form-control" name="message" rows="5"></textarea>
                                </div>
                                <div class="col-12 text-end">
                                    <button class="btn btn-primary" type="submit" id="btn-submit">Send message</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        @if (isset($data->content) && isset($data->show_content) && $data->show_content == 'on')
            <div class="mt-5"> {!! $data->content !!}</div>
        @endif
    </div>
</section>

@endsection


@section('scripts')
    <script src="https://www.google.com/recaptcha/api.js?render={{ env('RECAPTCHA_SITE_KEY') }}"></script>
    <script>
        $(function() {
            $(document).on('click', '#btn-submit', function(e) {

                e.preventDefault();
                let btn = $(this);
                btn.html('<i class="fas fa-circle-notch fa-spin"></i>')
                btn.prop('disabled', true)

                grecaptcha.ready(function() {
                    grecaptcha.execute("{{ env('RECAPTCHA_SITE_KEY') }}", {
                        action: 'submit'
                    }).then(function(token) {

                        $('#contactForm').prepend(
                            '<input type="hidden" class="token" name="token" value="' +
                            token + '">');

                        setTimeout(() => {
                            $("#contactForm").submit()
                            btn.html('Sign up')
                            btn.prop('disabled', false)
                        }, 500);
                    });
                });

                return false;
            });
        })
    </script>
@endsection
