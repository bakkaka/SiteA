<?php

    namespace App\DataFixtures\ORM;

    use App\Entity\City;
    
    use Doctrine\Bundle\FixturesBundle\Fixture;
    use Doctrine\Common\Persistence\ObjectManager;
    use Doctrine\ORM\EntityManagerInterface;
    use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

   
    class CityFixtures extends Fixture
    {
       
        public function load(ObjectManager $manager)
        {
            //Creating default roles
            $categorie = new City();
            $categorie->setName('Rabat');
           $manager->persist($categorie);
		   
		   $categorie = new City();
            $categorie->setName('Tanger');
           $manager->persist($categorie);
		   
		    $categorie = new City();
            $categorie->setName('Casablanca');
           $manager->persist($categorie);
		   
		   $categorie = new City();
            $categorie->setName('Kenitra');
           $manager->persist($categorie);
		   
		    $categorie = new City();
            $categorie->setName('Oujda');
           $manager->persist($categorie);
		   
		    $categorie = new City();
            $categorie->setName('Agadir');
           $manager->persist($categorie);
           
		    $categorie = new City();
            $categorie->setName('Marrakech');
           $manager->persist($categorie);
		   
		    $categorie = new City();
            $categorie->setName('El-jadida');
           $manager->persist($categorie);
		   
		    $categorie = new City();
            $categorie->setName('Es-saouira');
           $manager->persist($categorie);
		   
		    $categorie = new City();
            $categorie->setName('Souk-el-Arbaa');
           $manager->persist($categorie);
           
           
           
           
           
           

            $manager->flush();
            //$manager->clear();
        }
    }