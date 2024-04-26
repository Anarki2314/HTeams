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

    <section class="sign-in-section">
        <div class="container-block">
            <div class="container-form">
                <form action="" class="sign-in-form">
                    <h2 class="block-title text-center">Вход</h2>
                    <div class="form-content">
                        <div class="container-inputs">

                            <div class="container-input">
                                <input type="email" v-model="email" required class="form-input">
                                <label for="" class="input-label" :class="{ 'active': email }">Email</label>
                            </div>
                            
                            <div class="container-input">
                                <input type="password" v-model="password" required class="form-input">
                                <label for="" class="input-label" :class="{ 'active': password }">Пароль</label>
                            </div>


                        </div>
                        <div class="container-submit text-center">
                            <button type="submit" class="submit-btn main-button">Войти</button>
                        </div>
                        <div class="container-text text-center">
                            <div class="sign-in-text"><span>У вас нет аккаунта? <router-link to="/signUp">Зарегистрироваться</router-link></span></div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>



</template>

<script>
export default {
    data() {
        return {
            email: '',
            password: '',
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
.sign-in-text * {
    color: #666666;
    font-family: 'Montserrat Regular', sans-serif;
    font-size: var(--size-text);
}

.container-text {
    margin-top: 10px;
}
.sign-in-text a {
    text-decoration: underline;
}
</style>