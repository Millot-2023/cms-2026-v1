<?php
/**
 * PROJET-CMS-2026 - ÉDITEUR DESIGN SYSTEM (RESTO)
 * @author: Christophe Millot
 */
$is_local = ($_SERVER['REMOTE_ADDR'] === '127.0.0.1' || $_SERVER['SERVER_NAME'] === 'localhost');
if (!$is_local) { die("Acces reserve."); exit; }

$content_dir = "../content/";
$slug = isset($_GET['slug']) ? $_GET['slug'] : 'nouveau-projet';
$title = "Titre du Projet";
$category = "Design";
$summary = "";
$htmlContent = "";

if (file_exists($content_dir . $slug . '/data.php')) {
    include $content_dir . $slug . '/data.php';
}

$safeHtml = str_replace(["\r", "\n"], '', addslashes($htmlContent));
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Éditeur Pro - CMS 2026</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;700;900&display=swap">
    <style id="dynamic-styles"></style>
    <style>
        /* --- 1. CONFIGURATION DES THÈMES --- */
        :root {
            /* Sidebar : TOUJOURS NOIR */
            --sidebar-bg: #111111;
            --sidebar-border: #333333;
            --sidebar-text: #ffffff;
            --sidebar-muted: #666666;
            --sidebar-input: #1a1a1a;
            
            /* Interface variable (Défaut Dark) */
            --canvas-bg: #1a1a1a;
            --accent: #ffffff;
            --btn-active-bg: #ffffff;
            --btn-active-text: #000000;
        }

        body.light-mode {
            --canvas-bg: #e0e0e0;
            --accent: #000000;
        }
        
        body { 
            margin: 0; 
            font-family: 'Inter', sans-serif; 
            background-color: var(--canvas-bg); 
            color: var(--accent); 
            display: flex; 
            height: 100vh; 
            width: 100vw; 
            overflow: hidden; 
            transition: background 0.3s; 
        }
        
        /* --- 2. LE COCKPIT (SIDEBAR) --- */
        .sidebar { 
            position: fixed; 
            top: 0; left: 0; bottom: 0; 
            width: 340px; 
            background-color: var(--sidebar-bg); 
            border-right: 1px solid var(--sidebar-border); 
            display: flex; 
            flex-direction: column; 
            z-index: 1000;
            color: var(--sidebar-text);
            transition: transform 0.4s cubic-bezier(0.19, 1, 0.22, 1);
        }
        body.sidebar-hidden .sidebar { transform: translateX(-100%); }
        
        .sidebar-header { 
            padding: 40px 25px 25px; 
            border-bottom: 1px solid var(--sidebar-border); 
            display: flex; align-items: center; gap: 15px; 
        }
        .sidebar-header h2 { font-size: 10px; letter-spacing: 3px; text-transform: uppercase; margin: 0; color: var(--sidebar-muted); flex-grow: 1; }
        
        .sidebar-scroll { flex-grow: 1; overflow-y: auto; padding: 20px 25px; }
        
        .sidebar-footer { 
            padding: 25px; 
            border-top: 1px solid var(--sidebar-border); 
            background-color: var(--sidebar-bg);
            display: flex; flex-direction: column; gap: 10px; 
        }

        /* --- 3. INPUTS ET BOUTONS DU COCKPIT --- */
        .admin-input { 
            width: 100%; 
            background-color: var(--sidebar-input); 
            border: 1px solid var(--sidebar-border); 
            color: var(--sidebar-text); 
            padding: 12px; margin-bottom: 12px; font-size: 11px; 
            border-radius: 4px; outline: none; box-sizing: border-box;
        }

        .section-label { font-size: 9px; color: var(--sidebar-muted); text-transform: uppercase; margin-top: 25px; margin-bottom: 10px; display: block; }

        .grid-structure { display: flex; flex-direction: column; gap: 8px; }
        .row-h { display: grid; grid-template-columns: repeat(5, 1fr); gap: 8px; }
        .row-p { display: grid; grid-template-columns: repeat(3, 1fr); gap: 8px; }

        .tool-btn { 
            background-color: var(--sidebar-input); 
            border: 1px solid var(--sidebar-border); 
            color: var(--sidebar-muted); 
            height: 40px; cursor: pointer; font-size: 10px; font-weight: bold;
            border-radius: 4px; transition: 0.2s; 
        }
        .tool-btn:hover { border-color: #555; color: #fff; }
        .tool-btn.active { background-color: #fff; color: #000; border-color: #fff; }

        .gauge-row { background-color: var(--sidebar-input); padding: 15px; border-radius: 6px; margin-bottom: 10px; border: 1px solid var(--sidebar-border); }
        .gauge-info { display: flex; justify-content: space-between; margin-bottom: 8px; font-size: 10px; color: var(--sidebar-muted); }
        .gauge-data { color: var(--sidebar-text); font-family: monospace; }

        /* --- 4. CANVAS ET ZONE DE TRAVAIL --- */
        .canvas { flex-grow: 1; height: 100vh; display: flex; justify-content: center; padding: 80px 20px; overflow-y: auto; transition: padding-left 0.4s; }
        body:not(.sidebar-hidden) .canvas { padding-left: 340px; }

        .paper { 
            width: 100%; max-width: 850px; background: #ffffff; color: #000000; 
            min-height: 1100px; padding: 100px; box-shadow: 0 40px 100px rgba(0,0,0,0.5); 
            display: flex; flex-direction: column; 
        }

        /* --- 5. UTILITAIRES --- */
        .theme-toggle { cursor: pointer; font-size: 16px; color: var(--sidebar-text); }
        .sidebar-trigger { position: fixed; top: 20px; left: 20px; z-index: 500; background: var(--accent); color: var(--canvas-bg); border: none; width: 40px; height: 40px; border-radius: 4px; cursor: pointer; font-weight: bold; transition: 0.3s; }
    </style>
</head>
<body>

    <button class="sidebar-trigger" onclick="toggleSidebar()">☰</button>

    <aside class="sidebar">
        <div class="sidebar-header">
            <span style="color:#ff4d4d; cursor:pointer; font-weight:bold;" onclick="toggleSidebar()">✕</span>
            <h2>PROJET STUDIO</h2>
            <div class="theme-toggle" onclick="toggleTheme()" id="t-icon">🌙</div>
        </div>

        <div class="sidebar-scroll">
            <span class="section-label">MÉTADONNÉES</span>
            <input type="text" class="admin-input" placeholder="Slug" value="<?php echo $slug; ?>">
            <textarea class="admin-input" placeholder="Résumé" style="height:60px;"><?php echo $summary; ?></textarea>

            <span class="section-label">STRUCTURE</span>
            <div class="grid-structure">
                <div class="row-h">
                    <button class="tool-btn" id="btn-h1" onclick="addBlock('h1', 'Titre H1')">H1</button>
                    <button class="tool-btn" id="btn-h2" onclick="addBlock('h2', 'Titre H2')">H2</button>
                    <button class="tool-btn" id="btn-h3" onclick="addBlock('h3', 'Titre H3')">H3</button>
                    <button class="tool-btn" id="btn-h4" onclick="addBlock('h4', 'Titre H4')">H4</button>
                    <button class="tool-btn" id="btn-h5" onclick="addBlock('h5', 'Titre H5')">H5</button>
                </div>
                <div class="row-p">
                    <button class="tool-btn" id="btn-p" onclick="addBlock('p', 'Texte...')">P</button>
                    <button class="tool-btn" onclick="execStyle('bold')">B</button>
                    <button class="tool-btn" onclick="execStyle('italic')">I</button>
                </div>
            </div>

            <span class="section-label">RÉGLAGES</span>
            <div class="gauge-row">
                <div class="gauge-info"><span>TAILLE</span><span class="gauge-data" id="val-size">18</span></div>
                <input type="range" style="width:100%; accent-color:#fff;" min="8" max="120" value="18" oninput="updateStyle('fontSize', this.value+'px', 'val-size')">
            </div>
        </div>

        <div class="sidebar-footer">
            <button style="background:#fff; color:#000; border:none; padding:15px; font-weight:900; cursor:pointer; text-transform:uppercase;">PUBLIER</button>
            <a href="../index.php" style="color:var(--sidebar-muted); text-align:center; font-size:10px; text-decoration:none; border:1px solid var(--sidebar-border); padding:10px; border-radius:4px;">RETOUR</a>
        </div>
    </aside>

    <main class="canvas">
        <article class="paper" id="paper">
            <h1 contenteditable="true" onfocus="setTarget('h1')"><?php echo htmlspecialchars($title); ?></h1>
            <div id="editor-core"></div>
        </article>
    </main>

    <script>
    let currentTag = 'h1';

    function toggleTheme() {
        document.body.classList.toggle('light-mode');
        const isLight = document.body.classList.contains('light-mode');
        document.getElementById('t-icon').innerText = isLight ? '☀️' : '🌙';
    }

    function toggleSidebar() { document.body.classList.toggle('sidebar-hidden'); }
    function execStyle(cmd) { document.execCommand(cmd, false, null); }

    function setTarget(tag) {
        currentTag = tag;
        document.querySelectorAll('.tool-btn').forEach(b => b.classList.remove('active'));
        if(document.getElementById('btn-'+tag)) document.getElementById('btn-'+tag).classList.add('active');
    }

    function updateStyle(prop, val, id) {
        document.getElementById(id).innerText = val.replace('px','');
    }

    function addBlock(tag, txt) {
        const el = document.createElement(tag);
        el.contentEditable = true;
        el.innerHTML = txt;
        el.onfocus = () => setTarget(tag);
        document.getElementById('editor-core').appendChild(el);
    }
    </script>
</body>
</html>