<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Ingredient;

class IngredientController extends AbstractController
{

    /**
     * @Route("/ingredients", name="ingredients")
     */
    public function getIngredients(): Response
    {
        $repository = $this->getDoctrine()->getRepository(Ingredient::class);
        $ingredients = $repository->findAll();
        $dd = (array) $ingredients;
        dd($dd);
        return new Response(json_encode($ingredients));
    }

}
