<template>
    <div class="max-w-4xl mx-auto">
        <h1 class="text-3xl font-bold mb-8">Friends & Game Invites</h1>
        
        <div v-if="error" class="bg-red-500 text-white p-3 rounded-lg mb-4">
            {{ error }}
        </div>
        
        <div v-if="success" class="bg-green-500 text-white p-3 rounded-lg mb-4">
            {{ success }}
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <!-- Friend Requests -->
            <div class="bg-gray-800 rounded-lg shadow-lg overflow-hidden">
                <div class="bg-gray-700 px-4 py-3 flex justify-between items-center">
                    <h2 class="text-xl font-semibold">Friend Requests</h2>
                    <span class="bg-blue-600 px-2 py-1 rounded-full text-sm">
                        {{ pendingRequests.length }}
                    </span>
                </div>
                
                <div class="p-4 max-h-96 overflow-y-auto">
                    <div v-if="loading" class="flex justify-center py-6">
                        <svg class="animate-spin h-8 w-8 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </div>
                    
                    <div v-else-if="pendingRequests.length === 0" class="text-center py-6 text-gray-400">
                        No pending friend requests
                    </div>
                    
                    <div v-else>
                        <div 
                            v-for="request in pendingRequests" 
                            :key="request.id" 
                            class="bg-gray-700 rounded-lg p-3 mb-3 flex justify-between items-center"
                        >
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-blue-600 rounded-full overflow-hidden mr-3">
                                    <img 
                                        v-if="request.user?.avatar" 
                                        :src="request.user.avatar" 
                                        alt="Avatar" 
                                        class="w-full h-full object-cover"
                                    >
                                    <div v-else class="w-full h-full flex items-center justify-center">
                                        {{ request.user?.username?.charAt(0) || 'U' }}
                                    </div>
                                </div>
                                <div>
                                    <div class="font-medium">{{ request.user?.username || 'User' }}</div>
                                    <div class="text-xs text-gray-400">
                                        {{ formatDate(request.created_at) }}
                                    </div>
                                </div>
                            </div>
                            
                            <div class="flex space-x-2">
                                <button 
                                    @click="acceptFriendRequest(request.user.id)" 
                                    class="bg-green-600 px-3 py-1 rounded text-sm hover:bg-green-700 transition"
                                >
                                    Accept
                                </button>
                                <button 
                                    @click="rejectFriendRequest(request.user.id)" 
                                    class="bg-red-600 px-3 py-1 rounded text-sm hover:bg-red-700 transition"
                                >
                                    Reject
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Game Invites -->
            <div class="bg-gray-800 rounded-lg shadow-lg overflow-hidden">
                <div class="bg-gray-700 px-4 py-3 flex justify-between items-center">
                    <h2 class="text-xl font-semibold">Game Invites</h2>
                    <span class="bg-blue-600 px-2 py-1 rounded-full text-sm">
                        {{ gameInvites.length }}
                    </span>
                </div>
                
                <div class="p-4 max-h-96 overflow-y-auto">
                    <div v-if="loading" class="flex justify-center py-6">
                        <svg class="animate-spin h-8 w-8 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </div>
                    
                    <div v-else-if="gameInvites.length === 0" class="text-center py-6 text-gray-400">
                        No pending game invites
                    </div>
                    
                    <div v-else>
                        <div 
                            v-for="invite in gameInvites" 
                            :key="invite.id" 
                            class="bg-gray-700 rounded-lg p-3 mb-3"
                        >
                            <div class="flex justify-between items-center mb-2">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-blue-600 rounded-full overflow-hidden mr-3">
                                        <img 
                                            v-if="invite.from_user?.avatar" 
                                            :src="invite.from_user.avatar" 
                                            alt="Avatar" 
                                            class="w-full h-full object-cover"
                                        >
                                        <div v-else class="w-full h-full flex items-center justify-center">
                                            {{ invite.from_user?.username?.charAt(0) || 'U' }}
                                        </div>
                                    </div>
                                    <div>
                                        <div class="font-medium">{{ invite.from_user?.username || 'User' }}</div>
                                        <div class="text-xs text-gray-400">
                                            {{ formatDate(invite.created_at) }}
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="bg-blue-800 px-2 py-1 rounded text-xs">
                                    Game Invite
                                </div>
                            </div>
                            
                            <div class="text-sm mb-3">
                                <span class="text-gray-400">Categories:</span>
                                <span 
                                    v-for="(category, index) in invite.categories" 
                                    :key="category.id"
                                    class="ml-1"
                                >
                                    {{ category.name }}{{ index < invite.categories.length - 1 ? ',' : '' }}
                                </span>
                            </div>
                            
                            <div class="flex space-x-2">
                                <button 
                                    @click="acceptGameInvite(invite.id)" 
                                    class="bg-green-600 px-3 py-1 rounded text-sm hover:bg-green-700 transition"
                                >
                                    Accept & Play
                                </button>
                                <button 
                                    @click="rejectGameInvite(invite.id)" 
                                    class="bg-red-600 px-3 py-1 rounded text-sm hover:bg-red-700 transition"
                                >
                                    Decline
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Friends List -->
        <div class="bg-gray-800 rounded-lg shadow-lg overflow-hidden mb-8">
            <div class="bg-gray-700 px-4 py-3 flex justify-between items-center">
                <h2 class="text-xl font-semibold">Your Friends</h2>
                <span class="bg-blue-600 px-2 py-1 rounded-full text-sm">
                    {{ friends.length }}
                </span>
            </div>
            
            <div class="p-4">
                <div v-if="loading" class="flex justify-center py-6">
                    <svg class="animate-spin h-8 w-8 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </div>
                
                <div v-else-if="friends.length === 0" class="text-center py-6 text-gray-400">
                    You don't have any friends yet
                </div>
                
                <div v-else class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div 
                        v-for="friend in friends" 
                        :key="friend.id" 
                        class="bg-gray-700 rounded-lg p-3 flex justify-between items-center"
                    >
                        <div class="flex items-center">
                            <div class="w-12 h-12 bg-blue-600 rounded-full overflow-hidden mr-3">
                                <img 
                                    v-if="friend.avatar" 
                                    :src="friend.avatar" 
                                    alt="Avatar" 
                                    class="w-full h-full object-cover"
                                >
                                <div v-else class="w-full h-full flex items-center justify-center text-lg">
                                    {{ friend.username?.charAt(0) || 'U' }}
                                </div>
                            </div>
                            <div>
                                <div class="font-medium">{{ friend.username || 'User' }}</div>
                                <div class="text-xs text-gray-400 flex items-center">
                                    <span 
                                        class="inline-block w-2 h-2 rounded-full mr-1"
                                        :class="friend.online ? 'bg-green-500' : 'bg-gray-500'"
                                    ></span>
                                    {{ friend.online ? 'Online' : 'Offline' }}
                                </div>
                            </div>
                        </div>
                        
                        <div class="flex space-x-2">
                            <button 
                                @click="inviteFriendToGame(friend.id)" 
                                class="bg-blue-600 px-3 py-1 rounded text-sm hover:bg-blue-700 transition"
                            >
                                Invite to Game
                            </button>
                            <button 
                                @click="removeFriend(friend.id)" 
                                class="bg-gray-600 px-3 py-1 rounded text-sm hover:bg-gray-500 transition"
                            >
                                Remove
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Find Friends -->
        <div class="bg-gray-800 rounded-lg shadow-lg overflow-hidden">
            <div class="bg-gray-700 px-4 py-3">
                <h2 class="text-xl font-semibold">Find New Friends</h2>
            </div>
            
            <div class="p-4">
                <div class="mb-4">
                    <label for="search" class="block text-sm font-medium mb-1">Search by username or email</label>
                    <div class="flex">
                        <input
                            id="search"
                            v-model="searchQuery"
                            type="text"
                            class="flex-grow px-3 py-2 bg-gray-700 rounded-l-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="Enter username or email"
                            @keyup.enter="searchUsers"
                        >
                        <button
                            @click="searchUsers"
                            class="bg-blue-600 px-4 py-2 rounded-r-lg hover:bg-blue-700 transition"
                        >
                            Search
                        </button>
                    </div>
                </div>
                
                <div v-if="searchResults.length > 0" class="mt-4">
                    <h3 class="text-lg font-medium mb-2">Search Results</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div 
                            v-for="user in searchResults" 
                            :key="user.id" 
                            class="bg-gray-700 rounded-lg p-3 flex justify-between items-center"
                        >
                            <div class="flex items-center">
                                <div class="w-12 h-12 bg-blue-600 rounded-full overflow-hidden mr-3">
                                    <img 
                                        v-if="user.avatar" 
                                        :src="user.avatar" 
                                        alt="Avatar" 
                                        class="w-full h-full object-cover"
                                    >
                                    <div v-else class="w-full h-full flex items-center justify-center text-lg">
                                        {{ user.username?.charAt(0) || 'U' }}
                                    </div>
                                </div>
                                <div>
                                    <div class="font-medium">{{ user.username || 'User' }}</div>
                                    <div v-if="user.relationship" class="text-xs text-gray-400">
                                        {{ getRelationshipText(user.relationship) }}
                                    </div>
                                </div>
                            </div>
                            
                            <button 
                                v-if="!user.relationship || user.relationship === 'none'"
                                @click="sendFriendRequest(user.id)" 
                                class="bg-blue-600 px-3 py-1 rounded text-sm hover:bg-blue-700 transition"
                            >
                                Add Friend
                            </button>
                            <button 
                                v-else-if="user.relationship === 'pending_sent'"
                                disabled
                                class="bg-gray-600 px-3 py-1 rounded text-sm cursor-not-allowed"
                            >
                                Request Sent
                            </button>
                            <div 
                                v-else-if="user.relationship === 'pending_received'"
                                class="flex space-x-2"
                            >
                                <button 
                                    @click="acceptFriendRequest(user.id)" 
                                    class="bg-green-600 px-3 py-1 rounded text-sm hover:bg-green-700 transition"
                                >
                                    Accept
                                </button>
                                <button 
                                    @click="rejectFriendRequest(user.id)" 
                                    class="bg-red-600 px-3 py-1 rounded text-sm hover:bg-red-700 transition"
                                >
                                    Reject
                                </button>
                            </div>
                            <button 
                                v-else-if="user.relationship === 'friends'"
                                disabled
                                class="bg-green-600 px-3 py-1 rounded text-sm cursor-not-allowed"
                            >
                                Already Friends
                            </button>
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
import { useRouter } from 'vue-router';

