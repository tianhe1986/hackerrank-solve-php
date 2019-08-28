<?php
function viralAdvertising($n) {
    //初始第1天，固定为2
    $result = $now = 2;
    
    for ($i = 2; $i <= $n; $i++) {
        // 根据递推公式 d(i+1) = floor(d(i)*3/2); 迭代
        $now = intval(floor($now * 1.5));
        // 人数累加
        $result += $now;
    }
    
    return $result;
}
