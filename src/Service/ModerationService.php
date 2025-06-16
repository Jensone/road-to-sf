<?php

namespace App\Service;

use Gemini;
use App\Entity\Comment;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class ModerationService
{
    public function __construct(
        private EntityManagerInterface $em,
        private ParameterBagInterface $param
    ) {}

    /**
     * Méthode de modération via l'api d'un modèle de langage
     * @param Comment $comment
     * @return void
     */
    public function checkComment(Comment $comment): void
    {
        if (!$comment) {
            return;
        }

        $prompt = <<<EOT
Tu es un système de modération de commentaires pour une application de publication d’articles similaire à Medium. Ta tâche est d’analyser le commentaire fourni et de déterminer s’il doit être refusé selon les critères suivants :

- Le commentaire contient des insultes, propos haineux, xénophobes, racistes, discriminatoires, sexistes, homophobes ou tout contenu similaire.
- Le commentaire utilise du verlan, des caractères spéciaux ou des modifications d’écriture visant à contourner la modération (ex : "s@l0p3", "n1qu3", "b1d0u", etc.).
- Le commentaire contient des éléments suspects, du spam, des liens douteux ou des tentatives de manipulation.
- Le commentaire n’a aucun rapport avec la thématique ou le titre de l’article concerné.
- Le commentaire ne respecte pas la catégorie de l’article ou s’écarte du sujet.

Analyse le commentaire en prenant en compte le titre et la catégorie de l’article. Si le commentaire doit être refusé selon au moins un des critères ci-dessus, réponds uniquement par "true". Sinon, réponds uniquement par "false". Ne donne aucune autre explication, justification ou texte supplémentaire.

Voici les éléments à analyser :
- Commentaire : {$comment->getContent()}
- Titre de l’article : {$comment->getArticle()->getTitle()}
- Catégorie de l’article : {$comment->getArticle()->getCategory()->getName()}
EOT;

        $client = Gemini::client($this->param->get('GEMINI_KEY'));
        $result = $client->generativeModel(model: 'gemini-2.0-flash')->generateContent($prompt);

        $boolean = explode("\n", $result->text())[0]; // Extraire le boolean
        
        if ($boolean == 'false') {
            $comment->setIsModerated(true)->setIsPublished(true);
            $this->em->persist($comment);
            $this->em->flush();
        }

        return;
    }
}
