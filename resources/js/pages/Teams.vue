<template>
    <header-view/>
    <section class="teams-section">
        <div class="container-block">
            <div class="container-teams">
                <div class="form-search mb-3 text-end ">
                    <div class="container-search">
                        <input type="text" class="form-input" name="search" id="search" placeholder="Поиск" v-model.lazy="query['filter[title]']" @change.lazy="getTeams">
                        <button class="search-btn" @click="getTeams"> <img :src="'/assets/img/search.svg'" alt=""> </button>
                    </div>
                </div>
                <div class="container-teams-title d-flex align-items-center justify-content-between">
                    <h3 class="block-title text-center text-lg-start">Команды</h3>
                    <div class="container-filters">
                        <button class="filters-open-btn info-button" @click="toggleModal('modal-filters')">Фильтры</button>
                        <filters-modal v-if="showModal && activeModal === 'modal-filters'" :show="showModal" @close="closeModal">
                            <ul class="container-filters-items">
                                <li class="container-filters-item">
                                    <label class="filters-label">
                                        <input type="radio" name="isFull" class="filters-checkbox" value="" :checked="query['filter[isFull]'] == ''" v-model="query['filter[isFull]']">
                                        <span class="filters-radio-label">Все</span>
                                    </label>
                                    
                                
                                </li>
                                <li class="container-filters-item">
                                    <label class="filters-label">
                                        <input type="radio" name="isFull" class="filters-checkbox" value="true" :checked="query['filter[isFull]'] == 'true'" v-model="query['filter[isFull]']">
                                        <span class="filters-radio-label">Полная</span>
                                    </label>
                                    
                                
                                </li>
                                <li class="container-filters-item">
                                    <label class="filters-label">
                                        <input type="radio" name="isFull" class="filters-checkbox" value="false" :checked="query['filter[isFull]'] == 'false'" v-model="query['filter[isFull]']">
                                        <span class="filters-radio-label">Не полная</span>
                                    </label>
                                    
                                
                                </li>

                            </ul>
                            <div class="container-filters-buttons">
                                <button class="filters-btn filters-btn-reset" @click="resetFilters">Сбросить</button>
                                <button class="filters-btn filters-btn-apply" @click="getTeams();closeModal()">Применить</button>
                            </div>
                    </filters-modal>
                    </div>
                </div>
                <div class="container-sort"></div>
                <div class="container-teams-items container-page  d-flex flex-column">
                    <div class="container-empty-page row-card" v-if="!teams.length">
                        <div class="empty-page" >Ничего не найдено</div>
                    </div>
                    <loading-screen v-if="contentLoading"/>
                    <team-card v-for="team, index in teams" :key="index" :team="team" @openModal="openModal" @join="joinTeam"/>

                    <button class="container-pagination row-card d-flex justify-content-center "@click="loadNextPage" v-if="nextPage">
                        <span class="pagination-btn"  >Показать еще</span>
                    </button>
                </div>
            </div>
        </div>
    </section>

    <footer-view/>
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
import FooterView from '@/components/FooterView.vue'
import EventCard from '@/components/event/EventCard.vue';
import TeamCard from '@/components/team/TeamCard.vue';
import LoadingScreen from '@/components/LoadingScreen.vue';
import FiltersModal from '@/components/FiltersModal.vue';
import Modal from '@/components/Modal.vue';

import api from '../api.js';
import {push} from 'notivue'
export default {
    components: {
        HeaderView,
        FooterView,
        EventCard,
        TeamCard,
        LoadingScreen,
        FiltersModal,
        Modal
    },
    data() {
        return {
            query: {
                "filter[title]": '',
                "filter[isFull]": '',
                perPage: 1,
                ...this.$route.query
            },
            page: 1,

            nextPage: null,
            contentLoading: true,
            pageLoading: false,
            search: '',
            teams: [],

            showModal: false,
            activeModal: '',
        }
    },
    computed: {
        isAuth() {
            return this.$store.getters.isLoggedIn
        }
    },

    methods: {
        toggleModal(modalId) {
            this.showModal = !this.showModal;
            this.activeModal = modalId;
        },
        closeModal() {
            this.showModal = false;
            this.activeModal = '';
        },

        openModal(modalId) {
            this.showModal = true;
            this.activeModal = modalId;
        },
        async getTeams() {
            this.contentLoading = true;
            try {
                const response = await api.get('/teams?page=' + this.page + '&' + new URLSearchParams(this.query).toString());
                this.teams = response.data.data;
                this.nextPage = (response.data.next_page_url) ? response.data.next_page_url.split('page=')[1] : null;
                this.$router.push({query: {
                    'filter[title]': this.query['filter[title]'],
                    'filter[isFull]': this.query['filter[isFull]'],
                }});
            } catch (error) {

            } finally {
                this.contentLoading = false;
            }
        },


        async loadNextPage() {
            this.pageLoading = true;
            try {
                const response = await api.get('/teams?page=' + this.nextPage + '&' + new URLSearchParams(this.query).toString());
                this.nextPage = (response.data.next_page_url) ? response.data.next_page_url.split('page=')[1] : null;
                this.teams = [...this.teams, ...response.data.data];
            } catch (error) {
                console.log(error);
            } finally {
                this.pageLoading = false;
            }
        },


        resetFilters() {
            this.query['filter[isFull]'] = '';
            this.getTeams();
            this.closeModal();
        },

        async joinTeam(team_id) {

            try {
                const response = await api.post('/teams/join', {team_id: team_id});
                this.closeModal();
                push.success(response.data.message);
            } catch (error) {
                push.error(error.data.message);
            }
        },

    },


    created() {
        this.getTeams();
    }
}
</script>

<style scoped>
    .container-teams {
        width: clamp( 280px , 95% , 1200px);
        margin: 5dvw auto 0;

    }
    .container-teams-title {
    margin-bottom: clamp( 20px , 3vw , 40px );
    }
    .block-title {
        margin-bottom: 0;
    }

    .container-search{
        position: relative;
    }

    



    .container-teams-items{
        position: relative;
        min-height: 80dvh;
        gap: clamp(  40px , 4vw , 100px);
    }

    

</style>