document.addEventListener('DOMContentLoaded', () => {
    // DOM elementlerini seçme
    const welcomeScreen = document.getElementById('welcome-screen');
    const teamFormScreen = document.getElementById('team-form-screen');
    const quizScreen = document.getElementById('quiz-screen');
    const resultsScreen = document.getElementById('results-screen');
    
    const startBtn = document.getElementById('start-btn');
    const teamForm = document.getElementById('team-form');
    const teamNameInput = document.getElementById('team-name');
    const participantCountInput = document.getElementById('participant-count');
    
    const questionText = document.getElementById('question-text');
    const optionsContainer = document.getElementById('options-container');
    const questionCounter = document.getElementById('question-counter');
    const scoreDisplay = document.getElementById('score');
    const timerDisplay = document.getElementById('timer');
    const finalScoreDisplay = document.getElementById('final-score');
    const restartBtn = document.getElementById('restart-btn');
    
    // Quiz state
    let currentQuestionIndex = 0;
    let score = 0;
    let quizQuestions = [];
    let teamName = '';
    let participantCount = 0;
    let timerInterval;
    let timeLeft = 0;
    
    // Ekranları değiştirme fonksiyonu
    function showScreen(screen) {
        // Tüm ekranlardan active sınıfını kaldır
        [welcomeScreen, teamFormScreen, quizScreen, resultsScreen].forEach(s => {
            s.classList.remove('active');
        });
        
        // Gösterilecek ekrana active sınıfını ekle
        screen.classList.add('active');
    }
    
    // Başlangıç butonu olay dinleyicisi
    startBtn.addEventListener('click', () => {
        showScreen(teamFormScreen);
    });
    
    // Takım form gönderimi için olay dinleyicisi
    teamForm.addEventListener('submit', (e) => {
        e.preventDefault();
        
        teamName = teamNameInput.value;
        participantCount = parseInt(participantCountInput.value);
        
        // Quiz'i başlat
        startQuiz();
    });
    
    // Quiz'i başlatma fonksiyonu
    function startQuiz() {
        currentQuestionIndex = 0;
        score = 0;
        
        // Soruları karıştır
        quizQuestions = shuffleQuestions();
        
        // İlk soruyu göster
        showQuestion(quizQuestions[0]);
        updateQuestionCounter();
        updateScore();
        
        // Quiz ekranını göster
        showScreen(quizScreen);
    }
    
    // Soru gösterme fonksiyonu
    function showQuestion(question) {
        // Soru metnini güncelle
        questionText.textContent = question.question;
        
        // Seçenekleri temizle ve ekle
        optionsContainer.innerHTML = '';
        
        question.options.forEach((option, index) => {
            const optionElement = document.createElement('div');
            optionElement.classList.add('option');
            optionElement.textContent = option;
            
            optionElement.addEventListener('click', () => {
                checkAnswer(index, question.correctAnswer);
            });
            
            optionsContainer.appendChild(optionElement);
        });
        
        // Zamanlayıcıyı başlat
        startTimer(question.time);
    }
    
    // Cevap kontrolü
    function checkAnswer(selectedIndex, correctIndex) {
        clearInterval(timerInterval);
        
        const options = optionsContainer.querySelectorAll('.option');
        
        // Tüm seçenekleri devre dışı bırak
        options.forEach(option => {
            option.classList.add('disabled');
        });
        
        // Doğru cevabı işaretle
        options[correctIndex].classList.add('correct');
        
        // Seçilen cevap doğruysa puan ekle, yanlışsa yanlış seçeneği işaretle
        if (selectedIndex === correctIndex) {
            score += calculatePoints(timeLeft);
            updateScore();
        } else {
            options[selectedIndex].classList.add('wrong');
        }
        
        // Kısa bir gecikme sonra bir sonraki soruya geç
        setTimeout(() => {
            nextQuestion();
        }, 2000);
    }
    
    // Zamanlayıcı fonksiyonu
    function startTimer(seconds) {
        timeLeft = seconds;
        timerDisplay.textContent = timeLeft;
        
        clearInterval(timerInterval);
        
        timerInterval = setInterval(() => {
            timeLeft--;
            timerDisplay.textContent = timeLeft;
            
            if (timeLeft <= 0) {
                clearInterval(timerInterval);
                
                // Süre dolduğunda bir sonraki soruya geç
                const correctIndex = quizQuestions[currentQuestionIndex].correctAnswer;
                const options = optionsContainer.querySelectorAll('.option');
                
                options.forEach(option => {
                    option.classList.add('disabled');
                });
                
                options[correctIndex].classList.add('correct');
                
                setTimeout(() => {
                    nextQuestion();
                }, 2000);
            }
        }, 1000);
    }
    
    // Puan hesaplama
    function calculatePoints(timeRemaining) {
        const currentQuestion = quizQuestions[currentQuestionIndex];
        const difficulty = currentQuestion.difficulty;
        
        // Zorluk seviyesine göre baz puan
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
        
        // Kalan süreye göre bonus puan
        const timeBonus = Math.floor(timeRemaining / 3);
        
        return basePoints + timeBonus;
    }
    
    // Bir sonraki soruya geçme
    function nextQuestion() {
        currentQuestionIndex++;
        
        // Quiz bittiyse sonuç ekranını göster
        if (currentQuestionIndex >= quizQuestions.length) {
            showResults();
            return;
        }
        
        // Bir sonraki soruyu göster
        showQuestion(quizQuestions[currentQuestionIndex]);
        updateQuestionCounter();
    }
    
    // Soru sayacını güncelleme
    function updateQuestionCounter() {
        questionCounter.textContent = `Soru: ${currentQuestionIndex + 1}/${quizQuestions.length}`;
    }
    
    // Skoru güncelleme
    function updateScore() {
        scoreDisplay.textContent = `Puan: ${score}`;
    }
    
    // Sonuçları gösterme
    function showResults() {
        finalScoreDisplay.textContent = `Toplam Puan: ${score}`;
        showScreen(resultsScreen);
    }
    
    // Yeniden başlatma butonu
    restartBtn.addEventListener('click', () => {
        showScreen(welcomeScreen);
    });
}); 