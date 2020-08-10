<?php
function intervalSelection($intervals) {
    $result = 0;
    
    // 目前结尾坐标
    $ends = [0, 0];
    
    usort($intervals, function($a, $b){
        // 先按起点排，再按终点排
        $temp = $a[0] - $b[0];
        return $temp != 0 ? $temp : $a[1] - $b[1];
    });
    
    // 依次遍历，贪心处理，尽管给右方留更多空间。
    foreach ($intervals as $item) {
        // 能直接塞入
        if ($item[0] > $ends[0]) {
            $result++;
            $ends[0] = $item[1];
            continue;
        }
        
        if ($item[0] > $ends[1]) {
            $result++;
            $ends[1] = $item[1];
            continue;
        }
        
        // 让end大的变小
        $maxIndex = $ends[0] > $ends[1] ? 0 : 1;
        
        if ($item[1] < $ends[$maxIndex]) {
            $ends[$maxIndex] = $item[1];
        }
    }
    
    return $result;
}
