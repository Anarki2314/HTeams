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

    <footer-view/>
</template>

<script>
import HeaderView from '@/components/HeaderView.vue';
import AdminNav from '../../components/_admin/AdminNav.vue';
import OrganizerCard from '../../components/_admin/OrganizerCard.vue';
import FooterView from '@/components/FooterView.vue';
export default {
    components: {
        HeaderView,
        AdminNav,
        OrganizerCard,
        FooterView
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


    .filters-btn {
        background: none;
    }

    .container-organizers-items{
        min-height: 80dvh;
        gap: clamp(  40px , 4vw , 100px);
    }

</style>