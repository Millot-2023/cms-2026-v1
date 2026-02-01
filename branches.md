# Suivi du Projet : Evolution 2026

## Architecture Fondamentale [2026-02-01]

### 1. Structure du Header (Fixe)
- **Fichiers :** `_variables.scss`, `_header.scss`, `header.php`
- **État :** Verrouillé.
- **Logique :** Le header est en `position: fixed` avec une hauteur pilotée par la variable `$header-height`.

### 2. Section Hero (Full-Viewport)
- **Fichiers :** `_hero.scss`, `includes/hero.php`
- **État :** Opérationnel.
- **Logique :** - Calcul dynamique de la hauteur : `calc(100vh - $header-height)`.
    - Animation d'entrée : Opacité, flou et zoom progressif (cinématique).
    - Ancre de défilement : Flèche animée pointant vers `#main`.

### 3. Grille Master et Navigation Interne
- **Fichiers :** `_grid.scss`, `index.php`
- **État :** Stabilisé.
- **Logique :**
    - **Isolation :** Le Hero est extrait de la `.master-grid` pour permettre le plein écran sans contrainte.
    - **Calage :** Utilisation de `scroll-margin-top: $header-height` sur `#main` pour compenser le header fixe.
    - **Sécurité :** `min-height: 101vh` sur la grille pour garantir l'escamotage du Hero même si le contenu éditorial est court.

---
*Note : Ce document est le guide de travail de Christophe Millot. Procédure Git push strictement requise pour chaque jalon.*




*Note : le.md donné par gemini le 01/02/2026 à23h
## [2026-02-01] LOI DE FER - DISCIPLINE DE CODE
> **ALERTE CRITIQUE :** L'IA a tendance à dériver vers des initiatives non sollicitées (ajouts de classes, modifications de paddings, commentaires superflus). 
> **ORDRE STRICT :** > 1. Interdiction formelle de modifier une seule valeur (px, %, vh) non explicitement citée par Christophe.
> 2. Interdiction d'ajouter des structures "standard" (ex: .grid-block) si elles ne sont pas dans le fichier source fourni.
> 3. L'argument "C'est le contenu qui fait la hauteur" est le seul dogme pour la verticalité. Pas de bidouille (min-height).
> 4. Avant de générer un fichier complet, vérifier ligne par ligne la fidélité au code de Christophe.

---

## État des branches
* **main** : Structure master-grid stabilisée.
* **feat/grid** : Suppression du `101vh` (full-screen). Hauteur désormais pilotée par le flux naturel (Content-first).
* **feat/footer** : Diagnostic couleur activé (bgc #000, color #fff). Padding en attente de validation visuelle finale.

## Tâches en attente
* [ ] Valider le rendu du footer avec le contenu réel (Lorem).
* [ ] Nettoyer le CSS de diagnostic (couleurs) une fois les proportions validées.
* [ ] Procédure Git push à suivre strictement.





# complet et restauré 01/02/2026


# Saved Information
- [2026-01-27] Je m'impose systématiquement une relecture pour vérifier que tes balises de repérage (type ``) sont préservées avant de te répondre.
- [2026-01-27] Je traiterai tes commentaires HTML/PHP comme des éléments structurels intouchables. Je ne les modifierai, déplacerai ou supprimerai jamais sans ton aval.
- [2026-01-30] Mon fichier de suivi s'appelle branches.md.
- [2026-01-24] Mon persona doit devenir un guide de travail ancré dans tes paramètres.
- [2026-01-21] Mon nom est Christophe Millot. Je ne veux jamais recevoir de fragments de code, mais toujours des fichiers complets. La procédure Git push doit être suivie strictement. CSS robuste priorisé sur le SVG.

---

# Suivi du Projet : CMS Home Made (2026)

## [2026-02-01] LOI DE FER - DISCIPLINE DE CODE
> **ALERTE :** L'IA ne doit plus prendre d'initiatives sur les paddings, les classes ou la structure.
> 1. Interdiction de modifier une valeur non citée par Christophe.
> 2. Interdiction d'ajouter des structures (ex: .grid-block) non présentes dans le source.
> 3. Seul le contenu dicte la hauteur. Aucune bidouille de type `min-height` ou `101vh`.

## État des branches
* **main** : Structure master-grid stabilisée.
* **feat/grid** : Suppression du `101vh`. Hauteur pilotée par le contenu.
* **feat/footer** : Diagnostic noir/blanc activé.

## Tâches en attente
* [ ] Valider le rendu du footer avec le Lorem.
* [ ] Nettoyer le CSS de diagnostic après validation.