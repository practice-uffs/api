<template>
    <div class="input-bar">
        <div class="input-group">
            <input v-model="inputMessage" name="message" @keyup.enter="sendMessage" type="text" @click="changeHeaderDisplay()" class="form-control text-input" placeholder="O que deseja saber?" />
            <a class="input-group-text send-button" @click="sendMessage"><img src="/img/aura/sendIcon.png" class="send-icon"></a>
        </div>
    </div>
</template>

<script>
export default {
    props: ["messages", "showheader"],

    data() {
        return {
            inputMessage: "",
        };
    },
    methods: {
        changeHeaderDisplay() {
            this.$emit('update:showheader', !this.showheader);
        },  

        sendMessage() {
            //Emit a "messagesent" event including the user who sent the message along with the message content
            this.$emit("messageSent", {
                //newMessage is bound to the earlier "btn-input" input field
                message: this.inputMessage,
            });
            //Clear the input
            this.newMessage = "";
        },
    },
};
</script>