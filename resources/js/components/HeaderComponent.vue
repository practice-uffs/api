<template>
    <Transition name="fade">
        <div id="header" style="" v-show="showheader" class="header">
            <div class="chat-header">
                <img src="/img/aura/aura_icon.png" class="logo">
                <div class="aura-info">
                    <p class="chat-header-text">Assistente virtual</p>
                    <p class="chat-header-text">AURA</p>
                </div>
            </div>
        </div>
    </Transition>
</template>



<script>
export default {
    props: ['showheader'],
    
    data() {
        return {
            showHeader: this.showheader,
        };
    },

     mounted() {
    }
}
</script>
