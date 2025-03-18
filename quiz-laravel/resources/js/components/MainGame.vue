<template>
    <div class="quiz-app">
        <!-- Welcome Screen -->
        <div v-if="currentScreen === 'welcome'" class="screen welcome-screen">
            <h1>Bilgi Yarışması</h1>
            <p>Kahoot tarzı bilgi yarışmasına hoş geldiniz!</p>
            <div class="game-mode-selection">
                <button @click="selectMode('individual')" class="btn">Bireysel Oyun</button>
                <button @click="selectMode('team')" class="btn">Takım Oyunu</button>
            </div>
        </div>

        <!-- Individual Player Form Screen -->
        <div v-if="currentScreen === 'individualForm'" class="screen player-form-screen">
            <h2>Oyuncu Bilgileri</h2>
            <form @submit.prevent="showCategorySelection">
                <div class="form-group">
                    <label for="player-name">Adınız:</label>
                    <input type="text" id="player-name" v-model="playerName" required>
                </div>
                <button type="submit" class="btn">Devam Et</button>
            </form>
        </div>

        <!-- Team Form Screen -->
        <div v-if="currentScreen === 'teamForm'" class="screen team-form-screen">
            <h2>Takım Bilgileri</h2>
            <form @submit.prevent="showTeamMembersForm">
                <div class="form-group">
                    <label for="team-name">Takım İsmi:</label>
                    <input type="text" id="team-name" v-model="teamName" required>
                </div>
                <div class="form-group">
                    <label for="participant-count">Katılımcı Sayısı:</label>
                    <input type="number" id="participant-count" v-model="participantCount" min="1" max="10" required>
                </div>
                <button type="submit" class="btn">Devam Et</button>
            </form>
        </div>

        <!-- Team Members Form Screen -->
        <div v-if="currentScreen === 'teamMembersForm'" class="screen team-members-screen">
            <h2>Takım Üyeleri</h2>
            <form @submit.prevent="showCategorySelection">
                <div v-for="(member, index) in teamMembers" :key="index" class="form-group">
                    <label :for="'member-' + index">Üye {{ index + 1 }}:</label>
                    <input type="text" :id="'member-' + index" v-model="teamMembers[index]" required>
                </div>
                <button type="submit" class="btn">Devam Et</button>
            </form>
        </div>

        <!-- Category Selection Screen -->
        <div v-if="currentScreen === 'categorySelection'" class="screen category-screen">
            <h2>Kategori Seçimi</h2>
            <p>Oynamak istediğiniz kategorileri seçin:</p>
            <div class="categories-container">
                <div 
                    v-for="category in categories" 
                    :key="category.id" 
                    class="category-card"
                    :class="{ selected: selectedCategories.includes(category.id) }"
                    @click="toggleCategory(category.id)"
                >
                    <h3>{{ category.name }}</h3>
                    <p>{{ category.description }}</p>
                </div>
            </div>
            <p class="selection-info">{{ selectedCategories.length }} kategori seçildi</p>
            <button 
                @click="startQuiz" 
                class="btn" 
                :disabled="selectedCategories.length === 0"
            >
                Yarışmaya Başla
            </button>
        </div>

        <!-- Quiz Screen -->
        <div v-if="currentScreen === 'quiz'" class="screen quiz-screen">
            <div class="quiz-header">
                <h2>{{ currentQuestion.question }}</h2>
                <div class="timer" :class="{'warning': timeLeft <= 5}">{{ timeLeft }}</div>
            </div>
            <div class="quiz-category" v-if="currentQuestion.category">
                Kategori: {{ currentQuestion.category.name || 'Genel' }}
            </div>
            <div class="options-container">
                <div 
                    v-for="(option, index) in currentQuestion.options" 
                    :key="index" 
                    class="option"
                    :class="{
                        'correct': showingResults && index === currentQuestion.correct_answer,
                        'wrong': showingResults && selectedOption === index && index !== currentQuestion.correct_answer,
                        'selected': selectedOption === index && !showingResults,
                        'disabled': showingResults
                    }"
                    @click="!showingResults && selectOption(index)"
                >
                    {{ option }}
                </div>
            </div>
            <div class="quiz-footer">
                <div class="question-counter">Soru: {{ currentQuestionIndex + 1 }}/{{ totalQuestions }}</div>
                <div class="score">Puan: {{ score }}</div>
            </div>
        </div>

        <!-- Results Screen -->
        <div v-if="currentScreen === 'results'" class="screen results-screen">
            <h2>Yarışma Sonuçları</h2>
            <div v-if="gameMode === 'individual'" class="player-info">
                Oyuncu: {{ playerName }}
            </div>
            <div v-else class="team-info">
                <p>Takım: {{ teamName }} ({{ participantCount }} kişi)</p>
                <div class="team-members">
                    <p>Takım Üyeleri:</p>
                    <ul>
                        <li v-for="(member, index) in teamMembers" :key="index">{{ member }}</li>
                    </ul>
                </div>
            </div>
            <div class="final-score">Toplam Puan: {{ score }}</div>
            <div class="score-details">
                <p>Doğru Cevaplar: {{ correctAnswers }}</p>
                <p>Yanlış Cevaplar: {{ wrongAnswers }}</p>
                <p>Ortalama Yanıt Süresi: {{ averageResponseTime.toFixed(2) }} saniye</p>
            </div>
            <button @click="restart" class="btn">Yeniden Başlat</button>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            currentScreen: 'welcome',
            gameMode: null,
            playerName: '',
            teamName: '',
            participantCount: 2,
            teamMembers: [],
            categories: [],
            selectedCategories: [],
            questions: [],
            filteredQuestions: [],
            currentQuestionIndex: 0,
            totalQuestions: 25,
            score: 0,
            timeLeft: 0,
            timer: null,
            selectedOption: null,
            showingResults: false,
            correctAnswers: 0,
            wrongAnswers: 0,
            responseTimeSum: 0,
            currentResponseStart: 0,
            isAnswerCorrect: false
        };
    },
    computed: {
        currentQuestion() {
            return this.filteredQuestions[this.currentQuestionIndex] || {};
        },
        averageResponseTime() {
            const totalAnswers = this.correctAnswers + this.wrongAnswers;
            return totalAnswers > 0 ? this.responseTimeSum / totalAnswers : 0;
        }
    },
    mounted() {
        this.fetchCategories();
        
        // LocalStorage'dan kategori ve oyun modu bilgilerini kontrol et
        const selectedCategory = localStorage.getItem('selectedCategory');
        const gameMode = localStorage.getItem('gameMode');
        const customQuizId = localStorage.getItem('customQuizId');
        
        if (customQuizId) {
            // Özel yarışma ID'si varsa, o yarışmayı başlat
            this.loadCustomQuiz(customQuizId);
            localStorage.removeItem('customQuizId');
        } else if (selectedCategory) {
            // Kategori seçilmişse, o kategoriyi seç
            this.selectedCategories = [parseInt(selectedCategory)];
            localStorage.removeItem('selectedCategory');
        }
        
        if (gameMode && !customQuizId) {
            // Oyun modu seçilmişse ve özel yarışma yoksa, o modu başlat
            this.selectMode(gameMode);
            localStorage.removeItem('gameMode');
        }
    },
    methods: {
        selectMode(mode) {
            this.gameMode = mode;
            
            if (mode === 'individual') {
                // Kullanıcı giriş yapmış mı kontrol et
                const token = localStorage.getItem('token');
                const userJson = localStorage.getItem('user');
                
                if (token && userJson) {
                    // Kullanıcı giriş yapmış, kullanıcı adını al ve kategori seçimine geç
                    const user = JSON.parse(userJson);
                    this.playerName = user.name;
                    this.currentScreen = 'categorySelection';
                } else {
                    // Kullanıcı giriş yapmamış, isim sor
                    this.currentScreen = 'individualForm';
                }
            } else {
                // Takım oyunu için takım bilgilerini sor
                this.currentScreen = 'teamForm';
            }
        },
        showTeamMembersForm() {
            // Initialize team members array based on participantCount
            this.teamMembers = Array(parseInt(this.participantCount)).fill('');
            this.currentScreen = 'teamMembersForm';
        },
        showCategorySelection() {
            this.currentScreen = 'categorySelection';
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
        toggleCategory(categoryId) {
            const index = this.selectedCategories.indexOf(categoryId);
            if (index === -1) {
                this.selectedCategories.push(categoryId);
            } else {
                this.selectedCategories.splice(index, 1);
            }
        },
        async startQuiz() {
            // Fetch questions from API with category filtering
            try {
                let url = '/api/questions?limit=' + this.totalQuestions;
                
                if (this.selectedCategories.length > 0) {
                    url += '&categories=' + this.selectedCategories.join(',');
                }
                
                const response = await fetch(url);
                this.questions = await response.json();
                
                // Eğer yeterli soru yoksa kullanıcıya bildir ve farklı kategori seçmesini iste
                if (this.questions.length < this.totalQuestions) {
                    alert(`Seçtiğiniz kategorilerde yeterli soru bulunmamaktadır (${this.questions.length}/${this.totalQuestions}). Lütfen farklı kategoriler seçin.`);
                    return;
                }
                
                // Ensure we have enough medium and hard questions
                this.balanceQuestionDifficulty();
                
                // Shuffle and slice to get exactly the number of questions we need
                this.filteredQuestions = this.shuffleQuestions(this.questions).slice(0, this.totalQuestions);
                
                // Reset quiz state
                this.currentQuestionIndex = 0;
                this.score = 0;
                this.showingResults = false;
                this.correctAnswers = 0;
                this.wrongAnswers = 0;
                this.responseTimeSum = 0;
                this.selectedOption = null;
                
                // Show quiz screen and start timer
                this.currentScreen = 'quiz';
                this.startTimer();
                this.currentResponseStart = Date.now();
            } catch (error) {
                console.error('Failed to fetch questions:', error);
                alert('Sorular yüklenirken bir hata oluştu. Lütfen tekrar deneyin.');
            }
        },
        balanceQuestionDifficulty() {
            // Ensure we have a mix of difficulties
            // Aim for approximately 60% easy, 30% medium, 10% hard
            const easy = this.questions.filter(q => q.difficulty === 'easy');
            const medium = this.questions.filter(q => q.difficulty === 'medium');
            const hard = this.questions.filter(q => q.difficulty === 'hard');
            
            console.log(`Questions by difficulty - Easy: ${easy.length}, Medium: ${medium.length}, Hard: ${hard.length}`);
            
            // If we have too many easy questions, replace some with medium or hard
            if (easy.length > this.totalQuestions * 0.6 && (medium.length + hard.length) < this.totalQuestions * 0.4) {
                // Calculate how many easy questions to replace
                const excessEasy = Math.min(
                    easy.length - Math.ceil(this.totalQuestions * 0.6),
                    this.totalQuestions * 0.4 - (medium.length + hard.length)
                );
                
                if (excessEasy > 0) {
                    // Remove excess easy questions
                    this.questions = this.questions.filter(q => q.difficulty !== 'easy' || 
                        easy.indexOf(q) >= excessEasy);
                }
            }
        },
        shuffleQuestions(questions) {
            // Fisher-Yates algorithm
            const shuffled = [...questions];
            for (let i = shuffled.length - 1; i > 0; i--) {
                const j = Math.floor(Math.random() * (i + 1));
                [shuffled[i], shuffled[j]] = [shuffled[j], shuffled[i]];
            }
            return shuffled;
        },
        startTimer() {
            const seconds = this.currentQuestion.time || 30;
            this.timeLeft = seconds;
            
            clearInterval(this.timer);
            this.timer = setInterval(() => {
                if (this.isAnswerCorrect) return; // Don't decrease timer if answer is correct
                
                this.timeLeft--;
                
                if (this.timeLeft <= 0) {
                    clearInterval(this.timer);
                    this.handleTimeUp();
                }
            }, 1000);
        },
        handleTimeUp() {
            if (this.showingResults) return;
            
            this.selectedOption = null;
            this.showingResults = true;
            this.wrongAnswers++;
            
            // Calculate response time (using full question time since timed out)
            const responseTime = this.currentQuestion.time || 30;
            this.responseTimeSum += responseTime;
            
            // Move to next question after delay
            setTimeout(() => {
                this.nextQuestion();
            }, 2000);
        },
        selectOption(index) {
            if (this.showingResults) return; // Prevent multiple selection
            
            this.selectedOption = index;
            this.showingResults = true;
            
            // Calculate response time
            const responseTime = (Date.now() - this.currentResponseStart) / 1000;
            this.responseTimeSum += responseTime;
            
            // Check if answer is correct
            if (index === this.currentQuestion.correct_answer) {
                this.score += this.calculatePoints();
                this.correctAnswers++;
                this.isAnswerCorrect = true; // Mark answer as correct to pause timer
            } else {
                this.wrongAnswers++;
                this.isAnswerCorrect = false;
            }
            
            // Move to next question after delay
            setTimeout(() => {
                this.nextQuestion();
            }, 2000);
        },
        calculatePoints() {
            const difficulty = this.currentQuestion.difficulty;
            
            // Base points based on difficulty
            let basePoints = 0;
            switch (difficulty) {
                case 'easy':
                    basePoints = 10;
                    break;
                case 'medium':
                    basePoints = 20;
                    break;
                case 'hard':
                    basePoints = 30;
                    break;
                default:
                    basePoints = 10;
            }
            
            // Time bonus
            const timeBonus = Math.floor(this.timeLeft / 3);
            
            return basePoints + timeBonus;
        },
        nextQuestion() {
            this.currentQuestionIndex++;
            this.selectedOption = null;
            this.showingResults = false;
            this.isAnswerCorrect = false; // Reset correct answer flag
            
            if (this.currentQuestionIndex >= this.filteredQuestions.length || 
                this.currentQuestionIndex >= this.totalQuestions) {
                // End of quiz
                this.endQuiz();
                return;
            }
            
            // Reset response time tracker
            this.currentResponseStart = Date.now();
            
            // Start timer for next question
            this.startTimer();
        },
        endQuiz() {
            // Clear any active timer
            clearInterval(this.timer);
            
            // Save results to database
            this.saveResults();
            
            // Show results screen
            this.currentScreen = 'results';
        },
        async saveResults() {
            try {
                const resultData = {
                    team_name: this.gameMode === 'individual' ? this.playerName : this.teamName,
                    participant_count: this.gameMode === 'individual' ? 1 : this.participantCount,
                    score: this.score,
                    categories: this.selectedCategories
                };
                
                // Kullanıcı giriş yapmışsa token ekle
                const headers = {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                };
                
                const token = localStorage.getItem('token');
                if (token) {
                    headers['Authorization'] = `Bearer ${token}`;
                    
                    // Kullanıcı bilgilerini al
                    const userJson = localStorage.getItem('user');
                    if (userJson) {
                        const user = JSON.parse(userJson);
                        // Eğer bireysel oyun modunda ve oyuncu adı girilmemişse, kullanıcı adını kullan
                        if (this.gameMode === 'individual' && (!this.playerName || this.playerName.trim() === '')) {
                            resultData.team_name = user.name;
                        }
                    }
                }
                
                const response = await fetch('/api/results', {
                    method: 'POST',
                    headers: headers,
                    body: JSON.stringify(resultData)
                });
                
                if (!response.ok) {
                    throw new Error('Sonuçlar kaydedilirken bir hata oluştu');
                }
                
                const result = await response.json();
                console.log('Sonuçlar kaydedildi:', result);
                
            } catch (error) {
                console.error('Failed to save results:', error);
            }
        },
        restart() {
            this.currentScreen = 'welcome';
            this.gameMode = null;
            this.playerName = '';
            this.teamName = '';
            this.participantCount = 2;
            this.teamMembers = [];
            this.selectedCategories = [];
            this.score = 0;
            this.correctAnswers = 0;
            this.wrongAnswers = 0;
            this.responseTimeSum = 0;
        },
        // Özel yarışma yükleme metodu
        async loadCustomQuiz(quizId) {
            try {
                // Token kontrolü
                const token = localStorage.getItem('token');
                if (!token) {
                    alert('Oturum süreniz dolmuş. Lütfen tekrar giriş yapın.');
                    this.$router.push('/login');
                    return;
                }
                
                // Önce yarışma bilgilerini al
                const quizResponse = await fetch(`/api/custom-quiz/${quizId}`, {
                    headers: {
                        'Authorization': `Bearer ${token}`,
                        'Accept': 'application/json'
                    }
                });
                
                if (!quizResponse.ok) {
                    throw new Error('Yarışma bilgileri alınamadı');
                }
                
                const quiz = await quizResponse.json();
                
                // Yarışma sorularını al
                const questionsResponse = await fetch(`/api/custom-quiz/${quizId}/questions`, {
                    headers: {
                        'Authorization': `Bearer ${token}`,
                        'Accept': 'application/json'
                    }
                });
                
                if (!questionsResponse.ok) {
                    throw new Error('Yarışma soruları alınamadı');
                }
                
                this.questions = await questionsResponse.json();
                
                // Yarışma ayarlarını güncelle
                this.gameMode = 'individual';
                this.playerName = localStorage.getItem('user') ? JSON.parse(localStorage.getItem('user')).name : '';
                this.totalQuestions = quiz.question_count;
                this.selectedCategories = quiz.categories || [];
                
                // Soruları filtrele ve karıştır
                this.filteredQuestions = this.shuffleQuestions(this.questions).slice(0, this.totalQuestions);
                
                // Quiz durumunu sıfırla
                this.currentQuestionIndex = 0;
                this.score = 0;
                this.showingResults = false;
                this.correctAnswers = 0;
                this.wrongAnswers = 0;
                this.responseTimeSum = 0;
                this.selectedOption = null;
                
                // Quiz ekranını göster ve zamanlayıcıyı başlat
                this.currentScreen = 'quiz';
                this.startTimer();
                this.currentResponseStart = Date.now();
                
            } catch (error) {
                console.error('Failed to load custom quiz:', error);
                alert('Özel yarışma yüklenirken bir hata oluştu: ' + error.message);
                this.currentScreen = 'welcome';
            }
        }
    }
};
</script>

