<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * ArticleCategory
 *
 * @ORM\Table(name="articlecategory")
 * @ORM\Entity(repositoryClass="App\Repository\ArticleCategoryRepository")
 */
class ArticleCategory
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;
	
	/**

   * @ORM\ManyToMany(targetEntity="App\Entity\Article", mappedBy="articlecategories")

   */
   private $articles;

   public function __construct()
   {
       $this->articles = new ArrayCollection();
   }

   public function getId(): ?int
   {
       return $this->id;
   }

   public function getName(): ?string
   {
       return $this->name;
   }

   public function setName(string $name): self
   {
       $this->name = $name;

       return $this;
   }

   /**
    * @return Collection|Article[]
    */
   public function getArticles(): Collection
   {
       return $this->articles;
   }

   public function addArticle(Article $article): self
   {
       if (!$this->articles->contains($article)) {
           $this->articles[] = $article;
           $article->addArticlecategory($this);
       }

       return $this;
   }

   public function removeArticle(Article $article): self
   {
       if ($this->articles->contains($article)) {
           $this->articles->removeElement($article);
           $article->removeArticlecategory($this);
       }

       return $this;
   }
   public function __toString()
    {
  return $this->getName();
  }
   
}