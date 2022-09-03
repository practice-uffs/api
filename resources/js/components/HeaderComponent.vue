<template>
    <Transition name="fade">
        <div id="header" style="" v-show="showheader" class="header">
            <div class="chat-header">
                <img src="/img/aura/aura_icon.png" class="logo">
                <div class="aura-info">
                    <p class="chat-header-text">Assistente virtual</p>
                    <p class="chat-header-text">AURA</p>
                    <div style="align-self: center;" v-if="usertoken != 'null'">
                        <div v-show="showMenu" class="menu-options"><div @click="consentPopup()" >Sobre meu consentimento</div></div>
                        <font-awesome-icon class="cons-icon" @click="openMenu()" icon="fa-solid fa-chevron-up"/>
                    </div>
                </div>
            </div>
        </div>
    </Transition>
</template>



<script>
export default {
    props: ['showheader', 'usertoken', 'showconsentpop'],
    
    data() {
        return {
            showMenu: false,
            showHeader: this.showheader,
            showConsentPopup: this.showconsentpop,
        };
    },

    methods: {
        consentPopup() {
            console.log(this.showConsentPopup);
            this.$emit('update:showconsentpop', !this.showconsentpop);
        },

        openMenu() {
            this.showMenu = !this.showMenu;
            let icon = document.querySelector('.cons-icon');
            if (this.showMenu) 
                icon.style.transform = "rotate(90deg)";
            else { 
                icon.style.transform = "rotate(270deg)" ; 
                this.$emit('update:showconsentpop', false);
            }
        }
    },
}
</script>
