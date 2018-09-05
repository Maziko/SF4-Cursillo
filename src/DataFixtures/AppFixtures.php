<?php

namespace App\DataFixtures;

use App\Entity\MicroPost;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private const USERS = [
        [
            'username' => 'john_doe',
            'email' => 'john_doe@doe.com',
            'password' => 'john123',
            'fullName' => 'John Doe',
            'roles' => [User::ROLE_USER]
        ],
        [
            'username' => 'super_admin',
            'email' => 'super_admin@gmail.com',
            'password' => 'admin123',
            'fullName' => 'Micro Admin',
            'roles' => [User::ROLE_ADMIN]
        ],
        [
            'username' => 'Momo',
            'email' => 'momo@gmail.com',
            'password' => 'momo123',
            'fullName' => 'Momo Sanchez',
            'roles' => [User::ROLE_USER]
        ],
        [
            'username' => 'Amador',
            'email' => 'amador@gmail.com',
            'password' => 'amador123',
            'fullName' => 'Amador Sanchez',
            'roles' => [User::ROLE_USER]
        ],
    ];

    private const POST_TEXT = [
      'Hola, como esta uste?',
      'Qué buen día hace eh?',
      'Quiero un helado',
      'Me tengo que comprar un cochaso',
      'Tengo problemas con el movil',
      'Tengo que ir al médico',
      'Que planes tienes para hoy?',
      'Viste el partido de ayer?',
      'Que tal ha ido el día?'
    ];

    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $this->loadUsers($manager);
        $this->loadMicroPosts($manager);
    }

    private function loadMicroPosts(ObjectManager $manager)
    {
        for ($i = 0; $i < 30; $i++) {
            $microPost = new MicroPost();
            $microPost->setText(
                self::POST_TEXT[rand(0, count(self::POST_TEXT) - 1)]
            );
            $date = new \DateTime();
            $date->modify('-' .rand(0, 10). ' day');
            $microPost->setTime($date);
            $microPost->setUser($this->getReference(
                self::USERS[rand(0, count(self::USERS) - 1)]['username']
            ));
            $manager->persist($microPost);
        }

        $manager->flush();
    }

    private function loadUsers(ObjectManager $manager)
    {
        foreach(self::USERS as $userData){
            $user = new User();
            $user->setUsername($userData['username']);
            $user->setFullName($userData['fullName']);
            $user->setEmail($userData['email']);
            $user->setPassword(
                $this->passwordEncoder->encodePassword(
                    $user,
                    $userData['password']
                )
            );
            $user->setRoles($userData['roles']);
            $this->addReference($userData['username'], $user);

            $manager->persist($user);
        }

        $manager->flush();
    }
}