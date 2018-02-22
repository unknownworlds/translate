<div class="form-group">
    {!! Form::label('name', 'Name:') !!}
    {!! Form::text('name', null, ['class' => 'form-control']) !!}
</div>

<div class="form-group">
    {!! Form::label('file_handler', 'File handler:') !!}
    {!! Form::select('file_handler', ['SimpleJsonObject' => 'Simple JSON object', 'SteamAchievements' => 'Steam achievements', 'Manual' => 'Manual'], null, ['class' => 'form-control']) !!}
</div>

<div class="form-group">
    {!! Form::label('api_key', 'API key:') !!}
    {!! Form::text('api_key', null, ['class' => 'form-control']) !!}
</div>

<div class="form-group">
    {!! Form::submit($submitButton, ['class' => 'btn btn-primary form-control']) !!}
</div>