<?php

namespace UrlBundle\Entity;
use Doctrine\ORM\Mapping as ORM;


/**
 * View
* @ORM\HasLifecycleCallbacks
 */
 
class View
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $ip_address;

    /**
     * @var \DateTime
     */
    private $created_at;

    /**
     * @var string
     */
    private $token;

    /**
     * @var \UrlBundle\Entity\Link
     */
    private $link;


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set ipAddress
     *
     * @param string $ipAddress
     *
     * @return View
     */
    public function setIpAddress($ipAddress)
    {
        $this->ip_address = $ipAddress;

        return $this;
    }

    /**
     * Get ipAddress
     *
     * @return string
     */
    public function getIpAddress()
    {
        return $this->ip_address;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return View
     */
    public function setCreatedAt($createdAt)
    {
        if(!$this->getCreatedAt())
    {
        $this->created_at = new \DateTime();
    }
    return $this;
       
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * Set token
     *
     * @param string $token
     *
     * @return View
     */
    public function setToken($token)
    {
        $this->token = $token;

        return $this;
    }

    /**
     * Get token
     *
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }

   

    /**
     * Set link
     *
     * @param \UrlBundle\Entity\Link $link
     *
     * @return View
     */
    public function setLink(\UrlBundle\Entity\Link $link = null)
    {
        $this->link = $link;

        return $this;
    }

    /**
     * Get link
     *
     * @return \UrlBundle\Entity\Link
     */
    public function getLink()
    {
        return $this->link;
    }
    /**
     * @ORM\PrePersist
     */
    public function setCreatedAtValue()
    {
            if(!$this->getCreatedAt())
    {
        $this->created_at = new \DateTime();
    }
    }
}

