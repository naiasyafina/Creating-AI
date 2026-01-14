<?php
header('Content-Type: application/json');

// Load configuration dan environment variables
require_once __DIR__ . '/config.php';

// Enable debug mode - set false untuk production
$DEBUG_MODE = true;

// Fungsi untuk debug logging
function debug_log($message, $data = null) {
    global $DEBUG_MODE;
    if ($DEBUG_MODE) {
        error_log("AI Generator Debug: $message");
        if ($data) {
            error_log(print_r($data, true));
        }
    }
}

// ============================================
// FUNGSI FALLBACK API KEY
// ============================================

/**
 * Mendapatkan daftar API keys yang tersedia
 * Mengembalikan array API keys (yang tidak kosong)
 */
function getAvailableApiKeys() {
    $keys = [];
    
    // API Key utama
    $key1 = env('GEMINI_API_KEY');
    if (!empty($key1)) {
        $keys[] = ['key' => $key1, 'name' => 'Primary Key'];
    }
    
    // API Key cadangan 1
    $key2 = env('GEMINI_API_KEY_2');
    if (!empty($key2)) {
        $keys[] = ['key' => $key2, 'name' => 'Backup Key 1'];
    }
    
    // API Key cadangan 2
    $key3 = env('GEMINI_API_KEY_3');
    if (!empty($key3)) {
        $keys[] = ['key' => $key3, 'name' => 'Backup Key 2'];
    }
    
    return $keys;
}

/**
 * Melakukan request ke Gemini API
 * @return array ['success' => bool, 'data' => array|null, 'error' => string|null, 'httpCode' => int]
 */
function callGeminiAPI($apiKey, $data) {
    $apiUrl = 'https://generativelanguage.googleapis.com/v1/models/gemini-2.5-flash:generateContent?key=' . $apiKey;
    
    $ch = curl_init($apiUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json'
    ]);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curlError = curl_error($ch);
    curl_close($ch);
    
    if ($curlError) {
        return [
            'success' => false,
            'data' => null,
            'error' => 'cURL Error: ' . $curlError,
            'httpCode' => 0
        ];
    }
    
    $responseData = json_decode($response, true);
    
    if ($httpCode !== 200) {
        return [
            'success' => false,
            'data' => $responseData,
            'error' => $responseData['error']['message'] ?? 'Unknown API error',
            'httpCode' => $httpCode
        ];
    }
    
    return [
        'success' => true,
        'data' => $responseData,
        'error' => null,
        'httpCode' => $httpCode
    ];
}

// ============================================
// MAIN LOGIC
// ============================================

// Pastikan request method adalah POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid request method'
    ]);
    exit;
}

// Ambil judul dari POST
$judul = isset($_POST['judul']) ? trim($_POST['judul']) : '';

debug_log("Request received", ['judul' => $judul]);

// Validasi judul
if (empty($judul)) {
    echo json_encode([
        'success' => false,
        'message' => 'Judul tidak boleh kosong'
    ]);
    exit;
}

// Dapatkan semua API keys yang tersedia
$apiKeys = getAvailableApiKeys();

if (empty($apiKeys)) {
    echo json_encode([
        'success' => false,
        'message' => 'Tidak ada API key yang dikonfigurasi. Tambahkan GEMINI_API_KEY di file .env'
    ]);
    exit;
}

debug_log("Available API keys", count($apiKeys) . " keys found");

// Prompt untuk artikel yang lebih panjang dan lengkap
$prompt = "Tulis artikel LENGKAP dan PANJANG dalam Bahasa Indonesia tentang: \"$judul\"

PENTING - ATURAN WAJIB:
1. Artikel HARUS terdiri dari MINIMAL 4-6 paragraf yang LENGKAP
2. Setiap paragraf MINIMAL 4-6 kalimat yang detail
3. Total artikel MINIMAL 500-700 kata
4. Gunakan bahasa yang informatif dan mudah dipahami
5. Setiap kalimat HARUS berakhir dengan tanda titik (.)
6. JANGAN memotong kalimat atau paragraf di tengah
7. Pisahkan paragraf dengan baris kosong (gunakan \\n\\n)
8. Langsung tulis isi artikel tanpa menulis judul

