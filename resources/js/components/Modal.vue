<template>
    <div class="modal-container" @click.self="closeModal">
      
      <div class="modal-container-image">
        <img :src="'/assets/img/modal-bg.png'" alt="" class="modal-image">
        
        <div class="close modal-close" @click="closeModal">+</div>
        <div class="modal-container-content">
            <slot></slot>

          </div>

      </div>
    </div>
  </template>
  
  <script>
  export default {
    props: ['modalId'],
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
    }
  };
  </script>

  <style>

  .modal-container {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    display: flex;
    justify-content: center;
    align-items: center;
  }
  
.modal-close{
    position: absolute;
    transform: rotate(45deg);
    top : 25px;
    right: 10%;
    font-size: 50px;
    cursor: pointer;
    user-select: none;
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
  .modal-container-content {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: clamp(280px, 100%, 550px);
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

  .modal-container-input{
    position: relative;
    display: flex;
    justify-content: center;
    align-items: center;
    margin-bottom: clamp(15px, 3vw, 30px);
    margin-inline: auto;
    width: clamp(260px, 100%, 550px);

  }

  .modal-input {
    width: clamp(260px, 100%, 300px);
    height: 40px;
    outline: none;
    border: none;
    font-size: clamp(12px, 1.5vw, 18px);
    color: #f4f4f4;
    padding: 0 clamp(10px, 3vw, 20px);
    text-align: center;
    background: none;
    border: 2px solid #1B1B1B;  
    border-radius: 5px;
  }

  .modal-input.with-button{
    width: clamp(200px, 100%, 300px);
    border-right: none;
    border-radius: 5px 0 0 5px;
  
  }
  .modal-input-button{
    border-left: none;
    border-radius: 0 5px 5px 0;
  }
  .modal-input::placeholder{
    color:  #f4f4f4;
  }

  .modal-container-buttons{
    display: flex;
    justify-content: center;
    align-items: center;
    gap: clamp( 20px , 4.5vw , 60px);
  }
</style>