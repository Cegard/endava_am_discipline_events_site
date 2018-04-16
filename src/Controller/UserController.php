<?php
namespace App\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Person;
use App\Entity\Event;


class UserController extends Controller{
	
	
	public function setPersonFieldsThroughtPOST($person, $request){
		$person->setName($request->get("name"));
		$person->setLastName($request->get("lastName"));
		$person->setPhone($request->get("phone"));
		$person->setEmail($request->get("email"));
	}
	
	
	public function checkEmailAviability($password){
		$user = $this->getDoctrine()
					->getRepository(Person::class)
					->findOneBy([
						"email" => $password
					]);
		
		return $user == null;
	}
	
	
	public function signIn(SessionInterface $session, Request $request, UserPasswordEncoderInterface $encoder){
		$message = "";
		$events = [];
		$route = "home";
		
		if (!$session->has("loguedUser") && $request->getMethod() == "POST"){
			$entity = $this->getDoctrine()->getManager();
			$loguedUser = $this->getDoctrine()
					->getRepository(Person::class)
					->findOneBy([
						"email" => $request->request->get("email"),
						"password" => $request->request->get("pass")
					]);
			
			if ($loguedUser->getActiveState()){
				$session->set("loguedUserId", $loguedUser->getId());
				$session->set("loguedUserName", $loguedUser->getName());
				$session->set("loguedUserRole", $loguedUser->getRole());
				$events = $loguedUser->getCreatedEvents();
			}
			
			else
				$message = "There was an error";
		}
		
		return $this->redirectToRoute($route, array(
			"message" => $message,
			"events" => $events
		));
	}
	
	
	public function registerUser(Request $request){
		$message = "user registered, now log in";
		
		if ($this->checkEmailAviability($request->request->get("email"))){
			$entity = $this->getDoctrine()->getManager();
			$newPerson = new Person();
			$this->setPersonFieldsThroughtPOST($newPerson, $request->request);
			$newPerson->setPassword($request->request->get("pass"));
			$entity->persist($newPerson);
			$entity->flush();
		}
		
		else
			$message = "that email exists";
		
		return $this->redirectToRoute("home", array(
			"message" => $message
		));
	}
	
	
	public function updateUser(SessionInterface $session, Request $request){
		$message = "profile updated";
		$entity = $this->getDoctrine()->getManager();
		$userId = $request->request->get("userId", $session->get("loguedUserId"));
		$user = $this->getDoctrine()->getManager()
					->getRepository(Person::class)
					->findOneBy([
						"id" => $userId
					]);
		
		if ($user->getEmail() == $request->request->get("email") ||
					$this->checkEmailAviability($request->request->get("email"))){
			$this->setPersonFieldsThroughtPOST($user, $request->request);
			
			if ($request->has("pass"))
				$person->setPassword($request->get("pass"));
			
			$entity->persist($user);
			$entity->flush();
			$session->set("loguedUserName", $user->getName());
		}
		
		else
			$message = "that email exists";
		
		return $this->redirectToRoute("home", array(
			"message" => $message
		));
	}
	
	
	public function changeUserStatus(Request $request){
		$entity = $this->getDoctrine()->getManager();
		$userId = $request->request->get("userId", "");
		$user = $this->getDoctrine()->getManager()
					->getRepository(Person::class)
					->findOneBy([
						"id" => $userId
					]);
		$user->changeActiveState();
		
		$entity->persist($user);
		$entity->flush($user);
		
		return $this->redirectToRoute("home", array(
			"message" => "user updated"
		));
	}
}