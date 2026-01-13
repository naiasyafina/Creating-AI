<?php
/**
 * Database Connection dengan Environment Variables
 * AMAN untuk di-push ke GitHub - tidak ada hardcoded credentials!
 */

// Load configuration dan environment variables
require_once __DIR__ . '/config.php';

// Set timezone
date_default_timezone_set('Asia/Jakarta');

// Ambil konfigurasi database dari environment variables
$servername = env('DB_HOST');
$username = env('DB_USER');
$password = env('DB_PASS');
$db = env('DB_NAME');

// Create connection
$conn = new mysqli($servername, $username, $password, $db);

// Check apakah ada error connection
if ($conn->connect_error) {
    // Jika ada, hentikan script dan tampilkan pesan error
    die("Connection failed: " . $conn->connect_error);
}

// Set charset untuk menghindari masalah encoding
$conn->set_charset("utf8");

// Uncomment untuk debugging (HAPUS di production!)
// echo "Connected successfully<hr>";
?>