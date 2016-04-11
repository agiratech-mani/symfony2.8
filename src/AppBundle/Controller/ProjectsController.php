<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use AppBundle\Entity\Projects;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;



class ProjectsController extends Controller
{

    public function indexAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('welcome/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..'),
        ]);
       /* $number = rand(0, 100);

        return new Response(
            '<html><body>Lucky number: '.$number.'</body></html>'
        );*/
    }
    public function addAction(Request $request)
    {
        // replace this example code with whatever you need
        $project = new Projects();
        $form = $this->createFormBuilder($project)
            ->add('name', TextType::class, array('attr' => array('placeholder' => 'Name','class'=>'form-control')))
            ->add('price', IntegerType::class, array('attr' => array('placeholder' => 'Price','class'=>'form-control')))
            ->add('description', TextareaType::class, array('attr' => array('placeholder' => 'Description','class'=>'form-control')))
            ->add('save', SubmitType::class, array('label' => 'Create Project','attr' => array('placeholder' => 'Description','class'=>'btn btn-lg btn-success btn-block')))
            ->getForm();
        $form->handleRequest($request);
        if($form->isSubmitted())
        {
            if(!$form->isValid())
            {
                $this->addFlash(
                    'error',
                    'Your changes were saved!'
                );
            }
            else {
                // ... perform some action, such as saving the task to the database
                $em = $this->getDoctrine()->getManager();
                $em->persist($project);
                $em->flush();

                //$session = $this->getRequest()->getSession();
                //$session->getFlashBag()->add('message', 'Article saved!');
                return $this->redirect($this->generateUrl('addproject'));
                //return $this->redirectToRoute('task_success');
            }
        }
        return $this->render('projects/add.html.twig', array(
            'form' => $form->createView(),
        ));
    }
    public function editAction($projectId,Request $request)
    {
        // replace this example code with whatever you need
        //$project = new Projects();
        /*$repository = $this->getDoctrine()->getRepository('AppBundle:Projects');
        $project = $repository->find($projectId);*/
        $em = $this->getDoctrine()->getManager();
        $project = $em->getRepository('AppBundle:Projects')->find($projectId);
        if (!$project) {
          throw $this->createNotFoundException(
                  'No news found for id ' . $id
          );
        }

        //print_r($project);die;
        $form = $this->createFormBuilder($project)
            //->add('id', HiddenType::class)
            ->add('name', TextType::class, array('attr' => array('placeholder' => 'Name','class'=>'form-control')))
            ->add('price', IntegerType::class, array('attr' => array('placeholder' => 'Price','class'=>'form-control')))
            ->add('description', TextareaType::class, array('attr' => array('placeholder' => 'Description','class'=>'form-control')))
            ->add('save', SubmitType::class, array('label' => 'Create Project','attr' => array('placeholder' => 'Description','class'=>'btn btn-lg btn-success btn-block')))
            ->getForm();
        //$project->removeId();
         $form->handleRequest($request);
        // die;
        if ($form->isSubmitted() && $form->isValid()) {
            // ... perform some action, such as saving the task to the database
            /*$em = $this->getDoctrine()->getManager();
            $em->persist($project);*/
            $em->flush();

            //$session = $this->getRequest()->getSession();
            //$session->getFlashBag()->add('message', 'Article saved!');
            $this->addFlash(
                'success',
                'Your changes were saved!'
            );
            return $this->redirect($this->generateUrl('listproject'));
            //return $this->redirectToRoute('task_success');
        }

        return $this->render('projects/edit.html.twig', array(
            'form' => $form->createView(),
        ));
    }
    public function listAction($page)
    {
        $em = $this->getDoctrine()->getManager();
        $projects = $em->getRepository('AppBundle:Projects')->findAll();
        if (!$projects) {
            throw $this->createNotFoundException('No news found');
        }
        $build['projects'] = $projects;
        return $this->render('projects/list.html.twig', $build);
    }
    public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $project = $em->getRepository('AppBundle:Projects')->find($id);
        if (!$project) {
          throw $this->createNotFoundException(
                  'No project found for id ' . $id
          );
        }

        $form = $this->createFormBuilder($project)
                ->add('delete', 'submit')
                ->getForm();

        $form->handleRequest($request);

        if ($form->isValid()) {
          $em->remove($project);
          $em->flush();
          return $this->redirect($this->generateUrl('listproject'));
        }
        return $this->redirect($this->generateUrl('listproject'));
    }
}