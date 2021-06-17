<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Plate;

class PlateController extends AbstractController
{
    /**
     * @Route ("/plates", name="plates")
     */
    public function getPlates(): Response
    {
        $repository = $this->getDoctrine()->getRepository(Plate::class);
        $plates = $repository->findAll();
        
        $dd = (array) $plates;
        dd($dd);
        
        return new Response(json_encode($plates));
    }
}
