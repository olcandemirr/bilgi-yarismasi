<template>
    <div class="max-w-4xl mx-auto">
        <h1 class="text-3xl font-bold mb-8 text-center">Liderlik Tablosu</h1>
        
        <div v-if="loading" class="flex justify-center my-12">
            <svg class="animate-spin h-12 w-12 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
        </div>
        
        <div v-else-if="error" class="bg-red-500 text-white p-3 rounded-lg mb-4">
            {{ error }}
        </div>
        
        <div v-else class="bg-gray-800 rounded-lg shadow-lg overflow-hidden">
            <!-- Tabs -->
            <div class="grid grid-cols-2 bg-gray-700">
                <button 
                    class="py-3 text-center font-medium transition-colors"
                    :class="activeTab === 'individual' ? 'bg-gray-800 text-blue-400' : 'text-gray-300 hover:bg-gray-800/50'"
                    @click="activeTab = 'individual'"
                >
                    Bireysel
                </button>
                <button 
                    class="py-3 text-center font-medium transition-colors"
                    :class="activeTab === 'team' ? 'bg-gray-800 text-blue-400' : 'text-gray-300 hover:bg-gray-800/50'"
                    @click="activeTab = 'team'"
                >
                    Takım
                </button>
            </div>
            
            <!-- Filter bar -->
            <div class="flex items-center justify-between border-b border-gray-700 p-4">
                <div class="text-sm">
                    <label for="period" class="text-gray-400 mr-2">Zaman Dilimi:</label>
                    <select 
                        id="period" 
                        v-model="timePeriod"
                        class="bg-gray-700 px-2 py-1 rounded"
                    >
                        <option value="all">Tüm Zamanlar</option>
                        <option value="week">Bu Hafta</option>
                        <option value="month">Bu Ay</option>
                    </select>
                </div>
                
                <div>
                    <input 
                        type="search"
                        v-model="searchQuery"
                        placeholder="İsme göre ara..."
                        class="bg-gray-700 px-3 py-1 rounded text-sm w-36 sm:w-48"
                    />
                </div>
            </div>
            
            <!-- Leaderboard list -->
            <div class="p-4">
                <div v-if="filteredResults.length === 0" class="text-center py-8 text-gray-400">
                    Sonuç bulunamadı.
                </div>
                
                <div v-else>
                    <div class="grid grid-cols-12 text-sm text-gray-400 px-4 py-2 border-b border-gray-700">
                        <div class="col-span-1">#</div>
                        <div class="col-span-4">{{ activeTab === 'team' ? 'Takım' : 'Oyuncu' }}</div>
                        <div class="col-span-2">{{ activeTab === 'team' ? 'Üyeler' : 'Oyunlar' }}</div>
                        <div class="col-span-3">Puan</div>
                        <div class="col-span-2">Tarih</div>
                    </div>
                    
                    <div v-for="(result, index) in filteredResults" :key="index" class="group">
                        <div 
                            class="grid grid-cols-12 items-center px-4 py-3 border-b border-gray-700 transition hover:bg-gray-700/50"
                            :class="getUserHighlight(result)"
                        >
                            <div class="col-span-1 font-bold">
                                <span 
                                    v-if="index < 3"
                                    class="flex items-center justify-center w-6 h-6 rounded-full"
                                    :class="{
                                        'bg-yellow-500 text-gray-900': index === 0,
                                        'bg-gray-400 text-gray-900': index === 1,
                                        'bg-amber-700 text-gray-900': index === 2
                                    }"
                                >
                                    {{ index + 1 }}
                                </span>
                                <span v-else>{{ index + 1 }}</span>
                            </div>
                            
                            <div class="col-span-4 font-medium flex items-center">
                                <!-- Identity -->
                                <div class="w-8 h-8 bg-blue-600 rounded-full overflow-hidden flex items-center justify-center mr-2">
                                    <img 
                                        v-if="result.avatar" 
                                        :src="result.avatar" 
                                        alt="Avatar" 
                                        class="w-full h-full object-cover"
                                    >
                                    <span v-else class="text-sm">
                                        {{ getInitials(result) }}
                                    </span>
                                </div>
                                <span>{{ result.name }}</span>
                            </div>
                            
                            <div class="col-span-2">
                                {{ activeTab === 'team' ? result.participants : result.games_played }}
                            </div>
                            
                            <div class="col-span-3 font-bold text-blue-400">
                                {{ result.score }}
                            </div>
                            
                            <div class="col-span-2 text-sm text-gray-400">
                                {{ formatDate(result.date) }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- More button -->
            <div v-if="filteredResults.length > 0" class="text-center p-4 border-t border-gray-700">
                <button 
                    v-if="!allResultsShown"
                    @click="loadMore" 
                    class="px-4 py-2 border border-gray-600 rounded hover:bg-gray-700 transition"
                >
                    Daha Fazla Sonuç
                </button>
                <span v-else class="text-gray-500">Başka sonuç yok</span>
            </div>
        </div>
        
        <!-- Join now -->
        <div v-if="!isAuthenticated" class="mt-8 bg-gray-800 rounded-lg p-6 shadow-lg text-center">
            <h3 class="text-xl font-bold mb-3">Yarışmaya Katıl!</h3>
            <p class="mb-4">Diğerleriyle rekabet etmek ve adınızı liderlik tablosunda görmek için bir hesap oluşturun.</p>
            <div class="flex justify-center gap-4">
                <router-link 
                    to="/register" 
                    class="px-6 py-2 bg-blue-600 rounded-lg font-medium hover:bg-blue-700 transition"
                >
                    Kayıt Ol
                </router-link>
                
                <router-link 
                    to="/login" 
                    class="px-6 py-2 border border-blue-500 rounded-lg font-medium hover:bg-blue-800 transition"
                >
                    Giriş Yap
                </router-link>
            </div>
        </div>
        
        <!-- Play again -->
        <div class="mt-8 text-center">
            <router-link 
                to="/game/mode" 
                class="px-6 py-3 bg-blue-600 rounded-lg font-medium hover:bg-blue-700 transition inline-block"
            >
                Oyun Oyna
            </router-link>
        </div>
    </div>
</template>

<script>
import { ref, computed, onMounted, watch } from 'vue';
import { useStore } from 'vuex';

export default {
    setup() {
        const store = useStore();
        
        // State
        const loading = ref(true);
        const error = ref(null);
        const activeTab = ref('individual');
        const timePeriod = ref('all');
        const searchQuery = ref('');
        const limit = ref(10);
        
        // Mock data - would be fetched from API in a real app
        const individualResults = ref([
            { 
                id: 1, 
                name: 'AlexMaster', 
                avatar: null, 
                score: 450, 
                games_played: 15, 
                date: '2023-10-15', 
                is_current_user: false 
            },
            { 
                id: 2, 
                name: 'QuizWizard', 
                avatar: null, 
                score: 420, 
                games_played: 12, 
                date: '2023-10-18', 
                is_current_user: false 
            },
            { 
                id: 3, 
                name: 'BrainBox', 
                avatar: null, 
                score: 380, 
                games_played: 8, 
                date: '2023-10-20', 
                is_current_user: false 
            },
            { 
                id: 4, 
                name: 'KnowledgeKing', 
                avatar: null, 
                score: 350, 
                games_played: 7, 
                date: '2023-10-22', 
                is_current_user: true 
            },
            { 
                id: 5, 
                name: 'TriviaMaster', 
                avatar: null, 
                score: 320, 
                games_played: 9, 
                date: '2023-10-21', 
                is_current_user: false 
            },
            { 
                id: 6, 
                name: 'GeniusQuizzer', 
                avatar: null, 
                score: 310, 
                games_played: 5, 
                date: '2023-10-23', 
                is_current_user: false 
            },
            { 
                id: 7, 
                name: 'MindBender', 
                avatar: null, 
                score: 290, 
                games_played: 6, 
                date: '2023-10-19', 
                is_current_user: false 
            },
            { 
                id: 8, 
                name: 'SmartyCat', 
                avatar: null, 
                score: 270, 
                games_played: 8, 
                date: '2023-10-17', 
                is_current_user: false 
            },
            { 
                id: 9, 
                name: 'BrainiacChamp', 
                avatar: null, 
                score: 250, 
                games_played: 5, 
                date: '2023-10-18', 
                is_current_user: false 
            },
            { 
                id: 10, 
                name: 'FactsFirst', 
                avatar: null, 
                score: 230, 
                games_played: 4, 
                date: '2023-10-20', 
                is_current_user: false 
            },
            { 
                id: 11, 
                name: 'TriviaQueen', 
                avatar: null, 
                score: 220, 
                games_played: 7, 
                date: '2023-10-19', 
                is_current_user: false 
            },
            { 
                id: 12, 
                name: 'WisdomSeeker', 
                avatar: null, 
                score: 210, 
                games_played: 6, 
                date: '2023-10-21', 
                is_current_user: false 
            },
            { 
                id: 13, 
                name: 'QuizNinja', 
                avatar: null, 
                score: 200, 
                games_played: 5, 
                date: '2023-10-20', 
                is_current_user: false 
            },
        ]);
        
        const teamResults = ref([
            { 
                id: 1, 
                name: 'Brain Busters', 
                avatar: null, 
                score: 520, 
                participants: 4, 
                date: '2023-10-15', 
                is_current_user: false 
            },
            { 
                id: 2, 
                name: 'Trivia Titans', 
                avatar: null, 
                score: 490, 
                participants: 5, 
                date: '2023-10-18', 
                is_current_user: false 
            },
            { 
                id: 3, 
                name: 'Quiz Wizards', 
                avatar: null, 
                score: 480, 
                participants: 3, 
                date: '2023-10-20', 
                is_current_user: false 
            },
            { 
                id: 4, 
                name: 'Knowledge Ninjas', 
                avatar: null, 
                score: 450, 
                participants: 4, 
                date: '2023-10-22', 
                is_current_user: true 
            },
            { 
                id: 5, 
                name: 'Fact Finders', 
                avatar: null, 
                score: 420, 
                participants: 5, 
                date: '2023-10-21', 
                is_current_user: false 
            },
            { 
                id: 6, 
                name: 'Question Masters', 
                avatar: null, 
                score: 400, 
                participants: 3, 
                date: '2023-10-23', 
                is_current_user: false 
            },
            { 
                id: 7, 
                name: 'Smart Cookies', 
                avatar: null, 
                score: 380, 
                participants: 4, 
                date: '2023-10-19', 
                is_current_user: false 
            },
            { 
                id: 8, 
                name: 'Brainiacs', 
                avatar: null, 
                score: 370, 
                participants: 6, 
                date: '2023-10-17', 
                is_current_user: false 
            },
            { 
                id: 9, 
                name: 'Intellectual Giants', 
                avatar: null, 
                score: 350, 
                participants: 5, 
                date: '2023-10-18', 
                is_current_user: false 
            },
            { 
                id: 10, 
                name: 'Mind Benders', 
                avatar: null, 
                score: 330, 
                participants: 4, 
                date: '2023-10-20', 
                is_current_user: false 
            },
            { 
                id: 11, 
                name: 'Wisdom Seekers', 
                avatar: null, 
                score: 320, 
                participants: 5, 
                date: '2023-10-19', 
                is_current_user: false 
            },
            { 
                id: 12, 
                name: 'Quiz Legends', 
                avatar: null, 
                score: 310, 
                participants: 3, 
                date: '2023-10-21', 
                is_current_user: false 
            },
        ]);
        
        // Authentication state
        const isAuthenticated = computed(() => store.getters['auth/isAuthenticated']);
        
        // Results based on active tab
        const results = computed(() => {
            return activeTab.value === 'individual' ? individualResults.value : teamResults.value;
        });
        
        // Filtered results
        const filteredResults = computed(() => {
            let filtered = [...results.value];
            
            // Filter by search query
            if (searchQuery.value) {
                const query = searchQuery.value.toLowerCase();
                filtered = filtered.filter(result => 
                    result.name.toLowerCase().includes(query)
                );
            }
            
            // Filter by time period
            if (timePeriod.value !== 'all') {
                const now = new Date();
                const oneDay = 24 * 60 * 60 * 1000;
                
                if (timePeriod.value === 'week') {
                    const oneWeekAgo = new Date(now.getTime() - 7 * oneDay);
                    filtered = filtered.filter(result => 
                        new Date(result.date) >= oneWeekAgo
                    );
                } else if (timePeriod.value === 'month') {
                    const oneMonthAgo = new Date(now.getFullYear(), now.getMonth() - 1, now.getDate());
                    filtered = filtered.filter(result => 
                        new Date(result.date) >= oneMonthAgo
                    );
                }
            }
            
            // Apply limit
            return filtered.slice(0, limit.value);
        });
        
        // Whether all results are shown
        const allResultsShown = computed(() => {
            // Determine if all filtered results are shown
            let allFiltered = [...results.value];
            
            // Apply same filters as filteredResults
            if (searchQuery.value) {
                const query = searchQuery.value.toLowerCase();
                allFiltered = allFiltered.filter(result => 
                    result.name.toLowerCase().includes(query)
                );
            }
            
            if (timePeriod.value !== 'all') {
                const now = new Date();
                const oneDay = 24 * 60 * 60 * 1000;
                
                if (timePeriod.value === 'week') {
                    const oneWeekAgo = new Date(now.getTime() - 7 * oneDay);
                    allFiltered = allFiltered.filter(result => 
                        new Date(result.date) >= oneWeekAgo
                    );
                } else if (timePeriod.value === 'month') {
                    const oneMonthAgo = new Date(now.getFullYear(), now.getMonth() - 1, now.getDate());
                    allFiltered = allFiltered.filter(result => 
                        new Date(result.date) >= oneMonthAgo
                    );
                }
            }
            
            return limit.value >= allFiltered.length;
        });
        
        // Load more results
        const loadMore = () => {
            limit.value += 10;
        };
        
        // Reset limit when tab changes
        watch(activeTab, () => {
            limit.value = 10;
        });
        
        // Reset limit when filters change
        watch([timePeriod, searchQuery], () => {
            limit.value = 10;
        });
        
        // Format date
        const formatDate = (dateString) => {
            const date = new Date(dateString);
            return date.toLocaleDateString(undefined, { month: 'short', day: 'numeric', year: 'numeric' });
        };
        
        // Get user highlight classes
        const getUserHighlight = (result) => {
            return result.is_current_user ? 'bg-blue-900/30' : '';
        };
        
        // Get initials for avatar
        const getInitials = (result) => {
            return result.name.charAt(0).toUpperCase();
        };
        
        // Simulate API call
        onMounted(() => {
            // Simulate loading
            setTimeout(() => {
                loading.value = false;
            }, 1000);
        });
        
        return {
            loading,
            error,
            activeTab,
            timePeriod,
            searchQuery,
            filteredResults,
            isAuthenticated,
            allResultsShown,
            loadMore,
            formatDate,
            getUserHighlight,
            getInitials
        };
    }
}
</script> 