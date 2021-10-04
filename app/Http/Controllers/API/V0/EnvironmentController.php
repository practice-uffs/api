<?php

namespace App\Http\Controllers\API\V0;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class EnvironmentController extends Controller
{
    /**
     * Lista as informaçõe de ambiente de um usuário
     */
    public function index(Request $request){

        return response(
            null,
            Response::HTTP_NO_CONTENT
        );
    }
}
