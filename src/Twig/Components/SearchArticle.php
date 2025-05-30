<?php

namespace App\Twig\Components;

use App\Repository\ArticleRepository;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;

#[AsLiveComponent('SearchArticle', template: 'components/SearchArticle.html.twig')]
final class SearchArticle
{
    use DefaultActionTrait;

    /**
     * LiveProp est une classe qu'on utilise en annotation pour définir les propriétés "Live"
     * que l'on va utiliser dans le composant. C'est comme le passage de props en JavaScript.
     * 
     * "writable : true" signifie que la propriété est modifiable depuis le composant.
     * "url : true" signifie que la propriété sera disponible dans l'URL.
     */
    #[LiveProp(writable: true, url: true)]
    public ?string $query = null;

    public function __construct(private ArticleRepository $ar) {}

    public function getArticles(): array
    {   
        if ($this->query) { // S'il y a une requête, on cherche les articles correspondants
            return $this->ar->searchByTitle($this->query);
        }

        // On retourne les 10 derniers articles par défaut
        return $this->ar->findBy([], ['created_at' => 'DESC'], 10);
    }
}
