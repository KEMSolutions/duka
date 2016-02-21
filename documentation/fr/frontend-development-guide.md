##### Développer sur Duka
# Guide d'introduction au frontend

![Duka](https://cdn.kem.guru/duka/help/duka_icon.png)

* Introduction à Git
* Les fichiers de vues
* Log files
* Guide css
* Guide js

## Introduction à Git

Si votre boutique est hébergée par KEM Solutions, notre équipe a créé une fourche (*fork*) de Duka pour votre boutique sur un service d'hébergement de code collaboratif, tel Bitbucket. Votre boutique est hébergée sur une instance (un serveur virtuel) configuré pour être entièrement compatible avec Duka. La boutique utilise la dernière version de la branche *master* du code de votre boutique. Ainsi, pour mettre à jour le code de votre boutique, vous n'aurez qu'à mettre à jour la branche *master* sur le service d'hébergement collaboratif.

[Git](https://fr.wikipedia.org/wiki/Git) est le système de gestion du code utilisé par notre équipe et un favoris de l'industrie. Si vous n'avez jamais utilisé Git, il sera important de vous familiariser avec son fonctionnement. Nous vous présentons néanmoins quelques instructions de base.

### Installer Git

Afin de reproduire fidèlement les conditions d'exploitation du serveur sur votre machine locale, nous vous conseillons fortement d'utiliser [Laravel Homestead](https://laravel.com/docs/master/homestead). Git est inclus dans l'image virtualisée installée par Homestead, vous évitant ainsi la tâche de l'installer localement!

### Télécharger localement une copie de votre boutique

Afin de pouvoir modifier votre boutique, vous devrez en télécharger une copie sur votre machine de développement. Pour ce faire, connectez-vous à notre service d'hébergement centralisé du code et repérez l'url de son *repository*.

Par exemple, sur Bitbucket, vous la trouverez bien en évidence dans le coin haut droit.

![Emplacement de l'URL sur Git](https://cdn.kem.guru/duka/help/repository_url.png)

Copiez cette URL et utilisez là pour exécuter la commande suivante:
````
$ git clone git@bitbucket.org:kemsolutions/votre_boutique.git
`````

en remplaçant l'url par celle de votre boutique. Notez que vous devrez ajouter la clef privée de votre ordinateur (ou de l'installation de Homestead) à votre profil Bitbucket pour que cette commande fonctionne. Vous pouvez éviter cette procédure et vous connecter en utilisant votre mot de passe en utilisant l'URL donnée par Bitbucket lorsque vous sélectionnez HTTPS en lieu et place de SSH dans le menu déroulant...au prix d'une sécurité réduite!

Une fois la commande effectuée, un répertoire local est créé avec l'ensemble des fichiers associés à votre boutique. Déplacez, renommez ce dossier et/ou modifiez le fichier `Homestead.yaml` de votre installation de Homestead afin de refléter l'emplacement de votre boutique nouvellement clonée.

Exécutez ensuite 
````
$ composer install
````
afin d'importer les dépendances de votre boutique.

### Déployer une version modifiée de votre boutique

Après avoir effectué des changements à votre boutique et les avoir testés localement, vous serez enfin prêt à déployer ces changements sur votre boutique en production!

Assurez vous d'abord d'établir un `commit` de vos changements.
````
$ git commit -am "Inscrivez une description des changements effectués"
````
Il est recommandé d'effectuer un commit à chacune des étapes logiques de vos modifications et d'adopter un style permettant de comprendre *pourquoi* les changements ont été effectués. Par exemple, un historique de commits pourrait ressembler à
- Modification du style d'un bouton pour mieux le séparer de l'arrière plan
- Ajout d'un lien vers mon site Internet au pied de page pour en faciliter l'accès.
- Changement de l'intitulé des boutons 'Acheter' pour 'Acheter!' pour se conformer aux normes graphiques de l'entreprise.
- etc.

Évitez donc les messages de *commit* du genre
- Changements majeurs

ou encore
- Modification des fichiers view.blade.php en remplaçant les lignes 213 et 214.

Notez que les messages de commit de votre boutique devraient toujours être pensés de manière à permettre l'intervention de programmeurs extérieurs sur votre projet. Par convention, préférez les messages en anglais.

Si vous ajoutez des fichiers à votre *repository*, une image ou un nouveau fichier de langue, par exemple, entrez cette commande **avant** d'effectuer votre *commit*:

````
$ git add .
````

Finalement, lorsque votre code est bien testé et prêt à être déployé, lancez la commande suivant:
````
$ git push
````

Votre boutique sera automatiquement mise à jour dans les minutes qui suivent. Notez que les fichiers statiques existants peuvent être mis en mémoire tampon temporairement dans les navigateurs de vos visiteurs et, le cas échéant, sur le réseau de distribution de fichier utilisé pour votre site (Cloudflare, par exemple).

### Résoudre un conflit de *merge*

Duka est mis à jour régulièrement par l'équipe de développement de KEM Solutions. Certaines de ces mises à jour visent à résoudre des bugs alors que d'autres visent à augmenter les fonctionalités des boutiques. Dans tous les cas, il peut être avantageux d'effectuer un *merge* de ces mises à jour sur votre boutique. Bitbucket vous permet d'effectuer facilement ce merge du code en amont vers votre code en aval, directement depuis l'interface du site.

![Invite de Bitbucket à mettre au goût du jour un repository](https://cdn.kem.guru/duka/help/fork_behind_master.png)

Cependant, si des fichiers ont été modifiés à la fois par notre équipe et vous, un conflit surgira et l'opération de sychronisation sera impossible depuis Bitbucket. Nous vous invitons à lire sur la résolution des conflits, comme les outils permettant de résoudre ceux-ci varient selon la plateforme utilisée. Règle générale vous devrez toutefois procéder comme ceci:

1. Récupérer la dernière version de Duka sur votre installation locale
````
$ git pull git@bitbucket.org:kemsolutions/duka.git master
````

2. Résoudre le conflit. Par exemple, sur OS X, lorsque xCode est installé, on peut utiliser le logiciel *FileMerge* (opendiff) pour effectuer cette opération.

````
$ git mergetool -t opendiff
````

FileMerge s'ouvre alors, exposant les conflits repérés.

![Interface de opendiff](https://cdn.kem.guru/duka/help/opendiff.png)

- Représenté dans la partie A, le fichier *local*, donc le fichier que vous avez modifiés.
- Représenté dans la partie B, le fichier distant (*remote*), donc le fichier tel que mis à jour par les développeurs de Duka.
- Représenté dans la partie C le résultat (éditable) de votre opération de *merge*.
- Les flèches au milieu représentent la partie conservée de chacune des différences entre les deux fichiers. Les flèches soulignées en rouge indiquent un conflit nécessitant votre intervention.
- Dans le coin bas-droit, le menu Actions, vous permettant d'indiquer, pour chacun des conflits, si la version à conserver est celle de gauche (A - locale), ou droite (B - distante).

Cliquez sur chacunes des flèches du centre de manière à résoudre, un à un, les conflits.

Lorsque le conflit est résolu, sauvegardez le fichier final (qui s'affiche en C), puis quittez FileMerge (⌘+q).

3. Testez les modifications
4. Effectuez un commit en incluant les nouveaux fichiers.
````
$ git add -A
$ git commit -am "Resolved a merge conflict"
````

4. Déployez normalement votre boutique.

````
$ git push
````

## Les fichiers de vues

### Hiérarchie
* Fichier de traduction:
    * en: `resources/lang/en/boukem.php`
    * fr: `resources/lang/fr/boukem.php`
* Analytics:
    * Google Analytics: `resources/views/analytics/GAE/*`
    * MixPanel: `resources/views/analytics/mixpanel/*`
    * piwik: `resources/views/analytics/_piwik.blade.php`
* Authentication: `resources/views/auth/*`
* Checkout: 
    * main file: `resources/views/checkout/_checkout_form.blade.php`
    * country/province list: `resources/views/checkout/_country_list.blade.php` et `resources/views/checkout/_province_state_reg.blade.php`
* Pages d'erreur: `resources/views/errors/*.blade.php`
* Layout: Composants présents à travers tout le site sans comportement spécifique.
    * `resources/views/layout/*.blade.php`
* Produit: Vues responsables de l'affichage des produits. 
    * page de produit: `resources/views/product/view.blade.php`
    * carte de produit: `resources/views/product/_card.blade.php`
* Site: Vues spécifiques à certaines fonctionnalités. Ces dernières sont spécifiques au dossier dans lequel les vues sont.
    * `resources/views/layout/site/*`


### Introduction à Semantic UI

Afin de faciliter le développement frontend, Duka utilises [Semantic UI](http://semantic-ui.com/) comme cadre de développement. Il est fortement recommandé de se familiariser avec les concepts de base de cet outil afin de modifier les vues. Afin de faciliter votre introduction, voici quelques pointeurs sur les différentes composantes:

* Grid: http://semantic-ui.com/collections/grid.html  
`grid` de 16 colonnes.  
`row` permet d'aligner horizontalement les `column`.  
`stackable` permet de présenter les colonnes verticalement (une à la suite de l'autre) sur un appareil mobile.  
````
<div class="ui grid">
  <div class="row">
    <div class="five wide column">Colonne de gauche</div>
    <div class="six wide column">Colonne du centre</div>
    <div class="five wide column">Colonne de droite</div>
  </div>
</div>
````

* Container: http://semantic-ui.com/elements/container.html  
Permet d'ajuster la largeur en fonction de la largeur d'écran. Peut être jumelé avec `grid` dans un seul élément. 
````
<div class="ui container">
    <h1 class="ui header">Mon titre</h1>
</div>

<div class="ui grid container">
  <div class="row">
  ...
  </div>
</div>

<div class="ui fluid container">
  Pas de largeur maximale, s'étends à tout l'écran.
</div>

<div class="ui text container">
  <p>Largeur réduite, facilitant la lecture de texte.</p>
</div>
````


* Menu: http://semantic-ui.com/collections/menu.html
````
<div class="ui menu">
  <a class="item">Editorials</a>
  <a class="item">Reviews</a>
  <a class="item">Upcoming Events</a>
</div>
````

### Introduction à Blade

Duka utilises le cadre de développement PHP Laravel pour tout son backend. Les fichiers vues n'échappent pas à cet usage et adoptent [Laravel Blade](https://laravel.com/docs/master/blade).

La documentation de Laravel Blade est très explicite et son usage est simple. Notez que nous avons toutefois étendus Blade pour faciliter le développement des boutiques en ligne. En effet, on peut utiliser `@product(123)`pour ajouter une carte de produit dans n'importe quelle vue, où *123* est l'ID d'un produit.

### Personnaliser le header  
*(resources/views/layout/_header.blade.php)*  
Le header est organisé en deux lignes: `supertop` et `mainmenu`. 

`supertop` est la partie supérieure (Retour au site principal, Recherche, Panier sont les options par défaut), `mainmenu` est la partie principale regroupant les items essentiels à la navigation du site (Accueil, Catégories, Marques, Contact par défaut).

### Ouvrir le panier
Attacher la classe `view-cart` à un élément pour lui permettre d'afficher le panier lors d'un événement de type clic.


### Modifier logo
Le logo est défini par l'API de KEM et est accessible en appelant 
`{{ Store::logo() }}`.  
Par défaut, il est présenté dans la section `.mainmenu` du header.


## Fichiers de logs
Les fichiers de log sont disponibles à: `storage/logs/*.log`

## Guide css
Les fichiers de style sont dans: `public/css/dev/*`.  
### Organisation.
* `base`regroupe les styles de base (alert / badge / form / ...)  
* `app.css` est notre fichier principal: il contient toutes nos modifications de style. Si les modifications de style ne concernent aucun des autres modules ayant un fichier spécifique, elles devraient aller ici.
* `colors.css` est le fichier responsable d'appliquer les couleurs délivrées par l'API - lire les commentaires pour plus de détails.

### Gulp.
A la fin de chaque modification et avant de rafraichier et voir les changements apparaitre, il convient de: 
1. gulp css
2. php artisan cache:clear



## Guide js
Lire le readme.md pour plus d'information.

