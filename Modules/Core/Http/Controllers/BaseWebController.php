<?php

namespace Modules\Core\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;

class BaseWebController extends Controller
{
    /**
     * Method to check website health from monitoring system.
     *
     * @return JsonResponse
     */
    public function health()
    {
        return response()->json(['success'=>true,'status' => "UP"]);
    }
}
