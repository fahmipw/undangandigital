<?php
require_once 'api/db.php';

echo "<h2>Database Setup</h2>";

try {
    // 1. Koneksi awal (tanpa milih DB dulu untuk buat DB jika belum ada)
    $pdo = new PDO("mysql:host=" . DB_HOST, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // 2. Buat Database
    $pdo->exec("CREATE DATABASE IF NOT EXISTS " . DB_NAME . " CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    echo "✅ Database '" . DB_NAME . "' berhasil dibuat/sudah ada.<br>";

    // 3. Gunakan Database
    $pdo->exec("USE " . DB_NAME);

    // 4. Drop old tables (Reset for V2)
    $pdo->exec("SET FOREIGN_KEY_CHECKS = 0");
    $tables = ['guests', 'ucapan', 'rsvp', 'settings', 'admins', 'invitations', 'gallery', 'music', 'stories'];
    foreach ($tables as $t) $pdo->exec("DROP TABLE IF EXISTS $t");
    $pdo->exec("SET FOREIGN_KEY_CHECKS = 1");

    // 5. Baca schema.sql
    $sql = file_get_contents('api/schema.sql');
    
    // Split SQL into separate statements
    $queries = explode(';', $sql);
    foreach ($queries as $query) {
        $query = trim($query);
        if ($query) $pdo->exec($query);
    }
    
    echo "✅ Database & Tabel-tabel V2 berhasil diatur ulang.<br>";
    echo "✅ Akun Admin Default: <b>admin</b> / <b>admin123</b>.<br>";
    echo "✅ Contoh Undangan: <b>elvy-rokim</b>.<br>";
    echo "<br><a href='login.php' style='padding:10px 20px; background:#775a19; color:white; text-decoration:none; border-radius:5px;'>KLIK DISINI UNTUK LOGIN</a>";

} catch (PDOException $e) {
    echo "❌ Terjadi kesalahan: " . $e->getMessage();
}
