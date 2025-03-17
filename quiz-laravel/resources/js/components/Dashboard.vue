<template>
    <div class="dashboard-container">
        <div class="welcome-section">
            <h1>Hoş Geldiniz, {{ userName }}</h1>
            <p>Bilgi yarışmasına katılmak için bir kategori seçin veya profilinizi düzenleyin.</p>
        </div>
        
        <div class="dashboard-grid">
            <div class="dashboard-left">
                <div class="game-options">
                    <h2>Oyun Seçenekleri</h2>
                    <div class="options-grid">
                        <div class="option-card" @click="startIndividualGame">
                            <div class="option-icon">
                                <i class="fas fa-user"></i>
                            </div>
                            <h3>Bireysel Oyun</h3>
                            <p>Tek başınıza oynayın</p>
                        </div>
                        <div class="option-card" @click="startTeamGame">
                            <div class="option-icon">
                                <i class="fas fa-users"></i>
                            </div>
                            <h3>Takım Oyunu</h3>
                            <p>Arkadaşlarınızla birlikte oynayın</p>
                        </div>
                        <div class="option-card" @click="showCreateQuizModal = true">
                            <div class="option-icon">
                                <i class="fas fa-plus-circle"></i>
                            </div>
                            <h3>Kendi Yarışmanı Oluştur</h3>
                            <p>Özel sorular ve şablonlar ile kendi yarışmanızı oluşturun</p>
                        </div>
                    </div>
                </div>
                
                <!-- Kendi Yarışmanı Oluştur Modal -->
                <div v-if="showCreateQuizModal" class="modal-overlay" @click.self="showCreateQuizModal = false">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h2>Kendi Yarışmanı Oluştur</h2>
                            <button class="close-button" @click="showCreateQuizModal = false">&times;</button>
                        </div>
                        
                        <div class="modal-body">
                            <div class="create-quiz-steps">
                                <!-- Adım 1: Temel Bilgiler -->
                                <div v-if="createQuizStep === 1" class="quiz-step">
                                    <h3>Adım 1: Temel Bilgiler</h3>
                                    <div class="form-group">
                                        <label for="quiz-name">Yarışma Adı:</label>
                                        <input type="text" id="quiz-name" v-model="customQuiz.name" placeholder="Yarışmanızın adını girin" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="quiz-description">Açıklama:</label>
                                        <textarea id="quiz-description" v-model="customQuiz.description" placeholder="Yarışmanız hakkında kısa bir açıklama" rows="3"></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="quiz-question-count">Soru Sayısı:</label>
                                        <input type="number" id="quiz-question-count" v-model="customQuiz.questionCount" min="5" max="50" required>
                                    </div>
                                    <div class="form-actions">
                                        <button class="btn next-btn" @click="nextStep" :disabled="!isStep1Valid">İleri</button>
                                    </div>
                                </div>
                                
                                <!-- Adım 2: Kategori Seçimi -->
                                <div v-if="createQuizStep === 2" class="quiz-step">
                                    <h3>Adım 2: Kategori Seçimi</h3>
                                    <p>Yarışmanızda hangi kategorilerden sorular olsun?</p>
                                    
                                    <div class="categories-selection">
                                        <div 
                                            v-for="category in categories" 
                                            :key="category.id" 
                                            class="category-select-item"
                                            :class="{ selected: customQuiz.selectedCategories.includes(category.id) }"
                                            @click="toggleQuizCategory(category.id)"
                                        >
                                            <div class="category-checkbox">
                                                <i class="fas fa-check" v-if="customQuiz.selectedCategories.includes(category.id)"></i>
                                            </div>
                                            <div class="category-info">
                                                <h4>{{ category.name }}</h4>
                                                <p>{{ category.description }}</p>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label>
                                            <input type="checkbox" v-model="customQuiz.useCustomTemplate">
                                            Özel şablon kullan
                                        </label>
                                    </div>
                                    
                                    <div class="form-actions">
                                        <button class="btn back-btn" @click="prevStep">Geri</button>
                                        <button class="btn next-btn" @click="nextStep" :disabled="!isStep2Valid">İleri</button>
                                    </div>
                                </div>
                                
                                <!-- Adım 3: Şablon Yükleme (Eğer özel şablon seçildiyse) -->
                                <div v-if="createQuizStep === 3 && customQuiz.useCustomTemplate" class="quiz-step">
                                    <h3>Adım 3: Şablon Yükleme</h3>
                                    <p>Yarışmanız için özel bir şablon yükleyin.</p>
                                    
                                    <div class="template-upload">
                                        <div class="upload-area" @click="triggerFileInput" :class="{ 'has-file': customQuiz.templateFile }">
                                            <i class="fas fa-cloud-upload-alt"></i>
                                            <p v-if="!customQuiz.templateFile">Şablon dosyasını yüklemek için tıklayın veya sürükleyin</p>
                                            <p v-else>{{ customQuiz.templateFile.name }}</p>
                                            <input 
                                                type="file" 
                                                ref="fileInput" 
                                                @change="handleFileUpload" 
                                                accept=".json,.csv,.xlsx"
                                                style="display: none"
                                            >
                                        </div>
                                        <p class="file-info">Desteklenen formatlar: JSON, CSV, Excel</p>
                                    </div>
                                    
                                    <div class="form-actions">
                                        <button class="btn back-btn" @click="prevStep">Geri</button>
                                        <button class="btn next-btn" @click="nextStep" :disabled="!isStep3Valid">İleri</button>
                                    </div>
                                </div>
                                
                                <!-- Adım 4: Önizleme ve Oluşturma -->
                                <div v-if="createQuizStep === (customQuiz.useCustomTemplate ? 4 : 3)" class="quiz-step">
                                    <h3>Son Adım: Önizleme ve Oluşturma</h3>
                                    
                                    <div class="quiz-preview">
                                        <h4>{{ customQuiz.name }}</h4>
                                        <p>{{ customQuiz.description }}</p>
                                        
                                        <div class="preview-details">
                                            <div class="preview-item">
                                                <strong>Soru Sayısı:</strong> {{ customQuiz.questionCount }}
                                            </div>
                                            <div class="preview-item">
                                                <strong>Kategoriler:</strong> 
                                                <span v-if="customQuiz.selectedCategories.length === 0">Tüm kategoriler</span>
                                                <span v-else>
                                                    <span 
                                                        v-for="(categoryId, index) in customQuiz.selectedCategories" 
                                                        :key="categoryId"
                                                    >
                                                        {{ getCategoryName(categoryId) }}{{ index < customQuiz.selectedCategories.length - 1 ? ', ' : '' }}
                                                    </span>
                                                </span>
                                            </div>
                                            <div class="preview-item" v-if="customQuiz.useCustomTemplate">
                                                <strong>Şablon:</strong> {{ customQuiz.templateFile ? customQuiz.templateFile.name : 'Yüklenmedi' }}
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="output-options">
                                        <h4>Çıktı Seçenekleri</h4>
                                        <div class="output-options-grid">
                                            <div class="output-option">
                                                <input type="radio" id="output-web" value="web" v-model="customQuiz.outputType" name="output-type">
                                                <label for="output-web">Web Uygulaması</label>
                                            </div>
                                            <div class="output-option">
                                                <input type="radio" id="output-presentation" value="presentation" v-model="customQuiz.outputType" name="output-type">
                                                <label for="output-presentation">Sunum (PPTX)</label>
                                            </div>
                                            <div class="output-option">
                                                <input type="radio" id="output-pdf" value="pdf" v-model="customQuiz.outputType" name="output-type">
                                                <label for="output-pdf">PDF Dosyası</label>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="form-actions">
                                        <button class="btn back-btn" @click="prevStep">Geri</button>
                                        <button class="btn create-btn" @click="createCustomQuiz" :disabled="isCreatingQuiz">
                                            <i class="fas fa-spinner fa-spin" v-if="isCreatingQuiz"></i>
                                            <span v-else>Yarışmayı Oluştur</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="dashboard-right">
                <div class="leaderboard-section">
                    <h2>Liderlik Tablosu</h2>
                    <div v-if="loading" class="loading">Yükleniyor...</div>
                    <div v-else>
                        <div class="leaderboard-tabs">
                            <button 
                                @click="activeTab = 'all'" 
                                :class="{ active: activeTab === 'all' }"
                                class="tab-button"
                            >
                                Genel Sıralama
                            </button>
                            <button 
                                @click="activeTab = 'friends'" 
                                :class="{ active: activeTab === 'friends' }"
                                class="tab-button"
                            >
                                Arkadaşlar
                            </button>
                        </div>
                        
                        <div class="leaderboard-table">
                            <div class="leaderboard-header">
                                <div class="rank">#</div>
                                <div class="player">Oyuncu</div>
                                <div class="score">Puan</div>
                                <div class="categories">Kategoriler</div>
                                <div class="date">Tarih</div>
                            </div>
                            
                            <div v-if="activeTab === 'all' && topScores.length === 0" class="no-data">
                                Henüz hiç skor kaydedilmemiş.
                            </div>
                            
                            <div v-else-if="activeTab === 'friends' && friendScores.length === 0" class="no-data">
                                Arkadaş listenizde henüz kimse yok veya hiç skor kaydedilmemiş.
                            </div>
                            
                            <div 
                                v-for="(score, index) in activeTab === 'all' ? topScores : friendScores" 
                                :key="score.id" 
                                class="leaderboard-row"
                                :class="{ 'current-user': score.user_id === (user ? user.id : null) }"
                            >
                                <div class="rank">{{ index + 1 }}</div>
                                <div class="player">
                                    <div class="player-avatar">
                                        {{ getInitials(score.user ? score.user.name : score.team_name) }}
                                    </div>
                                    <div class="player-name">{{ score.user ? score.user.name : score.team_name }}</div>
                                </div>
                                <div class="score">{{ score.score }}</div>
                                <div class="categories">
                                    <div class="category-badges" v-if="score.categories">
                                        <span 
                                            v-for="(categoryId, idx) in parseCategories(score.categories)" 
                                            :key="idx" 
                                            class="category-badge"
                                            v-tooltip="getCategoryName(categoryId)"
                                        >
                                            {{ getCategoryShortName(categoryId) }}
                                        </span>
                                    </div>
                                    <span v-else class="no-categories">-</span>
                                </div>
                                <div class="date">{{ formatDate(score.created_at) }}</div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="stats-section">
                    <h2>İstatistikleriniz</h2>
                    <div class="stats-grid">
                        <div class="stat-card">
                            <div class="stat-value">{{ userStats.totalGames || 0 }}</div>
                            <div class="stat-label">Toplam Oyun</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-value">{{ userStats.highScore || 0 }}</div>
                            <div class="stat-label">En Yüksek Skor</div>
                        </div>
                    </div>
                </div>
                
                <div class="recent-games-section" v-if="recentGames.length > 0">
                    <h2>Son Oyunlarınız</h2>
                    <div class="recent-games-list">
                        <div v-for="(game, index) in recentGames" :key="index" class="recent-game-item">
                            <div class="game-info">
                                <div class="game-date">{{ formatDate(game.created_at) }}</div>
                                <div class="game-team">{{ game.team_name }}</div>
                            </div>
                            <div class="game-score">{{ game.score }} puan</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            categories: [],
            user: null,
            userStats: {
                totalGames: 0,
                highScore: 0
            },
            topScores: [],
            friendScores: [],
            recentGames: [],
            loading: true,
            activeTab: 'all',
            // Kendi Yarışmanı Oluştur için yeni değişkenler
            showCreateQuizModal: false,
            createQuizStep: 1,
            isCreatingQuiz: false,
            customQuiz: {
                name: '',
                description: '',
                questionCount: 25,
                selectedCategories: [],
                useCustomTemplate: false,
                templateFile: null,
                outputType: 'web'
            }
        }
    },
    computed: {
        userName() {
            if (!this.user || !this.user.name) return 'Oyuncu';
            return this.user.name.split(' ')[0]; // İlk adı göster
        },
        isStep1Valid() {
            return this.customQuiz.name.trim() !== '' && 
                   this.customQuiz.questionCount >= 5 && 
                   this.customQuiz.questionCount <= 50;
        },
        isStep2Valid() {
            return true; // Kategori seçimi opsiyonel olabilir
        },
        isStep3Valid() {
            if (!this.customQuiz.useCustomTemplate) return true;
            return this.customQuiz.templateFile !== null;
        }
    },
    mounted() {
        this.fetchUserData();
        this.fetchCategories();
        this.fetchTopScores();
    },
    methods: {
        async fetchUserData() {
            try {
                // Önce localStorage'dan kullanıcı bilgilerini al
                const storedUser = localStorage.getItem('user');
                if (storedUser) {
                    this.user = JSON.parse(storedUser);
                }
                
                // Token kontrolü
                const token = localStorage.getItem('token');
                if (!token) {
                    this.$router.push('/login');
                    return;
                }
                
                // API'den güncel kullanıcı bilgilerini al
                const response = await fetch('/api/user', {
                    headers: {
                        'Authorization': `Bearer ${token}`,
                        'Accept': 'application/json'
                    }
                });
                
                if (!response.ok) {
                    if (response.status === 401) {
                        // Token geçersiz, çıkış yap
                        this.handleLogout();
                        return;
                    }
                    throw new Error('Kullanıcı bilgileri alınamadı');
                }
                
                const userData = await response.json();
                this.user = userData;
                
                // Kullanıcı bilgilerini güncelle
                localStorage.setItem('user', JSON.stringify(userData));
                
                // Kullanıcı istatistiklerini al
                await this.fetchUserStats();
                
            } catch (error) {
                console.error('Dashboard error:', error);
            } finally {
                this.loading = false;
            }
        },
        
        async fetchUserStats() {
            try {
                const token = localStorage.getItem('token');
                
                // Kullanıcının oyun istatistiklerini al
                const response = await fetch('/api/results/user', {
                    headers: {
                        'Authorization': `Bearer ${token}`,
                        'Accept': 'application/json'
                    }
                });
                
                if (!response.ok) {
                    return;
                }
                
                const stats = await response.json();
                this.userStats = {
                    totalGames: stats.total_games || 0,
                    highScore: stats.high_score || 0
                };
                
                // Son oyunları al
                if (stats.recent_games) {
                    this.recentGames = stats.recent_games;
                }
                
            } catch (error) {
                console.error('Stats error:', error);
            }
        },
        
        async fetchTopScores() {
            try {
                // En yüksek skorları al
                const response = await fetch('/api/top-scores?with_users=true');
                const data = await response.json();
                this.topScores = data;
                
                // Arkadaş skorlarını da al (şimdilik aynı veri, gerçek arkadaş sistemi eklendiğinde değişecek)
                this.friendScores = data.filter(score => score.user_id !== null).slice(0, 5);
                
            } catch (error) {
                console.error('Top scores error:', error);
                this.topScores = [];
                this.friendScores = [];
            }
        },
        
        async fetchCategories() {
            try {
                const response = await fetch('/api/categories');
                this.categories = await response.json();
            } catch (error) {
                console.error('Failed to fetch categories:', error);
                // Fallback categories if API fails
                this.categories = [
                    { id: 1, name: 'Genel Kültür', slug: 'general', description: 'Çeşitli genel kültür soruları' },
                    { id: 2, name: 'Bilim', slug: 'science', description: 'Bilim ve teknoloji ile ilgili sorular' },
                    { id: 3, name: 'Tarih', slug: 'history', description: 'Tarihle ilgili sorular' },
                    { id: 4, name: 'Coğrafya', slug: 'geography', description: 'Dünya coğrafyası ile ilgili sorular' },
                    { id: 5, name: 'Spor', slug: 'sports', description: 'Spor dünyası ile ilgili sorular' },
                    { id: 6, name: 'Sanat', slug: 'art', description: 'Sanat ve kültür ile ilgili sorular' }
                ];
            }
        },
        
        startIndividualGame() {
            // Bireysel oyun modunu başlat
            localStorage.setItem('gameMode', 'individual');
            this.$router.push('/');
        },
        
        startTeamGame() {
            // Takım oyunu modunu başlat
            localStorage.setItem('gameMode', 'team');
            this.$router.push('/');
        },
        
        handleLogout() {
            // Token ve kullanıcı bilgilerini temizle
            localStorage.removeItem('token');
            localStorage.removeItem('user');
            
            // Giriş sayfasına yönlendir
            this.$router.push('/login');
        },
        
        formatDate(dateString) {
            if (!dateString) return '';
            const date = new Date(dateString);
            return date.toLocaleDateString('tr-TR', {
                day: '2-digit',
                month: '2-digit',
                year: 'numeric'
            });
        },
        
        getInitials(name) {
            if (!name) return '?';
            return name
                .split(' ')
                .map(n => n[0])
                .join('')
                .toUpperCase()
                .substring(0, 2);
        },
        
        parseCategories(categoriesJson) {
            if (!categoriesJson) return [];
            try {
                // Eğer zaten bir dizi ise doğrudan döndür
                if (Array.isArray(categoriesJson)) {
                    return categoriesJson;
                }
                
                // String ise JSON olarak parse et
                if (typeof categoriesJson === 'string') {
                    return JSON.parse(categoriesJson);
                }
                
                // Diğer durumlar için boş dizi döndür
                return [];
            } catch (e) {
                console.error('Error parsing categories:', e);
                return [];
            }
        },
        
        getCategoryName(categoryId) {
            const category = this.categories.find(c => c.id === categoryId);
            return category ? category.name : 'Bilinmeyen Kategori';
        },
        
        getCategoryShortName(categoryId) {
            const category = this.categories.find(c => c.id === categoryId);
            return category ? category.name.substring(0, 2) : 'BK';
        },
        
        // Kendi Yarışmanı Oluştur için yeni metodlar
        nextStep() {
            if (this.createQuizStep === 1 && !this.isStep1Valid) return;
            if (this.createQuizStep === 2 && !this.isStep2Valid) return;
            if (this.createQuizStep === 3 && !this.isStep3Valid && this.customQuiz.useCustomTemplate) return;
            
            const maxStep = this.customQuiz.useCustomTemplate ? 4 : 3;
            if (this.createQuizStep < maxStep) {
                this.createQuizStep++;
            }
        },
        
        prevStep() {
            if (this.createQuizStep > 1) {
                this.createQuizStep--;
            }
        },
        
        toggleQuizCategory(categoryId) {
            const index = this.customQuiz.selectedCategories.indexOf(categoryId);
            if (index === -1) {
                this.customQuiz.selectedCategories.push(categoryId);
            } else {
                this.customQuiz.selectedCategories.splice(index, 1);
            }
        },
        
        triggerFileInput() {
            this.$refs.fileInput.click();
        },
        
        handleFileUpload(event) {
            const file = event.target.files[0];
            if (file) {
                // Dosya türü kontrolü
                const validTypes = ['.json', '.csv', '.xlsx', 'application/json', 'text/csv', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'];
                const fileType = file.type || file.name.substring(file.name.lastIndexOf('.'));
                
                if (validTypes.some(type => fileType.includes(type))) {
                    this.customQuiz.templateFile = file;
                } else {
                    alert('Lütfen geçerli bir dosya formatı yükleyin (JSON, CSV veya Excel).');
                    event.target.value = null;
                }
            }
        },
        
        async createCustomQuiz() {
            this.isCreatingQuiz = true;
            
            try {
                // Form verilerini hazırla
                const formData = new FormData();
                formData.append('name', this.customQuiz.name);
                formData.append('description', this.customQuiz.description);
                formData.append('question_count', this.customQuiz.questionCount);
                formData.append('categories', JSON.stringify(this.customQuiz.selectedCategories));
                formData.append('output_type', this.customQuiz.outputType);
                
                if (this.customQuiz.useCustomTemplate && this.customQuiz.templateFile) {
                    formData.append('template_file', this.customQuiz.templateFile);
                }
                
                // Token kontrolü
                const token = localStorage.getItem('token');
                if (!token) {
                    alert('Oturum süreniz dolmuş. Lütfen tekrar giriş yapın.');
                    this.$router.push('/login');
                    return;
                }
                
                // API isteği
                const response = await fetch('/api/custom-quiz', {
                    method: 'POST',
                    headers: {
                        'Authorization': `Bearer ${token}`,
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: formData
                });
                
                if (!response.ok) {
                    throw new Error('Yarışma oluşturulurken bir hata oluştu');
                }
                
                const result = await response.json();
                
                // Çıktı tipine göre işlem yap
                if (this.customQuiz.outputType === 'web') {
                    // Web uygulaması olarak başlat
                    localStorage.setItem('customQuizId', result.id);
                    this.$router.push('/');
                } else {
                    // Dosya indirme işlemi
                    window.location.href = result.download_url;
                }
                
                // Modal'ı kapat ve formu sıfırla
                this.resetCustomQuizForm();
                this.showCreateQuizModal = false;
                
            } catch (error) {
                console.error('Custom quiz creation error:', error);
                alert('Yarışma oluşturulurken bir hata oluştu: ' + error.message);
            } finally {
                this.isCreatingQuiz = false;
            }
        },
        
        resetCustomQuizForm() {
            this.createQuizStep = 1;
            this.customQuiz = {
                name: '',
                description: '',
                questionCount: 25,
                selectedCategories: [],
                useCustomTemplate: false,
                templateFile: null,
                outputType: 'web'
            };
        }
    }
}
</script>

<style scoped>
.dashboard-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 30px 20px;
}

