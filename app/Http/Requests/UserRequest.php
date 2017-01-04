<?php
/**
 * Copyright (c) Unknown Worlds Entertainment, 2016.
 * Created by Lukas Nowaczek <lukas@unknownworlds.com> <@lnowaczek>
 * Visit http://unknownworlds.com/
 * This file is a part of proprietary software.
 */

namespace App\Http\Requests;

use App\Http\Requests\Request;
use Auth;

class UserRequest extends Request
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
                'name' => 'required|unique:languages',
                'email' => 'required|email|unique:languages',
            ];
        } elseif ($requestMethod == 'PATCH') {
            return [
                'name' => 'required',
                'email' => 'required|email',
            ];
        } elseif ($requestMethod == 'DELETE') {
            return [
            ];
        }

    }

}
