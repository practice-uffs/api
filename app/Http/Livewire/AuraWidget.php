<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class AuraWidget extends Component
{
    public $inputMessage;
    public $messages;

    public function mount()
    {
        $this->messages[0] = ['message' => 'Olá! Eu sou a AURA, uma assistente virtual desenvolvida pelo PRACTICE, converse comigo!',
                              'source' => 'aura'    
                            ];
        $this->inputMessage = '';
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

        $encodedUrl = urlencode($this->inputMessage);
        $requestUrl = '/v0/aura/nlp/qna/' . $encodedUrl;

        $request = Request::create($requestUrl, 'GET');
        $response = json_decode(Route::dispatch($request)->getContent());
        if ($response != null) {
            if (property_exists($response, 'answer')) {
                array_unshift($this->messages, ['message' => $response->answer,
                                            'source' => 'aura'   
                                            ]);
            } else {
                array_unshift($this->messages, ['message' => 'Não tenho resposta para isso',
                                            'source' => 'aura'   
                                            ]);
            }
        }
        $this->inputMessage = "";
        return;
    }
}
