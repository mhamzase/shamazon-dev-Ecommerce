@extends('layouts.app')

@section('content')
    <div class="container w-25">
        <h2>Register User</h2>

        <span class="success-message text-success"></span>
        <span class="error-message text-danger"></span>
        <div class="mb-3">
            <select class="form-control" id="role_id" name="role_id">
                <option value="">Select Role</option>
                @foreach($roles as $role)
                    <option value="{{ $role->id }}">{{ $role->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="exampleInputEmail1" class="form-label">Email address</label>
            <input type="email" name="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
        </div>
        <div class="mb-3">
            <label for="exampleInputPassword1" class="form-label">Password</label>
            <input type="password" name="password" class="form-control" id="exampleInputPassword1">
        </div>
        <button id="registerBuyer" class="btn btn-primary">Register</button>
    </div>
@endsection


@push('scripts')
    <script>
        $(document).ready(function() {
            $('#registerBuyer').click(function(e) {
                e.preventDefault();

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    type: 'POST',
                    url: '/api/user/register',
                    data: {
                        email: $('input[name="email"]').val(),
                        password: $('input[name="password"]').val(),
                        role_id: $('select[name="role_id"]').val()
                    },
                    success: function(data) {
                        if (data.status == 'success') {
                          console.log(data);
                            $('.success-message').text(data.message);
                            $('.error-message').text('');

                            $('input[name="email"]').val('');
                            $('input[name="password"]').val('');
                            $('select[name="role_id"]').val('');
                        } else {
                            $('.success-message').text('');
                            $('.error-message').text(data.message);
                        }
                    },
                    error: function(data) {
                        let errors = "";
                        $.each(data.responseJSON.errors, function(key, value) {
                            errors += value[0] + '<br>';
                        });

                        $('.error-message').html(errors);
                    }
                });
            });
        });
    </script>
@endpush
