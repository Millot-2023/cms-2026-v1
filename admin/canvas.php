<?php
/**
 * PROJET-CMS-2026 - CANVAS DE RENDU ISOLÉ (V3)
 * @author: Christophe Millot
 */
require_once '../core/config.php';

$slug = $_GET['project'] ?? '';
$content_dir = "../content/";
$data_path = $content_dir . $slug . '/data.php';

$title = "Nouveau Projet";
$htmlContent = "";

if (file_exists($data_path)) {
    $data = include $data_path;
    $title = $data['title'] ?? $title;
    $htmlContent = $data['htmlContent'] ?? '';
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;700;900&display=swap">
    <link rel="stylesheet" href="../assets/css/main.css">
    <style>
        /* Style spécifique au papier dans l'iframe */
        body { background: transparent; margin: 0; padding: 20px; display: flex; justify-content: center; }
        .paper { 
            width: 100%; 
            max-width: 850px; 
            background: #ffffff; 
            color: #000000; 
            min-height: 29.7cm; 
            padding: 80px; 
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            outline: none;
        }
        /* Désactiver les liens dans l'éditeur pour éviter de sortir de l'iframe */
        a { pointer-events: none; color: inherit; }
        
        /* Indicateur de zone éditable */
        [contenteditable="true"]:focus { outline: none; background: rgba(0,123,255,0.05); }
    </style>
</head>
<body>
    <article class="paper" id="paper-viewport">
        <h1 id="editable-title" contenteditable="true"><?php echo htmlspecialchars($title); ?></h1>
        <div id="editable-core" contenteditable="true">
            <?php echo $htmlContent; ?>
        </div>
    </article>

    <script>
        // Ce script permet à l'Iframe de dire à la Sidebar : "Je suis prête !"
        window.onload = () => {
            window.parent.postMessage({ type: 'ready' }, '*');
        };
    </script>
</body>
</html>