<?php
namespace App\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Response;


class HomeController extends Controller{
	
	
	public function renderHome(SessionInterface $session){
		return ($session->has("loguedUserId"))? $this->render("home.html.twig", array(
					"userName" => $session->get("loguedUserName"))) :
				$this->render("signIn.html.twig");
	}
	
	
	public function renderSignUp(){
		return $this->render("user/signUp.html.twig");
	}
}