# Premier pas avec Symfony

## Présentation

Créons un blog pour apprendre à utiliser Symfony 7.

## Liste priorisée de user stories

| Priorité | Rôle                 | Action                                                                | Bénéfice                                                           |
| :------: | -------------------- | --------------------------------------------------------------------- | ------------------------------------------------------------------ |
|     1    | Visiteur             | Consulter la liste des articles                                       | Découvrir rapidement le contenu disponible                         |
|     2    | Visiteur             | Lire un article détaillé                                              | Approfondir un sujet d’intérêt                                     |
|     3    | Utilisateur          | S’inscrire / se connecter                                             | Participer (commentaires) et accéder aux fonctionnalités protégées |
|     4    | Utilisateur connecté | Poster un commentaire                                                 | Partager son avis et échanger                                      |
|     5    | Utilisateur connecté | Éditer ou supprimer son propre commentaire                            | Gérer et corriger son contenu                                      |
|     6    | Administrateur       | Créer / supprimer un article via EasyAdmin                   | Gérer le contenu du blog de manière structurée                     |
|     7    | Administrateur       | Gérer (activer/désactiver) et modérer les commentaires                | Maintenir un espace de discussion respectueux des CGU              |
|     8    | Administrateur       | Gérer les utilisateurs (rôles, blocage)                               | Contrôler l’accès et la sécurité du site                           |
|     9    | Administrateur       | Configurer les blocs dynamiques du front (header, footer, sidebar)    | Personnaliser la mise en page sans recoder                         |
|    10    | Administrateur       | Consulter des statistiques basiques (nombre d’articles, commentaires) | Suivre l’activité du site                                          |


## 📘 Models (Database/Class Diagram)

```mermaid
classDiagram
  class Article {
    -int id
    -string title NN
    -string slug NN
    -string image NN
    -string keywords 
    -string description
    -text content
    -bool is_published NN
    -bool is_archived NN
    -DateTime created_at NN
    -DateTime updated_at NN
    -User author NN
    -Comment comments
  }

  class SitemapLink {
    -int id
    -string name NN
    -string description NN
    -string url NN
    -DateTime created_at NN
    -DateTime updated_at NN
    -bool is_active NN
  }

  class Comment {
    -int id
    -text content NN
    -DateTime created_at NN
    -bool is_moderated NN
    -bool is_published NN
    -User author NN
    -Article article
  }

  class User {
    -int id
    -string email NN
    -string password NN
    -string roles
    -DateTime created_at NN
    -DateTime updated_at NN
    -int warningCount NN
    -bool is_banned NN
    -bool is_active NN
  }

  class Block {
    -int id
    -string name NN
    -string content 
    -DateTime created_at NN
    -DateTime updated_at NN
  }

  Article "1" -- "0..*" Comment : contains
  User "1" -- "0..*" Article : (admin) writes
  User "1" -- "0..*" Comment : (logged-in) writes
  Block "*" -- "*" Article : displayed_on
```

## 🧭 Controllers

```mermaid
classDiagram
  class ArticleController {
    -index()
    -view()
    -create()
    -edit()
    -delete()
    -publish()
    -archive()
  }

  class CommentController {
    -create()
    -delete()
    -report()
  }

  class SecurityController {
    -register()
    -login()
    -logout()
  }

  class UserController {
    -profile()
    -newPassword()
    -myData()
    -delete()
  }
```

## 🔧 Services

```mermaid
classDiagram
  class EmailService {
    -sendInfoMail(string subject, string message)
    -sendLoginNotification(User user, array info)
    -sendWarning(User user, array info)
    -sendRgpdExport(User user, array info)
    -sendAccountDeletion(array info)
  }

  class ModerationService {
    -string API_KEY
    -moderate(Comment comment)
    -approve(Comment comment)
    -reject(Comment comment)
  }

  class ImageService {
    -upload(UploadedFile image)
    -compress(UploadedFile image)
    -delete(string slug)
  }

  class OptimizationService {
    -applySeo(Article article)
    -generateSitemap()
    -generateRobots()
  }
```

