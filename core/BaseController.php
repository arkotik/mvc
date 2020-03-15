<?php


namespace core;


class BaseController
{
    public $action;
    public $layout = 'main';

    public function render($view, $params)
    {
        $fileName = preg_match("/\.php$/i", $view) ? $view : "{$view}.php";
        if (file_exists(__DIR__ . "/{}")) {

        }
        $path = sprintf('%s/%s/%s/%s', ROOT, 'views', $this->action, $fileName);
        if (file_exists($path)) {
            ob_start();
            extract($params);
            include $path;
            $html = ob_get_contents();
            ob_end_clean();
            return $html;
        }
        return null;
    }
}