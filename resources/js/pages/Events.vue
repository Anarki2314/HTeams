<template>
    <header-view />
    <section class="events-section">
        <div class="container-block">
            <div class="container-events">
                <div class="form-search mb-3 text-end ">
                    <div class="container-search">
                        <input type="text" class="form-input" name="search" id="search" placeholder="Поиск"
                            v-model="search">
                        <button class="search-btn"> <img :src="'/assets/img/search.svg'" alt=""> </button>
                    </div>
                </div>
                <div class="container-events-title d-flex align-items-center justify-content-between">
                    <h3 class="block-title text-center text-lg-start">Мероприятия</h3>
                    <div class="container-filters">
                        <button class="filters-btn info-button">Фильтры</button>
                    </div>
                </div>
                <div class="container-sort"></div>
                <div class="container-events-items d-flex justify-content-center justify-content-lg-between flex-wrap">
                    <event-card v-for="event, index in events" :key="index" :event="event" />
                </div>
            </div>
        </div>
    </section>
    <footer-view />
</template>

<script>
import HeaderView from '@/components/HeaderView.vue';
import FooterView from '@/components/FooterView.vue'
import EventCard from '@/components/event/EventCard.vue';
export default {
    components: {
        HeaderView,
        FooterView,
        EventCard
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
                    title: 'Название мероприятия'
                },
                {
                    id: 2,
                    image: 'https://via.placeholder.com/500x250',
                    tags: ['Тег A', 'Тег B', 'Тег C', 'Тег D', 'Тег E', 'Тег F', 'Тег G'],
                    // date: new Date().toLocaleDateString('ru-RU', { year: 'numeric', month: '2-digit', day: '2-digit' }),
                    title: 'Другое название мероприятия'
                },
            ]
        }
    }
}
</script>

<style scoped>
.container-events {
    width: clamp(280px, 95%, 1200px);
    margin: 5dvw auto 0;

}

.container-events-title {
    margin-bottom: clamp(20px, 3vw, 40px);
}

.block-title {
    margin-bottom: 0;
}

.container-search {
    position: relative;
}

#search {
    background: transparent;
    padding: 5px 45px 5px 10px;
    border: 1px solid var(--color-main);
    border-radius: 5px;
    width: clamp(280px, 95%, 400px);
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

.container-events-items {
    min-height: 80dvh;
    row-gap: clamp(60px, 4vw, 100px);
}
</style>