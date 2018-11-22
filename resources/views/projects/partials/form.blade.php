<div class="form-group">
    {!! Form::label('name', 'Name:') !!}
    {!! Form::text('name', null, ['class' => 'form-control']) !!}
</div>

<div class="form-group">
    {!! Form::label('data_input_handler', 'Data input handler:') !!}
    {!! Form::select('data_input_handler', ['SimpleJsonObject' => 'Simple JSON object', 'SteamAchievements' => 'Steam achievements', 'Manual' => 'Manual'], null, ['class' => 'form-control']) !!}
</div>

<div class="form-group">
    {!! Form::label('data_output_handler', 'Data output handler:') !!}
    {!! Form::select('data_output_handler', ['SimpleJsonObject' => 'Simple JSON object', 'SteamAchievements' => 'Steam achievements', 'PredefinedTemplate' => 'Predefined template'], null, ['class' => 'form-control']) !!}
</div>

<div class="form-group">
    {!! Form::label('api_key', 'API key:') !!}
    {!! Form::text('api_key', null, ['class' => 'form-control']) !!}
</div>

<div class="form-group">
    {!! Form::label('output_template', 'Output template (optional):') !!}
    {!! Form::textarea('output_template', null, ['class' => 'form-control']) !!}
</div>

<div class="form-group">
    {!! Form::submit($submitButton, ['class' => 'btn btn-primary form-control']) !!}
</div>