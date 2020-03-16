<?php


namespace core;


class Router
{
    public $rules = [
        '<controller>/<action>' => '<controller>/<action>',
    ];

    public function __construct($rules = [])
    {
        $this->rules = array_merge($this->rules, $rules);
    }
}