<?php
namespace UrlBundle\Tests\Services;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use UrlBundle\Services\Base62Service;
use PHPUnit\Framework\TestCase;

class Base62ServiceTest extends TestCase
{
	public function testConversion()
	{
		$testNum = 1;
		$oShortenerSerivce = $this->get('urlshortener.base62');
		$expected =  $oShortenerSerivce->num_to_base62($testNum);
		$actual = 0;
		$this->assertEquals($expected, $actual);
	}
	
	
}