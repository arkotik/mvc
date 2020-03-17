<?php


namespace core;


use controllers\SiteController;

class Response
{
    public function __construct()
    {
    }
    
    /**
     * @param $request Request
     * @return BaseController
     */
    public function resolve($request)
    {
        require ROOT . '/controllers/SiteController.php';
        return new SiteController('index');
    }
    
}