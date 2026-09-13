<?php
require_once 'db.php';

$inv_id = $_GET['inv_id'] ?? 1;

try {
    $db = getDB();
    
    // Get active music for this invitation
    $stmt = $db->prepare("SELECT file_path, file_name FROM music WHERE invitation_id = ? AND is_active = 1 LIMIT 1");
    $stmt->execute([$inv_id]);
    $music = $stmt->fetch();
    
    // Get music settings
    $stmt = $db->prepare("SELECT key_name, key_value FROM settings WHERE invitation_id = ? AND key_name IN ('music_volume', 'music_autoplay')");
    $stmt->execute([$inv_id]);
    $settingsRaw = $stmt->fetchAll();
    
    $settings = [
        'music_volume' => 50,
        'music_autoplay' => 1
    ];
    
    foreach ($settingsRaw as $s) {
        $settings[$s['key_name']] = $s['key_value'];
    }
    
    if ($music) {
        jsonResponse([
            'success' => true,
            'music' => [
                'file_path' => $music['file_path'],
                'file_name' => $music['file_name'],
                'volume' => intval($settings['music_volume']) / 100,
                'autoplay' => intval($settings['music_autoplay']) === 1
            ]
        ]);
    } else {
        // Fallback to default music
        jsonResponse([
            'success' => true,
            'music' => [
                'file_path' => 'lagu.mp3',
                'file_name' => 'Default Music',
                'volume' => intval($settings['music_volume']) / 100,
                'autoplay' => intval($settings['music_autoplay']) === 1
            ]
        ]);
    }
    
} catch (PDOException $e) {
    jsonResponse(['success' => false, 'message' => $e->getMessage()], 500);
}