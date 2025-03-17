<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Models\CustomQuiz;
use App\Models\Question;
use App\Models\Category;
use PhpOffice\PhpPresentation\PhpPresentation;
use PhpOffice\PhpPresentation\IOFactory;
use PhpOffice\PhpPresentation\Style\Color;
use PhpOffice\PhpPresentation\Style\Alignment;
use PDF;

class CustomQuizController extends Controller
{
    /**
     * Özel yarışma oluştur
     */
    public function store(Request $request)
    {
        // Giriş kontrolü
        if (!auth()->check()) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
        
        // Validasyon
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'question_count' => 'required|integer|min:5|max:50',
            'categories' => 'nullable|string',
            'template_file' => 'nullable|file|mimes:json,csv,xlsx|max:2048',
            'output_type' => 'required|in:web,presentation,pdf'
        ]);
        
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        
        // Kategori bilgilerini parse et
        $selectedCategories = [];
        if ($request->has('categories') && !empty($request->categories)) {
            $selectedCategories = json_decode($request->categories);
        }
        
        // Özel yarışma kaydı oluştur
        $customQuiz = new CustomQuiz();
        $customQuiz->user_id = auth()->id();
        $customQuiz->name = $request->name;
        $customQuiz->description = $request->description;
        $customQuiz->question_count = $request->question_count;
        $customQuiz->categories = $selectedCategories;
        $customQuiz->output_type = $request->output_type;
        
        // Şablon dosyası varsa kaydet
        if ($request->hasFile('template_file')) {
            $file = $request->file('template_file');
            $fileName = time() . '_' . Str::slug($request->name) . '.' . $file->getClientOriginalExtension();
            $filePath = $file->storeAs('templates', $fileName, 'public');
            $customQuiz->template_file = $filePath;
        }
        
        $customQuiz->save();
        
        // Çıktı tipine göre işlem yap
        switch ($request->output_type) {
            case 'web':
                // Web uygulaması için ID döndür
                return response()->json([
                    'id' => $customQuiz->id,
                    'message' => 'Yarışma başarıyla oluşturuldu'
                ]);
                
            case 'presentation':
                // Sunum oluştur ve indir
                $downloadUrl = $this->createPresentation($customQuiz);
                return response()->json([
                    'id' => $customQuiz->id,
                    'download_url' => $downloadUrl,
                    'message' => 'Sunum başarıyla oluşturuldu'
                ]);
                
            case 'pdf':
                // PDF oluştur ve indir
                $downloadUrl = $this->createPDF($customQuiz);
                return response()->json([
                    'id' => $customQuiz->id,
                    'download_url' => $downloadUrl,
                    'message' => 'PDF başarıyla oluşturuldu'
                ]);
                
            default:
                return response()->json([
                    'id' => $customQuiz->id,
                    'message' => 'Yarışma başarıyla oluşturuldu'
                ]);
        }
    }
    
    /**
     * Kullanıcının özel yarışmalarını listele
     */
    public function index()
    {
        // Giriş kontrolü
        if (!auth()->check()) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
        
        $quizzes = CustomQuiz::where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->get();
            
        return response()->json($quizzes);
    }
    
    /**
     * Özel yarışma detaylarını getir
     */
    public function show($id)
    {
        // Giriş kontrolü
        if (!auth()->check()) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
        
        $quiz = CustomQuiz::where('id', $id)
            ->where('user_id', auth()->id())
            ->first();
            
        if (!$quiz) {
            return response()->json(['message' => 'Quiz not found'], 404);
        }
        
        return response()->json($quiz);
    }
    
    /**
     * Özel yarışma için soruları getir
     */
    public function getQuestions($id)
    {
        // Giriş kontrolü
        if (!auth()->check()) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
        
        $quiz = CustomQuiz::where('id', $id)
            ->where('user_id', auth()->id())
            ->first();
            
        if (!$quiz) {
            return response()->json(['message' => 'Quiz not found'], 404);
        }
        
        // Şablon dosyası varsa, şablondan soruları yükle
        if ($quiz->template_file) {
            $questions = $this->loadQuestionsFromTemplate($quiz);
            return response()->json($questions);
        }
        
        // Şablon yoksa, kategorilere göre soruları getir
        $query = Question::with('category');
        
        // Kategorilere göre filtrele
        if (!empty($quiz->categories)) {
            $query->whereIn('category_id', $quiz->categories);
        }
        
        // Soruları karıştır ve sınırla
        $questions = $query->inRandomOrder()
            ->limit($quiz->question_count)
            ->get();
            
        return response()->json($questions);
    }
    
    /**
     * Şablon dosyasından soruları yükle
     */
    private function loadQuestionsFromTemplate($quiz)
    {
        $filePath = storage_path('app/public/' . $quiz->template_file);
        $extension = pathinfo($filePath, PATHINFO_EXTENSION);
        
        $questions = [];
        
        switch ($extension) {
            case 'json':
                $jsonData = file_get_contents($filePath);
                $questions = json_decode($jsonData, true);
                break;
                
            case 'csv':
                // CSV dosyasını işle
                $csvData = array_map('str_getcsv', file($filePath));
                $headers = array_shift($csvData);
                
                foreach ($csvData as $row) {
                    $question = array_combine($headers, $row);
                    $questions[] = $this->formatQuestionFromTemplate($question);
                }
                break;
                
            case 'xlsx':
                // Excel dosyasını işle (PhpSpreadsheet kütüphanesi gerekli)
                // Bu örnekte basitleştirme için atlanmıştır
                break;
        }
        
        // Soru sayısını sınırla
        return array_slice($questions, 0, $quiz->question_count);
    }
    
    /**
     * Şablondan gelen soruyu formatla
     */
    private function formatQuestionFromTemplate($data)
    {
        // Varsayılan soru formatı
        $question = [
            'id' => rand(1000, 9999),
            'question' => $data['question'] ?? '',
            'options' => [
                $data['option_a'] ?? '',
                $data['option_b'] ?? '',
                $data['option_c'] ?? '',
                $data['option_d'] ?? ''
            ],
            'correct_answer' => (int)($data['correct_answer'] ?? 0),
            'difficulty' => $data['difficulty'] ?? 'medium',
            'time' => (int)($data['time'] ?? 30),
            'category' => [
                'id' => (int)($data['category_id'] ?? 1),
                'name' => $data['category_name'] ?? 'Genel Kültür'
            ]
        ];
        
        return $question;
    }
    
    /**
     * Sunum oluştur
     */
    private function createPresentation($quiz)
    {
        // Soruları al
        $questions = [];
        
        if ($quiz->template_file) {
            $questions = $this->loadQuestionsFromTemplate($quiz);
        } else {
            $query = Question::with('category');
            
            if (!empty($quiz->categories)) {
                $query->whereIn('category_id', $quiz->categories);
            }
            
            $questions = $query->inRandomOrder()
                ->limit($quiz->question_count)
                ->get()
                ->toArray();
        }
        
        // PhpPresentation ile sunum oluştur
        $presentation = new PhpPresentation();
        
        // Kapak sayfası
        $slide = $presentation->createSlide();
        $shape = $slide->createRichTextShape()
            ->setHeight(300)
            ->setWidth(600)
            ->setOffsetX(150)
            ->setOffsetY(200);
        $shape->getActiveParagraph()->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $textRun = $shape->createTextRun($quiz->name);
        $textRun->getFont()->setBold(true)->setSize(36);
        
        if ($quiz->description) {
            $shape = $slide->createRichTextShape()
                ->setHeight(100)
                ->setWidth(600)
                ->setOffsetX(150)
                ->setOffsetY(300);
            $shape->getActiveParagraph()->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $textRun = $shape->createTextRun($quiz->description);
            $textRun->getFont()->setSize(18);
        }
        
        // Her soru için slayt oluştur
        foreach ($questions as $index => $question) {
            $slide = $presentation->createSlide();
            
            // Soru başlığı
            $shape = $slide->createRichTextShape()
                ->setHeight(100)
                ->setWidth(600)
                ->setOffsetX(150)
                ->setOffsetY(50);
            $textRun = $shape->createTextRun('Soru ' . ($index + 1));
            $textRun->getFont()->setBold(true)->setSize(24);
            
            // Kategori
            $shape = $slide->createRichTextShape()
                ->setHeight(50)
                ->setWidth(600)
                ->setOffsetX(150)
                ->setOffsetY(100);
            $categoryName = isset($question['category']) ? $question['category']['name'] : 'Genel Kültür';
            $textRun = $shape->createTextRun('Kategori: ' . $categoryName);
            $textRun->getFont()->setSize(16)->setColor(new Color('4472C4'));
            
            // Soru metni
            $shape = $slide->createRichTextShape()
                ->setHeight(100)
                ->setWidth(600)
                ->setOffsetX(150)
                ->setOffsetY(150);
            $textRun = $shape->createTextRun($question['question']);
            $textRun->getFont()->setSize(18);
            
            // Seçenekler
            $options = $question['options'];
            $yOffset = 250;
            
            foreach ($options as $optIndex => $option) {
                $shape = $slide->createRichTextShape()
                    ->setHeight(50)
                    ->setWidth(600)
                    ->setOffsetX(150)
                    ->setOffsetY($yOffset);
                $textRun = $shape->createTextRun(chr(65 + $optIndex) . ') ' . $option);
                $textRun->getFont()->setSize(16);
                $yOffset += 50;
            }
        }
        
        // Sunumu kaydet
        $fileName = Str::slug($quiz->name) . '_' . time() . '.pptx';
        $filePath = storage_path('app/public/presentations/' . $fileName);
        
        // Dizin yoksa oluştur
        if (!file_exists(dirname($filePath))) {
            mkdir(dirname($filePath), 0755, true);
        }
        
        $writer = IOFactory::createWriter($presentation, 'PowerPoint2007');
        $writer->save($filePath);
        
        // İndirme URL'i döndür
        return url('storage/presentations/' . $fileName);
    }
    
    /**
     * PDF oluştur
     */
    private function createPDF($quiz)
    {
        // Soruları al
        $questions = [];
        
        if ($quiz->template_file) {
            $questions = $this->loadQuestionsFromTemplate($quiz);
        } else {
            $query = Question::with('category');
            
            if (!empty($quiz->categories)) {
                $query->whereIn('category_id', $quiz->categories);
            }
            
            $questions = $query->inRandomOrder()
                ->limit($quiz->question_count)
                ->get()
                ->toArray();
        }
        
        // PDF oluştur (Laravel-DOMPDF kullanılıyor)
        $pdf = PDF::loadView('pdf.quiz', [
            'quiz' => $quiz,
            'questions' => $questions
        ]);
        
        // PDF'i kaydet
        $fileName = Str::slug($quiz->name) . '_' . time() . '.pdf';
        $filePath = 'pdfs/' . $fileName;
        Storage::disk('public')->put($filePath, $pdf->output());
        
        // İndirme URL'i döndür
        return url('storage/' . $filePath);
    }
} 