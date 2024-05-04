<template>
    <header-view/>
    <section class="teams-section">
        <div class="container-block">
            <div class="container-teams">
                <div class="form-search mb-3 text-end ">
                    <div class="container-search">
                        <input type="text" class="form-input" name="search" id="search" placeholder="Поиск" v-model.lazy="query['filter[search]']">
                        <button class="search-btn"> <img :src="'/assets/img/search.svg'" alt=""> </button>
                        {{ query }}
                    </div>
                </div>
                <div class="container-teams-title d-flex align-items-center justify-content-between">
                    <h3 class="block-title text-center text-lg-start">Команды</h3>
                    <div class="container-filters">
                        <button class="filters-btn info-button" @click="toggleModal('modal-filters')">Фильтры</button>
                        <filters-modal v-if="showModal && activeModal === 'modal-filters'" :show="showModal" @close="closeModal">
                            <ul class="container-filters-items">
                                <li>
                                    <label class="filters-label">
                                        <input type="checkbox" class="filters-checkbox">
                                        <span class="filters-checkbox-label">Полная</span>
                                    </label>
                                    
                                
                                </li>
                                <li>
                                    <label class="filters-label">
                                        <input type="checkbox" class="filters-checkbox">
                                        <span class="filters-checkbox-label">Не полная</span>
                                    </label>
                                    
                                
                                </li>
                            </ul>
                    </filters-modal>
                    </div>
                </div>
                <div class="container-sort"></div>
                <div class="container-teams-items  d-flex flex-column flex-wrap">
                    <loading-screen v-if="contentLoading"/>
                    <team-card v-for="team, index in teams" :key="index" :team="team"/>

                </div>
            </div>
        </div>
    </section>
    <footer-view/>
</template>

<script>
import HeaderView from '@/components/HeaderView.vue';
import FooterView from '@/components/FooterView.vue'
import EventCard from '@/components/event/EventCard.vue';
import TeamCard from '@/components/team/TeamCard.vue';
import LoadingScreen from '@/components/LoadingScreen.vue';
import FiltersModal from '@/components/FiltersModal.vue';

import api from '../api.js';
export default {
    components: {
        HeaderView,
        FooterView,
        EventCard,
        TeamCard,
        LoadingScreen,
        FiltersModal
    },
    data() {
        return {
            query: {
                "filter[search]": '',
                sort: '',
                page: 1,
                perPage: 5,
                ...this.$route.query
            },
            contentLoading: true,
            search: '',
            teams: [],

            showModal: false,
            activeModal: '',
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
        async getTeams() {
            this.contentLoading = true;
            try {
                const response = await api.get('/teams?' + new URLSearchParams(this.query).toString());
                this.teams = response.data.data;
            } catch (error) {

            } finally {
                this.contentLoading = false;
            }
        }
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

    

    .filters-btn {
        background: none;
    }

    .container-teams-items{
        position: relative;
        min-height: 80dvh;
        gap: clamp(  40px , 4vw , 100px);
    }

    

</style>