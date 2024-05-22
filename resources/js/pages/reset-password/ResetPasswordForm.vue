<template>
    <header class="mb-3 mb-sm-0">
        <img :src="'/assets/img/header-left.png'" alt="" class="header-left-bg  text-center">
        <div class="container-block">
            <div class="d-flex justify-content-center align-items-center justify-content-md-between">
                <div class="container-logo">
                    <router-link to="/">
                        <img :src="'/assets/img/logo.svg'" alt="">
                    </router-link>
                </div>


            </div>
        </div>
    </header>

    <section class="reset-password-section ">
        <div class="container-block">
            <div class="container-form">
                <form action="" class="reset-password-form" @submit.prevent="resetPassword">
                    <h2 class="block-title text-center">Сброс пароля</h2>
                    <div class="form-content">
                        <div class="container-inputs">

                            <div class="container-input">
                                <input type="password" v-model="password" name="password" required class="form-input">
                                <label for="" class="input-label" :class="{ 'active': password }">Пароль</label>
                            </div>
                            <div class="container-input">
                                <input type="password" v-model="password_confirmation" name="password_confirmation" required class="form-input">
                                <label for="" class="input-label" :class="{ 'active': password_confirmation }">Повтор пароля</label>
                            </div>


                        </div>
                        <div class="container-submit text-center">
                            <button type="submit" class="button-view main-button" v-if=" !loading">Изменить</button>
                            <div class="loading" :class="{ 'd-none': !loading }"><img :src="'/assets/img/loading.svg'" alt=""></div>

                        </div>
                        <div class="container-text text-center">

                            <router-link  to="/signIn" class="reset-password-text">Назад</router-link>
                        </div>
                        
                    </div>
                </form>
            </div>
        </div>
    </section>



</template>

<script>
import api from '../../api.js';
import {push} from 'notivue';
import {useVuelidate} from '@vuelidate/core';
import {required, helpers, minLength, sameAs} from '@vuelidate/validators';

export default {
    setup() {
        return { v$: useVuelidate() }
    },
    data() {
        return {
            loading: false,
            password: '',
            password_confirmation: '',

            email: this.$route.query.email,
            token: this.$route.params.token,

        }
    },

    validations() {
        return {
            password: {
                required: helpers.withMessage('Поле `Пароль` обязательно.', required),
                minLengthValue: helpers.withMessage('Поле `Пароль` должно содержать не менее 8 символов.', minLength(8)),
                regex: helpers.withMessage('Поле `Пароль` должен содержать буквы в верхнем и нижнем регистре, цифры.', helpers.regex(/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)./)),
            },

            password_confirmation: {
                 required: helpers.withMessage('Поле `Повторите пароль` обязательно.', required),
                 sameAs: helpers.withMessage('Поле `Повторите пароль` должно совпадать с полем `Пароль`.', sameAs(this.password))
            },
        }
    },

    methods: {
        async resetPassword() {
            const isFormCorrect = await this.v$.$validate();
            if (!isFormCorrect) {
                this.v$.$errors.forEach((error) => {
                    push.error(error.$message)
                });
                return
            }
            this.loading = true;
            try {
                const response = await api.post('/reset-password/', {
                    password: this.password,
                    password_confirmation: this.password_confirmation,
                    token: this.token,
                    email: this.email,
                })


                push.success(response.data.message);
                localStorage.removeItem('reset-email', this.email);
                this.$router.push({ name: 'signIn' });
            } catch (error) {
                push.error(error.data.message)
            } finally {
                this.loading = false;
            }
        }
    },


}

</script>

<style scoped>
ul {
    list-style: none;
    display: flex;
    gap: 50px;
}

header {
    position: relative;
    padding: 40px 0 0;
    font-size: var(--size-text);
}
section{
    position: static;
}

.header-left-bg {
    position: absolute;
    top: 0;
    left: 0;
    z-index: -1;
}
.container-form {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    margin: 0 auto;
    width: clamp(280px, 95%, 480px)
}

.container-inputs {
    display: flex;
    flex-direction: column;
    gap: 30px;
    margin-bottom: 30px;
}

.container-input {
    position: relative;
}

.form-input {
    color: #f4f4f4;
    font-size: var(--size-text);
    
    width: 100%;
    background: none;
    outline: none;
    border: 2px solid #313131;
    text-align: center;
    border-radius: 5px;
    font-family: 'Montserrat Regular', sans-serif;
    padding: 10px 0;
}

.input-label {
    position: absolute;
    width: max-content;
    padding: 0px 5px;
    background-color: #1B1B1B;
    font-size: var(--size-text);
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    transition: all .3s ease;
    z-index: -1;
}

.form-input:focus+.input-label {
    top: 0;
    z-index: 1;
}


.input-label.active {
    top: 0;
    z-index: 1;
}


.button-view {
    width: 75%;
    padding: 10px 0;
}
.reset-password-text *, .reset-password-text:any-link{
    color: #666666;
    font-family: 'Montserrat Regular', sans-serif;
    font-size: var(--size-text);
}

.container-text {
    margin-top: 10px;
}
.reset-password-text a:any-link, .reset-password-text:any-link {
    text-decoration: underline;
}
</style>