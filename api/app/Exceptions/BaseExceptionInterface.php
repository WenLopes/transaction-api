<?php 

namespace App\Exceptions;

interface BaseExceptionInterface {

    /**
     * Exception Technical Code
     * @return string
     */
    public function errorCode() : int;

    /**
     * Exception Technical Message
     * @return string
     */
    public function errorMessage() : string;

    /**
     * Exception code
     * @return int
     */
    public function httpCode() : int;

    /**
     * log exception
     * @return int
     */
    public function log() : void;

    /**
     * Exception Message
     * @return string
     */
    public function message() : string;
}