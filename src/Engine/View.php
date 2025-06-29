<?php
namespace Mythos\Engine;

use Exception;
use Mythos\Engine\Template;
use Mythos\Engine\ViewInterface;
use Mythos\Exceptions\TemplateNotFoundException;

class View implements ViewInterface
{
    /**
     * Path property.
     */
    protected string $path = "../../resources/views";

    /**
     * Extension property.
     */
    protected string $extension = ".mythos";

    /**
     * 
     */
    protected array $params = [];

    /**
     * Options array
     *
     * @var array
     */
    protected $options = [
        'path' => '',
        'cache' => ''
    ];

    /**
     * Engine constructor
     *
     * @param array] $params parameters for view engine
     */
    public function __construct(array $params, string $extension = '.mythos')
    {
        $this->params = $params;
        $this->extension = $extension;

        $this->options = $this->getParams();
        if (isset($this->options['path'])) {
            $this->path = $this->options['path'];
        }
    }

    /**
     * Render view function
     *
     * @param string $view view to render
     * @param string|array $params parameters for view
     * 
     * @return string
     */
    public function view(string $view, array $params = []): void
    {
        $content = $this->display();
        $changes = 0;
        if (!empty($params)) {
            $view = $this->render($view, $params);
        } else {
            $view = $this->render($view);
        }
        echo str_replace('{{ display }}', $view, $content, $changes);
    }

    /**
     * Display function for yielding display tag on layout.
     * 
     * @param string $path  layout path
     * 
     * @return bool|string
     */
    public function display(string $path = "layouts.app"): bool|string
    {
        ob_start();
        include_once $this->getPath("$this->path.$path") . $this->extension;
        return ob_get_clean();
    }

    /**
     * Render template with data.
     *
     * @param string $viewPath  view path
     * @param array  $params parameters for view
     *
     * @return bool|string
     */
    public function render(string $viewPath, array $params = []): bool|string
    {
        $templateExt = $this->extension;
        $templatePath = $this->params['path'];
        $viewPath = $this->getPath("$templatePath.$viewPath");

        $view = "$viewPath$templateExt";

        if(! file_exists($view)) {
            throw new TemplateNotFoundException();
        }

        return (new Template($view, $params))->render();
    }

    /**
     * Gey value from key in params.
     * @return array
     */
    public function getParams(): array|string
    {
        $params = [];
        foreach ($this->params as $key => $value) {
            $params[$key] = $value;
        }
        return $params;
    }

    /**
     * Settter for specific key and value
     *
     * @param string $key key to asign value
     * @param string|array|object $value value to asign to key
     * @return void
     */
    public function setParam(string $key, string|array|object $value): void
    {
        $this->params[$key] = $value;
    }

    /**
     * Get path from string
     *
     * @param string $path path to views
     * 
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

    /**
     * Get extension property.
     */ 
    public function getExtension(): string
    {
        return $this->extension;
    }

    /**
     * Set extension property.
     */ 
    public function setExtension(string $extension): void
    {
        $this->extension = $extension;
    }
}