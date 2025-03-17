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
        
        <!-- Game not finished -->
        <div v-else-if="!isGameFinished" class="text-center my-12">
            <h2 class="text-2xl font-bold mb-4">Sonuç Bulunamadı</h2>
            <p class="mb-6">Sonuçları görmek için önce bir oyunu tamamlamanız gerekiyor.</p>
            <router-link to="/game/mode" class="px-6 py-3 bg-blue-600 rounded-lg font-medium hover:bg-blue-700 transition">
                Oyun Başlat
            </router-link>
        </div>
        
        <!-- Results display -->
        <div v-else>
            <div class="text-center mb-12">
                <h1 class="text-4xl font-bold mb-2">Oyun Tamamlandı!</h1>
                <p class="text-xl text-gray-300">İşte performansınız</p>
            </div>
            
            <!-- Score card -->
            <div class="bg-gray-800 rounded-lg p-8 shadow-lg mb-8 text-center">
                <div class="mb-6">
                    <span class="text-sm text-gray-400">{{ gameMode === 'team' ? 'Takım' : 'Oyuncu' }}:</span>
                    <span class="ml-1 font-medium text-lg">{{ playerName }}</span>
                </div>
                
                <div class="flex justify-center items-center mb-8">
                    <div class="w-32 h-32 bg-blue-600 rounded-full flex items-center justify-center relative">
                        <span class="text-4xl font-bold">{{ score }}</span>
                        <span class="absolute -top-2 -right-2 bg-green-500 rounded-full w-10 h-10 flex items-center justify-center text-sm font-bold">
                            {{ getScoreGrade(score) }}
                        </span>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
                    <div class="bg-gray-700 p-4 rounded-lg">
                        <div class="text-3xl font-bold text-blue-400">{{ correctAnswers }}</div>
                        <div class="text-sm text-gray-400">Doğru Cevap</div>
                    </div>
                    
                    <div class="bg-gray-700 p-4 rounded-lg">
                        <div class="text-3xl font-bold text-blue-400">{{ totalQuestions }}</div>
                        <div class="text-sm text-gray-400">Toplam Soru</div>
                    </div>
                    
                    <div class="bg-gray-700 p-4 rounded-lg">
                        <div class="text-3xl font-bold text-blue-400">{{ accuracy }}%</div>
                        <div class="text-sm text-gray-400">Doğruluk Oranı</div>
                    </div>
                </div>
                
                <div v-if="rank" class="mb-8">
                    <div class="text-lg">Sıralamanız</div>
                    <div class="text-3xl font-bold text-yellow-400 mt-2">
                        #{{ rank }}
                    </div>
                    <div class="text-sm text-gray-400 mt-1">
                        liderlik tablosunda
                    </div>
                </div>
                
                <div v-if="isAuthenticated" class="text-gray-400 mb-8">
                    Puanınız liderlik tablosuna kaydedildi!
                </div>
                
                <div class="flex flex-col md:flex-row gap-4 justify-center">
                    <router-link 
                        to="/game/mode" 
                        class="px-6 py-3 bg-blue-600 rounded-lg font-medium hover:bg-blue-700 transition text-center"
                    >
                        Tekrar Oyna
                    </router-link>
                    
                    <router-link 
                        to="/leaderboard" 
                        class="px-6 py-3 border border-blue-500 rounded-lg font-medium hover:bg-blue-800 transition text-center"
                    >
                        Liderlik Tablosunu Görüntüle
                    </router-link>
                </div>
            </div>
            
            <!-- Share section -->
            <div class="bg-gray-800 rounded-lg p-6 shadow-lg text-center">
                <h3 class="text-xl font-semibold mb-4">Sonucunuzu Paylaşın</h3>
                
                <div class="mb-6">
                    <p class="mb-2">Bu başarınızı arkadaşlarınızla paylaşın:</p>
                    <div class="bg-gray-700 p-3 rounded-lg text-sm mb-4">
                        Sınav Ustası uygulamasında {{ score }} puan aldım! Beni geçebilir misin? 🎮 #SınavUstası
                    </div>
                    
                    <div class="flex justify-center gap-4">
                        <button class="bg-blue-600 p-2 rounded-lg hover:bg-blue-700 transition">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M22 12c0-5.52-4.48-10-10-10S2 6.48 2 12c0 4.84 3.44 8.87 8 9.8V15H8v-3h2V9.5C10 7.57 11.57 6 13.5 6H16v3h-2c-.55 0-1 .45-1 1v2h3v3h-3v6.95c5.05-.5 9-4.76 9-9.95z"></path>
                            </svg>
                        </button>
                        
                        <button class="bg-blue-400 p-2 rounded-lg hover:bg-blue-500 transition">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M22.46 6c-.77.35-1.6.58-2.46.69.88-.53 1.56-1.37 1.88-2.38-.83.5-1.75.85-2.72 1.05C18.37 4.5 17.26 4 16 4c-2.35 0-4.27 1.92-4.27 4.29 0 .34.04.67.11.98C8.28 9.09 5.11 7.38 3 4.79c-.37.63-.58 1.37-.58 2.15 0 1.49.75 2.81 1.91 3.56-.71 0-1.37-.2-1.95-.5v.03c0 2.08 1.48 3.82 3.44 4.21a4.22 4.22 0 0 1-1.93.07 4.28 4.28 0 0 0 4 2.98 8.521 8.521 0 0 1-5.33 1.84c-.34 0-.68-.02-1.02-.06C3.44 20.29 5.7 21 8.12 21 16 21 20.33 14.46 20.33 8.79c0-.19 0-.37-.01-.56.84-.6 1.56-1.36 2.14-2.23z"></path>
                            </svg>
                        </button>
                        
                        <button class="bg-green-600 p-2 rounded-lg hover:bg-green-700 transition">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M21.79 1.21A.992.992 0 0 0 21 1h-7c-1.1 0-2 .9-2 2v7c0 .55.45 1 1 1h3.73c-.19.36-.43.69-.73.94-.39.33-.92.56-1.5.56-.88 0-1.67-.39-2.14-1h-2.22C10.53 13.57 12.37 15 14.5 15c.69 0 1.32-.12 1.9-.34l3.35 3.35c.2.2.51.2.71 0l1.4-1.4c.2-.2.2-.51 0-.71L18.62 14H18c0-2.95-1.05-5.67-2.79-7.79A6.996 6.996 0 0 0 9.5 2C5.91 2 3 4.91 3 8.5c0 .5.06.98.17 1.45C4.72 13.5 8.57 16 13 16c1.36 0 2.67-.23 3.92-.67l.43-.19c.39-.17.85-.02 1.08.31.76 1.11 2.07 1.83 3.57 1.83 1.45 0 2.71-.66 3.5-1.67l-1.39-1.39c-.57.35-1.24.56-1.96.56-1.1 0-2.07-.46-2.79-1.2C20.32 12.95 21 11.36 21 9.5c0-.69-.1-1.36-.26-2H23c.55 0 1-.45 1-1V3c0-.55-.45-1-1-1h-2.21zm-6.58 1.47L13.5 4l2.5 2.5-1.43 1.4L12.45 5.8l2.76-3.12z"></path>
                            </svg>
                        </button>
                    </div>
                </div>
                
                <div v-if="!isAuthenticated" class="border-t border-gray-700 pt-4">
                    <p class="mb-4">Puanlarınızı kaydetmek ve diğerleriyle rekabet etmek için hesap oluşturun!</p>
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
            </div>
        </div>
    </div>
