<template>
    
    <header-view/>

    <admin-nav/>
    
    <section class="users-section admin-section">
        <div class="container-block">
            <div class="container-users admin-container">

                <SearchForm @search="getUsers" v-model="query['filter[email]']" />
                <div class="container-users-title d-flex align-items-center justify-content-between">
                    <h3 class="block-title text-center text-lg-start">
                        Участники
                    </h3>
                    
                </div>
                <sorting v-model="query['sort']" :sortList="sortList" @update:modelValue="getUsers" />
                <div
                    class="container-users-items d-flex flex-column">
                    <loading-screen v-if="contentLoading" position="absolute"/>
                    <div class="container-empty-page row-card" v-if="!users.length">
                        <div class="empty-page">Ничего не найдено</div>
                    </div>
                    <user-card v-for="(user, index) in users" :key="index" :user="user" url="/_admin/users/" />
                    <button class="container-pagination row-card d-flex justify-content-center" @click="loadNextPage"
                        v-if="nextPage">
                        <span class="pagination-btn" v-if="!pageLoading">Показать еще</span>
                        <img :src="'/assets/img/loading.svg'" alt="" v-if="pageLoading" class="pagination-loading" />
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
import UserCard from '../../components/_admin/UserCard.vue';
import FooterView from '@/components/FooterView.vue';
import LoadingScreen from '../../components/LoadingScreen.vue';
import SearchForm from '../../components/SearchForm.vue';
import Sorting from '../../components/Sorting.vue';
import api from '../../api.js';

export default {
    components: {
        HeaderView,
        AdminNav,
        UserCard,
        FooterView,
        LoadingScreen,
        SearchForm,
        Sorting
    },

    data() {
        return {
            contentLoading: true,
            pageLoading: false,

            query: {
                "filter[email]": "",
                role: 'Пользователь',
                sort: "-created_at",
                perPage: 10,
                ...this.$route.query,
            },
            page: 1,
            nextPage: null,

            users: [],


            sortList: [
                {
                    label: "По времени регистрации",
                    items: [
                        { label: "От новых к старым", value: "-created_at" },
                        { label: "От старых к новым", value: "created_at" },
                    ],
                },
                {
                    label: "По почте",
                    items: [
                        { label: "От A к Z", value: "email" },
                        { label: "От Z к A", value: "-email" },
                    ],
                },
            ],

            showModal: false,
            activeModal: "",
        };
    },

    methods: {
        async getUsers() {
            this.contentLoading = true;
            try {
                const response = await api.get(
                    "/admin/users?page=" +
                    this.page +
                    "&" +
                    new URLSearchParams(this.query).toString()
                );
                this.users = response.data.data;
                this.nextPage = response.data.next_page_url
                    ? response.data.next_page_url.split("page=")[1]
                    : null;
                this.$router.push({
                    query: {
                        sort: this.query["sort"],
                        "filter[email]": this.query["filter[email]"],
                    },
                });
            } catch (error) {
            } finally {
                this.contentLoading = false;
            }
        },

        async loadNextPage() {
            this.pageLoading = true;
            try {
                const response = await api.get(
                    "/admin/users?page=" +
                    this.nextPage +
                    "&" +
                    new URLSearchParams(this.query).toString()
                );
                this.nextPage = response.data.next_page_url
                    ? response.data.next_page_url.split("page=")[1]
                    : null;
                this.users = [...this.users, ...response.data.data];
            } catch (error) {
                console.log(error);
            } finally {
                this.pageLoading = false;
            }
        },

    },

    created() {
        this.getUsers();
    },
}
</script>


<style scoped>




.admin-container {
        width: clamp( 280px , 95% , 1200px);
        margin: 0 auto;
    }
    .container-users-title {
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

    .container-users-items{
        min-height: 80dvh;
        gap: clamp(  40px , 4vw , 100px);
    }

</style>