.welcome-section {
    text-align: center;
    margin-bottom: 40px;
    padding: 30px;
    background-color: white;
    border-radius: 15px;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
}

.welcome-section h1 {
    font-size: 2.2rem;
    margin-bottom: 15px;
    background: linear-gradient(to right, #6c63ff, #ff4762);
    -webkit-background-clip: text;
    background-clip: text;
    color: transparent;
}

.welcome-section p {
    font-size: 1.1rem;
    color: #555;
}

.dashboard-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 30px;
}

h2 {
    font-size: 1.8rem;
    margin-bottom: 20px;
    position: relative;
    display: inline-block;
}

h2::after {
    content: '';
    position: absolute;
    bottom: -8px;
    left: 0;
    width: 60px;
    height: 3px;
    background: linear-gradient(to right, #6c63ff, #ff4762);
    border-radius: 3px;
}

.categories-section, .game-options, .stats-section, .leaderboard-section, .recent-games-section {
    margin-bottom: 40px;
    background-color: white;
    border-radius: 15px;
    padding: 30px;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
}

.categories-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 20px;
    margin-top: 20px;
}

.category-card {
    background-color: #f8f9fa;
    border-radius: 10px;
    padding: 20px;
    transition: all 0.3s ease;
    cursor: pointer;
    text-align: center;
    border: 1px solid #eee;
}

