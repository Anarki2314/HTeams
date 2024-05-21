<template>
    <div class="user-row-card row-card justify-content-sm-between">
        <router-link :to=" url + user.id" class="container-user-name row-card-container-name">
            <div class="container-user-image row-card-container-image">
                <img :src="user.avatar?.path" alt="" class="user-image row-card-image">
            </div>
            <h4 class="user-email; row-card-name">{{ user.email }}</h4>
        </router-link>
        <div class="container-user-apply-button row-card-container-button">
            <router-link :to="url + user.id" class="button-view secondary-button">Просмотр</router-link>
        </div>
    </div>
</template>


<script>

export default {
    props: {
        user: {
            type: Object,
            required: true
        },
        url: {
            type: String,
            required: true
        }
    },

}
</script>

<style scoped>

</style>