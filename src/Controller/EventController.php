<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Person;
use App\Entity\Event;


class EventController extends Controller{
    
    
    public function setEventFieldsThroughPOST($request, $event){
        $event->setName($request->get("name"));
        $event->setAddress($request->get("address"));
        $event->setCategory($request->get("category"));
        $event->setDescription($request->get("desc"));
        $event->setStartDate(new \DateTime($request->get("startDate")));
        $event->setEndDate(new \DateTime($request->get("endDate")));
        $event->setIsVirtual(
                ($request->get("pressence") == "presential")? false : true
        );
    }
    
    
    public function createEvent(SessionInterface $session, Request $request){
		$entity = $this->getDoctrine()->getManager();
		$loguedUser = $this->getDoctrine()->getManager()
					->getRepository(Person::class)
					->findOneBy([
						"id" => $session->get("loguedUserId")
					]);
        $newEvent = new Event();
        $this->setEventFieldsThroughPOST($request->request, $newEvent);
        $newEvent->setOwner($loguedUser);
        $loguedUser->addCreatedEvent($newEvent);
		
		$entity->persist($newEvent);
        $entity->persist($loguedUser);
		$entity->flush();
		
		return $this->redirectToRoute("home", array(
                    "message" => "event created"
                ));
    }
    
    
    public function editEvent(Request $request){
		$entity = $this->getDoctrine()->getManager();
		$pickedEvent = $this->getEvent($request->request->get("eventId", ""));
        $this->setEventFieldsThroughPOST($request->request, $pickedEvent);
		
		$entity->persist($pickedEvent);
		$entity->flush();
		
		return $this->redirectToRoute("home", array(
                "message" => "event edited"
        ));
    }
    
    
    public function getEvent($eventId): Event{
		
        return $this->getDoctrine()->getManager()
					->getRepository(Event::class)
					->findOneBy([
						"id" => $eventId
					]);
    }
    
    
    public function removeEvent(Request $request){
        $event = $this->getEvent($request->request->get("eventId", ""));
		$entity = $this->getDoctrine()->getManager();
        $entity->remove($event);
        $entity->flush();
		
		return $this->redirectToRoute("home", array(
                    "message" => "event removed"
                ));
    }
}