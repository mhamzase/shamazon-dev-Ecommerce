@extends('vender.layouts.app')



@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                {{-- @can('create-products') --}}
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Add product</h3>
                    </div>
                    <div class="card-body">
                        <form id="add-product" method="post" action="/api/products" enctype="multipart/form-data">
                            @csrf
                            <div class="errors text-danger"></div>
                            <div class="success-message text-success"></div>
                            <div class="form-group mt-3">
                                <label for="name">Name</label>
                                <input type="text" class="form-control" id="name" name="name" placeholder="Name"
                                    required>
                            </div>
                            <div class="form-group mt-3">
                                <label for="unit_price">Unit price</label>
                                <input type="text" class="form-control" id="unit_price" name="unit_price"
                                    placeholder="Unit price" required>
                            </div>
                            <div class="form-group mt-3">
                                <label for="currency">Currency</label>
                                <input type="number" class="form-control" id="currency" name="currency" placeholder="USD"
                                    value="USD" disabled>
                            </div>
                            <div class="form-group mt-3">
                                <label for="stock">Stock</label>
                                <input type="number" class="form-control" id="stock" name="stock" placeholder="Stock"
                                    required>
                            </div>
                            <div class="form-group mt-3">
                                <label for="image">Upload an Image</label>
                                <input class="form-control" type="file" id="image" name="image" required>
                            </div>
                            <button type="submit" class="btn btn-primary mt-3">
                                Add product
                            </button>
                        </form>
                    </div>
                </div>
                {{-- @endcan --}}
            </div>
        </div>

        {{-- @can('view-products') --}}
        <table class="table">
            <thead>
                <tr>
                    <th scope="col" style="visibility:hidden">ID</th>
                    <th scope="col">#</th>
                    <th scope="col">Image</th>
                    <th scope="col">Name</th>
                    <th scope="col">Unit Price</th>
                    <th scope="col">Currency</th>
                    <th scope="col">Stock</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
        {{-- @endcan --}}

        <div class="modal fade" id="update-product-modal" aria-hidden="true" aria-labelledby="updateProductLabel" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="updateProductLabel">Update product</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form id="update-product" method="post" action="/api/products" enctype="multipart/form-data">
                        @method('PUT')
                        @csrf
                        <div class="modal-body">
                            <input type="hidden" name="id" id="id" />
                            Name: <input type="text" name="name" id="name" class="form-control" required>
                            Unit price: <input type="text" name="unit_price" id="unit_price" class="form-control"
                                required>
                            Stock: <input type="text" name="stock" id="stock" class="form-control" required>
                            <div class="form-group mt-3">
                                <label for="update_image">Update an Image (Optional)</label>
                                <input class="form-control" type="file" id="update_image" name="update_image" />
                                <img src="" alt="" id="update_image_show" class="mt-3" width="100px">
                            </div>
                        </div>
                        <div class="modal-footer">

                            <button type="submit" class="btn btn-primary">Update</button>
                            <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endsection

    @push('scripts')
        <script>
            // hide login signup buttons when user logged in
            $(document).ready(function() {
                if (localStorage.getItem('access_token')) {
                    $('#logout-user').show();
                } else {
                    $('#logout-user').hide();
                }

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                // get all products
                fetchAllProducts();

                // save product
                $('#add-product').submit(function(e) {
                    e.preventDefault();
                    var formData = new FormData(this);
                    $.ajax({
                        type: 'POST',
                        url: '/api/products',
                        headers: {
                            'Authorization': 'Bearer ' + localStorage.getItem('access_token')
                        },
                        data: formData,
                        cache: false,
                        contentType: false,
                        processData: false,
                        success: function(data) {
                            if (data.status == 'success') {
                                $('tbody').append(
                                    '<tr>' +
                                    '<th scope="row" style="visibility:hidden">' + data.data
                                    .id + '</th>' +
                                    '<td>' + data.count + '</td>' +
                                    '<td> <img src="' + data.data.image +
                                    '" alt="" width="100px"/> </td>' +
                                    '<td>' + data.data.name + '</td>' +
                                    '<td>' + data.data.unit_price + '</td>' +
                                    '<td>' + data.data.currency + '</td>' +
                                    '<td>' + data.data.stock + '</td>' +
                                    '<td>' +
                                    '<button onclick="editProduct(' + data.data.id +
                                    ')" class="btn btn-sm btn-outline-primary">Edit</button>' +
                                    '<button onclick="deleteProduct(' + data.data.id +
                                    ')" class="btn btn-sm btn-outline-danger">Delete</button>' +
                                    '</tr>'
                                );

                                $('#name').val('');
                                $('#unit_price').val('');
                                $('#stock').val('');
                                $('#image').val('');

                                $('.errors').text('');
                                $('.success-message').text(data.message);
                            } else {
                                console.log(data.message);
                            }
                        },
                        error: function(data) {
                            let errors = '';

                            $.each(data.responseJSON.errors, function(key, value) {
                                errors += value[0] + '<br>';
                            });

                            $('.success-message').text('');
                            $('.errors').html(errors);
                        }

                    });
                });

                // update product
                $('#update-product').submit(function(e) {
                    e.preventDefault();
                    var formData = new FormData(this);
                    $.ajax({
                        type: 'POST',
                        url: '/api/products/' + $('#update-product').find('#id').val(),
                        headers: {
                            'Authorization': 'Bearer ' + localStorage.getItem('access_token')
                        },
                        data: formData,
                        cache: false,
                        contentType: false,
                        processData: false,
                        success: function(data) {
                            if (data.status == 'success') {
                                $('tbody').find('tr').each(function() {
                                    if ($(this).find('th').text() == data.data.id) {
                                        $(this).find('td').eq(0).text(data.count);
                                        $(this).find('td').eq(1).find('img').attr('src', data.data.image);
                                        $(this).find('td').eq(2).text(data.data.name);
                                        $(this).find('td').eq(3).text(data.data.unit_price);
                                        $(this).find('td').eq(4).text(data.data.currency);
                                        $(this).find('td').eq(5).text(data.data.stock);
                                        $(this).find('td').eq(6).html(
                                            '<button onclick="editProduct(' + data.data
                                            .id +
                                            ')" class="btn btn-sm btn-outline-primary">Edit</button>' +
                                            '<button onclick="deleteProduct(' + data
                                            .data.id +
                                            ')" class="btn btn-sm btn-outline-danger">Delete</button>'
                                        );
                                    }
                                });
                                $('#update-product-modal').modal('hide');
                            } else {
                                console.log(data.message);
                            }
                        }
                    });
                });
            });


            // fetch all products
            function fetchAllProducts() {
                $.ajax({
                    type: 'GET',
                    url: '/api/products',
                    headers: {
                        'Authorization': 'Bearer ' + localStorage.getItem('access_token')
                    },
                    success: function(data) {
                        if (data.status == 'success') {
                            $.each(data.data, function(index, value) {
                                $('tbody').append(
                                    '<tr>' +
                                    '<th scope="row" style="visibility:hidden">' + value.id + '</th>' +
                                    '<td>' + (index + 1) + '</td>' +
                                    '<td> <img src="' + value.image + '" alt="" width="100px"/> </td>' +
                                    '<td>' + value.name + '</td>' +
                                    '<td>' + value.unit_price + '</td>' +
                                    '<td>' + value.currency + '</td>' +
                                    '<td>' + value.stock + '</td>' +
                                    '<td>' +
                                    '<button onclick="editProduct(' + value.id +
                                    ')" class="btn btn-sm btn-outline-primary" >Edit</button>' +
                                    '<button onclick="deleteProduct(' + value.id +
                                    ')" class="btn btn-sm btn-outline-danger">Delete</button>' +
                                    '</tr>'
                                );
                            });
                        }
                    },
                    error: function(data) {
                        console.log(data.message)
                    }
                });
            }

            // delete product
            function deleteProduct(id) {
                if (confirm('Are you sure you want to delete this product?')) {
                    $.ajax({
                        type: 'DELETE',
                        url: '/api/products/' + id,
                        headers: {
                            'Authorization': 'Bearer ' + localStorage.getItem('access_token')
                        },
                        success: function(data) {
                            if (data.status == 'success') {
                                console.log(data.data);
                                $('tbody').find('tr').each(function() {
                                    if ($(this).find('th').text() == id) {
                                        $(this).remove();
                                    }
                                });
                            } else {
                                console.log(data.message);
                            }
                        }
                    });
                } else {
                    return false;
                }
            }

            // edit product
            function editProduct(id) {
                $.ajax({
                    type: 'GET',
                    url: '/api/products/' + id + '/edit',
                    headers: {
                        'Authorization': 'Bearer ' + localStorage.getItem('access_token')
                    },
                    success: function(data) {
                        if (data.status == 'success') {
                            $('#update-product').find('#id').val(data.data.id);
                            $('#update-product').find('#name').val(data.data.name);
                            $('#update-product').find('#unit_price').val(data.data.unit_price);
                            $('#update-product').find('#stock').val(data.data.stock);
                            $('#update-product').find('#update_image_show').attr('src', data.data.image);
                            $('#update-product-modal').modal('show');
                        } else {
                            console.log(data.message);
                        }
                    }
                });
            }
        </script>
    @endpush
