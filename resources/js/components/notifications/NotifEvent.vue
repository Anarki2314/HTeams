<template>
    <div class="notif-row-card justify-content-center justify-content-sm-between " >
        <h4 class="invite-name notif-row-card-name" v-if="event.type == 'started'">
            Мероприятие
            <router-link :to="'/events/' + event.event.id" class="info-button" @click="readNotif">
            {{ event.event.title }}
            </router-link> 
            началось
        </h4>
        <h4 class="invite-name notif-row-card-name" v-if="event.type == 'created'">
            Мероприятие
            <router-link :to="'/_admin/requests/' + event.event.id" class="info-button" @click="readNotif">
            {{ event.event.title }}
            </router-link> 
            ждет проверки
        </h4>
        <h4 class="invite-name notif-row-card-name" v-if="event.type == 'finished'">
            Мероприятие
            <router-link :to="'/events/' + event.event.id" class="info-button" @click="readNotif">
            {{ event.event.title }}
            </router-link> 
            завершено
        </h4>
        <h4 class="invite-name notif-row-card-name" v-if="event.type == 'results'">
            У мероприятия 
            <router-link :to="'/events/' + event.event.id" class="info-button" @click="readNotif">
            {{ event.event.title }}
            </router-link> 
            опубликованы результаты
        </h4>
        <h4 class="invite-name notif-row-card-name" v-if="event.type == 'cancelled'">
            Мероприятие 
            <span class="info-button">
            {{ event.event.title }}
            </span> 
            отменено. Причина: {{ event.message || 'По решению организатора' }}
        </h4>

        <div class="container-invite-buttons d-flex flew-wrap notif-row-card-container-buttons">
            <router-link :to="'/events/' + event.event.id" class="button-view dark-button notif-button" v-if="(event.type == 'results' || event.type == 'started' || event.type == 'finished') && !isLoading" @click="readNotif">Просмотр</router-link>
            <router-link :to="'/_admin/requests/' + event.event.id" class="button-view dark-button notif-button" v-if="(event.type == 'created') && !isLoading" @click="readNotif">Просмотр</router-link>
            <button class="button-view dark-button notif-button" v-if="event.type == 'cancelled' && !isLoading" @click="readNotif">Ок</button>
            <div class="loading" :class="{ 'd-none': !isLoading }"><img :src="'/assets/img/loading.svg'" alt=""></div>
        </div>
    </div>  
</template>


<script>
import api from '../../api.js';
import {push} from 'notivue'
export default {
    props: {
        event: {
            type: Object,
            required: true
        }
    },

    data() {
        return {
            isLoading: false,
        }
    },

    methods: {
        async readNotif() {
            try {
                this.isLoading = true;
                const response = await api.delete('/notifications/' + this.event.id)
                this.$emit('getNotifications');
                this.$emit('closeModal');
            } catch(error){
                console.log(error);
            } finally{
                this.isLoading = false;
            }
            
        }

    }
}
</script>

<style scoped>
.router-link-active {
    border: none;
    
}
</style>