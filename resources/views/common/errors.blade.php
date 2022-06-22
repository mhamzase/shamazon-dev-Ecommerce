@if($errors->any())
<div>
    <span class="text-danger" style="color:red;">Please fix the following errors before submitting the form again:</span>
    <ul>
        {!! implode('', $errors->all('<li class="text-danger" style="color:red;">:message</li>')) !!}
    </ul>
</div>
@endif