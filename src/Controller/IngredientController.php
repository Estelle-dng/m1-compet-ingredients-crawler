<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Doctrine\Common\Annotations\AnnotationReader;
use Symfony\Component\Serializer\Mapping\Loader\AnnotationLoader;


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
        
        
        $classMetadataFactory = new ClassMetadataFactory(new AnnotationLoader(new AnnotationReader()));
        $jsonEncoder = new JsonEncoder();
        $objectNormalizer = new ObjectNormalizer($classMetadataFactory);
        $serializer = new Serializer([$objectNormalizer], [$jsonEncoder]);
        $context = ["groups" => ["ingredient"]];
        $ingredients = $serializer->serialize($ingredients, "json", $context);

        return new Response($ingredients);
    }

}
