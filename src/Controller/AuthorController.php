<?php

namespace App\Controller;

use App\Entity\Author;
use App\Repository\AuthorRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use App\Form\AuthorType;

class AuthorController extends AbstractController
{
    #[Route('/author', name: 'app_author')]
    public function index(): Response
    {
        return $this->render('author/index.html.twig', [
            'controller_name' => 'AuthorController',
        ]);
    }
    #[Route('/affiche/author', name: 'showauthor')]
    public function fetch(AuthorRepository $repo): Response
    { 
       // return $this->render('author/show.html.twig',[
       //'author'=>$repo->findAll()
        //]);

        $authors = $repo->listAuthorByEmail();
        return $this->render('author/show.html.twig', [
            'author' => $authors,
        ]);
    }

    #[Route('/authora', name: 'addAuthor')]
    public function addBook(ManagerRegistry $mr,AuthorRepository $repo, Request $req): Response
    {
        $p=new Author();
        $form=$this->createForm(AuthorType::class,$p);
        $form->add('Save',SubmitType::class);
        $form->handleRequest($req);
                if ($form->isSubmitted() ) { 
                    $em=$mr->getManager();
                    $em->persist($p);
                    $em->flush();
        }
        /*$category=$repo->find(1);
        $p->setRef(234);
        $p->setPrice('56');
        $p->setDescription('product1');
        $p->setCatgory($category);*/

        return $this->renderForm('author/addauthor.html.twig',[
            'f'=>$form,
        ]);
    }

    #[Route('/editauthor/{id}', name: 'editAuthor')]
public function editauthor($id, ManagerRegistry $mr, AuthorRepository $repo, Request $req): Response
{
    // Récupérez le livre à partir du référencement (ID) fourni
    $author = $repo->find($id);
    // Créez un formulaire pour l'édition du livre
    $form = $this->createForm(AuthorType::class, $author);
    $form->add('edit', SubmitType::class);
    // Gérez la soumission du formulaire
    $form->handleRequest($req);
    if ($form->isSubmitted()) {
        $em = $mr->getManager();
        $em->flush();
    }
    return $this->renderForm('author/edit.html.twig', [
        'f' => $form,
    ]);
}


#[Route('/deleteauthor/{id}', name: 'deleteauthor')]
   public function deleteauthor($id, ManagerRegistry $mr,AuthorRepository $repo ){
   $em=$mr->getManager(); 
   $a=$repo->find($id);
   $em->remove($a);
   $em->flush();
   return new Response('removed');
   }
}
