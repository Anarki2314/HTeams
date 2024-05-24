import './bootstrap';
import 'primevue/resources/themes/aura-dark-amber/theme.css'
import 'notivue/notification.css'
import 'notivue/animations.css'
import 'notivue/notification-progress.css'
import { createApp } from 'vue';
import router from './router'
import store from './store/Auth-store'

import App from '@/App.vue';
import { createNotivue } from 'notivue';

import PrimeVue from 'primevue/config';

const notivue = createNotivue({
    position: 'top',
    limit: 4,
    enqueue: true,
    avoidDuplicates: true,
    notifications: {
      global: {
        duration: 5000
      }
    }
  });

createApp(App).use(PrimeVue, { unstyled: false}).use(notivue).use(router).use(store).mount('#app')