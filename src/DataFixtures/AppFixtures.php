<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Post;
use App\Entity\PostLike;
use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{

    /**
     * Encodeur de mot de passe
     *
     * @var UserPasswordHasherInterface
     */
    private $encoder;

    public function __construct(UserPasswordHasherInterface $encoder)
    {
        $this->encoder = $encoder;
    }
    
    public function load(ObjectManager $manager): void
    {

        $faker = Factory::create();
        $users= [];

        $user = new User();
        $user->setEmail('user@symfony.com')
            ->setPassword($this->encoder->hashPassword($user, 'password'))
        ;
        $manager->persist($user);

        $users[]= $user;

        for ($i = 0; $i < 20; $i++) {
            $user= new User;

            $user->setEmail($faker->freeEmail)
                ->setPassword($this->encoder->hashPassword($user, 'password'))
            ;
            $manager->persist($user);

            $users[]= $user;
        }

        for ($i = 0; $i < 20; $i++) {
            $post = new Post();
            $post->setTitle($faker->sentence(6))
                ->setIntroduction($faker->paragraph())
                ->setContent('<p>' . join(',', $faker->paragraphs()) . '</p>')
            ;
            $manager->persist($post);

            for ($j = 0; $j < mt_rand(0, 15); $j++) {
                $like= new PostLike;

                $like->setPost($post)
                // ->setUser($faker->randomElement($users))
                    ->setUser($users[array_rand($users, 1)])
                ;
                $manager->persist($like);
            }
        }

        $manager->flush();
    }
}
