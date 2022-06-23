<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;

class ProductController extends AbstractController
{
    /**
     * @Route("/api/product/{id}", name="product", methods={"GET"})
     */
    public function showProduct(Request $request, ManagerRegistry $doctrine, int $id): Response
    {
        $resp = [];

        $product = $doctrine->getRepository(Product::class)->find($id);

        if (!$product) {
            $resp['error'] = 'No product found';

            return new JsonResponse($resp);
        } else {
            $resp['product'] = $product;
        }

        $resp['error'] = '';

        return new JsonResponse($resp);
    }

}
