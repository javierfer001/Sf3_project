<?php

namespace com\MetronicBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('MetronicBundle:Default:index.html.twig');
    }
}
