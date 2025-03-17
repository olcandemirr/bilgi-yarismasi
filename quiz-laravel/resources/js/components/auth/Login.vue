<template>
    <div class="flex flex-col items-center justify-center min-h-[70vh]">
        <div class="bg-gray-800 p-8 rounded-lg shadow-lg w-full max-w-md">
            <h1 class="text-3xl font-bold mb-6 text-center">Login</h1>
            
            <div v-if="error" class="bg-red-500 text-white p-3 rounded-lg mb-4">
                {{ error }}
            </div>
            
            <form @submit.prevent="handleLogin">
                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium mb-1">Email</label>
                    <input
                        id="email"
                        v-model="email"
                        type="email"
                        required
                        class="w-full px-3 py-2 bg-gray-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="your@email.com"
                    >
                </div>
                
                <div class="mb-6">
                    <label for="password" class="block text-sm font-medium mb-1">Password</label>
                    <input
                        id="password"
                        v-model="password"
                        type="password"
                        required
                        class="w-full px-3 py-2 bg-gray-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="Your password"
                    >
                </div>
                
                <button
                    type="submit"
                    class="w-full bg-blue-600 py-3 rounded-lg font-medium hover:bg-blue-700 transition flex items-center justify-center"
                    :disabled="loading"
                >
                    <svg v-if="loading" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    {{ loading ? 'Logging in...' : 'Login' }}
                </button>
            </form>
            
            <div class="mt-6 text-center">
                <p>
                    Don't have an account? 
                    <router-link to="/register" class="text-blue-400 hover:text-blue-300">
                        Register
                    </router-link>
                </p>
            </div>
            
            <div class="mt-4 text-center">
                <router-link to="/game/mode" class="text-gray-400 hover:text-gray-300 text-sm">
                    Continue as guest
                </router-link>
            </div>
        </div>
    </div>
</template>

<script>
import { ref, computed } from 'vue';
import { useStore } from 'vuex';
import { useRouter } from 'vue-router';

export default {
    setup() {
        const store = useStore();
        const router = useRouter();
        
        const email = ref('');
        const password = ref('');
        const loading = computed(() => store.getters['auth/loading']);
        const error = computed(() => store.getters['auth/error']);
        
        const handleLogin = async () => {
            try {
                await store.dispatch('auth/login', {
                    email: email.value,
                    password: password.value
                });
                
                // Redirect to home page after successful login
                router.push('/');
            } catch (error) {
                console.error('Login failed', error);
                // Error is already handled in the store
            }
        };
        
        return {
            email,
            password,
            loading,
            error,
            handleLogin
        };
    }
}
</script> 