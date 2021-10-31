<?php

namespace App\Http\Controllers\API\V0;

use App\Auth\CredentialManager;
use App\Http\Controllers\Controller;
use App\Models\App;
use App\Models\Checkin;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class CheckinController extends Controller
{
    protected CredentialManager $credentialManager;
    
    public function __construct(CredentialManager $credentialManager)
    {
        $this->credentialManager = $credentialManager;
    }

    protected function processUrlParams($url) {
        $urlParts = parse_url($url);

        if ($urlParts === false) {
            return false;
        }

        $result = [
            'jwt_signature' => '',
            'is_valid' => true,
        ];

        $queryParams = [];
        parse_str(@$urlParts['query'], $queryParams);

        if (isset($queryParams['jwt_signature'])) {
            $jwt = $queryParams['jwt_signature']; 
            
            $result['jwt_signature'] = $jwt;
            $result['is_valid'] = $this->credentialManager->isValidJwtToken($jwt, config('app.key'));
        }

        return $result;
    }

    protected function urlHasParams($url) {
        $urlParts = parse_url($url);
        $queryParams = [];
        parse_str(@$urlParts['query'], $queryParams);

        return count($queryParams) > 0;
    }    
    
    /**
     * Processa e guarda o resultado de uma solicitação de checkin.
     * 
     * @OA\Post(
     *      path="/checkin",
     *      operationId="getProjectsList",
     *      tags={"Check-in"},
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
     * )
     */
    public function store(Request $request) {
        $input = $request->validate([
            'url' => 'required|url',
        ]);

        $result = $this->processUrlParams($input['url']);

        if ($result === false) {
            return response()->json(['message' => 'Invalid URL'], Response::HTTP_BAD_REQUEST);
        }

        $checkin = Checkin::create([
            'user_id'       => auth()->id(),
            'url'           => $input['url'],
            'jwt_signature' => $result['jwt_signature'],
            'is_valid'      => $result['is_valid'],
        ]);

        return response(
            $checkin,
            Response::HTTP_CREATED
        );
    }

    /**
     * Cria uma novo marcador de checkin, que possui uma URL, um qrcode, etc.
     * 
     * @OA\Post(
     *      path="/checkin/marker",
     *      operationId="getProjectsList",
     *      tags={"Check-in"},
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
    public function marker(Request $request) {
        $input = $request->validate([
            'url' => 'required|url',
            'app_id' => 'exists:' . App::class . ',id',
            'ttl_seconds' => 'integer',
        ]);

        $ttlSeconds = $input['ttl_seconds'] > 0 ? $input['ttl_seconds'] : Carbon::now()->addYear(2)->timestamp;
        $suffix = '?';

        if ($this->urlHasParams($input['url'])) {
            $suffix = '&';
        }

        $url = $input['url'] . $suffix . http_build_query([
            'app_id' => $input['app_id'],
            'ttl_seconds' => $input['ttl_seconds'],
        ]);

        $url .= '&jwt_signature=' . $this->credentialManager->createPassportFromLocalApp($input, $ttlSeconds);

        $qrCode = QrCode::generate('dddddd')->toHtml();

        return response([
            'url' => $url,
            'qr_code' => [
                'svg_base64' => 'data:image/svg+xml;base64,' . base64_encode($qrCode),
            ]
        ], Response::HTTP_CREATED);
    }
}
