<template>
    <header-view />
    <section class="events-section">
        <div class="container-block">
            <div class="container-events">
                <div class="container-back">
                        <router-link to="/profile" class="router-link-underline">Назад</router-link>
                </div>
            
                <SearchForm @search="getEvents" v-model="query['filter[title]']"/>
                <div class="container-events-title d-flex align-items-center justify-content-between">
                    <h3 class="block-title text-center text-lg-start">Завершенные соревнования</h3>
                    <div class="container-filters">
                        <button class="filters-open-btn info-button" @click="toggleModal('modal-filters')">Фильтры</button>
                        <filters-modal v-if="showModal && activeModal === 'modal-filters'" :show="showModal" @close="closeModal">
                            <div class="container-filter-types">
                                <div class="filter-type" :class="{ 'active': activeFilterType === 'tags' }">
                                    Тэги
                                </div>
                            </div>
                            <ul class="container-filters-items">
                                <li class="container-filters-item"  v-for="tag in tags" :key="tag.id">
                                    <label class="filters-label">

                                        <input type="checkbox" name="tag" class="filters-checkbox" :value="tag.id" v-model="selectedTags">
                                        <span class="filters-checkbox-label">{{ tag.title }}</span>
                                    </label>
                                </li>

                            </ul>
                            <div class="container-filters-buttons">
                                <button type="button" class="filters-btn filters-btn-reset" @click="resetFilters">Сбросить</button>
                                <button type="button" class="filters-btn filters-btn-apply" @click="getEvents();closeModal()">Применить</button>
                            </div>
                    </filters-modal>
                    </div>
                </div>
                <sorting v-model="query['sort']" :sortList="sortList" @update:modelValue="getEvents"/>
                <div class="container-events-items d-flex justify-content-center justify-content-lg-between flex-wrap position-relative">
                    <loading-screen v-if="contentLoading"/>
                    <div class="container-empty-page row-card" v-if="!events.length">
                        <div class="empty-page" >Ничего не найдено</div>
                    </div>
                    <event-card v-for="event, index in events" :key="index" :event="event" :url="urlEvent" />

                    <button class="container-pagination row-card d-flex justify-content-center" @click="loadNextPage"
                        v-if="nextPage">
                        <span class="pagination-btn" v-if="!pageLoading">Показать еще</span>
                        <img :src="'/assets/img/loading.svg'" alt="" v-if="pageLoading" class="pagination-loading" />
                    </button>
                </div>
            </div>
        </div>
    </section>
    <footer-view />
</template>

<script>
import HeaderView from '@/components/HeaderView.vue';
import FooterView from '@/components/FooterView.vue'
import EventCard from '@/components/event/EventCard.vue';
import SearchForm from '../../components/SearchForm.vue';
import FiltersModal from '../../components/FiltersModal.vue';
import LoadingScreen from '../../components/LoadingScreen.vue';
import Sorting from '../../components/Sorting.vue';
import api from '../../api.js';
import {push} from 'notivue'


export default {
    components: {
        HeaderView,
        FooterView,
        EventCard,
        SearchForm,
        FiltersModal,
        LoadingScreen,
        Sorting
    },
    data() {
        return {
            urlEvent: (this.isUser) ? '/events/' : '/profile/moderating-events/',
            contentLoading: true,
            pageLoading: false,
            
            query: {
                "filter[title]": '',
                "filter[tags]": [],
                "sort": '-updated_at',
                perPage: 10,
                ...this.$route.query
            },
            selectedTags: this.$route.query['filter[tags]'] || [],
            
            page: 1,
            nextPage: null,
            
            events: [],
            
            tags:[],
            activeFilterType: 'tags',
            

            sortList: [
                {
                    label: 'По времени создания',
                    items: [
                        {label: 'От новых к старым', value: '-updated_at'},
                        {label: 'От старых к новым', value: 'updated_at'},
                    ]
                },
            ],


            showModal: false,
            activeModal: '',
        }
    },

    computed:{
        isUser() {
            return this.$store.getters.isUser
        },

        isOrganizer() {
            return this.$store.getters.isOrganizer
        },
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
        async getEvents() {
            this.contentLoading = true;
            this.query['filter[tags]'] = this.selectedTags;
            try {
                const response = await api.get('/events/finished?page=' + this.page + '&' + new URLSearchParams(this.query).toString());
                this.events = response.data.data;
                this.nextPage = (response.data.next_page_url) ? response.data.next_page_url.split('page=')[1] : null;
                this.$router.push({query: {
                    'sort': this.query['sort'],
                    'filter[title]': this.query['filter[title]'],
                    'filter[tags]': this.selectedTags,
                }});
            } catch (error) {

            } finally {
                this.contentLoading = false;
            }
        },


        async loadNextPage() {
            this.pageLoading = true;
            try {
                const response = await api.get('/events/finished?page=' + this.nextPage + '&' + new URLSearchParams(this.query).toString());
                this.nextPage = (response.data.next_page_url) ? response.data.next_page_url.split('page=')[1] : null;
                this.events = [...this.events, ...response.data.data];
            } catch (error) {
                console.log(error);
            } finally {
                this.pageLoading = false;
            }
        },

        async getTags() {
            this.contentLoading = true;
            try {
                const response = await api.get("/tags");
                this.tags = response.data.data;
            } catch (error) {
                console.log(error);
            } finally {
                this.contentLoading = false;
            }
        },

        


        resetFilters() {
            this.selectedTags = [];
            this.getEvents();
            this.closeModal();
        },



    },


    created() {
        this.getEvents();
        this.getTags();
    }
}
</script>

<style scoped>
.container-events {
    width: clamp(280px, 95%, 1200px);
    margin: 5dvw auto 0;

}

.container-events-title {
    margin-bottom: clamp(20px, 3vw, 40px);
}

.block-title {
    margin-bottom: 0;
}



.filters-btn {
    background: none;
}

.container-events-items {
    min-height: 80dvh;
    row-gap: clamp(60px, 4vw, 100px);
}
</style>