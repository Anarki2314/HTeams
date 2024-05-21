<template>
    <loading-screen v-if="contentLoading" />
    <header-view class="" />
    <section class="profile-section ">
        <div class="container-block">
            <div class="container-profile ">
                <div class="container-back">
                    <a @click="$router.go(-1)" class="router-link-underline">Назад</a>
                </div>
                <div class="container-user-title d-flex justify-content-center justify-content-md-between flex-wrap">
                    <h3 class="block-title text-center text-lg-start">{{ user.email }}</h3>
                    <div class="container-profile-block-buttons d-flex ">
                        <button class="button-view main-button"
                            @click="openModal('modal-ban-user')">Заблокировать</button>
                    </div>
                </div>
                <div
                    class="container-profile-content mb-5 d-flex justify-content-center justify-content-lg-start flex-wrap">

                    <div class="container-profile-image d-flex flex-column">
                        <img :src="user.avatar?.path" alt="">

                    </div>

                    <div class="container-profile-about">
                        <h4 class="profile-subtitle text-center text-md-start">Личная информация:</h4>
                        <div class="container-profile-items ">
                            <div class="container-profile-item" v-if="user.isUser">Имя: <span
                                    class="profile-info-text">{{ user.name }}</span></div>
                            <div class="container-profile-item" v-if="user.isUser">Фамилия: <span
                                    class="profile-info-text">{{ user.surname }}</span></div>
                            <div class="container-profile-item" v-if="user.isOrganizer">Организация: <span
                                    class="profile-info-text">{{ user.orgName }}</span></div>
                            <div class="container-profile-item" v-if="user.isOrganizer">Телефон: <span
                                    class="profile-info-text">{{ user.phone }}</span></div>

                            <div class="container-profile-item">Дата регистрации: <span class="profile-info-text">{{
        user.created_at }}</span></div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>

    <modal v-if="showModal && activeModal === 'modal-ban-user'" :show="showModal" @close="closeModal">
        <h2 class="modal-title">Укажите время и причину блокировки</h2>
        <div class="modal-content">
            <form @submit.prevent="banUser">
                <div class="modal-container-input">
                    <input type="text" placeholder="Время блокировки в днях" v-mask="['#', '##', '###']" required class="modal-input"
                        v-model="banDate" />
                </div>
                <div class="modal-container-input">
                    <input type="text" placeholder="Причина" maxlength="32" required class="modal-input"
                        v-model="reason" />
                </div>
                <div class="modal-container-buttons">
                    <button type="button" class="button-view info-button" @click="closeModal">Отмена</button>
                    <div class="modal-container-button">
                        <button to="/signIn" class="button-view dark-button">Заблокировать</button>
                    </div>


                </div>
            </form>
        </div>
    </modal>
</template>

<script>
import HeaderView from '@/components/HeaderView.vue';
import LoadingScreen from '../../components/LoadingScreen.vue';
import Modal from '../../components/Modal.vue';
import api from '../../api.js';
import { push } from 'notivue';
import { mask } from 'vue-the-mask'
export default {
    components: {
        HeaderView,
        LoadingScreen,
        Modal
    },
    directives: { mask },

    data() {
        return {
            user: {

            },
            contentLoading: true,
            showModal: false,
            activeModal: null,

            banDate: '',
            reason: '',

        }
    },

    methods: {
        closeModal() {
            this.showModal = false;
            this.activeModal = '';
        },

        openModal(modalId) {
            this.showModal = true;
            this.activeModal = modalId;
        },
        async getUser() {
            this.contentLoading = true;
            try {
                const response = await api.get(`/admin/users/${this.$route.params.userId}`);
                this.user = response.data.data;
            } catch (error) {
                console.log(error);
            } finally {
                this.contentLoading = false;
            }
        }
    },

    created() {
        this.getUser();
    },
}
</script>

<style scoped>
section {
    display: flex;
    justify-content: center;
    align-items: center;
}

.container-profile {
    margin: 8dvw auto 0;
    width: clamp(280px, 95%, 1200px);
}


.container-user-title {
    gap: clamp(15px, 3vw , 30px);
    margin-bottom: clamp( 20px , 3vw , 40px );
}

.container-profile-block-buttons {
    gap: clamp(10px, 3vw, 30px);
}

.button-view {
    font-size: clamp(12px, 1.5vw, 18px);
}

.block-title {
    margin-bottom: 0;
}

.container-profile-content {
    gap: clamp(40px, 4vw, 100px)
}


.container-profile-image {
    gap: 15px;
}


.profile-subtitle {
    font-size: clamp(20px, 3vw, 30px);
    margin-bottom: clamp(20px, 3vw, 40px);
    color: var(--color-main);

}

.container-profile-items {
    display: flex;
    flex-direction: column;
    gap: clamp(10px, 3vw, 30px)
}

.container-profile-item {
    font-size: var(--size-text);
    display: flex;
    gap: 25px;
    align-items: center;
}
</style>