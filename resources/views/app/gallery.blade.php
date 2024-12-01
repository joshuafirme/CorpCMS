@extends('app._partials.app')

@section('content')
    <section class="bg-white py-5">
        <div class="container py-5">
            <h2 class="text-center mb-5 text-primary">Gallery</h2>
            <div class="row  g-4" data-aos="fade-up" data-aos-delay="100">
                @foreach ($gallery as $item)
                    <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                        <div class="gallery-item" data-bs-toggle="modal" data-bs-target="#imageModal"
                            data-bs-image="{{ $item->image }}">
                            <img src="{{ asset($item->image) }}" alt="{{ $item->title }}">
                            <div class="caption text-center">{{ $item->title }}</div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body p-0">
                    <img id="modalImage" src="" alt="Full Image" class="w-100">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        const imageModal = document.getElementById('imageModal');
        const modalImage = document.getElementById('modalImage');

        imageModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const imageUrl = button.getAttribute('data-bs-image');
            modalImage.src = imageUrl;
        });
    </script>
@endsection
