<?php

namespace App\Services;

use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

use Exception;
use FileException;

class Uploader {

  // ---------------------------------------------------------------------------------------------
  // Cette notation dans le constructeur impose PHP8
  // ---------------------------------------------------------------------------------------------
  public function __construct(private SluggerInterface $slugger) {} 
  // ---------------------------------------------------------------------------------------------
  public function uploadFile(UploadedFile $file, string $directoryFolder) {

    $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
    // this is needed to safely include the file name as part of the URL
    $safeFilename = $this->slugger->slug($originalFilename);
    $newFilename = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();
    // Move the file to the directory where brochures are stored
    try {
        $file->move( $directoryFolder, $newFilename );
        chmod($directoryFolder.'/'.$newFilename, 0644);
        return $newFilename;
    } catch (Exception $e) {
        // ... handle exception if something happens during file upload
        return '';
    }
  }
  // ---------------------------------------------------------------------------------------------
  public function deletePreviousFile(string $directoryFolder, 
                          string $previousfile) {
    try {
      if(file_exists($directoryFolder.'/'.$previousfile)){
          unlink($directoryFolder.'/'.$previousfile);
      }
    }
    catch(Exception $e) {
//        $this->logger->error("Cannot remove previous file $directoryFolder.'/'.$previousfile");
    }
  }
  // ---------------------------------------------------------------------------------------------
  public function deleteFile(string $filepath) {
    try {
      if(file_exists($filepath)){
          unlink($filepath);
      }
    }
    catch(Exception $e) {
//        $this->logger->error("Cannot remove previous file $directoryFolder.'/'.$previousfile");
    }
  }

  /*
          UPLOAD_ERR_OK  : 0. Aucune erreur, le téléchargement est correct.
          UPLOAD_ERR_INI_SIZE : 1. La taille du fichier téléchargé excède la valeur de upload_max_filesize, configurée dans le php.ini.
          UPLOAD_ERR_FORM_SIZE : 2. La taille du fichier téléchargé excède la valeur de MAX_FILE_SIZE, qui a été spécifiée dans le formulaire HTML.
          UPLOAD_ERR_PARTIAL : 3. Le fichier n'a été que partiellement téléchargé.
          UPLOAD_ERR_NO_FILE : 4. Aucun fichier n'a été téléchargé.
          UPLOAD_ERR_NO_TMP_DIR : 6. Un dossier temporaire est manquant.
          UPLOAD_ERR_CANT_WRITE : 7. Échec de l'écriture du fichier sur le disque.
          UPLOAD_ERR_EXTENSION : 8. Une extension PHP a arrêté l'envoi de fichier. PHP ne propose aucun moyen de déterminer quelle extension est en cause. L'examen du phpinfo() peut aider.            
  */

  public function getErrorMessage(UploadedFile $file) {
    $message = 'An error  occurred during file upload';
      switch ($file->getError()) {
          case UPLOAD_ERR_INI_SIZE: $message = 'File size exceeds upload_max_filesize in php.ini'; break;
          case UPLOAD_ERR_FORM_SIZE: $message = 'File size exceeds max size defined in the upload form'; break;
          case UPLOAD_ERR_PARTIAL: $message = 'Partial upload of the File'; break;
          case UPLOAD_ERR_CANT_WRITE: $message = 'unable to write File on disk'; break;
      }
      return $message;
  }

}