<?php
namespace App\Controller;
use App\Entity\RepLog;
use App\Form\RepLogType;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
class LiftController extends AbstractController
{
    /**
     * @Route("/lift", name="lift")
     */
    public function indexAction(Request $request)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_REMEMBERED');
        $form = $this->createForm(RepLogType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $repLog = $form->getData();
            $repLog->setUser($this->getUser());
            $em->persist($repLog);
            $em->flush();
            // return a blank form after success
            if ($request->isXmlHttpRequest()) {
                return $this->render('lift/_repRow.html.twig', [
                    'repLog' => $repLog
                ]);
            }
            $this->addFlash('notice', 'Reps crunched!');
            return $this->redirectToRoute('lift');
        }
        $repLogs = $this->getDoctrine()->getRepository('App:RepLog')
            ->findBy(array('user' => $this->getUser()))
        ;
        $totalWeight = 0;
        foreach ($repLogs as $repLog) {
            $totalWeight += $repLog->getTotalWeightLifted();
        }
        // render just the form for AJAX, there is a validation error
        if ($request->isXmlHttpRequest()) {
            $html = $this->renderView('lift/_form.html.twig', [
                'form' => $form->createView()
            ]);
            return new Response($html, 400);
        }
        return $this->render('lift/index.html.twig', array(
            'form' => $form->createView(),
            'repLogs' => $repLogs,
            'leaderboard' => $this->getLeaders(),
            'totalWeight' => $totalWeight,
        ));
    }
    /**
     * Returns an array of leader information
     *
     * @return array
     */
    private function getLeaders()
    {
        $leaderboardDetails = $this->getDoctrine()->getRepository('App:RepLog')
            ->getLeaderboardDetails()
        ;
        $userRepo = $this->getDoctrine()->getRepository('App:User');
        $leaderboard = array();
        foreach ($leaderboardDetails as $details) {
            if (!$user = $userRepo->find($details['user_id'])) {
                // interesting, this user is missing...
                continue;
            }
            $leaderboard[] = array(
                'username' => $user->getUsername(),
                'weight' => $details['weightSum'],
                'in_cats' => number_format($details['weightSum']/RepLog::WEIGHT_FAT_CAT, 1),
            );
        }
        return $leaderboard;
    }
}