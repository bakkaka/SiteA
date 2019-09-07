<?php

namespace App\Controller;

use App\Form\AuthorType;
use App\Entity\Author;
use App\Form\CommentType;
use App\Repository\CommentRepository;
use App\Repository\ArticleRepository;
use App\Entity\Article;
use App\Form\ArticleType;
use App\Entity\Comment;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

use App\Service\MarkdownHelper;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class CommentController extends AbstractController
{
    /**
     * @Route("/comment", name="comment")
     * @param ArticleRepository $articleRepository
     * @return Response
	 * @return App\Entity\Article
     */
    public function index(ArticleRepository $articleRepository): Response
    {
        $user = $this->getUser();

        return $this->render('comment/index.html.twig', [
            'comments' => $commentRepository->findAll(),
            //'allcomments' => $commentRepository->getArticleWithUser($user),

        ]);
    }


  

    /**
     * @Route("show/comment/{id}", name="comment_show", methods={"GET"})

     */
    public function show( ArticleRepository $articleRepository,CommentRepository $commentRepository, Request $request, $id,EntityManagerInterface $em): Response
    {
        //$comment = $commentRepository->find($id);
		 
        
        
        $comments = $commentRepository
            ->getCommentWithArticle($article);
        if (!$article) {
            throw $this->createNotFoundException(sprintf('No article for slug "%s"', $id));
        }
		
        
        return $this->render('blog/show.html.twig', [
            'article' => $article,
		  'comments' => $comments

        ]);
    }

    

    /**
     * @Route("/news/{id}/heart", name="article_toggle_heart", methods={"POST"})
     */
    public function toggleArticleHeart(Article $article, LoggerInterface $logger,EntityManagerInterface $em)
    {
        // TODO - actually heart/unheart the article!
        $logger->info('Article is being hearted!');
        $article->incrementHeartCount();
        $em->flush();
        return new JsonResponse(['hearts' => $article->getHeartCount()]);
    }
	
   
    
    /**
     * @Route("/addComment/article/{id}", name="blog_addcomment", methods={"GET","POST"})
	 * @Security("is_granted('ROLE_USER')")

     */

    public function addComment(Article $article, Request $request, CommentRepository $commentRepository, ArticleRepository $articleRepository, $id): Response
    {
	
	     $user = $this->getUser();
		 $author = $user->getAuthor();
	   
		if(!$user->getAuthor()){
		  $this->addFlash('error', 'Unable to find author!');
        return $this->redirectToRoute('admin_author_new');
		 $this->addFlash('error', 'Inscriver-vous pour devenir auteur!');
		}
	
	
	   
        $comment = new Comment();
        
        $entityManager = $this->getDoctrine()->getManager();
        $article = $entityManager->getRepository('App:Article')->findOneById($id);


        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $article = $entityManager->getRepository('App:Article')->findOneById($id);
            $comment->setAuthor($author);
          
            $comment->setArticle($article);


            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($comment);
            $entityManager->flush();
            $this->addFlash(
                'notice', 'Congratulations!!, your comment has been added!'
            );

            return $this->redirectToRoute('blog_show', array('id' => $article->getId()));
          
        }

        return $this->render('blog/addComment.html.twig', [
            $comments = $commentRepository
                ->getCommentWithArticle($article),
            'article' => $articleRepository->findOneById($id),
            'form' => $form->createView(),
        ]);
    }


}
