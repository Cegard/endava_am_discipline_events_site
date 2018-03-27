<?php
namespace App\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Response;


class HomeController extends Controller{
	
	
	public function renderHome(SessionInterface $session){
		$page = ($session->has("loguedUser"))? $this->render("home.html.twig", array(
					"userName" => $session->get("loguedUser"))) :
				$this->render("signIn.html.twig");
		
		return $page;
	}
}