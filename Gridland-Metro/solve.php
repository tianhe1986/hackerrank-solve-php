<?php
function gridlandMetro($n, $m, $k, $track) {
    // 一开始，都可以
    $result = $n * $m;
    
    // 以row为主键，记录所有的track
    $rowTrackList = [];
    foreach ($track as $item) {
        $rowTrackList[$item[0]][] = [$item[1], $item[2]];
    }
        
    // 对于每个row，将track排序，再进行处理 
    foreach ($rowTrackList as $list) {
        usort($list, function($a, $b) {
            $diff = $a[0] - $b[0];
            return $diff != 0 ? $diff : ($a[1] - $b[1]);
        });
        
        $start = 1;
        $end = 0;
        foreach ($list as $item) {
            if ($item[0] > $end) { // 与前一段没有交集，新开始一段
                $result = $result - ($end - $start + 1);
                $start = $item[0];
                $end = $item[1];
            } else { // 取更大的结束值
               if ($item[1] > $end) {
                   $end = $item[1];
               } 
            }
        }
        
        // 减去最后一段的数量
        $result = $result - ($end - $start + 1);
    }
    
    return $result;
}