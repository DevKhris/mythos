<?php

declare(strict_types=1);

namespace Mythos\Engine;

interface ViewInterface
{
    public function view($view,$params);

    public function render($view, $params);

    public function include($view,$params);

    public function display($path);

}
