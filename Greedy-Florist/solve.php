<?php
function getMinimumCost($k, $c) {
    $result = 0;
    
    // 花价格从高到低排序
    sort($c, SORT_NUMERIC);
    $c = array_reverse($c);
    $n = count($c);
    
    // 当前购买的倍增倍数
    $round = 1;
    // 用于计数，每k次则倍增倍数 + 1
    $nowCount = 0;
    
    // 贪心法，当前买花次数最少的人去买下一个价格最高的花
    for ($i = 0; $i < $n; $i++) {
        $result += $c[$i] * $round;
        $nowCount++;
        if ($nowCount >= $k) { // 这一轮每个人都买过了，进入下一轮
            $round++;
            $nowCount = 0;
        }
    }
    
    return $result;
}