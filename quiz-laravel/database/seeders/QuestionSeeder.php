<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class QuestionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get category IDs from the database
        $categories = DB::table('categories')->select('id', 'slug')->get()->keyBy('slug');

        // Array of questions with category IDs
        $questions = [
            // Genel Kültür Soruları
            [
                'question' => 'Türkiye\'nin başkenti neresidir?',
                'options' => json_encode(['İstanbul', 'Ankara', 'İzmir', 'Bursa']),
                'correct_answer' => 1,
                'difficulty' => 'easy',
                'category_id' => $categories['general']->id,
                'time' => 15,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'question' => 'Hangi ülke Avrupa Birliği\'nin kurucu üyelerinden değildir?',
                'options' => json_encode(['Almanya', 'Fransa', 'İtalya', 'İspanya']),
                'correct_answer' => 3,
                'difficulty' => 'medium',
                'category_id' => $categories['general']->id,
                'time' => 20,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'question' => 'Nobel Barış Ödülü hangi şehirde verilir?',
                'options' => json_encode(['Stokholm', 'Oslo', 'Kopenhag', 'Helsinki']),
                'correct_answer' => 1,
                'difficulty' => 'medium',
                'category_id' => $categories['general']->id,
                'time' => 20,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'question' => 'Hangisi Türkiye\'nin en kalabalık şehridir?',
                'options' => json_encode(['İstanbul', 'Ankara', 'İzmir', 'Bursa']),
                'correct_answer' => 0,
                'difficulty' => 'easy',
                'category_id' => $categories['general']->id,
                'time' => 15,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'question' => 'Hangisi bir Türk yemeği değildir?',
                'options' => json_encode(['Mantı', 'Sushi', 'Baklava', 'İmambayıldı']),
                'correct_answer' => 1,
                'difficulty' => 'easy',
                'category_id' => $categories['general']->id,
                'time' => 15,
                'created_at' => now(),
                'updated_at' => now()
            ],
            
            // Bilim Soruları
            [
                'question' => 'Hangi gezegen Güneş\'e en yakındır?',
                'options' => json_encode(['Venüs', 'Merkür', 'Dünya', 'Mars']),
                'correct_answer' => 1,
                'difficulty' => 'easy',
                'category_id' => $categories['science']->id,
                'time' => 15,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'question' => 'Su molekülünün kimyasal formülü nedir?',
                'options' => json_encode(['H2O', 'CO2', 'O2', 'N2']),
                'correct_answer' => 0,
                'difficulty' => 'easy',
                'category_id' => $categories['science']->id,
                'time' => 15,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'question' => 'İnsan vücudunda kaç kemik vardır?',
                'options' => json_encode(['206', '186', '226', '246']),
                'correct_answer' => 0,
                'difficulty' => 'easy',
                'category_id' => $categories['science']->id,
                'time' => 15,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'question' => 'DNA\'nın açılımı nedir?',
                'options' => json_encode(['Deoksiribonükleik Asit', 'Deoksiriboz Nükleik Asit', 'Dihidro Nükleik Asit', 'Diribonükleik Aminoasit']),
                'correct_answer' => 0,
                'difficulty' => 'hard',
                'category_id' => $categories['science']->id,
                'time' => 30,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'question' => 'İnsan genomu yaklaşık olarak kaç gen içerir?',
                'options' => json_encode(['10,000-15,000', '20,000-25,000', '30,000-35,000', '40,000-45,000']),
                'correct_answer' => 1,
                'difficulty' => 'hard',
                'category_id' => $categories['science']->id,
                'time' => 30,
                'created_at' => now(),
                'updated_at' => now()
            ],
            
            // Tarih Soruları
            [
                'question' => 'İstanbul\'un fethi hangi yılda gerçekleşmiştir?',
                'options' => json_encode(['1453', '1473', '1517', '1389']),
                'correct_answer' => 0,
                'difficulty' => 'medium',
                'category_id' => $categories['history']->id,
                'time' => 20,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'question' => 'I. Dünya Savaşı hangi yıllar arasında gerçekleşmiştir?',
                'options' => json_encode(['1914-1918', '1939-1945', '1905-1911', '1922-1927']),
                'correct_answer' => 0,
                'difficulty' => 'medium',
                'category_id' => $categories['history']->id,
                'time' => 20,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'question' => 'Türkiye Cumhuriyeti hangi tarihte ilan edilmiştir?',
                'options' => json_encode(['29 Ekim 1923', '23 Nisan 1920', '30 Ağustos 1922', '10 Kasım 1938']),
                'correct_answer' => 0,
                'difficulty' => 'easy',
                'category_id' => $categories['history']->id,
                'time' => 15,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'question' => 'Hangi uygarlık yazıyı ilk kullanan uygarlıktır?',
                'options' => json_encode(['Sümerler', 'Mısırlılar', 'Hititler', 'Çinliler']),
                'correct_answer' => 0,
                'difficulty' => 'hard',
                'category_id' => $categories['history']->id,
                'time' => 30,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'question' => 'Osmanlı İmparatorluğu\'nun kurucusu kimdir?',
                'options' => json_encode(['Osman Bey', 'Fatih Sultan Mehmet', 'Yavuz Sultan Selim', 'Kanuni Sultan Süleyman']),
                'correct_answer' => 0,
                'difficulty' => 'easy',
                'category_id' => $categories['history']->id,
                'time' => 15,
                'created_at' => now(),
                'updated_at' => now()
            ],
            
            // Coğrafya Soruları
            [
                'question' => 'Dünya\'nın en derin okyanus çukuru hangisidir?',
                'options' => json_encode(['Java Çukuru', 'Mariana Çukuru', 'Filipin Çukuru', 'Tonga Çukuru']),
                'correct_answer' => 1,
                'difficulty' => 'hard',
                'category_id' => $categories['geography']->id,
                'time' => 30,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'question' => 'Türkiye\'nin en yüksek dağı hangisidir?',
                'options' => json_encode(['Ağrı Dağı', 'Erciyes Dağı', 'Uludağ', 'Kaçkar Dağı']),
                'correct_answer' => 0,
                'difficulty' => 'easy',
                'category_id' => $categories['geography']->id,
                'time' => 15,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'question' => 'Hangisi Türkiye\'nin komşusu değildir?',
                'options' => json_encode(['Yunanistan', 'Bulgaristan', 'Irak', 'Mısır']),
                'correct_answer' => 3,
                'difficulty' => 'medium',
                'category_id' => $categories['geography']->id,
                'time' => 20,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'question' => 'Dünya\'nın en büyük okyanusu hangisidir?',
                'options' => json_encode(['Pasifik', 'Atlantik', 'Hint', 'Arktik']),
                'correct_answer' => 0,
                'difficulty' => 'easy',
                'category_id' => $categories['geography']->id,
                'time' => 15,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'question' => 'Van Gölü hangi bölgede yer alır?',
                'options' => json_encode(['Doğu Anadolu', 'İç Anadolu', 'Karadeniz', 'Güneydoğu Anadolu']),
                'correct_answer' => 0,
                'difficulty' => 'medium',
                'category_id' => $categories['geography']->id,
                'time' => 20,
                'created_at' => now(),
                'updated_at' => now()
            ],
            
            // Spor Soruları
            [
                'question' => 'Hangisi bir programlama dili değildir?',
                'options' => json_encode(['Python', 'HTML', 'Java', 'C++']),
                'correct_answer' => 1,
                'difficulty' => 'medium',
                'category_id' => $categories['sports']->id,
                'time' => 20,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'question' => 'FIFA Dünya Kupası kaç yılda bir düzenlenir?',
                'options' => json_encode(['4', '2', '3', '5']),
                'correct_answer' => 0,
                'difficulty' => 'easy',
                'category_id' => $categories['sports']->id,
                'time' => 15,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'question' => 'Hangi spor dalında "Grand Slam" turnuvaları düzenlenir?',
                'options' => json_encode(['Tenis', 'Golf', 'Futbol', 'Basketbol']),
                'correct_answer' => 0,
                'difficulty' => 'medium',
                'category_id' => $categories['sports']->id,
                'time' => 20,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'question' => 'Bir futbol maçında her takımda kaç oyuncu sahada yer alır?',
                'options' => json_encode(['11', '10', '9', '12']),
                'correct_answer' => 0,
                'difficulty' => 'easy',
                'category_id' => $categories['sports']->id,
                'time' => 15,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'question' => 'Olimpiyat bayrağında kaç halka vardır?',
                'options' => json_encode(['5', '4', '6', '3']),
                'correct_answer' => 0,
                'difficulty' => 'easy',
                'category_id' => $categories['sports']->id,
                'time' => 15,
                'created_at' => now(),
                'updated_at' => now()
            ],
            
            // Sanat Soruları
            [
                'question' => 'Hangisi bir sürrealist ressam değildir?',
                'options' => json_encode(['Salvador Dali', 'René Magritte', 'Claude Monet', 'Max Ernst']),
                'correct_answer' => 2,
                'difficulty' => 'hard',
                'category_id' => $categories['art']->id,
                'time' => 30,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'question' => 'Mona Lisa tablosunun ressamı kimdir?',
                'options' => json_encode(['Leonardo da Vinci', 'Vincent van Gogh', 'Pablo Picasso', 'Michelangelo']),
                'correct_answer' => 0,
                'difficulty' => 'easy',
                'category_id' => $categories['art']->id,
                'time' => 15,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'question' => 'Hangisi birincil renk değildir?',
                'options' => json_encode(['Kırmızı', 'Mavi', 'Yeşil', 'Sarı']),
                'correct_answer' => 3,
                'difficulty' => 'medium',
                'category_id' => $categories['art']->id,
                'time' => 20,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'question' => 'Hangisi klasik müzik bestecisi değildir?',
                'options' => json_encode(['Elvis Presley', 'Mozart', 'Beethoven', 'Bach']),
                'correct_answer' => 0,
                'difficulty' => 'medium',
                'category_id' => $categories['art']->id,
                'time' => 20,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'question' => '"Guernica" adlı tablo hangi ressama aittir?',
                'options' => json_encode(['Pablo Picasso', 'Salvador Dali', 'Vincent van Gogh', 'Claude Monet']),
                'correct_answer' => 0,
                'difficulty' => 'hard',
                'category_id' => $categories['art']->id,
                'time' => 30,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ];

        // Insert questions into database
        DB::table('questions')->insert($questions);
    }
}
