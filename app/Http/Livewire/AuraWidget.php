<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Http\Request;
use App\Http\Controllers\API\V0\AuraChatController;


class AuraWidget extends Component
{
    public $inputMessage;
    public $messages;
    public $token;
    public $type;
    public $agreeForm;
    public $agreed;
    public $disagreedForm;
    public $login;
    public $loggedIn;
    public $loginError;
    public $loginErrorMessage = '';
    public $username;
    public $password;
    public $profilePic;
    public $theme;
    public $messageId;
    public $userId;
    public $historyLoaded;

    public function mount()
    {   
        $this->messageId = 1;
        $this->messages[0] = ['id' => $this->messageId,
                              'message' => 'Olá! Eu sou a AURA, uma assistente virtual desenvolvida pelo PRACTICE, converse comigo!',
                              'source' => 'aura',
                              'userMessage' => 'has_no_message',
                              'assessed' => 2,  
                              'category' => 'welcome_message' 
                            ];
        $this->messageId++;
        $this->inputMessage = '';
        $this->historyLoaded = false;
        $this->token = request()->token;

        if ($this->token != null) {
            $request = Request::create('/v0/user/', 'GET');
            $request->headers->set('Authorization', 'Bearer '.$this->token);

            $response = json_decode(app()->handle($request)->getContent());
    
            if ($response != null) {
                if (property_exists($response, 'error')) {
                    $this->token = null;
                } else {
                    $this->userId = $response->id;
                    $this->loggedIn = true;
                }
            } else {
                $this->token = null;
            }
        }

        if ($this->type = request()->type == null){ 
            $this->type = "fullscreen";
        } else {
            if (request()->type != "fullscreen" && request()->type != "button"){
                $this->type = "fullscreen";
            } else {
                $this->type = request()->type;
            }
        }   

        if ($this->theme = request()->theme == null){ 
            $this->theme = "light";
        } else {
            if (request()->theme != "light" && request()->theme != "dark"){
                $this->theme = "light";
            } else {
                $this->theme = request()->theme;
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
        array_unshift($this->messages, ['id' => $this->messageId,
                                        'message' => $this->inputMessage,
                                        'source' => 'user',
                                        'userMessage' => $this->inputMessage,
                                        'assessed' => 2,  
                                        'category' => 'user_message'      
                                        ]);
        $this->messageId++;                                     

        $encodedUrl = rawurlencode($this->inputMessage);
        $requestUrl = '/v0/aura/nlp/' . $encodedUrl;

        
        $messageRequest = Request::create($requestUrl, 'GET');
        
        if ($this->token != null){
            $messageRequest->headers->set('Authorization', 'Bearer '.$this->token);
        }
        
        $messageResponse = json_decode(app()->handle($messageRequest)->getContent());

        if ($messageResponse != null) {   
            if (property_exists($messageResponse, 'error')){
                if ($messageResponse->error == "Missing bearer token in request"){
                    array_unshift($this->messages, ['id' => $this->messageId,
                                                    'message' => 'Para poder conversar comigo você precisa estar autenticado(a). Por favor autentique-se:',
                                                    'source' => 'aura',
                                                    'userMessage' => $this->inputMessage,
                                                    'assessed' => 2,  
                                                    'category' => 'user_not_authenticated'      
                                                    ]);
                    $this->messageId++;                            
                    $this->login = true;
                } else {
                    array_unshift($this->messages, ['id' => $this->messageId,
                                                    'message' => "Algo de errado aconteceu com a sua autenticação, tente autenticar novamente:",
                                                    'source' => 'aura',
                                                    'userMessage' => $this->inputMessage,
                                                    'assessed' => 2,  
                                                    'category' => 'authentication_failed'       
                                                    ]);
                    $this->messageId++;
                    $this->login = true;
                }
            } else { 
                $this->loggedIn = true;

                $auraConsentResponse = AuraChatController::consentStatus($this->userId);

                if ($auraConsentResponse['aura_consent'] == 0){
                    $this->displayAgreeForm();
                } else {
                    $this->agreed = true;
                }

                $historyResponse = AuraChatController::setAuraHistory($this->userId, $this->messages[0]);

                if (property_exists($messageResponse, 'answer')) {
                    array_unshift($this->messages, ['id' => $this->messageId,
                                                    'message' => $messageResponse->answer,
                                                    'source' => 'aura',
                                                    'userMessage' => $this->inputMessage,
                                                    'assessed' => 2,  
                                                    'category' => $messageResponse->intent
                                                    ]);

                    $historyResponse = AuraChatController::setAuraHistory($this->userId, $this->messages[0]);
                    $this->messageId++;

                } else {

                    array_unshift($this->messages, ['id' => $this->messageId,
                                                    'message' => 'Não tenho resposta para isso.',
                                                    'source' => 'aura',
                                                    'userMessage' => $this->inputMessage,
                                                    'assessed' => 2,  
                                                    'category' => 'aura_has_no_response'      
                                                    ]);

                    $historyResponse = AuraChatController::setAuraHistory($this->userId, $this->messages[0]);
                    $this->messageId++;
                }
            }
        } else {
            array_unshift($this->messages, ['id' => $this->messageId,
                                            'message' => 'Algo de errado está acontecendo com meus servidores, bip bop.',
                                            'source' => 'aura',
                                            'userMessage' => $this->inputMessage,
                                            'assessed' => 2,  
                                            'category' => 'aura_could_not_respond'      
                                            ]);
            $this->messageId++;
        }
        $this->inputMessage = "";
        return;
    }

    public function performLogin(){
        if( $this->username != '' && $this->password != '' ){
            $request = Request::create('/v0/auth/', 'POST',array('user' => $this->username, 
                                                                'password' => $this->password, 
                                                                'app_id' => '1'));
            
            $request->headers->set('Authorization', 'Bearer '.$this->token);

            $response = app()->handle($request);

            $data = json_decode($response->getContent());
            
            if ($data == null){
                $this->loginError = true;
                $this->loginErrorMessage = 'Usuário ou senha incorreto';
            } else {
                $this->userId = $data->user->id;
                
                $this->login = false;
                $this->loggedIn = true;
                $this->token = $data->passport;
               
                $this->loadHistory();

                $auraConsentResponse = AuraChatController::consentStatus($this->userId);
                
                if ($auraConsentResponse['aura_consent'] == '1'){
                    $this->agreed = true;
                } else {
                    $this->agreeForm = true;
                }
                
                $this->profilePic = "https://cc.uffs.edu.br/avatar/iduffs/".$data->user->uid;

                array_unshift($this->messages, ['id' => $this->messageId,
                                                'message' => 'Logado(a) com sucesso!!!',
                                                'source' => 'user',
                                                'userMessage' => 'has_no_message',
                                                'assessed' => 2,  
                                                'category' => 'user_logged_in'      
                                                ]);  
                $historyResponse = AuraChatController::setAuraHistory($this->userId, $this->messages[0]);                            
                                                 
                $this->messageId++;                                    
                array_unshift($this->messages, ['id' => $this->messageId,
                                                'message' => 'Seja bem vindo(a) '.$data->user->name.'! Converse comigo :)',
                                                'source' => 'aura',
                                                'userMessage' => 'has_no_message',
                                                'assessed' => 2,  
                                                'category' => 'user_authenticated'      
                                                ]);
                $historyResponse = AuraChatController::setAuraHistory($this->userId, $this->messages[0]);
                
                $this->messageId++;                                    
                $this->username = '';
                $this->password = '';
                
            }
            return;
        } else {
            $this->loginError = true;
            $this->loginErrorMessage = 'Ambos os campos devem ser preenchidos';
            return;
        }
    }

    public function consentUseOfData(){

        $response = AuraChatController::consent($this->userId);

        if ($response['aura_consent'] == 1){
            $this->agreed = true;
            $this->agreeForm = false;
        } else {
            array_unshift($this->messages, ['id' => $this->messageId,
                                            'message' => 'Não conseguimos aceitar o seu consentimento, erro nos servidores...',
                                            'source' => 'aura',
                                            'userMessage' => 'has_no_message',
                                            'assessed' => 2,  
                                            'category' => 'could_not_accept_aura_consent'      
                                            ]);
            $this->messageId++;   
        }
    }

    public function unonsentUseOfData(){

        $response = AuraChatController::unconsent($this->userId);
        if ($response['aura_consent'] == 0){
            $this->disagreedForm = true;
            $this->agreeForm = false;
            $this->agreed = false;
        } else {
            array_unshift($this->messages, ['id' => $this->messageId,
                                            'message' => 'Não conseguimos aceitar o seu não consentimento, erro nos servidores...',
                                            'source' => 'aura',
                                            'userMessage' => 'has_no_message',
                                            'assessed' => 2,  
                                            'category' => 'accepted_aura_consent'      
                                            ]);
            $this->messageId++;   
        }
    }

    public function displayAgreeForm(){
        $this->agreeForm = true;
        $this->disagreedForm = false;
    }

    public function assessAnswer($category, $userMessage, $rate, $messageId){
        $request = Request::create('/v0/analytics/', 'POST',array('user_id'=>$this->userId,
                                                                  'app_id'=>'1',
                                                                  'action'=>'aura_feedback',
                                                                  'key'=>$category,
                                                                  'value'=>$userMessage,
                                                                  'rate'=>$rate
                                                                ));
        
       

        $request->headers->set('Authorization', 'Bearer '.$this->token);

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
        $historyResponse = AuraChatController::getAuraHistory($this->userId);

        $keepUnloggedMessages = null;
        $keepUnloggedMessages = array_reverse($this->messages);
        $this->messages = null;
        $this->messages = array();

        $totalMessagesSize = 1;
        if ($historyResponse['aura_history'] != null){
            $lastSavedMessage = end($historyResponse['aura_history']);
            foreach ($historyResponse['aura_history'] as $objectMessage) {
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
        $this->messageId = $totalMessagesSize;
        $this->historyLoaded = true;
    }
     
}
