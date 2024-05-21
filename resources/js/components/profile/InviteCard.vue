<template>
    <div class="invite-row-card row-card justify-content-sm-between" >
            <div class="container-invite-image row-card-container-image">
                <img :src="invite.image" alt="" class="invite-image row-card-image">
            </div>
            <h4 class="invite-name row-card-name">{{ invite.email }}</h4>
        <div class="container-invite-buttons d-flex flew-wrap row-card-container-button">
            <button class="button-view main-button apply-button" @click="sendChoice(true)" v-if="!isLoading">Принять</button>

            <button class="button-view secondary-button apply-button" @click="sendChoice(false)" v-if="!isLoading">Отклонить</button>

            <div class="loading" :class="{ 'd-none': !isLoading }"><img :src="'/assets/img/loading.svg'" alt=""></div>

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

    data() {
        return {
            isLoading: false
        }
    },
    methods: {
        async sendChoice(choice) {
            this.isLoading = true
            this.invite.choice = choice
            try {
                this.$emit('sendChoice', this.invite);
            } catch (error) {
                push.error(error.data.message);
            } finally {
                this.isLoading = false
            }
            
            
        }
    }
}
</script>

<style scoped>
.container-invite-buttons{
        gap: clamp(15px, 3vw, 30px);
    }
</style>