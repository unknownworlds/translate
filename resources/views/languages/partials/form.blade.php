<div class="form-group">
    {{ html()->label('Name:', 'name') }}
    {{ html()->text('name')->class('form-control') }}
</div>

<div class="form-group">
    {{ html()->label('Steam API name (used for achievement translations):', 'steam_api_name') }}
    {{ html()->text('steam_api_name')->class('form-control') }}
</div>

<div class="form-group">
    {{ html()->label('Locale:', 'locale') }}
    {{ html()->text('locale')->class('form-control') }}
</div>

<div class="checkbox">
    <label>
        {{ html()->checkbox('is_rtl') }}
        Right to left language
    </label>
</div>

<div class="checkbox">
    <label>
        {{ html()->checkbox('skip_in_output') }}
        Skip when generating output files
    </label>
</div>

<div class="form-group">
    {{ html()->submit($submitButton)->class('btn btn-primary form-control') }}
</div>