<?php
namespace App\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Person;
use App\Entity\Event;


class UserController extends Controller{
	
	
	public function signIn(SessionInterface $session, Request $request){
		$message = "";
		$events = [];
		
		if (!$session->has("loguedUser") && $request->getMethod() == "POST"){
			$entity = $this->getDoctrine()->getManager();
			$loguedUser = $this->getDoctrine()
					->getRepository(Person::class)
					->findOneBy([
						"email" => $request->request->get("email"),
						"password" => $request->request->get("pass")
					]);
			
			if ($loguedUser){
				$session->set("loguedUserId", $loguedUser->getId());
				$session->set("loguedUserName", $loguedUser->getName());
				$events = $loguedUser->getCreatedEvents();
			}
			
			else
				$message = "There was an error";
		}
		
		return $this->redirectToRoute("home", array(
			"message" => $message,
			"events" => $events
		));
	}
	
	
	public function registerUser(Request $request){
		$entity = $this->getDoctrine()->getManager();
		
		$newPerson = new Person();
		$newPerson->setName($request->request->get("name"));
		$newPerson->setLastName($request->request->get("lastName"));
		$newPerson->setPhone($request->request->get("phone"));
		$newPerson->setEmail($request->request->get("email"));
		$newPerson->setPassword($request->request->get("pass"));
		
		$entity->persist($newPerson);
		$entity->flush();
		
		return $this->redirectToRoute("home", array(
			"message" => "user registered, now log in"
		));
	}
	
	
	public function updateUser(SessionInterface $session, Request $request){
		$entity = $this->getDoctrine()->getManager();
		$loguedUserId = $session->get("loguedUserId");
		$loguedUser = $this->getDoctrine()->getManager()
					->getRepository(Person::class)
					->findOneBy([
						"id" => $loguedUserId
					]);
		$loguedUser->setName($request->request->get("name"));
		$loguedUser->setLastName($request->request->get("lastName"));
		$loguedUser->setPhone($request->request->get("phone"));
		$loguedUser->setEmail($request->request->get("email"));
		$loguedUser->setPassword($request->request->get("pass"));
		
		$entity->persist($loguedUser);
		$entity->flush();
		$session->set("loguedUserName", $loguedUser->getName());
		
		return $this->redirectToRoute("home", array(
					"message" => "profile updated"
				));
	}
}