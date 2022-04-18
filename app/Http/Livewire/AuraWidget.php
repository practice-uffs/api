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
    public $login = false;
    public $loginError = false;
    public $loginErrorMessage = '';
    public $username;
    public $password;
    public $profilePic;

    public function mount()
    {
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
                $this->token = $data->passport;

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
}
