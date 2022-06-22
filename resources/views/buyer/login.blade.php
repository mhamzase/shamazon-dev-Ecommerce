@extends('layouts.app')


@section('content')


<div class="container w-25">
  <h3>Buyer Login</h3>
  <span class="success-message text-success"></span>
  <span class="error-message text-danger"></span>
      <div class="mb-3">
        <label for="exampleInputEmail1" class="form-label">Email address</label>
        <input type="email" name="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
      </div>
      <div class="mb-3">
        <label for="exampleInputPassword1" class="form-label">Password</label>
        <input type="password" name="password" class="form-control" id="exampleInputPassword1">
      </div>
      <button id="loginBuyer" class="btn btn-primary">Login</button>
</div>

@endsection


@push('scripts')
    <script>
        $(document).ready(function() {
            $('#loginBuyer').click(function(e) {
                e.preventDefault();

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                let email = $('input[name="email"]').val();
                let password = $('input[name="password"]').val();

                $.ajax({
                    type: 'POST',
                    url: '/api/buyer/login',
                    data: {
                        email: email,
                        password: password
                    },
                    success: function(data) {
                        
                        if (data.status == 'success') {
                            $('.success-message').text(data.message);
                            $('.error-message').text('');

                            // set access token to lcoal storage
                            localStorage.setItem('access_token', data.access_token);

                            // redirect to home page
                            window.location.href = '/';

                        } else {
                            console.log(data.message)
                            $('.success-message').text('');
                            $('.error-message').text(data.message);
                        }
                    },
                    error: function(data) {
                        let errors = "";

                        if(email != '' && password != '' && data.responseJSON.message != ""){
                            errors += data.responseJSON.message + '<br>';
                        }

                        $.each(data.responseJSON.errors, function(key, value) {
                            errors += value[0] + '<br>';
                        });
                        console.log(errors);
                        $('.error-message').html(errors);
                    }
                });
            });
        });
    </script>
@endpush
