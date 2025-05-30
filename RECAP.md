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

//...

use Symfony\Component\Validator\Constraints as Assert;

//...

    #[ORM\Column(length: 255)]
    #[Assert\Length(max: 255, message: '{{ max }} caractères maximum')]
    #[Assert\Regex(pattern: '/^[a-z0-9-]+$/')]
    private ?string $slug = null;
    
//...

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

### Les fixtures en détail

Dans les fixtures, il arrive que certaines d'entre elles soient nécessaires pour les autres. Par exemple, pour créer les articles, nous avons besoin des utilisateurs. Ou pour écrire les commentaires, nous avons besoin des articles.

Dans cette configuration, nous allons utiliser des dépendances entre les fixtures.

1 - Ajouter l'interface `DependentFixtureInterface` à la classe de la fixture qui à besoin d'une dépendance :

```php
//...

use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class CommentFixtures extends Fixture implements DependentFixtureInterface

//...

```

2 - Ajouter la méthode `getDependencies()` à la classe de la fixture qui à besoin d'une dépendance :

```php
//...

public function getDependencies(): array
{
    return [
        UserFixtures::class,
        ArticleFixtures::class, 
    ];
}

//...
```

3 - Dans le fichier `UserFixtures.php` et `ArticleFixtures.php`, ajouter les références :

```php

// $this->addReference($string, $object);

$this->addReference('USER_' . $i, $user);

```

`$i` fait référence à l'index de la boucle dans le cas où on créer plusieurs objets comme un ensemble d'article ou de commantaire.

4 - Pour finir, la récupération des objets ajoutés en référence dans les fixtures dépendantes :

```php
//...

$users = [];
for ($i = 0; $i < 100; $i++) {
    $users[] = $this->getReference('USER_' . $i, User::class);
}

//...
```

ASTUCE : Pour savoir si des fixtures sont dépendantes en elles, il suffit de consulter le diagramme de classe de l'application.

## Controllers

À l'aide des contrôlleurs, on va pouvoir gérer les actions de l'application suite aux requêtes HTTP réliser par les utilisateurs.

### Annotations

Les annotations depuis PHP 8, permettent de définir des des actions ou comportements dans le script sans avoir à ajouter d'éléments supplémentaires dans le code.

```php
#[Route('/articles', name: 'articles', methods: ['GET'])]
```

Ici, on définit une route à l'aide de la classe `Route` de Symfony. 
Elle contient les informations suivantes :

- l'URL : `/articles` qui est accessible depuis le navigateur
- le nom de la route : `articles` qui sera utilisé pour la récupération de la route depuis le code
- les méthodes HTTP : `GET` qui permet de récupérer les données de l'article

### Les routes

Une route est contituée de 2 parties :

- l'annotation
- la méthode (function)

Pour l'annontion, nous avons déjà vu plus haut à quoi cela correspond. Concernant la méthode, c'est à nous de définir le comportement de l'application lorsque la route qui la constitue est appelée.

```php
public function index(ArticleRepository $ar): Response
{
    // ... Votre code ici ...
}
```

Le code dans cette partie doit correspondre au scénario que vous avez défini lors de votre préparation pour la conception de l'application.

### Les données dans une route

Le contrôleur étant le chef d'orchestre de l'application, il est responsable de gérer les actions qui vont se produire. Parmis elles, il s'agira d'afficher des données particulières issues de la base de données.

Pour cela, nous diposons de plusieurs méthodes associées à nos en entités au travers des classes composées du terme `Repository`.

```php
public function index(ArticleRepository $ar): Response
{ 
    $articles = $ar->findAll(); // Récupération de tous les articles
}
```

### La passation à une vue

Pour communiquer avec les vues, nous devons utiliser une méthode spécifique qui se nomme `render()`. Cela nous permet de définir des variables qui seront disponibles dans les templates Twig, afin de les exploiter pour l'affichage et le rendu final.

```php
public function index(ArticleRepository $ar): Response
{
    $articles = $ar->findAll();
    return $this->render('article/index.html.twig', [
        'articles' => $articles // Envoi des articles à la vue sous forme de tableau d'objets
    ]);
}
```

## Twig

Twig est un moteur de template qui permet de générer du HTML. Mais il dispose de fonctionnalités plus avancées que le langage de balisage simple.

Nous pourrons utiliser des "fonctions", "extensions" ou "filtres" pour enrichir le template.

Quelques exemples :

- Afficher une variable : `{{ variable }}`
- Afficher une liste d'éléments : `{% for item in articles %}<!-- Votre code ici -->{% endfor %}`
- Définir une section (block) : `{% block title %}Titre de la page{% endblock %}`
- Construire un lien : `{{ path('article', { 'slug': item.slug }) }}`
- Inclure un autre template (composants) : `{% include 'article/index.html.twig' %}`

Et plein d'autres. Rendez-vous sur la [documentation officielle](https://twig.symfony.com) pour en savoir plus.

!IMPORTANT! : Pour gérer le CSS et le JS, cela ce passe dans le dossier `assets/` de votre projet. N'hésitez pas à l'explorer pour découvrir sa structure.

Dans le fichier `base.html.twig`, on y trouve le chargement des fichiers CSS et JS au travers de importmap (voir MDN) : 

```twig
{% block javascripts %}
    {% block importmap %}{{ importmap('app') }}{% endblock %}
{% endblock %}
```

Vous être libre d'ajouter des librairies ou polices d'écriture comme à l'accoutumée en fonction des besoins de votre projet.


## Live Component