<?php
function countingSort($arr) {
    $result = array_fill(0, 100, 0);
    
    foreach ($arr as $num) {
        $result[$num]++;
    }
    
    return $result;
}
