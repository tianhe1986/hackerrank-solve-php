<?php
function findMedian($arr) {
    $n = count($arr);
    
    //排序
    sort($arr);
    
    //返回最中间的元素
    return $arr[($n - 1)/2];
}
