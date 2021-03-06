<?php

namespace App\Controller;

use App\Entity\Clients;
use App\Entity\Users;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Twig\Environment;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\UsersRepository;
use JMS\Serializer\SerializerInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use Swagger\Annotations as SWG;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Nelmio\ApiDocBundle\Annotation as Doc;
use FOS\RestBundle\Controller\Annotations as Rest;

class UsersController extends AbstractController
{

  private $twig;

  public function __construct(UsersRepository $repository, EntityManagerInterface $em, Environment $twig)
  {
    $this->repository = $repository;
    $this->em = $em;
    $this->twig = $twig;
  }

  /**
    * @Rest\Get(
    *     path = "/bilemo/users",
    *     name = "usersshow"
    * )
    * @Rest\QueryParam(
    *     name="page",
    *     default="1",
    *     description="number of page"
    * )
    * @Rest\View
    *
    *
    * @SWG\Response(
    *     response=200,
    *     description="Returns list of all users.",
    *     @SWG\Schema(
    *         type="array",
    *         @SWG\Items(ref=@Model(type=Users::class))
    *     )
    * )
    * @SWG\Response(
    *     response=400,
    *     description="Page number is invalid"
    * )
    * @SWG\Response(
    *     response=405,
    *     description="Returned when method is not allowed"
    * )
    * @SWG\Tag(name="users")
    * @Security(name="Bearer")
    *
    */
  public function UsersShow(SerializerInterface $serialize, Request $request): Response
  {

      $page = $request->query->get('page');
      $limit = 5;

      if (is_numeric($page)) {

      $client = $this->getUser();
      $clientid = $client->getId();

      $repository = $this->getDoctrine()->getRepository(Users::class);
      $users = $repository->findAllUsers($clientid, $page, $limit);

      $allUsers = array();

      foreach ($users as $user ) {
        array_push($allUsers, [
        'id'=>$user['id'],
        'name'=>$user['name'],
        'firstname'=>$user['firstname'],
        'email'=>$user['email'],
        '_links'=>[
          'get'=>
          ['href'=>'/users/'.$user['id']],
          'delete'=>
          ['href'=>'/users/'.$user['id']]
        ]
        ]);
      }

      $data = $serialize->serialize($allUsers, 'json');

      return new Response($this->twig->render('base.html.twig', [
        'data' => $data
      ]));

    }

    else {
      return api_response('Numero de page invalide', 400);
    }
  }


