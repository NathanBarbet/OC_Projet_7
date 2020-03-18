<?php

namespace App\Controller;

use App\Entity\Clients;
use App\Entity\Users;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Twig\Environment;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\UsersRepository;
use JMS\Serializer\SerializerInterface;

class UsersController extends AbstractController
{

  private $twig;

  public function __construct(UsersRepository $repository, EntityManagerInterface $em, Environment $twig)
  {
    $this->repository = $repository;
    $this->em = $em;
    $this->twig = $twig;
  }


  public function Users(SerializerInterface $serialize, Request $request): Response
  {

    if ($request->isMethod('GET')) {

      $client = $this->getUser();
      $clientid = $client->getId();

      $repository = $this->getDoctrine()->getRepository(Users::class);
      $users = $repository->findAllUsers($clientid);

      $data = $serialize->serialize($users, 'json');

      $response = new Response($data);
      $response->headers->set('Content-Type', 'application/json');

      return $response;
    }

    elseif ($request->isMethod('POST')) {

      $user = new Users();

      $client = $this->getUser();
      $clientid = $client->getId();

      $data = $request->getContent();

      $user = $serialize->deserialize($data, 'App\Entity\Users', 'json');


      // vérification nom
      $name = $user->getName();
      if (isset($name) && ctype_alpha($name)) {
        $user->setName($name);
      }
      else {
        return api_response('Nom utilisateur invalide', 400);
      }
      // ***

      // vérification prénom
      $firstname = $user->getFirstname();
      if (isset($firstname) && ctype_alpha($firstname)) {
        $user->setFirstname($firstname);
      }
      else {
        return api_response('Prénom utilisateur invalide', 400);
      }
      // ***

      // vérification email
      $email = $user->getEmail();
      if (isset($email) && filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $user->setEmail($email);
      }
      else {
        return api_response('Email utilisateur invalide', 400);
      }
      // ***

      // vérification numero de rue
      $number = $user->getNumber();
      if (isset($number) && is_numeric($number)) {
        $user->setNumber($number);
      }
      else {
        return api_response('Numero de rue invalide, utiliser seulement des chiffres', 400);
      }
      // ***

      // vérification rue
      $street = $user->getStreet();
      if (isset($street) && ctype_alpha($street)) {
        $user->setStreet($street);
      }
      else {
        return api_response('Rue utilisateur invalide', 400);
      }
      // ***

      // vérification code postal
      $postalCode = $user->getPostalCode();
      if (isset($postalCode) && is_numeric($postalCode)) {
        $user->setPostalCode($postalCode);
      }
      else {
        return api_response('Code postal invalide', 400);
      }
      // ***

      // vérification ville
      $city = $user->getCity();
      if (isset($city) && ctype_alpha($city)) {
        $user->setCity($city);
      }
      else {
        return api_response('Ville utilisateur invalide', 400);
      }
      // ***

      // vérification téléphone
      $tel = $user->getTel();
      if (isset($tel) && preg_match("#^0[1-68]([-. ]?[0-9]{2}){4}$#", $tel))
      {
        $meta_carac = array("-", ".", " ");
        $tel = str_replace($meta_carac, "", $tel);
        $user->setTel($tel);
      }
      else {
        return api_response('Numéro de téléphone utilisateur invalide', 400);
      }
      // ***

      $repository = $this->getDoctrine()->getRepository(Clients::class);
      $client = $repository->findOneBy(['id' => $clientid]);
      $user->setClient($client);

      $this->em->persist($user);
      $this->em->flush();

      return api_response('L utilisateur à été ajouter', 200);
    }

    else {
      return api_response('Utiliser la methode GET pour afficher les utilisateurs, ou la methode POST pour ajouter un utilisateur ', 405);
    }
  }

  public function SingleUser($usersid, SerializerInterface $serialize, Request $request): Response
  {

    if ($request->isMethod('GET')) {

      $client = $this->getUser();
      $clientid = $client->getId();

      $repository = $this->getDoctrine()->getRepository(Users::class);
      $users = $repository->findSingleUsers($clientid, $usersid);

      if (!empty($users)) {
      $data = $serialize->serialize($users, 'json');

      $response = new Response($data);
      $response->headers->set('Content-Type', 'application/json');

      return $response;
    }
      else {
        return api_response('Cet utilisateur n existe pas', 404);
      }
    }

    elseif ($request->isMethod('DELETE')) {

      $client = $this->getUser();
      $clientid = $client->getId();

      $repository = $this->getDoctrine()->getRepository(Users::class);
      $user = $repository->findOneBy(['id' => $usersid, 'client' => $clientid]);

      if (!empty($user)) {
      $this->em->remove($user);
      $this->em->flush();

      return api_response('L utilisateur à été supprimer', 200);
      }

      else {
        return api_response('Cet utilisateur n existe pas', 404);
      }
    }

    else {
      return api_response('Utiliser la methode GET pour afficher cet utilisateur, ou la methode DELETE pour supprimer cet utilisateur', 405);
    }
  }

}
