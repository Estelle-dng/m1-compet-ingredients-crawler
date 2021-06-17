<?php

namespace App\Controller;

use App\Traits\SerializerTrait;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Plate;


class PlateController extends AbstractController
{
    use SerializerTrait;

    /**
     * @Route ("/plates", name="plates")
     */
    public function getPlates(): Response
    {
        $repository = $this->getDoctrine()->getRepository(Plate::class);
        $plates = $repository->findAll();
        
        $context = ["groups" => ["plate"]];
        $plates = $this->serializer()->serialize($plates, "json", $context);
        
        return new Response($plates);
    }
}
