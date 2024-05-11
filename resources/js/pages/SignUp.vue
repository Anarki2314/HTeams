<template>
    <header class="mb-3 mb-sm-0">
        <img :src="'/assets/img/header-left.png'" alt="" class="header-left-bg text-center" />
        <div class="container-block">
            <div class="d-flex justify-content-center align-items-center justify-content-md-between">
                <div class="container-logo">
                    <router-link to="/">
                        <img :src="'/assets/img/logo.svg'" alt="" />
                    </router-link>
                </div>
            </div>
        </div>
    </header>
    <section class="sign-up-section">
        <div class="container-block">
            <div class="container-form">
                <form action="" class="sign-up-form" @submit.prevent="signUp">
                    <h2 class="block-title text-center">Регистрация</h2>
                    <div class="form-content">
                        <div class="container-inputs">
                            <div class="container-input">
                                <input type="email" v-model.lazy="email" name="email" required class="form-input" />
                                <label for="" class="input-label" :class="{ active: email }">Email</label>
                            </div>
                            <div class="container-input" v-if="!organizer">
                                <input type="text" v-model.lazy="name" name="name" required class="form-input" pattern="^[а-яА-ЯёЁ]+$" title="Только кириллица" minlength="2"/>
                                <label for="" class="input-label" :class="{ active: name }">Имя</label>
                            </div>
                            <div class="container-input" v-if="!organizer">
                                <input type="text" v-model.lazy="surname" name="surname" required class="form-input" pattern="^[а-яА-ЯёЁ]+$" title="Только кириллица" minlength="2"/>
                                <label for="" class="input-label" :class="{ active: surname }">Фамилия</label>
                            </div>
                            <div class="container-input" v-if="organizer">
                                <input type="text" v-model.lazy="orgName" name="orgName" required class="form-input" pattern="^[а-яА-ЯёЁA-Za-z\s]+[а-яА-ЯёЁA-Za-z]+$" title="Только кириллица и латиница"/>
                                <label for="" class="input-label" :class="{ active: orgName }">Название
                                    организации</label>
                            </div>
                            <div class="container-input">
                                <input type="text" v-model.lazy="phone" v-mask="'+7(###)###-##-##'" name="tel" required masked="true" class="form-input" pattern="\+7\(\d{3}\)\d{3}-\d{2}-\d{2}" title="Телефон в формате +7(###)###-##-##"/>
                                <label for="" class="input-label" :class="{ active: phone }">Телефон</label>
                            </div>
                            <div class="container-input">
                                <input type="password" v-model.lazy="password" name="password" required class="form-input" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).+" title="Пароль должен содержать цифру, строчную и прописную букву"/>
                                <label for="" class="input-label" :class="{ active: password }">Пароль</label>
                            </div>
                            <div class="container-input">
                                <input type="password" v-model.lazy="passwordConfirmation" name="passwordConfirmation"
                                    required class="form-input" />
                                <label for="" class="input-label" :class="{ active: passwordConfirmation }">Повторите
                                    пароль</label>
                            </div>

                            <div class="container-input">
                                <input type="checkbox" name="terms" v-model="terms" class="terms-checkbox" id="terms"/>
                                <label for="terms" class="terms-label">Пользовательское соглашение</label>
                            </div>
                        </div>
                        <div class="container-submit text-center">
                            <button type="submit" class="submit-btn main-button" v-if="!loading">
                                Зарегистрироваться
                            </button>
                            <div class="loading" :class="{ 'd-none': !loading }">
                                <img :src="'/assets/img/loading.svg'" alt="" />
                            </div>
                        </div>
                        <div class="container-text text-center">
                            <div class="sign-up-text" v-if="organizer">
                                <span class="sign-up-organization" @click="organizer = false">Зарегистрироваться как
                                    участник</span>
                            </div>
                            <div class="sign-up-text" v-else>
                                <span class="sign-up-organization" @click="organizer = true">Зарегистрироваться как
                                    организатор</span>
                            </div>
                            <div class="sign-up-text">
                                <span>У вас ужё есть аккаунт?
                                    <router-link to="/signIn">Войти</router-link></span>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
</template>