.category-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgba(108, 99, 255, 0.1);
    border-color: #6c63ff;
}

.category-icon {
    font-size: 2rem;
    margin-bottom: 15px;
    color: #6c63ff;
}

.category-card h3 {
    font-size: 1.3rem;
    margin-bottom: 10px;
    color: #333;
}

.category-card p {
    color: #666;
    font-size: 0.9rem;
}

.options-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 20px;
    margin-top: 20px;
}

.option-card {
    background: linear-gradient(135deg, #f8f9fa 0%, #f0f0f0 100%);
    border-radius: 10px;
    padding: 25px;
    transition: all 0.3s ease;
    cursor: pointer;
    text-align: center;
    border: 1px solid #eee;
}

.option-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgba(108, 99, 255, 0.1);
    background: linear-gradient(135deg, #f0f0ff 0%, #fff0f5 100%);
    border-color: #6c63ff;
}

.option-icon {
    font-size: 2.5rem;
    margin-bottom: 15px;
    color: #6c63ff;
}

.option-card h3 {
    font-size: 1.5rem;
    margin-bottom: 10px;
    color: #333;
}

.option-card p {
    color: #666;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
    gap: 20px;
    margin-top: 20px;
}

.stat-card {
    background-color: #f8f9fa;
    border-radius: 10px;
    padding: 20px;
    text-align: center;
    border: 1px solid #eee;
}

.stat-value {
    font-size: 2.5rem;
    font-weight: bold;
    margin-bottom: 10px;
    background: linear-gradient(to right, #6c63ff, #ff4762);
    -webkit-background-clip: text;
    background-clip: text;
    color: transparent;
}

.stat-label {
    color: #666;
    font-size: 1rem;
}

.loading {
    text-align: center;
    padding: 20px;
    color: #666;
}

.leaderboard-tabs {
    display: flex;
    margin-bottom: 20px;
    border-bottom: 1px solid #eee;
}

.tab-button {
    padding: 10px 20px;
    background: none;
    border: none;
    font-size: 1rem;
    font-weight: 600;
    color: #666;
    cursor: pointer;
    transition: all 0.3s ease;
    position: relative;
}

.tab-button.active {
    color: #6c63ff;
}

.tab-button.active::after {
    content: '';
    position: absolute;
    bottom: -1px;
    left: 0;
    width: 100%;
    height: 3px;
    background: linear-gradient(to right, #6c63ff, #ff4762);
    border-radius: 3px 3px 0 0;
}

.leaderboard-table {
    margin-top: 20px;
}

.leaderboard-header {
    display: grid;
    grid-template-columns: 50px 2fr 1fr 1.5fr 1fr;
    padding: 10px 15px;
    background-color: #f8f9fa;
    border-radius: 8px 8px 0 0;
    font-weight: 600;
    color: #444;
}

.leaderboard-row {
    display: grid;
    grid-template-columns: 50px 2fr 1fr 1.5fr 1fr;
    padding: 15px;
    border-bottom: 1px solid #eee;
    align-items: center;
    transition: all 0.3s ease;
}

.leaderboard-row:hover {
    background-color: #f8f9fa;
}

.leaderboard-row.current-user {
    background-color: #f0f0ff;
}

.rank {
    font-weight: 700;
    color: #6c63ff;
}

.player {
    display: flex;
    align-items: center;
    gap: 15px;
}

.player-avatar {
    width: 35px;
    height: 35px;
    background: linear-gradient(to right, #6c63ff, #ff4762);
    border-radius: 50%;
    display: flex;
    justify-content: center;
    align-items: center;
    color: white;
    font-weight: bold;
    font-size: 0.8rem;
}

.player-name {
    font-weight: 500;
}

.score {
    font-weight: 700;
    color: #ff4762;
}

.categories {
    font-weight: 700;
    color: #6c63ff;
}

.date {
    color: #666;
    font-size: 0.9rem;
}

.no-data {
    text-align: center;
    padding: 30px;
    color: #666;
    background-color: #f8f9fa;
    border-radius: 8px;
    margin-top: 20px;
}

.recent-games-list {
    margin-top: 20px;
}

.recent-game-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 15px;
    border-bottom: 1px solid #eee;
    transition: all 0.3s ease;
}

.recent-game-item:hover {
    background-color: #f8f9fa;
}

.game-info {
    display: flex;
    flex-direction: column;
    gap: 5px;
}

.game-date {
    color: #666;
    font-size: 0.9rem;
}

.game-team {
    font-weight: 600;
    color: #444;
}

.game-score {
    font-weight: 700;
    color: #6c63ff;
}

.category-badges {
    display: flex;
    flex-wrap: wrap;
    gap: 5px;
}

.category-badge {
    background-color: #f0f0ff;
    padding: 3px 6px;
    border-radius: 4px;
    font-size: 0.7rem;
    color: #6c63ff;
    border: 1px solid #e0e0ff;
}

.no-categories {
    color: #aaa;
    font-style: italic;
}

@media (max-width: 992px) {
    .dashboard-grid {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 768px) {
    .categories-grid, .options-grid, .stats-grid {
        grid-template-columns: 1fr;
    }
    
    .welcome-section h1 {
        font-size: 1.8rem;
    }
    
    .leaderboard-header, .leaderboard-row {
        grid-template-columns: 40px 2fr 1fr 1.5fr 1fr;
        font-size: 0.9rem;
    }
    
    .player-avatar {
        width: 30px;
        height: 30px;
        font-size: 0.7rem;
    }
    
    .category-badge {
        font-size: 0.6rem;
        padding: 2px 4px;
    }
}

@media (max-width: 576px) {
    .leaderboard-header, .leaderboard-row {
        grid-template-columns: 30px 2fr 1fr 0fr 1fr;
    }
    
    .categories {
        display: none;
    }
}

/* Kendi Yarışmanı Oluştur için yeni stiller */
.modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: rgba(0, 0, 0, 0.5);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 1000;
}

.modal-content {
    background-color: white;
    border-radius: 15px;
    width: 90%;
    max-width: 800px;
    max-height: 90vh;
    overflow-y: auto;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
    animation: modalFadeIn 0.3s ease;
}

@keyframes modalFadeIn {
    from { opacity: 0; transform: translateY(-20px); }
    to { opacity: 1; transform: translateY(0); }
}

.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px 30px;
    border-bottom: 1px solid #eee;
}

.modal-header h2 {
    margin: 0;
    font-size: 1.8rem;
}

.modal-header h2::after {
    display: none;
}

.close-button {
    background: none;
    border: none;
    font-size: 1.8rem;
    cursor: pointer;
    color: #666;
    transition: color 0.3s ease;
}

.close-button:hover {
    color: #ff4762;
}

.modal-body {
    padding: 30px;
}

.quiz-step {
    animation: fadeIn 0.3s ease;
}

.quiz-step h3 {
    margin-bottom: 20px;
    font-size: 1.5rem;
    color: #333;
}

.form-actions {
    display: flex;
    justify-content: space-between;
    margin-top: 30px;
}

.back-btn {
    background: #f0f0f0;
    color: #333;
}

.back-btn:hover {
    background: #e0e0e0;
}

.next-btn, .create-btn {
    background: linear-gradient(to right, #6c63ff, #ff4762);
}

.create-btn {
    min-width: 180px;
}

.categories-selection {
    margin: 20px 0;
    max-height: 300px;
    overflow-y: auto;
    border: 1px solid #eee;
    border-radius: 10px;
}

.category-select-item {
    display: flex;
    align-items: center;
    padding: 15px;
    border-bottom: 1px solid #eee;
    cursor: pointer;
    transition: all 0.3s ease;
}

.category-select-item:last-child {
    border-bottom: none;
}

.category-select-item:hover {
    background-color: #f8f9fa;
}

.category-select-item.selected {
    background-color: #f0f0ff;
}

.category-checkbox {
    width: 24px;
    height: 24px;
    border: 2px solid #ddd;
    border-radius: 4px;
    margin-right: 15px;
    display: flex;
    justify-content: center;
    align-items: center;
    color: #6c63ff;
    transition: all 0.3s ease;
}

.category-select-item.selected .category-checkbox {
    border-color: #6c63ff;
    background-color: #6c63ff;
    color: white;
}

.category-info {
    flex: 1;
}

.category-info h4 {
    margin: 0 0 5px 0;
    font-size: 1.1rem;
    color: #333;
}

.category-info p {
    margin: 0;
    font-size: 0.9rem;
    color: #666;
}

.template-upload {
    margin: 20px 0;
}

.upload-area {
    border: 2px dashed #ddd;
    border-radius: 10px;
    padding: 40px 20px;
    text-align: center;
    cursor: pointer;
    transition: all 0.3s ease;
}

.upload-area:hover {
    border-color: #6c63ff;
    background-color: #f8f9fa;
}

.upload-area.has-file {
    border-color: #6c63ff;
    background-color: #f0f0ff;
}

.upload-area i {
    font-size: 3rem;
    color: #6c63ff;
    margin-bottom: 15px;
}

.file-info {
    text-align: center;
    font-size: 0.9rem;
    color: #666;
    margin-top: 10px;
}

.quiz-preview {
    background-color: #f8f9fa;
    border-radius: 10px;
    padding: 20px;
    margin-bottom: 30px;
}

.quiz-preview h4 {
    font-size: 1.3rem;
    margin-bottom: 10px;
    color: #333;
}

.preview-details {
    margin-top: 20px;
}

.preview-item {
    margin-bottom: 10px;
}

.preview-item strong {
    color: #444;
}

.output-options {
    margin-top: 30px;
}

.output-options h4 {
    font-size: 1.2rem;
    margin-bottom: 15px;
    color: #333;
}

.output-options-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 15px;
}

.output-option {
    display: flex;
    align-items: center;
    gap: 10px;
}

.output-option label {
    cursor: pointer;
}

@media (max-width: 768px) {
    .modal-content {
        width: 95%;
        max-height: 95vh;
    }
    
    .modal-header {
        padding: 15px 20px;
    }
    
    .modal-body {
        padding: 20px;
    }
    
    .output-options-grid {
        grid-template-columns: 1fr;
    }
}
</style> 