STRUKTUR ARTIKEL LENGKAP:
📝 Paragraf 1 (Pembukaan): Pengenalan topik, latar belakang, dan konteks umum - minimal 4 kalimat
📝 Paragraf 2 (Detail 1): Penjelasan mendalam aspek pertama - minimal 4 kalimat  
📝 Paragraf 3 (Detail 2): Penjelasan mendalam aspek kedua - minimal 4 kalimat
📝 Paragraf 4 (Detail 3): Informasi tambahan, fakta menarik, atau perkembangan - minimal 4 kalimat
📝 Paragraf 5 (Dampak): Manfaat, pengaruh, atau dampak terhadap masyarakat - minimal 4 kalimat
📝 Paragraf 6 (Penutup): Kesimpulan dan pandangan ke depan - minimal 3 kalimat

CONTOH ARTIKEL LENGKAP:
\"Kecerdasan buatan atau artificial intelligence (AI) telah menjadi salah satu teknologi paling revolusioner di abad ke-21 yang mengubah berbagai aspek kehidupan manusia secara fundamental. Perkembangan AI dimulai sejak tahun 1950-an namun baru mengalami kemajuan pesat dalam dua dekade terakhir berkat peningkatan kapasitas komputasi dan ketersediaan data dalam jumlah besar. Teknologi ini memungkinkan mesin untuk melakukan tugas-tugas yang sebelumnya hanya bisa dilakukan oleh manusia, seperti pengenalan pola, pengambilan keputusan, dan pembelajaran dari pengalaman. Saat ini, AI telah diaplikasikan dalam berbagai bidang mulai dari kesehatan, pendidikan, transportasi, hingga industri kreatif.

Dalam sektor kesehatan, artificial intelligence telah memberikan kontribusi luar biasa dalam meningkatkan kualitas diagnosis dan perawatan medis. Sistem AI dapat menganalisis gambar medis seperti hasil CT scan, MRI, dan X-ray dengan tingkat akurasi yang sangat tinggi, bahkan mampu mendeteksi kelainan yang mungkin terlewatkan oleh mata manusia. Teknologi machine learning membantu para dokter dalam memprediksi risiko penyakit berdasarkan riwayat kesehatan dan data genetik pasien, sehingga memungkinkan pencegahan dini dan penanganan yang lebih tepat. Selain itu, AI juga digunakan dalam pengembangan obat-obatan baru dengan mempercepat proses penelitian dan mengidentifikasi kombinasi molekul yang paling efektif untuk mengobati berbagai penyakit.

Di bidang pendidikan, kecerdasan buatan membuka era baru pembelajaran yang lebih personal dan adaptif untuk setiap individu. Platform pembelajaran berbasis AI dapat menganalisis gaya belajar, kecepatan pemahaman, dan kesulitan yang dihadapi setiap siswa secara real-time. Sistem ini kemudian menyesuaikan materi pembelajaran, tingkat kesulitan soal, dan metode pengajaran yang paling sesuai dengan kebutuhan masing-masing pelajar. Guru juga mendapat bantuan dari AI dalam bentuk sistem penilaian otomatis, analisis kemajuan belajar siswa, dan rekomendasi strategi pengajaran yang lebih efektif berdasarkan data pembelajaran yang terkumpul.

Perkembangan AI juga merevolusi industri transportasi melalui teknologi kendaraan otonom yang semakin canggih. Mobil self-driving menggunakan kombinasi sensor, kamera, radar, dan algoritma AI untuk memahami lingkungan sekitar dan membuat keputusan navigasi yang aman. Teknologi ini berpotensi mengurangi kecelakaan lalu lintas yang disebabkan oleh kesalahan manusia yang mencapai lebih dari 90 persen dari total kecelakaan. Sistem transportasi cerdas berbasis AI juga membantu mengoptimalkan arus lalu lintas di kota-kota besar, mengurangi kemacetan, dan menurunkan emisi karbon dari sektor transportasi.

