<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use Auth;

class ToolsFileImportRequest extends Request
{

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::check() && (Auth::user()->hasRole('Root')
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
            'project_id' => 'required',
            'language_id' => 'required',
            'user_id' => 'required',
            'data' => 'required',
        ];

    }

}
