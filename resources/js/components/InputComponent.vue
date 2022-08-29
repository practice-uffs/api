<template>
    <div class="input-bar">
        <div class="input-group">
            <input v-model="inputMessage" name="message" @keyup.enter="sendMessage" type="text" class="form-control text-input" placeholder="O que deseja saber?" @focus="changeHeaderDisplay()" @blur="changeHeaderDisplay()" :disabled="disabled"/>
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

            this.messages.push({id: this.messages.length + 1, message: this.inputMessage, source: "user", assessed: 2})

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
                var auraAnswer = "";

                if (data.answer != undefined) {
                    auraAnswer = data.answer;
                } else {
                    auraAnswer = "Não tenho resposta para isso.";
                }

                this.messages.push({id: this.messages.length + 1, message: auraAnswer, source: "aura", assessed: 2})

                this.disabled =  false;

            }).catch((error) => {
                if (error.response.status == 401) {
                    this.messages.push({
                        id: this.messages.length + 1, 
                        message: "Para poder conversar comigo você precisa estar autenticado(a). Por favor autentique-se:", 
                        source: "aura", 
                        assessed: 2
                    })
                    // Mostrar o formulário de login
                } else if(error.response.status == 500) {
                    this.messages.push({
                        id: this.messages.length + 1, 
                        message: "Algo de errado está acontecendo com meus servidores, bip bop.", 
                        source: "aura", 
                        assessed: 2
                    })
                }
                this.disabled =  false;
            });

            this.inputMessage = "";
        },
    },
};
</script>