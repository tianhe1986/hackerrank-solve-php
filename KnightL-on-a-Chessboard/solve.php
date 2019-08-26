<?php
function knightlOnAChessboard($n) {
    $result = [];
    for ($i = 1; $i < $n; $i++) {
        for ($j = 1; $j <= $i; $j++) {
            $result[$i][$j] = $result[$j][$i] = calcu($j, $i, $n);
        }
    }
    
    array_walk($result, function($arr){ksort($arr, SORT_NUMERIC);});
    ksort($result, SORT_NUMERIC);
    
    return $result;
}

function calcu($minStep, $maxStep, $n)
{
    //是否已遍历
    $flagMap = [];
    $flagMap[0][0] = true;
    
    //当前步数
    $nowStep = 0;
    
    //当前需要遍历的点
    $nowArr = [[0, 0]];
    $nextArr = [];
    
    //八种移动方式
    $calcuArr = [
        [-$minStep, -$maxStep],
        [-$minStep, $maxStep],
        [$minStep, -$maxStep],
        [$minStep, $maxStep],
        [-$maxStep, -$minStep],
        [-$maxStep, $minStep],
        [$maxStep, -$minStep],
        [$maxStep, $minStep],
    ];
    
    while ($nowArr) {
        foreach ($nowArr as &$point) {
            foreach ($calcuArr as &$calcuItem) {
                $t1 = $point[0] + $calcuItem[0];
                $t2 = $point[1] + $calcuItem[1];
                
                if ($t1 < 0 || $t1 >= $n || $t2 < 0 || $t2 >= $n) { //在棋盘之外了， 不可达
                    continue;
                }
                
                if (isset($flagMap[$t1][$t2])) { //已遍历
                    continue;
                }
                
                if ($t1 == $n - 1 && $t2 == $n - 1) { //到终点了
                    return $nowStep+1;
                }
                
                $flagMap[$t1][$t2] = true; //继续遍历
                $nextArr[] = [$t1, $t2];
            }
        }
        $nowStep++;
        $nowArr = $nextArr;
        $nextArr = [];
    }
    
    //全部遍历完成依旧没有到终点
    return -1;
}
