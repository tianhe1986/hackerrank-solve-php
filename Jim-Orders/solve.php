<?php
function jimOrders($orders) {
    $n = count($orders);
    
    // 二元组 [汉堡完成时间， 编号]
    $arr = [];
    for ($i = 0; $i < $n; $i++) {
        $arr[] = [$orders[$i][0] + $orders[$i][1], $i + 1];
    }
    
    // 排序
    usort($arr, function($a, $b){
        $diff = $a[0] - $b[0];
        return $diff != 0 ? $diff : ($a[1] - $b[1]);
    });
    
    $result = [];
    for ($i = 0; $i < $n; $i++) {
        $result[] = $arr[$i][1];
    }
    
    return $result;
}
