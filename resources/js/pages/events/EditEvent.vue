<template>
    <loading-screen v-if="contentLoading" />
    <header-view />

    <section class="create-event-section">
        <div class="container-block">
            <div class="container-create-event">
                <div class="container-back">
                    <a @click="$router.go(-1)" class="router-link-underline"
                        >Назад</a
                    >
                </div>
                <div
                    class="container-create-event-title d-flex justify-content-center justify-content-md-between flex-wrap"
                >
                    <h3 class="block-title text-center text-lg-start">
                        Редактирование соревнования
                    </h3>
                </div>
                <div class="container-event-form">
                    <form
                        action=""
                        class="event-form"
                        @submit.prevent="editEvent"
                    >
                        <div class="form-block">
                            <div class="container-input title-input">
                                <h4 class="input-title">Название</h4>
                                <div class="input-content">
                                    <input
                                        type="text"
                                        v-model="form.title"
                                        name="title"
                                        required
                                        class="form-input"
                                    />
                                </div>
                            </div>

                            <div class="container-input description-input">
                                <h4 class="input-title">Описание</h4>
                                <div class="input-content">
                                    <textarea
                                        type="text"
                                        v-model="form.description"
                                        name="description"
                                        required
                                        class="form-input"
                                        rows="3"
                                    ></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="form-block">
                            <div
                                class="container-input date-registration-input"
                            >
                                <h4 class="input-title">Начало регистрации</h4>
                                <div class="input-content">
                                    <input
                                        type="datetime-local"
                                        v-model="form.date_registration"
                                        name="date_registration"
                                        required
                                        class="form-input"
                                        :min="dateRegistrationMin"
                                        :max="dateRegistrationMax"
                                    />
                                </div>
                            </div>

                            <div class="container-input date-start-input">
                                <h4 class="input-title">Начало события</h4>
                                <div class="input-content">
                                    <input
                                        type="datetime-local"
                                        v-model="form.date_start"
                                        name="date_start"
                                        required
                                        class="form-input"
                                        :min="dateStartMin"
                                        :max="dateStartMax"
                                    />
                                </div>
                            </div>

                            <div class="container-input date-end-input">
                                <h4 class="input-title">Конец события</h4>
                                <div class="input-content">
                                    <input
                                        type="datetime-local"
                                        v-model="form.date_end"
                                        name="date_end"
                                        required
                                        class="form-input"
                                        :min="dateEndMin"
                                        :max="dateEndMax"
                                    />
                                </div>
                            </div>

                            <div class="container-submit">
                                <button
                                    type="submit"
                                    class="button-view main-button"
                                    v-if="!isLoading"
                                >
                                    Изменить
                                </button>
                                <div
                                    class="loading"
                                    :class="{ 'd-none': !isLoading }"
                                >
                                    <img
                                        :src="'/assets/img/loading.svg'"
                                        alt=""
                                    />
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</template>

<script>
import HeaderView from "@/components/HeaderView.vue";
import LoadingScreen from "@/components/LoadingScreen.vue";
import MultiSelect from "primevue/multiselect";

