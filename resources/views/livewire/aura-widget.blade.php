

  
@if($theme == 'dark')
<div class="container-fluid container-fluid-dark h-100" >
@endif

@if($theme == 'light')
<div class="container-fluid container-fluid-light h-100" >
@endif

    @if($type == 'button')
    <input type="checkbox" id="check"> <label class="chat-btn" for="check"><img height="45px" width="45px" src="{{ asset('img/aura/aura_icon.png') }}" /></label>
    <div class="wrapper">
    @endif
    
        <div class="row justify-content-center h-100">
            @if($type == 'button')
            <div class="col-md-12 col-xl-12 chat h-100"  >

                @if($theme == 'dark')
                <div class="card card-bg-theme-dark border-radius-15 h-100" >
                @endif

                @if($theme == 'light')
                <div class="card card-bg-theme-light border-radius-15 h-100" >
                @endif

            @endif
            @if($type == 'fullscreen')
            <div class="chat w-100 h-100"  >

                @if($theme == 'dark')
                <div class="card card-bg-theme-dark border-radius-0 h-100" >
                @endif

                @if($theme == 'light')
                <div class="card card-bg-theme-light border-radius-0 h-100" >
                @endif

            @endif
                    <div class="card-header msg_head">
                        <div class="d-flex bd-highlight header_height">
                            <div class="p-2">
                                <img src="{{ asset('img/aura/aura_icon_online.png') }}" class="user_img">
                            </div>
                            <div class="user_info p-2">
                                <span>Aura</span>
                                <p class="ia_practice">Inteligencia Artificial do PRACTICE</p>
                                <p class="practice">PRACTICE</p>
                            </div>
                            @if ($loggedIn == true)
                            <div class="ml-auto p-2">
                                <div class="dropdown">
                                    <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    </button>
                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                        @if ($agreed == true)
                                            @if ($disagreeForm == true)
                                                <a class="dropdown-item" href="#" wire:click="displayAgreeForm()">Concordar com o uso de dados</a>
                                            @else
                                                <a class="dropdown-item" href="#" wire:click="displayAgreeForm()">Discordar com o uso de dados</a>
                                            @endif  
                                        @else
                                            <a class="dropdown-item" href="#" wire:click="displayAgreeForm()">Concordar com o uso de dados</a>
                                        @endif    
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div> 
                    
                    </div>
                    <div class="card-body msg_card_body ">
                    @if($login == true)

                        <div class="d-flex justify-content-end mb-4">
                            
                            @if($theme == 'dark')
                                <div class="msg_container_send msg_container_send_theme_dark">
                            @endif

                            @if($theme == 'light')
                                <div class="msg_container_send msg_container_send_theme_light">
                            @endif

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

                    @if ($agreeForm == true)
                        <div class="d-flex justify-content-start mb-4">
                            <div class="img_cont_msg">
                                <img src="{{ asset('img/aura/aura_icon.png') }}" class="rounded-circle user_img_msg">
                            </div>
                            
                            @if($theme == 'dark')
                            <div class="msg_container msg_container_theme_dark">
                            @endif

                            @if($theme == 'light')
                            <div class="msg_container msg_container_theme_light">
                            @endif

                                Para que eu consiga evoluir constantemente, todas as mensagens e interações que você fizer comigo são armazenadas, na íntegra. As mensagens não estão associadas a você (a autoria é anonimizada). Você concorda com isso?
                                <div class="d-flex justify-content-around pt_10">
                                    <button class="btn btn-primary" wire:click="consentUseOfData()" >Concordo</button>
                                    <button class="btn btn-primary" wire:click="unonsentUseOfData()" >Discordo</button>
                                </div>
                                
                            </div>
                        </div>
                    @endif

                    @if ($disagreeForm == true)
                        <div class="d-flex justify-content-start mb-4">
                            <div class="img_cont_msg">
                                <img src="{{ asset('img/aura/aura_icon.png') }}" class="rounded-circle user_img_msg">
                            </div>
                            
                            @if($theme == 'dark')
                            <div class="msg_container msg_container_theme_dark">
                            @endif

                            @if($theme == 'light')
                            <div class="msg_container msg_container_theme_light">
                            @endif
                                O histórico de suas mensagens foi excluído e não armazenaremos mais os teus dados relacionados à Aura... No entanto, não posso mais conversar com você :(, caso queira conversar comigo, me dê a permissão para armazenar seus dados clicando do menu no canto superior direito
                            </div>
                        </div>
                    @endif

                    @foreach($messages as $message)

                        @if ($message['source'] == 'aura')

                            <div class="d-flex justify-content-start mb-4">
                                <div class="img_cont_msg">
                                    <img src="{{ asset('img/aura/aura_icon.png') }}" class="rounded-circle user_img_msg">
                                </div>

                                @if($theme == 'dark')
                                <div class="msg_container msg_container_theme_dark">
                                @endif

                                @if($theme == 'light')
                                <div class="msg_container msg_container_theme_light">
                                @endif

                                    {{ $message['message'] }}
                                </div>
                            </div>

                        @else
                            <div class="d-flex justify-content-end mb-4">
                                @if($theme == 'dark')
                                <div class="msg_container_send msg_container_send_theme_dark">
                                @endif

                                @if($theme == 'light')
                                <div class="msg_container_send msg_container_send_theme_light">
                                @endif
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
                                @if($agreeForm == false)
                                    @if($disagreeForm == false)
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