<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>{{ $project->name }} :: Quality Control</title>

    <link href="{{ asset('/css/pdf.css') }}" rel="stylesheet">
</head>

<body>

<h1 class="header">
    {{ $project->name }}
    <small>{{ $language->name }} translation quality control</small>
</h1>

<hr>

<div class="introduction">
    <p>
        Hello!<br>
        Subnautica is translated with a huge effort from it's community. Each language is translated by many people, and
        it's not always easy to keep everything in order. This document is going to help us find out if
        {{ $language->name }} translation is in a good state. Please go through it and keep an eye out on grammar
        errors,
        inconsistencies, spelling mistakes and any other errors.
        Thank you for your time!<br>
    </p>
    <p>
        Sections below follow a simple schema: each starts with a translation key used for identification, and some
        metadata. Below you'll find the original English text, followed by the translation.
    </p>
</div>

<hr>

<div class="translations">
    @foreach($baseStrings as $index => $baseString)
        @if(array_key_exists($baseString->id, $translatedStrings))
            <div class="section">
                <div class="section-header">
                    <h3>{{ $index+1 }}. {{ $baseString->key }}</h3>
                    <small>
                        {{ $translatedStrings[$baseString->id]->user->name }},
                        ID#{{ $translatedStrings[$baseString->id]->id }},
                        {{ $translatedStrings[$baseString->id]->created_at }},
                        approved by {{ $translatedStrings[$baseString->id]->accepted_by ?? 'N/A' }},
                        +{{ $translatedStrings[$baseString->id]->up_votes }} /
                        -{{ $translatedStrings[$baseString->id]->down_votes }} votes
                    </small>
                </div>

                <div class="string-text">
                    <div class="originalString">{{ $baseString->text }}</div>
                    <div class="translatedString">{{ $translatedStrings[$baseString->id]->text }}</div>
                    <div class="clear"></div>
                </div>
                {{--@else--}}
                {{--<div class="section-header">--}}
                {{--<h3>{{ $index+1 }}. {{ $baseString->key }}</h3>--}}
                {{--</div>--}}
                {{--<p class="originalString">{{ $baseString->text }}</p>--}}
                {{--<p class="translatedString">Not translated!</p>--}}
            </div>
        @endif
    @endforeach
</div>

</body>
</html>
