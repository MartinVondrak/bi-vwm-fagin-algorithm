<?php
/**
 * Created by PhpStorm.
 * User: martin
 * Date: 27.4.16
 * Time: 13:02
 */

namespace Fagin\Service;


use Fagin\Data\Database;
use Fagin\Exception\InvalidParamException;

abstract class AbstractSearchService {

    /** Dostupne agregacni funkce */
    const MIN = 'min';
    const MAX = 'max';
    const AVG = 'average';

    /** @var Database $database */
    protected $database;

    /**
     * AbstractSearchService konstruktor.
     */
    public function __construct() {
        $this->database = new Database();
    }

    /**
     * Spocita prumer hodnot v poli.
     *
     * @param array $values
     * @return float
     * @throws InvalidParamException
     */
    protected function avg($values) {
        if (!is_array($values)) {
            throw new InvalidParamException('Expected array ' . gettype($values) . 'given.');
        }

        return array_sum($values) / count($values);
    }
}