<?php
/**
 * Copyright (c) Unknown Worlds Entertainment, 2016.
 * Created by Lukas Nowaczek <lukas@unknownworlds.com> <@lnowaczek>
 * Visit http://unknownworlds.com/
 * This file is a part of proprietary software.
 */

namespace App\Http\Requests;

use App\Http\Requests\Request;
use App\Language;
use App\TranslatedString;
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

        return Auth::user()->hasRole($language->name . ' admin') || Auth::user()->hasRole('Root');
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
