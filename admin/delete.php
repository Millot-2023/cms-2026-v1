<?php
/**
 * PROJET-CMS-2026 - SUPPRESSION CHIRURGICALE
 * @author: Christophe Millot
 */

require_once '../core/config.php';

$is_local = ($_SERVER['REMOTE_ADDR'] === '127.0.0.1' || $_SERVER['SERVER_NAME'] === 'localhost');
if (!$is_local) { exit; }

if (isset($_GET['project'])) {
    $project = preg_replace('/[^A-Za-z0-9-]+/', '-', $_GET['project']);
    $target = "../content/" . $project;

    if (is_dir($target)) {
        // Fonction récursive pour vider le dossier avant suppression
        $files = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($target, RecursiveDirectoryIterator::SKIP_DOTS),
            RecursiveIteratorIterator::CHILD_FIRST
        );

        foreach ($files as $fileinfo) {
            $todo = ($fileinfo->isDir() ? 'rmdir' : 'unlink');
            $todo($fileinfo->getRealPath());
        }
        
        rmdir($target);
        
        // Redirection vers le cockpit avec succès
        header("Location: ../index.php?status=deleted");
        exit;
    }
}

header("Location: ../index.php?status=error");
exit;