<?php
function equalizeArray($arr) {
    $n = count($arr);
    
    // 出现的最大次数
    $maxNum = 0;
    
    // 统计每个数字出现的次数
    $countArr = array_fill(0, 101, 0);
    
    foreach ($arr as $num) {
        $countArr[$num]++;
        if ($countArr[$num] > $maxNum) {
            $maxNum = $countArr[$num];
        }
    }
    
    // 留下出现次数最多的，把其他的都删掉
    return $n - $maxNum;
}
