<?php
function countingSort($arr) {
    $countArr = array_fill(0, 100, 0);
    
    foreach ($arr as $num) {
        $countArr[$num]++;
    }
    
    $result = [];
    for ($i = 0; $i < 100; $i++) {
        for ($j = 1; $j <= $countArr[$i]; $j++) {
            $result[] = $i;
        }
    }
    
    return $result;
}
