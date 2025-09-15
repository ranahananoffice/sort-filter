@extends('layouts.master')
@section('title', 'Product Listing')
@push('style')
@endpush

@section('content')
    <div class="content-wrapper">
        <!-- Main content -->
        <section class="content">
            <div class="container mt-4">
                <div class="row mb-3">
                    <div class="col-md-3">
                        <label for="price-sort">Sort by Price</label>
                        <select id="price-sort" class="form-control">
                            <option value="">-- Sort By --</option>
                            <option value="lowToHigh" {{ request('sort') == 'lowToHigh' ? 'selected' : '' }}>
                                Price: Low to High
                            </option>
                            <option value="highToLow" {{ request('sort') == 'highToLow' ? 'selected' : '' }}>
                                Price: High to Low
                            </option>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label for="custom-sort">Filter by Status</label>
                        <select id="custom-sort" class="form-control">
                            <option value="">-- Filter --</option>
                            <option value="topSell" {{ request('sort') == 'topSell' ? 'selected' : '' }}>Top Seller</option>
                            <option value="discounted" {{ request('sort') == 'discounted' ? 'selected' : '' }}>Discounted
                            </option>
                            <option value="orignalPrice" {{ request('sort') == 'orignalPrice' ? 'selected' : '' }}>Orignal
                                Price</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label for="custom-sort">Search here</label>
                        <input type="hidden" name="routeName" id="routeName" value="">


                        <input type="text" name="searchProduct" id="searchProduct" class="form-control"
                            placeholder="Search">


                    </div>
                </div>

                <div class="row" id="product-list">
                    @include('product.partials.products', [
                        'allProductsPaginated' => $allProductsPaginated,
                    ])
                </div>

                <div class="row">
                    <div class="col-12 d-flex justify-content-end" id="pagination-wrapper">
                        {{ $allProductsPaginated->appends(request()->query())->links('pagination.customPagination') }}
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@push('scripts')
<script>
    function fetchProducts(page = 1) {
        const priceSort = $('#price-sort').val();   // lowToHigh | highToLow | ''
        const customSort = $('#custom-sort').val(); // topSell | discounted | orignalPrice | ''

        // build sort[] array with only non-empty values
        const sorts = [];
        if (priceSort) sorts.push(priceSort);
        if (customSort) sorts.push(customSort);

        const search = $('#searchProduct').val();

        $.ajax({
            url: "{{ route('products.index') }}",
            type: 'GET',
            data: {
                page: page,
                sort: sorts,    // jQuery will send as sort[]=val1&sort[]=val2
                search: search,
            },
            success: function(response) {
                $('#product-list').html(response.products);
                $('#pagination-wrapper').html(response.pagination);
            },
            error: function(xhr, status, err) {
                console.error(xhr.responseText || err);
                alert('Something went wrong. Check console/network tab.');
            }
        });
    }

    // trigger fetch on changes
    $(document).on('change', '#price-sort, #custom-sort', function() {
        fetchProducts(1);
    });

    // search debounce
    let searchTimeout;
    $(document).on('keyup', '#searchProduct', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => fetchProducts(1), 400);
    });

    // pagination links - will use current selects because fetchProducts reads them
    $(document).on('click', '.pagination a', function(e) {
        e.preventDefault();
        const url = new URL($(this).attr('href'), window.location.origin);
        const page = url.searchParams.get("page") || 1;
        fetchProducts(page);
    });
</script>
@endpush
