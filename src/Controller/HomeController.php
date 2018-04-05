<?php
namespace App\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;


class HomeController extends Controller{
	
	
	public function renderHome(SessionInterface $session, Request $request){
		
		return ($session->has("loguedUserId"))? $this->render("home.html.twig", array(
					"userName" => $session->get("loguedUserName"))) :
				$this->render("signIn.html.twig", array(
					"message" => $request->query->get("message")
				));
	}
	
	
	public function renderSignUp(){
		return $this->render("user/signUp.html.twig");
	}
}