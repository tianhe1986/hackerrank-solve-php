<?php
function angryChildren($k, $packets) {
    $n = count($packets);
    // 排序，从大到小
    sort($packets);
    $packets = array_reverse($packets);
    
    // 向前 k - 1项求和
    $sumArr = [];
    $tempSum = 0;
    for ($i = 0; $i < $k - 1; $i++) {
        $tempSum += $packets[$i];
    }
    $sumArr[$k - 2] = $tempSum;
    
    for ($i = $k - 1; $i < $n; $i++) {
        $tempSum = $tempSum + $packets[$i] - $packets[$i - $k + 1];
        $sumArr[$i] = $tempSum;
    }
    

    $temp = 0;
    
    // 首值
    for ($i = 0; $i < $k; $i++) {
        $temp += ($k - 1 - 2 * $i) * $packets[$i];
    }
    $result = $temp;
    
    // 依次递推计算相应值
    for ($i = $k; $i < $n; $i++) {
        $temp += ($k - 1) * (- $packets[$i] - $packets[$i - $k]) + 2 * $sumArr[$i - 1];
        if ($temp < $result) {
            $result = $temp;
        }
    }
    
    return $result;
}