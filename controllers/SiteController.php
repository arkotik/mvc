<?php

namespace controllers;

use core\BaseController;

class SiteController extends BaseController
{
    public function actionIndex()
    {
        return $this->render('/index', ['fuck' => 'off']);
    }

}