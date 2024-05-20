<template>
    <loading-screen v-if="contentLoading" />
    <header-view/>
    <div class="main-bg-container main-right-bg-container">

        <img :src=" '/assets/img/main-right-bg.png'" alt="" class="main-right-bg">
    </div>

    <div class="main-bg-container main-left-bg-container">
    <img :src=" '/assets/img/main-left-bg.png'" alt="" class="main-left-bg">

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
                        <div class="container-event-buttons d-flex flex-wrap">

                            <event-conditional-button :event="event" :key="event.id" @openModal="openModal" />

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
                <div class="container-info" v-if="event.status != 'Итоги'">
                    <h3 class="block-title text-center">Даты проведения</h3>

                    <div
                        class="container-info-items d-flex flex-column align-items-center justify-content-center"
                    >
                        <div class="container-info-item">
                            <div class="info-item-text">Начало регистрации</div>
                            <div class="info-item info-first">
                                {{ new Date(event.date_registration).toLocaleDateString('ru-RU', { year: 'numeric', month: '2-digit', day: '2-digit', hour: '2-digit', minute: '2-digit' })}}                            </div>
                        </div>
                        <div class="container-info-item">
                            <div class="info-item-text">Начало события</div>
                            <div class="info-item info-second">
                                {{ new Date(event.date_start).toLocaleDateString('ru-RU', { year: 'numeric', month: '2-digit', day: '2-digit', hour: '2-digit', minute: '2-digit' })}}                            </div>
                        </div>
                        <div class="container-info-item">
                            <div class="info-item-text">Конец события</div>
                            <div class="info-item info-third">
                                {{ new Date(event.date_end).toLocaleDateString('ru-RU', { year: 'numeric', month: '2-digit', day: '2-digit', hour: '2-digit', minute: '2-digit' })}}                            </div>
                        </div>
                    </div>
                </div>
                <div class="container-info" v-if='event.winners?.length && event.status == "Итоги"'>
                    <h3 class="block-title text-center">Победители</h3>

                    <div
                        class="container-info-items d-flex flex-column align-items-center justify-content-center"
                    >
                    <div
                            class="container-info-item"
                            v-for="(winner, index) in event.winners"
                            :key="winner.id"
                        >
                            <div class="info-item-text">
                                {{ winner.place }}-е место
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
                                {{ winner.team }} 
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
                        v-if="canDownload"
                        >Скачать задание</a
                    >

                    <button class="button-view main-button text-uppercase" @click="openModal('modal-join-event')" v-if="canJoin">Принять участие</button>
                </div>
            </div>
        </div>
    </section>



    <modal v-if="showModal && activeModal === 'modal-auth' && !isAuth" :show="showModal" @close="closeModal">
        <h2 class="modal-title">Чтобы зарегистрироваться на соревнование, войдите в аккаунт</h2>
                <div class="modal-content">
                    <div class="modal-container-buttons">
                        <button type="button" class="button-view info-button" @click="closeModal">Отмена</button>
                        <div class="modal-container-button">
                            <router-link to="/signIn" class="button-view dark-button">Войти</router-link>
                        </div>


                    </div>
                </div>
    </modal>

    <modal v-if="showModal && activeModal === 'modal-leader-event'" :show="showModal" @close="closeModal">
        <h2 class="modal-title">Зарегистрироваться на соревнование может только лидер команды</h2>
                <div class="modal-content">
                    <div class="modal-container-buttons justify-content-center">
                        <button type="button" class="button-view info-button" @click="closeModal">Закрыть</button>


                    </div>
                </div>
    </modal>

    <modal v-if="showModal && activeModal === 'modal-cancel-join-event'" :show="showModal" @close="closeModal">
        <h2 class="modal-title">Вы уверены, что хотите отказаться от участия?</h2>
                <div class="modal-content">
                    <div class="modal-container-buttons">
                        <button type="button" class="button-view info-button" @click="closeModal">Закрыть</button>
                        <div class="modal-container-button">
                            <button type="button" class="button-view dark-button" @click="cancelJoinEvent" v-if="!isLoading">Отказаться</button>
                            <div class="loading" :class="{ 'd-none': !isLoading }"><img :src="'/assets/img/loading.svg'" alt=""></div>
                            
                        </div>


                    </div>
                </div>
    </modal>
    <modal v-if="showModal && activeModal === 'modal-join-event'" :show="showModal" @close="closeModal">
        <h2 class="modal-title">Вы уверены, что хотите зарегистрироваться на соревнование?</h2>
                <div class="modal-content">
                    <div class="modal-container-buttons">
                        <button type="button" class="button-view info-button" @click="closeModal">Закрыть</button>
                        <div class="modal-container-button">
                            <button type="button" class="button-view dark-button" @click="joinEvent" v-if="!isLoading">Зарегистрироваться</button>
                            <div class="loading" :class="{ 'd-none': !isLoading }"><img :src="'/assets/img/loading.svg'" alt=""></div>

                        </div>


                    </div>
                </div>
    </modal>

    <modal v-if="showModal && activeModal === 'modal-answer-event'" :show="showModal" @close="closeModal">
        <h2 class="modal-title">Прикрепить ответ на задание</h2>
                <div class="modal-content">
                    <form @submit.prevent="answerEvent">
                    <div class="modal-container-input position-relative">
                        <input
                                        type="file"
                                        name="answer"
                                        id="answer"
                                        required
                                        class="form-input"
                                        @change="previewAnswer"
                                        accept=" application/pdf, application/msword , application/vnd.openxmlformats-officedocument.wordprocessingml.document "
                                    />
                                    <label for="answer" class="answer-label">
                                        <span class="answer-text">
                                            {{ answerFileName }}
                                        </span>
                                        <span class="answer-button"
                                            >Выбрать файл</span
                                        >
                                    </label>
                    </div>
                    <div class="modal-container-buttons">
                        <button type="button" class="button-view info-button" @click="closeModal">Закрыть</button>
                        <div class="modal-container-button">
                            <button type="submit" class="button-view dark-button" v-if="!isLoading">Прикрепить</button>
                            <div class="loading" :class="{ 'd-none': !isLoading }"><img :src="'/assets/img/loading.svg'" alt=""></div>

                        </div>


                    </div>
                    </form>
                </div>
    </modal>


