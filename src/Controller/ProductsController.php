<?php

namespace App\Controller;

use App\Entity\Products;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ProductsRepository;
use JMS\Serializer\SerializerInterface;
use Twig\Environment;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use Swagger\Annotations as SWG;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Nelmio\ApiDocBundle\Annotation as Doc;
use FOS\RestBundle\Controller\Annotations as Rest;

class ProductsController extends AbstractController
{

  private $twig;

  public function __construct(ProductsRepository $repository, EntityManagerInterface $em, Environment $twig)
  {
    $this->repository = $repository;
    $this->em = $em;
    $this->twig = $twig;
  }
  /**
       * @Rest\Get("/bilemo/products", name="products")
       *
       * @Rest\QueryParam(
       *     name="page",
       *     default="1",
       *     description="number of page"
       * )
       * @SWG\Response(
       *     response=200,
       *     description="Returned list of all products.",
       *     @SWG\Schema(
       *         type="array",
       *         @SWG\Items(ref=@Model(type=Products::class))
       *     )
       * )
       * @SWG\Response(
       *     response=405,
       *     description="Returned when method is not GET"
       * )
       * @SWG\Tag(name="products")
       * @Security(name="Bearer")
       */
  public function ShowProducts(SerializerInterface $serialize, Request $request): Response
  {

      $page = $request->query->get('page');
      $limit = 5;

    if (is_numeric($page)) {

      $repository = $this->getDoctrine()->getRepository(Products::class);
      $products = $repository->findAllProducts($page, $limit);

      $allProducts = array();

      foreach ($products as $product ) {
        array_push($allProducts, [
        'id'=>$product['id'],
        'name'=>$product['name'],
        'brand'=>$product['brand'],
        'model'=>$product['model'],
        '_links'=>[
          'get'=>
          ['href'=>'/users/'.$product['id']]
        ]
        ]);
      }

      $data = $serialize->serialize($allProducts, 'json');

      return new Response($this->twig->render('base.html.twig', [
        'data' => $data
      ]));

    }

    else {
      return api_response('Numero de page invalide', 400);
    }

  }

  /**
    * @Rest\Get(
    *     path = "/bilemo/products/{id}",
    *     name = "singleproducts"
    * )
    * @Rest\View
    *
    *
    * @SWG\Response(
    *     response=200,
    *     description="Returns product details",
    *     @SWG\Schema(
    *         type="array",
    *         @SWG\Items(ref=@Model(type=Products::class))
    *     )
    * )
    * @SWG\Response(
    *     response=404,
    *     description="Returned when ressource is not found"
    * )
    * @SWG\Response(
    *     response=405,
    *     description="Returned when method is not GET"
    * )
    * @SWG\Tag(name="products/{id}")
    * @Security(name="Bearer")
    */
  public function ShowSingleProducts($id, SerializerInterface $serialize, Request $request): Response
  {
   if (is_numeric($id)) {
    if ($request->isMethod('GET')) {

      $repository = $this->getDoctrine()->getRepository(Products::class);
      $products = $repository->findOneBy(['id' => $id]);

      if (!empty($products)) {
      $data = $serialize->serialize($products, 'json');

      return new Response($this->twig->render('base.html.twig', [
        'data' => $data
      ]));
    }
      else {
        return api_response('Ce produit n existe pas', 404);
      }
    }

    else {
      return api_response('Utiliser la methode GET', 405);
    }
  }
  else {
    return api_response('Produit invalide', 400);
  }
 }
}
