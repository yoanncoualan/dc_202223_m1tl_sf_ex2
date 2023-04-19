<?php

namespace App\Controller;

use App\Entity\Subject;
use App\Form\SubjectType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class SubjectController extends AbstractController
{
    #[Route('/subjects', name: 'app_subject')]
    public function index(EntityManagerInterface $em, Request $r, TranslatorInterface $t): Response
    {
        $subject = new Subject();
        $form = $this->createForm(SubjectType::class, $subject);
        $form->handleRequest($r);
        if($form->isSubmitted() && $form->isValid()){
            $em->persist($subject);
            $em->flush();
            $this->addFlash('success', $t->trans('subject.added'));
        }

        $subjects = $em->getRepository(Subject::class)->findAll();

        return $this->render('subject/index.html.twig', [
            'subjects' => $subjects,
            'form' => $form->createView()
        ]);
    }

    #[Route('/subject/{id}', name:'subject')]
    public function show(Subject $subject = null, TranslatorInterface $t, Request $r, EntityManagerInterface $em){
        if($subject == null){
            $this->addFlash('danger', $t->trans('subject.unknown'));
            return $this->redirectToRoute('app_subject');
        }

        $form = $this->createForm(SubjectType::class, $subject);
        $form->handleRequest($r);
        if($form->isSubmitted() && $form->isValid()){
            $em->persist($subject);
            $em->flush();
            $this->addFlash('success', $t->trans('subject.updated'));
        }

        return $this->render('subject/show.html.twig', [
            'subject' => $subject,
            'edit' => $form->createView()
        ]);
    }

    #[Route('/subject/delete/{id}', name:'subject_delete')]
    public function delete(Subject $subject = null, TranslatorInterface $t, EntityManagerInterface $em){
        if($subject == null){
            $this->addFlash('danger', $t->trans('subject.unknown'));
        }
        else{
            $em->remove($subject);
            $em->flush();
            $this->addFlash('warning', $t->trans('subject.deleted'));
        }

        return $this->redirectToRoute('app_subject');
    }
}
