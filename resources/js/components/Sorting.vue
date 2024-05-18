<template>

    <div class="container-sorting">

        <div class="container-sorting-title">
            <h3 class="sorting-title">Сортировка:</h3>
        </div>

        <div class="container-sorting-select">

            <Dropdown
            v-model="sortType" 
            @change="change" 
            :options="sortList" 
            optionLabel="label" 
            optionGroupLabel="label" 
            optionValue="value" 
            optionGroupChildren="items" 
            id="sort"/>
        </div>
    </div>

</template>
<script>

import Dropdown from 'primevue/dropdown';
export default {
    props:{
        modelValue: String,
        sortList: {
            type: Array,
            required: true
        }
    },

    components: {
        Dropdown
    },

    data() {
        return {
            sortType: this.$route.query['sort'] || this.modelValue,

        }
    },

    methods: {
        change() {
            this.$emit('update:modelValue', this.sortType)
        }
    }
}
</script>
<style>
    .container-sorting{
        display:flex;
        align-items: center;
        gap: 20px;
        margin-bottom: clamp(15px, 3vw, 30px);
    }
    .sorting-title{
        font-size: var(--size-text);
    }
</style>