<?php

namespace App\Controller;

use App\Entity\Feedback;
use App\Form\Type\FeedbackForm;
use App\Helper\UserSupportHelper;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/help")
 */
class HelpController extends Controller
{
    /**
     * @Route("/", name="show_help")
     */
    public function showHelpAction(Request $request, UserSupportHelper $helper)
    {
        $feedback = new Feedback();
        $em = $this->getDoctrine()->getManager();

        $form = $this->createForm(FeedbackForm::class, $feedback);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $feedback->setUser($this->getUser());

            $em->persist($feedback);
            $em->flush();

            $helper->sendEmail($feedback);

            $form = $this->createForm(FeedbackForm::class, new Feedback());

            return $this->render('client/help/help.html.twig', [
                'form' => $form->createView(),
                'gotMessage' => true
            ]);
        }


        return $this->render('client/help/help.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}