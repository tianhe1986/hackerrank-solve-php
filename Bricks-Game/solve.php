<?php
function bricksGame($arr) {
    $n = count($arr);
    // 求到末尾元素的累加和
    $sumArr = [];
    $sumArr[$n - 1] = $arr[$n - 1];
    for ($i = $n - 2; $i >= 0; $i--) {
        $sumArr[$i] = $sumArr[$i + 1] + $arr[$i];
    }

    $dp = [];
    
    return process($dp, $sumArr, 0, $n);
}

function process(&$dp, &$sumArr, $i, $n)
{
    if (isset($dp[$i])) {
        return $dp[$i];
    }
    
    if ($i >= $n - 3) { // 到末尾了，全部选上
        return $dp[$i] = $sumArr[$i];
    }
    
    // 让对方拿最少的
    $temp = min(process($dp, $sumArr, $i + 1, $n), process($dp, $sumArr, $i + 2, $n), process($dp, $sumArr, $i + 3, $n));
    
    return $dp[$i] = $sumArr[$i] - $temp;
}
