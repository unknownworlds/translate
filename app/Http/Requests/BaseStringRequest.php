<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use Auth;

class BaseStringRequest extends Request
{

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::user()->hasRole('Root');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'project_id' => 'required',
            'key' => 'required',
            'text' => 'required'
        ];
    }

}
