<?php


namespace core;


class Request
{
    private $headers = [];
    private $cookies = [];
    private $requestMethod = 'GET';
    
    public function __construct()
    {
        $this->headers = getallheaders();
        $this->requestMethod = $_SERVER['REQUEST_METHOD'];
        var_dump($_REQUEST);
        var_dump($_COOKIE);
        var_dump($_SERVER);
    }
    
    /**
     * @param string|null $name
     * @return array|string|null
     */
    public function getHeaders($name = null)
    {
        if (!is_null($name)) {
            return Helpers::getValue($this->headers, $name, null);
        }
        return $this->headers;
    }
}