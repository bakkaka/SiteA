<?php

namespace App\Services;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
//use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class ShoppingCart
{
    const CART_PRODUCTS_KEY = '_shopping_cart.products';

    private $sessionInter;
    private $em;

    private $products;

    public function __construct(SessionInterface $sessionInter, EntityManagerInterface $em)
    {
        $this->sessionInter = $sessionInter;
        $this->em = $em;
    }

    public function addProduct(Product $product)
    {
        $products = $this->getProducts();

        if (!in_array($product, $products)) {
            $products[] = $product;
        }

        $this->updateProducts($products);
    }

    /**
     * @return Product[]
     */
    public function getProducts()
    {
        if ($this->products === null) {
            $productRepo = $this->em->getRepository('App:Product');
            $ids = $this->sessionInter->get(self::CART_PRODUCTS_KEY, []);
            $products = [];
            foreach ($ids as $id) {
                $product = $productRepo->find($id);

                // in case a product becomes deleted
                if ($product) {
                    $products[] = $product;
                }
            }

            $this->products = $products;
        }

        return $this->products;
    }

    public function getTotal()
    {
        $total = 0;
        foreach ($this->getProducts() as $product) {
            $total += $product->getPrice();
        }

        return $total;
    }

    public function emptyCart()
    {
        $this->updateProducts([]);
    }

    /**
     * @param Product[] $products
     */
    private function updateProducts(array $products)
    {
        $this->products = $products;

        $ids = array_map(function(Product $item) {
            return $item->getId();
        }, $products);

        $this->sessionInter->set(self::CART_PRODUCTS_KEY, $ids);
    }
	
	public function removeFromCart(Product $product, $id)
    {
	    
        $this->sessionInter->remove($id);
        $this->updateProducts([]);
    }

    public function deleteProduct(Product $product)
    {
        //$this->session->remove('cart');
        //$this->session->remove('cart_data');
        // $this->session->clear();
    }
}