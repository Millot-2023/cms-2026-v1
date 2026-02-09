/**
 * PROJET-CMS-2026 - MOTEUR ÉDITEUR V3 (STRICT)
 * @author: Christophe Millot
 * Rôle : Pilotage du Canvas via Iframe + Design System Persistant
 */

const studio = {
    iframe: document.getElementById('viewport'),
    
    get canvas() {
        return this.iframe.contentDocument || this.iframe.contentWindow.document;
    },

    init() {
        console.log("Studio Engine V3 : Design System Actif.");
        this.bindEvents();
    },

    bindEvents() {
        // Bouton Sauvegarder
        const saveBtn = document.getElementById('btn-save');
        if (saveBtn) saveBtn.addEventListener('click', () => this.publish());

        // Slider Taille de Police (Design System)
        const sizeSlider = document.getElementById('slider-size');
        if (sizeSlider) {
            sizeSlider.addEventListener('input', (e) => {
                const size = e.target.value + 'px';
                this.applyStyleToSelection('fontSize', size);
            });
        }
    },

    // Applique le style dynamiquement à l'élément sélectionné ou au dernier bloc
    applyStyleToSelection(property, value) {
        const selection = this.canvas.getSelection();
        let target = null;

        if (selection.rangeCount > 0) {
            target = selection.anchorNode.parentElement;
        }

        // Si la sélection est bien dans la zone éditable
        if (target && target.closest('#paper-viewport')) {
            target.style[property] = value;
        }
    },

    addBlock(tag) {
        const target = this.canvas.getElementById('editable-core');
        if (!target) return;

        const block = this.canvas.createElement(tag === 'image' ? 'div' : tag);
        
        if (tag === 'image') {
            block.innerHTML = `<img src="../assets/img/image-template.png" style="width:100%; border-radius:8px; margin:20px 0;">`;
        } else if (tag === 'p') {
            block.innerText = "Nouveau paragraphe éditable...";
        } else {
            block.innerText = "Nouveau titre " + tag.toUpperCase();
        }

        block.setAttribute('contenteditable', 'true');
        target.appendChild(block);
        
        setTimeout(() => block.focus(), 10);
        block.scrollIntoView({ behavior: 'smooth', block: 'center' });
    },

    publish() {
        const slug = document.getElementById('inp-slug').value;
        const title = this.canvas.getElementById('editable-title').innerText;
        const html = this.canvas.getElementById('editable-core').innerHTML;
        const summary = document.getElementById('inp-summary').value;

        // Extraction du Design System (On récupère les styles inline majeurs)
        const designSettings = {
            mainTitleSize: this.canvas.getElementById('editable-title').style.fontSize || 'default'
        };

        const formData = new FormData();
        formData.append('slug', slug);
        formData.append('title', title);
        formData.append('summary', summary);
        formData.append('htmlContent', html);
        // On envoie le design system en JSON
        formData.append('designSystem', JSON.stringify(designSettings));

        const btn = document.getElementById('btn-save');
        const originalText = btn.innerText;
        btn.innerText = "SAUVEGARDE...";
        btn.disabled = true;

        fetch('save.php', { method: 'POST', body: formData })
            .then(r => r.json())
            .then(data => {
                alert("Confirmation : " + data.message);
                btn.innerText = originalText;
                btn.disabled = false;
            })
            .catch(err => {
                console.error("Erreur Studio:", err);
                btn.innerText = originalText;
                btn.disabled = false;
            });
    }
};

studio.init();
window.studio = studio;