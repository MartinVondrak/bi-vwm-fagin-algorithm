<?php

/**
 * Created by PhpStorm.
 * User: michal
 * Date: 13.4.16
 * Time: 22:24
 */

namespace Fagin\Controller;

class Controller {

    /** @var \Twig_Environment $twig */
    private $twig;

    const CODES = array(
        "400" => "HTTP/1.1 400 BAD REQUEST",
        "404" => "HTTP/1.1 404 NOT FOUND",
        "500" => "HTTP/1.1 500 INTERNAL ERROR"
    );

    /**
     * Controller constructor.
     *
     * @param \Twig_Environment $twig
     */
    public function __construct($twig) {
        $this->twig = $twig;
    }

    /**
     * Funkce zavola twig->render.
     *
     * @param string $file
     * @param array  $array
     * @return string
     */
    public function render($file, $array = array()) {
        return $this->twig->render($file, $array);
    }

    /**
     * Funkce vypisujici HTTP response.
     *
     * @param $code
     * @return mixed
     */
    public function response($code) {
        header($this->getCode($code));
        return $this->twig->render("static/error.html.twig", array('error' => $this->getCode($code)));
    }

    /**
     * Vraci HTTP code z pole CODES.
     *
     * @param $code
     * @return mixed
     */
    private function getCode($code) {
        if (!array_key_exists($code, self::CODES)) {
            return self::CODES["500"];
        }
        return self::CODES[$code];
    }

}