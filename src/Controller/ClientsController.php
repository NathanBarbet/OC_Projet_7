<?php

namespace App\Controller;

use App\Entity\Users;
use App\Entity\Clients;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Twig\Environment;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ClientsRepository;
use JMS\Serializer\SerializerInterface;

class ClientsController extends AbstractController
{

  private $twig;

  public function __construct(ClientsRepository $repository, EntityManagerInterface $em, Environment $twig)
  {
    $this->repository = $repository;
    $this->em = $em;
    $this->twig = $twig;
  }


  public function ShowClients($usersid, SerializerInterface $serialize, Request $request): Response
  {

      $repository = $this->getDoctrine()->getRepository(Clients::class);
      $clients = $repository->findAllClients($usersid);

      $data = $serialize->serialize($clients, 'json');

      $response = new Response($data);
      $response->headers->set('Content-Type', 'application/json');

      return $response;

  }

  public function ShowSingleClients($usersid, $clientsid, SerializerInterface $serialize, Request $request): Response
  {

      $repository = $this->getDoctrine()->getRepository(Clients::class);
      $clients = $repository->findSingleClients($usersid, $clientsid);

      $data = $serialize->serialize($clients, 'json');

      $response = new Response($data);
      $response->headers->set('Content-Type', 'application/json');

      return $response;

  }

  public function AddClient($usersid, Request $request): Response
  {
    return new Response($this->twig->render('form.html.twig', [
        'usersid' => $usersid
      ]));
  }

  public function AddClientValide($usersid, Request $request): Response
    {
        $client = new Clients();

        $name = htmlspecialchars(filter_input(INPUT_POST, 'name'));
        $client->setName($name);

        $firstname = htmlspecialchars(filter_input(INPUT_POST, 'firstname'));
        $client->setFirstname($firstname);

        $email = htmlspecialchars(filter_input(INPUT_POST, 'email'));
        $client->setEmail($email);

        $number = htmlspecialchars(filter_input(INPUT_POST, 'number'));
        $client->setNumber($number);

        $street = htmlspecialchars(filter_input(INPUT_POST, 'street'));
        $client->setStreet($street);

        $postalCode = htmlspecialchars(filter_input(INPUT_POST, 'postalCode'));
        $client->setPostalCode($postalCode);

        $city = htmlspecialchars(filter_input(INPUT_POST, 'city'));
        $client->setCity($city);

        $tel = htmlspecialchars(filter_input(INPUT_POST, 'tel'));
        $client->setTel($tel);

        $repository = $this->getDoctrine()->getRepository(Users::class);
        $user = $repository->findOneBy(['id' => $usersid]);
        $client->setUser($user);

        $this->em->persist($client);
        $this->em->flush();

        return $this->redirectToRoute('clients', array(
          'usersid' => $usersid
        ));
      }

      public function DeleteClient($usersid, $clientsid, Request $request): Response
      {

          $repository = $this->getDoctrine()->getRepository(Clients::class);
          $client = $repository->findOneBy(['id' => $clientsid, 'user' => $usersid]);

          $this->em->remove($client);
          $this->em->flush();

          return $this->redirectToRoute('clients', array(
            'usersid' => $usersid
          ));

      }

}
