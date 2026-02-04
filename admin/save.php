<?php
/**
 * PROJET-CMS-2026 - SAUVEGARDE
 * @author: Christophe Millot
 */

// Sécurité locale stricte
$is_local = ($_SERVER['REMOTE_ADDR'] === '127.0.0.1' || $_SERVER['SERVER_NAME'] === 'localhost');
if (!$is_local) { 
    header('Content-Type: application/json');
    echo json_encode(["status" => "error", "message" => "Acces reserve au mode local."]);
    exit; 
}

// Récupération du flux JSON envoyé par l'éditeur
$json = file_get_contents('php://input');
$data = json_decode($json, true);

if ($data && isset($data['slug'])) {
    // Définition du chemin du dossier projet
    $dir = "../content/" . $data['slug'];
    
    // Création du dossier s'il n'existe pas (droits 0777 pour XAMPP)
    if (!file_exists($dir)) { 
        mkdir($dir, 0777, true); 
    }

    $file_path = $dir . "/data.php";
    
    // Préparation du contenu structuré pour data.php
    $content = "<?php\n";
    $content .= "/**\n * DONNÉES GÉNÉRÉES PAR L'ÉDITEUR\n */\n\n";
    $content .= "\$title = " . var_export($data['title'], true) . ";\n";
    $content .= "\$designSystem = " . var_export($data['designSystem'], true) . ";\n";
    $content .= "\$htmlContent = " . var_export($data['htmlContent'], true) . ";\n";
    $content .= "?>";

    // Écriture du fichier sur le disque
    if (file_put_contents($file_path, $content)) {
        header('Content-Type: application/json');
        echo json_encode(["status" => "success", "message" => "Design publie avec succes dans " . $data['slug']]);
    } else {
        header('Content-Type: application/json');
        echo json_encode(["status" => "error", "message" => "Echec de l'ecriture du fichier data.php"]);
    }
} else {
    header('Content-Type: application/json');
    echo json_encode(["status" => "error", "message" => "Donnees JSON invalides ou manquantes."]);
}