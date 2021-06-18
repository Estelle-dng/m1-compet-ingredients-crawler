<?php

namespace App\Controller;

use App\Entity\Ingredient;
use App\Entity\Plate;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;

class IngredientController extends AbstractController
{
    /**
     * @Route("/ingredient", name="ingredient")
     */
  
    public function getAllIngredients(SerializerInterface $serializer): Response
    {
        //Serializer
        $em = $this->getDoctrine()->getRepository(Ingredient::class);
        $all = $em->getAllIngWithPlates();
        
        $allJson = $serializer->serialize($all, 'json', ['groups' => ['ing', 'circular_reference_handler'] ]);
        
        return new Response($allJson);
    }

    /**
     * @Route("/ingredient/{id}/plate", name="ingredientplate")
     */
    public function getPlateByIngredients($id, SerializerInterface $serializer) : Response {
        $em = $this->getDoctrine()->getRepository(Plate::class);
        $plateById = $em->getPlateByIndredients($id);
        $allJson = $serializer->serialize($plateById, 'json', ['groups' => ['plate', 'circular_reference_handler'] ]);
        return new Response($allJson);
    }

    /**
     * @Route("/ingredient/main", name="ingredientmain")
     */
    public function getMainIngredient(SerializerInterface $serializer) : Response {
        $em = $this->getDoctrine()->getRepository(Ingredient::class);
        $plateById = $em->findOnlyMain();
        $allJson = $serializer->serialize($plateById, 'json', ['groups' => ['ing', 'circular_reference_handler'] ]);
        return new Response($allJson);
    }
}