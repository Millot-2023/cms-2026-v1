<?php
/**
 * PROJET-CMS-2026 - ÉDITEUR MÉCANIQUE (MAJ : RESTORE & PURGE)
 */

$is_local = ($_SERVER['REMOTE_ADDR'] === '127.0.0.1' || $_SERVER['SERVER_NAME'] === 'localhost');
if (!$is_local) { die("Acces reserve."); exit; }

$action = isset($_GET['action']) ? $_GET['action'] : '';
$slug   = isset($_GET['slug']) ? $_GET['slug'] : '';

// --- ACTION : CRÉATION ---
if ($action === 'new') {
    $new_slug = 'projet-' . date('Ymd-His');
    $target_path = '../content/' . $new_slug;
    if (!is_dir($target_path)) {
        mkdir($target_path, 0777, true);
        $d = date('d.m.Y');
        $php_content = "<?php\n" . chr(36) . "title = 'NOUVEAU PROJET';\n" . chr(36) . "category = 'DESIGN';\n" . chr(36) . "date = '$d';\n" . chr(36) . "summary = 'Résumé...';\n?>";
        file_put_contents($target_path . '/data.php', $php_content);
        if (file_exists('../assets/img/placeholder.jpg')) copy('../assets/img/placeholder.jpg', $target_path . '/thumb.jpg');
        header("Location: ../index.php?status=success#main"); exit;
    }
}

// --- ACTION : ARCHIVAGE (DELETE) ---
if ($action === 'delete' && !empty($slug)) {
    $source = '../content/' . $slug;
    $trash = '../content/_trash';
    if (!is_dir($trash)) mkdir($trash, 0777, true);
    rename($source, $trash . '/' . date('Ymd-His') . '_' . $slug);
    header("Location: ../index.php?status=archived#main"); exit;
}

// --- ACTION : RESTAURER ---
if ($action === 'restore' && !empty($slug)) {
    $source = '../content/_trash/' . $slug;
    $original_name = substr($slug, 16); // On retire le timestamp
    $target = '../content/' . $original_name;
    
    if (is_dir($source)) {
        rename($source, $target);
        header("Location: trash.php?status=restored"); exit;
    }
}

// --- ACTION : PURGE DÉFINITIVE ---
if ($action === 'purge' && !empty($slug)) {
    $target = '../content/_trash/' . $slug;
    if (is_dir($target)) {
        // Fonction simple pour supprimer un dossier et son contenu
        $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($target, RecursiveDirectoryIterator::SKIP_DOTS), RecursiveIteratorIterator::CHILD_FIRST);
        foreach ($files as $fileinfo) {
            ($fileinfo->isDir()) ? rmdir($fileinfo->getRealPath()) : unlink($fileinfo->getRealPath());
        }
        rmdir($target);
        header("Location: trash.php?status=purged"); exit;
    }
}

header("Location: ../index.php"); exit;