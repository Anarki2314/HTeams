<template>
    
    <header-view/>

    <admin-nav/>
    
    <section class="events-section">
        <div class="container-block">
            <div class="container-events admin-container">
                <div class="form-search mb-3 text-end ">
                    <div class="container-search">
                        <input type="text" class="form-input" name="search" id="search" placeholder="Поиск"
                            v-model="search">
                        <button class="search-btn"> <img :src="'/assets/img/search.svg'" alt=""> </button>
                    </div>
                </div>
                <div class="container-events-title d-flex align-items-center justify-content-between">
                    <h3 class="block-title text-center text-lg-start">Заявки</h3>
                    <div class="container-filters">
                        <button class="filters-btn info-button">Фильтры</button>
                    </div>
                </div>
                <div class="container-sort"></div>
                <div class="container-events-items d-flex justify-content-center justify-content-lg-between flex-wrap">
                    <request-card v-for="event, index in events" :key="index" :event="event" />
                </div>
            </div>
        </div>
    </section>

    <footer-view/>
</template>

<script>
import HeaderView from '@/components/HeaderView.vue';
import AdminNav from '../../components/_admin/AdminNav.vue';
import FooterView from '@/components/FooterView.vue';
import RequestCard from '../../components/_admin/RequestCard.vue';

export default {
    components: {
        HeaderView,
        AdminNav,
        FooterView,
        RequestCard
    },
    data() {
        return {
            search: '',
            events: [
                {
                    id: 1,
                    image: 'https://via.placeholder.com/500x250',
                    tags: ['Тег 1', 'Тег 2', 'Тег 3', 'Тег 4'],
                    date: new Date().toLocaleDateString('ru-RU', { year: 'numeric', month: '2-digit', day: '2-digit' }),
                    title: 'Название соревнования'
                },
                {
                    id: 2,
                    image: 'https://via.placeholder.com/500x250',
                    tags: ['Тег A', 'Тег B', 'Тег C', 'Тег D', 'Тег E', 'Тег F', 'Тег G'],
                    // date: new Date().toLocaleDateString('ru-RU', { year: 'numeric', month: '2-digit', day: '2-digit' }),
                    title: 'Другое название соревнования'
                },
            ]
        }
    }
}
</script>


<style scoped>




.admin-container {
        width: clamp( 280px , 95% , 1200px);
        margin: 0 auto;
    }
    .container-events-title {
    margin-bottom: clamp( 20px , 3vw , 40px );
    }
    .block-title {
        margin-bottom: 0;
    }

    

    .filters-btn {
        background: none;
    }

    .container-events-items{
        min-height: 80dvh;
        gap: clamp(  40px , 4vw , 100px);
    }

</style>