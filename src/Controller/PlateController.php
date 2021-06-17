<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Plate;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Doctrine\Common\Annotations\AnnotationReader;
use Symfony\Component\Serializer\Mapping\Loader\AnnotationLoader;


class PlateController extends AbstractController
{
    /**
     * @Route ("/plates", name="plates")
     */
    public function getPlates(): Response
    {
        $repository = $this->getDoctrine()->getRepository(Plate::class);
        $plates = $repository->findAll();
        
        
        $classMetadataFactory = new ClassMetadataFactory(new AnnotationLoader(new AnnotationReader()));
        $jsonEncoder = new JsonEncoder();
        $objectNormalizer = new ObjectNormalizer($classMetadataFactory);
        $serializer = new Serializer([$objectNormalizer], [$jsonEncoder]);
        $context = ["groups" => ["plate"]];
        $plates = $serializer->serialize($plates, "json", $context);

        dd($plates);
        
        return new Response($plates);
    }
}
