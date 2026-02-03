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
            <p><a href="../index.php" style="text-decoration: none; color: #666;">← Retour à l'accueil</a></p>
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

                    // Découpage propre du nom : Ymd-His_nom-du-projet
                    $parts = explode('_', $folder, 2);
                    $display_name = isset($parts[1]) ? $parts[1] : $folder;
                    
                    // Formatage de la date de suppression
                    $date_brute = $parts[0]; // Ymd-His
                    $date_f = substr($date_brute, 6, 2) . '/' . substr($date_brute, 4, 2) . ' à ' . substr($date_brute, 9, 2) . ':' . substr($date_brute, 11, 2);
                    ?>
                    
                    <article class="grid-block card-trash" style="opacity: 0.7; border: 1px dashed #ccc;">
                        <div class="card-content">
                            <div class="card-meta">
                                <span class="category" style="color: #ff4444;">ARCHIVE</span>
                                <span class="date">Supprimé le <?php echo $date_f; ?></span>
                            </div>
                            
                            <h3 style="color: #555;"><?php echo strtoupper($display_name); ?></h3>
                            
                            <p class="summary" style="min-height: auto; font-style: italic; font-size: 0.8rem;">
                                Dossier : <?php echo $folder; ?>
                            </p>
                            
                            <div class="card-action" style="display: flex; gap: 10px; margin-top: 1rem;">
                                <a href="editor.php?action=restore&slug=<?php echo $folder; ?>" 
                                   class="btn-open" 
                                   style="background: #e1f5fe; color: #0288d1; flex: 1; text-align: center; font-size: 0.7rem;">
                                   RESTAURER
                                </a>
                                
                                <a href="editor.php?action=purge&slug=<?php echo $folder; ?>" 
                                   class="btn-open" 
                                   style="background: #ffebee; color: #c62828; flex: 1; text-align: center; font-size: 0.7rem;" 
                                   onclick="return confirm('Détruire DEFINITIVEMENT ce dossier ?');">
                                   DÉTRUIRE
                                </a>
                            </div>
                        </div>
                    </article>
                    <?php
                }
            } else {
                echo "<p>Le dossier de stockage des archives n'existe pas encore.</p>";
            }
            ?>
        </div>
    </main>
</div>

<?php include '../includes/footer.php'; ?>