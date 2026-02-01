<?php
// projet-cms-2026 - Configuration racine
define('SITE_NAME', 'CMS EVOLUTION');
define('BASE_URL', 'http://localhost/projet-cms-2026/');
define('ASSETS_URL', BASE_URL . 'assets/');
define('INC_PATH', __DIR__ . '/../includes/');

// Gestion des dates
date_default_timezone_set('Europe/Paris');
setlocale(LC_TIME, 'fr_FR.utf8', 'fra');
?>