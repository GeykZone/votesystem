<?php

function slugify($string) {
    
    $prepositions = array('in', 'at', 'on', 'by', 'into', 'off', 'onto', 'from', 'to', 'with', 'a', 'an', 'the', 'using', 'for');
    $pattern = '/\b(?:' . implode('|', $prepositions) . ')\b/i';
    $string = preg_replace($pattern, '', $string);

    
    $string = preg_replace('/[^a-z0-9]+/i', '-', $string);

    
    $string = trim($string, '-');


    $string = strtolower($string);

    return $string;
}

?>