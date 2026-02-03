# 📖 DICTIONNAIRE GEMINI & PROTOCOLES

## • Fat finger friendly
Se dit d'une interface utilisateur (boutons, liens, menus) conçue avec des zones cliquables suffisamment larges pour être activées facilement avec le pouce ou l'index, sans erreur de frappe.

L'idée est d'éviter l'effet "gros doigts" où l'on appuie sur deux boutons en même temps ou sur le mauvais lien parce qu'ils sont trop petits ou trop proches.

### Les règles d'or (Standard UX)
Pour qu'une interface soit considérée comme **Fat finger friendly**, elle doit respecter certains critères techniques :
* **Taille minimale :** La zone tactile doit mesurer au moins 44x44 pixels (Apple) ou 48x48 pixels (Google).
* **Espacement :** Il doit y avoir assez de "vide" (padding/margin) entre deux éléments interactifs.
* **Accessibilité :** Essentiel pour les smartphones et pour les personnes ayant des troubles de la dextérité.

### Exemple concret dans ton code
Au lieu d'un lien texte minuscule : `[Supprimer]`
On préférera un bouton robuste en CSS : `[    Supprimer    ]` (avec un padding généreux).

---

## • Surface d'Attaque Minimale
> **Définition :** Concept de sécurité informatique visant à limiter au maximum le nombre de points d'entrée (vecteurs d'attaque) qu'un pirate pourrait exploiter. Plus la surface est petite, plus le système est facile à sécuriser.

### Application au projet
Dans le cadre de ce développement, la stratégie retenue est l'**Isolation Physique** :
* **Environnement Local :** Contient le moteur dynamique (`PHP`), l'interface de gestion (`Admin`) et les fichiers de configuration. C'est ici que se trouve la "complexité".
* **Environnement Production (Nuxit) :** Ne contient que les fichiers exportés (`HTML`, `CSS`, `JS` épuré). 
* **Résultat :** En supprimant tout script exécutable et toute base de données du serveur distant, on réduit la **Surface d'Attaque** à néant. Un fichier HTML "mort" ne peut pas être hacké.

---

## • Local-First Design
> **Définition :** Approche consistant à construire l'outil de production (l'établi) sur une machine locale (ton PC via XAMPP) avant de pousser le résultat final vers le web.

L'utilisateur (Christophe) est le seul à posséder les outils de création. Le serveur distant n'est qu'un miroir d'exposition, ce qui renforce la sécurité et garantit le contrôle total sur les données sources.

---

## • Flat-file CMS (Système de fichiers plats)
Système qui stocke ses données dans des fichiers texte ou PHP individuels (comme tes fichiers `data.php`) plutôt que dans une base de données SQL.
* **Avantage :** Ultra-léger, facile à sauvegarder (un simple copier/coller du dossier `content/`), et parfaitement adapté à une version statique.

---

## • Slug
La partie d'une URL qui identifie une page de manière lisible (ex: `projet-20260202-231731`).
Dans ce projet, le **Slug** correspond au nom du dossier dans `content/`. Il sert de clé unique pour charger le bon contenu dans `article.php`.

---

# 📝 DICTIONNAIRE MARKDOWN (Protocoles)

## 1. Titres (Structure Hiérarchique)
`# Titre 1` : Nom du projet uniquement.
`## Titre 2` : Sections principales (Architecture, Historique).
`### Titre 3` : Sous-sections (Fichiers, Logique).

## 2. Listes de Tâches (Task Lists)
* `[ ]` Tâche à faire (Espace obligatoire après le crochet).
* `[x]` Tâche terminée (Le 'x' peut être minuscule ou majuscule).

## 3. Emphase & Style
* *Italique* : Pour les notes légères.
* **Gras** : Pour les mots-clés et l'emphase.
* ~~Barré~~ : Pour les idées ou fonctions abandonnées.

## 4. Blocs de Code (Syntax Highlighting)
Utiliser les backticks (```) suivis du nom du langage (php, scss, markdown) pour activer la coloration syntaxique.

## 5. Citations & Alertes (Blockquotes)
> **NOTE :**
> Nécessite une ligne vide AVANT le chevron `>`.
> Le symbole `>` doit être au début de la ligne.

---

## 6. Tableaux (Tables)

| Composant | État | Fichier |
| :--- | :---: | ---: |
| Header | OK | _header.scss |
| Footer | OK | _footer.scss |