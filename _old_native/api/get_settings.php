<?php
require_once 'db.php';

try {
    $db = getDB();
    $stmt = $db->query("SELECT * FROM settings");
    $settings = $stmt->fetchAll();
    $data = [];
    foreach ($settings as $s) {
        $data[$s['key_name']] = $s['key_value'];
    }
    jsonResponse(['success' => true, 'data' => $data]);
} catch (PDOException $e) {
    jsonResponse(['success' => false, 'message' => 'Database error'], 500);
}
