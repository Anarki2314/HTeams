<template>
    <loading-screen v-if="contentLoading" />
    <header-view class="" />

    <section class="profile-section ">
        <div class="container-block">
            <div class="container-profile ">
                <h3 class="block-title text-center text-lg-start">{{ user.email }}</h3>
                <div
                    class="container-profile-content mb-5 d-flex justify-content-center justify-content-lg-start flex-wrap">

                    <div class="container-profile-image d-flex flex-column">
                        <img :src="user.avatar?.path" alt="" class="profile-image">

                        <div class="profile-image-text" @click="openModal('modal-generate-avatar')">Сгенерировать фото
                        </div>
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

                            <div class="container-profile-item">Телефон: <span class="profile-info-text">{{ user.phone
                                    }}</span></div>
                            <div class="container-profile-item">Дата регистрации: <span class="profile-info-text">{{
        user.created_at }}</span></div>
                            <div class="container-profile-item"><span class="info-button"
                                    @click="openModal('modal-change-password')">Сменить пароль</span></div>
                            <div class="container-profile-item" v-if="!user.isAdmin"><span class="info-button"
                                    @click="openModal('modal-update-profile')">Редактировать профиль</span></div>
                            <div class="container-profile-item"><span class="info-button"
                                    @click="openModal('modal-delete-profile')">Удалить аккаунт</span></div>
                        </div>
                    </div>
                    <div class="container-profile-stats" v-if="!user.isAdmin">
                        <h4 class="profile-subtitle text-center text-md-start">Соревнования:</h4>

                        <div class="container-profile-items ">
                            <div class="container-profile-item" v-if="user.isOrganizer"><router-link
                                    to="/profile/moderating-events" class="profile-info-text info-button">На
                                    проверке</router-link></div>
                            <div class="container-profile-item"><router-link to="/profile/upcoming"
                                    class="profile-info-text info-button">Предстоящие</router-link></div>
                            <div class="container-profile-item"><router-link to="/profile/finished"
                                    class="profile-info-text info-button">Завершенные</router-link></div>
                            <div class="container-profile-item" v-if="!user.isOrganizer && !haveTeam"><span
                                    class="info-button" @click="openModal('modal-create-team')">Создать команду</span>
                            </div>
                            <div class="container-profile-item" v-if="!user.isOrganizer && !haveTeam"><span
                                    class="info-button" @click="openModal('modal-join-team')">Вступить в команду</span>
                            </div>
                            <div class="container-profile-item" v-if="user.isOrganizer"><router-link
                                    to="/profile/create-event" class="info-button">Создать соревнование</router-link>
                            </div>
                            <div class="container-profile-item" v-if="!user.isOrganizer && haveTeam"><router-link
                                    to="/profile/team" class="info-button">Ваша команда</router-link></div>
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
                        <input type="text" placeholder="Название команды" required class="modal-input"
                            v-model="teamname" />
                    </div>
                    <div class="modal-container-buttons">
                        <button type="button" class="button-view info-button" @click="closeModal">Отмена</button>
                        <div class="modal-container-button">
                            <button class="button-view dark-button" type="submit" v-if="!isLoading">Создать</button>
                            <div class="loading" :class="{ 'd-none': !isLoading }"><img :src="'/assets/img/loading.svg'"
                                    alt=""></div>
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
                        <input type="text" placeholder="Код команды" v-mask="'XXXXXXXXXX'" required class="modal-input"
                            v-model="inviteCode" />
                    </div>
                    <div class="modal-container-buttons">
                        <button type="button" class="button-view info-button" @click="closeModal">Отмена</button>
                        <div class="modal-container-button">
                            <button class="button-view dark-button" type="submit" v-if="!isLoading">Отправить</button>
                            <div class="loading" :class="{ 'd-none': !isLoading }"><img :src="'/assets/img/loading.svg'"
                                    alt=""></div>
                        </div>


                    </div>
                </form>
            </div>
        </modal>
        <modal v-if="showModal && activeModal === 'modal-change-password'" modalId="modal-change-password"
            @close="closeModal">
            <h2 class="modal-title">Смена пароля</h2>
            <div class="modal-content">
                <form @submit.prevent="changePassword">
                    <div class="modal-container-input">
                        <input type="password" placeholder="Старый пароль" required class="modal-input"
                            v-model="changePsw.old_password" />
                    </div>
                    <div class="modal-container-input">
                        <input type="password" placeholder="Новый пароль" required class="modal-input"
                            v-model="changePsw.new_password" />
                    </div>
                    <div class="modal-container-input">
                        <input type="password" placeholder="Повтор пароля" required class="modal-input"
                            v-model="changePsw.new_password_confirmation" />
                    </div>
                    <div class="modal-container-buttons">
                        <button type="button" class="button-view info-button" @click="closeModal">Отмена</button>
                        <div class="modal-container-button">
                            <button class="button-view dark-button" type="submit" v-if="!isLoading">Изменить</button>
                            <div class="loading" :class="{ 'd-none': !isLoading }"><img :src="'/assets/img/loading.svg'"
                                    alt=""></div>
                        </div>


                    </div>
                </form>
            </div>
        </modal>
        <modal v-if="showModal && activeModal === 'modal-update-profile'" modalId="modal-update-profile"
            @close="closeUpdateModal">
            <h2 class="modal-title">Редактирование профиля</h2>
            <div class="modal-content">
                <form @submit.prevent="updateProfile">
                    <div class="modal-container-input" v-if="user.isUser">
                        <input type="text" placeholder="Имя" required class="modal-input" v-model="updateForm.name" />
                    </div>
                    <div class="modal-container-input" v-if="user.isUser">
                        <input type="text" placeholder="Фамилия" required class="modal-input"
                            v-model="updateForm.surname" />
                    </div>
                    <div class="modal-container-input" v-if="user.isOrganizer">
                        <input type="text" placeholder="Название организации" required class="modal-input"
                            v-model="updateForm.orgName" />
                    </div>
                    <div class="modal-container-buttons">
                        <button type="button" class="button-view info-button" @click="closeUpdateModal">Отмена</button>
                        <div class="modal-container-button">
                            <button class="button-view dark-button" type="submit" v-if="!isLoading">Изменить</button>
                            <div class="loading" :class="{ 'd-none': !isLoading }"><img :src="'/assets/img/loading.svg'"
                                    alt=""></div>
                        </div>


                    </div>
                </form>
            </div>
        </modal>
        <modal v-if="showModal && activeModal === 'modal-delete-profile'" modalId="modal-delete-profile"
            @close="closeModal">
            <h2 class="modal-title">Вы уверены, что хотите удалить свой аккаунт?</h2>
            <div class="modal-content">
                <form @submit.prevent="deleteProfile">
                    <div class="modal-container-buttons">
                        <button type="button" class="button-view info-button" @click="closeModal">Отмена</button>
                        <div class="modal-container-button">
                            <button class="button-view dark-button" type="submit" v-if="!isLoading">Удалить</button>
                            <div class="loading" :class="{ 'd-none': !isLoading }"><img :src="'/assets/img/loading.svg'"
                                    alt=""></div>
                        </div>


                    </div>
                </form>
            </div>
        </modal>
        <modal v-if="showModal && activeModal === 'modal-generate-avatar'" modalId="modal-generate-avatar"
            @close="closeModal">
            <h2 class="modal-title">Сгенерировать новую фотографию?</h2>
            <div class="modal-content">
                <form @submit.prevent="generateNewAvatar">
                    <div class="modal-container-buttons">
                        <button type="button" class="button-view info-button" @click="closeModal">Отмена</button>
                        <div class="modal-container-button">
                            <button class="button-view dark-button" type="submit"
                                v-if="!isLoading">Сгенерировать</button>
                            <div class="loading" :class="{ 'd-none': !isLoading }"><img :src="'/assets/img/loading.svg'"
                                    alt=""></div>
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
import { push } from 'notivue';
import { useVuelidate } from '@vuelidate/core';
import { required, sameAs, minLength, helpers, maxLength } from '@vuelidate/validators'
import { mask } from 'vue-the-mask'
export default {
    components: {
        HeaderView,
        Modal,
        LoadingScreen
    },

    setup() {
        return {
            v$: useVuelidate()
        }
    },
    directives: { mask },

    data() {
        return {

            user: {},
            teamname: '',
            showModal: false,
            activeModal: '',
            inviteCode: '',
            isLoading: false,
            contentLoading: true,

            changePsw: {
                old_password: '',
                new_password: '',
                new_password_confirmation: ''
            },

            updateForm: {
                name: '',
                surname: '',
                orgName: '',
            }
        }
    },
    computed: {
        haveTeam() {
            return this.$store.getters.haveTeam;
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

        closeUpdateModal() {
            this.showModal = false;
            this.activeModal = '';
            this.updateForm.name = this.user.name;
            this.updateForm.surname = this.user.surname;
            this.updateForm.orgName = this.user.orgName;
        },

        async createTeam() {
            this.isLoading = true;

            try {
                const response = await api.post('/profile/create-team', { title: this.teamname });
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
                const response = await api.post('/profile/join-team', { invite_code: this.inviteCode.toUpperCase() });
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
                this.updateForm.name = this.user.name;
                this.updateForm.surname = this.user.surname;
                this.updateForm.orgName = this.user.orgName;
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
        },

        async updateProfile() {
            const isFormCorrect = await this.v$.$validate();
            if (!isFormCorrect) {
                this.v$.$errors.forEach(error => {
                    push.error(error.$message);
                });
                return
            }
            try {
                this.isLoading = true;
                const response = await api.put('/profile/', this.updateForm);
                this.user = response.data.data;
                push.success(response.data.message);
                this.closeUpdateModal();
                this.getProfile();
            } catch (error) {
                push.error(error.data.message);
            } finally {
                this.isLoading = false;
            }



        },

        async generateNewAvatar() {
            this.isLoading = true;
            try {
                const response = await api.post('/profile/generate-avatar');
                this.user.avatar = response.data.data;
                push.success(response.data.message);

                this.closeModal();
            } catch (error) {
                push.error(error.data.message);
            } finally {
                this.isLoading = false;
            }
        },

        async changePassword() {
            const isFormCorrect = await this.v$.$validate();
            if (!isFormCorrect) {
                this.v$.$errors.forEach(error => {
                    push.error(error.$message);
                });
                return
            }
            this.isLoading = true;
            try {

                const response = await api.post('/profile/change-password', this.changePsw);
                push.success(response.data.message);
                this.closeModal();
            } catch (error) {
                push.error(error.data.message);
            } finally {
                this.isLoading = false;
            }
        }
    },

    validations() {
        const rules={}
        if (this.activeModal === 'modal-change-password') {
            rules.changePsw= {
                old_password: {
                    required: helpers.withMessage('Поле `Текущий пароль` обязательно.', required),
                },

                new_password: {
                    required: helpers.withMessage('Поле `Новый пароль` обязательно.', required),
                    regex: helpers.withMessage('Поле `Новый пароль` должен содержать буквы в верхнем и нижнем регистре, цифры.', helpers.regex(/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)./)),
                    minLength: helpers.withMessage('Поле `Новый пароль` должен содержать не менее 8 символов.', minLength(8))
                },
                new_password_confirmation: {
                    required: helpers.withMessage('Поле `Повтор пароля` обязательно.', required),
                    sameAs: helpers.withMessage('Поле `Повтор пароля` должно совпадать с полем `Новый пароль`.', sameAs(this.changePsw.new_password))
                },
            }
        }
        if (this.activeModal === 'modal-update-profile') {
            
            if (this.user.isOrganizer) {
                rules.updateForm ={
                    updateForm:{
                        orgName: {
                    required: helpers.withMessage('Поле `Название организации` обязательно.', required),
                    regex: helpers.withMessage('Поле `Название организации` должно содержать только кириллицу и латиницу.', helpers.regex(/^(?!.*\s{2})[а-яА-ЯёЁA-Za-z\s]+$/)),
                    minLengthValue: helpers.withMessage('Поле `Название организации` должно содержать не менее 2 букв.', minLength(2)),
                    maxLengthValue: helpers.withMessage('Поле `Название организации` должно содержать не более 50 букв.', maxLength(50))
                        }
                }
            }
            } else if (this.user.isUser) {
                 rules.updateForm={
                        name: {
                            required: helpers.withMessage('Поле `Имя` обязательно.', required),
                            regex: helpers.withMessage('Поле `Имя` должно содержать только кириллицу.', helpers.regex(/^[а-яА-ЯёЁ]+$/u)),
                            minLengthValue: helpers.withMessage('Поле `Имя` должно содержать не менее 2 букв.', minLength(2)),
                            maxLengthValue: helpers.withMessage('Поле `Имя` должно содержать не более 32 букв.', maxLength(32)),
                        },

                        surname: {
                            required: helpers.withMessage('Поле `Фамилия` обязательно.', required),
                    regex: helpers.withMessage('Поле `Фамилия` должно содержать только кириллицу.', helpers.regex(/^[а-яА-ЯёЁ]+$/u)),
                    minLengthValue: helpers.withMessage('Поле `Фамилия` должно содержать не менее 2 букв.', minLength(2)),
                    maxLengthValue: helpers.withMessage('Поле `Фамилия` должно содержать не более 32 букв.', maxLength(32)),
                    }
                }
                }
                
        }

        return rules
    },

    created() {
        this.getProfile();

    }
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

.container-profile-content {
    gap: clamp(40px, 4vw, 100px)
}


.container-profile-image {
    gap: 15px;
}

.profile-image {
    width: 200px;
    height: 200px;
    border-radius: 5px;
}

.profile-image-text {
    font-size: var(--size-subtext);
    cursor: pointer;
    width: fit-content;
    color: #505050;
    border-bottom: #505050 1px solid;
}


.profile-subtitle {
    font-size: clamp(20px, 3vw, 30px);
    margin-bottom: clamp(20px, 3vw, 40px);
    color: var(--color-main);

}

.container-profile-items {
    display: flex;
    flex-direction: column;
    gap: clamp(10px, 3vw, 30px);
    max-width: 450px;

}

.container-profile-item {
    font-size: var(--size-text);
    display: flex;

    gap: 10px clamp(10px, 2.5vw, 25px);
    align-items: center;
    flex-wrap: wrap;
}

.profile-info-text {
    text-overflow: ellipsis;
    overflow: hidden;
}
</style>