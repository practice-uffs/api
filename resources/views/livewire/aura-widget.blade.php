
<div class="container-fluid h-100" >
    
    @if($type == 'button')
    <input type="checkbox" id="check"> <label class="chat-btn" for="check"><img height="45px" width="45px" src="{{ asset('img/aura/aura_icon.png') }}" /></label>
    <div class="wrapper">
    @endif
    
        <div class="row justify-content-center h-100">
            @if($type == 'button')
            <div class="col-md-12 col-xl-12 chat h-100"  >
                <div class="card border-radius-15 h-100" >
            @endif
            @if($type == 'fullscreen')
            <div class="chat w-100 h-100"  >
                <div class="card border-radius-0 h-100" > 
            @endif
                    <div class="card-header msg_head" >
                        <div class="d-flex bd-highlight header_height" >
                            <div >
                                <img src="{{ asset('img/aura/aura_icon_online.png') }}" class="user_img">
                            </div>
                            <div class="user_info" >
                                <span>Aura</span>
                                <p class="ia_practice">Inteligencia Artificial do PRACTICE</p>
                                <p class="practice">PRACTICE</p>
                            </div>
                        </div> 
                    </div>
                    <div class="card-body msg_card_body ">
                    @if($login == true)

                        <div class="d-flex justify-content-end mb-4">
                            <div class="msg_cotainer_send">
                                Login (idUFFS):
                                <input wire:model="username" wire:keydown.enter="performLogin" type="text" class="form-control type_msg" placeholder="Username"></input>
                                Senha:
                                <input wire:model="password" type="password" wire:keydown.enter="performLogin" class="form-control type_msg" placeholder="Senha"></input>
                                
                                @if($loginError == true)
                                    <div class="d-flex justify-content-around pt_10">
                                        <small class="w-75 login_error" >{{ $loginErrorMessage }}</small>
                                    </div>
                                @endif
                                
                                <div class="d-flex justify-content-around pt_10">
                                    <button class="btn btn-primary" wire:click="performLogin()" >Fazer login</button>
                                </div>
                                
                            </div>
                            <div style="overflow:hidden; height:40px; width:40px; border-radius:50%;">
                                    @if ($profilePic)
                                        <img src="{{ $profilePic }}" class="img-fluid">
                                    @else
                                        <img src="{{ asset('img/aura/user.png') }}">
                                    @endif
                            </div>
                        </div>
                        
                    @endif

                    @if ($agreedForm == true)
                        <div class="d-flex justify-content-start mb-4">
                            <div class="img_cont_msg">
                                <img src="{{ asset('img/aura/aura_icon.png') }}" class="rounded-circle user_img_msg">
                            </div>
                            
                            <div class="msg_cotainer">
                                Para que eu consiga evoluir constantemente, todas as mensagens e interações que você fizer comigo são armazenadas, na íntegra. As mensagens não estão associadas a você (a autoria é anonimizada). Você concorda com isso?
                                <div class="d-flex justify-content-around pt_10">
                                    <button class="btn btn-primary" wire:click="consentUseOfData()" >Concordo</button>
                                    <button class="btn btn-primary" wire:click="notConsentUseOfData()" >Discordo</button>
                                </div>
                                
                            </div>
                        </div>
                    @endif

                    @foreach($messages as $message)

                        @if ($message['source'] == 'aura')

                            <div class="d-flex justify-content-start mb-4">
                                <div class="img_cont_msg">
                                    <img src="{{ asset('img/aura/aura_icon.png') }}" class="rounded-circle user_img_msg">
                                </div>
                                <div class="msg_cotainer">
                                    {{ $message['message'] }}
                                </div>
                            </div>

                        @else
                            <div class="d-flex justify-content-end mb-4">
                                <div class="msg_cotainer_send">
                                    {{ $message['message'] }}
                                </div>
                                <div style="overflow:hidden; height:40px; width:40px; border-radius:50%;">
                                    
                                    @if ($profilePic)
                                        <img src="{{ $profilePic }}" class="img-fluid">
                                    @else
                                        
                                        <img src="{{ asset('img/aura/user.png') }}">
                                    @endif
                                </div>
                            </div>

                        @endif

                    @endforeach
                
                    </div>
            
                    <div class="card-footer" >
                        <div class="input-group" >
                            @if($login == false)
                                @if($agreedForm == false)
                                    <input wire:model="inputMessage" wire:keydown.enter="sendMessage" type="text" class="form-control type_msg" placeholder="Escreva sua mensagem..."></input>
                                    <a class="input-group-text send_btn" wire:click="sendMessage()"><i class="fas fa-location-arrow"></i></a>
                                @else
                                    
                                    <input wire:model="inputMessage" wire:keydown.enter="sendMessage" type="text" class="form-control type_msg" placeholder="Escreva sua mensagem..." disabled></input>
                                    <a class="input-group-text send_btn" wire:click="sendMessage()" disabled><i class="fas fa-location-arrow"></i></a>
                                @endif    
                            @else
                                <input wire:model="inputMessage" wire:keydown.enter="sendMessage" type="text" class="form-control type_msg" placeholder="Escreva sua mensagem..." disabled></input>
                                <a class="input-group-text send_btn" wire:click="sendMessage()" disabled><i class="fas fa-location-arrow"></i></a>
                            @endif
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @if($type == 'button')
    </div>
    @endif
    
</div>