<?php
function bonetrousle($n, $k, $b) {
    if ($k < $b) { // 种类就不够
        return [-1];
    }
    
    $min = (1 + $b) * $b / 2;
    $max = ($k + $k - $b + 1) * $b / 2;
    if ($n < $min || $n > $max) { // 比最小数量还小，或是比最大数量还大
        return [-1];
    }
    
    $diff = $n - $min;
    
    $result = [];
    // 初始化数组
    for ($i = 1; $i <= $b; $i++) {
        $result[] = $i;
    }
    
    // 每个位置能够往上增加的上限数
    $upper = $k - $b;
    
    if ($upper == 0) { // 上限为0，已经是最小了
        return $result;
    }
    
    // 多出来的余数
    $mod = $diff % $upper;
    
    // 末尾有多少个达到上限
    $upperNum = ($diff - $mod) / $upper;
    
    // 多出来的余数
    if ($mod > 0) {
        $result[$b - $upperNum - 1] += $mod;
    }
    
    // 末尾的达到上限的处理
    for ($i = $b - 1, $end = $i - $upperNum; $i > $end; $i--) {
        $result[$i] += $upper;
    }

    return $result;
}
