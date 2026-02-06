<?php
/**
 * PROJET-CMS-2026 - SAUVEGARDE (STABLE)
 * @author: Christophe Millot
 */

$is_local = ($_SERVER['REMOTE_ADDR'] === '127.0.0.1' || $_SERVER['SERVER_NAME'] === 'localhost');
if (!$is_local) { exit; }

// 1. On récupère les données peu importe le format d'envoi
$json = file_get_contents('php://input');
$decoded = json_decode($json, true);

// On fusionne les données du JSON et du POST classique
$data = $decoded ? array_merge($_POST, $decoded) : $_POST;

if ($data && isset($data['slug'])) {
    // Nettoyage du slug
    $slug = preg_replace('/[^A-Za-z0-9-]+/', '-', strtolower($data['slug']));
    $dir = "../content/" . $slug;
    
    if (!file_exists($dir)) { 
        mkdir($dir, 0777, true); 
    }

    $file_path = $dir . "/data.php";
    
    // 2. Préparation du Design System (on le décode si c'est du JSON string)
    $ds = $data['designSystem'];
    if(is_string($ds)) {
        $ds = json_decode($ds, true);
    }
    
    // 3. Construction du fichier PHP
    $content = "<?php\n";
    $content .= "/** Fichier généré par Studio CMS **/\n\n";
    $content .= "\$title = " . var_export($data['title'] ?? 'Sans titre', true) . ";\n";
    $content .= "\$cover = " . var_export($data['cover'] ?? '', true) . ";\n";
    $content .= "\$category = " . var_export($data['category'] ?? 'Design', true) . ";\n";
    $content .= "\$date = " . var_export(date('d.m.Y'), true) . ";\n";
    $content .= "\$summary = " . var_export($data['summary'] ?? '', true) . ";\n";
    $content .= "\$designSystem = " . var_export($ds, true) . ";\n";
    
    // DOUBLE SÉCURITÉ : On enregistre sous les deux noms de variables
    $htmlContentRaw = $data['htmlContent'] ?? '';
    $content .= "\$htmlContent = " . var_export($htmlContentRaw, true) . ";\n";
    $content .= "\$content = " . var_export($htmlContentRaw, true) . ";\n"; // Doublon de sécurité
    
    $content .= "?>";

    if (file_put_contents($file_path, $content)) {
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