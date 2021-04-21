<?php

declare(strict_types=1);

namespace Mythos\Engine;

use Mythos\Engine\ViewInterface;

class View implements ViewInterface
{
    /**
     * Path property
     *
     * @var string
     */
    protected $path = "../../resources/views";

    protected $params = [];

    protected $options = [];

    /**
     * Engine constructor
     *
     * @param array] $params parameters for view engine
     */
    public function __construct($params)
    {
        $this->params = $params;
        $this->options = $this->getParams();
        if (isset($this->options['path'])) {
            $this->path = $this->options['path'];
        } 

        return $this;
    }
    /**
     * Render view function
     *
     * @param string $view view to render
     *
     * @return string
     */
    public function view($view, $params = [])
    {
        $content = $this->display();
        if (!empty($params)) {
            $view = $this->render($view, $params);
        } else {
            $view = $this->render($view);
        }
        echo str_replace('{{ display }}', $view, $content);
    }

    /**
     * Display function for yielding display tag on layout
     *
     * @return void
     */
    public function display($path = "layouts.app")
    {
        ob_start();
        include_once $this->getPath("$this->path.$path") . ".mythos";
        return ob_get_clean();
    }

    /**
     * Render function
     *
     * @param string $view   view
     * @param array  $params parameters for view
     *
     * @return object
     */
    public function render($view, $params = [])
    {
        $view = $this->getPath($view);

        ob_start();
        if (!empty($params)) {
            extract($params);
            foreach ($params as $key => $value) {
                $params[$key] = $value;
            }
        }
        include $this->getPath("$this->path.$view") . ".mythos";
        return ob_get_clean();
    }

    public function getParams()
    {
       $params = [];
       foreach($this->params as $key => $value)
       {
          $params[$key] = $value;
       }
       return $params;
    }

    public function getPath($path)
    {
        return str_replace(['/','\\','.'], DIRECTORY_SEPARATOR,$path);
    }
}
