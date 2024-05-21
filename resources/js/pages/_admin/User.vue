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
                        <button class="button-view main-button" @click="openModal('modal-unban-user')" v-if="user.isBanned">Разблокировать</button>
                        <button class="button-view main-button" @click="openModal('modal-ban-user')" v-else>Заблокировать</button>
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

                            <div class="container-profile-item">Дата регистрации: <span class="profile-info-text">{{user.created_at }}</span></div>
                            <div class="container-profile-item" v-if="user.isBanned">Причина блокировки: <span class="profile-info-text">{{ user.ban?.reason }}</span></div>
                            <div class="container-profile-item" v-if="user.isBanned">Блокировка до: <span class="profile-info-text">{{ user.ban?.expires_at }}</span></div>

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
                    <input type="text" placeholder="Время блокировки в днях" v-mask="['#', '##', '###']" required
                        class="modal-input" v-model="banTime" />
                </div>
                <div class="modal-container-input">
                    <input type="text" placeholder="Причина" maxlength="32" required class="modal-input"
                        v-model="reason" />
                </div>
                <div class="modal-container-buttons">
                    <button type="button" class="button-view info-button" @click="closeModal">Отмена</button>
                    <div class="modal-container-button">
                        <button class="button-view dark-button" v-if="!isLoading" type="submit"> Заблокировать</button>
                        <div class="loading" :class="{ 'd-none': !isLoading }"><img :src="'/assets/img/loading.svg'"
                                alt=""></div>
                    </div>


                </div>
            </form>
        </div>
    </modal>
    <modal v-if="showModal && activeModal === 'modal-unban-user'" :show="showModal" @close="closeModal">
        <h2 class="modal-title">Разблокировать пользователя?</h2>
        <div class="modal-content">
            <form @submit.prevent="unbanUser">
                <div class="modal-container-buttons">
                    <button type="button" class="button-view info-button" @click="closeModal">Отмена</button>
                    <div class="modal-container-button">
                        <button class="button-view dark-button" v-if="!isLoading" type="submit"> Разблокировать</button>
                        <div class="loading" :class="{ 'd-none': !isLoading }"><img :src="'/assets/img/loading.svg'"
                                alt=""></div>
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
import { push } from 'notivue';
import { mask } from 'vue-the-mask'

import { useVuelidate } from '@vuelidate/core'
import { required, helpers, minValue, maxValue, maxLength } from '@vuelidate/validators';
import api from '../../api.js';
export default {
    components: {
        HeaderView,
        LoadingScreen,
        Modal
    },
    setup() {
        return { v$: useVuelidate() }
    },
    directives: { mask },

    data() {
        return {
            user: {

            },
            contentLoading: true,
            isLoading: false,
            showModal: false,
            activeModal: null,

            banTime: '',
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
        },

        async banUser() {
            const isFormCorrect = await this.v$.$validate();
            if (!isFormCorrect) {
                this.v$.$errors.forEach((error) => {
                    push.error(error.$message);
                })
                return
            }
            try {
                this.isLoading = true;
                const response = await api.post(`/admin/users/${this.$route.params.userId}/ban`, {
                    banTime: this.banTime,
                    reason: this.reason
                });
                push.success(response.data.message);
                this.closeModal();
                this.getUser();
            } catch (error) {
                push.error(error.data.message);
            } finally {
                this.isLoading = false;
            }
        },

        async unbanUser() {
            try {
                this.isLoading = true;
                const response = await api.post(`/admin/users/${this.$route.params.userId}/unban`);
                push.success(response.data.message);
                this.closeModal();
                this.getUser();
            } catch (error) {
                push.error(error.data.message);
            } finally {
                this.isLoading = false;
            }
        }
    },

    validations() {
        return {
            banTime: {
                required: helpers.withMessage('Необходимо указать время бана', required),
                minValue: helpers.withMessage('Минимальное время бана 1 день', minValue(1)),
                maxValue: helpers.withMessage('Максимальное время бана 365 дней', maxValue(365)),
            },
            reason: {
                required: helpers.withMessage('Поле обязательно для заполнения', required),
                regex: helpers.withMessage('Причина бана может содержать только буквы, цифры и пробелы', helpers.regex(/^[а-яА-ЯёЁa-zA-Z0-9\s]+$/)),
                maxLength: helpers.withMessage('Максимальная длина причины бана 32 символа', maxLength(32)),
            },
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
    gap: clamp(15px, 3vw, 30px);
    margin-bottom: clamp(20px, 3vw, 40px);
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