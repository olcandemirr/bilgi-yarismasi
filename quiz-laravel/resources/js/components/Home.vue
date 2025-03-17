<template>
    <div class="max-w-5xl mx-auto">
        <div class="mb-16 text-center">
            <h1 class="text-4xl lg:text-5xl font-bold mb-4 text-blue-400">Sınav Ustası</h1>
            <p class="text-xl text-gray-300">Bilgini test et, arkadaşlarınla rekabet et!</p>
        </div>
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-12">
            <!-- Solo Oyna -->
            <div class="bg-gray-800 rounded-xl p-6 shadow-lg transition-transform hover:scale-105">
                <h2 class="text-2xl font-bold mb-3 text-center">Solo Oyna</h2>
                <p class="mb-6 text-gray-300 text-center">
                    Bilginizi test edin, arkadaşlarınıza meydan okuyun ve farklı kategorilerdeki sorularımızla liderlik tablosunda yükselin!
                </p>
                <div class="flex justify-center">
                    <router-link 
                        to="/game/mode" 
                        class="px-5 py-2 bg-blue-600 rounded-lg inline-block font-medium hover:bg-blue-700 transition"
                    >
                        Oynamaya Başla
                    </router-link>
                </div>
            </div>
            
            <!-- Rekabet Et -->
            <div class="bg-gray-800 rounded-xl p-6 shadow-lg transition-transform hover:scale-105">
                <h2 class="text-2xl font-bold mb-3 text-center">Rekabet etmek</h2>
                <p class="mb-6 text-gray-300 text-center">
                    Arkadaşlarınıza veya hiç tanımadığınız kişilere meydan okuyun ve liderlik tablosunda kimin en üst sırada yer aldığını görün.
                </p>
                <div class="flex justify-center">
                    <router-link 
                        to="/leaderboard" 
                        class="px-5 py-2 bg-blue-600 rounded-lg inline-block font-medium hover:bg-blue-700 transition"
                    >
                        Liderlik Tablosunu Görüntüle
                    </router-link>
                </div>
            </div>
            
            <!-- Hesap Oluştur -->
            <div v-if="!isAuthenticated" class="bg-gray-800 rounded-xl p-6 shadow-lg transition-transform hover:scale-105">
                <h2 class="text-2xl font-bold mb-3 text-center">Bir hesap oluşturun</h2>
                <p class="mb-6 text-gray-300 text-center">
                    Puanlarınızı takip etmek, arkadaşlarınıza meydan okumak ve sınav deneyiminizi kişiselleştirmek için kaydolun.
                </p>
                <div class="flex justify-center gap-4">
                    <router-link 
                        to="/register" 
                        class="px-5 py-2 bg-blue-600 rounded-lg inline-block font-medium hover:bg-blue-700 transition"
                    >
                        Kayıt Ol
                    </router-link>
                    <router-link 
                        to="/login" 
                        class="px-5 py-2 border border-blue-500 rounded-lg inline-block font-medium hover:bg-blue-800 transition"
                    >
                        Giriş Yap
                    </router-link>
                </div>
            </div>
            
            <!-- Arkadaşlar ve Oyun Davetleri (Sadece giriş yapmış kullanıcılar için) -->
            <div v-else class="bg-gray-800 rounded-xl p-6 shadow-lg transition-transform hover:scale-105">
                <h2 class="text-2xl font-bold mb-3 text-center">Arkadaşlar & Davetler</h2>
                <p class="mb-6 text-gray-300 text-center">
                    Arkadaşlarını yönet, oyun davetlerini görüntüle ve meydan okumaları başlat.
                </p>
                <div class="flex justify-center">
                    <router-link 
                        to="/friends" 
                        class="px-5 py-2 bg-blue-600 rounded-lg inline-block font-medium hover:bg-blue-700 transition"
                    >
                        <span class="relative">
                            Arkadaşları Yönet
                            <span v-if="hasFriendRequests || hasGameInvites" class="absolute -top-3 -right-3 bg-red-500 rounded-full w-5 h-5 flex items-center justify-center text-xs">
                                {{ friendRequestsCount + gameInvitesCount }}
                            </span>
                        </span>
                    </router-link>
                </div>
            </div>
        </div>
        
        <!-- Kategoriler Kısmı -->
        <div class="bg-gray-800 rounded-xl p-6 shadow-lg mb-12">
            <h2 class="text-2xl font-bold mb-5 text-center">Çeşitli kategorilerdeki kapsamlı soru koleksiyonumuzla kendinize meydan okuyun.</h2>
            
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                <div class="bg-gray-700 p-4 rounded-lg text-center">
                    <div class="w-12 h-12 bg-blue-600 mx-auto rounded-full flex items-center justify-center mb-2">
                        <span class="text-xl">🔬</span>
                    </div>
                    <span>Bilim</span>
                </div>
                <div class="bg-gray-700 p-4 rounded-lg text-center">
                    <div class="w-12 h-12 bg-blue-600 mx-auto rounded-full flex items-center justify-center mb-2">
                        <span class="text-xl">📜</span>
                    </div>
                    <span>Tarih</span>
                </div>
                <div class="bg-gray-700 p-4 rounded-lg text-center">
                    <div class="w-12 h-12 bg-blue-600 mx-auto rounded-full flex items-center justify-center mb-2">
                        <span class="text-xl">🌍</span>
                    </div>
                    <span>Coğrafya</span>
                </div>
                <div class="bg-gray-700 p-4 rounded-lg text-center">
                    <div class="w-12 h-12 bg-blue-600 mx-auto rounded-full flex items-center justify-center mb-2">
                        <span class="text-xl">⚽</span>
                    </div>
                    <span>Spor</span>
                </div>
                <div class="bg-gray-700 p-4 rounded-lg text-center">
                    <div class="w-12 h-12 bg-blue-600 mx-auto rounded-full flex items-center justify-center mb-2">
                        <span class="text-xl">🎵</span>
                    </div>
                    <span>Müzik</span>
                </div>
                <div class="bg-gray-700 p-4 rounded-lg text-center">
                    <div class="w-12 h-12 bg-blue-600 mx-auto rounded-full flex items-center justify-center mb-2">
                        <span class="text-xl">🎬</span>
                    </div>
                    <span>Film</span>
                </div>
                <div class="bg-gray-700 p-4 rounded-lg text-center">
                    <div class="w-12 h-12 bg-blue-600 mx-auto rounded-full flex items-center justify-center mb-2">
                        <span class="text-xl">💻</span>
                    </div>
                    <span>Teknoloji</span>
                </div>
                <div class="bg-gray-700 p-4 rounded-lg text-center">
                    <div class="w-12 h-12 bg-blue-600 mx-auto rounded-full flex items-center justify-center mb-2">
                        <span class="text-xl">🎨</span>
                    </div>
                    <span>Sanat</span>
                </div>
            </div>
            
            <div class="text-center mt-8">
                <router-link 
                    to="/game/categories" 
                    class="px-5 py-2 bg-blue-600 rounded-lg inline-block font-medium hover:bg-blue-700 transition"
                >
                    Tüm Kategorileri Gör
                </router-link>
            </div>
        </div>
        
        <!-- Nasıl Oynanır -->
        <div class="bg-gray-800 rounded-xl p-6 shadow-lg mb-12">
            <h2 class="text-2xl font-bold mb-5 text-center">Nasıl Oynanır</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="flex flex-col items-center">
                    <div class="w-16 h-16 bg-blue-600 rounded-full flex items-center justify-center mb-4 text-2xl">1</div>
                    <h3 class="text-lg font-semibold mb-2">Oyun Modunu Seç</h3>
                    <p class="text-center text-gray-300">Solo oyna veya takım halinde rekabet et</p>
                </div>
                
                <div class="flex flex-col items-center">
                    <div class="w-16 h-16 bg-blue-600 rounded-full flex items-center justify-center mb-4 text-2xl">2</div>
                    <h3 class="text-lg font-semibold mb-2">Kategorileri Seç</h3>
                    <p class="text-center text-gray-300">Tercih ettiğin konularda sorular al</p>
                </div>
                
                <div class="flex flex-col items-center">
                    <div class="w-16 h-16 bg-blue-600 rounded-full flex items-center justify-center mb-4 text-2xl">3</div>
                    <h3 class="text-lg font-semibold mb-2">Soruları Yanıtla</h3>
                    <p class="text-center text-gray-300">Ne kadar hızlı olursan, o kadar çok puan kazanırsın!</p>
                </div>
            </div>
        </div>
        
        <!-- Başlangıç CTA -->
        <div class="text-center mb-12">
            <router-link 
                to="/game/mode" 
                class="px-6 py-3 bg-blue-600 rounded-lg inline-block text-lg font-medium hover:bg-blue-700 transition"
            >
                Oynamaya Başla
            </router-link>
        </div>
    </div>
</template>

<script>
import { computed } from 'vue';
import { useStore } from 'vuex';

export default {
    setup() {
        const store = useStore();
        
        // Auth state
        const isAuthenticated = computed(() => store.getters['auth/isAuthenticated']);
        
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
        
        return {
            isAuthenticated,
            hasFriendRequests,
            hasGameInvites,
            friendRequestsCount,
            gameInvitesCount
        };
    }
}
</script> 