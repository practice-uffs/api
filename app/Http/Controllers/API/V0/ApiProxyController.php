<?php

namespace App\Http\Controllers\API\V0;

use App\Http\Controllers\Controller;
use App\Models\App;
use App\Services\PracticeApiClientService;
use F9Web\ApiResponseHelpers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

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

        if (config('app.debug')) {
            Log::debug('ApiProxyController::proxy', [
                'app_name' => $app->name,
                'app_api_url' => $app->api_url,
                'verb' => $verb,
                'intendedUrl' => $intendedUrl
            ]);
        }

        $response = $this->api()->fetch($app, $verb, $intendedUrl, $request->all());

        if (!$response->getBody()) {
            return $this->respondError('No response body');
        }

        $content = json_decode($response->getBody(), true);

        if (isset($content['error'])) {
            return $this->respondError($content['error']);
        }

        return $this->respondWithSuccess($content);
    }
}
