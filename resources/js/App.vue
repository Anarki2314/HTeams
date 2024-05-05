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

::-webkit-scrollbar-track{
    background: #1B1B1B;
}

::-webkit-scrollbar-thumb{
    background: #DEAC35;
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

.container-search {
    position: relative;
}

#search {
    background: transparent;
    padding: 7px 45px 7px 10px;
    border: 1px solid var(--color-main);
    border-radius: 5px;
    width: clamp(280px, 95%, 400px);
    color: #f4f4f4;
    font-size: var(--size-text);
}

#search::placeholder {
    color: #313131;
}

.search-btn {
    position: absolute;
    top: 50%;
    right: 10px;
    background: transparent;
    border: none;
    transform: translateY(-50%);
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
    cursor: pointer;
    outline: none;
    transition: all .3s ease;
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
.notif-button {
    padding: clamp(2px, 1.5vw, 5px) clamp(5px, 1.5vw, 20px);
    color: #f4f4f4;
    font-size: clamp(12px, 1.5vw, 18px);
    cursor: pointer;
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
.dark-outline-button{
    border: 2px solid #1B1B1B;
    color: #1B1B1B;
}

.dark-outline-button:hover{
    background-color: rgb(27, 27, 27, 0.7);
    color: #f4f4f4;
}

.dark-outline-button:focus-visible{
    color: #f4f4f4;
    background-color: rgb(27, 27, 27, 0.7);
}

.dark-outline-button:active{
    color: #f4f4f4;
    background-color: rgb(27, 27, 27, 0.8);
}

</style>