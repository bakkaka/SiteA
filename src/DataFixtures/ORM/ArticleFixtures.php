<?php

    namespace App\DataFixtures\ORM;

    use App\Entity\ArticleCategory;
    
    use Doctrine\Bundle\FixturesBundle\Fixture;
    use Doctrine\Common\Persistence\ObjectManager;
    use Doctrine\ORM\EntityManagerInterface;
    use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

   
    class ArticleFixtures extends Fixture
    {
       
        public function load(ObjectManager $manager)
        {
            //Creating default roles
            $categorie = new ArticleCategory();
            $categorie->setName('HightTech');
           $manager->persist($categorie);
		   
		   $categorie = new ArticleCategory();
            $categorie->setName('Blog');
           $manager->persist($categorie);
		   
		   $categorie = new 	ArticleCategory();
            $categorie->setName('Informatique');
           $manager->persist($categorie);
		   
		   $categorie = new ArticleCategory();
            $categorie->setName('Science');
           $manager->persist($categorie);
           

            $manager->flush();
            //$manager->clear();
        }
    }