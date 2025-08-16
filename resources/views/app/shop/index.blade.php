@extends('app._partials.app')

@section('content')
    <style>
        .preview-thumb {
            transition: transform 0.3s ease;
        }

        .preview-thumb:hover {
            transform: scale(1.1);
        }

        #quantity {
            font-size: 1.1rem;
            height: 45px;
            padding: 0;
            box-shadow: none;
        }
        #increase-btn, #decrease-btn {
            font-size: 1.25rem;
            padding: 0.375rem 0.75rem;
        }
    </style>
  <section class="bg-light py-5">
    <div class="container py-5">
        <h2 class="text-center mb-5 text-primary">{{ $page->title }}</h2>
        <p>{!! $page->content !!}</p>

        <div class="row g-4">
            <!-- Product Card -->
            @foreach ($products as $product)
                <div class="col-md-6 col-lg-4">
                    <div class="card border-0 shadow-sm h-100">
                        <img src="{{ $product->product_img ?? 'https://placehold.co/400x500?text=Coming+Soon' }}" class="card-img-top object-fit-cover" alt="Unseen Wins Book" style="height: 350px; object-fit: cover;">
                        <div class="card-body d-flex flex-column">
                            <h3 class="card-title text-primary fw-bold">{{ $product->product_name }}</h3>
                            <p class="card-text text-muted small mb-2">By Coach Xavier Nunag</p>
                            <p class="card-text text-dark small mb-3">
                                {{ !empty($product->product_description) ?  $product->product_description : 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Id ipsam ea vitae neque aperiam repellendus soluta, accusantium eaque odit tempora doloremque possimus autem impedit nostrum dolor expedita assumenda deserunt nisi!' }}
                            </p>
                            <h4 class="text-secondary fw-semibold mb-3">Price: &#8369; {{ $product->product_price }}</h4>
                            <div class="mt-auto d-flex gap-2">
                                <a href="{{ $product->product_amazon ?? '/#'}}" target="_blank" class="btn btn-sm btn-primary w-100">Buy on Amazon</a>
                                <a href="/shop/{{ $product->product_slug }}" class="btn btn-sm btn-outline-secondary w-100">Local Inquiry</a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{ asset("/js/custom/shop.js") }}"></script>
@endsection
