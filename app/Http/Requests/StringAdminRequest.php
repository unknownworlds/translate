<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use App\Models\Language;
use App\Models\TranslatedString;
use Auth;

class StringAdminRequest extends Request
{

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $string = TranslatedString::findOrFail($this->get('string_id'));
        $language = Language::findOrFail($string->language_id);

        return Auth::check() &&
            (Auth::user()->hasRole($language->name.' admin')
                || Auth::user()->hasRole('Root')
                || Auth::user()->hasRole('Admin'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [

        ];
    }

}
