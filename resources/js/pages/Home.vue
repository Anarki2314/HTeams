<template>
    <header-view/>
    <div class="main-bg-container main-right-bg-container">

        <img :src=" '/assets/img/main-right-bg.png'" alt="" class="main-right-bg">
    </div>

    <div class="main-bg-container main-left-bg-container">
    <img :src=" '/assets/img/main-left-bg.png'" alt="" class="main-left-bg">

    </div>
     

    <section class="title-section">
        <div class="container-block">

            <div class="container-title d-flex justify-content-center justify-content-lg-between align-items-center">
                <div class="container-title-left text-center text-lg-start">

                    <h1 class="title-text">HTeams</h1>
                    <p class="title-description">Объединяйся в команды для удобной работы</p>
                </div>
                
                <div class="container-title-right d-none d-lg-block">
                    <img :src="'/assets/img/main-title.png'" alt="" class="title-img">
                </div>
            </div>
        </div>
    </section>

    <section class="stats-section">
        <div class="container-block">
            <div class="container-stats">
                <h3 class="block-title text-center text-lg-start">Статистика</h3>
                <div class="container-stats-content d-flex justify-content-center justify-content-lg-between align-items-center flex-wrap text-center text-lg-start">

                    <div class="container-stats-left">
                        <div class="container-stats-text d-flex align-items-center"><span class="stats-number">{{ users }}</span> <span class="stats-description">{{ this.declOfNum(users, ['участник', 'участника', 'участников'])}}</span> </div>
                        <div class="container-stats-text d-flex align-items-center"><span class="stats-number">{{ teams }}</span> <span class="stats-description">{{ this.declOfNum(teams, ['команда', 'команды', 'команд']) }}</span> </div>
                        <div class="container-stats-text d-flex align-items-center"><span class="stats-number">{{ events }}</span> <span class="stats-description">{{this.declOfNum(events, ['соревнование', 'соревнования', 'соревнований'])}}</span> </div>
                    </div>
                    
                    <div class="container-stats-right d-flex align-items-center justify-content-center justify-content-lg-between flex-wrap">
                        <div class="container-stats-block">
                            <div class="container-stats-img text-center">
                                <img :src="'/assets/img/main-stats-site.png'" alt="">
                            </div>
                            <p class="stats-description text-center">Первый сайт для объединения команд</p>
                        </div>
                        <div class="container-stats-block">
                            <div class="container-stats-img text-center">
                                <img :src="'/assets/img/main-stats-support.png'" alt="">
                            </div>
                            <p class="stats-description text-center">Оперативная служба поддержки</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="events-section">
        <div class="container-block">
            <div class="container-events-title d-flex justify-content-center justify-content-sm-between align-items-end flex-wrap">
                <h3 class="block-title text-center text-lg-start mb-3">Ближайшие соревнования</h3>
                <router-link to="/events" class="events-link info-button">Все соревнования</router-link>
            </div>

            <div class="container-events d-flex justify-content-center justify-content-lg-around align-items-center flex-wrap">


                <div class="container-events-block" v-for="event in nearEvents" :key="event.id">

                    <router-link :to="'/events/' + event.id" >
                        <div class="container-events-block position-relative">
                            <div class="container-event-img">
                                <img :src="event.image?.path" alt="">
                            </div>
                            <div class="container-event-content">
                                <h4 class="event-title"> {{ event.title }}</h4>
                                
                            </div>
                        </div>
                    </router-link>
                </div>



            </div>
        </div>
    </section>

    <footer-view/>
</template>

<script>
import HeaderView from '@/components/HeaderView.vue';
import FooterView from '@/components/FooterView.vue';

import api from '../api.js';
export default {
    components: {
        HeaderView,
        FooterView
    },

    data() {
        return {
            users: 0,
            teams: 0,
            events: 0,

            nearEvents:[]
        }
    },



    methods: {
        declOfNum(n, titles) {  
            return titles[(n % 10 === 1 && n % 100 !== 11) ? 0 : n % 10 >= 2 && n % 10 <= 4 && (n % 100 < 10 || n % 100 >= 20) ? 1 : 2]
        },
        async getStats() {
            const response = await api.get('/stats');
            this.users = response.data.data.users;
            this.teams = response.data.data.teams;
            this.events = response.data.data.events;
        },

        async getNearEvents() {
            const response = await api.get('/near');
            this.nearEvents = response.data.data;
        }
    },

    created() {
        this.getStats();
        this.getNearEvents();
    }
}
</script>

<style scoped>
header {
    position: absolute;
    top: 0;
    left: 0;
    z-index: 100;
    width: 100%;
}

section{
    min-height: 100dvh;
    display:flex;
    justify-content: center;
    align-items: center;
}

.container-title {
    margin:0 auto;
    gap: 95px;
    width: clamp( 280px , 75%, 1460px);
}

.main-bg-container{
    position: absolute;
    z-index: -1;
}

.main-bg-container img {
    transform: translateY(-50%);
}

.main-right-bg-container{
    right: 0;
    top: 100dvh;
}
.main-left-bg-container{
    left: 0;
    top: 200dvh;
}



.title-text {
    font-weight: 500;
    font-size:clamp( 32px , 8vw , 84px );
    line-height: 77px;
    color: var( --color-main );
}
.title-description{
    max-width: 650px;
    font-size:clamp( 20px , 6vw , 48px );
}
.title-img {
    width: clamp(  280px , 100% , 509px );
}




.stats-number {
    font-size:clamp( 20px , 4.5vw , 72px );
    color: var( --color-main );
}
.container-stats-content{
    gap: clamp( 20px , 5vw , 60px);
}

.stats-description {
    font-size:clamp( 14px , 4vw , 24px );
    font-size:var( --size-text );
}
.container-stats-text{
    gap: clamp(20px, 5vw ,60px);
}
.container-stats-right {
    gap: clamp(20px, 5vw , 100px);
}
.container-stats-block {
    max-width: 280px;
}



.container-events {
    gap: clamp( 20px , 3vw , 40px);
}
.container-events-title {
    margin-bottom: clamp( 20px , 3vw , 40px );
}
.events-section .block-title {
    margin-bottom: 0;
}

.events-link {
    font-size:var( --size-text );
}
.container-event-img img {
    border-radius: 10px;

    width: clamp( 280px , 95vw , 650px);
    aspect-ratio: 1.8/1;
    object-fit: cover;
    filter: brightness(0.5);
}

.container-event-content {
    position: absolute;
    width: 100%;
    height: 100%;
    display:flex;
    align-items: end;
    padding: clamp(10px, 2vw , 25px) clamp(15px, 2vw , 30px);
    font-size: clamp( 16px , 4vw ,30px);
    bottom: 0;
    left: 0;
    z-index: 1;
}
.event-title {
    margin-bottom: 0;
}
</style>