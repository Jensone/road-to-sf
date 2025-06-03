# Commandes Symfony CLI

IMPORTANT : `php bin/console` et `symfony console` sont exactement la même commande

Pour utiliser la commande `symfony` il faut l'installer sur sa machine. Cet outil est exclusivement dédié au développement, pas à la production.

IMPORTANT 2: Les commandes ne fonctionnent que dans un projet Symfony (avec un dossier `bin` à l'intérieur).

## Création de projet

```bash
# Créer une application web fullstack
symfony new blog --webapp

# Créer une api ou microservice
symfony new doctomap
```

## Créer une base de données

Pour créer une base de données il faut utiliser la commande :

```bash
symfony console doctrine:database:create
```

Une BDD par défaut ne contient pas les tables correspondantes à vos entités. Pour les ajouter il faut créer un fichier de migration : 

```bash
symfony console make:migration
```

Le fichier de migration est créé dans `src/Migrations`. Il faut ensuite l'exécuter :

```bash
symfony console doctrine:migrations:migrate
```

## Remplir la base de données avec les fixtures

Les fixtures sont des données pré-remplies dans la base de données. C'est vous qui devez les coder pour remplir la base de données: 

```bash
symfony console doctrine:fixtures:load
```

## Créer une entité

Les entités sont des classes qui représente les données stockés en BDD.

```bash
symfony console make:entity
```

## Créer un utilisateur

```bash
symfony console make:user
```

Surtout pas de `make:entity` pour créer un utilisateur, il faut utiliser la commande `make:user` qui crée un utilisateur avec toutes les méthodes de base.

Ensuite pour modifier l'utilisateur avec le CLI, on utilise la commande `make:entity`.