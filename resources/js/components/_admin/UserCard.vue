<template>
    <div class="user-row-card row-card justify-content-sm-between" v-if="hasRequiredProps">
        <router-link :to="'/_admin/users/' + user.id" class="container-user-name row-card-container-name">
            <div class="container-user-image row-card-container-image">
                <img :src="user.image" alt="" class="user-image row-card-image">
            </div>
            <h4 class="user-email; row-card-name">{{ user.email }}</h4>
        </router-link>
        <div class="container-user-apply-button row-card-container-button">
            <router-link :to="'/_admin/users/' + user.id" class="button-view secondary-button">Просмотр</router-link>
        </div>
    </div>
</template>


<script>

export default {
    props: {
        user: {
            type: Object,
            required: true

        }
    },

    computed: {
        hasRequiredProps() {
            const { id, email, image } = this.user
            return id && email && image;
        }
    }
}
</script>

<style scoped>

</style>