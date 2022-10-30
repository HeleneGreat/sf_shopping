<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Category;
use App\Entity\Product;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{

    #[Route('/home', name: 'home')]
    public function home(ManagerRegistry $doctrine): Response
    {

        $allCategories = $doctrine->getRepository(Category::class)->findAll();
        $imgCarousel = $doctrine->getRepository(Product::class)->findBy([], ['id' => 'DESC'], 5, 0);;
        return $this->render('home.html.twig', [
            // 'allCategories' => $allCategories,
            'pageTitle' => "Bienvenue",
            'imgCarousel' => $imgCarousel,
            'allCategories' => $allCategories,
        ]);
    }

    // Function for variables called in all pages


}
