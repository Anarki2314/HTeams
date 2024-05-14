<template>
    <header-view />

    <admin-nav />

    <section class="tags-section admin-section">
        <div class="container-block">
            <div class="container-tag admin-container">
                <div class="form-search mb-3 text-end">
                    <div class="container-search">
                        <input
                            type="text"
                            class="form-input"
                            name="search"
                            id="search"
                            placeholder="Поиск"
                            v-model.lazy="query['filter[title]']"
                            @change.lazy="getTags"
                        />
                        <button class="search-btn" @click="getTags">
                            <img :src="'/assets/img/search.svg'" alt="" />
                        </button>
                    </div>
                </div>

                <h3 class="block-title text-center text-lg-start">Тэги</h3>

                <div class="container-tag-create">
                    <button
                        class="button-view main-button"
                        @click="openModal('modal-create-tag')"
                    >
                        Создать тэг
                    </button>
                </div>

                <div class="container-sort"></div>
                <div
                    class="container-admin-items  container-page position-relative d-flex flex-column"
                >
                    <loading-screen v-if="contentLoading" />
                    <div
                        class="container-empty-page row-card"
                        v-if="!tags.length"
                    >
                        <div class="empty-page">Ничего не найдено</div>
                    </div>
                    <tag-card
                        @openDeleteModal="openDeleteModal"
                        v-for="(tag, index) in tags"
                        :key="tag"
                        :tag="tag"
                    />
                    <button
                        class="container-pagination row-card d-flex justify-content-center"
                        @click="loadNextPage"
                        v-if="nextPage"
                    >
                        <span class="pagination-btn" v-if="!pageLoading"
                            >Показать еще</span
                        >
                        <img
                            :src="'/assets/img/loading.svg'"
                            alt=""
                            v-if="pageLoading"
                            class="pagination-loading"
                        />
                    </button>
                </div>
            </div>
        </div>
    </section>

    <footer-view />

    <modal
        v-if="showModal && activeModal === 'modal-create-tag'"
        modalId="modal-create-tag"
        @close="closeModal"
    >
        <h2 class="modal-title">Введите название тэга</h2>
        <div class="modal-content">
            <form @submit.prevent="createTag">
                <div class="modal-container-input">
                    <input
                        type="text"
                        placeholder="Название тэга"
                        required
                        class="modal-input"
                        v-model="tagName"
                    />
                </div>
                <div class="modal-container-buttons">
                    <button
                        type="button"
                        class="button-view info-button"
                        @click="closeModal"
                    >
                        Отмена
                    </button>
                    <div class="modal-container-button">
                        <button
                            class="button-view dark-button"
                            type="submit"
                            v-if="!isLoading"
                        >
                            Создать
                        </button>
                        <div class="loading" :class="{ 'd-none': !isLoading }">
                            <img :src="'/assets/img/loading.svg'" alt="" />
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </modal>
    <modal
        v-if="showModal && activeModal === 'modal-delete-tag'"
        modalId="modal-delete-tag"
        @close="closeModal"
    >
        <h2 class="modal-title">Вы уверены, что хотите удалить тэг?</h2>
        <div class="modal-content">
            <form @submit.prevent="deleteTag">
                <div class="modal-container-buttons">
                    <button
                        type="button"
                        class="button-view info-button"
                        @click="closeModal"
                    >
                        Отмена
                    </button>
                    <div class="modal-container-button">
                        <button
                            class="button-view dark-button"
                            type="submit"
                            v-if="!isLoading"
                        >
                            Удалить
                        </button>
                        <div class="loading" :class="{ 'd-none': !isLoading }">
                            <img :src="'/assets/img/loading.svg'" alt="" />
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </modal>
</template>

<script>
import HeaderView from "@/components/HeaderView.vue";
import AdminNav from "../../components/_admin/AdminNav.vue";
import TagCard from "../../components/_admin/TagCard.vue";
import FooterView from "@/components/FooterView.vue";
import Modal from "@/components/Modal.vue";
import LoadingScreen from "@/components/LoadingScreen.vue";

import { useVuelidate } from "@vuelidate/core";
import { required, helpers } from "@vuelidate/validators";
import api from "../../api.js";
import { push } from "notivue";
export default {
    components: {
        HeaderView,
        AdminNav,
        TagCard,
        FooterView,
        Modal,
        LoadingScreen,
    },

    setup() {
        return { v$: useVuelidate() };
    },
    data() {
        return {
            query: {
                "filter[title]": "",
                perPage: 10,
                ...this.$route.query,
            },

            tagName: "",

            showModal: false,
            activeModal: "",
            activeTagId: null,
            page: 1,
            nextPage: null,
            contentLoading: true,
            isLoading: false,
            pageLoading: false,
            tags: [],
        };
    },

    methods: {
        openModal(modalId) {
            this.showModal = true;
            this.activeModal = modalId;
        },

        openDeleteModal(modalId, id) {
            this.showModal = true;
            this.activeModal = modalId;
            this.activeTagId = id;
        },
        closeModal() {
            this.showModal = false;
            this.activeModal = "";
            this.activeTagId = null;
        },
        async createTag() {
            const isFormCorrect = await this.v$.$validate();
            if (!isFormCorrect) {
                this.v$.$errors.forEach((error) => {
                    push.error(error.$message);
                });
                return;
            }

            this.isLoading = true;
            try {
                const response = await api.post("/admin/tags", {
                    title: this.tagName,
                });
                push.success(response.data.message);
                this.getTags();
                this.closeModal();
                this.tagName = "";
            } catch (error) {
                push.error(error.data.message);
            } finally {
                this.isLoading = false;
            }
        },

        async deleteTag() {
            try {
                this.isLoading = true;
                const response = await api.delete(
                    "/admin/tags/" + this.activeTagId
                );
                push.success(response.data.message);
                this.getTags();
                this.closeModal();
            } catch (error) {
                push.error(error.data.message);
            } finally {
                this.isLoading = false;
            }
        },

        async getTags() {
            this.contentLoading = true;
            try {
                const response = await api.get(
                    "/admin/tags?page=" +
                        this.page +
                        "&" +
                        new URLSearchParams(this.query).toString()
                );
                this.tags = response.data.data;
                this.nextPage = response.data.next_page_url
                    ? response.data.next_page_url.split("page=")[1]
                    : null;
                this.$router.push({
                    query: {
                        "filter[title]": this.query["filter[title]"],
                        "filter[isFull]": this.query["filter[isFull]"],
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
                    "/admin/tags?page=" +
                        this.nextPage +
                        "&" +
                        new URLSearchParams(this.query).toString()
                );
                this.nextPage = response.data.next_page_url
                    ? response.data.next_page_url.split("page=")[1]
                    : null;
                this.tags = [...this.tags, ...response.data.data];
            } catch (error) {
                console.log(error);
            } finally {
                this.pageLoading = false;
            }
        },
    },

    validations() {
        return {
            tagName: {
                required: helpers.withMessage("Заполните поле", required),
                regex: helpers.withMessage(
                    "Только буквы",
                    helpers.regex(/^[а-яА-ЯёЁA-Za-z]+$/)
                ),
            },
        };
    },

    created() {
        this.getTags();
    },
};
</script>

<style scoped>
.admin-container {
    width: clamp(280px, 95%, 1200px);
    margin: 0 auto;
}

.container-tag-create {
    margin-bottom: clamp(20px, 3vw, 40px);
}

.container-admin-items {
    min-height: 80dvh;
    gap: clamp(40px, 4vw, 100px);
}
</style>
