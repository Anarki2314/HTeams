<template>

    <div class="wrapper">

        <router-view/>
    </div>
    <Notivue v-slot="item">
    <NotivueSwipe :item="item">
        <Notification :item="item" :theme="pastelTheme" >
            <NotificationProgress :item="item"/>
        
        </Notification>
    </NotivueSwipe>
</Notivue>
</template>

<script>

import {Notivue, Notification, NotificationProgress, NotivueSwipe, pastelTheme}  from 'notivue';
export default {
    components: {
        Notivue,
        Notification,
        NotificationProgress,
        NotivueSwipe
    },

    data() {
        return {
            pastelTheme
        }
    }
}
</script>

<style lang="scss">

@import url("../sass/app.scss");
:root{
    --color-main: #DEAC35;
    --size-title: clamp(24px, 3vw, 48px);
    --size-text: clamp(12px, 1.5vw, 24px);
    --size-subtext: clamp(12px, 1.5vw, 18px);
}

@font-face {
    font-family: "Montserrat Bold";
    src: url( "../fonts/Montserrat-Bold.ttf" );
}

@font-face {
    font-family: "Montserrat Regular";
    src: url("../fonts/Montserrat-Regular.ttf");
}
* {
    font-family: "Montserrat Bold";
}
html {
    scroll-behavior: smooth;
}
body {
    background-color: #1B1B1B !important;
    color: #f4f4f4 !important;
}
section {
    position: relative;
}
img{
    max-width: 100%;
}
a:any-link {
    color: inherit;
    text-decoration: none;
    cursor: pointer;
}

h1, h2, h3, h4, h5, h6 {
    margin: 0;
    padding: 0;

}
p {
    margin-bottom: clamp( 20px , 3vw , 30px );
}

::-webkit-scrollbar-track{
    background: #1B1B1B;
}

::-webkit-scrollbar-thumb{
    background: var(--color-main);
}
::-webkit-scrollbar{
    width: 5px;
    height: 10px;

}
.router-link-underline:hover{
    border-bottom: #f4f4f4 1px solid ;
}
.router-link-active{
    border-bottom: #f4f4f4 1px solid ;
}
ul {
    list-style: none;
    margin: 0;
    padding: 0;
}

.wrapper {
    max-width: 1920px;
    margin:0 auto;
    min-height: 100dvh;
}

.container-block{
    width:clamp(280px, 90dvw, 1600px);
    margin: 0 auto;

}

.block-title {
    font-size: var(--size-title);
    color: var(--color-main);
    margin-bottom: clamp( 20px , 3vw , 40px );
}


.container-back {
    margin-bottom: clamp(20px, 3vw, 40px);
    font-size: var(--size-text);
}
.container-back a{
    color: #f4f4f4;
    cursor: pointer;
    text-decoration: none;
}



.container-filters{
    position: relative;
}
.filters-open-btn {
        background: none;
    }

.container-page {
    overflow: auto;
    height: 90dvh;
}
.empty-page {
    font-size: var(--size-text);
    margin:0 auto;
}

.container-pagination {
    background-color: #313131;
    position: relative;
    cursor: pointer;
    outline: none;
    transition: all .3s ease;
    display: flex;
}

.container-pagination:hover, .container-pagination:focus, .container-pagination:active {
    background-color: rgb(49, 49, 49, 0.8);
    border-color: rgb(49, 49, 49, 0.8);
    
}

.pagination-btn {
    color: #f4f4f4;
    outline: none;
    font-size: var(--size-text);
    border: none;
    cursor: pointer;
    background-color: transparent;
    display: flex;
    align-items: center;
    justify-content: center;
    height: 100%;
    width: 100%;
}
.pagination-loading {
    width: clamp(24px, 3vw, 40px);
}



.row-card {
    height: fit-content;
    display:flex;
    flex-wrap: wrap;
    width: 100%;
    align-items: center;
    justify-content: space-between;
    padding: clamp(10px, 1.5vw, 20px) clamp(20px, 3vw, 40px);
    border: #313131 2px solid;
    gap: clamp(15px, 1.5vw, 20px);
    border-radius: 5px;
}
.row-card-image {
    width: clamp(48px, 5vw, 80px);
    border-radius: 50%;
}

.row-card-container-name {
    display: flex;
    align-items: center;
    gap: clamp(10px, 3vw, 60px);
}

.row-card-name {
    font-size: var(--size-text);
}

.notif-row-card {
    height: fit-content;
    display:flex;
    flex-wrap: wrap;
    width: 100%;
    align-items: center;
    justify-content: space-between;
    padding: clamp(5px, 1.5vw, 10px) clamp(15px, 3vw, 30px);
    border: #1B1B1B 2px solid;
    gap: clamp(10px, 1.5vw, 15px);
    border-radius: 5px;
}
.notif-row-card-image {
    width: clamp(48px, 5vw, 80px);
    border-radius: 50%;
}

.notif-row-card-container-name {
    display: flex;
    align-items: center;
    gap: clamp(10px, 3vw, 30px);
}

.notif-row-card-name {
    font-size: clamp(12px, 1.5vw, 20px);
    width: clamp(280px, 100%, 400px);
}

.notif-row-card-container-buttons{
    gap: clamp(10px, 3vw, 20px);
}

/** Buttons */
@import url("@sass/buttons.scss");

/** PrimeVue */

@import url("@sass/primevue.scss");

</style>