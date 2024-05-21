<template>
    <div class="team-row-card row-card justify-content-sm-between">
        <router-link :to="'/_admin/teams/' + team.id" class="container-team-name row-card-container-name">
            <div class="container-team-image row-card-container-image">
                <img :src="team.leader?.avatar.path" alt="" class="team-image row-card-image">
            </div>
            <h4 class="team-name row-card-name">{{ team.title }}</h4>
        </router-link>
        <div class="container-team-apply-button row-card-container-button">
            <router-link :to="'/_admin/teams/' + team.id" class="button-view secondary-button">Просмотр</router-link>
        </div>
    </div>
</template>


<script>

export default {
    props: {
        team: {
            type: Object,
            required: true

        }
    },

}
</script>

<style scoped>

</style>