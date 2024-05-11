<template>
<loading-screen v-if="contentLoading"/>
<header-view class=""/>

    <section class="profile-section ">
        <div class="container-block">
            <div class="container-profile ">
                <h3 class="block-title text-center text-lg-start">{{ user.email }}</h3>
                <div class="container-profile-content mb-5 d-flex justify-content-center justify-content-lg-between flex-wrap">

                    <div class="container-profile-image d-flex flex-column">
                        <!-- <img :src="'/assets/img/profile.png'" alt=""> -->
                        <img src="https://via.placeholder.com/200" alt="">

                        <div class="profile-image-text">Изменить фото</div>
                        <div class="profile-image-text">Удалить фото</div>
                    </div>
                    
                    <div class="container-profile-about">
                        <h4 class="profile-subtitle text-center text-md-start">Личная информация:</h4>
                        <div class="container-profile-items ">
                            <div class="container-profile-item" v-if="user.isUser">Имя: <span class="profile-info-text">{{ user.name }}</span></div>
                            <div class="container-profile-item" v-if="user.isUser">Фамилия: <span class="profile-info-text">{{ user.surname }}</span></div>
                            <div class="container-profile-item" v-if="user.isOrganizer">Организация: <span class="profile-info-text">{{ user.orgName }}</span></div>

                            <div class="container-profile-item">Телефон: <span class="profile-info-text">{{ user.phone }}</span></div>
                            <div class="container-profile-item">Дата регистрации: <span class="profile-info-text">{{ user.createdAt }}</span></div>
                            <div class="container-profile-item"><span class="info-button">Сменить пароль</span></div>
                            <div class="container-profile-item"><span class="info-button" @click="openModal('modal-delete-profile')">Удалить аккаунт</span></div>
                        </div>
                    </div>
                    <div class="container-profile-stats" v-if="!user.isAdmin">
                    <h4 class="profile-subtitle text-center text-md-start">Статистика:</h4>

                    <div class="container-profile-items ">
                            <div class="container-profile-item" v-if="!user.isOrganizer">Всего соревнований: <span class="profile-info-text">{name}</span></div>
                            <div class="container-profile-item" v-if="user.isOrganizer">Создано соревнований: <span class="profile-info-text">{name}</span></div>
                            <div class="container-profile-item">Предстоящие: <router-link to="/profile/upcoming" class="profile-info-text info-button">{surname}</router-link></div>
                            <div class="container-profile-item">Завершенные: <router-link to="/profile/finished" class="profile-info-text info-button">{phone}</router-link></div>
                            <div class="container-profile-item" v-if="!user.isOrganizer && !user.haveTeam"><span class="info-button" @click="openModal('modal-create-team')">Создать команду</span></div>
                            <div class="container-profile-item" v-if="!user.isOrganizer && !user.haveTeam"><span class="info-button" @click="openModal('modal-join-team')">Вступить в команду</span></div>
                            <div class="container-profile-item" v-if="user.isOrganizer"><router-link to="/profile/create-event" class="info-button">Создать соревнование</router-link></div>
                            <div class="container-profile-item" v-if="!user.isOrganizer && user.haveTeam"><router-link to="/profile/team" class="info-button">Ваша команда</router-link></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <modal v-if="showModal && activeModal === 'modal-create-team'" modalId="modal-create-team" @close="closeModal">
                <h2 class="modal-title">Введите название команды</h2>
                <div class="modal-content">
                    <form @submit.prevent="createTeam">
                    <div class="modal-container-input">
                        <input type="text" placeholder="Название команды" required class="modal-input" v-model="teamname"/>
                    </div>
                    <div class="modal-container-buttons">
                        <button type="button" class="button-view info-button" @click="closeModal">Отмена</button>
                        <div class="modal-container-button">
                            <button class="button-view dark-button" type="submit" v-if
                            =" !isLoading">Создать</button>
                            <div class="loading" :class="{ 'd-none': !isLoading }"><img :src="'/assets/img/loading.svg'" alt=""></div>
                        </div>
                    </div>
                    </form>
                </div>
        </modal>
        <modal v-if="showModal && activeModal === 'modal-join-team'" modalId="modal-join-team" @close="closeModal">
                <h2 class="modal-title">Введите код команды</h2>
                <div class="modal-content">
                    <form @submit.prevent="joinTeam">
                    <div class="modal-container-input">
                        <input type="text" placeholder="Код команды" required class="modal-input" v-model="inviteCode"/>
                    </div>
                    <div class="modal-container-buttons">
                        <button type="button" class="button-view info-button" @click="closeModal">Отмена</button>
                        <div class="modal-container-button">
                            <button class="button-view dark-button" type="submit" v-if
                            =" !isLoading">Отправить</button>
                            <div class="loading" :class="{ 'd-none': !isLoading }"><img :src="'/assets/img/loading.svg'" alt=""></div>
                        </div>


                    </div>
                    </form>
                </div>
        </modal>
        <modal v-if="showModal && activeModal === 'modal-delete-profile'" modalId="modal-delete-profile" @close="closeModal">
                <h2 class="modal-title">Вы уверены, что хотите удалить свой аккаунт?</h2>
                <div class="modal-content">
                    <form @submit.prevent="deleteProfile">
                    <div class="modal-container-buttons">
                        <button type="button" class="button-view info-button" @click="closeModal">Отмена</button>
                        <div class="modal-container-button">
                            <button class="button-view dark-button" type="submit" v-if
                            =" !isLoading">Удалить</button>
                            <div class="loading" :class="{ 'd-none': !isLoading }"><img :src="'/assets/img/loading.svg'" alt=""></div>
                        </div>


                    </div>
                    </form>
                </div>
        </modal>
    </section>