<script>
import { mask } from 'vue-the-mask';
import api from '../api.js';
import { push } from 'notivue';
import { useVuelidate } from '@vuelidate/core';
import { required, email, helpers, sameAs, minLength } from '@vuelidate/validators';
export default {
    setup() {
        return { v$: useVuelidate() }

    },
    directives: { mask },
    data() {
        return {
            loading: false,
            organizer: false,
            email: '',
            name: '',
            surname: '',
            orgName: '',
            phone: '',
            password: '',
            passwordConfirmation: '',
            terms: false,
        }
    },

    validations() {
        const rules = {
            email: {
                required: helpers.withMessage('Поле `Email` обязательно.', required),
                email: helpers.withMessage('Поле `Email` должно быть валидным email адресом.', email),
            },

            phone: {
                required: helpers.withMessage('Поле `Телефон` обязательно.', required),
                regex: helpers.withMessage('Поле `Телефон` должен быть в формате +7(###)###-##-##', helpers.regex(/^\+7\(\d{3}\)\d{3}-\d{2}-\d{2}$/)),
            },

            password: {
                required: helpers.withMessage('Поле `Пароль` обязательно.', required),
                minLengthValue: helpers.withMessage('Поле `Пароль` должно содержать не менее 8 символов.', minLength(8)),
                regex: helpers.withMessage('Поле `Пароль` должен содержать буквы в верхнем и нижнем регистре, цифры.', helpers.regex(/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)./)),
            },

            passwordConfirmation: {
                 required: helpers.withMessage('Поле `Повторите пароль` обязательно.', required),
                 sameAs: helpers.withMessage('Поле `Повторите пароль` должно совпадать с полем `Пароль`.', sameAs(this.password))
            },
            terms: {
                sameAs: helpers.withMessage('Поле `Пользовательское соглашение` обязательно.', sameAs(true))
            },
        }
        if (this.organizer) {
            rules.orgName = {
                required: helpers.withMessage('Поле `Название организации` обязательно.', required),
                regex: helpers.withMessage('Поле `Название организации` должно содержать только кириллицу и латиницу.', helpers.regex(/^(?!.*\s{2})[а-яА-ЯёЁA-Za-z\s]+$/)),
                minLengthValue: helpers.withMessage('Поле `Название организации` должно содержать не менее 2 букв.', minLength(2))
            }
        } else {
            rules.name = { 
                required: helpers.withMessage('Поле `Имя` обязательно.', required),
                regex: helpers.withMessage('Поле `Имя` должно содержать только кириллицу.', helpers.regex(/^[а-яА-ЯёЁ]+$/u)),
                minLengthValue: helpers.withMessage('Поле `Имя` должно содержать не менее 2 букв.', minLength(2))
            }

            rules.surname = {
                required: helpers.withMessage('Поле `Фамилия` обязательно.', required),
                regex: helpers.withMessage('Поле `Фамилия` должно содержать только кириллицу.', helpers.regex(/^[а-яА-ЯёЁ]+$/u)),
                minLengthValue: helpers.withMessage('Поле `Фамилия` должно содержать не менее 2 букв.', minLength(2))
            }

        }
        return rules
    },

    methods: {

        async signUp() {
            const isFormCorrect = await this.v$.$validate();
            if (!isFormCorrect) {
                this.v$.$errors.forEach(error => {
                    push.error(error.$message);
                });
                return
            }
            this.loading = true;
            try {
                const data = {
                    email: this.email,
                    password: this.password,
                    password_confirmation: this.passwordConfirmation,
                    phone: this.phone,
                }
                if (this.organizer) {
                    data.orgName = this.orgName;
                    data.role_id = 2;
                } else {
                    data.name = this.name;
                    data.surname = this.surname;
                    data.role_id = 1;
                }

                const response = await api.post('/auth/sign-up', {
                    ...data
                })
                localStorage.setItem('token', response.data.data.token);
                localStorage.setItem('user', JSON.stringify(response.data.data.user));

                this.$store.commit('login', response.data.data);
                push.success(response.data.message);
                this.$router.push({ name: 'home' });
            } catch (error) {
                if (error.status === 401) {
                    push.error(error.data.message);
                }
                if (error.status === 422) {
                    const errors = error.data.errors
                    for (const errorInput in errors) {
                        if (Object.hasOwnProperty.call(errors, errorInput)) {
                            push.error(errors[errorInput][0]);
                        }
                    }
                }
            } finally {
                this.loading = false;
            }

        },
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

.header-left-bg {
    position: absolute;
    top: 0;
    left: 0;
    z-index: -1;
}

.container-form {
    margin: 0 auto;
    width: clamp(280px, 95%, 480px);
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
    font-family: "Montserrat Regular", sans-serif;
    padding: 10px 0;
}

.input-label {
    position: absolute;
    width: max-content;
    padding: 0px 5px;
    background-color: #1b1b1b;
    font-size: var(--size-text);
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    transition: all 0.3s ease;
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

.terms-checkbox {
    position: absolute;
    opacity: 0;
    cursor: pointer;
    height: 0;
    width: 0;
}

.terms-label {
    font-size: var(--size-text);
    cursor: pointer;
}

.terms-label::before {
    content: "";
    display: inline-block;
    width: 48px;
    height: 48px;
    border: 2px solid #313131;
    border-radius: 5px;
    margin-right: 10px;
    vertical-align: middle;
}

#terms:checked+.terms-label::before {
    background-image: url("/assets/img/check.png");
    background-color: #313131;
    background-position: center;
    background-repeat: no-repeat;
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
    transition: all 0.3s ease;
}

.submit-btn:hover {
    background-color: var(--color-main);
    color: #1b1b1b;
}

.sign-up-text * {
    color: #666666;
    font-family: "Montserrat Regular", sans-serif;
    font-size: var(--size-text);
}

.container-text {
    margin-top: 10px;
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.sign-up-organization,
.sign-up-text a {
    cursor: pointer;
    text-decoration: underline;
}
</style>
