<?php
function flatlandSpaceStations($n, $c) {
    $result = 0;
    
    // 上一个空间站的index
    $last = -1;
    
    // 储存每个城市是否有空间站
    $flagArr = [];
    foreach ($c as $index) {
        $flagArr[$index] = true;
    }
    
    for ($i = 0; $i < $n; $i++) {
        if (isset($flagArr[$i])) { //碰到了一个空间站
            // 如果是第一个， 则此段最远距离为起点到此城市的距离
            // 否则， 是两个空间站距离的一半
            $temp = $last >= 0 ? intval(($i - $last)/2) : $i;
            if ($temp > $result) {
                $result = $temp;
            }
            $last = $i;
        }
    }
    
    // 最后一个空间站到终点的距离
    $temp = $i - 1 - $last;
    if ($temp > $result) {
        $result = $temp;
    }
    
    return $result;
}
