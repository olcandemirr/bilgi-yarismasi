<template>
    <div class="max-w-4xl mx-auto">
        <h1 class="text-3xl font-bold mb-8 text-center">Kategorileri Seçin</h1>
        
        <div v-if="error" class="bg-red-500 text-white p-3 rounded-lg mb-4">
            {{ error }}
        </div>
        
        <p class="text-lg mb-6 text-center">
            Quiziniz için istediğiniz kategorileri seçin. Her kategoriden dengeli bir soru karışımı hazırlayacağız.
        </p>
        
        <div v-if="loading" class="flex justify-center my-12">
            <svg class="animate-spin h-12 w-12 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
        </div>
        
        <div v-else>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 mb-8">
                <div 
                    v-for="category in categories" 
                    :key="category.id"
                    class="bg-gray-800 rounded-lg p-5 shadow-lg cursor-pointer border-2 transform transition duration-200"
                    :class="isSelected(category.id) ? 'border-blue-500 scale-105' : 'border-transparent hover:scale-105'"
                    @click="toggleCategory(category.id)"
                >
                    <div class="flex flex-col items-center">
                        <div class="w-16 h-16 bg-blue-600 rounded-full flex items-center justify-center mb-3">
                            <!-- Category icon based on category name -->
                            <span class="text-xl font-bold">{{ getCategoryIcon(category.name) }}</span>
                        </div>
                        <h3 class="text-xl font-semibold text-center">{{ category.name }}</h3>
                        <p class="text-sm text-gray-400 text-center mt-2">{{ category.question_count || 0 }} soru</p>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="flex justify-between items-center mt-12">
            <div>
                <router-link 
                    to="/game/mode" 
                    class="px-6 py-2 rounded-lg border border-blue-500 hover:bg-blue-800 transition"
                >
                    Geri
                </router-link>
            </div>
            
            <div class="text-center">
                <p v-if="selectedCategories.length === 0" class="mb-2 text-red-400">
                    Lütfen en az bir kategori seçin
                </p>
                <p v-else class="mb-2">
                    {{ selectedCategories.length }} kategori seçildi
                </p>
            </div>
            
            <div>
                <button 
                    @click="startGame"
                    class="px-6 py-2 rounded-lg bg-blue-600 hover:bg-blue-700 transition"
                    :disabled="selectedCategories.length === 0 || loading"
                >
                    Quize Başla
                </button>
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
        
        const loading = computed(() => store.getters['game/loading']);
        const error = computed(() => store.getters['game/error']);
        const categories = computed(() => store.getters['game/categories'] || []);
        
        const selectedCategories = ref([]);
        
        // Fetch categories on mount
        onMounted(async () => {
            try {
                await store.dispatch('game/fetchCategories');
            } catch (error) {
                console.error('Kategoriler yüklenemedi', error);
            }
        });
        
        const isSelected = (categoryId) => {
            return selectedCategories.value.includes(categoryId);
        };
        
        const toggleCategory = (categoryId) => {
            if (isSelected(categoryId)) {
                selectedCategories.value = selectedCategories.value.filter(id => id !== categoryId);
            } else {
                selectedCategories.value.push(categoryId);
            }
        };
        
        const startGame = async () => {
            if (selectedCategories.value.length === 0) {
                return;
            }
            
            try {
                // Get game mode and player info from store
                const gameMode = store.getters['game/gameMode'];
                const playerInfo = store.getters['game/playerInfo'];
                
                // Start the game with selected categories
                await store.dispatch('game/startGame', {
                    gameMode,
                    playerInfo,
                    categories: selectedCategories.value
                });
                
                // Navigate to game component
                router.push('/game');
            } catch (error) {
                console.error('Oyun başlatılamadı', error);
            }
        };
        
        // Get an icon based on category name
        const getCategoryIcon = (categoryName) => {
            // Türkçe ve İngilizce kategori isimlerini destekle
            const name = categoryName.toLowerCase();
            
            if (name.includes('bilim') || name.includes('science')) return '🔬';
            if (name.includes('tarih') || name.includes('history')) return '📜';
            if (name.includes('coğraf') || name.includes('geography')) return '🌍';
            if (name.includes('spor') || name.includes('sport')) return '⚽';
            if (name.includes('müzik') || name.includes('music')) return '🎵';
            if (name.includes('film') || name.includes('movie')) return '🎬';
            if (name.includes('teknoloji') || name.includes('tech')) return '💻';
            if (name.includes('sanat') || name.includes('art')) return '🎨';
            if (name.includes('edebiyat') || name.includes('literature')) return '📚';
            if (name.includes('yemek') || name.includes('food')) return '🍲';
            if (name.includes('hayvan') || name.includes('animal')) return '🐾';
            if (name.includes('matematik') || name.includes('math')) return '🔢';
            
            // Default icon for other categories
            return categoryName.charAt(0).toUpperCase();
        };
        
        return {
            loading,
            error,
            categories,
            selectedCategories,
            isSelected,
            toggleCategory,
            startGame,
            getCategoryIcon
        };
    }
}
</script> 