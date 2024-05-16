<template>

<div class="admin-nav">
        <div class="container-block">
            <div class="container-admin-nav">
                <div class="container-admin-nav-items d-flex flex-wrap">
                    <router-link to="/_admin/" class="admin-nav-item button-view main-button">Заявки на создание соревнований</router-link>
                    <router-link to="/_admin/teams" class="admin-nav-item button-view main-button">Команды</router-link>
                    <router-link to="/_admin/users" class="admin-nav-item button-view main-button">Пользователи</router-link>
                    <router-link to="/_admin/organizers" class="admin-nav-item button-view main-button">Организаторы</router-link>
                    <router-link to="/_admin/tags" class="admin-nav-item button-view main-button">Теги</router-link>
                    

                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
.admin-nav {
    margin: 7dvw auto clamp(20px, 3.5vw, 60px);
}

.container-admin-nav-items {
    gap: clamp( 20px , 3vw , 40px);
}
</style>