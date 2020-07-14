<?php
function largestPermutation($k, $arr) {
    $n = count($arr);
    $indexArr = [];
    
    // 记录每个值的位置，用于交换时用
    foreach ($arr as $index => $value) {
        $indexArr[$value] = $index;
    }
    
    for ($i = 0; $i < $n; $i++) {
        if ($arr[$i] == $n - $i) { // 当前位置已经是最大值了
            continue;
        }
        
        // 将最大值交换过来
        $arr[$indexArr[$n - $i]] = $arr[$i];
        // 被交换的值的位置也变了
        $indexArr[$arr[$i]] = $indexArr[$n - $i];
        
        $arr[$i] = $n - $i;
        $k--;
        
        if ($k == 0) { // 次数用尽
            break;
        }
    }
    
    return $arr;
}
