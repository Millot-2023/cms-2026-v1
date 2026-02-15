# Suivi du Projet : Evolution (Système Hybride)

## Vision Stratégique [2026-02-09]
- **Concept :** CMS Dynamique Local (XAMPP) -> Export Statique Production (Nuxit).
- **Surface d'Attaque :** Nulle (Fichiers .php de données convertis ou sécurisés).
- **Architecture V3 :** Isolation par Iframe (Séparation stricte Interface / Contenu).
- **Objectif :** Élimination totale de la dette technique (Zéro style inline, Zéro !important).

## État des Blocs (Cahier des Charges)
- **[A] Contenu :** ✅ Validé (CRUD dossiers/fichiers opérationnel).
- **[B] Sécurité :** ✅ Validé (Filtrage IP locale + Verrouillage Sidebar).
- **[C] Interface :** ⚠️ En refonte (Migration vers architecture propre V3).
- **[D] Rendu :** ⚠️ En refonte (Passage au rendu isolé via Iframe).
- **[E] Export :** ⚪ En attente.

---

## Plan de Développement (Branches Git)

### 1. Branche : `feat/core-structure` [TERMINE / MERGED]
- **Objectif :** Génération auto des projets et moteur de sauvegarde.

### 2. Branche : `stabilite-editeur-2026` [TERMINE / MERGED]
- **Objectif :** Finalisation de l'ergonomie de l'éditeur.

### 3. Branche : `feat/ui-refinement` [TERMINE / MERGED]
- **Objectif :** Identité visuelle des Cards et stabilisation de la vue Article.

### 4. Branche : `feat/trash-and-clean` [TERMINE / MERGED]
- **Objectif :** Allègement du poids des données et gestion de la suppression.

### 5. Branche : `feat/ui-uniformization` [TERMINE / MERGED]
- **Objectif :** Alignement militaire de l'interface et résilience de l'éditeur.

### 6. Branche : `feat/hero-refinement` [TERMINE / MERGED]
- **Objectif :** Nettoyage sémantique et stabilisation du rendu visuel.

### 7. Branche : `arch/v3-clean-start` [EN COURS]
- **Objectif :** Refonte structurelle "Zéro Conflit".
- **Actions :**
    - Suppression du JavaScript et du CSS interne de `editor.php`.
    - Mise en place du pont de communication (Bridge) entre la Sidebar et l'Iframe.
    - Centralisation de la logique métier dans `assets/js/evolution.js`.

---

## Historique des Décisions IA (Discipline de Code)
- **[2026-01-30] :** Nommage du fichier de suivi "branches.md".
- **[2026-02-06] :** Interdiction formelle de fragmenter les fichiers PHP.
- **[2026-02-08] :** Constat de dette technique (usage abusif de !important).
- **[2026-02-09] :** **Pivot Stratégique V3 :** Décision de basculer vers une isolation par Iframe pour garantir l'intégrité du code et la propreté du SCSS.
- **[2026-02-09] :** **Audit de Rigueur :** Interdiction de tout style inline ou injection JS directe dans le DOM de l'interface.