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

    public function index(string $route, string $text) {
        $response = $this->auraNLP->fetch($route, $text);

        return response(
            $response,
            Response::HTTP_OK
        );
    }
}
