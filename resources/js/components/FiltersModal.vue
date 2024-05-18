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
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    display: flex;
    width: 70%;
    flex-direction: column;
    gap: clamp( 10px, 1.5vw ,25px);
  }
  .container-filters-types{
    display: flex;
    gap: clamp( 10px, 1.5vw ,25px);
  }
 .filters-type{
    font-size: var(--size-text);
    cursor: pointer;
 }
 .filters-type.active{
  text-decoration: underline;
  text-underline-offset: 3px;
 }
 .filters-type:hover{
  text-decoration: underline;
  text-underline-offset: 3px;
 }
  .container-filters-items {
    overflow: auto;
    display: flex;
    max-height: clamp(120px, 15vw, 180px);
    flex-direction: column;
    gap: clamp( 10px, 1.5vw ,25px);
  }

  .container-filters-item{
    display: flex;
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
    font-size: var(--size-text);
    gap: 10px;
    cursor: pointer;
  }

  .filters-checkbox-label::before {
    content: "";
    display: block;
    width: clamp(24px, 3vw, 30px);
    height: clamp(24px, 3vw, 30px);
    border: 1px solid #1B1B1B;
    border-radius: 5px;
  }
  .filters-radio-label {
    position: relative;
    display: flex;
    align-items: center;
    font-size: var(--size-text);
    gap: 10px;
    cursor: pointer;
  }

  .filters-radio-label::before {
    content: "";
    display: block;
    width: clamp(24px, 3vw, 30px);
    aspect-ratio: 1 / 1;
    border: 1px solid #1B1B1B;
    border-radius: 50%;
  }

  .filters-checkbox:checked + .filters-checkbox-label::before {
    background: url('/assets/img/filters-mark.png') no-repeat center / 75%;
  }
  .filters-checkbox:checked + .filters-radio-label::before {
    background: url('/assets/img/filters-mark.png') no-repeat center / 75%;
  }
  .modal-filters-image {
    width: clamp(280px, 50dvw, 550px);
    filter: drop-shadow(0px 0px 10px rgba(0, 0, 0, 0.25));
  }

  .container-filters-buttons{
    display: flex;
    justify-content: space-around;
    align-items: center;
    gap: 20px;
  }

  .filters-btn {
    border: none;
    background-color: transparent;
    outline: none;
    font-size: var(--size-text);
  }

  .filters-btn:hover, .filters-btn:focus, .filters-btn:active {
    text-decoration: underline;
    
  }
  .filters-btn-reset {
    color: #f4f4f4;
  }

  .filters-btn-apply {
    color: var(--color-main);
  }

</style>