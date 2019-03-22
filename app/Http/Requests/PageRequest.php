<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use Auth;

class PageRequest extends Request
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
        $requestMethod = \Request::getMethod();

        if ($requestMethod == 'POST') {
            return [
                'title' => 'required|unique:pages',
                'content' => 'required',
            ];
        } elseif ($requestMethod == 'PATCH') {
            return [
                'title' => 'required',
                'content' => 'required',
            ];
        } elseif ($requestMethod == 'DELETE') {
            return [];
        }


    }

}
