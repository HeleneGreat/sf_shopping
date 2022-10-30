<?php

namespace App\Controller;

use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Product;
use App\Entity\Category;
use App\Entity\Comment;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\Type\CommentType;
use App\Form\Type\ProductType;


class ProductController extends AbstractController
{

    // List of all products (really slow)
    #[Route('/product', name: 'app_product')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $products = $doctrine->getRepository(Product::class)->findAll();
        return $this->render('category/cat-products.html.twig', [
            'pageTitle' => "Tous les produits",
            "products" => $products,
            'catname' => 'Tous les produits',
        ]);
    }


    // A product's details and comments
    #[Route('/product/{productId}', name: 'one_product', requirements:['productId' => '\d+'])]
    public function oneProduct(ManagerRegistry $doctrine, int $productId, Request $request): Response
    {
        // Product's properties
        $allCategories = $doctrine->getRepository(Category::class)->findAll();
        $product = $doctrine->getRepository(Product::class)->find($productId);
        if(!$product){
            throw $this->createNotFoundException(
                'No product found for id ' . $productId
            );
        }
        // Product's comments
        $allComments = $doctrine->getRepository(Comment::class)->findBy(['product_id' => $productId], ['id' => 'DESC']);
        // Save new comment
        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $this->newComment($doctrine, $comment, $form, $productId);
            return $this->redirectToRoute('one_product', ['productId' => $productId]);
        }
        // Render view
        return $this->renderForm('product/one-product.html.twig', [
            'allCategories' => $allCategories,
            'productId'=> $productId,
            'name' => $product->getName(),
            'image' => $product->getImage(),
            'description' => $product->getDescription(),
            'pageTitle' => $product->getName(),
            'commentForm' => $form,
            'allComments' => $allComments,
        ]);
    }

    // Add a new comment
    public function newComment(ManagerRegistry $doctrine, $comment, $form, $productId){
        $comment = $form->getData();
        $product = $doctrine->getRepository(Product::class)->find($productId);
        $comment->setProductId($product);
        $comment->setDate(new DateTime("now"));
        $entityManager = $doctrine->getManager();
        $entityManager->persist($comment);
        $entityManager->flush();
    }

    // Form to modify a product
    #[Route('/product/modify/{productId}', name: 'modify_product', requirements:['productId' => '\d+'])]
    public function modifyProduct(ManagerRegistry $doctrine, int $productId, Request $request): Response
    {
        $allCategories = $doctrine->getRepository(Category::class)->findAll();
        $product = $doctrine->getRepository(Product::class)->find($productId);
        if(!$product){
            throw $this->createNotFoundException(
                'No product found for id ' . $productId
            );
        }
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $doctrine->getManager()->flush();
            return $this->redirectToRoute('one_product', ['productId' => $productId]);
        }
        
        return $this->renderForm('product/modify-product.html.twig', [
            'allCategories' => $allCategories,
            'name' => $product->getName(),
            'image' => $product->getImage(),
            'description' => $product->getDescription(),
            'pageTitle' => $product->getName(),
            'productForm' => $form,
        ]);
    }

    // Form to create a new product
    #[Route('/product/create', name: 'create_product', requirements:['create' => 'a-zA-Z'])]
    public function createProduct(ManagerRegistry $doctrine, Request $request):Response
    {
        $allCategories = $doctrine->getRepository(Category::class)->findAll();
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $product = $form->getData();
            $entityManager = $doctrine->getManager();
            $entityManager->persist($product);
            $entityManager->flush();
            return $this->redirectToRoute('home');
        }
        
        return $this->renderForm('product/create-product.html.twig', [
            'allCategories' => $allCategories,
            'pageTitle' => "Ajouter un nouvel animal",
            'productForm' => $form
        ]);
    }

    // Form to delete a product
    #[Route('/product/delete/{productId}', name: 'delete_product', requirements:['productId' => '\d+'])]
    public function deleteProduct(ManagerRegistry $doctrine, int $productId)
    {
        $product = $doctrine->getRepository(Product::class)->find($productId);
        $entityManager = $doctrine->getManager();
        $entityManager->remove($product);
        $entityManager->flush();

        return $this->redirectToRoute('home');
    }






    // public function createProduct(ManagerRegistry $doctrine):Response
    // {
    //     $entityManager = $doctrine->getManager();
    //     $category = $doctrine->getRepository(Category::class)->find('2');
    //     $product = new Product();
    //     $product->setCategoryId($category);
    //     $product->setName('Moustache');
    //     $product->setPrice(32);
    //     $product->setDescription('Chat de rue, aime sa liberté.');
    //     $product->setShipping(4.5);
    //     $product->setImage('https://cdn.pixabay.com/photo/2016/05/07/20/49/cat-1378184_960_720.jpg');
    //     // tell Doctrine you want to (eventually) save the Product (no queries yet)
    //     $entityManager->persist($product);
    //     // actually executes the queries (i.e. the INSERT query)
    //     $entityManager->flush();
    //     return new Response('Saved new product with id '.$product->getId());
        
    //     // $dog = $doctrine->getRepository(Product::class)->find('1');
    //     // var_dump($dog->getCategoryId());die;
    //     // return $dog;
    // }

}
