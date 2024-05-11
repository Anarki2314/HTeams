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
                                    <input type="text" name="tags" required class="form-input" />
                                </div>
                            </div>
                            
                            <div class="container-input task-input">
                                <h4 class="input-title">Задание</h4>
                                <div class="input-content">
                                    <input type="file" name="task" id="task" required class="form-input" @change="previewTask" accept=" application/pdf, application/msword " />
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
                            
                            <div class="container-input prize-input">
                                <h4 class="input-title">Приз</h4>
                                <div class="input-content">
                                    <input type="text" v-model="form.prize" name="prize" required class="form-input" />
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

import { useVuelidate } from '@vuelidate/core';
import { required, helpers } from '@vuelidate/validators';
import api from '../../api.js';
import { push } from 'notivue';
import {mask} from 'vue-the-mask';
export default {
    components: {
        HeaderView,
        LoadingScreen,
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


            form: {
                title: '',
                description: '',
                prize: '',
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
            return new Date(new Date(this.form.date_registration || this.dateRegistrationMin).setDate(new Date(this.form.date_registration || this.dateRegistrationMin).getDate() + 7)).toISOString().slice(0, 10) + 'T00:00:00';
        },
        
        dateStartMax(){
            return new Date(new Date(this.form.date_registration || this.dateRegistrationMax).setMonth(new Date(this.form.date_registration || this.dateRegistrationMax).getMonth()+1)).toISOString().slice(0,10) + 'T23:59:59'
        },
        dateEndMin(){
            return new Date(new Date(this.form.date_start || this.dateStartMin).setDate(new Date(this.form.date_start || this.dateStartMin).getDate() + 7)).toISOString().slice(0, 10) + 'T00:00:00';
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
                console.log(error);
                // const errors = error.response.data.errors
                //     for (const errorInput in errors) {
                //         if (Object.hasOwnProperty.call(errors, errorInput)) {
                //             push.error(errors[errorInput][0]);
                //         }
                //     }
            } finally {

                this.isLoading = false;
            }
        }
    },

    created(){
        this.contentLoading = false;
    }
}

</script>

<style lang="scss" scoped>
@import "@sass/event-form.scss";
</style>