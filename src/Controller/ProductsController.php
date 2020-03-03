<?php

namespace App\Controller;

use App\Entity\Products;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ProductsRepository;
use JMS\Serializer\SerializerInterface;

class ProductsController extends AbstractController
{

  public function __construct(ProductsRepository $repository, EntityManagerInterface $em)
  {
    $this->repository = $repository;
    $this->em = $em;
  }


  public function ShowProducts(SerializerInterface $serialize, Request $request): Response
  {

      $repository = $this->getDoctrine()->getRepository(Products::class);
      $products = $repository->findAllProducts();

      $data = $serialize->serialize($products, 'json');

      $response = new Response($data);
      $response->headers->set('Content-Type', 'application/json');

      return $response;

  }

  public function ShowSingleProducts($id, SerializerInterface $serialize, Request $request): Response
  {

      $repository = $this->getDoctrine()->getRepository(Products::class);
      $products = $repository->findOneBy(['id' => $id]);

      $data = $serialize->serialize($products, 'json');

      $response = new Response($data);
      $response->headers->set('Content-Type', 'application/json');

      return $response;

  }
}
