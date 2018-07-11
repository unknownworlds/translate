@extends('app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <ul class="alert alert-info list-unstyled">
                    <li>
                        Hello, and welcome to Unknown Worlds Translations! We use this community-powered application to
                        translate our projects to as many languages as possible, with your help! To get started - create
                        an account using the link in top right corner or log in if you already have one.
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Login</div>
                    <div class="panel-body">
                        @if (count($errors) > 0)
                            <div class="alert alert-danger">
                                <strong>Whoops!</strong> There were some problems with your input.<br><br>
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form class="form-horizontal" role="form" method="POST" action="{{ url('/login') }}">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">

                            <div class="form-group">
                                <label class="col-md-4 control-label">E-Mail Address</label>

                                <div class="col-md-6">
                                    <input type="email" class="form-control" name="email" value="{{ old('email') }}">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-4 control-label">Password</label>

                                <div class="col-md-6">
                                    <input type="password" class="form-control" name="password">
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-6 col-md-offset-4">
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="remember"> Remember Me
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-6 col-md-offset-4">
                                    <button type="submit" class="btn btn-primary">Login</button>

                                    <a class="btn btn-link" href="{{ url('/password/reset') }}">
                                        Forgot Your Password?
                                    </a>
                                </div>
                            </div>

                            <hr>

                            @if(env('BUS_LOGIN_ENABLED'))
                                <div class="form-group">
                                    <div class="col-md-6 col-md-offset-4">
                                        <a class="btn btn-primary" href="{{ url('/social-login/bus') }}">
                                            Login with Unknown Worlds Account
                                        </a>
                                        <p>
                                            This is the preferred way of using the site. Other options will be
                                            deprecated soon. You can upgrade to Unknown Worlds Account at any time.
                                        </p>
                                    </div>
                                </div>
                            @endif

                            <div class="form-group">
                                <div class="col-md-6 col-md-offset-4">
                                    @if(env('GITHUB_LOGIN_ENABLED'))
                                        <a class="btn btn-primary" href="{{ url('/social-login/github') }}">
                                            Login with GitHub
                                        </a>
                                    @endif
                                    @if(env('FACEBOOK_LOGIN_ENABLED'))
                                        <a class="btn btn-primary" href="{{ url('/social-login/facebook') }}">
                                            Login with Facebook
                                        </a>
                                    @endif
                                    @if(env('GOOGLE_LOGIN_ENABLED'))
                                        <a class="btn btn-primary" href="{{ url('/social-login/google') }}">
                                            Login with Google
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
