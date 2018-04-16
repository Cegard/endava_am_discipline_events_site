<?php
namespace App\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Person;
use App\Entity\Event;


class HomeController extends Controller{
	
	
	public function renderHome(SessionInterface $session, Request $request){
		$page = null;
		
		if ($session->has("loguedUserId")){
			$loguedUser = $this->getDoctrine()->getManager()
					->getRepository(Person::class)
					->findOneBy([
						"id" => $session->get("loguedUserId")
					]);
			
			$route = ($loguedUser->getRole() == "admin")? "user/admin.html.twig" :
						"home.html.twig";
			
			$page = $this->render($route, array(
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
	
	
	public function renderSignUp(SessionInterface $session, Request $request){
		
		return $this->render("user/signUp.html.twig", array(
			"loguedUserId" => $request->request->get("userId",
													 $session->get("loguedUserId", "")),
			"isOwner" => !($request->request->has("userId"))
		));
	}
	
	
	public function renderEventCreation(Request $request){
		
		return $this->render("event/newEvent.html.twig", array(
			"eventId" => $request->query->get("eventId", "")
		));
	}
	
	
	public function renderAdminUsersPage(SessionInterface $session){
		$page = null;
		
		if ($session->get("loguedUserRole", "X") == "admin"){
			$users = $this->getDoctrine()->getRepository(Person::class)->findAll();
			$page = $this->render("user/usersList.html.twig", array(
				"users" => $users
			));
		}
		
		else
			$page = $this->redirectToRoute("home", array(
				"message" => "you're not admin"
			));
		
		return $page;
	}
	
	
	public function renderAdminEventsPage(SessionInterface $session){
		$page = null;
		
		if ($session->get("loguedUserRole", "X") == "admin"){
			$events = $this->getDoctrine()->getRepository(Event::class)->findAll();
			$page = $this->render("event/eventsList.html.twig", array(
				"events" => $events
			));
		}
		
		else
			$page = $this->redirectToRoute("home", array(
				"message" => "you're not admin"
			));
		
		return $page;
	}
}