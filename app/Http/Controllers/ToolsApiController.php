<?php

namespace App\Http\Controllers;

use App\Models\BaseString;
use Symfony\Component\HttpFoundation\Request;

class ToolsApiController extends BaseApiController
{

    public function __construct()
    {
        $this->middleware(['auth', 'hasRole:Root']);
    }

    public function markQualityControlledString(Request $request)
    {
        $string = BaseString::findOrFail($request->get('base_string_id'));

        $string->update([
            'quality_controlled' => !$string->quality_controlled
        ]);

        return $this->respond($string);
    }

}
