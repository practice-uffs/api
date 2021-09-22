<?php

namespace App\Http\Controllers\API\V0;

use App\Http\Controllers\Controller;
use App\Services\PracticeApiClientService;
use Illuminate\Http\Request;

class ApiProxyController extends Controller
{
    protected int $appId;
    protected PracticeApiClientService $api;

    public function setup() {
        // TODO: classes filhas devam implementar esse método, e.x. $this->setAppId(1);
    }

    public function __construct(PracticeApiClientService $api)
    {
        $this->api = $api;
        $this->setup();
    }

    public function setAppId(int $appId) {
        $this->appId = $appId;
    }

    public function getAppId() {
        return $this->appId;
    }

    public function api() {
        return $this->api;
    }

    public function proxy(Request $request) {
        $appId = $this->getAppId();
        $verb = $request->method();
        
        $baseUrl = $request->segment(1) . '/' . $request->segment(2);
        $indentedUrl = str_replace($baseUrl, '', $request->path());

        return $this->api()->fetch($appId, $verb, $indentedUrl, $request->all());
    }
}
