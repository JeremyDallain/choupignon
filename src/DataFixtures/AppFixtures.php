<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Item;
use App\Entity\User;
use App\Entity\Comment;
use App\Entity\Category;
use Bezhanov\Faker\Provider\Commerce;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    protected $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');
        $faker->addProvider(new \Bezhanov\Faker\Provider\Commerce($faker));

        $admin = new User();
            $admin->setEmail("admin@gmail.com")
                ->setName("admin")
                ->setRoles(['ROLE_ADMIN'])
                ->setRegisteredAt($faker->dateTimeBetween('-6 month', '-5 month'))
                ->setPassword($this->encoder->encodePassword($admin, "password"));         
            $manager->persist($admin);

        $users = [];

        for ($u=1; $u <= 10; $u++) { 
            
            $user = new User();
            $user->setEmail("user$u@gmail.com")
                ->setName("user$u")
                ->setRoles(['ROLE_USER'])
                ->setRegisteredAt($faker->dateTimeBetween('-4 month', '-3 month'))
                ->setPassword($this->encoder->encodePassword($user, "password"));

            $users[] = $user;            
            $manager->persist($user);
        }

        $categories = [];

        $cate1 = new Category();
        $cate1->setLabel("Couture")
            ->setColor("#a84832");
        $categories[] = $cate1;            
        $manager->persist($cate1);

        $cate2 = new Category();
        $cate2->setLabel("Bijou")
            ->setColor("#3b56ad");
        $categories[] = $cate2;            
        $manager->persist($cate2);


        for ($c=1; $c <= 3; $c++) { 
            
            $creator = new User();
            $creator->setEmail("creator$c@gmail.com")
                ->setName("creator$c")
                ->setRoles(['ROLE_CREATOR'])
                ->setRegisteredAt($faker->dateTimeBetween('-5 month', '-4 month'))
                ->setPassword($this->encoder->encodePassword($creator, "password"));      
            $manager->persist($creator);

            for ($i = 0; $i < mt_rand(5, 10); $i++) { 
                $item = new Item();
                $item->setTitle($faker->department())
                    ->setDescription($faker->paragraphs(5, true))
                    ->setCreatedAt($faker->dateTimeBetween('-4 month', '-3 month'))
                    ->setMainPicture('image_default.jpg')
                    ->setUser($creator)
                    ->setCategory($faker->randomElement($categories));                
                $manager->persist($item);

                for ($co = 0; $co < mt_rand(0, 3); $co++) {
                    $comment = new Comment();
                    $comment->setPostedAt($faker->dateTimeBetween('-3 month', 'now'))
                        ->setContent($faker->sentence(10, true))
                        ->setItem($item)
                        ->setUser($faker->randomElement($users));                
                    $manager->persist($comment);  
                }
            }
        }         
        $manager->flush();
    }
}
