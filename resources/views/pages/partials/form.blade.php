<div class="form-group">
    {{ html()->label('Title:', 'title') }}
    {{ html()->text('title')->class('form-control') }}
</div>

<div class="form-group">
    {{ html()->label('Content (raw HTML):', 'content') }}
    {{ html()->textarea('content')->class('form-control')->attributes(['cols' => 50, 'rows' => 10])->text(old('content', $page->content ?? '')) }}
</div>

<div class="form-group">
    {{ html()->submit($submitButton)->class('btn btn-primary form-control') }}
</div>