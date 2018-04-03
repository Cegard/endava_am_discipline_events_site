<?php
namespace App\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Person;


class UserController extends Controller{
	
	
	public function signIn(SessionInterface $session, Request $request){
		
		if (!$session->has("loguedUser") && $request->getMethod() == "POST"){
			$session->set("loguedUser", $request->request->get("email"));
		}
		
		return $this->redirectToRoute("home");
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
		
		return $this->redirectToRoute("home");
	}
}