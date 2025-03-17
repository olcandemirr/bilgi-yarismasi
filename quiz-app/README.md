# Bilgi Yarışması Uygulaması

Bu proje, Kahoot benzeri bir online bilgi yarışması platformudur. Farklı zorluk seviyelerinde sorular içerir ve takım halinde oynamak için tasarlanmıştır.

## Özellikler

- Kolay, orta ve zor seviyelerde karışık sorular
- Takım ismi ve katılımcı sayısı girişi
- Zamanlayıcı özelliği (sorunun zorluğuna göre değişen süre)
- Görsel geri bildirim (doğru/yanlış cevaplar için renk kodlaması)
- Puan sistemi (zorluk seviyesi ve kalan süreye göre)
- Mobil uyumlu tasarım

## Nasıl Kullanılır

1. İndeks dosyasını bir web tarayıcısında açın (`index.html`)
2. "Başla" butonuna tıklayın
3. Takım adınızı ve katılımcı sayısını girin
4. "Yarışmaya Başla" butonuna tıklayın
5. Soruları yanıtlayın (her soru için sınırlı süreniz var)
6. Yarışma bitiminde toplam puanınızı görün
7. "Yeniden Başlat" butonuyla yeni bir yarışma başlatabilirsiniz

## Teknik Detaylar

- Saf JavaScript, HTML ve CSS kullanılarak geliştirilmiştir
- Herhangi bir ek kütüphane veya çerçeve gerektirmez
- Tüm sorular ve cevaplar `questions.js` dosyasında bulunur
- Ana uygulama mantığı `main.js` dosyasında yer alır

## Sorular ve Zorluk Seviyeleri

- Kolay sorular: 15 saniye süre ve 10 baz puan
- Orta zorlukta sorular: 20 saniye süre ve 20 baz puan
- Zor sorular: 30 saniye süre ve 30 baz puan

Kalan süreye göre de bonus puanlar kazanabilirsiniz!

## Özelleştirme

Kendi sorularınızı eklemek için `questions.js` dosyasını düzenleyebilirsiniz. Her soru şu formatı takip etmelidir:

```javascript
{
    question: "Soru metni?",
    options: ["Seçenek 1", "Seçenek 2", "Seçenek 3", "Seçenek 4"],
    correctAnswer: 0, // 0-indexed doğru cevap indeksi
    difficulty: "easy", // "easy", "medium" veya "hard"
    time: 15 // Saniye cinsinden süre
}
```

---

Keyfini çıkarın ve iyi eğlenceler! 