<?php

namespace App\Controller;
use App\Entity\Article;
use App\Entity\User;
use App\Repository\UserRepository;
use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class HomeController extends AbstractController
{
    /**
     * @Route("/", name="homepage")
	
     */
   public function index(ArticleRepository $articleRepository): Response
    {
        return $this->render('home/index.html.twig', [
            'articles' => $articleRepository->findAll(),
        ]);
    }
	
	/**
     * @Route("show/{id}", name="article_show", methods={"GET"})
     */
    public function show(Article $article): Response
    {
        return $this->render('home/show.html.twig', [
            'article' => $article,
        ]);
    }
	
	/**
 * @Route("/user/{username}", name="user")
 */
public function userAction($name)
{
    $user = $this->authorRepository->findOneByUsername($user);

    if (!$user) {
        $this->addFlash('error', 'Unable to find author!');
        return $this->redirectToRoute('homepage');
    }

    return $this->render('home/user.html.twig', [
        'user' => $user
    ]);
}

}
