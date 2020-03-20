<?php
function pylons($k, $arr) {
    $result = 0;
    
    $n = count($arr);
    
    // 左方第一个可建造的城市
    $leftNearArr = [];
    
    // 初始化当前左方第一个城市为负，即没有。
    $leftValue = -$n;
    
    for ($i = 0; $i < $n; $i++) {
        $leftNearArr[$i] = $leftValue;
        if ($arr[$i] == 1) {
            $leftValue = $i;
        }
    }
    
    // 补上最后一个
    $leftNearArr[$n] = $leftValue;
    
    // 当前最左的没有被覆盖的索引，初始为0
    $nowLeftIndex = 0;
    
    while ($nowLeftIndex < $n) {
        $processIndex = $nowLeftIndex + $k;
        if ($processIndex > $n) {
            $processIndex = $n;
        }
        
        $findIndex = $leftNearArr[$processIndex];
        if ($findIndex <= $nowLeftIndex - $k) { // 无法覆盖nowLeftIndex， 失败
            return -1;
        }
        
        $result++;
        // 更新为findIndex右侧覆盖范围外最左的索引
        $nowLeftIndex = $findIndex + $k;
    }
    
    return $result;
}