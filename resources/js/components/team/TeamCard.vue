<template>
    <div class="team-row-card row-card justify-content-sm-between">
        <router-link :to="'/teams/' + team.id" class="container-team-name row-card-container-name">
            <div class="container-team-image row-card-container-image">
                <img :src="team.image" alt="" class="team-image row-card-image">
            </div>
            <h4 class="team-name row-card-name">{{ team.title }}</h4>
        </router-link>
        <div class="container-team-apply-button row-card-container-button">
            <button class="button-view secondary-button apply-button" @click="sendApplication" v-if="!haveTeam && team.members_count < 5 && (isUser || !isAuth)">Подать заявку на вступление</button>
        </div>
    </div>
</template>


<script>

export default {
    data() {

        return {

        }
    },
    props: {
        team: {
            type: Object,
            required: true

        }
    },

    computed: {
        isAuth() {
            return this.$store.getters.isLoggedIn
        },

        haveTeam(){
            return this.$store.getters.haveTeam
        },

        isUser() {
            return this.$store.getters.isUser
        },

    },
    methods: {
        async sendApplication() {
            if (!this.isAuth) {
                this.$emit('openModal', 'modal-auth');
                return
            }
            this.$emit('join', this.team.id);

        }
    },

}
</script>

<style scoped>

</style>