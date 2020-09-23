<?php
function longestIncreasingSubsequence($arr) {
    // 记录每个长度的最小结尾元素
    $minArr = [];
    $len = 0;
    
    foreach ($arr as $num) {
        if ($len == 0 || $minArr[$len - 1] < $num) { // 是目前为止出现的最大值，增加到末尾
            $minArr[$len++] = $num;
            continue;
        }
        
        // 更新最小结尾元素
        $minArr[binarySearchGe($minArr, $num, $len)] = $num;
    }
    
    return $len;
}

// 找大于等于某值的第一项
function binarySearchGe(&$minArr, $value, $len)
{
    $low = 0;
    $high = $len - 1;
    while ($low <= $high) {
        $middle = ($low + $high) >> 1;
        if ($minArr[$middle] == $value) {
            return $middle;
        } else if ($minArr[$middle] > $value) {
            $high = $middle - 1;
        } else {
            $low = $middle + 1;
        }
    }
    
    return $low;
}
