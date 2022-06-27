<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Http\Request;
use App\Http\Controllers\API\V0\AuraChatController;


class AuraWidget extends Component
{
    public $inputMessage;
    public $messages;
    public $loginErrorMessage = '';
    public $username;
    public $password;
    public $widgetSettings;
    public $user;

    public function mount()
    {
        $this->messages[0] = ['id' => 1,
                              'message' => 'Olá! Eu sou a AURA, uma assistente virtual desenvolvida pelo PRACTICE, converse comigo!',
                              'source' => 'aura',
                              'userMessage' => 'has_no_message',
                              'assessed' => 2,  
                              'category' => 'welcome_message' 
                            ];
        
        $this->inputMessage = '';

        $this->widgetSettings = [
            'theme' => 'light',
            'type' => 'fullscreen',
            'history_loaded' => false,
            'display_agree_form' => false,
            'display_login_form' => false
        ];

        $this->user = [
            'token' => request()->token,
            'id' => 0,
            'profile_pic' => '',
            'consent_status' => -1 // -1: not answered; 1: agreed, 0: disagreed
        ];

        if (request()->type == "button"){
            $this->widgetSettings['type'] = "button";
        }
        if (request()->theme == "dark") {
            $this->widgetSettings['theme'] = "dark";
        }  

        if ($this->user['token'] != null) {
            $request = Request::create('/v0/user/', 'GET');
            $request->headers->set('Authorization', 'Bearer '.$this->user['token']);

            $response = json_decode(app()->handle($request)->getContent());
    
            if ($response != null) {
                if (property_exists($response, 'error')) {
                    $this->user['token'] = null;
                } else {
                    $this->user['id'] = $response->id;
                    $this->user['profile_pic'] = "https://cc.uffs.edu.br/avatar/iduffs/".$response->uid;
                    $consentStatus = AuraChatController::consentStatus($response->id)['aura_consent'];
                    $this->user['consent_status'] = $consentStatus == 1 ? $consentStatus : -1;
                }
            } else {
                $this->user['token'] = null;
            }
        }
    }

    public function render()
    {   
        return view('livewire.aura-widget');
    }

    public function sendMessage(){

        if ($this->inputMessage == ""){
            return;
        }
        $this->addMessageToChat($this->inputMessage, 'user_message', false, $this->inputMessage, 'user');
                                           

        $encodedUrl = rawurlencode($this->inputMessage);
        $requestUrl = '/v0/aura/nlp/' . $encodedUrl;
        
        $messageRequest = Request::create($requestUrl, 'GET');
        
        if ($this->user['token'] != null){
            $messageRequest->headers->set('Authorization', 'Bearer '.$this->user['token']);
        }
        
        $messageResponse = json_decode(app()->handle($messageRequest)->getContent());

        if ($messageResponse != null) {   
            if (property_exists($messageResponse, 'error')){
                if ($messageResponse->error == "Missing bearer token in request"){
                    $this->addMessageToChat('Para poder conversar comigo você precisa estar autenticado(a). Por favor autentique-se:', 'user_not_authenticated', false, $this->inputMessage);                                          
                    $this->widgetSettings['display_login_form'] = true;
                } else {
                    $this->addMessageToChat('Algo de errado aconteceu com a sua autenticação, tente autenticar novamente', 'authentication_failed', false, $this->inputMessage);
                    $this->widgetSettings['display_login_form'] = true;
                }
            } else { 
                $consentStatus = AuraChatController::consentStatus($this->user['id']);
                
                if ($consentStatus['aura_consent'] == 0){
                    $this->widgetSettings['display_agree_form'] = true;
                    $this->inputMessage = "";
                    return;
                } else {
                    $this->user['consent_status'] = 1;
                }

                AuraChatController::setAuraHistory($this->user['id'], $this->messages[0]);

                if (property_exists($messageResponse, 'answer')) {
                    $this->addMessageToChat($messageResponse->answer, $messageResponse->intent, true, $this->inputMessage);
                } else {
                    $this->addMessageToChat('Não tenho resposta para isso.', 'aura_has_no_response', true, $this->inputMessage);
                }
            }
        } else {
            $consentStatus = AuraChatController::consentStatus($this->user['id']);

            if ($consentStatus['aura_consent'] == 0){
                $this->widgetSettings['display_agree_form'] = true;
                $this->inputMessage = "";
                return;
            } else {
                $this->user['consent_status'] = 1;
            }

            $this->addMessageToChat('Algo de errado está acontecendo com meus servidores, bip bop.', 'aura_could_not_respond', false, $this->inputMessage);            
        }
        $this->inputMessage = "";
        return;
    }

