<template>
    <div class="app-container">
        <header class="app-header">
            <nav class="main-nav">
                <div class="logo">
                    <router-link to="/">Bilgi Yarışması</router-link>
                </div>
                <div class="nav-links">
                    <template v-if="isAuthenticated">
                        <router-link to="/dashboard" class="nav-link">Dashboard</router-link>
                        <div class="profile-dropdown">
                            <div class="profile-icon" @click="toggleProfileMenu">
                                <div class="avatar-placeholder">{{ userInitials }}</div>
                            </div>
                            <div class="dropdown-menu" v-if="showProfileMenu">
                                <router-link to="/profile" class="dropdown-item">
                                    <i class="fas fa-user"></i> Profil
                                </router-link>
                                <a href="#" @click.prevent="logout" class="dropdown-item">
                                    <i class="fas fa-sign-out-alt"></i> Çıkış Yap
                                </a>
                            </div>
                        </div>
                    </template>
                    <template v-else>
                        <router-link to="/login" class="nav-link">Giriş Yap</router-link>
                        <router-link to="/register" class="nav-link">Kayıt Ol</router-link>
                    </template>
                </div>
            </nav>
        </header>
        
        <main class="app-content">
            <router-view></router-view>
        </main>
        
        <footer class="app-footer">
            <p>&copy; 2025 Bilgi Yarışması. Tüm hakları saklıdır.</p>
        </footer>
    </div>
</template>

<script>
export default {
    data() {
        return {
            isAuthenticated: false,
            user: null,
            showProfileMenu: false
        };
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
    created() {
        // Sayfa yüklendiğinde kimlik doğrulama durumunu kontrol et
        this.checkAuth();
        
        // LocalStorage değişikliklerini dinle
        window.addEventListener('storage', this.checkAuth);
        
        // Tıklama olaylarını dinle (dropdown menüyü kapatmak için)
        document.addEventListener('click', this.handleOutsideClick);
    },
    beforeUnmount() {
        // Event listener'ları temizle
        window.removeEventListener('storage', this.checkAuth);
        document.removeEventListener('click', this.handleOutsideClick);
    },
    methods: {
        checkAuth() {
            // Token varsa kullanıcı giriş yapmış demektir
            const token = localStorage.getItem('token');
            this.isAuthenticated = !!token;
            
            if (this.isAuthenticated) {
                const storedUser = localStorage.getItem('user');
                if (storedUser) {
                    this.user = JSON.parse(storedUser);
                }
            } else {
                this.user = null;
            }
        },
        toggleProfileMenu(event) {
            // Tıklama olayının dışarıya yayılmasını engelle
            event.stopPropagation();
            this.showProfileMenu = !this.showProfileMenu;
        },
        handleOutsideClick(event) {
            // Profil menüsü dışında bir yere tıklandığında menüyü kapat
            const dropdown = document.querySelector('.profile-dropdown');
            if (dropdown && !dropdown.contains(event.target)) {
                this.showProfileMenu = false;
            }
        },
        logout() {
            const token = localStorage.getItem('token');
            
            if (token) {
                fetch('/api/logout', {
                    method: 'POST',
                    headers: {
                        'Authorization': `Bearer ${token}`,
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                }).catch(error => {
                    console.error('Logout error:', error);
                }).finally(() => {
                    // Token ve kullanıcı bilgilerini temizle
                    localStorage.removeItem('token');
                    localStorage.removeItem('user');
                    
                    // Kimlik doğrulama durumunu güncelle
                    this.isAuthenticated = false;
                    this.user = null;
                    
                    // Ana sayfaya yönlendir
                    this.$router.push('/');
                });
            }
        }
    }
};
</script>

<style>
body {
    margin: 0;
    padding: 0;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background: linear-gradient(135deg, #86e3ff 0%, #e5ccff 100%);
    min-height: 100vh;
}

.app-container {
    display: flex;
    flex-direction: column;
    min-height: 100vh;
}

.app-header {
    background-color: rgba(255, 255, 255, 0.9);
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    padding: 15px 30px;
}

.main-nav {
    display: flex;
    justify-content: space-between;
    align-items: center;
    max-width: 1200px;
    margin: 0 auto;
}

.logo a {
    font-size: 1.5rem;
    font-weight: bold;
    color: #333;
    text-decoration: none;
    background: linear-gradient(to right, #6c63ff, #ff4762);
    -webkit-background-clip: text;
    background-clip: text;
    color: transparent;
}

.nav-links {
    display: flex;
    gap: 20px;
    align-items: center;
}

.nav-link {
    color: #444;
    text-decoration: none;
    font-weight: 500;
    padding: 5px 10px;
    border-radius: 4px;
    transition: all 0.3s ease;
}

.nav-link:hover {
    background-color: rgba(108, 99, 255, 0.1);
    color: #6c63ff;
}

.profile-dropdown {
    position: relative;
}

.profile-icon {
    cursor: pointer;
    transition: transform 0.3s ease;
}

.profile-icon:hover {
    transform: translateY(-2px);
}

.avatar-placeholder {
    width: 40px;
    height: 40px;
    background: linear-gradient(to right, #6c63ff, #ff4762);
    border-radius: 50%;
    display: flex;
    justify-content: center;
    align-items: center;
    color: white;
    font-weight: bold;
    font-size: 1rem;
    box-shadow: 0 2px 8px rgba(108, 99, 255, 0.3);
}

.dropdown-menu {
    position: absolute;
    top: 50px;
    right: 0;
    background-color: white;
    border-radius: 8px;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    width: 180px;
    z-index: 100;
    overflow: hidden;
    animation: fadeIn 0.2s ease;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(-10px); }
    to { opacity: 1; transform: translateY(0); }
}

.dropdown-item {
    display: flex;
    align-items: center;
    padding: 12px 15px;
    color: #444;
    text-decoration: none;
    transition: all 0.2s ease;
}

.dropdown-item i {
    margin-right: 10px;
    color: #6c63ff;
}

.dropdown-item:hover {
    background-color: #f5f5f7;
    color: #6c63ff;
}

.app-content {
    flex: 1;
    padding: 20px;
    max-width: 1200px;
    margin: 0 auto;
    width: 100%;
}

.app-footer {
    background-color: rgba(255, 255, 255, 0.9);
    padding: 15px 30px;
    text-align: center;
    color: #666;
    font-size: 0.9rem;
    margin-top: auto;
}

@media (max-width: 768px) {
    .main-nav {
        flex-direction: column;
        gap: 15px;
    }
    
    .nav-links {
        width: 100%;
        justify-content: center;
    }
    
    .dropdown-menu {
        right: -70px;
    }
}
</style> 