<template>
    <div class="max-w-4xl mx-auto">
        <h1 class="text-3xl font-bold mb-8">Your Profile</h1>
        
        <div v-if="error" class="bg-red-500 text-white p-3 rounded-lg mb-4">
            {{ error }}
        </div>
        
        <div v-if="success" class="bg-green-500 text-white p-3 rounded-lg mb-4">
            {{ success }}
        </div>
        
        <div class="bg-gray-800 rounded-lg shadow-lg overflow-hidden">
            <div class="p-6">
                <div class="flex flex-col md:flex-row gap-6">
                    <div class="w-full md:w-1/3">
                        <div class="flex flex-col items-center">
                            <div class="w-32 h-32 bg-blue-600 rounded-full overflow-hidden mb-4">
                                <img 
                                    v-if="user?.avatar" 
                                    :src="user.avatar" 
                                    alt="Avatar" 
                                    class="w-full h-full object-cover"
                                >
                                <div v-else class="w-full h-full flex items-center justify-center text-3xl">
                                    {{ userInitials }}
                                </div>
                            </div>
                            
                            <h2 class="text-xl font-semibold mb-1">
                                {{ user?.username || 'Username' }}
                            </h2>
                            <p class="text-gray-400 mb-4">
                                {{ user?.email || 'email@example.com' }}
                            </p>
                            
                            <div class="mt-2 border-t border-gray-700 pt-4 w-full">
                                <h3 class="text-lg font-medium mb-2">Stats</h3>
                                <div class="grid grid-cols-2 gap-2">
                                    <div class="bg-gray-700 p-3 rounded-lg text-center">
                                        <div class="text-2xl font-bold text-blue-400">{{ totalGames }}</div>
                                        <div class="text-sm text-gray-400">Games Played</div>
                                    </div>
                                    <div class="bg-gray-700 p-3 rounded-lg text-center">
                                        <div class="text-2xl font-bold text-blue-400">{{ highScore }}</div>
                                        <div class="text-sm text-gray-400">High Score</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="w-full md:w-2/3">
                        <h3 class="text-xl font-semibold mb-4">Edit Profile</h3>
                        <form @submit.prevent="updateProfile">
                            <div class="mb-4">
                                <label for="name" class="block text-sm font-medium mb-1">Name</label>
                                <input
                                    id="name"
                                    v-model="formData.name"
                                    type="text"
                                    class="w-full px-3 py-2 bg-gray-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                >
                            </div>
                            
                            <div class="mb-4">
                                <label for="username" class="block text-sm font-medium mb-1">Username</label>
                                <input
                                    id="username"
                                    v-model="formData.username"
                                    type="text"
                                    class="w-full px-3 py-2 bg-gray-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                >
                            </div>
                            
                            <div class="mb-4">
                                <label for="bio" class="block text-sm font-medium mb-1">Bio</label>
                                <textarea
                                    id="bio"
                                    v-model="formData.bio"
                                    rows="3"
                                    class="w-full px-3 py-2 bg-gray-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                ></textarea>
                            </div>
                            
                            <div class="mb-4">
                                <label for="avatar" class="block text-sm font-medium mb-1">Avatar URL</label>
                                <input
                                    id="avatar"
                                    v-model="formData.avatar"
                                    type="text"
                                    class="w-full px-3 py-2 bg-gray-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    placeholder="https://example.com/avatar.jpg"
                                >
                                <p class="mt-1 text-sm text-gray-400">
                                    Enter a URL for your avatar image
                                </p>
                            </div>
                            
                            <button
                                type="submit"
                                class="bg-blue-600 px-6 py-2 rounded-lg font-medium hover:bg-blue-700 transition flex items-center justify-center"
                                :disabled="loading"
                            >
                                <svg v-if="loading" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                {{ loading ? 'Updating...' : 'Update Profile' }}
                            </button>
                        </form>
                        
                        <div class="mt-8 border-t border-gray-700 pt-4">
                            <h3 class="text-xl font-semibold mb-4 text-red-400">Danger Zone</h3>
                            <div class="bg-gray-700 p-4 rounded-lg">
                                <h4 class="font-medium mb-2">Change Password</h4>
                                <form @submit.prevent="changePassword">
                                    <div class="mb-3">
                                        <label for="current_password" class="block text-sm font-medium mb-1">Current Password</label>
                                        <input
                                            id="current_password"
                                            v-model="passwordData.current_password"
                                            type="password"
                                            required
                                            class="w-full px-3 py-2 bg-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                        >
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="new_password" class="block text-sm font-medium mb-1">New Password</label>
                                        <input
                                            id="new_password"
                                            v-model="passwordData.new_password"
                                            type="password"
                                            required
                                            class="w-full px-3 py-2 bg-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                        >
                                    </div>
                                    
                                    <div class="mb-4">
                                        <label for="new_password_confirmation" class="block text-sm font-medium mb-1">Confirm New Password</label>
                                        <input
                                            id="new_password_confirmation"
                                            v-model="passwordData.new_password_confirmation"
                                            type="password"
                                            required
                                            class="w-full px-3 py-2 bg-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                        >
                                    </div>
                                    
                                    <button
                                        type="submit"
                                        class="bg-red-600 px-6 py-2 rounded-lg font-medium hover:bg-red-700 transition"
                                        :disabled="loading"
                                    >
                                        Change Password
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { ref, computed, onMounted } from 'vue';
import { useStore } from 'vuex';

