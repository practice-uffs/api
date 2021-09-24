<?php

namespace App\Http\Controllers\API\V0;

use App\Http\Controllers\Controller;
use App\Models\App;
use App\Services\PracticeApiClientService;
use Illuminate\Http\Request;

use F9Web\ApiResponseHelpers;
use Illuminate\Http\JsonResponse;

class ApiProxyController extends Controller
{
    use ApiResponseHelpers;

    protected PracticeApiClientService $api;

    public function setup() {
        // TODO: classes filhas devam implementar esse método
    }

    public function __construct(PracticeApiClientService $api)
    {
        $this->api = $api;
        $this->setup();
    }

    public function api() {
        return $this->api;
    }

    public function proxy(App $app, Request $request): JsonResponse {
        $verb = $request->method();
        $baseUrl = $request->segment(1) . '/' . $request->segment(2);
        $intendedUrl = str_replace($baseUrl, '', $request->path());

        $result = $this->api()->fetch($app, $verb, $intendedUrl, $request->all());
        return $this->respondWithSuccess($result);
    }
}
