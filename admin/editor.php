<?php
/**
 * PROJET-CMS-2026 - ÉDITEUR (DESIGN SYSTEM MODE)
 * @author: Christophe Millot
 */

$is_local = ($_SERVER['REMOTE_ADDR'] === '127.0.0.1' || $_SERVER['SERVER_NAME'] === 'localhost');
if (!$is_local) { die("Acces reserve."); exit; }

$content_dir = "../content/";
$slug = isset($_GET['slug']) ? $_GET['slug'] : 'default-page';
$title = "Titre du Projet";
$htmlContent = "";

// Chargement des données existantes
if (file_exists($content_dir . $slug . '/data.php')) {
    include $content_dir . $slug . '/data.php';
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Éditeur - CMS 2026</title>
    <style id="dynamic-styles"></style>
    <style>
        :root { 
            --sidebar-bg: #111; --accent: #fff; --text-muted: #666; 
            --red-close: #ff4d4d; --canvas-bg: #e5e5e5;
        }
        
        body { 
            margin: 0; font-family: 'Inter', sans-serif; 
            background: #222; color: #fff; display: flex; 
            height: 100vh; overflow: hidden; width: 100vw; 
        }
        
        /* SIDEBAR */
        .sidebar { 
            position: fixed; top: 0; left: 0; bottom: 0;
            width: 320px; background: var(--sidebar-bg); padding: 30px 20px; 
            display: flex; flex-direction: column; border-right: 1px solid #333; 
            z-index: 100; 
            transition: transform 0.4s cubic-bezier(0.19, 1, 0.22, 1);
        }
        body.sidebar-hidden .sidebar { transform: translateX(-100%); }

        .sidebar h2 { 
            font-size: 14px; letter-spacing: 2px; text-transform: uppercase; 
            margin-bottom: 40px; display: flex; align-items: center; color: #fff;
        }

        .close-sidebar { color: var(--red-close); cursor: pointer; font-weight: bold; font-size: 18px; margin-right: 15px; }
        .mode-toggle { margin-left: auto; cursor: pointer; font-size: 18px; }

        .section-label { font-size: 10px; color: var(--text-muted); text-transform: uppercase; margin-top: 20px; margin-bottom: 15px; display: block; }
        
        .btn-main { 
            width: 100%; background: #fff; color: #000; border: none; padding: 12px; 
            font-weight: bold; cursor: pointer; margin-bottom: 10px; text-transform: uppercase; font-size: 11px; 
        }

        .grid-tools { display: grid; grid-template-columns: repeat(4, 1fr); gap: 10px; margin-bottom: 10px; }
        .grid-targets { display: grid; grid-template-columns: repeat(6, 1fr); gap: 5px; margin-bottom: 10px; }

        .tool-btn { 
            background: #222; border: 1px solid #333; color: #fff; height: 40px; 
            cursor: pointer; display: flex; align-items: center; justify-content: center; 
            font-size: 11px; font-weight: bold;
        }
        .tool-btn:hover { background: #fff; color: #000; }
        .tool-btn.active { background: #fff; color: #000; border-color: #fff; }

        /* JAUGES */
        .gauge-row { display: flex; align-items: center; height: 35px; gap: 10px; margin-bottom: 5px; }
        .gauge-label { width: 70px; font-size: 10px; color: var(--text-muted); text-transform: uppercase; }
        .gauge-control { flex: 1; display: flex; align-items: center; }
        .gauge-slider { width: 100%; cursor: pointer; accent-color: #fff; }
        .gauge-data { width: 50px; text-align: right; font-family: monospace; font-size: 11px; color: #fff; }

        .reset-btn {
            background: transparent; border: 1px solid #333; color: #666; 
            padding: 8px; cursor: pointer; font-size: 10px; text-transform: uppercase;
            margin: 15px 0; transition: all 0.2s; letter-spacing: 1px; width: 100%;
        }
        .reset-btn:hover { border-color: var(--red-close); color: var(--red-close); }

        .publish-btn { 
            margin-top: auto; background: #fff; color: #000; border: none; padding: 15px; 
            cursor: pointer; font-weight: bold; text-transform: uppercase; font-size: 12px;
        }

        .exit-btn { 
            margin-top: 20px; background: transparent; border: 1px solid #333; color: #888; padding: 12px; 
            cursor: pointer; text-transform: uppercase; font-size: 11px;
        }

        /* CANVAS */
        .canvas { 
            flex-grow: 1; width: 100vw; background: var(--canvas-bg); 
            display: flex; justify-content: center; padding: 60px; 
            overflow-y: auto; transition: padding-left 0.4s;
        }
        body:not(.sidebar-hidden) .canvas { padding-left: 380px; } 
        
        .paper { 
            position: relative; 
            width: 850px; background: #fff; color: #000; 
            min-height: 1100px; height: auto; 
            padding: 100px; margin-bottom: 100px;
            box-shadow: 0 20px 50px rgba(0,0,0,0.1); 
            display: flex; flex-direction: column;
            box-sizing: border-box;
            transition: background 0.3s, color 0.3s;
        }

        /* MODE SOMBRE SUR LA FEUILLE */
        .paper.dark-mode { background: #111 !important; color: #fff !important; }

        .gear-trigger {
            position: absolute; top: 0; left: 0;
            width: 50px; height: 50px; background: #000;
            display: flex; align-items: center; justify-content: center;
            cursor: pointer; color: #fff; transition: opacity 0.2s; z-index: 50;
        }
        .gear-trigger:hover { opacity: 0.8; }

        /* STRUCTURE DES BLOCS ET SUPPRESSION */
        .block-container { position: relative; width: 100%; }
        
        .delete-btn {
            position: absolute; left: -45px; top: 50%; transform: translateY(-50%);
            width: 24px; height: 24px; background: var(--red-close);
            color: #fff; display: none; align-items: center; justify-content: center;
            cursor: pointer; border-radius: 2px; font-size: 12px; font-weight: bold;
            z-index: 10;
        }
        .block-container:hover .delete-btn, 
        .block-container:focus-within .delete-btn { display: flex; }

        .paper h1, .paper h2, .paper h3, .paper h4, .paper h5, .paper p { 
            margin: 0 0 1em 0; outline: none; word-wrap: break-word; 
        }
        .content-area { flex-grow: 1; }
    </style>
</head>
<body class="sidebar-hidden"> 
    <aside class="sidebar" id="sidebar">
        <h2>
            <span class="close-sidebar" onclick="toggleSidebar()">✕</span>
            PARAMÈTRES 
            <span id="icon-toggle" class="mode-toggle" onclick="toggleDarkMode()">🌙</span>
        </h2>

        <span class="section-label">TYPOGRAPHIE</span>
        <button class="btn-main" onclick="addBlock('p')">AJOUTER MANIFESTE</button>
        
        <div class="grid-tools">
            <button class="tool-btn" onclick="updateGlobalStyle('textAlign', 'left')">
                <svg width="18" height="18" fill="currentColor" viewBox="0 0 24 24"><path d="M3 15h18v-2H3v2zm0 4h18v-2H3v2zm0-8h18V9H3v2zm0-6v2h18V5H3z"/></svg>
            </button>
            <button class="tool-btn" onclick="updateGlobalStyle('textAlign', 'center')">
                <svg width="18" height="18" fill="currentColor" viewBox="0 0 24 24"><path d="M7 15h10v-2H7v2zm-4 4h18v-2H3v2zm0-8h18V9H3v2zm4-4h10V5H7v2z"/></svg>
            </button>
            <button class="tool-btn" onclick="updateGlobalStyle('textAlign', 'right')">
                <svg width="18" height="18" fill="currentColor" viewBox="0 0 24 24"><path d="M3 15h18v-2H3v2zm0 4h18v-2H3v2zm0-8h18V9H3v2zm0-6v2h18V5H3z" style="transform: scaleX(-1); transform-origin: center;"/></svg>
            </button>
            <button class="tool-btn" onclick="updateGlobalStyle('textAlign', 'justify')">
                <svg width="18" height="18" fill="currentColor" viewBox="0 0 24 24"><path d="M3 21h18v-2H3v2zM3 7h18V5H3v2zm0 4h18V9H3v2zm0 4h18v-2H3v2z"/></svg>
            </button>
        </div>

        <span class="section-label">ARCHITECTURE GLOBALE</span>
        <div class="grid-targets">
            <button class="tool-btn" id="btn-p" onclick="addBlock('p')">P</button>
            <button class="tool-btn" id="btn-h1" onclick="setGlobalTarget('h1')">H1</button>
            <button class="tool-btn" id="btn-h2" onclick="addBlock('h2')">H2</button>
            <button class="tool-btn" id="btn-h3" onclick="addBlock('h3')">H3</button>
            <button class="tool-btn" id="btn-h4" onclick="addBlock('h4')">H4</button>
            <button class="tool-btn" id="btn-h5" onclick="addBlock('h5')">H5</button>
        </div>

        <span class="section-label">PILOTAGE <span id="target-label">H1</span></span>

        <div class="gauge-row">
            <div class="gauge-label">Taille</div>
            <div class="gauge-control">
                <input type="range" id="slider-size" class="gauge-slider" min="10" max="150" value="48" oninput="updateGlobalStyle('fontSize', this.value + 'px', 'val-size')">
            </div>
            <div class="gauge-data"><span id="val-size">48</span>px</div>
        </div>

        <div class="gauge-row">
            <div class="gauge-label">Graisse</div>
            <div class="gauge-control">
                <input type="range" id="slider-weight" class="gauge-slider" min="100" max="900" step="100" value="700" oninput="updateGlobalStyle('fontWeight', this.value, 'val-weight')">
            </div>
            <div class="gauge-data"><span id="val-weight">700</span></div>
        </div>

        <div class="gauge-row">
            <div class="gauge-label">Hauteur</div>
            <div class="gauge-control">
                <input type="range" id="slider-height" class="gauge-slider" min="0.5" max="3" step="0.1" value="1.2" oninput="updateGlobalStyle('lineHeight', this.value, 'val-height')">
            </div>
            <div class="gauge-data"><span id="val-height">1.2</span></div>
        </div>

        <button class="reset-btn" onclick="resetGlobalStyle()">Réinitialiser</button>
        <button class="publish-btn" onclick="publishDesign()">PUBLIER LE DESIGN</button>
        <button class="exit-btn" onclick="window.location.href='../index.php'">Quitter</button>
    </aside>

    <main class="canvas">
        <article class="paper" id="paper">
            <div class="gear-trigger" onclick="toggleSidebar()">
                <svg width="20" height="20" fill="currentColor" viewBox="0 0 24 24"><path d="M19.14 12.94c.04-.3.06-.61.06-.94 0-.32-.02-.64-.07-.94l2.03-1.58c.18-.14.23-.41.12-.61l-1.92-3.32c-.12-.22-.37-.29-.59-.22l-2.39.96c-.5-.38-1.03-.7-1.62-.94l-.36-2.54c-.04-.24-.24-.41-.48-.41h-3.84c-.24 0-.43.17-.47.41l-.36 2.54c-.59.24-1.13.57-1.62.94l-2.39-.96c-.22-.08-.47 0-.59.22L2.74 8.87c-.12.21-.08.47.12.61l2.03 1.58c-.05.3-.09.63-.09.94s.02.64.07.94l-2.03 1.58c-.18.14-.23.41-.12.61l1.92 3.32c.12.22.37.29.59.22l2.39-.96c.5.38 1.03.7 1.62.94l.36 2.54c.05.24.24.41.48.41h3.84c.24 0 .44-.17.47-.41l.36-2.54c.59-.24 1.13-.56 1.62-.94l2.39.96c.22.08.47 0 .59-.22l1.92-3.32c.12-.22.07-.47-.12-.61l-2.01-1.58zM12 15.6c-1.98 0-3.6-1.62-3.6-3.6s1.62-3.6 3.6-3.6 3.6 1.62 3.6 3.6-1.62 3.6-3.6 3.6z"/></svg>
            </div>

            <h1 contenteditable="true" id="editable-h1" onfocus="setGlobalTarget('h1')"><?php echo htmlspecialchars($title); ?></h1>
            <div class="content-area" id="editor-core" onclick="handleCoreClick(event)">
                <?php echo $htmlContent; ?>
            </div>
        </article>
    </main>

    <script>
    let currentTag = 'h1';
    let slug = '<?php echo $slug; ?>';
    
    // Initialisation
    const designSystem = <?php echo isset($designSystem) ? json_encode($designSystem) : json_encode([
        'h1' => ['fontSize' => '48px', 'fontWeight' => '700', 'lineHeight' => '1.2', 'textAlign' => 'center'],
        'h2' => ['fontSize' => '32px', 'fontWeight' => '600', 'lineHeight' => '1.3', 'textAlign' => 'left'],
        'h3' => ['fontSize' => '24px', 'fontWeight' => '500', 'lineHeight' => '1.4', 'textAlign' => 'left'],
        'h4' => ['fontSize' => '20px', 'fontWeight' => '500', 'lineHeight' => '1.4', 'textAlign' => 'left'],
        'h5' => ['fontSize' => '18px', 'fontWeight' => '500', 'lineHeight' => '1.4', 'textAlign' => 'left'],
        'p'  => ['fontSize' => '18px', 'fontWeight' => '400', 'lineHeight' => '1.6', 'textAlign' => 'left']
    ]); ?>;

    function toggleSidebar() { document.body.classList.toggle('sidebar-hidden'); }

    function setGlobalTarget(tag) {
        currentTag = tag;
        document.getElementById('target-label').innerText = tag.toUpperCase();
        document.querySelectorAll('.grid-targets .tool-btn').forEach(btn => btn.classList.remove('active'));
        const btn = document.getElementById('btn-' + tag);
        if(btn) btn.classList.add('active');

        const s = designSystem[tag];
        document.getElementById('slider-size').value = parseInt(s.fontSize);
        document.getElementById('val-size').innerText = parseInt(s.fontSize);
        document.getElementById('slider-weight').value = s.fontWeight;
        document.getElementById('val-weight').innerText = s.fontWeight;
        document.getElementById('slider-height').value = s.lineHeight;
        document.getElementById('val-height').innerText = s.lineHeight;
    }

    function updateGlobalStyle(prop, value, displayId) {
        designSystem[currentTag][prop] = value;
        if(displayId) document.getElementById(displayId).innerText = value.replace('px', '');
        renderStyles();
    }

    function renderStyles() {
        let css = "";
        for (const tag in designSystem) {
            css += `.paper ${tag} { 
                font-size: ${designSystem[tag].fontSize}; 
                font-weight: ${designSystem[tag].fontWeight}; 
                line-height: ${designSystem[tag].lineHeight}; 
                text-align: ${designSystem[tag].textAlign}; 
            }\n`;
        }
        document.getElementById('dynamic-styles').innerHTML = css;
    }

    function addBlock(tag) {
        const core = document.getElementById('editor-core');
        const container = document.createElement('div');
        container.className = "block-container";

        const newEl = document.createElement(tag);
        newEl.contentEditable = "true";
        newEl.innerText = (tag === 'p') ? "Saisissez votre manifeste..." : "Nouveau Titre " + tag.toUpperCase();
        newEl.onfocus = () => setGlobalTarget(tag);
        
        const delBtn = document.createElement('div');
        delBtn.className = "delete-btn";
        delBtn.innerHTML = "✕";
        delBtn.onclick = () => { container.remove(); setGlobalTarget('h1'); };

        container.appendChild(newEl);
        container.appendChild(delBtn);
        core.appendChild(container);
        
        setGlobalTarget(tag);
        newEl.focus();
    }

    function handleCoreClick(e) {
        if(e.target !== e.currentTarget) {
            const tag = e.target.tagName.toLowerCase();
            if(['h1','h2','h3','h4','h5','p'].includes(tag)) { setGlobalTarget(tag); }
        }
    }

    async function publishDesign() {
        const data = {
            slug: slug,
            title: document.getElementById('editable-h1').innerText,
            designSystem: designSystem,
            htmlContent: document.getElementById('editor-core').innerHTML
        };

        try {
            const response = await fetch('save.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(data)
            });
            const result = await response.json();
            alert(result.message);
        } catch (error) { alert("Erreur lors de la publication"); }
    }

    function resetGlobalStyle() {
        designSystem[currentTag] = { fontSize: '20px', fontWeight: '400', lineHeight: '1.5', textAlign: 'left' };
        setGlobalTarget(currentTag);
        renderStyles();
    }

    function toggleDarkMode() { 
        const paper = document.getElementById('paper');
        const icon = document.getElementById('icon-toggle');
        paper.classList.toggle('dark-mode');
        icon.innerText = paper.classList.contains('dark-mode') ? '☀️' : '🌙';
    }

    window.onload = () => { 
        renderStyles();
        setGlobalTarget('h1'); 
    };
    </script>
</body>
</html>