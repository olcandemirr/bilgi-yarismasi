<template>
    <div class="profile-container">
        <div class="profile-card">
            <h2>Profil Bilgileri</h2>
            <div v-if="error" class="error-message">{{ error }}</div>
            <div v-if="success" class="success-message">{{ success }}</div>
            
            <div v-if="loading" class="loading">Yükleniyor...</div>
            
            <div v-if="user" class="user-info">
                <div class="avatar">
                    <div class="avatar-placeholder">{{ userInitials }}</div>
                </div>
                
                <div v-if="!editMode" class="user-details">
                    <h3>{{ user.name }}</h3>
                    <p>{{ user.email }}</p>
                    
                    <div class="stats">
                        <div class="stat-item">
                            <span class="stat-value">{{ userStats.totalGames || 0 }}</span>
                            <span class="stat-label">Toplam Oyun</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-value">{{ userStats.highScore || 0 }}</span>
                            <span class="stat-label">En Yüksek Skor</span>
                        </div>
                    </div>
                    
                    <div class="actions">
                        <button @click="toggleEditMode" class="btn btn-primary">Profili Düzenle</button>
                        <button @click="logout" class="btn btn-danger">Çıkış Yap</button>
                    </div>
                </div>
                
                <div v-else class="edit-form">
                    <form @submit.prevent="updateProfile">
                        <div class="form-group">
                            <label for="name">Ad Soyad</label>
                            <input type="text" id="name" v-model="editForm.name" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="email">E-posta</label>
                            <input type="email" id="email" v-model="editForm.email" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="password">Yeni Şifre (Değiştirmek istemiyorsanız boş bırakın)</label>
                            <input type="password" id="password" v-model="editForm.password">
                        </div>
                        
                        <div class="form-group">
                            <label for="password_confirmation">Şifre Tekrar</label>
                            <input type="password" id="password_confirmation" v-model="editForm.password_confirmation">
                        </div>
                        
                        <div class="form-actions">
                            <button type="submit" class="btn btn-success">Kaydet</button>
                            <button type="button" @click="toggleEditMode" class="btn btn-secondary">İptal</button>
                        </div>
                    </form>
                </div>
            </div>
            
            <div class="game-history" v-if="user && !editMode">
                <h3>Oyun Geçmişi</h3>
                <div v-if="gameHistory.length === 0" class="no-history">
                    Henüz oyun geçmişiniz bulunmuyor.
                </div>
                <div v-else class="history-list">
                    <div v-for="(game, index) in gameHistory" :key="index" class="history-item">
                        <div class="history-date">{{ formatDate(game.created_at) }}</div>
                        <div class="history-details">
                            <div class="history-team">{{ game.team_name }}</div>
                            <div class="history-categories" v-if="game.categories">
                                <span class="category-badge" v-for="(categoryId, idx) in parseCategories(game.categories)" :key="idx">
                                    {{ getCategoryName(categoryId) }}
                                </span>
                            </div>
                            <div class="history-score">{{ game.score }} puan</div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="back-to-dashboard" v-if="user && !editMode">
                <router-link to="/dashboard" class="btn btn-outline">Dashboard'a Dön</router-link>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            user: null,
            userStats: {
                totalGames: 0,
                highScore: 0
            },
            gameHistory: [],
            categories: [],
            error: null,
            success: null,
            loading: true,
            editMode: false,
            editForm: {
                name: '',
                email: '',
                password: '',
                password_confirmation: ''
            }
        }
    },
    computed: {
        userInitials() {
            if (!this.user || !this.user.name) return '?';
            return this.user.name
                .split(' ')
                .map(n => n[0])
                .join('')
                .toUpperCase();
        }
    },
    mounted() {
        this.fetchUserData();
        this.fetchCategories();
    },
    methods: {
        async fetchUserData() {
            this.loading = true;
            this.error = null;
            
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
                
                // Form verilerini kullanıcı bilgileriyle doldur
                this.editForm.name = userData.name;
                this.editForm.email = userData.email;
                
                // Kullanıcı bilgilerini güncelle
                localStorage.setItem('user', JSON.stringify(userData));
                
                // Kullanıcı istatistiklerini al
                await this.fetchUserStats();
                
                // Oyun geçmişini al
                await this.fetchGameHistory();
                
            } catch (error) {
                console.error('Profile error:', error);
                this.error = 'Kullanıcı bilgileri yüklenirken bir hata oluştu.';
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
                
                // Oyun geçmişini de al
                if (stats.game_history) {
                    this.gameHistory = stats.game_history;
                }
                
            } catch (error) {
                console.error('Stats error:', error);
            }
        },
        
        async fetchGameHistory() {
            try {
                const token = localStorage.getItem('token');
                
                // Kullanıcının oyun geçmişini al
                const response = await fetch('/api/results/user/history', {
                    headers: {
                        'Authorization': `Bearer ${token}`,
                        'Accept': 'application/json'
                    }
                });
                
                if (!response.ok) {
                    return;
                }
                
                const history = await response.json();
                this.gameHistory = history || [];
                
            } catch (error) {
                console.error('Game history error:', error);
            }
        },
        
        toggleEditMode() {
            this.editMode = !this.editMode;
            
            if (this.editMode) {
                // Form verilerini kullanıcı bilgileriyle doldur
                this.editForm.name = this.user.name;
                this.editForm.email = this.user.email;
                this.editForm.password = '';
                this.editForm.password_confirmation = '';
            }
        },
        
        async updateProfile() {
            this.error = null;
            this.success = null;
            
            try {
                const token = localStorage.getItem('token');
                
                // Şifre alanları boşsa, formdan çıkar
                const formData = { ...this.editForm };
                if (!formData.password) {
                    delete formData.password;
                    delete formData.password_confirmation;
                }
                
                // Profil bilgilerini güncelle
                const response = await fetch('/api/user/update', {
                    method: 'POST',
                    headers: {
                        'Authorization': `Bearer ${token}`,
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify(formData)
                });
                
                const result = await response.json();
                
                if (!response.ok) {
                    throw new Error(result.message || 'Profil güncellenirken bir hata oluştu.');
                }
                
                // Kullanıcı bilgilerini güncelle
                this.user = result.user;
                localStorage.setItem('user', JSON.stringify(result.user));
                
                this.success = 'Profil bilgileriniz başarıyla güncellendi.';
                this.editMode = false;
                
            } catch (error) {
                console.error('Update profile error:', error);
                this.error = error.message || 'Profil güncellenirken bir hata oluştu.';
            }
        },
        
        async logout() {
            try {
                const token = localStorage.getItem('token');
                
                await fetch('/api/logout', {
                    method: 'POST',
                    headers: {
                        'Authorization': `Bearer ${token}`,
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });
                
                this.handleLogout();
                
            } catch (error) {
                console.error('Logout error:', error);
                // Hata olsa bile çıkış yap
                this.handleLogout();
            }
        },
        
        handleLogout() {
            // Token ve kullanıcı bilgilerini temizle
            localStorage.removeItem('token');
            localStorage.removeItem('user');
            
            // Giriş sayfasına yönlendir
            this.$router.push('/login');
        },
        
        formatDate(dateString) {
            const date = new Date(dateString);
            return date.toLocaleDateString('tr-TR', {
                day: '2-digit',
                month: '2-digit',
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });
        },
        
        async fetchCategories() {
            try {
                const response = await fetch('/api/categories');
                this.categories = await response.json();
            } catch (error) {
                console.error('Failed to fetch categories:', error);
                this.categories = [];
            }
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
        }
    }
}
</script>

