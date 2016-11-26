<?php

namespace UrlBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('UrlBundle:Default:index.html.twig');
    }
}
