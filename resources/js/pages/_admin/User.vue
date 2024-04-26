<template>
    <header-view class=""/>
        <section class="profile-section ">
            <div class="container-block">
                <div class="container-profile ">
                    <div class="container-back">
                        <router-link to="/_admin/users" class="">Назад</router-link>
                    </div>
                    <div class="container-profile-title d-flex justify-content-center justify-content-lg-between flex-wrap">
                        <h3 class="block-title text-center text-lg-start">{profileEmail}</h3>
                        <div class="container-profile-block-buttons d-flex ">
                            <button class="button-view main-button">Заблокировать на время</button>
                            <button class="button-view secondary-button">Заблокировать навсегда</button>
                        </div>
                    </div>
                    <div class="container-profile-content mb-5 d-flex justify-content-center justify-content-lg-start flex-wrap">
    
                        <div class="container-profile-image d-flex flex-column">
                            <!-- <img :src="'/assets/img/profile.png'" alt=""> -->
                            <img src="https://via.placeholder.com/200" alt="">
    
                        </div>
                        
                        <div class="container-profile-about">
                            <h4 class="profile-subtitle text-center text-md-start">Личная информация:</h4>
                            <div class="container-profile-items ">
                                <div class="container-profile-item" v-if="!isOrganizer">Имя: <span class="profile-info-text">{name}</span></div>
                                <div class="container-profile-item" v-if="!isOrganizer">Фамилия: <span class="profile-info-text">{surname}</span></div>
                                <div class="container-profile-item" v-if="isOrganizer">Организация: <span class="profile-info-text">{surname}</span></div>
                                <div class="container-profile-item" v-if="isOrganizer">Телефон: <span class="profile-info-text">{surname}</span></div>
    
                                <div class="container-profile-item">Дата регистрации: <span class="profile-info-text">{date}</span></div>
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
                user : {},
                isOrganizer : false
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
    
    .container-profile {
        margin: 8dvw auto 0;
        width: clamp( 280px , 95% , 1200px);
    }

    .container-profile-title {
        margin-bottom: clamp( 20px , 3vw , 40px );
        gap: clamp(10px, 3vw, 30px);
    }

    .container-profile-block-buttons{
        gap: clamp(10px, 3vw, 30px);
    }
    .button-view{
        font-size: clamp(12px, 1.5vw, 18px);
    }
    .block-title {
        margin-bottom: 0;
    }
    
    .container-profile-content{
        gap: clamp(  40px , 4vw , 100px)
    }
    
    
    .container-profile-image{
        gap: 15px;
    }
    
    
    .profile-subtitle {
        font-size:clamp( 20px , 3vw , 30px );
        margin-bottom: clamp( 20px , 3vw , 40px );
        color: var(--color-main);
    
    }
    
    .container-profile-items{
        display:flex;
        flex-direction: column;
        gap: clamp( 10px , 3vw ,30px)
    }
    
    .container-profile-item{
        font-size: var(--size-text);
        display:flex;
        gap: 25px;
        align-items: center;
    }
    
    </style>