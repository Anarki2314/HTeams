<template>
        <div class="container-event-participate d-flex">
                <button class="button-view main-button" v-if="canParticipate">Принять участие</button>
                <button class="button-view darken-button" v-if="canCancelParticipation">Отменить участие</button>
                <button class="button-view darken-button" v-if="canEdit">Редактировать</button>
                <button class="button-view main-button" v-if="canDownload">Скачать задание</button>
                <a href="#info" class="button-view main-button" v-if="haveResults">Итоги</a>
        </div>
</template>

<script>


export default {

    data() {
        return {
            statuses: ['Новое', 'Регистрация', 'Началось', 'Завершено'],
            isUser: true,
            isOrganizer: false,

        }
    },
    props: {
        event: {
            type: Object,
            required: true
        }
        
    },

    computed: {


        canParticipate() {
            
            return !this.isOrganizer&& this.event.status === 'Регистрация' && !this.event.isParticipated;
        },

        canCancelParticipation() {
            return this.isUser && this.event.status === 'Регистрация' && this.event.isParticipated;
        },
        canEdit() {
            return this.isOrganizer && this.event.status === 'Новое';
        },

        canDownload() {
            return this.isUser && this.event.status === 'Началось' && this.event.isParticipated;
        },

        haveResults() {
            return this.event.status === 'Завершено';
        }
    },
    

}
</script>
