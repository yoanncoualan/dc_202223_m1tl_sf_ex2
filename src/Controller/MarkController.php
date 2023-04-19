<?php

namespace App\Controller;

use App\Entity\Mark;
use App\Entity\Subject;
use App\Form\MarkType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class MarkController extends AbstractController
{
    #[Route('/', name: 'app_mark')]
    public function index(Request $r, EntityManagerInterface $em, TranslatorInterface $t): Response
    {

        $mark = new Mark();
        $form = $this->createForm(MarkType::class, $mark);
        $form->handleRequest($r);
        if($form->isSubmitted() && $form->isValid()){
            $em->persist($mark);
            $em->flush();
            $this->addFlash('success', $t->trans('mark.added'));
        }

        $marks = $em->getRepository(Mark::class)->findAll();
        $subjects = $em->getRepository(Subject::class)->findAll();

        return $this->render('mark/index.html.twig', [
            'add' => $form->createView(),
            'marks' => $marks,
            'subjects' => $subjects
        ]);
    }
}
