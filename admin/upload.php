<?php
// admin/upload.php
header('Content-Type: application/json');

// dirname(__DIR__) remplace le chemin en dur /cms-2026-v5/
// Cela permet de remonter d'un cran (sortir de admin/) et d'aller dans assets/img/
$targetDir = dirname(__DIR__) . '/assets/img/';

if (isset($_FILES['file'])) {
    
    if (!file_exists($targetDir)) {
        mkdir($targetDir, 0777, true);
    }

    $originalName = basename($_FILES['file']['name']);
    $fileName = time() . '_' . $originalName;
    $targetPath = $targetDir . $fileName;

    if (move_uploaded_file($_FILES['file']['tmp_name'], $targetPath)) {
        // Succès : on renvoie le chemin relatif pour la sauvegarde
        echo json_encode([
            'success' => true, 
            'path' => 'assets/img/' . $fileName
        ]);
    } else {
        $error = error_get_last();
        echo json_encode([
            'success' => false, 
            'message' => 'Erreur de copie vers : ' . $targetDir
        ]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Aucun fichier reçu.']);
}
exit;