import { useVuelidate } from "@vuelidate/core";
import {
    required,
    helpers,
    minLength,
    maxLength,
    minValue,
    maxValue,
} from "@vuelidate/validators";
import api from "../../api.js";
import { push } from "notivue";
export default {
    components: {
        HeaderView,
        LoadingScreen,
        MultiSelect,
    },
    setup() {
        return { v$: useVuelidate() };
    },
    data() {
        return {
            contentLoading: true,
            isLoading: false,

            form: {
                title: "",
                description: "",
                date_registration: "",
                date_start: "",
                date_end: "",
            },
        };
    },
    computed: {
        dateRegistrationMin() {
            return (
                new Date(new Date().setDate(new Date().getDate() + 7) + 3 * 60 * 60 * 1000)
                    .toISOString()
                    .slice(0, 10) + "T00:00:00"
            );
        },

        dateRegistrationMax() {
            return (
                new Date(new Date().setMonth(new Date().getMonth() + 1) + 3 * 60 * 60 * 1000)
                    .toISOString()
                    .slice(0, 10) + "T23:59:59"
            );
        },
        dateStartMin() {
            return (
                new Date(
                    new Date(
                        this.form.date_registration || this.dateRegistrationMin
                    ).setDate(
                        new Date(
                            this.form.date_registration ||
                                this.dateRegistrationMin
                        ).getDate() + 7
                    ) + 3 * 60 * 60 * 1000
                )
                    .toISOString()
                    .slice(0, 10) + "T00:00"
            );
        },

        dateStartMax() {
            return (
                new Date(
                    new Date(
                        this.form.date_registration || this.dateRegistrationMax
                    ).setMonth(
                        new Date(
                            this.form.date_registration ||
                                this.dateRegistrationMax
                        ).getMonth() + 1
                    ) + 3 * 60 * 60 * 1000
                )
                    .toISOString()
                    .slice(0, 10) + "T23:59"
            );
        },
        dateEndMin() {
            return (
                new Date(
                    new Date(this.form.date_start || this.dateStartMin).setDate(
                        new Date(
                            this.form.date_start || this.dateStartMin
                        ).getDate() + 3
                    ) + 3 * 60 * 60 * 1000
                )
                    .toISOString()
                    .slice(0, 10) + "T00:00"
            );
        },

        dateEndMax() {
            return (
                new Date(
                    new Date(
                        this.form.date_start || this.dateStartMax
                    ).setMonth(
                        new Date(
                            this.form.date_start || this.dateStartMax
                        ).getMonth() + 1
                    ) + 3 * 60 * 60 * 1000
                )
                    .toISOString()
                    .slice(0, 10) + "T23:59:59"
            );
        },
    },
    methods: {
        async editEvent() {
            const isFormCorrect = await this.v$.$validate();
            if (!isFormCorrect) {
                this.v$.$errors.forEach((error) => {
                    push.error(error.$message);
                });
                return;
            }
            this.isLoading = true;

            try {
                const response = await api.put(
                    "/events/" + this.$route.params.eventId,
                    this.form,
                );
                this.$router.push({
                    name: "moderating-events",
                    params: { eventId: this.$route.params.eventId },
                });
            } catch (error) {
                console.log(error);
                const errors = error.data.errors;
                for (const errorInput in errors) {
                    if (Object.hasOwnProperty.call(errors, errorInput)) {
                        push.error(errors[errorInput][0]);
                    }
                }
            } finally {
                this.isLoading = false;
            }
        },

        async getEvent() {
            try {
                this.contentLoading = true;
                const response = await api.get(
                    `/events/${this.$route.params.eventId}/full`
                );
                this.form = response.data.data;
            } catch (error) {
                console.log(error);
            } finally {
                this.contentLoading = false;
            }
        },

    },

    created() {
        this.getEvent();
    },

    validations() {
        return {
            form: {
                title: {
                    required: helpers.withMessage(
                        "Поле `Название` обязательно",
                        required
                    ),
                    regex: helpers.withMessage(
                        "В поле `Название` можно использовать только буквы и цифры",
                        helpers.regex(/^(?!.*\s{2})[а-яА-ЯёЁA-Za-z\d\s\-\_]+$/)
                    ),
                    minLength: helpers.withMessage(
                        "Поле `Название` должно содержать не менее 2 символов",
                        minLength(2)
                    ),
                    maxLength: helpers.withMessage(
                        "Поле `Название` должно содержать не более 50 символов",
                        maxLength(50)
                    ),
                },
                description: {
                    required: helpers.withMessage(
                        "Поле `Описание` обязательно",
                        required
                    ),
                    regex: helpers.withMessage(
                        "В поле `Описание` можно использовать только буквы и цифры",
                        helpers.regex(/^(?!.*\s{2})[а-яА-ЯёЁA-Za-z\d\s\-\_]+$/)
                    ),
                    minLength: helpers.withMessage(
                        "Поле `Описание` должно содержать не менее 100 символов",
                        minLength(100)
                    ),
                    maxLength: helpers.withMessage(
                        "Поле `Описание` должно содержать не более 350 символов",
                        maxLength(350)
                    ),
                },
                date_registration: {
                    required: helpers.withMessage(
                        "Поле `Начало регистрации` обязательно",
                        required
                    ),
                },
                date_start: {
                    required: helpers.withMessage(
                        "Поле `Начало события` обязательно",
                        required
                    ),
                },
                date_end: {
                    required: helpers.withMessage(
                        "Поле `Конец события` обязательно",
                        required
                    ),
                },
            },
        };
    },
};
</script>

<style lang="scss" scoped>
.container-prizes-place {
    display: flex;
    flex-direction: column;
    gap: 15px;
}
@import "@sass/event-form.scss";
</style>
