<template>
    <div class="modal-container" @click.self="closeModal">
        <div class="modal-container-image">
          <img :src="'/assets/img/notif-bg.png'" alt="" class="modal-image">

          <div class="close modal-close" @click="closeModal">+</div>
          <div class="modal-container-notif-content">
            <notif-invite @getNotifications="getNotifications" v-for="invite in notifications.invites" :key="invite" :invite="invite"/>
            <notif-event @getNotifications="getNotifications" v-for="event in notifications.events" :key="event" :event="event" @closeModal="closeModal"/>
          </div>

      </div>
    </div>
  </template>
  
  <script>
  import NotifInvite from './notifications/NotifInvite.vue';
  import NotifEvent from './notifications/NotifEvent.vue';
  export default {
    components: {
      NotifInvite,
      NotifEvent
    },
    props: {
        notifications: Object,
        modalId: String
    },
    mounted() {
      document.addEventListener('keydown', this.handleKeyDown);
    },
    beforeDestroy() {
      document.removeEventListener('keydown', this.handleKeyDown)
    },

    methods: {
      handleKeyDown(event) {
        if (event.key === 'Escape') {
          this.closeModal();
        }
      },
      closeModal() {
        this.$emit('close');
      },

      getNotifications() {
        this.$emit('getNotifications');
      }
    }
  };
  </script>

  <style scoped>

  .modal-container {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 10;
    background-color: rgba(0, 0, 0, 0.5);
    display: flex;
    justify-content: center;
    align-items: center;
  }
  .modal-container-image{
    position: relative;
    overflow: hidden;
    display: flex;
    justify-content: center;
    align-items: center;  
    width: max-content;
  }
  .modal-image{
    width: 940px;
    max-width: 940px;
  }
  .modal-container-notif-content {
    position: absolute;
    top: 50%;
    left: 50%;
    height: 45%;
    transform: translate(-50%, -50%);
    width: clamp(280px, 90%, 800px);
    display:flex;
    flex-direction: column;
    gap: clamp( 20px , 4.5vw , 40px);
    overflow: auto;
  }
.modal-close{
    position: absolute;
    transform: rotate(45deg);
    top : 25px;
    right: 10%;
    font-size: 50px;
    cursor: pointer;
}
  .modal-content *{
    font-size: clamp(12px, 1.5vw, 18px);  
  }
  .modal-content button {
    height: 40px;
    padding: 0 clamp(15px, 3vw, 40px)
  }
  .modal-title {
    font-size: clamp(20px, 3vw, 30px);
    margin-bottom: clamp(15px, 3vw, 30px);
    text-align:center;
  }


  .modal-container-buttons{
    display: flex;
    justify-content: center;
    align-items: center;
    gap: clamp( 20px , 4.5vw , 60px);
  }
</style>