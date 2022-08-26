<template>
    <div class="input-bar">
        <div class="input-group">
            <input v-model="inputMessage" name="message" @keyup.enter="sendMessage" type="text" class="form-control text-input" placeholder="O que deseja saber?" @focus="changeHeaderDisplay()" @blur="changeHeaderDisplay()"/>
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
            if (window.innerWidth < 449)
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