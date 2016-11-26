<?php

namespace UrlBundle\DataFixtures\ORM;
 
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use UrlBundle\Entity\Link;
 
class LoadLinkData extends AbstractFixture implements OrderedFixtureInterface
{
  public function load(ObjectManager $em)
  {
    $link1 = new Link();
    $link1->setLongUrl('wwww.google.com');
    $link1->setShortUrl('xxxx');
    $link1->setToken('abcdef');
    $link1->setUser(null);

    $link2 = new Link();
    $link2->setLongUrl('wwww.google.com');
    $link2->setShortUrl('yyy');
    $link2->setToken('abcdefg');
    $link1->setUser(null);
 
 
    $em->persist($link1);
    $em->persist($link2);
    
 
    $em->flush();
 
    $this->addReference('link1', $link1);
    $this->addReference('link2', $link2);
    
  }
 
  public function getOrder()
  {
    return 1; // the order in which fixtures will be loaded
  }
}