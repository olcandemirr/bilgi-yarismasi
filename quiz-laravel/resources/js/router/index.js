import { createRouter, createWebHistory } from 'vue-router';
import store from '../store';

// Import components
import Home from '../components/Home.vue';
import Login from '../components/auth/Login.vue';
import Register from '../components/auth/Register.vue';
import Profile from '../components/user/Profile.vue';
import Friends from '../components/user/Friends.vue';
import Game from '../components/game/Game.vue';
import GameModeSelection from '../components/game/GameModeSelection.vue';
import CategorySelection from '../components/game/CategorySelection.vue';
import GameResults from '../components/game/GameResults.vue';
import Leaderboard from '../components/Leaderboard.vue';

// Create routes
const routes = [
    {
        path: '/',
        name: 'home',
        component: Home
    },
    {
        path: '/login',
        name: 'login',
        component: Login,
        meta: { guestOnly: true }
    },
    {
        path: '/register',
        name: 'register',
        component: Register,
        meta: { guestOnly: true }
    },
    {
        path: '/profile',
        name: 'profile',
        component: Profile,
        meta: { requiresAuth: true }
    },
    {
        path: '/friends',
        name: 'friends',
        component: Friends,
        meta: { requiresAuth: true }
    },
    {
        path: '/game',
        name: 'game',
        component: Game
    },
    {
        path: '/game/mode',
        name: 'gameMode',
        component: GameModeSelection
    },
    {
        path: '/game/categories',
        name: 'categories',
        component: CategorySelection
    },
    {
        path: '/game/results',
        name: 'results',
        component: GameResults
    },
    {
        path: '/leaderboard',
        name: 'leaderboard',
        component: Leaderboard
    }
];

// Create router instance
const router = createRouter({
    history: createWebHistory(),
    routes
});

// Navigation guards
router.beforeEach((to, from, next) => {
    const isAuthenticated = store.getters['auth/isAuthenticated'];
    
    // Route requires authentication
    if (to.matched.some(record => record.meta.requiresAuth) && !isAuthenticated) {
        next({ name: 'login' });
    }
    // Route is for guests only (like login, register)
    else if (to.matched.some(record => record.meta.guestOnly) && isAuthenticated) {
        next({ name: 'home' });
    }
    // All other routes
    else {
        next();
    }
});

export default router; 