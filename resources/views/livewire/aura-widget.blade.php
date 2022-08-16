<div id="app" class="container-fluid container-fluidd-{{$widgetSettings['theme']}} h-100" >
    <example-component></example-component>
        <div class="row justify-content-center h-100">
                <div class="chat w-100 h-100"  >
                    <div class="card chat-background border-radius-0" >
                    <div id="header" wire:ignore class="header">
                        <div class="chat-header">
                            <img src="{{ asset('img/aura/aura_icon.png') }}" class="logo">
                            <div class="aura-info">
                                <p class="chat-header-text">Assistente virtual</p>
                                <p class="chat-header-text">AURA</p>
                            </div>
                        </div> 
                    </div>

                    <div id="chat-body" class="chat-body msg_card_body" onscroll="handleHeader()">
                        <div wire:loading.delay wire:target="sendMessage" class="aura_typing text-secondary">
                            Aura está digitando...
                        </div>
                    @if($widgetSettings['display_login_form'] == true)
                        <div class="d-flex justify-content-end mb-4">
                            <div class="msg-container-send">
                                <label>
                                    Login (idUFFS):
                                    <input wire:model="username" wire:keydown.enter="performLogin" type="text" class="form-control text-input" wire:click="inputFocus()" placeholder="Seu id UFFS">
                                </label>
                                <br>
                                <label>
                                    Senha:
                                    <input wire:model="password" type="password" wire:keydown.enter="performLogin" class="form-control text-input" wire:click="inputFocus()" placeholder="Digite sua senha">
                                </label>
                                
                                @if($loginErrorMessage != '')
                                    <div class="d-flex justify-content-around pt_10">
                                        <small class="w-75 login_error" >{{ $loginErrorMessage }}</small>
                                    </div>
                                @endif
                                
                                <div class="d-flex justify-content-around pt_10">
                                    <button class="btn btn-primary" wire:click="performLogin()" >
                                        <span wire:loading wire:target="performLogin" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                        Fazer login
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endif
                    @if ($widgetSettings['display_agree_form'] == true)
                        <div class="d-flex justify-content-start mb-4">
                            <div class="msg-container">
                                Para que eu consiga evoluir constantemente, todas as mensagens e interações que você fizer comigo são armazenadas, na íntegra. As mensagens não estão associadas a você (a autoria é anonimizada). Você concorda com isso?
                                <div class="d-flex justify-content-around pt_10">
                                    <button class="btn btn-primary" wire:click="consentUseOfData()" >
                                        <span wire:loading wire:target="consentUseOfData" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                        Concordo
                                    </button>
                                    <button class="btn btn-primary" wire:click="unconsentUseOfData()" >
                                        <span wire:loading wire:target="unconsentUseOfData" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                        Discordo
                                    </button>
                                </div>
                                
                            </div>
                        </div>
                    @endif

                    @foreach($messages as $message)
                        @if ($message['source'] == 'aura')
                            <div class="d-flex justify-content-start received-message mb-4">
                                <div class="msg-container">
                                    {{ $message['message'] }}

                                    @if ($this->user['token'] != null && $user['consent_status'] == 1)
                                        @if ($message['assessed'] == 2)    
                                            <div class="d-flex justify-content-between">
                                                <button class="rate-icon" wire:click="assessAnswer('{{$message['category']}}','{{$message['userMessage']}}',0,{{$message['id']}})">
                                                    <img src="{{ asset('img/aura/doubt.png') }}" class="rate-icon" alt="Mensagem sem sentido">
                                                </button>
                                                <button class="rate-icon" wire:click="assessAnswer('{{$message['category']}}','{{$message['userMessage']}}',1,{{$message['id']}})">
                                                    <img src="{{ asset('img/aura/heart.png') }}" class="rate-icon" alt="Apreciar mensagem">
                                                </button>
                                            </div>
                                        @else
                                            <div class="d-flex justify-content-end">
                                                <div>
                                                @if ($message['assessed'] == 0)    
                                                    <small class="mr-2 text-danger">Mensagem avaliada!</small>
                                                @elseif ($message['assessed'] == 1)    
                                                    <small class="mr-2 text-success">Mensagem avaliada!</small>
                                                @elseif ($message['assessed'] == -1)    
                                                    <small class="mr-2 text-danger">Não foi possível avaliar a resposta.</small>
                                                @endif
                                                </div>
                                            </div>
                                        @endif
                                    @endif
                                </div>
                            </div>
                        @else
                            <div class="d-flex justify-content-end mb-4">
                                <div class="msg-container-send">
                                    {{ $message['message'] }}
                                </div>
                            </div>
                        @endif
                    @endforeach

                    @if ($user['token'] != null && $user['consent_status'] == 1 && $widgetSettings['history_loaded'] == false)
                        <div>
                            <a wire:click="loadHistory">
                                <p class="w-100 pt-3 pb-3 text-center text-muted cursor-pointer">
                                    <u>Mostrar mensagens anteriores</u>
                                </p>
                            </a>
                        </div>
                    @endif

                    </div>
            
                    <div class="input-bar" >
                        <div class="input-group" >
                            @if($widgetSettings['display_login_form'] == false && $user['consent_status'] != 0)
                                <input wire:model="inputMessage" wire:keydown.enter="sendMessage" type="text" wire:click="inputFocus()" class="form-control text-input" placeholder="O que deseja saber?" />
                                <a class="input-group-text send-button" wire:click="sendMessage"><img src="{{ asset('img/aura/sendIcon.png') }}" class="send-icon"></a>
                            @else
                                <input wire:model="inputMessage" wire:keydown.enter="sendMessage" wire:click="inputFocus()" type="text" class="form-control text-input" placeholder="O que deseja saber?" disabled />
                                <a class="input-group-text send-button" wire:click="sendMessage" disabled><img src="{{ asset('img/aura/sendIcon.png') }}" class="send-icon"></a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
</div>

<script>
    var lastScrollTop = 0;
    function handleHeader() {
        if (window.innerWidth < 449) {
            navbar = document.getElementById('header');
            chat = document.getElementById('chat-body');

            if(chat.scrollTop > lastScrollTop){ 
                showHeader();
            }
            
            else{
                hideHeader();
            }
            lastScrollTop = chat.scrollTop; 
        };
    };

    function hideHeader() {
        navbar = document.getElementById('header');
        navbar.style.opacity='0';
        navbar.style.zIndex='0';
    };

    function showHeader() {
        navbar = document.getElementById('header');
        navbar.style.opacity='1';
        navbar.style.zIndex='1';
    };
</script>

<script>
    document.addEventListener('livewire:load', function () {
        if (window.innerWidth < 450) {
            Livewire.on('hideHeader', function () {
                var showHead = @this.get('headerVisible')
                showHead ? showHeader() : hideHeader()
            });
        }
    });
</script>

@if($widgetSettings['theme'] == 'light')
    <style>
    :root {
        --theme: #ECECEC;
        --container: #D9D9D9;
        --input-font-color: #000;
        --input-color: #BDD0D7;
        --font-color: #2F7B9A;
        --placeholder: #6F6F6F;
    }
    </style>
@else
    <style>
    :root {
        --theme: #041C26;
        --container: #153E4B;
        --input-font-color: #FFF;
        --input-color: #153E4B;
        --font-color: #FCAE17;
        --placeholder: #7796A0;
    }
    .send-icon {
            content: url("{{ asset('img/aura/sendIconDark.png') }}");
    }
    </style>
@endif