<style scoped>
.quiz-app {
    width: 100%;
    max-width: 900px;
    margin: 0 auto;
    background-color: rgba(255, 255, 255, 0.95);
    border-radius: 15px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
    padding: 30px;
    text-align: center;
    position: relative;
    overflow: hidden;
}

.quiz-app::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: 
        radial-gradient(circle at 10% 20%, rgba(108, 99, 255, 0.1) 0%, transparent 20%),
        radial-gradient(circle at 90% 80%, rgba(255, 71, 98, 0.1) 0%, transparent 20%),
        linear-gradient(135deg, #6c63ff33 0%, #ff476233 100%);
    z-index: -1;
}

.screen {
    animation: fadeIn 0.5s ease;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

h1 {
    color: #333;
    margin-bottom: 20px;
    font-size: 2.8rem;
    background: linear-gradient(to right, #6c63ff, #ff4762);
    -webkit-background-clip: text;
    background-clip: text;
    color: transparent;
    margin-bottom: 30px;
}

h2 {
    color: #333;
    margin-bottom: 20px;
    font-size: 2rem;
    position: relative;
    display: inline-block;
}

h2::after {
    content: '';
    position: absolute;
    bottom: -10px;
    left: 50%;
    transform: translateX(-50%);
    width: 80px;
    height: 3px;
    background: linear-gradient(to right, #6c63ff, #ff4762);
    border-radius: 3px;
}

h3 {
    font-size: 1.3rem;
    margin-bottom: 10px;
    color: #444;
}

p {
    margin-bottom: 20px;
    font-size: 1.1rem;
    line-height: 1.5;
    color: #555;
}

.btn {
    display: inline-block;
    background: linear-gradient(to right, #6c63ff, #ff4762);
    color: white;
    font-size: 1.1rem;
    padding: 12px 30px;
    border: none;
    border-radius: 50px;
    cursor: pointer;
    transition: all 0.3s ease;
    margin: 15px 10px;
    font-weight: 600;
    box-shadow: 0 4px 15px rgba(108, 99, 255, 0.2);
}

.btn:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 20px rgba(108, 99, 255, 0.3);
}

.btn:disabled {
    opacity: 0.6;
    transform: none;
    cursor: not-allowed;
}

.game-mode-selection {
    display: flex;
    flex-direction: column;
    gap: 15px;
    max-width: 320px;
    margin: 0 auto;
}

.form-group {
    margin-bottom: 20px;
    text-align: left;
}

.form-group label {
    display: block;
    margin-bottom: 8px;
    font-weight: 600;
    color: #444;
}

.form-group input {
    width: 100%;
    padding: 14px;
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

.categories-container {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 20px;
    margin-bottom: 30px;
}

.category-card {
    background: #f5f5f7;
    border-radius: 12px;
    padding: 15px;
    cursor: pointer;
    transition: all 0.3s ease;
    border: 2px solid transparent;
    position: relative;
    overflow: hidden;
}

.category-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 5px;
    background: linear-gradient(to right, #6c63ff, #ff4762);
    opacity: 0;
    transition: opacity 0.3s ease;
}

.category-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.05);
}

.category-card:hover::before {
    opacity: 1;
}

.category-card.selected {
    border-color: #6c63ff;
    background-color: #f0f0ff;
}

.category-card.selected::before {
    opacity: 1;
}

.selection-info {
    font-weight: 600;
    color: #6c63ff;
    margin-bottom: 5px;
}

.quiz-header {
    position: relative;
    margin-bottom: 40px;
}

.quiz-category {
    color: #6c63ff;
    font-size: 1.1rem;
    margin-bottom: 20px;
    font-weight: 600;
}

.timer {
    position: absolute;
    top: -20px;
    right: -20px;
    background: linear-gradient(to right, #6c63ff, #ff4762);
    color: white;
    width: 60px;
    height: 60px;
    border-radius: 50%;
    display: flex;
    justify-content: center;
    align-items: center;
    font-size: 1.5rem;
    font-weight: bold;
    box-shadow: 0 5px 15px rgba(108, 99, 255, 0.3);
    transition: all 0.3s ease;
}

.timer.warning {
    background: linear-gradient(to right, #ff4762, #ff7e00);
    animation: pulse 1s infinite;
}

@keyframes pulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.05); }
    100% { transform: scale(1); }
}

.options-container {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 15px;
    margin-bottom: 30px;
}

.option {
    background-color: #f5f5f7;
    padding: 20px 15px;
    border-radius: 12px;
    cursor: pointer;
    transition: all 0.3s ease;
    font-weight: 500;
    position: relative;
    overflow: hidden;
    z-index: 1;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.03);
}

