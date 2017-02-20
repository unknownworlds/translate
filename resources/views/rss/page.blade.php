@extends('app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">

                <div class="panel panel-default">
                    <div class="panel-heading">RSS feeds</div>

                    <div class="panel-body">

                        <h3>About</h3>
                        <p>
                            You can use any of the RSS feeds below to stay up to date with changes to the translated
                            strings. RSS feeds can be used by both specialized RSS readers or some email clients.
                        </p>

                        @foreach($projects as $project)
                            <h3>{{ $project->name }} RSS feeds</h3>
                            <ul>
                                <li>
                                    <a href="/rss/base-strings/{{ $project->id }}">Base string updates</a>
                                </li>
                            </ul>
                            <ul>
                                @foreach($languages as $language)
                                    <li>
                                        <a href="/rss/translations/{{ $project->id }}/{{ $language->id }}">
                                            {{ $language->name }} translations feed
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        @endforeach

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
