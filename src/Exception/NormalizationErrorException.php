<?php
/**
 * Created by PhpStorm.
 * User: martin
 * Date: 13.4.16
 * Time: 23:38
 */

namespace Fagin\Exception;


class NormalizationErrorException extends \Exception {

    /**
     * NormalizationErrorException konstruktor.
     *
     * @param string          $message
     * @param int             $code
     * @param \Exception|null $previous
     */
    public function __construct($message = "", $code = 0, \Exception $previous = null) {
        parent::__construct($message, $code, $previous);
    }
}