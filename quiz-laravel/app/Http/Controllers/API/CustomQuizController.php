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
use Barryvdh\DomPDF\Facade\Pdf;

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
            'template_file' => 'nullable|file|mimes:json,csv,jpg,jpeg,pptx,ppt|max:5120',
            'custom_questions' => 'nullable|string',
            'output_type' => 'required|in:web,pdf,presentation'
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
        
        // Özel sorular varsa kaydet
        if ($request->has('custom_questions') && !empty($request->custom_questions)) {
            $customQuiz->custom_questions = $request->custom_questions;
        }
        
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
                
            case 'pdf':
                // PDF oluştur ve indir
                $downloadUrl = $this->createPDF($customQuiz);
                return response()->json([
                    'id' => $customQuiz->id,
                    'download_url' => $downloadUrl,
                    'message' => 'PDF başarıyla oluşturuldu'
                ]);
                
            case 'presentation':
                // Basit HTML sunum oluştur
                $downloadUrl = $this->createSimplePresentation($customQuiz);
                return response()->json([
                    'id' => $customQuiz->id,
                    'download_url' => $downloadUrl,
                    'message' => 'Sunum başarıyla oluşturuldu'
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
     * Özel yarışma için PDF oluştur
     */
    public function generatePDF($id)
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
        
        // PDF oluştur
        $downloadUrl = $this->createPDF($quiz);
        
        return response()->json([
            'download_url' => $downloadUrl,
            'message' => 'PDF başarıyla oluşturuldu'
        ]);
    }
    
    /**
     * Özel yarışma için sunum oluştur
     */
    public function generatePresentation($id)
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
        
        // Sunum oluştur
        $downloadUrl = $this->createSimplePresentation($quiz);
        
        return response()->json([
            'download_url' => $downloadUrl,
            'message' => 'Sunum başarıyla oluşturuldu'
        ]);
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
        
        // Özel sorular varsa, onları döndür
        if (!empty($quiz->custom_questions)) {
            $questions = json_decode($quiz->custom_questions, true);
            return response()->json($questions);
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
                
            case 'jpg':
            case 'jpeg':
            case 'ppt':
            case 'pptx':
                // Resim veya sunum dosyaları için boş sorular oluştur
                // Bu durumda kullanıcı kendi sorularını ekleyecek
                for ($i = 0; $i < $quiz->question_count; $i++) {
                    $questions[] = [
                        'id' => rand(1000, 9999),
                        'question' => 'Soru ' . ($i + 1),
                        'options' => ['Şık A', 'Şık B', 'Şık C', 'Şık D'],
                        'correct_answer' => 0,
                        'difficulty' => 'medium',
                        'time' => 30,
                        'category' => [
                            'id' => 1,
                            'name' => 'Genel Kültür'
                        ]
                    ];
                }
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
     * PDF oluştur
     */
    private function createPDF($quiz)
    {
        // Soruları al
        $questions = $this->getQuestionsForOutput($quiz);
        
        // PDF oluştur (Laravel-DOMPDF kullanılıyor)
        $pdf = PDF::loadView('pdf.quiz', [
            'quiz' => $quiz,
            'questions' => $questions
        ]);
        
        // PDF'i kaydet
        $fileName = Str::slug($quiz->name) . '_' . $quiz->id . '.pdf';
        $filePath = 'pdfs/' . $fileName;
        
        // Storage klasörünün varlığını kontrol et
        if (!Storage::disk('public')->exists('pdfs')) {
            Storage::disk('public')->makeDirectory('pdfs');
        }
        
        Storage::disk('public')->put($filePath, $pdf->output());
        
        // İndirme URL'i döndür
        return url('storage/' . $filePath);
    }
    
    /**
     * Basit HTML sunum oluştur
     */
    private function createSimplePresentation($quiz)
    {
        // Soruları al
        $questions = $this->getQuestionsForOutput($quiz);
        
        // HTML sunum oluştur
        $html = view('presentation.quiz', [
            'quiz' => $quiz,
            'questions' => $questions
        ])->render();
        
        // HTML'i kaydet
        $fileName = Str::slug($quiz->name) . '_' . $quiz->id . '.html';
        $filePath = 'presentations/' . $fileName;
        
        // Storage klasörünün varlığını kontrol et
        if (!Storage::disk('public')->exists('presentations')) {
            Storage::disk('public')->makeDirectory('presentations');
        }
        
        Storage::disk('public')->put($filePath, $html);
        
        // İndirme URL'i döndür
        return url('storage/' . $filePath);
    }
    
    /**
     * Çıktı için soruları al
     */
    private function getQuestionsForOutput($quiz)
    {
        // Özel sorular varsa, onları kullan
        if (!empty($quiz->custom_questions)) {
            return json_decode($quiz->custom_questions, true);
        }
        
        // Şablon dosyası varsa, şablondan soruları yükle
        if ($quiz->template_file) {
            return $this->loadQuestionsFromTemplate($quiz);
        }
        
        // Şablon yoksa, kategorilere göre soruları getir
        $query = Question::with('category');
        
        // Kategorilere göre filtrele
        if (!empty($quiz->categories)) {
            $query->whereIn('category_id', $quiz->categories);
        }
        
        // Soruları karıştır ve sınırla
        return $query->inRandomOrder()
            ->limit($quiz->question_count)
            ->get()
            ->toArray();
    }
    
    /**
     * PDF dosyasını indir
     */
    public function downloadPDF($id)
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
        
        // PDF dosyasının yolunu kontrol et
        $filePath = storage_path('app/public/pdfs/' . Str::slug($quiz->name) . '_' . $id . '.pdf');
        
        if (!file_exists($filePath)) {
            // PDF yoksa oluştur
            $this->createPDF($quiz);
        }
        
        // Dosyayı indir
        return response()->download($filePath);
    }
    
    /**
     * Sunum dosyasını indir
     */
    public function downloadPresentation($id)
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
        
        // Sunum dosyasının yolunu kontrol et
        $filePath = storage_path('app/public/presentations/' . Str::slug($quiz->name) . '_' . $id . '.html');
        
        if (!file_exists($filePath)) {
            // Sunum yoksa oluştur
            $this->createSimplePresentation($quiz);
        }
        
        // Dosyayı indir
        return response()->download($filePath);
    }
} 