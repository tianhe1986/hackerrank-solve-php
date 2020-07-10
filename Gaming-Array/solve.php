<?php
function gamingArray($arr) {
    // 总操作步数
    $canHandleNum = 0;
    
    // 当前最大元素
    $max = -1;
    
    foreach ($arr as $num) {
        if ($num > $max) { // 找右方第一个更大的
            $canHandleNum++;
            $max = $num;
        }
    }
    
    return $canHandleNum % 2 == 1 ? 'BOB' : 'ANDY';
}