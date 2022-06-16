<?php

namespace App\Http\Controllers\API\V0;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use App\Models\AuraChat;


class AuraChatController extends Controller
{
    public static function getAuraChat($userId) {
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

    public static function consent($userId)
    {
        $auraChat = AuraChatController::getAuraChat($userId);
        

        $data = [
            'aura_consent' => 1
        ];
        $auraChat->update($data);

        return ['aura_consent' => $auraChat->aura_consent];
    }

    public static function unconsent($userId)
    {
        // We're not saving this user's message history yet
        // Here should be done the deletion of the message history of this user
        $auraChat = AuraChatController::getAuraChat($userId);

        $data = [
            'aura_consent' => 0
        ];
        $auraChat->update($data);

        return ['aura_consent' => $auraChat->aura_consent];
    }

    public static function consentStatus($userId)
    {
        $auraChat = AuraChatController::getAuraChat($userId);
        return ['aura_consent' => $auraChat->aura_consent];
    }

    public static function setAuraHistory($userId, $auraHistory)
    {

        if(!is_array($auraHistory)){
            $auraHistory = json_decode($auraHistory);
        }

        $auraChat = AuraChatController::getAuraChat($userId);    
        
        if ($auraChat->aura_history == null){
            $data = [
                'aura_history' => array($auraHistory)
            ];
        } else {
            $history = $auraChat->aura_history;
            if (count($history) >= 5) {
                array_shift($history);
            }

            array_push ($history,$auraHistory);
            $data = [
                'aura_history' => $history
            ];
        }

        $auraChat->update($data);

        return True;
    }

    public static function getAuraHistory($userId)
    {
        $auraChat = AuraChatController::getAuraChat($userId);
        return ['aura_history' => $auraChat->aura_history];
    }

    public static function deleteAuraHistory($userId)
    {
        $auraChat = AuraChatController::getAuraChat($userId);
        $data = [
            'aura_history' => null
        ];
        $updated = $auraChat->update($data);
        if ($updated) {
            return True;
        }

        return False;
    }
}

