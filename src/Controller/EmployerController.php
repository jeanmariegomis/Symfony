<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use App\Entity\Employer;
use App\Entity\Repository;
use App\Entity\Service;

class EmployerController extends AbstractController
{
    /**
     * @Route("/employer", name="employer")
     */
    public function index()
    {
        return $this->render('employer/index.html.twig', [
            'controller_name' => 'EmployerController',
        ]);
    }

     /**
     * @Route("/", name="employer_home")
     */
  
     public function home(){
        return $this->render('employer/home.html.twig');
     }


     /**
     * @Route("/employer/new", name="employer_create")
     * @Route("employer/{id}/edit", name="employer_edit")
     */

    public function form(Employer $employer, Request $request, ObjectManager $manager) {

        if(!$employer){

            $employer = new Employer();

        }
     
       

        $form = $this->createFormBuilder($employer)
                      ->add('matricule')
                      ->add('nom',TextType::class) 
                      ->add('datenaiss', DateType::class, [
                       'widget' => 'single_text',
                       // this is actually the default format for single_text
                       'format' => 'yyyy-MM-dd',
                   ])

                      ->add('salaire')
                      //->add('service')
                      
                //       ,  EntityType::class, [
                //        'class' => Service::class,
                //        'choice_label' => 'libelle',
                //    ])

                  
                      ->getForm();

                     
                      $form->handleRequest($request);
                      if ($form->isSubmitted() && $form->isValid()){
                          $manager->persist($employer);
                          $manager->flush();

                          return $this->redirectToRoute('employer_list', [
                            'id' => $employer->getId()
                          ]);
                      }
                      

       return $this->render('employer/create.html.twig', [
           'formEmployer' => $form->createView(),
           'editMode' => $employer->getId() !==null
       ]);
    }

    /**
     * @Route("employer/liste", name="employer_list")
     */

    public function liste(){
        $repo = $this->getDoctrine()->getRepository(Employer::class);
        $employers = $repo->findAll();

        return $this->render('employer/liste.html.twig', [
           'employers'=> $employers
        ]);
    }

}
