<template>
    <div class="tag-row-card row-card" v-if="show">

            <div class="container-tag-name row-card-container-name">
                <h4 class="tag-name row-card-name" v-if="!editMode">{{ tag.name }}</h4>
                <input type="text" class="tag-input row-card-input" placeholder="Тег" v-model="editTag.name" v-if="editMode">
            </div>
            <div class="container-tag-buttons d-flex flew-wrap row-card-container-button">
                <button class="button-view main-button" @click="toggleEditMode" v-if="!editMode">Редактировать</button>
                <button class="button-view main-button" @click="saveTag" v-if="editMode">Сохранить</button>

                <button  class="button-view secondary-button" @click="$emit('deleteTag', tag.id)" v-if="!editMode">Удалить</button>
                <button  class="button-view secondary-button" @click="toggleEditMode" v-if="editMode">Отмена</button>
            </div>
    </div>
</template>


<script>

export default {
    props: {
        tag: {
            type: Object,
            required: true
        }
    },
    data() {
        return {
            show: true,
            editMode: false,
            editTag: {
                name: this.tag.name
            }
        }
    },
    methods: {
        toggleEditMode() {
                this.editMode = !this.editMode
        },
        saveTag() {
                // Make an HTTP request to your API server

                this.tag.name = this.editTag.name
                // Toggle the edit mode
                this.toggleEditMode();
        }
    },
    mounted() {
        this.editMode = (!this.tag.name)
    },
}
</script>

<style scoped>
    .row-card{
        padding: clamp(16px, 2.4vw, 32px) clamp(20px, 3vw, 40px);
    }
    .container-tag-buttons{
        gap: clamp(15px, 3vw, 30px);
    }
    .container-tag-name{
        display:flex;
        flex: 1 1 auto;
    }
    .tag-name {
        padding: 6px 11px;
    }

    .container-tag-input{
        position: relative;
    }

    .tag-input {
        background: transparent;
        padding: 5px 45px 5px 10px;
        border: 1px solid var(--color-main);
        border-radius: 5px;
        width: clamp( 280px , 95% , 400px);
        color: #f4f4f4;
        font-size: var(--size-text);
    }
    .tag-input::placeholder {
        color: #313131;
    }
</style>