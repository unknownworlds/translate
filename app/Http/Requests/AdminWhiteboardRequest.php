<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use App\Models\Language;
use Auth;

class AdminWhiteboardRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $language = Language::findOrFail($this->get('language_id'));

        return Auth::check()
            && (Auth::user()->hasRole($language->name . ' admin')
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
            //
        ];
    }
}
