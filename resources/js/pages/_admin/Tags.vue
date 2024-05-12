<template>
    <loading-screen v-if="contentLoading" />
    <header-view/>

    <admin-nav/>
    
    <section class="tags-section admin-section">
        <div class="container-block">
            <div class="container-tag admin-container">

                <div class="form-search mb-3 text-end ">
                    <div class="container-search">
                        <input type="text" class="form-input" name="search" id="search" placeholder="Поиск" v-model="query['filter[title]']">
                        <button class="search-btn"> <img :src="'/assets/img/search.svg'" alt=""> </button>
                    </div>
                </div>

                    <h3 class="block-title text-center text-lg-start">Тэги </h3>
                    
                <div class="container-tag-create">
                    <button class="button-view main-button" @click="createTag">Создать тэг</button>
                </div>
                

                <div class="container-sort"></div>
                <div class="container-admin-items d-flex flex-column flex-wrap">

                    <tag-card v-for="tag, index in tags" :key="tag" :tag="tag"/>

                </div>
            </div>
        </div>
    </section>

    <footer-view/>
</template>

<script>
import HeaderView from '@/components/HeaderView.vue';
import AdminNav from '../../components/_admin/AdminNav.vue';
import TagCard from '../../components/_admin/TagCard.vue';
import FooterView from '@/components/FooterView.vue';

export default {
    components: {
        HeaderView,
        AdminNav,
        TagCard,
        FooterView
    },

    data() {
        return {
            query: {
                "filter[title]": '',
                perPage: 10,
                ...this.$route.query
            },
            page: 1,

            nextPage: null,
            contentLoading: true,
            pageLoading: false,
            tags: [
            ]
        }
    },

    methods: {
        createTag() {
            this.tags.unshift({
                id: null,
                title: '',
            })
        }
    }
}
</script>


<style scoped>




.admin-container {
        width: clamp( 280px , 95% , 1200px);
        margin: 0 auto;
    }

    
    .container-tag-create {
        margin-bottom: clamp( 20px , 3vw , 40px );
    }

    .container-admin-items{
        min-height: 80dvh;
        gap: clamp(  40px , 4vw , 100px);
    }

</style>