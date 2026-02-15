<?php
/**
 * PROJET-CMS-2026 - GESTION DE LA CORBEILLE (AFFICHAGE)
 */
require_once '../core/config.php';

$is_local = ($_SERVER['REMOTE_ADDR'] === '127.0.0.1' || $_SERVER['SERVER_NAME'] === 'localhost' || $_SERVER['REMOTE_ADDR'] === '::1');
if (!$is_local) { exit("Accès réservé."); }

$trash_dir = "../content/_trash/";
$archived_projects = [];

if (is_dir($trash_dir)) {
    $items = scandir($trash_dir);
    foreach ($items as $item) {
        if ($item !== '.' && $item !== '..' && is_dir($trash_dir . $item)) {
            $archived_projects[] = $item;
        }
    }
}

require_once '../includes/header.php'; 
?>

<main class="trash-page" style="padding-top: 120px; max-width: 1100px; margin: 0 auto;">
    <div style="padding: 0 20px;">
        <h1 style="font-weight: 800;">CORBEILLE</h1>
        <p style="color: #666; margin-bottom: 40px;">Projets archivés.</p>

        <?php if (empty($archived_projects)): ?>
            <div style="background: #f5f5f5; padding: 60px; text-align: center;">
                <p>La corbeille est vide.</p>
                <a href="<?php echo BASE_URL; ?>index.php">Retour</a>
            </div>
        <?php else: ?>
            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 20px;">
                <?php foreach ($archived_projects as $folder): 
                    $project_data = [];
                    $data_file = $trash_dir . $folder . '/data.php';
                    if (file_exists($data_file)) { $project_data = include $data_file; }
                    $image_url = (!empty($project_data['cover'])) ? BASE_URL . $project_data['cover'] : '';
                ?>
                    <div style="border: 1px solid #eee; padding: 15px; background: #fff;">
                        <div style="width: 100%; height: 150px; background: #eee; margin-bottom: 10px;">
                            <?php if ($image_url): ?>
                                <img src="<?php echo $image_url; ?>" style="width:100%; height:100%; object-fit:cover;">
                            <?php endif; ?>
                        </div>
                        <h3 style="font-size: 14px;"><?php echo htmlspecialchars($project_data['title'] ?? $folder); ?></h3>
                        <div style="margin-top: 15px; display: flex; gap: 10px;">
                            <a href="restore.php?project=<?php echo urlencode($folder); ?>" style="background:#000; color:#fff; padding: 5px 10px; text-decoration:none; font-size:11px;">RESTAURER</a>
                            <a href="purge.php?project=<?php echo urlencode($folder); ?>" onclick="return confirm('Supprimer définitivement ?')" style="color:red; font-size:11px;">PURGER</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</main>

<?php require_once '../includes/footer.php'; ?>