<?php

namespace Modules\Core\Http\Controllers\Api;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

class BaseApiController extends Controller
{
    /**
     * Return a success json to check api docs.
     *
     * @return JsonResponse
     *
     * @OA\Get (
     *     path="/api/check-version",
     *     summary="Get a success mesaage!",
     *     @OA\Response(
     *         response=200,
     *         description="OK"
     *     )
     * )
     */
    public function checkVersion()
    {
        return response()->json(['code'=>200,'message' => "success"]);
    }

    /**
     * Method to check api health from monitoring system.
     *
     * @return JsonResponse
     */
    public function health()
    {
        return response()->json(['success'=>true,'status' => "UP"]);
    }
}
