<?php

namespace App\DataFixtures;

use App\Entity\Clients;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ClientsFixtures extends Fixture
{

  private $encoder;

  public function __construct(UserPasswordEncoderInterface $encoder)
  {
      $this->encoder = $encoder;
  }

    public function load(ObjectManager $manager)
    {
        $client = new Clients();
        $client->setName('SmartPhones.com');
        $client->setEmail('test4@test.fr');

        $password = $this->encoder->encodePassword($client, 'test');
        $client->setPassword($password);
        $client->setRoles('["ROLE_USER"]');


        $manager->persist($client);

        $manager->flush();
    }
}
