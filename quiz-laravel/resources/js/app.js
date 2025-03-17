import './bootstrap';
import { createApp } from 'vue';
import { createRouter, createWebHistory } from 'vue-router';

// Import main Vue component
import App from './App.vue';

// Import route components
import Login from './components/Login.vue';
import Register from './components/Register.vue';
import Profile from './components/Profile.vue';
import Dashboard from './components/Dashboard.vue';
import MainGame from './components/MainGame.vue';

// Define routes
const routes = [
    { path: '/', component: MainGame },
    { path: '/login', component: Login },
    { path: '/register', component: Register },
    { path: '/dashboard', component: Dashboard, meta: { requiresAuth: true } },
    { path: '/profile', component: Profile, meta: { requiresAuth: true } }
];

// Create router
const router = createRouter({
    history: createWebHistory(),
    routes
});

// Navigation guards
router.beforeEach((to, from, next) => {
    const isAuthenticated = localStorage.getItem('token');
    
    if (to.meta.requiresAuth && !isAuthenticated) {
        next('/login');
    } else if (to.path === '/login' && isAuthenticated) {
        // Kullanıcı giriş yapmışsa ve login sayfasına gitmek istiyorsa dashboard'a yönlendir
        next('/dashboard');
    } else {
        next();
    }
});

// Create app
const app = createApp(App);

// Use router
app.use(router);

// Mount the app
app.mount('#app');
