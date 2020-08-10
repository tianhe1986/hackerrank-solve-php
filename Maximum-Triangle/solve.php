<?php
function maximumPerimeterTriangle($sticks) {
    $n = count($sticks);
    $result = [-1];
    
    // 排序
    sort($sticks);
    
    // 最大的肯定是连续三个，不做多想
    for ($i = $n - 1; $i >= 2; $i--) {
        if ($sticks[$i - 2] + $sticks[$i - 1] > $sticks[$i]) {
            $result = [$sticks[$i - 2], $sticks[$i - 1], $sticks[$i]];
            break;
        }
    }
    
    return $result;
}