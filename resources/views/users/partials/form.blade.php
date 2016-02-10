<div class="form-group">
    {!! Form::label('name', 'Name:') !!}
    {!! Form::text('name', null, ['class' => 'form-control']) !!}
</div>

<div class="form-group">
    {!! Form::label('email', 'Email:') !!}
    {!! Form::text('email', null, ['class' => 'form-control']) !!}
</div>

{{--<div class="form-group">--}}
    {{--{!! Form::label('password', 'Password:') !!}--}}
    {{--{!! Form::password('password', ['class' => 'form-control']) !!}--}}
{{--</div>--}}

<h2>Roles</h2>

@foreach($roles as $role)
<div class="checkbox">
    <label>
        {!! Form::checkbox('userRoles[]', $role->id, $user->hasRole($role->name)) !!}
        {{ $role->name }}
    </label>
</div>
@endforeach

<div class="form-group">
    {!! Form::submit($submitButton, ['class' => 'btn btn-primary form-control']) !!}
</div>