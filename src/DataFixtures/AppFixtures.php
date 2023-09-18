<?php

namespace App\DataFixtures;

use App\Entity\Article;
use App\Entity\Utilisateur;
use App\Entity\Categorie;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');
        for ($i = 0; $i < 20; $i++) {
            $utilisateur = new utilisateur();
            $utilisateur->setPseudo($faker->name());
            $utilisateur->setMdp($faker->password());
            $utilisateur->setMail($faker->email());
            $manager->persist($utilisateur);
        }

        for ($i = 0; $i < 20; $i++) {
            $categorie = new categorie();
            $categorie->setLibelle('categorie '.$i);
            $manager->persist($categorie);
        }

        for ($i = 0; $i < 20; $i++) {
            $article = new article();
            $article->setTitre('article '.$i);
            $article->setContenu(mt_rand(10, 100));
            $article->setDate($faker->datetime());
            $article->setCategorie($categorie);
            $article->setAuteur($utilisateur);
            $manager->persist($article);
        }

        $manager->flush();
    }
}
