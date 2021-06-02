<?php 

namespace App\Exceptions\Interfaces;

interface ReportableExceptionInterface {

    /**
     * Exception Technical Message
     * @return string
     */
    public function exceptionMessage() : string;

    /**
     * Report exception
     * @return void
     */
    public function report() : void;
}