<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use App\Entity\Article;
use App\Entity\Category;
use Cocur\Slugify\Slugify;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class ArticleFixtures extends Fixture implements DependentFixtureInterface
{
    public function __construct(
        private readonly UserPasswordHasherInterface $hasher,
    ) {}

    public function load(ObjectManager $manager): void
    {
        $slugger = new Slugify();
        $faker = Factory::create('fr_FR');

        // Admin
        $admin = new User();
        $admin
            ->setEmail('admin@admin.fr')
            ->setUsername('admin')
            ->setPassword($this->hasher->hashPassword($admin, 'admin'))
            ->setWarningCount(0)
            ->setRoles(['ROLE_ADMIN'])
        ;

        $manager->persist($admin);
        $manager->flush(); // Admin enregistré en base de données

        $articles = [
            "Quoi de neuf dans Symfony 7 ? Tour d’horizon des nouveautés majeures",
            "Migrer un projet Symfony 6 vers Symfony 7 : guide étape par étape",
            "Symfony 7 et PHP 8.2 : quelles synergies pour vos applications ?",
            "Installer Symfony 7 : méthodes, outils et pièges à éviter",
            "Structurer son projet avec Symfony 7 : architecture recommandée",
            "Les meilleures pratiques pour développer avec Symfony 7",
            "Sécuriser son application Symfony 7 : nouveautés et conseils",
            "Optimiser les performances de Symfony 7 : astuces avancées",
            "Découverte du composant HttpClient amélioré dans Symfony 7",
            "Utiliser Doctrine ORM efficacement avec Symfony 7",
            "Symfony 7 : gestion des utilisateurs et authentification moderne",
            "Introduction à Symfony Flex avec Symfony 7",
            "Créer une API RESTful avec Symfony 7",
            "Tests automatisés sous Symfony 7 : PHPUnit, Panther et autres outils",
            "Personnaliser le système de routing dans Symfony 7",
            "Intégrer Twig et personnaliser vos vues dans Symfony 7",
            "Gérer les fichiers et uploads dans Symfony 7",
            "Pagination et affichage de listes d’articles avec Symfony 7",
            "Internationalisation et traduction dans Symfony 7",
            "Mettre en place un système de notifications dans Symfony 7",
            "Les bundles incontournables pour Symfony 7",
            "Utiliser Messenger pour la gestion des files d’attente dans Symfony 7",
            "Optimiser le cache HTTP et OpCache avec Symfony 7",
            "Déployer une application Symfony 7 sur un hébergement cloud",
            "Monitoring et logs dans Symfony 7 : outils et bonnes pratiques",
            "Gérer les erreurs et exceptions dans Symfony 7",
            "Symfony 7 et l’architecture hexagonale : pourquoi et comment ?",
            "Utiliser les attributs PHP pour simplifier la configuration dans Symfony 7",
            "Intégrer un système de commentaires dans un blog Symfony 7",
            "SEO et Symfony 7 : optimiser le référencement de vos pages",
            "Symfony 7 pour les grandes entreprises : retours d’expérience",
            "Les nouveautés du composant Form dans Symfony 7",
            "Utiliser Webpack Encore avec Symfony 7 pour gérer les assets",
            "Protéger son application contre les failles XSS et CSRF dans Symfony 7",
            "Symfony 7 et la gestion des migrations de base de données",
            "Créer un back-office sur mesure avec Symfony 7",
            "Intégrer des API tierces avec Symfony 7 (Stripe, Sendinblue, etc.)",
            "Symfony 7 et Docker : environnement de développement moderne",
            "Les nouveautés du composant Security dans Symfony 7",
            "Gérer les rôles et permissions dans Symfony 7",
            "Utiliser les événements et listeners dans Symfony 7",
            "Symfony 7 et GraphQL : mise en œuvre et avantages",
            "Développer une application mobile backend avec Symfony 7",
            "Symfony 7 et les microservices : architecture et communication",
            "Générer des PDF et des rapports avec Symfony 7",
            "Intégrer un système de paiement dans Symfony 7",
            "Les meilleures extensions et plugins pour Symfony 7",
            "Automatiser les tâches avec Symfony 7 et Scheduler",
            "Gérer les emails transactionnels avec Symfony 7",
            "Symfony 7 et la gestion des sessions utilisateur",
            "Mettre en place un système de recherche avec Elasticsearch et Symfony 7",
            "Symfony 7 et la gestion des fichiers volumineux",
            "Utiliser RabbitMQ ou Redis avec Symfony 7",
            "Symfony 7 et l’accessibilité web : bonnes pratiques",
            "Gérer les dépendances avec Composer dans Symfony 7",
            "Symfony 7 et l’intégration continue (CI/CD)",
            "Les nouveautés du composant Validator dans Symfony 7",
            "Symfony 7 pour les applications e-commerce",
            "Créer un site multilingue avec Symfony 7",
            "Symfony 7 et la gestion des webhooks",
            "Les nouveautés du composant Console dans Symfony 7",
            "Symfony 7 et la gestion des fichiers statiques",
            "Gérer les tâches planifiées (cron) avec Symfony 7",
            "Symfony 7 et SSO (Single Sign-On)",
            "Intégrer React ou Vue.js avec Symfony 7",
            "Symfony 7 et la gestion des notifications push",
            "Mettre en place un système de votes/likes dans Symfony 7",
            "Symfony 7 et la gestion des abonnements",
            "Utiliser les tests fonctionnels dans Symfony 7",
            "Symfony 7 et la gestion des logs personnalisés",
            "Gérer les environnements de développement, staging et production dans Symfony 7",
            "Symfony 7 et la gestion des images (redimensionnement, optimisation)",
            "Les nouveautés du composant Serializer dans Symfony 7",
            "Symfony 7 et la gestion des tokens JWT",
            "Intégrer un chatbot avec Symfony 7",
            "Symfony 7 et la gestion des fichiers temporaires",
            "Utiliser les services externes (API météo, géolocalisation) dans Symfony 7",
            "Symfony 7 et la gestion des notifications SMS",
            "Symfony 7 et la génération de QR codes",
            "Les nouveautés du composant Translation dans Symfony 7",
            "Symfony 7 et la gestion des fichiers CSV/Excel",
            "Symfony 7 et la gestion des imports/exports de données",
            "Mettre en place un système de parrainage avec Symfony 7",
            "Symfony 7 et la gestion des campagnes emailing",
            "Symfony 7 et l’intégration de Google Analytics",
            "Symfony 7 et la gestion des cookies et RGPD",
            "Symfony 7 et la gestion des web sockets",
            "Symfony 7 et la génération de sitemaps dynamiques",
            "Symfony 7 et la gestion des newsletters",
            "Symfony 7 et la gestion des notifications en temps réel",
            "Symfony 7 et la gestion des fichiers ZIP",
            "Symfony 7 et la gestion des workflows métiers",
            "Symfony 7 et la gestion des historiques d’actions utilisateur",
            "Symfony 7 et la gestion des dashboards personnalisés",
            "Symfony 7 et la gestion des fichiers audio/vidéo",
            "Symfony 7 et la gestion des tags et catégories",
            "Symfony 7 et la gestion des favoris/bookmarks",
            "Symfony 7 et la gestion des invitations utilisateurs",
            "Symfony 7 et la gestion des alertes système",
            "Symfony 7 : bilan et perspectives pour les développeurs PHP"
        ];

        $categories = [
            'Front-end',
            'Back-end',
            'DevOps',
            'Cloud'
        ];

        $catArray = [];
        foreach ($categories as $category) {
            $cat = new Category();
            $cat->setName($category);

            $manager->persist($cat);
            array_push($catArray, $cat);
        }

        $manager->flush();

        // Articles
        $i = 0;
        foreach ($articles as $item) {
            $date = $faker->dateTimeBetween('-6 months', 'now');
            $immutableDate = (new \DateTimeImmutable())->createFromMutable($date);
            $article = new Article();
            $article
                ->setTitle($item)
                ->setSlug($slugger->slugify($item))
                ->setKeywords($faker->words(5, true))
                ->setDescription($faker->sentence())
                ->setContent($faker->text())
                ->setIsPublished($faker->boolean(70))
                ->setIsArchived($faker->boolean(10))
                ->setAuthor($admin)
                ->setCategory($faker->randomElement($catArray))
                ->setCreatedAt($immutableDate)
            ;

            $manager->persist($article);
            $this->addReference('ARTICLE_' . $i, $article);

            if ($i % 100 === 0) {
                $manager->flush(); // Article enregistré en base de données tous les 10 articles
            }

            $i++;
        }


        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [UserFixtures::class];
    }
}