Dampak positif artificial intelligence terhadap produktivitas dan efisiensi ekonomi sangat signifikan di berbagai sektor industri. Perusahaan-perusahaan menggunakan AI untuk mengotomasi proses produksi, meningkatkan kontrol kualitas, dan mengoptimalkan rantai pasokan mereka. Dalam sektor layanan pelanggan, chatbot berbasis AI mampu melayani pertanyaan konsumen 24/7 dengan respons yang cepat dan akurat. Industri keuangan memanfaatkan AI untuk mendeteksi transaksi mencurigakan, mencegah penipuan, dan memberikan rekomendasi investasi yang personal berdasarkan profil risiko setiap nasabah.

Meskipun membawa banyak manfaat, pengembangan dan implementasi AI juga memerlukan perhatian serius terhadap aspek etika dan regulasi. Isu-isu seperti privasi data, bias algoritma, dan dampak terhadap lapangan pekerjaan perlu ditangani dengan bijak melalui kebijakan yang tepat. Ke depannya, kecerdasan buatan diperkirakan akan terus berkembang dan semakin terintegrasi dalam kehidupan sehari-hari, sehingga penting bagi masyarakat untuk memahami teknologi ini dan mempersiapkan diri menghadapi transformasi digital yang sedang berlangsung.\"

SEKARANG TULIS ARTIKEL LENGKAP MINIMAL 500 KATA tentang: \"$judul\"

INGAT: 
- WAJIB minimal 4-6 paragraf lengkap!
- JANGAN berhenti di tengah kalimat atau paragraf!
- Pisahkan paragraf dengan \\n\\n
- Tulis sampai SELESAI dan LENGKAP!";

// Data yang akan dikirim ke API dengan konfigurasi optimal
$data = [
    'contents' => [
        [
            'parts' => [
                ['text' => $prompt]
            ]
        ]
    ],
    'generationConfig' => [
        'temperature' => 0.7,
        'topK' => 40,
        'topP' => 0.95,
        'maxOutputTokens' => 2000,
        'stopSequences' => []
    ],
    'safetySettings' => [
        [
            'category' => 'HARM_CATEGORY_HARASSMENT',
            'threshold' => 'BLOCK_NONE'
        ],
        [
            'category' => 'HARM_CATEGORY_HATE_SPEECH',
            'threshold' => 'BLOCK_NONE'
        ],
        [
            'category' => 'HARM_CATEGORY_SEXUALLY_EXPLICIT',
            'threshold' => 'BLOCK_NONE'
        ],
        [
            'category' => 'HARM_CATEGORY_DANGEROUS_CONTENT',
            'threshold' => 'BLOCK_NONE'
        ]
    ]
];

debug_log("Request payload", $data);

// ============================================
// FALLBACK MECHANISM - Coba semua API keys
// ============================================

$result = null;
$usedKeyName = '';
$triedKeys = [];
$lastError = null;

// Loop melalui semua API keys
foreach ($apiKeys as $index => $keyInfo) {
    $currentKey = $keyInfo['key'];
    $keyName = $keyInfo['name'];
    
    debug_log("Trying API key: $keyName (key " . ($index + 1) . " of " . count($apiKeys) . ")");
    
    $result = callGeminiAPI($currentKey, $data);
    
    $triedKeys[] = [
        'name' => $keyName,
        'success' => $result['success'],
        'httpCode' => $result['httpCode'],
        'error' => $result['error']
    ];
    
    // Jika berhasil, keluar dari loop
    if ($result['success']) {
        $usedKeyName = $keyName;
        debug_log("Success with $keyName");
        break;
    }
    
    // Simpan error terakhir
    $lastError = $result;
    
    // Jika error 429 (quota exceeded), coba key berikutnya
    if ($result['httpCode'] == 429) {
        debug_log("Quota exceeded for $keyName, trying next key...");
        continue;
    }
    
    // Jika error 403 (API key invalid), coba key berikutnya
    if ($result['httpCode'] == 403) {
        debug_log("API key invalid for $keyName, trying next key...");
        continue;
    }
    
    // Untuk error lain (bukan quota/invalid key), langsung return error
    debug_log("Fatal error on $keyName: " . $result['error']);
    break;
}

