<?php
function missingNumbers($arr, $brr) {
    $missMap = [];
    
    $numMap = [];
    foreach ($arr as $num) {
        // 计数处理
        if ( ! isset($numMap[$num])) {
            $numMap[$num] = 1;
        } else {
            $numMap[$num]++;
        }
    }
    
    foreach ($brr as $num) {
        if ( ! isset($numMap[$num])) { // 没有出现过, 算缺失
            $missMap[$num] = true;
        } else {
            $numMap[$num]--;
            if ($numMap[$num] < 0) { // 出现次数不够, 算缺失
                $missMap[$num] = true;
            }
        }
    }
    
    // 从小到大排序
    $result = array_keys($missMap);
    sort($result);
    
    return $result;
}
