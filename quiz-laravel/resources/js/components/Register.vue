<template>
    <div class="register-container">
        <div class="register-card">
            <h2>Kayıt Ol</h2>
            <div v-if="error" class="error-message">{{ error }}</div>
            <form @submit.prevent="register">
                <div class="form-group">
                    <label for="name">Ad Soyad</label>
                    <input 
                        type="text" 
                        id="name" 
                        v-model="form.name" 
                        required
                        placeholder="Adınız ve soyadınız"
                    >
                    <div v-if="errors.name" class="field-error">{{ errors.name[0] }}</div>
                </div>
                <div class="form-group">
                    <label for="email">E-posta</label>
                    <input 
                        type="email" 
                        id="email" 
                        v-model="form.email" 
                        required
                        placeholder="E-posta adresiniz"
                    >
                    <div v-if="errors.email" class="field-error">{{ errors.email[0] }}</div>
                </div>
                <div class="form-group">
                    <label for="password">Şifre</label>
                    <input 
                        type="password" 
                        id="password" 
                        v-model="form.password" 
                        required
                        placeholder="Şifreniz (en az 6 karakter)"
                    >
                    <div v-if="errors.password" class="field-error">{{ errors.password[0] }}</div>
                </div>
                <div class="form-group">
                    <label for="password_confirmation">Şifre Tekrarı</label>
                    <input 
                        type="password" 
                        id="password_confirmation" 
                        v-model="form.password_confirmation" 
                        required
                        placeholder="Şifrenizi tekrar girin"
                    >
                </div>
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary" :disabled="loading">
                        {{ loading ? 'Kayıt yapılıyor...' : 'Kayıt Ol' }}
                    </button>
                </div>
                <div class="form-footer">
                    <p>Zaten hesabınız var mı? <router-link to="/login">Giriş Yap</router-link></p>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            form: {
                name: '',
                email: '',
                password: '',
                password_confirmation: ''
            },
            error: null,
            errors: {},
            loading: false
        }
    },
    methods: {
        async register() {
            this.loading = true;
            this.error = null;
            this.errors = {};
            
            try {
                const response = await fetch('/api/register', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify(this.form)
                });
                
                const data = await response.json();
                
                if (!response.ok) {
                    if (response.status === 422) {
                        this.errors = data;
                    } else if (data.message) {
                        this.error = data.message;
                    } else {
                        this.error = 'Kayıt olurken bir hata oluştu.';
                    }
                    return;
                }
                
                // Kullanıcı bilgilerini ve token'ı sakla
                localStorage.setItem('token', data.access_token);
                localStorage.setItem('user', JSON.stringify(data.user));
                
                // Dashboard'a yönlendir
                this.$router.push('/dashboard');
                
            } catch (error) {
                console.error('Register error:', error);
                this.error = 'Bir hata oluştu. Lütfen tekrar deneyin.';
            } finally {
                this.loading = false;
            }
        }
    }
}
</script>

<style scoped>
.register-container {
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
    padding: 20px;
    background: linear-gradient(135deg, #86e3ff 0%, #e5ccff 100%);
}

.register-card {
    width: 100%;
    max-width: 400px;
    background-color: white;
    border-radius: 10px;
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

.form-group {
    margin-bottom: 20px;
}

label {
    display: block;
    margin-bottom: 8px;
    font-weight: 600;
    color: #444;
}

input {
    width: 100%;
    padding: 12px;
    border: 1px solid #ddd;
    border-radius: 6px;
    font-size: 1rem;
    transition: border-color 0.3s;
}

input:focus {
    border-color: #6c63ff;
    outline: none;
    box-shadow: 0 0 0 2px rgba(108, 99, 255, 0.2);
}

.form-actions {
    margin-top: 30px;
}

.btn {
    display: block;
    width: 100%;
    padding: 12px;
    background: linear-gradient(to right, #6c63ff, #ff4762);
    color: white;
    border: none;
    border-radius: 6px;
    font-size: 1rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s;
}

.btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(108, 99, 255, 0.3);
}

.btn:disabled {
    opacity: 0.7;
    cursor: not-allowed;
    transform: none;
}

.error-message {
    background-color: #ffebee;
    color: #f44336;
    padding: 10px;
    border-radius: 6px;
    margin-bottom: 20px;
    font-size: 0.9rem;
}

.field-error {
    color: #f44336;
    font-size: 0.8rem;
    margin-top: 5px;
}

.form-footer {
    margin-top: 20px;
    text-align: center;
    font-size: 0.9rem;
    color: #666;
}

.form-footer a {
    color: #6c63ff;
    text-decoration: none;
    font-weight: 600;
}

.form-footer a:hover {
    text-decoration: underline;
}
</style> 