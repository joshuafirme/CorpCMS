<script src="{{ asset('libs/js/jquery.min.js') }}"></script>

{{-- <script src="{{ asset('libs/js/custom/helper.js?v=' . rand(0, 9999)) }}"></script> --}}

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous">
</script>

<script src="{{ asset('js/custom/app.js') }}"></script>
<script src="{{ asset('js/custom/scripts.js') }}"></script>
<script src="{{ asset('js/custom/helper.js') }}"></script>

<script src="{{ asset('libs/js/sweetalert2.min.js') }}"></script>
<script src="{{ asset('libs/js/aos.js') }}"></script>
<script>
    AOS.init({
        disable: 'mobile',
        duration: 600,
        once: true,
    });
</script>

{{-- <script src="{{ asset('js/custom/onboarding.js'></script> --}}
