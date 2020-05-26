<?php
function twoArrays($k, $a, $b) {
    $n = count($a);
    
    // 一个从小到大，一个从大到小
    sort($a);
    sort($b);
    $b = array_reverse($b);
    
    for ($i = 0; $i < $n; $i++) {
        if ($a[$i] + $b[$i] < $k) { // 存在不匹配的，直接return
            return 'NO';
        }
    }
    
    return 'YES';
}
