<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Category;
use App\Entity\Product;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{

    #[Route('/home', name: 'home')]
    public function home(ManagerRegistry $doctrine): Response
    {
        $allCategories = $doctrine->getRepository(Category::class)->findAll();
        return $this->render('home.html.twig', [
            'allCategories' => $allCategories,
            'pageTitle' => "Bienvenue",
        ]);
    }


    
    #[Route('/category/{catId}', name: 'category_products')]
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



    #[Route('/category/init', name: 'create_category')]
    public function createCategory(ManagerRegistry $doctrine):Response
    {
        $entityManager = $doctrine->getManager();
        $category = new Category();
        $category->setName('');
        $category->setImage('');
        // tell Doctrine you want to (eventually) save the Category (no queries yet)
        $entityManager->persist($category);
        // actually executes the queries (i.e. the INSERT query)
        $entityManager->flush();
        return new Response('Saved new category with id '.$category->getId());
    }

}
