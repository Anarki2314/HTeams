<template>
    <loading-screen v-if="contentLoading" />
    <header-view />
    <div class="main-bg-container main-right-bg-container">
        <img
            :src="'/assets/img/main-right-bg.png'"
            alt=""
            class="main-right-bg"
        />
    </div>

    <div class="main-bg-container main-left-bg-container">
        <img
            :src="'/assets/img/main-left-bg.png'"
            alt=""
            class="main-left-bg"
        />
    </div>

    <section class="event-main-section">
        <div class="container-block">
            <div class="container-event">
                <div class="container-back">
                        <a @click="$router.go(-1)" class="router-link-underline">Назад</a>
                    </div>
                <div
                    class="container-event-main d-flex justify-content-center justify-content-md-start flex-wrap"
                >
                    <div class="container-event-image">
                        <img
                            :src="event.image?.path"
                            alt=""
                            class="event-image"
                        />
                    </div>

                    <div
                        class="container-event-info d-flex flex-column justify-content-center"
                    >
                        <h3 class="event-title">{{ event.title }}</h3>
                        <span class="event-info"
                            >Статус: {{ event.status }}</span
                        >
                        <span class="event-info"
                            >Организатор: {{ event.creator }}</span
                        >
                        <div
                            class="container-event-tags d-flex align-items-center flex-wrap"
                        >
                            <span
                                class="event-tag"
                                v-for="(tag, index) in event.tags"
                                :key="tag"
                            >
                                #{{ tag.title }}
                            </span>
                        </div>
                        <div class="container-event-buttons d-flex flex-wrap" v-if="event.status === 'На проверке'">
                            <button type="button" @click="openModal('modal-approve-event')" class="button-view main-button">Одобрить</button>
                            <button type="button" class="button-view secondary-button" @click="openModal('modal-cancel-event')">Отменить</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="event-info-section" id="info">
        <div class="container-block">
            <div
                class="container-about d-flex align-items-center justify-content-center flex-wrap"
            >
                <div class="container-info">
                    <h3 class="block-title text-center">Даты проведения</h3>

                    <div
                        class="container-info-items d-flex flex-column align-items-center justify-content-center"
                    >
                        <div class="container-info-item">
                            <div class="info-item-text">Начало регистрации</div>
                            <div class="info-item info-first">
                                {{event.date_registration}}
                            </div>
                        </div>
                        <div class="container-info-item">
                            <div class="info-item-text">Начало события</div>
                            <div class="info-item info-second">
                                {{event.date_start}}
                            </div>
                        </div>
                        <div class="container-info-item">
                            <div class="info-item-text">Конец события</div>
                            <div class="info-item info-third">
                                {{event.date_end}}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="container-prize">
                    <h3 class="block-title text-center">Награды</h3>
                    <div
                        class="container-info-items d-flex flex-column align-items-center justify-content-center"
                    >
                        <div
                            class="container-info-item"
                            v-for="(prize, index) in event.prizes"
                            :key="prize.id"
                        >
                            <div class="info-item-text">
                                {{ prize.place }}-е место
                            </div>
                            <div
                                class="info-item"
                                :class="
                                    index === 0
                                        ? 'info-first'
                                        : index === 1
                                        ? 'info-second'
                                        : 'info-third'
                                "
                            >
                                {{ prize.prize }} руб
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="event-description-section">
        <div class="container-block">
            <div class="container-description">
                <h3 class="block-title text-center">Описание</h3>
                <div class="container-description-text text-center">
                    <p>
                        {{ event.description }}
                    </p>
                </div>
                <div
                    class="container-event-participate d-flex justify-content-center"
                >
                    <a
                        class="button-view main-button text-uppercase"
                        :href="event.task?.path"
                        :download="event.task?.name"
                        >Скачать задание</a
                    >
                </div>
            </div>
        </div>
    </section>

    <modal v-if="showModal && activeModal === 'modal-cancel-event'" modalId="modal-cancel-event" @close="closeModal">
                <h2 class="modal-title">Укажите причину</h2>
                <div class="modal-content">
                    <form @submit.prevent="cancelEvent">
                        <div class="modal-container-input">
                    <input
                        type="text"
                        placeholder="Причина"
                        required
                        class="modal-input"
                        v-model="message"
                    />
                </div>
                    <div class="modal-container-buttons">
                        <button type="button" class="button-view info-button" @click="closeModal">Закрыть</button>
                        <div class="modal-container-button">
                            <button class="button-view dark-button" type="submit" v-if
                            =" !isLoading"> Отменить</button>
                            <div class="loading" :class="{ 'd-none': !isLoading }"><img :src="'/assets/img/loading.svg'" alt=""></div>
                        </div>


                    </div>
                    </form>
                </div>
        </modal>
    <modal v-if="showModal && activeModal === 'modal-approve-event'" modalId="modal-approve-event" @close="closeModal">
                <h2 class="modal-title">Вы уверены, что хотите одобрить соревнование?</h2>
                <div class="modal-content">
                    <form @submit.prevent="approveEvent">
                    <div class="modal-container-buttons">
                        <button type="button" class="button-view info-button" @click="closeModal">Закрыть</button>
                        <div class="modal-container-button">
                            <button class="button-view dark-button" type="submit" v-if
                            =" !isLoading"> Одобрить</button>
                            <div class="loading" :class="{ 'd-none': !isLoading }"><img :src="'/assets/img/loading.svg'" alt=""></div>
                        </div>


                    </div>
                    </form>
                </div>
        </modal>
