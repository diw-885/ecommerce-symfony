<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Color;
use App\Entity\Product;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');

        // Un administrateur
        $user = new User();
        $user->setEmail('matthieu@boxydev.com');
        $user->setPassword($this->encoder->encodePassword($user, 'password'));
        $user->setRoles(['ROLE_ADMIN']);
        $manager->persist($user);

        // Un utilisateur
        $user = new User();
        $user->setEmail('fiorella@boxydev.com');
        $user->setPassword($this->encoder->encodePassword($user, 'password'));
        $manager->persist($user);

        $user = new User();
        $user->setEmail('marina@boxydev.com');
        $user->setPassword($this->encoder->encodePassword($user, 'password'));
        $manager->persist($user);

        for ($i = 1; $i <= 5; $i++) {
            $color = new Color();
            $color->setName($faker->colorName());
            $color->setValue(['r' => 100, 'v' => 200, 'b' => 255]);
            $this->addReference('color-'.$i, $color);
            $manager->persist($color);
        }

        for ($i = 1; $i <= 10; $i++) {
            $category = new Category();
            $category->setName($faker->sentence(3));
            $category->setSlug($faker->slug());
            // Je mets de côté chaque objet $category dans une sorte de tableau que je pourrais utiliser plus tard
            $this->addReference('category-'.$i, $category);
            $manager->persist($category);
        }

        for ($i = 0; $i < 360; $i++) {
            $product = new Product();
            $product->setName($faker->sentence(3));
            $product->setSlug($faker->slug());
            $product->setDescription($faker->text());
            $product->setPrice($faker->numberBetween(100, 2000));
            $product->setCreatedAt(\DateTimeImmutable::createFromMutable($faker->dateTimeBetween('-30 days')));
            $product->setLiked($faker->boolean(25));
            $product->setImage(null);
            $product->setPromotion($faker->numberBetween(0, 70));
            // Je récupère une référence dans le tableau qui contient d'autres objets
            $product->setCategory($this->getReference('category-'.rand(1, 10)));
            $product->addColor($this->getReference('color-'.rand(1, 5)));
            $product->addColor($this->getReference('color-'.rand(1, 5)));
            $product->addColor($this->getReference('color-'.rand(1, 5)));
            $product->setUser($user);
            $manager->persist($product);
        }

        $manager->flush();
    }
}
