<?php 

/**
 * Returns the value of the key passed as a parameter
 * 
 * @param Array|Object $source
 * @param String $index
 * @return Mixed
 */
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