.option::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(to right, #f0f0ff, #fff0f5);
    opacity: 0;
    z-index: -1;
    transition: opacity 0.3s ease;
}

.option:hover:not(.disabled)::before {
    opacity: 1;
}

.option:hover:not(.disabled) {
    transform: translateY(-3px);
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
}

.option.selected {
    background-color: #e0e0ff;
    transform: translateY(-3px);
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
}

.option.correct {
    background-color: #4caf50;
    color: white;
}

.option.wrong {
    background-color: #f44336;
    color: white;
}

.option.disabled {
    pointer-events: none;
}

.quiz-footer {
    display: flex;
    justify-content: space-between;
    padding-top: 20px;
    border-top: 1px solid #eee;
    font-weight: 600;
    color: #555;
}

.final-score {
    font-size: 2.5rem;
    font-weight: bold;
    margin: 30px 0;
    background: linear-gradient(to right, #6c63ff, #ff4762);
    -webkit-background-clip: text;
    background-clip: text;
    color: transparent;
}

.player-info, .team-info {
    font-size: 1.3rem;
    margin-bottom: 20px;
    color: #444;
}

.team-members {
    max-width: 400px;
    margin: 0 auto;
    text-align: left;
    background-color: #f5f5f7;
    padding: 15px 20px;
    border-radius: 10px;
}

.team-members ul {
    list-style-type: none;
    padding: 0;
}

.team-members li {
    margin-bottom: 5px;
    display: flex;
    align-items: center;
}

.team-members li::before {
    content: '•';
    color: #6c63ff;
    font-size: 1.5rem;
    margin-right: 10px;
}

.score-details {
    background-color: #f5f5f7;
    border-radius: 12px;
    padding: 20px;
    max-width: 400px;
    margin: 0 auto 30px;
    text-align: left;
}

.score-details p {
    margin-bottom: 10px;
    font-weight: 500;
}

@media (max-width: 768px) {
    .options-container, .categories-container {
        grid-template-columns: 1fr;
    }
    
    .quiz-app {
        padding: 20px;
        margin: 15px;
        width: auto;
    }
    
    h1 {
        font-size: 2.2rem;
    }
    
    h2 {
        font-size: 1.6rem;
    }
    
    .timer {
        width: 50px;
        height: 50px;
        font-size: 1.3rem;
        top: -15px;
        right: -15px;
    }
}
</style> 