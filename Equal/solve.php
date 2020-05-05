<?php
function equal($arr) {
    // 需要额外增加的次数
    $plusArr = [0, 1, 1, 2, 2];
    
    // 最小值-1之后，需要额外增加的次数
    $plusAddOneArr = [1, 1, 2, 2, 1];
    
    // 最小值-2之后，需要额外增加的次数
    $plusAddTwoArr = [1, 2, 2, 1, 2];
    
    $n = count($arr);
    
    // 数组中的最小项
    $compareItem = $arr[0];
    
    for ($i = 1; $i < $n; $i++) {
        if ($arr[$i] < $compareItem) {
            $compareItem = $arr[$i];
        }
    }
    
    $result = 0;
    
    // 不修正，需要额外增加的次数之和
    $normalValue = 0;
    
    // 注意， -1和-2修正是要多加1次操作的
    // 但是，在遍历时并没有跳过最小值对应的项，在此时会将这1次操作加上，因此oneValue和twoValue初始也为0即可
    
    // -1修正，需要额外增加的次数之和
    $oneValue = 0;
    
    // -2修正，需要额外增加的次数之和
    $twoValue = 0;
    for ($i = 0; $i < $n; $i++) {
        $diff = $arr[$i] - $compareItem;
        
        // 每次给5个， 多出来的再额外增加次数
        $mod = $diff % 5;  
        $result += ($diff - $mod) / 5;
        
        
        $normalValue += $plusArr[$mod];
        $oneValue += $plusAddOneArr[$mod];
        $twoValue += $plusAddTwoArr[$mod];
    }
    
    // 选不补，补1，补2中的最小值
    $min = $normalValue;
    if ($oneValue < $min) {
        $min = $oneValue;
    }
    
    if ($twoValue < $min) {
        $min = $twoValue;
    }
    
    return $result + $min;
}