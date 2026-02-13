<?php
// admin/upload.php
header('Content-Type: application/json');

// On définit le chemin réel sur le serveur
// $_SERVER['DOCUMENT_ROOT'] donne "C:/xampp/htdocs"
// On y ajoute le chemin vers ton projet
$targetDir = $_SERVER['DOCUMENT_ROOT'] . '/cms-2026-v5/assets/img/';

if (isset($_FILES['file'])) {
    
    // Création du dossier si Windows fait de la résistance
    if (!file_exists($targetDir)) {
        mkdir($targetDir, 0777, true);
    }

    $originalName = basename($_FILES['file']['name']);
    $fileName = time() . '_' . $originalName;
    $targetPath = $targetDir . $fileName;

    if (move_uploaded_file($_FILES['file']['tmp_name'], $targetPath)) {
        // Succès : on renvoie le chemin relatif pour l'affichage HTML
        echo json_encode([
            'success' => true, 
            'path' => 'assets/img/' . $fileName
        ]);
    } else {
        // En cas d'erreur, on demande à PHP d'être très précis
        $error = error_get_last();
        echo json_encode([
            'success' => false, 
            'message' => 'Erreur de copie vers : ' . $targetDir . ' | Raison : ' . ($error['message'] ?? 'Droits Windows insuffisants')
        ]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Aucun fichier reçu par le serveur.']);
}
exit;