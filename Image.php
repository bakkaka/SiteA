<?php

// src/Leimen/SiteBundle/Entity/Image



namespace App\Entity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\HttpFoundation\File\File;

use Symfony\Component\HttpFoundation\File\UploadedFile;


/**
* @ORM\Table(name="leimen_image")
* @ORM\Entity
 * @ORM\HasLifecycleCallbacks
*/

class Image

{

    /**
* @ORM\Column(name="id", type="integer")
* @ORM\Id
* @ORM\GeneratedValue(strategy="AUTO")
*/
  private $id;

  /**
* @ORM\Column(name="url", type="string", length=255)
*/
  private $url;

  /**
* @ORM\Column(name="alt", type="string", length=255)
*/
  private $alt;



  

  private $file;


  // On ajoute cet attribut pour y stocker le nom du fichier temporairement

  private $tempFilename;


  // On modifie le setter de File, pour prendre en compte l'upload d'un fichier lorsqu'il en existe déjà un autre

  public function setFile(UploadedFile $file = null)

  {

    $this->file = $file;


    // On vérifie si on avait déjà un fichier pour cette entité

    if (null !== $this->url) {

      // On sauvegarde l'extension du fichier pour le supprimer plus tard

      $this->tempFilename = $this->url;


      // On réinitialise les valeurs des attributs url et alt

      $this->url = null;

      $this->alt = null;

    }

  }


  /**

   * @ORM\PrePersist()

   * @ORM\PreUpdate()

   */

  public function preUpload()

  {

    // Si jamais il n'y a pas de fichier (champ facultatif), on ne fait rien

    if (null === $this->file) {

      return;

    }


    // Le nom du fichier est son id, on doit juste stocker également son extension

    // Pour faire propre, on devrait renommer cet attribut en « extension », plutôt que « url »

    $this->url = $this->md5(uniqid()).$file->guessExtension();


    // Et on génère l'attribut alt de la balise <img>, à la valeur du nom du fichier sur le PC de l'internaute

    $this->alt = $this->file->getClientOriginalName();

  }


  /**

   * @ORM\PostPersist()

   * @ORM\PostUpdate()

   */

  public function upload()

  {

    // Si jamais il n'y a pas de fichier (champ facultatif), on ne fait rien

    if (null === $this->file) {

      return;

    }


    // Si on avait un ancien fichier, on le supprime

    if (null !== $this->tempFilename) {

      $oldFile = $this->getUploadRootDir().'/'.$this->id.'.'.$this->tempFilename;

      if (file_exists($oldFile)) {

        unlink($oldFile);

      }

    }


    // On déplace le fichier envoyé dans le répertoire de notre choix

    $this->file->move(

      $this->getUploadRootDir(), // Le répertoire de destination

      $this->id.'.'.$this->url   // Le nom du fichier à créer, ici « id.extension »

    );

  }


  /**

   * @ORM\PreRemove()

   */

  public function preRemoveUpload()

  {

    // On sauvegarde temporairement le nom du fichier, car il dépend de l'id

    $this->tempFilename = $this->getUploadRootDir().'/'.$this->id.'.'.$this->url;

  }


  /**

   * @ORM\PostRemove()

   */

  public function removeUpload()

  {

    // En PostRemove, on n'a pas accès à l'id, on utilise notre nom sauvegardé

    if (file_exists($this->tempFilename)) {

      // On supprime le fichier

      unlink($this->tempFilename);

    }

  }


  public function getUploadDir()

  {

    // On retourne le chemin relatif vers l'image pour un navigateur

    return 'uploads/img';

  }


  protected function getUploadRootDir()

  {

    // On retourne le chemin relatif vers l'image pour notre code PHP

     return __DIR__.'/../../public/'.$this->getUploadDir();
  }


  
  public function getWebPath()
  {
    return $this->getUploadDir().'/'.$this->getId().'.'.$this->getUrl();
  }


  /**
* @return integer
*/
  public function getId()
  {
    return $this->id;
  }

  /**
* @param string $url
* @return Image
*/
  public function setUrl($url)
  {
    $this->url = $url;
    return $this;
  }

  /**
* @return string
*/
  public function getUrl()
  {
    return $this->url;
  }

  /**
* @param string $alt
* @return Image
*/
  public function setAlt($alt)
  {
    $this->alt = $alt;
    return $this;
  }

  /**
* @return string
*/
  public function getAlt()
  {
    return $this->alt;
  }

 

  public function getFile()
  {
    return $this->file;
    }
public function __toString()
    {
      return $this->getAlt();
    }
}