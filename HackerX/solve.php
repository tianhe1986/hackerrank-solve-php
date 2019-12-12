<?php
function missileDefend($missiles) {
    $newArr = [];
    
    // 新项
    // 假设原项是 [t, f]， 则新项是 [t + f, t - f]
    foreach ($missiles as $item) {
        $newArr[] = [$item[0] + $item[1], $item[0] - $item[1]];
    }
    
    // 将新项排序
    usort($newArr, function($a, $b) {
       $diff = $a[0] - $b[0];
       return $diff != 0 ? $diff : ($a[1] - $b[1]);
    });
    
    // 找第二项的最长递减子序列
    
    // 用来存储各长度子序列的最大尾项，从大到小排序
    $maxTailArr = [];
    
    foreach ($newArr as $item) {
        // 找到第一个小于等于 $item[1]的 index，替换掉数组中当前值
        $index = findLeIndex($maxTailArr, $item[1]);
        $maxTailArr[$index] = $item[1];
    }
    
    return count($maxTailArr);
}

function findLeIndex(&$arr, $value)
{
    if (empty($arr)) {
        return 0;
    }
    
    // 比第一项大
    if ($value >= $arr[0]) {
        return 0;
    }
    
    $n = count($arr);
    if ($value < $arr[$n - 1]) { //比最后一项小
        return $n;
    }
    
    //二分查找
    $low = 0;
    $high = $n - 1;
    while ($low <= $high) {
        $middle = intval(($low + $high)/2);
        
        if ($arr[$middle] == $value) { // 一样大，直接返回
            return $middle;
        } else if ($arr[$middle] > $value) { // 更大，继续往后找
            $low = $middle + 1;
        } else {
            $high = $middle - 1;
        }
    }
    
    return $low;
}
