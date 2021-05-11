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
     * Parameters property
     *
     * @var array
     */
    protected $params = [];

    /**
     * Options property
     *
     * @var array
     */
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
     * @param string $path layout path
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

    /**
     * Asset helper function
     *
     * @param string $asset route to path for asset
     * @return void
     */
    public function asset($asset)
    {
        // parse url from server name
        $url = parse_url($_SERVER['SERVER_NAME']);
        // escape directory for asset
        $asset = str_replace('\\', '/', $asset);
        // parse url with path and assset
        $path = parse_url("https://" . $url['path'] . "/$asset");
        // return path as string from key path
        return ($path['path']);
    }

    /**
     * Call function within view
     *
     * @param string $callback
     * @param array $params
     * @return void
     */
    protected function call($callback, $params = [])
    {
        call_user_func($callback, $params);
    }

    /**
     * Get parameters function
     *
     * @return void
     */
    public function getParams()
    {
        $params = [];
        foreach ($this->params as $key => $value) {
            $params[$key] = $value;
        }
        return $params;
    }

    /**
     * Get path from string
     *
     * @param string $path
     * @return void
     */
    public function getPath($path)
    {
        return str_replace(['/', '\\', '.'], DIRECTORY_SEPARATOR, $path);
    }
}