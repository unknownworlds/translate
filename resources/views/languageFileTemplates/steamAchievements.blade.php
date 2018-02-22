"lang"
{
	"Language"	"{{ $language['steam_api_name'] }}"
	"Tokens"
	{
@foreach($strings as $key => $value)
		"{{ $key }}" "{!! addslashes($value) !!}"
@endforeach
	}
}
