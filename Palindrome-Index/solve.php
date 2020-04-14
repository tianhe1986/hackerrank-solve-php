<?php
function palindromeIndex($s) {
    $len = strlen($s);
    
    $left = 0;
    $right = $len - 1;
    
    $leftItem = null;
    $rightItem = null;
    
    // 之前是否已经存在不匹配的情况
    $hasNotMatch = false;
    
    // 从两端向中间匹配
    while ($left <= $right) {
        if ($s[$left] == $s[$right]) { // 字符匹配，继续
            $left++;
            $right--;
        } else {
            if ($hasNotMatch) { // 如果之前已经有不匹配的情况，说明去除右方的index是不对的，应该去除左方
                return $leftItem;
            } else { // 之前没有不匹配的情况，记录下当前左右index，然后去除右方index，继续匹配
                $hasNotMatch = true;
                $leftItem = $left;
                $rightItem = $right;
                $right--;
            }
        }
    }
    
    // 有不匹配的，说明去除右方正确，否则，本身就是回文，不用去除
    return $hasNotMatch ? $rightItem : -1;
}
