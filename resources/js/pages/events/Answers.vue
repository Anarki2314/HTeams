<template>
    <header-view />
    <section class="teams-section">
        <div class="container-block">
            <div class="container-teams">
                <div class="container-teams-title d-flex align-items-center justify-content-between">
                    <h3 class="block-title text-center text-lg-start">
                        Команды
                    </h3>
                </div>
                <div class="container-sort"></div>
                <div class="container-teams-items container-page d-flex flex-column">
                    <div class="container-empty-page row-card" v-if="!answers.length">
                        <div class="empty-page">Ничего не найдено</div>
                    </div>
                    <loading-screen v-if="contentLoading" />

                    <div class="team-row-card row-card justify-content-sm-between" v-for="(answer, index) in answers" :key="index" >
                        <router-link :to="'/teams/' + answer.team.id"
                            class="container-team-name row-card-container-name">
                            <div class="container-team-image row-card-container-image">
                                <img :src="answer.team.leader.avatar?.path" alt="" class="team-image row-card-image" />
                            </div>
                            <h4 class="team-name row-card-name">
                                {{ answer.team.title }}
                            </h4>
                        </router-link>
                        <div class="container-team-apply-button row-card-container-button">
                            <a :href="answer.answer.path" :download="answer.answer.name"
                                class="button-view secondary-button">Скачать файл</a>
                        </div>
                    </div>

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
import HeaderView from "@/components/HeaderView.vue";
import FooterView from "@/components/FooterView.vue";
import LoadingScreen from "@/components/LoadingScreen.vue";
import api from "../../api.js";
import { push } from "notivue";
export default {
    components: {
        HeaderView,
        FooterView,
        LoadingScreen,
    },
    data() {
        return {
            query: {
                perPage: 10,
                ...this.$route.query,
            },
            page: 1,

            nextPage: null,
            contentLoading: true,
            pageLoading: false,
            answers: [],
        };
    },

    methods: {
        async getTeams() {
            this.contentLoading = true;
            try {
                const response = await api.get(
                    "/events/" + this.$route.params.eventId + "/answers?page=" +
                    this.page +
                    "&" +
                    new URLSearchParams(this.query).toString()
                );
                this.answers = response.data.data;
                this.nextPage = response.data.next_page_url
                    ? response.data.next_page_url.split("page=")[1]
                    : null;
            } catch (error) {
            } finally {
                this.contentLoading = false;
            }
        },

        async loadNextPage() {
            this.pageLoading = true;
            try {
                const response = await api.get(
                    "/events/" + this.$route.params.eventId + "/answers?page=" +
                    this.nextPage +
                    "&" +
                    new URLSearchParams(this.query).toString()
                );
                this.nextPage = response.data.next_page_url
                    ? response.data.next_page_url.split("page=")[1]
                    : null;
                this.answers = [...this.answers, ...response.data.data];
            } catch (error) {
                console.log(error);
            } finally {
                this.pageLoading = false;
            }
        },
    },

    created() {
        this.getTeams();
    },
};
</script>

<style scoped>
.container-teams {
    width: clamp(280px, 95%, 1200px);
    margin: 5dvw auto 0;
}

.container-teams-title {
    margin-bottom: clamp(20px, 3vw, 40px);
}

.block-title {
    margin-bottom: 0;
}

.container-search {
    position: relative;
}

.container-teams-items {
    position: relative;
    min-height: 80dvh;
    gap: clamp(40px, 4vw, 100px);
}
</style>
