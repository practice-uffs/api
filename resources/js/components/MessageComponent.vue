<template>
    <div>
        <div class="d-flex justify-content-center mb-4 mt-4" v-if="!loadedHistory">
            <a @click="loadHistory">Carregar histórico de mensagens</a>
        </div>
        <div class="spinner justify-content-center mb-4 mt-4" style="display: none">
            <div class="spinner-border text-secondary" role="status">
                <span class="sr-only">Carregando...</span>
            </div>
        </div>
        <div class="chat-ctn" v-for="message in msgs">
            <div v-if="message.source == 'aura'" class="d-flex justify-content-start received-message mb-4">
                <div class="msg-container">
                    {{ message.message }}
                    <!-- <div v-if="user.token != 'null' && user.consent == '1'"> -->
                        <div v-if="message.assessed == '2'" class="d-flex justify-content-between">
                            <button class="rate-icon" @click="rateMessage(message, 0)"> 
                                <img src="/img/aura/doubt.png" class="rate-icon" alt="Mensagem sem sentido">
                            </button>
                            <button class="rate-icon" @click="rateMessage(message, 1)">
                                <img src="/img/aura/heart.png" class="rate-icon" alt="Apreciar a mensagem">
                            </button>
                        </div>
        
                        <div v-if="message.assessed != '2'" class="d-flex justify-content-end">
                            <small v-if="message.assessed == '1'" class="mr-2 text-success">Mensagem avaliada!</small>
                            <small v-if="message.assessed == '0'" class="mr-2 text-danger">Mensagem avaliada!</small>
                            <small v-if="message.assessed == '-1'" class="mr-2 text-danger">Não foi possível avaliar a resposta.</small>
                        </div>
                    <!--  </div> -->
                    </div>
                </div>
            <div v-if="message.source != 'aura'" class="d-flex justify-content-end mb-4">
                <div class="msg-container-send">
                    {{ message.message }}
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: ['message', 'usertoken'],
    
    data() {
        return {
            msgs: this.message,
            userToken: this.usertoken,
            assessing: false,
            loadedHistory: false
        };
    },
    
    methods: {
        rateMessage(message, rate) {
            if (this.assessing == true) {
                return;
            }

            this.assessing = true;

            axios({
                method: "POST",
                url: "/v0/analytics/",
                headers: {
                    "Authorization": `Bearer ${this.userToken}`,
                    "Content-Type": "application/json",
                },
                data: {
                    "app_id": "1",
                    "action": "aura_feedback",
                    "key": message.category,
                    "value": message.userMessage,
                    "rate": rate,
                }
            }).then(() => {
                this.msgs[message.id - 1].assessed = rate;
                this.assessing = false;
            }).catch(() => {
                this.msgs[message.id - 1].assessed = -1;
                this.assessing = false;
            });

        },
        loadHistory() {
            this.loadedHistory = true;
            this.toggleSpinner();

            axios({
                method: "GET",
                url: "/v0/aura/chat/history",
                headers: {
                    Authorization: `Bearer ${this.userToken}`
                }
            }).then((response) => {
                let data = response.data;                
                this.msgs = data.aura_history.concat(this.msgs);
                this.toggleSpinner();
            }).catch(() => {
                this.msgs.unshift({id: this.msgs.length + 1, message: "Não foi possível carregar seu histórico de mensagens.", source: "aura"});
                this.toggleSpinner();
            });
        },
        toggleSpinner() {
            let spinner = document.getElementsByClassName('spinner')[0];

            if (spinner.style.display === "none") {
                spinner.style.display = "flex";
            } else {
                spinner.style.display = "none";
            }
        }
    }
};
</script>
           