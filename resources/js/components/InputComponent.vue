<template>
    <div class="input-bar">
        <div class="input-group">
            <input v-model="inputMessage" autocomplete="off" name="message" @keyup.enter="sendMessage" type="text" class="form-control text-input" placeholder="O que deseja saber?" @focus="changeHeaderDisplay()" @blur="changeHeaderDisplay()" :disabled="disabled"/>
            <a class="input-group-text send-button" @click="sendMessage"><img src="/img/aura/sendIcon.png" class="send-icon"></a>
        </div>
    </div>
</template>

<script>
export default {
    props: ["messages", "showheader", "usertoken"],

    data() {
        return {
            inputMessage: "",
            userToken: this.usertoken,
            disabled:  false,
        };
    },
    methods: {
        changeHeaderDisplay() {
            if (window.innerWidth < 449)
              this.$emit('update:showheader', !this.showheader);
        },  

        sendMessage() {
            if (this.inputMessage == ""){
                return;
            }
            this.disabled =  true;

            let userMessage = {id: this.messages.length + 1, message: this.inputMessage, source: "user"};

            this.messages.push(userMessage);
            this.saveMessage(userMessage);

            var auraAnswer = {
                id: this.messages.length + 1,
                message: null,
                source: "aura",
                assessed: 2,
                userMessage: this.inputMessage,
                category: null
            };

            var encodedMessage = encodeURIComponent(this.inputMessage);
            var requestUrl = "/v0/aura/nlp/domain/" + encodedMessage

            axios({
                method: "GET",
                url: requestUrl,
                headers: {
                    Authorization: `Bearer ${this.userToken}`
                }
            }).then((response) => {
                const data = response.data;

                if (data.answer != undefined) {
                    auraAnswer.message = data.answer;
                    auraAnswer.category = data.intent;
                } else {
                    auraAnswer.message = "Não tenho resposta para isso.";
                    auraAnswer.category = "aura_has_no_response";
                }

                this.messages.push(auraAnswer)
                this.saveMessage(auraAnswer)
                this.disabled =  false;        
            }).catch((error) => {
                if (error.response.status == 401) {
                    auraAnswer.message = "Para poder conversar comigo você precisa estar autenticado(a). Por favor autentique-se:";
                    auraAnswer.category = "user_not_authenticated";
                    // Mostrar o formulário de login
                } else if(error.response.status == 500) {
                    auraAnswer.message = "Algo de errado está acontecendo com meus servidores, bip bop.";
                    auraAnswer.category = "aura_could_not_respond";
                }
                this.messages.push(auraAnswer)
                this.saveMessage(auraAnswer)
                this.disabled =  false;
            });
 
            this.inputMessage = "";
        },
        saveMessage(message) {
            axios({
                method: "POST",
                url: "/v0/aura/chat/history/add-message",
                headers: {
                    Authorization: `Bearer ${this.userToken}`
                },
                data: {
                    "message": message
                }
            });
        }
    },
};
</script>