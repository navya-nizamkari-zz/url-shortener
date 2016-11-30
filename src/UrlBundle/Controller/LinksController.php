<?php
namespace UrlBundle\Controller;

use UrlBundle\Entity\Link;
use UrlBundle\Entity\View;
use UrlBundle\Form\LinkType;
use UrlBundle\Services\Base62Services;
use FOS\RestBundle\Controller\Annotations;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\RouteRedirectView;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Routing\ClassResourceInterface;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use FOS\RestBundle\Controller\Annotations\RouteResource;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * Class LinksController
 * @package UrlBundle\Controller
 * @RouteResource("link")
 */
class LinksController extends FOSRestController implements ClassResourceInterface
{

	/**
	 * 
	 *
	 */
	/**
	 * @Rest\Post("/history", name="getHistory")
	 *
	 */
	public function historyAction(Request $request)
	{
		$em = $this->getDoctrine()->getManager();
		$input = $request->request->all();
		$short_url = $input['short_url'];
		$token = $input['token'];
		$val = $em->createQuery(
				'SELECT v.ip_address, v.created_at FROM UrlBundle:Link l
                 JOIN UrlBundle:View v
                 WITH v.link = l.id
                 WHERE l.shortUrl = :short'
				)->setParameter('short', $short_url)
				->getResult();
		
		$val2 = $em->createQuery(
				'SELECT l.token FROM UrlBundle:Link l
                 WHERE l.shortUrl = :short')->setParameter('short', $short_url)->getSingleResult()['token'];
		
		if(password_verify($token, $val2))
				return $val;
		
				
				
	}
	
	public function indexAction() {
	
		return $this->render('default/index.html.twig');
	}
	
	/**
	 * @Rest\Post("/analytics", name="getAnalytics")
	 * 
	 */
	public function analyticsAction(Request $request)
	{
		
		$em = $this->getDoctrine()->getManager();
		$input = $request->request->all();
		$err_msg_token = " token";
		$err_msg_url = " token";
		$err_msg_url = " short url";
		$short_url = $request->request->get('short_url');
		$token = $request->request->get('token');
		$val = $em->createQuery(
				'SELECT v.ip_address, v.created_at FROM UrlBundle:Link l
                 JOIN UrlBundle:View v
                 WITH v.link = l.id
                 WHERE l.shortUrl = :short'
				)->setParameter('short', $short_url)
				->getResult();
		
				$rows = $em->createQuery(
				'SELECT l.id FROM UrlBundle:Link l
                 WHERE l.shortUrl = :short'
						)->setParameter('short', $short_url)
						->getResult();
				
		if (count($rows)==0)
			return $this->render('view/show.html.twig',array(
					'err_msg' => $err_msg_url,));
				
				$val2 = $em->createQuery(
						'SELECT l.token FROM UrlBundle:Link l
                 WHERE l.shortUrl = :short')->setParameter('short', $short_url)->getSingleResult()['token'];
		
				$val3 = $em->createQuery(
						'SELECT l.longUrl FROM UrlBundle:Link l
                 WHERE l.shortUrl = :short')->setParameter('short', $short_url)->getSingleResult()['longUrl'];
		
		//$val = $request;
				if(password_verify($token, $val2))
				return $this->render('view/index.html.twig', array(
						'views' => $val,
						'link' => $val3,
				));
				else
				{
					
					return $this->render('view/show.html.twig',array(
						'err_msg' => $err_msg_token,));
// 					echo "<script>
// 						alert('Enter correct token');
// 						window.location.href='pageviews';
// 						</script>";
				}
	}
	
	public function cgetAction()
	{
		//return $this->getDoctrine()->getRepository('MentBundle:Category')->find($id);
	
		$links= $this->get('crv.doctrine_entity_repository.link')->findAll()->getResult();
		if ($links === null) {
	
			return new View(null, Response::HTTP_NOT_FOUND);
	
		}
		return $links;
	}
	
	public function getAction($short_url)
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
	
	public function postAction(Request $request)
	{
		
		$olink = new Link();	
		$form = $this->createForm(LinkType::class, $olink, [
						'csrf_protection' => false,
			]);
		$data = $request->request->all();
		
		$form->submit($data);
		
		 		if (!$form->isValid()) {
		 			return $form;
		 		}
		 		
		$form->handleRequest($request);
		if ($form->isValid()) {
			$em = $this->getDoctrine()->getManager();
			$iId = $this->get('crv.doctrine_entity_repository.link')->countId();
		
			//create the url shortener
			$oShortenerSerivce = $this->get('urlshortener.base62');
			$sShort_code = $oShortenerSerivce->num_to_base62($iId + 55);
			$vals = $request->request->all();
			$long = $vals['long_url'];
			//$long = implode(';', $long);
			//echo $long;
			$token = $vals['token'];
			$token = password_hash($token, PASSWORD_DEFAULT);
				// add the short_code to the $oUrl object
			$olink->setShortUrl($sShort_code);
			$olink->setLongUrl($long);
			$olink->setToken($token);
			
			//persist the new $oUrl
			$em = $this->getDoctrine()->getManager();
			$em->persist($olink);
			$em->flush();
			return array(
					"code" => 200,
					"message" => "Your url is : www.localhost:8000/l/".$olink->getShortUrl(),
					"errors" => null
			);
		}
		//return array(
		//		'form' => $form,
		//);
			$link = $form->getData();
		
			$routeOptions = [
		 				'id' => $link->getId(),
		 				'_format' => $request->get('_format'),
		 		];
		
		 		return $this->routeRedirectView('emptypage', $routeOptions, Response::HTTP_CREATED);
		
		
// 		$form = $this->createForm(LinkType::class, null, [
// 				'csrf_protection' => false,
// 		]);
	
// 		$form->submit($request->request->all());
	
// 		if (!$form->isValid()) {
// 			return $form;
// 		}
		
// 		$link = $form->getData();
	
// 		$em = $this->getDoctrine()->getManager();
// 		$em->persist($link);
// 		$em->flush();
	
// 		$routeOptions = [
// 				'id' => $link->getId(),
// 				'_format' => $request->get('_format'),
// 		];
	
// 		return $this->routeRedirectView('get_link', $routeOptions, Response::HTTP_CREATED);
	}
	
	private function dataFunc($short_url, $token)
	{
	
		$em = $this->getDoctrine()->getManager();
	
		$err_msg_token = " token";
		$err_msg_url = " token";
		$err_msg_url = " short url";
	
		$val = $em->createQuery(
				'SELECT v.ip_address, v.created_at FROM UrlBundle:Link l
                 JOIN UrlBundle:View v
                 WITH v.link = l.id
                 WHERE l.shortUrl = :short'
				)->setParameter('short', $short_url)
				->getResult();
		
				$rows = $em->createQuery(
						'SELECT l.id FROM UrlBundle:Link l
                 WHERE l.shortUrl = :short'
						)->setParameter('short', $short_url)
						->getResult();
				
						if (count($rows)==0)
							return $this->render('view/show.html.twig',array(
									'err_msg' => $err_msg_url,));
	
					$val2 = $em->createQuery(
							'SELECT l.token FROM UrlBundle:Link l
                 WHERE l.shortUrl = :short')->setParameter('short', $short_url)->getSingleResult()['token'];
	
			return $val;
					//$val = $request;
					if(password_verify($token, $val2))
						return $this->render('view/index.html.twig', array(
								'views' => $val,));
						else
						{
	
							return $this->render('view/show.html.twig',array(
									'err_msg' => $err_msg_token,));
							// 					echo "<script>
							// 						alert('Enter correct token');
							// 						window.location.href='pageviews';
							// 						</script>";
						}
	}
	
	/**
	 * @Rest\Post("/create", name="createNew")
	 *
	 */
	public function createAction(Request $request)
	{
	
		$olink = new Link();
		$form = $this->createForm(LinkType::class, $olink, [
				'csrf_protection' => false,
		]);
		$data = $request->request->all();
	
		$form->submit($data);
	
		if (!$form->isValid()) {
			return $form;
		}
		 
		$form->handleRequest($request);
		if ($form->isValid()) {
			$em = $this->getDoctrine()->getManager();
			$iId = $this->get('crv.doctrine_entity_repository.link')->countId();
	
			//create the url shortener
			$oShortenerSerivce = $this->get('urlshortener.base62');
			$sShort_code = $oShortenerSerivce->num_to_base62($iId + 25);
			$vals = $request->request->all();
			$long = $vals['long_url'];
			//$long = implode(';', $long);
			//echo $long;
			$token = $vals['token'];
			$token = password_hash($token, PASSWORD_DEFAULT);
			// add the short_code to the $oUrl object
			$olink->setShortUrl($sShort_code);
			$olink->setLongUrl($long);
			$olink->setToken($token);
				
			//persist the new $oUrl
			$em = $this->getDoctrine()->getManager();
			$em->persist($olink);
			$em->flush();
		 $sos =	$this->dataFunc($sShort_code, $token);
		 $str = "www.localhost:8000/l/".$olink->getShortUrl();
		 return $this->render('view/index.html.twig', array(
		 		'views' => $sos,
		 		'link' => $str,
		 ));
		}
		//return array(
		//		'form' => $form,
		//);
		
	
	}
	
	
	/*
	 * @Route("/l", name="emptypage")
	 */
	public function emptyAction($short_url)
	{
		 
		 
	}
	
}