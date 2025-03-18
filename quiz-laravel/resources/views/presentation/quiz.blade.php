<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $quiz->name }} - Sunum</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
        }
        
        .slide {
            width: 100%;
            height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            background-color: white;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            padding: 40px;
            box-sizing: border-box;
            position: relative;
            overflow: hidden;
            page-break-after: always;
        }
        
        .title-slide {
            background: linear-gradient(135deg, #6c63ff 0%, #ff4762 100%);
            color: white;
        }
        
        .title-slide h1 {
            font-size: 3rem;
            margin-bottom: 20px;
            text-align: center;
        }
        
        .title-slide p {
            font-size: 1.5rem;
            opacity: 0.9;
            text-align: center;
        }
        
        .question-slide {
            padding: 60px;
        }
        
        .question-number {
            position: absolute;
            top: 20px;
            left: 20px;
            font-size: 1.2rem;
            color: #6c63ff;
            font-weight: bold;
        }
        
        .question-text {
            font-size: 2rem;
            margin-bottom: 40px;
            text-align: center;
            color: #333;
        }
        
        .options {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            width: 80%;
            max-width: 800px;
        }
        
        .option {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            font-size: 1.2rem;
            color: #333;
            border: 2px solid #eee;
            transition: all 0.3s ease;
        }
        
        .option.correct {
            background-color: #d4edda;
            border-color: #c3e6cb;
            color: #155724;
        }
        
        .option-letter {
            font-weight: bold;
            color: #6c63ff;
            margin-right: 10px;
        }
        
        .navigation {
            position: fixed;
            bottom: 20px;
            right: 20px;
            display: flex;
            gap: 10px;
            z-index: 100;
        }
        
        .nav-button {
            background-color: #6c63ff;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1rem;
            transition: all 0.3s ease;
        }
        
        .nav-button:hover {
            background-color: #5a52e0;
        }
        
        @media print {
            .navigation {
                display: none;
            }
            
            .slide {
                page-break-after: always;
                height: 100vh;
                box-shadow: none;
            }
        }
    </style>
</head>
<body>
    <!-- Başlık Slaytı -->
    <div class="slide title-slide" id="slide-0">
        <h1>{{ $quiz->name }}</h1>
        @if($quiz->description)
            <p>{{ $quiz->description }}</p>
        @endif
        <p>Toplam {{ count($questions) }} Soru</p>
    </div>
    
    <!-- Soru Slaytları -->
    @foreach($questions as $index => $question)
    <div class="slide question-slide" id="slide-{{ $index + 1 }}">
        <div class="question-number">Soru {{ $index + 1 }}</div>
        <div class="question-text">{{ $question['question'] }}</div>
        
        <div class="options">
            @foreach($question['options'] as $optIndex => $option)
                <div class="option {{ $optIndex === $question['correct_answer'] ? 'correct' : '' }}">
                    <span class="option-letter">{{ ['A', 'B', 'C', 'D'][$optIndex] }}</span>
                    {{ $option }}
                </div>
            @endforeach
        </div>
    </div>
    @endforeach
    
    <!-- Navigasyon -->
    <div class="navigation">
        <button class="nav-button" onclick="prevSlide()">Önceki</button>
        <button class="nav-button" onclick="nextSlide()">Sonraki</button>
        <button class="nav-button" onclick="window.print()">Yazdır</button>
    </div>
    
    <script>
        let currentSlide = 0;
        const totalSlides = {{ count($questions) + 1 }};
        
        function showSlide(index) {
            // Tüm slaytları gizle
            document.querySelectorAll('.slide').forEach(slide => {
                slide.style.display = 'none';
            });
            
            // Geçerli slaytı göster
            document.getElementById('slide-' + index).style.display = 'flex';
            currentSlide = index;
        }
        
        function nextSlide() {
            if (currentSlide < totalSlides - 1) {
                showSlide(currentSlide + 1);
            }
        }
        
        function prevSlide() {
            if (currentSlide > 0) {
                showSlide(currentSlide - 1);
            }
        }
        
        // Başlangıçta ilk slaytı göster
        showSlide(0);
        
        // Klavye navigasyonu
        document.addEventListener('keydown', function(event) {
            if (event.key === 'ArrowRight') {
                nextSlide();
            } else if (event.key === 'ArrowLeft') {
                prevSlide();
            }
        });
    </script>
</body>
</html> 