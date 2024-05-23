<template>
    
    <header-view/>

    <admin-nav/>
    
    <section class="teams-section admin-section">
        <div class="container-block">
            <div class="container-teams admin-container">

                <SearchForm class="mb-3 text-end" v-model="query['filter[title]']" @search="getTeams" />
                <div class="container-teams-title d-flex align-items-center justify-content-between">
                    <h3 class="block-title text-center text-lg-start">Команды</h3>
                    <div class="container-filters">
                        <button class="filters-open-btn info-button" @click="toggleModal('modal-filters')">Фильтры</button>
                        <filters-modal v-if="showModal && activeModal === 'modal-filters'" :show="showModal" @close="closeModal">
                            <ul class="container-filters-items">
                                <li class="container-filters-item">
                                    <label class="filters-label">
                                        <input type="radio" name="isFull" class="filters-checkbox" value="" :checked="query['filter[isFull]'] == ''" v-model="filterIsFull">
                                        <span class="filters-radio-label">Все</span>
                                    </label>
                                    
                                
                                </li>
                                <li class="container-filters-item">
                                    <label class="filters-label">
                                        <input type="radio" name="isFull" class="filters-checkbox" value="true" :checked="query['filter[isFull]'] == 'true'" v-model="filterIsFull">
                                        <span class="filters-radio-label">Полная</span>
                                    </label>
                                    
                                
                                </li>
                                <li class="container-filters-item">
                                    <label class="filters-label">
                                        <input type="radio" name="isFull" class="filters-checkbox" value="false" :checked="query['filter[isFull]'] == 'false'" v-model="filterIsFull">
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
                    <loading-screen v-if="contentLoading" position="absolute"/>
                    <team-card v-for="team, index in teams" :key="index" :team="team" @openModal="openModal" @join="joinTeam"/>

                    <button class="container-pagination row-card d-flex justify-content-center "@click="loadNextPage" v-if="nextPage">

                            <span class="pagination-btn" v-if="!pageLoading">Показать еще</span>
                            <img :src="'/assets/img/loading.svg'" alt="" v-if="pageLoading" class="pagination-loading">
                    </button>
                </div>
            </div>
            </div>
    </section>

    <footer-view/>
</template>

<script>
import HeaderView from '@/components/HeaderView.vue';
import AdminNav from '../../components/_admin/AdminNav.vue';
import TeamCard from '../../components/_admin/TeamCard.vue';
import SearchForm from '../../components/SearchForm.vue';
import FiltersModal from '../../components/FiltersModal.vue';
import FooterView from '@/components/FooterView.vue';
import LoadingScreen from '../../components/LoadingScreen.vue';

import api from '../../api';
export default {
    components: {
        HeaderView,
        AdminNav,
        TeamCard,
        FooterView,
        SearchForm,
        FiltersModal,
        LoadingScreen
    },
    data() {
        return {
            query: {
                "filter[title]": '',
                "filter[isFull]": '',
                perPage: 10,
                ...this.$route.query
            },
            page: 1,

            filterIsFull: this.$route.query['filter[isFull]'] ?? '',
            nextPage: null,
            contentLoading: true,
            pageLoading: false,
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
                this.query['filter[isFull]'] = this.filterIsFull;
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
            this.filterIsFull = '';
            this.getTeams();
            this.closeModal();
        },

    },


    created() {
        this.getTeams();
    }
}
</script>


<style scoped>




.admin-container {
        width: clamp( 280px , 95% , 1200px);
        margin: 0 auto;
    }
    .container-teams-title {
    margin-bottom: clamp( 20px , 3vw , 40px );
    }
    .block-title {
        margin-bottom: 0;
    }

    .search-btn {
        position: absolute;
        top: 50%;
        right: 10px;
        background: transparent;
        border: none;
        transform: translateY(-50%);
    }

    .filters-btn {
        background: none;
    }

    .container-teams-items{
        min-height: 80dvh;
        gap: clamp(  40px , 4vw , 100px);
    }

</style>