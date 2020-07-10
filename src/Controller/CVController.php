<?php

namespace App\Controller;

use App\Entity\CV;
use App\Entity\User;
use App\Form\CVFormType;
use phpDocumentor\Reflection\Types\This;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CVController extends AbstractController
{

    /**
     * @Route("/resumeList", name="cvs")
     */
    public function index()
    {
        $cvs = $this->getUser()->getCVs()->toArray();

        return $this->render('cv/index.html.twig', [
            'controller_name' => 'CVController',
            'cvs'=>$cvs
        ]);
    }
    /**
     * @Route("/resumeList/delete/{id}", name="deletecv")
     */
    public function deletecv(int $id){
        $cv = $this->getDoctrine()->getRepository(CV::class)->find($id);
        $en = $this->getDoctrine()->getManager();
        $en->remove($cv);
        $en->flush();

        return $this->redirectToRoute('cvs');
    }


    /**
     * @Route("/resumeList/create", name="createcv")
     */
    public function create(Request $request){
        $CV = new CV();
        $form = $this->createForm(CVFormType::class,$CV);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $CV = $form->getData();
            $this->getUser()->addCV($CV);
            $entity = $this->getDoctrine()->getManager();
            $entity->persist($CV);
            $entity->flush();


            return $this->render('default/index.html.twig',[
                'user'=>$this->getUser()
            ]);
        }
        return $this->render('cv/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/resumeList/edit/{id}", name="editcv")
     * @param int $id
     * @param CV $cv
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function edit(int $id,CV $cv,Request $request){
        $form = $this->createForm(CVFormType::class,$cv);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $cv->setUpdatedAt(new \DateTime('now'));
            $em = $this->getDoctrine()->getManager();
            $cv = $form->getData();
            $em->persist($cv);
            $em->flush();

            return $this->redirectToRoute('cvs');
        }
        return $this->render('cv/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }



}
