<template>
    <div v-show="showconsentpop" class="consent-popup consent-card">
        Descrever um pouco sobre o que é guardado de cada usuário e o que acontece caso opte por recurar o armazenamento.
        <br>Você aceita que alguns dos seus dados sejam armazenados?
        <div style="display: flex;justify-content: space-around; margin-top:50px">
            <button class="accept" @click="handleConsent(1)">Permitir</button>
            <button class="deny" @click="handleConsent(0)">Negar</button>
        </div>
    </div>
</template>

<script>
export default {  
    props: ['showconsentpop', 'usertoken'],
    
    data() {
        return {
            showConsentPopup: this.showconsentpop,
        };
    },

    methods: {
        handleConsent(consentStatus) {
            this.$emit('update:showconsentpop', !this.showconsentpop);

            axios({
                method: "POST",
                url: "/v0/aura/chat/consent-status",
                headers: {
                    "Authorization": `Bearer ${this.usertoken}`,
                    "Content-Type": "application/json",
                },
                data: {
                    "consent_status": consentStatus,
                }
            }).then(() => {
                if (consentStatus) {
                    this.$emit('userallow');
                } else {
                    this.$emit('userdeny');
                }
            }).catch((e) => {
                console.log(e.response)
            });
        },
    },
}
</script>

<style>
    .consent-card {
        display: flex;
    }
    .accept {
        background-color: var(--sucess-color);
    }
    .deny {
        background-color: var(--warning-color);
    }
    button {
        padding: 15px;
        color: var(--theme);
        background-color: var(--font-color);
        border: none;
        border-radius: 40px;
        width: 45%;
    }
</style>