</template>

<script>

import HeaderView from '@/components/HeaderView.vue';
import LoadingScreen from '@/components/LoadingScreen.vue';
import EventConditionalButton from '@/components/event/EventConditionalButton.vue';
import Modal from '@/components/Modal.vue';
import api from "../../api.js";
import { push } from 'notivue';
export default {
    components: {
        HeaderView,
        EventConditionalButton,
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

            answerFileName: "Выберите файл",
            answerFile: null,
        };
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

        canJoin() {
            return (this.isUser && this.haveTeam) && (!this.event.isJoined && this.event.status == 'Регистрация'); 
        },

        canDownload() {
            return (this.isUser && this.haveTeam) && (this.event.isJoined && this.event.status == 'Началось');
        },
    },

    methods: {
       previewAnswer(event) {
           if (!event.target.files[0]) {
               this.answerFileName = "Выберите задание";
               this.answerFile = null;
               return;
           }
           this.answerFile = event.target.files[0];
           this.answerFileName = this.answerFile.name;
       },
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
                    `/events/${this.$route.params.eventId}`
                );
                this.event = response.data.data;
            } catch (error) {
                console.log(error);
            } finally {
                this.contentLoading = false;
            }

        },

        async joinEvent() {
            try {
                this.isLoading = true;
                const response = await api.post(
                    `/events/${this.$route.params.eventId}/join`
                );
                push.success(response.data.message);
                this.event.isJoined = true;
                this.closeModal();
            } catch (error) {
                console.log(error);
            } finally {
                this.isLoading = false;
            }
        },

        async cancelJoinEvent() {
            try {
                this.isLoading = true;
                const response = await api.delete(
                    `/events/${this.$route.params.eventId}/leave`
                );
                push.success(response.data.message);
                this.event.isJoined = false;
                this.closeModal();
            } catch (error) {
                console.log(error);
            } finally {
                this.isLoading = false;
            }
        },

        async answerEvent() {
            try {
                this.isLoading = true;
                const response = await api.post(
                    `/events/${this.$route.params.eventId}/answer`,
                    {
                        answer: this.answerFile
                    },
                    {
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        }
                    }
                );
                push.success(response.data.message);
                this.event.answer = response.data.data.answer;
                this.closeModal();

            } catch (error) {
                push.error(error.data.message);
            } finally {
                this.isLoading = false;
            }
        },
    },
    created() {
        this.getEvent();
    },
}
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

.form-input{
    width: 100%;
    padding: 10px 10px;
    color: #f4f4f4;
    font-size: var(--size-text);
    outline: none;
    background: none;
    border: 1px solid var(--color-main);
    border-radius: 5px;
}
.form-input {
        width: 0;
        height: 0;
        opacity: 0;
        padding: 0;
        border: none;
        position: absolute;
    }

    .answer-label{
        width: 100%;
        position: relative;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-radius: 5px;
        border: 1px solid var(--color-main);
        padding: 0 0 0 10px;
        overflow: hidden;
        cursor: pointer;
        font-size: var(--size-text);


        .answer-text{
            flex: 1;
            font-size: clamp(12px, 1.5vw, 18px);
            overflow: hidden;
            text-overflow: ellipsis;
            text-wrap: nowrap;
        }
        .answer-button{
            background: none;
            outline: none;
            border:none;
            border-left: 1px solid var(--color-main);
            padding: 5px 10px;
            color: #f4f4f4;
            transition: all 0.3s ease;
        }
    }

    .form-input:focus + .answer-label .answer-button{
        background-color: var(--color-main);
        color: #1B1B1B;
    }

    .answer-label:hover .answer-button{
        background-color: var(--color-main);
        color: #1B1B1B;
    }
</style>