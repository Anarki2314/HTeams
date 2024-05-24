<template>
    <loading-screen v-if="contentLoading" />
    <header-view class="" />
    <section class="team-section">
        <div class="container-block">
            <div class="container-team">
                <div class="container-back">
                    <a @click="$router.go(-1)" class="router-link-underline">Назад</a>
                </div>
                <div class="container-team-title d-flex justify-content-center justify-content-md-between flex-wrap">
                    <h3 class="block-title text-center text-lg-start">
                        {{ team.title }}
                    </h3>
                    <div class="container-team-apply-button">
                        <div class="button-view secondary-button" v-if="!isFull && !haveTeam && (isUser || !isAuth)"
                            @click="joinTeam">
                            Подать заявку на вступление
                        </div>
                    </div>
                </div>
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
                            class="container-team-members  justify-content-start justify-content-lg-between flex-wrap">
                            <team-member-card v-for="member in team.members" :key="member" :member="member" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <modal v-if="showModal && activeModal === 'modal-auth' && !isAuth" :show="showModal" @close="closeModal">
        <h2 class="modal-title">Чтобы подать заявку на вступление, войдите в аккаунт</h2>
        <div class="modal-content">
            <div class="modal-container-buttons">
                <button type="button" class="button-view info-button" @click="closeModal">Отмена</button>
                <div class="modal-container-button">
                    <router-link to="/signIn" class="button-view dark-button">Войти</router-link>
                </div>


            </div>
        </div>
    </modal>
</template>

<script>
import HeaderView from '@/components/HeaderView.vue';
import LoadingScreen from '@/components/LoadingScreen.vue';
import TeamMemberCard from '@/components/team/TeamMemberCard.vue';
import Modal from '@/components/Modal.vue';

import api from '../../api.js';
import { push } from 'notivue';
export default {
    components: {
        HeaderView,
        LoadingScreen,
        TeamMemberCard,
        Modal
    },

    data() {
        return {
            contentLoading: true,
            team: {},
            isFull: false,
            showModal: false,
            activeModal: '',
        }
    },

    computed: {

        isAuth() {
            return this.$store.getters.isLoggedIn
        },

        isUser() {
            return this.$store.getters.isUser
        },

        haveTeam() {
            return this.$store.getters.haveTeam
        },
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

        async joinTeam() {
            if (!this.isAuth) {
                this.openModal('modal-auth');
                return

            }
            try {
                const response = await api.post('/teams/join', { team_id: this.team.id });
                this.closeModal();
                push.success(response.data.message);
            } catch (error) {
                push.error(error.data.message);
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
    gap: clamp(40px, 4vw, 100px);
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
</style>
