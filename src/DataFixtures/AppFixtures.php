<?php

namespace App\DataFixtures;

use App\Entity\Article;
use App\Entity\UserSecurity;
use App\Entity\Categorie;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');
        for ($i = 0; $i < 11; $i++) {
            $utilisateur = new UserSecurity();
            $utilisateur->setPseudo($faker->name());
            $utilisateur->setPassword($faker->password());
            $utilisateur->setEmail($faker->email());
            $categorie = new categorie();
            $categorie->setLibelle($i);
            $article = new article();
            $article->setTitre('article '.$i);
            $article->setContenu(mt_rand(10, 100));
            $article->setDate($faker->datetime());
            $article->setCategorie($categorie);
            $article->setUserSecurity($utilisateur);
            $manager->persist($utilisateur);
            $manager->persist($article);
            $manager->persist($categorie);
        }

        $manager->flush();
    }
}
