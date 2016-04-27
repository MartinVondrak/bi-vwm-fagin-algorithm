<?php
/**
 * Created by PhpStorm.
 * User: martin
 * Date: 27.4.16
 * Time: 23:32
 */

namespace Fagin\Service;


use Fagin\Exception\OutputFileException;

class TimerLogger {

    /** @var resource $file */
    private $file;

    private $start;

    /**
     * TimerLogger konstruktor.
     *
     * @param string $file
     * @throws OutputFileException
     */
    public function __construct($file) {
        $this->file = fopen($file, 'a');

        if ($this->file === false) {
            throw new OutputFileException('Could not open file ' . $file);
        }

        $this->start = null;
    }

    /**
     * TimeLogger destruktor.
     */
    public function __destruct() {
        fclose($this->file);
    }

    /**
     * Spusti mereni casu a vrati soucasny timestamp v mikrosekundach.
     *
     * @return mixed
     */
    public function start() {
        $this->start = microtime(true);
        return $this->start;
    }

    /**
     * Ukonci akci, zaloguje ji a vrati delku trvani.
     *
     * @param string $message
     * @return mixed|null
     */
    public function stop($message = '') {
        if ($this->start === null) {
            return null;
        }

        $end = microtime(true);
        $this->logFinish($end - $this->start, $message);
        return $end;
    }

    /**
     * Zaloguje delku trvani akce do souboru.
     *
     * @param mixed  $time time in seconds
     * @param string $message
     */
    private function logFinish($time, $message) {
        $now = new \DateTime();
        $time = round($time * 1000, 3);
        $str = '[' . $now->format('d-m-Y H:i:s') . '] EVENT (' . $_SERVER['REMOTE_ADDR'] . ') ' . $time . 'ms ' . $message . PHP_EOL;
        fputs($this->file, $str);
    }

    public function logMessage($message) {
        $now = new \DateTime();
        $str = '[' . $now->format('d-m-Y H:i:s') . '] MESSAGE (' . $_SERVER['REMOTE_ADDR'] . ') ' . $message . PHP_EOL;
        fputs($this->file, $str);
    }

}