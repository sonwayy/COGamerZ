<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use Faker\Generator;
use App\Entity\Contact;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AppFixtures extends Fixture
{
    /**
     * @var Generator
     */
    private Generator $faker;

    public function __construct()
    {
        $this->faker = Factory::create('fr_FR');
    }

    public function load(ObjectManager $manager): void
    {

        for ($i = 0; $i < 10; $i++) {
            $user = new User();
            $user->setNickname($this->faker->userName)
                ->setEmail($this->faker->email)
                ->setRoles(['ROLE_USER'])
                ->setPassword($this->faker->password)
                ->setBirthday($this->faker->dateTimeBetween('-30 years', '-18 years'))
                ->setIsVerified(false);
            $manager->persist($user);
        }


        //Contact
        for($i = 0; $i < 10; $i++) {
            $contact = new Contact();
            $contact->setFullName($this->faker->name)
                ->setEmail($this->faker->email)
                ->setSubject('Demande n°' . ($i + 1))
                ->setMessage($this->faker->text);
            $manager->persist($contact);
        }
    
        $manager->flush();

    }
}
