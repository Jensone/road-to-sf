<?php

namespace App\Twig\Extension;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

/**
 * Extension Twig pour filtrer l'affichage des dates en fonction de la date actuelle
 */
class TimeExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            new TwigFilter('time_diff', [$this, 'getTimeDiff']),
            new TwigFilter('time_diff_to', [$this, 'getTimeDiffTo']),
        ];
    }

    /**
     * Retourne la différence entre la date et la date actuelle
     * @param \DateTimeInterface $date
     * @return string
     */
    public function getTimeDiff(\DateTimeInterface $date): string
    {
        $now = new \DateTimeImmutable();
        $diff = $date->diff($now);

        if ($diff->y > 0) {
            return $diff->y . ' année' . ($diff->d > 1 ? 's' : '');
        }

        if ($diff->m > 0) {
            return $diff->m . ' mois';
        }

        if ($diff->d > 0) {
            return $diff->d . ' jour' . ($diff->d > 1 ? 's' : '');
        }

        if ($diff->h > 0) {
            return $diff->h . ' heure' . ($diff->h > 1 ? 's' : '');
        }

        return $diff->i . ' minute' . ($diff->i > 1 ? 's' : '');
    }

    /**
     * Retourne la différence entre la date sur laquelle le filtre
     * est appliqué et la date fournie en paramètre
     * @param \DateTimeInterface $from Date de départ
     * @param \DateTimeInterface $to Date d'arrivée
     * @return string
     */
    public function getTimeDiffTo(\DateTimeInterface $from, \DateTimeInterface $to): string
    {
        $diff = $from->diff($to);

        if ($diff->y > 0) {
            return $diff->y . ' année' . ($diff->d > 1 ? 's' : '');
        }

        if ($diff->m > 0) {
            return $diff->m . ' mois';
        }

        if ($diff->d > 0) {
            return $diff->d . ' jour' . ($diff->d > 1 ? 's' : '');
        }

        if ($diff->h > 0) {
            return $diff->h . ' heure' . ($diff->h > 1 ? 's' : '');
        }

        return $diff->i . ' minute' . ($diff->i > 1 ? 's' : '');
    }
}