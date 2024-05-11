<template>
    <header>
        <img :src="'/assets/img/header-left.png'" alt="" class="header-left-bg">
        <img :src="'/assets/img/header-right.png'" alt="" class="header-right-bg d-none d-lg-block">
        <div class="container-block">
            <div class="d-flex justify-content-between align-items-center">
                <div class="container-logo">

                    <router-link to="/">
                        
                        <img :src="'/assets/img/logo.svg'"
                            alt="">
                    </router-link>

                </div>

                <nav>
                    <ul class=" d-none d-lg-flex">
                        <li><router-link to="/" class="router-link-underline">Главная</router-link></li>
                        <li><router-link to="/events" class="router-link-underline">Соревнования</router-link></li>
                        <li><router-link to="/teams" class="router-link-underline">Команды</router-link></li>
                    </ul>
                </nav>

                <div class="container-auth  d-none d-lg-flex" v-if="!isAuth">
                    <router-link to="/signIn" class="sign-in-link">Войти</router-link>
                    <router-link to="/signUp">Зарегистрироваться</router-link>

                    <!-- <router-link to="/profile" class="profile-link">example@example.com</router-link>
                    <button class="logout-button">Выйти</button> -->
                </div>
                <div class="container-auth  d-none d-lg-flex align-items-center" v-if="isAuth">
                    <div class="container-notif-info d-flex align-items-center" @click="openModal('notif-modal')">
                        <div class="container-notif-img">
                            <img :src="'/assets/img/notif.png'" alt="" class="notif-img">
                        </div>
                        <div class="container-notif-count">{{ count }}</div>
                    </div>
                    <router-link to="/profile" class="profile-link">{{ $store.getters.getUser.email }}</router-link>
                    <button class="logout-button" @click="logout">Выйти</button>
                </div>
            </div>
        </div>
        <NotifModal :notifications="notifications" @getNotifications="getNotifications"  v-if="showModal && activeModal=='notif-modal'" @close="closeModal" modalId="notif-modal"/>
    </header>





</template>

<script>
import NotifModal from './NotifModal.vue';
import api from '../api.js';
import { push } from 'notivue';
export default {

    components: {
        NotifModal
    },
    data() {
        return {
            notifications: [],
            count: 0,
            showModal: false,
            activeModal: ''
        }
    },

    computed: {
        isAuth() {
            return this.$store.getters.isLoggedIn
        }
    },

    methods: {
        openModal(modalId) {
            this.showModal = true;
            this.activeModal = modalId;
        },
        closeModal() {
            this.showModal = false;
            this.activeModal = '';
        },
        async getNotifications() {
            try{
                const response = await api.get('/notifications');
                this.notifications = response.data.data;
                this.count = response.data.count;
            } catch (error) {
                if (error.status === 401) {
                    this.$store.dispatch('logout')
                    localStorage.removeItem('token');
                    localStorage.removeItem('user');
                    push.error('Срок действия сессии истек. Пожалуйста, войдите в аккаунт еще раз');
                    this.$router.replace('/signIn')

                }
            }
        }, 

        async logout() {
            const notification = push.promise('Выход из аккаунта...')
            try{
                const response = await api.post('/auth/sign-out')
                localStorage.removeItem('token');
                localStorage.removeItem('user');
                this.$store.dispatch('logout')
                this.$router.replace('/')
            } catch (error) {
                localStorage.removeItem('token');
                localStorage.removeItem('user');
                this.$store.dispatch('logout')
                this.$router.replace('/')
            } finally {
                notification.resolve('Вы вышли из аккаунта')
            }
        } 

    },

    created() {
        if (this.isAuth) {
            this.getNotifications();
            
        }
    }
}
</script>
<style scoped>

ul {
    list-style: none;
    display: flex;
    gap: 50px;
    
}

a:any-link {
    font-size: var(--size-text);

}
header{
    position: relative;
    padding: 40px 0;
}

.header-left-bg {
    position: absolute;
    top: 0;
    left: 0;
    z-index: -1;
}
.header-right-bg {
    position: absolute;
    top: 0;
    right: 0;
    z-index: -1;
}
.container-auth {
    color: var(--color-main);
    display: flex;
    gap: 20px;
}
.sign-in-link {
    border: 2px solid var(--color-main);
    padding: 0px 14px;
    border-radius: 5px;
}

.router-link-active.profile-link {
    border-bottom: 1px solid var(--color-main);
}

.profile-link:hover{
    border-bottom: 1px solid var(--color-main);
}

.container-notif-info{
    gap: 10px;
    font-size: var(--size-text);
    cursor: pointer;
}
.logout-button {
    padding: 0;
    border: none;
    background: none;
    font-size: var(--size-text);
    color: var(--color-main);
}
</style>