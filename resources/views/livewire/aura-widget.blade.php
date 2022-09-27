<div id="app" class="container-fluid h-100" >
        <div class="row justify-content-center h-100">
                <div class="chat w-100 h-100"  >
                    <div class="card chat-background border-radius-0" >
                    
                    <header-component :usertoken.sync="userToken" :showconsentpop.sync="showConsentPopup" :showheader.sync="showHeader"></header-component>

                    <login-component :showlogin.sync="showLogin"></login-component>
                    <consent-component @userallow="userAllowUseOfData()" @userdeny="userDenyUseOfData()" :usertoken.sync="userToken" :showconsentpop.sync="showConsentPopup"></consent-component>

                    <div id="chat-body" class="chat-body msg_card_body" onscroll="handleHeader()">
                        <message-component :usertoken.sync="userToken" :message.sync="messages"></message-component>
                        {{-- <div wire:loading.delay wire:target="sendMessage" class="aura_typing text-secondary">
                            Aura está digitando... achar algum substituto em Vue
                        </div>  --}} 
                    </div>
                    <input-component :datauseallowed.sync='dataUseAllowed' :usertoken.sync="userToken" :messages.sync="messages" :showheader.sync="showHeader"></input-component>
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