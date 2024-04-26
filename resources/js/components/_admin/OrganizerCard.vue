<template>
    <div class="organizer-row-card row-card justify-content-sm-between" v-if="hasRequiredProps">
        <router-link :to="'/_admin/organizers/' + organizer.id" class="container-organizer-name row-card-container-name">
            <div class="container-organizer-image row-card-container-image">
                <img :src="organizer.image" alt="" class="organizer-image row-card-image">
            </div>
            <h4 class="organizer-name row-card-name">{{ organizer.orgName }}</h4>
        </router-link>
        <div class="container-organizer-apply-button row-card-container-button">
            <router-link :to="'/_admin/organizers/' + organizer.id" class="button-view secondary-button">Просмотр</router-link>
        </div>
    </div>
</template>


<script>

export default {
    props: {
        organizer: {
            type: Object,
            required: true

        }
    },

    computed: {
        hasRequiredProps() {
            const { id, orgName, image } = this.organizer
            return id && orgName && image;
        }
    }
}
</script>

<style scoped>

</style>