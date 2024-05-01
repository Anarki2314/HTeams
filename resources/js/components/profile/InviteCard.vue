<template>
    <div class="invite-row-card row-card justify-content-sm-between" >
        <router-link :to="'/invites/' + invite.id" class="container-invite-name row-card-container-name">
            <div class="container-invite-image row-card-container-image">
                <img :src="invite.image" alt="" class="invite-image row-card-image">
            </div>
            <h4 class="invite-name row-card-name">{{ invite.email }}</h4>
        </router-link>
        <div class="container-invite-buttons d-flex flew-wrap row-card-container-button">
            <button class="button-view main-button apply-button">Принять</button>
            <button class="button-view secondary-button apply-button">Отклонить</button>
        </div>
    </div>
</template>


<script>

export default {
    props: {
        invite: {
            type: Object,
            required: true

        }
    },

}
</script>

<style scoped>
.container-invite-buttons{
        gap: clamp(15px, 3vw, 30px);
    }
</style>