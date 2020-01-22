<?php

namespace AppBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest; // alias pour toutes les annotations
use AppBundle\Form\Type\PlaceType;
use AppBundle\Entity\Place;


class PlaceController extends Controller
{


	/**
     * @Rest\View(serializerGroups={"place"})
     * @Rest\Get("/places")
     */
    public function getPlacesAction()
    {
        $places= $this->getDoctrine()->getRepository(Place::class)->findAll();
        
        return $places;
    }

	/**
	 * @Rest\View(serializerGroups={"place"}, serializerGroups={"place"})
     * @Rest\Get("/places/{id}")
     */
	public function getPlaceAction($id)
	{
		$place= $this->getDoctrine()->getRepository(Place::class)->find($id);
		 if (empty($place)) {
            return \FOS\RestBundle\View\View::create(['message' => 'Place not found'], Response::HTTP_NOT_FOUND);
        }
		
		return $place;
	}	
	
	/**
     * @Rest\View(statusCode=Response::HTTP_CREATED,  serializerGroups={"place"})
     * @Rest\Post("/places")
     */
    public function postPlacesAction(Request $request)
    {
        $place = new Place();
        $form = $this->createForm(PlaceType::class, $place);

        $form->submit($request->request->all()); 

        if ($form->isValid()) {
            $em = $this->get('doctrine.orm.entity_manager');
            foreach ($place->getPrices() as $price) {
                $price->setPlace($place);
                $em->persist($price);
            }
            $em->persist($place);
            $em->flush();
            return $place;
        } else {
            return $form;
        }
    }


     /**
     * @Rest\View(statusCode=Response::HTTP_NO_CONTENT, serializerGroups={"place"})
     * @Rest\Delete("/places/{id}")
     */
    public function removePlaceAction(Request $request)
    {
        $em = $this->get('doctrine.orm.entity_manager');
        $place = $em->getRepository('AppBundle:Place')
                    ->find($request->get('id'));
        /* @var $place Place */

        if (!$place) {
            return;
        }

        foreach ($place->getPrices() as $price) {
            $em->remove($price);
        }
        $em->remove($place);
        $em->flush();
        
    }

    /**
     * @Rest\View(serializerGroups={"place"})
     * @Rest\Put("/places/{id}")
     */
    public function putPlacesAction(Request $request)
    {
        return $this->updatePlace($request, true);
    }

    /**
     * @Rest\View(serializerGroups={"place"})
     * @Rest\Patch("/places/{id}")
     */
    public function patchPlacesAction(Request $request)
    {
        return $this->updatePlace($request, false);
    }


    private function updatePlace(Request $request, $clearMissing)
    {
        $place = $this->get('doctrine.orm.entity_manager')
                ->getRepository('AppBundle:Place')
                ->find($request->get('id')); // L'identifiant en tant que paramètre n'est plus nécessaire
        /* @var $place Place */

        if (empty($place)) {
            return \FOS\RestBundle\View\View::create(['message' => 'Place not found'], Response::HTTP_NOT_FOUND);
        }

        $form = $this->createForm(PlaceType::class, $place);

        // Le paramètre false dit à Symfony de garder les valeurs dans notre       
        $form->submit($request->request->all(), $clearMissing);

        if ($form->isValid()) {
            $em = $this->get('doctrine.orm.entity_manager');
            $em->persist($place);
            $em->flush();
            return $place;
        } else {
            return $form;
        }
    }
		

}