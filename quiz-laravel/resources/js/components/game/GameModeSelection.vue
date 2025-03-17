<template>
    <div class="max-w-4xl mx-auto">
        <h1 class="text-3xl font-bold mb-8 text-center">Oyun Modu Seçin</h1>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-12">
            <!-- Bireysel Mod -->
            <div 
                class="bg-gray-800 rounded-lg p-6 shadow-lg cursor-pointer border-2 transition duration-200"
                :class="selectedMode === 'individual' ? 'border-blue-500 scale-105' : 'border-transparent hover:scale-105'"
                @click="selectMode('individual')"
            >
                <div class="text-center mb-6">
                    <div class="w-20 h-20 mx-auto bg-blue-600 rounded-full flex items-center justify-center mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                    <h2 class="text-2xl font-bold mb-2">Bireysel Mod</h2>
                    <p class="text-gray-400">
                        Kendi başınıza oynayın ve liderlik tablosuna yükselin. Ne kadar hızlı cevap verirseniz, o kadar çok puan kazanırsınız!
                    </p>
                </div>
                
                <div v-if="selectedMode === 'individual'" class="mt-4">
                    <form @submit.prevent="startGame">
                        <div class="mb-4">
                            <label for="playerName" class="block text-sm font-medium text-gray-400 mb-1">Oyuncu Adınız</label>
                            <input 
                                type="text" 
                                id="playerName" 
                                v-model="playerName"
                                class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                placeholder="Adınızı girin"
                                required
                            />
                        </div>
                        
                        <div class="text-center">
                            <button 
                                type="submit"
                                class="px-5 py-2 bg-blue-600 rounded-lg font-medium hover:bg-blue-700 transition disabled:opacity-50 disabled:cursor-not-allowed"
                                :disabled="!playerName"
                            >
                                Devam Et
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Takım Modu -->
            <div 
                class="bg-gray-800 rounded-lg p-6 shadow-lg cursor-pointer border-2 transition duration-200"
                :class="selectedMode === 'team' ? 'border-blue-500 scale-105' : 'border-transparent hover:scale-105'"
                @click="selectMode('team')"
            >
                <div class="text-center mb-6">
                    <div class="w-20 h-20 mx-auto bg-blue-600 rounded-full flex items-center justify-center mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <h2 class="text-2xl font-bold mb-2">Takım Modu</h2>
                    <p class="text-gray-400">
                        Arkadaşlarınızla birlikte oynayın ve diğer takımlara karşı yarışın. Soruları tartışarak daha yüksek puan almaya çalışın!
                    </p>
                </div>
                
                <div v-if="selectedMode === 'team'" class="mt-4">
                    <form @submit.prevent="startGame">
                        <div class="mb-4">
                            <label for="teamName" class="block text-sm font-medium text-gray-400 mb-1">Takım Adı</label>
                            <input 
                                type="text" 
                                id="teamName" 
                                v-model="teamName"
                                class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                placeholder="Takım adınızı girin"
                                required
                            />
                        </div>
                        
                        <div class="mb-4">
                            <label for="teamSize" class="block text-sm font-medium text-gray-400 mb-1">Takım Üyesi Sayısı</label>
                            <select 
                                id="teamSize" 
                                v-model="teamSize"
                                class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                required
                            >
                                <option value="2">2 Oyuncu</option>
                                <option value="3">3 Oyuncu</option>
                                <option value="4">4 Oyuncu</option>
                                <option value="5">5 Oyuncu</option>
                            </select>
                        </div>
                        
                        <div class="text-center">
                            <button 
                                type="submit"
                                class="px-5 py-2 bg-blue-600 rounded-lg font-medium hover:bg-blue-700 transition disabled:opacity-50 disabled:cursor-not-allowed"
                                :disabled="!teamName || !teamSize"
                            >
                                Devam Et
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { ref } from 'vue';
import { useStore } from 'vuex';
import { useRouter } from 'vue-router';

export default {
    setup() {
        const store = useStore();
        const router = useRouter();
        
        // Game mode selection
        const selectedMode = ref('');
        const playerName = ref('');
        const teamName = ref('');
        const teamSize = ref('3');
        
        const selectMode = (mode) => {
            selectedMode.value = mode;
        };
        
        const startGame = () => {
            // Prepare player info based on selected mode
            const playerInfo = selectedMode.value === 'individual'
                ? { name: playerName.value }
                : { teamName: teamName.value, participantCount: parseInt(teamSize.value) };
            
            // Store game mode and player info in Vuex
            store.commit('game/setGameMode', selectedMode.value);
            store.commit('game/setPlayerInfo', playerInfo);
            
            // Navigate to category selection
            router.push('/game/categories');
        };
        
        return {
            selectedMode,
            playerName,
            teamName,
            teamSize,
            selectMode,
            startGame
        };
    }
}
</script> 