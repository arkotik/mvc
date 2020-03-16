<?php


namespace core;


use Exception;
use Throwable;

class View
{
    public $params = [];
    public $defaultExtension = 'php';
    
    private $_viewFiles = [];
    
    public function render($view, $params = [])
    {
        $viewFile = $this->findViewFile($view);
        return $this->renderFile($viewFile, $params);
    }
    
    /**
     * @param $view
     * @return string
     * @throws Exception
     */
    protected function findViewFile($view)
    {
        if (strncmp($view, '/', 1) === 0) {
            if (!is_null(App::$app->controller)) {
                $file = App::$app->controller->getViewPath() . '/' . ltrim($view, '/');
            } else {
                throw new Exception("Unable to locate view file for view '$view': no active controller.");
            }
        } elseif (($currentViewFile = $this->getRequestedViewFile()) !== false) {
            $file = dirname($currentViewFile) . DIRECTORY_SEPARATOR . $view;
        } else {
            throw new Exception("Unable to resolve view file for view '$view': no active view context.");
        }
        if (pathinfo($file, PATHINFO_EXTENSION) !== '') {
            return $file;
        }
        $path = $file . '.' . $this->defaultExtension;
        if ($this->defaultExtension !== 'php' && !is_file($path)) {
            $path = $file . '.php';
        }
    
        return $path;
    }
    
    protected function getRequestedViewFile()
    {
        return empty($this->_viewFiles) ? false : end($this->_viewFiles)['requested'];
    }
    
    /**
     * @param $viewFile
     * @param array $params
     * @return false|string
     * @throws Throwable
     */
    public function renderFile($viewFile, $params = [])
    {
        $viewFile = $requestedFile = $viewFile;
        
        $output = '';
        $this->_viewFiles[] = [
            'resolved' => $viewFile,
            'requested' => $requestedFile
        ];
    
        $output = $this->renderPhpFile($viewFile, $params);
        
        array_pop($this->_viewFiles);
        
        return $output;
    }
    
    public function renderPhpFile($_file_, $_params_ = [])
    {
        $_obInitialLevel_ = ob_get_level();
        ob_start();
        ob_implicit_flush(false);
        extract($_params_, EXTR_OVERWRITE);
        try {
            /** @noinspection PhpIncludeInspection */
            require $_file_;
            return ob_get_clean();
        } catch (Exception $e) {
            while (ob_get_level() > $_obInitialLevel_) {
                if (!@ob_end_clean()) {
                    ob_clean();
                }
            }
            throw $e;
        } catch (Throwable $e) {
            while (ob_get_level() > $_obInitialLevel_) {
                if (!@ob_end_clean()) {
                    ob_clean();
                }
            }
            throw $e;
        }
    }
}