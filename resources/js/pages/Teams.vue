<template>
    <header-view/>
    <section class="teams-section">
        <div class="container-block">
            <div class="container-teams">
                <div class="form-search mb-3 text-end ">
                    <div class="container-search">
                        <input type="text" class="form-input" name="search" id="search" placeholder="Поиск" v-model="search">
                        <button class="search-btn"> <img :src="'/assets/img/search.svg'" alt=""> </button>
                    </div>
                </div>
                <div class="container-teams-title d-flex align-items-center justify-content-between">
                    <h3 class="block-title text-center text-lg-start">Команды</h3>
                    <div class="container-filters">
                        <button class="filters-btn info-button">Фильтры</button>
                    </div>
                </div>
                <div class="container-sort"></div>
                <div class="container-teams-items d-flex flex-column flex-wrap">

                    <team-card v-for="team, index in teams" :key="index" :team="team"/>

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
import TeamCard from '@/components/team/TeamCard.vue';
export default {
    components: {
        HeaderView,
        FooterView,
        EventCard,
        TeamCard
    },
    data() {
        return {
            search: '',
            teams: [  
                {
                    id: 1,
                    name: 'Название команды',
                    image: 'https://via.placeholder.com/80',
                    
                },
                {
                    id: 2,
                    name: 'Название команды',
                    image: 'https://via.placeholder.com/80',
                    
                },
            ]
        }
    }
}
</script>

<style scoped>
    .container-teams {
        width: clamp( 280px , 95% , 1200px);
        margin: 5dvw auto 0;

    }
    .container-teams-title {
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

    .container-teams-items{
        min-height: 80dvh;
        gap: clamp(  40px , 4vw , 100px);
    }

    

</style>