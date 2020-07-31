<?php
function permutationGame($arr) {
    $n = count($arr);
    
    $cache = [];
    $result = dynamic($cache, $arr, 0, $n);
    
    return $result != 0 ? 'Alice' : 'Bob';
}

function dynamic(&$cache, &$arr, $mask, $nowCount)
{
    if (isset($cache[$mask])) {
        return $cache[$mask];
    }
    
    if ($nowCount == 1) { // 只有一个元素，当然是逆序
        return $cache[$mask] = 0;
    }
    
    $pre = null;
    
    // 先检查一遍是不是单调递增
    $isAsc = true;
    foreach ($arr as $i) {
        if ($mask & (1 << $i)) { // 被移除了
            continue;
        }
        
        if ($pre !== null) {
            if ($i < $pre) {
                $isAsc = false;
                break;
            }
        }
        
        $pre = $i;
    }
    
    if ($isAsc) { // 单调递增，为0
        return $cache[$mask] = 0;
    }
    
    $flagArr = [];
    
    // 计算Mex
    foreach ($arr as $i) {
        if ($mask & (1 << $i)) { // 被移除了
            continue;
        }
        
        $temp = dynamic($cache, $arr, $mask | (1 << $i), $nowCount - 1);
        $flagArr[$temp] = true;
    }
    
    for ($i = 0; $i <= $nowCount; $i++) {
        if ( ! isset($flagArr[$i])) {
            return $cache[$mask] = $i;
        }
    }
}
