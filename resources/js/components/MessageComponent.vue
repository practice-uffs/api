<template>
    <div>
    <div class="chat-ctn" v-for="message in msgs">
        <div v-if="message.source == 'aura'" class="d-flex justify-content-start received-message mb-4">
            <div class="msg-container">
                {{ message.message }}
                <!-- <div v-if="user.token != 'null' && user.consent == '1'"> -->
                    <div v-if="message.assessed == '2'" class="d-flex justify-content-between">
                        <button class="rate-icon" @click="rateMessage(0)"> 
                            <img src="/img/aura/doubt.png" class="rate-icon" alt="Mensagem sem sentido">
                        </button>
                        <button class="rate-icon" @click="rateMessage(1)">
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
    props: ['message'],
    
    data() {
        return {
            msgs: this.message
        };
    },
    
    methods: {
        rateMessage(rate) {
            this.$emit('update:showheader', !this.showheader);
            //wire:click="assessAnswer('{{$message['category']}}','{{$message['userMessage']}}',0,{{$message['id']}})
        }
    },
};
</script>
           