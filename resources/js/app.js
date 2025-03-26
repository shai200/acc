import './bootstrap';
import { createApp } from 'vue';
import { createRouter, createWebHistory } from 'vue-router';
import App from './components/App.vue';
import Screen1 from './components/Screen1.vue';
import Screen2 from './components/Screen2.vue';
import Screen3 from './components/Screen3.vue';
import Screen4 from './components/Screen4.vue';

// Create router
const router = createRouter({
    history: createWebHistory(),
    routes: [
        {
            path: '/',
            redirect: '/screen1'
        },
        {
            path: '/screen1',
            name: 'screen1',
            component: Screen1
        },
        {
            path: '/screen2',
            name: 'screen2',
            component: Screen2
        },
        {
            path: '/screen3',
            name: 'screen3',
            component: Screen3
        },
        {
            path: '/screen4',
            name: 'screen4',
            component: Screen4
        }
    ]
});

// Create Vue app
const app = createApp(App);

// Use router
app.use(router);

// Mount the app
app.mount('#app');
