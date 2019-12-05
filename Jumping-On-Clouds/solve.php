<?php
function jumpingOnClouds($c) {
    $result = 0;
    
    // 结束位置
    $end = count($c) - 1;
    
    // 当前位置
    $index = 0;
    while ($index < $end) { // 没到结束位置时，一直遍历
        // 尝试跳两格
        $next = $index + 2;
        if ($next > $end || $c[$next] == 1) { // 如果跳两格会超出范围，或是会落在闪电上，则只能跳一格
            $next--;
        }
        
        $result++;
        $index = $next;
    }
    
    return $result;
}
