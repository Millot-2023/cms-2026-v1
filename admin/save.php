<?php
/**
 * PROJET-CMS-2026 - MOTEUR DE SAUVEGARDE V5 (PROPRE)
 */

header('Content-Type: application/json');
require_once '../core/config.php';

$is_local = ($_SERVER['REMOTE_ADDR'] === '127.0.0.1' || $_SERVER['SERVER_NAME'] === 'localhost');
if (!$is_local) {
    echo json_encode(['status' => 'error', 'message' => 'Accès refusé.']);
    exit;
}

$data = $_POST;
$slug = $data['slug'] ?? '';

if (empty($slug)) {
    echo json_encode(['status' => 'error', 'message' => 'Le slug est manquant.']);
    exit;
}

$slug = preg_replace('/[^A-Za-z0-9-]+/', '-', strtolower($slug));
$dir = "../content/" . $slug;
if (!file_exists($dir)) { mkdir($dir, 0777, true); }

$file_path = $dir . "/data.php";

// 1. Récupération des données existantes
$existingData = [];
if (file_exists($file_path)) {
    $loaded = @include $file_path;
    if (is_array($loaded)) { $existingData = $loaded; }
}

// 2. Décodage des Blocs et du Design System
$ds = $data['designSystem'] ?? [];
if(is_string($ds)) { $ds = json_decode($ds, true); }

$blocks = $data['blocks'] ?? [];
if(is_string($blocks)) { $blocks = json_decode($blocks, true); }

// 3. Gestion de la cover (On récupère le chemin simple)
// 3. Gestion de la cover (Nettoyage de sécurité)
$coverValue = $data['cover'] ?? ($existingData['cover'] ?? '');

if (!empty($coverValue) && strpos($coverValue, 'data:image') === false) {
    // On extrait uniquement le nom du fichier (ex: 123_photo.jpg)
    // Quoi qu'il arrive (chemin avec content, ../, ou localhost), on ne garde que la fin
    $coverValue = basename($coverValue);
    
    // On enregistre le chemin propre par rapport à la racine
    $coverValue = 'assets/img/' . $coverValue;
}







// 4. Préparation du tableau final
$finalData = [
    'title'        => $data['title'] ?? ($existingData['title'] ?? 'Sans titre'),
    'cover'        => $coverValue,
    'category'     => $data['category'] ?? ($existingData['category'] ?? 'Music'),
    'date'         => $existingData['date'] ?? date('d.m.Y'),
    'updated'      => date('Y-m-d H:i:s'),
    'summary'      => $data['summary'] ?? ($existingData['summary'] ?? ''),
    'designSystem' => $ds,
    'blocks'       => $blocks, // On sauve les blocs structurés !
    'htmlContent'  => $data['htmlContent'] ?? '' // On garde au cas où pour la compatibilité
];

// 5. Écriture du fichier
$content_file = "<?php\n/** Fichier généré proprement - " . date('d.m.Y H:i') . " **/\n";
$content_file .= "return " . var_export($finalData, true) . ";\n";

if (file_put_contents($file_path, $content_file)) {
    echo json_encode(["status" => "success", "message" => "Projet enregistré avec succès dans assets/img/"]);
} else {
    echo json_encode(["status" => "error", "message" => "Erreur d'écriture."]);
}