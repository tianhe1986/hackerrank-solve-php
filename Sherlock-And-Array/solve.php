<?php
function balancedSums($arr) {
    // 总和
    $sum = 0;
    foreach ($arr as $num) {
        $sum += $num;
    }
    
    // 检查等式 总和 - 当前项 = 2 * 左和
    $doubleLeftSum = 0;
    foreach ($arr as $num) {
        if ($sum - $num == $doubleLeftSum) {
            return 'YES';
        }
        
        $doubleLeftSum += $num * 2;
    }
    
    return 'NO';
}
