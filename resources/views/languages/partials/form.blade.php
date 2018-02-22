<div class="form-group">
    {!! Form::label('name', 'Name:') !!}
    {!! Form::text('name', null, ['class' => 'form-control']) !!}
</div>

<div class="form-group">
    {!! Form::label('steam_api_name', 'Steam API name (used for achievement translations):') !!}
    {!! Form::text('steam_api_name', null, ['class' => 'form-control']) !!}
</div>

<div class="form-group">
    {!! Form::label('locale', 'Locale:') !!}
    {!! Form::text('locale', null, ['class' => 'form-control']) !!}
</div>

<div class="checkbox">
    <label>
        {!! Form::checkbox('is_rtl') !!}
        Right to left language
    </label>
</div>

<div class="checkbox">
    <label>
        {!! Form::checkbox('skip_in_output') !!}
        Skip when generating output files
    </label>
</div>

<div class="form-group">
    {!! Form::submit($submitButton, ['class' => 'btn btn-primary form-control']) !!}
</div>