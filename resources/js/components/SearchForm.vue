<template>

    <form class="form-search mb-3 text-end " @submit.prevent @change="$emit('search')">
        <div class="container-search">
            <input type="text" class="form-input" name="search" id="search" placeholder="Поиск" :value="modelValue"
            @change.lazy="$emit('update:modelValue', $event.target.value)">

            <button type="submit" class="search-btn"> <img :src="'/assets/img/search.svg'" alt="">
            </button>
        </div>
    </form>

</template>

<script>

export default {

    props: {
        modelValue: String
    },
}
</script>

<style scoped>
.container-search {
    position: relative;
}

#search {
    background: transparent;
    padding: 7px 45px 7px 10px;
    border: 1px solid var(--color-main);
    border-radius: 5px;
    width: clamp(280px, 95%, 400px);
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
</style>