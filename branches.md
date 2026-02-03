# Saved Information (Protocoles de Travail)
- [2026-01-27] Relecture systématique des balises de repérage avant réponse.
- [2026-01-27] Commentaires HTML/PHP = Éléments structurels intouchables.
- [2026-01-21] Nom : Christophe Millot. Pas de fragments, fichiers complets uniquement. 
- [2026-01-21] Procédure Git push strictement suivie. CSS robuste priorisé sur le SVG.
- [2026-02-01] LOI DE FER : Aucune initiative sur les valeurs (px, vh, classes) sans accord.

---

# Suivi du Projet : Evolution (Système Hybride)

## Vision Stratégique [2026-02-02]
- **Concept :** CMS Dynamique Local (XAMPP) -> Export Statique Production (Nuxit).
- **Surface d'Attaque :** Nulle (Fichiers .php de données convertis ou sécurisés).
- **Architecture :** Article > Sections (Grid-block). Système de stockage : Flat-file (data.php).

## État des Blocs (Cahier des Charges)
- **[A] Contenu :** ✅ Validé (CRUD dossiers/fichiers opérationnel).
- **[B] Sécurité :** 🟠 En réflexion (Filtrage adresse IP locale validé).
- **[C] Interface :** ✅ Validé (Interface d'édition via _admin.scss connectée).
- **[E] Export :** ⚪ En attente.

---

## Plan de Développement (Branches Git)

### 1. Branche : `feat/core-structure` [TERMINE / MERGED]
- **Objectif :** Génération auto des projets et moteur de sauvegarde.
- **Résultat :** Index filtré (ignore `_`), Cards fantômes, et édition directe.

### 2. Branche : `feat/ui-refinement` [PROCHAINE ÉTAPE]
- **Objectif :** Identité visuelle des Cards et stabilisation de la vue Article.
- **Tâches :** - Intégration des `thumb.jpg` dans la boucle index.
    - Désactivation du `:hover` sur le `.grid-block` en mode lecture seule.
    - Formatage des dates et résumés (tronçonnage).

---

## Historique des Décisions IA (Discipline de Code)
- **[2026-02-02] :** Validation du moteur de sauvegarde. L'IA a interdiction de modifier les marges ou les structures CSS validées sans accord explicite. Convention de dossier `_` pour archivage validée.