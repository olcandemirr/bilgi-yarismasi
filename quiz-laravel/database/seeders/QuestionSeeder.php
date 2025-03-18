<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Question;
use App\Models\Category;

class QuestionSeeder extends Seeder
{
    public function run()
    {
        // Kategorileri al
        $generalCategory = Category::where('name', 'Genel Kültür')->first();
        $scienceCategory = Category::where('name', 'Bilim')->first();
        $historyCategory = Category::where('name', 'Tarih')->first();
        $geographyCategory = Category::where('name', 'Coğrafya')->first();
        $sportsCategory = Category::where('name', 'Spor')->first();
        $artCategory = Category::where('name', 'Sanat')->first();
        
        // Genel Kültür - Orta Seviye Sorular
        $generalMediumQuestions = [
            [
                'question' => 'Türkiye\'nin en kalabalık şehri hangisidir?',
                'options' => ['İstanbul', 'Ankara', 'İzmir', 'Bursa'],
                'correct_answer' => 0,
                'difficulty' => 'medium',
                'time' => 30,
                'category_id' => $generalCategory->id
            ],
            [
                'question' => 'Hangi gezegen Güneş Sisteminin en büyük gezegenidir?',
                'options' => ['Mars', 'Venüs', 'Jüpiter', 'Satürn'],
                'correct_answer' => 2,
                'difficulty' => 'medium',
                'time' => 30,
                'category_id' => $generalCategory->id
            ],
            [
                'question' => 'İnsan vücudundaki en büyük organ hangisidir?',
                'options' => ['Kalp', 'Beyin', 'Deri', 'Akciğer'],
                'correct_answer' => 2,
                'difficulty' => 'medium',
                'time' => 30,
                'category_id' => $generalCategory->id
            ],
            [
                'question' => 'Hangi element periyodik tabloda "Fe" sembolü ile gösterilir?',
                'options' => ['Demir', 'Fosfor', 'Flor', 'Fermiyum'],
                'correct_answer' => 0,
                'difficulty' => 'medium',
                'time' => 30,
                'category_id' => $generalCategory->id
            ],
            [
                'question' => 'Dünyanın en büyük okyanusu hangisidir?',
                'options' => ['Atlas Okyanusu', 'Hint Okyanusu', 'Pasifik Okyanusu', 'Arktik Okyanusu'],
                'correct_answer' => 2,
                'difficulty' => 'medium',
                'time' => 30,
                'category_id' => $generalCategory->id
            ],
            [
                'question' => 'Hangi yıl Türkiye Cumhuriyeti ilan edilmiştir?',
                'options' => ['1920', '1921', '1922', '1923'],
                'correct_answer' => 3,
                'difficulty' => 'medium',
                'time' => 30,
                'category_id' => $generalCategory->id
            ],
            [
                'question' => 'İnsan DNA\'sında kaç kromozom çifti bulunur?',
                'options' => ['21', '22', '23', '24'],
                'correct_answer' => 2,
                'difficulty' => 'medium',
                'time' => 30,
                'category_id' => $generalCategory->id
            ],
            [
                'question' => 'Hangi gezegen "Kızıl Gezegen" olarak bilinir?',
                'options' => ['Venüs', 'Mars', 'Jüpiter', 'Satürn'],
                'correct_answer' => 1,
                'difficulty' => 'medium',
                'time' => 30,
                'category_id' => $generalCategory->id
            ],
            [
                'question' => 'İnsan vücudundaki en küçük kemik hangisidir?',
                'options' => ['Kulak kemiği', 'El kemiği', 'Ayak kemiği', 'Burun kemiği'],
                'correct_answer' => 0,
                'difficulty' => 'medium',
                'time' => 30,
                'category_id' => $generalCategory->id
            ],
            [
                'question' => 'Hangi element periyodik tabloda "Au" sembolü ile gösterilir?',
                'options' => ['Gümüş', 'Altın', 'Bakır', 'Alüminyum'],
                'correct_answer' => 1,
                'difficulty' => 'medium',
                'time' => 30,
                'category_id' => $generalCategory->id
            ]
        ];

        // Genel Kültür - Zor Seviye Sorular
        $generalHardQuestions = [
            [
                'question' => 'Kuantum fiziğinde "Schrödinger\'in Kedisi" deneyi neyi açıklamaya çalışır?',
                'options' => [
                    'Kuantum süperpozisyonu',
                    'Kuantum dolanıklığı',
                    'Kuantum tünelleme',
                    'Kuantum hesaplama'
                ],
                'correct_answer' => 0,
                'difficulty' => 'hard',
                'time' => 45,
                'category_id' => $generalCategory->id
            ],
            [
                'question' => 'Hangi nörotransmitter "mutluluk hormonu" olarak bilinir?',
                'options' => ['Dopamin', 'Serotonin', 'Norepinefrin', 'Asetilkolin'],
                'correct_answer' => 1,
                'difficulty' => 'hard',
                'time' => 45,
                'category_id' => $generalCategory->id
            ],
            [
                'question' => 'DNA\'nın çift sarmal yapısını ilk kez kim keşfetmiştir?',
                'options' => [
                    'Watson ve Crick',
                    'Mendel',
                    'Darwin',
                    'Pasteur'
                ],
                'correct_answer' => 0,
                'difficulty' => 'hard',
                'time' => 45,
                'category_id' => $generalCategory->id
            ],
            [
                'question' => 'Hangi genetik hastalık "Down Sendromu" olarak bilinir?',
                'options' => [
                    'Trisomi 21',
                    'Trisomi 18',
                    'Trisomi 13',
                    'Trisomi 16'
                ],
                'correct_answer' => 0,
                'difficulty' => 'hard',
                'time' => 45,
                'category_id' => $generalCategory->id
            ],
            [
                'question' => 'Kara deliklerin olay ufkunun ötesinde ne olduğu teorik olarak bilinmemektedir. Bu durum ne olarak adlandırılır?',
                'options' => [
                    'Tekillik',
                    'Ufuk',
                    'Vakum',
                    'Boşluk'
                ],
                'correct_answer' => 0,
                'difficulty' => 'hard',
                'time' => 45,
                'category_id' => $generalCategory->id
            ],
            [
                'question' => 'Hangi element periyodik tabloda "U" sembolü ile gösterilir?',
                'options' => ['Uranyum', 'Uran', 'Uranüs', 'Uranit'],
                'correct_answer' => 0,
                'difficulty' => 'hard',
                'time' => 45,
                'category_id' => $generalCategory->id
            ],
            [
                'question' => 'İnsan genomunda kaç gen bulunmaktadır?',
                'options' => [
                    '20,000-25,000',
                    '30,000-35,000',
                    '40,000-45,000',
                    '50,000-55,000'
                ],
                'correct_answer' => 0,
                'difficulty' => 'hard',
                'time' => 45,
                'category_id' => $generalCategory->id
            ],
            [
                'question' => 'Hangi bilim insanı "Görelilik Teorisi"ni geliştirmiştir?',
                'options' => [
                    'Isaac Newton',
                    'Albert Einstein',
                    'Max Planck',
                    'Niels Bohr'
                ],
                'correct_answer' => 1,
                'difficulty' => 'hard',
                'time' => 45,
                'category_id' => $generalCategory->id
            ],
            [
                'question' => 'DNA\'nın yapısını oluşturan nükleotidler arasında kaç farklı bağlantı türü vardır?',
                'options' => ['2', '3', '4', '5'],
                'correct_answer' => 1,
                'difficulty' => 'hard',
                'time' => 45,
                'category_id' => $generalCategory->id
            ],
            [
                'question' => 'Hangi element periyodik tabloda "Hg" sembolü ile gösterilir?',
                'options' => ['Cıva', 'Gümüş', 'Altın', 'Bakır'],
                'correct_answer' => 0,
                'difficulty' => 'hard',
                'time' => 45,
                'category_id' => $generalCategory->id
            ]
        ];

        // Bilim - Orta Seviye Sorular
        $scienceMediumQuestions = [
            [
                'question' => 'Hangi gezegenin en fazla uydusu vardır?',
                'options' => ['Jüpiter', 'Satürn', 'Uranüs', 'Neptün'],
                'correct_answer' => 0,
                'difficulty' => 'medium',
                'time' => 30,
                'category_id' => $scienceCategory->id
            ],
            [
                'question' => 'İnsan vücudunda kaç kemik bulunur?',
                'options' => ['206', '207', '208', '209'],
                'correct_answer' => 0,
                'difficulty' => 'medium',
                'time' => 30,
                'category_id' => $scienceCategory->id
            ],
            [
                'question' => 'Suyun kimyasal formülü nedir?',
                'options' => ['H2O', 'CO2', 'O2', 'H2SO4'],
                'correct_answer' => 0,
                'difficulty' => 'medium',
                'time' => 30,
                'category_id' => $scienceCategory->id
            ],
            [
                'question' => 'Hangi vitamin güneş ışığı yardımıyla vücutta üretilir?',
                'options' => ['A vitamini', 'B vitamini', 'C vitamini', 'D vitamini'],
                'correct_answer' => 3,
                'difficulty' => 'medium',
                'time' => 30,
                'category_id' => $scienceCategory->id
            ],
            [
                'question' => 'Periyodik tabloda kaç element vardır?',
                'options' => ['116', '117', '118', '119'],
                'correct_answer' => 2,
                'difficulty' => 'medium',
                'time' => 30,
                'category_id' => $scienceCategory->id
            ],
            [
                'question' => 'Hangi kan grubu evrensel donördür?',
                'options' => ['A Rh+', 'B Rh-', 'AB Rh+', '0 Rh-'],
                'correct_answer' => 3,
                'difficulty' => 'medium',
                'time' => 30,
                'category_id' => $scienceCategory->id
            ],
            [
                'question' => 'Ses hangi ortamda en hızlı yayılır?',
                'options' => ['Hava', 'Su', 'Katı', 'Boşluk'],
                'correct_answer' => 2,
                'difficulty' => 'medium',
                'time' => 30,
                'category_id' => $scienceCategory->id
            ],
            [
                'question' => 'İnsan vücudundaki en büyük kas hangisidir?',
                'options' => ['Kalp kası', 'Baldır kası', 'Gluteus Maximus', 'Latissimus Dorsi'],
                'correct_answer' => 2,
                'difficulty' => 'medium',
                'time' => 30,
                'category_id' => $scienceCategory->id
            ],
            [
                'question' => 'Hangi hayvan türü memeli değildir?',
                'options' => ['Yunus', 'Yarasa', 'Penguen', 'Kanguru'],
                'correct_answer' => 2,
                'difficulty' => 'medium',
                'time' => 30,
                'category_id' => $scienceCategory->id
            ],
            [
                'question' => 'Işık bir yılda kaç kilometre yol alır?',
                'options' => ['9.5 trilyon km', '9.5 milyar km', '9.5 milyon km', '9.5 bin km'],
                'correct_answer' => 0,
                'difficulty' => 'medium',
                'time' => 30,
                'category_id' => $scienceCategory->id
            ]
        ];

        // Bilim - Zor Seviye Sorular
        $scienceHardQuestions = [
            [
                'question' => 'Hangi parçacık ışık fotonlarını taşır?',
                'options' => ['Elektron', 'Proton', 'Nötron', 'Bozon'],
                'correct_answer' => 3,
                'difficulty' => 'hard',
                'time' => 45,
                'category_id' => $scienceCategory->id
            ],
            [
                'question' => 'Hangi element periyodik tabloda en yüksek atom numarasına sahiptir?',
                'options' => ['Oganesson', 'Fermiyum', 'Rutherfordium', 'Lawrencium'],
                'correct_answer' => 0,
                'difficulty' => 'hard',
                'time' => 45,
                'category_id' => $scienceCategory->id
            ],
            [
                'question' => 'CRISPR-Cas9 hangi alanda kullanılan bir tekniktir?',
                'options' => ['Gen düzenleme', 'Nükleer fizyon', 'Kuantum hesaplama', 'Yapay zeka'],
                'correct_answer' => 0,
                'difficulty' => 'hard',
                'time' => 45,
                'category_id' => $scienceCategory->id
            ],
            [
                'question' => 'Hangi hastalık mitokondriyal DNA mutasyonlarından kaynaklanır?',
                'options' => [
                    'Leigh sendromu',
                    'Down sendromu',
                    'Huntington hastalığı',
                    'Kistik fibrozis'
                ],
                'correct_answer' => 0,
                'difficulty' => 'hard',
                'time' => 45,
                'category_id' => $scienceCategory->id
            ],
            [
                'question' => 'Kuantum dolanıklık hangi fizik alanında gözlemlenir?',
                'options' => [
                    'Kuantum mekaniği',
                    'Klasik mekanik',
                    'Termodinamik',
                    'Elektromanyetizma'
                ],
                'correct_answer' => 0,
                'difficulty' => 'hard',
                'time' => 45,
                'category_id' => $scienceCategory->id
            ],
            [
                'question' => 'Hangi protein DNA\'nın paketlenmesinde görev alır?',
                'options' => ['Histon', 'Kollajen', 'Keratin', 'Elastin'],
                'correct_answer' => 0,
                'difficulty' => 'hard',
                'time' => 45,
                'category_id' => $scienceCategory->id
            ],
            [
                'question' => 'Hangi element süperiletkenlik özelliği gösterir?',
                'options' => ['Niyobyum', 'Altın', 'Gümüş', 'Bakır'],
                'correct_answer' => 0,
                'difficulty' => 'hard',
                'time' => 45,
                'category_id' => $scienceCategory->id
            ],
            [
                'question' => 'Hangi enzim DNA replikasyonunda görev alır?',
                'options' => [
                    'DNA polimeraz',
                    'RNA polimeraz',
                    'Lipaz',
                    'Amilaz'
                ],
                'correct_answer' => 0,
                'difficulty' => 'hard',
                'time' => 45,
                'category_id' => $scienceCategory->id
            ],
            [
                'question' => 'Hangi parçacık Higgs bozonu ile etkileşime girerek kütle kazanır?',
                'options' => [
                    'W ve Z bozonları',
                    'Fotonlar',
                    'Gluonlar',
                    'Nötrinolar'
                ],
                'correct_answer' => 0,
                'difficulty' => 'hard',
                'time' => 45,
                'category_id' => $scienceCategory->id
            ],
            [
                'question' => 'PCR tekniği hangi bilim insanı tarafından geliştirilmiştir?',
                'options' => [
                    'Kary Mullis',
                    'James Watson',
                    'Francis Crick',
                    'Rosalind Franklin'
                ],
                'correct_answer' => 0,
                'difficulty' => 'hard',
                'time' => 45,
                'category_id' => $scienceCategory->id
            ]
        ];

        // Tarih - Orta Seviye Sorular
        $historyMediumQuestions = [
            [
                'question' => 'Osmanlı İmparatorluğu hangi yıl kurulmuştur?',
                'options' => ['1299', '1301', '1302', '1303'],
                'correct_answer' => 0,
                'difficulty' => 'medium',
                'time' => 30,
                'category_id' => $historyCategory->id
            ],
            [
                'question' => 'İstanbul\'un fethi hangi yıl gerçekleşmiştir?',
                'options' => ['1451', '1452', '1453', '1454'],
                'correct_answer' => 2,
                'difficulty' => 'medium',
                'time' => 30,
                'category_id' => $historyCategory->id
            ],
            [
                'question' => 'Türkiye Cumhuriyeti\'nin ilk cumhurbaşkanı kimdir?',
                'options' => [
                    'Mustafa Kemal Atatürk',
                    'İsmet İnönü',
                    'Fevzi Çakmak',
                    'Kazım Karabekir'
                ],
                'correct_answer' => 0,
                'difficulty' => 'medium',
                'time' => 30,
                'category_id' => $historyCategory->id
            ],
            [
                'question' => 'Kurtuluş Savaşı hangi yıllar arasında gerçekleşmiştir?',
                'options' => [
                    '1919-1922',
                    '1920-1923',
                    '1921-1924',
                    '1918-1921'
                ],
                'correct_answer' => 0,
                'difficulty' => 'medium',
                'time' => 30,
                'category_id' => $historyCategory->id
            ],
            [
                'question' => 'Hangi antlaşma ile Türkiye Cumhuriyeti\'nin bağımsızlığı uluslararası alanda tanınmıştır?',
                'options' => [
                    'Lozan Antlaşması',
                    'Sevr Antlaşması',
                    'Mondros Ateşkes Antlaşması',
                    'Ankara Antlaşması'
                ],
                'correct_answer' => 0,
                'difficulty' => 'medium',
                'time' => 30,
                'category_id' => $historyCategory->id
            ],
            [
                'question' => 'Türkiye\'de çok partili hayata geçiş hangi yıl olmuştur?',
                'options' => ['1945', '1946', '1947', '1948'],
                'correct_answer' => 1,
                'difficulty' => 'medium',
                'time' => 30,
                'category_id' => $historyCategory->id
            ],
            [
                'question' => 'Osmanlı İmparatorluğu\'nun son padişahı kimdir?',
                'options' => [
                    'Vahdettin',
                    'Abdülmecid',
                    'Abdülaziz',
                    'II. Abdülhamid'
                ],
                'correct_answer' => 0,
                'difficulty' => 'medium',
                'time' => 30,
                'category_id' => $historyCategory->id
            ],
            [
                'question' => 'Türk tarihinde ilk Türk-İslam devleti hangisidir?',
                'options' => [
                    'Karahanlılar',
                    'Gazneliler',
                    'Selçuklular',
                    'Uygurlar'
                ],
                'correct_answer' => 0,
                'difficulty' => 'medium',
                'time' => 30,
                'category_id' => $historyCategory->id
            ],
            [
                'question' => 'Türkiye\'de Latin alfabesine geçiş hangi yıl olmuştur?',
                'options' => ['1927', '1928', '1929', '1930'],
                'correct_answer' => 1,
                'difficulty' => 'medium',
                'time' => 30,
                'category_id' => $historyCategory->id
            ],
            [
                'question' => 'Osmanlı İmparatorluğu\'nun en uzun süre tahtta kalan padişahı kimdir?',
                'options' => [
                    'Kanuni Sultan Süleyman',
                    'Fatih Sultan Mehmet',
                    'Yavuz Sultan Selim',
                    'II. Abdülhamid'
                ],
                'correct_answer' => 0,
                'difficulty' => 'medium',
                'time' => 30,
                'category_id' => $historyCategory->id
            ]
        ];

        // Tarih - Zor Seviye Sorular
        $historyHardQuestions = [
            [
                'question' => 'Osmanlı İmparatorluğu\'nda ilk anayasa hangi yıl ilan edilmiştir?',
                'options' => ['1876', '1878', '1880', '1882'],
                'correct_answer' => 0,
                'difficulty' => 'hard',
                'time' => 45,
                'category_id' => $historyCategory->id
            ],
            [
                'question' => 'Köy Enstitüleri hangi yıl kurulmuştur?',
                'options' => ['1940', '1941', '1942', '1943'],
                'correct_answer' => 0,
                'difficulty' => 'hard',
                'time' => 45,
                'category_id' => $historyCategory->id
            ],
            [
                'question' => 'II. Meşrutiyet hangi yıl ilan edilmiştir?',
                'options' => ['1908', '1909', '1910', '1911'],
                'correct_answer' => 0,
                'difficulty' => 'hard',
                'time' => 45,
                'category_id' => $historyCategory->id
            ],
            [
                'question' => 'Türkiye\'de ilk nüfus sayımı hangi yıl yapılmıştır?',
                'options' => ['1927', '1928', '1929', '1930'],
                'correct_answer' => 0,
                'difficulty' => 'hard',
                'time' => 45,
                'category_id' => $historyCategory->id
            ],
            [
                'question' => 'Osmanlı İmparatorluğu\'nda Tanzimat Fermanı hangi yıl ilan edilmiştir?',
                'options' => ['1839', '1840', '1841', '1842'],
                'correct_answer' => 0,
                'difficulty' => 'hard',
                'time' => 45,
                'category_id' => $historyCategory->id
            ],
            [
                'question' => 'Türkiye\'de kadınlara milletvekili seçme ve seçilme hakkı hangi yıl verilmiştir?',
                'options' => ['1934', '1935', '1936', '1937'],
                'correct_answer' => 0,
                'difficulty' => 'hard',
                'time' => 45,
                'category_id' => $historyCategory->id
            ],
            [
                'question' => 'Osmanlı İmparatorluğu\'nda ilk Türkçe gazete hangisidir?',
                'options' => [
                    'Takvim-i Vekayi',
                    'Tercüman-ı Ahval',
                    'Ceride-i Havadis',
                    'Tasvir-i Efkar'
                ],
                'correct_answer' => 0,
                'difficulty' => 'hard',
                'time' => 45,
                'category_id' => $historyCategory->id
            ],
            [
                'question' => 'Türkiye\'nin NATO\'ya üyeliği hangi yıl gerçekleşmiştir?',
                'options' => ['1950', '1951', '1952', '1953'],
                'correct_answer' => 2,
                'difficulty' => 'hard',
                'time' => 45,
                'category_id' => $historyCategory->id
            ],
            [
                'question' => 'Osmanlı İmparatorluğu\'nda Islahat Fermanı hangi yıl ilan edilmiştir?',
                'options' => ['1854', '1855', '1856', '1857'],
                'correct_answer' => 2,
                'difficulty' => 'hard',
                'time' => 45,
                'category_id' => $historyCategory->id
            ],
            [
                'question' => 'Türkiye\'de ilk çok partili seçimler hangi yıl yapılmıştır?',
                'options' => ['1946', '1947', '1948', '1949'],
                'correct_answer' => 0,
                'difficulty' => 'hard',
                'time' => 45,
                'category_id' => $historyCategory->id
            ]
        ];

        // Coğrafya - Orta Seviye Sorular
        $geographyMediumQuestions = [
            [
                'question' => 'Türkiye\'nin en yüksek dağı hangisidir?',
                'options' => ['Ağrı Dağı', 'Erciyes Dağı', 'Uludağ', 'Kaçkar Dağı'],
                'correct_answer' => 0,
                'difficulty' => 'medium',
                'time' => 30,
                'category_id' => $geographyCategory->id
            ],
            [
                'question' => 'Türkiye\'nin en büyük gölü hangisidir?',
                'options' => ['Van Gölü', 'Tuz Gölü', 'Beyşehir Gölü', 'İznik Gölü'],
                'correct_answer' => 0,
                'difficulty' => 'medium',
                'time' => 30,
                'category_id' => $geographyCategory->id
            ],
            [
                'question' => 'Türkiye\'nin en uzun nehri hangisidir?',
                'options' => ['Kızılırmak', 'Fırat', 'Dicle', 'Sakarya'],
                'correct_answer' => 0,
                'difficulty' => 'medium',
                'time' => 30,
                'category_id' => $geographyCategory->id
            ],
            [
                'question' => 'Hangi ilimiz üç büyük denize kıyısı olan bölgede yer alır?',
                'options' => ['İstanbul', 'İzmir', 'Antalya', 'Çanakkale'],
                'correct_answer' => 0,
                'difficulty' => 'medium',
                'time' => 30,
                'category_id' => $geographyCategory->id
            ],
            [
                'question' => 'Türkiye\'nin en batısındaki il hangisidir?',
                'options' => ['Çanakkale', 'Edirne', 'İzmir', 'Balıkesir'],
                'correct_answer' => 0,
                'difficulty' => 'medium',
                'time' => 30,
                'category_id' => $geographyCategory->id
            ],
            [
                'question' => 'Türkiye\'nin en büyük adası hangisidir?',
                'options' => ['Gökçeada', 'Bozcaada', 'Büyükada', 'Marmara Adası'],
                'correct_answer' => 0,
                'difficulty' => 'medium',
                'time' => 30,
                'category_id' => $geographyCategory->id
            ],
            [
                'question' => 'Hangi ilimiz Karadeniz\'e kıyısı yoktur?',
                'options' => ['Çorum', 'Sinop', 'Ordu', 'Rize'],
                'correct_answer' => 0,
                'difficulty' => 'medium',
                'time' => 30,
                'category_id' => $geographyCategory->id
            ],
            [
                'question' => 'Türkiye\'nin en kalabalık ikinci şehri hangisidir?',
                'options' => ['Ankara', 'İzmir', 'Bursa', 'Antalya'],
                'correct_answer' => 0,
                'difficulty' => 'medium',
                'time' => 30,
                'category_id' => $geographyCategory->id
            ],
            [
                'question' => 'Türkiye\'nin en büyük ovası hangisidir?',
                'options' => ['Konya Ovası', 'Çukurova', 'Bafra Ovası', 'Harran Ovası'],
                'correct_answer' => 0,
                'difficulty' => 'medium',
                'time' => 30,
                'category_id' => $geographyCategory->id
            ],
            [
                'question' => 'Türkiye\'nin en soğuk ili hangisidir?',
                'options' => ['Ardahan', 'Kars', 'Erzurum', 'Ağrı'],
                'correct_answer' => 0,
                'difficulty' => 'medium',
                'time' => 30,
                'category_id' => $geographyCategory->id
            ]
        ];

        // Coğrafya - Zor Seviye Sorular
        $geographyHardQuestions = [
            [
                'question' => 'Türkiye\'nin en büyük delta ovası hangisidir?',
                'options' => ['Çarşamba Ovası', 'Bafra Ovası', 'Çukurova', 'Gediz Ovası'],
                'correct_answer' => 0,
                'difficulty' => 'hard',
                'time' => 45,
                'category_id' => $geographyCategory->id
            ],
            [
                'question' => 'Türkiye\'nin en derin gölü hangisidir?',
                'options' => ['Van Gölü', 'Hazar Gölü', 'Salda Gölü', 'Eğirdir Gölü'],
                'correct_answer' => 2,
                'difficulty' => 'hard',
                'time' => 45,
                'category_id' => $geographyCategory->id
            ],
            [
                'question' => 'Türkiye\'nin en büyük mağarası hangisidir?',
                'options' => ['Pınargözü Mağarası', 'Damlataş Mağarası', 'İnsuyu Mağarası', 'Dim Mağarası'],
                'correct_answer' => 0,
                'difficulty' => 'hard',
                'time' => 45,
                'category_id' => $geographyCategory->id
            ],
            [
                'question' => 'Türkiye\'nin en yüksek şelalesi hangisidir?',
                'options' => ['Tortum Şelalesi', 'Düden Şelalesi', 'Manavgat Şelalesi', 'Kurşunlu Şelalesi'],
                'correct_answer' => 0,
                'difficulty' => 'hard',
                'time' => 45,
                'category_id' => $geographyCategory->id
            ],
            [
                'question' => 'Türkiye\'nin en büyük ikinci gölü hangisidir?',
                'options' => ['Tuz Gölü', 'Beyşehir Gölü', 'Eğirdir Gölü', 'İznik Gölü'],
                'correct_answer' => 0,
                'difficulty' => 'hard',
                'time' => 45,
                'category_id' => $geographyCategory->id
            ],
            [
                'question' => 'Türkiye\'nin en uzun kanyonu hangisidir?',
                'options' => ['Ulubey Kanyonu', 'Valla Kanyonu', 'Köprülü Kanyon', 'Ihlara Vadisi'],
                'correct_answer' => 0,
                'difficulty' => 'hard',
                'time' => 45,
                'category_id' => $geographyCategory->id
            ],
            [
                'question' => 'Türkiye\'nin en büyük sulak alanı hangisidir?',
                'options' => ['Sultan Sazlığı', 'Manyas Gölü', 'Kızılırmak Deltası', 'Göksu Deltası'],
                'correct_answer' => 2,
                'difficulty' => 'hard',
                'time' => 45,
                'category_id' => $geographyCategory->id
            ],
            [
                'question' => 'Türkiye\'nin en büyük ikinci dağı hangisidir?',
                'options' => ['Süphan Dağı', 'Erciyes Dağı', 'Kaçkar Dağı', 'Cilo Dağı'],
                'correct_answer' => 3,
                'difficulty' => 'hard',
                'time' => 45,
                'category_id' => $geographyCategory->id
            ],
            [
                'question' => 'Türkiye\'nin en büyük yeraltı gölü hangisidir?',
                'options' => ['Altınbeşik Mağarası', 'Dim Mağarası', 'İnsuyu Mağarası', 'Damlataş Mağarası'],
                'correct_answer' => 0,
                'difficulty' => 'hard',
                'time' => 45,
                'category_id' => $geographyCategory->id
            ],
            [
                'question' => 'Türkiye\'nin en büyük ikinci akarsu havzası hangisidir?',
                'options' => ['Fırat Havzası', 'Dicle Havzası', 'Sakarya Havzası', 'Yeşilırmak Havzası'],
                'correct_answer' => 0,
                'difficulty' => 'hard',
                'time' => 45,
                'category_id' => $geographyCategory->id
            ]
        ];

        // Spor - Orta Seviye Sorular
        $sportsMediumQuestions = [
            [
                'question' => 'Türkiye\'nin en çok şampiyonluğu olan futbol takımı hangisidir?',
                'options' => ['Galatasaray', 'Fenerbahçe', 'Beşiktaş', 'Trabzonspor'],
                'correct_answer' => 0,
                'difficulty' => 'medium',
                'time' => 30,
                'category_id' => $sportsCategory->id
            ],
            [
                'question' => 'Bir futbol maçında kaç oyuncu değişikliği yapılabilir? (Normal sürede)',
                'options' => ['3', '4', '5', '6'],
                'correct_answer' => 2,
                'difficulty' => 'medium',
                'time' => 30,
                'category_id' => $sportsCategory->id
            ],
            [
                'question' => 'Basketbolda bir üçlük atış kaç puan değerindedir?',
                'options' => ['2', '3', '4', '5'],
                'correct_answer' => 1,
                'difficulty' => 'medium',
                'time' => 30,
                'category_id' => $sportsCategory->id
            ],
            [
                'question' => 'Voleybolda bir set kaç sayıda biter?',
                'options' => ['15', '21', '25', '30'],
                'correct_answer' => 2,
                'difficulty' => 'medium',
                'time' => 30,
                'category_id' => $sportsCategory->id
            ],
            [
                'question' => 'Hangi spor dalında "Grand Slam" turnuvaları düzenlenir?',
                'options' => ['Tenis', 'Golf', 'Basketbol', 'Voleybol'],
                'correct_answer' => 0,
                'difficulty' => 'medium',
                'time' => 30,
                'category_id' => $sportsCategory->id
            ],
            [
                'question' => 'Bir futbol sahası yaklaşık kaç metre uzunluğundadır?',
                'options' => ['90-120', '80-110', '70-100', '60-90'],
                'correct_answer' => 0,
                'difficulty' => 'medium',
                'time' => 30,
                'category_id' => $sportsCategory->id
            ],
            [
                'question' => 'Hangi spor dalında "hat-trick" terimi kullanılır?',
                'options' => ['Futbol', 'Basketbol', 'Voleybol', 'Tenis'],
                'correct_answer' => 0,
                'difficulty' => 'medium',
                'time' => 30,
                'category_id' => $sportsCategory->id
            ],
            [
                'question' => 'NBA\'de bir çeyrek kaç dakikadır?',
                'options' => ['10', '12', '15', '20'],
                'correct_answer' => 1,
                'difficulty' => 'medium',
                'time' => 30,
                'category_id' => $sportsCategory->id
            ],
            [
                'question' => 'Olimpiyat bayrağındaki halkaların sayısı kaçtır?',
                'options' => ['4', '5', '6', '7'],
                'correct_answer' => 1,
                'difficulty' => 'medium',
                'time' => 30,
                'category_id' => $sportsCategory->id
            ],
            [
                'question' => 'Bir maraton kaç kilometredir?',
                'options' => ['40.195', '41.195', '42.195', '43.195'],
                'correct_answer' => 2,
                'difficulty' => 'medium',
                'time' => 30,
                'category_id' => $sportsCategory->id
            ]
        ];

        // Spor - Zor Seviye Sorular
        $sportsHardQuestions = [
            [
                'question' => 'FIFA Dünya Kupası\'nı en çok kazanan ülke hangisidir?',
                'options' => ['Brezilya', 'Almanya', 'İtalya', 'Arjantin'],
                'correct_answer' => 0,
                'difficulty' => 'hard',
                'time' => 45,
                'category_id' => $sportsCategory->id
            ],
            [
                'question' => 'NBA tarihinde en çok şampiyonluk kazanan takım hangisidir?',
                'options' => [
                    'Boston Celtics',
                    'Los Angeles Lakers',
                    'Chicago Bulls',
                    'Golden State Warriors'
                ],
                'correct_answer' => 0,
                'difficulty' => 'hard',
                'time' => 45,
                'category_id' => $sportsCategory->id
            ],
            [
                'question' => 'Hangi tenisçi Grand Slam\'lerde en çok şampiyonluk kazanmıştır?',
                'options' => [
                    'Novak Djokovic',
                    'Rafael Nadal',
                    'Roger Federer',
                    'Pete Sampras'
                ],
                'correct_answer' => 0,
                'difficulty' => 'hard',
                'time' => 45,
                'category_id' => $sportsCategory->id
            ],
            [
                'question' => 'Formula 1 tarihinde en çok dünya şampiyonluğu kazanan pilot kimdir?',
                'options' => [
                    'Lewis Hamilton',
                    'Michael Schumacher',
                    'Sebastian Vettel',
                    'Ayrton Senna'
                ],
                'correct_answer' => 1,
                'difficulty' => 'hard',
                'time' => 45,
                'category_id' => $sportsCategory->id
            ],
            [
                'question' => 'Olimpiyat oyunlarında en çok altın madalya kazanan sporcu kimdir?',
                'options' => [
                    'Michael Phelps',
                    'Usain Bolt',
                    'Larisa Latynina',
                    'Carl Lewis'
                ],
                'correct_answer' => 0,
                'difficulty' => 'hard',
                'time' => 45,
                'category_id' => $sportsCategory->id
            ],
            [
                'question' => 'NBA\'de bir sezonda en çok sayı ortalamasına sahip olan oyuncu kimdir?',
                'options' => [
                    'Wilt Chamberlain',
                    'Michael Jordan',
                    'Kobe Bryant',
                    'LeBron James'
                ],
                'correct_answer' => 0,
                'difficulty' => 'hard',
                'time' => 45,
                'category_id' => $sportsCategory->id
            ],
            [
                'question' => 'Futbolda "Ballon d\'Or" ödülünü en çok kazanan oyuncu kimdir?',
                'options' => [
                    'Lionel Messi',
                    'Cristiano Ronaldo',
                    'Johan Cruyff',
                    'Michel Platini'
                ],
                'correct_answer' => 0,
                'difficulty' => 'hard',
                'time' => 45,
                'category_id' => $sportsCategory->id
            ],
            [
                'question' => 'Hangi ülke Dünya Kupası\'nda ev sahibi olarak şampiyon olmuştur?',
                'options' => [
                    'Fransa',
                    'İngiltere',
                    'Almanya',
                    'Brezilya'
                ],
                'correct_answer' => 0,
                'difficulty' => 'hard',
                'time' => 45,
                'category_id' => $sportsCategory->id
            ],
            [
                'question' => 'NBA\'de "triple-double" rekorunu elinde bulunduran oyuncu kimdir?',
                'options' => [
                    'Russell Westbrook',
                    'Oscar Robertson',
                    'Magic Johnson',
                    'LeBron James'
                ],
                'correct_answer' => 0,
                'difficulty' => 'hard',
                'time' => 45,
                'category_id' => $sportsCategory->id
            ],
            [
                'question' => 'Hangi boksör "The Greatest" (En Büyük) lakabıyla tanınır?',
                'options' => [
                    'Muhammad Ali',
                    'Mike Tyson',
                    'Floyd Mayweather',
                    'George Foreman'
                ],
                'correct_answer' => 0,
                'difficulty' => 'hard',
                'time' => 45,
                'category_id' => $sportsCategory->id
            ]
        ];

        // Sanat - Orta Seviye Sorular
        $artMediumQuestions = [
            [
                'question' => 'Mona Lisa tablosunun ressamı kimdir?',
                'options' => [
                    'Leonardo da Vinci',
                    'Michelangelo',
                    'Raphael',
                    'Donatello'
                ],
                'correct_answer' => 0,
                'difficulty' => 'medium',
                'time' => 30,
                'category_id' => $artCategory->id
            ],
            [
                'question' => 'Van Gogh\'un en ünlü eserlerinden biri hangisidir?',
                'options' => [
                    'Yıldızlı Gece',
                    'Son Akşam Yemeği',
                    'Çığlık',
                    'Guernica'
                ],
                'correct_answer' => 0,
                'difficulty' => 'medium',
                'time' => 30,
                'category_id' => $artCategory->id
            ],
            [
                'question' => 'Hangi sanat akımının öncüsü Pablo Picasso\'dur?',
                'options' => [
                    'Kübizm',
                    'Empresyonizm',
                    'Sürrealizm',
                    'Romantizm'
                ],
                'correct_answer' => 0,
                'difficulty' => 'medium',
                'time' => 30,
                'category_id' => $artCategory->id
            ],
            [
                'question' => 'Sistine Şapeli\'nin tavanını kim boyamıştır?',
                'options' => [
                    'Michelangelo',
                    'Leonardo da Vinci',
                    'Raphael',
                    'Botticelli'
                ],
                'correct_answer' => 0,
                'difficulty' => 'medium',
                'time' => 30,
                'category_id' => $artCategory->id
            ],
            [
                'question' => 'Hangi müzik aleti yaylı çalgılar ailesine aittir?',
                'options' => [
                    'Keman',
                    'Piyano',
                    'Flüt',
                    'Gitar'
                ],
                'correct_answer' => 0,
                'difficulty' => 'medium',
                'time' => 30,
                'category_id' => $artCategory->id
            ],
            [
                'question' => 'Türk sanat müziğinde hangi makam en çok kullanılır?',
                'options' => [
                    'Rast',
                    'Hicaz',
                    'Nihavend',
                    'Uşşak'
                ],
                'correct_answer' => 0,
                'difficulty' => 'medium',
                'time' => 30,
                'category_id' => $artCategory->id
            ],
            [
                'question' => 'Hangi ressam kulağını kesmiştir?',
                'options' => [
                    'Van Gogh',
                    'Monet',
                    'Dali',
                    'Rembrandt'
                ],
                'correct_answer' => 0,
                'difficulty' => 'medium',
                'time' => 30,
                'category_id' => $artCategory->id
            ],
            [
                'question' => 'Hangi müzik türü New Orleans\'da doğmuştur?',
                'options' => [
                    'Jazz',
                    'Blues',
                    'Rock',
                    'Hip Hop'
                ],
                'correct_answer' => 0,
                'difficulty' => 'medium',
                'time' => 30,
                'category_id' => $artCategory->id
            ],
            [
                'question' => 'Türkiye\'nin ilk opera ve bale binası hangi şehirdedir?',
                'options' => [
                    'İstanbul',
                    'Ankara',
                    'İzmir',
                    'Bursa'
                ],
                'correct_answer' => 1,
                'difficulty' => 'medium',
                'time' => 30,
                'category_id' => $artCategory->id
            ],
            [
                'question' => 'Hangi dans Latin Amerika kökenlidir?',
                'options' => [
                    'Salsa',
                    'Vals',
                    'Polka',
                    'Zeybek'
                ],
                'correct_answer' => 0,
                'difficulty' => 'medium',
                'time' => 30,
                'category_id' => $artCategory->id
            ]
        ];

        // Sanat - Zor Seviye Sorular
        $artHardQuestions = [
            [
                'question' => 'Hangi empresyonist ressam "Nilüferler" serisini yapmıştır?',
                'options' => [
                    'Claude Monet',
                    'Edgar Degas',
                    'Pierre-Auguste Renoir',
                    'Paul Cézanne'
                ],
                'correct_answer' => 0,
                'difficulty' => 'hard',
                'time' => 45,
                'category_id' => $artCategory->id
            ],
            [
                'question' => 'Hangi sanat akımı "bilinçaltının sanatı" olarak bilinir?',
                'options' => [
                    'Sürrealizm',
                    'Dadaizm',
                    'Ekspresyonizm',
                    'Fovizm'
                ],
                'correct_answer' => 0,
                'difficulty' => 'hard',
                'time' => 45,
                'category_id' => $artCategory->id
            ],
            [
                'question' => 'Hangi besteci "Dokuzuncu Senfoni"yi sağır olduğu dönemde bestelemiştir?',
                'options' => [
                    'Beethoven',
                    'Mozart',
                    'Bach',
                    'Tchaikovsky'
                ],
                'correct_answer' => 0,
                'difficulty' => 'hard',
                'time' => 45,
                'category_id' => $artCategory->id
            ],
            [
                'question' => 'Hangi ressam "Campbell\'s Çorba Kutuları" serisiyle pop art akımının öncüsü olmuştur?',
                'options' => [
                    'Andy Warhol',
                    'Roy Lichtenstein',
                    'Keith Haring',
                    'Robert Rauschenberg'
                ],
                'correct_answer' => 0,
                'difficulty' => 'hard',
                'time' => 45,
                'category_id' => $artCategory->id
            ],
            [
                'question' => 'Hangi opera bestecisi "Ring Dörtlemesi"ni bestelemiştir?',
                'options' => [
                    'Richard Wagner',
                    'Giuseppe Verdi',
                    'Wolfgang Amadeus Mozart',
                    'Giacomo Puccini'
                ],
                'correct_answer' => 0,
                'difficulty' => 'hard',
                'time' => 45,
                'category_id' => $artCategory->id
            ],
            [
                'question' => 'Hangi Türk ressam "Kaplumbağa Terbiyecisi" tablosunu yapmıştır?',
                'options' => [
                    'Osman Hamdi Bey',
                    'İbrahim Çallı',
                    'Şeker Ahmet Paşa',
                    'Fikret Mualla'
                ],
                'correct_answer' => 0,
                'difficulty' => 'hard',
                'time' => 45,
                'category_id' => $artCategory->id
            ],
            [
                'question' => 'Hangi heykeltıraş "Düşünen Adam" heykelinin yaratıcısıdır?',
                'options' => [
                    'Auguste Rodin',
                    'Michelangelo',
                    'Bernini',
                    'Henry Moore'
                ],
                'correct_answer' => 0,
                'difficulty' => 'hard',
                'time' => 45,
                'category_id' => $artCategory->id
            ],
            [
                'question' => 'Hangi sanat akımı "soyut dışavurumculuk" olarak da bilinir?',
                'options' => [
                    'Action Painting',
                    'Pop Art',
                    'Minimalizm',
                    'Konstrüktivizm'
                ],
                'correct_answer' => 0,
                'difficulty' => 'hard',
                'time' => 45,
                'category_id' => $artCategory->id
            ],
            [
                'question' => 'Hangi besteci "Dört Mevsim" konçertosunu bestelemiştir?',
                'options' => [
                    'Antonio Vivaldi',
                    'Johann Sebastian Bach',
                    'Wolfgang Amadeus Mozart',
                    'Franz Schubert'
                ],
                'correct_answer' => 0,
                'difficulty' => 'hard',
                'time' => 45,
                'category_id' => $artCategory->id
            ],
            [
                'question' => 'Hangi ressam "Çığlık" tablosunun yaratıcısıdır?',
                'options' => [
                    'Edvard Munch',
                    'Gustav Klimt',
                    'Egon Schiele',
                    'Wassily Kandinsky'
                ],
                'correct_answer' => 0,
                'difficulty' => 'hard',
                'time' => 45,
                'category_id' => $artCategory->id
            ]
        ];

        // Soruları veritabanına ekle
        foreach ($generalMediumQuestions as $question) {
            Question::create($question);
        }

        foreach ($generalHardQuestions as $question) {
            Question::create($question);
        }

        foreach ($scienceMediumQuestions as $question) {
            Question::create($question);
        }

        foreach ($scienceHardQuestions as $question) {
            Question::create($question);
        }

        foreach ($historyMediumQuestions as $question) {
            Question::create($question);
        }

        foreach ($historyHardQuestions as $question) {
            Question::create($question);
        }

        foreach ($geographyMediumQuestions as $question) {
            Question::create($question);
        }

        foreach ($geographyHardQuestions as $question) {
            Question::create($question);
        }

        foreach ($sportsMediumQuestions as $question) {
            Question::create($question);
        }

        foreach ($sportsHardQuestions as $question) {
            Question::create($question);
        }

        foreach ($artMediumQuestions as $question) {
            Question::create($question);
        }

        foreach ($artHardQuestions as $question) {
            Question::create($question);
        }
    }
}
