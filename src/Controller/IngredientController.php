<?php

namespace App\Controller;

use App\Traits\SerializerTrait;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Entity\Ingredient;

class IngredientController extends AbstractController
{
    use SerializerTrait;
    
    /**
     * @Route("/ingredients", name="ingredients")
     */
    public function getIngredients(): Response
    {
        $repository = $this->getDoctrine()->getRepository(Ingredient::class);
        $ingredients = $repository->findAll();
        
        $context = ["groups" => ["ingredient"]];
        $ingredients = $this->serializer()->serialize($ingredients, "json", $context);

        return new Response($ingredients);
    }

}
