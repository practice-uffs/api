<?php

namespace App\Http\Controllers\API\V0;

use Illuminate\Http\Request;

class MuralController extends ApiProxyController
{
    public function setup()
    {
        $this->setAppId(config('practiceapi.app_id.mural'));
    }
}
