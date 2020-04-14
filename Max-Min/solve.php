<?php
function maxMin($k, $arr) {
    $min = 1000000001;
    $n = count($arr);
    
    // 排序
    sort($arr);
    
    // 遍历找最小值
    for ($i = $n - $k; $i >=0; $i--) {
        // 此范围内max - min
        $diff = $arr[$i + $k - 1] - $arr[$i];
        if ($diff < $min) {
            $min = $diff;
        }
    }
    
    return $min;
}
