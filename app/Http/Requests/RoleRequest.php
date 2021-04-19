<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use Auth;

class RoleRequest extends Request
{

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::check() && Auth::user()->hasRole('Root');
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
                'name' => 'required|unique:roles',
            ];
        } elseif ($requestMethod == 'PATCH') {
            return [
                'name' => 'required',
            ];
        } elseif ($requestMethod == 'DELETE') {
            return [];
        }


    }

}
