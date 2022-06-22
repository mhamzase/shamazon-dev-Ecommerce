<nav class="navbar navbar-expand-lg bg-light">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">Shamazon</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="{{ route('home') }}">Home</a>
                </li>
            </ul>
            <div class="d-flex">
                <a href="{{ route('user.register') }}" id="sign-up" type="button"
                    class="btn btn-success mx-2">Sign Up</a>
                <a href="{{ route('user.login-as') }}" id="login" type="button" class="btn btn-dark  mx-2">Login</a>

                <div class="dropdown" id="cart">
                    <button class="btn btn-secondary dropdown-toggle" type="button" id="cartItems"
                        data-bs-toggle="dropdown" aria-expanded="false">
                    </button>
                    <ul id="place-order-button" class="dropdown-menu d-none" aria-labelledby="dropdownMenuButton1">
                        <li>
                            <button type="submit" class="dropdown-item" id="place-order">Place Order</button>
                        </li>
                    </ul>
                </div>
                <button id="logout-user" type="button" class="btn btn-danger  mx-2">Logout</button>
            </div>
        </div>
    </div>
</nav>


@push('scripts')
    <script>
        // hide login signup buttons when user logged in
        $(document).ready(function() {
            if (localStorage.getItem('access_token')) {
                $('#sign-up').hide();
                $('#login').hide();
                $('#logout-user').show();
                $('#cart').show();
            } else {
                $('#sign-up').show();
                $('#login').show();
                $('#logout-user').hide();
                $('#cart').hide();
            }

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // fetch no of items
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
                        $('#cartItems').html('<i class="bi bi-minecart"></i>' + '  ' + data.data);

                        if (data.data) {
                            $('#place-order-button').removeClass('d-none');
                        } else {
                            $('#place-order-button').addClass('d-none');
                        }

                    }
                },
            });

            // logout user
            $('#logout-user').click(function(e) {
                $.ajax({
                    type: 'POST',
                    url: '/api/logout',
                    headers: {
                        'Authorization': 'Bearer ' + localStorage.getItem('access_token')
                    },
                    success: function(data) {
                        console.log(data);
                        if (data.status == 'success') {
                            localStorage.removeItem('access_token');
                            window.location.href = '/';
                        }
                    },
                    error: function(data) {
                        console.log(data.message)
                    }
                });

            });

            // place Order
            $('#place-order').click(function(e) {
                $.ajax({
                    type: 'POST',
                    url: '/api/placeorder',
                    headers: {
                        'Authorization': 'Bearer ' + localStorage.getItem('access_token')
                    },
                    success: function(data) {
                        if (data.status == 'success') {
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
                                            '  ' + data.data);

                                        // window.location.href = '/';
                                    }
                                },
                            });
                        }
                    },
                    error: function(data) {
                        console.log(data.message)
                    }
                });

            });
        });
    </script>
@endpush
