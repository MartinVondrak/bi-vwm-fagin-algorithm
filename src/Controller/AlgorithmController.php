<?php
/**
 * Created by PhpStorm.
 * User: michal
 * Date: 14.4.16
 * Time: 14:23
 */

namespace Fagin\Controller;

use Fagin\Exception\InvalidAggregationFunctionException;
use Fagin\Exception\InvalidParamException;
use Fagin\Exception\InvalidTopKException;
use Fagin\Service\AbstractSearchService;
use Fagin\Service\CarOperation;
use Fagin\Service\FaginSearchService;
use Fagin\Service\LinearSearchService;


class AlgorithmController extends Controller {

    /** @var CarOperation $carOperation */
    private $carOperation;

    /** @var FaginSearchService $faginService */
    private $faginService;

    /** @var LinearSearchService $faginService */
    private $linearService;

    /**
     * FaginController konstruktor.
     *
     * @param \Twig_Environment $twig
     */
    public function __construct($twig) {
        parent::__construct($twig);
        $this->carOperation = new CarOperation();
        $this->faginService = new FaginSearchService();
        $this->linearService = new LinearSearchService();
    }

    /**
     * Vyrenderuje vyhledavaci formular
     *
     * @return mixed
     */
    public function findFormAction() {
        return $this->render('algorithm/find.html.twig');
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
            switch ($_POST["algorithm"]) {
                case AbstractSearchService::FAGIN:
                    $cars = json_encode($this->faginService->getKProductsWithParams(explode(",", $_POST["params"]), $_POST["top_k"], $_POST["aggregation"]));
                    break;
                case AbstractSearchService::LINEAR:
                    $cars = json_encode($this->linearService->getKProductsWithParams(explode(",", $_POST["params"]), $_POST["top_k"], $_POST["aggregation"]));
                    break;
                default:
                    header(self::CODES[400]);
                    return json_encode("Invalid algorithm");
            }
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
        return $cars;
    }

}