## 🎧 Event Listeners

```mermaid
classDiagram
  class ArticleListener {
    -onArticleCreated(Article article)
    -onArticleUpdated(Article article)
    -onArticleDeleted(Article article)
  }

  class UserListener {
    -onUserLogin()
    -onUserValidated()
  }
```

## 📑 Sequence Diagrams

### Create an article

```mermaid
sequenceDiagram
    autonumber
    User->>ArticleController: POST /articles/new
    ArticleController->>ImageService: upload(image)
    ImageService-->>ArticleController: imageUrl
    ArticleController->>OptimizationService: applySeo(article)
    ArticleController->>ArticleController: save(article)
    ArticleController-->>User: Redirect to article created
```

### Edit an article

```mermaid
sequenceDiagram
    autonumber
    User->>ArticleController: POST /article/{slug}/edit
    ArticleController->>ArticleController: findOneBySlug(slug)
    ArticleController->>ImageService: upload(newImage)
    ImageService-->>ArticleController: imageUrl
    ArticleController->>ArticleController: persist(article)
    ArticleController-->>User: Redirect to article edited
```

### View an article

```mermaid
sequenceDiagram
    autonumber
    User->>ArticleController: GET /article/{slug}
    ArticleController->>ArticleController: fetchArticle(slug)
    ArticleController-->>User: Display article
```

### Delete an article

```mermaid
sequenceDiagram
    autonumber
    User->>ArticleController: POST /article/d/{slug}
    ArticleController->>ArticleController: fetchArticle(slug)
    ArticleController->>ImageService: delete(image)
    ArticleController->>ArticleController: delete(article)
    ArticleController-->>User: Redirect to article list
```

### Post a comment

```mermaid
sequenceDiagram
    autonumber
    User->>CommentController: POST /articles/{slug}/comments
    CommentController->>ModerationService: moderate(comment)
    ModerationService-->>CommentController: moderationResult
    CommentController->>CommentController: save(comment)
    CommentController-->>User: Redirect to article
```

### Delete a comment

```mermaid
sequenceDiagram
    autonumber
    User->>CommentController: POST /comment/{id}
    CommentController->>CommentController: delete(comment)
    CommentController-->>User: Redirect to article
```


### Routes 

| Route                  | Accès                | Fonctionnalités principales                                                                                                  |
|------------------------|----------------------|-----------------------------------------------------------------------------------------------------------------------------|
| /articles              | Tout le monde        | Voir tous les articles (pagination, filtrage, cliquables)                                                                   |
| /article/slug          | Tout le monde        | Lire l'article, lire/écrire des commentaires, fiche auteur, liker, favoris, partager, signaler l'article                    |
| /authors               | Tout le monde        | Liste des auteurs avec informations                                                                                         |
| /author/name           | Tout le monde        | Voir tous ses articles, follow l'auteur, voir ses infos                                                                    |
| /search                | Tout le monde        | Rechercher et consulter les résultats                                                                                       |
| /dashboard             | Admin                | Toutes les fonctionnalités d'administration                                                                                |
| /inscription           | Tout le monde        | S'inscrire                                                                                                                  |
| /connexion             | Tout le monde        | Se connecter                                                                                                                |
| /                      | Tout le monde        | Derniers articles, articles les plus likés, incitation à l'inscription, navigation, catégories d'articles                  |
| /profil/name           | Tout le monde (public)| Voir l'activité sur l'app                                                                                                   |
| /settings              | Connecté             | Gérer ses paramètres et informations personnelles                                                                           |
| /support               | Connecté             | Ouvrir des tickets de support                                                                                               |
| /notification          | Connecté             | Voir notifications : likes, auteurs suivis, mentions, suivi de ses articles, follow si auteur                              |
