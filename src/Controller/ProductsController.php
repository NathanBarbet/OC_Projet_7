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
       * @SWG\Response(
       *     response=200,
       *     description="Returned list of all products.",
       *     @SWG\Schema(
       *         type="array",
       *         @SWG\Items(ref=@Model(type=Products::class))
       *     )
       * )
       * @SWG\Tag(name="products")
       * @Security(name="Bearer")
       */
  public function ShowProducts(SerializerInterface $serialize, Request $request): Response
  {

    if ($request->isMethod('GET')) {
      
      $page = $request->query->get('page');
      $limit = 5;

      $repository = $this->getDoctrine()->getRepository(Products::class);
      $products = $repository->findAllProducts($page, $limit);

      $data = $serialize->serialize($products, 'json');

      return new Response($this->twig->render('base.html.twig', [
        'data' => $data
      ]));

    }

    else {
      return api_response('Utiliser la methode GET', 405);
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
}
