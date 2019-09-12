<?php
function jumpingOnClouds($c, $k) {
    // 云朵总数
    $n = count($c);
    
    // 一开始的能量点
    $result = 100;
    
    // 用于记录当前位置，一开始为0
    $place = 0;
    while (true) {
        // 移动一步，并扣除能量
        $place = ($place + $k) % $n;
        $result--;

        // 需要额外扣除能量
        if ($c[$place] == 1) {
            $result -= 2;
        }
        
        // 回到起点了
        if ($place == 0) {
            break;
        }
    }
    
    return $result;
}
