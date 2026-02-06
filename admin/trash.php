<?php
/**
 * PROJET-CMS-2026 - GESTION DE LA CORBEILLE
 * Version stabilisée pour Christophe Millot
 */
require_once '../core/config.php';

// 1. SÉCURITÉ LOCALE
$is_local = ($_SERVER['REMOTE_ADDR'] === '127.0.0.1' || $_SERVER['SERVER_NAME'] === 'localhost');
if (!$is_local) { 
    die("Acces reserve."); 
    exit; 
}

// 2. INCLUDES
include '../includes/header.php'; 
?>

<div class="master-grid" style="padding-top: 100px;">
    <main id="main">
        <header class="section-header" style="margin-bottom: 2rem;">
            <h1 class="section-title">Corbeille (Archives)</h1>
            <p><a href="<?php echo BASE_URL; ?>index.php" style="text-decoration: none; color: #666;">← Retour à l'accueil</a></p>
        </header>

        <div class="grid-container">
            <?php
            $trash_path = '../content/_trash/';
            
            if (is_dir($trash_path)) {
                $folders = array_diff(scandir($trash_path), array('..', '.'));
                
                if (empty($folders)) {
                    echo "<div style='grid-column: 1/-1; padding: 40px; text-align: center; background: #f9f9f9; border-radius: 20px; border: 2px dashed #ccc;'>";
                    echo "<p style='color: #999;'>La corbeille est vide.</p>";
                    echo "</div>";
                }

                foreach ($folders as $folder) {
                    $project_dir = $trash_path . $folder;

                    // Découpage du nom : Ymd-His_nom-du-projet
                    $parts = explode('_', $folder, 2);
                    $display_name = isset($parts[1]) ? $parts[1] : $folder;
                    
                    // Formatage sécurisé de la date de suppression
                    $date_brute = $parts[0]; // Ymd-His
                    $date_f = "Date inconnue";
                    if(strlen($date_brute) >= 15) {
                        $date_f = substr($date_brute, 6, 2) . '/' . substr($date_brute, 4, 2) . '/' . substr($date_brute, 0, 4) . ' à ' . substr($date_brute, 9, 2) . ':' . substr($date_brute, 11, 2);
                    }
                    ?>
                    
                    <article class="grid-block card-trash" style="opacity: 0.7; border: 1px dashed #ccc; background: #fff; border-radius: 12px; overflow: hidden;">
                        <div class="card-content" style="padding: 20px;">
                            <div class="card-meta" style="display: flex; justify-content: space-between; font-size: 0.7rem; margin-bottom: 1rem;">
                                <span class="category" style="color: #ff4444; font-weight: bold; text-transform: uppercase;">ARCHIVE</span>
                                <span class="date" style="color: #999;">Supprimé le <?php echo $date_f; ?></span>
                            </div>
                            
                            <h3 style="color: #333; margin: 0 0 10px 0; font-size: 1.1rem;"><?php echo htmlspecialchars(strtoupper($display_name)); ?></h3>
                            
                            <p class="summary" style="margin-bottom: 1.5rem; font-style: italic; font-size: 0.8rem; color: #666; word-break: break-all;">
                                Dossier : <?php echo htmlspecialchars($folder); ?>
                            </p>
                            
                            <div class="card-action" style="display: flex; gap: 10px;">
                                <a href="editor.php?action=restore&slug=<?php echo urlencode($folder); ?>" 
                                   class="btn-open" 
                                   style="background: #e1f5fe; color: #0288d1; flex: 1; text-align: center; font-size: 0.7rem; padding: 10px; border-radius: 6px; text-decoration: none; font-weight: bold;">
                                    RESTAURER
                                </a>
                                
                                <a href="editor.php?action=purge&slug=<?php echo urlencode($folder); ?>" 
                                   class="btn-open" 
                                   style="background: #ffebee; color: #c62828; flex: 1; text-align: center; font-size: 0.7rem; padding: 10px; border-radius: 6px; text-decoration: none; font-weight: bold;" 
                                   onclick="return confirm('Détruire DEFINITIVEMENT ce dossier ?');">
                                    DÉTRUIRE
                                </a>
                            </div>
                        </div>
                    </article>
                    <?php
                }
            } else {
                echo "<div style='grid-column: 1/-1; padding: 40px; text-align: center; color: #666;'>Le dossier de stockage des archives (<code>_trash</code>) n'existe pas encore.</div>";
            }
            ?>
        </div>
    </main>
</div>

<?php include '../includes/footer.php'; ?>