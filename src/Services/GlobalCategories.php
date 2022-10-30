<?php


namespace App\Services;

use App\Entity\Category;
use Doctrine\Persistence\ManagerRegistry;
use \Twig\Extension\GlobalsInterface;

class GlobalCategories extends \Twig\Extension\AbstractExtension
{

    public function getCategories(ManagerRegistry $doctrine)
    {
        $allCategories = $doctrine->getRepository(Category::class)->findAll();
        $this->twig->addGlobal('allCategories', $allCategories);


     
        // return $allCategories;
    }

}

