<div class="form-group">
    {{ html()->label('Name:', 'name') }}
    {{ html()->text('name')->class('form-control') }}
</div>

<div class="form-group">
    {{ html()->label('Data input handler:', 'data_input_handler') }}
    {{ html()->select('data_input_handler', $inputHandlers)->class('form-control') }}
</div>

<div class="form-group">
    {{ html()->label('Data output handler:', 'data_output_handler') }}
    {{ html()->select('data_output_handler', $outputHandlers)->class('form-control') }}
</div>

<div class="form-group">
    {{ html()->label('API key:', 'api_key') }}
    {{ html()->text('api_key')->class('form-control') }}
</div>

<div class="form-group">
    {{ html()->label('Output template (optional):', 'output_template') }}
    {{ html()->textarea('output_template')->class('form-control')->attributes(['cols' => 50, 'rows' => 10])->html(e(old('output_template', $project->output_template ?? ''))) }}
</div>

<div class="form-group">
    {{ html()->submit($submitButton)->class('btn btn-primary form-control') }}
</div>