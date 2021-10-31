<?php

namespace App\Http\Controllers\API\V0;

use Illuminate\Http\Request;

class MuralController extends ApiProxyController
{
    public function setup()
    {
        $this->setAppId(config('practiceapi.app_id.mural'));
    }

    /**
     * @OA\Get(
     *      path="/abcd",
     *      operationId="getProjectsList",
     *      tags={"Mural"},
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
    private function abc() {
        return 'abc';
    }
}
