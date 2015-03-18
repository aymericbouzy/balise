balise
======

Le site **balise** permet aux binets de la Kès de l'Ecole Polytechnique de gérer en ligne leur trésorerie et leurs subventions.

# Installation

Pour installer le site, il faut :
* mettre en ligne le contenu du dossier htdocs
* ajouter le fichier de configuration
* initialiser la base de données SQL
* mettre à jour le fichier .htaccess

## Dossier htdocs

Le site peut être mis en ligne uniquement depuis le réseau élève. Il faut tout d'abord être administrateur du groupe Frankiz "Projet Balise", la demande se fait directement sur [frankiz](https://www.frankiz.net/groups/see/projetbalise). Ensuite, on a accès au dossier ftp sur le serveur `binets`, auquel on se connecte avec son login et mot de passe frankiz. Il ne reste plus ensuite qu'à remplacer le dossier `htdocs` par celui du repository `balise`.

## Fichier de configuration

Le fichier de configuration du site s'appelle `htdocs/config/production.php` : le fichier `htdocs/config/development.php` peut servir de base pour savoir quels paramètres spécifier, et sous quelle format. Si on développe en local et que l'on souhaite utiliser le fichier `development.php`, il suffit de définir une variable d'environnement au lancement du serveur. Avec MAMP en particulier, il suffit de rajouter le fichier `envvars` suivant dans le dossier `MAMP/Library/bin/` avec par exemple :
```
export state="development"
export webmaster_email="prenom.nom@polytechnique.edu"
```
Cela aura pour effet de définir une variable d'environnement appelée `state`, initialisée à `"development"` ; le site prendra alors le fichier de configuration `developement.php`.
La variable `ROOT_PATH` permet éventuellement de placer le site dans un sous-dossier : par exemple, si on initialise le serveur sur le dossier `balise/` (plutôt que `htdocs/`), on pourra définir la variable `ROOT_PATH` comme étant égale à `htdocs/`.
Les variables `DATABASE_USERNAME`, `DATABASE_PASSWORD`, `DATABASE_NAME`, `DATABASE_HOST`, `DATABASE_PORT`, `FRANKIZ_AUTH_KEY` sont fournies par le BR. La valeur de `FRANKIZ_AUTH_KEY` dans le fichier `development.php` est valide si le site se trouve sur `localhost:3000`.
La variable `REAL_FRANKIZ_CONNECTION` **doit** être définie à `true` sur le site en production. Quand elle est définie à `false`, plutôt que de se connecter via frankiz, on se connecte en choissisant l'identité de n'importe quelle personne existante en base de données. C'est pratique en développement pour faire des tests sur les droits des différentes personnes.

## Initialisation de la base de données SQL

Pour initialiser la base de données, il suffit d'exécuter le fichier `Balise.sql` dans la base de données que l'on aura préalablement créée, en lui donnant pour nom celui renseigné dans `DATABASE_NAME`.
La première personne à se connecter sera administrateur de la Kès.

## Mise à jour du fichier .htaccess

Le fichier `.htaccess` permet de faire de la réécriture d'url. Il convertit par exemple l'url `home` en `index.php?controller=home&action=index`. Pour le mettre à jour ou le créer, il suffit d'exécuter le fichier `url_rewriting.php` : par exemple, si le serveur a été lancé sur `localhost:3000`, il suffit de faire la requête `localhost:3000/url_rewriting.php`.
Si on ne souhaite pas utiliser de réécriture d'url, on peut l'indiquer en définissant la variable de configuration `URL_REWRITE` à `false`.

## Mise à jour du site en production sur balise.bin

Pour mettre à jour le site, il suffit de suivre les mêmes étapes : mise en ligne du fichier htdocs, mise à jour des champs de la base de données si certains ont été modifiés ou ajoutés (à voir selon les situations), mise à jour des urls reconnues en faisant simplement la requête `balise.bin/url_rewriting.php`.

# Principes suivis dans le code du site

Le code utilise l'architecture MVC (Model, View, Controller), essaye de suivre la norme DRY (Don't Repeat Yourself), et possède un certain nombres de conventions propres, largement inspirées pour certaines de la philosophie du Ruby on Rails.

## MVC

MVC est un principe pour organiser du code selon 3 catégories : le modèle, les vues et les controllers.

### Modèle

Le modèle est ce que l'on souhaite manipuler : il définit un certain nombre d'opérations que l'on peut effectuer, et des manières d'accéder aux informations qu'il contient. Il donne un sens à ce qui est contenu dans la base de donnée.
Dans le cas du site balise, une opération est un modèle : on peut récupérer des informations sur une opération, comme par exemple son montant, mais aussi son état (en attente de validation, validée ...). Ces informations peuvent être directement stockées en base de donnée (le montant) ou calculées (l'état) à partir de plusieurs informations présentes dans la base de donnée.
Le but est que lorsqu'on a besoin de n'importe quelle information, il n'y a plus qu'à la demander au modèle, sans avoir à effectuer le moindre calcul.

### Vues

Les vues sont tout ce que l'utilisateur voit : les pages web, les emails ... C'est ici qu'on y retrouve le HTML, dans lequel sont insérées les informations au moyen de balise php. On y fait aucun calcul, uniquement des appels de fonctions.

### Controlleurs

Le controlleur est ce qui permet de savoir quoi effectuer pour chaque url demandées : il s'occupe des vérifications de sécurité et de validité des informations envoyées par l'utilisateur, il appelle les fonctions qui modifient le modèle, il redirige l'utilisateur, il génère la vue qui convient.

## DRY

La norme DRY indique que chaque information ne doit être écrite qu'une seule fois. Il s'agit d'une règle générale à tout code informatique qui permet de modifier du code facilement, et de le faire évoluer selon les besoins, en plus de faire gagner du temps pour l'écrire (à long terme). La norme DRY est souvent antagoniste avec les problèmes d'efficacité du code produit (il y a souvent beaucoup trop d'appels de fonctions), mais on ne se préoccupe pas de ces considérations pour l'instant.
Le meilleur moyen de se rendre compte que l'on est en train de violer la norme DRY est lorsqu'on a recours au copié-collé d'autre chose que du nom d'une variable ou d'une fonction. Si on est dans cette situation, c'est qu'il y a probablement un moyen de factoriser son code.
Il existe une exception à cette norme : les vues. Les vues sont tellement susceptibles d'être modifiées que l'on tolère un tout petit peu de code non-DRY, pourvu que cela reste très localisé.

## Conventions propres

* *les helpers* : Les helpers sont des fonctions particulières qui retournent du code HTML : elles permettent justement d'ecrire du code DRY, même dans les vues.
* *le fichier layout/application.php* : ce fichier est celui à partir duquel toutes les pages HTML du site sont générées.
* *le dossier asset/* : on y met tous les fichiers qui peuvent être demandés par l'utilisateur : CSS, javascript, images ...
* *le dossier form/* : la logique de chaque formulaire est décrite a cette endroit. Il permet ensuite soit de génerer le formulaire HTML montré à l'utilisateur, soit de vérifier que `$_POST` contient des informations valides pour ce formulaire, et de les mettre en forme pour être utilisable par le controlleur.
* *les fichiers base.php* : ces fichiers contiennent du code qui doit être effectué préalablement à l'exécution de n'importe quel fichier dans le même dossier.
* *les varibles $binet et $term* : si on se trouve dans un controlleur préfixé par `binet`, donc relatif au mandat d'un binet particulier, les variables `$binet` et `$term` contiennent respectivement le mandat et la promo du mandat concerné. Attention à ne pas les écraser ! TODO : remplacer par des constantes php.