  /**
    * @Rest\Post(
    *     path = "/bilemo/users",
    *     name = "usersadd"
    * )
    * @Rest\View
    *
    *      @SWG\Parameter(
    *          name="JSON",
    *          in="body",
    *          description="User in JSON format",
    *          required=true,
    *          format="application/json",
    *          @SWG\Schema(
    *              type="object",
    *              @SWG\Property(property="name", type="string", example="prenom"),
    *              @SWG\Property(property="firstname", type="string", example="nom"),
    *              @SWG\Property(property="email", type="string", example="test@test.fr"),
    *              @SWG\Property(property="number", type="string", example="55"),
    *              @SWG\Property(property="street", type="string", example="rue test"),
    *              @SWG\Property(property="postal_code", type="string", example="65500"),
    *              @SWG\Property(property="city", type="string", example="ville"),
    *              @SWG\Property(property="tel", type="string", example="0123456789"),
    *          )
    *
    *      )
    *
    * @SWG\Response(
    *     response=201,
    *     description="Add a new user",
    * )
    * @SWG\Response(
    *     response=400,
    *     description="Field is invalid"
    * )
    * @SWG\Response(
    *     response=405,
    *     description="Returned when method is not allowed"
    * )
    * @SWG\Tag(name="users")
    * @Security(name="Bearer")
    *
    */
  public function UsersAdd(SerializerInterface $serialize, Request $request): Response
  {

    if ($request->isMethod('POST')) {

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
      if (isset($street)) {
        $user->setStreet($street);
      }
      else {
        return api_response('Rue utilisateur invalide', 400);
      }
      // ***

      // vérification code postal
      $postalCode = $user->getPostalCode();
      if (isset($postalCode) && is_numeric($number)) {
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

      return api_response('L utilisateur à été ajouter', 201);
    }

    else {
      return api_response('Utiliser la methode POST pour ajouter un utilisateur ', 405);
    }
  }


  /**
    * @Rest\Get(
    *     path = "/bilemo/users/{usersid}",
    *     name = "singleusershow"
    * )
    * @Rest\View
    *
    *
    * @SWG\Response(
    *     response=200,
    *     description="Returns details of a user",
    *     @SWG\Schema(
    *         type="array",
    *         @SWG\Items(ref=@Model(type=Users::class))
    *     )
    * )
    * @SWG\Response(
    *     response=404,
    *     description="Returned when user is not found"
    * )
    * @SWG\Response(
    *     response=400,
    *     description="User is invalid"
    * )
    * @SWG\Response(
    *     response=405,
    *     description="Returned when method is not allowed"
    * )
    * @SWG\Tag(name="users/{usersid}")
    * @Security(name="Bearer")
    *
    */
  public function SingleUserShow($usersid, SerializerInterface $serialize, Request $request): Response
  {

  if (is_numeric($usersid)) {
    if ($request->isMethod('GET')) {

      $client = $this->getUser();
      $clientid = $client->getId();

      $repository = $this->getDoctrine()->getRepository(Users::class);
      $users = $repository->findSingleUsers($clientid, $usersid);

      $tabUser = array();

      foreach ($users as $user ) {
        array_push($tabUser, [
        'id'=>$user['id'],
        'name'=>$user['name'],
        'firstname'=>$user['firstname'],
        'email'=>$user['email'],
        'number'=>$user['number'],
        'street'=>$user['street'],
        'postalCode'=>$user['postalCode'],
        'city'=>$user['city'],
        'tel'=>$user['tel'],
        '_links'=>[
          'delete'=>
          ['href'=>'/users/'.$user['id']]
        ]
        ]);
      }

      if (!empty($users)) {
      $data = $serialize->serialize($tabUser, 'json');

      return new Response($this->twig->render('base.html.twig', [
        'data' => $data
      ]));
    }
      else {
        return api_response('Cet utilisateur n existe pas', 404);
      }
    }

    else {
      return api_response('Utiliser la methode GET pour afficher cet utilisateur', 405);
    }
  }
  else {
    return api_response('Utilisateur invalide', 400);
  }
}



  /**
    * @Rest\Delete(
    *     path = "/bilemo/users/{usersid}",
    *     name = "singleuserdelete"
    * )
    * @Rest\View
    *
    *
    * @SWG\Response(
    *     response=204,
    *     description="Delete a specific user",
    * )
    * @SWG\Response(
    *     response=404,
    *     description="Returned when user is not found"
    * )
    * @SWG\Response(
    *     response=400,
    *     description="User is invalid"
    * )
    * @SWG\Response(
    *     response=403,
    *     description="Returned when if the user does not belong to you"
    * )
    * @SWG\Response(
    *     response=405,
    *     description="Returned when method is not allowed"
    * )
    * @SWG\Tag(name="users/{usersid}")
    * @Security(name="Bearer")
    *
    */
  public function SingleUserDelete($usersid, Request $request)
  {
   if (is_numeric($usersid)) {
    if ($request->isMethod('DELETE')) {

      $client = $this->getUser();
      $clientid = $client->getId();

      $repository = $this->getDoctrine()->getRepository(Users::class);
      $user = $repository->findOneBy(['id' => $usersid]);

    if ($clientid == $user->getClient()->getId()) {

      if (!empty($user)) {
      $this->em->remove($user);
      $this->em->flush();

      return api_response('', 204);
      }

      else {
        return api_response('Cet utilisateur n existe pas', 404);
      }
    }
    else {
      return api_response('Vous ne pouvez pas supprimer cet utilisateur', 403);
    }
  }

    else {
      return api_response('Utiliser la methode DELETE pour supprimer cet utilisateur', 405);
    }
  }
  else {
    return api_response('Utilisateur invalide', 400);
  }
}


  /**
    * @Rest\Post(
    *     path = "/bilemo/login",
    *     name = "api_login_check"
    * )
    * @Rest\View
    *
    *      @SWG\Parameter(
    *          name="login",
    *          in="body",
    *          description="JSON login",
    *          required=true,
    *          format="application/json",
    *          @SWG\Schema(
    *              type="object",
    *              @SWG\Property(property="username", type="string", example="test@test.fr"),
    *              @SWG\Property(property="password", type="string", example="test"),
    *          )
    *
    *      )
    *
    * @SWG\Response(
    *     response=200,
    *     description="Returns a JWT token",
    * )
    * @SWG\Response(
    *     response=401,
    *     description="Returned when invalid credentials"
    * )
    * @SWG\Response(
    *     response=405,
    *     description="Returned when method is not allowed"
    * )
    * @SWG\Tag(name="login")
    *
    */
  public function login()
  {

  }

}
