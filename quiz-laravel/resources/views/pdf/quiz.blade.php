<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $quiz->name }} - PDF</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 20px;
            background-color: white;
            color: #333;
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #6c63ff;
        }
        
        .header h1 {
            color: #6c63ff;
            font-size: 24px;
            margin-bottom: 10px;
        }
        
        .header p {
            color: #666;
            font-size: 14px;
            margin: 5px 0;
        }
        
        .question-item {
            margin-bottom: 30px;
            page-break-inside: avoid;
        }
        
        .question-header {
            background-color: #f0f0ff;
            padding: 10px 15px;
            border-radius: 5px 5px 0 0;
            border-left: 4px solid #6c63ff;
        }
        
        .question-number {
            font-weight: bold;
            color: #6c63ff;
            margin-right: 10px;
        }
        
        .question-text {
            font-size: 16px;
            font-weight: bold;
        }
        
        .options-list {
            padding: 15px;
            background-color: #f9f9f9;
            border-radius: 0 0 5px 5px;
            border-left: 4px solid #eee;
        }
        
        .option {
            margin-bottom: 10px;
            padding: 8px 12px;
            background-color: white;
            border-radius: 4px;
            border: 1px solid #eee;
        }
        
        .option.correct {
            background-color: #d4edda;
            border-color: #c3e6cb;
        }
        
        .option-letter {
            font-weight: bold;
            color: #6c63ff;
            margin-right: 10px;
        }
        
        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 12px;
            color: #999;
            border-top: 1px solid #eee;
            padding-top: 20px;
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
            <p>{{ $quiz->description }}</p>
        @endif
        <p>Toplam {{ count($questions) }} Soru</p>
        <p>Oluşturulma Tarihi: {{ $quiz->created_at->format('d.m.Y') }}</p>
    </div>
    
    @foreach($questions as $index => $question)
        <div class="question-item">
            <div class="question-header">
                <span class="question-number">Soru {{ $index + 1 }}</span>
                <span class="question-text">{{ $question['question'] }}</span>
            </div>
            
            <div class="options-list">
                @foreach($question['options'] as $optIndex => $option)
                    <div class="option {{ $optIndex === $question['correct_answer'] ? 'correct' : '' }}">
                        <span class="option-letter">{{ ['A', 'B', 'C', 'D'][$optIndex] }}</span>
                        {{ $option }}
                    </div>
                @endforeach
            </div>
        </div>
        
        @if(($index + 1) % 5 == 0 && $index < count($questions) - 1)
            <div class="page-break"></div>
        @endif
    @endforeach
    
    <div class="footer">
        <p>Bu yarışma {{ $quiz->user->name ?? 'Anonim' }} tarafından oluşturulmuştur.</p>
        <p>© {{ date('Y') }} Bilgi Yarışması Uygulaması</p>
    </div>
</body>
</html> 