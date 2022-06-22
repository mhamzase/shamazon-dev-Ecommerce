@extends('layouts.app')

@section('content')
    <div class="container w-25 d-flex justify-content-between align-items-center flex-column mt-5">
        <h2>Login as</h2>
        <br/>
       <div>
        <a href="{{ route('buyer.login') }}" class="btn btn-outline-dark btn-lg">Buyer</a> |
        <a href="{{ route('vendor.login') }}" class="btn btn-outline-dark btn-lg">Vendor</a>
       </div>
    </div>
@endsection
