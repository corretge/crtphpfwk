<?php


function numtostr($num){
    $big = array(    '', 
                    'thousand', 
                    'million', 
                    'billion', 
                    'trillion', 
                    'quadrillion', 
                    'quintillion', 
                    'sextillion', 
                    'septillion');
    $small = array(    '', 
                    'one', 
                    'two', 
                    'three', 
                    'four', 
                    'five', 
                    'six', 
                    'seven', 
                    'eight', 
                    'nine', 
                    'ten', 
                    'eleven', 
                    'twelve', 
                    'thirteen', 
                    'fourteen', 
                    'fifteen', 
                    'sixteen', 
                    'seventeen', 
                    'eighteen', 
                    'nineteen');
    $other = array(    '',
                    '',
                    'twenty', 
                    'thirty', 
                    'fourty', 
                    'fifty', 
                    'sixty', 
                    'seventy', 
                    'eighty', 
                    'ninety');
    $hun = 'hundred';
    $end = array();
    $num = strrev($num);
    $final = array();
    for($i=0; $i<strlen($num); $i+=3){
        $end[$i] = strrev(substr($num, $i, 3));
    }
    $end = array_reverse($end);
    for($i=0; $i<sizeof($end); $i++){
        $len = strlen($end[$i]);
        $temp = $end[$i];
        if($len == 3){
            $final[] = $temp{0} != '0' ? $small[$end[$i]{0}] . ' ' . $hun : $small[$end[$i]{0}];
            $end[$i] = substr($end[$i], 1, 2);
        }
        if($len > 1){
            $final[] = array_key_exists($end[$i], $small) ? $small[$end[$i]] : $other[$end[$i]{0}] . ' ' . $small[$end[$i]{1}];
        }else{
            $final[] = $small[$end[$i]{0}];
        }
        $final[] = $temp != '000' ? $big[sizeof($end) - $i - 1] : '';
    }
    return str_replace(array('  ', '  ', '  ', '  ', '  ', '  ', '  '), ' ', implode(' ',$final));
}

