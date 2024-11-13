@extends('app._partials.app')

@section('content')
    <section class="bg-white py-5">
        <div class="container px-1 px-md-3 px-lg-5">
            @if (isset($data->show_contact_form) && $data->show_contact_form == 'on')
                @include('app._partials.alerts')
                <div class="card">

                    <h2 class="text-center text-primary">Contact Us</h2>
                    <div class="card-body">
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
                            <div class="col-12">
                                <button class="btn btn-primary" type="submit" id="btn-submit">Send message</button>
                            </div>

                        </form>
                    </div>
                </div>
            @endif
            @if (isset($data->content) && isset($data->show_content) && $data->show_content == 'on')
                <div class="mt-5"> {!! $data->content !!}</div>
            @endif
        </div>
    </section>

    <section class="bg-white py-5">
        <div class="svg-border-waves text-primary">
            <!-- Wave SVG Border-->
            <svg class="wave" style="pointer-events: none" fill="currentColor" preserveAspectRatio="none"
                xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 1920 75">
                <defs>
                    <style>
                        .a {
                            fill: none;
                        }

                        .b {
                            clip-path: url(#a);
                        }

                        .d {
                            opacity: 0.5;
                            isolation: isolate;
                        }
                    </style>
                </defs>
                <clipPath id="a">
                    <rect class="a" width="1920" height="75"></rect>
                </clipPath>
                <g class="b">
                    <path class="c"
                        d="M1963,327H-105V65A2647.49,2647.49,0,0,1,431,19c217.7,3.5,239.6,30.8,470,36,297.3,6.7,367.5-36.2,642-28a2511.41,2511.41,0,0,1,420,48">
                    </path>
                </g>
                <g class="b">
                    <path class="d"
                        d="M-127,404H1963V44c-140.1-28-343.3-46.7-566,22-75.5,23.3-118.5,45.9-162,64-48.6,20.2-404.7,128-784,0C355.2,97.7,341.6,78.3,235,50,86.6,10.6-41.8,6.9-127,10">
                    </path>
                </g>
                <g class="b">
                    <path class="d"
                        d="M1979,462-155,446V106C251.8,20.2,576.6,15.9,805,30c167.4,10.3,322.3,32.9,680,56,207,13.4,378,20.3,494,24">
                    </path>
                </g>
                <g class="b">
                    <path class="d"
                        d="M1998,484H-243V100c445.8,26.8,794.2-4.1,1035-39,141-20.4,231.1-40.1,378-45,349.6-11.6,636.7,73.8,828,150">
                    </path>
                </g>
            </svg>
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
