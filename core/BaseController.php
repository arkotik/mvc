<?php


namespace core;


use HttpException;

class BaseController
{
    private static $name;

    public $action;
    public $layout = 'main';
    
    private $viewPath;
    private $_defaultAction;
    private $view;
    
    public function __construct($action = null)
    {
        $this->action = $action;
        $this->_defaultAction = Helpers::getValue(App::$app->config, 'defaultAction', 'index');
    }
    
    public function getViewPath()
    {
        if ($this->viewPath === null) {
            $this->viewPath = ROOT . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . $this->getSelfName();
        }
        
        return $this->viewPath;
    }
    
    public function getSelfName()
    {
        if (!self::$name) {
            $fullName = Helpers::camel2id(Helpers::basename(get_called_class()));
            self::$name = preg_replace('/-controller$/', '', $fullName);
        }
        return self::$name;
    }
    
    /**
     * @param mixed $viewPath
     * @return BaseController
     */
    public function setViewPath($viewPath)
    {
        $this->viewPath = $viewPath;
        return $this;
    }
    
    /**
     * @param $view
     * @param array $params
     * @return false|string
     */
    public function render($view, $params = [])
    {
        $content = $this->getView()->render($view, $params);
        return $this->renderContent($content);
    }
    
    public function renderContent($content)
    {
        $layoutFile = $this->findLayoutFile($this->getView());
        if ($layoutFile !== false) {
            return $this->getView()->renderFile($layoutFile, ['content' => $content]);
        }
        
        return $content;
    }
    
    public function getView()
    {
        if ($this->view === null) {
            $this->view = new View();
        }
        
        return $this->view;
    }
    
    public function findLayoutFile($view)
    {
        $layout = $this->layout;
        if (!isset($layout)) {
            return false;
        }
        $file = App::$app->getLayoutPath() . DIRECTORY_SEPARATOR . $layout;
        if (pathinfo($file, PATHINFO_EXTENSION) !== '') {
            return $file;
        }
        $path = $file . '.' . $view->defaultExtension;
        if ($view->defaultExtension !== 'php' && !is_file($path)) {
            $path = $file . '.php';
        }
    
        return $path;
    }
    
    /**
     * @return mixed
     * @throws HttpException
     */
    public function runAction()
    {
        $actionName = $this->action ?? $this->_defaultAction;
        $action = self::getActionName($actionName);
        if (method_exists($this, $action)) {
            return $this->$action();
        } else {
            throw new HttpException('Not found action', 404);
        }
    }
    
    public static function getActionName($action)
    {
        return 'action' . Helpers::id2Camel($action);
    }
}