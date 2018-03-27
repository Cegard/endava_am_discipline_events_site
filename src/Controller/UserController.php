<?php
namespace App\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Request;


class UserController extends Controller{
	
	
	public function logIn(SessionInterface $session, Request $request){
		
		if (!$session->has("loguedUser") && $request->getMethod() == "POST"){
			$session->set("loguedUser", $request->request->get("signInEmail"));
		}
		
		return $this->redirectToRoute("home");
	}
}