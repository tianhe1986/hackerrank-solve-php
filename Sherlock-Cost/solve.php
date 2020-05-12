<?php
function cost($B) {
    // A[i]对应位置取1时能够达到的最大值
    $minArr = [];
    
    // A[i]对应位置取B[i]时能够达到的最大值
    $maxArr = [];
    
    $n = count($B);
    
    $minArr[1] = $B[0] - 1;
    $maxArr[1] = $B[1] - 1;
    
    for ($i = 2; $i < $n; $i++) {
        // 前一个位置取最大值
        $minArr[$i] = $maxArr[$i - 1] + ($B[$i - 1] - 1);
                
        // 与前一个位置也取1相比
        if ($minArr[$i] < $minArr[$i - 1]) {
            $minArr[$i] = $minArr[$i - 1];
        }
        
        // 前一个位置取1
        $maxArr[$i] = $minArr[$i - 1] + ($B[$i] - 1);
        
        // 前一个位置取最大值
        $temp = $maxArr[$i - 1] + abs($B[$i] - $B[$i - 1]);
        if ($temp > $maxArr[$i]) {
            $maxArr[$i] = $temp;
        }
    }
    
    return $minArr[$n - 1] > $maxArr[$n - 1] ? $minArr[$n - 1] : $maxArr[$n - 1];
}
