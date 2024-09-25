import { createApp } from 'vue';
import home from './components/HomeComponent.vue';
import i18n from '../../src/i18n'; 

const app = createApp({});


app.component('home', home);
app.use(i18n);
app.mount('#app');
