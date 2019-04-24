<?php

namespace Ethansmart\HttpBuilder\Builder;

use Ethansmart\HttpBuilder\Http\HttpClient;
use Ethansmart\HttpBuilder\Log\Log;
use BadMethodCallException;

class HttpClientBuilder
{
    protected $version = null ;
    protected $headers = null ;

    public function __construct()
    {
        $this->version = "1.0";
    }

    public static function create()
    {
        return new static();
    }

    public function build($logInstance = null)
    {
        return new HttpClient($logInstance);
    }

    public function __call($name, $arguments)
    {
        // TODO: Implement __call() method.
        throw new BadMethodCallException("该版本中".$this->version." $name 不支持");
    }

    public function getVersion()
    {
        return $this->version;
    }


}