<template>
    <div class="min-h-screen flex flex-col bg-gray-900 text-white">
        <!-- Header/Navigation -->
        <header class="bg-gray-800 shadow-md">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <!-- Logo and main nav -->
                    <div class="flex">
                        <div class="flex-shrink-0 flex items-center">
                            <router-link to="/" class="text-xl font-bold text-blue-400">
                                Sınav Ustası
                            </router-link>
                        </div>
                        
                        <!-- Desktop Navigation -->
                        <nav class="hidden md:ml-6 md:flex md:space-x-4">
                            <router-link 
                                to="/" 
                                class="px-3 py-2 rounded-md text-sm font-medium"
                                :class="$route.path === '/' ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white'"
                            >
                                Ana Sayfa
                            </router-link>
                            
                            <router-link 
                                to="/game/mode" 
                                class="px-3 py-2 rounded-md text-sm font-medium"
                                :class="$route.path.startsWith('/game') ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white'"
                            >
                                Oyna
                            </router-link>
                            
                            <router-link 
                                to="/leaderboard" 
                                class="px-3 py-2 rounded-md text-sm font-medium"
                                :class="$route.path === '/leaderboard' ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white'"
                            >
                                Liderlik Tablosu
                            </router-link>
                        </nav>
                    </div>
                    
                    <!-- User menu -->
                    <div class="hidden md:ml-6 md:flex md:items-center">
                        <div v-if="isAuthenticated" class="ml-3 relative flex items-center space-x-4">
                            <!-- Friend notifications -->
                            <router-link to="/friends" class="relative">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-300 hover:text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                                <span v-if="hasFriendRequests || hasGameInvites" class="absolute -top-1 -right-1 bg-red-500 rounded-full w-4 h-4 flex items-center justify-center text-xs">
                                    {{ friendRequestsCount + gameInvitesCount }}
                                </span>
                            </router-link>
                            
                            <!-- Profile dropdown -->
                            <div class="relative">
                                <button 
                                    @click="isProfileOpen = !isProfileOpen" 
                                    class="flex text-sm rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-800 focus:ring-white"
                                >
                                    <div v-if="user?.avatar" class="h-8 w-8 rounded-full overflow-hidden">
                                        <img :src="user.avatar" alt="Kullanıcı avatar" class="h-full w-full object-cover" />
                                    </div>
                                    <div v-else class="h-8 w-8 rounded-full bg-blue-600 flex items-center justify-center">
                                        <span class="text-sm font-medium">{{ userInitials }}</span>
                                    </div>
                                </button>
                                
                                <div v-if="isProfileOpen" 
                                     class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg py-1 bg-gray-700 ring-1 ring-black ring-opacity-5 focus:outline-none"
                                     @blur="isProfileOpen = false"
                                     @click.away="isProfileOpen = false"
                                >
                                    <div class="px-4 py-2 text-sm text-gray-300 border-b border-gray-600">
                                        Kullanıcı: <span class="font-medium text-white">{{ user?.name }}</span>
                                    </div>
                                    
                                    <router-link to="/profile" class="block px-4 py-2 text-sm text-gray-300 hover:bg-gray-600">
                                        Profilim
                                    </router-link>
                                    
                                    <button @click="handleLogout" class="w-full text-left block px-4 py-2 text-sm text-gray-300 hover:bg-gray-600">
                                        Çıkış Yap
                                    </button>
                                </div>
                            </div>
                        </div>
                        
                        <div v-else class="flex items-center space-x-2">
                            <router-link 
                                to="/login" 
                                class="px-3 py-2 rounded-md text-sm font-medium text-gray-300 hover:bg-gray-700 hover:text-white"
                            >
                                Giriş Yap
                            </router-link>
                            
                            <router-link 
                                to="/register" 
                                class="px-3 py-2 rounded-md text-sm font-medium bg-blue-600 hover:bg-blue-700"
                            >
                                Kayıt Ol
                            </router-link>
                        </div>
                    </div>
                    
                    <!-- Mobile menu button -->
                    <div class="-mr-2 flex items-center md:hidden">
                        <button @click="isMobileMenuOpen = !isMobileMenuOpen" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-white hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white">
                            <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path v-if="!isMobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                <path v-else stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Mobile menu -->
            <div v-if="isMobileMenuOpen" class="md:hidden">
                <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3">
                    <router-link 
                        to="/" 
                        class="block px-3 py-2 rounded-md text-base font-medium"
                        :class="$route.path === '/' ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white'"
                        @click="isMobileMenuOpen = false"
                    >
                        Ana Sayfa
                    </router-link>
                    
                    <router-link 
                        to="/game/mode" 
                        class="block px-3 py-2 rounded-md text-base font-medium"
                        :class="$route.path.startsWith('/game') ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white'"
                        @click="isMobileMenuOpen = false"
                    >
                        Oyna
                    </router-link>
                    
                    <router-link 
                        to="/leaderboard" 
                        class="block px-3 py-2 rounded-md text-base font-medium"
                        :class="$route.path === '/leaderboard' ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white'"
                        @click="isMobileMenuOpen = false"
                    >
                        Liderlik Tablosu
                    </router-link>
                </div>
                
                <div v-if="isAuthenticated" class="pt-4 pb-3 border-t border-gray-700">
                    <div class="flex items-center px-5">
                        <div class="flex-shrink-0">
                            <div v-if="user?.avatar" class="h-10 w-10 rounded-full overflow-hidden">
                                <img :src="user.avatar" alt="Kullanıcı avatar" class="h-full w-full object-cover" />
                            </div>
                            <div v-else class="h-10 w-10 rounded-full bg-blue-600 flex items-center justify-center">
                                <span class="text-sm font-medium">{{ userInitials }}</span>
                            </div>
                        </div>
                        <div class="ml-3">
                            <div class="text-base font-medium text-white">{{ user?.name }}</div>
                            <div class="text-sm font-medium text-gray-400">{{ user?.email }}</div>
                        </div>
                    </div>
                    
                    <div class="mt-3 px-2 space-y-1">
                        <router-link 
                            to="/profile" 
                            class="block px-3 py-2 rounded-md text-base font-medium text-gray-400 hover:text-white hover:bg-gray-700"
                            @click="isMobileMenuOpen = false"
                        >
                            Profilim
                        </router-link>
                        
                        <router-link 
                            to="/friends" 
                            class="block px-3 py-2 rounded-md text-base font-medium text-gray-400 hover:text-white hover:bg-gray-700"
                            @click="isMobileMenuOpen = false"
                        >
                            Arkadaşlar & Oyun Davetleri
                            <span v-if="hasFriendRequests || hasGameInvites" class="ml-2 inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-500 text-white">
                                {{ friendRequestsCount + gameInvitesCount }}
                            </span>
                        </router-link>
                        
                        <button 
                            @click="handleLogout(); isMobileMenuOpen = false;" 
                            class="w-full text-left block px-3 py-2 rounded-md text-base font-medium text-gray-400 hover:text-white hover:bg-gray-700"
                        >
                            Çıkış Yap
                        </button>
                    </div>
                </div>
                
                <div v-else class="pt-4 pb-3 border-t border-gray-700">
                    <div class="space-y-1 px-2">
                        <router-link 
                            to="/login" 
                            class="block px-3 py-2 rounded-md text-base font-medium text-gray-400 hover:text-white hover:bg-gray-700"
                            @click="isMobileMenuOpen = false"
                        >
                            Giriş Yap
                        </router-link>
                        
                        <router-link 
                            to="/register" 
                            class="block px-3 py-2 rounded-md text-base font-medium text-white bg-blue-600 hover:bg-blue-700"
                            @click="isMobileMenuOpen = false"
                        >
                            Kayıt Ol
                        </router-link>
                    </div>
                </div>
            </div>
        </header>
        
        <!-- Page content -->
        <main class="flex-grow py-10">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <router-view></router-view>
            </div>
        </main>
        
        <!-- Footer -->
        <footer class="bg-gray-800 py-6">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col md:flex-row md:justify-between items-center">
                    <div class="text-gray-400 text-sm mb-4 md:mb-0">
                        &copy; {{ new Date().getFullYear() }} Sınav Ustası. Tüm hakları saklıdır.
                    </div>
                    <div class="flex space-x-6">
                        <a href="#" class="text-gray-400 hover:text-white">
                            <span class="sr-only">Hakkında</span>
                            Hakkında
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white">
                            <span class="sr-only">Gizlilik</span>
                            Gizlilik
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white">
                            <span class="sr-only">Şartlar</span>
                            Şartlar
                        </a>
                    </div>
                </div>
            </div>
        </footer>
    </div>
