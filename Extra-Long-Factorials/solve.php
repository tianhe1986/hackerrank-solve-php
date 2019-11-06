<?php
function extraLongFactorials($n) {
    // 最多100的阶乘，用201位10进制就可以存储
    $maxLen = 201;
    $arr = array_fill(0, $maxLen, 0);
    $arr[0] = 1;
    
    for ($i = 1; $i <= $n; $i++) {
        $addNum = 0; // 存储进位
        for ($j = 0; $j < $maxLen; $j++) {
            $temp = $arr[$j] * $i + $addNum; //与当前位的值相乘，再加上进位
            $arr[$j] = $temp % 10;
            $addNum = ($temp - $arr[$j]) / 10; //继续进位
        }
    }
    
    echo ltrim(implode("", array_reverse($arr)), '0')."\n";
}