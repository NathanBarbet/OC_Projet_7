<?php

namespace App\Controller;

use App\Entity\Clients;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ClientsRepository;
use JMS\Serializer\SerializerInterface;

class ClientsController extends AbstractController
{

  public function __construct(ClientsRepository $repository, EntityManagerInterface $em)
  {
    $this->repository = $repository;
    $this->em = $em;
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
}
