<?php
namespace App\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Person;


class HomeController extends Controller{
	
	
	public function renderHome(SessionInterface $session, Request $request){
		$page = null;
		
		if ($session->has("loguedUserId")){
			$loguedUser = $this->getDoctrine()->getManager()
					->getRepository(Person::class)
					->findOneBy([
						"id" => $session->get("loguedUserId")
					]);
			
			$page = $this->render("home.html.twig", array(
					"userName" => $session->get("loguedUserName"),
					"message" => $request->query->get("message", ""),
					"events" => $loguedUser->getCreatedEvents()));
		}
		
		else
			$page = $this->render("signIn.html.twig", array(
					"message" => $request->query->get("message")
				));
		
		return $page;
	}
	
	
	public function renderSignUp(SessionInterface $session){
		$loguedUserId = $session->get("loguedUserId", "");
		return $this->render("user/signUp.html.twig", array(
					"loguedUserId" => $loguedUserId
				));
	}
	
	
	public function renderEventCreation(){
		return $this->render("event/newEvent.html.twig");
	}
}