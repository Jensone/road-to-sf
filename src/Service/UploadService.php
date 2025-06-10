<?php

namespace App\Service;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Class UploadService
 * namespace App\Service
 * Permet de gérer les téléversements dans l'application
 */
class UploadService
{
    private array $typeArr = []; // initialisation du tableau

    public function __construct(private ParameterBagInterface $params)
    {
        // Tableau de ref pour les dossiers en focntion des types de fichiers
        $this->typeArr = [
            'image' => $this->params->get('upload_folder') . '/images',
            'document' => $this->params->get('upload_folder') . '/docs',
            'other' => $this->params->get('upload_folder')
        ];
    }

    public function upload(UploadedFile $file, string $type): string
    {
        try {
            $filename = uniqid($type . '-') . '.' . $file->guessExtension(); // Nom généré
            $file->move($this->typeArr[$type], $filename); // Déplacement du fichier
        } catch (\Exception $err) { return $err->getMessage(); }

        return $filename; // Retourne le nom du fichier
    }

    public function delete(string $filename, string $type)
    {
        $file = $this->typeArr[$type] . '/' . $filename;

        try {
            if (file_exists($file)) { unlink($file); }
        } catch (\Exception $err) { return $err->getMessage(); }
    }
}