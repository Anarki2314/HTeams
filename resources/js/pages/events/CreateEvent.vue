<template>
    <loading-screen v-if="contentLoading" />
    <header-view />
    
    <section class="create-event-section">
        <div class="container-block">
            <div class="container-create-event">
                <div class="container-back">
                    <a @click="$router.go(-1)" class="router-link-underline">Назад</a>
                </div>
                <div class="container-create-event-title d-flex justify-content-center justify-content-md-between flex-wrap">
                    <h3 class="block-title text-center text-lg-start">
                        Создание соревнования
                    </h3>
                </div>
                <div class="container-event-form">
                    
                    <form action="" class="event-form" @submit.prevent="createEvent">
                        <div class="form-block">
                            
                            <div class="container-input image-input">
                                <h4 class="input-title">Изображение</h4>
                                <div class="input-content">
                                    <input type="file" name="image" id="image" required class="form-input" @change="previewImage" accept="image/png, image/jpeg, image/jpg"/>
                                    <label for="image" class="image-label">
                                        Выберите изображение
                                        <img class="image-preview" v-if="imageUrl" :src="imageUrl" alt="">
                                    </label>
                                </div>
                            </div>

                            <div class="container-input tags-input">
                                <h4 class="input-title">Теги</h4>
                                <div class="input-content">
                                    <MultiSelect
                                    v-model="form.tags" 
                                    :options="tags"
                                    optionLabel="title"
                                    optionValue="id"
                                    display="chip"



                                    filter
                                    placeholder="Выберите теги"
                                    name="tags" 
                                    required 
                                    class="form-input form-multiselect"
                                    >
                                    </MultiSelect>

                                </div>
                            </div>
                            
                            <div class="container-input task-input">
                                <h4 class="input-title">Задание</h4>
                                <div class="input-content">
                                    <input type="file" name="task" id="task" required class="form-input" @change="previewTask" accept=" application/pdf, application/msword , application/vnd.openxmlformats-officedocument.wordprocessingml.document " />
                                    <label for="task" class="task-label">
                                        <span class="task-text"> {{ taskName }} </span>
                                        <span class="task-button">Выбрать файл</span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-block">

                            <div class="container-input title-input">
                                <h4 class="input-title">Название</h4>
                                <div class="input-content">
                                    <input type="text" v-model="form.title" name="title" required class="form-input" />
                                </div>
                            </div>

                            <div class="container-input description-input">
                                <h4 class="input-title">Описание</h4>
                                <div class="input-content">
                                    <textarea type="text" v-model="form.description" name="description" required class="form-input" rows="3"></textarea>
                                </div>
                            </div>
                            
                            <div class="container-input">
                                <h4 class="input-title">Призовые</h4>
                                <div class="container-prizes-place">
                                    <div class="container-input">
                                        <h4 class="input-title">1-е место</h4>
                                        <div class="input-content">
                                            <input 
                                            type="text" 
                                            v-model="form.prizes.firstPlace" 
                                            name="firstPlace" 
                                            required 
                                            class="form-input" 
                                            />
                                        </div>
                                    </div>
                                    <div class="container-input">
                                        <h4 class="input-title">2-е место</h4>
                                        <div class="input-content">
                                            <input type="text" v-model="form.prizes.secondPlace" name="secondPlace" required class="form-input" />
                                        </div>
                                    </div>
                                    <div class="container-input">
                                        <h4 class="input-title">3-е место</h4>
                                        <div class="input-content">
                                            <input type="text" v-model="form.prizes.thirdPlace" name="thirdPlace" required class="form-input" />
                                        </div>
                                    </div>
                                </div>

                            </div>

                        </div>

                        
                        <div class="form-block">
        
                            <div class="container-input date-registration-input">
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
                                <button type="submit" class="button-view main-button">Создать</button>
                                <div class="loading" :class="{ 'd-none': !isLoading }"><img :src="'/assets/img/loading.svg'" alt=""></div>
                            </div>
        
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </section>
</template>


<script>

import HeaderView from '@/components/HeaderView.vue';
import LoadingScreen from '@/components/LoadingScreen.vue';
import MultiSelect from 'primevue/multiselect';

