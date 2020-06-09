<?php
function stringReduction($s) {
    // 计数
    $countArr = [
        'a' => 0,
        'b' => 0,
        'c' => 0
    ];
    
    $n = strlen($s);
    for ($i = 0; $i < $n; $i++) {
        $countArr[$s[$i]]++;
    }
    
    // 出现过的字母种类数
    $containNum = 0;
    
    // 出现字母个数为奇数的数量
    $oddNum = 0;
    
    foreach ($countArr as $count) {
        if ($count > 0) {
            $containNum++;
        }
        
        if ($count % 2 == 1) {
            $oddNum++;
        }
    }
    
    // 默认，只包含一种字符时，一个也消不了
    $result = $n;
    if ($containNum > 1) {
        // 3奇或3偶，则最短长度为2，否则为1
        $result = ($oddNum == 0 || $oddNum == 3) ? 2 : 1;
    }
    
    return $result;
}
