<?php

namespace App\Controller;

use App\Entity\Plate;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class PlateController extends AbstractController
{
    /**
     * @Route("/plate", name="plate")
     */
    public function index(SerializerInterface $serializer): Response
    {
        //Serializer

        $em = $this->getDoctrine()->getRepository(Plate::class);
        $all = $em->findAll();
        $allJson = $serializer->serialize($all, 'json', ['groups' => ['plate','circular_reference_handler']]);
        
        return new Response($allJson);
    }
}