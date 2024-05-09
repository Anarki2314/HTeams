<template>
    <router-link :to="'/events/'+ event.id " class="event-card" v-if="hasRequiredProps">
        <div class="event-card-image-container">
            <img class="event-card-image"
             :src="event.image"  alt="">
        </div>
        <div class="event-card-body d-flex flex-column">
            <h3 class="event-card-title"> {{ event.title }}</h3>
            <div class="event-card-tags d-flex align-items-center flex-wrap">

                <span class="event-card-tag " v-for="tag, index in slicedTags" :key="tag "> #{{ tag.replace(' ', '') }} </span>
                <span class="event-card-tag" v-if="slicedTags.length < event.tags.length"> +{{ event.tags.length - slicedTags.length }}</span>
                
            </div>
            <div class="event-card-date"> {{ new Date().toLocaleDateString('ru-RU', { year: 'numeric', month: '2-digit', day: '2-digit' })}} </div>
        </div>
    </router-link>
</template>

<script>
export default {
    data: function () {
        
        return {
            slicedTags: [],
        }
    },
    props: {
        event: {
            type: Object,
            required: true
        }
    },
    methods: {
    },
    mounted() {
        this.slicedTags = this.event.tags.slice(0, 4)
    },
    computed: {
        hasRequiredProps() {
            const { id, image, title, tags, date } = this.event
            return id && image && title && tags && date;
        }
    }
}
</script>

<style scoped>

.event-card{
    width: clamp( 280px, 100% , 500px);
}
.event-card-image{
    width: clamp( 280px, 100% , 500px);
    aspect-ratio: 1.8/1;
    object-fit: cover;
}
.event-card-body{
    margin-top: 10px;
    gap: 10px;
}
.event-card-title{
    font-size:clamp( 20px , 3vw , 30px );
}
.event-card-date{
    font-size:clamp( 20px , 3vw , 30px );
}
.event-card-tags{
    gap: 20px;
    }
.event-card-tag{
    color: var(--color-main);
    font-size: var(--size-text);
}

</style>