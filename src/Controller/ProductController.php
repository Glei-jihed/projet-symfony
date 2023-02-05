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
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use App\Service\MailerService;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class ProductController extends AbstractController
{  
    #[IsGranted('ROLE_ADMIN')]
    #[Route('/admin', name: 'page_admin')]
    public function admin(ManagerRegistry $doctrine):Response{
        $repository = $doctrine->getRepository(Product::class);
        $product = $repository->findAll(['Name'=>"ASC"]);
        return $this->render('admin.html.twig',[
            'product'=>$product
        ]);
        

    }
    
    #[Route('/delete/{id}', name: 'product_delete')]
    public function delete(ManagerRegistry $doctrine,$id):RedirectResponse{
        //récupérer la personne
        $repository = $doctrine->getRepository(Product::class);
        $product = $repository->find($id);

        //si la personne existe
        if($product){
            $entityManager = $doctrine->getManager();
            $entityManager->remove($product);
            $entityManager->flush();
            $this->addFlash(type:'success',message:"product deleted");

        }else{    //si non
            $this->addFlash(type:'error',message:"cannot find the product");


        }
        return $this->redirectToRoute('page_admin');


        

    }
    
    
    
    #[Route('/homepage', name: 'product_list')]
    public function showall(ManagerRegistry $doctrine): Response{
        $repository = $doctrine->getRepository(Product::class);
        $product = $repository->findAll(['Name'=>"ASC"]);
        return $this->render('homepage.html.twig',[
            'product'=>$product

        ]);

    

    } 

    #[Route('/product/{id}', name: 'product_information')]
    public function information(ManagerRegistry $doctrine,$id): Response{
        $repository = $doctrine->getRepository(Product::class);
        $product = $repository->find($id);
        //$product = $repository->findBy(['Name'=>$Name]);

        if(!$product){
            $this->addFlash(type:'error',message:"le produit n'existe pas");
            return $this->redirectToRoute('product_list');

        }
        return $this->render('/product/information.html.twig',[
            'product'=>$product

        ]);

    

    
    } 
    
    #[Route('/new/product', name: 'app_product')]
    public function addProduct(ManagerRegistry $doctrine,Request $request,SluggerInterface $slugger,
        MailerService $mailer,ValidatorInterface $validator,
    ): Response

    {   
        //$entityManager = $doctrine->getManager();
        $product = new Product();
        //$product->setName('violent');

        //$product->setDateCreation(new \DateTime('tomorrow'));
        

        $form = $this->createForm(ProductType::class, $product);
        
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            

            $photo = $form->get('photo')->getData();

            // this condition is needed because the 'brochure' field is not required
            // so the PDF file must be processed only when a file is uploaded
            if ($photo) {
                $originalFilename = pathinfo($photo->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$photo->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $photo->move(
                        $this->getParameter('Product_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $product->setImage($newFilename);
            }
            dump($product);
            $entityManager = $doctrine->getManager();
            $entityManager->persist($product);



            $entityManager->flush();
            if($form->isSubmitted()){
                $message = ' a été ajouté par un admin ';
                $mailMessage = $product->getName().' '.$product->getId().' '.$message;
            }
            $mailer->sendEmail(content: $mailMessage);
            return $this->redirectToRoute('page_admin');
            
            
            //$this->addFlash($product->Name."a été enregistré avec succés");

        } else {
                return $this->render('/product/addProduct.html.twig',[
                'form'=> $form->createView()

                ]);

        }
        
        return $this->render('/product/addProduct.html.twig', [
            'form' =>$form->createView()   
           
        ]);


          
    }
}
