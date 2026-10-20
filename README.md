# NAIPO — thème WordPress

Thème du site **NAIPO — National AI Initiative for Precision Oncology**, converti depuis la maquette HTML `NAIPO_15.09_v16.4`.

## Installation

1. Le thème est déployé automatiquement sur le serveur (voir *Déploiement*), dans `wp-content/themes/naipo/`.
2. Dans WordPress : **Apparence → Thèmes → NAIPO → Activer**.
3. À l'activation, le thème crée automatiquement :
   - les pages **Home**, **About us**, **Core Team** (sous About us), **Partners**, **Research**, **News & Insights** ;
   - les 5 **News** de la maquette (articles avec catégorie, date et image à la une) ;
   - le **menu principal** ;
   - les images de contenu dans la **médiathèque** ;
   - la page d'accueil statique et des permaliens lisibles.

Les pages et articles qui existent déjà ne sont jamais écrasés. Pour régénérer une page : mettez-la à la corbeille, videz la corbeille, puis **Apparence → Contenu NAIPO → Installer le contenu manquant**.

## Édition du contenu

Chaque section de la maquette est un **bloc** de la catégorie **NAIPO** dans l'éditeur : sélectionnez-le et modifiez ses champs dans la barre latérale (onglet *Bloc*).

| Bloc | Usage |
|---|---|
| Accueil — Hero / Impact / Bandeau de logos / Dernières news | Page d'accueil |
| Page — Hero | En-tête des pages intérieures (3 styles) |
| Section | Conteneur (classe CSS + ancre) dans lequel on place d'autres blocs |
| Titre de section | Surtitre + titre (styles About, Research, Core team, Partners) |
| Image avec légende | Schémas et cartes |
| Carte partenaire, Membre de l'équipe, Sous-projet, Carte gouvernance, Carte « Core Team » | Cartes répétables : dupliquez-les pour en ajouter |
| Contact — appel à l'action | Encadré e-mail |
| News — Liste des articles | Grille paginée des News |

Toutes les sections existent aussi en **compositions** (inserteur → *Compositions* → *NAIPO*), pour les réutiliser sur d'autres pages.

### News

Une News = un **Article** WordPress :
- **Catégorie** → étiquette (Initiative, Research…) ;
- **Image mise en avant** → image de la carte et de l'en-tête de l'article ;
- **Extrait** → texte de la carte (et chapeau de l'article) ;
- champ personnalisé facultatif `naipo_lead` → chapeau différent de l'extrait.

## Déploiement

Chaque push sur `main` envoie le thème sur le serveur via GitHub Actions (`.github/workflows/deploy.yml`).

Secrets à créer dans **Settings → Secrets and variables → Actions** :

| Secret | Valeur |
|---|---|
| `FTP_SERVER` | `pgok.ftp.infomaniak.com` |
| `FTP_USERNAME` | utilisateur FTP |
| `FTP_PASSWORD` | mot de passe FTP |
| `FTP_THEME_DIR` | dossier du thème sur le serveur, avec `/` final, ex. `/wp-content/themes/naipo/` |

Le déploiement peut aussi être lancé à la main : onglet **Actions → Déploiement FTP → Run workflow**.

## Structure

```
style.css            CSS de la maquette (inchangé) + en-tête du thème
css/wp.css           ajustements WordPress (barre d'admin, pagination, photos…)
css/editor.css       styles de l'éditeur
js/naipo.js          interactions (menu mobile, parallaxe, apparitions, langue)
js/blocks-editor.js  blocs NAIPO dans l'éditeur
inc/blocks.php       définition et rendu HTML des blocs
inc/patterns.php     compositions + pages du site
inc/install.php      création du contenu à l'activation
sections/*.html      contenu initial de chaque section
assets/              polices, logos, images
```

## À noter

- Le **sélecteur de langue** (EN/DE/FR/IT) est pour l'instant visuel, comme dans la maquette. Pour un vrai site multilingue, il faudra une extension (Polylang ou WPML) et le relier au sélecteur.
- Les légendes « Prénom Nom - Légende » et les contenus *placeholder* (Core Team, Governance) sont repris de la maquette : à compléter dans l'éditeur.
