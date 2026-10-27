<div class="form-group">
    {{ html()->label('Name:', 'name') }}
    {{ html()->text('name')->class('form-control') }}
</div>

<div class="form-group">
    {{ html()->submit($submitButton)->class('btn btn-primary form-control') }}
</div>