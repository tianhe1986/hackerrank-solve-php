<?php
function minimumBribes($q) {
    $n = count($q);
    
    for ($i = 0; $i < $n; $i++) {
        if ($q[$i] > $i + 3) {
            return 'Too chaotic';
        }
    }
    
    return getReverseNum($q);
}

// 获取逆序数
function getReverseNum($q) {
    $n = count($q);
    $temp = [];
    
    return mergeSort($q, $temp, 0, $n - 1);
}

// 归并排序的同时计算逆序数
function mergeSort(&$q, &$temp, $left, $right)
{
    $reverseNum = 0;
    
    if ($left < $right) { // 需要归并
        $mid = ($left + $right) >> 1;
        
        // 递归对左右两边归并排序
        $reverseNum += mergeSort($q, $temp, $left, $mid);
        $reverseNum += mergeSort($q, $temp, $mid + 1, $right);
        
        // 将左右合并在一起
        $reverseNum += merge($q, $temp, $left, $mid, $right);
    }
    
    return $reverseNum;
}

function merge(&$q, &$temp, $left, $mid, $right)
{
    $reverseNum = 0;
    
    // 左边遍历索引
    $i = $left;
    
    // 右边遍历索引
    $j = $mid + 1;
    
    // 最终合并好的索引
    $k = $left;
    
    while ($i <= $mid && $j <= $right) {
        if ($q[$i] <= $q[$j]) { // 左边小于等于右边，则不构成逆序
            $temp[$k++] = $q[$i++];
        } else { // 增加对应逆序数
            $reverseNum += $mid + 1 - $i;
            $temp[$k++] = $q[$j++];
        }
    }
    
    // 将剩下的依次放入
    while ($i <= $mid) {
        $temp[$k++] = $q[$i++];
    }
    
    while ($j <= $right) {
        $temp[$k++] = $q[$j++];
    }
    
    // 将排好序的部分放回原数组
    for ($i = $left; $i <= $right; $i++) {
        $q[$i] = $temp[$i];
    }
    
    return $reverseNum;
}
