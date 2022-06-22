@extends('layouts.app')

@section('content')
    <div class="row m-3">
        @foreach ($products as $product)
            <div class="card mx-2 my-3" style="width: 18rem;">
                <div class="card-body">
                    <h5 class="card-title"> {{ ucfirst($product->name) }} </h5>
                    <span class="fw-bold">Unit price:</span> <span>{{ $product->unit_price }}</span><br />
                    <span class="fw-bold">Currency:</span> <span>{{ $product->currency }}</span><br />
                    <span class="fw-bold">In stock:</span> <span>{{ $product->stock }}</span><br />

                    <input type="hidden" name="product_id" id="product_id" value="{{ $product->id }}" />
                    <input type="number" class="form-control" name="quantity" id="quantity{{ $product->id }}"
                        placeholder="Quantity" min="1" max="{{ $product->stock }}" required />
                    <span class="text-danger error"></span>

                    <button class="btn btn-primary mt-4"
                        onclick="addToCart({{ $product->id }}, document.getElementById('quantity{{ $product->id }}').value)">Add
                        to
                        cart</button>
                </div>
            </div>
        @endforeach
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
        });

        function addToCart(product_id, quantity) {
            if (quantity == '') {
                alert('Please enter quantity');
                return;
            } else {
                $.ajax({
                    type: 'POST',
                    url: '/api/add-to-cart',
                    data: {
                        quantity: parseInt(quantity),
                        product_id: product_id,
                    },
                    headers: {
                        'Authorization': 'Bearer ' + localStorage.getItem('access_token')
                    },
                    success: function(data) {
                        if (data.status == 'success') {
                            $('input[type=number]').val('');

                            $.ajax({
                                type: 'GET',
                                url: '/api/no_of_cart_items',
                                data: {},
                                headers: {
                                    'Authorization': 'Bearer ' + localStorage.getItem(
                                        'access_token')
                                },
                                success: function(data) {
                                    if (data.status == 'success') {
                                        $('#cartItems').html(
                                            '<i class="bi bi-minecart"></i>' +
                                            data.data);

                                        if (data.data) {
                                            $('#place-order-button').removeClass('d-none');
                                        } else {
                                            $('#place-order-button').addClass('d-none');
                                        }
                                    }
                                },
                            });
                        }
                    },
                    error: function(data) {
                        if (data.status == 401) {
                            // alert('Please login to add to cart');
                            window.location.href = '/buyer/login';
                        }
                    }
                });
            }
        }
    </script>
@endpush
