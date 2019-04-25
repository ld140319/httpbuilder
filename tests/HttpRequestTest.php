<?php
/*
 * Copyright 2018 Ethan
 * Test Cases
 */

namespace Ethansmart\HttpBuilder\Tests;

use Ethansmart\HttpBuilder\Builder\HttpClientBuilder;
use PHPUnit\Framework\TestCase;

class HttpRequestTest extends TestCase
{
	private $client;

	// setUp method
	public function setUp()
	{
		$this->client = HttpClientBuilder::create()
            ->build();
	}

	// test http get request
	public function testHttpGet()
	{
		$data = [
            'headers'=>["Content-Type:application"=>"application/json"],
            'url'=>'https://www.baidu.com'
        ];
        $result = $this->client->Get($data);
		$this->assertEquals(0, $result["code"]);
	}

	// test http post request
	public function testHttpPost()
	{
		$data = [
            'headers'=>["Content-Type:application"=>"application/json"],
            'url'=>'https://www.baidu.com',
            'params'=>[
            	"user"=>"username:ethan"
            ]
        ];
        $result = $this->client->Post($data);
		$this->assertEquals(0, $result["code"]);
	}

	// test http header request
	public function testHttpHeader()
	{
		$data = [
            'headers'=>["Content-Type:application"=>"application/json"],
            'url'=>'https://www.baidu.com',
            'params'=>[
            	"user"=>"username:ethan"
            ]
        ];
        $result = $this->client->Header($data);
		$this->assertEquals(0, $result["code"]);
	}

}