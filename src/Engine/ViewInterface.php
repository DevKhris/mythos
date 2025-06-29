<?php

namespace Mythos\Engine;

interface ViewInterface
{
    public function view(string $view, array $params);

    public function render(string $view, array $params);

    public function display(string $path);

    public function getParams();

    public function setParam(string $param, string|array|object $value);

    public function getPath(string $path);

    public function setPath(string $path);

     public function getExtension();

    public function setExtension(string $extension);
}