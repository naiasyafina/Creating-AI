<?php
/**
 * Configuration file untuk load environment variables
 * File ini aman untuk di-push ke GitHub karena tidak berisi credential
 */

// Fungsi sederhana untuk load .env file
function loadEnv($path = __DIR__ . '/.env') {
    if (!file_exists($path)) {
        throw new Exception('.env file not found! Copy .env.example to .env and configure it.');
    }

    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    
    foreach ($lines as $line) {
        // Skip comments
        if (strpos(trim($line), '#') === 0) {
            continue;
        }

        // Parse KEY=VALUE
        if (strpos($line, '=') !== false) {
            list($key, $value) = explode('=', $line, 2);
            $key = trim($key);
            $value = trim($value);
            
            // Set as environment variable
            if (!array_key_exists($key, $_ENV)) {
                $_ENV[$key] = $value;
                putenv("$key=$value");
            }
        }
    }
}

// Load environment variables
try {
    loadEnv();
} catch (Exception $e) {
    die('Error: ' . $e->getMessage());
}

// Helper function untuk mengambil env variable
function env($key, $default = null) {
    return $_ENV[$key] ?? getenv($key) ?? $default;
}

// Pastikan API key tersedia
if (empty(env('GEMINI_API_KEY'))) {
    die('Error: GEMINI_API_KEY not set in .env file');
}
?>
