<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use App\Services\ShoppingCart;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;

class OrderController extends BaseController
{
    /**
     * @Route("/cart/product/{slug}", name="order_add_product_to_cart")
     * @Method("POST")
     */
    public function addProductToCartAction(Product $product, ShoppingCart $shoppingCart)
    {
        //$this->get('shopping_cart')
		$shoppingCart
            ->addProduct($product);

        $this->addFlash('success', 'Product added!');

        return $this->redirectToRoute('order_checkout');
    }

    /**
     * @Route("/checkout", name="order_checkout")
     * @Security("is_granted('ROLE_USER')")
     */
    public function checkoutAction(Request $request, ShoppingCart $shoppingCart)
    {
        //$products = $this->get('shopping_cart')->getProducts();
		$products = $shoppingCart->getProducts();

        if ($request->isMethod('POST')) {
            $token = $request->request->get('stripeToken');

            \Stripe\Stripe::setApiKey("sk_test_HOHvYxqJe8bdC5PsRnpO9zVR003e2fLGbp");
            \Stripe\Charge::create(array(
              "amount" => $shoppingCart->getTotal() * 100,
              "currency" => "usd",
              "source" => $token,
              "description" => "First test charge!"
            ));

           $shoppingCart->emptyCart();
            $this->addFlash('success', 'Order Complete! Yay!');

            return $this->redirectToRoute('homepage');
        }

        return $this->render('order/checkout.html.twig', array(
            'products' => $products,
            'cart' => $shoppingCart
        ));

    }
	
	 /**
     * @Route("/remove/from/cart/{slug}", name="remove_from_cart")
     */
    public function removeFromCart($slug, ShoppingCart $shoppingCart, ProductRepository $productRepository)
    {
	   $product =  $productRepository->findOneBySlug($slug); 
        $shoppingCart->removeFromCart($product, $slug);
        return $this->redirectToRoute('order_checkout');
    }
}
