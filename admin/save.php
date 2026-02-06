<?php
/**
 * PROJET-CMS-2026 - SAUVEGARDE (VERSION ASSAINIE)
 * @author: Christophe Millot
 */

// 1. Sécurisation et chargement de la configuration
require_once '../core/config.php';

$is_local = ($_SERVER['REMOTE_ADDR'] === '127.0.0.1' || $_SERVER['SERVER_NAME'] === 'localhost');
if (!$is_local) { exit; }

// 2. Récupération des données (JSON ou POST)
$json = file_get_contents('php://input');
$decoded = json_decode($json, true);
$data = $decoded ? array_merge($_POST, $decoded) : $_POST;

if ($data && isset($data['slug'])) {
    // Nettoyage du slug
    $slug = preg_replace('/[^A-Za-z0-9-]+/', '-', strtolower($data['slug']));
    $dir = "../content/" . $slug;
    
    if (!file_exists($dir)) { 
        mkdir($dir, 0777, true); 
    }

    $file_path = $dir . "/data.php";
    
    // 3. Préparation du Design System
    $ds = $data['designSystem'];
    if(is_string($ds)) {
        $ds = json_decode($ds, true);
    }
    
    // 4. Construction du fichier PHP (Formatage rigoureux)
    $content_file = "<?php\n";
    $content_file .= "/** Fichier généré par Studio CMS - " . date('d.m.Y H:i') . " **/\n\n";
    $content_file .= "\$title = " . var_export($data['title'] ?? 'Sans titre', true) . ";\n";
    $content_file .= "\$cover = " . var_export($data['cover'] ?? '', true) . ";\n";
    $content_file .= "\$category = " . var_export($data['category'] ?? 'Design', true) . ";\n";
    $content_file .= "\$date = " . var_export(date('d.m.Y'), true) . ";\n";
    $content_file .= "\$summary = " . var_export($data['summary'] ?? '', true) . ";\n";
    $content_file .= "\$designSystem = " . var_export($ds, true) . ";\n";
    
    // On conserve la double variable pour la compatibilité ascendante (Legacy)
    // Mais on prépare la transition vers une variable unique propre
    $htmlContentRaw = $data['htmlContent'] ?? '';
    $content_file .= "\$htmlContent = " . var_export($htmlContentRaw, true) . ";\n";
    $content_file .= "\$content = " . var_export($htmlContentRaw, true) . ";\n"; 
    
    $content_file .= "?>";

    // 5. Écriture physique
    if (file_put_contents($file_path, $content_file)) {
        header('Content-Type: application/json');
        echo json_encode(["status" => "success", "message" => "Projet publié avec succès !"]);
    } else {
        header('Content-Type: application/json');
        echo json_encode(["status" => "error", "message" => "Erreur d'écriture sur le serveur."]);
    }
} else {
    header('Content-Type: application/json');
    echo json_encode(["status" => "error", "message" => "Données manquantes (Slug absent)."]);
}