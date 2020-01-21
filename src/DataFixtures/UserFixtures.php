<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
	private $encoder;

	public function __construct(UserPasswordEncoderInterface $encoder)
	{
		$this->encoder = $encoder;
	}

    public function load(ObjectManager $manager)
    {
		// Gestion des utilisateurs
		$user = new User();

		$hash = $this->encoder->encodePassword($user, 'admin');

		$user->setLastname('admin')
			->setUsername('admin@gmail.com')
			->setEmail('admin@gmail.com')
			->setRoles(['ROLE_ADMIN'])
			->setPassword($hash);
		$manager->persist($user);

		$manager->flush();
    }
}
