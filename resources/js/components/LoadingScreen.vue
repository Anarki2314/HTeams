<template>

        <div id="loading-screen" :class="`position-${position}`">

            <img :src="'/assets/img/loading.svg'" alt="loading image">
        </div>
    
</template>

<script>
export default {
    name: "LoadingScreen",
    props: {
        position: {
            type: String,
            default: 'fixed'
        }
    }
}
</script>


<style lang="scss" scoped>

    #loading-screen {
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: 100;
        background-color: #1B1B1B;
        display: flex;
        align-items: center;
        justify-content: center;
        img {
            width: clamp(64px, 10vw, 128px);
        }
    }
</style>