<?php
/**
 * Created by PhpStorm.
 * User: michal
 * Date: 27.4.16
 * Time: 21:46
 */

namespace Fagin\Exception;


class InvalidTopKException extends \Exception {

    /**
     * InvalidTopKException konstruktor.
     *
     * @param string          $message
     * @param int             $code
     * @param \Exception|null $previous
     */
    public function __construct($message = "", $code = 0, \Exception $previous = null) {
        parent::__construct($message, $code, $previous);
    }

}