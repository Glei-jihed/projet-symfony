<?php

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Product;
use App\Form\ProductType;
use App\Form\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\String\Slugger\SluggerInterface;

class UpdateController extends AbstractController
{   
    #[IsGranted('ROLE_ADMIN')]
    #[Route('/update/{id}', name: 'app_update')]
    public function update(ManagerRegistry $doctrine,Request $request,$id,product $product = null,SluggerInterface $slugger): Response
    {   
        if(!$product){
            $product = new product();

        }
        //$product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            //dump($product);
            $entityManager = $doctrine->getManager();
            $entityManager->persist($product);
            $entityManager->flush();
            return $this->redirectToRoute('page_admin');
            
            

        } else {
                return $this->render('/update/update.html.twig',[
                'form'=> $form->createView()

                ]);

        }
        
        

        return $this->render('/update/update.html.twig', [
            'form' =>$form->createView()   
           
        ]);
    }
}

