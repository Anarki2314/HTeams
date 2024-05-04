<template>
    <div class="modal-filters-container" @click.self="closeModal">
        <div class="modal-filters-container-image">
          <img :src="'/assets/img/filter-bg.png'" alt="" class="modal-filters-image">

          <div class="modal-filters-container-content">
            <slot></slot>

          </div>

      </div>
    </div>
  </template>
  
  <script>
  export default {
    props: ['show'],
    mounted() {
      document.addEventListener('keydown', this.handleKeyDown);
    },
    beforeDestroy() {
      document.removeEventListener('keydown', this.handleKeyDown);
    },

    methods: {
      handleKeyDown(event) {
        if (event.key === 'Escape') {
          this.$emit('close');
        }
      },
      closeModal(event) {
        this.$emit('close');
      }
    }
  };
  </script>

  <style>
  .modal-filters-container {
    position: absolute;
    top: 100%;
    left: auto;
    right: 0;
    width: auto;
    height: auto;
    z-index: 10;
    background-color: transparent;
    display: block;
  }
  .modal-filters-container-image{
    position: relative;
    overflow: hidden;
    width: 100%;
    display: flex;
    justify-content: center;
    align-items: center;  
    width: max-content;
  }
  .modal-filters-container-content {
    position: absolute;
    overflow: auto;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: clamp(280px, 100%, 400px);
  }
 
  .container-filters-items {
    display: flex;
    flex-direction: column;
    gap: clamp( 10px, 1.5vw ,25px);
  }
  .filters-checkbox{
    position: absolute;
    opacity: 0;
    cursor: pointer;
    height: 0;
    width: 0;
  }

  .filters-checkbox-label {
    position: relative;
    display: flex;
    align-items: center;
    gap: 10px;
  }

  .filters-checkbox-label::before {
    content: "";
    display: block;
    width: 30px;
    height: 30px;
    border: 1px solid #1B1B1B;
    border-radius: 5px;
  }

  .filters-checkbox:checked + .filters-checkbox-label::before {
    background: url('/assets/img/filters-mark.png') no-repeat center;
  }
  .modal-filters-image {
    width: clamp(280px, 50dvw, 550px);
  }
  .modal-content *{
    font-size: clamp(12px, 1.5vw, 18px);  
  }
  .modal-title {
    font-size: clamp(20px, 3vw, 30px);
    margin-bottom: clamp(15px, 3vw, 30px);
    text-align:center;
  }

</style>