</template>

<script>
import { ref, computed, onMounted, watch } from 'vue';
import { useStore } from 'vuex';
import { useRouter } from 'vue-router';

export default {
    setup() {
        const store = useStore();
        const router = useRouter();
        
        // Mobile menu state
        const isMobileMenuOpen = ref(false);
        
        // Profile dropdown state
        const isProfileOpen = ref(false);
        
        // Auth state
        const isAuthenticated = computed(() => store.getters['auth/isAuthenticated']);
        const user = computed(() => store.getters['auth/currentUser']);
        
        // Friend requests and game invites
        const hasFriendRequests = computed(() => {
            return store.getters['friends/pendingRequests']?.length > 0;
        });
        
        const hasGameInvites = computed(() => {
            return store.getters['friends/gameInvites']?.length > 0;
        });
        
        const friendRequestsCount = computed(() => {
            return store.getters['friends/pendingRequests']?.length || 0;
        });
        
        const gameInvitesCount = computed(() => {
            return store.getters['friends/gameInvites']?.length || 0;
        });
        
        // User initials for avatar
        const userInitials = computed(() => {
            if (!user.value) return '';
            const name = user.value.name || '';
            return name.charAt(0).toUpperCase();
        });
        
        // Fetch user data on mount
        onMounted(async () => {
            try {
                await store.dispatch('auth/fetchUser');
                
                // If authenticated, fetch friend requests and game invites
                if (isAuthenticated.value) {
                    await store.dispatch('friends/fetchPendingRequests');
                    await store.dispatch('friends/fetchGameInvites');
                }
            } catch (error) {
                console.error('Error fetching user data:', error);
            }
        });
        
        // Watch authentication state and fetch friend requests when user logs in
        watch(isAuthenticated, async (newValue) => {
            if (newValue) {
                await store.dispatch('friends/fetchPendingRequests');
                await store.dispatch('friends/fetchGameInvites');
            }
        });
        
        // Handle logout
        const handleLogout = async () => {
            try {
                await store.dispatch('auth/logout');
                isProfileOpen.value = false;
                router.push('/');
            } catch (error) {
                console.error('Logout error:', error);
            }
        };
        
        return {
            isMobileMenuOpen,
            isProfileOpen,
            isAuthenticated,
            user,
            userInitials,
            hasFriendRequests,
            hasGameInvites,
            friendRequestsCount,
            gameInvitesCount,
            handleLogout
        };
    }
}
</script> 