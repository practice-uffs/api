<?php

namespace App\Http\Controllers\API\V0;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use App\Services\AuraNLP;

class AuraController extends Controller
{
    protected AuraNLP $auraNLP;

    public function __construct(AuraNLP $auraNLP)
    {
        $this->auraNLP = $auraNLP;
    }

    /**
     * @OA\Get(
     *      path="/abc",
     *      operationId="getProjectsList",
     *      tags={"Aura"},
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
    public function index(string $route, string $text) {
        $response = $this->auraNLP->fetch($route, $text);

        return response(
            $response,
            Response::HTTP_OK   
        );
    }
}
