@extends('vender.layouts.app')



@section('content')
    {{-- show orders --}}
    <div class="container mt-4">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Orders</h3>
                    </div>
                    <div class="card-body text-center">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>SR#</th>
                                    <th>Order ID</th>
                                    <th>Buyer Email</th>
                                    <th>Order Status</th>
                                    <th>Order Date</th>
                                    <th>View Order Details</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($orders as $order)
                                    <tr>
                                        <td class="fw-bold">{{ $loop->iteration }}</td>
                                        <td>{{ $order[0]->order_id }}</td>
                                        <td>{{ $order[0]->order->user->email }}</td>
                                        <td>{{ $order[0]->order->status ? \App\Enums\Status::getTypeName($order[0]-- > order->status) : \App\Enums\Status::getTypeName($order[0]->order->status) }}
                                        </td>
                                        <td>{{ $order[0]->created_at->format('D d/F/Y') }}</td>
                                        <td>
                                            <button class="btn btn-primary" {{-- data-bs-toggle="modal" href="#view-order-details" --}}
                                                onclick="viewOrderDetails({{ $order[0]->order_id }})">View</button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade modal-lg" id="view-order-details" aria-hidden="true" aria-labelledby="viewOrderDetailsLabel"
        tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewOrderDetailsLabel">Order details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <h3>Products</h3>
                    <table class="table table-bordered text-center">
                        <thead>
                            <tr>
                                <th>SR#</th>
                                <th>Name</th>
                                <th>Unit price</th>
                                <th>Quantity</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                    <div class="text-right">
                        <span class="display-4">Total Amount: </span> <span id="total-amount"
                            class="display-4 fw-bold"></span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection


@push('scripts')
    <script>
        function viewOrderDetails(order_id) {
            $.ajax({
                type: 'GET',
                url: '/api/order/' + order_id + '/products',
                data: {
                    order_id: order_id
                },
                headers: {
                    'Authorization': 'Bearer ' + localStorage.getItem('access_token')
                },
                success: function(data) {
                    if (data.status == 'success') {
                        var order_items = data.data[0].order_items;
                        var total_amount = 0;

                        var table = $('#view-order-details .modal-body table tbody');
                        table.empty();

                        for (var i = 0; i < order_items.length; i++) {
                            var order_item = order_items[i];

                            total_amount += order_item.product.unit_price * order_item.quantity;

                            var tr = $('<tr>');
                            tr.append($('<td>').text(i + 1));
                            tr.append($('<td>').text(order_item.product.name));
                            tr.append($('<td>').text('$' + order_item.product.unit_price));
                            tr.append('<td>' + order_item.quantity + '</td>');
                            tr.append($('<td>').append($(
                                `<a target="_blank" href='/products/${order_item.product.id}'  class="btn btn-primary">`
                            ).text('View product')));
                            table.append(tr);
                        }

                        $('#total-amount').text('');
                        $('#view-order-details #total-amount').append('$' + total_amount);

                        $('#view-order-details').modal('show');
                    }
                }
            });
        }
    </script>
@endpush
