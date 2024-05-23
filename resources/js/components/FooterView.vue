<template>
    <footer >
        <div class="footer-container-img position-relative overflow-hidden">

            <img :src="'/assets/img/footer-bg.png'" alt="" class="footer-img">
        </div>

        <div class="footer-container-content">
            <div class="container-block">
                <div class="footer-container d-flex justify-content-center text-center text-lg-start justify-content-md-between flex-wrap">
                <div class="footer-container-navigation">
                    <h3 class="block-title">Навигация</h3>
                    <ul>
                        <li><router-link to="/" class="router-link-underline">Главная</router-link></li>
                        <li><router-link to="/events" class="router-link-underline">Соревнования</router-link></li>
                        <li><router-link to="/teams" class="router-link-underline">Команды</router-link></li>
                    </ul>
                </div>
                <div class="footer-container-contacts">
                    <h3 class="block-title">Контакты</h3>
                    <div class="contacts-items">
                        <div class="contacts-item">
                            <div class="contacts-item-img-container">
                                <img :src="'/assets/img/tme.png'" alt="" class="contacts-item-img">
                            </div>
                            <!-- <a href="https://t.me/hteams">@HTeams</a> -->
                            <a href="#">@HTeams</a>
                        </div>
                        <div class="contacts-item">
                            <div class="contacts-item-img-container">
                                <img :src="'/assets/img/vk.png'" alt="" class="contacts-item-img">
                            </div>
                            <!-- <a href="https://vk.com/hteams">HTeams</a> -->
                            <a href="#">HTeams</a>
                        </div>
                        <div class="contacts-item">
                            <div class="contacts-item-img-container">
                                <img :src="'/assets/img/mail.png'" alt="" class="contacts-item-img">
                            </div>
                            <!-- <a href="mailto:hteams@gmail.com">hteams@gmail.com</a> -->
                            <a href="#">hteams@gmail.com</a>
                        </div>
                        </div>
                </div>
            </div>
        </div>
    </div>
    </footer>

</template>

<style scoped>
    footer {
        margin-top: 40px;
        position: relative;
    }
    a:any-link {
        font-size: var(--size-text);

    }
    ul {
        list-style: none;
        display:flex;
        flex-direction: column;
        gap: clamp(10px, 2vw , 20px);
    }
    .footer-container-content {
        position: absolute;
        width: 100%;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        z-index: 1;
    }
    .contacts-items {
        display:flex;
        flex-direction: column;
        gap: clamp(10px, 2vw , 20px);
    }
    .contacts-item {
        display:flex;
        align-items: center;
        gap: clamp(10px, 2vw , 20px);
    }
    .footer-img {
        max-width: min-content;
        user-select: none;
        pointer-events: none;
    }
    .footer-container {
        gap: clamp( 30px , 3vw , 40px);
    }
</style>