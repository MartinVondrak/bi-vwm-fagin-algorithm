<?php
/**
 * Created by PhpStorm.
 * User: martin
 * Date: 27.4.16
 * Time: 13:02
 */

namespace Fagin\Service;

use Fagin\Data\Car;
use Fagin\Data\Database;
use Fagin\Exception\InvalidParamException;
use Fagin\Exception\InvalidAggregationFunctionException;
use Fagin\Exception\InvalidTopKException;

abstract class AbstractSearchService {

    /** Dostupne vyhledavaci algoritmy */
    const LINEAR = 'seq';
    const FAGIN = 'fagin';

    /** @var array ALGORITHMS */
    const ALGORITHMS = array(
        self::LINEAR,
        self::FAGIN
    );

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
            throw new InvalidParamException('Expected non-empty array ' . gettype($values) . ' given.');
        }

        return array_sum($values) / count($values);
    }

    /**
     * Agreguje jednotlive parametry auta a seradi je.
     *
     * @param array  $cars
     * @param string $aggregationFunction
     * @return array
     * @throws InvalidAggregationFunctionException
     * @throws InvalidParamException
     */
    protected function aggregateAndSortProducts(&$cars, $aggregationFunction) {
        $this->timeLogger->start();

        foreach ($cars as $id => $params) {
            switch ($aggregationFunction) {
                case self::MAX:
                    $aggregatedValues = max($params);
                    break;
                case self::MIN:
                    $aggregatedValues = min($params);
                    break;
                case self::AVG:
                    $aggregatedValues = $this->avg($params);
                    break;
                default:
                    throw new InvalidAggregationFunctionException($aggregationFunction);
                    break;
            }

            $cars[$id]['aggregation'] = $aggregatedValues;
        }

        $this->timeLogger->stop('Calculating aggregated values');
        $this->timeLogger->start();
        uasort($cars, array('self', 'sortCarsDesc'));
        $this->timeLogger->stop('Sorting cars');
        return $cars;
    }

    /**
     * Vrati pole k nejlepsich aut ze $sorted pole.
     *
     * @param array $sorted
     * @param int   $k
     * @return Car[]
     */
    protected function getTopKCars(&$sorted, $k) {
        $carIds = array();
        reset($sorted);

        for ($i = 0; $i < $k; $i++) {
            $carIds[] = key($sorted);
            next($sorted);
        }

        $cars = $this->database->fetchCarByIds($carIds, true);
        return $cars;
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

    /**
     * Zkontroluje jestli je top K cislo a pokud je vetsi nez pocet produktu v databazi zmensi ho.
     *
     * @param mixed $k
     * @return int
     * @throws InvalidTopKException
     */
    protected function validateTopK($k) {
        if (!is_numeric($k)) {
            throw new InvalidTopKException('Expected numeric top K ' . gettype($k) . ' given.');
        }

        $carCount = $this->database->countCars();

        if ($k > $carCount) {
            $k = $carCount;
        }

        return intval($k);
    }
}