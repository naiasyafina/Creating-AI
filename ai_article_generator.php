<?php
header('Content-Type: application/json');

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

// API Key Gemini - GANTI DENGAN API KEY ANDA
$apiKey = 'AIzaSyAEtr0JqGxyoun_88u6HFq_EYXBrNHnssg';

// Endpoint Gemini API
$apiUrl = 'https://generativelanguage.googleapis.com/v1/models/gemini-2.5-flash:generateContent?key=' . $apiKey;

debug_log("API URL", $apiUrl);

// Prompt yang lebih spesifik untuk mendapatkan respons lengkap
$prompt = "Tulis artikel singkat dalam Bahasa Indonesia tentang: \"$judul\"

ATURAN WAJIB:
1. Tulis 3-4 kalimat yang LENGKAP dan UTUH
2. Setiap kalimat harus berakhir dengan tanda titik (.)
3. Jelaskan topik dengan DETAIL dan INFORMATIF
4. TIDAK BOLEH memotong kalimat di tengah
5. Kalimat boleh panjang asalkan LENGKAP dan sesuai konteks
6. Langsung tulis isi artikel tanpa judul

CONTOH FORMAT YANG BENAR (perhatikan kalimat lengkap dan detail):
\"NCT Dream adalah boy group Korea Selatan yang debut pada 16 Agustus 2016 di bawah naungan SM Entertainment sebagai sub-unit NCT dengan konsep rotasi anggota berdasarkan usia. Grup ini awalnya terdiri dari tujuh anggota remaja yaitu Mark, Renjun, Jeno, Haechan, Jaemin, Chenle, dan Jisung yang membawakan lagu-lagu dengan konsep segar dan energik yang sesuai dengan usia mereka. Pada tahun 2020, NCT Dream resmi menjadi unit permanen dengan tujuh anggota tetap dan terus aktif merilis album-album berkualitas yang meraih kesuksesan komersial baik di Korea maupun internasional. Hingga saat ini, NCT Dream dikenal sebagai salah satu boy group paling populer dengan fanbase yang disebut NCTzen dan telah memenangkan berbagai penghargaan bergengsi di industri musik K-Pop.\"

Sekarang tulis artikel lengkap tentang: \"$judul\"";

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
        'temperature' => 0.8,
        'topK' => 40,
        'topP' => 0.95,
        'maxOutputTokens' => 500, 
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

// Initialize cURL
$ch = curl_init($apiUrl);

// Set cURL options
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json'
]);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_TIMEOUT, 30);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Untuk debugging

// Execute request
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

debug_log("HTTP Code", $httpCode);
debug_log("Raw Response", $response);

// Check for cURL errors
if (curl_errno($ch)) {
    $error = curl_error($ch);
    curl_close($ch);
    
    debug_log("cURL Error", $error);
    
    echo json_encode([
        'success' => false,
        'message' => 'Error connecting to AI: ' . $error,
        'debug' => $DEBUG_MODE ? ['curl_error' => $error] : null
    ]);
    exit;
}

curl_close($ch);

// Parse response
$responseData = json_decode($response, true);

debug_log("Parsed Response", $responseData);

// Check if response is valid
if ($httpCode !== 200) {
    $errorMsg = $responseData['error']['message'] ?? 'Unknown error';
    
    debug_log("API Error", ['code' => $httpCode, 'message' => $errorMsg]);
    
    // Check jika quota exceeded
    if ($httpCode == 429) {
        echo json_encode([
            'success' => false,
            'message' => '❌ API Quota habis! Silakan gunakan API key baru atau aktifkan billing.',
            'debug' => $DEBUG_MODE ? ['http_code' => $httpCode, 'response' => $responseData] : null
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'AI API Error: ' . $errorMsg,
            'debug' => $DEBUG_MODE ? ['http_code' => $httpCode, 'response' => $responseData] : null
        ]);
    }
    exit;
}

// Extract generated text
if (isset($responseData['candidates'][0]['content']['parts'][0]['text'])) {
    $generatedText = trim($responseData['candidates'][0]['content']['parts'][0]['text']);
    
    debug_log("Generated Text", $generatedText);
    
    // Validasi output - pastikan ada titik di akhir (kalimat lengkap)
    if (!preg_match('/[.!?]$/', $generatedText)) {
        // Jika tidak ada tanda baca di akhir, tambahkan titik
        $generatedText .= '.';
        debug_log("Added period to complete sentence");
    }
    
    // Hitung jumlah kalimat
    $sentenceCount = preg_match_all('/[.!?]+/', $generatedText);
    debug_log("Sentence count", $sentenceCount);
    
    // Periksa jika kalimat terlalu pendek (kurang dari 100 karakter = kemungkinan terpotong)
    if (strlen($generatedText) < 100) {
        debug_log("WARNING: Text too short, might be truncated", strlen($generatedText));
        // Kirim warning ke user tapi tetap tampilkan hasilnya
        $warningMsg = " (Peringatan: Hasil mungkin terpotong, silakan edit)";
    } else {
        $warningMsg = "";
    }
    
    echo json_encode([
        'success' => true,
        'article' => $generatedText,
        'message' => '✅ Artikel berhasil di-generate oleh AI!' . $warningMsg,
        'debug' => $DEBUG_MODE ? [
            'text_length' => strlen($generatedText),
            'sentence_count' => $sentenceCount,
            'model' => 'gemini-2.5-flash',
            'full_response' => $responseData
        ] : null
    ]);
} else {
    debug_log("No content in response");
    
    // Cek apakah ada finish_reason
    $finishReason = $responseData['candidates'][0]['finishReason'] ?? 'unknown';
    debug_log("Finish Reason", $finishReason);
    
    echo json_encode([
        'success' => false,
        'message' => 'Tidak dapat mengambil hasil dari AI. Reason: ' . $finishReason,
        'debug' => $DEBUG_MODE ? [
            'finish_reason' => $finishReason,
            'full_response' => $responseData
        ] : null
    ]);
}
?>