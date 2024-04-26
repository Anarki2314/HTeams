<template>
    
    <header-view/>

    <admin-nav/>
    
    <section class="organizers-section admin-section">
        <div class="container-block">
            <div class="container-request admin-container">

                <div class="form-search mb-3 text-end ">
                    <div class="container-search">
                        <input type="text" class="form-input" name="search" id="search" placeholder="Поиск" v-model="search">
                        <button class="search-btn"> <img :src="'/assets/img/search.svg'" alt=""> </button>
                    </div>
                </div>

                <div class="container-organizers-title d-flex align-items-center justify-content-between">
                    <h3 class="block-title text-center text-lg-start">Организаторы </h3>
                    <div class="container-filters">
                        <button class="filters-btn info-button">Фильтры</button>
                    </div>
                </div>

                <div class="container-sort"></div>
                <div class="container-admin-items d-flex flex-column flex-wrap">

                    <organizer-card v-for="organizer, index in organizers" :key="index" :organizer="organizer"/>

                </div>
            </div>
        </div>
    </section>
</template>

<script>
import HeaderView from '@/components/HeaderView.vue';
import AdminNav from '../../components/_admin/AdminNav.vue';
import OrganizerCard from '../../components/_admin/OrganizerCard.vue';

export default {
    components: {
        HeaderView,
        AdminNav,
        OrganizerCard
    },
    data() {
        return {
            organizers: [
                {
                    id: 1,
                    orgName: 'Организатор 1',
                    image: 'https://via.placeholder.com/80',
                }
            ],
            search: ''
        }
    },
}
</script>


<style scoped>




.admin-container {
        width: clamp( 280px , 95% , 1200px);
        margin: 0 auto;
    }
    .container-organizers-title {
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

    .container-organizers-items{
        min-height: 80dvh;
        gap: clamp(  40px , 4vw , 100px);
    }

</style>