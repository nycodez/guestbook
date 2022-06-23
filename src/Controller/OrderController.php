<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Entity\Product;
use App\Entity\CartProduct;
use App\Entity\OrderProduct;
use App\Entity\CartProductRepository;
use App\Entity\Order;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;
use Knp\Snappy\Pdf;

class OrderController extends AbstractController
{
    /**
     * @Route("/api/cart", name="cart", methods={"GET"})
     */
    public function showCart(ManagerRegistry $doctrine, Security $security): Response
    {
        $user = $security->getUser();

        $resp = [];
        
        $cartProducts = $user->getCartProducts();

        foreach($cartProducts as $cartProduct) {
            $resp['products'][] = [
                "cart_id" => $cartProduct->getProductId()->getId(),
                "product_id" => $cartProduct->getId(),
                "name" => $cartProduct->getProductId()->getName(),
                "price" => $cartProduct->getProductId()->getPrice(),
                "quantity" => $cartProduct->getQuantity()
            ];
        }

        $resp['error'] = '';

        return new JsonResponse($resp);
    }

    /**
     * @Route("/api/cart/add", name="addProduct", methods={"POST"})
     */
    public function addProduct(Request $request, ManagerRegistry $doctrine, Security $security): Response
    {
        $resp = [];

        $user = $security->getUser();
        $id = $request->get('product_id');
        $qty = $request->get('quantity');

        if(!$id || !$qty) {
            $resp['error'] = 'Input error';

            return new JsonResponse($resp);
        }

        $product = $doctrine->getRepository(Product::class)->find($id);

        $cartProduct = new CartProduct();
        $cartProduct->setUserId($user);
        $cartProduct->setProductId($product);
        $cartProduct->setQuantity($qty);
        $em = $doctrine->getManager();
        $em->persist($cartProduct);
        $em->flush();

        $resp['error'] = '';

        return new JsonResponse($resp);
    }

    /**
     * @Route("/api/cart/delete", name="deleteProduct", methods={"POST"})
     */
    public function deleteProduct(Request $request, ManagerRegistry $doctrine, Security $security): Response
    {
        $resp = [];

        $user = $security->getUser();
        $cart_id = $request->get('cart_id');
        $product_id = $request->get('product_id');

        if(!$cart_id && !$product_id) {
            $resp['error'] = 'Input error';

            return new JsonResponse($resp);
        }

        if($cart_id) {
            $cartProduct = $doctrine->getRepository(CartProduct::class)->find($cart_id);
            $em = $doctrine->getManager();
            $em->remove($cartProduct);    
        } else if($product_id) {
            $product = $doctrine->getRepository(Product::class)->find($product_id);
            $cartProduct = $doctrine->getRepository(CartProduct::class)->findBy(['user_id' => $user, 'product_id' => $product]);   
            $em = $doctrine->getManager();
            $em->remove($cartProduct[0]);     
        }

        $em->flush();

        $resp['error'] = '';

        return new JsonResponse($resp);
    }

    /**
     * @Route("/api/order/create", name="createOrder", methods={"POST"})
     */
    public function createOrder(Request $request, ManagerRegistry $doctrine, Security $security): Response
    {
        $user = $security->getUser();
        $user_id = $user->getId();

        $resp = [];
        $total = 0;
        
        $cartProducts = $user->getCartProducts();

        foreach($cartProducts as $cartProduct) {
            $amount = $cartProduct->getProductId()->getPrice() * $cartProduct->getQuantity();
            $product_id = $cartProduct->getProductId();

            $total += $amount;
        }

        $order = new Order();
        $order->setUserId($user_id);
        $order->setSubmittedAt(new \DateTime());
        $order->setTotal($total);

        $em = $doctrine->getManager();
        $em->persist($order);
        $em->flush();

        // $order_id = $order->getId();

        // FIX: looping through the same collection twice is wasteful

        foreach($cartProducts as $cartProduct) {
            $product_id = $cartProduct->getProductId();

            $orderProduct = new OrderProduct();
            $orderProduct->setOrderId($order);
            $orderProduct->setProductId($product_id);
            $orderProduct->setName($cartProduct->getProductId()->getName());
            $orderProduct->setQuantity($cartProduct->getQuantity());
            $orderProduct->setAmount($cartProduct->getProductId()->getPrice());
    
            $em = $doctrine->getManager();
            $em->persist($orderProduct);
            $em->flush();
        }

        $em = $doctrine->getManager();

        foreach($cartProducts as $cartProduct) {
            $em->remove($cartProduct);     
        }

        $em->flush();

        $resp['error'] = '';

        return new JsonResponse($resp);
    }

    /**
     * @Route("/api/order/invoice", name="deleteProduct", methods={"POST"})
     */
    public function createInvoice(Request $request, ManagerRegistry $doctrine, Security $security, Pdf $knpSnappyPdf): Response
    {
        $resp = [];
        $resp['error'] = '';

        $knpSnappyPdf->generate('http://www.google.fr', '/srv/app/invoices/file.pdf');

        return new JsonResponse($resp);
    }

}
