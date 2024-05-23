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
    <section class="email-verify-section">
        <div class="container-block">
            <div class="container-email-verify text-center">
                <h1 class="email-verify-title">Подтвердите почту</h1>
                <h2 class="email-verify-subtitle">Письмо с подтверждением было отправлено на указанную почту, если вы не получили письмо, проверьте спам</h2>
                <button  class="button-view main-button" @click="sendEmail" v-if="!isLoading" :disabled="coolDown">Отправить еще раз</button>
                <div class="loading" :class="{ 'd-none': !isLoading }"><img :src="'/assets/img/loading.svg'" alt=""></div>

            </div>
        </div>
    </section>
    
</template>

<script>

    import api from '../../api.js';
    import { push } from 'notivue';
    export default {
        name: "EmailVerify",
        data() {
            return {
                isLoading: false,
                coolDown: false,
                
            }
        },
        computed: {
            isVerified() {
                return this.$store.getters.isVerified;
            }
        },
        methods: {
            coolDownInterval() {
                if (this.coolDown > 0) {
                    setTimeout(() => {
                        this.coolDown--;
                        this.coolDownInterval();
                    }, 1000);
                }
            },
            async sendEmail() {
                this.isLoading = true;
                this.coolDown = 60;
                try {
                    const response = await api.post('/email/verify/resend');
                    push.success(response.data.message);
                    this.coolDownInterval();
                } catch (error) {
                    push.error(error.data.message);
                } finally {
                    this.isLoading = false;
                }
            }
        },

        created() {
            if (this.isVerified) {
                this.$router.push('/');
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
    position: absolute;
    padding: 40px 0 0;
    width: 100%;
    z-index: 100;   
    font-size: var(--size-text);
}

.header-left-bg {
    position: absolute;
    top: 0;
    left: 0;
    z-index: -1;
}
    .email-verify-section{
        min-height: 100dvh;
        display: flex;
        justify-content: center;
        align-items: center;
    }
    .container-email-verify{
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        gap: clamp( 20px , 3vw , 40px );
    }
    .email-verify-title{
        font-size: var(--size-title);
        color: var(--color-main);

    }

    .email-verify-subtitle{
        font-size: clamp( 20px , 3vw , 30px );
    }

</style>