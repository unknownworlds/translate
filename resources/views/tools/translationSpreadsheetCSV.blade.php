"Key","ID","Original English text","Word count",@foreach($languages as $language)"{{ $language->name }}",@endforeach

@foreach($baseStrings as $baseString)
"{{ html_entity_decode($baseString->key) }}","{{ $baseString->id }}","{{ html_entity_decode(str_replace("\n", '\n',$baseString->text)) }}","{{ str_word_count($baseString->text) }}",@foreach($languages as $language)"{{@ html_entity_decode(str_replace("\n", '\n', $translatedStringsCSVFriendly[$language->id][$baseString->id] ?? null)) }}",@endforeach

@endforeach
