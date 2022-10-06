<template>
    <div class="login-popup" v-if="showlogin">
        <div class="container">
            <p>ID UFFS</p>
            <input v-model="userId" name="userLoginId" @keyup.enter="login()" type="text" class="form-input" placeholder="Digite seu ID UFFS"/>
            <p>Senha</p>
            <input v-model="userPass" name="userPassId" @keyup.enter="login()" :type="this.showPass? 'text':'password'" id="userPassword" class="form-input" placeholder="Escreva sua Senha"/>
            <div class="hide-pass-icon" @click="showPass = !showPass">
                <TransitionGroup name="difuse" tag="div"> 
                    <div key="0" v-if="!showPass"><font-awesome-icon icon="fa-solid fa-eye"/></div>
                    <div key="1" v-if="showPass"><font-awesome-icon icon="fa-solid fa-eye-low-vision"/></div>
                </TransitionGroup>
            </div>
            <div v-if="error" class="error-message">Usuário ou senha inválidos!</div>
            <button class="" @keyup.enter="login()" @click="login()">login</button>
        </div>
    </div>
</template> 

<script>
    export default {
        props: ["showlogin", "usertoken"],

        data() {
            return {
                inputMessage: "",
                userId: "",
                userPass: "",
                showPass: false,
                error: false
            };
        },
        methods: {
            login() {
                if (this.userId == "" || this.userPass ==  "") {
                    this.error = true;
                    return;
                }

                axios({
                    method: "POST",
                    url: "/v0/auth/",
                    headers: {
                    "Authorization": `Bearer ${this.usertoken}`,
                    "Content-Type": "application/json",
                },
                data: {
                    "user": this.userId,
                    "app_id": 1,
                    "password": this.userPass
                }
                }).then((response)=>{
                    this.$emit('update:showlogin', false);
                    this.$emit('update:usertoken', response.data.passport);
                }).catch((e) => {
                    this.error = true;
                    this.$emit('update:showlogin', true);
                });
            }, 
        },
    };
</script>

<style scoped>
p {
    margin: 35px 0 0 20px;
}

.form-input {
    background-color: var(--input-color)!important;
    color: var(--input-font-color)!important;
    border-radius: 85px!important;
    height: 50px!important;
    width: 100%;
    height: calc(1.5em + 0.75rem + 2px);
    padding: 0.375rem 0.75rem;
    font-size: 1rem;
    line-height: 1.5;
    border: 1px solid #ced4da;
}

button {
    padding: 15px;
    color: var(--theme);
    background-color: var(--font-color);
    margin: 14% 0 0 24%;
    border: none;
    border-radius: 40px;
    width: 45%;
}

.error-message {
    color: #f54542;
    text-align: center;
}
</style>

