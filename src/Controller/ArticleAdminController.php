<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class ArticleAdminController extends AbstractController
{
    /**
     * @Route("/admin/article/list", name="admin_article_list", methods={"GET"})
     */
    public function index(ArticleRepository $articleRepository): Response
    {
        return $this->render('article/index.html.twig', [
            'articles' => $articleRepository->findAll(),
        ]);
    }

    /**
     * @Route("/admin/article/new", name="admin_article_new", methods={"GET","POST"})
	 * @Security("is_granted('ROLE_USER')")
     */
    public function new(Request $request): Response
    {
	     $user = $this->getUser();
		 $author = $user->getAuthor();
	     //$user = $this->user->getAuthor();
		if(!$user->getAuthor()){
		  $this->addFlash('error', 'Unable to find author!');
        return $this->redirectToRoute('admin_author_new');
		 $this->addFlash('error', 'Inscriver-vous pour devenir auteur!');
		}
        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
		    $article->setAuthor($author);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($article);
            $entityManager->flush();

            return $this->redirectToRoute('admin_article_list');
        }

        return $this->render('article/new.html.twig', [
            'article' => $article,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/article/{id}", name="admin_article_show", methods={"GET"})
     *
    public function show($id, ArticleRepository $articleRepository): Response
    {
	    $article = $articleRepository->find($id);
        return $this->render('article/show.html.twig', [
            'article' => $article,
        ]);
    }
	*/

    /**
     * @Route("/admin/article/{id}/edit", name="admin_article_edit", methods={"GET","POST"})
     */
    public function edit(Article $article, Request $request): Response
    {
	    // $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
		
            $this->getDoctrine()->getManager()->flush();
			$this->addFlash(
                'notice', 'Congratulations!!, your resource has been modified!'
				);

            return $this->redirectToRoute('admin_article_list', [
                'id' => $article->getId(),
            ]);

        }

        return $this->render('article/edit.html.twig', [
            'article' => $article,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/article/{id}/delete", name="admin_article_delete")
     */

    public function delete($id, Article $article, Request $request): Response
    {
        $this->denyAccessUnlessGranted('DELETE', $article);

        $entityManager = $this->getDoctrine()->getManager();
         $entityManager->remove($article);
        $entityManager->flush();

        return $this->redirectToRoute('admin_article_list');
    }
}
