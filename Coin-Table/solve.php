<?php
function coinOnTheTable($m, $k, $board) {
    $n = count($board);

    $endPoint = null;
    
    for ($i = 0; $i < $n; $i++) {
        for ($j = 0; $j < $m; $j++) {
            if ($board[$i][$j] == '*') {
                $endPoint = [$i, $j];
                break;
            }
        }
        
        if ($endPoint) {
            break;
        }
    }
    
    // 最小距离map
    $minDisMap = [];
    for ($i = 0; $i < $n; $i++) {
        for ($j = 0; $j < $m; $j++) {
            $minDisMap[$i][$j] = abs($i - $endPoint[0]) + abs($j - $endPoint[1]);
        }
    }
    
    $cache = [];
    
    // 终点到自己始终OK
    for ($i = 0; $i <= $k; $i++) {
        $cache[$endPoint[0]][$endPoint[1]][$i] = 0;
    }
    
    $result = dynamic($cache, $board, $minDisMap, 0, 0, $n, $m, $k);

    if ($result >= 10000) {
        $result = -1;
    }
    
    return $result;
}

function dynamic(&$cache, &$board, &$minDisMap, $i, $j, $n, $m, $remainStep)
{
    if (isset($cache[$i][$j][$remainStep])) {
        return $cache[$i][$j][$remainStep];
    }
    
    if ($remainStep < $minDisMap[$i][$j]) { // 步数余额不足，返回无穷大
        return $cache[$i][$j][$remainStep] = 10000;
    }
    
    $result = 10000;
    $nextStep = $remainStep - 1;
    if ($i > 0) { // 往上走
        $temp = dynamic($cache, $board, $minDisMap, $i - 1, $j, $n, $m, $nextStep);
        if ($board[$i][$j] != 'U') {
            $temp++;
        }
        
        if ($temp < $result) {
            $result = $temp;
        }
    }
    
    if ($j > 0) { // 往左走
        $temp = dynamic($cache, $board, $minDisMap, $i, $j - 1, $n, $m, $nextStep);
        if ($board[$i][$j] != 'L') {
            $temp++;
        }
        
        if ($temp < $result) {
            $result = $temp;
        }
    }
    
    if ($i < $n - 1) { // 往下走
        $temp = dynamic($cache, $board, $minDisMap, $i + 1, $j, $n, $m, $nextStep);
        if ($board[$i][$j] != 'D') {
            $temp++;
        }
        
        if ($temp < $result) {
            $result = $temp;
        }
    }
    
    if ($j < $m - 1) { // 往右走
        $temp = dynamic($cache, $board, $minDisMap, $i, $j + 1, $n, $m, $nextStep);
        if ($board[$i][$j] != 'R') {
            $temp++;
        }
        
        if ($temp < $result) {
            $result = $temp;
        }
    }
    
    return $cache[$i][$j][$remainStep] = $result;
}
