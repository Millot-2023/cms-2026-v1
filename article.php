<?php 
require_once 'core/config.php'; 

// Récupération sécurisée du slug
$slug = $_GET['slug'] ?? '';
$path_data = 'content/' . $slug . '/data.php';

if (!empty($slug) && file_exists($path_data)) {
    include $path_data;
} else {
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title; ?> - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="<?php echo ASSETS_URL; ?>css/main.css">
</head>
<body>

    <div class="master-grid">
        
        <?php include INC_PATH . 'header.php'; ?>

        <section class="hero-section full-width" style="background-image: url('content/<?php echo $slug; ?>/thumb.jpg'); background-size: cover; background-position: center;">
            <div class="hero-content">
                <span class="tag"><?php echo $category; ?></span>
                <h1><?php echo $title; ?></h1>
                <time><?php echo date('d F Y', strtotime($date)); ?></time>
            </div>
        </section>

        <main id="main">
            <div class="bloc-manifeste align-left">
                <p><?php echo $summary; ?></p>
            </div>

            </main>

        <?php include INC_PATH . 'footer.php'; ?>

    </div>

    <?php include 'admin/editor.php'; ?>

    <script src="<?php echo ASSETS_URL; ?>js/evolution.js"></script>
</body>
</html>