import { useVuelidate } from '@vuelidate/core';
import { required, helpers, minLength, maxLength, minValue, maxValue } from '@vuelidate/validators';
import api from '../../api.js';
import { push } from 'notivue';
import {mask} from 'vue-the-mask';
export default {
    components: {
        HeaderView,
        LoadingScreen,
        MultiSelect,
    },
    setup() {
        return { v$: useVuelidate() }

    },
    directives: {
        mask
    },
    data() {
        return {
            imageUrl: null,
            taskName: 'Выберите задание',
            contentLoading: true,
            isLoading: false,
            tags: [],

            form: {
                title: '',
                description: '',
                prizes: {
                    firstPlace: null,
                    secondPlace: null,
                    thirdPlace: null
                },
                date_registration: '',
                date_start: '',
                date_end: '',
                tags: [],
                task: null,
                image: null,
            },
        }
    },
    computed: {
        dateRegistrationMin() {
            return new Date(new Date().setDate(new Date().getDate() + 7)).toISOString().slice(0, 10) + 'T00:00:00'
        },

        dateRegistrationMax(){
            return new Date(new Date().setMonth(new Date().getMonth()+1)).toISOString().slice(0, 10) + 'T23:59:59'
        },
        dateStartMin() {
            return new Date(new Date(this.form.date_registration || this.dateRegistrationMin).setDate(new Date(this.form.date_registration || this.dateRegistrationMin).getDate() + 7)).toISOString().slice(0, 10) + 'T00:00';
        },
        
        dateStartMax(){
            return new Date(new Date(this.form.date_registration || this.dateRegistrationMax).setMonth(new Date(this.form.date_registration || this.dateRegistrationMax).getMonth()+1)).toISOString().slice(0,10) + 'T23:59'
        },
        dateEndMin(){
            return new Date(new Date(this.form.date_start || this.dateStartMin).setDate(new Date(this.form.date_start || this.dateStartMin).getDate() + 3)).toISOString().slice(0, 10) + 'T00:00';
        },

        dateEndMax(){
            return new Date(new Date(this.form.date_start || this.dateStartMax).setMonth(new Date(this.form.date_start || this.dateStartMax).getMonth() + 1)).toISOString().slice(0,10) + 'T23:59:59'
        }

    },
    methods: {
        previewImage(event) {
            if (!event.target.files[0]) {
                this.imageUrl = null;
                this.form.image = null;
                return
            }
            this.form.image = event.target.files[0];
            
            this.imageUrl = URL.createObjectURL(this.form.image);
        },

        previewTask(event) {
            if (!event.target.files[0]) {
                this.taskName = 'Выберите задание';
                this.form.task = null;
                return
            }
            this.form.task = event.target.files[0];
            this.taskName = this.form.task.name;
        },

        async createEvent() {
            const isFormCorrect = await this.v$.$validate();
            if (!isFormCorrect) {
                this.v$.$errors.forEach(error => {
                    push.error(error.$message);
                });
                return
            }
            this.isLoading = true;

            try {

                const response = await api.post('/events/', this.form, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                });
                console.log(response);
                // this.$router.push({ name: 'event', params: { id: response.data.id } })
            } catch (error) {
        const errors = error.response.data.errors
                    for (const errorInput in errors) {
                        if (Object.hasOwnProperty.call(errors, errorInput)) {
                            push.error(errors[errorInput][0]);
                        }
                    }
            } finally {

                this.isLoading = false;
            }
        },

        async getTags() {
            try {
                const response = await api.get('/tags');
                this.tags = response.data.data;
            } catch (error) {
                console.log(error);
            }
        }
    },

    created(){
        this.getTags();
        this.contentLoading = false;
    },

    validations() {
        return {
            form: {
                title: {
                    required: helpers.withMessage('Поле `Название` обязательно', required),
                    regex: helpers.withMessage('В поле `Название` можно использовать только буквы и цифры', helpers.regex(/^(?!.*\s{2})[а-яА-ЯёЁA-Za-z\d\s\-\_]+$/)),
                    minLength: helpers.withMessage('Поле `Название` должно содержать не менее 2 символов', minLength(2)),
                },
                description: {
                    required: helpers.withMessage('Поле `Описание` обязательно', required),
                    regex: helpers.withMessage('В поле `Описание` можно использовать только буквы и цифры', helpers.regex(/^(?!.*\s{2})[а-яА-ЯёЁA-Za-z\d\s\-\_]+$/)),
                    minLength: helpers.withMessage('Поле `Описание` должно содержать не менее 100 символов', minLength(100)),
                },
                date_registration: {
                    required: helpers.withMessage('Поле `Начало регистрации` обязательно', required),
                },
                date_start: {
                    required: helpers.withMessage('Поле `Начало события` обязательно', required),
                },
                date_end: {
                    required: helpers.withMessage('Поле `Конец события` обязательно', required),
                },
                task: {
                    required: helpers.withMessage('Поле `Задание` обязательно', required),
                },
                image: {
                    required: helpers.withMessage('Поле `Изображение` обязательно', required),
                },
                tags: {
                    required: helpers.withMessage('Поле `Теги` обязательно', required),
                },

                prizes:{
                    firstPlace: {
                        required: helpers.withMessage('Поле `1 место` обязательно', required),
                        regex: helpers.withMessage('Поле `1 место` должно содержать только цифры', helpers.regex(/^\d+$/)),
                        minValue: helpers.withMessage('Поле `1 место` должно быть больше чем 1000', minValue(1000)),
                        maxValue: helpers.withMessage('Поле `1 место` должно быть меньше чем 1000000', maxValue(1000000))
                    },
                    secondPlace: {
                        required: helpers.withMessage('Поле `2 место` обязательно', required),
                        regex: helpers.withMessage('Поле `2 место` должно содержать только цифры', helpers.regex(/^\d+$/)),
                        minValue: helpers.withMessage('Поле `1 место` должно быть больше чем 1000', minValue(1000)),
                        maxValue: helpers.withMessage('Поле `2 место` должно быть меньше чем поле `1 место`', maxValue(this.form.prizes.firstPlace))
                    },
                    thirdPlace: {
                        required: helpers.withMessage('Поле `3 место` обязательно', required),
                        regex: helpers.withMessage('Поле `3 место` должно содержать только цифры', helpers.regex(/^\d+$/)),
                        minValue: helpers.withMessage('Поле `1 место` должно быть больше чем 1000', minValue(1000)),
                        maxValue: helpers.withMessage('Поле `3 место` должно быть меньше чем поле `2 место`', maxValue(this.form.prizes.secondPlace))
                    }
                }
            }
        }
    }
}

</script>

<style lang="scss" scoped>
.container-prizes-place{
    display: flex;
    flex-direction: column;
    gap: 15px;
}
@import "@sass/event-form.scss";
</style>