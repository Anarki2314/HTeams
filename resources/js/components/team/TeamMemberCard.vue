<template>
    <div class="container-team-member d-flex align-items-center">
        <div class="container-member-image">
            <img
                :src="member.avatar?.path"
                alt=""
                class="member-image"
            />
        </div>
        <div class="member-name d-flex align-items-center">{{ member.surname }} {{ member.name }}
        <img :src="'/assets/img/leader-mark.png'" alt="" v-if ="member.isLeader" class="leader-mark">
        <span class="kick-btn" v-if="type == 'profile'" @click="$emit('openModal', 'modal-kick-member')">+</span>
        </div>
    </div>
</template>

<script>

export default {
    props: {
        member:{
            type: Object,
            required: true
        },
        type:{
            type: String,
            default: 'team'
        }
    },
}
</script>

<style scoped>

.container-team-member{
        gap: clamp( 10px , 3vw ,30px);
    }
    
    .member-image{
        width: clamp( 64px , 7vw , 80px);
        border-radius: 50%;
    }

    .member-name {
        font-size: var(--size-text);
        gap: clamp( 10px , 3vw ,30px);
    }

    .leader-mark {
        width: clamp( 24px , 3vw , 30px);
    }

    .kick-btn {
        transform: rotate(45deg);
    font-size: 50px;
    cursor: pointer;
    user-select: none;
    }
</style>
