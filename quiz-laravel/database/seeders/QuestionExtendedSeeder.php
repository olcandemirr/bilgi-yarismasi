<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class QuestionExtendedSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Get category IDs from the database
        $categories = DB::table('categories')->select('id', 'slug')->get()->keyBy('slug');

        // Array of additional questions
        $questions = [
            // Genel Kültür Soruları - 10 Soru
            [
                'question' => 'Dünyanın en kalabalık şehri hangisidir?',
                'options' => json_encode(['Tokyo', 'Delhi', 'Şanghay', 'Pekin']),
                'correct_answer' => 0,
                'difficulty' => 'medium',
                'category_id' => $categories['general']->id,
                'time' => 20,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'question' => 'Hangisi Nobel ödülü kategorilerinden biri değildir?',
                'options' => json_encode(['Fizik', 'Edebiyat', 'Matematik', 'Kimya']),
                'correct_answer' => 2,
                'difficulty' => 'medium',
                'category_id' => $categories['general']->id,
                'time' => 20,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'question' => 'Türkiye\'nin en uzun nehri hangisidir?',
                'options' => json_encode(['Kızılırmak', 'Fırat', 'Sakarya', 'Yeşilırmak']),
                'correct_answer' => 0,
                'difficulty' => 'medium',
                'category_id' => $categories['general']->id,
                'time' => 20,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'question' => 'Bir insanın kanında en çok bulunan hücre türü hangisidir?',
                'options' => json_encode(['Alyuvar', 'Akyuvar', 'Trombosit', 'Lenfosit']),
                'correct_answer' => 0,
                'difficulty' => 'medium',
                'category_id' => $categories['general']->id,
                'time' => 20,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'question' => 'Hangisi bir bakliyat değildir?',
                'options' => json_encode(['Fasulye', 'Nohut', 'Patates', 'Mercimek']),
                'correct_answer' => 2,
                'difficulty' => 'easy',
                'category_id' => $categories['general']->id,
                'time' => 15,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'question' => 'Türkiye\'nin nüfus açısından en büyük ikinci şehri hangisidir?',
                'options' => json_encode(['İzmir', 'Ankara', 'Bursa', 'Antalya']),
                'correct_answer' => 1,
                'difficulty' => 'medium',
                'category_id' => $categories['general']->id,
                'time' => 20,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'question' => 'İnsan vücudu yaklaşık olarak yüzde kaç sudan oluşur?',
                'options' => json_encode(['40-50%', '60-70%', '80-90%', '30-40%']),
                'correct_answer' => 1,
                'difficulty' => 'medium',
                'category_id' => $categories['general']->id,
                'time' => 20,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'question' => 'Türkiye\'de kaç coğrafi bölge vardır?',
                'options' => json_encode(['5', '6', '7', '8']),
                'correct_answer' => 2,
                'difficulty' => 'easy',
                'category_id' => $categories['general']->id,
                'time' => 15,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'question' => 'Hangisi Akdeniz\'e kıyısı olan bir ülke değildir?',
                'options' => json_encode(['İtalya', 'Portekiz', 'Mısır', 'Yunanistan']),
                'correct_answer' => 1,
                'difficulty' => 'medium',
                'category_id' => $categories['general']->id,
                'time' => 20,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'question' => 'Bir futbol maçında hakem kaç sarı kart gösterirse oyuncu kırmızı kart alır?',
                'options' => json_encode(['1', '2', '3', '4']),
                'correct_answer' => 1,
                'difficulty' => 'easy',
                'category_id' => $categories['general']->id,
                'time' => 15,
                'created_at' => now(),
                'updated_at' => now()
            ],
            
            // Bilim Soruları - 10 Soru
            [
                'question' => 'İnsanın kalbi günde ortalama kaç kez atar?',
                'options' => json_encode(['50,000', '100,000', '150,000', '200,000']),
                'correct_answer' => 1,
                'difficulty' => 'medium',
                'category_id' => $categories['science']->id,
                'time' => 20,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'question' => 'Işık bir yılda yaklaşık kaç kilometre yol alır?',
                'options' => json_encode(['9.5 milyon km', '9.5 milyar km', '9.5 trilyon km', '950 milyon km']),
                'correct_answer' => 2,
                'difficulty' => 'hard',
                'category_id' => $categories['science']->id,
                'time' => 30,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'question' => 'Hangi element periyodik tabloda "Na" sembolü ile gösterilir?',
                'options' => json_encode(['Azot', 'Neon', 'Nikel', 'Sodyum']),
                'correct_answer' => 3,
                'difficulty' => 'medium',
                'category_id' => $categories['science']->id,
                'time' => 20,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'question' => 'Hangisi bir elektromanyetik dalga değildir?',
                'options' => json_encode(['Radyo dalgaları', 'X-ışınları', 'Ses dalgaları', 'Kızılötesi ışınlar']),
                'correct_answer' => 2,
                'difficulty' => 'hard',
                'category_id' => $categories['science']->id,
                'time' => 30,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'question' => 'Mitokondri hücrenin hangi işlevini yerine getirir?',
                'options' => json_encode(['Protein üretimi', 'Enerji üretimi', 'Hücre bölünmesi', 'DNA replikasyonu']),
                'correct_answer' => 1,
                'difficulty' => 'medium',
                'category_id' => $categories['science']->id,
                'time' => 20,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'question' => 'İnsan genomundaki kromozom sayısı kaçtır?',
                'options' => json_encode(['23', '46', '42', '48']),
                'correct_answer' => 1,
                'difficulty' => 'medium',
                'category_id' => $categories['science']->id,
                'time' => 20,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'question' => 'E=mc² formülü hangi bilim insanına aittir?',
                'options' => json_encode(['Isaac Newton', 'Albert Einstein', 'Nikola Tesla', 'Galileo Galilei']),
                'correct_answer' => 1,
                'difficulty' => 'easy',
                'category_id' => $categories['science']->id,
                'time' => 15,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'question' => 'Hangisi bir karbon bileşiği değildir?',
                'options' => json_encode(['Glikoz', 'Metan', 'Propan', 'Amonyak']),
                'correct_answer' => 3,
                'difficulty' => 'hard',
                'category_id' => $categories['science']->id,
                'time' => 30,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'question' => 'Fotosentez için gerekli olmayan madde hangisidir?',
                'options' => json_encode(['Su', 'Karbondioksit', 'Oksijen', 'Işık']),
                'correct_answer' => 2,
                'difficulty' => 'medium',
                'category_id' => $categories['science']->id,
                'time' => 20,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'question' => 'Dünya\'nın en büyük uydusu hangisidir?',
                'options' => json_encode(['Ay', 'Uluslararası Uzay İstasyonu', 'Hubble Teleskobu', 'GPS Uyduları']),
                'correct_answer' => 0,
                'difficulty' => 'easy',
                'category_id' => $categories['science']->id,
                'time' => 15,
                'created_at' => now(),
                'updated_at' => now()
            ],
            
            // Tarih Soruları - 10 Soru
            [
                'question' => 'Hangi yılda Türkiye Cumhuriyeti kurulmuştur?',
                'options' => json_encode(['1920', '1921', '1922', '1923']),
                'correct_answer' => 3,
                'difficulty' => 'easy',
                'category_id' => $categories['history']->id,
                'time' => 15,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'question' => 'II. Dünya Savaşı\'nın başlangıç tarihi hangisidir?',
                'options' => json_encode(['1935', '1939', '1941', '1945']),
                'correct_answer' => 1,
                'difficulty' => 'medium',
                'category_id' => $categories['history']->id,
                'time' => 20,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'question' => 'Amerika Birleşik Devletleri\'nin ilk başkanı kimdir?',
                'options' => json_encode(['Thomas Jefferson', 'Abraham Lincoln', 'George Washington', 'John Adams']),
                'correct_answer' => 2,
                'difficulty' => 'medium',
                'category_id' => $categories['history']->id,
                'time' => 20,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'question' => 'Fransız Devrimi hangi yıl gerçekleşmiştir?',
                'options' => json_encode(['1776', '1789', '1804', '1815']),
                'correct_answer' => 1,
                'difficulty' => 'hard',
                'category_id' => $categories['history']->id,
                'time' => 30,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'question' => 'Birinci Dünya Savaşı\'nı bitiren antlaşma hangisidir?',
                'options' => json_encode(['Versay Antlaşması', 'Lozan Antlaşması', 'Sevr Antlaşması', 'Westphalia Antlaşması']),
                'correct_answer' => 0,
                'difficulty' => 'medium',
                'category_id' => $categories['history']->id,
                'time' => 20,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'question' => 'Sovyetler Birliği hangi yıl dağılmıştır?',
                'options' => json_encode(['1989', '1990', '1991', '1993']),
                'correct_answer' => 2,
                'difficulty' => 'medium',
                'category_id' => $categories['history']->id,
                'time' => 20,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'question' => 'Hangisi Türkiye Cumhuriyeti\'nin kurucusu Mustafa Kemal Atatürk\'ün soyadı kanunu çıkmadan önceki adıdır?',
                'options' => json_encode(['Mustafa Kemal Paşa', 'Gazi Mustafa Kemal', 'Mustafa Kemal Bey', 'Kemal Bey']),
                'correct_answer' => 0,
                'difficulty' => 'medium',
                'category_id' => $categories['history']->id,
                'time' => 20,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'question' => 'İstanbul\'un fethi sırasında Osmanlı padişahı kimdi?',
                'options' => json_encode(['Yıldırım Beyazıt', 'Fatih Sultan Mehmet', 'Kanuni Sultan Süleyman', 'Yavuz Sultan Selim']),
                'correct_answer' => 1,
                'difficulty' => 'easy',
                'category_id' => $categories['history']->id,
                'time' => 15,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'question' => 'Hangi antik uygarlık piramitleriyle ünlüdür?',
                'options' => json_encode(['Mısır', 'Yunan', 'Roma', 'Aztek']),
                'correct_answer' => 0,
                'difficulty' => 'easy',
                'category_id' => $categories['history']->id,
                'time' => 15,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'question' => 'Çanakkale Savaşı hangi yıllarda gerçekleşmiştir?',
                'options' => json_encode(['1913-1914', '1914-1915', '1915-1916', '1916-1917']),
                'correct_answer' => 2,
                'difficulty' => 'medium',
                'category_id' => $categories['history']->id,
                'time' => 20,
                'created_at' => now(),
                'updated_at' => now()
            ],
            
            // Coğrafya Soruları - 5 Soru
            [
                'question' => 'Dünyanın en büyük okyanusu hangisidir?',
                'options' => json_encode(['Atlantik', 'Hint', 'Pasifik', 'Arktik']),
                'correct_answer' => 2,
                'difficulty' => 'easy',
                'category_id' => $categories['geography']->id,
                'time' => 15,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'question' => 'Avrupa\'nın en uzun nehri hangisidir?',
                'options' => json_encode(['Tuna', 'Volga', 'Ren', 'Don']),
                'correct_answer' => 1,
                'difficulty' => 'medium',
                'category_id' => $categories['geography']->id,
                'time' => 20,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'question' => 'Hangisi bir ada ülkesi değildir?',
                'options' => json_encode(['Japonya', 'Filipinler', 'Vietnam', 'Endonezya']),
                'correct_answer' => 2,
                'difficulty' => 'medium',
                'category_id' => $categories['geography']->id,
                'time' => 20,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'question' => 'Dünyanın en yüksek dağı hangisidir?',
                'options' => json_encode(['K2', 'Everest', 'Kilimanjaro', 'Mont Blanc']),
                'correct_answer' => 1,
                'difficulty' => 'easy',
                'category_id' => $categories['geography']->id,
                'time' => 15,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'question' => 'Hangi ülke kıtalar arası bir konuma sahiptir?',
                'options' => json_encode(['Rusya', 'Türkiye', 'Mısır', 'Hepsi']),
                'correct_answer' => 3,
                'difficulty' => 'medium',
                'category_id' => $categories['geography']->id,
                'time' => 20,
                'created_at' => now(),
                'updated_at' => now()
            ],
            
            // Spor Soruları - 5 Soru
            [
                'question' => 'Bir basketbol takımında kaç oyuncu sahada yer alır?',
                'options' => json_encode(['4', '5', '6', '7']),
                'correct_answer' => 1,
                'difficulty' => 'easy',
                'category_id' => $categories['sports']->id,
                'time' => 15,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'question' => 'Wimbledon hangi spor dalında düzenlenen bir turnuvadır?',
                'options' => json_encode(['Tenis', 'Golf', 'Futbol', 'Kriket']),
                'correct_answer' => 0,
                'difficulty' => 'easy',
                'category_id' => $categories['sports']->id,
                'time' => 15,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'question' => 'Tour de France hangi spor dalıyla ilgilidir?',
                'options' => json_encode(['Yüzme', 'Bisiklet', 'Koşu', 'Otomobil Yarışı']),
                'correct_answer' => 1,
                'difficulty' => 'medium',
                'category_id' => $categories['sports']->id,
                'time' => 20,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'question' => 'Voleybolda bir takım kaç oyuncudan oluşur?',
                'options' => json_encode(['4', '5', '6', '7']),
                'correct_answer' => 2,
                'difficulty' => 'medium',
                'category_id' => $categories['sports']->id,
                'time' => 20,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'question' => 'Hangisi bir Su Sporları Olimpiyat Disiplini değildir?',
                'options' => json_encode(['Yüzme', 'Su Topu', 'Dalış', 'Sörf']),
                'correct_answer' => 3,
                'difficulty' => 'hard',
                'category_id' => $categories['sports']->id,
                'time' => 30,
                'created_at' => now(),
                'updated_at' => now()
            ],
            
            // Sanat Soruları - 5 Soru
            [
                'question' => '"Çığlık" adlı tablo hangi ressamın eseridir?',
                'options' => json_encode(['Edvard Munch', 'Vincent van Gogh', 'Pablo Picasso', 'Claude Monet']),
                'correct_answer' => 0,
                'difficulty' => 'medium',
                'category_id' => $categories['art']->id,
                'time' => 20,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'question' => 'Türk Edebiyatında "Çalıkuşu" romanının yazarı kimdir?',
                'options' => json_encode(['Yaşar Kemal', 'Reşat Nuri Güntekin', 'Halide Edip Adıvar', 'Orhan Pamuk']),
                'correct_answer' => 1,
                'difficulty' => 'medium',
                'category_id' => $categories['art']->id,
                'time' => 20,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'question' => 'Hangisi bir barok dönem bestecisi değildir?',
                'options' => json_encode(['Johann Sebastian Bach', 'Antonio Vivaldi', 'Georg Friedrich Händel', 'Wolfgang Amadeus Mozart']),
                'correct_answer' => 3,
                'difficulty' => 'hard',
                'category_id' => $categories['art']->id,
                'time' => 30,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'question' => 'Aşağıdakilerden hangisi Türk sinemasının "Yeşilçam" döneminin ünlü yönetmenlerinden biridir?',
                'options' => json_encode(['Nuri Bilge Ceylan', 'Lütfi Akad', 'Ferzan Özpetek', 'Fatih Akın']),
                'correct_answer' => 1,
                'difficulty' => 'medium',
                'category_id' => $categories['art']->id,
                'time' => 20,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'question' => 'Hangisi bir heykel sanatçısı değildir?',
                'options' => json_encode(['Auguste Rodin', 'Michelangelo', 'Leonardo da Vinci', 'Alberto Giacometti']),
                'correct_answer' => 2,
                'difficulty' => 'hard',
                'category_id' => $categories['art']->id,
                'time' => 30,
                'created_at' => now(),
                'updated_at' => now()
            ],
            
            // Bilgisayar ve Teknoloji Soruları - 5 Soru (Spor kategorisine ekliyoruz)
            [
                'question' => 'Hangisi bir programlama dili değildir?',
                'options' => json_encode(['Java', 'SQL', 'Photoshop', 'Python']),
                'correct_answer' => 2,
                'difficulty' => 'medium',
                'category_id' => $categories['sports']->id,
                'time' => 20,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'question' => 'İlk kişisel bilgisayar (PC) hangi şirket tarafından piyasaya sürülmüştür?',
                'options' => json_encode(['Apple', 'IBM', 'Microsoft', 'Dell']),
                'correct_answer' => 1,
                'difficulty' => 'medium',
                'category_id' => $categories['sports']->id,
                'time' => 20,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'question' => 'HTML hangi amaçla kullanılır?',
                'options' => json_encode(['Veritabanı yönetimi', 'Web sayfaları oluşturma', 'Mobil uygulama geliştirme', 'İşletim sistemi programlama']),
                'correct_answer' => 1,
                'difficulty' => 'easy',
                'category_id' => $categories['sports']->id,
                'time' => 15,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'question' => 'Hangisi bir işletim sistemi değildir?',
                'options' => json_encode(['Windows', 'Linux', 'Oracle', 'macOS']),
                'correct_answer' => 2,
                'difficulty' => 'easy',
                'category_id' => $categories['sports']->id,
                'time' => 15,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'question' => 'Bilgisayar dünyasında "bug" ne anlama gelir?',
                'options' => json_encode(['Yazılım hatası', 'Donanım arızası', 'Bilgisayar virüsü', 'Güvenlik açığı']),
                'correct_answer' => 0,
                'difficulty' => 'easy',
                'category_id' => $categories['sports']->id,
                'time' => 15,
                'created_at' => now(),
                'updated_at' => now()
            ],
        ];

        // Insert questions into database
        DB::table('questions')->insert($questions);
    }
}
