<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Person;
use App\Entity\Event;


class EventController extends Controller{
    
    
    public function createEvent(SessionInterface $session, Request $request){
		$entity = $this->getDoctrine()->getManager();
		$loguedUser = $this->getDoctrine()->getManager()
					->getRepository(Person::class)
					->findOneBy([
						"id" => $session->get("loguedUserId")
					]);
        
		$newEvent = new Event();
        $newEvent->setName($request->request->get("name"));
        $newEvent->setAddress($request->request->get("address"));
        $newEvent->setCategory($request->request->get("category"));
        $newEvent->setDescription($request->request->get("desc"));
        $newEvent->setStartDate(new \DateTime($request->request->get("startDate")));
        $newEvent->setEndDate(new \DateTime($request->request->get("endDate")));
        $newEvent->setIsVirtual(
                ($request->request->get("pressence") == "presential")? false : true
        );
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
        
        $pickedEvent->setName($request->request->get("name"));
        $pickedEvent->setAddress($request->request->get("address"));
        $pickedEvent->setCategory($request->request->get("category"));
        $pickedEvent->setDescription($request->request->get("desc"));
        $pickedEvent->setStartDate(new \DateTime($request->request->get("startDate")));
        $pickedEvent->setEndDate(new \DateTime($request->request->get("endDate")));
        $pickedEvent->setIsVirtual(
                ($request->request->get("pressence") == "presential")? false : true
        );
		
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