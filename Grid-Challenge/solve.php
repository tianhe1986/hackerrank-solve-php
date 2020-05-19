<?php
function gridChallenge($grid) {
    $m = count($grid);
    $n = strlen($grid[0]);
    
    // 字符转数字，便于比较
    $preArr = [];
    for ($j = 0; $j < $n; $j++) {
        $preArr[] = ord($grid[0][$j]);
    }
    
    // 首行直接排序
    sort($preArr);
    
    // 第二行开始，依次排好序，再与上一行相比
    for ($i = 1; $i < $m; $i++) {
        $arr = [];
        for ($j = 0; $j < $n; $j++) {
            $arr[] = ord($grid[$i][$j]);
        }
        sort($arr);
        
        for ($j = 0; $j < $n; $j++) {
            if ($preArr[$j] > $arr[$j]) { // 有不满足的，就直接返回了
                return 'NO';
            }
        }
        
        $preArr = $arr;
    }
    
    return 'YES';
}
