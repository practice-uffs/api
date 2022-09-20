<template>
    <Transition name="fade">
        <div id="header" v-show="showheader" class="header">
            <div class="chat-header">
                <img src="/img/aura/aura_icon.png" class="logo">
                <div class="aura-info">
                    <p class="chat-header-text">Assistente virtual</p>
                    <p class="chat-header-text">AURA</p>
                    <div class="header-menu" v-if="usertoken != 'null'">
                        <TransitionGroup name="difuse" tag="div"> 
                            <div key="0" v-if="showMenu" class="menu-options"><div @click="consentPopup()" style="color:var(--font-color)">Sobre meu consentimento</div></div>
                            <div key="1" v-if="!menuOpened"><font-awesome-icon @click="openMenu()" icon="fa-solid fa-ellipsis-vertical"/></div>
                            <div key="2" v-if="menuOpened"><font-awesome-icon @click="openMenu()" icon="fa-solid fa-xmark"/></div> 
                        </TransitionGroup>
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
            menuOpened: false,
            showHeader: this.showheader,
            showConsentPopup: this.showconsentpop,
        };
    },

    methods: {
        consentPopup() {
            this.$emit('update:showconsentpop', !this.showconsentpop);
        },

        openMenu() {
            this.showMenu = !this.showMenu;
            this.menuOpened = !this.menuOpened;
        }
    },
}
</script>
