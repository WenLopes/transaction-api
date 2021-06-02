<?php 

namespace App\Exceptions\Interfaces;

interface RenderableExceptionInterface {

    /**
     * Exception code
     * @return int
     */
    public function httpCode() : int;

    /**
     * Render exception
     */
    public function render();

    /**
     * Exception Message
     * @return string
     */
    public function message() : string;
}