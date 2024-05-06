<template>

    <section class="not-found-section">
        <div class="container-block">
            <div class="container-not-found">
                <h1 class="not-found-title">Ошибка 404</h1>
                <h2 class="not-found-subtitle">Страница не найдена</h2>
                <router-link to="/" class="button-view main-button">Вернуться на главную</router-link>
            </div>
        </div>
    </section>
    
</template>

<style scoped>

    .not-found-section{
        min-height: 100dvh;
        display: flex;
        justify-content: center;
        align-items: center;
    }
    .container-not-found{
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        gap: clamp( 20px , 3vw , 40px );
    }
    .not-found-title{
        font-size: var(--size-title);
        color: var(--color-main);

    }

    .not-found-subtitle{
        font-size: clamp( 20px , 3vw , 30px );
    }

</style>