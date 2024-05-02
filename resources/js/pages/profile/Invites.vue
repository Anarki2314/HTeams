<template>
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

import api from '../../api.js';
export default {
    components: {
        HeaderView,
        FooterView,
        EventCard,
        InviteCard
    },
    data() {
        return {
            search: '',
            invites: [  
            ]
        }
    },
    methods: {
        async getInvites() {
            try {
                const response = await api.get('profile/team/invites');
                this.invites = response.data.data.invites;
            } catch (error) {
                console.log(error);
            }
        },

        async sendChoice(invite) {

            try {
                const response = await api.post('/profile/team/invite/team-choice', {
                    choice: invite.choice,
                    from_user: invite.id
                });
                console.log(response.data);
            } catch (error) {
                console.log(error);
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

    #search {
        background: transparent;
        padding: 5px 45px 5px 10px;
        border: 1px solid var(--color-main);
        border-radius: 5px;
        width: clamp( 280px , 95% , 400px);
        color: #f4f4f4;
        font-size: var(--size-text);
    }
    #search::placeholder {
        color: #313131;
    }

    .search-btn {
        position: absolute;
        top: 50%;
        right: 10px;
        background: transparent;
        border: none;
        transform: translateY(-50%);
    }

    .filters-btn {
        background: none;
    }

    .container-invites-items{
        min-height: 80dvh;
        gap: clamp(  40px , 4vw , 100px);
    }

    

</style>