import './bootstrap';
import { createApp } from 'vue';

const app = createApp({});

import JobVacancy from './components/views/JobVacancy.vue';

app.component('job-vacancy', JobVacancy);


app.mount('#app');
