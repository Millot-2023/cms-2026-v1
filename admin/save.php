<?php
/**
 * PROJET-CMS-2026 - MOTEUR DE SAUVEGARDE V3 (DESIGN SYSTEM READY)
 * @author: Christophe Millot
 */

header('Content-Type: application/json');
require_once '../core/config.php';

// Sécurité : accès local uniquement
$is_local = ($_SERVER['REMOTE_ADDR'] === '127.0.0.1' || $_SERVER['SERVER_NAME'] === 'localhost');
if (!$is_local) {
    echo json_encode(['status' => 'error', 'message' => 'Accès refusé.']);
    exit;
}

// 1. Récupération et nettoyage des données
$slug        = $_POST['slug'] ?? '';
$title       = $_POST['title'] ?? 'Sans titre';
$summary     = $_POST['summary'] ?? '';
$htmlContent = $_POST['htmlContent'] ?? '';
$coverData   = $_POST['coverImage'] ?? ''; 

// RÉCUPÉRATION DU DESIGN SYSTEM (JSON envoyé par editor-engine.js)
$designSystemRaw = $_POST['designSystem'] ?? '[]';
$designSystemArray = json_decode($designSystemRaw, true) ?? [];

if (empty($slug)) {
    echo json_encode(['status' => 'error', 'message' => 'Slug manquant.']);
    exit;
}

$project_dir = "../content/" . $slug . "/";
if (!is_dir($project_dir)) {
    mkdir($project_dir, 0777, true);
}

// 2. Gestion de l'image de couverture (Extraction physique)
$cover_filename = "thumb.jpg"; 
if (strpos($coverData, 'data:image') === 0) {
    $data_parts = explode(',', $coverData);
    if (count($data_parts) > 1) {
        $img_binary = base64_decode($data_parts[1]);
        file_put_contents($project_dir . $cover_filename, $img_binary);
    }
} elseif (!empty($coverData)) {
    $cover_filename = basename($coverData);
}

// 3. Préparation du fichier data.php (Format propre et persistant)
$php_data = "<?php\n";
$php_data .= "/**\n * DATA DU PROJET : " . strtoupper($slug) . "\n * Généré le : " . date('Y-m-d H:i:s') . "\n */\n\n";
$php_data .= "return [\n";
$php_data .= "    'title'        => " . var_export($title, true) . ",\n";
$php_data .= "    'slug'         => " . var_export($slug, true) . ",\n";
$php_data .= "    'category'     => 'Design',\n"; 
$php_data .= "    'summary'      => " . var_export($summary, true) . ",\n";
$php_data .= "    'cover'        => " . var_export($cover_filename, true) . ",\n";
$php_data .= "    'htmlContent'  => " . var_export($htmlContent, true) . ",\n";
$php_data .= "    'designSystem' => " . var_export($designSystemArray, true) . "\n"; 
$php_data .= "];\n";

// 4. Écriture sécurisée sur le disque
if (file_put_contents($project_dir . "data.php", $php_data)) {
    echo json_encode([
        'status'  => 'success',
        'message' => 'Projet et Design System mis à jour avec succès.',
        'fileName'=> $cover_filename
    ]);
} else {
    echo json_encode([
        'status'  => 'error',
        'message' => 'Erreur lors de l\'écriture du fichier data.php'
    ]);
}