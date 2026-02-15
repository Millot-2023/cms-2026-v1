<?php
/**
 * PROJET-CMS-2026 - RESTAURATION DEPUIS LA CORBEILLE
 * @author: Christophe Millot
 */

require_once '../core/config.php';

// Sécurité locale (IPv4 + IPv6)
$is_local = ($_SERVER['REMOTE_ADDR'] === '127.0.0.1' || $_SERVER['SERVER_NAME'] === 'localhost' || $_SERVER['REMOTE_ADDR'] === '::1');
if (!$is_local) { exit; }

if (isset($_GET['project'])) {
    // 1. On récupère le nom COMPLET du dossier (ex: 20260215-113428_mon-projet)
    $folder_name = $_GET['project']; 
    
    // 2. On définit la source (dans la corbeille)
    $source = "../content/_trash/" . $folder_name;

    // 3. On prépare la destination (dans content/) en enlevant le timestamp
    // On cherche l'underscore qui sépare la date du nom
    if (strpos($folder_name, '_') !== false) {
        $parts = explode('_', $folder_name, 2);
        $clean_name = $parts[1]; // On prend ce qu'il y a après l'underscore
    } else {
        $clean_name = $folder_name;
    }
    
    $destination = "../content/" . $clean_name;

    // 4. Action de restauration
    if (is_dir($source)) {
        // Si un dossier du même nom existe déjà dans content, on le supprime ou on renomme
        if (is_dir($destination)) {
            $destination .= "-restored-" . time();
        }

        if (rename($source, $destination)) {
            header("Location: " . BASE_URL . "index.php?status=restored");
            exit;
        }
    }
}

// Si on arrive ici, c'est qu'il y a eu un problème
header("Location: " . BASE_URL . "index.php?status=error_restore");
exit;