<?php
function candies($n, $arr) {
    $result = 0;
    
    // 从左向右遍历得到的满足条件的最小值
    $leftValue = [];
    $leftValue[0] = 1;
    for ($i = 1; $i < $n; $i++) {
        $leftValue[$i] = $arr[$i] > $arr[$i - 1] ? $leftValue[$i - 1] + 1 : 1;
    }
    
    // 从右向左遍历得到的满足条件的最小值
    $rightValue = [];
    $rightValue[$n - 1] = 1;
    for ($i = $n - 2; $i >= 0; $i--) {
        $rightValue[$i] = $arr[$i] > $arr[$i + 1] ? $rightValue[$i + 1] + 1 : 1;
    }
    
    // 取左右值满足条件的值中的大者
    for ($i = 0; $i < $n; $i++) {
        $result += ($leftValue[$i] > $rightValue[$i] ? $leftValue[$i] : $rightValue[$i]);
    }
    
    return $result;
}