export default {
    setup() {
        const store = useStore();
        const router = useRouter();
        
        const loading = computed(() => 
            store.getters['friends/loading'] || 
            store.getters['game/loading']
        );
        const error = computed(() => 
            store.getters['friends/error'] || 
            store.getters['game/error']
        );
        
        // Data
        const friends = computed(() => store.getters['friends/allFriends'] || []);
        const pendingRequests = computed(() => store.getters['friends/pendingRequests'] || []);
        const gameInvites = computed(() => store.getters['game/gameInvites'] || []);
        const searchQuery = ref('');
        const searchResults = ref([]);
        const success = ref('');
        
        // Load data on mount
        onMounted(async () => {
            try {
                await Promise.all([
                    store.dispatch('friends/fetchFriends'),
                    store.dispatch('friends/fetchPendingRequests'),
                    store.dispatch('game/fetchGameInvites')
                ]);
            } catch (error) {
                console.error('Failed to fetch data', error);
            }
        });
        
        // Friend request methods
        const sendFriendRequest = async (userId) => {
            try {
                await store.dispatch('friends/sendFriendRequest', userId);
                success.value = 'Friend request sent!';
                // Update search results to show the request was sent
                searchResults.value = searchResults.value.map(user => {
                    if (user.id === userId) {
                        return { ...user, relationship: 'pending_sent' };
                    }
                    return user;
                });
                
                // Clear success message after 3 seconds
                setTimeout(() => {
                    success.value = '';
                }, 3000);
            } catch (error) {
                console.error('Failed to send friend request', error);
            }
        };
        
        const acceptFriendRequest = async (userId) => {
            try {
                await store.dispatch('friends/acceptFriendRequest', userId);
                success.value = 'Friend request accepted!';
                
                // Clear success message after 3 seconds
                setTimeout(() => {
                    success.value = '';
                }, 3000);
            } catch (error) {
                console.error('Failed to accept friend request', error);
            }
        };
        
        const rejectFriendRequest = async (userId) => {
            try {
                await store.dispatch('friends/rejectFriendRequest', userId);
                success.value = 'Friend request rejected!';
                
                // Clear success message after 3 seconds
                setTimeout(() => {
                    success.value = '';
                }, 3000);
            } catch (error) {
                console.error('Failed to reject friend request', error);
            }
        };
        
        const removeFriend = async (userId) => {
            try {
                await store.dispatch('friends/removeFriend', userId);
                success.value = 'Friend removed!';
                
                // Clear success message after 3 seconds
                setTimeout(() => {
                    success.value = '';
                }, 3000);
            } catch (error) {
                console.error('Failed to remove friend', error);
            }
        };
        
        // Game invite methods
        const inviteFriendToGame = async (userId) => {
            // This would show a modal to select categories
            // For now, just use empty categories
            try {
                await store.dispatch('game/sendGameInvite', {
                    userId,
                    categories: []
                });
                success.value = 'Game invite sent!';
                
                // Clear success message after 3 seconds
                setTimeout(() => {
                    success.value = '';
                }, 3000);
            } catch (error) {
                console.error('Failed to send game invite', error);
            }
        };
        
        const acceptGameInvite = async (inviteId) => {
            try {
                await store.dispatch('game/acceptGameInvite', inviteId);
                // The acceptGameInvite action will redirect to the game
            } catch (error) {
                console.error('Failed to accept game invite', error);
            }
        };
        
        const rejectGameInvite = async (inviteId) => {
            try {
                await store.dispatch('game/rejectGameInvite', inviteId);
                success.value = 'Game invite rejected!';
                
                // Clear success message after 3 seconds
                setTimeout(() => {
                    success.value = '';
                }, 3000);
            } catch (error) {
                console.error('Failed to reject game invite', error);
            }
        };
        
        // Search methods
        const searchUsers = async () => {
            if (!searchQuery.value.trim()) return;
            
            try {
                const results = await store.dispatch('friends/searchUsers', searchQuery.value);
                searchResults.value = results;
            } catch (error) {
                console.error('Failed to search users', error);
            }
        };
        
        // Helper methods
        const formatDate = (dateString) => {
            if (!dateString) return '';
            
            const date = new Date(dateString);
            return date.toLocaleDateString();
        };
        
        const getRelationshipText = (relationship) => {
            switch (relationship) {
                case 'pending_sent':
                    return 'Request sent';
                case 'pending_received':
                    return 'Request received';
                case 'friends':
                    return 'Already friends';
                default:
                    return '';
            }
        };
        
        return {
            loading,
            error,
            success,
            friends,
            pendingRequests,
            gameInvites,
            searchQuery,
            searchResults,
            sendFriendRequest,
            acceptFriendRequest,
            rejectFriendRequest,
            removeFriend,
            inviteFriendToGame,
            acceptGameInvite,
            rejectGameInvite,
            searchUsers,
            formatDate,
            getRelationshipText
        };
    }
}
</script> 