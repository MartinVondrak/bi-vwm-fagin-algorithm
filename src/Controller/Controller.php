<?php

/**
 * Created by PhpStorm.
 * User: michal
 * Date: 13.4.16
 * Time: 22:24
 */

namespace Fagin\Controller;

use Fagin\Data\Database;

class Controller
{
    private $twig;

    public function __construct($twig) {
        $this->twig = $twig;
    }

    /**
     * Funkce zavola twig->render
     * @param string $file
     * @param array $array
     */
    public function render($file,$array) {
        return $this->twig->render($file,$array);
    }
}