<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Http\Request;


class AuraWidget extends Component
{
    public $inputMessage;
    public $messages;
    public $token;
    public $type;
    public $agreeForm;
    public $agreed;
    public $disagreeForm;
    public $login;
    public $loggedIn;
    public $loginError;
    public $loginErrorMessage = '';
    public $username;
    public $password;
    public $profilePic;

    public function mount()
    {   
        $this->agreeForm = false;
        $this->disagreeForm = false;
        $this->agreed = false;
        $this->login = false;
        $this->loggedIn = false;
        $this->loginError = false;
        $this->messages[0] = ['message' => 'Olá! Eu sou a AURA, uma assistente virtual desenvolvida pelo PRACTICE, converse comigo!',
                              'source' => 'aura'    
                            ];
        $this->inputMessage = '';
        $this->token = request()->token;
        if ($this->type = request()->type == null){ 
            $this->type = "fullscreen";
        } else {
            if (request()->type != "fullscreen" && request()->type != "button"){
                $this->type = "fullscreen";
            } else {
                $this->type = request()->type;
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
        array_unshift($this->messages, ['message' => $this->inputMessage,
                                     'source' => 'user'   
                                    ]);

        $encodedUrl = rawurlencode($this->inputMessage);
        $requestUrl = '/v0/aura/nlp/domain/' . $encodedUrl;

        
        $request = Request::create($requestUrl, 'GET');
        
        if ($this->token != null){
            $request->headers->set('Authorization', 'Bearer '.$this->token);
        } 
        
        $response = json_decode(app()->handle($request)->getContent());

        if ($response != null) {   
            if (property_exists($response, 'error')){
                if ($response->error == "Missing bearer token in request"){
                    array_unshift($this->messages, ['message' => 'Para poder conversar comigo você precisa estar autenticado(a). Por favor autentique-se:',
                                                'source' => 'aura'   
                                                ]);
                    $this->login = true;
                } else {
                    array_unshift($this->messages, ['message' => "Algo de errado aconteceu com a sua autenticação, tente autenticar novamente:",
                                                'source' => 'aura'   
                                                ]);
                    $this->login = true;
                }
            } else { 
                
                if (property_exists($response, 'answer')) {
                    array_unshift($this->messages, ['message' => $response->answer,
                                                'source' => 'aura'   
                                                ]);
                } else {
                    array_unshift($this->messages, ['message' => 'Não tenho resposta para isso.',
                                                'source' => 'aura'   
                                                ]);
                }
            }
        } else {
            array_unshift($this->messages, ['message' => 'Algo de errado está acontecendo com meus servidores, bip bop.',
                                                'source' => 'aura'   
                                                ]);
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
                $this->login = false;
                $this->loggedIn = true;
                $this->token = $data->passport;
                if ($data->user->aura_consent == '1'){
                    $this->agreed = true;
                } else {
                    $this->agreeForm = true;
                }

                $this->profilePic = "https://cc.uffs.edu.br/avatar/iduffs/".$data->user->uid;

                array_unshift($this->messages, ['message' => 'Logado(a) com sucesso!!!',
                                                    'source' => 'user'   
                                                    ]);
                array_unshift($this->messages, ['message' => 'Seja bem vindo(a) '.$data->user->name.'! Converse comigo :)',
                                                    'source' => 'aura'   
                                                    ]);
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
        $requestUrl = '/v0/user/aura_consent';
        $request = Request::create($requestUrl, 'GET');
        if ($this->token != null){
            $request->headers->set('Authorization', 'Bearer '.$this->token);
        } 
        $response = json_decode(app()->handle($request)->getContent());
        if ($response->aura_consent == 1){
            $this->agreed = true;
            $this->agreeForm = false;
        } else {
            array_unshift($this->messages, ['message' => 'Não conseguimos aceitar o seu consentimento, erro nos servidores...',
                                                'source' => 'aura'   
                                                ]);
        }
    }
    public function unonsentUseOfData(){
        $requestUrl = '/v0/user/aura_unconsent';
        $request = Request::create($requestUrl, 'GET');
        if ($this->token != null){
            $request->headers->set('Authorization', 'Bearer '.$this->token);
        } 
        $response = json_decode(app()->handle($request)->getContent());
        if ($response->aura_consent == 0){
            $this->disagreeForm = true;
            $this->agreeForm = false;
        } else {
            array_unshift($this->messages, ['message' => 'Não conseguimos aceitar o seu não consentimento, erro nos servidores...',
                                                'source' => 'aura'   
                                                ]);
        }
    }

    public function displayAgreeForm(){
        $this->agreeForm = true;
        $this->disagreeForm = false;
    }
    
}
