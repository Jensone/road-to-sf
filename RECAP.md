#

## Préparation

Ce qui suit part du principe que vous disposez de : 

- User stories
- Modélisation UML
- Assets (images etc.)
- UI Design (Design System, police d'écriture, etc.)
  
Avant de continuer.

## Créer un projet avec symfony-cli

Pour créer un projet, il suffit de tapez dans terminal la commande :

```bash 
symfony new nomduprojet --webapp

```

`--webapp` permet de configurer le projet en application web traditionnelle (back + front). Sans ce drapeau nous obtiendrons une structre pour une application de type API ou microservice.

!IMPORTANT! : déplacez-vous dans le dossier du projet créé par symfony-cli avec la commande `cd nomduprojet`


## Suite à la création du projet


Il convient de commencer par créer les entités avant même de s'intéreser à autre chose.

```bash 
symfony console make:entity

# ou

symfony console m:e

```

symfony-cli va vous poser des queestions afin de construire votre entité en autonomie, puis vous pourrez la modifier à votre guise par la suite une fois créée.

La seul entité que vous ne pouvez pas créer avec `m:e`est l'utilisateur (quelque soit son nom).
Pour le faire on va utiliser la commande suivante :

```bash 
symfony console m:user
```

Une fois cette entité créée, vous pouvez la modifier avec la commande `m:e`, ou manuellement.

## Création de la base de données

Doctrine, l'ORM de symfony, nécessite une base de données pour fonctionner. Avant tout il faut renseigner les information de connexion à celle-ci. Rendez-vous dans le fichier `.env` et modifier les lignes correspondantes en fonction de votre configuration (SQLite, MySQL, PostgreSQL, etc.).

Exemple : 

```text
DATABASE_URL="sqlite:///%kernel.project_dir%/var/data_%kernel.environment%.db"
```

Cela va configurer votre projet pour utiliser une base de données SQLite, donc Doctrine agira en fonction de cela.


### Commandes

Créer un fichier de migration :

```bash 
symfony console make:migration

```

Créer la base de données :

```bash 
symfony console doctrine:database:create

# ou

symfony console d:d:c

```

Executer les migrations :

```bash 
symfony console doctrine:migrations:migrate

# ou

symfony console d:m:m

```

Ces étapes, donne à la fin un base de données composée du schéma issue des entités de notre projet.

!IMPORTANT! : Toutes modifications* des PROPRIÉTÉS d'une entité doit être suivie d'une nouvelle migration dans la base de données.

*nom, type ou nullable

## Validation de données

Premier pas vers la sécurisation de notre application, la validation est un concept propre à tout type de framework ou application. L'objectif étant de limité voir complète bloquer la possibilité d'écrire des données incorrectes (en fonction de nos contraintes).

Dans symfony, on utilise les `Annontations` pour mettre en place des validations.

```php

/...

use Symfony\Component\Validator\Constraints as Assert;

/...

    #[ORM\Column(length: 255)]
    #[Assert\Length(max: 255, message: '{{ max }} caractères maximum')]
    #[Assert\Regex(pattern: '/^[a-z0-9-]+$/')]
    private ?string $slug = null;
    
/...

```

## Création des Fixtures

Les fixtures sont des données pré-remplies qui sont utilisées pour initialiser la base de données lors de la création de la structure de notre projet.

Leur avantage est de permettre de définir des données de base pour les tests, et de les utiliser lors de l'exécution des tests ainsi le développement de l'application.

### Installation des fixtures

Pour installer les fixtures, il suffit de lancer la commande suivante :

```bash

composer req orm-fixtures --dev
```

### Création des fixtures

Créer un fichier de fixtures :

```bash
symfony console make:fixtures
```

Cette commande va créer un fichier `src/DataFixtures/<nomdufichier>.php`.


## Exécution des fixtures

Pour exécuter les fixtures, il suffit de lancer la commande suivante :

```bash

symfony console doctrine:fixtures:load

# ou

symfony console d:f:l
```


