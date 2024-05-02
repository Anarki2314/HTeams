<template>

    <div class="wrapper">

        <router-view v-slot="{ Component, route }">
            <div :key="route.name">
                <Component :is="Component" />
            </div>
        </router-view>
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

<style>


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
    background-color: #1B1B1B;
    color: #f4f4f4;
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
.router-link-underline:hover{
    border-bottom: #f4f4f4 1px solid ;
}
.router-link-active{
    border-bottom: #f4f4f4 1px solid ;
}
ul {
    margin: 0;
    padding: 0;
}

.wrapper {
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

.info-button{
    color: #f4f4f4;
    outline: none;
    font-size: var(--size-text);
    border: none;
    border-bottom: #f4f4f4 1px solid ;
    cursor: pointer;
}

.button-view.info-button:hover{
    text-decoration: underline;
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

.button-view {
    padding: clamp(5px, 1.5vw, 10px) clamp(15px, 3vw, 40px);
    background:none;
    outline: none;
    border: none;
    color: #f4f4f4;
    font-size: var(--size-text);
    cursor: pointer;
    border-radius: 5px;
    text-align: center;
    transition: all 0.3s ease;
}
.main-button, .main-button:any-link{
    background: none;
    border: 2px solid var(--color-main);
    color: var(--color-main);
}
.main-button:hover{
    background-color: var(--color-main);
    color: #1B1B1B;
}
.main-button:active, .router-link-active.main-button {
    background-color: var(--color-main);

    color: #1B1B1B;
}
.main-button:focus-visible {
    background-color: var(--color-main);
    color: #1B1B1B;

    outline: none;
}
.secondary-button, .secondary-button:any-link{
    background: #313131;
    border: 2px solid #313131;
}
.secondary-button:hover{
    background: rgb(49, 49, 49, 0.7);
}
.secondary-button:active {
    background: rgb(49, 49, 49, 0.8);
}
.secondary-button:focus-visible {
    outline: none;
    background: rgb(49, 49, 49, 0.8);
}

.darken-button, .darken-button:any-link{
    border: 2px solid #313131;
    color: #313131;
}
.darken-button:hover{
    background-color: #313131;
    color: #f4f4f4;
}
.darken-button:focus-visible{
    outline: none;
    background-color: #313131;
    color: #f4f4f4;
}

.darken-button:active{
    background-color: #313131;
    color: #f4f4f4;
}

.dark-button{
    background-color: #1B1B1B;
    border: 2px solid #1B1B1B;
    color: #f4f4f4;
}

.dark-button:hover{
    background-color: rgb(27, 27, 27, 0.7);
}

.dark-button:focus-visible{
    background-color: rgb(27, 27, 27, 0.7);
}

.dark-button:active{
    background-color: rgb(27, 27, 27, 0.8);
}

</style>