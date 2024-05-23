<template>
    <header-view />
    <section class="winners-section">
        <div class="container-block">
            <div class="container-winners">
                <div class="container-back">
                        <a @click="$router.go(-1)" class="router-link-underline">Назад</a>
                </div>
                <div class="container-winners-title d-flex align-items-center justify-content-center">
                    <h3 class="block-title text-center">
                        Определение победителей
                    </h3>
                </div>

                <form action="" class="winners-form" @submit.prevent="confirmWinners">
                    <div class="container-form">
                        <div class="form-group">
                            <h3 class="winner-title">1-е место</h3>

                            <div class="select-container">
                                <Dropdown v-model="form.firstPlace" :options="teams" optionLabel="title" optionValue="id" placeholder="Выберите команду" filter class="winners-select"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <h3 class="winner-title">2-е место</h3>

                            <div class="select-container">
                                <Dropdown v-model="form.secondPlace" :options="teams" optionLabel="title" optionValue="id" placeholder="Выберите команду" filter class="winners-select"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <h3 class="winner-title">3-е место</h3>

                            <div class="select-container">
                                <Dropdown v-model="form.thirdPlace" :options="teams" optionLabel="title" optionValue="id" placeholder="Выберите команду" filter class="winners-select"/>
                            </div>
                        </div>
                    </div>
                    <div class="container-form-submit d-flex justify-content-center">
                        <button class="button-view main-button" type="submit">Сохранить</button>
                    </div>
                </form>
                

            </div>
        </div>

    </section>
    <modal v-if="showModal && activeModal === 'modal-confirm-winners'" modalId="modal-confirm-winners" @close="closeModal">
                <h2 class="modal-title">Вы уверены в своем выборе?</h2>
                <div class="modal-content">
                    <form @submit.prevent="chooseWinners">
                    <div class="modal-container-buttons">
                        <button type="button" class="button-view info-button" @click="closeModal">Закрыть</button>
                        <div class="modal-container-button">
                            <button class="button-view dark-button" type="submit" v-if
                            =" !isLoading"> Сохранить</button>
                            <div class="loading" :class="{ 'd-none': !isLoading }"><img :src="'/assets/img/loading.svg'" alt=""></div>
                        </div>


                    </div>
                    </form>
                </div>
        </modal>

</template>

<script>
import HeaderView from "@/components/HeaderView.vue";
import FooterView from "@/components/FooterView.vue";
import LoadingScreen from "@/components/LoadingScreen.vue";
import Dropdown from 'primevue/dropdown';
import Modal from "@/components/Modal.vue";
import api from "../../api.js";
import { push } from "notivue";
import { useVuelidate } from "@vuelidate/core";
import {
    required,
    helpers,
    sameAs,
    not,
    and
    
} from "@vuelidate/validators";
export default {
    components: {
        HeaderView,
        FooterView,
        LoadingScreen,
        Dropdown,
        Modal
    },

    setup() {
        return { v$: useVuelidate() }
    },
    data() {
        return {
            contentLoading: true,
            isLoading : false,
            teams:[],

            showModal: false,
            activeModal: "",
            form: {
                firstPlace: null,
                secondPlace: null,
                thirdPlace: null,
            }
        };
    },

    methods: {

        closeModal() {
            this.showModal = false;
            this.activeModal = "";
        },

        openModal(modal) {
            this.showModal = true;
            this.activeModal = modal;
        },
        async getTeams() {
            this.contentLoading = true;
            try {
                const response = await api.get("/events/" + this.$route.params.eventId + "/teams");
                this.teams = response.data.data;

            } catch (error) {
            } finally {
                this.contentLoading = false;
            }
        },

        async confirmWinners() {

            const isFormCorrect = await this.v$.$validate();
            if (!isFormCorrect) {
                this.v$.$errors.forEach((error) => {
                    push.error(error.$message);
                });

                return;
            }

            this.openModal("modal-confirm-winners");
        },

        async chooseWinners() {
            try{
                this.isLoading = true;
                const response = await api.post("/events/" + this.$route.params.eventId + "/winners", this.form);
                push.success(response.data.message);
                this.closeModal();
                this.$router.push({ name: "event" , params: { eventId: this.$route.params.eventId } });
            } catch (error) {
                push.error(error.data.message);
            } finally {
                this.isLoading = false;
            }
        },
        
        notSameAsForFirst(value) {
            return this.form.secondPlace !== value || this.form.thirdPlace !== value
        },

        notSameAsForSecond(value) {
            return this.form.firstPlace !== value || this.form.thirdPlace !== value
        },

        notSameAsForThird(value) {
            return this.form.firstPlace !== value || this.form.secondPlace !== value
        }
            
    },

    validations() {
        return {
            form: {
                firstPlace: {
                    required: helpers.withMessage("Поле `1-е место`обязательно для заполнения", required),
                    // notSameAs: helpers.withMessage("Поле `1-е место` не может быть равно 2-му или 3-му месту", this.notSameAsForFirst),
                },
                secondPlace: {
                    required: helpers.withMessage("Поле `2-е место` обязательно для заполнения", required),
                    // notSameAs: helpers.withMessage("Поле `2-е место` не может быть равно 1-му или 3-му месту", this.notSameAsForSecond),
                },
                thirdPlace: {
                    required: helpers.withMessage("Поле `3-е место` обязательно для заполнения", required),
                    // notSameAs: helpers.withMessage("Поле `3-е место` не может быть равно 1-му или 2-му месту", this.notSameAsForThird),
                },
            },
        };
    },
    created() {
        this.getTeams();
    },
};
</script>

<style lang="scss" scoped>
@import url("@sass/primevue.scss");

.container-winners {
    width: clamp(280px, 95%, 1200px);
    margin: 5dvw auto 0;
}
.container-winners-title {
    margin-bottom: clamp(15px, 3vw, 30px);
}
.container-form{
    display: flex;
    justify-content: space-around;
    align-items: center;
    flex-wrap: wrap;
    gap: clamp(20px,5vw,60px);
    flex: 1 1 auto;
    margin-bottom: clamp(20px, 3vw, 30px);
}

.form-group{
    display: flex;
    flex-direction: column;
    gap: clamp(10px, 3vw, 30px);
    width: 280px;
}

.select-container .p-dropdown {
    width: 100%;
}
.winner-title{
    font-size: clamp(14px, 3vw, 30px);
    text-align: center;
}




.block-title {
    margin-bottom: 0;
}


</style>
