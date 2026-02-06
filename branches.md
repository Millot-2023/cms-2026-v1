# Suivi du Projet : Evolution (Système Hybride)

## Vision Stratégique [2026-02-02]
- **Concept :** CMS Dynamique Local (XAMPP) -> Export Statique Production (Nuxit).
- **Surface d'Attaque :** Nulle (Fichiers .php de données convertis ou sécurisés).
- **Architecture :** Article > Sections (Grid-block). Système de stockage : Flat-file (data.php).

## État des Blocs (Cahier des Charges)
- **[A] Contenu :** ✅ Validé (CRUD dossiers/fichiers opérationnel).
- **[B] Sécurité :** ✅ Validé (Filtrage IP locale + Verrouillage Sidebar).
- **[C] Interface :** ✅ Validé (Bouton Quitter fixe, Sidebar noire persistante, cockpit stabilisé).
- **[E] Export :** ⚪ En attente.

---

## Plan de Développement (Branches Git)

### 1. Branche : `feat/core-structure` [TERMINE / MERGED]
- **Objectif :** Génération auto des projets et moteur de sauvegarde.

### 2. Branche : `stabilite-editeur-2026` [STATUT : STABLE / VERSION DEFINITIVE]
- **Objectif :** Finalisation de l'ergonomie de l'éditeur.
- **Résultat :** - Bouton "QUITTER" ancré en zone fixe sous "PUBLIER".
    - Correction des injections de textes par défaut (H1, P, Grilles).
    - Nettoyage du dépôt (Suppression de `editor copy.php`).
    - Préservation des jauges Gutter, Width et Image Upload.

### 3. Branche : `feat/ui-refinement` [EN COURS]
- **Objectif :** Identité visuelle des Cards et stabilisation de la vue Article.
- **Tâches :** - Intégration des `thumb.jpg` dans la boucle index.
    - Désactivation du `:hover` sur le `.grid-block` en mode lecture seule.
    - Formatage des dates et résumés (tronçonnage).

---

## Historique des Décisions IA (Discipline de Code)
- **[2026-02-02] :** Validation du moteur de sauvegarde.
- **[2026-02-06] :** Stabilisation du cockpit. Respect strict de la sidebar noire. Interdiction de fragmenter les fichiers PHP envoyés.