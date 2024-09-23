<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class FileService
{

    public function __construct(
        private SluggerInterface $slugger, 
        private ParameterBagInterface $parameters
        ){}

    public function safeName(UploadedFile $uploadedFile){
        $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
        $safeOriginalName = $this->slugger->slug($originalFilename);
        return $safeOriginalName.'-'.uniqid().'.'.$uploadedFile->guessExtension();  
    }
    /**
     * Takes a file, saves it in the corresponding folder (defined in config/services.yaml) and returns the filepath.
     * @param UploadedFile $uploadedFile
     * @return string local path to the file
     */
    public function saveProfilePic(UploadedFile $uploadedFile) : string{
        return $uploadedFile->move($this->parameters->get('profilePic_directory'), $this->safeName($uploadedFile))->getPathname()
        ;
    }
}