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
    protected $path;

    protected $params = [];

    protected $options = [];

    public function __construct($params)
    {
        $this->path = $params['path'];
        $this->options = [
            'cache_dir' => $params->cache
        ];

        // Create template engine instance
        // $this->engine = '';

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
            $view = $this->include($view, $params);
        } else {
            $view = $this->include($view);
        }
        echo str_replace('{{ display }}', $view, $content);
    }

    /**
     * Display function for yielding display tag on layout
     *
     * @return void
     */
    public function display($path = "layouts\\app")
    {
        ob_start();
        include_once $this->path . str_replace('/', DIRECTORY_SEPARATOR,"$path.php");
        return ob_get_clean();
    }

    public function include($view, $params = [])
    {
        $this->render($view,$params);
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
        $view = str_replace(['/','\\','.'], DIRECTORY_SEPARATOR, $view);

        ob_start();
        if (!empty($params)) {
            extract($params);
            foreach ($params as $key => $value) {
                $params[$key] = $value;
            }
        }
        include $this->path . DIRECTORY_SEPARATOR . "$view.php";
        return ob_get_clean();
    }
}
