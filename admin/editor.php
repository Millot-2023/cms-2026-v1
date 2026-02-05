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
            --sidebar-bg: #111111;
            --sidebar-border: #333333;
            --sidebar-text: #ffffff;
            --sidebar-muted: #666666;
            --sidebar-input: #1a1a1a;
            --canvas-bg: #1a1a1a;
            --accent: #ffffff;
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

        /* --- 3. BOUTONS ET GRILLES --- */
        .admin-input { 
            width: 100%; background-color: var(--sidebar-input); border: 1px solid var(--sidebar-border); 
            color: var(--sidebar-text); padding: 12px; margin-bottom: 12px; font-size: 11px; 
            border-radius: 4px; outline: none; box-sizing: border-box;
        }

        .section-label { font-size: 9px; color: var(--sidebar-muted); text-transform: uppercase; margin-top: 25px; margin-bottom: 10px; display: block; }

        .grid-structure { display: flex; flex-direction: column; gap: 8px; }
        .row-h { display: grid; grid-template-columns: repeat(5, 1fr); gap: 8px; }
        .row-styles { display: grid; grid-template-columns: repeat(2, 1fr); gap: 8px; }
        .row-align { display: grid; grid-template-columns: repeat(4, 1fr); gap: 8px; }

        .tool-btn { 
            background-color: var(--sidebar-input); border: 1px solid var(--sidebar-border); 
            color: var(--sidebar-muted); height: 40px; cursor: pointer; font-size: 10px; font-weight: bold;
            border-radius: 4px; transition: 0.2s; text-transform: uppercase;
            display: flex; align-items: center; justify-content: center;
        }
        .tool-btn:hover { border-color: #555; color: #fff; }

        .gauge-row { background-color: var(--sidebar-input); padding: 15px; border-radius: 6px; margin-bottom: 10px; border: 1px solid var(--sidebar-border); }
        .gauge-info { display: flex; justify-content: space-between; margin-bottom: 8px; font-size: 10px; color: var(--sidebar-muted); }
        .gauge-data { color: var(--sidebar-text); font-family: monospace; }

        /* --- 4. CANVAS ET PAPER --- */
        .canvas { flex-grow: 1; height: 100vh; display: flex; justify-content: center; padding: 80px 20px; overflow-y: auto; transition: padding-left 0.4s; }
        body:not(.sidebar-hidden) .canvas { padding-left: 340px; }

        .paper { 
            width: 100%; max-width: 850px; background: #ffffff; color: #000000; 
            min-height: 1100px; padding: 100px; box-shadow: 0 40px 100px rgba(0,0,0,0.5); 
            display: flex; flex-direction: column; 
        }

        /* --- 5. GESTION DES BLOCS --- */
        .block-container {
            position: relative;
            margin-bottom: 5px;
        }
        .block-container:hover {
            outline: 1px solid #f2f2f2;
        }
        .delete-block {
            position: absolute;
            left: -18px; 
            top: 0;
            background: #ff4d4d;
            color: white;
            width: 18px;
            height: 18px;
            border-radius: 2px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 9px;
            cursor: pointer;
            opacity: 0;
            transition: opacity 0.2s;
            z-index: 10;
        }
        .block-container:hover .delete-block {
            opacity: 1;
        }

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
                    <button class="tool-btn" onclick="addBlock('h1', 'Titre H1')">H1</button>
                    <button class="tool-btn" onclick="addBlock('h2', 'Titre H2')">H2</button>
                    <button class="tool-btn" onclick="addBlock('h3', 'Titre H3')">H3</button>
                    <button class="tool-btn" onclick="addBlock('h4', 'Titre H4')">H4</button>
                    <button class="tool-btn" onclick="addBlock('h5', 'Titre H5')">H5</button>
                </div>
                
                <button class="tool-btn" onclick="addBlock('p', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.')">Paragraphe</button>
                
                <div class="row-styles">
                    <button class="tool-btn" onclick="execStyle('bold')">B</button>
                    <button class="tool-btn" onclick="execStyle('italic')">I</button>
                </div>

                <div class="row-align">
                    <button class="tool-btn" title="Gauche" onclick="execStyle('justifyLeft')">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 10H3M21 6H3M21 14H3M17 18H3"/></svg>
                    </button>
                    <button class="tool-btn" title="Centré" onclick="execStyle('justifyCenter')">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 10H6M21 6H3M21 14H3M18 18H6"/></svg>
                    </button>
                    <button class="tool-btn" title="Droite" onclick="execStyle('justifyRight')">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10H7M21 6H3M21 14H3M21 18H7"/></svg>
                    </button>
                    <button class="tool-btn" title="Justifié" onclick="execStyle('justifyFull')">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10H3M21 6H3M21 14H3M21 18H3"/></svg>
                    </button>
                </div>
            </div>

            <span class="section-label">RÉGLAGES : <span id="target-label" style="color:#fff">H1</span></span>
            <div class="gauge-row">
                <div class="gauge-info"><span>TAILLE</span><span class="gauge-data"><span id="val-size">64</span>px</span></div>
                <input type="range" id="slider-size" style="width:100%; accent-color:#fff;" min="8" max="120" value="64" oninput="updateStyle('fontSize', this.value+'px', 'val-size')">
            </div>
        </div>

        <div class="sidebar-footer">
            <button style="background:#fff; color:#000; border:none; padding:15px; font-weight:900; cursor:pointer; text-transform:uppercase;">PUBLIER</button>
            <a href="../index.php" style="color:var(--sidebar-muted); text-align:center; font-size:10px; text-decoration:none; border:1px solid var(--sidebar-border); padding:10px; border-radius:4px;">RETOUR</a>
        </div>
    </aside>

    <main class="canvas">
        <article class="paper" id="paper">
            <div class="block-container">
                <div class="delete-block" onclick="this.parentElement.remove()">✕</div>
                <h1 contenteditable="true" onfocus="setTarget('h1')"><?php echo htmlspecialchars($title); ?></h1>
            </div>
            <div id="editor-core"></div>
        </article>
    </main>

    <script>
    let currentTag = 'h1';
    let designSystem = {
        'h1': { fontSize: '64px' },
        'h2': { fontSize: '42px' },
        'h3': { fontSize: '30px' },
        'h4': { fontSize: '24px' },
        'h5': { fontSize: '18px' },
        'p':  { fontSize: '18px' }
    };

    function renderStyles() {
        let css = "";
        for (let tag in designSystem) {
            css += `.paper ${tag} { font-size: ${designSystem[tag].fontSize}; margin-top:0; margin-bottom:0.5em; outline:none; }\n`;
        }
        document.getElementById('dynamic-styles').innerHTML = css;
    }

    function updateStyle(prop, val, displayId) {
        designSystem[currentTag][prop] = val;
        document.getElementById(displayId).innerText = val.replace('px', '');
        renderStyles();
    }

    function setTarget(tag) {
        currentTag = tag;
        document.getElementById('target-label').innerText = tag.toUpperCase() === 'P' ? 'PARAGRAPHE' : tag.toUpperCase();
        
        if(designSystem[tag]) {
            let val = parseInt(designSystem[tag].fontSize);
            document.getElementById('slider-size').value = val;
            document.getElementById('val-size').innerText = val;
        }
    }

    function toggleTheme() {
        document.body.classList.toggle('light-mode');
        document.getElementById('t-icon').innerText = document.body.classList.contains('light-mode') ? '☀️' : '🌙';
    }

    function toggleSidebar() { document.body.classList.toggle('sidebar-hidden'); }
    function execStyle(cmd) { document.execCommand(cmd, false, null); }

    function addBlock(tag, txt) {
        const container = document.createElement('div');
        container.className = 'block-container';

        const delBtn = document.createElement('div');
        delBtn.className = 'delete-block';
        delBtn.innerHTML = '✕';
        delBtn.onclick = () => container.remove();

        const el = document.createElement(tag);
        el.contentEditable = true;
        el.innerHTML = txt;
        el.onfocus = () => setTarget(tag);

        container.appendChild(delBtn);
        container.appendChild(el);
        document.getElementById('editor-core').appendChild(container);
        
        el.focus();
    }

    window.onload = () => { renderStyles(); setTarget('h1'); };
    </script>
</body>
</html>