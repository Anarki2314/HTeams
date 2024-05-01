<template>
    <header-view class=""/>
        <section class="team-section ">
            <div class="container-block">
                <div class="container-team ">
                    <div class="container-back">
                        <a @click="$router.go(-1)" class="router-link-underline">Назад</a>
                    </div>
                    <div class="container-team-title d-flex justify-content-center justify-content-md-between flex-wrap">
                        <h3 class="block-title text-center text-lg-start">{teamEmail}</h3>
                        <div class="container-team-apply-button">

                            <div class="button-view secondary-button">Подать заявку на вступление</div>
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
        },
        data() {
            return {


            }
        },
        methods: {
            
        },
        mounted() {
            if (!this.$store.getters.haveTeam){
                this.$store.dispatch('setMessage', {
                    type : 'error',
                    text : 'Вы не состоите ни в одной команде'
                })
                this.$router.push('/profile/')
            }

            try {

            } catch (error) {
            }
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
    
    </style>