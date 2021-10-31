<?php

namespace App\Http\Controllers\API\V0;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class EnvironmentController extends Controller
{
    /**
     * Lista as informaçõe de ambiente de um usuário.
     * 
     * @OA\Get(
     *      path="/env",
     *      operationId="getProjectsList",
     *      tags={"Environment"},
     *      summary="Get list of projects",
     *      description="Returns list of projects",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     *     )
     */
    public function index(Request $request){

        return response(
            null,
            Response::HTTP_NO_CONTENT
        );
    }
}
