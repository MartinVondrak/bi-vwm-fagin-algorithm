<?php
/**
 * Created by PhpStorm.
 * User: michal
 * Date: 14.4.16
 * Time: 14:23
 */

namespace Fagin\Controller;

use Fagin\Data\Car;
use Fagin\Exception\InvalidAggregationFunctionException;
use Fagin\Exception\InvalidParamException;
use Fagin\Exception\InvalidTopKException;
use Fagin\Service\AbstractSearchService;
use Fagin\Service\CarOperation;
use Fagin\Service\FaginSearchService;


class FaginController extends Controller {

    /** @var CarOperation $carOperation */
    private $carOperation;

    /** @var FaginSearchService $faginService */
    private $faginService;

    /**
     * FaginController konstruktor.
     *
     * @param \Twig_Environment $twig
     */
    public function __construct($twig) {
        parent::__construct($twig);
        $this->carOperation = new CarOperation();
        $this->faginService = new FaginSearchService();
    }

    /**
     * Vyrenderuje vyhledavaci formular
     *
     * @return mixed
     */
    public function findFormAction() {
        return $this->render('fagin/find.html.twig');
    }

    /**
     * Vrati JSON vsech aut.
     *
     * @return string
     */
    public function getAllCarsAction() {
        $cars = $this->carOperation->getAllCars();
        return json_encode($cars);
    }

    /**
     * Vrati JSON vsech aut na zaklade fagin algoritmu
     *
     * @return string
     */
    public function findCarsAction() {
        try {
            $aggregation = $this->validateAggregation($_POST["aggregation"]);
            $params = $this->validateParams(explode(",", $_POST["params"]));
            $top_k = $this->validateTopK($_POST["top_k"]);
        } catch (InvalidAggregationFunctionException $ex) {
            header(self::CODES[400]);
            return json_encode($ex->getMessage());

        } catch (InvalidParamException $ex) {
            header(self::CODES[400]);
            return json_encode($ex->getMessage());

        } catch (InvalidTopKException $ex) {
            header(self::CODES[400]);
            return json_encode($ex->getMessage());
        }
        $cars = json_encode($this->faginService->getKProductsWithParams($params, $top_k, $aggregation));
        return $cars;
    }

    /**
     * Validuje agregacni funkci
     *
     * @param string $aggregation
     * @return string
     * @throws InvalidAggregationFunctionException
     */
    private function validateAggregation($aggregation) {
        if (!in_array($aggregation, AbstractSearchService::AGGREGATIONS)) {
            throw new InvalidAggregationFunctionException($aggregation);
        }
        return $aggregation;
    }

    /**
     * Validuje parametry
     *
     * @param string[] $params
     * @return string[]
     * @throws InvalidParamException
     */
    private function validateParams($params) {
        foreach ($params as $param) {
            if (!in_array($param, Car::PARAMS)) {
                throw new InvalidParamException($params);
            }
        }
        return $params;
    }

    /**
     * Validuje Top K
     *
     * @param string $top_k
     * @return string
     * @throws InvalidTopKException
     */
    private function validateTopK($top_k) {
        if (!is_numeric($top_k)) {
            throw new InvalidTopKException($top_k);
        }
        return $top_k;
    }

}