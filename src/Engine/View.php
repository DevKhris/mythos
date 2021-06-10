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

    /**
     * 
     */
    protected $params = [];

    /**
     * Options array
     *
     * @var array
     */
    protected $options = [
        'path' => $this->path,
        'cache' => ''
    ];

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
        include $this->getPath("$this->params['path'].$view") . ".mythos";
        return ob_get_clean();
    }

    public function getParams(): array|string
    {
        $params = [];
        foreach ($this->params as $key => $value) {
            $params[$key] = $value;
        }
        return $params;
    }

    /**
     * Settter for specific param and value
     *
     * @param string $param
     * @param string|array|object $value
     * @return void
     */
    public function setParam(string $param, string|array|object $value): void
    {
        $this->params[$param] = $value;
    }

    /**
     * Get path from string
     *
     * @param string] $path
     * @return string
     */
    public function getPath(string $path): string
    {
        $result = str_replace(['/', '\\', '.'], DIRECTORY_SEPARATOR, $path);
        return $result;
    }

    public function setPath(string $value): void
    {
        $this->setParam('path', $value);
    }
}