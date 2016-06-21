@extends('app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">

                <div class="panel panel-default">
                    <div class="panel-heading">Home</div>

                    <div class="panel-body">

                        <div class="alert alert-info">
                            Please report any bugs and feature requests to lukas@unknownworlds.com or via twitter
                            to @lnowaczek. Remember to take a look at the Guidelines, Instructions, and FAQ.
                        </div>

                        <ul class="nav nav-tabs" role="tablist">
                            <li role="presentation" class="active">
                                <a href="#translation-log" aria-controls="translation-log" role="tab" data-toggle="tab">
                                    Translation log</a>
                            </li>
                            <li role="presentation">
                                <a href="#strings-updates" aria-controls="strings-updates" role="tab" data-toggle="tab">
                                    Base strings updates</a>
                            </li>
                            <li role="presentation">
                                <a href="#translation-progress" aria-controls="translation-progress" role="tab"
                                   data-toggle="tab">
                                    Translation progress</a>
                            </li>
                        </ul>

                        <div class="tab-content">
                            <div role="tabpanel" class="tab-pane active" id="translation-log">
                                <ul class="top15">
                                    @foreach($log as $entry)
                                        <li>
                                            {{ $entry->created_at->diffForHumans() }},
                                            {{ $entry->project->name }}: {{ $entry->text }}
                                            <img src="img/country-flags/{{@ $languages[$entry->language_id] }}.png" alt="{{@ $languages[$entry->language_id] }}" />
                                        </li>
                                    @endforeach
                                </ul>
                            </div>

                            <div role="tabpanel" class="tab-pane" id="strings-updates">
                                <ul class="top15">
                                    @foreach($baseStringsLog as $entry)
                                        <li>
                                            {{ $entry->created_at->diffForHumans() }},
                                            {{ $entry->project->name }}: {{ $entry->text }}
                                        </li>
                                    @endforeach
                                </ul>
                            </div>

                            <div role="tabpanel" class="tab-pane" id="translation-progress">
                                <ul class="list-unstyled top15">
                                    @foreach($translationProgress as $entry)
                                        <li>
                                            {{ $entry->project->name }}, {{ $entry->language->name }}:
                                            {{ round($entry->count / $baseStringCounts[$entry->project_id] * 100, 3) }}%
                                            <div class="progress">
                                                <div class="progress-bar" role="progressbar" aria-valuenow="60"
                                                     aria-valuemin="0"
                                                     aria-valuemax="100"
                                                     style="width: {{ round($entry->count / $baseStringCounts[$entry->project_id] * 100, 3) }}%;">
                                                </div>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
