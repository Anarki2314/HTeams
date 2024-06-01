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

    <section class="sign-in-section ">
        <div class="container-block">
            <div class="container-form">
                <form action="" class="sign-in-form" @submit.prevent="signIn">
                    <h2 class="block-title text-center">Вход</h2>
                    <div class="form-content">
                        <div class="container-inputs">

                            <div class="container-input">
                                <input type="email" v-model="email" name="email" required class="form-input">
                                <label for="" class="input-label" :class="{ 'active': email }">Email</label>
                            </div>
                            
                            <div class="container-input">
                                <input type="password" v-model="password" name="password" required class="form-input">
                                <label for="" class="input-label" :class="{ 'active': password }">Пароль</label>
                            </div>


                        </div>
                        <div class="container-submit text-center">
                            <button type="submit" class="submit-btn main-button" v-if=" !loading">Войти</button>
                            <div class="loading" :class="{ 'd-none': !loading }"><img :src="'/assets/img/loading.svg'" alt=""></div>

                        </div>
                        <div class="container-text text-center">
                            <div class="sign-in-text"><span>У вас нет аккаунта? <router-link to="/signUp">Зарегистрироваться</router-link></span></div>

                            <router-link  to="/forgot-password" class="sign-in-text">Забыли пароль?</router-link>
                        </div>
                        
                    </div>
                </form>
            </div>
        </div>
    </section>



</template>

<script>
import api from '../api.js';
import {push} from 'notivue';
import {useVuelidate} from '@vuelidate/core';
import {required, email, helpers,minLength} from '@vuelidate/validators';

export default {
    setup() {
        return { v$: useVuelidate() }
    },
    data() {
        return {
            loading: false,
            email: '',
            password: '',
        }
    },

    validations() {
        return {
            email: {
                required: helpers.withMessage('Поле `Email` обязательно', required),
                email: helpers.withMessage('Поле `Email` должно быть валидным email адресом.', email),
            },

            password: {
                required: helpers.withMessage('Поле `Пароль` обязательно', required),
                minLength: helpers.withMessage('Поле `Пароль` должно содержать не менее 8 символов', minLength(8))
            },
        }
    },

    methods: {
        async signIn() {
            const isFormCorrect = await this.v$.$validate();
            if (!isFormCorrect) {
                this.v$.$errors.forEach((error) => {
                    push.error(error.$message)
                });
                return
            }
            this.loading = true;
            try {
                const response = await api.post('/auth/sign-in', {
                    email: this.email,
                    password: this.password,
                })
                localStorage.setItem('token', response.data.data.token);
                localStorage.setItem('user', JSON.stringify(response.data.data.user));
                this.$store.commit('login', response.data.data);
                if (response.data.data.user.isVerified) {
                    this.$router.push({ name: 'home' }  );
                } else {
                    this.$router.push({ name: 'email-verify' }  );
                }
            } catch (error) {
                push.error(error.data.message)
            } finally {
                this.loading = false;
            }
        }
    }

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


.submit-btn {
    width: 75%;
    background: none;
    border: 2px solid var(--color-main);
    color: var(--color-main);
    font-size: var(--size-text);
    padding: 10px 0;
    border-radius: 5px;
    cursor: pointer;
    transition: all .3s ease;
}

.submit-btn:hover {
    background-color: var(--color-main);
    color: #1B1B1B;
}
.sign-in-text *, .sign-in-text:any-link{
    color: #666666;
    font-family: 'Montserrat Regular', sans-serif;
    font-size: clamp(14px, 3vw, 20px);
}

.container-text {
    margin-top: 10px;
}
.sign-in-text a:any-link, .sign-in-text:any-link {
    text-decoration: underline;
}
</style>