<template>
    <header-view class=""/>
        <section class="team-section ">
            <div class="container-block">
                <div class="container-team ">
                    <div class="container-back">
                        <router-link to="/teams" class="">Назад</router-link>
                    </div>
                    <div class="container-team-title d-flex justify-content-center justify-content-md-between flex-wrap">
                        <h3 class="block-title text-center text-lg-start">{teamEmail}</h3>
                        <div class="container-profile-block-buttons d-flex ">
                            <button class="button-view main-button">Заблокировать на время</button>
                            <button class="button-view secondary-button">Заблокировать навсегда</button>
                        </div>
                    </div>
                    <div class="container-team-content mb-5 d-flex justify-content-center justify-content-lg-start flex-wrap">
    
                        <div class="container-team-image d-flex flex-column">
                            <!-- <img :src="'/assets/img/team.png'" alt=""> -->
                            <img src="https://via.placeholder.com/300" alt="">
    
                        </div>
                        
                        <div class="container-team-about">
                            <h4 class="team-subtitle text-center text-md-start">Участники:</h4>
                            <div class="container-team-members d-flex justify-content-start justify-content-lg-between flex-wrap">


                                <div class="container-team-member d-flex align-items-center">
                                    <div class="container-member-image">
                                        <img src="https://via.placeholder.com/60" alt="" class="member-image">
                                    </div>
                                    <div class="member-name ">Фамилия Имя</div>
                                </div>

                                
                                <div class="container-team-member d-flex align-items-center">
                                    <div class="container-member-image">
                                        <img src="https://via.placeholder.com/60" alt="" class="member-image">
                                    </div>
                                    <div class="member-name ">Фамилия Имя</div>
                                </div>
                                <div class="container-team-member d-flex align-items-center">
                                    <div class="container-member-image">
                                        <img src="https://via.placeholder.com/60" alt="" class="member-image">
                                    </div>
                                    <div class="member-name ">Фамилия Имя</div>
                                </div>


                                <div class="container-team-member d-flex align-items-center">
                                    <div class="container-member-image">
                                        <img src="https://via.placeholder.com/60" alt="" class="member-image">
                                    </div>
                                    <div class="member-name ">Фамилия Имя</div>
                                </div>
                                <div class="container-team-member d-flex align-items-center ">
                                    <div class="container-member-image">
                                        <img src="https://via.placeholder.com/60" alt="" class="member-image">
                                    </div>
                                    <div class="member-name ">Фамилия Имя</div>
                                </div>

                            </div>
                        </div>
                        
                        
                    </div>
                </div>
            </div>
        </section>
    </template>
    
    <script>
    import HeaderView from '@/components/HeaderView.vue';
    export default {
        components: {
            HeaderView
        }
    }
    </script>
    
    <style scoped>
    
    
    
    section{
        display:flex;
        justify-content: center;
        align-items: center;
    }
    
    .container-team {
        margin: 8dvw auto 0;
        width: clamp( 280px , 95% , 1200px);
    }
    

    .block-title {
        margin-bottom: 0;
    }
    .container-team-title{
        gap: clamp(15px, 3vw , 30px);
        margin-bottom: clamp( 20px , 3vw , 40px );

    }
    .container-team-content{
        gap: clamp(  40px , 4vw , 100px)
    }
    
    .team-subtitle {
        font-size:clamp( 20px , 3vw , 30px );
        margin-bottom: clamp( 20px , 3vw , 40px );
        color: var(--color-main);
    
    }
    .container-team-about {
        flex: 1 1 50%;
    }
    .container-team-members{
        display:flex;
        flex-direction: row;
        gap: clamp( 10px , 3vw ,30px)
    }
    
    .container-team-member{
        gap: clamp( 10px , 3vw ,30px);
    }
    
    .member-image{
        width: clamp( 64px , 7vw , 80px);
        border-radius: 50%;
    }

    .member-name {
        font-size: var(--size-text);
    }

    .container-profile-block-buttons{
        gap: clamp(10px, 3vw, 30px);
    }
    .button-view{
        font-size: clamp(12px, 1.5vw, 18px);
    }
    
    </style>