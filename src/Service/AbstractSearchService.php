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

    /** @var array AGGREGATIONS */
    const AGGREGATIONS = array(
        self::MIN,
        self::MAX,
        self::AVG
    );

    /** @var Database $database */
    protected $database;

    /** @var TimerLogger $timeLoger */
    protected $timeLogger;

    /**
     * AbstractSearchService konstruktor.
     */
    public function __construct() {
        $this->database = new Database();
        $this->timeLogger = new TimerLogger($_SERVER['DOCUMENT_ROOT'] . '/logs/time.log');
    }

    /**
     * Spocita prumer hodnot v poli.
     *
     * @param array $values
     * @return float
     * @throws InvalidParamException
     */
    protected function avg($values) {
        if (!is_array($values) or empty($values)) {
            throw new InvalidParamException('Expected non-empty array ' . gettype($values) . 'given.');
        }

        return array_sum($values) / count($values);
    }

    /**
     * Komparator pro razeni aut podle agregovanych hodnot sestupne.
     *
     * @param array $a
     * @param array $b
     * @return int
     */
    protected static function sortCarsDesc($a, $b) {
        if ($a['aggregation'] < $b['aggregation']) {
            return 1;
        }

        return -1;
    }
}