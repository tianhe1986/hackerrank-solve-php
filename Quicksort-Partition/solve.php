<?php
function quickSort($arr) {
    $result = [];
    
    //按照题意来，左中右三个数组，最后组合在一起
    $left = [];
    $equal = [];
    $right = [];
    
    $cmpValue = $arr[0];
    
    foreach ($arr as $value) {
        if ($value == $cmpValue) {
            $equal[] = $value;
        } else if ($value < $cmpValue) {
            $left[] = $value;
        } else {
            $right[] = $value;
        }
    }
    
    // 合成一个数组，为啥我不用array_merge呢，因为不想用
    foreach ([$left, $equal, $right] as $item) {
        foreach ($item as $value) {
            $result[] = $value;
        }
    }
    
    return $result;
}