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
                <h1 class="email-verify-title">Почта подтверждена</h1>
                <h2 class="email-verify-subtitle">Через {{ declOfNum(coolDown, ['секунду', 'секунды', 'секунд'], true) }} вы будете перенаправлены на главную страницу</h2>

            </div>
        </div>
    </section>

</template>

<script>

import api from '../../api.js';
import { push } from 'notivue';
export default {
    name: "EmailVerified",
    data() {
        return {
            isLoading: false,
            coolDown: 5,

        }
    },
    methods: {
        declOfNum(number, titles, full = false) {
            const cases = [2, 0, 1, 1, 1, 2]

            const result = titles[(number % 100 > 4 && number % 100 < 20) ? 2 : cases[(number % 10 < 5) ? number % 10 : 5]]
            return full ? `${number} ${result}` : result
        },
        coolDownInterval() {
            if (this.coolDown > 0) {
                setTimeout(() => {
                    this.coolDown--;
                    this.coolDownInterval();
                }, 1000);
            } else {
                this.$router.push({ name: 'home' });
            }
        },
    },

    mounted() {
        this.coolDownInterval();
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
.email-verify-section {
    min-height: 100dvh;
    display: flex;
    justify-content: center;
    align-items: center;
}

.container-email-verify {
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    gap: clamp(20px, 3vw, 40px);
}

.email-verify-title {
    font-size: var(--size-title);
    color: var(--color-main);

}

.email-verify-subtitle {
    font-size: clamp(20px, 3vw, 30px);
}
</style>