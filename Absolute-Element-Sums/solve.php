<?php
function playingWithNumbers($arr, $queries) {
    $n = count($arr);
    // 原始和
    $originalSum = 0;
    // 个数数组
    $numArr = [];
    foreach ($arr as $num) {
        if ( ! isset($numArr[$num])) {
            $numArr[$num] = 1;
        } else {
            $numArr[$num]++;
        }
        
        $originalSum += ($num >= 0 ? $num : -$num);
    }
    
    // 累加个数数组, 小于等于此值的数量
    $accumuArr = [];
    $accumuArr[-2001] = 0;
    for ($i = -2000; $i <= 2000; $i++) {
        $accumuArr[$i] = $accumuArr[$i-1] + (isset($numArr[$i]) ? $numArr[$i] : 0);
    }
    
    // 0 - 此区间内求和数组
    $sumArr = [];
    $sumArr[0] = 0;
    for ($i = 1; $i <= 2000; $i++) {
        $sumArr[$i] = $sumArr[$i - 1] + (isset($numArr[$i]) ? $numArr[$i] * $i : 0);
        $sumArr[-$i] = $sumArr[1 - $i] + (isset($numArr[-$i]) ? - $numArr[-$i] * $i : 0);
    }
    
    $result = [];
    $nowValue = 0;
    foreach ($queries as $query) {
        $nowValue += $query;
        
        $result[] = $originalSum + getChangeNum($accumuArr, $sumArr, $nowValue, $n);
    }
    
    return $result;
}

function getChangeNum(&$accumuArr, &$sumArr, $value, $n)
{
    if ($value == 0) {
        return 0;
    }
    
    // 如果value是正的
    // 大于等于-value的， 增加value, 小于-value的，减去value
    // 加上两倍[-value, 0]区间之和
    $result = 0;
    if ($value > 0) {
        // n - 小于等于-value - 1的数量
        $lessNum = isset($accumuArr[-$value - 1]) ? $accumuArr[-$value - 1] : 0;
        $result += ($n - 2*$lessNum) * $value;
        $result += 2 * (isset($sumArr[-$value]) ? $sumArr[-$value] : $sumArr[-2000]);
    } else {
        // 如果value是负的
        // 小于等于-value的，增加-value，大于-value的，增加value
        // 减去两倍[0, -value]区间之和
        $lessNum = isset($accumuArr[-$value]) ? $accumuArr[-$value] : $n;
        $result += ($n - 2 * $lessNum) * $value;
        $result -= 2 * (isset($sumArr[-$value]) ? $sumArr[-$value] : $sumArr[2000]);
    }
    
    return $result;
}