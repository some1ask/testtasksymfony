<?php

namespace App\Controller;

use App\Entity\Company;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @Route("/default", name="default")
     */
    public function index()
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_ANONYMOUSLY');
        $companies = $this->getDoctrine()->getRepository(Company::class)->findAll();
        $user = $this->getUser();
        return $this->render('default/index.html.twig', [
            'user'=>$user,
            'companies'=>$companies
        ]);
    }
}
