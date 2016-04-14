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

    const CODES = array(
        "404" => "HTTP/1.1 404 NOT FOUND",
        "500" => "HTTP/1.1 500 INTERNAL ERROR"
    );

    /**
     * Controller constructor.
     * @param $twig
     */
    public function __construct($twig) {
        $this->twig = $twig;
    }

    /**
     * Funkce zavola twig->render
     * @param string $file
     * @param array $array
     */
    public function render($file,$array = NULL) {
        return $this->twig->render($file,$array);
    }

    public function response($code) {
        header($this->getCode($code));
        return $this->twig->render("static/error.html.twig",array('error' => $this->getCode($code)));
    }

    private function getCode($code) {
        if(!array_key_exists($code,self::CODES)) {
            return self::CODES["500"];
        }
        return self::CODES[$code];
    }

}