</template>

<script>
import HeaderView from '@/components/HeaderView.vue';
import Modal from '@/components/Modal.vue';
import LoadingScreen from '@/components/LoadingScreen.vue';
import api from '../../api.js';
import {push} from 'notivue';
export default {
    components: {
        HeaderView,
        Modal,
        LoadingScreen
    },
    data() {
        return {

            user : {},
            teamname: '',
            showModal: false,
            activeModal: '',
            inviteCode: '',
            isLoading: false,
            contentLoading: true,
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

        async createTeam() {
            this.isLoading = true;

            try {
                const response = await api.post('/profile/create-team', {title: this.teamname});
                this.user = response.data.data.user;
                this.closeModal();
                push.success(response.data.message);
            } catch (error) {
                push.error(error.data.message);
            } finally {
                this.isLoading = false;
            }

        },

        async joinTeam() {
            this.isLoading = true;
            try {
                const response = await api.post('/profile/join-team', {invite_code: this.inviteCode});
                this.closeModal();
                push.success(response.data.message);
            } catch (error) {
                push.error(error.data.message);
            } finally {
                this.isLoading = false;
            }
        },

        async getProfile() {
            this.contentLoading = true;
            try {
            const response = await api.get('/profile/');
            this.user = response.data.data.user;
        } catch (error) {
        } finally {
            this.contentLoading = false;
        }
        },

        async deleteProfile() {
            this.isLoading = true;
            try {
                const response = await api.delete('/profile/');
                this.closeModal();
                push.success(response.data.message);
                this.$store.dispatch('logout');
                localStorage.removeItem('token');
                localStorage.removeItem('user');
                this.$router.push('/');
            } catch (error) {
                push.error(error.data.message);
            } finally {
                this.isLoading = false;
            }
        }
    },

    created() {
        this.getProfile();

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

.container-profile-content{
    gap: clamp(  40px , 4vw , 100px)
}


.container-profile-image{
    gap: 15px;
}

.profile-image-text {
    font-size: var(--size-subtext);
    cursor: pointer;
    width: fit-content;
    color: #505050;
    border-bottom: #505050 1px solid;
}


.profile-subtitle {
    font-size:clamp( 20px , 3vw , 30px );
    margin-bottom: clamp( 20px , 3vw , 40px );
    color: var(--color-main);

}

.container-profile-items{
    display:flex;
    flex-direction: column;
    gap: clamp( 10px , 3vw ,30px);
    max-width: 450px;

}

.container-profile-item{
    font-size: var(--size-text);
    display:flex;
    gap: 25px;
    align-items: center;
}


</style>