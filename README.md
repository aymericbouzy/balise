balise
======

Le site **balise** permet aux binets de la Kès de l'Ecole Polytechnique de gérer en ligne leur trésorerie et leurs subventions.

# Installation

Avant tout, il s'agit de récupérer le contenu du site : on peut le faire de diverses façons en utilisant GitHub, mais si les termes `SSH` ou `git clone` ne te dise rien, le plus simple est encore de télécharger le site au format .zip ...

Pour installer le site (sur `balise.bin` ou en local), il faut :
* placer le dossier htdocs à la racine du serveur
* ajouter le fichier de configuration
* initialiser la base de données SQL
* démarer le serveur sur le port 3000 si on fait tourner le site en local

## Dossier htdocs

Le site peut être mis en ligne sur `balise.bin` uniquement depuis le réseau élève. Il faut tout d'abord être administrateur du groupe Frankiz "Projet Balise", la demande se fait directement sur [frankiz](https://www.frankiz.net/groups/see/projetbalise). Ensuite, on a accès au dossier ftp sur le serveur `binets`, auquel on se connecte avec son login et mot de passe frankiz. Il ne reste plus ensuite qu'à remplacer le dossier `htdocs` par celui du repository `balise`.
En local, il faut initialiser le serveur sur le dossier `htdocs`. Dans le cas où l'on ne peut pas configurer soi-même le dossier sur lequel est démarré le serveur, on peut créer un symlink du dossier `htdocs` dans le dossier où est démarré le serveur, que l'on appelera par exemple `balise` : ensuite, il suffit de définir la variable `ROOT_PATH` à `"balise/"` (voir section suivante).

## Fichier de configuration

Le fichier de configuration du site sur `balise.bin` s'appelle `htdocs/config/production.php`, et sur `localhost:3000`, il s'appelle `htdocs/config/development.php` : le fichier `htdocs/config/example.php` peut servir de base pour savoir quels paramètres spécifier, et sous quelle format.

### Racine du serveur

La variable `ROOT_PATH` permet éventuellement de placer le site dans un sous-dossier : par exemple, si on initialise le serveur sur le dossier `balise/` (plutôt que `htdocs/`), on pourra définir la variable `ROOT_PATH` comme étant égale à `htdocs/`.

### Connexion à la base de données

Les variables `DATABASE_USERNAME`, `DATABASE_PASSWORD`, `DATABASE_NAME`, `DATABASE_HOST`, `DATABASE_PORT`, `FRANKIZ_AUTH_KEY` sont fournies par le BR pour le serveur `balise.bin`, et par la base de données locale si on développe en local. La valeur de `FRANKIZ_AUTH_KEY` dans le fichier `example.php` est valide si le site se trouve sur `localhost:3000`. Si problème de connexion à la base de donnée, essayer avec `DATABASE_PORT` définie à `""`, et vérifier également les autres paramètres.

### Login via Frankiz

La variable `REAL_FRANKIZ_CONNECTION` **doit** être définie à `true` sur le site en production. Quand elle est définie à `false`, plutôt que de se connecter via frankiz, on se connecte en choissisant l'identité de n'importe quelle personne existante en base de données. C'est pratique en développement pour faire des tests sur les droits des différentes personnes.

### Réécriture d'URL

La variable `URL_REWRITE` sert à définir si l'on souhaite utiliser de la réécriture d'URL ou non, c'est à dire utiliser des URLs du type `/home` ou `./index.php?controller=home&action=index`. Si le serveur utilisé n'a pas de RewriteEngine capable d'interpréter le fichier `.htaccess`, il peut être utile de définir `URL_REWRITE` à `false`.

## Initialisation de la base de données SQL

Pour initialiser la base de données, il suffit d'exécuter le fichier `Balise.sql` dans la base de données que l'on aura préalablement créée, en lui donnant pour nom celui renseigné dans `DATABASE_NAME`.
La première personne à se connecter sera administrateur de la Kès.

## Démarage du serveur sur le port 3000

Si l'on souhaite utiliser la connexion avec Frankiz, c'est obligatoire. Si l'on souhaite utiliser deux fichiers de configuration, celui de production et celui de dévelopement, c'est également nécessaire. Dans tous les autres cas, c'est à dire si `REAL_FRANKIZ_CONNECTION` a été définie à `false` et si on a nommé le fichier de configuration `production.php`, on peut faire tourner le site sur le port que l'on souhaite.
La configuration du port du serveur est spécifique à chaque serveur : dans MAMP, on trouve ce paramètre dans Préférences > Ports > Apache Port.

## Mise à jour du site en production sur balise.bin

Pour mettre à jour le site suite à , il suffit de suivre les mêmes étapes : mise en ligne du fichier htdocs et mise à jour des champs de la base de données si certains ont été modifiés ou ajoutés (à voir selon les situations).

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

* *les helpers* : Les helpers sont des fonctions particulières qui retournent du code HTML : elles permettent justement d'écrire du code DRY, même dans les vues.
* *le fichier layout/application.php* : ce fichier est celui à partir duquel toutes les pages HTML du site sont générées.
* *le dossier asset/* : on y met tous les fichiers qui peuvent être demandés par l'utilisateur : CSS, javascript, images ...
* *le dossier form/* : la logique de chaque formulaire est décrite a cette endroit. Il permet ensuite soit de génerer le formulaire HTML montré à l'utilisateur, soit de vérifier que `$_POST` contient des informations valides pour ce formulaire, et de les mettre en forme pour être utilisable par le controlleur.
* *les fichiers base.php* : ces fichiers contiennent du code qui doit être effectué préalablement à l'exécution de n'importe quel fichier dans le même dossier.
* *le fichier .htaccess* : il est généré par le site : inutile d'aller le modifier soi-même ! c'est la fonction `urlrewrite` qui s'en occupe.