// ============================================
// PROCESS RESULT
// ============================================

if ($result && $result['success']) {
    // Berhasil mendapatkan hasil
    $responseData = $result['data'];
    
    if (isset($responseData['candidates'][0]['content']['parts'][0]['text'])) {
        $generatedText = trim($responseData['candidates'][0]['content']['parts'][0]['text']);
        
        debug_log("Generated Text", $generatedText);
        
        // Cek finish reason untuk deteksi artikel terpotong
        $finishReason = $responseData['candidates'][0]['finishReason'] ?? 'UNKNOWN';
        debug_log("Finish Reason", $finishReason);
        
        $warningMsg = "";
        
        // Periksa jika artikel terpotong
        if ($finishReason === 'MAX_TOKENS') {
            $warningMsg = " ⚠️ (Artikel mencapai batas maksimal, mungkin ada bagian yang terpotong. Silakan edit atau generate ulang)";
        } elseif ($finishReason === 'SAFETY') {
            $warningMsg = " ⚠️ (Beberapa konten difilter oleh AI untuk keamanan)";
        } elseif (strlen($generatedText) < 300) {
            $warningMsg = " ⚠️ (Artikel terlalu pendek, silakan generate ulang untuk hasil lebih lengkap)";
            debug_log("WARNING: Text too short", strlen($generatedText));
        }
        
        // Tambahkan info jika menggunakan backup key
        if ($usedKeyName !== 'Primary Key') {
            $warningMsg .= " ℹ️ (Menggunakan $usedKeyName)";
        }
        
        // Validasi output - pastikan ada titik di akhir
        if (!preg_match('/[.!?]$/', $generatedText)) {
            $generatedText .= '.';
            debug_log("Added period to complete sentence");
        }
        
        // Hitung jumlah kalimat
        $sentenceCount = preg_match_all('/[.!?]+/', $generatedText);
        debug_log("Sentence count", $sentenceCount);
        
        echo json_encode([
            'success' => true,
            'article' => $generatedText,
            'message' => '✅ Artikel berhasil di-generate oleh AI!' . $warningMsg,
            'debug' => $DEBUG_MODE ? [
                'text_length' => strlen($generatedText),
                'sentence_count' => $sentenceCount,
                'finish_reason' => $finishReason,
                'model' => 'gemini-2.5-flash',
                'api_key_used' => $usedKeyName,
                'keys_tried' => $triedKeys,
                'full_response' => $responseData
            ] : null
        ]);
    } else {
        debug_log("No content in response");
        
        $finishReason = $responseData['candidates'][0]['finishReason'] ?? 'unknown';
        debug_log("Finish Reason", $finishReason);
        
        echo json_encode([
            'success' => false,
            'message' => 'Tidak dapat mengambil hasil dari AI. Reason: ' . $finishReason,
            'debug' => $DEBUG_MODE ? [
                'finish_reason' => $finishReason,
                'api_key_used' => $usedKeyName,
                'keys_tried' => $triedKeys,
                'full_response' => $responseData
            ] : null
        ]);
    }
} else {
    // Semua API keys gagal
    debug_log("All API keys failed", $triedKeys);
    
    $errorMessage = '❌ Semua API key gagal!';
    
    if ($lastError) {
        if ($lastError['httpCode'] == 429) {
            $errorMessage = '❌ Semua API key kehabisan quota! Silakan tambahkan API key baru di file .env (GEMINI_API_KEY_2, GEMINI_API_KEY_3)';
        } elseif ($lastError['httpCode'] == 403) {
            $errorMessage = '❌ Semua API key tidak valid! Periksa konfigurasi API key di file .env';
        } else {
            $errorMessage = '❌ AI API Error: ' . ($lastError['error'] ?? 'Unknown error');
        }
    }
    
    echo json_encode([
        'success' => false,
        'message' => $errorMessage,
        'debug' => $DEBUG_MODE ? [
            'keys_tried' => $triedKeys,
            'last_error' => $lastError
        ] : null
    ]);
}
?>