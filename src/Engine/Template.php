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

        $view = $this->replacePlaceholder($this->path);
        eval('?>' . $view);

        return ob_get_clean();
    }
    

    /**
     * Replace placeholder prefix from templating for functional code.
     * 
     * @return array|string
     */
    public function replacePlaceholder(string $view): array|string
    {
        $viewFile = file_get_contents($view,  false);
        $viewFile = str_replace(['{{', '}}'], ['<?=', '?>'], $viewFile);
        $viewFile = str_replace(['{#', '#}'], ['<?php', '?>'], $viewFile);
        return $viewFile;
    }
    
    /**
     * Safetly escape values from render
     */
    public function escape(?string $value): string
    {
        return htmlspecialchars($value ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }
}
