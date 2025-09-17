@foreach ($allProductsPaginated as $product)
  <div class="col-md-4 mb-4">
    <div class="card h-100 product-card" data-id="{{ $product->id }}" style="cursor: pointer;">
        <img src="{{ asset($product->image) }}" class="card-img-top" alt="Product Image">
        <div class="card-body">
            @if ($product->isTopSeller > 0)
                <span class="badge badge-success">Top Seller</span>
            @endif

            <h5 class="card-title">{{ $product->title ?? 'Card title' }}</h5>

            {{-- Tags Section --}}
            <div class="mb-2">
                <span class="badge"
                    style="background-color: white; color: #333; border: 1px solid #ccc; border-radius: 20px; padding: 5px 10px; margin: 2px; display: inline-block;">
                    {{ $product->tag }}
                </span>
                <span class="badge"
                    style="background-color: white; color: #333; border: 1px solid #ccc; border-radius: 20px; padding: 5px 10px; margin: 2px; display: inline-block;">
                    {{ number_format($product->reviews_avg_rating,1) }}
                </span>
            </div>

            <p class="card-text">{{ $product->description ?? 'No description' }}</p>

            <span>Price: {{ $product->originalPrice }}</span><br>

            @if ($product->discountPrice > 0)
                <span class="badge badge-info">Discounted Price: {{ $product->discountPrice }}</span>
            @endif
        </div>
    </div>
</div>
@endforeach
