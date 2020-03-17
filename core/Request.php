<?php


namespace core;


class Request
{
    private $headers = [];
    private $cookies = [];
    private $requestMethod = 'GET';
    private $requestUrlInfo = [];
    
    public $get = [];
    public $post = [];
    public $files = [];
    
    public function __construct()
    {
        $this->headers = getallheaders();
        $this->requestMethod = $_SERVER['REQUEST_METHOD'];
        $this->buildRequestUrlInfo();
        $this->cookies = $_COOKIE;
        $this->get = $_GET;
        $this->post = $_POST;
        $this->files = $_FILES;
    }
    
    public function get($key = null)
    {
        if (!is_null($key)) {
            return Helpers::getValue($this->get, $key, null);
        }
        return $this->get;
    }
    
    public function post($key = null)
    {
        if (!is_null($key)) {
            return Helpers::getValue($this->post, $key, null);
        }
        return $this->post;
    }
    
    public function files()
    {
        return $this->files;
    }
    
    /**
     * @param string|null $key
     * @return array|string|null
     */
    public function getHeaders($key = null)
    {
        if (!is_null($key)) {
            return Helpers::getValue($this->headers, $key, null);
        }
        return $this->headers;
    }
    
    protected function buildRequestUrlInfo() {
        $url = Helpers::getValue($_SERVER, 'REQUEST_SCHEME', 'http' . ($_SERVER['HTTPS'] == 'on' ? 's' : ''));
        $url .= '://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
        $this->requestUrlInfo = parse_url($url);
    }
}