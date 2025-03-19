import './bootstrap';
import { createApp } from 'vue';
import { createRouter, createWebHistory } from 'vue-router';
import App from './components/App.vue';
import Home from './components/Home.vue';
import Customers from './components/Customers.vue';
import Products from './components/Products.vue';

// Create router
const router = createRouter({
    history: createWebHistory(),
    routes: [
        {
            path: '/',
            name: 'home',
            component: Home
        },
        {
            path: '/customers',
            name: 'customers',
            component: Customers
        },
        {
            path: '/products',
            name: 'products',
            component: Products
        }
    ]
});

// Create Vue app
const app = createApp(App);

// Use router
app.use(router);

// Mount the app
app.mount('#app');
