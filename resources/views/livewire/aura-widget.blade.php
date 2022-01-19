
<div class="container-fluid h-100" >
    <div class="row justify-content-center h-100">
        <div class="col-md-12 col-xl-12 chat h-100"  >
            <div class="card h-100" >
                <div class="card-header msg_head" >
                    <div class="d-flex bd-highlight">
                        <div class="img_cont">
                            <img src="{{ asset('img/aura/aura_icon.png') }}" class="rounded-circle user_img">
                            <span class="online_icon"></span>
                        </div>
                        <div class="user_info">
                            <span>Aura</span>
                            <p>Inteligencia Artificial do PRACTICE</p>
                        </div>
                    </div> 
                </div>
                <div class="card-body msg_card_body ">
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
                            <div class="img_cont_msg">
                                <img src="{{ asset('img/aura/user.png') }}" class="rounded-circle user_img_msg">
                            </div>
                        </div>
                    @endif
                @endforeach
                </div>

                <div class="card-footer">
                    <div class="input_alignment"></div>
                    <div class="input-group">
                        <input wire:model="inputMessage" wire:keydown.enter="sendMessage" type="text" class="form-control type_msg" placeholder="Escreva sua mensagem..."></input>
                        <a class="input-group-text send_btn" wire:click="sendMessage()"><i class="fas fa-location-arrow"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


