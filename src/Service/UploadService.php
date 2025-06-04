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

    public function __construct(
        private ParameterBagInterface $params,
    ){}

        public function upload(UploadedFile $file, string $type): string
        {
            $typeArr= [ // Tableau de ref pour les dossiers en focntion des types de fichiers
                'image' => $this->params->get('upload_folder') . '/images', 
                'document' => $this->params->get('upload_folder') . '/docs', 
                'other' => $this->params->get('upload_folder')
            ];

            try {
                $filename = uniqid($type . '-') . '.' . $file->guessExtension(); // Nom généré
                $file->move($typeArr[$type], $filename); // Déplacement du fichier
            } catch (\Exception $err) {
                return $err->getMessage();
            }

            return $filename; // Retourne le nom du fichier
        }



}