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
        $client->setName('PhoneWall.fr');
        $client->setEmail('test@test.fr');

        $password = $this->encoder->encodePassword($client, 'test');
        $client->setPassword($password);


        $manager->persist($client);

        $manager->flush();
    }
}
