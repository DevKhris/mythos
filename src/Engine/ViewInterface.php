<?php

declare(strict_types=1);

namespace Mythos\Engine;

interface ViewInterface
{
    /**
     * Path property
     *
     * @var string
     */
    protected $path;

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

    public function view($view,$params);

    public function render($view, $params);

    public function include($view,$params);

    public function display($path);

}
