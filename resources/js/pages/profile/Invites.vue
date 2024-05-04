<template>
    <loading-screen v-if="contentLoading"/>
    <header-view/>
    <section class="invites-section">
        <div class="container-block">
            <div class="container-invites">
                <div class="container-back">
                        <a @click="$router.go(-1)" class="router-link-underline">Назад</a>
                    </div>  
                <div class="form-search mb-3 text-end ">
                    <div class="container-search">
                        <input type="text" class="form-input" name="search" id="search" placeholder="Поиск" v-model="search">
                        <button class="search-btn"> <img :src="'/assets/img/search.svg'" alt=""> </button>
                    </div>
                </div>
                <div class="container-invites-title d-flex align-items-center justify-content-between">
                    <h3 class="block-title text-center text-lg-start">Приглашения</h3>
                </div>
                <div class="container-sort"></div>
                <div class="container-invites-items d-flex flex-column flex-wrap">

                    <invite-card v-for="invite, index in invites" :key="index" :invite="invite" @sendChoice="sendChoice"/>

                </div>
            </div>
        </div>
    </section>
    <footer-view/>
</template>

<script>
import HeaderView from '@/components/HeaderView.vue';
import FooterView from '@/components/FooterView.vue'
import EventCard from '@/components/event/EventCard.vue';
import InviteCard from '@/components/profile/InviteCard.vue';
import LoadingScreen from '@/components/LoadingScreen.vue';

import api from '../../api.js';
import {push} from 'notivue';
export default {
    components: {
        HeaderView,
        FooterView,
        EventCard,
        InviteCard,
        LoadingScreen
    },
    data() {
        return {
            search: '',
            invites: [  
            ],
            contentLoading: true
        }
    },
    methods: {
        async getInvites() {
            this.contentLoading = true;
            try {
                const response = await api.get('profile/team/invites');
                this.invites = response.data.data.invites;
            } catch (error) {
                console.log(error);
            } finally {
                this.contentLoading = false;
            }
        },

        async sendChoice(invite) {

            try {
                const response = await api.post('/profile/team/invite/team-choice', {
                    choice: invite.choice,
                    from_user: invite.userId
                });
                push.info(response.data.message);
            } catch (error) {
                push.error(error.data.message);
            } finally {
                this.getInvites();
            }
        }
    },

    created() {
        this.getInvites();
    }
}
</script>

<style scoped>
    .container-invites {
        width: clamp( 280px , 95% , 1200px);
        margin: 5dvw auto 0;

    }
    .container-invites-title {
    margin-bottom: clamp( 20px , 3vw , 40px );
    }
    .block-title {
        margin-bottom: 0;
    }

    .container-search{
        position: relative;
    }


    .filters-btn {
        background: none;
    }

    .container-invites-items{
        min-height: 80dvh;
        gap: clamp(  40px , 4vw , 100px);
    }

    

</style>