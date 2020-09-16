<?php
function longestCommonSubsequence($a, $b) {
    $m = count($a);
    $n = count($b);
    
    $dp = [];
    
    process($dp, $a, $b, 0, 0, $m, $n);
    // 拼接成数组返回
    $result = [];
    
    $item = $dp[0][0];
    while ($item[0] > 0) {
        $result[] = $a[$item[1]];
        $item = $dp[$item[3]][$item[4]];
    }
    
    return $result;
}

function process(&$dp, &$a, &$b, $i, $j, $m, $n)
{
    if (isset($dp[$i][$j])) {
        return $dp[$i][$j];
    }
    
    if ($i >= $m || $j >= $n) {
        return $dp[$i][$j] = [0, $i, $j, $i, $j];
    }
    
    // 两者相等，加入
    if ($a[$i] == $b[$j]) {
        $item = process($dp, $a, $b, $i + 1, $j + 1, $m, $n);
        $item[0] += 1;
        $item[3] = $item[1];
        $item[4] = $item[2];
        $item[1] = $i;
        $item[2] = $j;
        return $dp[$i][$j] = $item;
    }
    
    // 不相等， 去除a中元素 vs 去除b中元素
    $r1 = process($dp, $a, $b, $i + 1, $j, $m, $n);
    $r2 = process($dp, $a, $b, $i, $j + 1, $m, $n);
    
    return $dp[$i][$j] = ($r1[0] >= $r2[0] ? $r1 : $r2);
}