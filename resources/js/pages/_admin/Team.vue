<template>
    <LoadingScreen v-if="contentLoading" />
    <header-view class="" />
    <section class="team-section ">
        <div class="container-block">
            <div class="container-team ">
                <div class="container-back">
                    <a @click="$router.go(-1)" class="router-link-underline">Назад</a>
                </div>
                <div class="container-team-title d-flex justify-content-center justify-content-md-between flex-wrap">
                    <h3 class="block-title text-center text-lg-start">{{ team.title }}</h3>
                    <div class="container-profile-block-buttons d-flex ">
                        <button class="button-view main-button" @click="openModal('modal-unban-users')" v-if="team.isBanned">Разблокировать пользователей</button>
                        <button class="button-view main-button" @click="openModal('modal-ban-users')" v-else>Заблокировать пользователей</button>
                    </div>
                </div>
                <div
                    class="container-team-content mb-5 d-flex justify-content-center justify-content-lg-start flex-wrap">

                    <div
                        class="container-team-content mb-5 d-flex justify-content-center justify-content-lg-start flex-wrap">
                        <div class="container-team-image d-flex flex-column">
                            <img :src="team.leader?.avatar.path" alt="" />
                        </div>

                        <div class="container-team-about">
                            <h4 class="team-subtitle text-center text-md-start">
                                Участники:
                            </h4>
                            <div
                                class="container-team-members justify-content-start justify-content-lg-between flex-wrap">
                                <team-member-card v-for="member in team.members" :key="member" :member="member"
                                    type="admin" />
                            </div>
                        </div>
                    </div>


                </div>
            </div>
        </div>
    </section>

    <modal v-if="showModal && activeModal === 'modal-ban-users'" :show="showModal" @close="closeModal">
        <h2 class="modal-title">Укажите время и причину блокировки</h2>
        <div class="modal-content">
            <form @submit.prevent="banUsers">
                <div class="modal-container-input">
                    <input type="text" placeholder="Время блокировки в днях" v-mask="['#', '##', '###']"  required class="modal-input"
                        v-model="banTime" />
                </div>
                <div class="modal-container-input">
                    <input type="text" placeholder="Причина" maxlength="32" required class="modal-input"
                        v-model="reason" />
                </div>
                <div class="modal-container-buttons">
                    <button type="button" class="button-view info-button" @click="closeModal">Отмена</button>
                    <div class="modal-container-button">
                        <button class="button-view dark-button" v-if="!isLoading" type="submit">Заблокировать</button>
                        <div class="loading" :class="{ 'd-none': !isLoading }"><img :src="'/assets/img/loading.svg'" alt=""></div>
                    </div>


                </div>
            </form>
        </div>
    </modal>

    <modal v-if="showModal && activeModal === 'modal-unban-users'" :show="showModal" @close="closeModal">
        <h2 class="modal-title">Разблокировать пользователей?</h2>
        <div class="modal-content">
            <form @submit.prevent="unbanUsers">
                <div class="modal-container-buttons">
                    <button type="button" class="button-view info-button" @click="closeModal">Отмена</button>
                    <div class="modal-container-button">
                        <button class="button-view dark-button" v-if="!isLoading" type="submit"> Разблокировать</button>
                        <div class="loading" :class="{ 'd-none': !isLoading }"><img :src="'/assets/img/loading.svg'" alt=""></div>
                    </div>
                </div>
            </form>
        </div>
    </modal>
</template>

<script>
import HeaderView from '@/components/HeaderView.vue';
import Modal from '@/components/Modal.vue';
import TeamMemberCard from '@/components/team/TeamMemberCard.vue';
import LoadingScreen from '../../components/LoadingScreen.vue';
import { push } from 'notivue';
import { mask } from 'vue-the-mask'

import { useVuelidate } from '@vuelidate/core'
import { required, helpers, minValue, maxValue, maxLength } from '@vuelidate/validators';
import api from '../../api';
export default {
    components: {
        HeaderView,
        TeamMemberCard,
        Modal,
        LoadingScreen

    },

    setup() {
        return { v$: useVuelidate() }
    },
    directives: { mask },
    data() {
        return {
            isLoading: false,
            contentLoading: true,
            team: {},
            isFull: false,
            showModal: false,
            activeModal: '',

            banTime: '',
            reason: ''
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
        async getTeam() {

            this.contentLoading = true;
            try {
                const response = await api.get('/teams/' + this.$route.params.teamId);
                this.team = response.data.data;
                this.isFull = this.team.members.length == 5;
            } catch (error) {
                // Page not found
            } finally {
                this.contentLoading = false;
            }
        },

        async banUsers() {
            const isFormCorrect = await this.v$.$validate();
            if (!isFormCorrect) {
                this.v$.$errors.forEach((error) => {
                    push.error(error.$message);
                })
                return
            }
            try {
                this.isLoading = true;
                const response = await api.post(`/admin/users/${this.$route.params.teamId}/ban-by-team`, {
                    banTime: this.banTime,
                    reason: this.reason
                });
                push.success(response.data.message);
                this.closeModal();
                this.getTeam();
            } catch (error) {
                push.error(error.data.message);
            } finally {
                this.isLoading = false;
            }
        },

        async unbanUsers() {
            try {
                this.isLoading = true;
                const response = await api.post(`/admin/users/${this.$route.params.teamId}/unban-by-team`);
                push.success(response.data.message);
                this.closeModal();
                this.getTeam();
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
        this.getTeam();
    }
}
</script>

<style scoped>
section {
    display: flex;
    justify-content: center;
    align-items: center;
}

.container-team {
    margin: 8dvw auto 0;
    width: clamp(280px, 95%, 1200px);
}


.block-title {
    margin-bottom: 0;
}

.container-team-title {
    gap: clamp(15px, 3vw, 30px);
    margin-bottom: clamp(20px, 3vw, 40px);

}

.container-team-content {
    gap: clamp(40px, 4vw, 100px)
}

.team-subtitle {
    font-size: clamp(20px, 3vw, 30px);
    margin-bottom: clamp(20px, 3vw, 40px);
    color: var(--color-main);

}

.container-team-about {
    flex: 1 1 50%;
}

.container-team-members {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap:clamp(10px, 3vw, 30px) clamp(20px, 4.5vw,80px) ;
}

.member-image {
    width: clamp(64px, 7vw, 80px);
    border-radius: 50%;
}

.member-name {
    font-size: var(--size-text);
}

.container-profile-block-buttons {
    gap: clamp(10px, 3vw, 30px);
}

.button-view {
    font-size: clamp(12px, 1.5vw, 18px);
}
</style>