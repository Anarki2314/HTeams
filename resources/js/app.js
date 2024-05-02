import './bootstrap';
import 'notivue/notification.css'
import 'notivue/animations.css'
import 'notivue/notification-progress.css'
import "../sass/app.scss";
import { createApp } from 'vue';
import router from './router'
import store from './store/Auth-store'

import App from '@/App.vue';
import { createNotivue } from 'notivue';

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

createApp(App).use(notivue).use(router).use(store).mount('#app')