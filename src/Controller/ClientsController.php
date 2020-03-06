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

    if ($request->isMethod('GET')) {

      $repository = $this->getDoctrine()->getRepository(Clients::class);
      $clients = $repository->findAllClients($usersid);

      $data = $serialize->serialize($clients, 'json');

      $response = new Response($data);
      $response->headers->set('Content-Type', 'application/json');

      return $response;
    }

    else {
      return api_response('Utiliser la methode GET', 405);
    }
  }

  public function ShowSingleClients($usersid, $clientsid, SerializerInterface $serialize, Request $request): Response
  {

    if ($request->isMethod('GET')) {

      $repository = $this->getDoctrine()->getRepository(Clients::class);
      $clients = $repository->findSingleClients($usersid, $clientsid);

      if (!empty($clients)) {
      $data = $serialize->serialize($clients, 'json');

      $response = new Response($data);
      $response->headers->set('Content-Type', 'application/json');

      return $response;
    }
      else {
        return api_response('Ce client n existe pas', 404);
      }
    }

    else {
      return api_response('Utiliser la methode GET', 405);
    }
  }

  public function AddClient($usersid, Request $request): Response
    {

      if ($request->isMethod('POST')) {

        $client = new Clients();

        // vérification nom
        $name = htmlspecialchars(filter_input(INPUT_POST, 'name'));
        if (isset($name) && ctype_alpha($name)) {
          $client->setName($name);
        }
        else {
          return api_response('Nom du client invalide', 400);
        }
        // ***

        // vérification prénom
        $firstname = htmlspecialchars(filter_input(INPUT_POST, 'firstname'));
        if (isset($firstname) && ctype_alpha($firstname)) {
          $client->setFirstname($firstname);
        }
        else {
          return api_response('Prénom du client invalide', 400);
        }
        // ***

        // vérification email
        $email = htmlspecialchars(filter_input(INPUT_POST, 'email'));
        if (isset($email) && filter_var($email, FILTER_VALIDATE_EMAIL)) {
          $client->setEmail($email);
        }
        else {
          return api_response('Email du client invalide', 400);
        }
        // ***

        // vérification numero de rue
        $number = htmlspecialchars(filter_input(INPUT_POST, 'number'));
        if (isset($number) && is_numeric($number)) {
          $client->setNumber($number);
        }
        else {
          return api_response('Numero de rue invalide, utiliser seulement des chiffres', 400);
        }
        // ***

        // vérification rue
        $street = htmlspecialchars(filter_input(INPUT_POST, 'street'));
        if (isset($street) && ctype_alpha($street)) {
          $client->setStreet($street);
        }
        else {
          return api_response('Rue du client invalide', 400);
        }
        // ***

        // vérification code postal
        $postalCode = htmlspecialchars(filter_input(INPUT_POST, 'postalCode'));
        if (isset($postalCode) && is_numeric($postalCode)) {
          $client->setPostalCode($postalCode);
        }
        else {
          return api_response('Code postal invalide', 400);
        }
        // ***

        // vérification ville
        $city = htmlspecialchars(filter_input(INPUT_POST, 'city'));
        if (isset($city) && ctype_alpha($city)) {
          $client->setCity($city);
        }
        else {
          return api_response('Ville du client invalide', 400);
        }
        // ***

        // vérification téléphone
        $tel = htmlspecialchars(filter_input(INPUT_POST, 'tel'));
        if (isset($tel) && preg_match("#^0[1-68]([-. ]?[0-9]{2}){4}$#", $tel))
        {
        	$meta_carac = array("-", ".", " ");
        	$tel = str_replace($meta_carac, "", $tel);
        	$client->setTel($tel);
        }
        else {
          return api_response('Numéro de téléphone du client invalide', 400);
        }
        // ***

        $repository = $this->getDoctrine()->getRepository(Users::class);
        $user = $repository->findOneBy(['id' => $usersid]);
        $client->setUser($user);

        $this->em->persist($client);
        $this->em->flush();

        return api_response('Le client à été ajouter', 200);
      }

      else {
        return api_response('Utiliser la methode POST', 405);
      }
    }

      public function DeleteClient($usersid, $clientsid, Request $request): Response
      {

        if ($request->isMethod('DELETE')) {

          $repository = $this->getDoctrine()->getRepository(Clients::class);
          $client = $repository->findOneBy(['id' => $clientsid, 'user' => $usersid]);

          if (!empty($client)) {
          $this->em->remove($client);
          $this->em->flush();

          return api_response('Le client à été supprimer', 200);
          }

          else {
            return api_response('Ce client n existe pas', 404);
          }
        }

        else {
          return api_response('Utiliser la methode DELETE', 405);
        }
      }
}
