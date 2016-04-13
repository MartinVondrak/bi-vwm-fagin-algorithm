<?php
/**
 * Created by PhpStorm.
 * User: martin
 * Date: 10.4.16
 * Time: 15:14
 */

namespace Fagin\Exception;


class InvalidParamException extends \Exception {

    /**
     * InvalidParamException konstruktor.
     *
     * @param string          $message
     * @param int             $code
     * @param \Exception|null $previous
     */
    public function __construct($message = "", $code = 0, \Exception $previous = null) {
        parent::__construct($message, $code, $previous);
    }
}