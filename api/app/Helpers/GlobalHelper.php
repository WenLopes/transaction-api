<?php 

/**
 * Returns the value of the key passed as a parameter
 * 
 * @param Array|Object $source
 * @param string $index
 * @return Mixed
 */
if ( ! function_exists('getProperty'))
{
    function getProperty($source, string $index)
    {
        if( gettype($source) == 'object'){
            $source = (array) $source;
        }
    
        if( array_key_exists("{$index}", $source) ){
            return $source["{$index}"];
        }
        return null;
    }
}

/**
 * Returns formatted decimal value for the BRL pattern
 * 
 * @param float $value
 * @param int $numAfterComma
 * @return string
 */
if (! function_exists('format_brl')) {
    function format_brl(float $value, int $numAfterComma = 2) : string
    {
        return number_format($value, $numAfterComma, ',', '.');
    }
}