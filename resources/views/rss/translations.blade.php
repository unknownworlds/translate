<? xml version = "1.0" encoding = "UTF-8">
<rss version="2.0">
    <channel>
        <title>{{ $project->name }} {{ $language->name }} translations log</title>
        <link>{{ env('APP_URL') }}</link>
        <description>Latest translations for {{ $project->name }} in {{ $language->name }}</description>
        @foreach($log as $entry)
            <item>
                <title>
                    {{ $entry->text }}
                </title>
                <pubDate>
                    {{ $entry->created_at->toRssString() }}
                </pubDate>
                <link>
                {{ env('APP_URL') }}/#{{ md5($entry->text.$entry->created_at) }}
                </link>
                <description>
                    {{ $entry->text }}
                </description>
            </item>
        @endforeach
    </channel>
</rss>