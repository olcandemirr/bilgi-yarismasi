<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>{{ $quiz->name }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        h1 {
            color: #6c63ff;
            font-size: 24px;
            margin-bottom: 10px;
        }
        .description {
            font-style: italic;
            color: #666;
            margin-bottom: 20px;
        }
        .question {
            margin-bottom: 30px;
            page-break-inside: avoid;
        }
        .question-header {
            background-color: #f0f0ff;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 10px;
        }
        .question-number {
            font-weight: bold;
            color: #6c63ff;
        }
        .category {
            color: #666;
            font-size: 12px;
            margin-bottom: 5px;
        }
        .question-text {
            font-weight: bold;
            margin-bottom: 15px;
        }
        .options {
            margin-left: 20px;
        }
        .option {
            margin-bottom: 8px;
        }
        .correct {
            color: #4caf50;
            font-weight: bold;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            font-size: 12px;
            color: #999;
            border-top: 1px solid #eee;
            padding-top: 10px;
        }
        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $quiz->name }}</h1>
        @if($quiz->description)
            <div class="description">{{ $quiz->description }}</div>
        @endif
        <div>Toplam Soru: {{ count($questions) }}</div>
    </div>
    
    @foreach($questions as $index => $question)
        <div class="question">
            <div class="question-header">
                <div class="question-number">Soru {{ $index + 1 }}</div>
                <div class="category">
                    Kategori: {{ isset($question['category']) ? $question['category']['name'] : 'Genel Kültür' }}
                </div>
            </div>
            
            <div class="question-text">{{ $question['question'] }}</div>
            
            <div class="options">
                @foreach($question['options'] as $optIndex => $option)
                    <div class="option {{ $optIndex === $question['correct_answer'] ? 'correct' : '' }}">
                        {{ chr(65 + $optIndex) }}) {{ $option }}
                    </div>
                @endforeach
            </div>
        </div>
        
        @if(($index + 1) % 5 == 0 && $index < count($questions) - 1)
            <div class="page-break"></div>
        @endif
    @endforeach
    
    <div class="footer">
        Bu yarışma {{ now()->format('d.m.Y') }} tarihinde {{ auth()->user()->name }} tarafından oluşturulmuştur.
    </div>
</body>
</html> 