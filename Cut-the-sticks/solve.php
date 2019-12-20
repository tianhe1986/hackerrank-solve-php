<?php
function cutTheSticks($arr) {
    $countArr = [];
    
    // 记录每个长度的数量
    foreach ($arr as $num) {
        if ( ! isset($countArr[$num])) {
            $countArr[$num] = 0;
        }
        
        $countArr[$num]++;
    }
    
    // 按长度从小到大排序
    ksort($countArr);
    
    // 当前剩余木棍数
    $nowNum = count($arr);
    $result = [];
    
    foreach ($countArr as $count) {
        // 当前木棍，每根都切
        $result[] = $nowNum;
        
        // 最短的木棍全部被丢弃
        $nowNum -= $count;
    }
    
    return $result;
}
