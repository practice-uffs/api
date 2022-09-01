<div id="app" class="container-fluid h-100" >
        <div class="row justify-content-center h-100">
                <div class="chat w-100 h-100"  >
                    <div class="card chat-background border-radius-0" >
                    
                    <header-component :showheader.sync="showHeader"></header-component>

                    <login-component :showlogin.sync="showLogin"></login-component>

                    <div id="chat-body" class="chat-body msg_card_body" onscroll="handleHeader()">
                        <message-component :usertoken.sync="userToken" :message.sync="messages"></message-component>
                        {{-- <div wire:loading.delay wire:target="sendMessage" class="aura_typing text-secondary">
                            Aura está digitando... achar algum substituto em Vue
                        </div>  --}} 

                    {{-- @if($widgetSettings['display_login_form'] == true)
                        <div class="d-flex justify-content-end mb-4">
                            <div class="msg-container-send">
                                <label>
                                    Login (idUFFS):
                                    <input wire:model="username" wire:keydown.enter="performLogin" type="text" class="form-control text-input" placeholder="Seu id UFFS">
                                </label>
                                <br>
                                <label>
                                    Senha:
                                    <input wire:model="password" type="password" wire:keydown.enter="performLogin" class="form-control text-input" placeholder="Digite sua senha">
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
                    @endif --}}

                    </div>
                    <input-component :usertoken.sync="userToken" :messages.sync="messages" :showheader.sync="showHeader"></input-component>
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
                displayHeader();
            }
            
            else{
                hideHeader();
            }
            lastScrollTop = chat.scrollTop; 
        };
    };

    function hideHeader() {
        navbar = document.querySelector('.header');
        navbar.style.opacity='0';
    };

    function displayHeader() {
        navbar = document.querySelector('.header');
        navbar.style.opacity='';
    };
</script>