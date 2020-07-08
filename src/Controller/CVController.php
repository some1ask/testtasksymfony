<?php

namespace App\Controller;

use App\Entity\CV;
use App\Entity\User;
use App\Form\CVFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CVController extends AbstractController
{
    /**
     * @Route("/resumeList", name="cvs")
     */
    public function index()
    {
        return $this->render('cv/index.html.twig', [
            'controller_name' => 'CVController',
        ]);
    }
    /**
     * @Route("/resumeList/create", name="cvs")
     */
    public function create(Request $request){
        $CV = new CV();

        $form = $this->createForm(CVFormType::class,$CV);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $CV = $form->getData();
            $this->getUser()->addCV($CV);
            //$user->addCV($CV);
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
}
