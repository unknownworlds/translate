<div class="form-group">
    {{ html()->label('Name:', 'name') }}
    {{ html()->text('name')->class('form-control') }}
</div>

<div class="form-group">
    {{ html()->label('Email:', 'email') }}
    {{ html()->text('email')->class('form-control') }}
</div>

{{--<div class="form-group">--}}
{{--{{ html()->label('Password:', 'password') }}--}}
{{--{{ html()->password('password')->class('form-control') }}--}}
{{--</div>--}}

<h2>Roles</h2>

@foreach($roles as $role)
    <div class="checkbox">
        <label>
            {{ html()->checkbox('userRoles[]', $user->hasRole($role->name), $role->id) }}
            {{ $role->name }}
        </label>
    </div>
@endforeach

<div class="form-group">
    {{ html()->submit($submitButton)->class('btn btn-primary form-control') }}
</div>