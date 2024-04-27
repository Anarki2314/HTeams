<template>
<header-view class=""/>
    <section class="profile-section ">
        <div class="container-block">
            <div class="container-profile ">
                <h3 class="block-title text-center text-lg-start">{profileEmail}</h3>
                <div class="container-profile-content mb-5 d-flex justify-content-center justify-content-lg-between flex-wrap">

                    <div class="container-profile-image d-flex flex-column">
                        <!-- <img :src="'/assets/img/profile.png'" alt=""> -->
                        <img src="https://via.placeholder.com/200" alt="">

                        <div class="profile-image-text">Изменить фото</div>
                        <div class="profile-image-text">Удалить фото</div>
                    </div>
                    
                    <div class="container-profile-about">
                        <h4 class="profile-subtitle text-center text-md-start">Личная информация:</h4>
                        <div class="container-profile-items ">
                            <div class="container-profile-item" v-if="!isOrganizer">Имя: <span class="profile-info-text">{name}</span></div>
                            <div class="container-profile-item" v-if="!isOrganizer">Фамилия: <span class="profile-info-text">{surname}</span></div>
                            <div class="container-profile-item" v-if="isOrganizer">Организация: <span class="profile-info-text">{surname}</span></div>

                            <div class="container-profile-item">Телефон: <span class="profile-info-text">{phone}</span></div>
                            <div class="container-profile-item">Дата регистрации: <span class="profile-info-text">{date}</span></div>
                            <div class="container-profile-item"><span class="info-button">Сменить пароль</span></div>
                            <div class="container-profile-item"><span class="info-button">Удалить аккаунт</span></div>
                        </div>
                    </div>
                    <div class="container-profile-stats">
                    <h4 class="profile-subtitle text-center text-md-start">Статистика:</h4>

                    <div class="container-profile-items ">
                            <div class="container-profile-item" v-if="!isOrganizer">Всего мероприятий: <span class="profile-info-text">{name}</span></div>
                            <div class="container-profile-item" v-if="isOrganizer">Создано мероприятий: <span class="profile-info-text">{name}</span></div>
                            <div class="container-profile-item">Предстоящие: <router-link to="/profile/upcoming" class="profile-info-text info-button">{surname}</router-link></div>
                            <div class="container-profile-item">Завершенные: <router-link to="/profile/finished" class="profile-info-text info-button">{phone}</router-link></div>
                            <div class="container-profile-item" v-if="!isOrganizer"><span class="info-button" @click="openModal('modal-create-team')">Создать команду</span></div>
                            <div class="container-profile-item" v-if="!isOrganizer"><span class="info-button" @click="openModal('modal-join-team')">Вступить в команду</span></div>
                            <div class="container-profile-item" v-if="isOrganizer"><span class="info-button">Создать мероприятие</span></div>
                            <!-- <div class="container-profile-item"><span class="info-but  ton">Ваша команда</span></div> -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <modal v-if="showModal && activeModal === 'modal-create-team'" modalId="modal-create-team" @close="closeModal">
                <h2 class="modal-title">Введите название команды</h2>
                <div class="modal-content">
                    <div class="modal-container-input">
                        <input type="text" placeholder="Название команды" class="modal-input" v-model="teamname"/>
                    </div>
                    <div class="modal-container-buttons">
                        <button class="button-view dark-button">Создать</button>
                    </div>
                </div>
        </modal>
        <modal v-if="showModal && activeModal === 'modal-join-team'" modalId="modal-join-team" @close="closeModal">
                <h2 class="modal-title">Введите код команды</h2>
                <div class="modal-content">
                    <div class="modal-container-input">
                        <input type="text" placeholder="Код команды" class="modal-input" v-model="inviteCode"/>
                    </div>
                    <div class="modal-container-buttons">
                        <button class="button-view dark-button">Создать</button>
                    </div>
                </div>
        </modal>
    </section>
</template>

<script>
import HeaderView from '@/components/HeaderView.vue';
import Modal from '@/components/Modal.vue';
export default {
    components: {
        HeaderView,
        Modal
    },
    data() {
        return {
            teamname : '',
            user : {},
            isOrganizer : false,
            showModal: false,
            activeModal: '',
        }
    },
    methods: {
        openModal(modalId) {
            this.showModal = true;
            this.activeModal = modalId;
        },
        closeModal() {
            this.showModal = false;
            this.activeModal = '';
        },
    },
}
</script>

<style scoped>


section{
    display:flex;
    justify-content: center;
    align-items: center;
}

.container-profile {
    margin: 8dvw auto 0;
    width: clamp( 280px , 95% , 1200px);
}

.container-profile-content{
    gap: clamp(  40px , 4vw , 100px)
}


.container-profile-image{
    gap: 15px;
}

.profile-image-text {
    font-size: var(--size-subtext);
    cursor: pointer;
    width: fit-content;
    color: #505050;
    border-bottom: #505050 1px solid;
}


.profile-subtitle {
    font-size:clamp( 20px , 3vw , 30px );
    margin-bottom: clamp( 20px , 3vw , 40px );
    color: var(--color-main);

}

.container-profile-items{
    display:flex;
    flex-direction: column;
    gap: clamp( 10px , 3vw ,30px)
}

.container-profile-item{
    font-size: var(--size-text);
    display:flex;
    gap: 25px;
    align-items: center;
}


</style>