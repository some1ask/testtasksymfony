<?php

namespace App\Controller;

use App\Entity\Company;
use App\Entity\CV;
use App\Form\CompanyType;
use App\Form\CVFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CompanyController extends AbstractController
{
    /**
     * @Route("/company", name="company")
     */
    public function index()
    {
        $companies = $this->getUser()->getCompanies()->toArray();
        return $this->render('company/index.html.twig', [
            'controller_name' => 'CompanyController',
            'companies'=>$companies
        ]);
    }

    /**
     * @Route("/company/create", name="createcompany")
     */
    public function create(Request $request)
    {
        $company = new Company();
        $form = $this->createForm(CompanyType::class, $company);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $company = $form->getData();
            $this->getUser()->addCompany($company);
            $entity = $this->getDoctrine()->getManager();
            $entity->persist($company);
            $entity->flush();


            return $this->redirectToRoute('company');
        }
        return $this->render('company/create.html.twig', [
            'companyform' => $form->createView(),
        ]);
    }
    /**
     * @Route("/company/edit/{id}", name="editcompany")
     * @param int $id
     * @param Company $cv
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function edit(int $id,Company $company,Request $request){
        $form = $this->createForm(CompanyType::class,$company);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($company);
            $em->flush();

            return $this->redirectToRoute('company');
        }
        return $this->render('company/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/company/view/{id}",name="companyview")
     * @param int $id
     */
    public function view(int $id){
        $company = $this->getDoctrine()->getRepository(Company::class)->findOneBy(['id'=>$id]);
        return $this->render('company/view.html.twig',[
           'company' => $company
        ]);
    }
    /**
     * @Route("/company/delete/{id}", name="deletecompany")
     */
    public function deletecompany(int $id){
        $company = $this->getDoctrine()->getRepository(Company::class)->find($id);
        $en = $this->getDoctrine()->getManager();
        $en->remove($company);
        $en->flush();

        return $this->redirectToRoute('company');
    }
}
