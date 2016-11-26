<?php

namespace UrlBundle\Entity;

/**
 * Link
 */
class Link
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $long_url;

    /**
     * @var string
     */
    private $short_url;

    /**
     * @var string
     */
    private $token;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $views;

    /**
     * @var \UrlBundle\Entity\User
     */
    private $user;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->views = new \Doctrine\Common\Collections\ArrayCollection();
    }

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
     * Set longUrl
     *
     * @param string $longUrl
     *
     * @return Link
     */
    public function setLongUrl($longUrl)
    {
        $this->long_url = $longUrl;

        return $this;
    }

    /**
     * Get longUrl
     *
     * @return string
     */
    public function getLongUrl()
    {
        return $this->long_url;
    }

    /**
     * Set shortUrl
     *
     * @param string $shortUrl
     *
     * @return Link
     */
    public function setShortUrl($shortUrl)
    {
        $this->short_url = $shortUrl;

        return $this;
    }

    /**
     * Get shortUrl
     *
     * @return string
     */
    public function getShortUrl()
    {
        return $this->short_url;
    }

    /**
     * Set token
     *
     * @param string $token
     *
     * @return Link
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
     * Add view
     *
     * @param \UrlBundle\Entity\View $view
     *
     * @return Link
     */
    public function addView(\UrlBundle\Entity\View $view)
    {
        $this->views[] = $view;

        return $this;
    }

    /**
     * Remove view
     *
     * @param \UrlBundle\Entity\View $view
     */
    public function removeView(\UrlBundle\Entity\View $view)
    {
        $this->views->removeElement($view);
    }

    /**
     * Get views
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getViews()
    {
        return $this->views;
    }

    /**
     * Set user
     *
     * @param \UrlBundle\Entity\User $user
     *
     * @return Link
     */
    public function setUser(\UrlBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \UrlBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

     public function __toString()
    {
        return (string)$this->id;
    }
}