<style scoped>
.profile-container {
    display: flex;
    justify-content: center;
    align-items: flex-start;
    min-height: 100vh;
    padding: 40px 20px;
}

.profile-card {
    width: 100%;
    max-width: 700px;
    background-color: white;
    border-radius: 15px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    padding: 30px;
}

h2 {
    text-align: center;
    margin-bottom: 30px;
    color: #333;
    position: relative;
}

h2::after {
    content: '';
    position: absolute;
    bottom: -10px;
    left: 50%;
    transform: translateX(-50%);
    width: 60px;
    height: 3px;
    background: linear-gradient(to right, #6c63ff, #ff4762);
    border-radius: 3px;
}

h3 {
    font-size: 1.5rem;
    margin-bottom: 20px;
    color: #444;
    position: relative;
    display: inline-block;
}

h3::after {
    content: '';
    position: absolute;
    bottom: -8px;
    left: 0;
    width: 40px;
    height: 2px;
    background: linear-gradient(to right, #6c63ff, #ff4762);
    border-radius: 2px;
}

.loading {
    text-align: center;
    padding: 20px;
    color: #666;
}

.error-message, .success-message {
    padding: 12px 15px;
    border-radius: 8px;
    margin-bottom: 20px;
    font-weight: 500;
}

.error-message {
    background-color: #ffebee;
    color: #d32f2f;
    border-left: 4px solid #d32f2f;
}

.success-message {
    background-color: #e8f5e9;
    color: #2e7d32;
    border-left: 4px solid #2e7d32;
}

.user-info {
    display: flex;
    flex-direction: column;
    align-items: center;
}

.avatar {
    margin-bottom: 20px;
}

.avatar-placeholder {
    width: 100px;
    height: 100px;
    background: linear-gradient(to right, #6c63ff, #ff4762);
    border-radius: 50%;
    display: flex;
    justify-content: center;
    align-items: center;
    color: white;
    font-size: 2.2rem;
    font-weight: bold;
    box-shadow: 0 4px 15px rgba(108, 99, 255, 0.3);
}

.user-details {
    text-align: center;
    margin-bottom: 30px;
    width: 100%;
}

.user-details h3 {
    font-size: 1.8rem;
    margin-bottom: 5px;
    color: #333;
}

.user-details p {
    color: #666;
    margin-bottom: 20px;
}

.stats {
    display: flex;
    justify-content: center;
    gap: 30px;
    margin: 25px 0;
}

.stat-item {
    text-align: center;
    background-color: #f8f9fa;
    padding: 15px 25px;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
}

.stat-value {
    display: block;
    font-size: 1.8rem;
    font-weight: bold;
    color: #6c63ff;
    margin-bottom: 5px;
}

.stat-label {
    color: #666;
    font-size: 0.9rem;
}

.actions {
    display: flex;
    justify-content: center;
    gap: 15px;
    margin-top: 20px;
}

.btn {
    padding: 10px 20px;
    border-radius: 50px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    border: none;
    font-size: 1rem;
}

.btn:hover {
    transform: translateY(-3px);
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
}

.btn-primary {
    background: linear-gradient(to right, #6c63ff, #8c63ff);
    color: white;
}

.btn-danger {
    background: linear-gradient(to right, #ff4762, #ff6b47);
    color: white;
}

.btn-success {
    background: linear-gradient(to right, #2ecc71, #27ae60);
    color: white;
}

.btn-secondary {
    background: #f1f1f1;
    color: #333;
}

.btn-outline {
    background: transparent;
    color: #6c63ff;
    border: 2px solid #6c63ff;
}

.btn-outline:hover {
    background-color: #f0f0ff;
}

.edit-form {
    width: 100%;
    max-width: 500px;
    margin: 0 auto;
}

.form-group {
    margin-bottom: 20px;
}

.form-group label {
    display: block;
    margin-bottom: 8px;
    font-weight: 600;
    color: #444;
}

.form-group input {
    width: 100%;
    padding: 12px 15px;
    border: 1px solid #ddd;
    border-radius: 8px;
    font-size: 1rem;
    transition: all 0.3s ease;
}

.form-group input:focus {
    border-color: #6c63ff;
    box-shadow: 0 0 0 2px rgba(108, 99, 255, 0.2);
    outline: none;
}

.form-actions {
    display: flex;
    justify-content: space-between;
    margin-top: 30px;
}

.game-history {
    margin-top: 40px;
    width: 100%;
}

.no-history {
    text-align: center;
    padding: 20px;
    background-color: #f8f9fa;
    border-radius: 10px;
    color: #666;
}

.history-list {
    margin-top: 15px;
}

.history-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 15px;
    border-bottom: 1px solid #eee;
    transition: all 0.3s ease;
}

.history-item:hover {
    background-color: #f8f9fa;
}

.history-date {
    color: #666;
    font-size: 0.9rem;
}

.history-details {
    display: flex;
    align-items: center;
    gap: 20px;
}

.history-team {
    font-weight: 600;
    color: #444;
}

.history-categories {
    display: flex;
    gap: 5px;
}

.category-badge {
    background-color: #f0f0f0;
    padding: 5px 10px;
    border-radius: 5px;
    font-size: 0.8rem;
    color: #666;
}

.history-score {
    font-weight: 700;
    color: #6c63ff;
}

.back-to-dashboard {
    margin-top: 30px;
    text-align: center;
}

@media (max-width: 768px) {
    .stats {
        flex-direction: column;
        gap: 15px;
    }
    
    .actions {
        flex-direction: column;
        gap: 10px;
    }
    
    .btn {
        width: 100%;
    }
    
    .history-item {
        flex-direction: column;
        align-items: flex-start;
        gap: 10px;
    }
    
    .history-details {
        width: 100%;
        justify-content: space-between;
    }
}
</style> 