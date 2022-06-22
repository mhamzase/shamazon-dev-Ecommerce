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
                    <a href="{{ route('vendor.dashboard') }}" class="btn btn-sm btn-outline-secondary" id="addproduct" type="button">Dashboard</a>
                    <a href="{{ route('vendor.orders.index') }}" class="btn btn-sm btn-outline-primary" id="addproduct" type="button">View Orders</a>
                </li>
            </ul>
            <div class="d-flex">
                <button id="logout-user" type="button" class="btn btn-danger  mx-2">Logout</button>
            </div>
        </div>
    </div>
</nav>


@push('scripts')
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('#logout-user').click(function(e) {
            e.preventDefault();

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
    </script>
@endpush
