shortener
=========

Url Shortener's are used for multiple purposes. Brand's use it to increase their value, it makes sharing links easier. 

This website can be used to shorten any URL. So what's different about it as comapred to Goo.gl or Bit.ly ? The analytics of your page hits are open for all to me. The website, let's to create secure tokens for your URL's so that only people with access to it can view page hits. 

It is built using Symfony framework.

![alt tag](https://github.com/navya-nizamkari/url-shortener/blob/master/shot.png)


To shorten URL's using REST API : 

Hit the following :

www.localhost:8000/links with a JSON request of the format :

{
	"long_url": "24",
    "token": "wow"
}

For getting pageviews using API hit the following :

{
	"short_url": "24",
    "token": "wow"
}

To install the project on local machine, download it and run composer install. Then create database using :

php bin/console doctrine:database:create

and populate it using :

php bin/console doctrine:fixtures:load