    public function performLogin(){
        if( $this->username != '' && $this->password != '' ){
            $request = Request::create('/v0/auth/', 'POST',array('user' => $this->username, 
                                                                'password' => $this->password, 
                                                                'app_id' => '1'));
            
            $request->headers->set('Authorization', 'Bearer '.$this->user['token']);
            $response = app()->handle($request);

            $data = json_decode($response->getContent());
            
            if ($data == null){
                $this->loginErrorMessage = 'Usuário ou senha incorreto';
            } else {
                $this->user['id'] = $data->user->id;
                
                $this->widgetSettings['display_login_form'] = false;
                $this->user['token'] = $data->passport;
               
                $this->loadHistory();

                $consentStatus = AuraChatController::consentStatus($this->user['id']);
                
                if ($consentStatus['aura_consent'] == '1'){
                    $this->user['consent_status'] = 1;
                } else {
                    $this->widgetSettings['display_agree_form'] = true;
                }
                
                $this->user['profile_pic'] = "https://cc.uffs.edu.br/avatar/iduffs/".$data->user->uid;

                $this->addMessageToChat('Logado(a) com sucesso!!!', 'user_logged_in', true, 'has_no_message', 'user');
               
                $this->addMessageToChat('Seja bem vindo(a) '.$data->user->name.'! Converse comigo :)', 'user_authenticated', true);
                                     
                $this->username = '';
                $this->password = '';
                
            }
            return;
        } else {
            $this->loginErrorMessage = 'Ambos os campos devem ser preenchidos';
            return;
        }
    }

    public function consentUseOfData(){

        $consentStatus = AuraChatController::consent($this->user['id']);

        if ($consentStatus['aura_consent'] == 1){
            $this->widgetSettings['display_agree_form'] = false;
            $this->user['consent_status'] = 1;
            $this->widgetSettings['history_loaded'] == true;
        } else {
            $this->addMessageToChat('Não conseguimos aceitar o seu consentimento, erro nos servidores...', 'could_not_accept_aura_consent', false);               
        }
    }

    public function addMessageToChat(string $message, string $category, bool $save, string $userMessage = 'has_no_message', string $source = 'aura') {
        array_unshift($this->messages, ['id' => count($this->messages) + 1,
                                        'message' => $message,
                                        'source' => $source,
                                        'userMessage' => $userMessage,
                                        'assessed' => 2,
                                        'category' => $category      
                                        ]);
        if ($save == true) {
            AuraChatController::setAuraHistory($this->user['id'], $this->messages[0]);
        }
    }

    public function unconsentUseOfData(){

        $consentStatus = AuraChatController::unconsent($this->user['id']);
        if ($consentStatus['aura_consent'] == 0){
            $this->widgetSettings['display_agree_form'] = false;
            $this->user['consent_status'] = 0;
            $this->addMessageToChat(
                'O histórico de suas mensagens foi excluído e não armazenaremos mais os teus dados relacionados à Aura... No entanto, não posso mais conversar com você :(, caso queira conversar comigo, me dê a permissão para armazenar seus dados clicando do menu no canto superior direito',
                'accepted_aura_consent', 
                false
            );
        } else {
            $this->addMessageToChat('Não conseguimos aceitar o seu não consentimento, erro nos servidores...', 'accepted_aura_consent', false);
        }
    }

    public function displayAgreeForm(){
        $this->widgetSettings['display_agree_form'] = true;
    }

    public function assessAnswer($category, $userMessage, $rate, $messageId){
        $request = Request::create('/v0/analytics/', 'POST',array('user_id'=>$this->user['id'],
                                                                  'app_id'=>'1',
                                                                  'action'=>'aura_feedback',
                                                                  'key'=>$category,
                                                                  'value'=>$userMessage,
                                                                  'rate'=>$rate
                                                                ));

        $request->headers->set('Authorization', 'Bearer '.$this->user['token']);
        $response = app()->handle($request);

        $data = json_decode($response->getContent());
        
        if (property_exists($data, 'errors')){
            $this->messages[count($this->messages)-$messageId]["assessed"] = -1; 
            // Assessed: 2 stands for 'not assessed', 1 for 'liked', 0 for 'disliked' and -1 for 'assessment error'.
        } else {
            $this->messages[count($this->messages)-$messageId]["assessed"] = $rate;
        }
    }

    public function loadHistory(){
        $auraHistory = AuraChatController::getAuraHistory($this->user['id']);

        $keepUnloggedMessages = null;
        $keepUnloggedMessages = array_reverse($this->messages);
        $this->messages = null;
        $this->messages = array();

        $totalMessagesSize = 1;
        if ($auraHistory['aura_history'] != null){
            $lastSavedMessage = end($auraHistory['aura_history']);
            foreach ($auraHistory['aura_history'] as $objectMessage) {
                $arrayMessage = (array) $objectMessage;
                $arrayMessage["id"] = $totalMessagesSize;
                $totalMessagesSize = $totalMessagesSize + 1;
                array_unshift($this->messages, $arrayMessage);
            }
        }

        if($keepUnloggedMessages != null){
            foreach ($keepUnloggedMessages as $message) {
                $message["id"] = $totalMessagesSize;
                $totalMessagesSize = $totalMessagesSize + 1;
                array_unshift($this->messages, $message);
            }
        }
        $this->widgetSettings['history_loaded'] = true;
    }
     
}
