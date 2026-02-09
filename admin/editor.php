<?php
/**
 * PROJET-CMS-2026 - ÉDITEUR DESIGN SYSTEM (VERSION v3.0 - ARCHITECTURE ISOLÉE)
 * @author: Christophe Millot
 */

// 1. Chargement de la configuration centrale
require_once '../core/config.php';

$is_local = ($_SERVER['REMOTE_ADDR'] === '127.0.0.1' || $_SERVER['SERVER_NAME'] === 'localhost');
if (!$is_local) { die("Acces reserve."); exit; }

$content_dir = "../content/";
$trash_dir   = "../content/_trash/";

// --- LOGIQUE DE GESTION DE LA CORBEILLE ---
if (isset($_GET['action']) && isset($_GET['slug'])) {
    $action = $_GET['action'];
    $slug   = $_GET['slug'];

    if ($action === 'restore') {
        $parts = explode('_', $slug, 2);
        $original_name = isset($parts[1]) ? $parts[1] : $slug;
        if (rename($trash_dir . $slug, $content_dir . $original_name)) {
            header('Location: ' . BASE_URL . 'index.php?status=restored');
            exit;
        }
    }

    if ($action === 'purge') {
        $target = $trash_dir . $slug;
        $files = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($target, RecursiveDirectoryIterator::SKIP_DOTS),
            RecursiveIteratorIterator::CHILD_FIRST
        );
        foreach ($files as $fileinfo) {
            $todo = ($fileinfo->isDir() ? 'rmdir' : 'unlink');
            $todo($fileinfo->getRealPath());
        }
        if (rmdir($target)) {
            header('Location: trash.php?status=purged');
            exit;
        }
    }
}

$slug = isset($_GET['project']) ? $_GET['project'] : '';
if (empty($slug)) {
    header('Location: ' . BASE_URL . 'index.php');
    exit;
}

// Chargement des données pour l'initialisation du cockpit
$title = "Titre du Projet";
$summary = "";
$cover = ""; 
$designSystemArray = [];

$data_path = $content_dir . $slug . '/data.php';
if (file_exists($data_path)) {
    $data_loaded = include $data_path;
    if (is_array($data_loaded)) {
        $title = $data_loaded['title'] ?? $title;
        $summary = $data_loaded['summary'] ?? $summary;
        $cover = $data_loaded['cover'] ?? $cover;
        $designSystemArray = $data_loaded['designSystem'] ?? [];
    }
}

$cover_path = "";
if (!empty($cover)) {
    $cover_path = (strpos($cover, 'data:image') === 0) ? $cover : $content_dir . $slug . '/' . $cover;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>STUDIO V3 - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;700;900&display=swap">
    <link rel="stylesheet" href="../assets/css/main.css">
    <style>
        body, html { margin: 0; padding: 0; height: 100vh; overflow: hidden; background: #1a1a1a; font-family: 'Inter', sans-serif; }
        .studio-container { display: flex; height: 100vh; width: 100vw; }
        
        .sidebar { width: 340px; background: #000000; border-right: 1px solid #333; display: flex; flex-direction: column; z-index: 100; color: #fff; }
        .sidebar-scroll { flex: 1; overflow-y: auto; padding: 20px; }
        
        .canvas-area { flex: 1; position: relative; background: #1a1a1a; display: flex; align-items: center; justify-content: center; padding: 40px; }
        
        #viewport {
            width: 100%;
            max-width: 900px;
            height: 100%;
            border: none;
            background: #fff;
            box-shadow: 0 30px 100px rgba(0,0,0,0.5);
            transition: width 0.3s ease;
        }

        .admin-input { width: 100%; background: #111; border: 1px solid #333; color: #fff; padding: 12px; margin-bottom: 15px; border-radius: 4px; font-size: 11px; outline: none; }
        .section-label { font-size: 9px; color: #666; text-transform: uppercase; margin: 20px 0 10px; display: block; letter-spacing: 1px; }
        .tool-btn { background: #1a1a1a; border: 1px solid #333; color: #fff; padding: 10px; cursor: pointer; font-size: 10px; width: 100%; border-radius: 4px; margin-bottom: 5px; text-transform: uppercase; transition: 0.2s; }
        .tool-btn:hover { background: #222; border-color: #555; }
        .btn-publish { background: #fff !important; color: #000 !important; font-weight: 900; padding: 15px; margin-top: 20px; border: none; }
    </style>
</head>
<body class="v3-studio">

    <div class="studio-container">
        
        <aside class="sidebar">
            <div class="sidebar-scroll">
                <h2 style="font-size: 10px; letter-spacing: 3px; color: #666; margin-bottom: 30px;">CMS-2026 STUDIO</h2>

                <span class="section-label">Projet Actuel</span>
                <input type="text" id="inp-slug" class="admin-input" value="<?php echo htmlspecialchars($slug); ?>" readonly>
                <textarea id="inp-summary" class="admin-input" placeholder="Résumé du projet..." style="height:80px;"><?php echo htmlspecialchars($summary); ?></textarea>

                <span class="section-label">Structure</span>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 5px;">
                    <button class="tool-btn" onclick="studio.addBlock('h2')">Titre H2</button>
                    <button class="tool-btn" onclick="studio.addBlock('h3')">Titre H3</button>
                    <button class="tool-btn" onclick="studio.addBlock('p')">Texte</button>
                    <button class="tool-btn" onclick="studio.addBlock('image')">Image</button>
                </div>

                <span class="section-label">Design System</span>
                <div class="gauge-row">
                    <input type="range" id="slider-size" min="8" max="150" value="80">
                </div>

                <button id="btn-save" class="tool-btn btn-publish">Mettre à jour</button>
            </div>

            <div style="padding: 20px; border-top: 1px solid #333;">
                <a href="<?php echo BASE_URL; ?>index.php" style="color: #666; text-decoration: none; font-size: 10px; text-transform: uppercase;">⬅ Retour au Cockpit</a>
            </div>
        </aside>

        <main class="canvas-area">
            <iframe id="viewport" src="canvas.php?project=<?php echo $slug; ?>"></iframe>
        </main>

    </div>

    <script src="../assets/js/editor-engine.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            console.log("V3 Studio : Mode Isolé activé.");
        });
    </script>
</body>
</html>