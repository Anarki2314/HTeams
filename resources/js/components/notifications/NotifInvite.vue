<template>
    <div class="notif-row-card justify-content-center justify-content-sm-between " >
        <h4 class="invite-name notif-row-card-name" v-if="invite.type == 'join'">Пользователь <span >
            {{ invite.email }}
        </span> хочет присоединиться к команде</h4>
        <h4 class="invite-name notif-row-card-name" v-if="invite.type == 'invite'">Команда <router-link to="/teams/{{ invite.teamId }}" class="info-button">{{ invite.teamName }}</router-link>
        приглашает вас вступить в команду</h4>
        <div class="container-invite-buttons d-flex flew-wrap notif-row-card-container-buttons">
            <button class="button-view dark-button notif-button" @click="sendChoice(true)" v-if="!isLoading">Принять</button>
            <button class="button-view dark-outline-button notif-button" @click="sendChoice(false)" v-if="!isLoading">Отклонить</button>
            <div class="loading" :class="{ 'd-none': !isLoading }"><img :src="'/assets/img/loading.svg'" alt=""></div>

        </div>
    </div>
</template>


<script>
import api from '../../api.js';
import {push} from 'notivue'
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
            this.invite.choice = choice;
            try {
                this.isLoading = true
                if (this.invite.type == 'join') {
                    const response = await api.post('/profile/team/invite/team-choice', {
                        choice: choice,
                        from_user: this.invite.userId
                    });
                    push.info(response.data.message);
                } else if (this.invite.type == 'invite') {
                    const response = await api.post('/profile/team/invite/user-choice', {
                        choice: choice,
                        team_id: this.invite.teamId
                    });
                    push.info(response.data.message);

                }
            } catch (error) {
                push.error(error.data.message);
            } finally {
                this.isLoading = false
                const responseUser = await api.post("/auth/refresh");
                localStorage.setItem("token", responseUser.data.data.token);
                localStorage.setItem("user", JSON.stringify(responseUser.data.data.user));
                this.$store.commit('login', responseUser.data.data);
                this.$emit('getNotifications', this.invite);
            }
        }


    }
}
</script>
