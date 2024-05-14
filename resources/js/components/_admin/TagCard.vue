<template>
        <form @submit.prevent="updateTag">

    <div class="tag-row-card row-card" v-if="show">
            <div class="container-tag-title row-card-container-name">
                <h4 class="tag-title row-card-name" v-if="!editMode">{{ tag.title }}</h4>
                <input type="text" class="tag-input row-card-input" placeholder="Тег" v-model="editTag.title" v-if="editMode">
            </div>
            <div class="container-tag-buttons d-flex flew-wrap row-card-container-button">
                <button type='button' class="button-view main-button" @click="toggleEditMode" v-if="!editMode">Редактировать</button>
                <button type='submit' class="button-view main-button" v-if="editMode && !isLoading" >Сохранить</button>
                <img :src="'/assets/img/loading.svg'" alt="" class="loading" :class="{ 'd-none': !isLoading }">

                <button type="button"  class="button-view secondary-button" @click="$emit('openDeleteModal', 'modal-delete-tag', tag.id)" v-if="!editMode">Удалить</button>
                <button type="button"  class="button-view secondary-button" @click="toggleEditMode" v-if="editMode">Отмена</button>
            </div>
        </div>
    </form>
</template>


<script>

import { useVuelidate } from '@vuelidate/core'
import { helpers } from '@vuelidate/validators'
import { push } from 'notivue'
import api from '../../api.js'  

export default {
    setup() {
        return { v$: useVuelidate() }
    },
    props: {
        tag: {
            type: Object,
            required: true
        }
    },
    data() {
        return {
            isLoading: false,
            show: true,
            editMode: false,
            editTag: {
                title: this.tag.title
            }
        }
    },
    validations() {
        return {
            editTag: {
                title: {
                    regex: helpers.withMessage('Только буквы', helpers.regex(/^[а-яА-ЯёЁA-Za-z]+$/))
                }
            }
        }
    },
    methods: {
        toggleEditMode() {
                this.editMode = !this.editMode
        },
        async updateTag() {
            if (this.editTag.title === this.tag.title) {
                this.toggleEditMode();
                return
            }
            if (!this.editTag.title) {
                this.$emit('openDeleteModal', 'modal-delete-tag', this.tag.id)
                return
            }
            const isTagCorrect = await this.v$.$validate()

            if (!isTagCorrect) {
                push.error('Только буквы');
                return
            }
            try{
                this.isLoading = true
                const response = await api.put('/admin/tags/' + this.tag.id, {
                    title: this.editTag.title
                })

                push.success(response.data.message);
                this.tag.title = this.editTag.title
                this.editTag.title = '';
                this.toggleEditMode();
            } catch (error) {
                push.error(error.data.message);
            } finally{
                this.isLoading = false
            }

                // Make an HTTP request to your API server

        }
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
    .container-tag-title{
        display:flex;
        flex: 1 1 auto;
    }
    .tag-title {
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