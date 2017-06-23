<?php

namespace com\BackEndBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class DefaultController extends Controller {

    public function indexAction(Request $request) {
        $error_login="";
        if ($request->getMethod() == "POST") {
            $email = $request->get('email');
            $pass = $request->get('password');

            $em = $this->getDoctrine()->getManager();
            $user = $em->getRepository('BackEndBundle:User')
                    ->findOneBy(array('user' => $email, 'pass' => $pass));

            if ($user != null) {
                // Logueamos al usuario
                $token = new UsernamePasswordToken($user->getUsername(), $pass, 'user_db', $user->getRoles());
                $this->container->get('security.token_storage')->setToken($token);

                return $this->redirect($this->generateUrl("metronic_homepage"));
            }
            $error_login= "No existe el usuario";
        }
        return $this->render('BackEndBundle:Default:index.html.twig',array(
            'error_login'=>$error_login
        ));
    }

    public function principalAction() {
        return $this->render('BackEndBundle:Category:index.html.twig');
    }

}
