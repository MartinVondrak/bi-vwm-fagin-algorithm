<?php
/**
 * Created by PhpStorm.
 * User: martin
 * Date: 26.4.16
 * Time: 16:55
 */

namespace Fagin\Exception;


class InvalidAggregationFunctionException extends \Exception {

    /**
     * InvalidAggregationFunctionException konstruktor.
     *
     * @param string          $message
     * @param int             $code
     * @param \Exception|null $previous
     */
    public function __construct($message = "", $code = 0, \Exception $previous = null) {
        parent::__construct($message, $code, $previous);
    }
}