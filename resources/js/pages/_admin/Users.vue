<template>
    
    <header-view/>

    <admin-nav/>
    
    <section class="users-section admin-section">
        <div class="container-block">
            <div class="container-request admin-container">

                <div class="form-search mb-3 text-end ">
                    <div class="container-search">
                        <input type="text" class="form-input" name="search" id="search" placeholder="Поиск" v-model="search">
                        <button class="search-btn"> <img :src="'/assets/img/search.svg'" alt=""> </button>
                    </div>
                </div>

                <div class="container-users-title d-flex align-items-center justify-content-between">
                    <h3 class="block-title text-center text-lg-start">Пользователи </h3>
                    <div class="container-filters">
                        <button class="filters-btn info-button">Фильтры</button>
                    </div>
                </div>

                <div class="container-sort"></div>
                <div class="container-admin-items d-flex flex-column flex-wrap">

                    <user-card v-for="user, index in users" :key="index" :user="user"/>

                </div>
            </div>
        </div>
    </section>

    <footer-view/>
</template>

<script>
import HeaderView from '@/components/HeaderView.vue';
import AdminNav from '../../components/_admin/AdminNav.vue';
import UserCard from '../../components/_admin/UserCard.vue';
import FooterView from '@/components/FooterView.vue';

export default {
    components: {
        HeaderView,
        AdminNav,
        UserCard,
        FooterView
    },

    data() {
        return {
            users: [
                {
                    id: 1,
                    email: 'XfUeh@example.com',
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
    .container-users-title {
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

    .container-users-items{
        min-height: 80dvh;
        gap: clamp(  40px , 4vw , 100px);
    }

</style>