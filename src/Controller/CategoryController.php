<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Category;
use App\Entity\Product;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\Type\CategoryType;

class CategoryController extends AbstractController
{
    
    #[Route('/category/{catId}', name: 'category_products', requirements:['catId' => '\d+'])]
    public function categoryProducts(ManagerRegistry $doctrine, int $catId): Response
    {
        $allCategories = $doctrine->getRepository(Category::class)->findAll();
        $cat = $doctrine->getRepository(Category::class)->find($catId);
        // $products = $doctrine->getRepository(Product::class)->findBy(['category_id_id' => $catId]);
        $products = $doctrine->getRepository(Product::class)->findBy(['categoryId' => $catId]);
        if(!$cat){
            throw $this->createNotFoundException(
                'Aucune catégorie ne correspond au numéro ' . $catId
            );
        }
        return $this->render('category/cat-products.html.twig', [
            'allCategories' => $allCategories,
            'catname' => $cat->getName(),
            'catimage' => $cat->getImage(),
            "products" => $products,
            'pageTitle' => $cat->getName(),
        ]);
    }


    #[Route('/category/create', name: 'create_category', requirements:['create' => 'a-zA-Z'])]
    public function createCategory(ManagerRegistry $doctrine, Request $request):Response
    {
        $allCategories = $doctrine->getRepository(Category::class)->findAll();
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $category = $form->getData();
            $entityManager = $doctrine->getManager();
            $entityManager->persist($category);
            $entityManager->flush();
            return $this->redirectToRoute('home');
        }
        
        return $this->renderForm('category/create-category.html.twig', [
            'allCategories' => $allCategories,
            'pageTitle' => "Ajouter un nouvel animal",
            'categoryForm' => $form
        ]);
    }


    // #[Route('/category/init', name: 'create_category')]
    // public function createCategory(ManagerRegistry $doctrine):Response
    // {
    //     $entityManager = $doctrine->getManager();
    //     $category = new Category();
    //     $category->setName('');
    //     $category->setImage('');
    //     // tell Doctrine you want to (eventually) save the Category (no queries yet)
    //     $entityManager->persist($category);
    //     // actually executes the queries (i.e. the INSERT query)
    //     $entityManager->flush();
    //     return new Response('Saved new category with id '.$category->getId());
    // }

}