export default {
    setup() {
        const store = useStore();
        
        const loading = computed(() => store.getters['auth/loading']);
        const error = computed(() => store.getters['auth/error']);
        const user = computed(() => store.getters['auth/user']);
        
        const success = ref('');
        const totalGames = ref(0);
        const highScore = ref(0);
        
        // Form data for profile update
        const formData = ref({
            name: '',
            username: '',
            bio: '',
            avatar: ''
        });
        
        // Form data for password change
        const passwordData = ref({
            current_password: '',
            new_password: '',
            new_password_confirmation: ''
        });
        
        // User initials for avatar fallback
        const userInitials = computed(() => {
            if (!user.value) return '';
            
            const name = user.value.username || user.value.name || '';
            return name.charAt(0).toUpperCase();
        });
        
        // Load user data
        onMounted(async () => {
            // Fetch user data if not already available
            if (!user.value) {
                await store.dispatch('auth/fetchUser');
            }
            
            // Populate form with user data
            if (user.value) {
                formData.value.name = user.value.name || '';
                formData.value.username = user.value.username || '';
                formData.value.bio = user.value.bio || '';
                formData.value.avatar = user.value.avatar || '';
            }
            
            // TODO: Fetch user game stats
            // For now, use mock data
            totalGames.value = 12;
            highScore.value = 320;
        });
        
        // Update profile handler
        const updateProfile = async () => {
            try {
                await store.dispatch('auth/updateProfile', formData.value);
                success.value = 'Profile updated successfully!';
                
                // Clear success message after 3 seconds
                setTimeout(() => {
                    success.value = '';
                }, 3000);
            } catch (error) {
                console.error('Failed to update profile', error);
                // Error is handled in the store
            }
        };
        
        // Change password handler
        const changePassword = async () => {
            if (passwordData.value.new_password !== passwordData.value.new_password_confirmation) {
                store.commit('auth/setError', 'New passwords do not match');
                return;
            }
            
            try {
                // This is where you'd dispatch an action to change password
                // await store.dispatch('auth/changePassword', passwordData.value);
                
                // For now, just show a success message
                success.value = 'Password updated successfully!';
                
                // Clear form
                passwordData.value.current_password = '';
                passwordData.value.new_password = '';
                passwordData.value.new_password_confirmation = '';
                
                // Clear success message after 3 seconds
                setTimeout(() => {
                    success.value = '';
                }, 3000);
            } catch (error) {
                console.error('Failed to change password', error);
            }
        };
        
        return {
            loading,
            error,
            success,
            user,
            formData,
            passwordData,
            userInitials,
            totalGames,
            highScore,
            updateProfile,
            changePassword
        };
    }
}
</script> 