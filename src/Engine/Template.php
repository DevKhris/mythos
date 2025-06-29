<?php


namespace Mythos\Engine;

class Template
{
    protected string $path;
    public array $params = [];

    public function __construct($path, $params)
    {
        $this->path = $path;
        $this->params = $params;
    }

    /**
     * Render content with data.
     *
     * @return bool|string
     */
    public function render(): bool|string
    {
        ob_start();
        if (!empty($this->params)) {
            foreach ($this->params as $key => $value) {
                $this->params[$key] = $value;
            }
            extract($this->params, EXTR_REFS);
        }

        include_once $this->path;

        return ob_get_clean();
    }
    
    /**
     * Safetly escape values from render
     */
    public function escape(?string $value): string
    {
        return htmlspecialchars($value ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }
}
