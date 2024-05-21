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
                        <button class="button-view main-button"
                            @click="openModal('modal-ban-team')">Заблокировать</button>
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
                                class="container-team-members d-flex justify-content-start justify-content-lg-between flex-wrap">
                                <team-member-card v-for="member in team.members" :key="member" :member="member"
                                    type="admin" />
                            </div>
                        </div>
                    </div>


                </div>
            </div>
        </div>
    </section>

    <modal v-if="showModal && activeModal === 'modal-ban-team'" :show="showModal" @close="closeModal">
        <h2 class="modal-title">Укажите время и причину блокировки</h2>
        <div class="modal-content">
            <form @submit.prevent="banTeam">
                <div class="modal-container-input">
                    <input type="text" placeholder="Время блокировки в днях" v-mask="['#', '##', '###']"  required class="modal-input"
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
import Modal from '@/components/Modal.vue';
import TeamMemberCard from '@/components/team/TeamMemberCard.vue';
import LoadingScreen from '../../components/LoadingScreen.vue';
import { push } from 'notivue';
import { mask } from 'vue-the-mask'
import api from '../../api';
export default {
    components: {
        HeaderView,
        TeamMemberCard,
        Modal,
        LoadingScreen

    },

    directives: { mask },
    data() {
        return {
            contentLoading: true,
            team: {},
            isFull: false,
            showModal: false,
            activeModal: '',

            banDate: '',
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
    display: flex;
    flex-direction: row;
    gap: clamp(10px, 3vw, 30px)
}

.container-team-member {
    gap: clamp(10px, 3vw, 30px);
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