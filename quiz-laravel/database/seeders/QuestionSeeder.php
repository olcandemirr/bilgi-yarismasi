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
            // Yeni Genel Kültür Soruları - Kolay
            [
                'question' => 'Türkiye\'nin para birimi nedir?',
                'options' => json_encode(['Dolar', 'Euro', 'Türk Lirası', 'Riyal']),
                'correct_answer' => 2,
                'difficulty' => 'easy',
                'category_id' => $categories['general']->id,
                'time' => 15,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'question' => 'Türk bayrağındaki hilal ve yıldız hangi renktedir?',
                'options' => json_encode(['Kırmızı', 'Beyaz', 'Sarı', 'Mavi']),
                'correct_answer' => 1,
                'difficulty' => 'easy',
                'category_id' => $categories['general']->id,
                'time' => 15,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'question' => 'Hangisi Türkiye\'nin resmi dilidir?',
                'options' => json_encode(['Türkçe', 'İngilizce', 'Arapça', 'Farsça']),
                'correct_answer' => 0,
                'difficulty' => 'easy',
                'category_id' => $categories['general']->id,
                'time' => 15,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'question' => 'Türkiye kaç coğrafi bölgeye ayrılmıştır?',
                'options' => json_encode(['5', '6', '7', '8']),
                'correct_answer' => 2,
                'difficulty' => 'easy',
                'category_id' => $categories['general']->id,
                'time' => 15,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'question' => 'Hangisi Türkiye\'nin milli içeceği olarak kabul edilir?',
                'options' => json_encode(['Ayran', 'Çay', 'Kahve', 'Boza']),
                'correct_answer' => 1,
                'difficulty' => 'easy',
                'category_id' => $categories['general']->id,
                'time' => 15,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'question' => 'Türkiye\'nin en büyük gölü hangisidir?',
                'options' => json_encode(['Van Gölü', 'Tuz Gölü', 'Beyşehir Gölü', 'İznik Gölü']),
                'correct_answer' => 0,
                'difficulty' => 'easy',
                'category_id' => $categories['general']->id,
                'time' => 15,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'question' => 'Hangisi Türkiye\'nin komşu ülkelerinden biri değildir?',
                'options' => json_encode(['Yunanistan', 'Bulgaristan', 'Romanya', 'İran']),
                'correct_answer' => 2,
                'difficulty' => 'easy',
                'category_id' => $categories['general']->id,
                'time' => 15,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'question' => 'Türkiye\'nin en batısındaki il hangisidir?',
                'options' => json_encode(['Çanakkale', 'İzmir', 'Edirne', 'Balıkesir']),
                'correct_answer' => 0,
                'difficulty' => 'easy',
                'category_id' => $categories['general']->id,
                'time' => 15,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'question' => 'Türkiye\'nin en doğusundaki il hangisidir?',
                'options' => json_encode(['Kars', 'Iğdır', 'Ağrı', 'Hakkari']),
                'correct_answer' => 1,
                'difficulty' => 'easy',
                'category_id' => $categories['general']->id,
                'time' => 15,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'question' => 'Türkiye\'nin plaka kodu kaçtır?',
                'options' => json_encode(['34', '90', '61', 'TR']),
                'correct_answer' => 3,
                'difficulty' => 'easy',
                'category_id' => $categories['general']->id,
                'time' => 15,
                'created_at' => now(),
                'updated_at' => now()
            ],
            // Yeni Genel Kültür Soruları - Orta
            [
                'question' => 'Türkiye\'nin ilk kadın savaş pilotu kimdir?',
                'options' => json_encode(['Sabiha Gökçen', 'Halide Edip Adıvar', 'Nene Hatun', 'Fatma Aliye']),
                'correct_answer' => 0,
                'difficulty' => 'medium',
                'category_id' => $categories['general']->id,
                'time' => 20,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'question' => 'Türkiye\'nin en uzun nehri hangisidir?',
                'options' => json_encode(['Sakarya Nehri', 'Yeşilırmak', 'Kızılırmak', 'Fırat Nehri']),
                'correct_answer' => 2,
                'difficulty' => 'medium',
                'category_id' => $categories['general']->id,
                'time' => 20,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'question' => 'Türkiye\'nin en kalabalık ikinci şehri hangisidir?',
                'options' => json_encode(['İzmir', 'Ankara', 'Bursa', 'Antalya']),
                'correct_answer' => 1,
                'difficulty' => 'medium',
                'category_id' => $categories['general']->id,
                'time' => 20,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'question' => 'Türkiye\'nin ilk yerli otomobil markası nedir?',
                'options' => json_encode(['Devrim', 'Anadol', 'TOGG', 'Tofaş']),
                'correct_answer' => 1,
                'difficulty' => 'medium',
                'category_id' => $categories['general']->id,
                'time' => 20,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'question' => 'Türkiye\'nin en yüksek ikinci dağı hangisidir?',
                'options' => json_encode(['Süphan Dağı', 'Erciyes Dağı', 'Kaçkar Dağı', 'Cilo Dağı']),
                'correct_answer' => 3,
                'difficulty' => 'medium',
                'category_id' => $categories['general']->id,
                'time' => 20,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'question' => 'Türkiye\'nin en büyük adası hangisidir?',
                'options' => json_encode(['Gökçeada', 'Bozcaada', 'Büyükada', 'Marmara Adası']),
                'correct_answer' => 0,
                'difficulty' => 'medium',
                'category_id' => $categories['general']->id,
                'time' => 20,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'question' => 'Türkiye\'nin en büyük yüzölçümüne sahip ili hangisidir?',
                'options' => json_encode(['Ankara', 'Konya', 'Sivas', 'Erzurum']),
                'correct_answer' => 1,
                'difficulty' => 'medium',
                'category_id' => $categories['general']->id,
                'time' => 20,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'question' => 'Türkiye\'nin ilk üniversitesi hangisidir?',
                'options' => json_encode(['İstanbul Üniversitesi', 'Ankara Üniversitesi', 'Atatürk Üniversitesi', 'Boğaziçi Üniversitesi']),
                'correct_answer' => 0,
                'difficulty' => 'medium',
                'category_id' => $categories['general']->id,
                'time' => 20,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'question' => 'Türkiye\'nin ilk Nobel ödülü alan vatandaşı kimdir?',
                'options' => json_encode(['Orhan Pamuk', 'Aziz Sancar', 'Yaşar Kemal', 'Nazım Hikmet']),
                'correct_answer' => 0,
                'difficulty' => 'medium',
                'category_id' => $categories['general']->id,
                'time' => 20,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'question' => 'Türkiye\'nin en büyük ikinci gölü hangisidir?',
                'options' => json_encode(['Tuz Gölü', 'Beyşehir Gölü', 'Eğirdir Gölü', 'İznik Gölü']),
                'correct_answer' => 0,
                'difficulty' => 'medium',
                'category_id' => $categories['general']->id,
                'time' => 20,
                'created_at' => now(),
                'updated_at' => now()
            ],
            // Yeni Genel Kültür Soruları - Zor
            [
                'question' => 'Türkiye\'nin en az nüfusa sahip ili hangisidir?',
                'options' => json_encode(['Bayburt', 'Tunceli', 'Ardahan', 'Kilis']),
                'correct_answer' => 0,
                'difficulty' => 'hard',
                'category_id' => $categories['general']->id,
                'time' => 30,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'question' => 'Türkiye\'nin en derin gölü hangisidir?',
                'options' => json_encode(['Van Gölü', 'Hazar Gölü', 'Salda Gölü', 'Nemrut Gölü']),
                'correct_answer' => 2,
                'difficulty' => 'hard',
                'category_id' => $categories['general']->id,
                'time' => 30,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'question' => 'Türkiye\'nin en uzun kıyı şeridine sahip denizi hangisidir?',
                'options' => json_encode(['Karadeniz', 'Akdeniz', 'Ege Denizi', 'Marmara Denizi']),
                'correct_answer' => 1,
                'difficulty' => 'hard',
                'category_id' => $categories['general']->id,
                'time' => 30,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'question' => 'Türkiye\'nin en yüksek barajı hangisidir?',
                'options' => json_encode(['Atatürk Barajı', 'Keban Barajı', 'Deriner Barajı', 'Berke Barajı']),
                'correct_answer' => 2,
                'difficulty' => 'hard',
                'category_id' => $categories['general']->id,
                'time' => 30,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'question' => 'Türkiye\'nin en eski yerleşim yeri olarak kabul edilen yer neresidir?',
                'options' => json_encode(['Çatalhöyük', 'Göbeklitepe', 'Truva', 'Efes']),
                'correct_answer' => 1,
                'difficulty' => 'hard',
                'category_id' => $categories['general']->id,
                'time' => 30,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'question' => 'Türkiye\'nin en büyük kanyonu hangisidir?',
                'options' => json_encode(['Köprülü Kanyon', 'Valla Kanyonu', 'Ulubey Kanyonu', 'Saklıkent Kanyonu']),
                'correct_answer' => 2,
                'difficulty' => 'hard',
                'category_id' => $categories['general']->id,
                'time' => 30,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'question' => 'Türkiye\'nin en büyük mağarası hangisidir?',
                'options' => json_encode(['Damlataş Mağarası', 'Dupnisa Mağarası', 'Pınargözü Mağarası', 'Karaca Mağarası']),
                'correct_answer' => 2,
                'difficulty' => 'hard',
                'category_id' => $categories['general']->id,
                'time' => 30,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'question' => 'Türkiye\'nin en yüksek şelalesi hangisidir?',
                'options' => json_encode(['Tortum Şelalesi', 'Manavgat Şelalesi', 'Kurşunlu Şelalesi', 'Düden Şelalesi']),
                'correct_answer' => 0,
                'difficulty' => 'hard',
                'category_id' => $categories['general']->id,
                'time' => 30,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'question' => 'Türkiye\'nin en büyük ikinci ihracat kalemi nedir?',
                'options' => json_encode(['Tekstil', 'Otomotiv', 'Makine', 'Kimyasal Ürünler']),
                'correct_answer' => 0,
                'difficulty' => 'hard',
                'category_id' => $categories['general']->id,
                'time' => 30,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'question' => 'Türkiye\'nin en büyük yeraltı şehri hangisidir?',
                'options' => json_encode(['Derinkuyu', 'Kaymaklı', 'Özkonak', 'Tatlarin']),
                'correct_answer' => 0,
                'difficulty' => 'hard',
                'category_id' => $categories['general']->id,
                'time' => 30,
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
            // Yeni Bilim Soruları - Kolay
            [
                'question' => 'Periyodik tablodaki elementlerin sayısı kaçtır?',
                'options' => json_encode(['92', '108', '118', '120']),
                'correct_answer' => 2,
                'difficulty' => 'easy',
                'category_id' => $categories['science']->id,
                'time' => 15,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'question' => 'İnsan vücudundaki en büyük organ hangisidir?',
                'options' => json_encode(['Karaciğer', 'Beyin', 'Deri', 'Kalp']),
                'correct_answer' => 2,
                'difficulty' => 'easy',
                'category_id' => $categories['science']->id,
                'time' => 15,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'question' => 'Güneş sistemindeki en büyük gezegen hangisidir?',
                'options' => json_encode(['Dünya', 'Jüpiter', 'Satürn', 'Neptün']),
                'correct_answer' => 1,
                'difficulty' => 'easy',
                'category_id' => $categories['science']->id,
                'time' => 15,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'question' => 'Işık bir yılda kaç kilometre yol alır?',
                'options' => json_encode(['9.5 milyon km', '9.5 milyar km', '9.5 trilyon km', '9.5 katrilyon km']),
                'correct_answer' => 2,
                'difficulty' => 'easy',
                'category_id' => $categories['science']->id,
                'time' => 15,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'question' => 'Suyun kaynama noktası kaç derecedir?',
                'options' => json_encode(['90°C', '100°C', '110°C', '120°C']),
                'correct_answer' => 1,
                'difficulty' => 'easy',
                'category_id' => $categories['science']->id,
                'time' => 15,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'question' => 'İnsan vücudundaki en büyük kemik hangisidir?',
                'options' => json_encode(['Kafatası', 'Uyluk kemiği (Femur)', 'Kaval kemiği (Tibia)', 'Omurga']),
                'correct_answer' => 1,
                'difficulty' => 'easy',
                'category_id' => $categories['science']->id,
                'time' => 15,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'question' => 'Hangisi bir element değildir?',
                'options' => json_encode(['Oksijen', 'Hidrojen', 'Su', 'Karbon']),
                'correct_answer' => 2,
                'difficulty' => 'easy',
                'category_id' => $categories['science']->id,
                'time' => 15,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'question' => 'Dünya\'nın en yakın komşu gezegeni hangisidir?',
                'options' => json_encode(['Mars', 'Venüs', 'Merkür', 'Jüpiter']),
                'correct_answer' => 1,
                'difficulty' => 'easy',
                'category_id' => $categories['science']->id,
                'time' => 15,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'question' => 'İnsan vücudundaki kan hücrelerini üreten organ hangisidir?',
                'options' => json_encode(['Karaciğer', 'Dalak', 'Kemik iliği', 'Böbrekler']),
                'correct_answer' => 2,
                'difficulty' => 'easy',
                'category_id' => $categories['science']->id,
                'time' => 15,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'question' => 'Hangisi bir memeli değildir?',
                'options' => json_encode(['Yunus', 'Yarasa', 'Penguen', 'Kanguru']),
                'correct_answer' => 2,
                'difficulty' => 'easy',
                'category_id' => $categories['science']->id,
                'time' => 15,
                'created_at' => now(),
                'updated_at' => now()
            ],
            // Yeni Bilim Soruları - Orta
            [
                'question' => 'Atom numarası 6 olan element hangisidir?',
                'options' => json_encode(['Oksijen', 'Karbon', 'Azot', 'Helyum']),
                'correct_answer' => 1,
                'difficulty' => 'medium',
                'category_id' => $categories['science']->id,
                'time' => 20,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'question' => 'Hangisi bir elektromanyetik dalga değildir?',
                'options' => json_encode(['Radyo dalgaları', 'X-ışınları', 'Ses dalgaları', 'Gama ışınları']),
                'correct_answer' => 2,
                'difficulty' => 'medium',
                'category_id' => $categories['science']->id,
                'time' => 20,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'question' => 'İnsan vücudundaki en küçük kemik hangisidir?',
                'options' => json_encode(['Üzengi kemiği', 'Çekiç kemiği', 'Örs kemiği', 'Kuyruk sokumu']),
                'correct_answer' => 0,
                'difficulty' => 'medium',
                'category_id' => $categories['science']->id,
                'time' => 20,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'question' => 'Hangisi bir izotop değildir?',
                'options' => json_encode(['Döteryum', 'Trityum', 'Proton', 'Karbon-14']),
                'correct_answer' => 2,
                'difficulty' => 'medium',
                'category_id' => $categories['science']->id,
                'time' => 20,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'question' => 'Hangisi bir asit değildir?',
                'options' => json_encode(['Hidroklorik asit', 'Sülfürik asit', 'Sodyum hidroksit', 'Nitrik asit']),
                'correct_answer' => 2,
                'difficulty' => 'medium',
                'category_id' => $categories['science']->id,
                'time' => 20,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'question' => 'Hangisi bir bileşik değildir?',
                'options' => json_encode(['Su (H2O)', 'Karbondioksit (CO2)', 'Oksijen (O2)', 'Amonyak (NH3)']),
                'correct_answer' => 2,
                'difficulty' => 'medium',
                'category_id' => $categories['science']->id,
                'time' => 20,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'question' => 'Hangisi bir virüs hastalığı değildir?',
                'options' => json_encode(['Grip', 'AIDS', 'Sıtma', 'Hepatit']),
                'correct_answer' => 2,
                'difficulty' => 'medium',
                'category_id' => $categories['science']->id,
                'time' => 20,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'question' => 'Hangisi bir omurgasız hayvan değildir?',
                'options' => json_encode(['Ahtapot', 'Yengeç', 'Kurbağa', 'Akrep']),
                'correct_answer' => 2,
                'difficulty' => 'medium',
                'category_id' => $categories['science']->id,
                'time' => 20,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'question' => 'Hangisi bir solunum sistemi hastalığı değildir?',
                'options' => json_encode(['Astım', 'Bronşit', 'Ülser', 'Zatürre']),
                'correct_answer' => 2,
                'difficulty' => 'medium',
                'category_id' => $categories['science']->id,
                'time' => 20,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'question' => 'Hangisi bir sindirim enzimi değildir?',
                'options' => json_encode(['Amilaz', 'Pepsin', 'İnsülin', 'Lipaz']),
                'correct_answer' => 2,
                'difficulty' => 'medium',
                'category_id' => $categories['science']->id,
                'time' => 20,
                'created_at' => now(),
                'updated_at' => now()
            ],
            // Yeni Bilim Soruları - Zor
            [
                'question' => 'Hangisi bir kuantum parçacığı değildir?',
                'options' => json_encode(['Foton', 'Nötron', 'Molekül', 'Kuark']),
                'correct_answer' => 2,
                'difficulty' => 'hard',
                'category_id' => $categories['science']->id,
                'time' => 30,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'question' => 'Hangisi bir nörotransmitter değildir?',
                'options' => json_encode(['Dopamin', 'Serotonin', 'İnsülin', 'Asetilkolin']),
                'correct_answer' => 2,
                'difficulty' => 'hard',
                'category_id' => $categories['science']->id,
                'time' => 30,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'question' => 'Hangisi bir RNA türü değildir?',
                'options' => json_encode(['mRNA', 'tRNA', 'rRNA', 'dRNA']),
                'correct_answer' => 3,
                'difficulty' => 'hard',
                'category_id' => $categories['science']->id,
                'time' => 30,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'question' => 'Hangisi bir endokrin bez değildir?',
                'options' => json_encode(['Tiroid', 'Hipofiz', 'Pankreas', 'Dalak']),
                'correct_answer' => 3,
                'difficulty' => 'hard',
                'category_id' => $categories['science']->id,
                'time' => 30,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'question' => 'Hangisi bir elektromanyetik spektrum bölgesi değildir?',
                'options' => json_encode(['Kızılötesi', 'Ultraviyole', 'Mikrodalga', 'Ultrason']),
                'correct_answer' => 3,
                'difficulty' => 'hard',
                'category_id' => $categories['science']->id,
                'time' => 30,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'question' => 'Hangisi bir termodinamik yasa değildir?',
                'options' => json_encode(['Enerjinin korunumu yasası', 'Entropi yasası', 'Görelilik yasası', 'Sıfırıncı yasa']),
                'correct_answer' => 2,
                'difficulty' => 'hard',
                'category_id' => $categories['science']->id,
                'time' => 30,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'question' => 'Hangisi bir genetik hastalık değildir?',
                'options' => json_encode(['Down sendromu', 'Hemofili', 'Kistik fibrozis', 'Tetanoz']),
                'correct_answer' => 3,
                'difficulty' => 'hard',
                'category_id' => $categories['science']->id,
                'time' => 30,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'question' => 'Hangisi bir kimyasal bağ türü değildir?',
                'options' => json_encode(['İyonik bağ', 'Kovalent bağ', 'Metalik bağ', 'Kuantum bağı']),
                'correct_answer' => 3,
                'difficulty' => 'hard',
                'category_id' => $categories['science']->id,
                'time' => 30,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'question' => 'Hangisi bir hücre organeli değildir?',
                'options' => json_encode(['Mitokondri', 'Golgi aygıtı', 'Ribozom', 'Nöron']),
                'correct_answer' => 3,
                'difficulty' => 'hard',
                'category_id' => $categories['science']->id,
                'time' => 30,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'question' => 'Hangisi bir fizik sabiti değildir?',
                'options' => json_encode(['Planck sabiti', 'Avogadro sabiti', 'Boltzmann sabiti', 'Einstein sabiti']),
                'correct_answer' => 3,
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
            
            // Yeni Coğrafya Soruları - Kolay
            [
                'question' => 'Türkiye\'nin en büyük şehri hangisidir?',
                'options' => json_encode(['Ankara', 'İstanbul', 'İzmir', 'Bursa']),
                'correct_answer' => 1,
                'difficulty' => 'easy',
                'category_id' => $categories['geography']->id,
                'time' => 15,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'question' => 'Türkiye kaç denize kıyısı vardır?',
                'options' => json_encode(['2', '3', '4', '5']),
                'correct_answer' => 2,
                'difficulty' => 'easy',
                'category_id' => $categories['geography']->id,
                'time' => 15,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'question' => 'Hangisi Türkiye\'nin denizlerinden biri değildir?',
                'options' => json_encode(['Karadeniz', 'Ege Denizi', 'Akdeniz', 'Hazar Denizi']),
                'correct_answer' => 3,
                'difficulty' => 'easy',
                'category_id' => $categories['geography']->id,
                'time' => 15,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'question' => 'Türkiye\'nin en kalabalık ikinci şehri hangisidir?',
                'options' => json_encode(['Ankara', 'İzmir', 'Bursa', 'Antalya']),
                'correct_answer' => 0,
                'difficulty' => 'easy',
                'category_id' => $categories['geography']->id,
                'time' => 15,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'question' => 'Türkiye\'nin en batısındaki il hangisidir?',
                'options' => json_encode(['Çanakkale', 'İzmir', 'Edirne', 'Balıkesir']),
                'correct_answer' => 0,
                'difficulty' => 'easy',
                'category_id' => $categories['geography']->id,
                'time' => 15,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'question' => 'Türkiye\'nin en doğusundaki il hangisidir?',
                'options' => json_encode(['Kars', 'Iğdır', 'Ağrı', 'Hakkari']),
                'correct_answer' => 1,
                'difficulty' => 'easy',
                'category_id' => $categories['geography']->id,
                'time' => 15,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'question' => 'Türkiye\'nin en büyük gölü hangisidir?',
                'options' => json_encode(['Van Gölü', 'Tuz Gölü', 'Beyşehir Gölü', 'İznik Gölü']),
                'correct_answer' => 0,
                'difficulty' => 'easy',
                'category_id' => $categories['geography']->id,
                'time' => 15,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'question' => 'Hangisi Türkiye\'nin komşu ülkelerinden biridir?',
                'options' => json_encode(['Romanya', 'Ukrayna', 'Bulgaristan', 'Mısır']),
                'correct_answer' => 2,
                'difficulty' => 'easy',
                'category_id' => $categories['geography']->id,
                'time' => 15,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'question' => 'Türkiye\'nin başkenti neresidir?',
                'options' => json_encode(['İstanbul', 'Ankara', 'İzmir', 'Bursa']),
                'correct_answer' => 1,
                'difficulty' => 'easy',
                'category_id' => $categories['geography']->id,
                'time' => 15,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'question' => 'Türkiye kaç coğrafi bölgeye ayrılmıştır?',
                'options' => json_encode(['5', '6', '7', '8']),
                'correct_answer' => 2,
                'difficulty' => 'easy',
                'category_id' => $categories['geography']->id,
                'time' => 15,
                'created_at' => now(),
                'updated_at' => now()
            ],
            
            // Yeni Coğrafya Soruları - Orta
            [
                'question' => 'Türkiye\'nin en uzun nehri hangisidir?',
                'options' => json_encode(['Sakarya Nehri', 'Yeşilırmak', 'Kızılırmak', 'Fırat Nehri']),
                'correct_answer' => 2,
                'difficulty' => 'medium',
                'category_id' => $categories['geography']->id,
                'time' => 20,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'question' => 'Türkiye\'nin en büyük adası hangisidir?',
                'options' => json_encode(['Gökçeada', 'Bozcaada', 'Büyükada', 'Marmara Adası']),
                'correct_answer' => 0,
                'difficulty' => 'medium',
                'category_id' => $categories['geography']->id,
                'time' => 20,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'question' => 'Türkiye\'nin en büyük yüzölçümüne sahip ili hangisidir?',
                'options' => json_encode(['Ankara', 'Konya', 'Sivas', 'Erzurum']),
                'correct_answer' => 1,
                'difficulty' => 'medium',
                'category_id' => $categories['geography']->id,
                'time' => 20,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'question' => 'Hangisi Karadeniz Bölgesi\'nde yer alan bir il değildir?',
                'options' => json_encode(['Trabzon', 'Rize', 'Samsun', 'Çankırı']),
                'correct_answer' => 3,
                'difficulty' => 'medium',
                'category_id' => $categories['geography']->id,
                'time' => 20,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'question' => 'Hangisi Ege Bölgesi\'nde yer alan bir il değildir?',
                'options' => json_encode(['İzmir', 'Aydın', 'Muğla', 'Antalya']),
                'correct_answer' => 3,
                'difficulty' => 'medium',
                'category_id' => $categories['geography']->id,
                'time' => 20,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'question' => 'Türkiye\'nin en büyük ikinci gölü hangisidir?',
                'options' => json_encode(['Tuz Gölü', 'Beyşehir Gölü', 'Eğirdir Gölü', 'İznik Gölü']),
                'correct_answer' => 0,
                'difficulty' => 'medium',
                'category_id' => $categories['geography']->id,
                'time' => 20,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'question' => 'Hangisi Türkiye\'nin en yüksek dağlarından biri değildir?',
                'options' => json_encode(['Ağrı Dağı', 'Cilo Dağı', 'Süphan Dağı', 'Uludağ']),
                'correct_answer' => 3,
                'difficulty' => 'medium',
                'category_id' => $categories['geography']->id,
                'time' => 20,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'question' => 'Türkiye\'nin en uzun kıyı şeridine sahip denizi hangisidir?',
                'options' => json_encode(['Karadeniz', 'Akdeniz', 'Ege Denizi', 'Marmara Denizi']),
                'correct_answer' => 1,
                'difficulty' => 'medium',
                'category_id' => $categories['geography']->id,
                'time' => 20,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'question' => 'Hangisi Türkiye\'nin önemli boğazlarından biri değildir?',
                'options' => json_encode(['İstanbul Boğazı', 'Çanakkale Boğazı', 'Mersin Boğazı', 'Kerç Boğazı']),
                'correct_answer' => 2,
                'difficulty' => 'medium',
                'category_id' => $categories['geography']->id,
                'time' => 20,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'question' => 'Türkiye\'nin en kalabalık üçüncü şehri hangisidir?',
                'options' => json_encode(['İzmir', 'Bursa', 'Antalya', 'Adana']),
                'correct_answer' => 0,
                'difficulty' => 'medium',
                'category_id' => $categories['geography']->id,
                'time' => 20,
                'created_at' => now(),
                'updated_at' => now()
            ],
            
            // Yeni Coğrafya Soruları - Zor
            [
                'question' => 'Türkiye\'nin en az nüfusa sahip ili hangisidir?',
                'options' => json_encode(['Bayburt', 'Tunceli', 'Ardahan', 'Kilis']),
                'correct_answer' => 0,
                'difficulty' => 'hard',
                'category_id' => $categories['geography']->id,
                'time' => 30,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'question' => 'Türkiye\'nin en derin gölü hangisidir?',
                'options' => json_encode(['Van Gölü', 'Hazar Gölü', 'Salda Gölü', 'Nemrut Gölü']),
                'correct_answer' => 2,
                'difficulty' => 'hard',
                'category_id' => $categories['geography']->id,
                'time' => 30,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'question' => 'Türkiye\'nin en yüksek barajı hangisidir?',
                'options' => json_encode(['Atatürk Barajı', 'Keban Barajı', 'Deriner Barajı', 'Berke Barajı']),
                'correct_answer' => 2,
                'difficulty' => 'hard',
                'category_id' => $categories['geography']->id,
                'time' => 30,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'question' => 'Türkiye\'nin en büyük kanyonu hangisidir?',
                'options' => json_encode(['Köprülü Kanyon', 'Valla Kanyonu', 'Ulubey Kanyonu', 'Saklıkent Kanyonu']),
                'correct_answer' => 2,
                'difficulty' => 'hard',
                'category_id' => $categories['geography']->id,
                'time' => 30,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'question' => 'Türkiye\'nin en büyük mağarası hangisidir?',
                'options' => json_encode(['Damlataş Mağarası', 'Dupnisa Mağarası', 'Pınargözü Mağarası', 'Karaca Mağarası']),
                'correct_answer' => 2,
                'difficulty' => 'hard',
                'category_id' => $categories['geography']->id,
                'time' => 30,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'question' => 'Türkiye\'nin en yüksek şelalesi hangisidir?',
                'options' => json_encode(['Tortum Şelalesi', 'Manavgat Şelalesi', 'Kurşunlu Şelalesi', 'Düden Şelalesi']),
                'correct_answer' => 0,
                'difficulty' => 'hard',
                'category_id' => $categories['geography']->id,
                'time' => 30,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'question' => 'Türkiye\'nin en büyük yeraltı şehri hangisidir?',
                'options' => json_encode(['Derinkuyu', 'Kaymaklı', 'Özkonak', 'Tatlarin']),
                'correct_answer' => 0,
                'difficulty' => 'hard',
                'category_id' => $categories['geography']->id,
                'time' => 30,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'question' => 'Hangisi Türkiye\'nin UNESCO Dünya Mirası Listesi\'nde yer alan bir yer değildir?',
                'options' => json_encode(['Göreme Milli Parkı', 'Pamukkale', 'Efes', 'Abant Gölü']),
                'correct_answer' => 3,
                'difficulty' => 'hard',
                'category_id' => $categories['geography']->id,
                'time' => 30,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'question' => 'Türkiye\'nin en büyük delta ovası hangisidir?',
                'options' => json_encode(['Çukurova', 'Bafra Ovası', 'Çarşamba Ovası', 'Gediz Deltası']),
                'correct_answer' => 0,
                'difficulty' => 'hard',
                'category_id' => $categories['geography']->id,
                'time' => 30,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'question' => 'Türkiye\'nin en büyük ikinci adası hangisidir?',
                'options' => json_encode(['Bozcaada', 'Marmara Adası', 'İmralı Adası', 'Büyükada']),
                'correct_answer' => 0,
                'difficulty' => 'hard',
                'category_id' => $categories['geography']->id,
                'time' => 30,
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
            
            // Yeni Spor Soruları - Kolay
            [
                'question' => 'Bir futbol maçı kaç dakika sürer?',
                'options' => json_encode(['45 dakika', '90 dakika', '120 dakika', '60 dakika']),
                'correct_answer' => 1,
                'difficulty' => 'easy',
                'category_id' => $categories['sports']->id,
                'time' => 15,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'question' => 'Basketbolda bir takımda kaç oyuncu sahada yer alır?',
                'options' => json_encode(['4', '5', '6', '7']),
                'correct_answer' => 1,
                'difficulty' => 'easy',
                'category_id' => $categories['sports']->id,
                'time' => 15,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'question' => 'Voleybolda bir takımda kaç oyuncu sahada yer alır?',
                'options' => json_encode(['5', '6', '7', '8']),
                'correct_answer' => 1,
                'difficulty' => 'easy',
                'category_id' => $categories['sports']->id,
                'time' => 15,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'question' => 'Hangisi bir kış sporu değildir?',
                'options' => json_encode(['Kayak', 'Snowboard', 'Buz Pateni', 'Sörf']),
                'correct_answer' => 3,
                'difficulty' => 'easy',
                'category_id' => $categories['sports']->id,
                'time' => 15,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'question' => 'Türkiye\'nin en çok şampiyonluk kazanan futbol takımı hangisidir?',
                'options' => json_encode(['Galatasaray', 'Fenerbahçe', 'Beşiktaş', 'Trabzonspor']),
                'correct_answer' => 0,
                'difficulty' => 'easy',
                'category_id' => $categories['sports']->id,
                'time' => 15,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'question' => 'Bir futbol kalesi kaç metre genişliğindedir?',
                'options' => json_encode(['5.5 metre', '7.32 metre', '8.5 metre', '6.5 metre']),
                'correct_answer' => 1,
                'difficulty' => 'easy',
                'category_id' => $categories['sports']->id,
                'time' => 15,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'question' => 'Hangisi bir atletizm branşı değildir?',
                'options' => json_encode(['100 metre koşu', 'Uzun atlama', 'Yüksek atlama', 'Halter']),
                'correct_answer' => 3,
                'difficulty' => 'easy',
                'category_id' => $categories['sports']->id,
                'time' => 15,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'question' => 'Bir maraton kaç kilometredir?',
                'options' => json_encode(['30.5 km', '42.195 km', '50 km', '21 km']),
                'correct_answer' => 1,
                'difficulty' => 'easy',
                'category_id' => $categories['sports']->id,
                'time' => 15,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'question' => 'Hangisi bir su sporu değildir?',
                'options' => json_encode(['Yüzme', 'Dalış', 'Su Topu', 'Badminton']),
                'correct_answer' => 3,
                'difficulty' => 'easy',
                'category_id' => $categories['sports']->id,
                'time' => 15,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'question' => 'Bir tenis maçında kaç set kazanan oyuncu maçı kazanır?',
                'options' => json_encode(['1', '2', '3', '4']),
                'correct_answer' => 2,
                'difficulty' => 'easy',
                'category_id' => $categories['sports']->id,
                'time' => 15,
                'created_at' => now(),
                'updated_at' => now()
            ],
            
            // Yeni Spor Soruları - Orta
            [
                'question' => 'Futbolda "hat-trick" ne anlama gelir?',
                'options' => json_encode(['Bir maçta 3 gol atmak', 'Bir maçta 3 asist yapmak', 'Üç maç üst üste gol atmak', 'Üç farklı pozisyonda oynamak']),
                'correct_answer' => 0,
                'difficulty' => 'medium',
                'category_id' => $categories['sports']->id,
                'time' => 20,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'question' => 'Basketbolda "triple-double" ne anlama gelir?',
                'options' => json_encode(['Üç sayılık atışta %100 isabet', 'Bir maçta üç farklı kategoride çift haneli sayıya ulaşmak', 'Üç periyotta da en az 10 sayı atmak', 'Üç maç üst üste 20+ sayı atmak']),
                'correct_answer' => 1,
                'difficulty' => 'medium',
                'category_id' => $categories['sports']->id,
                'time' => 20,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'question' => 'Hangisi bir Grand Slam tenis turnuvası değildir?',
                'options' => json_encode(['Wimbledon', 'Roland Garros', 'US Open', 'Madrid Open']),
                'correct_answer' => 3,
                'difficulty' => 'medium',
                'category_id' => $categories['sports']->id,
                'time' => 20,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'question' => 'Futbolda "offside" (ofsayt) kuralı ne zaman getirilmiştir?',
                'options' => json_encode(['1863', '1883', '1925', '1947']),
                'correct_answer' => 0,
                'difficulty' => 'medium',
                'category_id' => $categories['sports']->id,
                'time' => 20,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'question' => 'Olimpiyat oyunları kaç yılda bir düzenlenir?',
                'options' => json_encode(['2 yıl', '3 yıl', '4 yıl', '5 yıl']),
                'correct_answer' => 2,
                'difficulty' => 'medium',
                'category_id' => $categories['sports']->id,
                'time' => 20,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'question' => 'Hangisi bir Formula 1 takımı değildir?',
                'options' => json_encode(['Ferrari', 'Mercedes', 'Red Bull', 'Yamaha']),
                'correct_answer' => 3,
                'difficulty' => 'medium',
                'category_id' => $categories['sports']->id,
                'time' => 20,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'question' => 'Türkiye\'nin ilk olimpiyat madalyasını kazandığı branş hangisidir?',
                'options' => json_encode(['Güreş', 'Boks', 'Halter', 'Atletizm']),
                'correct_answer' => 0,
                'difficulty' => 'medium',
                'category_id' => $categories['sports']->id,
                'time' => 20,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'question' => 'NBA\'de bir çeyrek kaç dakika sürer?',
                'options' => json_encode(['10 dakika', '12 dakika', '15 dakika', '20 dakika']),
                'correct_answer' => 1,
                'difficulty' => 'medium',
                'category_id' => $categories['sports']->id,
                'time' => 20,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'question' => 'Hangisi bir golf terimi değildir?',
                'options' => json_encode(['Birdie', 'Eagle', 'Bogey', 'Smash']),
                'correct_answer' => 3,
                'difficulty' => 'medium',
                'category_id' => $categories['sports']->id,
                'time' => 20,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'question' => 'Bir futbol sahası yaklaşık olarak kaç metre uzunluğundadır?',
                'options' => json_encode(['80-90 metre', '90-100 metre', '100-110 metre', '110-120 metre']),
                'correct_answer' => 2,
                'difficulty' => 'medium',
                'category_id' => $categories['sports']->id,
                'time' => 20,
                'created_at' => now(),
                'updated_at' => now()
            ],
            
            // Yeni Spor Soruları - Zor
            [
                'question' => 'Futbolda "Ballon d\'Or" (Altın Top) ödülünü en çok kazanan oyuncu kimdir?',
                'options' => json_encode(['Lionel Messi', 'Cristiano Ronaldo', 'Johan Cruyff', 'Michel Platini']),
                'correct_answer' => 0,
                'difficulty' => 'hard',
                'category_id' => $categories['sports']->id,
                'time' => 30,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'question' => 'Hangisi bir biatlon yarışmasında yer alan disiplinlerden biri değildir?',
                'options' => json_encode(['Kayaklı koşu', 'Tüfekle atış', 'Paten', 'Kızak']),
                'correct_answer' => 3,
                'difficulty' => 'hard',
                'category_id' => $categories['sports']->id,
                'time' => 30,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'question' => 'Bir rugby takımında kaç oyuncu sahada yer alır?',
                'options' => json_encode(['11', '13', '15', '17']),
                'correct_answer' => 2,
                'difficulty' => 'hard',
                'category_id' => $categories['sports']->id,
                'time' => 30,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'question' => 'Hangisi bir eskrim kılıcı türü değildir?',
                'options' => json_encode(['Flöre', 'Epe', 'Sabre', 'Katana']),
                'correct_answer' => 3,
                'difficulty' => 'hard',
                'category_id' => $categories['sports']->id,
                'time' => 30,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'question' => 'Decathlon (Dekathlon) yarışmasında kaç farklı atletizm disiplini yer alır?',
                'options' => json_encode(['7', '8', '10', '12']),
                'correct_answer' => 2,
                'difficulty' => 'hard',
                'category_id' => $categories['sports']->id,
                'time' => 30,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'question' => 'Hangisi bir curling terimdir?',
                'options' => json_encode(['Strike', 'Hammer', 'Ace', 'Fault']),
                'correct_answer' => 1,
                'difficulty' => 'hard',
                'category_id' => $categories['sports']->id,
                'time' => 30,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'question' => 'Tour de France bisiklet yarışı kaç etaptan oluşur?',
                'options' => json_encode(['15-18', '18-21', '21-24', '24-27']),
                'correct_answer' => 1,
                'difficulty' => 'hard',
                'category_id' => $categories['sports']->id,
                'time' => 30,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'question' => 'Hangisi bir jimnastik aleti değildir?',
                'options' => json_encode(['Paralel bar', 'Denge tahtası', 'Atlama masası', 'Trambulin']),
                'correct_answer' => 1,
                'difficulty' => 'hard',
                'category_id' => $categories['sports']->id,
                'time' => 30,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'question' => 'Bir kriket takımında kaç oyuncu yer alır?',
                'options' => json_encode(['9', '11', '13', '15']),
                'correct_answer' => 1,
                'difficulty' => 'hard',
                'category_id' => $categories['sports']->id,
                'time' => 30,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'question' => 'Hangisi bir okçuluk hedef mesafesi değildir?',
                'options' => json_encode(['50 metre', '70 metre', '90 metre', '110 metre']),
                'correct_answer' => 3,
                'difficulty' => 'hard',
                'category_id' => $categories['sports']->id,
                'time' => 30,
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
            ],
            
            // Yeni Tarih Soruları - Kolay
            [
                'question' => 'Kurtuluş Savaşı hangi antlaşma ile sona ermiştir?',
                'options' => json_encode(['Sevr Antlaşması', 'Lozan Antlaşması', 'Mondros Ateşkes Antlaşması', 'Ankara Antlaşması']),
                'correct_answer' => 1,
                'difficulty' => 'easy',
                'category_id' => $categories['history']->id,
                'time' => 15,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'question' => 'Türkiye\'nin ilk cumhurbaşkanı kimdir?',
                'options' => json_encode(['Mustafa Kemal Atatürk', 'İsmet İnönü', 'Fevzi Çakmak', 'Kazım Karabekir']),
                'correct_answer' => 0,
                'difficulty' => 'easy',
                'category_id' => $categories['history']->id,
                'time' => 15,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'question' => 'Osmanlı İmparatorluğu kaç yıl hüküm sürmüştür?',
                'options' => json_encode(['400 yıl', '500 yıl', '600 yıl', '700 yıl']),
                'correct_answer' => 2,
                'difficulty' => 'easy',
                'category_id' => $categories['history']->id,
                'time' => 15,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'question' => 'Türkiye\'de çok partili hayata geçiş hangi yılda olmuştur?',
                'options' => json_encode(['1923', '1938', '1946', '1950']),
                'correct_answer' => 2,
                'difficulty' => 'easy',
                'category_id' => $categories['history']->id,
                'time' => 15,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'question' => 'Türk kadınlarına seçme ve seçilme hakkı hangi yılda verilmiştir?',
                'options' => json_encode(['1923', '1930', '1934', '1938']),
                'correct_answer' => 2,
                'difficulty' => 'easy',
                'category_id' => $categories['history']->id,
                'time' => 15,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'question' => 'Hangisi Atatürk\'ün soyadı kanunundan önce kullandığı isimlerden biri değildir?',
                'options' => json_encode(['Mustafa Kemal', 'Gazi Mustafa Kemal', 'Mustafa Kemal Paşa', 'Kemal Atatürk']),
                'correct_answer' => 3,
                'difficulty' => 'easy',
                'category_id' => $categories['history']->id,
                'time' => 15,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'question' => 'Türkiye\'nin NATO\'ya üye olduğu yıl hangisidir?',
                'options' => json_encode(['1945', '1952', '1960', '1974']),
                'correct_answer' => 1,
                'difficulty' => 'easy',
                'category_id' => $categories['history']->id,
                'time' => 15,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'question' => 'Hangisi Osmanlı İmparatorluğu\'nun kurulduğu şehirdir?',
                'options' => json_encode(['Bursa', 'Söğüt', 'Konya', 'Edirne']),
                'correct_answer' => 1,
                'difficulty' => 'easy',
                'category_id' => $categories['history']->id,
                'time' => 15,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'question' => 'Türkiye\'nin ilk anayasası hangi yılda ilan edilmiştir?',
                'options' => json_encode(['1876', '1921', '1924', '1961']),
                'correct_answer' => 1,
                'difficulty' => 'easy',
                'category_id' => $categories['history']->id,
                'time' => 15,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'question' => 'Hangisi Türkiye Cumhuriyeti\'nin kuruluşunda kabul edilen ilkelerden biri değildir?',
                'options' => json_encode(['Cumhuriyetçilik', 'Milliyetçilik', 'Laiklik', 'Sosyalizm']),
                'correct_answer' => 3,
                'difficulty' => 'easy',
                'category_id' => $categories['history']->id,
                'time' => 15,
                'created_at' => now(),
                'updated_at' => now()
            ],
            // Yeni Tarih Soruları - Orta
            [
                'question' => 'Osmanlı İmparatorluğu\'nun en uzun süre tahtta kalan padişahı kimdir?',
                'options' => json_encode(['Kanuni Sultan Süleyman', 'Fatih Sultan Mehmet', 'Yavuz Sultan Selim', 'II. Abdülhamid']),
                'correct_answer' => 0,
                'difficulty' => 'medium',
                'category_id' => $categories['history']->id,
                'time' => 20,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'question' => 'Türkiye\'de Latin alfabesine geçiş hangi yılda olmuştur?',
                'options' => json_encode(['1923', '1926', '1928', '1930']),
                'correct_answer' => 2,
                'difficulty' => 'medium',
                'category_id' => $categories['history']->id,
                'time' => 20,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'question' => 'Osmanlı İmparatorluğu\'nun son padişahı kimdir?',
                'options' => json_encode(['V. Mehmed Reşad', 'VI. Mehmed Vahideddin', 'II. Abdülhamid', 'Abdülmecid Efendi']),
                'correct_answer' => 1,
                'difficulty' => 'medium',
                'category_id' => $categories['history']->id,
                'time' => 20,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'question' => 'Türkiye\'nin ilk çok partili seçimleri hangi yılda yapılmıştır?',
                'options' => json_encode(['1923', '1946', '1950', '1960']),
                'correct_answer' => 2,
                'difficulty' => 'medium',
                'category_id' => $categories['history']->id,
                'time' => 20,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'question' => 'Hangisi Osmanlı İmparatorluğu\'nun Duraklama Dönemi padişahlarından biridir?',
                'options' => json_encode(['Yavuz Sultan Selim', 'Kanuni Sultan Süleyman', 'IV. Murad', 'Fatih Sultan Mehmet']),
                'correct_answer' => 2,
                'difficulty' => 'medium',
                'category_id' => $categories['history']->id,
                'time' => 20,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'question' => 'Türkiye\'de ilk nüfus sayımı hangi yılda yapılmıştır?',
                'options' => json_encode(['1923', '1927', '1935', '1940']),
                'correct_answer' => 1,
                'difficulty' => 'medium',
                'category_id' => $categories['history']->id,
                'time' => 20,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'question' => 'Hangisi Osmanlı İmparatorluğu\'nun kuruluş döneminde fethedilen şehirlerden biri değildir?',
                'options' => json_encode(['Bursa', 'İznik', 'Edirne', 'İstanbul']),
                'correct_answer' => 3,
                'difficulty' => 'medium',
                'category_id' => $categories['history']->id,
                'time' => 20,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'question' => 'Türkiye\'nin ilk kadın bakanı kimdir?',
                'options' => json_encode(['Sabiha Gökçen', 'Halide Edip Adıvar', 'Türkan Akyol', 'Tansu Çiller']),
                'correct_answer' => 2,
                'difficulty' => 'medium',
                'category_id' => $categories['history']->id,
                'time' => 20,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'question' => 'Hangisi Osmanlı İmparatorluğu\'nun en geniş sınırlarına ulaştığı padişahtır?',
                'options' => json_encode(['Yavuz Sultan Selim', 'Kanuni Sultan Süleyman', 'Fatih Sultan Mehmet', 'II. Abdülhamid']),
                'correct_answer' => 1,
                'difficulty' => 'medium',
                'category_id' => $categories['history']->id,
                'time' => 20,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'question' => 'Türkiye\'de soyadı kanunu hangi yılda kabul edilmiştir?',
                'options' => json_encode(['1923', '1926', '1934', '1938']),
                'correct_answer' => 2,
                'difficulty' => 'medium',
                'category_id' => $categories['history']->id,
                'time' => 20,
                'created_at' => now(),
                'updated_at' => now()
            ],
            // Yeni Tarih Soruları - Zor
            [
                'question' => 'Osmanlı İmparatorluğu\'nun ilk anayasası olan Kanun-i Esasi hangi yılda ilan edilmiştir?',
                'options' => json_encode(['1839', '1856', '1876', '1908']),
                'correct_answer' => 2,
                'difficulty' => 'hard',
                'category_id' => $categories['history']->id,
                'time' => 30,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'question' => 'Türkiye\'de Köy Enstitüleri hangi yılda kurulmuştur?',
                'options' => json_encode(['1935', '1940', '1945', '1950']),
                'correct_answer' => 1,
                'difficulty' => 'hard',
                'category_id' => $categories['history']->id,
                'time' => 30,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'question' => 'Osmanlı İmparatorluğu\'nda Lale Devri hangi padişah döneminde yaşanmıştır?',
                'options' => json_encode(['III. Ahmed', 'III. Selim', 'II. Mahmud', 'I. Abdülmecid']),
                'correct_answer' => 0,
                'difficulty' => 'hard',
                'category_id' => $categories['history']->id,
                'time' => 30,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'question' => 'Türkiye\'de Varlık Vergisi hangi yılda uygulanmıştır?',
                'options' => json_encode(['1938', '1942', '1946', '1950']),
                'correct_answer' => 1,
                'difficulty' => 'hard',
                'category_id' => $categories['history']->id,
                'time' => 30,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'question' => 'Osmanlı İmparatorluğu\'nda Tanzimat Fermanı hangi padişah döneminde ilan edilmiştir?',
                'options' => json_encode(['II. Mahmud', 'Abdülmecid', 'Abdülaziz', 'II. Abdülhamid']),
                'correct_answer' => 1,
                'difficulty' => 'hard',
                'category_id' => $categories['history']->id,
                'time' => 30,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'question' => 'Türkiye\'de Demokrat Parti hangi yılda kurulmuştur?',
                'options' => json_encode(['1945', '1946', '1950', '1954']),
                'correct_answer' => 1,
                'difficulty' => 'hard',
                'category_id' => $categories['history']->id,
                'time' => 30,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'question' => 'Osmanlı İmparatorluğu\'nda Islahat Fermanı hangi yılda ilan edilmiştir?',
                'options' => json_encode(['1839', '1856', '1876', '1908']),
                'correct_answer' => 1,
                'difficulty' => 'hard',
                'category_id' => $categories['history']->id,
                'time' => 30,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'question' => 'Türkiye\'de Harf İnkılabı\'nın mimarı olarak bilinen eğitimci kimdir?',
                'options' => json_encode(['İsmail Hakkı Baltacıoğlu', 'Hasan Ali Yücel', 'İsmail Hakkı Tonguç', 'Falih Rıfkı Atay']),
                'correct_answer' => 0,
                'difficulty' => 'hard',
                'category_id' => $categories['history']->id,
                'time' => 30,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'question' => 'Osmanlı İmparatorluğu\'nda Meşrutiyet\'in ikinci kez ilan edildiği yıl hangisidir?',
                'options' => json_encode(['1876', '1889', '1908', '1918']),
                'correct_answer' => 2,
                'difficulty' => 'hard',
                'category_id' => $categories['history']->id,
                'time' => 30,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'question' => 'Türkiye\'de Köy Enstitüleri hangi yılda kapatılmıştır?',
                'options' => json_encode(['1950', '1954', '1960', '1971']),
                'correct_answer' => 0,
                'difficulty' => 'hard',
                'category_id' => $categories['history']->id,
                'time' => 30,
                'created_at' => now(),
                'updated_at' => now()
            ],
            
            // Yeni Sanat Soruları - Kolay
            [
                'question' => 'Hangisi bir müzik aleti değildir?',
                'options' => json_encode(['Keman', 'Gitar', 'Flüt', 'Palette']),
                'correct_answer' => 3,
                'difficulty' => 'easy',
                'category_id' => $categories['art']->id,
                'time' => 15,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'question' => 'Hangisi bir resim tekniği değildir?',
                'options' => json_encode(['Yağlı boya', 'Sulu boya', 'Kara kalem', 'Origami']),
                'correct_answer' => 3,
                'difficulty' => 'easy',
                'category_id' => $categories['art']->id,
                'time' => 15,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'question' => 'Hangisi bir dans türü değildir?',
                'options' => json_encode(['Bale', 'Tango', 'Vals', 'Sonat']),
                'correct_answer' => 3,
                'difficulty' => 'easy',
                'category_id' => $categories['art']->id,
                'time' => 15,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'question' => 'Hangisi bir edebi tür değildir?',
                'options' => json_encode(['Roman', 'Şiir', 'Öykü', 'Natürmort']),
                'correct_answer' => 3,
                'difficulty' => 'easy',
                'category_id' => $categories['art']->id,
                'time' => 15,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'question' => 'Hangisi bir tiyatro türü değildir?',
                'options' => json_encode(['Komedi', 'Trajedi', 'Dram', 'Senfoni']),
                'correct_answer' => 3,
                'difficulty' => 'easy',
                'category_id' => $categories['art']->id,
                'time' => 15,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'question' => 'Hangisi bir sinema türü değildir?',
                'options' => json_encode(['Korku', 'Komedi', 'Aksiyon', 'Füg']),
                'correct_answer' => 3,
                'difficulty' => 'easy',
                'category_id' => $categories['art']->id,
                'time' => 15,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'question' => 'Hangisi bir mimari üslup değildir?',
                'options' => json_encode(['Barok', 'Gotik', 'Rokoko', 'Allegro']),
                'correct_answer' => 3,
                'difficulty' => 'easy',
                'category_id' => $categories['art']->id,
                'time' => 15,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'question' => 'Hangisi bir heykel malzemesi değildir?',
                'options' => json_encode(['Mermer', 'Bronz', 'Ahşap', 'Tuval']),
                'correct_answer' => 3,
                'difficulty' => 'easy',
                'category_id' => $categories['art']->id,
                'time' => 15,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'question' => 'Hangisi bir fotoğraf tekniği değildir?',
                'options' => json_encode(['Portre', 'Manzara', 'Makro', 'Konçerto']),
                'correct_answer' => 3,
                'difficulty' => 'easy',
                'category_id' => $categories['art']->id,
                'time' => 15,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'question' => 'Hangisi bir müzik türü değildir?',
                'options' => json_encode(['Caz', 'Rock', 'Pop', 'Fresk']),
                'correct_answer' => 3,
                'difficulty' => 'easy',
                'category_id' => $categories['art']->id,
                'time' => 15,
                'created_at' => now(),
                'updated_at' => now()
            ],
            
            // Yeni Sanat Soruları - Orta
            [
                'question' => '"Çığlık" tablosunun ressamı kimdir?',
                'options' => json_encode(['Edvard Munch', 'Vincent van Gogh', 'Pablo Picasso', 'Salvador Dali']),
                'correct_answer' => 0,
                'difficulty' => 'medium',
                'category_id' => $categories['art']->id,
                'time' => 20,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'question' => 'Hangisi bir empresyonist (izlenimci) ressam değildir?',
                'options' => json_encode(['Claude Monet', 'Pierre-Auguste Renoir', 'Edgar Degas', 'Pablo Picasso']),
                'correct_answer' => 3,
                'difficulty' => 'medium',
                'category_id' => $categories['art']->id,
                'time' => 20,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'question' => 'Hangisi bir klasik müzik bestecisi değildir?',
                'options' => json_encode(['Wolfgang Amadeus Mozart', 'Ludwig van Beethoven', 'Johann Sebastian Bach', 'Louis Armstrong']),
                'correct_answer' => 3,
                'difficulty' => 'medium',
                'category_id' => $categories['art']->id,
                'time' => 20,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'question' => 'Hangisi bir opera değildir?',
                'options' => json_encode(['La Traviata', 'Carmen', 'Tosca', 'Bolero']),
                'correct_answer' => 3,
                'difficulty' => 'medium',
                'category_id' => $categories['art']->id,
                'time' => 20,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'question' => 'Hangisi bir bale eseri değildir?',
                'options' => json_encode(['Kuğu Gölü', 'Fındıkkıran', 'Uyuyan Güzel', 'Ay Işığı Sonatı']),
                'correct_answer' => 3,
                'difficulty' => 'medium',
                'category_id' => $categories['art']->id,
                'time' => 20,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'question' => 'Hangisi bir heykeltraş değildir?',
                'options' => json_encode(['Auguste Rodin', 'Michelangelo', 'Donatello', 'Gustav Klimt']),
                'correct_answer' => 3,
                'difficulty' => 'medium',
                'category_id' => $categories['art']->id,
                'time' => 20,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'question' => 'Hangisi bir mimari akım değildir?',
                'options' => json_encode(['Art Nouveau', 'Bauhaus', 'Modernizm', 'Dadaizm']),
                'correct_answer' => 3,
                'difficulty' => 'medium',
                'category_id' => $categories['art']->id,
                'time' => 20,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'question' => 'Hangisi bir tiyatro yazarı değildir?',
                'options' => json_encode(['William Shakespeare', 'Anton Çehov', 'Samuel Beckett', 'Ernest Hemingway']),
                'correct_answer' => 3,
                'difficulty' => 'medium',
                'category_id' => $categories['art']->id,
                'time' => 20,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'question' => 'Hangisi bir film yönetmeni değildir?',
                'options' => json_encode(['Alfred Hitchcock', 'Stanley Kubrick', 'Martin Scorsese', 'Andy Warhol']),
                'correct_answer' => 3,
                'difficulty' => 'medium',
                'category_id' => $categories['art']->id,
                'time' => 20,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'question' => 'Hangisi bir edebi akım değildir?',
                'options' => json_encode(['Romantizm', 'Realizm', 'Sembolizm', 'Kübizm']),
                'correct_answer' => 3,
                'difficulty' => 'medium',
                'category_id' => $categories['art']->id,
                'time' => 20,
                'created_at' => now(),
                'updated_at' => now()
            ],
            
            // Yeni Sanat Soruları - Zor
            [
                'question' => 'Hangisi bir post-empresyonist ressam değildir?',
                'options' => json_encode(['Vincent van Gogh', 'Paul Cézanne', 'Paul Gauguin', 'Claude Monet']),
                'correct_answer' => 3,
                'difficulty' => 'hard',
                'category_id' => $categories['art']->id,
                'time' => 30,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'question' => 'Hangisi bir sürrealist sanatçı değildir?',
                'options' => json_encode(['Salvador Dali', 'René Magritte', 'Max Ernst', 'Henri Matisse']),
                'correct_answer' => 3,
                'difficulty' => 'hard',
                'category_id' => $categories['art']->id,
                'time' => 30,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'question' => 'Hangisi bir ekspresyonist ressam değildir?',
                'options' => json_encode(['Edvard Munch', 'Ernst Ludwig Kirchner', 'Egon Schiele', 'Georges Seurat']),
                'correct_answer' => 3,
                'difficulty' => 'hard',
                'category_id' => $categories['art']->id,
                'time' => 30,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'question' => 'Hangisi bir kübist ressam değildir?',
                'options' => json_encode(['Pablo Picasso', 'Georges Braque', 'Juan Gris', 'Wassily Kandinsky']),
                'correct_answer' => 3,
                'difficulty' => 'hard',
                'category_id' => $categories['art']->id,
                'time' => 30,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'question' => 'Hangisi bir fovizm akımı ressamı değildir?',
                'options' => json_encode(['Henri Matisse', 'André Derain', 'Maurice de Vlaminck', 'Piet Mondrian']),
                'correct_answer' => 3,
                'difficulty' => 'hard',
                'category_id' => $categories['art']->id,
                'time' => 30,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'question' => 'Hangisi bir minimalist sanatçı değildir?',
                'options' => json_encode(['Donald Judd', 'Carl Andre', 'Sol LeWitt', 'Jackson Pollock']),
                'correct_answer' => 3,
                'difficulty' => 'hard',
                'category_id' => $categories['art']->id,
                'time' => 30,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'question' => 'Hangisi bir pop art sanatçısı değildir?',
                'options' => json_encode(['Andy Warhol', 'Roy Lichtenstein', 'Keith Haring', 'Mark Rothko']),
                'correct_answer' => 3,
                'difficulty' => 'hard',
                'category_id' => $categories['art']->id,
                'time' => 30,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'question' => 'Hangisi bir dadaist sanatçı değildir?',
                'options' => json_encode(['Marcel Duchamp', 'Man Ray', 'Hans Arp', 'Frida Kahlo']),
                'correct_answer' => 3,
                'difficulty' => 'hard',
                'category_id' => $categories['art']->id,
                'time' => 30,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'question' => 'Hangisi bir konstrüktivist sanatçı değildir?',
                'options' => json_encode(['Vladimir Tatlin', 'El Lissitzky', 'Alexander Rodchenko', 'Kazimir Malevich']),
                'correct_answer' => 3,
                'difficulty' => 'hard',
                'category_id' => $categories['art']->id,
                'time' => 30,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'question' => 'Hangisi bir soyut ekspresyonist ressam değildir?',
                'options' => json_encode(['Jackson Pollock', 'Willem de Kooning', 'Mark Rothko', 'René Magritte']),
                'correct_answer' => 3,
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
