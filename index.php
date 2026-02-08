<?php
/**
 * PROJET-CMS-2026 - ARCHITECTURE NETTOYÉE
 * Focus : Correction structurelle et restauration des accès
 * @author: Christophe Millot
 */
require_once 'core/config.php';
require_once 'includes/header.php'; 
require_once 'includes/hero.php'; 
?>

<div class="master-grid">
    <main id="main">
        <h1 class="section-title">Gestion des Projets</h1>

        <div class="grid-container">
            
            <div class="grid-block">
                <div class="card-image">
                    <img src="assets/img/image-template.png" alt="Modèle de projet">
                </div>
                
                <div class="card-content">
                    <header class="article-header">
                        <div style="display: flex; justify-content: space-between; align-items: center; font-size: 0.75rem; margin-bottom: 0.5rem; text-transform: uppercase; letter-spacing: 0.5px;">
                            <span class="category" style="font-weight: bold; color: #888;">SYSTÈME</span>
                            <p class="date" style="margin: 0; color: #666;"><?php echo date('d F Y'); ?></p>
                        </div>
                        <h1 class="main-article-title">Nouveau Projet</h1>
                    </header>
                    
                    <p>Initialiser un nouvel article ou une nouvelle étude de cas dans le CMS.</p>
                    
                    <div class="card-footer" style="margin-top: auto;">
                        <a href="admin/editor.php?project=nouveau-projet-<?php echo time(); ?>" class="btn-create" style="display: block; text-align: center; background: #000; color: #fff; padding: 0.8rem; border-radius: 8px; text-decoration: none; font-weight: bold; border: 1px solid #444;">
                            CRÉER
                        </a>
                    </div>
                </div>
            </div>

            <?php
            $content_path = 'content/';
            if (is_dir($content_path)) {
                $folders = array_diff(scandir($content_path), array('..', '.', '_trash'));
                
                foreach ($folders as $folder) {
                    if (strpos($folder, '_') === 0) continue;

                    $project_dir = $content_path . $folder;
                    $data_file = $project_dir . '/data.php';
                    
                    if (file_exists($data_file)) {
                        $title = "Sans titre";
                        $category = "Non classé";
                        $date = "--/--/--";
                        $cover = "";
                        $summary = "";

                        include $data_file; 
                        ?>
                        <article class="grid-block" style="position: relative;">
                            
                            <a href="javascript:void(0);" 
                               onclick="confirmDelete('<?php echo $folder; ?>')" 
                               class="btn-trash-overlay" 
                               title="Supprimer définitivement">×</a>

                            <div class="card-image">
                                <?php if(!empty($cover)): ?>
                                    <?php 
                                        $image_src = (strpos($cover, 'data:image') === 0) ? $cover : $content_path . $folder . '/' . $cover;
                                    ?>
                                <img src="<?php echo $image_src; ?>" alt="<?php echo htmlspecialchars($title); ?>">
                                <?php else: ?>
                                    <img src="assets/img/image-template.png" alt="Pas d'image">
                                <?php endif; ?>
                            </div>
                            
                            <div class="card-content">
                                <header class="article-header">
                                    <div style="display: flex; justify-content: space-between; align-items: center; font-size: 0.75rem; margin-bottom: 0.5rem; text-transform: uppercase; letter-spacing: 0.5px;">
                                        <span class="category" style="font-weight: bold; color: #888;"><?php echo htmlspecialchars($category); ?></span>
                                        <p class="date" style="margin: 0; color: #666;"><?php echo htmlspecialchars($date); ?></p>
                                    </div>
                                    <h1 class="main-article-title"><?php echo htmlspecialchars($title); ?></h1>
                                </header>
                                
                                <p><?php echo mb_strimwidth(strip_tags($summary), 0, 140, "..."); ?></p>
                                
                                <div class="card-footer" style="display: flex; align-items: center; gap: 10px; margin-top: auto;">
                                    <div style="display: flex; flex: 1; gap: 10px;">
                                        <a href="admin/editor.php?project=<?php echo $folder; ?>" class="btn-open" style="flex: 1; text-align: center; border: 1px solid #555; color: #fff; padding: 0.6rem; border-radius: 6px; text-decoration: none; font-weight: bold; font-size: 0.75rem; background: #333;">
                                            ÉDITER
                                        </a>
                                        <a href="article.php?slug=<?php echo $folder; ?>" class="btn-open" style="flex: 1; text-align: center; background: #444; color: #fff; padding: 0.6rem; border-radius: 6px; text-decoration: none; font-weight: bold; font-size: 0.75rem;">
                                            LIRE
                                        </a>
                                    </div>

                                    </div>
                            </div>
                        </article>
                        <?php
                    }
                }
            }
            ?>
        </div> 
    </main>
</div>

<script>
function confirmDelete(slug) {
    const confirmation = confirm("ALERTE SÉCURITÉ - Christophe :\n\nConfirmer la suppression du dossier [" + slug + "] ?");
    if (confirmation) {
        window.location.href = "admin/delete.php?project=" + slug;
    }
}
</script>

<style>
.btn-trash-overlay {
    position: absolute;
    top: 10px;
    right: 10px;
    width: 28px;
    height: 28px;
    background: #000;
    color: #fff;
    text-decoration: none;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    font-weight: bold;
    z-index: 100;
    transition: all 0.2s ease;
    border: 1px solid rgba(255,255,255,0.2);
}
.btn-trash-overlay:hover { background: #ff0000; transform: scale(1.1); }
</style>

<?php require_once 'includes/footer.php'; ?>