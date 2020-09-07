"Language","Translated word count","Remaining word count"
@foreach($languages as $language)
"{{ $language->name }}","{{ $translatedWordCounts[$language->id] ?? 0 }}","{{ $totalEnglishWordCount-($translatedWordCounts[$language->id] ?? 0) }}"
@endforeach
