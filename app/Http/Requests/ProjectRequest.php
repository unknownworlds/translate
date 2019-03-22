<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use Auth;

class ProjectRequest extends Request
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

        if ($requestMethod == 'GET') {
            return [];
        } elseif ($requestMethod == 'POST') {
            return [
                'name' => 'required|unique:projects',
                'data_input_handler' => 'required',
                'data_output_handler' => 'required',
                'api_key' => 'required|min:16|max:32',
            ];
        } elseif ($requestMethod == 'PATCH') {
            return [
                'name' => 'required',
                'data_input_handler' => 'required',
                'data_output_handler' => 'required',
                'api_key' => 'required|min:16|max:32',
            ];
        } elseif ($requestMethod == 'DELETE') {
            return [
            ];
        }


    }

}
