<template>
    <div class="max-w-4xl mx-auto">
        <!-- Loading state -->
        <div v-if="loading" class="flex justify-center items-center min-h-[60vh]">
            <svg class="animate-spin h-16 w-16 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
        </div>
        
        <!-- Error state -->
        <div v-else-if="error" class="bg-red-500 text-white p-4 rounded-lg my-4">
            {{ error }}
            <div class="mt-4">
                <router-link to="/game/mode" class="px-4 py-2 bg-white text-red-600 rounded-lg font-medium">
                    Baştan Başla
                </router-link>
            </div>
        </div>
        
        <!-- Game is not active -->
        <div v-else-if="!isGameActive" class="text-center my-12">
            <h2 class="text-2xl font-bold mb-4">Aktif Oyun Yok</h2>
            <p class="mb-6">Önce bir oyun modu ve kategoriler seçmeniz gerekiyor.</p>
            <router-link to="/game/mode" class="px-6 py-3 bg-blue-600 rounded-lg font-medium hover:bg-blue-700 transition">
                Oyun Başlat
            </router-link>
        </div>
        
        <!-- Game is active -->
        <div v-else>
            <!-- Game header -->
            <div class="flex justify-between items-center mb-6">
                <div>
                    <span class="text-sm text-gray-400">{{ gameMode === 'team' ? 'Takım' : 'Oyuncu' }}:</span>
                    <span class="ml-1 font-medium">{{ playerName }}</span>
                </div>
                
                <div class="bg-gray-800 px-4 py-2 rounded-lg">
                    <span class="text-sm text-gray-400">Puan:</span>
                    <span class="ml-1 font-bold text-blue-400">{{ score }}</span>
                </div>
            </div>
            
            <!-- Progress bar -->
            <div class="bg-gray-800 rounded-full h-2 mb-8 overflow-hidden">
                <div 
                    class="bg-blue-600 h-full transition-all duration-300"
                    :style="{ width: `${progress}%` }"
                ></div>
            </div>
            
            <div v-if="currentQuestion" class="bg-gray-800 rounded-lg p-6 shadow-lg mb-8">
                <!-- Question header -->
                <div class="flex justify-between items-center mb-4">
                    <span class="bg-gray-700 px-3 py-1 rounded-lg text-sm">
                        Soru {{ currentQuestionIndex + 1 }} / {{ totalQuestions }}
                    </span>
                    
                    <span 
                        class="bg-gray-700 px-3 py-1 rounded-lg text-sm flex items-center"
                        :class="{'text-red-400': timeRemaining <= 5}"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        {{ timeRemaining }}sn
                    </span>
                </div>
                
                <!-- Difficulty badge -->
                <div class="mb-4">
                    <span 
                        class="px-3 py-1 rounded-full text-xs font-medium"
                        :class="{
                            'bg-green-600': currentQuestion.difficulty === 'easy',
                            'bg-yellow-600': currentQuestion.difficulty === 'medium',
                            'bg-red-600': currentQuestion.difficulty === 'hard'
                        }"
                    >
                        {{ getDifficultyText(currentQuestion.difficulty) }}
                    </span>
                </div>
                
                <!-- Question text -->
                <h2 class="text-xl font-semibold mb-6">{{ currentQuestion.question }}</h2>
                
                <!-- Options -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <button 
                        v-for="(option, index) in parsedOptions" 
                        :key="index"
                        @click="submitAnswer(index)"
                        class="p-4 rounded-lg text-left transition-all duration-200"
                        :class="getOptionClass(index)"
                        :disabled="selectedAnswerIndex !== null"
                    >
                        <span class="mr-2 font-bold">{{ ['A', 'B', 'C', 'D'][index] }}.</span>
                        {{ option }}
                    </button>
                </div>
                
                <!-- Feedback message after answering -->
                <div 
                    v-if="selectedAnswerIndex !== null" 
                    class="mt-6 p-4 rounded-lg text-center"
                    :class="{
                        'bg-green-600/20 text-green-400': isLastAnswerCorrect,
                        'bg-red-600/20 text-red-400': !isLastAnswerCorrect && selectedAnswerIndex !== null,
                    }"
                >
                    <div v-if="isLastAnswerCorrect" class="font-medium">
                        Doğru! +{{ lastEarnedPoints }} puan
                    </div>
                    <div v-else class="font-medium">
                        Yanlış. Doğru cevap: {{ ['A', 'B', 'C', 'D'][currentQuestion.correct_answer] }}.
                    </div>
                </div>
            </div>
        </div>
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
        
        // Game state from store
        const loading = computed(() => store.getters['game/loading']);
        const error = computed(() => store.getters['game/error']);
        const isGameActive = computed(() => store.getters['game/isGameActive']);
        const gameMode = computed(() => store.getters['game/gameMode']);
        const playerInfo = computed(() => store.getters['game/playerInfo']);
        const currentQuestion = computed(() => store.getters['game/currentQuestion']);
        const currentQuestionIndex = computed(() => store.getters['game/currentQuestionIndex']);
        const totalQuestions = computed(() => store.getters['game/totalQuestions']);
        const score = computed(() => store.getters['game/score']);
        const progress = computed(() => store.getters['game/progress']);
        const timeRemaining = computed(() => store.getters['game/timeRemaining']);
        
        // Local state
        const selectedAnswerIndex = ref(null);
        const isLastAnswerCorrect = ref(false);
        const lastEarnedPoints = ref(0);
        
        // Derived data
        const playerName = computed(() => {
            if (!playerInfo.value) return '';
            
            return gameMode.value === 'team' 
                ? playerInfo.value.teamName 
                : playerInfo.value.name;
        });
        
        // Parse options from JSON string if needed
        const parsedOptions = computed(() => {
            if (!currentQuestion.value) return [];
            
            // Handle options stored as JSON string
            if (typeof currentQuestion.value.options === 'string') {
                try {
                    return JSON.parse(currentQuestion.value.options);
                } catch (e) {
                    console.error('Seçenekler JSON olarak ayrıştırılamadı', e);
                    return [];
                }
            }
            
            // Handle options stored as array
            return currentQuestion.value.options || [];
        });
        
        // Watch for question changes to reset selection
        watch(currentQuestionIndex, () => {
            selectedAnswerIndex.value = null;
            isLastAnswerCorrect.value = false;
            lastEarnedPoints.value = 0;
        });
        
        // CSS classes for options
        const getOptionClass = (index) => {
            // Base classes
            const baseClasses = 'bg-gray-700 hover:bg-gray-600';
            
            // If no answer selected yet
            if (selectedAnswerIndex === null) {
                return baseClasses;
            }
            
            // Correct answer
            if (currentQuestion.value.correct_answer === index) {
                return 'bg-green-600 hover:bg-green-600';
            }
            
            // Selected but incorrect answer
            if (selectedAnswerIndex.value === index) {
                return 'bg-red-600 hover:bg-red-600';
            }
            
            // Other options after answering
            return 'bg-gray-700 opacity-50';
        };
        
        // Submit an answer
        const submitAnswer = (index) => {
            // Prevent multiple submissions
            if (selectedAnswerIndex.value !== null) return;
            
            selectedAnswerIndex.value = index;
            
            // Call store action to handle scoring and progression
            const result = store.dispatch('game/submitAnswer', index);
            
            // Update local state based on result
            isLastAnswerCorrect.value = currentQuestion.value.correct_answer === index;
            lastEarnedPoints.value = isLastAnswerCorrect.value ? 
                calculatePoints(currentQuestion.value.difficulty, timeRemaining.value) : 0;
            
            // If this is the last question, navigate to results after a delay
            if (currentQuestionIndex.value === totalQuestions.value - 1) {
                setTimeout(() => {
                    router.push('/game/results');
                }, 2000);
            }
        };
        
        // Calculate points for a correct answer
        const calculatePoints = (difficulty, timeLeft) => {
            const basePoints = {
                'easy': 10,
                'medium': 20,
                'hard': 30
            }[difficulty] || 10;
            
            // Bonus for answering quickly (up to 100% bonus)
            const timeBonus = Math.round((timeLeft / 30) * basePoints);
            
            return basePoints + timeBonus;
        };
        
        // Get difficulty text in Turkish
        const getDifficultyText = (difficulty) => {
            const difficultyMap = {
                'easy': 'Kolay',
                'medium': 'Orta',
                'hard': 'Zor'
            };
            
            return difficultyMap[difficulty] || difficulty.charAt(0).toUpperCase() + difficulty.slice(1);
        };
        
        // Handle case when user directly navigates to game page without setup
        onMounted(() => {
            if (!isGameActive.value && !loading.value) {
                // Redirect to game mode selection if no active game
                router.push('/game/mode');
            }
        });
        
        return {
            loading,
            error,
            isGameActive,
            gameMode,
            playerInfo,
            playerName,
            currentQuestion,
            currentQuestionIndex,
            totalQuestions,
            score,
            progress,
            timeRemaining,
            parsedOptions,
            selectedAnswerIndex,
            isLastAnswerCorrect,
            lastEarnedPoints,
            getOptionClass,
            submitAnswer,
            getDifficultyText
        };
    }
}
</script> 