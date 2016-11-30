<?php

namespace UrlBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use UrlBundle\Entity\Link;
use UrlBundle\Entity\View;
use UrlBundle\Form\LinkType;
use UrlBundle\Services\Base62Services;
use Symfony\Component\HttpFoundation\Request;
use UrlBundle\Controller\LinksController;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('UrlBundle:Default:index.html.twig');
    }
    
   /*
    * @Route("/pageviews", name="page_views")
    */ 
    public function pageAction()
    {
    	return $this->render('UrlBundle:Default:page.html.twig');
    }
    

    
    /*
     * @Route("/l/{short_url}", name="redirectpage")
     */
    public function redirectAction($short_url)
    {
    	$referrer = "none";
    	if(isset($_SERVER['HTTP_CLIENT_IP'])) {
    		$referrer = $_SERVER['HTTP_CLIENT_IP'];
    	}
    
    	$em = $this->getDoctrine()->getManager();
    
    	$val = $em->getRepository('UrlBundle:Link')->findBy(array('shortUrl' => $short_url));
    	//	$val = $this->get('crv.doctrine_entity_repository.link')->createFindOneByShortUrlQuery($short_url)->getSingleResult();
    	//$longUrl = $val['longUrl'];
    	//return $this->redirect("http://www.google.com");
    	$longUrl = $val[0]->getLongUrl();
    	$token = $val[0]->getToken();
    	//	str_replace('"', "", $url);
    
    	$id = $val[0]->getId();
    	$this->storeView($val[0], $referrer, $token);
    		
    	return $this->redirect("https://".$longUrl);
    
    }
    
    private function storeView($link, $referrer, $token)
    {
    	$view1 = new View();
    	$view1->setLink($link);
    	$view1->setIpAddress($referrer);
    	$view1->setCreatedAt(0);
    	$view1->setToken($token);
    	$em = $this->getDoctrine()->getManager();
    	$em->persist($view1);
    	$em->flush();
    }
}
