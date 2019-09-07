<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;

use App\Form\ProductType;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ProductController extends BaseController
{
     /**
     * @Route("/admin/product/list", name="admin_product_list")
	 */
     public function listAction(Request $request, ProductRepository $productRepository): Response
    {
        $products = $productRepository->findAll();
        return $this->render('product/list.html.twig', [

            //'allarticles' => $articleRepository->getArticleWithUser($user),
            'products' => $products,
        ]);
    }

    
	
	
    /**
     * @Route("/products/{slug}", name="product_show")
     */
    public function showAction(Product $product)
    {
        return $this->render('product/show.html.twig', array(
            'product' => $product
        ));
    }

    /**
     * @Route("/pricing", name="pricing_show")
     */
    public function pricingAction()
    {
        return $this->render('product/pricing.html.twig', array(
        ));
    }
	
		 /**
     * @Route("admin/product/new", name="admin_product_new")
	 * @Security("is_granted('ROLE_USER')")
     */
    public function new(Request $request): Response
    {

        $user = $this->getUser();
        $product = new Product();


        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
		$em = $this->getDoctrine()->getManager();
         
        $em->persist($product);
            $em->flush();
            $this->addFlash('success', 'Product Created! Espérant de vendre!');


            return $this->redirectToRoute('homepage');
        }

        return $this->render('product/new.html.twig', [
            'product' => $product,
            'form' => $form->createView(),
        ]);
    }
	
	    /**
     * @Route("/admin/product/{slug}/edit", name="admin_product_edit", methods={"GET","POST"})
     */
    public function edit(Product $product, Request $request): Response
    {
        //$this->denyAccessUnlessGranted('EDIT', $article);
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->getDoctrine()->getManager()->flush();
            $this->addFlash(
                'notice', 'Congratulations!!, your product has been modified!'
            );

            return $this->redirectToRoute('product', [
                'id' => $product->getId(),
            ]);
        }

        return $this->render('product/edit.html.twig', [
            'product' => $product,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("admin/product/{slug}/delete", name="admin_product_delete", methods={"GET","POST"})
     */

    public function delete(Product $product, Request $request): Response
    {
        //$this->denyAccessUnlessGranted('DELETE', $article);

        $entityManager = $this->getDoctrine()->getManager();
         $entityManager->remove($product);
        $entityManager->flush();

        return $this->redirectToRoute('homepage');
    }

}