</template>

<script>
import HeaderView from "@/components/HeaderView.vue";
import LoadingScreen from "@/components/LoadingScreen.vue";
import Modal from "@/components/Modal.vue";

import api from "../../api.js";
import { push } from "notivue";
export default {
    components: {
        HeaderView,
        LoadingScreen,
        Modal
    },
    data() {
        return {
            contentLoading: true,
            isLoading: false,
            showModal: false,
            activeModal: "",

            event: {},
            message: ''
        };
    },

    methods: {
        closeModal() {
            this.showModal = false;
            this.activeModal = "";
        },
        
        openModal(modalId) {
            this.showModal = true;
            this.activeModal = modalId;
        },
        async getEvent() {
            try {
                this.contentLoading = true;
                const response = await api.get(
                    `/events/${this.$route.params.requestId}/full`
                );
                this.event = response.data.data;
            } catch (error) {
                console.log(error);
            } finally {
                this.contentLoading = false;
            }
        },

        async cancelEvent() {
            this.isLoading = true;
            try {
                const response = await api.post(`/events/${this.$route.params.requestId}/cancel`, {
                    'message': this.message
                });
                push.success(response.data.message);
                this.closeModal();
                this.$router.push({ name: "admin-requests"});
            } catch (error) {
                push.error(error.data.message);
            } finally {
                this.isLoading = false;
            }
        },
        async approveEvent() {
            this.isLoading = true;
            try {
                const response = await api.put(`/events/${this.$route.params.requestId}/approve`);
                push.success(response.data.message);
                this.closeModal();
                this.$router.push({ name: "admin-requests"});
            } catch (error) {
                console.log(error);
            } finally {
                this.isLoading = false;
            }
        },
    },

    created() {
        this.getEvent();
    },
};
</script>

<style scoped>
section {
    min-height: 100dvh;
    display: flex;
    justify-content: center;
    align-items: center;
    /* margin: 8dvw auto; */
}

.event-main-section {
    margin: 5dvw auto 0;
    align-items: start;
    min-height: calc(92dvh - 140px);
}
.event-description-section {
    margin: 10dvh auto;
}

.main-bg-container {
    position: absolute;
    z-index: -1;
}

.main-bg-container img {
    transform: translateY(-50%);
}

.main-right-bg-container {
    right: 0;
    top: 100dvh;
}
.main-left-bg-container {
    left: 0;
    top: 200dvh;
}

.container-event {
    width: clamp(280px, 95%, 1300px);
    margin: 0 auto;
}

.container-event-main {
    gap: 30px;
    margin: clamp(30px, 5vw, 60px) 0;
}

.event-image {
    width: clamp(280px, 95vw, 900px);
    object-fit: cover;
    border-radius: 5px;
    aspect-ratio: 1.8/1;
}

.container-event-info {
    gap: clamp(15px, 3.5vw, 30px);

    flex: 1;
}

.event-title {
    font-size: var(--size-title);
}
.event-info {
    font-size: var(--size-text);
}

.container-event-tags {
    gap: 20px;
}
.event-tag {
    color: var(--color-main);
    font-size: var(--size-text);
}

.container-event-buttons{
    gap: 20px;
}

.container-about {
    gap: clamp(20px, 3vw, 60px);
}

.container-info-items {
    gap: clamp(15px, 3vw, 30px);
}

.container-info-item {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: clamp(25px, 3vw, 75px);
    align-items: center;
}

.info-item-text {
    font-size: clamp(14px, 3vw, 30px);
    text-align: end;
}

.info-item {
    text-align: start;
    color: var(--color-main);
}

.info-first {
    font-size: clamp(16px, 4vw, 48px);
}

.info-second {
    font-size: clamp(16px, 3.5vw, 42px);
}

.info-third {
    font-size: clamp(16px, 3vw, 36px);
}

.container-description-text {
    font-size: clamp(16px, 3vw, 30px);
    margin-bottom: clamp(15px, 3vw, 30px);
}

.event-description-section .main-button {
    font-size: var(--size-title);
    padding: clamp(10px, 2vw, 30px) clamp(20px, 3vw, 60px);
}
</style>