</template>

<script>
import { computed, onMounted } from 'vue';
import { useStore } from 'vuex';
import { useRouter } from 'vue-router';

export default {
    setup() {
        const store = useStore();
        const router = useRouter();
        
        // Game state from store
        const loading = computed(() => store.getters['game/loading']);
        const error = computed(() => store.getters['game/error']);
        const isGameFinished = computed(() => store.getters['game/isGameFinished']);
        const gameMode = computed(() => store.getters['game/gameMode']);
        const playerInfo = computed(() => store.getters['game/playerInfo']);
        const score = computed(() => store.getters['game/score']);
        const totalQuestions = computed(() => store.getters['game/totalQuestions']);
        
        // Auth state
        const isAuthenticated = computed(() => store.getters['auth/isAuthenticated']);
        
        // Mock data for now - would be calculated from actual results in a real app
        const correctAnswers = computed(() => {
            // Estimate correct answers based on score and average points per question
            const avgPointsPerQuestion = 15; // Assuming average 15 points per correct answer
            return Math.round(score.value / avgPointsPerQuestion);
        });
        
        const accuracy = computed(() => {
            if (totalQuestions.value === 0) return 0;
            return Math.round((correctAnswers.value / totalQuestions.value) * 100);
        });
        
        // Mock rank for now - would be fetched from API in a real app
        const rank = computed(() => {
            if (score.value < 50) return 120;
            if (score.value < 100) return 78;
            if (score.value < 200) return 45;
            if (score.value < 300) return 23;
            return 10;
        });
        
        // Player name
        const playerName = computed(() => {
            if (!playerInfo.value) return '';
            
            return gameMode.value === 'team' 
                ? playerInfo.value.teamName 
                : playerInfo.value.name;
        });
        
        // Get grade based on score
        const getScoreGrade = (score) => {
            if (score >= 300) return 'A+';
            if (score >= 250) return 'A';
            if (score >= 200) return 'B+';
            if (score >= 150) return 'B';
            if (score >= 100) return 'C+';
            if (score >= 50) return 'C';
            return 'D';
        };
        
        // Handle case when user directly navigates to results page without playing
        onMounted(() => {
            if (!isGameFinished.value && !loading.value) {
                // Redirect to game mode selection if no completed game
                router.push('/game/mode');
            }
        });
        
        return {
            loading,
            error,
            isGameFinished,
            gameMode,
            playerInfo,
            playerName,
            score,
            totalQuestions,
            correctAnswers,
            accuracy,
            rank,
            isAuthenticated,
            getScoreGrade
        };
    }
}
</script> 