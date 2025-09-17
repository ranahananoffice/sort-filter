<div class="modal-header">
    <h5 class="modal-title">{{ $product->title }}</h5>
    <button type="button" class="close" data-dismiss="modal">&times;</button>
</div>
<div class="modal-body">
    <img src="{{ asset($product->image) }}" class="img-fluid mb-3" alt="Product Image">
    <p>{{ $product->description }}</p>
    <p>Price: {{ $product->originalPrice }}</p>
    @if ($product->discountPrice > 0)
        <p><strong>Discounted Price:</strong> {{ $product->discountPrice }}</p>
    @endif
    <p><strong>Average Rating:</strong> {{ number_format($product->reviews_avg_rating,1) }}</p>
</div>
