<template>
    <loading-screen v-if="contentLoading"/> 
    <header-view class=""/>
        <section class="team-section ">
            <div class="container-block">
                <div class="container-team ">
                    <div class="container-back">
                        <a @click="$router.go(-1)" class="router-link-underline">Назад</a>
                    </div>
                    <div class="container-team-title d-flex justify-content-center justify-content-md-between flex-wrap">
                        <h3 class="block-title text-center text-lg-start">{{ team.title }}</h3>
                        <div class="container-team-title-buttons d-flex">
                            <button class="button-view secondary-button" @click="openModal('modal-delete-team')" v-if="user.isLeader">Удалить команду</button>
                            <button class="button-view secondary-button" @click="openModal('modal-leave-team')" v-if="!user.isLeader">Покинуть команду</button>
                        </div>
                    </div>
                    <div class="container-team-content mb-5 d-flex justify-content-center justify-content-lg-between flex-wrap">
    
                        <div class="container-team-image d-flex flex-column">
                            <!-- <img :src="'/assets/img/team.png'" alt=""> -->
                            <img src="https://via.placeholder.com/300" alt="">
    
                        </div>
                        
                        <div class="container-team-about">
                            <h4 class="team-subtitle text-center text-md-start">Участники:</h4>
                            <div class="container-team-members d-flex justify-content-start justify-content-lg-between flex-wrap flex-column">

                                <team-member-card v-for="member in team.members" :key="member.id" :member="member"/>

                            </div>
                        </div>

                        <div class="container-team-stats">
                            <h4 class="team-subtitle text-center text-md-start">Статистика:</h4>
                            
                            <div class="container-team-items">
                                <div class="container-team-item">
                                    Код приглашения: <span>{{ team.inviteCode }}</span>
                                </div>
                                <div class="container-team-item" v-if="user.isLeader"> 
                                <router-link to="/profile/team/invites" class="info-button" >
                                        Заявки на вступление  
                                </router-link>
                                </div>
                                <div class="container-team-item"><span to="/profile/team" class="info-button" @click="openModal('modal-invite-team')" v-if="!isTeamFull && user.isLeader">Пригласить в команду</span></div>
                                <div class="container-team-item" v-if="user.isLeader"><span to="/profile/team" class="info-button" >Удалить команду</span></div>
                            </div>

                        </div>
                        
                        
                    </div>
                </div>
            </div>
            <modal v-if="showModal && activeModal === 'modal-invite-team'" modalId="modal-invite-team" @close="closeModal">
                <h2 class="modal-title">Введите пользователя</h2>
                <div class="modal-content">
                    <form @submit.prevent="inviteFromTeam">
                    <div class="modal-container-input">
                        <input type="text" placeholder="email" required class="modal-input" v-model="email"/>
                    </div>
                    <div class="modal-container-buttons">
                        <button type="button" class="button-view info-button" @click="closeModal">Отмена</button>
                        <div class="modal-container-button">
                            <button class="button-view dark-button" type="submit" v-if
                            =" !isLoading" >Отправить</button>
                            <div class="loading" :class="{ 'd-none': !isLoading }"><img :src="'/assets/img/loading.svg'" alt=""></div>
                        </div>
                    </div>
                    </form>
                </div>
        </modal>
            <modal v-if="showModal && activeModal === 'modal-delete-team'" modalId="modal-delete-team" @close="closeModal">
                <h2 class="modal-title">Вы уверены, что хотите удалить команду?</h2>
                <div class="modal-content">
                    <form @submit.prevent="deleteTeam">
                    <div class="modal-container-buttons">
                        <button type="button" class="button-view info-button" @click="closeModal">Отмена</button>
                        <div class="modal-container-button">
                            <button class="button-view dark-button" type="submit" v-if
                            =" !isLoading" >Удалить</button>
                            <div class="loading" :class="{ 'd-none': !isLoading }"><img :src="'/assets/img/loading.svg'" alt=""></div>
                        </div>
                    </div>
                    </form>
                </div>
        </modal>
            <modal v-if="showModal && activeModal === 'modal-leave-team'" modalId="modal-leave-team" @close="closeModal">
                <h2 class="modal-title">Вы уверены, что хотите покинуть команду?</h2>
                <div class="modal-content">
                    <form @submit.prevent="leaveTeam">
                    <div class="modal-container-buttons">
                        <button type="button" class="button-view info-button" @click="closeModal">Отмена</button>
                        <div class="modal-container-button">
                            <button class="button-view dark-button" type="submit" v-if
                            =" !isLoading" >Покинуть</button>
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
    import TeamMemberCard from '@/components/team/TeamMemberCard.vue';
    import Modal from '@/components/Modal.vue';
    import LoadingScreen from '@/components/LoadingScreen.vue';

    import api from '../../api.js';
    import {push} from 'notivue';
    export default {
        components: {
            HeaderView,
            TeamMemberCard,
            Modal,
            LoadingScreen
        },
        data() {
            return {
                user : this.$store.getters.getUser,
                team: {
                    title: '',
                    inviteCode: '',
                    members: {}
                },

                showModal: false,
                activeModal: '',

                email: '',

                isLoading: false,
                contentLoading: true,

            }
        },

        methods: {
            async getTeam() {
                this.contentLoading = true;
                try {
                    const response = await api.get('/profile/team');
                    this.team = response.data.data.team;
                } catch (error) {
                        
                    } finally {
                        this.contentLoading = false;
                    }
            },

            openModal(modalId) {
                this.showModal = true;
                this.activeModal = modalId;
            },

            closeModal() {
                this.showModal = false;
                this.activeModal = '';
            },

            async inviteFromTeam() {
                this.isLoading = true;
                try {
                    const response = await api.post('/profile/team/invite', {email: this.email});
                    this.closeModal();
                    this.email = '';
                    push.success(response.data.message);
                } catch (error) {
                    push.error(error.data.message);
                } finally {
                    this.isLoading = false;
                }
            },

            async deleteTeam() {
                this.isLoading = true;
                try {
                    const response = await api.delete('/profile/team');
                    this.closeModal();
                    push.success(response.data.message);
                    this.$router.push('/profile/');
                } catch (error) {
                    push.error(error.data.message);
                } finally {
                    this.isLoading = false;
                }
            },

            async leaveTeam() {
                this.isLoading = true;
                try {
                    const response = await api.delete('/profile/team/leave');
                    this.closeModal();
                    push.success(response.data.message);
                    this.$router.push('/profile/');
                } catch (error) {
                    push.error(error.data.message);
                } finally {
                    this.isLoading = false;
                }
            }
        },
        async created() {
            if (!this.$store.getters.haveTeam){
                push.info('Вы не состоите в команде');
                this.$router.push('/profile/')
            }

            await this.getTeam();
        },
        computed: {
            isTeamFull() {
                return this.team.members.length >= 5 ;
            }

        },
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
    
    .container-team-about, .container-team-stats{
    flex: 1;        
    }

    .block-title {
        margin-bottom: 0;
    }
    .container-team-title{
        gap: clamp(15px, 3vw , 30px);
        margin-bottom: clamp( 20px , 3vw , 40px );

    }

    .container-team-title-buttons{
        gap: clamp( 15px , 3vw , 30px);
    }
    .container-team-content{
        gap: clamp(  40px , 4vw , 100px)
    }
    
    .team-subtitle {
        font-size:clamp( 20px , 3vw , 30px );
        margin-bottom: clamp( 20px , 3vw , 40px );
        color: var(--color-main);
    
    }
    .container-team-members{
        display:flex;
        gap: clamp( 10px , 3vw ,30px)
    }
    
    
.container-team-items{
    display:flex;
    flex-direction: column;
    gap: clamp( 10px , 3vw ,30px);
    max-width: 450px;

}

.container-team-item{
    font-size: var(--size-text);
    display:flex;
    gap: 25px;
    align-items: center;
}
    
    </style>