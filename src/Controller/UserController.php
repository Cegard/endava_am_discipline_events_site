<?php
namespace App\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Person;


class UserController extends Controller{
	
	
	public function signIn(SessionInterface $session, Request $request){
		$message = "";
		
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
			}
			
			else
				$message = "There was an error";
		}
		
		return $this->redirectToRoute("home", array(
			"message" => $message
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
}