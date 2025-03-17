// Soru veritabanı - Kolay, orta ve zor zorluk seviyelerinde karışık sorular
const questions = [
    // Kolay sorular
    {
        question: "Türkiye'nin başkenti neresidir?",
        options: ["İstanbul", "Ankara", "İzmir", "Bursa"],
        correctAnswer: 1, // Ankara (0-indexed)
        difficulty: "easy",
        time: 15
    },
    {
        question: "Hangi gezegen Güneş'e en yakındır?",
        options: ["Venüs", "Merkür", "Dünya", "Mars"],
        correctAnswer: 1, // Merkür
        difficulty: "easy",
        time: 15
    },
    {
        question: "Su molekülünün kimyasal formülü nedir?",
        options: ["H2O", "CO2", "O2", "N2"],
        correctAnswer: 0, // H2O
        difficulty: "easy",
        time: 15
    },
    {
        question: "İnsan vücudunda kaç kemik vardır?",
        options: ["206", "186", "226", "246"],
        correctAnswer: 0, // 206
        difficulty: "easy",
        time: 15
    },
    
    // Orta zorlukta sorular
    {
        question: "Hangisi bir programlama dili değildir?",
        options: ["Python", "HTML", "Java", "C++"],
        correctAnswer: 1, // HTML (markup dilidir)
        difficulty: "medium",
        time: 20
    },
    {
        question: "Nobel Barış Ödülü hangi şehirde verilir?",
        options: ["Stokholm", "Oslo", "Kopenhag", "Helsinki"],
        correctAnswer: 1, // Oslo
        difficulty: "medium",
        time: 20
    },
    {
        question: "Hangisi birincil renk değildir?",
        options: ["Kırmızı", "Mavi", "Yeşil", "Sarı"],
        correctAnswer: 3, // Sarı
        difficulty: "medium",
        time: 20
    },
    {
        question: "Hangi ülke Avrupa Birliği'nin kurucu üyelerinden değildir?",
        options: ["Almanya", "Fransa", "İtalya", "İspanya"],
        correctAnswer: 3, // İspanya
        difficulty: "medium",
        time: 20
    },
    
    // Zor sorular
    {
        question: "DNA'nın açılımı nedir?",
        options: ["Deoksiribonükleik Asit", "Deoksiriboz Nükleik Asit", "Dihidro Nükleik Asit", "Diribonükleik Aminoasit"],
        correctAnswer: 0, // Deoksiribonükleik Asit
        difficulty: "hard",
        time: 30
    },
    {
        question: "Dünya'nın en derin okyanus çukuru hangisidir?",
        options: ["Java Çukuru", "Mariana Çukuru", "Filipin Çukuru", "Tonga Çukuru"],
        correctAnswer: 1, // Mariana Çukuru
        difficulty: "hard",
        time: 30
    },
    {
        question: "Hangisi bir sürrealist ressam değildir?",
        options: ["Salvador Dali", "René Magritte", "Claude Monet", "Max Ernst"],
        correctAnswer: 2, // Claude Monet (Empresyonist)
        difficulty: "hard",
        time: 30
    },
    {
        question: "İnsan genomu yaklaşık olarak kaç gen içerir?",
        options: ["10,000-15,000", "20,000-25,000", "30,000-35,000", "40,000-45,000"],
        correctAnswer: 1, // 20,000-25,000
        difficulty: "hard",
        time: 30
    }
];

// Soruları karıştırmak için yardımcı fonksiyon
function shuffleQuestions() {
    // Fisher-Yates (Knuth) Karıştırma Algoritması
    const shuffled = [...questions];
    for (let i = shuffled.length - 1; i > 0; i--) {
        const j = Math.floor(Math.random() * (i + 1));
        [shuffled[i], shuffled[j]] = [shuffled[j], shuffled[i]];
    }
    return shuffled;
} 