<?php
/**
 * PROJET-CMS-2026 - SUPPRESSION DÉFINITIVE (PURGE)
 */
require_once '../core/config.php';

$is_local = ($_SERVER['REMOTE_ADDR'] === '127.0.0.1' || $_SERVER['SERVER_NAME'] === 'localhost' || $_SERVER['REMOTE_ADDR'] === '::1');
if (!$is_local) { exit; }

function delete_directory($dir) {
    if (!file_exists($dir)) { return true; }
    if (!is_dir($dir)) { return unlink($dir); }

    foreach (scandir($dir) as $item) {
        if ($item == '.' || $item == '..') { continue; }
        if (!delete_directory($dir . DIRECTORY_SEPARATOR . $item)) { return false; }
    }
    return rmdir($dir);
}

if (isset($_GET['project'])) {
    // On récupère le nom brut sans le modifier pour correspondre exactement au dossier
    $folder_name = $_GET['project']; 
    $target = "../content/_trash/" . $folder_name;

    if (is_dir($target)) {
        if (delete_directory($target)) {
            header("Location: " . BASE_URL . "admin/trash.php?status=purged");
            exit;
        }
    }
}

header("Location: " . BASE_URL . "admin/trash.php?status=error_purge");
exit;