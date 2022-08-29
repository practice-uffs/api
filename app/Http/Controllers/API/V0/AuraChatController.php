<?php

namespace App\Http\Controllers\API\V0;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use App\Models\AuraChat;


class AuraChatController extends Controller
{

    public function index() {
        $userId = auth()->id();

        $auraChat = $this->getAuraChat($userId);

        return response()->json(
            $auraChat, 
            Response::HTTP_OK
        ); 
    }

    public function getAuraChat($userId) {
        $auraChat = AuraChat::where('user_id', $userId)->first();
        if ($auraChat != null) {
            return $auraChat;
        }

        $auraChat = AuraChat::create([
            'user_id' => $userId,
            'aura_consent' => 0,
            'aura_history' => []
        ]);
        return $auraChat;
    }

    public function setConsentStatus(Request $request) {
        $request['user_id'] = auth()->id();

        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'consent_status' => 'required|boolean'
        ]);

        if ($validator->fails()) {
            return response()->json(
                ['errors' => $validator->errors()], 
                Response::HTTP_BAD_REQUEST
            ); 
        }

        if ($request['consent_status'] == 1) {
            $response = $this->consent($request['user_id']);
        } else {
            $response = $this->unconsent($request['user_id']);
        }

        return response()->json(
            $response, 
            Response::HTTP_OK
        ); 
    }

    public function consent($userId) {
        $auraChat = $this->getAuraChat($userId);
        
        $data = [
            'aura_consent' => 1
        ];
        $auraChat->update($data);

        return ['aura_consent' => $auraChat->aura_consent];
    }

    public function unconsent($userId) {
        $auraChat = $this->getAuraChat($userId);

        $data = [
            'aura_consent' => 0,
            'aura_history' => []
        ];
        $auraChat->update($data);

        return ['aura_consent' => $auraChat->aura_consent];
    }

    public function getConsentStatus() {
        $userId = auth()->id();
        $auraChat = $this->getAuraChat($userId);

        return response()->json(
            ['aura_consent' => $auraChat->aura_consent], 
            Response::HTTP_OK
        ); 
    }

    public function addMessageToHistory(Request $request) {
        $request['user_id'] = auth()->id();

        $validator = Validator::make($request->all(), [
            'user_id' => 'required|unique:channels,user_id',
            'message' => 'required|array',
        ]);

        if ($validator->fails()) {
            return response()->json(
                ['errors' => $validator->errors()], 
                Response::HTTP_BAD_REQUEST
            ); 
        }

        $message = $request['message'];
        $auraChat = $this->getAuraChat($request['user_id']);    

        if(!is_array($message)){
            $message = json_decode($message);
        }

        if ($auraChat->aura_history == null){
            $data = [
                'aura_history' => array($message)
            ];
        } else {
            $history = $auraChat->aura_history;
            if (count($history) >= 500) {
                array_shift($history);
            }

            array_push ($history,$message);
            $data = [
                'aura_history' => $history
            ];
        }

        $auraChat->update($data);

        return response()->json(
            [], 
            Response::HTTP_OK
        ); 
    }

    public function getAuraHistory() {
        $auraChat = $this->getAuraChat(auth()->id());

        return response()->json(
            ['aura_history' => $auraChat->aura_history], 
            Response::HTTP_OK
        ); 
    }

    public function deleteAuraHistory() {
        $auraChat = $this->getAuraChat(auth()->id());
        $data = [
            'aura_history' => []
        ];

        $auraChat->update($data);

        return response()->json(
            [], 
            Response::HTTP_OK
        ); 
    }
}

