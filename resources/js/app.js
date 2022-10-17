import './bootstrap';
import { createApp } from 'vue';

import PrimeVue from "primevue/config";
import AutoComplete from 'primevue/autocomplete';
import Toaster from "@meforma/vue-toaster";
import 'primevue/resources/primevue.min.css'
import 'primeicons/primeicons.css'
import 'primevue/resources/themes/nova/theme.css'

const app = createApp({});

window.$userId = document.querySelector("meta[name='user-id']").getAttribute('content');

import JobVacancy from './components/views/JobVacancy.vue';

app.component('job-vacancy', JobVacancy);
app.component('AutoComplete', AutoComplete);

app.use(PrimeVue)
    .use(Toaster)
    .mount('#app');
