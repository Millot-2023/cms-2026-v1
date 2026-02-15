# 🛠️ CMS-2026 : DOCUMENTATION UNIQUE & SYNTHÈSE GLOBALE

## 1. SUIVI D'ÉVOLUTION (`branches.md`)

### Vision Stratégique [2026-02-09]
* **Concept :** CMS Dynamique Local (XAMPP) -> Export Statique Production (Nuxit).
* **Surface d'Attaque :** Nulle (Fichiers .php de données sécurisés par filtrage IP locale).
* **Architecture :** Article > Sections (Grid-block). Système de stockage : Flat-file (`data.php`).

### État des Blocs (Cahier des Charges)
* **[A] Contenu :** ✅ Validé (CRUD dossiers/fichiers opérationnel).
* **[B] Sécurité :** ✅ Validé (Filtrage IP locale + Verrouillage Sidebar).
* **[C] Interface :** ✅ Validé (Sidebar noire `#000000`, Cockpit stabilisé).
* **[D] Rendu :** ✅ Validé (Grille Militaire 220px, Isolation `.editor-grid`, Extraction images physiques).
* **[E] Corbeille :** ✅ Validé (Système d'archivage vers `content/_trash/`).
* **[F] Export :** ⚪ En attente.

### Historique des Branches (Dernières évolutions)
* **feat-cockpit-corbeille** [2026-02-09] : Stabilisation de l'éditeur (initBlocks), routage absolu via `BASE_URL`, et système de corbeille fonctionnel.

---

## 2. DICTIONNAIRE & PROTOCOLES (`lexique.md`)

### Concepts UX/UI
* **Fat finger friendly :** Zones cliquables larges (min 44x44px) pour éviter les erreurs.
* **Grille Militaire :** Verrouillage strict de l'affichage des cards à **220px** de hauteur.
* **Sidebar Noire :** Repère visuel exclusif à l'administration (`#000000`).

### Concepts Sécurité & Structure
* **BASE_URL :** Constante impérative pour TOUS les liens (`header.php`) pour éviter les ruptures de navigation entre `/admin` et la racine.
* **Flat-file CMS :** Stockage sans base de données SQL dans des fichiers `data.php`.
* **Slug :** Identifiant unique basé sur le nom du dossier dans `content/`.

### Lexique de Travail
* **Push / Allez on push ! :** Ordre formel de sécurisation du code et mise à jour de la mémoire.

---

## 3. PROTOCOLES GIT & MAINTENANCE (`PUSH.md`)

### Procédure de Synchronisation Standard
1.  **État :** `git status`
2.  **Indexation :** `git add .`
3.  **Commit :** `git commit -m "TYPE: Description précise"`
4.  **Push :** `git push origin [nom-de-la-branche]`

---

## 4. DISCIPLINE DE CODE & DÉCISIONS IA
* **[2026-02-06] :** Sidebar gauche impérativement à `#000000`.
* **[2026-02-06] :** Interdiction de fragmenter les fichiers (envoi de **100% du code**).
* **[2026-02-07] :** Priorité au **CSS robuste** sur le SVG pour l'architecture.
* **[2026-02-09] :** **Routage Absolu :** Utilisation obligatoire de `<?php echo BASE_URL; ?>` pour les liens du header.
* **[2026-02-09] :** **Binding JS :** Toute modification de l'éditeur doit ré-initialiser les événements (`initBlocks()`).
* **[2026-02-08] :** **Neutralité Sémantique :** Interdiction de générer des textes de remplissage sans ordre direct.

---

## 5. AUDIT DE STRUCTURE & VÉRIFICATIONS CRITIQUES
* **Flux Data :** Extraction JPG/PNG obligatoire. Interdiction stricte du Base64 dans `data.php`.
* **Identité :** Le nom du dossier dans `/content` fait foi pour le routage.
* **Intégrité Nav :** Vérifier systématiquement la constante `BASE_URL` dans `core/config.php`.
* **Nettoyage :** Avant chaque push, supprimer les fichiers `.tmp` ou dossiers de tests vides.

---

## 6. GESTION DES ACTIFS (LOGIQUE SYSTÈME)
* **Chemins :** Utilisation de `ASSETS_URL` pour les ressources (CSS/JS) et `BASE_URL` pour la navigation.
* **Fail-Safe :** Toute écriture dans `data.php` doit d'abord valider l'existence du dossier cible.
* **Autonomie CSS :** Priorité aux styles encapsulés. Aucune dépendance externe (CDN) pour garantir le fonctionnement 100% Hors-Ligne (XAMPP).