<?php

require_once __DIR__.'/vendor/autoload.php';

use Ethansmart\HttpBuilder\Builder\HttpClientBuilder;

class Test
{
    protected $client ;
    function __construct()
    {
        $this->client = HttpClientBuilder::create()
            ->build();
    }

    public function get()
    {
        $data = [
            'headers'=>["Content-Type:application"=>"application/json", "X-HTTP-Method-Override"=>"GET", "Request_id"=>"Ethan"],
            'url'=>'https://www.baidua.com'
        ];

        return $this->client
            ->setTimeout(5)
            ->Get($data);
    }
};

(new Test())->get();
