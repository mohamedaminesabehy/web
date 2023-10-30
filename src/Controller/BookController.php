<?php

namespace App\Controller;
use App\Repository\BookRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;


use App\Entity\Book;
use App\Form\BookType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BookController extends AbstractController
{
    #[Route('/book', name: 'app_book')]
    public function index(): Response
    {
        return $this->render('book/index.html.twig', [
            'controller_name' => 'BookController',
        ]);
    }

    #[Route('/booka', name: 'addBook')]
    public function addBook(ManagerRegistry $mr,BookRepository $repo, Request $req): Response
    {
        $p=new Book();
        $form=$this->createForm(BookType::class,$p);
        $form->add('Ajouter',SubmitType::class);
        $form->handleRequest($req);
                if ($form->isSubmitted() ) { 
                    $p->setPulished(true);
                    $em=$mr->getManager();
                    $em->persist($p);
                    $em->flush();
        }
        /*$category=$repo->find(1);
        $p->setRef(234);
        $p->setPrice('56');
        $p->setDescription('product1');
        $p->setCatgory($category);*/

        return $this->renderForm('book/add.html.twig',[
            'f'=>$form,
        ]);
    }

    #[Route('/editbook/{ref}', name: 'editBook')]
public function editBook($ref, ManagerRegistry $mr, BookRepository $repo, Request $req): Response
{
    // Récupérez le livre à partir du référencement (ID) fourni
    $book = $repo->find($ref);
    // Créez un formulaire pour l'édition du livre
    $form = $this->createForm(BookType::class, $book);
    $form->add('edit', SubmitType::class);
    // Gérez la soumission du formulaire
    $form->handleRequest($req);
    if ($form->isSubmitted()) {
        $em = $mr->getManager();
        $em->flush();
    }
    return $this->renderForm('book/edit.html.twig', [
        'f' => $form,
    ]);
}



    #[Route('/fetch', name: 'show')]
    public function fetch(BookRepository $repo): Response
    { 
       // return $this->render('book/list.html.twig',[
       //'book'=>$repo->findAll()
       // ]);
       $books = $repo->booksListByAuthors();
    return $this->render('book/list.html.twig', [
        'books' => $books,
    ]);
    }


    #[Route('/deletebook/{ref}', name: 'delete')]
   public function deletebook($ref, ManagerRegistry $mr,BookRepository $repo ){
   $em=$mr->getManager(); 
   $a=$repo->find($ref);
   $em->remove($a);
   $em->flush();
   return new Response('removed');
   }


   #[Route('/ShowBook/{ref}', name: 'app_detailBook')]

   public function showBook($ref, BookRepository $repository)
   {
       $book = $repository->find($ref);
       if (!$book) {
           return $this->redirectToRoute('app_AfficheBook');
       }
       return $this->render('book/show.html.twig', ['b' => $book]);

}

#[Route('/fetch', name: 'show')]
public function publicprive(BookRepository $repo): Response
{ 
    
    $books = $repo->findAll();
    $publishedCount = 0;
    $notPublishedCount = 0;
    foreach ($books as $book) {
        if ($book->isPulished()) {
            $publishedCount++;
        } else {
            $notPublishedCount++;
        }
    }
    return $this->render('book/list.html.twig', [
        'book' => $books,
        'publishedCount' => $publishedCount,
        'notPublishedCount' => $notPublishedCount,
    ]);
}
#[Route('/search', name: 'search_book')]
public function searchBook(Request $request, BookRepository $repo): Response
{
    $searchTerm = $request->query->get('search');
    
    if ($searchTerm) {
        $books = $repo->searchBookByRef($searchTerm);
    } else {
        $books = $repo->findAll();
    }

    return $this->render('book/list.html.twig', [
        'book' => $books,
    ]);
}



}
