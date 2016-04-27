<?php
/**
 * Created by PhpStorm.
 * User: martin
 * Date: 28.4.16
 * Time: 0:34
 */

namespace Fagin\Exception;


class OutputFileException extends \Exception {

    /**
     * OutputFileException konstruktor.
     *
     * @param string          $message
     * @param int             $code
     * @param \Exception|null $previous
     */
    public function __construct($message = "", $code = 0, \Exception $previous = null) {
        parent::__construct($message, $code, $previous);
    }
}