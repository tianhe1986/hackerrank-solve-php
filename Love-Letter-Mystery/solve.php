<?php
function theLoveLetterMystery($s) {
    $n = strlen($s);
    $result = 0;

    for ($i = 0, $j = $n - 1; $i < $j; $i++,$j--) {
        $result += abs(ord($s[$i]) - ord($s[$j])); // 对称位置的差值累加
    }
    
    return $result;
}
