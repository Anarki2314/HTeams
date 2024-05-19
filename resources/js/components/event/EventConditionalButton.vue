<template>
        <div class="container-event-participate d-flex">

                <button type="button" class="button-view main-button" v-if="canJoin" @click="joinEvent">
                    Принять участие
                </button>

                <button type="button" class="button-view darken-button" v-if="canCancelJoin"
                    @click="cancelJoinEvent">
                    Отменить участие
                </button>

                <button type="button" class="button-view main-button" v-if="canDownload">
                    Скачать задание
                </button>

                <a href="#info" class="button-view main-button" v-if="haveResults">Итоги</a>

        </div>
</template>

<script>


export default {

    props: {
        event: {
            type: Object,
            required: true
        }
        
    },

    computed: {

        isAuth() {
            return this.$store.getters.isLoggedIn;
        },
        isUser() {
            return this.$store.getters.isUser;
        },

        isLeader() {
            return this.$store.getters.isLeader;
        },
        haveTeam() {
            return this.$store.getters.haveTeam;
        },

        canJoin() {
            return (this.isUser && this.haveTeam) && (!this.event.isJoined && this.event.status == 'Регистрация'); 
        },

        canCancelJoin() {
            return (this.isUser && this.haveTeam && this.isLeader) && (this.event.isJoined && this.event.status == 'Регистрация'); 
        },

        canDownload() {
            return (this.isUser && this.haveTeam) && (this.event.isJoined && this.event.status == 'Началось');
        },

        haveResults() {
            return this.event.status == 'Итоги';
        }
    },

    methods: {

        joinEvent() {
            if (!this.isAuth) {
                this.$emit('openModal', 'modal-auth');
                return
            }    
            if (!this.isLeader){
                this.$emit('openModal', 'modal-leader-event');
                return
            }

            this.$emit('openModal', 'modal-join-event');
        },

        cancelJoinEvent() {
            if (!this.isAuth) {
                this.$emit('openModal', 'modal-auth');
                return
            }    

            this.$emit('openModal', 'modal-cancel-join-event');
        }
    },

}
</script>
