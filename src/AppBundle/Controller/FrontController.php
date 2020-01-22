<?php

namespace AppBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest; // alias pour toutes les annotations
use AppBundle\Form\Type\PlaceType;
use AppBundle\Entity\Place;
use Symfony\Component\Routing\Annotation\Route;

class FrontController extends Controller
{

    /**
     * @Route("/front/show", name="front_show")
     */
    public function showAction()
    {
        return $this->render('place/show.html.twig');
    }
		

}