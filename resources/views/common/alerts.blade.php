@if (session('message'))
<div class="alert {{ session('alert-class') }}" role="alert">
    {!! session('message') !!}
</div>
@endif
